<?PHP

namespace src\Data;

use src\Business\DonatorService;
use src\Data\config\DBConfig;
use src\Entities\User;

class RedLightDistrictDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    
    public function __construct()
    {
        global $connection;
        $this->con = $connection;                        
        $this->dbh = $connection->con;
    }
    
    public function __destruct()
    {
        $this->dbh = null;
        $this->con = null;
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(*) AS `total` FROM `rld` WHERE `id` > 0");
            return $row['total'];
        }
    }
    
    public function getRedLightDistrictPageInfo()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT u.`id`, u.`pimpLv`, u.`pimpXp`, u.`pimpProfit`, u.`pimpAttempts`, u.`pimpAmount`, u.`whoresStreet`,
                    (SELECT SUM(`whores`) FROM `rld_whore` WHERE `userID`=u.`id`) AS `rld_whores`
                FROM `user` AS u
                WHERE u.`id` = :uid
            ");
            $statement->execute(array(':uid' => $_SESSION['UID']));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $userObj = new User();
                $userObj->setPimpLv($row['pimpLv']);
                $userObj->setPimpXp(array('experience' => $row['pimpXp'], 'class' => "bg-purple"));
                $userObj->setPimpXpRaw($row['pimpXp']);
                $userObj->setPimpProfit($row['pimpProfit']);
                $userObj->setPimpAttempts($row['pimpAttempts']);
                $userObj->setPimpAmount($row['pimpAmount']);
                $userObj->setWhoresStreet($row['whoresStreet']);
                if($row['rld_whores'] > 0)
                    $userObj->setWhoresRLD($row['rld_whores']);
                else
                    $userObj->setWhoresRLD(0);
                $userObj->setTotalWhores($row['whoresStreet']+$row['rld_whores']);

                $statement = $this->dbh->prepare("
                    SELECT rld.`whores`, rld.`stateID`, s.`name` AS `stateName`
                    FROM `rld_whore` AS rld LEFT JOIN `state` AS s ON (rld.`stateID`=s.`id`)
                    WHERE rld.`userID`= :uid ORDER BY rld.`stateID` ASC
                    LIMIT 6
                ");
                $statement->execute(array(':uid' => $_SESSION['UID']));

                $list = array();
                foreach($statement AS $r)
                {
                    $lijst = array();
                    $lijst['stateID'] = $r['stateID'];
                    $lijst['stateName'] = $r['stateName'];
                    $lijst['whores'] = $r['whores'];
                    
                    array_push($list, $lijst);
                }
                $userObj->setWhoresList($list);

                return $userObj;
            }
        }
    }
    
    public function pimpWhores($amount, $newLv, $newXp, $who = false)
    {
        if(isset($_SESSION['UID']))
        {
            global $userData;
            $waitingTime = 300;
            $donatorService = new DonatorService();
            $waitingTime = $donatorService->adjustWaitingTime($waitingTime, $userData->getDonatorID());
            if($who == FALSE)
            {
                $uid = $_SESSION['UID'];
                $statement = $this->dbh->prepare("
                    UPDATE `user`
                    SET `whoresStreet`=`whoresStreet`+ :pimped, `pimpAmount`=`pimpAmount`+ :pimped, `pimpAttempts`=`pimpAttempts`+'1',
                    `cPimpWhores`= :time, `pimpLv`= :plv, `pimpXp`= :pxp
                    WHERE `id`= :uid
                ");
                $statement->execute(array(':pimped' => $amount, ':plv' => $newLv, ':pxp' => $newXp, ':time' => time() + $waitingTime, ':uid' => $uid));
            }
            else
            {
                global $userService;
                $uid = $userService->getIdByUsername($who);
                $statement = $this->dbh->prepare("
                    UPDATE `user`
                    SET `whoresStreet`=`whoresStreet`+ :pimped
                    WHERE `id`= :fid;

                    UPDATE `user`
                    SET `pimpAmount`=`pimpAmount`+ :pimped, `pimpAttempts`=`pimpAttempts`+'1', `cPimpWhoresFor`= :time, `pimpLv`= :plv, `pimpXp`= :pxp
                    WHERE `id`= :uid
                ");
                $statement->execute(array(
                    ':pimped' => $amount, ':fid' => $uid,
                    ':time' => time() + $waitingTime, ':plv' => $newLv, ':pxp' => $newXp, ':uid' => $_SESSION['UID']
                ));
            }
        }
    }
    
    public function placeWhoresBehindWindow($amount, $rldInfo)
    {
        if(isset($_SESSION['UID']))
        {
            global $userData;
            $statement = $this->dbh->prepare("SELECT `id` FROM `rld_whore` WHERE `userID`= :uid AND `stateID`= :stateID LIMIT 1");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':stateID' => $userData->getStateID()));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $update = $this->dbh->prepare("UPDATE `rld_whore` SET `whores`=`whores`+ :amount WHERE `id`= :rldid");
                $update->execute(array(':amount' => $amount, ':rldid' => $row['id']));
            }
            else
            {
                $insert = $this->dbh->prepare("INSERT INTO `rld_whore` (`stateID`, `userID`, `whores`) VALUES (:stateID, :uid, :amount)");
                $insert->execute(array(':stateID' => $userData->getStateID(), ':uid' => $_SESSION['UID'], ':amount' => $amount));
            }
            
            $price = $profitOwner = $amount * $rldInfo->getPriceEachWindow();
            
            $statement = $this->dbh->prepare("UPDATE `user` SET `cash`=`cash`- :price, `whoresStreet`=`whoresStreet`- :amount WHERE `id`= :uid");
            $statement->execute(array(':price' => $price, ':amount' => $amount, ':uid' => $_SESSION['UID']));
            
            /** Possession logic for buying windows | pay owner if exists and not self **/
            /** Bullet Factory & RLD will fetch owner without use of $possession->getPossessionByPossessId() since they're partially operated from a different table! **/
            $owner = $this->con->getDataSR("SELECT `userID` FROM `possess` WHERE `id`= :pid AND `active`='1' AND `deleted`='0'", array(':pid' => $rldInfo->getPossessID()));
            if(isset($owner['userID']) && $owner['userID'] > 0 && $owner['userID'] != $_SESSION['UID'])
            {
                $this->con->setData("
                    UPDATE `possess` SET `profit`=`profit`+ :profit, `profit_hour`=`profit_hour`+ :profit WHERE `id`= :pid AND `active`='1' AND `deleted`='0';
                    UPDATE `user` SET `bank`=`bank`+ :profit WHERE `id`= :oid AND `active`='1' AND `deleted`='0'
                ", array(':profit' => $profitOwner, ':pid' => $rldInfo->getPossessID(), ':oid' => $owner['userID']));
            }
        }
    }
    
    public function getRLDWhoresFromState($stateID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `whores` FROM `rld_whore` WHERE `stateID`= :stateID AND `userID`= :uid LIMIT 1");
            $statement->execute(array(':stateID' => $stateID, ':uid' => $_SESSION['UID']));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return $row['whores'];
            else
                return 0;
        }
    }
    
    public function takeAwayWhoresBehindWindow($stateID, $amount)
    {
        if(isset($_SESSION['UID']))
        {
            if(($this->getRLDWhoresFromState($stateID) - $amount) == 0)
            {
                $statement = $this->dbh->prepare("DELETE FROM `rld_whore` WHERE `stateID`= :stateID AND `userID`= :uid");
                $statement->execute(array(':stateID' => $stateID, ':uid' => $_SESSION['UID']));
            }
            else
            {
                $statement = $this->dbh->prepare("UPDATE `rld_whore` SET `whores`=`whores`- :amount WHERE `stateID`= :stateID AND `userID` = :uid");
                $statement->execute(array(':amount' => $amount, ':stateID' => $stateID, ':uid' => $_SESSION['UID']));
            }
            $statement = $this->dbh->prepare("UPDATE `user` SET `whoresStreet`=`whoresStreet`+ :amount WHERE `id`= :uid");
            $statement->execute(array(':amount' => $amount, ':uid' => $_SESSION['UID']));
        }
    }
}
