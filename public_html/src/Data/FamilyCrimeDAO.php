<?PHP

namespace src\Data;

use src\Business\FamilyCrimeService;
use src\Business\FamilyMercenaryService;
use src\Business\DonatorService;
use src\Data\config\DBConfig;
use src\Entities\FamilyCrime;

class FamilyCrimeDAO extends DBConfig
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
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `family_crime` WHERE `familyID`= :fid AND `active` = '1' AND `deleted` = '0'");
            $statement->execute(array(':fid' => $this->familyID));
            $row = $statement->fetch();
            return $row['total'];
        }
    }
    
    public function getFamilyCrimes()
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $rows = $this->con->getData("
                SELECT fc.`id`, fc.`num_participants`, fc.`participants`, fc.`stateID`, s.`name` AS `state`, fc.`crime`, fc.`mercenaries`
                FROM `family_crime` AS fc
                LEFT JOIN `state` AS s
                ON (fc.`stateID`=s.`id`)
                WHERE fc.`familyID`= :fid AND fc.`active`='1' AND fc.`deleted`='0'
                ORDER BY `id` ASC 
            ", array(':fid' => $this->familyID));
            
            if(!empty($rows))
            {
                global $userService;
                $list = array();
                $famCrimeService = new FamilyCrimeService();
                
                foreach($rows AS $row)
                {
                    $famCrime = new FamilyCrime();
                    $famCrime->setId($row['id']);
                    $famCrime->setNumParticipants($row['num_participants']);
                    $famCrime->setStateID($row['stateID']);
                    $famCrime->setState($row['state']);
                    $famCrime->setCrime($famCrimeService->crimeNames[$row['crime']]);
                    $names = array();
                    $numInCrime = 0;
                    $userInCrime = false;
                    foreach(unserialize($row['participants']) AS $uid)
                    {
                        if($_SESSION['UID'] == $uid)
                            $userInCrime = true;
                        
                        $names[] = $userService->getUsernameById($uid);
                        $numInCrime++;
                    }
                    $famCrime->setParticipants(implode(',', $names));
                    $famCrime->setNumInCrime($numInCrime);
                    $famCrime->setUserInCrime($userInCrime);
                    $famCrime->setWithMercenaries(false);
                    if($row['mercenaries'] == 1)
                        $famCrime->setWithMercenaries(true);
                    
                    $famMercService = new FamilyMercenaryService();
                    $familyMercenaries = $famMercService->getMercenaries();
                    if(is_object($familyMercenaries))
                    {
                        $freeSlots = $famCrime->getNumParticipants() - $famCrime->getNumInCrime();
                        if($freeSlots > 0)
                        {
                            if($familyMercenaries->getMercenariesAvailable() >= $freeSlots)
                                $famCrime->setMercenariesReady($freeSlots);
                            else
                                $famCrime->setMercenariesReady($familyMercenaries->getMercenariesAvailable());
                        }
                    }
                    
                    array_push($list, $famCrime);
                }
                return $list;
            }
        }
    }
    
    public function userInsideFamilyCrime()
    {
        $uid = $_SESSION['UID'];
        if(isset($uid) && $this->familyID != 0)
        {
            $rows = $this->con->getData("SELECT `starterUID`, `participants` FROM `family_crime` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0'", array(':fid' =>$this->familyID));
            foreach($rows AS $row)
            {
                if(in_array($uid, unserialize($row['participants'])))
                    return true;
            }
        }
        return false;
    }
    
    public function organizeFamilyCrime($numParticipants, $participants, $crime, $stateID, $mercenaries = false)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                INSERT INTO `family_crime` (`starterUID`, `num_participants`, `participants`, `familyID`, `stateID`, `mercenaries`, `crime`)
                VALUES (:uid, :num, :prt, :fid, :sid, :merc, :crime)
            ", array(
                ':uid' => $_SESSION['UID'], ':num' => $numParticipants, ':prt' => $participants, ':fid' => $this->familyID,
                ':sid' => $stateID, ':merc' => $mercenaries, ':crime' => $crime
            ));
        }
    }
    
    public function getFamilyCrimeById($id)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $row = $this->con->getDataSR("
                SELECT fc.`id`, fc.`num_participants`, fc.`participants`, fc.`stateID`, s.`name` AS `state`, fc.`crime`, fc.`mercenaries`
                FROM `family_crime` AS fc
                LEFT JOIN `state` AS s
                ON (fc.`stateID`=s.`id`)
                WHERE fc.`id`= :cid AND fc.`familyID`= :fid
                LIMIT 1
            ", array(':cid' => $id, ':fid' => $this->familyID));
            
            if(isset($row['id']) && (int)$row['id'] === $id)
            {
                global $userService;
                $famCrimeService = new FamilyCrimeService();
                
                $famCrime = new FamilyCrime();
                $famCrime->setId($row['id']);
                $famCrime->setNumParticipants($row['num_participants']);
                $famCrime->setParticipantIds($row['participants']);
                $famCrime->setStateID($row['stateID']);
                $famCrime->setState($row['state']);
                $famCrime->setCrime($famCrimeService->crimeNames[$row['crime']]);
                $famCrime->setCrimeID($row['crime']);
                $names = array();
                $numInCrime = 0;
                $userInCrime = false;
                foreach(unserialize($row['participants']) AS $uid)
                {
                    if($_SESSION['UID'] == $uid)
                        $userInCrime = true;
                    
                    $names[] = $userService->getUsernameById($uid);
                    $numInCrime++;
                }
                $famCrime->setParticipants(implode($names));
                $famCrime->setNumInCrime($numInCrime);
                $famCrime->setUserInCrime($userInCrime);
                $famCrime->setWithMercenaries(false);
                if($row['mercenaries'] == 1)
                    $famCrime->setWithMercenaries(true);
                
                $famMercService = new FamilyMercenaryService();
                $familyMercenaries = $famMercService->getMercenaries();
                if(is_object($familyMercenaries))
                {
                    $freeSlots = $famCrime->getNumParticipants() - $famCrime->getNumInCrime();
                    if($freeSlots > 0)
                    {
                        if($familyMercenaries->getMercenariesAvailable() >= $freeSlots)
                            $famCrime->setMercenariesReady($freeSlots);
                        else
                            $famCrime->setMercenariesReady($familyMercenaries->getMercenariesAvailable());
                    }
                }
                
                return $famCrime;
            }
        }
    }
    
    public function getWaitingTimeByUserID($uid)
    {
        if(isset($_SESSION['UID'])  && $this->familyID != 0)
        {
            $row = $this->con->getDataSR("
                SELECT `cFamilyCrimes` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $uid));
            
            if(isset($row['cFamilyCrimes']) && $row['cFamilyCrimes'])
                return $row['cFamilyCrimes'];
            else
                return true;
        }
    }
    
    public function joinFamilyCrime($famCrimeID, $newParticipants)
    {
        if(isset($_SESSION['UID'])  && $this->familyID != 0)
        {
            $this->con->setData("
                UPDATE `family_crime` SET `participants`= :new WHERE `id`= :id AND `familyID`= :fid  AND `active`='1' AND `deleted`='0'
            ", array(':new' => $newParticipants, ':id' => $famCrimeID, ':fid' => $this->familyID));
        }
    }
    
    public function leaveFamilyCrime($famCrimeID, $newParticipants)
    { // Acts as a bool for: TRUE = family crime got deleted (last person) | FALSE = regular leave
        if(isset($_SESSION['UID'])  && $this->familyID != 0)
        {
            if(count(unserialize($newParticipants)) === 0)
            {
                $this->con->setData("
                    DELETE FROM `family_crime` WHERE `id`= :id AND `familyID`= :fid  AND `active`='1' AND `deleted`='0'
                ", array(':id' => $famCrimeID, ':fid' => $this->familyID));
                return true;
            }
            else
            {
                $this->con->setData("
                    UPDATE `family_crime` SET `participants`= :new WHERE `id`= :id AND `familyID`= :fid  AND `active`='1' AND `deleted`='0'
                ", array(':new' => $newParticipants, ':id' => $famCrimeID, ':fid' => $this->familyID));
                return false;
            }
        }
        return NULL;
    }
    
    public function updateUserFamilyCrimeTime($uid, $waitingTime, $honorPoints = 3, $success = false)
    { // Successful crime? Also add 3 honorpoints.
        if(isset($_SESSION['UID'])  && $this->familyID != 0)
        {
            $d = $this->con->getDataSR("SELECT `donatorID`, `cHalvingTimes` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':uid' => $uid));
            $donatorService = new DonatorService();
            $waitingTime = $donatorService->adjustWaitingTime($waitingTime, $d['donatorID'], $d['cHalvingTimes']);
            if($success === true)
                $this->con->setData("
                    UPDATE `user` SET `cFamilyCrimes`= :time, `honorPoints`=`honorPoints`+ :hp WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(':time' => (time() + $waitingTime), ':hp' => $honorPoints, ':uid' => $uid));
            else
                $this->con->setData("
                    UPDATE `user` SET `cFamilyCrimes`= :time WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(':time' => (time() + $waitingTime), ':uid' => $uid));
                
            return true;
        }
    }
    
    public function deleteFamilyCrimeById($cid)
    {
        if(isset($_SESSION['UID'])  && $this->familyID != 0)
        {
            $this->con->setData("
                DELETE FROM `family_crime` WHERE `id`= :cid AND `familyID`= :fid
            ", array(':cid' => $cid, ':fid' => $this->familyID));
            return true;
        }
    }
    
    public function useFamilyMercenariesInCrime($mercs)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                UPDATE `family` SET `mercenariesUsed`=`mercenariesUsed`+ :mercs WHERE `id`= :fid AND `active`='1' AND `deleted`='0'
            ", array(':mercs' => $mercs, ':fid' => $this->familyID));
        }
    }
}
