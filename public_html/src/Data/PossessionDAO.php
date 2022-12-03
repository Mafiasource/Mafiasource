<?PHP

namespace src\Data;

use src\Business\StateService;
use src\Data\config\DBConfig;
use src\Data\FamilyDAO;
use src\Entities\Possession;
use src\Entities\Possess;
use src\Entities\PossessTransfer;
use src\Entities\RLD;
use src\Entities\BulletFactory;

class PossessionDAO extends DBConfig
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
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `possession` WHERE `deleted` = '0' AND `active` = '1'");
            $statement->execute();
            $row = $statement->fetch();
            return $row['total'];
        }
    }
    
    public function getAllPossessionsByStateAndCity($stateID, $cityID)
    {
        if(isset($_SESSION['UID']))
        {
            $list['positionlessPossessions'] = $this->getPossessions();
            $list['statePossessions'] = $this->getPossessions($stateID);
            $list['cityPossessions'] = $this->getPossessions($stateID, $cityID);
            return $list;
        }
    }
    
    private function getPossessions($stateID = 0, $cityID = 0)
    {
        if(isset($_SESSION['UID']))
        {
            global $langs;
            
            $state = new StateService();
            $statement = $this->dbh->prepare("
                SELECT p2.`id` AS `id`, p.`id` AS `pid`, p2.`name_".$this->lang."` AS `name`, p2.`picture`, p2.`price`, p.`stateID`, p.`cityID`, p.`userID`, u.`username`, p.`profit`
                FROM `possess` AS p
                LEFT JOIN `possession` AS p2
                ON (p.`pID`=p2.`id`)
                LEFT JOIN `user` AS u
                ON (p.`userID`=u.`id`)
                WHERE p.`stateID`= :sid AND p.`cityID` = :cid
                AND p.`active`='1' AND p.`deleted`='0'
                AND p2.`active`='1' AND p2.`deleted`='0'
                ORDER BY p.`id` ASC
            ");
            $statement->execute(array(':sid' => $stateID, ':cid' => $cityID));
            $list = array();
            foreach($statement AS $row)
            {
                $possession = new Possession();
                $possession->setId($row['id']);
                $possession->setName($row['name']);
                $possession->setPicture($row['picture']);
                $possession->setPrice($row['price']);
                
                $possess = new Possess();
                $possess->setId($row['pid']); // possess record id
                $possess->setPID($row['id']); // possession record id
                $possess->setStateID($row['stateID']);
                $possess->setState($state->getStateNameById($row['stateID']));
                $possess->setCityID($row['cityID']);
                $possess->setCity($state->getCityNameById($row['cityID']));
                $possess->setUserID($row['userID']);
                $possess->setUsername($row['username']);
                if(empty($row['username'])) $possess->setUsername($langs['NONE']);
                $possess->setProfit($row['profit']);
                $possession->setPossessDetails($possess);
                
                array_push($list, $possession);
            }
            return $list;
        }
    }
    
    public function getPossessionByPossessId($id)
    {
        if(isset($_SESSION['UID']))
        {
            global $langs;
            
            $state = new StateService();
            $statement = $this->dbh->prepare("
                SELECT p2.`id` AS `id`, p.`id` AS `pid`, p2.`name_".$this->lang."` AS `name`, p2.`price`, p.`stateID`, p.`cityID`, p.`userID`, u.`username`, p.`profit`, p.`stake`,
                    s.`name` AS `state`, c.`name` AS `city`,
                    bf.`id` AS `bfID`, bf.`bullets`, bf.`priceEachBullet`, bf.`production`,
                    rld.`id` AS `rldID`, `rld`.`windows`, rld.`priceEachWindow`
                FROM `possess` AS p
                LEFT JOIN `possession` AS p2
                ON (p.`pID`=p2.`id`)
                LEFT JOIN `bullet_factory` AS bf
                ON (p.`id`=bf.`possessID`)
                LEFT JOIN `rld` AS rld
                ON (p.`id`=rld.`possessID`)
                LEFT JOIN `state` AS s
                ON (p.`stateID`=s.`id`)
                LEFT JOIN `city` AS c
                ON (p.`cityID`=c.`id`)
                LEFT JOIN `user` AS u
                ON (p.`userID`=u.`id`)
                WHERE p.`id`= :pid
                AND p.`active`='1' AND p.`deleted`='0'
                AND p2.`active`='1' AND p2.`deleted`='0'
            ");
            $statement->execute(array(':pid' => $id));
            $row = $statement->fetch();
            if(isset($row['pid']) && $row['pid'] == $id)
            {
                $possession = new Possession();
                $possession->setId($row['id']);
                $possession->setName($row['name']);
                $possession->setPrice($row['price']);
                
                $possess = new Possess();
                $possess->setId($row['pid']); // possess record id
                $possess->setPID($row['id']); // possession record id
                $possess->setStateID($row['stateID']);
                $possess->setState($state->getStateNameById($row['stateID']));
                $possess->setCityID($row['cityID']);
                $possess->setCity($state->getCityNameById($row['cityID']));
                $possess->setUserID($row['userID']);
                $possess->setUsername($row['username']);
                if(empty($row['username'])) $possess->setUsername($langs['NONE']);
                $possess->setProfit($row['profit']);
                $possess->setStake($row['stake']);
                $possession->setPossessDetails($possess);
                
                if(!is_null($row['bfID']))
                {
                    $bulletFactory = new BulletFactory();
                    $bulletFactory->setId($row['bfID']);
                    $bulletFactory->setPossessID($row['pid']);
                    $bulletFactory->setBullets($row['bullets']);
                    $bulletFactory->setPriceEachBullet($row['priceEachBullet']);
                    $bulletFactory->setProduction($row['production']);
                    $bulletFactory->setProducing(false);
                    if($bulletFactory->getProduction() > 0)
                        $bulletFactory->setProducing(true);
                    
                    $possession->setBulletFactoryDetails($bulletFactory);
                }
                if(!is_null($row['rldID']))
                {
                    $rld = new RLD();
                    $rld->setId($row['rldID']);
                    $rld->setPossessID($row['pid']);
                    $rld->setWindows($row['windows'] * 10000);
                    $rld->setPriceEachWindow($row['priceEachWindow']);
                    $possession->setRedLightDistrictDetails($rld);
                }
                
                return $possession;
            }
        }
        return FALSE;
    }
    
    public function userHasPossessionById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `id` FROM `possess` WHERE `pID`= :id AND `userID`= :uid AND `active`='1' AND `deleted`='0'", array(':id' => $id, ':uid' => $_SESSION['UID']));
            if(isset($row['id']) && $row['id'] > 0)
                return true;
        }
        return false;
    }
    
    public function receiverHasPossessionById($rid, $id)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `id` FROM `possess` WHERE `pID`= :id AND `userID`= :rid AND `active`='1' AND `deleted`='0'", array(':id' => $id, ':rid' => $rid));
            if(isset($row['id']) && $row['id'] > 0)
                return true;
        }
        return false;
    }
    
    public function familyHasAmountPossessionsById($id, $familyID, $amount = 2)
    {
        if(isset($_SESSION['UID']))
        {
            $familyData = new FamilyDAO();
            $ids = $familyData->getImplodedFamilyMemberIds($familyID);
            
            $rows = $this->con->getData("SELECT `id` FROM `possess` WHERE `pID`= :id AND `userID` IN(".$ids.") AND `active`='1' AND `deleted`='0'", array(':id' => $id));
            
            if(!empty($rows) && count($rows) >= $amount)
                return true;
        }
        return false;
    }
    
    public function familyHasAmountCountryPossessions($familyID, $amount = 1)
    {
        if(isset($_SESSION['UID']))
        {
            $familyData = new FamilyDAO();
            $ids = $familyData->getImplodedFamilyMemberIds($familyID);
            
            $rows = $this->con->getData("
                SELECT `id` FROM `possess` WHERE `userID` IN(".$ids.") AND `stateID`='0' AND `cityID`='0' AND `pID` IN (10, 12, 18) AND `active`='1' AND `deleted`='0'
            ");
            
            if(!empty($rows) && count($rows) >= $amount)
                return true;
        }
        return false;
    }
    
    public function userHasCountryPossession()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `possess` WHERE `userID`= :uid AND `stateID`='0' AND `cityID`='0' AND `pID` IN (10, 12, 18) AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $_SESSION['UID']));
            
            if(isset($row['id']) && $row['id'] > 0)
                return true;
        }
        return false;
    }
    
    public function receiverHasCountryPossession($rid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `possess` WHERE `userID`= :rid AND `stateID`='0' AND `cityID`='0' AND `pID` IN (10, 12, 18) AND `active`='1' AND `deleted`='0'
            ", array(':rid' => $rid));
            
            if(isset($row['id']) && $row['id'] > 0)
                return true;
        }
        return false;
    }
    
    public function possessTransferedToReceiver($pid, $rid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `receiverID` FROM `possess_transfer` WHERE `possessID`= :pid AND `senderID`= :uid AND `receiverID`= :rid LIMIT 1
            ", array(':pid' =>$pid, ':uid' =>$_SESSION['UID'], ':rid' => $rid));
            
            if(isset($row['receiverID']) && $row['receiverID'] == $rid)
                return true;
        }
        return false;
    }
    
    public function buyPossessionByPossessId($pid, $price)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `possess` SET `userID`= :uid, `profit`='0', `profit_hour`='0', `stake`='50000' WHERE `id`= :pid AND `active`='1' AND `deleted`='0'");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':pid' => $pid));
            
            $statement = $this->dbh->prepare("UPDATE `user` SET `cash`=`cash`- :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0'");
            $statement->execute(array(':price' => $price, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function getPossessIdByPossessionId($id, $stateID = false, $cityID = false)
    {
        if(isset($_SESSION['UID']))
        {
            if($cityID == false && $stateID == false)
            {
                $statement = $this->dbh->prepare("SELECT `id` FROM `possess` WHERE `pID`= :id AND `active`='1' AND `deleted`='0'");
                $statement->execute(array(':id' => $id));
            }
            elseif($stateID != false && $cityID == false)
            {
                $statement = $this->dbh->prepare("SELECT `id` FROM `possess` WHERE `pID`= :id AND `stateID`= :sid AND `active`='1' AND `deleted`='0'");
                $statement->execute(array(':id' => $id, ':sid' => $stateID));
            }
            elseif($stateID != false && $cityID != false)
            {
                $statement = $this->dbh->prepare("SELECT `id` FROM `possess` WHERE `pID`= :id AND `stateID`= :sid AND `cityID`= :cid AND `active`='1' AND `deleted`='0'");
                $statement->execute(array(':id' => $id, ':sid' => $stateID, ':cid' => $cityID));
            }
            if(isset($statement))
            {
                $row = $statement->fetch();
                if(isset($row['id']) && $row['id'] > 0)
                {
                    return $row['id'];
                }
            }
        }
    }
    
    public function dropPossession($pid)
    {
        if(isset($_SESSION['UID']))
        {
            $addQuery = "";
            if($pid >= 1 & $pid <= 6) // Bullet Factory
                $addQuery .= "; UPDATE `bullet_factory` SET `priceEachBullet`='2500', `production`='0' WHERE `possessID`= :pid";
            elseif($pid >= 7 & $pid <= 12) // Red Light District
                $addQuery .= "; UPDATE `rld` SET `priceEachWindow`='150' WHERE `possessID`= :pid";
            
            $this->con->setData("
                UPDATE `possess` SET `userID`='0', `profit`='0', `profit_hour`='0', `stake`='50000' WHERE `id`= :pid AND `userID`= :uid AND `active`='1' AND `deleted`='0'
                ".$addQuery."
            ", array(':pid' => $pid, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function getUserPossessionsManagement()
    {
        if(isset($_SESSION['UID']))
        {
            $possessions = $this->con->getData("
                SELECT p2.`id` AS `id`, p.`id` AS `pid`, p2.`name_".$this->lang."` AS `name`, p.`stateID`, p.`cityID`, p.`profit`, p.`profit_hour`, p.`stake`,
                    s.`name` AS `state`, c.`name` AS `city`,
                    bf.`id` AS `bfID`, bf.`bullets`, bf.`priceEachBullet`, bf.`production`,
                    rld.`id` AS `rldID`, `rld`.`windows`, rld.`priceEachWindow`
                FROM `possess` AS p
                LEFT JOIN `possession` AS p2
                ON (p.`pID`=p2.`id`)
                LEFT JOIN `bullet_factory` AS bf
                ON (p.`id`=bf.`possessID`)
                LEFT JOIN `rld` AS rld
                ON (p.`id`=rld.`possessID`)
                LEFT JOIN `state` AS s
                ON (p.`stateID`=s.`id`)
                LEFT JOIN `city` AS c
                ON (p.`cityID`=c.`id`)
                WHERE p.`userID`= :uid AND p.`active`='1' AND p.`deleted`='0'
                    AND p2.`active`='1' AND p2.`deleted`='0'
                ORDER BY p.`id` ASC
            ", array(':uid' => $_SESSION['UID']));
            
            $list = array();
            foreach($possessions AS $row)
            {
                $possession = new Possession();
                $possession->setId($row['id']); 
                $possession->setName($row['name']);
                
                $possess = new Possess();
                $possess->setId($row['pid']); // Id possess record 
                $possess->setPID($row['id']); // Id possession record
                $possess->setStateID($row['stateID']);
                $possess->setState($row['state']);
                $possess->setCityID($row['cityID']);
                $possess->setCity($row['city']);
                $possess->setUserID($_SESSION['UID']);
                $possess->setProfit($row['profit']);
                $possess->setProfitHour($row['profit_hour']);
                $possess->setStake($row['stake']);
                $possess->setIsOwner(true);
                $possession->setPossessDetails($possess);
                
                if(!is_null($row['bfID']))
                {
                    $bulletFactory = new BulletFactory();
                    $bulletFactory->setId($row['bfID']);
                    $bulletFactory->setPossessID($row['pid']);
                    $bulletFactory->setBullets($row['bullets']);
                    $bulletFactory->setPriceEachBullet($row['priceEachBullet']);
                    $bulletFactory->setProduction($row['production']);
                    $bulletFactory->setProducing(false);
                    if($bulletFactory->getProduction() > 0)
                        $bulletFactory->setProducing(true);
                    
                    $possession->setBulletFactoryDetails($bulletFactory);
                }
                if(!is_null($row['rldID']))
                {
                    $rld = new RLD();
                    $rld->setId($row['rldID']);
                    $rld->setPossessID($row['pid']);
                    $rld->setWindows($row['windows'] * 10000);
                    $rld->setPriceEachWindow($row['priceEachWindow']);
                    $possession->setRedLightDistrictDetails($rld);
                }
                array_push($list, $possession);
            }
            return $list;
        }
    }
    
    public function getTransferedPossessions()
    {
        if(isset($_SESSION['UID']))
        {
            $transfers = $this->con->getData("SELECT `possessID` FROM `possess_transfer` WHERE `receiverID`= :uid", array(':uid' => $_SESSION['UID']));
            
            $trans = array();
            foreach($transfers AS $t)
                $trans[] = (int)$t['possessID'];
            
            $ids = implode(',', $trans);
            
            $possessions = $this->con->getData("
                SELECT p2.`id` AS `id`, p.`id` AS `pid`, p2.`name_".$this->lang."` AS `name`, p.`stateID`, p.`cityID`, p.`userID`,
                    s.`name` AS `state`, c.`name` AS `city`
                FROM `possess` AS p
                LEFT JOIN `possession` AS p2
                ON (p.`pID`=p2.`id`)
                LEFT JOIN `state` AS s
                ON (p.`stateID`=s.`id`)
                LEFT JOIN `city` AS c
                ON (p.`cityID`=c.`id`)
                WHERE p.`id` IN(".$ids.") AND p.`active`='1' AND p.`deleted`='0'
                    AND p2.`active`='1' AND p2.`deleted`='0'
                ORDER BY p.`id` ASC
            ");
            
            $list = array();
            if(is_array($possessions))
            {
                foreach($possessions AS $row)
                {
                    $possession = new Possession();
                    $possession->setId($row['id']);
                    $possession->setName($row['name']);
                    
                    $possess = new Possess();
                    $possess->setId($row['pid']); // Id possess record 
                    $possess->setPID($row['id']); // Id possession record
                    $possess->setStateID($row['stateID']);
                    $possess->setState($row['state']);
                    $possess->setCityID($row['cityID']);
                    $possess->setCity($row['city']);
                    $possess->setUserID($row['userID']);
                    $possess->setIsOwner(false);
                    $possession->setPossessDetails($possess);
                    
                    array_push($list, $possession);
                }
                return $list;
            }
        }
    }
    
    public function getTransferedPossessionByPossessID($pid)
    {
        if(isset($_SESSION['UID']))
        {
            $transfer = $this->con->getDataSR("SELECT `senderID` FROM `possess_transfer` WHERE `possessID`= :pid AND `receiverID`= :uid", array(':pid' => $pid, ':uid' => $_SESSION['UID']));
            if(isset($transfer['senderID']) && $transfer['senderID'] > 0)
            {
                $pt = new PossessTransfer();
                $pt->setPossessID($pid);
                $pt->setSenderID($transfer['senderID']);
                $pt->setReceiverID($_SESSION['UID']);
                
                return $pt;
            }
        }
    }
    
    public function transferPossessionRequest($pid, $rid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                INSERT INTO `possess_transfer` (`possessID`, `senderID`, `receiverID`) VALUES (:pid, :sid, :rid) ON DUPLICATE KEY UPDATE `possessID`=`possessID`
            ", array(':pid' => $pid, ':sid' => $_SESSION['UID'], ':rid' => $rid));
        }
    }
    
    public function acceptTransferedPossession($pid, $sid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->removePossessTransferByPossessID($pid);
            $this->con->setData("UPDATE `possess` SET `userID`= :uid, `profit`='0', `profit_hour`='0' WHERE `id`= :pid AND `userID`= :sid", array(':uid' => $_SESSION['UID'], ':pid' => $pid, ':sid' => $sid));
        }
    }
    
    public function denyTransferedPossession($pid, $sid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("DELETE FROM `possess_transfer` WHERE `possessID`= :pid AND `senderID`= :sid AND `receiverID`= :uid", array(':pid' => $pid, ':sid' => $sid, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function removePossessTransferByPossessID($pid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("DELETE FROM `possess_transfer` WHERE `possessID`= :pid", array(':pid' => $pid));
        }
    }
    
    public function changeBulletPrice($pid, $price)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `bullet_factory` SET `priceEachBullet`= :price WHERE `possessID`= :pid AND `priceEachBullet`!= :price", array(':price' => $price, ':pid' => $pid));
        }
    }
    
    public function produceBullets($pid, $production)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `bullet_factory` SET `production`= :p WHERE `possessID`= :pid AND `production`!= :p", array(':p' => $production, ':pid' => $pid));
        }
    }
    
    public function buyWindows($pid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `rld` SET `windows`=`windows`+'1' WHERE `possessID`= :pid;
                UPDATE `user` SET `bank`=`bank`-'1000000' WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(
                ':pid' => $pid,
                ':uid' => $_SESSION['UID']
            ));
        }
    }
    
    public function changeWindowPrice($pid, $price)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `rld` SET `priceEachWindow`= :price WHERE `possessID`= :pid AND `priceEachWindow`!= :price", array(':price' => $price, ':pid' => $pid));
        }
    }
    
    public function changeStake($pid, $stake)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `possess` SET `stake`= :stake WHERE `id`= :pid AND `userID`= :uid AND `stake`!= :stake", array(':stake' => $stake, ':uid' => $_SESSION['UID'], ':pid' => $pid));
        }
    }
    
    public function getBulletFactoryInfoByPossessID($id)
    { // Bullet factories has it's own table.
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `bullets`, `priceEachBullet`, `production` FROM `bullet_factory` WHERE `possessID`= :id LIMIT 1");
            $statement->execute(array(':id' => $id));
            
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $bf = new BulletFactory();
                $bf->setId($row['id']);
                $bf->setPossessID($id);
                $bf->setBullets($row['bullets']);
                $bf->setPriceEachBullet($row['priceEachBullet']);
                $bf->setProduction($row['production']);
                $bf->setProducing(false);
                if($bf->getProduction() > 0)
                    $bf->setProducing(true);
                
                return $bf;
            }
        }
    }
    
    public function getRLDInfoByPossessID($id)
    { // Red Light District has it's own table.
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `windows`, `priceEachWindow` FROM `rld` WHERE `possessID`= :id LIMIT 1");
            $statement->execute(array(':id' => $id));
            
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                global $userData;
                $rld = new RLD();
                $rld->setId($row['id']);
                $rld->setPossessID($id);
                $rld->setWindows($row['windows'] * 10000);
                $rld->setPriceEachWindow($row['priceEachWindow']);
                
                $statement2 = $this->dbh->prepare("SELECT SUM(`whores`) AS `whoresTotal` FROM `rld_whore` WHERE `stateID`= :stateID");
                $statement2->execute(array(':stateID' => $userData->getStateID()));
                $w = $statement2->fetch();
                
                $rld->setWindowsUsed($w['whoresTotal']);
                
                return $rld;
            }
        }
    }
    
    public function getProfilePossessionsByUserID($uid)
    {
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData("
                SELECT up.`id` AS `possessID`, `pID` AS `possessionID`, p.`name_".$this->lang."` AS `name`, s.`name` AS `state`, c.`name` AS `city`
                FROM `possess` AS up
                LEFT JOIN `possession` AS p
                ON  (up.`pID`=p.`id`)
                LEFT JOIN `state` AS s
                ON (up.`stateID`=s.`id`)
                LEFT JOIN `city` AS c
                ON (up.`cityID`=c.`id`)
                WHERE up.`userID`= :uid AND up.`active`='1' AND up.`deleted`='0'
                ORDER BY up.`id` ASC
            ", array(':uid' => $uid));
            
            $list = array();
            
            foreach($rows AS $row)
            {
                $possession = new Possession();
                $possession->setName($row['name']);
                
                $possess = new Possess();
                $possess->setId($row['possessID']); // Id possess record 
                $possess->setPID($row['possessionID']);
                $possess->setState($row['state']);
                $possess->setCity($row['city']);
                $possess->setUserID($uid);
                $possession->setPossessDetails($possess);
                
                array_push($list, $possession);
            }
            return $list;
        }
    }
    
    public function applyProfitForOwner($pData, $profit, $oid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `possess` SET `profit`=`profit`+ :profit, `profit_hour`=`profit_hour`+ :profit WHERE `id`= :pid AND `userID`= :oid AND `active`='1' AND `deleted`='0' LIMIT 1;
                UPDATE `user` SET `bank`=`bank`+ :profit WHERE `id`= :oid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':profit' => $profit, ':pid' => $pData->getPossessDetails()->getId(), ':oid' => $oid));
        }
    }
    
    public function applyLossForOwner($pData, $loss, $oid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `possess` SET `profit`=`profit`- :loss, `profit_hour`=`profit_hour`- :loss WHERE `id`= :pid AND `userID`= :oid AND `active`='1' AND `deleted`='0' LIMIT 1;
                UPDATE `user` SET `bank`=`bank`- :loss WHERE `id`= :oid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':loss' => $loss, ':pid' => $pData->getPossessDetails()->getId(), ':oid' => $oid));
        }
    }
    
    public function takeOverOwner($pData, $uid, $oid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `possess` SET `userID`= :uid, `profit`='0', `profit_hour`='0' WHERE `id`= :pid AND `userID`= :oid AND `active`='1' AND `deleted`='0' LIMIT 1;
                UPDATE `user` SET `bank`='0' WHERE `id`= :oid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':uid' => $uid, ':pid' => $pData->getPossessDetails()->getId(), ':oid' => $oid));
        }        
    }
}
