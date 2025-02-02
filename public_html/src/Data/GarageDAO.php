<?PHP

namespace src\Data;

use src\Business\GarageService;
use src\Data\config\DBConfig;
use src\Data\StateDAO;
use src\Data\PossessionDAO;
use src\Entities\Family;
use src\Entities\Garage;
use src\Entities\UserGarage;
use src\Entities\FamilyGarage;
use src\Entities\Vehicle;

class GarageDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $garageQry = "SELECT `id` FROM `garage` WHERE `userGarageID` = :gid AND `active`='1' AND `deleted`='0'";
    private $garageIdByStateQry = "SELECT `id` FROM `user_garage` WHERE `userID`= :uid AND `stateID`= :stateID AND `active`='1' AND `deleted`='0' LIMIT 1";
    private $garageSizeByStateQry = "SELECT `id`, `size` FROM `user_garage` WHERE `userID`= :uid AND `stateID`= :stateID AND `active`='1' AND `deleted`='0' LIMIT 1";
    private $familyGarageQry = "SELECT `id` FROM `garage` WHERE `famGarageID` = :gid AND `active`='1' AND `deleted`='0'";
    private $familyGarageIdQry = "SELECT `id` FROM `family_garage` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1";
    private $familyGarageSizeQry = "SELECT `id`, `size` FROM `family_garage` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1";
    private $userPlusCashQry = "UPDATE `user` SET `cash`=`cash`+ :val WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1";
    private $userMinusCashQry = "UPDATE `user` SET `cash`=`cash`- :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1";
    
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
            global $userData;
            global $route;
            $rn = $route->getRouteName();
            $famID = $userData->getFamilyID();
            
            $ugRow = $this->con->getDataSR($this->garageIdByStateQry, array(':uid' => $_SESSION['UID'], ':stateID' => $userData->getStateID()));
            
            if($famID > 0)
                $fgRow = $this->con->getDataSR($this->familyGarageIdQry, array(':fid' => $famID));
            
            if(isset($ugRow['id']) && $ugRow['id'] > 0 && ($rn == 'garage' || $rn == 'garage-page'))
                $row = $this->con->getDataSR("
                    SELECT COUNT(`id`) AS `total` FROM `garage` WHERE `userGarageID`= :gid AND `active`='1' AND `deleted`='0'
                ", array(':gid' => $ugRow['id']));
            
            if(isset($fgRow['id']) && $fgRow['id'] > 0 && (($rn == 'family-garage' || $rn == 'family-garage-page' || $rn == 'family-crimes') && $famID > 0))
                $row = $this->con->getDataSR("
                    SELECT COUNT(`id`) AS `total` FROM `garage` WHERE `famGarageID`= :gid AND `active`='1' AND `deleted`='0'
                ", array(':gid' => $fgRow['id']));
            
            if($rn == 'garage-shop' || $rn == 'garage-shop-page')
                $row = $this->con->getDataSR("SELECT COUNT(`id`) AS `total` FROM `vehicle` WHERE `stealLv` <= '100' AND `active`='1' AND `deleted`='0'");
            
            if(isset($row['total'])) return $row['total'];
        }
        return false;
    }
    
    public function hasGarageInState($stateID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare($this->garageIdByStateQry);
            $statement->execute(array(':uid' => $_SESSION['UID'], ':stateID' => $stateID));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return TRUE;
        }
        return FALSE;
    }
    
    public function hasFamilyGarage($famID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare($this->familyGarageIdQry);
            $statement->execute(array(':fid' => $famID));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return TRUE;
        }
        return FALSE;
    }
    
    public function getGarageSizeByState($stateID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare($this->garageSizeByStateQry);
            $statement->execute(array(':uid' => $_SESSION['UID'], ':stateID' => $stateID));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return $row['size'];
        }
        return FALSE;
    }
    
    public function getFamilyGarageSize($famID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare($this->familyGarageSizeQry);
            $statement->execute(array(':fid' => $famID));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return $row['size'];
        }
        return FALSE;
    }
    
    public function getStateIDByGarageID($id)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `stateID` FROM `user_garage` WHERE `id` = :id", array(':id' => $id));
            if(isset($row['stateID']) > 0)
            {
                return $row['stateID'];
            }
        }
        return false;
    }
    
    private function getSpaceByGarageOption($size)
    {
        if(isset($_SESSION['UID']))
        {
            $garageService = new GarageService();
            switch($size)
            {
                case 'small':
                    $totalSpace = $garageService->garageOptions['small']['space'];
                    break;
                case 'medium':
                    $totalSpace = $garageService->garageOptions['medium']['space'];
                    break;
                case 'large':
                    $totalSpace = $garageService->garageOptions['large']['space'];
                    break;
                case 'extra-large':
                    $totalSpace = $garageService->garageOptions['extra-large']['space'];
                    break;
                default:
                    $totalSpace = 5;
                    break;
            }
            return $totalSpace;
        }
    }
    
    public function hasSpaceLeftInGarage($stateID, $returnOccupied = false)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare($this->garageSizeByStateQry);
            $statement->execute(array(':uid' => $_SESSION['UID'], ':stateID' => $stateID));
            $row = $statement->fetch();
            
            $gid = isset($row['id']) ? $row['id'] : null;
            $size = isset($row['size']) ? $row['size'] : null;
            
            if($gid && $size)
            {
                $sVal = $this->getSpaceByGarageOption($size);
                
                $statement = $this->dbh->prepare($this->garageQry);
                $statement->execute(array(':gid' => $gid));
                $num = $statement->rowCount();
                if($num >= $sVal)
                {
                    if($returnOccupied)
                        return (int)$num;
                    
                    return FALSE;
                }
                else
                {
                    if($returnOccupied)
                        return (int)$num;
                    
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    private function getFamilySpaceByGarageOption($size)
    {
        if(isset($_SESSION['UID']))
        {
            $garageService = new GarageService();
            switch($size)
            {
                case 'small':
                    $totalSpace = $garageService->familyGarageOptions['small']['space'];
                    break;
                case 'medium':
                    $totalSpace = $garageService->familyGarageOptions['medium']['space'];
                    break;
                case 'large':
                    $totalSpace = $garageService->familyGarageOptions['large']['space'];
                    break;
                case 'extra-large':
                    $totalSpace = $garageService->familyGarageOptions['extra-large']['space'];
                    break;
                default:
                    $totalSpace = 15;
                    break;
            }
            return $totalSpace;
        }
    }
    
    public function hasSpaceLeftInFamilyGarage($famID, $returnOccupied = false)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare($this->familyGarageSizeQry);
            $statement->execute(array(':fid' => $famID));
            $row = $statement->fetch();
            
            $gid = isset($row['id']) ? $row['id'] : null;
            $size = isset($row['size']) ? $row['size'] : null;
            
            if($gid && $size)
            {
                $sVal = $this->getFamilySpaceByGarageOption($size);
                
                $statement = $this->dbh->prepare($this->familyGarageQry);
                $statement->execute(array(':gid' => $gid));
                $num = $statement->rowCount();
                if($num >= $sVal)
                {
                    if($returnOccupied)
                        return (int)$num;
                    
                    return FALSE;
                }
                else
                {
                    if($returnOccupied)
                        return (int)$num;
                    
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    public function spaceLeftInGarage($stateID, $maxSpace)
    {
        $occupied = $this->hasSpaceLeftInGarage($stateID, true);
        if($occupied >= 0)
            return $maxSpace - $occupied;
        
        return 0;
    }
    
    public function spaceLeftInFamilyGarage($famID, $maxSpace)
    {
        $occupied = $this->hasSpaceLeftInFamilyGarage($famID, true);
        if($occupied >= 0)
            return $maxSpace - $occupied;
        
        return 0;
    }
    
    public function getGaragesWithFreeSpace()
    {
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData("
                SELECT `id`, `stateID`, `size` FROM `user_garage` WHERE `userID`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $_SESSION['UID']));
            if($rows)
            {
                $list = array();
                foreach($rows AS $row)
                {
                    if($this->hasSpaceLeftInGarage($row['stateID']))
                    {
                        $ug = new UserGarage();
                        $ug->setId($row['id']);
                        $ug->setSize($row['size']);
                        $ug->setStateID($row['stateID']);
                        
                        $state = new StateDAO();
                        $ug->setState($state->getStateNameById($row['stateID']));
                        
                        array_push($list,$ug);
                    }
                }
                return $list;
            }
        }
        return FALSE;
    }
    
    public function sellStolenVehicle($value)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare($this->userPlusCashQry);
            $statement->execute(array(':val' => $value, ':uid' => $_SESSION['UID']));
            
            if(isset($_SESSION['steal-vehicles'])) unset($_SESSION['steal-vehicles']);
        }
    }
    
    public function sellStolenFamilyVehicle($familyID, $value)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `family` SET `money`=`money`+ :val WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':val' => $value, ':fid' => $familyID));
        }
    }
    
    public function addVehicleToGarage($svData, $stateID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare($this->garageIdByStateQry);
            $statement->execute(array(':uid' => $_SESSION['UID'], ':stateID' => $stateID));
            $row = $statement->fetch();
            
            $statement = $this->dbh->prepare("INSERT INTO `garage` (`userGarageID`,`vehicleID`,`damage`) VALUES (:gid,:vid,:dmg)");
            $statement->execute(array(':gid' => $row['id'], ':vid' => $svData['vehicleID'], ':dmg' => $svData['dmg']));
            
            if(isset($_SESSION['steal-vehicles'])) unset($_SESSION['steal-vehicles']);
        }
    }
    
    public function addFamilyVehicleToGarage($vData, $familyID)
    {
        if(isset($_SESSION['UID']) && $familyID > 0)
        {
            $statement = $this->dbh->prepare($this->familyGarageIdQry);
            $statement->execute(array(':fid' => $familyID));
            $row = $statement->fetch();
            
            $statement = $this->dbh->prepare("INSERT INTO `garage` (`famGarageID`,`vehicleID`,`damage`) VALUES (:gid,:vid,:dmg)");
            $statement->execute(array(':gid' => $row['id'], ':vid' => $vData['vehicleID'], ':dmg' => $vData['dmg']));
            return true;
        }
    }
    
    private function applyPossessionProfits($pData, $profit)
    {
        if(isset($_SESSION['UID']))
        {
            /** Possession logic for buying garage | pay owner if exists and not self **/
            if(is_object($pData)) $possOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $possOwner > 0 && $possOwner != $_SESSION['UID'])
            {
                $possessionData = new PossessionDAO();
                $possessionData->applyProfitForOwner($pData, $profit, $possOwner);
            }
        }
    }
    
    public function buyGarageInState($size, $price, $stateID, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("INSERT INTO `user_garage` (`stateID`,`userID`,`size`) VALUES (:stateID, :uid, :size);" . $this->userMinusCashQry, array(
                ':stateID' => $stateID, ':uid' => $_SESSION['UID'], ':size' => $size,
                ':price' => $price, ':uid' => $_SESSION['UID']
            ));
            
            $this->applyPossessionProfits($pData, $price);
        }
    }
    
    public function sellGarageInState($size, $price, $stateID)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `user_garage` SET `deleted`='1' WHERE `stateID`= :stateID AND `userID`= :uid AND `size`= :size;" . $this->userPlusCashQry, array(
                ':stateID' => $stateID, ':uid' => $_SESSION['UID'], ':size' => $size,
                ':val' => $price, ':uid' => $_SESSION['UID']
            ));
        }
    }
    
    public function buyFamilyGarage($famID, $size, $price, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("INSERT INTO `family_garage` (`familyID`,`size`) VALUES (:fid, :size)");
            $statement->execute(array(':fid' => $famID, ':size' => $size));
            
            $statement = $this->dbh->prepare("UPDATE `family` SET `money`=`money`- :price WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':price' => $price, ':fid' => $famID));
            
            $this->applyPossessionProfits($pData, $price);
        }
    }
    
    public function sellFamilyGarage($famID, $size, $value)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `family_garage` SET `deleted`='1' WHERE `familyID`= :fid AND `size`= :size");
            $statement->execute(array(':fid' => $famID, ':size' => $size));
            
            $statement = $this->dbh->prepare("UPDATE `family` SET `money`=`money`+ :val WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':val' => $value, ':fid' => $famID));
        }
    }
    
    public function getVehiclesInGarageByState($stateID, $from, $to)
    {
        if(isset($_SESSION['UID']) && is_int($from) && is_int($to) && $to <= 50 && $to >=1)
        {
            if($this->hasGarageInState($stateID) == TRUE)
            {
                $statement = $this->dbh->prepare($this->garageIdByStateQry);
                $statement->execute(array(':uid' => $_SESSION['UID'], ':stateID' => $stateID));
                $row = $statement->fetch();
            
                $statement = $this->dbh->prepare("
                    SELECT g.`id`, g.`userGarageID`, g.`vehicleID`, g.`damage`, v.`name` AS `vehicleName`, v.`picture` AS `vehiclePicture`, v.`price` AS `vehiclePrice`
                    FROM `garage` AS g
                    LEFT JOIN `vehicle` AS v
                    ON (g.`vehicleID`=v.`id`)
                    WHERE g.`userGarageID` = :gid AND v.`active`='1' AND v.`deleted`='0' AND g.`active`='1' AND g.`deleted`='0'
                    ORDER BY g.`id` ASC LIMIT $from, $to
                ");
                $statement->execute(array(':gid' => $row['id']));
                
                global $userData;
                $list = array();
                foreach($statement AS $g)
                {
                    if($userData->getDonatorID() >= 1)
                        $g['vehiclePrice'] *= 0.95;
                    
                    $g['vehiclePrice'] *= 0.65;
                    $garage = new Garage();
                    $garage->setId($g['id']);
                    $garage->setUserGarageID($g['userGarageID']);
                    $garage->setFamGarageID(0);
                    $garage->setValue((int)((round($g['vehiclePrice']) / 100) * round(100 - $g['damage'])));
                    $garage->setDamage($g['damage']);
                    
                    $vehicle = new Vehicle();
                    $vehicle->setId($g['vehicleID']);
                    $vehicle->setName($g['vehicleName']);
                    $vehicle->setPrice($g['vehiclePrice']);
                    $vehicle->setPicture($g['vehiclePicture']);
                    
                    $garage->setVehicle($vehicle);
                    
                    $diff = round($g['vehiclePrice']) - $garage->getValue();
                    $costs = round($diff * 1.25, 0);
                    $costs = $costs > 0 ? $costs : 0;
                    
                    $garage->setRepairCosts($costs);
                    
                    array_push($list,$garage);
                }
                return $list;
            }
        }
    }
    
    public function getVehiclesInFamilyGarage($famID, $from, $to)
    {
        if(isset($_SESSION['UID']) && is_int($from) && is_int($to) && $to <= 50 && $to >=1)
        {
            if($this->hasFamilyGarage($famID) == TRUE)
            {
                $statement = $this->dbh->prepare($this->familyGarageIdQry);
                $statement->execute(array(':fid' => $famID));
                $row = $statement->fetch();
            
                $statement = $this->dbh->prepare("
                    SELECT g.`id`, g.`famGarageID`, g.`vehicleID`, g.`damage`, v.`name` AS `vehicleName`, v.`picture` AS `vehiclePicture`, v.`price` AS `vehiclePrice`
                    FROM `garage` AS g
                    LEFT JOIN `vehicle` AS v
                    ON (g.`vehicleID`=v.`id`)
                    WHERE g.`famGarageID` = :gid AND v.`active`='1' AND v.`deleted`='0' AND g.`active`='1' AND g.`deleted`='0'
                    ORDER BY g.`id` ASC LIMIT $from, $to
                ");
                $statement->execute(array(':gid' => $row['id']));
                
                $list = array();
                foreach($statement AS $g)
                {
                    $garage = new Garage();
                    $garage->setId($g['id']);
                    $garage->setUserGarageID(0);
                    $garage->setFamGarageID($g['famGarageID']);
                    $garage->setValue((int)((round($g['vehiclePrice']) / 100) * round(100 - $g['damage'])));
                    $garage->setDamage($g['damage']);
                    
                    $vehicle = new Vehicle();
                    $vehicle->setId($g['vehicleID']);
                    $vehicle->setName($g['vehicleName']);
                    $vehicle->setPrice($g['vehiclePrice']);
                    
                    $garage->setVehicle($vehicle);
                    
                    array_push($list,$garage);
                }
                return $list;
            }
        }
    }
    
    private function setGarageTuneInfo($garage, $g)
    {
        $garage->setTires($g['tires']);
        $garage->setEngine($g['engine']);
        $garage->setExhaust($g['exhaust']);
        $garage->setShockAbsorbers($g['shockAbsorbers']);
        
        return $garage;
    }
    
    private function setVehicleTuneInfo($vehicle, $g)
    {
        $garageService = new GarageService();
        $hp = $g['horsepower'];
        $ts = $g['topspeed'];
        $ac = $g['acceleration'];
        $ct = $g['control'];
        $br = $g['breaking'];
        
        foreach(array_keys($garageService->tuneShop) AS $tune)
        {
            $tuneDb = GarageService::getTuneDbField($tune);
            $hp += $garageService->tuneShop[$tune][$g[$tuneDb]]['pk'];
            $ts += $garageService->tuneShop[$tune][$g[$tuneDb]]['ts'];
            $ac += $garageService->tuneShop[$tune][$g[$tuneDb]]['ac'];
            $ct += $garageService->tuneShop[$tune][$g[$tuneDb]]['ct'];
            $br += $garageService->tuneShop[$tune][$g[$tuneDb]]['br'];
        }
        $vehicle->setHorsepower($hp);
        $vehicle->setTopspeed($ts);
        $vehicle->setAcceleration($ac);
        $vehicle->setControl($ct);
        $vehicle->setBreaking($br);
        
        return $vehicle;
    }
    
    public function getAllVehiclesInGarageByState($stateID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare($this->garageIdByStateQry);
            $statement->execute(array(':uid' => $_SESSION['UID'], ':stateID' => $stateID));
            $row = $statement->fetch();
            if(isset($row['id']))
            {
                $statement = $this->dbh->prepare("
                    SELECT g.`id`, g.`userGarageID`, g.`vehicleID`, v.`name` AS `vehicleName`, v.`horsepower`, v.`topspeed`, v.`acceleration`, v.`control`, v.`breaking`,
                        g.`tires`, g.`engine`, g.`exhaust`, g.`shockAbsorbers`
                    FROM `garage` AS g
                    LEFT JOIN `vehicle` AS v
                    ON (g.`vehicleID`=v.`id`)
                    WHERE g.`userGarageID` = :gid AND v.`active`='1' AND v.`deleted`='0' AND g.`active`='1' AND g.`deleted`='0'
                    ORDER BY g.`id` ASC
                ");
                $statement->execute(array(':gid' => $row['id']));
                
                $list = array();
                foreach($statement AS $g)
                {
                    $garage = new Garage();
                    $garage->setId($g['id']);
                    $garage->setUserGarageID($g['userGarageID']);
                    $garage->setFamGarageID(0);
                    $garage = $this->setGarageTuneInfo($garage, $g);
                    
                    $vehicle = new Vehicle();
                    $vehicle->setId($g['vehicleID']);
                    $vehicle->setName($g['vehicleName']);
                    $vehicle = $this->setVehicleTuneInfo($vehicle, $g);
                    
                    $garage->setVehicle($vehicle);
                    
                    array_push($list,$garage);
                }
                return $list;
            }
        }
        return false;
    }
    
    public function moveVehicleToGarageInState($vehicleID, $stateID)
    {
        if(isset($_SESSION['UID']))
        {
            $vehicle = $this->con->getDataSR("SELECT `vehicleID`, `damage` FROM `garage` WHERE `id`= :id AND `active`='1' AND `deleted`='0' LIMIT 1", array(':id' => $vehicleID));
            $garage = $this->con->getDataSR($this->garageIdByStateQry, array(':uid' => $_SESSION['UID'], ':stateID' => $stateID));
            if(isset($garage['id']) && $garage['id'] > 0) // Garage space check already done in business
            {
                $this->con->setData("DELETE FROM `garage` WHERE `id`= :id ", array(':id' => $vehicleID));
                $this->con->setData("
                    INSERT INTO `garage` (`userGarageID`, `vehicleID`, `damage`) VALUES (:fgid, :vid, :dmg)
                ", array(':fgid' => $garage['id'], ':vid' => $vehicle['vehicleID'], ':dmg' => $vehicle['damage']));
                return true;
            }
        }
    }
    
    public function userOwnsVehicle($id)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT ug.`userID` FROM `garage` AS g LEFT JOIN `user_garage` AS ug ON (g.`userGarageID`=ug.`id`) WHERE g.`id`= :id");
            $statement->execute(array(':id' => $id));
            $row = $statement->fetch();
            
            if(isset($row['userID']) && $row['userID'] == $_SESSION['UID'])
                return TRUE;
        }
        return FALSE;
    }
    
    public function familyOwnsVehicle($id, $famID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT fg.`familyID` FROM `garage` AS g LEFT JOIN `family_garage` AS fg ON (g.`famGarageID`=fg.`id`) WHERE g.`id`= :id");
            $statement->execute(array(':id' => $id));
            $row = $statement->fetch();
            
            if(isset($row['familyID']) && $row['familyID'] == $famID)
                return TRUE;
        }
        return FALSE;
    }
    
    public function isVehicleInGarageInState($stateID, $id)
    {
        if(isset($_SESSION['UID']))
        {
            if($this->hasGarageInState($stateID) == TRUE)
            {
                $statement = $this->dbh->prepare($this->garageIdByStateQry);
                $statement->execute(array(':uid' => $_SESSION['UID'], ':stateID' => $stateID));
                $row = $statement->fetch();
                
                $statement = $this->dbh->prepare("
                    SELECT `id`
                    FROM `garage`
                    WHERE `userGarageID` = :gid AND `id`= :id ");
                $statement->execute(array(':gid' => $row['id'], ':id' => $id));
                
                $row2 = $statement->fetch();
                
                if($row2 !== NULL && is_array($row2) && $row2['id'] == $id)
                    return TRUE;
            }
        }
        return FALSE;
    }
    
    public function isVehicleInFamilyGarage($id, $famID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare($this->familyGarageIdQry);
            $statement->execute(array(':fid' => $famID));
            $row = $statement->fetch();
            
            $statement = $this->dbh->prepare("
                SELECT `id`
                FROM `garage`
                WHERE `famGarageID` = :gid AND `id`= :id ");
            $statement->execute(array(':gid' => $row['id'], ':id' => $id));
            
            $row2 = $statement->fetch();
            
            if($row2 !== NULL && is_array($row2) && $row2['id'] == $id)
                return TRUE;
        }
        return FALSE;
    }
    
    public function getVehicleInGarageById($id)
    {
        if(isset($_SESSION['UID']))
        {
            if($this->userOwnsVehicle($id))
            {
                $statement = $this->dbh->prepare("
                    SELECT g.`id`, g.`userGarageID`, g.`vehicleID`, g.`damage`, v.`name` AS `vehicleName`, v.`picture` AS `vehiclePicture`, v.`price` AS `vehiclePrice`,
                        v.`horsepower`, v.`topspeed`, v.`acceleration`, v.`control`, v.`breaking`, g.`tires`, g.`engine`, g.`exhaust`, g.`shockAbsorbers`
                    FROM `garage` AS g
                    LEFT JOIN `vehicle` AS v
                    ON (g.`vehicleID`=v.`id`)
                    WHERE g.`id` = :id AND v.`active`='1' AND v.`deleted`='0'");
                $statement->execute(array(':id' => $id));
                
                global $userData;
                $g = $statement->fetch();
                if(!empty($g))
                {
                    if($userData->getDonatorID() >= 1)
                        $g['vehiclePrice'] *= 0.95;
                    
                    $g['vehiclePrice'] *= 0.65;
                    $garage = new Garage();
                    $garage->setId($g['id']);
                    $garage->setUserGarageID($g['userGarageID']);
                    $garage->setFamGarageID(0);
                    $garage->setValue((int)((round($g['vehiclePrice']) / 100) * round(100 - $g['damage'])));
                    $garage->setDamage($g['damage']);
                    $garage = $this->setGarageTuneInfo($garage, $g);
                    
                    $vehicle = new Vehicle();
                    $vehicle->setId($g['vehicleID']);
                    $vehicle->setName($g['vehicleName']);
                    $vehicle->setPrice($g['vehiclePrice']);
                    $vehicle->setPicture($g['vehiclePicture']);
                    $vehicle = $this->setVehicleTuneInfo($vehicle, $g);
                    
                    $garage->setVehicle($vehicle);
                    
                    $diff = round($g['vehiclePrice']) - $garage->getValue();
                    $costs = round($diff * 1.25, 0);
                    $costs = $costs > 0 ? $costs : 0;
                    
                    $garage->setRepairCosts($costs);
                        
                    return $garage;
                }
            }
        }
    }
    
    public function getFamilyVehicleById($id, $famID)
    {
        if(isset($_SESSION['UID']))
        {
            if($this->familyOwnsVehicle($id, $famID))
            {
                $statement = $this->dbh->prepare("
                    SELECT g.`id`, g.`famGarageID`, g.`vehicleID`, g.`damage`, v.`name` AS `vehicleName`, v.`picture` AS `vehiclePicture`, v.`price` AS `vehiclePrice`
                    FROM `garage` AS g
                    LEFT JOIN `vehicle` AS v
                    ON (g.`vehicleID`=v.`id`)
                    WHERE g.`id` = :id AND v.`active`='1' AND v.`deleted`='0'");
                $statement->execute(array(':id' => $id));
                
                $g = $statement->fetch();
                if(!empty($g))
                {
                    $garage = new Garage();
                    $garage->setId($g['id']);
                    $garage->setUserGarageID(0);
                    $garage->setFamGarageID($g['famGarageID']);
                    $garage->setValue((int)((round($g['vehiclePrice']) / 100) * round(100 - $g['damage'])));
                    $garage->setDamage($g['damage']);
                    
                    $vehicle = new Vehicle();
                    $vehicle->setId($g['vehicleID']);
                    $vehicle->setName($g['vehicleName']);
                    $vehicle->setPrice($g['vehiclePrice']);
                    $vehicle->setPicture($g['vehiclePicture']);
                    
                    $garage->setVehicle($vehicle);
                    
                    return $garage;
                }
            }
        }
    }
    
    public function repairVehicle($vData, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            if($this->userOwnsVehicle($vData->getId()))
            {
                $profitOwner = $vData->getRepairCosts();
                
                $this->con->setData("UPDATE `garage` SET `damage`='0' WHERE `id` = :vid;" . $this->userMinusCashQry, array(
                    ':vid' => $vData->getId(),
                    ':price' => $vData->getRepairCosts(), ':uid' => $_SESSION['UID']
                ));
                
                $this->applyPossessionProfits($pData, $profitOwner);
            }
        }
    }
    
    public function sellVehicle($vData)
    {
        if(isset($_SESSION['UID']))
        {
            if($this->userOwnsVehicle($vData->getId()))
            {
                $this->con->setData("DELETE FROM `garage` WHERE `id` = :vid;" . $this->userPlusCashQry, array(
                    ':vid' => $vData->getId(),
                    ':val' => $vData->getValue(), ':uid' => $_SESSION['UID']
                ));
            }
        }
    }
    
    public function getVehiclesInShop($from, $to)
    {
        if(isset($_SESSION['UID']) && is_int($from) && is_int($to) && $to <= 50 && $to >=1)
        {
            $rows = $this->con->getData("
                SELECT `id`, `name` AS `vehicleName`, `picture` AS `vehiclePicture`, `price` AS `vehiclePrice`
                FROM `vehicle`
                WHERE `active`='1' AND `deleted`='0' AND `stealLv` <= '100'
                ORDER BY `position` ASC LIMIT $from, $to
            ");
            
            global $userData;
            $list = array();
            foreach($rows AS $v)
            {
                if($userData->getDonatorID() >= 1)
                        $v['vehiclePrice'] *= 0.95;
                
                $vehicle = new Vehicle();
                $vehicle->setId($v['id']);
                $vehicle->setName($v['vehicleName']);
                $vehicle->setPicture($v['vehiclePicture']);
                $vehicle->setPrice($v['vehiclePrice']);
                
                array_push($list,$vehicle);
            }
            return $list;
        }
    }
    
    public function getShopVehicleById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $v = $this->con->getDataSR("
                SELECT `id`, `name` AS `vehicleName`, `picture` AS `vehiclePicture`, `price` AS `vehiclePrice`
                FROM `vehicle`
                WHERE `id` = :id AND `active`='1' AND `deleted`='0' AND `stealLv` <= '100'
            ",array(':id' => $id));
            
            if(!empty($v))
            {
                global $userData;
                if($userData->getDonatorID() >= 1)
                        $v['vehiclePrice'] *= 0.95;
                
                $vehicle = new Vehicle();
                $vehicle->setId($v['id']);
                $vehicle->setName($v['vehicleName']);
                $vehicle->setPicture($v['vehiclePicture']);
                $vehicle->setPrice($v['vehiclePrice']);
                
                return $vehicle;
            }
        }
    }
    
    public function getShopVehicleInfo($id)
    {
        if(isset($_SESSION['UID']))
        {
            $v = $this->con->getDataSR("
                SELECT `id`, `name` AS `vehicleName`, `picture` AS `vehiclePicture`, `price` AS `vehiclePrice`,
                    `horsepower`, `topspeed`, `acceleration`, `control`, `breaking`, `stealLv`
                FROM `vehicle`
                WHERE `id` = :id AND `active`='1' AND `deleted`='0' AND `stealLv` <= '100'
            ",array(':id' => $id));
            
            if(!empty($v))
            {
                global $userData;
                if($userData->getDonatorID() >= 1)
                        $v['vehiclePrice'] *= 0.95;
                
                $vehicle = new Vehicle();
                $vehicle->setId($v['id']);
                $vehicle->setName($v['vehicleName']);
                $vehicle->setPicture($v['vehiclePicture']);
                $vehicle->setPrice($v['vehiclePrice']);
                $vehicle->setHorsepower($v['horsepower']);
                $vehicle->setTopspeed($v['topspeed']);
                $vehicle->setAcceleration($v['acceleration']);
                $vehicle->setControl($v['control']);
                $vehicle->setBreaking($v['breaking']);
                $vehicle->setStealLv($v['stealLv']);
                
                return $vehicle;
            }
        }
    }
    
    public function buyVehicle($vehicleData, $garageID, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            $profitOwner = $vehicleData->getPrice();
            
            $this->con->setData("INSERT INTO `garage` (`userGarageID`, `vehicleID`, `damage`) VALUES (:gid, :vid, :dmg);" . $this->userMinusCashQry, array(
                ':gid' => $garageID, ':vid' => $vehicleData->getId(), ':dmg' => 0,
                ':price' => $vehicleData->getPrice(), ':uid' => $_SESSION['UID']
            ));
            
            $this->applyPossessionProfits($pData, $profitOwner);
        }
    }
    
    public function getFamilyCrusherConverter($famID)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `crusher`, `converter` FROM `family` WHERE `id`= :fid AND `active`='1' AND `deleted`='0'
            ", array(':fid' => $famID));
            
            if((isset($row['crusher']) && $row['crusher'] >= 0) || (isset($row['converter']) && $row['converter'] >= 0))
            {
                $family = new Family();
                $family->setCrusher($row['crusher']);
                $family->setConverter($row['converter']);
                
                return $family;
            }
        }
    }
    
    public function buyFamilyCrusher($famID, $crusher)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `family` SET `crusher`= :cpcty, `money`=`money`- :price WHERE `id`= :fid AND `active`='1' AND `deleted`='0'
            ", array(':cpcty' => $crusher['capacity'], ':price' => $crusher['price'], ':fid' => $famID));
        }
    }
    
    public function buyFamilyConverter($famID, $converter)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `family` SET `converter`= :cpcty, `money`=`money`- :price WHERE `id`= :fid AND `active`='1' AND `deleted`='0'
            ", array(':cpcty' => $converter['capacity'], ':price' => $converter['price'], ':fid' => $famID));
        }
    }
    
    public function sellFamilyVehicles($implodeStr, $money, $famID)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("DELETE FROM `garage` WHERE `id` IN (".$implodeStr.")", array());
            
            $this->con->setData("
                UPDATE `family` SET `money`=`money`+ :money WHERE `id`= :fid AND `active`='1' AND `deleted`='0'
            ", array(':money' => $money, ':fid' => $famID));
        }
    }
    
    public function crushConvertFamilyVehicles($implodeStr, $bullets, $num, $famID)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("DELETE FROM `garage` WHERE `id` IN (".$implodeStr.")", array());
            
            $this->con->setData("
                UPDATE `family` SET `bullets`=`bullets`+ :bullets, `crusher`=`crusher`- :num, `converter`=`converter`- :num WHERE `id`= :fid AND `active`='1' AND `deleted`='0'
            ", array(':bullets' => $bullets, ':num' => $num, ':fid' => $famID));
        }
    }
    
    public function buyVehicleTuneUpgrade($vehicleData, $tuneData, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `garage` SET `".$tuneData['tuneDb']."`= :item WHERE `id`= :gid AND `".$tuneData['tuneDb']."`='0' AND `active`='1' AND `deleted`='0';" .
                $this->userMinusCashQry, array(
                ':item' => $tuneData['item'], ':gid' => $vehicleData->getId(),
                ':price' => $tuneData['price'], ':uid' => $_SESSION['UID']
            ));
            
            $this->applyPossessionProfits($pData, $tuneData['price']);
        }
    }
    
    public function sellVehicleTuneUpgrade($vehicleData, $tuneData, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `garage` SET `".$tuneData['tuneDb']."`='0' WHERE `id`= :gid AND `".$tuneData['tuneDb']."`= :item AND `active`='1' AND `deleted`='0';" .
                $this->userPlusCashQry, array(
                ':gid' => $vehicleData->getId(), ':item' => $tuneData['item'],
                ':val' => $tuneData['price'], ':uid' => $_SESSION['UID']
            ));
            
            $this->applyPossessionProfits($pData, $tuneData['price']);
        }
    }
}
