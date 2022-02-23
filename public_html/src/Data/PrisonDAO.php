<?PHP

namespace src\Data;

use app\config\Routing;
use src\Data\config\DBConfig;
use src\Data\PossessionDAO;
use src\Entities\Prison;

class PrisonDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    
    public function __construct()
    {
        global $lang;
        global $connection;
        
        $this->con = $connection;
        $this->dbh = $connection->con;
        $this->lang = $lang;
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            $queryAddon = $famID = "";
            $paramsArr = array(':time' => time());
            if (strpos($_SERVER['REQUEST_URI'],'family-prison') !== false)
            {
                global $userData;
                $famID = $userData->getFamilyID();
                $queryAddon = " AND u.`familyID`= :famID ";
                $paramsArr[':famID'] = $famID;
            }
            $statement = $this->dbh->prepare("SELECT COUNT(p.`id`) AS `total` FROM `prison` AS p LEFT JOIN `user` AS u ON (p.`userID`=u.`id`) WHERE p.`time` > :time".$queryAddon."");
            $statement->execute($paramsArr);
            $row = $statement->fetch();
            if(isset($row['total'])) return $row['total'];
        }
        return false;
    }
    
    public function getPrisonerByPID($pid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT p.`id`, p.`userID`,p.`time`, u.`username`
                FROM `prison` AS p
                LEFT JOIN `user` AS u
                ON (p.`userID`=u.`id`)
                WHERE p.`id`=:pid"
            );
            $statement->execute(array(':pid' => $pid));
            $row = $statement->fetch();
            if(!empty($row))
            {
                $data = new Prison();
                $data->setId($row['id']);
                $data->setUserID($row['userID']);
                $data->setUsername($row['username']);
                $data->setTime($row['time']);
                return $data;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return null;
        }
    }
    
    public function fetchPrisoners($from, $to, $fam = false)
    {
        if(isset($_SESSION['UID']) && is_int($from) && is_int($to) && $to <= 50 && $to >=1)
        {
            $statement = $this->dbh->prepare("DELETE FROM `prison` WHERE `time` < :time");
            $statement->execute(array(':time' => time()));
            if(!isset($fam) || $fam == false)
            {
                $statement = $this->dbh->prepare("
                    SELECT p.`id`, p.`userID`, p.`time`, u.`username`
                    FROM `prison` AS p 
                    LEFT JOIN `user` AS u 
                    ON (p.userID=u.id)
                    WHERE p.`time` > :time
                    GROUP BY p.`userID`
                    ORDER BY p.`time` DESC, p.`id` DESC
                    LIMIT $from,$to
                ");
                $statement->execute(array(':time' => time()));
                $list = array();
                foreach($statement AS $row)
                {
                    $data = new Prison();
                    $data->setId($row['id']);
                    $data->setUserID($row['userID']);
                    $data->setUsername($row['username']);
                    $data->setTime($row['time']);
                    array_push($list, $data);
                }
                return $list;
            }
            else
            {
                $statement = $this->dbh->prepare("
                    SELECT p.`id`, p.`userID`, p.`time`, u.`username`
                    FROM `prison` AS p
                    LEFT JOIN `user`  AS u
                    ON (p.userID=u.id)
                    WHERE p.`time` > :time
                    AND u.`familyID` = :famID
                    GROUP BY p.`userID`
                    ORDER BY p.`time` DESC, p.`id` DESC
                    LIMIT $from,$to
                ");
                $statement->execute(array(':time' => time(), ':famID' => $fam));
                $list = array();
                foreach($statement AS $row)
                {
                    $data = new Prison();
                    $data->setId($row['id']);
                    $data->setUserID($row['userID']);
                    $data->setUsername($row['username']);
                    $data->setTime($row['time']);
                    array_push($list,$data);
                }
                return $list;
            }
        }
    }
    
    public function buyOutPlayerByPID($pID, $priceToBuyOut, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            $p = $this->con->getDataSR("
                SELECT `userID` FROM `prison` WHERE `id`= :pid LIMIT 1
            ", array(':pid' => $pID));
            if(isset($p['userID']) && $p['userID'] > 0)
            {
                $profitOwner = $priceToBuyOut;
                $this->con->setData("
                    UPDATE `prison` SET `time`='0' WHERE `id`= :pid;
                    UPDATE `user` SET `cash`=`cash`- :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(
                    ':pid' => $pID,
                    ':price' => $priceToBuyOut, ':uid' => $_SESSION['UID']
                ));
                
                /** Possession logic for buy out player | pay owner if exists and not self **/
                if(is_object($pData)) $prisonOwner = $pData->getPossessDetails()->getUserID();
                if(is_object($pData) && $prisonOwner > 0 && $prisonOwner != $_SESSION['UID'])
                {
                    $possessionData = new PossessionDAO();
                    $possessionData->applyProfitForOwner($pData, $profitOwner, $prisonOwner);
                }
            }
        }
    }
    
    public function successfulBreakOutPlayerByPID($pID, $rankpoints)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("DELETE FROM `prison` WHERE `id` = :pid");
            $statement->execute(array(':pid' => $pID));
            
            $statement = $this->dbh->prepare("UPDATE `user` SET `rankpoints`=`rankpoints` + :amount, `prisonBusts`=`prisonBusts`+'1' WHERE id = :uid");
            $statement->execute(array(':amount' => $rankpoints, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function failedBreakOutPlayerByPrisonerObject($prisoner)
    {
        $this->putUserInPrison($prisoner->getUserID(), ($prisoner->getTime()+120));
        $this->putUserInPrison($_SESSION['UID'], (time()+180));
    }
    
    public function putUserInPrison($userID, $time)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("DELETE FROM `prison` WHERE `time` < :time; DELETE FROM `prison` WHERE `userID`= :uid");
            $statement->execute(array(':time' => time(), ':uid' => $userID));
            
            $statement = $this->dbh->prepare("INSERT INTO `prison` (`userID`,`time`) VALUES (:uid, :time)");
            $statement->execute(array(':uid' => $userID, ':time' => $time));
        }
    }
    
    public function isUserInPrison($userID)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `time` FROM `prison` WHERE `userID`= :uid
            ", array(':uid' => $userID));
            
            if(isset($row['time']) && $row['time'] > time())
                return true;
            else
                return false;
        }
    }
}
