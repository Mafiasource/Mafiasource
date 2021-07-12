<?PHP

namespace src\Data;

use src\Business\FamilyMissionService;
use src\Data\config\DBConfig;
use src\Entities\FamilyMission;

class FamilyMissionDAO extends DBConfig
{
    protected $con = ""; // Init
    private $dbh = ""; // Init, old query con var, slightly longer writing

    public function __construct()
    {
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
    }

    public function __destruct()
    {
        $this->dbh = null;
    }

    public function getRecordsCount()
    {
        $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `user_mission` WHERE `active` = '1' AND `deleted` = '0' LIMIT 1");
        $statement->execute();
        $row = $statement->fetch();
        if(isset($row['total'])) return $row['total'];
    }
    
    public function getMissionTiersAndProgress($uid = false)
    {
        if(isset($_SESSION['UID']))
        {
            if($uid === false)
                $uid = $_SESSION['UID'];
            
            $row = $this->con->getDataSR("
                SELECT `crimesLv` AS `m2`, `vehiclesLv` AS `m1`, `pimpLv` AS `m3`, `smugglingLv` AS `m4`, `m5c` AS `m5`, `kills` AS `m6`,
                    (SELECT COUNT(`id`) FROM `user_mission_carjacker` WHERE `userID`= :uid) AS `m7`, `m8c` AS `m8`
                FROM `user`
                WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                LIMIT 1
            ", array(':uid' => $uid));
            
            $tiersProgress = array();
            if(isset($row['m1']) && $row['m1'] >= 1)
            {
                $missionService = new MissionService();
                $missionTiers = $missionService->missionTiers;
                
                foreach($missionTiers AS $m => $t)
                {
                    $tiersProgress[$m] = array('t' => 1, 'p' => $row["m" . $m]);
                    foreach($t['todo'] AS $k => $todo)
                    {
                        if($row['m' . $m] >= $todo)
                        {
                            $tiersProgress[$m]['t'] = $k;
                            if(array_key_exists($k + 1, $t['todo']))
                                $tiersProgress[$m]['t'] = $k + 1;
                        }
                    }
                }
            }
            return $tiersProgress;
        }
    }
    
    public function userHasStolenVehicleBefore($vid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `id` FROM `user_mission_carjacker` WHERE `userID`= :uid AND `vehicleID`= :vid LIMIT 1", array(':uid' => $_SESSION['UID'], ':vid' => $vid));
            if(isset($row['id']) && $row['id'] > 0)
                return true;
        }
        return false;
    }
    
    public function addToMission5Count($uid, $amount)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `user` SET `m5c`=`m5c`+ :a WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':a' => $amount, ':uid' => $uid));
        }
    }
    
    public function addNewCarjackerVehicle($vid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("INSERT INTO `user_mission_carjacker` (`userID`, `vehicleID`) VALUES (:uid, :vid)", array('uid' => $_SESSION['UID'], ':vid' => $vid));
        }
    }
    
    public function addCountToCarjackerVehicle($vid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `user_mission_carjacker` SET `stolenAmount`=`stolenAmount`+'1' WHERE `userID`= :uid AND `vehicleID`= :vid LIMIT 1", array(':uid' => $_SESSION['UID'], ':vid' => $vid));
        }
    }
    
    public function addToMission8Count($amount)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `user` SET `m8c`=`m8c`+ :a WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':a' => $amount, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function getProfileMissionsByFamilyID($fid)
    {
        if(isset($_SESSION['UID']))
        {
            $missionService = new MissionService();
            $missionTiers = $missionService->missionTiers;
            $tiersProgress = $this->getMissionTiersAndProgress($fid);
            $list = array();
            foreach($tiersProgress AS $m => $t)
            {
                if(($t['t'] == 6 || ($m == 7)) && $t['p'] >= $missionTiers[$m]['todo'][$t['t']])
                {
                    $mission = new Mission();
                    $mission->setId($t);
                    $mission->setName($missionService->missions[$m]);
                    
                    array_push($list, $mission);
                }
            }
            return $list;
        }
    }
}
