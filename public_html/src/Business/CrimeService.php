<?PHP

namespace src\Business;

use src\Business\Logic\game\Statics\Crimes AS CrimeStatics;
use src\Business\PrisonService;
use src\Business\GarageService;
use src\Business\MissionService;
use src\Business\NotificationService;
use src\Business\DailyChallengeService;
use src\Business\PublicMissionService;
use src\Data\CrimeDAO;
use app\config\Routing;

/* Class has some cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */

class CrimeService extends CrimeStatics
{
    private $data;
    public $weapons = array(
        1 => array('name' => "UZI", 'price' => 25000),
        array('name' => "Stg44", 'price' => 50000),
        array('name' => "M4", 'price' => 100000)
    );
    public $intel = array(
        1 => array('name' => "Basic", 'price' => 25000),
        array('name' => "Intermediate", 'price' => 50000),
        array('name' => "Most", 'price' => 75000),
        array('name' => "Thorough", 'price' => 100000)
    );

    public function __construct()
    {
        $this->data = new CrimeDAO();
    }

    public function __destruct()
    {
        $this->data = null;
    }

    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function commitCrime($post)
    {
        global $userData;
        global $language;
        global $langs;
        $l = $language->crimesLangs();
        global $security;
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        $crimeData = $this->data->getCrimeById($post['id']);
        $userCrimeLv = $this->data->getCrimesPageInfo()['user']->getCrimesLv();
        $userCrimeXp = $this->data->getCrimesPageInfo()['user']->getCrimesXpRaw();
        if($crimeData == FALSE || $crimeData->getLevel() > $userCrimeLv)
        {
            $error = $l['INVALID_CRIME_SELECTED'];
        }
        if($userData->getCCrimes() > time())
        {
            $error = $langs['WAITING_TIME_NOT_PASSED'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $chance = $security->randInt(0, 100);
            if($userData->getCharType() == 3)
                $chance = $security->randInt(5, 100);
            
            if($crimeData->getDifficulty() < $chance)
            {
                $success = false; // Init
                $chance2 = $security->randInt(0, 100);
                if($userCrimeLv > $crimeData->getDifficulty() && $chance2 > 25) $success = TRUE;
                elseif($userCrimeLv == $crimeData->getDifficulty() && $chance2 > 50) $success = TRUE;
                elseif($userCrimeLv < $crimeData->getDifficulty() && $chance2 > 75) $success = TRUE;
                else $success = FALSE;

                if($success == TRUE)
                {
                    global $route;
                    global $userService;
                    $maxRp = $crimeData->getMaxRankpoints();
                    $minRp = $crimeData->getMaxRankpoints() - 3;
                    if($minRp < 0) $minRp = 1;
                    $stolenMoney = $security->randInt($crimeData->getMinProfit(), $crimeData->getMaxProfit());
                    $rpCollected = $security->randInt($minRp, $maxRp);
                    $newLvlData = self::levelCalculations($userCrimeLv, $userCrimeXp);
                    
                    if($userCrimeLv < $newLvlData['levelAfter'])
                    {
                        $m = 2;
                        $missionService = new MissionService();
                        $mTierProgress = $missionService->getMissionTierAndProgressByMission($m);
                        
                        $missionTier = $missionService->missionTiers[$m];
                        $todo = $missionTier['todo'][$mTierProgress['t']];
                        $bank = $missionTier['prizeMoney'][$mTierProgress['t']];
                        $hp = $missionTier['prizeHp'][$mTierProgress['t']];
                        if($mTierProgress['p'] + 1 >= $todo && $todo > $mTierProgress['p'])
                        {
                            $missionService->payoutMissionPrize($bank, $hp);
                            
                            $notification = new NotificationService();
                            $params = "mission=".$missionService->missions[$m]."&bank=".number_format($bank, 0, '', ',')."&hp=".number_format($hp, 0, '', ',');
                            $notification->sendNotification($userData->getId(), 'USER_ACHIEVED_MISSION', $params);
                        }
                    }
                    
                    $dailyChallengeService = new DailyChallengeService();
                    $dailyChallengeService->addToDailiesIfActive(3);
                    
                    $publicMissionService = new PublicMissionService();
                    $publicMissionService->addToPublicMisionIfActive(3);
                    
                    $this->data->commitCrimeSuccess($stolenMoney, $rpCollected, $newLvlData['levelAfter'], $newLvlData['xpAfter']);
                    
                    $replaces = array(
                        array('part' => number_format($stolenMoney, 0, '', ','), 'message' => $l["COMMIT_CRIME_SUCCESS"], 'pattern' => '/{stolenMoney}/'),
                        array('part' => number_format($rpCollected, 0, '', ','), 'message' => FALSE, 'pattern' => '/{rankpoints}/')
                    );
                    $replacedMessage = $route->replaceMessageParts($replaces);
                    
                    $searchCreditsMessage = $userService->searchCredits($langs['DEZETHIS'] . " " . $langs['CRIME']);
                    if($searchCreditsMessage)
                        return array(Routing::successMessage($replacedMessage), $searchCreditsMessage);
                    
                    return Routing::successMessage($replacedMessage);
                }
                else
                {
                    $prison = new PrisonService();
                    $this->data->commitCrimeFail();
                    $prison->putUserInPrison($_SESSION['UID'], time()+90);
                    return Routing::errorMessage($l['COMMIT_CRIME_ARRESTED']);
                }
            }
            else
            {
                $this->data->commitCrimeFail();
                return Routing::errorMessage($l['COMMIT_CRIME_FAILED']);
            }
        }
    }
    
    public function commitOrganizedCrime($post)
    {
        global $route;
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->crimesLangs();
        $id = (int)$post['id'];
        
        $crimeData = $this->data->getOrganizedCrimeById($id);
        $crimePage = $this->data->getCrimesPageInfo(true);
        $userCrimeLv = $crimePage['user']->getCrimesLv();
        $userCrimeXp = $crimePage['user']->getCrimesXpRaw();
        
        if(is_object($crimeData) && $crimeData->getType() > 1)
            $preparedCrimeData = $this->data->getPreparedOrganizedCrimeByCrimeID($crimeData->getId());
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($crimeData == FALSE)
        {
            $error = $l['INVALID_CRIME_SELECTED'];
        }
        if($userData->getCCrimes() > time())
        {
            $error = $langs['WAITING_TIME_NOT_PASSED'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if($userCrimeLv < 15)
        {
            $error = $l['PLAYER_NOT_EXPERIENCED_ENOUGH'];
        }
        if($crimePage['user']->getWeapon() == 0 || $crimePage['user']->getBullets() < 50)
        {
            $error = $l['NEED_FIRE_WEAPON_EQUIPPED'];
        }
        if(is_object($crimeData) && $crimeData->getType() == 2)
        {
            /* ORGANIZED CRIME TYPE 2 | ERRORS */
            global $userService;
            if(!is_object($preparedCrimeData) ||
                (
                  (is_object($preparedCrimeData) && ($preparedCrimeData->getUserID() != $userData->getId() || $preparedCrimeData->getParticipantID() != $userData->getId())) &&
                    $preparedCrimeData->getGarageID() == 0 || $preparedCrimeData->getWeaponType() == 0
                )
            )
            {
                $error = $l['NOT_PREPARED_FOR_CRIME'];
            }
            if(is_object($preparedCrimeData))
            {
                $prison = new PrisonService();
                $p = $preparedCrimeData->getParticipantID();
                if($preparedCrimeData->getParticipantID() == $userData->getId())
                    $p = $preparedCrimeData->getUserID();
                
                if($prison->isUserInPrison($p))
                {
                    $error = $l['ONE_OR_MORE_IN_PRISON'];
                }
                $wt = $this->data->getWaitingTimeByUserID($p);
                if($wt == false || $wt > time())
                {
                    if((isset($error) && ($error === $l['ONE_OR_MORE_IN_PRISON'] || $error = $langs['WAITING_TIME_NOT_PASSED'])) || !isset($error))
                        $error = "";
                    
                    $replaces = array(
                        array('part' => $userService->getUsernameById($p), 'message' => $l['MEMBER_NOT_READY'], 'pattern' => '/{user}/'),
                        array('part' => "<span id='cOrgCrimeWaitTime_".$p."'></span>".counterClean("OrgCrimeWaitTime_".$p, $wt), 'message' => FALSE, 'pattern' => '/{waitingTime}/')
                    );
                    $replacedMessage = $route->replaceMessageParts($replaces);
                    $error .= $replacedMessage."&nbsp;";
                }
            }
        }
        if(is_object($crimeData) && $crimeData->getType() == 3)
        {
            /* ORGANIZED CRIME TYPE 3 | ERRORS */
            global $userService;
            if(!is_object($preparedCrimeData) || 
                (
                  (is_object($preparedCrimeData) && ($preparedCrimeData->getUserID() != $userData->getId())) && 
                    $preparedCrimeData->getGarageID() == 0 || $preparedCrimeData->getWeaponType() == 0 || $preparedCrimeData->getIntelType() == 0
                )
            )
            {
                $error = $l['NOT_PREPARED_FOR_CRIME'];
            }
            if(is_object($preparedCrimeData))
            {
                $prison = new PrisonService();
                $participants = array($preparedCrimeData->getParticipantID(), $preparedCrimeData->getParticipant2ID(), $preparedCrimeData->getParticipant3ID());
                $i = 0;
                foreach($participants As $p)
                {
                    if($prison->isUserInPrison($p))
                    {
                        if((isset($error) && $i === 0) || !isset($error))
                            $error = "";
                        
                        $error = $l['ONE_OR_MORE_IN_PRISON'];
                    }
                    $wt = $this->data->getWaitingTimeByUserID($p);
                    if($wt == false || $wt > time())
                    {
                        if((isset($error) && $i === 0 && ($error === $l['ONE_OR_MORE_IN_PRISON'] || $error = $langs['WAITING_TIME_NOT_PASSED'])) || !isset($error))
                            $error = "";
                        
                        $replaces = array(
                            array('part' => $userService->getUsernameById($p), 'message' => $l['MEMBER_NOT_READY'], 'pattern' => '/{user}/'),
                            array('part' => "<span id='cOrgCrimeWaitTime_".$p."'></span>".counterClean("OrgCrimeWaitTime_".$p, $wt), 'message' => FALSE, 'pattern' => '/{waitingTime}/')
                        );
                        $replacedMessage = $route->replaceMessageParts($replaces);
                        $error .= $replacedMessage."&nbsp;";
                    }
                    $i++;
                }
            }
            if(is_object($preparedCrimeData) && $preparedCrimeData->getCommitTime() != 0 && $preparedCrimeData->getCommitTime() <= time())
            {
                $error = $l['ALREADY_PREPARED_FOR_CRIME'];
            }
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            /* Code below covers both the successful execution of org crime type 1 (solo) & 2 (duo) */
            if(!isset($preparedCrimeData) || (is_object($preparedCrimeData) && $crimeData->getType() == 2))
            {
                $chanceVal = 0;
                if(isset($preparedCrimeData) && is_object($preparedCrimeData) && $crimeData->getType() == 2)
                {
                    $chanceVal = $preparedCrimeData->getWeaponType();
                    if($preparedCrimeData->getUserID() == $userData->getId())
                    {
                        $participant = $preparedCrimeData->getParticipant();
                        $participantID = $preparedCrimeData->getParticipantID();
                    }
                    else
                    {
                        $participant = $preparedCrimeData->getUsername();
                        $participantID = $preparedCrimeData->getUserID();
                    }
                    if(isset($participant))
                    {
                        $participantCrimeLv = $userService->getUserProfile($participant)->getCrimesLv();
                        $participantCrimeXp = $userService->getUserProfile($participant)->getCrimesXpRaw();
                    }
                }
                $success = $heat = $hurt = $busted = false;
                $chance = $security->randInt($chanceVal, 100);
                if($crimeData->getDifficulty() < ($chance))
                {
                    $chance2 = $security->randInt(0, 100);
                    if($userCrimeLv > $crimeData->getDifficulty() && $chance2 > 65) $success = true;
                    elseif($userCrimeLv == $crimeData->getDifficulty() && $chance2 > 82) $success = true;
                    elseif($userCrimeLv < $crimeData->getDifficulty() && $chance2 > 97) $success = true;
                    
                    if($success === false)
                    {
                        $chanceHeat = $security->randInt(0, 100);
                        if($chanceHeat < 40)
                        {
                            $chanceBusted = $security->randInt(0, 100);
                            if($chanceBusted <= 60)
                                $busted = true;
                            else
                                $heat = true;
                        }
                        elseif($chanceHeat >= 40 && $chanceHeat < 70)
                            null; // Simple fail, no heat nor bust
                        elseif($chanceHeat >= 70)
                            $hurt = true;
                    }
                }
                else
                    $success = true;
                
                if($success === true || $hurt === true || $heat === true)
                {
                    $maxRp = 2;
                    $minRp = 1;
                    $stolenMoney = $security->randInt($crimeData->getMinProfit(), $crimeData->getMaxProfit());
                    if(isset($participant)) $stolenMoney = $stolenMoney / 2;
                    $rpCollected = $security->randInt($minRp, $maxRp);
                    if(isset($participant)) $participantRpCollected = $security->randInt($minRp, $maxRp);
                    $newLvlData = self::levelCalculations($userCrimeLv, $userCrimeXp);
                    
                    $missionService = new MissionService();
                    $m = 2;
                    if($userCrimeLv < $newLvlData['levelAfter'])
                    {
                        $mTierProgress = $missionService->getMissionTierAndProgressByMission($m);
                        
                        $missionTier = $missionService->missionTiers[$m];
                        $todo = $missionTier['todo'][$mTierProgress['t']];
                        $bank = $missionTier['prizeMoney'][$mTierProgress['t']];
                        $hp = $missionTier['prizeHp'][$mTierProgress['t']];
                        if($mTierProgress['p'] + 1 >= $todo && $todo > $mTierProgress['p'])
                        {
                            $missionService->payoutMissionPrize($bank, $hp);
                            
                            $notification = new NotificationService();
                            $params = "mission=".$missionService->missions[$m]."&bank=".number_format($bank, 0, '', ',')."&hp=".number_format($hp, 0, '', ',');
                            $notification->sendNotification($userData->getId(), 'USER_ACHIEVED_MISSION', $params);
                        }
                    }
                    
                    if(isset($participant))
                    {
                        $participantNewLvlData = self::levelCalculations($participantCrimeLv, $participantCrimeXp);
                        if($participantCrimeLv < $participantNewLvlData['levelAfter'])
                        {
                            $mTierProgress = $missionService->getMissionTierAndProgressByMission($m, $participantID);
                            
                            $missionTier = $missionService->missionTiers[$m];
                            $todo = $missionTier['todo'][$mTierProgress['t']];
                            $bank = $missionTier['prizeMoney'][$mTierProgress['t']];
                            $hp = $missionTier['prizeHp'][$mTierProgress['t']];
                            if($mTierProgress['p'] + 1 >= $todo && $todo > $mTierProgress['p'])
                            {
                                $missionService->payoutMissionPrize($bank, $hp, $participantID);
                                
                                $notification = new NotificationService();
                                $params = "mission=".$missionService->missions[$m]."&bank=".number_format($bank, 0, '', ',')."&hp=".number_format($hp, 0, '', ',');
                                $notification->sendNotification($userData->getId(), 'USER_ACHIEVED_MISSION', $params);
                            }
                        }
                    }
                    $hurtPercent = $bulletsSpend = array();
                    $hurtPercent[1] = $bulletsSpend[1] = $hurtPercent[2] = $bulletsSpend[2] = false;
                    if($hurt === true || $heat === true)
                    {
                        $hurtGrade = $security->randInt(1, 100);
                        if($hurtGrade > 0 && $hurtGrade <= 25)
                        {
                            $hurtPercent[1] = $security->randInt(1, 2);
                            $hurtPercent[2] = $security->randInt(1, 2);
                            $bulletsSpend[1] = $security->randInt(2, 6);
                            $bulletsSpend[2] = $security->randInt(2, 6);
                            $failChance = $security->randInt(80, 120);
                        }
                        elseif($hurtGrade > 25 && $hurtGrade <= 50)
                        {
                            $hurtPercent[1] = $security->randInt(1, 3);
                            $hurtPercent[2] = $security->randInt(1, 3);
                            $bulletsSpend[1] = $security->randInt(3, 16);
                            $bulletsSpend[2] = $security->randInt(3, 16);
                            $failChance = $security->randInt(50, 100);
                        }
                        elseif($hurtGrade > 50 && $hurtGrade <= 75)
                        {
                            $hurtPercent[1] = $security->randInt(2, 4);
                            $hurtPercent[2] = $security->randInt(2, 4);
                            $bulletsSpend[1] = $security->randInt(4, 27);
                            $bulletsSpend[2] = $security->randInt(4, 27);
                            $failChance = $security->randInt(20, 50);
                        }
                        else
                        {
                            $hurtPercent[1] = $security->randInt(3, 8);
                            $hurtPercent[2] = $security->randInt(3, 8);
                            $bulletsSpend[1] = $security->randInt(5, 50);
                            $bulletsSpend[2] = $security->randInt(5, 50);
                            $failChance = $security->randInt(1, 30);
                        }
                        $failedChance = $security->randInt(1, 100);
                        if($failedChance > $failChance)
                            $failed = true;
                    }
                    if(isset($failed) && $failed === true)
                    {
                        $this->data->commitCrimeFail($crimeData->getWaitingTimeCompletion(), $hurtPercent[1], $bulletsSpend[1]);
                        
                        if(isset($participant))
                        {
                            $this->data->commitCrimeFail($crimeData->getWaitingTimeCompletion(), $hurtPercent[2], $bulletsSpend[2], $participantID);
                            $this->data->removeOrganizedCrime($id);
                            // Send notification
                            $notification = new NotificationService();
                            $params = "hurtPercent=".$hurtPercent[2]."&bullets=".$bulletsSpend[2];
                            $notification->sendNotification($participantID, 'ORGANIZED_CRIME_2_FAILED_AND_HURT', $params);
                        }
                        
                        $replaces = array(
                            array('part' => $hurtPercent[1], 'message' => $l['COMMIT_CRIME_FAILED_AND_HURT'], 'pattern' => '/{hurtPercent}/'),
                            array('part' => number_format($bulletsSpend[1], 0, '', ','), 'message' => FALSE, 'pattern' => '/{bullets}/'),
                        );
                        
                        $replacedMessage = $route->replaceMessageParts($replaces);
                        return Routing::errorMessage($replacedMessage);
                    }
                    else
                    {
                        if($hurt === true)
                        {
                            if(isset($participant))
                            {
                                // Send notification
                                $notification = new NotificationService();
                                $params = "stolenMoney=".number_format($stolenMoney, 0, '', ',')."&rankpoints=".$participantRpCollected."&hurtPercent=".$hurtPercent[2]."&bullets=".$bulletsSpend[2];
                                $notification->sendNotification($participantID, 'ORGANIZED_CRIME_2_SUCCESS_BUT_HURT', $params);
                            }
                            $replaces = array(
                                array('part' => number_format($stolenMoney, 0, '', ','), 'message' => $l['COMMIT_CRIME_SUCCESS_BUT_HURT'], 'pattern' => '/{stolenMoney}/'),
                                array('part' => number_format($rpCollected, 0, '', ','), 'message' => FALSE, 'pattern' => '/{rankpoints}/'),
                                array('part' => $hurtPercent[1], 'message' => FALSE, 'pattern' => '/{hurtPercent}/'),
                                array('part' => number_format($bulletsSpend[1], 0, '', ','), 'message' => FALSE, 'pattern' => '/{bullets}/'),
                            );
                            
                            $replacedMessage = $route->replaceMessageParts($replaces);
                        }
                        elseif($heat === true)
                        {
                            if(isset($participant))
                            {
                                // Send notification
                                $notification = new NotificationService();
                                $params = "stolenMoney=".number_format($stolenMoney, 0, '', ',')."&rankpoints=".$participantRpCollected."&bullets=".$bulletsSpend[2];
                                $notification->sendNotification($participantID, 'ORGANIZED_CRIME_2_SUCCESS_BUT_HEAT', $params);
                            }
                            $replaces = array(
                                array('part' => number_format($stolenMoney, 0, '', ','), 'message' => $l['COMMIT_CRIME_SUCCESS_BUT_HEAT'], 'pattern' => '/{stolenMoney}/'),
                                array('part' => number_format($rpCollected, 0, '', ','), 'message' => FALSE, 'pattern' => '/{rankpoints}/'),
                                array('part' => number_format($bulletsSpend[1], 0, '', ','), 'message' => FALSE, 'pattern' => '/{bullets}/'),
                            );
                            
                            $replacedMessage = $route->replaceMessageParts($replaces);
                            foreach(array_keys($hurtPercent) AS $k) $hurtPercent[$k] = false; // Only bullets fired not hurt whatever got set, reset
                        }
                        else
                        {
                            if(isset($participant))
                            {
                                // Send notification
                                $notification = new NotificationService();
                                $params = "stolenMoney=".number_format($stolenMoney, 0, '', ',')."&rankpoints=".$participantRpCollected;
                                $notification->sendNotification($participantID, 'ORGANIZED_CRIME_2_SUCCESS', $params);
                            }
                            $replaces = array(
                                array('part' => number_format($stolenMoney, 0, '', ','), 'message' => $l['COMMIT_CRIME_SUCCESS'], 'pattern' => '/{stolenMoney}/'),
                                array('part' => number_format($rpCollected, 0, '', ','), 'message' => FALSE, 'pattern' => '/{rankpoints}/'),
                            );
                            
                            $replacedMessage = $route->replaceMessageParts($replaces);
                        }
                        $this->data->commitCrimeSuccess($stolenMoney, $rpCollected, $newLvlData['levelAfter'], $newLvlData['xpAfter'], $crimeData->getWaitingTimeCompletion(), $hurtPercent[1], $bulletsSpend[1]);
                        if(isset($participant))
                        {
                            $this->data->commitCrimeSuccess(
                                $stolenMoney, $participantRpCollected, $participantNewLvlData['levelAfter'], $participantNewLvlData['xpAfter'], $crimeData->getWaitingTimeCompletion(), $hurtPercent[2],
                                $bulletsSpend[2], $participantID
                            );
                            $this->data->removeOrganizedCrime($id);
                        }
                        return Routing::successMessage($replacedMessage);
                    }
                }
                elseif($busted === true)
                {
                    $this->data->commitCrimeFail($crimeData->getWaitingTimeCompletion());
                    if(isset($participant))
                    {
                        $this->data->commitCrimeFail($crimeData->getWaitingTimeCompletion(), false, false, $participantID);
                        $this->data->removeOrganizedCrime($id);
                        $imprisoned = $security->randInt(1, 3);
                        if($imprisoned == 1)
                            $inPrison = $userData->getId();
                        elseif($imprisoned == 2)
                            $inPrison = $participantID;
                        else
                            $inPrison = array($userData->getId(), $participantID);
                    }
                    else
                        $inPrison = $userData->getId();
                    
                    $prison = new PrisonService();
                    if(is_array($inPrison))
                        foreach($inPrison as $u)
                            $prison->putUserInPrison($u, time() + $crimeData->getPrisonTimeBusted());
                    else
                        $prison->putUserInPrison($inPrison, time() + $crimeData->getPrisonTimeBusted());
                    
                    $replacedMessage = $route->replaceMessagePart($crimeData->getPrisonTimeBusted(), $l["COMMIT_ORGANIZED_CRIME_ARRESTED"], '/{prisonTime}/');
                    if(isset($participant) && ($inPrison == $participantID || (is_array($inPrison) && in_array($participantID, $inPrison))))
                    {
                        // Send notification
                        $notification = new NotificationService();
                        $params = "prisonTime=".$crimeData->getPrisonTimeBusted();
                        $notification->sendNotification($participantID, 'ORGANIZED_CRIME_2_ARRESTED', $params);
                        
                        if($inPrison == $participantID)
                        {
                            $replaces = array(
                                array('part' => $participant, 'message' => $l['COMMIT_ORGANIZED_CRIME_PARTICIPANT_ARRESTED'], 'pattern' => '/{user}/'),
                                array('part' => $crimeData->getPrisonTimeBusted(), 'message' => FALSE, 'pattern' => '/{prisonTime}/'),
                            );
                            
                            $replacedMessage = $route->replaceMessageParts($replaces);
                        }
                    }
                    elseif(isset($participant) && ($inPrison == $userData->getId() || (is_array($inPrison) && in_array($userData->getId(), $inPrison))))
                    {
                        // Send notification
                        $notification = new NotificationService();
                        $params = "user=".$userData->getUsername()."&prisonTime=".$crimeData->getPrisonTimeBusted();
                        $notification->sendNotification($participantID, 'ORGANIZED_CRIME_2_STARTER_ARRESTED', $params);
                    }
                    return Routing::errorMessage($replacedMessage);
                }
                else
                {
                    $this->data->commitCrimeFail($crimeData->getWaitingTimeCompletion());
                    if(isset($participant))
                    {
                        $this->data->commitCrimeFail($crimeData->getWaitingTimeCompletion(), false, false, $participantID);
                        $this->data->removeOrganizedCrime($id);
                        // Send notification
                        $notification = new NotificationService();
                        $notification->sendNotification($participantID, 'ORGANIZED_CRIME_2_FAILED');
                    }
                    return Routing::errorMessage($l['COMMIT_CRIME_FAILED']);
                }
            }
            /* // END SUCCESSFUL COMMIT ORGANIZED CRIME TYPE 1 & 2 */
            
            /* // SUCCESSFUL COMMIT ORGANIZED CRIME TYPE 3 */
            /* Code below covers successful execution of org crime type 3 (four players) */
            /* Crime type 3 is mostly cronjob based and therefore pretty short in logic here: (View cronjob/one_minute.php for the actual execution & deletion) */
            if(is_object($preparedCrimeData) && $crimeData->getType() == 3)
            {
                $this->data->executeOrganizedCrimeType3($id, $crimeData->getWaitingTimeCompletion(), $crimeData->getTravelTimeCompletion());
                
                return Routing::successMessage($l['EXECUTE_ORGANIZED_CRIME_3']);
            }
            /* // END SUCCESSFUL COMMIT ORGANIZED CRIME TYPE 3 */
        }
    }
    
    public function prepareOrganizedCrime($post)
    {
        global $route;
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l = $language->crimesLangs();
        
        $job = isset($post['job']) ? $security->xssEscape($post['job']) : null;
        $username = isset($post['username']) ? $security->xssEscape($post['username']) : null;
        $getaway = isset($post['getaway']) ? $security->xssEscape($post['getaway']) : null;
        $ground = isset($post['ground']) ? $security->xssEscape($post['ground']) : null;
        $intel = isset($post['intel']) ? $security->xssEscape($post['intel']) : null;
        
        if(isset($username)) $userProfileData = $userService->getUserProfile($username);
        if(isset($getaway)) $getawayProfileData = $userService->getUserProfile($getaway);
        if(isset($ground)) $groundProfileData = $userService->getUserProfile($ground);
        if(isset($intel)) $intelProfileData = $userService->getUserProfile($intel);
        
        $crimeData = $this->data->getOrganizedCrimeById($post['id']);
        $crimePage = $this->data->getCrimesPageInfo(true);
        
        if(is_object($crimeData) && $crimeData->getType() > 1)
            $preparedCrimeData = $this->data->getPreparedOrganizedCrimeByCrimeID($crimeData->getId());
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($crimeData == FALSE)
        {
            $error = $l['INVALID_CRIME_SELECTED'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if(is_object($preparedCrimeData))
        {
            $error = $l['ALREADY_PREPARED_FOR_CRIME'];
        }
        if($crimePage['user']->getWeapon() == 0 || $crimePage['user']->getBullets() < 50)
        {
            $error = $l['NEED_FIRE_WEAPON_EQUIPPED'];
        }
        if(isset($job) && isset($username))
        {
            if($job != 'driver' && $job != 'raider')
                $error = $l['INVALID_JOB_SELECTED'];
            
            $userID = is_object($userProfileData) && $userProfileData->getId() ? $userProfileData->getId() : null;
            if(!$userID)
                $error = $langs['PLAYER_DOESNT_EXIST'];
            
            if(isset($userID) && $userProfileData->getCrimesLv() < 15)
                $error = $l['PLAYER_NOT_EXPERIENCED_ENOUGH'];
            
            if(isset($userID) && $this->data->isUserInPreparedCrimeByCrimeID($crimeData->getId(), $userID))
                $error = $l['PLAYER_PART_OF_DIFFERENT_CRIME'];
            
            if(isset($userID) && $userID == $userData->getId())
                $error = $l['CANNOT_INVITE_CRIME_SELF'];
        }
        if(isset($getaway) && isset($ground) && isset($intel))
        {
            $txtGetaway = "getaway ".strtolower($l['DRIVER']);
            $txtGround = strtolower($l['GROUND']);
            $txtIntel = "intel";
            $getawayID = is_object($getawayProfileData) ? $getawayProfileData->getId() : null;
            $groundID = is_object($groundProfileData) ? $groundProfileData->getId() : null;
            $intelID = is_object($intelProfileData) ? $intelProfileData->getId() : null;                                    
            if(isset($getawayID) && isset($groundID) && isset($intelID))
            {
                if($getawayID == $groundID || $getawayID == $intelID || $groundID == $intelID)
                    $error = $l['SELECTED_PLAYER_MULTIPLE_TIMES'];
            }            
            if(!is_numeric($getawayID))
                $error = $langs['PLAYER_DOESNT_EXIST']." <strong>(Getaway)</strong>";
            
            if(!is_numeric($groundID))
                $error = $langs['PLAYER_DOESNT_EXIST']." <strong>(Ground)</strong>";
            
            if(!is_numeric($intelID))
                $error = $langs['PLAYER_DOESNT_EXIST']." <strong>(Intel)</strong>";
            
            if(isset($getawayID) && $getawayProfileData->getCrimesLv() < 15)
                $error = $replacedMessage = $route->replaceMessagePart($txtGetaway, $l["TYPE_NOT_EXPERIENCED_ENOUGH"], '/{type}/');
            
            if(isset($groundID) && $groundProfileData->getCrimesLv() < 15)
                $error = $replacedMessage = $route->replaceMessagePart($txtGround, $l["TYPE_NOT_EXPERIENCED_ENOUGH"], '/{type}/');
            
            if(isset($intelID) && $intelProfileData->getCrimesLv() < 15)
                $error = $replacedMessage = $route->replaceMessagePart($txtIntel, $l["TYPE_NOT_EXPERIENCED_ENOUGH"], '/{type}/');
            
            if(isset($getawayID) && $this->data->isUserInPreparedCrimeByCrimeID($crimeData->getId(), $getawayID))
                $error = $replacedMessage = $route->replaceMessagePart($txtGetaway, $l["TYPE_PART_OF_DIFFERENT_CRIME"], '/{type}/');
            
            if(isset($groundID) && $this->data->isUserInPreparedCrimeByCrimeID($crimeData->getId(), $groundID))
                $error = $replacedMessage = $route->replaceMessagePart($txtGround, $l["TYPE_PART_OF_DIFFERENT_CRIME"], '/{type}/');
            
            if(isset($intelID) && $this->data->isUserInPreparedCrimeByCrimeID($crimeData->getId(), $intelID))
                $error = $replacedMessage = $route->replaceMessagePart($txtIntel, $l["TYPE_PART_OF_DIFFERENT_CRIME"], '/{type}/');
            
            if((isset($intelID) && $intelID == $userData->getId()) || (isset($groundID) && $groundID == $userData->getId() || (isset($getawayID) && $getawayID == $userData->getId())))
                $error = $l['CANNOT_INVITE_CRIME_SELF'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            if(isset($job) && isset($username))
            {
                $jobOther = $l['RAIDER'];
                if($job == "raider") $jobOther = $l['DRIVER'];
                $this->data->prepareOrganizedCrimeType2($crimeData, $job, $userID);
                
                // Send notification
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername()."&job=".$jobOther;
                $notification->sendNotification($userID, 'USER_INVITED_TO_ORGANIZED_CRIME_2', $params);
                
                $replaces = array(
                    array('part' => $userProfileData->getUsername(), 'message' => $l['PREPARE_ORGANIZED_CRIME_2_SUCCESS'], 'pattern' => '/{username}/'),
                    array('part' => strtolower($jobOther), 'message' => FALSE, 'pattern' => '/{job}/'),
                );
                
                $replacedMessage = $route->replaceMessageParts($replaces);
            }
            elseif(isset($getaway) && isset($ground) && isset($intel))
            {
                $this->data->prepareOrganizedCrimeType3($crimeData, $getawayID, $groundID, $intelID);
                
                // Send notifications
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername();
                $notification->sendNotification($getawayProfileData->getId(), 'DRIVER_INVITED_TO_ORGANIZED_CRIME_3', $params);
                $notification->sendNotification($groundProfileData->getId(), 'GROUND_INVITED_TO_ORGANIZED_CRIME_3', $params);
                $notification->sendNotification($intelProfileData->getId(), 'INTEL_INVITED_TO_ORGANIZED_CRIME_3', $params);
                
                $replaces = array(
                    array('part' => $getawayProfileData->getUsername(), 'message' => $l['PREPARE_ORGANIZED_CRIME_3_SUCCESS'], 'pattern' => '/{getaway}/'),
                    array('part' => $groundProfileData->getUsername(), 'message' => FALSE, 'pattern' => '/{ground}/'),
                    array('part' => $intelProfileData->getUsername(), 'message' => FALSE, 'pattern' => '/{intel}/'),
                );
                
                $replacedMessage = $route->replaceMessageParts($replaces);
            }
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function userInteractOrganizedCrime($post)
    {
        global $route;
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l = $language->crimesLangs();
        $id = (int)$post['id'];
        
        $readyUp = isset($post['ready-up']) ? $post['ready-up'] : null;
        $changeParticipant = isset($post['change-participant']) ? $post['change-participant'] : null;
        $changeDriver = isset($post['change-driver']) ? $post['change-driver'] : null;
        if($changeDriver) $changeParticipant = $changeDriver;
        $changeGround = isset($post['change-ground']) ? $post['change-ground'] : null;
        if($changeGround) $changeParticipant = $changeGround;
        $changeIntel = isset($post['change-intel']) ? $post['change-intel'] : null;
        if($changeIntel) $changeParticipant = $changeIntel;
        $stop = isset($post['stop']) ? $post['stop'] : null;
        $stopConfirm = isset($post['stop-confirm']) ? $post['stop-confirm'] : null;
        
        if(isset($post['username'])) $participant = $security->xssEscape($post['username']);
        if($changeDriver && isset($post['getaway'])) $participant = $security->xssEscape($post['getaway']);
        if($changeGround && isset($post['ground'])) $participant = $security->xssEscape($post['ground']);
        if($changeIntel && isset($post['intel'])) $participant = $security->xssEscape($post['intel']);
        if(isset($participant)) $participantProfile = $userService->getUserProfile($participant);
        if(isset($post['vehicleID'])) $vehicleID = (int)$post['vehicleID'];
        if(isset($post['weaponType'])) $weaponType = (int)$post['weaponType'];
        
        $crimeData = $this->data->getOrganizedCrimeById($id);
        $crimePage = $this->data->getCrimesPageInfo(true);
        
        if(is_object($crimeData) && $crimeData->getType() > 1)
            $preparedCrimeData = $this->data->getPreparedOrganizedCrimeByCrimeID($crimeData->getId());
        
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
        if($crimeData == FALSE)
        {
            $error = $l['INVALID_CRIME_SELECTED'];
        }
        if(!is_object($preparedCrimeData) || $preparedCrimeData->getUserID() != $userData->getId())
        {
            $error = $l['NOT_PREPARED_FOR_CRIME'];
        }
        if($crimePage['user']->getWeapon() == 0 || $crimePage['user']->getBullets() < 50)
        {
            $error = $l['NEED_FIRE_WEAPON_EQUIPPED'];
        }
        if(isset($readyUp) && is_object($preparedCrimeData))
        {
            if($preparedCrimeData->getUserReady() && ($preparedCrimeData->getGarageID() != 0 || $preparedCrimeData->getWeaponType() > 0))
            {
                $error = $l['ALREADY_PREPARED_AND_READY'];
            }
            switch($preparedCrimeData->getJob())
            {
                default:
                case 1:
                    $garageService = new GarageService();
                    if(!$garageService->isVehicleInGarageInState($userData->getStateID(), $vehicleID))
                    {
                        $error = $l['VEHICLE_NOT_IN_CURRENT_GARAGE'];
                    }
                    break;
                case 2:
                    if(!array_key_exists($weaponType, $this->weapons))
                    {
                        $error = $l['INVALID_WEAPON'];
                    }
                    if(array_key_exists($weaponType, $this->weapons) && $this->weapons[$weaponType]['price'] > $userData->getCash())
                    {
                        $error = $langs['NOT_ENOUGH_MONEY_CASH'];
                    }
                    break;
            }
        }
        if(isset($changeParticipant) && isset($participant))
        {
            $userID = is_object($participantProfile) && $participantProfile->getId() ? $participantProfile->getId() : null;
            if(!$userID)
                $error = $langs['PLAYER_DOESNT_EXIST'];
            
            if(isset($userID) && $participantProfile->getCrimesLv() < 15)
                $error = $l['PLAYER_NOT_EXPERIENCED_ENOUGH'];
            
            if(isset($userID) && $this->data->isUserInPreparedCrimeByCrimeID($crimeData->getId(), $userID))
                $error = $l['PLAYER_PART_OF_DIFFERENT_CRIME'];
            
            if(isset($userID) && $userID == $userData->getId())
                $error = $l['CANNOT_INVITE_CRIME_SELF'];
        }
        if(isset($stop) && is_object($preparedCrimeData))
        {
            $replaces = array(
                array('part' => $security->getToken(), 'message' => $l['LEADER_STOP_ORGANIZED_CRIME_CONFIRM'], 'pattern' => '/{securityToken}/'),
                array('part' => $id, 'message' => FALSE, 'pattern' => '/{id}/'),
            );
            $error = $route->replaceMessageParts($replaces);
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            if($readyUp)
            {
                if($preparedCrimeData->getJob() == 1)
                    $this->data->readyUpOrganizedCrimeType2($id, 1, $vehicleID);
                elseif($preparedCrimeData->getJob() == 2)
                    $this->data->readyUpOrganizedCrimeType2($id, 2, $weaponType);
                
                return Routing::successMessage($l['READY_UP_ORGANIZED_CRIME_SUCCESS']);
            }
            elseif($changeParticipant)
            {
                $num = "";
                $note = "USER_INVITED_TO_ORGANIZED_CRIME_2";
                $invOrg3 = "_INVITED_TO_ORGANIZED_CRIME_3";
                if($changeDriver)
                {
                    $note = $msg = "DRIVER" . $invOrg3;
                }
                elseif($changeGround)
                {
                    $num = 2;
                    $note = $msg = "GROUND" . $invOrg3;
                }
                elseif($changeIntel)
                {
                    $num = 3;
                    $note = $msg = "INTEL" . $invOrg3;
                }
                $this->data->changeOrganizedCrimeParticipant($id, $participantProfile->getId(), $num);
                
                if($changeDriver || $changeGround || $changeIntel)
                {
                    $params = "user=".$userData->getUsername();
                    
                    $replacedMessage = $route->replaceMessagePart($participantProfile->getUsername(), $l[$msg], '/{username}/');
                }
                else
                {
                    $jobOther = $l['RAIDER'];
                    if($preparedCrimeData->getJob() == 2) $jobOther = $l['DRIVER'];
                    $params = "user=".$userData->getUsername()."&job=".$jobOther;
                    
                    $replaces = array(
                        array('part' => $participantProfile->getUsername(), 'message' => $l['PREPARE_ORGANIZED_CRIME_2_SUCCESS'], 'pattern' => '/{username}/'),
                        array('part' => strtolower($jobOther), 'message' => FALSE, 'pattern' => '/{job}/'),
                    );
                    
                    $replacedMessage = $route->replaceMessageParts($replaces);
                }
                // Send notification
                $notification = new NotificationService();
                $notification->sendNotification($userID, $note, $params);
                
                return Routing::successMessage($replacedMessage);
            }
            elseif($stopConfirm)
            {
                $this->data->stopOrganizedCrime($id);
                
                // Send notification
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername();
                $notification->sendNotification($preparedCrimeData->getParticipantID(), 'LEADER_STOPPED_ORGANIZED_CRIME', $params);
                if($crimeData->getType() == 3)
                {
                    if($preparedCrimeData->getParticipant2ID() != 0)
                        $notification->sendNotification($preparedCrimeData->getParticipant2ID(), 'LEADER_STOPPED_ORGANIZED_CRIME', $params);
                    
                    if($preparedCrimeData->getParticipant3ID() != 0)
                        $notification->sendNotification($preparedCrimeData->getParticipant3ID(), 'LEADER_STOPPED_ORGANIZED_CRIME', $params);
                }
                
                return Routing::successMessage($l['LEADER_STOP_ORGANIZED_CRIME_SUCCESS']);
            }
        }
    }
    
    public function participantInteractOrganizedCrime($post)
    {
        global $route;
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->crimesLangs();
        $id = (int)$post['id'];
        
        $accept = isset($post['accept']) ? $post['accept'] : null;
        $deny = isset($post['deny']) ? $post['deny'] : null;
        $denyConfirm = isset($post['deny-confirm']) ? $post['deny-confirm'] : null;
        
        if(isset($post['vehicleID'])) $vehicleID = (int)$post['vehicleID'];
        if(isset($post['weaponType'])) $weaponType = (int)$post['weaponType'];
        if(isset($post['intelType'])) $intelType = (int)$post['intelType'];
        
        $crimeData = $this->data->getOrganizedCrimeById($id);
        $crimePage = $this->data->getCrimesPageInfo(true);
        
        if(is_object($crimeData) && $crimeData->getType() > 1)
            $preparedCrimeData = $this->data->getPreparedOrganizedCrimeByCrimeID($crimeData->getId());
        
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
        if($crimeData == FALSE)
        {
            $error = $l['INVALID_CRIME_SELECTED'];
        }
        if(!is_object($preparedCrimeData) || 
            ($preparedCrimeData->getParticipantID() != $userData->getId() && $preparedCrimeData->getParticipant2ID() != $userData->getId() && $preparedCrimeData->getParticipant3ID() != $userData->getId())
        )
        {
            $error = $l['NOT_PREPARED_FOR_CRIME'];
        }
        if($crimePage['user']->getWeapon() == 0 || $crimePage['user']->getBullets() < 50)
        {
            $error = $l['NEED_FIRE_WEAPON_EQUIPPED'];
        }
        if(isset($accept) && is_object($preparedCrimeData))
        {
            if($crimeData->getType() == 2)
            {
                if($preparedCrimeData->getParticipantReady() && ($preparedCrimeData->getGarageID() != 0 || $preparedCrimeData->getWeaponType() != 0))
                {
                    $error = $l['ALREADY_PREPARED_AND_READY'];
                }
            }
            else
            {
                if($preparedCrimeData->getParticipantID() == $userData->getId())
                {
                    if($preparedCrimeData->getParticipantReady() && $preparedCrimeData->getGarageID() != 0)
                    {
                        $error = $l['ALREADY_PREPARED_AND_READY'];
                    }
                }
                elseif($preparedCrimeData->getParticipant2ID() == $userData->getId())
                {
                    if($preparedCrimeData->getParticipant2Ready() && $preparedCrimeData->getWeaponType() != 0)
                    {
                        $error = $l['ALREADY_PREPARED_AND_READY'];
                    }
                }
                elseif($preparedCrimeData->getParticipant3ID() == $userData->getId())
                {
                    if($preparedCrimeData->getParticipant3Ready() && $preparedCrimeData->getIntelType() != 0)
                    {
                        $error = $l['ALREADY_PREPARED_AND_READY'];
                    }
                }
            }
            switch($preparedCrimeData->getJob())
            {
                default:
                case 0: // TYPE 3 ALL JOBS
                    if($preparedCrimeData->getParticipantID() == $userData->getId())
                    {
                        $garageService = new GarageService();
                        if(!$garageService->isVehicleInGarageInState($userData->getStateID(), $vehicleID))
                        {
                            $error = $l['VEHICLE_NOT_IN_CURRENT_GARAGE'];
                        }
                    }
                    elseif($preparedCrimeData->getParticipant2ID() == $userData->getId())
                    {
                        if(!array_key_exists($weaponType, $this->weapons))
                        {
                            $error = $l['INVALID_WEAPON'];
                        }
                        if(array_key_exists($weaponType, $this->weapons) && $this->weapons[$weaponType]['price'] > $userData->getCash())
                        {
                            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
                        }
                    }
                    elseif($preparedCrimeData->getParticipant3ID() == $userData->getId())
                    {
                        if(!array_key_exists($intelType, $this->intel))
                        {
                            $error = $l['INVALID_INTEL'];
                        }
                        if(array_key_exists($intelType, $this->intel) && $this->intel[$intelType]['price'] > $userData->getCash())
                        {
                            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
                        }
                    }
                    break;
                case 1: // TYPE 2 JOB DRIVER
                    if(!array_key_exists($weaponType, $this->weapons))
                    {
                        $error = $l['INVALID_WEAPON'];
                    }
                    if(array_key_exists($weaponType, $this->weapons) && $this->weapons[$weaponType]['price'] > $userData->getCash())
                    {
                        $error = $langs['NOT_ENOUGH_MONEY_CASH'];
                    }
                    break;
                case 2: // TYPE 2 JOB RAIDER
                    $garageService = new GarageService();
                    if(!$garageService->isVehicleInGarageInState($userData->getStateID(), $vehicleID))
                    {
                        $error = $l['VEHICLE_NOT_IN_CURRENT_GARAGE'];
                    }
                    break; 
            }
        }
        if(isset($deny) && is_object($preparedCrimeData))
        {
            $replaces = array(
                array('part' => $security->getToken(), 'message' => $l['PARTICIPANT_DENY_ORGANIZED_CRIME_CONFIRM'], 'pattern' => '/{securityToken}/'),
                array('part' => $id, 'message' => FALSE, 'pattern' => '/{id}/'),
            );
            $error = $route->replaceMessageParts($replaces);
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            if($accept)
            {
                if($crimeData->getType() == 2)
                {
                    if($preparedCrimeData->getJob() == 1)
                        $this->data->acceptOrganizedCrimeType2($id, 1, $weaponType);
                    elseif($preparedCrimeData->getJob() == 2)
                        $this->data->acceptOrganizedCrimeType2($id, 2, $vehicleID);
                }
                else
                {
                    if($preparedCrimeData->getParticipantID() == $userData->getId())
                        $this->data->acceptOrganizedCrimeType3($id, "", $vehicleID);
                    elseif($preparedCrimeData->getParticipant2ID() == $userData->getId())
                        $this->data->acceptOrganizedCrimeType3($id, 2, $weaponType);
                    elseif($preparedCrimeData->getParticipant3ID() == $userData->getId())
                        $this->data->acceptOrganizedCrimeType3($id, 3, $intelType);
                }
                
                return Routing::successMessage($l['READY_UP_ORGANIZED_CRIME_SUCCESS']);
            }
            elseif($denyConfirm)
            {
                if($crimeData->getType() == 2)
                    $this->data->denyOrganizedCrime($id);
                else
                {
                    if($preparedCrimeData->getParticipantID() == $userData->getId())
                        $this->data->denyOrganizedCrime($id);
                    elseif($preparedCrimeData->getParticipant2ID() == $userData->getId())
                        $this->data->denyOrganizedCrime($id, 2);
                    elseif($preparedCrimeData->getParticipant3ID() == $userData->getId())
                        $this->data->denyOrganizedCrime($id, 3);
                }
                
                // Send notification
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername();
                $notification->sendNotification($preparedCrimeData->getUserID(), 'PARTICIPANT_DENIED_ORGANIZED_CRIME', $params);
                
                return Routing::successMessage($l['PARTICIPANT_DENY_ORGANIZED_CRIME_SUCCESS']);
            }
        }
    }
    
    public function getCrimesPageInfo($organized = null)
    {
        return $this->data->getCrimesPageInfo($organized);
    }
}
