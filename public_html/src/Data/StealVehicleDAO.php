<?PHP

namespace src\Data;

use src\Business\DonatorService;
use src\Data\config\DBConfig;
use src\Entities\User;
use src\Entities\StealVehicle;

class StealVehicleDAO extends DBConfig
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
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `steal_vehicle` WHERE `deleted` = '0' AND `active` = '1' LIMIT 1");
            $statement->execute();
            $row = $statement->fetch();
            return isset($row['total']) ? $row['total'] : 0;
        }
    }
    
    public static function gcd($num1, $num2)
    {
       while ($num2 != 0){
         $t = $num1 % $num2;
         $num1 = $num2;
         $num2 = $t;
       }
       return $num1;
    }
    
    public function getStealVehiclesPageInfo()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `vehiclesLv`, `vehiclesXp`, `vehiclesProfit`, `vehiclesSuccess`, `vehiclesFail`, `vehiclesRankpoints` FROM `user` WHERE `id` = :uid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':uid' => $_SESSION['UID']));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $userObj = new User();
                $userObj->setVehiclesLv($row['vehiclesLv']);
                $userObj->setVehiclesXp(array('experience' => $row['vehiclesXp'], 'class' => "bg-danger"));
                $userObj->setVehiclesXpRaw($row['vehiclesXp']);
                $userObj->setVehiclesProfit($row['vehiclesProfit']);
                $userObj->setVehiclesSuccess($row['vehiclesSuccess']);
                $userObj->setVehiclesFail($row['vehiclesFail']);
                $userObj->setVehiclesRankpoints($row['vehiclesRankpoints']);
                $sfRatio = self::gcd($row['vehiclesSuccess'],$row['vehiclesFail']);
                if($sfRatio != 0)
                {
                    $userObj->setVehiclesSFRatio($row['vehiclesSuccess']/$sfRatio.':'.$row['vehiclesFail']/$sfRatio);
                }
                else
                {
                    $userObj->setVehiclesSFRatio($row['vehiclesSuccess'].':'.$row['vehiclesFail']);
                }
                $statement2 = $this->dbh->prepare("SELECT `id`, `name_".$this->lang."` AS `name`, `description_".$this->lang."` AS `description`, `level`, `picture` FROM `steal_vehicle` WHERE `level` <= :vehiclesLv  AND `active`='1' AND `deleted`='0' ORDER BY `level` ASC");
                $statement2->execute(array(':vehiclesLv' => $row['vehiclesLv']));
                $vehiclesList = array();
                $cnt = $statement2->rowCount();
                $i = 1;
                foreach($statement2 AS $row2)
                {
                    $sv = new StealVehicle();
                    $sv->setId($row2['id']);
                    $sv->setName($row2['name']);
                    $sv->setLevel($row2['level']);
                    $sv->setDescription($row2['description']);
                    if(file_exists(DOC_ROOT.'/web/public/images/steal_vehicle/'.$row2['picture'])) $sv->setPicture($row2['picture']);
                    $sv->setActive(false);
                    if($i == $cnt) $sv->setActive(true);
                    array_push($vehiclesList,$sv);
                    $i++;
                }
            }
            return array('user' => $userObj, 'svehicles' => $vehiclesList);
        }
    }
    
    public function getStealVehicleById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT `id`,`name_".$this->lang."` AS `name`,`description_".$this->lang."` AS `description`,
                `level`,`difficulty`,`maxRankPoints`
                FROM `steal_vehicle`
                WHERE `id` = :id AND `active`='1' AND `deleted`='0'
                LIMIT 1                
            ");
            $statement->execute(array(':id' => $id));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $sv = new StealVehicle();
                $sv->setId($row['id']);
                $sv->setName($row['name']);
                $sv->setDescription($row['description']);
                $sv->setLevel($row['level']);
                $sv->setDifficulty($row['difficulty']);
                $sv->setMaxRankPoints($row['maxRankPoints']);
                return $sv;
            }
            else
            {
                return FALSE;
            }
        }
    }
    
    public function stealVehicleSuccess($stolenValue, $rpCollected, $newLv, $newXp)
    {
        if(isset($_SESSION['UID']))
        {
            global $userData;            
            $waitingTime = 120;
            $donatorService = new DonatorService();
            $waitingTime = $donatorService->adjustWaitingTime($waitingTime, $userData->getDonatorID(), $userData->getCHalvingTimes());
            $statement = $this->dbh->prepare("
                UPDATE `user`
                SET `vehiclesProfit`=`vehiclesProfit`+ :sv, `vehiclesSuccess`=`vehiclesSuccess`+'1',
                `rankpoints`=`rankpoints`+ :rp, `vehiclesRankpoints`=`vehiclesRankpoints`+ :rp,
                `cStealVehicles`= :wait, `vehiclesLv`= :vLv, `vehiclesXp`= :vXp
                WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                LIMIT 1
            ");
            $statement->execute(array(':sv' => $stolenValue, ':rp' => $rpCollected, ':wait' => (time() + $waitingTime), ':vLv' => $newLv, ':vXp' => $newXp, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function stealVehicleFail()
    {
        if(isset($_SESSION['UID']))
        {
            global $userData;            
            $waitingTime = 120;
            $donatorService = new DonatorService();
            $waitingTime = $donatorService->adjustWaitingTime($waitingTime, $userData->getDonatorID(), $userData->getCHalvingTimes());
            $statement = $this->dbh->prepare("
                UPDATE `user`
                SET `vehiclesFail`=`vehiclesFail`+'1', `cStealVehicles` = :wait
                WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                LIMIT 1                
            ");
            $statement->execute(array(':wait' => (time() + $waitingTime), ':uid' => $_SESSION['UID']));
        }
    }
}
