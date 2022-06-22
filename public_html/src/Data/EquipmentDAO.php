<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Data\PossessionDAO;
use src\Entities\Airplane;
use src\Entities\Protection;
use src\Entities\Weapon;

class EquipmentDAO extends DBConfig
{
    protected $con = "";            
    private $dbh = "";
    private $table = "weapon";
    private $allowedTables = array("airplane", "protection", "weapon");
    
    public function __construct($table = "weapon")
    {
        global $connection;
        $this->con = $connection;                        
        $this->dbh = $connection->con;
        
        $this->table = $table;
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']) && in_array($this->table, $this->allowedTables))
        {
            $add = "";
            if($this->table != "weapon") $add = "AND `id`!='0'";
            $row = $this->con->getDataSR("SELECT COUNT(*) AS `total` FROM `$this->table` WHERE `deleted` = '0' AND `active` = '1' $add");
            return $row['total'];
        }
    }
    
    public function getEquipmentPage()
    {
        if(isset($_SESSION['UID']) && in_array($this->table, $this->allowedTables))
        {
            global $userService;
            $addSelect = $addWhere = "";
            $type = substr(ucfirst($this->table), 0, 1);
            
            switch($this->table)
            {
                default:
                case 'airplane':
                    $addSelect = ", `power`";
                    break;
                case 'protection':
                    $addSelect = ", `protection`";
                    break;
                case 'weapon':
                    $addSelect = ", `wpnExpTrain`, `damage`";
                    break;
            }
            if($this->table != 'weapon') $addWhere = "AND `id`!='0'";
            
            $rows = $this->con->getData("
                SELECT `id`, `name`, `picture`, `price` $addSelect FROM `$this->table` WHERE `deleted` = '0' AND `active` = '1' $addWhere
            ");
            
            $list = array();
            global $userData;
            global $lang;
            foreach($rows AS $row)
            {
                if($userData->getDonatorID() >= 5)
                    $row['price'] *= 0.95;
                
                $checkPossession = $this->con->getDataSR("
                    SELECT `id` FROM `equipment` WHERE `userID`= :uid AND `type`= :type AND `equipmentID`= :eid
                ", array(':uid' => $_SESSION['UID'], ':type' => $type, ':eid' => $row['id']));
                
                switch($this->table)
                {
                    default:
                    case 'airplane':
                        $equipment = new Airplane();
                        $equipment->setEquipped(false);
                        if($userService->getStatusPageInfo()->getAirplane() == $row['name']) $equipment->setEquipped(true);
                        $equipment->setPower($row['power']);
                        break;
                    case 'protection':
                        $equipment = new Protection();
                        $equipment->setEquipped(false);
                        if($userService->getStatusPageInfo()->getProtection() == $row['name']) $equipment->setEquipped(true);
                        $equipment->setProtection($row['protection']);
                        break;
                    case 'weapon':
                        $equipment = new Weapon();
                        $equipment->setEquipped(false);
                        if($userService->getStatusPageInfo()->getWeapon() == $row['name']) $equipment->setEquipped(true);
                        $equipment->setWpnExpTrain($row['wpnExpTrain']);
                        $equipment->setDamage($row['damage']);
                        break;
                }
                $equipment->setId($row['id']);
                $equipment->setName($row['name']);
                if($lang == "en" && $row['name'] == "Mes") $equipment->setName("Knife");
                $equipment->setPicture($row['picture']);
                $equipment->setPrice($row['price']);
                $equipment->setInPossession(false);
                if(
                  (!empty($checkPossession) && $checkPossession['id'] > 0)
                  || (empty($checkPossession) && $row['id'] == 0)
                )
                    $equipment->setInPossession(true);
                
                array_push($list, $equipment);
            }
            
            return $list;
        }
    }
    
    public function buyEquipment($equipmentID, $pData)
    {
        if(isset($_SESSION['UID']) && in_array($this->table, $this->allowedTables))
        {
            $type = substr(ucfirst($this->table), 0, 1);
            
            if(in_array($type, array("W","P","A")))
            {
                $id = $equipmentID;
                
                $row = $this->con->getDataSR("
                    SELECT `id`, `price` FROM `$this->table` WHERE `deleted` = '0' AND `active` = '1' AND `id`= :eid
                ", array(':eid' => $id));
                
                global $userData;
                if($userData->getDonatorID() >= 5)
                    $row['price'] *= 0.95;
                
                $profitOwner = $row['price'];
                
                $this->con->setData("
                    UPDATE `user` SET `cash`=`cash`- :price, `$this->table`= :eid WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(':price' => $row['price'], ':eid' => $equipmentID, ':uid' => $_SESSION['UID']));
            
                $this->con->setData("INSERT INTO `equipment` (`userID`,`type`,`equipmentID`) VALUES (:uid, '$type', :eid)", array(':uid' => $_SESSION['UID'], ':eid' => $id));
                
                /** Possession logic for buying equipment | pay owner if exists and not self **/
                if(is_object($pData)) $storesOwner = $pData->getPossessDetails()->getUserID();
                if(is_object($pData) && $storesOwner > 0 && $storesOwner != $_SESSION['UID'])
                {
                    $possessionData = new PossessionDAO();
                    $possessionData->applyProfitForOwner($pData, $profitOwner, $storesOwner);
                }
            }
        }
    }
    
    public function sellEquipment($equipmentID)
    {
        if(isset($_SESSION['UID']) && in_array($this->table, $this->allowedTables))
        {
            $type = substr(ucfirst($this->table), 0, 1);
            
            if(in_array($type, array("W","P","A")))
            {
                $id = $equipmentID;
                
                $row = $this->con->getDataSR("
                    SELECT `id`, `price` FROM `$this->table` WHERE `deleted` = '0' AND `active` = '1' AND `id`= :eid
                ", array(':eid' => $id));
                
                global $userData;
                if($userData->getDonatorID() >= 5)
                    $row['price'] *= 0.95;
                
                $this->con->setData("
                    UPDATE `user` SET `cash`=`cash`+ :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(':price' => ($row['price']*0.6), ':uid' => $_SESSION['UID']));
                
                $this->con->setData("
                    UPDATE `user` SET `$this->table`='0' WHERE `id`= :uid AND `$this->table`= :eid AND `active`='1' AND `deleted`='0'
                ", array(':uid' => $_SESSION['UID'], ':eid' => $id));
            
                $this->con->setData("DELETE FROM `equipment` WHERE `type`='$type' AND `userID`= :uid AND `equipmentID`= :eid", array(':uid' => $_SESSION['UID'], ':eid' => $id));
            }
        }
    }
    
    public function equipEquipment($equipmentID)
    {
        if(isset($_SESSION['UID']) && in_array($this->table, $this->allowedTables))
        {
            $type = substr(ucfirst($this->table), 0, 1);
            
            if(in_array($type, array("W","P","A")))
            {
                $id = $equipmentID;
                
                $this->con->setData("
                    UPDATE `user` SET `$this->table`= :eid WHERE `id`= :uid AND `$this->table`!= :eid AND `active`='1' AND `deleted`='0'
                ", array(':eid' => $id, ':uid' => $_SESSION['UID']));
            }
        }
    }
}
