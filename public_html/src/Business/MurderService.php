<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\HitlistService;
use src\Business\PossessionService;
use src\Business\EquipmentService;
use src\Business\ResidenceService;
use src\Business\MissionService;
use src\Business\NotificationService;
use src\Data\MurderDAO;

class MurderService
{
    private $data;
    
    public $maxDetectives = 3;

    public function __construct()
    {
        $this->data = new MurderDAO();
        global $userData;
        if($userData->getDonatorID() == 5)
            $this->maxDetectives = 5;
        elseif($userData->getDonatorID() == 10)
            $this->maxDetectives = 10;
    }

    public function __destruct()
    {
        $this->data = null;
    }

    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function getDetectivreHourCosts()
    {
        $hourCosts = array();
        for($i = 2; $i <= 24; $i++)
            $hourCosts[$i] = $i * 100000;
        
        return $hourCosts;
    }
    
    public function handlePossessionResponse($possResponse, $psData)
    {
        if(!is_bool($possResponse) && is_object($psData))
        {
            global $language;
            $pl       = $language->murderLangs();
            global $route;
            if(is_array($possResponse))
            {
                switch($possResponse['reason'])
                {
                    default: case 'status':
                        $msgAdd = $pl['TAKE_OVER_POSSESSION_STATUS_ERROR'];
                        break;
                    case 'self':
                        $msgAdd = $pl['TAKE_OVER_POSSESSION_SELF_ERROR'];
                        break;
                    case 'self-country-poss':
                        $msgAdd = $l['TAKE_OVER_POSSESSION_SELF_COUNTRY_ERROR'];
                        break;
                    case 'family':
                        $msgAdd = $pl['TAKE_OVER_POSSESSION_FAMILY_ERROR'];
                        break;
                    case 'family-country-poss':
                        $msgAdd = $l['TAKE_OVER_POSSESSION_FAMILY_COUNTRY_ERROR'];
                        break;
                }
            }
            elseif($possResponse == 'took-over')
                $msgAdd = $pl['TAKE_OVER_POSSESSION_TOOK_OVER'];
            
            $replacedMessage = $route->replaceMessagePart(strtolower($psData->getName()), $msgAdd, '/{possessionName}/');
            
            return $replacedMessage;
        }
        return false;
    }
    
    public function murderPlayer($post)
    {
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l        = $language->murderLangs();
        $bullets = isset($_POST['bullets']) ? (int)round($_POST['bullets']) : null;
        
        $murderData = $this->data->getMurderInfoByUserID($userData->getId());
        $victimProfile = $userService->getUserProfile($post['victim']);
        if(is_object($victimProfile)) $victimID = $victimProfile->getId() ? $victimProfile->getId() : FALSE;
        if(isset($victimID))
        {
            $check = $this->data->hasAttackedVictimLast10Min($victimID);
            $checkFamAlliance = $this->data->isVictimInFamilyAlliance($victimProfile->getFamilyID(), $userData->getFamilyID());
        }
        $victimIsScumFiveDaysOff = is_object($victimProfile) ? ( $victimProfile->getRankID() == 0 && $victimProfile->getLastclick() < (time() - (60*60*24*5))) : false;
        
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if(is_object($murderData) && $murderData->getIsProtected())
        {
            $error = $l['CANT_MURDER_WITH_PROTECTION'];
        }
        if(isset($bullets) && $murderData->getWeapon() != 0)
        {
            if($bullets < 1)
            {
                $error = $l['FIRE_1_BULLET_MIN'];
            }
            if($bullets > $murderData->getBullets())
            {
                $error = $l['DONT_OWN_THAT_MANY_BULLETS'];
            }
            if(strlen($bullets) > 10)
            {
                $error = $l['INVALID_AMOUNT_OF_BULLETS'];
            }
        }
        if($userData->getCash() < 0 || $userData->getBank() < 0)
        {
            $error = $l['CANNOT_MURDER_WITH_CASH_OR_BANK_IN_MIN'];
        }
        if(isset($check) && $check == true)
        {
            $error = $l['ALREADY_ATTACKED_PLAYER_LAST_10MIN'];
        }
        if(isset($checkFamAlliance) && $checkFamAlliance == true)
        {
            $error = $l['CANT_MURDER_PLAYER_IN_ALLIANCE_WITH_FAMILY'];
        }
        if(is_object($victimProfile))
        {
            if($userData->getCityID() != $this->data->getVictimCityIdByUserID($victimID))
            {
                $error = $l['NOT_IN_SAME_CITY'];
            }
            if($victimProfile->getHealth() <= 0)
            {
                $error = $l['PLAYER_ALREADY_DEAD'];
            }
            if($this->data->isVictimByUserIdProtected($victimID))
            {
                $error = $l['CANT_MURDER_PLAYER_WITH_PROTECTION'];
            }
            if($victimProfile->getStatusID() <= 2)
            {
                $error = $l['CANT_MURDER_TEAM_MEMBER'];
            }
            if(($victimProfile->getCash() < 0 || $victimProfile->getBank() < 0))
            {
                $error = $l['CANNOT_MURDER_PLAYER_WITH_CASH_OR_BANK_IN_MIN'];
            }
            if($userData->getFamilyID() == $victimProfile->getFamilyID() && $userData->getFamilyID() !== 0)
            {
                $error = $l['CANT_MURDER_PLAYER_INSIDE_FAMILY'];
            }
            if(
                (($userData->getRankID() - $victimProfile->getRankID() > 3) || ($userData->getRankID() - $victimProfile->getRankID() < -3) ||
                    ($userData->getRankID() == 11 && $victimProfile->getRankID() != 11) || ($userData->getRankID() != 11 && $victimProfile->getRankID() == 11)
                ) && !$victimIsScumFiveDaysOff
            )
            {
                $error = $l['CANNOT_ATTACK'];
            }
        }
        if((isset($victimID) && is_bool($victimID) && $victimID == FALSE ) || !is_object($victimProfile))
        {
            $error = $langs['PLAYER_DOESNT_EXIST'];
        }
        if(isset($victimID) && !is_bool($victimID) && $_SESSION['UID'] == $victimID)
        {
            $error = $langs['CANNOT_COMMIT_ACTION_SELF'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $replacedMessage = ''; // Init
            $victimMurderData = $this->data->getMurderInfoByUserID($victimID);
            $victimBfSettings = $this->data->getVictimsBackfireSettings($victimID);
            
            $weaponService = new EquipmentService("weapon");
            $protectionService = new EquipmentService("protection");
            $residenceService = new ResidenceService();
            $weapons = $weaponService->getEquipmentPage();
            $protections = $protectionService->getEquipmentPage();
            $residences = $residenceService->getResidencePage();
            
            $bulletList = array(200, 300, 500, 1000, 1500, 2500, 4000, 5000, 7500, 12500, 20000, 25000, 35000, 50000, 75000, 115000, 175000, 225000, 300000, 450000);
            $weaponList = array(0 => 1.0);
            foreach($weapons AS $w)
                $weaponList[] = 1 - ($w->getDamage() / 100);
            
            $protectionList = array(0 => 1.0);
            foreach($protections AS $p)
                $protectionList[] = 1 + ($p->getProtection() / 100);
            
            $defenceList = array(0 => 1.0);
            foreach($residences AS $r)
                $defenceList[] = 1 + ($r->getDefence() / 100);
            
            $knife = array(
                $security->randInt(26, 40),
                $security->randInt(21, 30),
                $security->randInt(17, 25),
                $security->randInt(14, 22),
                $security->randInt(12, 18),
                $security->randInt(9, 14),
                $security->randInt(6, 12),
                $security->randInt(4, 6),
                $security->randInt(3, 5),
                $security->randInt(2, 4),
                $security->randInt(2, 3),
                $security->randInt(1, 3),
                $security->randInt(1, 2),
           	);
            
            $bulletsToKill = round(
                (($bulletList[$userData->getRankID()] * $protectionList[$murderData->getProtection()]) * $defenceList[$murderData->getResidence()]) * $weaponList[$victimMurderData->getWeapon()], 0
            );
            $victimBulletsToKill = round(
                (($bulletList[$victimProfile->getRankID()] * $protectionList[$victimMurderData->getProtection()]) * $defenceList[$victimMurderData->getResidence()]) * $weaponList[$murderData->getWeapon()], 0
            );
            
            $bulletsToKill += $murderData->getKills() * 2;
            $victimBulletsToKill += $victimMurderData->getKills() * 2;
            
            if($victimMurderData->getWeaponExperience() > $murderData->getWeaponExperience())
            {
                $factor = 1 - (($victimMurderData->getWeaponExperience() - $murderData->getWeaponExperience()) / 1000);
                $victimBulletsToKill = round($factor * $victimBulletsToKill, 0);
            }
            elseif($murderData->getWeaponExperience() > $victimMurderData->getWeaponExperience())
            {
                $factor = 1 - (($murderData->getWeaponExperience() - $victimMurderData->getWeaponExperience()) / 1000);
                $bulletsToKill = round($factor * $bulletsToKill, 0);
            }
            if($victimBfSettings['backfireType'] == 0)
            {
                $victimBullets = round($victimMurderData->getBullets() * ($victimBfSettings['backfireNumber'] / 100), 0);
            }
            elseif($victimBfSettings['backfireType'] == 1)
            {
                $victimBullets = $victimBfSettings['backfireNumber'];
                if($victimBfSettings['backfireNumber'] > $victimMurderData->getBullets())
                {
                    $victimBullets = $victimMurderData->getBullets();
                }
            }
            elseif($victimBfSettings['backfireType'] == 2)
            {
                $victimBullets = $victimMurderData->getBullets();
            }
            elseif($victimBfSettings['backfireType'] == 3)
            {
                if(round($bullets * 2, 0) > $victimMurderData->getBullets())
    			{
    				$victimBullets = $victimMurderData->getBullets();
    			} 
    			else 
    			{
    				$victimBullets = round($bullets * 2, 0);
    			}
            }
            elseif($victimBfSettings['backfireType'] == 4)
            {
                if($bullets > $victimMurderData->getBullets())
    			{
    				$victimBullets = $victimMurderData->getBullets();
    			}
    			else 
    			{
    				$victimBullets = $bullets;
    			}
            }
    		elseif($victimBfSettings['backfireType'] == 5)
    		{
    			if(round($bullets / 2, 0) > $victimMurderData->getBullets())
    			{
    				$victimBullets = $victimMurderData->getBullets();
    			} 
    			else 
    			{
    				$victimBullets = round($bullets / 2, 0);
    			}
    	    }
            $weaponExperienceGained = false;
            if($murderData->getWeaponExperience() < 100)
            {
                $weaponExperienceGained = $security->randInt(1, 3);
                if($weaponExperienceGained + $murderData->getWeaponExperience() > 100)
                {
                    $weaponExperienceGained = 100 - $murderData->getWeaponExperience();
                }
                $replacedMessage = $route->replaceMessagePart($weaponExperienceGained, $l['MURDER_PLAYER_SUCCESS_WEAPON_EXP'], '/{exp}/');
            }
            
            $possessionService = new PossessionService();
            $victimPossessions = $possessionService->getProfilePossessionsByUserID($victimID);
            
            $died = array();
            
            $headshotRand = 0;
            if($murderData->getWeapon() != 0)
                $headshotRand = $security->randInt(1, 100);
            
            if($headshotRand == 50)
            { // HEADSHOT
                $result = "05";
                $replacedMessage .= $route->replaceMessagePart($victimProfile->getUsername(), $l['MURDER_PLAYER_SUCCESS_HEADSHOT'], '/{victim}/');
                
                $hitlistService = new HitlistService();
                $hitlistData = $hitlistService->getHitlistDataByUserID($victimID);
                if(is_object($hitlistData) && $hitlistData->getOrdererID() != $userData->getId())
                {
                    $this->data->payOutKillerHitlist($userData->getId(), $hitlistData->getPrize());
                    
                    $replaces = array(
                        array('part' => $victimProfile->getUsername(), 'message' => $l['MURDER_PLAYER_ON_HITLIST_SUCCESS'], 'pattern' => '/{victim}/'),
                        array('part' => number_format($hitlistData->getPrize(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{prize}/')
                    );
                    
                    $replacedMessage .= $route->replaceMessageParts($replaces);
                }
                foreach($victimPossessions AS $vp)
                {
                    $possResponse = $this->data->victimKilledPossessionApply($vp);
                    $replacedMessage .= $this->handlePossessionResponse($possResponse, $vp);
                }
                // Send notification
                $notification = new NotificationService();
                $params = "attacker=".$userData->getUsername();
                $notification->sendNotification($victimID, 'MURDERED_BY_ATTACKER_HEADSHOT', $params);
                
                $died[] = $victimProfile;
            }
            else
            { // Anything but HEADSHOT
                if($murderData->getWeapon() == 0)
                {
                    $bullets = 0;
                    $healthHit = round((($knife[$victimProfile->getRankID()] / ($victimMurderData->getProtection() + 1)) / ($victimMurderData->getResidence() + 1)), 0);
                }
    			elseif($murderData->getWeapon() > 0)
                    $healthHit = round($bullets / $victimBulletsToKill * 100, 0);
                
                if($victimMurderData->getWeapon() == 0)
                {
                    $victimBullets = 0;
                    $victimHealthHit = round((($knife[$userData->getRankID()] / ($murderData->getProtection() + 1)) / ($murderData->getResidence() + 1)), 0);
                }
    			elseif($victimMurderData->getWeapon() > 0)
    				$victimHealthHit = round($victimBullets / $bulletsToKill * 100, 0);
                
    	    	$healthLeft = $userData->getHealth() - $victimHealthHit;
    	    	$victimHealthLeft = $victimProfile->getHealth() - $healthHit;
                
    		    if($healthLeft < 1 && $victimHealthLeft > 0)
                { // Attacker died, victim survived
                    $result = "01";
                    $stolenMoney = round($userData->getCash() * ($security->randInt(20, 25) / 100), 0);
                    
                    $replaces = array(
                        array('part' => $victimProfile->getUsername(), 'message' => $l['MURDER_SUCCESS_DIED_VICTIM_SURVIVED'], 'pattern' => '/{victim}/'),
                        array('part' => number_format($stolenMoney, 0, '', ','), 'message' => FALSE, 'pattern' => '/{stolenMoney}/')
                    );
                    
                    $replacedMessage .= $route->replaceMessageParts($replaces);
                    
                    $hitlistService = new HitlistService();
                    $hitlistData = $hitlistService->getHitlistDataByUserID($userData->getId());
                    if(is_object($hitlistData))
                    {
                        $this->data->payOutKillerHitlist($victimID, $hitlistData->getPrize());
                        
                        // Send notification
                        $notification = new NotificationService();
                        $params = "attacker=".$userData->getUsername()."&stolenMoney=".number_format($stolenMoney, 0, '', ',')."&prize=".number_format($hitlistData->getPrize(), 2, '', ',');
                        $notification->sendNotification($victimID, 'SURVIVED_ATTACK_KILLED_ATTACKER_ADD_HITLIST', $params);
                    }
                    else
                    {
                        // Send notification
                        $notification = new NotificationService();
                        $params = "attacker=".$userData->getUsername()."&stolenMoney=".number_format($stolenMoney, 0, '', ',');
                        $notification->sendNotification($victimID, 'SURVIVED_ATTACK_KILLED_ATTACKER', $params);
                    }
                    $died[] = $userData;
                }
                elseif($healthLeft < 1 && $victimHealthLeft < 1)
                { // Both died
                    $result = "00";
                    $replacedMessage .= $route->replaceMessagePart($victimProfile->getUsername(), $l['MURDER_SUCCESS_BOTH_DIED'], '/{victim}/');
                    
                    // Send notification
                    $notification = new NotificationService();
                    $params = "attacker=".$userData->getUsername();
                    $notification->sendNotification($victimID, 'DIED_IN_ATTACK_KILLED_ATTACKER', $params);
                    
                    $died[] = $userData;
                    $died[] = $victimProfile;
                }
    			elseif($healthLeft > 0 && $victimHealthLeft < 1)
                { //  Victim died, attacker survived
                    $result = "10";
                    $stolenMoney = round($victimProfile->getCash() * ($security->randInt(20, 25) / 100), 0);
                    
                    $replaces = array(
                        array('part' => $victimProfile->getUsername(), 'message' => $l['MURDER_SUCCESS_KILLED_VICTIM'], 'pattern' => '/{victim}/'),
                        array('part' => number_format($stolenMoney, 0, '', ','), 'message' => FALSE, 'pattern' => '/{stolenMoney}/')
                    );
                    
                    $replacedMessage .= $route->replaceMessageParts($replaces);
                    
                    $hitlistService = new HitlistService();
                    $hitlistData = $hitlistService->getHitlistDataByUserID($victimID);
                    if(is_object($hitlistData) && $hitlistData->getOrdererID() != $userData->getId())
                    {
                        $this->data->payOutKillerHitlist($userData->getId(), $hitlistData->getPrize());
                        
                        $replaces = array(
                            array('part' => $victimProfile->getUsername(), 'message' => $l['MURDER_PLAYER_ON_HITLIST_SUCCESS'], 'pattern' => '/{victim}/'),
                            array('part' => number_format($hitlistData->getPrize(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{prize}/')
                        );
                        
                        $replacedMessage .= $route->replaceMessageParts($replaces);
                    }
                    foreach($victimPossessions AS $vp)
                    {
                        $possResponse = $this->data->victimKilledPossessionApply($vp);
                        $replacedMessage .= $this->handlePossessionResponse($possResponse, $vp);
                    }
                    // Send notification
                    $notification = new NotificationService();
                    $params = "attacker=".$userData->getUsername();
                    $notification->sendNotification($victimID, 'MURDERED_BY_ATTACKER', $params);
                    
                    $died[] = $victimProfile;
                }
                elseif($healthLeft > 0 && $victimHealthLeft > 0)
                { // Both survived
                    $result = "11";
                    if($victimHealthHit > $healthHit)
                    {
                        $stolenMoney = round($userData->getCash() * (rand(20, 25) / 100), 0);
                        
                        $replaces = array(
                            array('part' => $victimProfile->getUsername(), 'message' => $l['MURDER_SUCCESS_BOTH_SURVIVED_VICTIM_STOLE'], 'pattern' => '/{victim}/'),
                            array('part' => number_format($stolenMoney, 0, '', ','), 'message' => FALSE, 'pattern' => '/{stolenMoney}/')
                        );
                        
                        $replacedMessage .= $route->replaceMessageParts($replaces);
                        
                        // Send notification
                        $notification = new NotificationService();
                        $params = "attacker=".$userData->getUsername()."&stolenMoney=".number_format($stolenMoney, 0, '', ',');
                        $notification->sendNotification($victimID, 'SURVIVED_ATTACK_STOLE_ATTACKER', $params);
                    }
                    else
                    {
                        $stolenMoney = round($victimProfile->getCash() * (rand(20, 25) / 100), 0);
                        
                        $replaces = array(
                            array('part' => $victimProfile->getUsername(), 'message' => $l['MURDER_SUCCESS_BOTH_SURVIVED_ATTACKER_STOLE'], 'pattern' => '/{victim}/'),
                            array('part' => number_format($stolenMoney, 0, '', ','), 'message' => FALSE, 'pattern' => '/{stolenMoney}/')
                        );
                        
                        $replacedMessage .= $route->replaceMessageParts($replaces);
                        
                        // Send notification
                        $notification = new NotificationService();
                        $params = "attacker=".$userData->getUsername()."&stolenMoney=".number_format($stolenMoney, 0, '', ',');
                        $notification->sendNotification($victimID, 'SURVIVED_ATTACK_ATTACKER_STOLE', $params);
                    }
                }
            }
            
            /* Check killfrenzy mission achievement for (possibly both) kill(s) */
            $m = 6;
            $missionService = new MissionService();
            foreach($died AS $d)
            {
                $o = ($d->getId() == $userData->getId()) ? $victimProfile : $userData;
                $mTierProgress = $missionService->getMissionTierAndProgressByMission($m, $o->getId());
                
                $missionTier = $missionService->missionTiers[$m];
                $todo = $missionTier['todo'][$mTierProgress['t']];
                $bank = $missionTier['prizeMoney'][$mTierProgress['t']];
                $hp = $missionTier['prizeHp'][$mTierProgress['t']];
                if($mTierProgress['p'] + 1 >= $todo && $todo > $mTierProgress['p'])
                {
                    $missionService->payoutMissionPrize($bank, $hp, $o->getId());
                    
                    $notification = new NotificationService();
                    $params = "mission=".$missionService->missions[$m]."&bank=".number_format($bank, 0, '', ',')."&hp=".number_format($hp, 0, '', ',');
                    $notification->sendNotification($o->getId(), 'USER_ACHIEVED_MISSION', $params);
                }
            }
            
            /* Apply final data layer changes after check killfrenzy mission, before returning replaced action message */
            switch($result)
            {
                default:
                case "05": // HEADSHOT
                    $this->data->murderMadeHeadshot($weaponExperienceGained, $victimID);
                    break;
                case "01": // Attacker died, victim survived
                    $this->data->murderAttackerDied($victimBullets, $victimHealthLeft, $stolenMoney, $victimID);
                    break;
                case "00": // Both died
                    $this->data->murderBothDied($victimID);
                    break;
                case "10": // Victim died, attacked survived
                    $this->data->murderVictimDied($weaponExperienceGained, $bullets, $healthLeft, $stolenMoney, $victimID);
                    break;
                case "11": // Both survived
                    if($victimHealthHit > $healthHit)
                        $this->data->murderSurvivedVictimStole($weaponExperienceGained, $bullets, $healthLeft, $victimBullets, $victimHealthLeft, $stolenMoney, $victimID);
                    else
                        $this->data->murderSurvivedAttackerStole($weaponExperienceGained, $bullets, $healthLeft, $victimBullets, $victimHealthLeft, $stolenMoney, $victimID);
                    
                    break;
            }
            foreach($died AS $d)
            {
                $this->data->payOutTestementHolderByUserID($d->getId(), round($d->getBank() / 2, 0));
                $this->data->killPlayerByUserID($d->getId());
            }
            $this->data->addToMurderLog($victimID, $result);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function setBackfire($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l        = $language->murderLangs();
        $type = (int)round($post['backfire-type']);
        if($type === 0)
            $percentage = (int)round($post['backfire-percentage']);
        elseif($type === 1)
            $number = (int)round($_POST['backfire-number']);
        
        $bfSettings = $this->data->getBackfireSettings();
        $setType = $bfSettings['backfireType'];
        $setNumber = $bfSettings['backfireNumber'];
        
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if($type < 0 || $type > 5)
        {
            $error = $l['INVALID_BACKFRITE_TYPE'];
        }
        if(isset($percentage) && ($percentage < 0 || $percentage > 100))
        {
            $error = $l['BACKFIRE_PERCENT_BETWEEN_0_AND_100'];
        }
        if(isset($number) && $number < 0)
        {
            $error = $l['BACKFIRE_NUMBER_HIGHER_OR_0'];
        }
        if($type == $setType && (isset($percentage) && $percentage  == $setNumber || isset($number) && $number == $setNumber || $type >=2))
        {
            $error = $l['BACKFIRE_SETTINGS_SAME_AS_CURRENT'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            if($type === 0)
                $this->data->setBackfire($type, $percentage);
            elseif($type === 1)
                $this->data->setBackfire($type, $number);
            else
                $this->data->setBackfire($type);
            
            return Routing::successMessage($l['SET_BACKFIRE_SUCCESS']);
        }
    }
    
    public function hireDetective($post)
    {
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l        = $language->murderLangs();
        $time = (int)round($post['time']);
        $shadow = isset($post['shadow']) ? true : false;
        $shadowCosts = isset($shadow) && $shadow === true ? 2500000 : 0;
        
        $statusData = $userService->getStatusPageInfo();
        $victimProfile = $userService->getUserProfile($post['username']);
        if(is_object($victimProfile)) $victimID = $victimProfile->getId() ? $victimProfile->getId() : FALSE;
        if(isset($victimID)) $check = $this->data->isVictimHiredByUserDetective($victimID);
        $victimIsScumFiveDaysOff = is_object($victimProfile) ? ( $victimProfile->getRankID() == 0 && $victimProfile->getLastclick() < (time() - (60*60*24*5))) : false;
        
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if(is_object($statusData) && $statusData->getIsProtected())
        {
            $error = $l['CANT_HIRE_WITH_PROTECTION'];
        }
        if($time < 2 || $time > 24)
        {
            $error = $l['INVALID_TIME'];
        }
        else
        {
            $costs = $this->getDetectivreHourCosts()[$time] + $shadowCosts;
        }
        if(isset($costs) && $costs > $userData->getCash())
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if($this->data->getDetectiveVictimRowCount() >= $this->maxDetectives)
        {
            $error = $l['ALL_DETECTIVES_IN_USE'];
        }
        if(isset($check) && $check == true)
        {
            $error = $l['PLAYER_ALREADY_IN_SEARCHLIST'];
        }
        if(is_object($victimProfile) && $victimProfile->getHealth() <= 0)
        {
            $error = $l['PLAYER_ALREADY_DEAD'];
        }
        if(is_object($victimProfile) &&
            (($userData->getRankID() - $victimProfile->getRankID() > 3) || ($userData->getRankID() - $victimProfile->getRankID() < -3) ||
                ($userData->getRankID() == 11 && $victimProfile->getRankID() != 11) || ($userData->getRankID() != 11 && $victimProfile->getRankID() == 11)
            ) && !$victimIsScumFiveDaysOff
        )
        {
            $error = $l['CANNOT_ATTACK_CANNOT_HIRE'];
        }
        if((isset($victimID) && is_bool($victimID) && $victimID == FALSE ) || !is_object($victimProfile))
        {
            $error = $langs['PLAYER_DOESNT_EXIST'];
        }
        if(isset($victimID) && !is_bool($victimID) && $_SESSION['UID'] == $victimID)
        {
            $error = $langs['CANNOT_COMMIT_ACTION_SELF'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $possession = new PossessionService();
            $possessionId = 11; //Detectieve desk | Possession logic
            $possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID(), $userData->getCityID()); // Possess table record id |Stad bezitting
            $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
        
            global $route;
            $timeFound = time() + $security->randInt(900, 3600);
            $this->data->hireDetective($victimID, $costs, $time, $timeFound, $pData, $shadow);
            
            $replacedMessage = $route->replaceMessagePart($victimProfile->getUsername(), $l['HIRE_DETECTIVE_SUCCESS'], '/{victim}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function trainWeaponTraining($post)
    {
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l = $language->murderLangs();
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if($userData->getCWeaponTraining() > time())
        {
            $error = $langs['WAITING_TIME_NOT_PASSED'];
        }
        if($userService->getStatusPageInfo()->getWeaponTraining() >= 100)
        {
            $error = $l['ALREADY_100_WEAPON_TRAINING'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;            
            $percentGained = $this->data->trainWeaponTraining();
            $replacedMessage = $route->replaceMessagePart($percentGained, $l['TRAIN_WEAPON_TRAINING_SUCCESS'], '/{percent}/');
            return Routing::successMessage($replacedMessage);            
        }
    }
    
    public function getBackfireSettings()
    {
        return $this->data->getBackfireSettings();
    }
    
    public function getDetectives()
    {
        return $this->data->getDetectives();
    }
    
    public function getMurderLog($from, $to)
    {
        return $this->data->getMurderLog($from, $to);
    }
    
    public function getFamilyMurderLog($from, $to)
    {
        return $this->data->getFamilyMurderLog($from, $to);
    }
}
