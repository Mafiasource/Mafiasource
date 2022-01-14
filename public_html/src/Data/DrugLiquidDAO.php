<?PHP

namespace src\Data;

use src\Business\SmuggleService;
use src\Business\DrugLiquidService;
use src\Business\DonatorService;
use src\Data\config\DBConfig;
use src\Data\SmuggleDAO;
use src\Entities\DrugLiquid;

class DrugLiquidDAO extends DBConfig
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
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `drug_liquid` WHERE `id`> '1'");
            $statement->execute();
            $row = $statement->fetch();
            return $row['total'];
        }
    }
    
    public function getDrugLiquidUnits($type = 1)
    {
        $myUnits = $this->con->getData("
            SELECT `id` FROM `drug_liquid` WHERE `userID`= :uid AND `type`= :type LIMIT 20
        ", array(':uid' => $_SESSION['UID'], ':type' => $type));
        
        if(count($myUnits))
        {
            global $security;
            foreach($myUnits AS $unit)
            {
                $randUnits = $security->randInt(50, 100);
                $this->con->setData("
                    UPDATE `drug_liquid` SET `units`= :units WHERE `id`= :id AND `units`='0' AND `type`= :type AND `time`<= :time
                ", array(':units' => $randUnits, ':id' => $unit['id'], ':type' => $type, ':time' => time()));
            }
        }
        
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData("
                SELECT dl.`id`, dl.`type`, dl.`smuggleID`, s.`name_".$this->lang."` AS `smuggleName`, dl.`units`, dl.`time`
                FROM `drug_liquid` AS dl
                LEFT JOIN `smuggle` AS s
                ON(dl.`smuggleID`=s.`id`)
                WHERE dl.`userID`= :uid AND dl.`type`= :type
                LIMIT 20
            ", array(':uid' => $_SESSION['UID'], ':type' => $type));
            
            $list = array();
            foreach($rows AS $row)
            {
                $dl = new DrugLiquid();
                $dl->setId($row['id']);
                $dl->setUserID($_SESSION['UID']);
                $dl->setSmuggleID($row['smuggleID']);
                $dl->setSmuggleName($row['smuggleName']);
                $dl->setUnits($row['units']);
                $dl->setTime($row['time']);
                
                array_push($list, $dl);
            }
            return $list;
        }
    }
    
    public function buyUnits($type, $id, $count)
    {
        if(isset($_SESSION['UID']))
        {
            global $userData;
            $waitingTime = 600;
            $donatorService = new DonatorService();
            $waitingTime = $donatorService->adjustWaitingTime($waitingTime, $userData->getDonatorID(), $userData->getCHalvingTimes());
            $extraQueries = "";
            $price = 0;
            for($i = 1; $i <= $count; $i++)
            {
                $extraQueries .= "INSERT INTO `drug_liquid` (`userID`, `type`, `smuggleID`, `time`) VALUES (:uid, :type, :sID, :time);";
                $price += 5000;
            }
            $this->con->setData("
                UPDATE `user` SET `cash`=`cash`- :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0';
                ".$extraQueries."
            ", array(':price' => $price, ':uid' => $_SESSION['UID'], ':type' => $type, ':sID' => $id, ':time' => (time() + $waitingTime)));
        }
    }
    
    public function getDrugLiquidUnitByIdAndType($id, $type)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT dl.`id`, dl.`type`, dl.`smuggleID`, s.`name_".$this->lang."` AS `smuggleName`, dl.`units`, dl.`time`
                FROM `drug_liquid` AS dl
                LEFT JOIN `smuggle` AS s
                ON(dl.`smuggleID`=s.`id`)
                WHERE dl.`id`= :id AND dl.`userID`= :uid AND dl.`type`= :type
            ", array(':id' => $id, ':uid' => $_SESSION['UID'], ':type' => $type));
            
            if($row['id'] > 0)
            {
                $dl = new DrugLiquid();
                $dl->setId($row['id']);
                $dl->setUserID($_SESSION['UID']);
                $dl->setSmuggleID($row['smuggleID']);
                $dl->setSmuggleName($row['smuggleName']);
                $dl->setUnits($row['units']);
                $dl->setTime($row['time']);
                
                return $dl;
            }
        }
        return false;
    }
    
    public function collectUnit($sData, $unit)
    {
        if(isset($_SESSION['UID']))
        {
            global $smuggle;
            $smuggleDAO = new SmuggleDAO();
            $smuggleDAO->buyUnits($sData['smuggle']->getType(), $smuggle->unitNumbers[$sData['smuggle']->getId()], $unit->getUnits(), 0);
        }
    }
    
    public function removeUnits($units)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                DELETE FROM `drug_liquid` WHERE `id` IN (".$units.")
            ");
        }
    }
}
