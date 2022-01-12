<?PHP
 
namespace src\Business;

use src\Business\PrisonService;
use src\Business\VehicleService;
use src\Business\CrimeService;
use src\Business\GarageService;
use src\Business\MissionService;
use src\Business\NotificationService;
use src\Business\DailyChallengeService;
use src\Business\PublicMissionService;
use src\Data\StealVehicleDAO;
use app\config\Routing;

/* Class has some cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */
 
class StealVehicleService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new StealVehicleDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function stealVehicle($post)
    {
        global $userData;
        global $language;
        global $langs;
        $l = $language->stealVehiclesLangs();
        global $security;
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        $svData = $this->data->getStealVehicleById($post['id']);
        $userVehicleLv = $this->data->getStealVehiclesPageInfo()['user']->getVehiclesLv();
        $userVehicleXp = $this->data->getStealVehiclesPageInfo()['user']->getVehiclesXpRaw();
        if($svData == FALSE || $svData->getLevel() > $userVehicleLv)
        {
            $error = $l['INVALID_STEAL_VEHICLE_SELECTED'];
        }
        if($userData->getCStealVehicles() > time())
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
            if($userData->getCharType() == 1)
                $chance = $security->randInt(5, 100);
            
            if($svData->getDifficulty() < $chance)
            {
                $success = false; //Init
                $chance2 = $security->randInt(0, 100);
                if($userVehicleLv > $svData->getDifficulty() && $chance2 > 25) { $success = TRUE; $damage = "minimum"; }
                elseif($userVehicleLv == $svData->getDifficulty() && $chance2 > 50) { $success = TRUE; $damage = "medium"; }
                elseif($userVehicleLv < $svData->getDifficulty() && $chance2 > 75) { $success = TRUE; $damage = "maximum"; }
                else $success = FALSE;
                
                if($success == TRUE)
                {
                    global $route;
                    global $userService;
                    $vehicle = new VehicleService();
                    $garage = new GarageService();                                                
                    $addSome = $addSome2 = $addSome3 = ""; //Init
                    switch($damage)
                    {
                        case "minimum":
                            $dmg = $security->randInt(1, 60);
                            break;
                        case "medium":
                            $dmg = $security->randInt(30, 80);
                            break;
                        case "maximum":
                            $dmg = $security->randInt(40, 100);
                            break;
                    }
                    $maxRp = $svData->getMaxRankpoints();
                    $minRp = $svData->getMaxRankpoints() - 3;
                    if($minRp < 0) $minRp = 1;
                    $stolenVehicle = $vehicle->getRandomVehicleByLv($userVehicleLv);
                    
                    $m = 7;
                    $missionService = new MissionService();
                    $mTierProgress = $missionService->getMissionTierAndProgressByMission($m);
                    $missionTier = $missionService->missionTiers[$m];
                    $todo = $missionTier['todo'][$mTierProgress['t']];
                    $bank = $missionTier['prizeMoney'][$mTierProgress['t']];
                    $hp = $missionTier['prizeHp'][$mTierProgress['t']];
                    $uniqueVehicle = $missionService->userHasStolenVehicleBefore($stolenVehicle->getId()) === true ? 0 : 1;
                    if($mTierProgress['p'] + $uniqueVehicle >= $todo && $todo > $mTierProgress['p'])
                    {
                        $missionService->addNewCarjackerVehicle($stolenVehicle->getId());
                        $missionService->payoutMissionPrize($bank, $hp);
                        
                        $notification = new NotificationService();
                        $params = "mission=".$missionService->missions[$m]."&bank=".number_format($bank, 0, '', ',')."&hp=".number_format($hp, 0, '', ',');
                        $notification->sendNotification($userData->getId(), 'USER_ACHIEVED_MISSION', $params);
                    }
                    elseif($todo > $mTierProgress['p'] && $uniqueVehicle == 1)
                        $missionService->addNewCarjackerVehicle($stolenVehicle->getId());
                    else
                        $missionService->addCountToCarjackerVehicle($stolenVehicle->getId());                                         
                                                                                    
                    $rpCollected = $security->randInt($minRp, $maxRp);
                    $newLvlData = CrimeService::levelCalculations($userVehicleLv, $userVehicleXp);
                    
                    if($userVehicleLv < $newLvlData['levelAfter'])
                    {
                        $m = 1;
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
                    $publicMissionService = new PublicMissionService();
                    $dailyChallengeService->addToDailiesIfActive(1);
                    $publicMissionService->addToPublicMisionIfActive(1);
                    
                    $vehiclePrice = $stolenVehicle->getPrice();
                    
                    if($userData->getDonatorID() >= 1)
                        $vehiclePrice *= 0.95;
                    
                    $vehiclePrice *= 0.65;
                    $stolenValue = round((round($vehiclePrice) / 100) * (100 - $dmg));
                    $stateID = $userData->getStateID();
                    $this->data->stealVehicleSuccess($stolenValue, $rpCollected, $newLvlData['levelAfter'], $newLvlData['xpAfter']);
                    if($garage->hasGarageInState($stateID) === TRUE && $dmg < 75 && $garage->hasSpaceLeftInGarage($stateID) === TRUE)
                    {
                        // Give user choice
                        $_SESSION['steal-vehicles']['stolenValue'] = $stolenValue;
                        $_SESSION['steal-vehicles']['vehicleID'] = $stolenVehicle->getId();
                        $_SESSION['steal-vehicles']['dmg'] = $dmg;
                        $_SESSION['steal-vehicles']['time'] = time();
                        $replacedMessage = $route->replaceMessagePart(number_format($stolenValue, 0, '', ','), $l["STORE_OR_SELL_VEHIICLE"], '/{price}/');
                        $addSome = "<br />".$replacedMessage;
                    }
                    elseif($dmg > 75)
                    {
                        // vehicle was total loss and sold..
                        $garage->sellStolenVehicle($stolenValue);
                        $replacedMessage = $route->replaceMessagePart(number_format($stolenValue, 0, '', ','), $l["VEHICLE_TOTAL_LOSS_SOLD"], '/{price}/');
                        $addSome = "<br />".$replacedMessage;
                    }
                    elseif(!$garage->hasGarageInState($stateID))
                    {
                        // Vehicle sold, no garage in current state to store..
                        $garage->sellStolenVehicle($stolenValue);
                        $replacedMessage = $route->replaceMessagePart(number_format($stolenValue, 0, '', ','), $l["NO_GARAGE_VEHICLE_SOLD"], '/{price}/');
                        $replacedMessage = $route->replaceMessagePart($userData->getState(), $replacedMessage, '/{state}/');
                        $addSome = "<br />".$replacedMessage;
                    }
                    else
                    {
                        $garage->sellStolenVehicle($stolenValue);
                        $replacedMessage = $route->replaceMessagePart(number_format($stolenValue, 0, '', ','), $l["NO_SPACE_VEHICLE_SOLD"], '/{price}/');
                        $addSome = "<br />".$replacedMessage;
                    }
                    $replacedMessage = $route->replaceMessagePart("<b>".$stolenVehicle->getName()."</b>", $l["STEAL_VEHICLE_SUCCESS"], '/{stolenVehicle}/');
                    $replacedMessage = $route->replaceMessagePart("<b>".$dmg."</b>", $replacedMessage, '/{damage}/');
                    $replacedMessage = $route->replaceMessagePart("<b>".number_format($rpCollected, 0, '', ',')."</b>", $replacedMessage, '/{rankpoints}/');
                    $replacedMessage = $route->replaceMessagePart($addSome, $replacedMessage, '/{addSome}/');
                    $replacedMessage = $route->replaceMessagePart($addSome2, $replacedMessage, '/{addSome2}/');
                    $replacedMessage = $route->replaceMessagePart($addSome3, $replacedMessage, '/{addSome3}/');
                    $replacedMessage = $route->replaceMessagePart("<img src='".PROTOCOL.STATIC_SUBDOMAIN.".".$route->settings['domainBase']."/foto/web/public/images/vehicle/".$stolenVehicle->getPicture()."' class='middle' alt='".$stolenVehicle->getName()."'/>",$replacedMessage,'/{picture}/');
                    
                    $searchCreditsMessage = $userService->searchCredits($langs['STEAL_VEHICLES']);
                    if($searchCreditsMessage)
                        return array(Routing::successMessage($replacedMessage), $searchCreditsMessage);
                    
                    return Routing::successMessage($replacedMessage);
                }
                else
                {
                    $this->data->stealVehicleFail();
                    $prison = new PrisonService();                                        
                    $prison->putUserInPrison($_SESSION['UID'], time()+150);
                    return Routing::errorMessage($l['STEAL_VEHICLE_ARRESTED']);
                }
            } 
            else
            {
                $this->data->stealVehicleFail();
                return Routing::errorMessage($l['STEAL_VEHICLE_FAILED']);
            }
        }
    }
    
    public function getStealVehiclesPageInfo()
    {
        return $this->data->getStealVehiclesPageInfo();
    }
}
