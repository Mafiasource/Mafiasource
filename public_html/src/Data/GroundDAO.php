<?PHP

namespace src\Data;

use src\Business\GroundService;
use src\Business\StateService;
use src\Business\DonatorService;
use src\Data\config\DBConfig;
use src\Entities\Ground;
use src\Entities\GroundBuilding;

class GroundDAO extends DBConfig
{
    protected $con = ""; // Init
    private $dbh = ""; // Init
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
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `ground` WHERE  `active` = '1' AND `deleted` = '0'");
            $statement->execute();
            $row = $statement->fetch();
            return isset($row['total']) ? $row['total'] : 0;
        }
    }
    
    public function getHometownFamilyByStateID($stateID)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT COUNT(g.`id`) AS `ground`, u.`familyID`, f.`name` AS `familyName`
                FROM `ground` AS g
                INNER JOIN `user` AS u
                ON (g.`userID`=u.`id`)
                LEFT JOIN `family` AS f
                ON (u.`familyID`=f.`id`)
                WHERE u.`familyID`>'0' AND g.`stateID`= :stateID
                GROUP BY u.`familyID`
                ORDER BY `ground` DESC LIMIT 1
            ", array(':stateID' => $stateID));
            
            if(isset($row['ground']) && $row['ground'] > 0)
                return $row['familyName'];
            else
                return FALSE;
        }
    }
    
    public function getGroundDataByStateIdAndGroundID($stateID, $groundID, $buildingsInfo = FALSE)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT g.`id`, g.`userID`, u.`username`, g.`building1`, g.`building2`, g.`building3`, g.`building4`, g.`building5`,
                    g.`cBuilding1`, g.`cBuilding2`, g.`cBuilding3`, g.`cBuilding4`, g.`cBuilding5`
                FROM `ground` AS g
                LEFT JOIN `user` AS u
                ON (g.`userID`=u.`id`)
                WHERE g.`stateID`= :sid AND g.`gID`= :gID AND g.`active`='1' AND g.`deleted`='0'
            ", array(':sid' => $stateID, ':gID' => $groundID));
            
            if(isset($row['id']) && $row['id'] > 0)
            {
                $state = new StateService();
                
                $ground = new Ground();
                $ground->setId($row['id']);
                $ground->setGID($groundID);
                $ground->setStateID($stateID);
                $ground->setState($state->getStateNameById($stateID));
                $ground->setUserID($row['userID']);
                $ground->setUsername($row['username']);
                $ground->setCBuilding1($row['cBuilding1']);
                $ground->setCBuilding2($row['cBuilding2']);
                $ground->setCBuilding3($row['cBuilding3']);
                $ground->setCBuilding4($row['cBuilding4']);
                $ground->setCBuilding5($row['cBuilding5']);
                if($buildingsInfo !== FALSE)
                {
                    $buildingsList = array();
                    for($i = 1; $i <= 5; $i++)
                    {
                        $building = $this->getBuildingInfoById($i, $row['building'.$i]);
                        array_push($buildingsList, $building);
                    }
                    $ground->setBuildings($buildingsList);
                }
                $ground->setInPossession(false);
                if(isset($row['userID']) && $row['userID'] == $_SESSION['UID']) $ground->setInPossession(true);
                $ground->setPicture("ground-map/no-owner.".$this->lang.".png");
                if(isset($row['userID']) && $row['userID'] != 0)
                {
                    $familyID = $this->con->getDataSR("SELECT `familyID` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':uid' => $row['userID']))['familyID'];
                    if($familyID == 0)
                        $ground->setPicture("families/nopic_small.jpg");
                    else
                    {
                        $ground->setPicture("families/nopic_small.jpg");
                        $familyIcon = $this->con->getDataSR("SELECT `icon` FROM `family` WHERE `id`= :fid AND `active`='1' AND `deleted`='0'", array(':fid' => $familyID))['icon'];
                        if(!empty($familyIcon)) $ground->setPicture("families/".$familyID."/uploads/".$familyIcon);
                    }
                }
                
                return $ground;
            }
        }
    }
    
    public function getBuildingInfoById($id, $level = FALSE)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id`, `name_".$this->lang."` AS `name` , `picture`, `price`, `income` FROM `ground_building` WHERE `id`= :bid AND `active`='1' AND `deleted`='0'
            ", array(':bid' => $id));
            if(isset($row['id']) && $row['id'] > 0)
            {
                $building = new GroundBuilding();
                $building->setId($row['id']);
                $building->setName($row['name']);
                $building->setPicture($row['picture']);
                $building->setPrice($row['price']);
                $building->setIncome($row['income']);
                if($level >= 1 && $level <= 5) $building->setIncome(GroundService::getIncomeByLevel($row['income'], $level));
                $building->setInPossession(true);
                $building->setLevel(0);
                if($level == FALSE || $level == 0)
                    $building->setInPossession(false);
                else
                    $building->setLevel($level);
                
                return $building;
            }
        }
    }
    
    public function getGroundInPossession()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT COUNT(`id`) AS `ground` FROM `ground` WHERE `userID`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $_SESSION['UID']));
            return isset($row['ground']) ? $row['ground'] : 0;
        }
    }
    
    public function buyGround($ground)
    {
        if(isset($_SESSION['UID']))
        {
            if(is_object($ground) && $ground->getStateID() > 0 && $ground->getGID() > 0)
            {
                $this->con->setData("
                    UPDATE `ground` SET `userID`= :uid WHERE `stateID`= :sid AND `gID`= :gID AND `id`= :id AND `active`='1' AND `deleted`='0'
                ", array(':uid' => $_SESSION['UID'], ':sid' => $ground->getStateID(), ':gID' => $ground->getGID(), ':id' => $ground->getId()));
                
                $this->con->setData("
                    UPDATE `user` SET `cash`=`cash`- '100000' WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(':uid' => $_SESSION['UID']));
            }
        }
    }
    
    public function checkBuildingPossessionOnGround($ground, $building)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `building".$building->getId()."` FROM `ground` WHERE `userID`= :uid AND `stateID`= :sid AND `gID`= :gID AND `id`= :id AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $_SESSION['UID'], ':sid' => $ground->getStateID(), ':gID' => $ground->getGID(), ':id' => $ground->getId()));
            $bid = isset($row['building' . $building->getId()]) ? $row['building' . $building->getId()] : null;
            if(isset($bid) && $bid >= 1 && $bid <= 5)
                return TRUE;
            else
                return FALSE;
        }
        return FALSE;
    }
    
    public function buyGroundBuilding($ground, $building)
    {
        if(isset($_SESSION['UID']))
        {
            if(is_object($ground) && $ground->getStateID() > 0 && $ground->getGID() > 0 && is_object($building))
            {
                $this->con->setData("
                    UPDATE `ground`
                    SET `building".$building->getId()."`= '1'
                    WHERE `userID`= :uid AND `stateID`= :sid AND `gID`= :gID AND `id`= :id AND `active`='1' AND `deleted`='0'
                ", array(':uid' => $_SESSION['UID'], ':sid' => $ground->getStateID(), ':gID' => $ground->getGID(), ':id' => $ground->getId()));
                
                $this->con->setData("
                    UPDATE `user` SET `cash`=`cash`- :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(':price' => $building->getPrice(), ':uid' => $_SESSION['UID']));
            }
        }
    }
    
    public function upgradeGroundBuilding($ground, $building)
    {
        if(isset($_SESSION['UID']))
        {
            if(is_object($ground) && $ground->getStateID() > 0 && $ground->getGID() > 0 && is_object($building))
            {
                global $userData;
                $waitingTime = 60*60*24;
                $donatorService = new DonatorService();
                $waitingTime = $donatorService->adjustWaitingTime($waitingTime, $userData->getDonatorID(), $userData->getCHalvingTimes());
                $this->con->setData("
                    UPDATE `ground`
                    SET `building".$building->getId()."`=`building".$building->getId()."`+'1', `cBuilding".$building->getId()."`= :buildTime
                    WHERE `userID`= :uid AND `stateID`= :sid AND `gID`= :gID AND `id`= :id AND `active`='1' AND `deleted`='0'
                ", array(':buildTime' => (time() + $waitingTime), ':uid' => $_SESSION['UID'], ':sid' => $ground->getStateID(), ':gID' => $ground->getGID(), ':id' => $ground->getId()));
                
                $this->con->setData("
                    UPDATE `user` SET `cash`=`cash`- :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(':price' => round(($building->getPrice() * ($building->getLevel()+1)) * 0.75), ':uid' => $_SESSION['UID']));
            }
        }
    }
    
    public function bombGround($ground, $price, $success = FALSE)
    {
        if(isset($_SESSION['UID']))
        {
            if(is_object($ground) && $ground->getStateID() > 0 && $ground->getGID() > 0)
            {
                global $userData;
                $waitingTime = 900;
                $donatorService = new DonatorService();
                $waitingTime = $donatorService->adjustWaitingTime($waitingTime, $userData->getDonatorID(), $userData->getCHalvingTimes());
                if($success == TRUE)
                {
                    $this->con->setData("
                        UPDATE `ground`
                        SET `building1`= '0', `building2`= '0', `building3`= '0', `building4`= '0', `building5`= '0',
                            `cBuilding1`='0', `cBuilding2`='0', `cBuilding3`='0', `cBuilding4`='0', `cBuilding5`='0', `userID`= :uid
                        WHERE `userID`= :oid AND `stateID`= :sid AND `gID`= :gID AND `id`= :id AND `active`='1' AND `deleted`='0';
                        UPDATE `user` `rankpoints`=`rankpoints`+'2' WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                    ", array(':uid' => $_SESSION['UID'], ':oid' => $ground->getUserID(), ':sid' => $ground->getStateID(), ':gID' => $ground->getGID(), ':id' => $ground->getId()));
                }
                $this->con->setData("
                    UPDATE `user` SET `cash`=`cash`- :price, `cBombardement`= :time WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(':price' => $price, ':time' => time() + $waitingTime, ':uid' => $_SESSION['UID']));
            }
        }
    }
}
