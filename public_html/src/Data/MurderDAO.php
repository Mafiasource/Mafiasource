<?PHP

namespace src\Data;

use src\Business\MurderService;
use src\Business\DonatorService;
use src\Business\PossessionService;
use src\Data\config\DBConfig;
use src\Data\PossessionDAO;
use src\Data\FamilyDAO;
use src\Data\UserDAO;
use src\Entities\Detective;
use src\Entities\MurderLog;
use src\Entities\User;

class MurderDAO extends DBConfig
{
    protected $con = ""; // Init
    private $dbh = ""; // Init, old query con var, slightly longer writing
    private $lang = "en";
    private $dateFormat = "%d-%m-%Y %H:%i:%s"; // SQL Format

    public function __construct()
    {
        global $lang;
        global $connection;
        
        $this->con = $connection;
        $this->dbh = $connection->con;
        $this->lang = $lang;
        if($this->lang == 'en') $this->dateFormat = "%m-%d-%Y %r"; // SQL Format
    }

    public function __destruct()
    {
        $this->dbh = null;
    }

    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            global $route;
            switch($route->getRouteName())
            {
                default:
                case 'murder-logs':
                case 'murder-logs-page':
                    $row = $this->con->getDataSR("
                        SELECT COUNT(`id`) AS `total` FROM `murder_log` WHERE (`attackerID`= :uid OR `victimID`= :uid) AND `active`='1' AND `deleted`='0' LIMIT 1
                    ", array(':uid' => $_SESSION['UID']));
                    break;
                case 'family-history':
                case 'family-history-page':
                    global $userData;
                    $fid = $userData->getFamilyID();
                    $familyData = new FamilyDAO();
                    $ids = $familyData->getImplodedFamilyMemberIds($fid);
                    
                    $row = $this->con->getDataSR("
                        SELECT COUNT(`id`) AS `total` FROM `murder_log` WHERE (`attackerID` IN (".$ids.") OR `victimID` IN (".$ids.")) AND `active`='1' AND `deleted`='0' LIMIT 1
                    ", array(':uid' => $_SESSION['UID']));
                    break;
            }
        }
        if(isset($row['total'])) return $row['total'];
    }
    
    public function getVictimCityIdByUserID($vid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `cityID` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':uid' => $vid));
            if(isset($row['cityID'])) return $row['cityID'];
        }
        return false;
    }
    
    public function hasAttackedVictimLast10Min($vid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `murder_log` WHERE `attackerID`= :uid AND `victimID`= :vid AND `time`> :timePast ORDER BY `time` DESC LIMIT 1
            ", array(':uid' => $_SESSION['UID'], ':vid' => $vid, ':timePast' => (time() - 600)));
            if(isset($row['id']) && $row['id'] > 0)
                return true;
        }
        return false;
    }
    
    public function isVictimInFamilyAlliance($vFid, $uFid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `family_alliance` WHERE `familyID`= :vFid AND `allianceFamilyID`= :uFid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':vFid' => $vFid, ':uFid' => $uFid));
            if(isset($row['id']) && $row['id'] > 0)
                return true;
        }
        return false;
    }
    
    public function isVictimByUserIdProtected($vid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `restartDate`, `isProtected` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':uid' => $vid));
            if(isset($row['restartDate']) && $row['isProtected'] == 1 && strtotime($row['restartDate']) > strtotime(date('Y-m-d H:i:s', strtotime("-3 days"))))
                return true;
        }
        return false;
    }
    
    public function victimKilledPossessionApply($pData)
    {
        if(isset($_SESSION['UID']))
        {
            if(is_object($pData)) $possessionOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $possessionOwner > 0 && $possessionOwner != $_SESSION['UID'])
            {
                global $userService;
                global $userData;
                $possessionService = new PossessionService();
                $familyPossessionLimit = $possessionService->familyCountryPossLimit;
                if($pData->getPossessDetails()->getState() != "" && $pData->getPossessDetails()->getCity() != '')
                    $familyPossessionLimit = $possessionService->familyCityPossLimit;
                elseif($pData->getPossessDetails()->getState() != "")
                    $familyPossessionLimit = $possessionService->familyStatePossLimit;
                
                $statusData = $userService->getStatusPageInfo();
                $reason = is_object($statusData) && $statusData->getIsProtected() ? 'status' : false;
                $reason = $possessionService->userHasPossessionById($pData->getPossessDetails()->getPID()) ? 'self' : $reason;
                $reason = $familyPossessionLimit == $possessionService->familyCountryPossLimit && $possessionService->userHasCountryPossession() ? 'self-country-poss' : $reason;
                $reason = $possessionService->familyHasAmountPossessionsById($pData->getPossessDetails()->getPID(), $userData->getFamilyID(), $familyPossessionLimit) ? 'family' : $reason;
                $reason = $familyPossessionLimit == $possessionService->familyCountryPossLimit && $possessionService->familyHasAmountCountryPossessions($userData->getFamilyID(),
                    $familyPossessionLimit) ? 'fam-country-poss' : $reason;
                if(isset($reason) && $reason !== false)
                    $uid = 0;
                else
                    $uid = $_SESSION['UID'];
                
                $possessionData = new PossessionDAO();
                $possessionData->takeOverOwner($pData, $uid, $possessionOwner);
                
                if($uid === 0)
                    return array('took-over' => false, 'reason' => $reason);
                elseif($reason === false)
                    return 'took-over';
            }
        }
    }
    
    public function murderMadeHeadshot($weaponExp, $victimUid)
    {
        if(isset($_SESSION['UID']))
        {
            if($weaponExp === false) $weaponExp = 0;
            $this->con->setData("
                UPDATE `user`
                SET `cash`=`cash`+'500000', `kills`=`kills`+'1', `headshots`=`headshots`+'1', `bullets`=`bullets`-'1', `weaponExperience`=`weaponExperience`+ :wpe
                WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1;
                UPDATE `user` SET `deaths`=`deaths`+'1' WHERE `id`= :vid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(
                ':wpe' => $weaponExp, ':uid' => $_SESSION['UID'],
                ':vid' => $victimUid
            ));
        }
    }
    
    public function murderAttackerDied($victimFiredBullets, $victimHealthLeft, $victimStoleMoney, $victimUid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `user`
                SET `cash`=`cash`+ :stolenMoney, `health`= :hl, `kills`=`kills`+'1', `bullets`=`bullets`- :b
                WHERE `id`= :vid AND `active`='1' AND `deleted`='0' LIMIT 1;
                UPDATE `user` SET `cash`=`cash`- :stolenMoney, `health`='0', `deaths`=`deaths`+'1' WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(
                ':stolenMoney' => $victimStoleMoney, ':hl' => $victimHealthLeft, ':b' => $victimFiredBullets, ':vid' => $victimUid,
                ':uid' => $_SESSION['UID']
            ));
        }
    }
    
    public function murderBothDied($victimUid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `user` SET `health`='0', `kills`=`kills`+'1', `deaths`=`deaths`+'1' WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1;
                UPDATE `user` SET `health`='0', `kills`=`kills`+'1', `deaths`=`deaths`+'1' WHERE `id`= :vid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(
                ':vid' => $victimUid,
                ':uid' => $_SESSION['UID']
            ));
        }
    }
    
    public function murderVictimDied($weaponExp, $firedBullets, $healthLeft, $stoleMoney, $victimUid)
    {
        if(isset($_SESSION['UID']))
        {
            if($weaponExp === false) $weaponExp = 0;
            $this->con->setData("
                UPDATE `user`
                SET `cash`=`cash`+ :stolenMoney, `health`= :hl, `kills`=`kills`+'1', `bullets`=`bullets`- :b, `weaponExperience`=`weaponExperience`+ :wpe
                WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1;
                UPDATE `user` SET `cash`=`cash`- :stolenMoney, `health`='0', `deaths`=`deaths`+'1' WHERE `id`= :vid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(
                ':stolenMoney' => $stoleMoney, ':hl' => $healthLeft, ':b' => $firedBullets, ':wpe' => $weaponExp, ':uid' => $_SESSION['UID'],
                ':vid' => $victimUid
            ));
        }
    }
    
    public function murderSurvivedVictimStole($weaponExp, $firedBullets, $healthLeft, $victimFiredBullets, $victimHealthLeft, $stoleMoney, $victimUid)
    {
        if(isset($_SESSION['UID']))
        {
            if($weaponExp === false) $weaponExp = 0;
            $this->con->setData("
                UPDATE `user`
                SET `cash`=`cash`- :stolenMoney, `health`= :hl, `bullets`=`bullets`- :b, `weaponExperience`=`weaponExperience`+ :wpe
                WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1;
                UPDATE `user`
                SET `cash`=`cash`+ :stolenMoney, `health`= :vhl, `bullets`=`bullets`- :vb
                WHERE `id`= :vid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(
                ':stolenMoney' => $stoleMoney, ':hl' => $healthLeft, ':b' => $firedBullets, ':wpe' => $weaponExp, ':uid' => $_SESSION['UID'],
                ':vhl' => $victimHealthLeft, ':vb' => $victimFiredBullets, ':vid' => $victimUid
            ));
        }
    }
    
    public function murderSurvivedAttackerStole($weaponExp, $firedBullets, $healthLeft, $victimFiredBullets, $victimHealthLeft, $stoleMoney, $victimUid)
    {
        if(isset($_SESSION['UID']))
        {
            if($weaponExp === false) $weaponExp = 0;
            $this->con->setData("
                UPDATE `user`
                SET `cash`=`cash`+ :stolenMoney, `health`= :hl, `bullets`=`bullets`- :b, `weaponExperience`=`weaponExperience`+ :wpe
                WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1;
                UPDATE `user`
                SET `cash`=`cash`- :stolenMoney, `health`= :vhl, `bullets`=`bullets`- :vb
                WHERE `id`= :vid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(
                ':stolenMoney' => $stoleMoney, ':hl' => $healthLeft, ':b' => $firedBullets, ':wpe' => $weaponExp, ':uid' => $_SESSION['UID'],
                ':vhl' => $victimHealthLeft, ':vb' => $victimFiredBullets, ':vid' => $victimUid
            ));
        }
    }
    
    public function addToMurderLog($vid, $result)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("INSERT INTO `murder_log` (`attackerID`, `victimID`, `time`, `result`) VALUES (:uid, :vid, :t, :r)", array(':uid' => $_SESSION['UID'], ':vid' => $vid,
                ':t' => time(), ':r' => $result)
            );
        }
    }
    
    public function killPlayerByUserID($uid)
    {
        if(isset($_SESSION['UID']))
        {
            $userDAO = new UserDAO();
            $userDAO->resetUser($uid);
            $this->con->setData("
                UPDATE `user`
                  SET `health`='0', `rankpoints`='0', `cash`='0', `whoresStreet`='0', `bullets`='0', `weapon`='0', `protection`='0', `airplane`='0', `weaponExperience`='0',
                    `weaponTraining`='0', `residence`='0', `residenceHistory`='', `power`='0', `cardio`='0', `luckybox`='0', `cCrimes`='0', `cWeaponTraining`='0',
                    `cGymTraining`='0', `cStealVehicles`='0', `cPimpWhores`='0', `cFamilyRaid`='0', `cFamilyCrimes`='0', `cBombardement`='0', `cTravelTime`='0'
                WHERE `id`= :uid AND `statusID`<='7' AND `health`>='0' AND `active`='1' AND `deleted`='0' LIMIT 1;
            ", array(':uid' => $uid));
        }
    }
    
    private function getTestamentHolderByUserID($vid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `testamentHolder` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':uid' => $vid));
            if(isset($row['testementHolder']) && is_numeric($row['testemantHolder']) && $row['testemantHolder'] > 0)
                return $row['testemantHolder'];
        }
        return false;
    }
    
    public function payOutTestementHolderByUserID($uid, $money)
    {
        $hid = $this->getTestamentHolderByUserID($uid);
        if(isset($_SESSION['UID']) && isset($hid) && $hid >= 0)
        {
            $this->con->setData("UPDATE `user` SET `bank`=`bank`+ :money WHERE `id`= :hid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':money' => $money, ':hid' => $hid));
        }
    }
    
    public function payOutKillerHitlist($kid, $money)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `user` SET `bank`=`bank`+ :money WHERE `id`= :kid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':money' => $money, ':kid' => $kid));
        }
    }
    
    public function getMurderInfoByUserID($uid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT `id`, `kills`, `deaths`, `headshots`, `weaponExperience`, `weaponTraining`, `power`, `cardio`,
                    `weapon`, `protection`, `bullets`, `isProtected`, `residence`, `restartDate`
                FROM `user`
                WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                LIMIT 1
            ");
            $statement->execute(array(':uid' => $uid));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $userObj = new User();
                $userObj->setIsProtected(false);
                $dateFormat = "d-m-Y H:i:s";
                if($this->lang == "en")
                    $dateFormat = "m-d-Y g:i:s A";
                
                if($row['isProtected'] == 1 && strtotime($row['restartDate']) > strtotime(date('Y-m-d H:i:s', strtotime("-3 days"))))
                    $userObj->setIsProtected(date($dateFormat, strtotime($row['restartDate'])+(60*60*24*3)));
                
                $userObj->setKills($row['kills']);
                $userObj->setWeaponExperience($row['weaponExperience']);
                $userObj->setWeaponTraining($row['weaponTraining']);
                $userObj->setPower($row['power']);
                $userObj->setCardio($row['cardio']);
                $gymTraining = 100;
                if((($row['power']+$row['cardio'])/2) < 100) $gymTraining = (($row['power']+$row['cardio'])/2);
                $userObj->setGymTrainingBar(array('training' => $gymTraining, 'class' => "bg-success"));
                $userObj->setBullets($row['bullets']);
                $userObj->setWeapon($row['weapon']);
                $userObj->setProtection($row['protection']);
                $userObj->setResidence($row['residence']);
                
                return $userObj;
            }
        }
    }
    
    public function getVictimsBackfireSettings($vid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `backfireType`, `backfireNumber` FROM `user` WHERE `id`= :vid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':vid' => $vid));
            
            if(isset($row['backfireType']) && $row['backfireType'] >= 0 && $row['backfireType'] <= 5)
                return $row;
        }
        return false;
    }
    
    public function getBackfireSettings()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `backfireType`, `backfireNumber` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':uid' => $_SESSION['UID']));
            
            if(isset($row['backfireType']) && $row['backfireType'] >= 0 && $row['backfireType'] <= 5)
                return $row;
        }
        return false;
    }
    
    public function setBackfire($type, $num = false)
    {
        if(isset($_SESSION['UID']))
        {
            $addSet = "";
            $addParams = array();
            if(($type === 0 || $type === 1) && $num !== false)
            {
                $addSet = ", `backfireNumber`= :num";
                $addParams[':num'] = $num;
            }
            $this->con->setData("
                UPDATE `user` SET `backfireType`= :type".$addSet." WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array_merge(array(':type' => $type, ':uid' => $_SESSION['UID']), $addParams));
        }
    }
    
    public function getDetectives()
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `detective` AS d
                SET d.`foundCityID`= (SELECT `cityID` FROM `user` WHERE `id`= d.`victimID` AND `active`='1' AND `deleted`='0')
                WHERE d.`userID`= :uid AND d.`timeFound`< :tf AND `shadow`='0' AND d.`active`='1' AND d.`deleted`='0';
                DELETE FROM `detective` AS dd WHERE DATE_ADD(hour,dd.`hours`,dd.`startDate`)< :tf
            ", array(':uid' => $_SESSION['UID'], ':tf' => time()));
            
            $deletions = $this->con->getData("SELECT `id`, DATE_ADD(`startDate`, INTERVAL `hours` HOUR) AS `dateAdd` FROM `detective` WHERE `userID`= :uid", array(':uid' => $_SESSION['UID']));
            foreach($deletions AS $d)
                if(strtotime($d['dateAdd']) < time())
                    $this->con->setData("DELETE FROM `detective` WHERE `id`= :id", array(':id' => $d['id']));
            
            $rows = $this->con->getData("
                SELECT d.`id`, d.`userID`, u.`username`, d.`victimID`, v.`username` AS `victim`, d.`startDate`, d.`hours`, d.`timeFound`,
                    d.`shadow`, d.`foundCityID`, vc.`id` AS `cityID`, vc.`name` AS `city`, fc.`name` AS `foundCity`
                FROM `detective` AS d
                LEFT JOIN `user` AS u
                ON (d.`userID`=u.`id`)
                LEFT JOIN `user` AS v
                ON (d.`victimID`=v.`id`)
                LEFT JOIN `city` AS vc
                ON (v.`cityID`=vc.`id`)
                LEFT JOIN `city` AS fc
                ON (d.`foundCityID`=fc.`id`)
                WHERE d.`userID`= :uid AND d.`active`='1' AND d.`deleted`='0'
                LIMIT 15
            ", array(':uid' => $_SESSION['UID']));
            
            $list = array();
            foreach($rows AS $row)
            {
                $detective = new Detective();
                $detective->setId($row['id']);
                $detective->setUserID($_SESSION['UID']);
                $detective->setUsername($row['username']);
                $detective->setVictimID($row['victimID']);
                $detective->setVictim($row['victim']);
                $detective->setStartDate($row['startDate']);
                $detective->setHours($row['hours']);
                $t = $row['hours'] * 3600;
                $tl = strtotime($row['startDate']) + $t - time();
                $detective->setTimeLeft($tl);
                $detective->setShadow(false);
                if($row['shadow'] == 1)
                    $detective->setShadow(true);
                
                if(time() > $row['timeFound'])
                {
                    $detective->setTimeFound($row['timeFound']);
                    if($detective->getShadow())
                    {
                        $detective->setCityID($row['cityID']);
                        $detective->setCity($row['city']);
                    }
                    else
                    {
                        $detective->setFoundCityID($row['foundCityID']);
                        $detective->setFoundCity($row['foundCity']);
                    }
                }
                array_push($list, $detective);
            }
            return $list;
        }
    }
    
    public function isVictimHiredByUserDetective($vid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `detective` WHERE `victimID`= :vid AND `userID`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':vid' => $vid, ':uid' => $_SESSION['UID']));
            if(isset($row['id']) && $row['id'] > 0)
                return true;
        }
        return false;
    }
    
    public function getDetectiveVictimRowCount()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT UNIX_TIMESTAMP(`startDate`) AS `startDate` FROM `detective` WHERE `userID`= :uid AND `active`='1' AND `deleted`='0' ORDER BY `startDate` DESC LIMIT 15
            ");
            if($statement->execute(array(':uid' => $_SESSION['UID'])))
                return $statement->rowCount();
        }
        return 0;
    }
    
    public function hireDetective($vid, $costs, $hours, $timeFound, $pData, $shadow = false)
    {
        if(isset($_SESSION['UID']))
        {
            $profitOwner = $costs;
            
            $this->con->setData("
                INSERT INTO `detective` (`userID`, `victimID`, `startDate`, `hours`, `timeFound`, `shadow`) VALUES (:uid, :vid, NOW(), :h, :tf, :sh);
                UPDATE `user` SET `cash`=`cash`- :c WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(
                ':uid' => $_SESSION['UID'], ':vid' => $vid, ':h' => $hours, ':tf' => $timeFound, ':sh' => $shadow,
                ':c' => $costs
            ));
            
            /** Possession logic for buying family garage | pay owner if exists and not self **/
            if(is_object($pData)) $deskOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $deskOwner > 0 && $deskOwner != $_SESSION['UID'])
            {
                $possessionData = new PossessionDAO();
                $possessionData->applyProfitForOwner($pData, $profitOwner, $deskOwner);
            }
        }
    }
    
    public function trainWeaponTraining()
    {
        if(isset($_SESSION['UID']))
        {
            global $security;
            global $userData;
            global $userService;
            $waitingTime = 300;
            $donatorService = new DonatorService();
            $waitingTime = $donatorService->adjustWaitingTime($waitingTime, $userData->getDonatorID(), $userData->getCHalvingTimes());
            
            $p = $security->randInt(1, 3);
            if(($userService->getStatusPageInfo()->getWeaponTraining() + $p) >= 100)
            {
                $this->con->setData("
                    UPDATE `user` SET `weaponTraining`='100', `cWeaponTraining`= :time WHERE `id`= :uid AND `weaponTraining`!='100' AND `active`='1' AND `deleted`='0' LIMIT 1
                ", array(':time' => (time() + $waitingTime), ':uid' => $_SESSION['UID']));
            }
            else
            {
                $this->con->setData("
                    UPDATE `user` SET `weaponTraining`=`weaponTraining`+ :p, `cWeaponTraining`= :time WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
                ",array(':p' => $p, ':time' => (time() + $waitingTime), ':uid' => $_SESSION['UID']));
            }
            return $p;
        }
    }
    
    public function getMurderLog($from, $to)
    {
        if(isset($_SESSION['UID']) && is_int($from) && is_int($to) && $to <= 50 && $to >=1)
        {
            $from = (int)round($from);
            $to = (int)round($to);
            global $userData;
            $attacks = $this->con->getData("
                SELECT ml.`id`, ml.`victimID`, v.`username` AS `victim`, ml.`time`, ml.`result`, DATE_FORMAT(FROM_UNIXTIME(ml.`time`), '".$this->dateFormat."') AS `dateFormat`
                FROM `murder_log` AS ml
                LEFT JOIN `user` AS v
                ON (ml.`victimID`=v.`id`)
                WHERE ml.`attackerID`= :uid AND ml.`active`='1' AND ml.`deleted`='0'
                ORDER BY `time` DESC
                LIMIT $from, $to
            ", array(':uid' => $_SESSION['UID']));
            
            $attackList = array();
            foreach($attacks AS $row)
            {
                $log = new MurderLog();
                $log->setId($row['id']);
                $log->setAttackerID($_SESSION['UID']);
                $log->setAttacker($userData->getUsername());
                $log->setVictimID($row['victimID']);
                $log->setVictim($row['victim']);
                $log->setTime($row['time']);
                $log->setDate($row['dateFormat']);
                $log->setResult($row['result']);
                
                array_push($attackList, $log);
            }
            
            $hits = $this->con->getData("
                SELECT  ml.`id`, ml.`attackerID`, a.`username` AS `attacker`, ml.`time`, ml.`result`, DATE_FORMAT(FROM_UNIXTIME(ml.`time`), '".$this->dateFormat."') AS `dateFormat`
                FROM `murder_log` AS ml
                LEFT JOIN `user` AS a
                ON (ml.`attackerID`=a.`id`)
                WHERE ml.`victimID`= :uid AND ml.`active`='1' AND ml.`deleted`='0'
                ORDER BY `time` DESC
                LIMIT $from, $to
            ", array(':uid' => $_SESSION['UID']));
            
            $hitList = array();
            foreach($hits AS $row)
            {
                $log = new MurderLog();
                $log->setId($row['id']);
                $log->setAttackerID($row['attackerID']);
                $log->setAttacker($row['attacker']);
                $log->setVictimID($_SESSION['UID']);
                $log->setVictim($userData->getUsername());
                $log->setTime($row['time']);
                $log->setDate($row['dateFormat']);
                $log->setResult($row['result']);
                
                array_push($hitList, $log);
            }
            return array('attacks' => $attackList, 'hits' => $hitList);
        }
        return FALSE;
    }
    
    public function getFamilyMurderLog($from, $to)
    {
        if(isset($_SESSION['UID']) && is_int($from) && is_int($to) && $to <= 50 && $to >=1)
        {
            $from = (int)round($from);
            $to = (int)round($to);
            global $userData;
            $fid = $userData->getFamilyID();
            $familyData = new FamilyDAO();
            $ids = $familyData->getImplodedFamilyMemberIds($fid);
            
            $attacks = $this->con->getData("
                SELECT ml.`id`, ml.`attackerID`, a.`username` AS `attacker`, ml.`victimID`, v.`username` AS `victim`, ml.`time`, ml.`result`,
                    DATE_FORMAT(FROM_UNIXTIME(ml.`time`), '".$this->dateFormat."') AS `dateFormat`
                FROM `murder_log` AS ml
                LEFT JOIN `user` AS a
                ON (ml.`attackerID`=a.`id`)
                LEFT JOIN `user` AS v
                ON (ml.`victimID`=v.`id`)
                WHERE ml.`attackerID` IN(".$ids.") AND ml.`active`='1' AND ml.`deleted`='0'
                ORDER BY `time` DESC
                LIMIT $from, $to
            ");
            
            $attackList = array();
            foreach($attacks AS $row)
            {
                $log = new MurderLog();
                $log->setId($row['id']);
                $log->setAttackerID($row['attackerID']);
                $log->setAttacker($row['attacker']);
                $log->setVictimID($row['victimID']);
                $log->setVictim($row['victim']);
                $log->setTime($row['time']);
                $log->setDate($row['dateFormat']);
                $log->setResult($row['result']);
                
                array_push($attackList, $log);
            }
            
            $hits = $this->con->getData("
                SELECT  ml.`id`, ml.`attackerID`, a.`username` AS `attacker`, ml.`victimID`, v.`username` AS `victim`, ml.`time`, ml.`result`,
                    DATE_FORMAT(FROM_UNIXTIME(ml.`time`), '".$this->dateFormat."') AS `dateFormat`
                FROM `murder_log` AS ml
                LEFT JOIN `user` AS a
                ON (ml.`attackerID`=a.`id`)
                LEFT JOIN `user` AS v
                ON (ml.`victimID`=v.`id`)
                WHERE ml.`victimID` IN(".$ids.") AND ml.`active`='1' AND ml.`deleted`='0'
                ORDER BY `time` DESC
                LIMIT $from, $to
            ");
            
            $hitList = array();
            foreach($hits AS $row)
            {
                $log = new MurderLog();
                $log->setId($row['id']);
                $log->setAttackerID($row['attackerID']);
                $log->setAttacker($row['attacker']);
                $log->setVictimID($row['victimID']);
                $log->setVictim($row['victim']);
                $log->setTime($row['time']);
                $log->setDate($row['dateFormat']);
                $log->setResult($row['result']);
                
                array_push($hitList, $log);
            }
            return array('attacks' => $attackList, 'hits' => $hitList);
        }
        return FALSE;
    }
}
