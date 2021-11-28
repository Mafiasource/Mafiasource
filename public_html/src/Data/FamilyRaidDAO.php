<?PHP

namespace src\Data;

use src\Business\FamilyRaidService;
use src\Business\DonatorService;
use src\Data\config\DBConfig;
use src\Entities\FamilyRaid;

class FamilyRaidDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    
    private $familyID;
    
    public function __construct()
    {
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
        
        global $userData;
        $this->familyID = $userData->getFamilyID();
    }
    
    public function __destruct()
    {
        $this->dbh = null;
        $this->con = null;
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `family_raid` WHERE `familyID`= :fid AND `active` = '1' AND `deleted` = '0'");
            $statement->execute(array(':fid' => $this->familyID));
            $row = $statement->fetch();
            return $row['total'];
        }
    }
    
    public function getFamilyRaidMemberList()
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $rows = $this->con->getData("
                SELECT `id`, `username`, `lastclick` FROM `user` WHERE `familyID`= :fid AND `id`!= :uid AND `cFamilyRaid`< :time AND `active`='1' AND `deleted`='0'
            ", array(':fid' => $this->familyID, ':uid' => $_SESSION['UID'], ':time' => time()));
            
            $list = array();
            foreach($rows AS $row)
            {
                $rowInRaid = $this->con->getDataSR("
                    SELECT `id` FROM `family_raid` WHERE `leaderID`= :uid OR `driverID`= :uid OR `bombExpertID`= :uid OR `weaponExpertID`= :uid LIMIT 1
                ", array(':uid' => $row['id']));
                if(!$rowInRaid)
                {
                    $online = false;
                    if($row['lastclick'] > (time() - 300))
                        $online = true;
                    
                    array_push($list, array('username' => $row['username'], 'online' => $online));
                }
            }
            return $list;
        }
    }
    
    public function getActiveFamilyRaid()
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $row = $this->con->getDataSR("
                SELECT fr.`id`, fr.`stateID`, fr.`driverReady`, fr.`bombExpertReady`, fr.`weaponExpertReady`, fr.`garageID`,
                    fr.`bombType`, fr.`weaponType`, fr.`bullets`, l.`username` AS `leader`, d.`username` AS `driver`,
                    be.`username` AS `bombExpert`, we.`username` AS `weaponExpert`, v.`name` AS `vehicleName`,
                    fr.`leaderID`, fr.`driverID`, fr.`bombExpertID`, fr.`weaponExpertID`
                FROM `family_raid` AS fr
                LEFT JOIN `user` AS l
                ON (fr.`leaderID`=l.`id`)
                LEFT JOIN `user` AS d
                ON (fr.`driverID`=d.`id`)
                LEFT JOIN `user` AS be
                ON (fr.`bombExpertID`=be.`id`)
                LEFT JOIN `user` AS we
                ON (fr.`weaponExpertID`=we.`id`)
                LEFT JOIN `garage` AS g
                ON (fr.`garageID`=g.`id`)
                LEFT JOIN `vehicle` AS v
                ON (g.`vehicleID`=v.`id`)
                WHERE fr.`familyID`= :fid AND (fr.`leaderID`= :uid  OR fr.`driverID`= :uid OR fr.`bombExpertID`= :uid OR fr.`weaponExpertID`= :uid)
                    AND fr.`active`='1' AND fr.`deleted`='0'
            ", array(':fid' => $this->familyID, ':uid' => $_SESSION['UID']));
            if(isset($row['id']) && $row['id'] > 0)
            {
                global $language;
                $l = $language->familyRaidLangs();
                $famRaidService = new FamilyRaidService();
                $famRaid = new FamilyRaid();
                $famRaid->setId($row['id']);
                $famRaid->setStateID($row['stateID']);
                $famRaid->setLeader($row['leader']);
                $famRaid->setLeaderID($row['leaderID']);
                $famRaid->setDriverID($row['driverID']);
                $famRaid->setDriver($row['driver']);
                $famRaid->setBombExpertID($row['bombExpertID']);
                $famRaid->setBombExpert($row['bombExpert']);
                $famRaid->setWeaponExpertID($row['weaponExpertID']);
                $famRaid->setWeaponExpert($row['weaponExpert']);
                $famRaid->setGarageID($row['garageID']);
                if($famRaid->getGarageID() == -1)
                    $famRaid->setVehicle($l['HIRED_VEHICLE']);
                else
                    $famRaid->setVehicle($row['vehicleName']);
                
                if(array_key_exists($row['bombType'], $famRaidService->bombs))
                {
                    $famRaid->setBombType($row['bombType']);
                    $famRaid->setBomb($famRaidService->bombs[$row['bombType']]);
                }
                if(array_key_exists($row['weaponType'], $famRaidService->weapons))
                {
                    $famRaid->setWeaponType($row['weaponType']);
                    $famRaid->setWeapon($famRaidService->weapons[$row['weaponType']]);
                }
                $famRaid->setBullets($row['bullets']);
                $famRaid->setDriverReady(false);
                if($row['driverReady'] == 1)
                    $famRaid->setDriverReady(true);
                    
                $famRaid->setBombExpertReady(false);
                if($row['bombExpertReady'] == 1)
                    $famRaid->setBombExpertReady(true);
                
                $famRaid->setWeaponExpertReady(false);
                if($row['weaponExpertReady'] == 1)
                    $famRaid->setWeaponExpertReady(true);
                
                return $famRaid;
            }
        }
        return false;
    }
    
    public function userInsideFamilyRaid()
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `family_raid`
                WHERE (`leaderID`= :uid OR `driverID`= :uid OR `bombExpertID`= :uid OR `weaponExpertID`= :uid) AND `familyID`= :fid AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $_SESSION['UID'], ':fid' => $this->familyID));
            
            if(isset($row['id']) && $row['id'] > 0)
                return true;
            else
                return false;
        }
    }
    
    public function userInsideAcceptedFamilyRaid()
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `family_raid`
                WHERE (`leaderID`= :uid OR (`driverID`= :uid AND `driverReady`='1') OR (`bombExpertID`= :uid AND `bombExpertReady`='1') OR (`weaponExpertID`= :uid AND `weaponExpertReady`='1')) AND `familyID`= :fid AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $_SESSION['UID'], ':fid' => $this->familyID));
            
            if(isset($row['id']) && $row['id'] > 0)
                return true;
            else
                return false;
        }
    }
    
    public function organizeFamilyRaid($sid, $did, $bid, $wid)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                INSERT INTO `family_raid`
                (`stateID`, `familyID`, `leaderID`, `driverID`, `bombExpertID`, `weaponExpertID`)
                VALUES
                (:sid, :fid, :lid, :did, :bid, :wid)
            ", array(':sid' => $sid, ':fid' => $this->familyID, ':lid' => $_SESSION['UID'], ':did' => $did, ':bid' => $bid, ':wid' => $wid));
        }
    }
    
    public function driverAcceptFamilyRaid($frid, $gid)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            if($gid == -1)
            {
                $this->con->setData("UPDATE `user` SET `cash`=`cash`-'15000' WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':uid' => $_SESSION['UID']));
            }
            $this->con->setData("
                UPDATE `family_raid` SET `driverReady`=1, `garageID`= :gid WHERE `id`= :frid AND `driverID`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':gid' => $gid, ':frid' => $frid, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function driverQuitFamilyRaid($frid)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                UPDATE `family_raid` SET `driverID`=0, `driverReady`=0, `garageID`=NULL WHERE `id`= :frid AND `driverID`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':frid' => $frid, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function bombExpertAcceptFamilyRaid($frid, $bid)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $familyRaidService = new FamilyRaidService();
            $this->con->setData("
                UPDATE `family_raid` SET `bombExpertReady`=1, `bombType`= :bid WHERE `id`= :frid AND `bombExpertID`= :uid AND `active`='1' AND `deleted`='0';
                UPDATE `user` SET `cash`=`cash`- :price WHERE `id`=:uid AND `active`='1' AND `deleted`='0'
            ", array(':bid' => $bid, ':frid' => $frid, ':uid' => $_SESSION['UID'], ':price' => $familyRaidService->bombs[$bid]['price']));
        }
    }
    
    public function bombExpertQuitFamilyRaid($frid)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                UPDATE `family_raid` SET `bombExpertID`=0, `bombExpertReady`=0, `bombType`=NULL WHERE `id`= :frid AND `bombExpertID`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':frid' => $frid, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function weaponExpertAcceptFamilyRaid($frid, $wid, $bullets)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $familyRaidService = new FamilyRaidService();
            $price = (int)$familyRaidService->weapons[$wid]['price'];
            $price += (int)$bullets*50;
            $this->con->setData("
                UPDATE `family_raid` SET `weaponExpertReady`=1, `weaponType`= :wid, `bullets`= :bullets WHERE `id`= :frid AND `weaponExpertID`= :uid AND `active`='1' AND `deleted`='0';
                UPDATE `user` SET `cash`=`cash`- :price WHERE `id`=:uid AND `active`='1' AND `deleted`='0'
            ", array(':wid' => $wid, ':bullets' => $bullets, ':frid' => $frid, ':uid' => $_SESSION['UID'], ':price' => $price));
        }
    }
    
    public function weaponExpertQuitFamilyRaid($frid)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                UPDATE `family_raid` SET `weaponExpertID`=0, `weaponExpertReady`=0, `weaponType`=NULL WHERE `id`= :frid AND `weaponExpertID`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':frid' => $frid, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function changeFamilyRaidDriver($famRaidID, $driverID)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                UPDATE `family_raid` SET `driverID`= :did, `driverReady`='0', `garageID`='0' WHERE `id`= :frid AND `leaderID`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':did' => $driverID, ':frid' => $famRaidID, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function changeFamilyRaidBombExpert($famRaidID, $bombExpertID)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                UPDATE `family_raid` SET `bombExpertID`= :beid, `bombExpertReady`='0', `bombType`='0' WHERE `id`= :frid AND `leaderID`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':beid' => $bombExpertID, ':frid' => $famRaidID, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function changeFamilyRaidWeaponExpert($famRaidID, $weaponExpertID)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                UPDATE `family_raid` SET `weaponExpertID`= :weid, `weaponExpertReady`='0', `weaponType`='0', `bullets`='0' WHERE `id`= :frid AND `leaderID`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':weid' => $weaponExpertID, ':frid' => $famRaidID, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function quitFamilyRaid($famRaidID)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                DELETE FROM `family_raid` WHERE `id`= :frid AND `leaderID`= :uid
            ", array(':frid' => $famRaidID, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function startFamilyRaidSuccess($familyRaid, $priceEach)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $params = array(
                ':priceEach' => $priceEach, ':uid' => $_SESSION['UID'], ':fid' => $this->familyID, ':did' => $familyRaid->getDriverID(),
                ':bid' => $familyRaid->getBombExpertID(), ':wid' => $familyRaid->getWeaponExpertID(), ':frid' => $familyRaid->getId()
            );
            $waitingTimes = $this->getWaitingTimes($familyRaid);
            $params[':timeL'] =  (time() + $waitingTimes['L']);
            $params[':timeD'] =  (time() + $waitingTimes['D']);
            $params[':timeB'] =  (time() + $waitingTimes['B']);
            $params[':timeW'] =  (time() + $waitingTimes['W']);
            $this->con->setData("
                UPDATE `user` SET `bank`=`bank`+ :priceEach, `rankpoints`=`rankpoints`+'3', `cFamilyRaid`= :timeL WHERE `id`= :uid AND `familyID`= :fid AND `active`='1' AND `deleted`='0';
                UPDATE `user` SET `bank`=`bank`+ :priceEach, `rankpoints`=`rankpoints`+'3', `cFamilyRaid`= :timeD WHERE `id`= :did AND `familyID`= :fid AND `active`='1' AND `deleted`='0';
                UPDATE `user` SET `bank`=`bank`+ :priceEach, `rankpoints`=`rankpoints`+'3', `cFamilyRaid`= :timeB WHERE `id`= :bid AND `familyID`= :fid AND `active`='1' AND `deleted`='0';
                UPDATE `user` SET `bank`=`bank`+ :priceEach, `rankpoints`=`rankpoints`+'3', `cFamilyRaid`= :timeW WHERE `id`= :wid AND `familyID`= :fid AND `active`='1' AND `deleted`='0';
                DELETE FROM `family_raid` WHERE `id`= :frid AND `leaderID`= :uid
            ", $params);
        }
    }
    
    public function startFamilyRaidFail($familyRaid)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $params = array(
                ':uid' => $_SESSION['UID'], ':fid' => $this->familyID, ':did' => $familyRaid->getDriverID(),
                ':bid' => $familyRaid->getBombExpertID(), ':wid' => $familyRaid->getWeaponExpertID(), ':frid' => $familyRaid->getId()
            );
            $waitingTimes = $this->getWaitingTimes($familyRaid);
            $params[':timeL'] =  (time() + $waitingTimes['L']);
            $params[':timeD'] =  (time() + $waitingTimes['D']);
            $params[':timeB'] =  (time() + $waitingTimes['B']);
            $params[':timeW'] =  (time() + $waitingTimes['W']);
            $this->con->setData("
                UPDATE `user` SET `cFamilyRaid`= :timeL WHERE `id`= :uid AND `familyID`= :fid AND `active`='1' AND `deleted`='0';
                UPDATE `user` SET `cFamilyRaid`= :timeD WHERE `id`= :did AND `familyID`= :fid AND `active`='1' AND `deleted`='0';
                UPDATE `user` SET `cFamilyRaid`= :timeB WHERE `id`= :bid AND `familyID`= :fid AND `active`='1' AND `deleted`='0';
                UPDATE `user` SET `cFamilyRaid`= :timeW WHERE `id`= :wid AND `familyID`= :fid AND `active`='1' AND `deleted`='0';
                DELETE FROM `family_raid` WHERE `id`= :frid AND `leaderID`= :uid
            ", $params);
        }
    }
    
    public function getWaitingTimes($familyRaid)
    {
        $waitingTime = 720;
        $donatorService = new DonatorService();
        $participantQry  = "SELECT `donatorID`, `cHalvingTimes` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1";
        $dL = $this->con->getDataSR($participantQry, array(':uid' => $familyRaid->getLeaderID()));
        $dD = $this->con->getDataSR($participantQry, array(':uid' => $familyRaid->getDriverID()));
        $dB = $this->con->getDataSR($participantQry, array(':uid' => $familyRaid->getBombExpertID()));
        $dW = $this->con->getDataSR($participantQry, array(':uid' => $familyRaid->getWeaponExpertID()));
        $waitingTimeL = $donatorService->adjustWaitingTime($waitingTime, $dL['donatorID'], $dL['cHalvingTimes']);
        $waitingTimeD = $donatorService->adjustWaitingTime($waitingTime, $dD['donatorID'], $dD['cHalvingTimes']);
        $waitingTimeB = $donatorService->adjustWaitingTime($waitingTime, $dB['donatorID'], $dB['cHalvingTimes']);
        $waitingTimeW = $donatorService->adjustWaitingTime($waitingTime, $dW['donatorID'], $dW['cHalvingTimes']);
        
        return array('L' => $waitingTimeL, 'D' => $waitingTimeD, 'B' => $waitingTimeB, 'W' => $waitingTimeW);
    }
}
