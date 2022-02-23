<?PHP

namespace src\Data;

use src\Business\CrimeService;
use src\Data\config\DBConfig;
use src\Entities\User;
use src\Entities\Crime;
use src\Entities\OrganizedCrime;
use src\Entities\PreparedOrganizedCrime;

class CrimeDAO extends DBConfig
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
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `crime` WHERE `deleted` = '0' AND `active` = '1'");
            $statement->execute();
            $row = $statement->fetch();
            return $row['total'];
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
    
    public function getCrimesPageInfo($organized = null)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `crimesLv`, `crimesXp`, `crimesProfit`, `crimesSuccess`, `crimesFail`, `crimesRankpoints`, `bullets`, `weapon` FROM `user` WHERE `id` = :uid");
            $statement->execute(array(':uid' => $_SESSION['UID']));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $userObj = new User();
                $userObj->setCrimesLv($row['crimesLv']);
                $userObj->setCrimesXp(array('experience' => $row['crimesXp']));
                $userObj->setCrimesXpRaw($row['crimesXp']);
                $userObj->setCrimesProfit($row['crimesProfit']);
                $userObj->setCrimesSuccess($row['crimesSuccess']);
                $userObj->setCrimesFail($row['crimesFail']);
                $userObj->setCrimesRankpoints($row['crimesRankpoints']);
                $sfRatio = self::gcd($row['crimesSuccess'],$row['crimesFail']);
                if($sfRatio != 0)
                {
                    $userObj->setCrimesSFRatio($row['crimesSuccess']/$sfRatio.':'.$row['crimesFail']/$sfRatio);
                }
                else
                {
                    $userObj->setCrimesSFRatio($row['crimesSuccess'].':'.$row['crimesFail']);
                }
                $userObj->setBullets($row['bullets']);
                $userObj->setWeapon($row['weapon']);
                
                if(!isset($organized))
                {
                    global $userData;
                    $statement2 = $this->dbh->prepare("
                        SELECT `id`, `name_".$this->lang."` AS `name`, `description_".$this->lang."` AS `description`, `picture`, `level`, `minProfit`, `maxProfit`, `donatorID`
                        FROM `crime` WHERE `level` <= :crimesLv AND `donatorID`<= :donatorID  AND `active`='1' AND `deleted`='0' ORDER BY `level` ASC
                    ");
                    $statement2->execute(array(':crimesLv' => $row['crimesLv'], ':donatorID' => $userData->getDonatorID()));
                    $crimesList = array();
                    $cnt = $statement2->rowCount();
                    $i = 1;
                    foreach($statement2 AS $row2)
                    {
                        $crime = new Crime();
                        $crime->setId($row2['id']);
                        $crime->setName($row2['name']);
                        $crime->setLevel($row2['level']);
                        $crime->setDescription($row2['description']);
                        if(file_exists(DOC_ROOT.'/web/public/images/crime/'.$row2['picture'])) $crime->setPicture($row2['picture']);
                        $crime->setMinProfit($row2['minProfit']);
                        $crime->setMaxProfit($row2['maxProfit']);
                        $crime->setDonatorID($row2['donatorID']);
                        $crime->setActive(false);
                        if($i == $cnt) $crime->setActive(true);
                        
                        array_push($crimesList, $crime);
                        $i++;
                    }
                }
                else
                {
                    $rows2 = $this->con->getData("SELECT `id`, `name_".$this->lang."` AS `name`, `description_".$this->lang."` AS `description`, `picture`, `type`, `minProfit`, `maxProfit` FROM `crime_org` WHERE `type` >= 1 AND `type`<= 3  AND `active`='1' AND `deleted`='0' ORDER BY `id` ASC");
                    $crimesList = array();
                    $cnt = count($rows2);
                    $i = 1;
                    foreach($rows2 AS $row2)
                    {
                        $crime = new OrganizedCrime();
                        $crime->setId($row2['id']);
                        $crime->setName($row2['name']);
                        $crime->setType($row2['type']);
                        $crime->setDescription($row2['description']);
                        if(file_exists(DOC_ROOT.'/web/public/images/crime/'.$row2['picture'])) $crime->setPicture($row2['picture']);
                        $crime->setMinProfit($row2['minProfit']);
                        $crime->setMaxProfit($row2['maxProfit']);
                        $crime->setActive(false);
                        if($i == $cnt) $crime->setActive(true);
                        
                        $preparedCrime = $this->getPreparedOrganizedCrimeByCrimeID($crime->getId());
                        if(is_object($preparedCrime))
                            $crime->setPreparedCrime($preparedCrime);
                        
                        array_push($crimesList, $crime);
                        $i++;
                    }
                }
            }
            return array('user' => $userObj, 'crimes' => $crimesList);
        }
    }
    
    public function getCrimeById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT `id`,`name_".$this->lang."` AS `name`,`description_".$this->lang."` AS `description`,
                `level`,`minProfit`,`maxProfit`,`difficulty`,`maxRankPoints`
                FROM `crime`
                WHERE `id` = :id AND `active`='1' AND `deleted`='0'
            ");
            $statement->execute(array(':id' => $id));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $crime = new Crime();
                $crime->setId($row['id']);
                $crime->setName($row['name']);
                $crime->setDescription($row['description']);
                $crime->setLevel($row['level']);
                $crime->setMinProfit($row['minProfit']);
                $crime->setMaxProfit($row['maxProfit']);
                $crime->setDifficulty($row['difficulty']);
                $crime->setMaxRankPoints($row['maxRankPoints']);
                return $crime;
            }
            else
            {
                return FALSE;
            }
        }
    }
    
    public function getOrganizedCrimeById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT `id`,`name_".$this->lang."` AS `name`,`description_".$this->lang."` AS `description`, `type`,
                `minProfit`, `maxProfit`, `difficulty`, `waitingTimeCompletion`, `travelTimeCompletion`, `prisonTimeBusted`
                FROM `crime_org`
                WHERE `id` = :id AND `active`='1' AND `deleted`='0'
            ");
            $statement->execute(array(':id' => $id));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $crime = new OrganizedCrime();
                $crime->setId($row['id']);
                $crime->setName($row['name']);
                $crime->setDescription($row['description']);
                $crime->setType($row['type']);
                $crime->setMinProfit($row['minProfit']);
                $crime->setMaxProfit($row['maxProfit']);
                $crime->setDifficulty($row['difficulty']);
                $crime->setWaitingTimeCompletion($row['waitingTimeCompletion']);
                $crime->setTravelTimeCompletion($row['travelTimeCompletion']);
                $crime->setPrisonTimeBusted($row['prisonTimeBusted']);
                return $crime;
            }
            else
            {
                return FALSE;
            }
        }
    }
    
    public function commitCrimeSuccess($money, $rp, $newLv, $newXp, $waitingTime = false, $hurt = false, $bullets = false, $participantID = false) // false params used for organized crimes
    {
        if(isset($_SESSION['UID']))
        {
            $uid = $_SESSION['UID'];
            if(isset($participantID) && $participantID > 0)
                $uid = $participantID;
            
            $crimeService = new CrimeService();
            $crimeService->commitCrimeSuccess($uid, $money, $rp, $newLv, $newXp, $waitingTime, $hurt, $bullets);
        }
    }
    
    public function commitCrimeFail($waitingTime = false, $hurt = false, $bullets = false, $participantID = false) // false params used for organized crimes
    {
        if(isset($_SESSION['UID']))
        {
            $uid = $_SESSION['UID'];
            if(isset($participantID) && $participantID > 0)
                $uid = $participantID;
            
            $crimeService = new CrimeService();
            $crimeService->commitCrimeFail($uid, $waitingTime, $hurt, $bullets);
        }
    }
    
    public function getPreparedOrganizedCrimeByCrimeID($crimeID)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT cp.`id`, cp.`userID`, cp.`job`, cp.`participantID`, cp.`participant2ID`, cp.`participant3ID`, cp.`userReady`, cp.`participantReady`, cp.`participant2Ready`, cp.`participant3Ready`,
                    cp.`garageID`, v.`name` AS `vehicleName`, cp.`weaponType`, cp.`intelType`, cp.`commitTime`,
                    u.`username`, p.`username` AS `participant`, p2.`username` AS `participant2`, p3.`username` AS `participant3`
                FROM `crime_org_prep` AS cp
                LEFT JOIN `user` AS u
                ON (cp.`userID`=u.`id`)
                LEFT JOIN `user` AS p
                ON (cp.`participantID`=p.`id`)
                LEFT JOIN `user` AS p2
                ON (cp.`participant2ID`=p2.`id`)
                LEFT JOIN `user` AS p3
                ON (cp.`participant3ID`=p3.`id`)
                LEFT JOIN `garage` AS g
                ON (cp.`garageID`=g.`id`)
                LEFT JOIN `vehicle` AS v
                ON (g.`vehicleID`=v.`id`)
                WHERE cp.`orgCrimeID`= :cid AND (cp.`userID`= :uid OR cp.`participantID`= :uid OR cp.`participant2ID`= :uid OR cp.`participant3ID`= :uid)
            ", array(':cid' => $crimeID, ':uid' => $_SESSION['UID']));
            
            if(isset($row['id']) && $row['id'] > 0)
            {
                $crimeService = new CrimeService();
                $crime = new PreparedOrganizedCrime();
                $crime->setId($row['id']);
                $crime->setOrgCrimeID($crimeID);
                $crime->setUserID($row['userID']);
                $crime->setUsername($row['username']);
                $crime->setJob($row['job']);
                $crime->setParticipantID($row['participantID']);
                $crime->setParticipant($row['participant']);
                $crime->setParticipant2ID($row['participant2ID']);
                $crime->setParticipant2($row['participant2']);
                $crime->setParticipant3ID($row['participant3ID']);
                $crime->setParticipant3($row['participant3']);
                $crime->setUserReady($row['userReady']);
                $crime->setParticipantReady($row['participantReady']);
                $crime->setParticipant2Ready($row['participant2Ready']);
                $crime->setParticipant3Ready($row['participant3Ready']);
                $crime->setGarageID($row['garageID']);
                $crime->setVehicle($row['vehicleName']);
                $crime->setWeaponType($row['weaponType']);
                if(array_key_exists($row['weaponType'], $crimeService->weapons)) $crime->setWeapon($crimeService->weapons[$row['weaponType']]);
                $crime->setIntelType($row['intelType']);
                if(array_key_exists($row['intelType'], $crimeService->intel)) $crime->setIntel($crimeService->intel[$row['intelType']]);
                $crime->setCommitTime($row['commitTime']);
                
                return $crime;
            }
        }
    }
    
    public function isUserInPreparedCrimeByCrimeID($crimeID, $userID)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `crime_org_prep` WHERE `orgCrimeID`= :cid AND (`userID`= :uid OR `participantID`= :uid OR `participant2ID`= :uid OR `participant3ID`= :uid)
            ", array(':cid' => $crimeID, ':uid' => $userID));
            
            if(isset($row['id']) && $row['id'] > 0)
                return true;
        }
        return false;
    }
    
    public function prepareOrganizedCrimeType2($crimeData, $job, $participantID)
    {
        if(isset($_SESSION['UID']))
        {
            $jobID = 1;
            if($job == "raider")
                $jobID = 2;
            
            $this->con->setData("
                INSERT INTO `crime_org_prep` (`orgCrimeID`, `userID`, `job`, `participantID`) VALUES (:ocid, :uid, :job, :pid)
            ", array(':ocid' => $crimeData->getId(), ':uid' => $_SESSION['UID'], ':job' => $jobID, ':pid' => $participantID));
        }
    }
    
    public function prepareOrganizedCrimeType3($crimeData, $getawayID, $groundID, $intelID)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                INSERT INTO `crime_org_prep` (`orgCrimeID`, `userID`, `job`, `participantID`, `participant2ID`, `participant3ID`, `userReady`) VALUES (:ocid, :uid, 0, :pid, :p2id, :p3id, 1)
            ", array(':ocid' => $crimeData->getId(), ':uid' => $_SESSION['UID'], ':pid' => $getawayID, ':p2id' => $groundID, ':p3id' => $intelID));
        }
    }
    
    public function readyUpOrganizedCrimeType2($id, $job, $value)
    {
        if(isset($_SESSION['UID']))
        {
            $set = "`garageID`= :v";
            if($job == 2)
                $set = "`weaponType`= :v";
            
            $this->con->setData("
                UPDATE `crime_org_prep` SET ".$set.", `userReady`='1' WHERE `orgCrimeID`= :ocid AND `userID`= :uid
            ", array(':v' => $value, ':ocid' => $id, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function changeOrganizedCrimeParticipant($id, $participantID, $num = "")
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `crime_org_prep` SET `participant".$num."ID`= :pid, `participant".$num."Ready`='0' WHERE `orgCrimeID`= :ocid AND `userID`= :uid
            ", array(':ocid' => $id, ':pid' => $participantID, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function stopOrganizedCrime($id)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                DELETE FROM `crime_org_prep` WHERE `orgCrimeID`= :ocid AND `userID`= :uid
            ", array(':ocid' => $id, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function acceptOrganizedCrimeType2($id, $jobStarter, $value)
    {
        if(isset($_SESSION['UID']))
        {
            $set = "`garageID`= :v";
            $addSql = "";
            $addPar = array();
            if($jobStarter == 1)
            {
                $crimeService = new CrimeService();
                $set = "`weaponType`= :v";
                $addSql = ";UPDATE `user` SET `cash`=`cash`- :p WHERE `id`= :uid AND `active`='1' AND `deleted`='0'";
                $addPar = array(':p' => $crimeService->weapons[$value]['price']);
            }
            $this->con->setData("
                UPDATE `crime_org_prep` SET ".$set.", `participantReady`='1' WHERE `orgCrimeID`= :ocid AND `participantID`= :uid ".$addSql."
            ", array_merge(array(':v' => $value, ':ocid' => $id, ':uid' => $_SESSION['UID']), $addPar));
        }
    }
    
    public function acceptOrganizedCrimeType3($id, $num, $value)
    {
        if(isset($_SESSION['UID']))
        {
            $set = "`garageID`= :v";
            $addSql = "";
            $cashMinusSql = "UPDATE `user` SET `cash`=`cash`- :p WHERE `id`= :uid AND `active`='1' AND `deleted`='0'";
            $addPar = array();
            if($num == 2)
            {
                $crimeService = new CrimeService();
                $set = "`weaponType`= :v";
                $addSql = ";" . $cashMinusSql;
                $addPar = array(':p' => $crimeService->weapons[$value]['price']);
            }
            elseif($num == 3)
            {
                $crimeService = new CrimeService();
                $set = "`intelType`= :v";
                $addSql = ";" . $cashMinusSql;
                $addPar = array(':p' => $crimeService->intel[$value]['price']);
            }
            $this->con->setData("
                UPDATE `crime_org_prep` SET ".$set.", `participant".$num."Ready`='1' WHERE `orgCrimeID`= :ocid AND `participant".$num."ID`= :uid ".$addSql."
            ", array_merge(array(':v' => $value, ':ocid' => $id, ':uid' => $_SESSION['UID']), $addPar));
        }
    }
    
    public function denyOrganizedCrime($id, $num = "")
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `crime_org_prep` SET `participant".$num."ID`='0', `participant".$num."Ready`='0' WHERE `orgCrimeID`= :ocid AND `participant".$num."ID`= :uid
            ", array(':ocid' => $id, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function getWaitingTimeByUserID($uid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `cCrimes` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $uid));
            
            if(isset($row['cCrimes']) && $row['cCrimes'])
                return $row['cCrimes'];
            else
                return true;
        }
    }
    
    public function removeOrganizedCrime($id)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                DELETE FROM `crime_org_prep` WHERE `orgCrimeID`= :ocid AND (`userID`= :uid OR `participantID`= :uid)
            ", array(':ocid' => $id, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function executeOrganizedCrimeType3($id, $waitingTime, $travelTime)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `crime_org_prep` SET `commitTime`= :time WHERE `orgCrimeID`= :ocid AND `userID`= :uid
            ", array(':time' => time(), ':ocid' => $id, ':uid' => $_SESSION['UID']));
            
            $crime = $this->con->getDataSR("
                SELECT `userID`, `participantID`, `participant2ID`, `participant3ID` FROM `crime_org_prep` WHERE `orgCrimeID`= :ocid AND `userID`= :uid
            ", array(':ocid' => $id, ':uid' => $_SESSION['UID']));
            
            $ids = $crime['userID'] . ", " . $crime['participantID'] . ", " . $crime['participant2ID'] . ", " . $crime['participant3ID'];
            $this->con->setData("
                UPDATE `user` SET `cCrimes`= :wtime, `cTravelTime`= :ttime WHERE `id` IN (".$ids.") AND `active`='1' AND `deleted`='0'
            ", array(':wtime' => (time() + $waitingTime), ':ttime' => (time() + $travelTime)));
        }
    }
}
