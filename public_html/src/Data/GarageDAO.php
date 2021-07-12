<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Data\StateDAO;
use src\Entities\Family;
use src\Entities\Garage;
use src\Entities\UserGarage;
use src\Entities\FamilyGarage;
use src\Entities\Vehicle;

class GarageDAO extends DBConfig
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
            global $userData;
            global $route;
            $rn = $route->getRouteName();
            $famID = $userData->getFamilyID();
            
            $statement = $this->dbh->prepare("SELECT `id` FROM `user_garage` WHERE `userID`= :uid AND `stateID`= :stateID AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':stateID' => $userData->getStateID()));
            $ugRow = $statement->fetch();
            
            if($famID > 0)
            {
                $statement = $this->dbh->prepare("SELECT `id` FROM `family_garage` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
                $statement->execute(array(':fid' => $famID));
                $fgRow = $statement->fetch();
            }
            if(isset($ugRow['id']) && $ugRow['id'] > 0)
            {
                if($rn == 'garage' || $rn == 'garage-page')
                {
                    $row = $this->con->getDataSR("
                        SELECT COUNT(`id`) AS `total` FROM `garage` WHERE `deleted` = '0' AND `active` = '1' AND `userGarageID`= :gid
                    ", array(':gid' => $ugRow['id']));
                }
            }
            if(isset($fgRow['id']) && $fgRow['id'] > 0)
            {                            
                if(($rn == 'family-garage' || $rn == 'family-garage-page' || $rn == 'family-crimes') && $famID > 0)
                {
                    if(isset($fgRow['id']) && $fgRow['id'] > 0)
                    {
                        $row = $this->con->getDataSR("
                            SELECT COUNT(`id`) AS `total` FROM `garage` WHERE `deleted` = '0' AND `active` = '1' AND `famGarageID`= :gid
                        ", array(':gid' => $fgRow['id']));
                    }
                }
            }
            if($rn == 'garage-shop' || $rn == 'garage-shop-page')
            {
                $row = $this->con->getDataSR("
                    SELECT COUNT(`id`) AS `total` FROM `vehicle` WHERE `deleted` = '0' AND `active` = '1' AND `stealLv` <= '100'
                ");
            }
            if(isset($row['total'])) return $row['total'];
        }
        return false;
    }
    
    public function hasGarageInState($stateID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `user_garage` WHERE `userID`= :uid AND `stateID`= :stateID AND `active`='1' AND `deleted`='0' LIMIT 1");
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
            $statement = $this->dbh->prepare("SELECT `id` FROM `family_garage` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
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
            $statement = $this->dbh->prepare("SELECT `id`, `size` FROM `user_garage` WHERE `userID`= :uid AND `stateID`= :stateID AND `active`='1' AND `deleted`='0' LIMIT 1");
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
            $statement = $this->dbh->prepare("SELECT `id`, `size` FROM `family_garage` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
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
    
    public function hasSpaceLeftInGarage($stateID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `size` FROM `user_garage` WHERE `userID`= :uid AND `stateID`= :stateID AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':stateID' => $stateID));
            $row = $statement->fetch();
            
            $gid = isset($row['id']) ? $row['id'] : null;
            $size = isset($row['size']) ? $row['size'] : null;
            
            if($gid && $size)
            {
                switch($size)
                {
                    case 'small':
                        $sVal = 5;
                        break;
                    case 'medium':
                        $sVal = 10;
                        break;
                    case 'large':
                        $sVal = 20;
                        break;
                    case 'extra-large':
                        $sVal = 35;
                        break;
                    default:
                        $sVal = 5;
                        break;
                }
                
                $statement = $this->dbh->prepare("SELECT `id`  FROM `garage` WHERE `userGarageID` = :gid AND `active`='1' AND `deleted`='0'");
                $statement->execute(array(':gid' => $gid));
                if($statement->rowCount() >= $sVal)
                    return FALSE;
                else
                    return TRUE;
            }
        }
        return FALSE;
    }
    
    public function hasSpaceLeftInFamilyGarage($famID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `size` FROM `family_garage` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':fid' => $famID));
            $row = $statement->fetch();
            
            $gid = isset($row['id']) ? $row['id'] : null;
            $size = isset($row['size']) ? $row['size'] : null;
            
            if($gid && $size)
            {
                switch($size)
                {
                    case 'small':
                        $sVal = 15;
                        break;
                    case 'medium':
                        $sVal = 45;
                        break;
                    case 'large':
                        $sVal = 120;
                        break;
                    case 'extra-large':
                        $sVal = 300;
                        break;
                    default:
                        $sVal = 15;
                        break;
                }
                
                $statement = $this->dbh->prepare("SELECT `id`  FROM `garage` WHERE `famGarageID` = :gid AND `active`='1' AND `deleted`='0'");
                $statement->execute(array(':gid' => $gid));
                if($statement->rowCount() >= $sVal)
                    return FALSE;
                else
                    return TRUE;
            }
        }
        return FALSE;
    }
    
    public function spaceLeftInGarage($stateID, $maxSpace)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `size` FROM `user_garage` WHERE `userID`= :uid AND `stateID`= :stateID AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':stateID' => $stateID));
            $row = $statement->fetch();
            
            $gid = isset($row['id']) ? $row['id'] : null;
            $size = isset($row['size']) ? $row['size'] : null;
            
            if($gid && $size)
            {
                switch($size)
                {
                    case 'small':
                        $sVal = 5;
                        break;
                    case 'medium':
                        $sVal = 10;
                        break;
                    case 'large':
                        $sVal = 20;
                        break;
                    case 'extra-large':
                        $sVal = 35;
                        break;
                    default:
                        $sVal = 5;
                        break;
                }
                
                $statement = $this->dbh->prepare("SELECT `id`  FROM `garage` WHERE `userGarageID` = :gid AND `active`='1' AND `deleted`='0'");
                $statement->execute(array(':gid' => $gid));
                if($statement->rowCount() >= $sVal)
                    return FALSE;
                else
                    return $maxSpace - $statement->rowCount();
            }
        }
        return FALSE;
    }
    
    public function spaceLeftInFamilyGarage($famID, $maxSpace)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `size` FROM `family_garage` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':fid' => $famID));
            $row = $statement->fetch();
            
            $gid = isset($row['id']) ? $row['id'] : null;
            $size = isset($row['size']) ? $row['size'] : null;
            
            if($gid && $size)
            {
                switch($size)
                {
                    case 'small':
                        $sVal = 15;
                        break;
                    case 'medium':
                        $sVal = 45;
                        break;
                    case 'large':
                        $sVal = 120;
                        break;
                    case 'extra-large':
                        $sVal = 300;
                        break;
                    default:
                        $sVal = 15;
                        break;
                }
                
                $statement = $this->dbh->prepare("SELECT `id`  FROM `garage` WHERE `famGarageID` = :gid AND `active`='1' AND `deleted`='0'");
                $statement->execute(array(':gid' => $gid));
                if($statement->rowCount() >= $sVal)
                    return FALSE;
                else
                    return $maxSpace - $statement->rowCount();
            }
        }
        return FALSE;
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
            $statement = $this->dbh->prepare("UPDATE `user` SET `cash`=`cash`+ :val WHERE `id`= :uid");
            $statement->execute(array(':val' => $value, ':uid' => $_SESSION['UID']));
            
            if(isset($_SESSION['steal-vehicles'])) unset($_SESSION['steal-vehicles']);
        }
    }
    
    public function sellStolenFamilyVehicle($familyID, $value)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `family` SET `money`=`money`+ :val WHERE `id`= :fid");
            $statement->execute(array(':val' => $value, ':fid' => $familyID));
        }
    }
    
    public function addVehicleToGarage($svData, $stateID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `user_garage` WHERE `userID`= :uid AND `stateID`= :stateID AND `active`='1' AND `deleted`='0' LIMIT 1");
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
            $statement = $this->dbh->prepare("SELECT `id` FROM `family_garage` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':fid' => $familyID));
            $row = $statement->fetch();
            
            $statement = $this->dbh->prepare("INSERT INTO `garage` (`famGarageID`,`vehicleID`,`damage`) VALUES (:gid,:vid,:dmg)");
            $statement->execute(array(':gid' => $row['id'], ':vid' => $vData['vehicleID'], ':dmg' => $vData['dmg']));
            return true;
        }
    }
    
    public function buyGarageInState($size, $price, $stateID, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            $profitOwner = $price;
            
            $statement = $this->dbh->prepare("INSERT INTO `user_garage` (`stateID`,`userID`,`size`) VALUES (:stateID, :uid, :size)");
            $statement->execute(array(':stateID' => $stateID, ':uid' => $_SESSION['UID'], ':size' => $size));
            
            $statement = $this->dbh->prepare("UPDATE `user` SET `cash`=`cash`- :price WHERE `id`= :uid");
            $statement->execute(array(':price' => $price, ':uid' => $_SESSION['UID']));
            
            /** Possession logic for buying garage | pay owner if exists and not self **/
            if(is_object($pData)) $garageOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $garageOwner > 0 && $garageOwner != $_SESSION['UID'])
            {
                $this->con->setData("
                    UPDATE `possess` SET `profit`=`profit`+ :profit, `profit_hour`=`profit_hour`+ :profit WHERE `id`= :pid AND `active`='1' AND `deleted`='0';
                    UPDATE `user` SET `bank`=`bank`+ :profit WHERE `id`= :oid AND `active`='1' AND `deleted`='0'
                ", array(':profit' => $profitOwner, ':pid' => $pData->getPossessDetails()->getId(), ':oid' => $garageOwner));
            }
        }
    }
    
    public function buyFamilyGarage($famID, $size, $price, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            $profitOwner = $price;
            
            $statement = $this->dbh->prepare("INSERT INTO `family_garage` (`familyID`,`size`) VALUES (:fid, :size)");
            $statement->execute(array(':fid' => $famID, ':size' => $size));
            
            $statement = $this->dbh->prepare("UPDATE `family` SET `money`=`money`- :price WHERE `id`= :fid");
            $statement->execute(array(':price' => $price, ':fid' => $famID));
            
            /** Possession logic for buying family garage | pay owner if exists and not self **/
            if(is_object($pData)) $garageOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $garageOwner > 0 && $garageOwner != $_SESSION['UID'])
            {
                $this->con->setData("
                    UPDATE `possess` SET `profit`=`profit`+ :profit, `profit_hour`=`profit_hour`+ :profit WHERE `id`= :pid AND `active`='1' AND `deleted`='0';
                    UPDATE `user` SET `bank`=`bank`+ :profit WHERE `id`= :oid AND `active`='1' AND `deleted`='0'
                ", array(':profit' => $profitOwner, ':pid' => $pData->getPossessDetails()->getId(), ':oid' => $garageOwner));
            }
        }
    }
    
    public function getVehiclesInGarageByState($stateID, $from, $to)
    {
        if(isset($_SESSION['UID']) && is_int($from) && is_int($to) && $to < 50)
        {
            if($this->hasGarageInState($stateID) == TRUE)
            {
                $statement = $this->dbh->prepare("SELECT `id` FROM `user_garage` WHERE `userID`= :uid AND `stateID`= :stateID AND `active`='1' AND `deleted`='0' LIMIT 1");
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
                    
                    $garage = new Garage();
                    $garage->setId($g['id']);
                    $garage->setUserGarageID($g['userGarageID']);
                    $garage->setFamGarageID(0);
                    $garage->setVehicleID($g['vehicleID']);
                    $garage->setVehicleName($g['vehicleName']);
                    $garage->setVehiclePicture($g['vehiclePicture']);
                    $garage->setVehicleValue((($g['vehiclePrice']/100) * (100-$g['damage'])));
                    $garage->setDamage($g['damage']);
                    
                    $price = $g['vehiclePrice'];
                    $pr = 100 - $g['damage'];
                    $pr = $pr / 100;
                    $price = round($price * $pr, 0);
                    $diff = $g['vehiclePrice'] - $price;
                    $costs = round($diff * 1.25, 0);
                    
                    $garage->setRepairCosts($costs);
                    
                    array_push($list,$garage);
                }
                return $list;
            }
        }
    }
    
    public function getVehiclesInFamilyGarage($famID, $from, $to)
    {
        if(isset($_SESSION['UID']) && is_int($from) && is_int($to) && $to < 50)
        {
            if($this->hasFamilyGarage($famID) == TRUE)
            {
                $statement = $this->dbh->prepare("SELECT `id` FROM `family_garage` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
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
                    $garage->setVehicleID($g['vehicleID']);
                    $garage->setVehicleName($g['vehicleName']);
                    $garage->setVehicleValue((($g['vehiclePrice']/100) * (100-$g['damage'])));
                    $garage->setDamage($g['damage']);
                    
                    array_push($list,$garage);
                }
                return $list;
            }
        }
    }
    
    public function getAllVehiclesInGarageByState($stateID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `user_garage` WHERE `userID`= :uid AND `stateID`= :stateID AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':stateID' => $stateID));
            $row = $statement->fetch();
            if(isset($row['id']))
            {
                $statement = $this->dbh->prepare("
                    SELECT g.`id`, g.`userGarageID`, g.`vehicleID`, v.`name` AS `vehicleName`
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
                    $garage->setVehicleID($g['vehicleID']);
                    $garage->setVehicleName($g['vehicleName']);
                    
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
            $garage = $this->con->getDataSR("
                SELECT `id` FROM `user_garage` WHERE `userID`= :uid AND `stateID`= :stateID AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':uid' => $_SESSION['UID'], ':stateID' => $stateID));
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
                $statement = $this->dbh->prepare("SELECT `id` FROM `user_garage` WHERE `userID`= :uid AND `stateID`= :stateID AND `active`='1' AND `deleted`='0' LIMIT 1");
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
            $statement = $this->dbh->prepare("SELECT `id` FROM `family_garage` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
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
                    SELECT g.`id`, g.`userGarageID`, g.`vehicleID`, g.`damage`, v.`name` AS `vehicleName`, v.`picture` AS `vehiclePicture`, v.`price` AS `vehiclePrice`
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
                    
                    $garage = new Garage();
                    $garage->setId($g['id']);
                    $garage->setUserGarageID($g['userGarageID']);
                    $garage->setFamGarageID(0);
                    $garage->setVehicleID($g['vehicleID']);
                    $garage->setVehicleName($g['vehicleName']);
                    $garage->setVehiclePicture($g['vehiclePicture']);
                    $garage->setVehicleValue((($g['vehiclePrice']/100) * (100-$g['damage'])));
                    $garage->setDamage($g['damage']);
                    
                    $price = $g['vehiclePrice'];
                    $pr = 100 - $g['damage'];
                    $pr = $pr / 100;
                    $price = round($price * $pr, 0);
                    $diff = $g['vehiclePrice'] - $price;
                    $costs = round($diff * 1.25, 0);
                    
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
                    $garage->setVehicleID($g['vehicleID']);
                    $garage->setVehicleName($g['vehicleName']);
                    $garage->setVehiclePicture($g['vehiclePicture']);
                    $garage->setVehicleValue((($g['vehiclePrice']/100) * (100-$g['damage'])));
                    $garage->setDamage($g['damage']);
                    
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
                $statement = $this->dbh->prepare("UPDATE `garage` SET `damage`='0' WHERE `id` = :vid");
                $statement->execute(array(':vid' => $vData->getId()));
                
                $statement = $this->dbh->prepare("UPDATE `user` SET `cash`=`cash`- :val WHERE `id`= :uid");
                $statement->execute(array(':val' => $vData->getRepairCosts(), ':uid' => $_SESSION['UID']));
                
                /** Possession logic for repairing vehicle (garage) | pay owner if exists and not self **/
                if(is_object($pData)) $garageOwner = $pData->getPossessDetails()->getUserID();
                if(is_object($pData) && $garageOwner > 0 && $garageOwner != $_SESSION['UID'])
                {
                    $this->con->setData("
                        UPDATE `possess` SET `profit`=`profit`+ :profit, `profit_hour`=`profit_hour`+ :profit WHERE `id`= :pid AND `active`='1' AND `deleted`='0';
                        UPDATE `user` SET `bank`=`bank`+ :profit WHERE `id`= :oid AND `active`='1' AND `deleted`='0'
                    ", array(':profit' => $profitOwner, ':pid' => $pData->getPossessDetails()->getId(), ':oid' => $garageOwner));
                }
            }
        }
    }
    
    public function sellVehicle($vData)
    {
        if(isset($_SESSION['UID']))
        {
            if($this->userOwnsVehicle($vData->getId()))
            {
                $statement = $this->dbh->prepare("DELETE FROM `garage` WHERE `id` = :vid");
                $statement->execute(array(':vid' => $vData->getId()));
                
                $statement = $this->dbh->prepare("UPDATE `user` SET `cash`=`cash`+ :val WHERE `id`= :uid");
                $statement->execute(array(':val' => $vData->getVehicleValue(), ':uid' => $_SESSION['UID']));
            }
        }
    }
    
    public function getVehiclesInShop($from, $to)
    {
        if(isset($_SESSION['UID']) && is_int($from) && is_int($to) && $to < 50)
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
            
            $this->con->setData("INSERT INTO `garage` (`userGarageID`, `vehicleID`, `damage`) VALUES (:gid, :vid, :dmg)", array(':gid' => $garageID, ':vid' => $vehicleData->getId(), ':dmg' => 0));
            $this->con->setData("UPDATE `user` SET `cash`=`cash`- :price WHERE `id`= :uid", array(':price' => $vehicleData->getPrice(), ':uid' => $_SESSION['UID']));
            
            /** Possession logic for buying a vehicle (vehicle business) | pay owner if exists and not self **/
            if(is_object($pData)) $businessOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $businessOwner > 0 && $businessOwner != $_SESSION['UID'])
            {
                $this->con->setData("
                    UPDATE `possess` SET `profit`=`profit`+ :profit, `profit_hour`=`profit_hour`+ :profit WHERE `id`= :pid AND `active`='1' AND `deleted`='0';
                    UPDATE `user` SET `bank`=`bank`+ :profit WHERE `id`= :oid AND `active`='1' AND `deleted`='0'
                ", array(':profit' => $profitOwner, ':pid' => $pData->getPossessDetails()->getId(), ':oid' => $businessOwner));
            }
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
    
    public function getTunedVehiclesInGarageByState($stateID)
    {
        if(isset($_SESSION['UID']))
        {
            /*
            $rows = $this->con->getData("
            
            ");
            */
        }
    }
}
