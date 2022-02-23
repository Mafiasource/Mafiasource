<?PHP
 
namespace src\Business;

use app\config\Routing;
use src\Business\StateService;
use src\Business\PossessionService;
use src\Business\MissionService;
use src\Business\NotificationService;
use src\Business\DailyChallengeService;
use src\Business\PublicMissionService;
use src\Data\RedLightDistrictDAO;
 
class RedLightDistrictService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new RedLightDistrictDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public static function levelCalculations($userLv, $userXp, $hoesPimped, $hoesMax)
    {
        $levelAfter = $userLv;
        if($userLv == 1){
            $xpGained = (($hoesPimped * 150) / ($userLv * $hoesMax));
        } elseif($userLv == 2){
            $xpGained = (($hoesPimped * 75) / ($userLv * $hoesMax));
        } else {
            $xpGained = (($hoesPimped * 100) / ($userLv * $hoesMax));
        }
        
        if(strtotime("2022-01-28 14:00:00") < strtotime('now') && strtotime("2022-02-01 14:00:00") > strtotime('now'))
            $xpGained *= 2;
        
        $newXp = $userXp+$xpGained;
        
        if($newXp >= 100 && $newXp < 200 && $userLv != 100)
        {
            $levelAfter++;
            $xpAfter = $newXp-100;
        }
        elseif($newXp >= 200 && $newXp < 300 && $userLv != 100)
        {
            $levelAfter=$levelAfter+2;
            $xpAfter = $newXp-200;
        }
        elseif($newXp >= 300 && $newXp < 400 && $userLv != 100)
        {
            $levelAfter=$levelAfter+3;
            $xpAfter = $newXp-300;
        }
        elseif($newXp >= 400 && $newXp < 500 && $userLv != 100)
        {
            $levelAfter=$levelAfter+4;
            $xpAfter = $newXp-400;
        }
        elseif($newXp >= 500 && $newXp < 600 && $userLv != 100)
        {
            $levelAfter=$levelAfter+5;
            $xpAfter = $newXp-500;
        }
        elseif($userLv != 100)
        {
            $xpAfter = $userXp+$xpGained;
        }
        else
        {
            $xpAfter = 100;
        }
        if($levelAfter == 100) $xpAfter = 100;
        return array('levelAfter' => $levelAfter, 'xpAfter' => $xpAfter);
    }
    
    public function pimpWhores($post, $who = false)
    {
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l        = $language->redLightDistrictLangs();
        $whoID = $userService->getIdByUsername($who);
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
        if($who == false)
        {
            if($userData->getCPimpWhores() > time())
            {
                $error = $langs['WAITING_TIME_NOT_PASSED'];
            }
        }
        else
        {
            if($userData->getCPimpWhoresFor() > time())
            {
                $error = $langs['WAITING_TIME_NOT_PASSED'];
            }
            if($whoID === FALSE)
            {
                $error = $langs['PLAYER_DOESNT_EXIST'];
            }
            if($userData->getId() == $whoID)
            {
                $error = $l['CANNOT_PIMP_FOR_SELF'];
            }
        }
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            global $userService;
            $rldPage = $this->data->getRedLightDistrictPageInfo();
            $minPimp = $rldPage->getPimpLv() - 5;
            $maxPimp = $rldPage->getPimpLv();
            if($minPimp < 1) $minPimp = 1;
            $pimped = $security->randInt($minPimp, $maxPimp);
            if($userData->getCharType() == 4)
                $pimped *= 1.15;
                
            $pimped = round($pimped);
            
            $dailyChallengeService = new DailyChallengeService();
            $dailyChallengeService->addToDailiesIfActive(4, $pimped);
            
            $newLvlData = self::levelCalculations($rldPage->getPimpLv(), $rldPage->getPimpXpRaw(), $pimped, $maxPimp);
            
            if($rldPage->getPimpLv() < $newLvlData['levelAfter'])
            {
                $m = 3;
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
            $publicMissionService = new PublicMissionService();
            if($who !== false)
            {
                $publicMissionService->addToPublicMisionIfActive(18, $pimped);
                
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername()."&whores=".$pimped;
                $notification->sendNotification($whoID, 'USER_PIMPED_FOR_YOU', $params);

                $replacedMessage = $route->replaceMessagePart($pimped, $l['PIMP_WHORES_FOR_OTHER_SUCCESS'], '/{amount}/');
                $replacedMessage = $route->replaceMessagePart($userService->getUserProfile($who)->getUsername(), $replacedMessage, '/{user}/');
            }
            else
            {
                $publicMissionService->addToPublicMisionIfActive(5, $pimped);
                $replacedMessage = $route->replaceMessagePart($pimped, $l['PIMP_WHORES_SELF_SUCCESS'], '/{amount}/');
            }
            $this->data->pimpWhores($pimped, $newLvlData['levelAfter'], $newLvlData['xpAfter'], $who);
            
            $searchCreditsMessage = $userService->searchCredits($langs['PIMP_WHORES']);
            if($searchCreditsMessage)
                return array(Routing::successMessage($replacedMessage), $searchCreditsMessage);

            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function placeWhoresBehindWindow($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l        = $language->redLightDistrictLangs();
        $amount = (int)$post['amount'];
        $possession = new PossessionService();
        $possessionId = 2; //Red Light District | Possession logic
        $possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID());
        $rldInfo = $possession->getRLDInfoByPossessID($possessId);
        
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
        $windowsLeft = $rldInfo->getWindows()-$rldInfo->getWindowsUsed();
        if($amount > $windowsLeft)
        {
            $error = $l['NOT_ENOUGH_WINDOWS_LEFT'];
        }
        if($this->getRedLightDistrictPageInfo()->getWhoresStreet() < $amount)
        {
            $error =$l['NOT_ENOUGH_STREET_WHORES'];
        }
        if($userData->getCash() < ($amount * $rldInfo->getPriceEachWindow()))
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }

        if(empty($amount) || !is_numeric($amount) || $amount < 1)
        {
            $error = $l['INVALID_AMOUNT_WINDOWS'];
        }

        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->placeWhoresBehindWindow($amount, $rldInfo);
            
            $replacedMessage = $route->replaceMessagePart($amount, $l['BUY_WINDOWS_SUCCESS'], '/{amount}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function takeAwayWhoresBehindWindow($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l            = $language->redLightDistrictLangs();
        $state        = new StateService();
        $stateID      = $state->getStateIdByName($post['state']);
        
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
        if($this->data->getRLDWhoresFromState($stateID) < $post['amount'])
        {
            $error = $l['NOT_THAT_MUCH_WHORES_WINDOW'];
        }
        if(empty($post['amount']) || !is_numeric($post['amount']) || $post['amount'] < 1)
        {
            $error = $l['INVALID_AMOUNT_WINDOWS'];
        }
        if($stateID == FALSE)
        {
            $error = $l['INVALID_STATE_SELECTED'];
        }
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->takeAwayWhoresBehindWindow($stateID, $post['amount']);
            
            $replacedMessage = $route->replaceMessagePart($post['amount'], $l['TAKE_AWAY_WINDOWS_SUCCESS'], '/{amount}/');
            $replacedMessage = $route->replaceMessagePart($post['state'], $replacedMessage, '/{state}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function getRedLightDistrictPageInfo()
    {
        return $this->data->getRedLightDistrictPageInfo();
    }
}
