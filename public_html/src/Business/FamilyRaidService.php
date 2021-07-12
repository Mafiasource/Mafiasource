<?PHP

namespace src\Business;

use src\Business\NotificationService;
use src\Business\PrisonService;
use src\Business\StateService;
use src\Business\GarageService;
use src\Business\MissionService;
use src\Data\FamilyRaidDAO;
use app\config\Routing;
 
class FamilyRaidService
{
    private $data;
    public $bombs = array(
        1 => array('name' => "1x TNT", 'price' => 25000, 'chance' => 2),
        array('name' => "2x TNT", 'price' => 50000, 'chance' => 3),
        array('name' => "1x C4", 'price' => 50000, 'chance' => 3),
        array('name' => "2x C4", 'price' => 100000, 'chance' => 5)
    );
    public $weapons = array(
        1 => array('name' => "UZI", 'price' => 25000, 'chance' => 2),
        array('name' => "Stg44", 'price' => 50000, 'chance' => 3),
        array('name' => "M4", 'price' => 100000, 'chance' => 5)
    );
    
    public function __construct()
    {
        $this->data = new FamilyRaidDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function organizeFamilyRaid($post)
    {
        global $route;
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyRaidLangs();
        $fl = $language->familyLangs();
        
        $driver = $security->xssEscape($post['driver']);
        $bombExpert = $security->xssEscape($post['bombExpert']);
        $weaponExpert = $security->xssEscape($post['weaponExpert']);
        $check = $this->data->userInsideFamilyRaid();
        
        $multipleSame = false;
        if( $driver == $bombExpert || $driver == $weaponExpert || $driver == $userData->getUsername() || 
            $bombExpert == $weaponExpert || $bombExpert == $userData->getUsername() || 
            $weaponExpert == $userData->getUsername()
        )
            $multipleSame = true;
        
        $driverProfile = $userService->getUserProfile($driver);
        $bombExpertProfile = $userService->getUserProfile($bombExpert);
        $weaponExpertProfile = $userService->getUserProfile($weaponExpert);
        
        $driverCheck = $bombExpertCheck = $weaponExpertCheck = false;
        $availableMembers = $this->data->getFamilyRaidMemberList();
        foreach($availableMembers AS $m)
        {
            if($m['username'] == $driver)
                $driverCheck = true;
            if($m['username'] == $bombExpert)
                $bombExpertCheck = true;
            if($m['username'] == $weaponExpert)
                $weaponExpertCheck = true;
        }
        
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
        if($userData->getFamilyID() == 0 || $userData->getFamilyID() == false)
        {
            $error = $fl['FAMILY_DOESNT_EXIST'];
        }
        if($multipleSame)
        {
            $error = $l['SELECTED_USER_MULTIPLE_TIMES'];
        }
        if(!$driverCheck)
        {
            $replacedMessage = $route->replaceMessagePart(strtolower($l['DRIVER']), $l['RAID_TYPE_NOT_AVAILABLE'], '/{type}/');
            $error = $replacedMessage;
        }
        if(!$bombExpertCheck)
        {
            $replacedMessage = $route->replaceMessagePart(strtolower($l['BOMB'])." expert", $l['RAID_TYPE_NOT_AVAILABLE'], '/{type}/');
            $error = $replacedMessage;
        }
        if(!$weaponExpertCheck)
        {
            $replacedMessage = $route->replaceMessagePart(strtolower($langs['WEAPON'])." expert", $l['RAID_TYPE_NOT_AVAILABLE'], '/{type}/');
            $error = $replacedMessage;
        }
        if(!$driverProfile || $driverProfile->getFamilyID() != $userData->getFamilyID())
        {
            $replacedMessage = $route->replaceMessagePart(strtolower($l['DRIVER']), $l['RAID_TYPE_NOT_IN_FAMILY'], '/{type}/');
            $error = $replacedMessage;
        }
        if(!$bombExpertProfile || $bombExpertProfile->getFamilyID() != $userData->getFamilyID())
        {
            $replacedMessage = $route->replaceMessagePart(strtolower($l['BOMB'])." expert", $l['RAID_TYPE_NOT_IN_FAMILY'], '/{type}/');
            $error = $replacedMessage;
        }
        if(!$weaponExpertProfile || $weaponExpertProfile->getFamilyID() != $userData->getFamilyID())
        {
            $replacedMessage = $route->replaceMessagePart(strtolower($langs['WEAPON'])." expert", $l['RAID_TYPE_NOT_IN_FAMILY'], '/{type}/');
            $error = $replacedMessage;
        }
        if($check)
        {
            $error = $l['ALREADY_INSIDE_FAMILY_RAID'];
        }
        if($userData->getCFamilyRaid() > time())
        {
            $error = $langs['WAITING_TIME_NOT_PASSED'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->organizeFamilyRaid($userData->getStateID(), $driverProfile->getId(), $bombExpertProfile->getId(), $weaponExpertProfile->getId());
            
            // Send notifications to participants
            $notification = new NotificationService();
            $params = "state=".$userData->getState();
            $notification->sendNotification($driverProfile->getId(), 'DRIVER_INVITED_TO_FAMILY_RAID', $params);
            
            $params = "state=".$userData->getState();
            $notification->sendNotification($bombExpertProfile->getId(), 'BOMB_EXPERT_INVITED_TO_FAMILY_RAID', $params);
            
            $params = "state=".$userData->getState();
            $notification->sendNotification($weaponExpertProfile->getId(), 'WEAPON_EXPERT_INVITED_TO_FAMILY_RAID', $params);
            
            return Routing::successMessage($l['ORGANIZE_FAMILY_RAID_SUCCESS']);
        }
    }
    
    public function leaderInteractFamilyRaid($post)
    {
        global $route;
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyRaidLangs();
        $fl = $language->familyLangs();
        
        $familyRaidID = (int)$post['familyRaidID'];
        $familyRaid = $this->data->getActiveFamilyRaid();
        
        $changeDriver = isset($post['change-driver']) ? $post['change-driver'] : null;
        $changeBombExpert = isset($post['change-bombExpert']) ? $post['change-bombExpert'] : null;
        $changeWeaponExpert = isset($post['change-weaponExpert']) ? $post['change-weaponExpert'] : null;
        $start = isset($post['start']) ? $post['start'] : null;
        $quit = isset($post['quit']) ? $post['quit'] : null;
        $quitConfirm = isset($post['quit-confirm']) ? $post['quit-confirm'] : null;
        
        if(isset($post['new-driver'])) $driver = $security->xssEscape($post['new-driver']);
        if(isset($post['new-bombExpert'])) $bombExpert = $security->xssEscape($post['new-bombExpert']);
        if(isset($post['new-weaponExpert'])) $weaponExpert = $security->xssEscape($post['new-weaponExpert']);
        
        if(isset($driver)) $driverProfile = $userService->getUserProfile($driver);
        if(isset($bombExpert)) $bombExpertProfile = $userService->getUserProfile($bombExpert);
        if(isset($weaponExpert)) $weaponExpertProfile = $userService->getUserProfile($weaponExpert);
        
        $driverCheck = $bombExpertCheck = $weaponExpertCheck = false;
        $availableMembers = $this->data->getFamilyRaidMemberList();
        foreach($availableMembers AS $m)
        {
            if($changeDriver && $m['username'] == $driver)
                $driverCheck = true;
            if($changeBombExpert && $m['username'] == $bombExpert)
                $bombExpertCheck = true;
            if($changeWeaponExpert && $m['username'] == $weaponExpert)
                $weaponExpertCheck = true;
        }
        
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
        if($userData->getFamilyID() == 0 || $userData->getFamilyID() == false)
        {
            $error = $fl['FAMILY_DOESNT_EXIST'];
        }
        if(is_object($familyRaid) && $familyRaid->getId() != $familyRaidID || !is_object($familyRaid))
        {
            $error = $l['NOT_PARTICIPATING_IN_RAID'];
        }
        if(is_object($familyRaid) && $familyRaid->getLeader() !== $userData->getUsername())
        {
            $error = $l['ONLY_LEADER_CAN_START_RAID'];
        }
        if(is_object($familyRaid) && $familyRaid->getStateID() != $userData->getStateID())
        {
            $stateService = new StateService();
            $replacedMessage = $route->replaceMessagePart($stateService->getStateNameById($familyRaid->getStateID()), $l['NOT_IN_SAME_STATE_AS_RAID'], '/{state}/');
            $error = $replacedMessage;
        }
        if($changeDriver || $changeBombExpert || $changeWeaponExpert)
        {
            /* EDIT RAID MEMBER ERRORS */
            if($changeDriver && !$driverCheck)
            {
                $replacedMessage = $route->replaceMessagePart(strtolower($l['DRIVER']), $l['RAID_TYPE_NOT_AVAILABLE'], '/{type}/');
                $error = $replacedMessage;
            }
            if($changeBombExpert && !$bombExpertCheck)
            {
                $replacedMessage = $route->replaceMessagePart(strtolower($l['BOMB'])." expert", $l['RAID_TYPE_NOT_AVAILABLE'], '/{type}/');
                $error = $replacedMessage;
            }
            if($changeWeaponExpert && !$weaponExpertCheck)
            {
                $replacedMessage = $route->replaceMessagePart(strtolower($langs['WEAPON'])." expert", $l['RAID_TYPE_NOT_AVAILABLE'], '/{type}/');
                $error = $replacedMessage;
            }
            if($changeDriver && (!$driverProfile || $driverProfile->getFamilyID() != $userData->getFamilyID()))
            {
                $replacedMessage = $route->replaceMessagePart(strtolower($l['DRIVER']), $l['RAID_TYPE_NOT_IN_FAMILY'], '/{type}/');
                $error = $replacedMessage;
            }
            if($changeBombExpert && (!$bombExpertProfile || $bombExpertProfile->getFamilyID() != $userData->getFamilyID()))
            {
                $replacedMessage = $route->replaceMessagePart(strtolower($l['BOMB'])." expert", $l['RAID_TYPE_NOT_IN_FAMILY'], '/{type}/');
                $error = $replacedMessage;
            }
            if($changeWeaponExpert && (!$weaponExpertProfile || $weaponExpertProfile->getFamilyID() != $userData->getFamilyID()))
            {
                $replacedMessage = $route->replaceMessagePart(strtolower($langs['WEAPON'])." expert", $l['RAID_TYPE_NOT_IN_FAMILY'], '/{type}/');
                $error = $replacedMessage;
            }
        }
        elseif($start)
        {
            /* START RAID ERRORS */
            if(is_object($familyRaid))
            {
                if(!$familyRaid->getDriverReady() || !$familyRaid->getBombExpertReady() || !$familyRaid->getWeaponExpertReady())
                {
                    $error = $l['RAID_NOT_READY_TO_START'];
                }
                $prison = new PrisonService();
                if( $prison->isUserInPrison($userData->getId()) || // LEADER
                    $prison->isUserInPrison($familyRaid->getDriverID()) ||
                    $prison->isUserInPrison($familyRaid->getBombExpertID()) ||
                    $prison->isUserInPrison($familyRaid->getWeaponExpertID())
                )
                    $someInPrison = true;
                if(isset($someInPrison) && $someInPrison === true)
                {
                    $error = $l['ONE_OR_MORE_IN_PRISON'];
                }
            }
        }
        if($userData->getCFamilyRaid() > time())
        {
            $error = $langs['WAITING_TIME_NOT_PASSED'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            if($start)
            {
                if($familyRaid->getGarageID() == -1)
                    $driverChance = $security->randInt(2, 5);
                else
                    $driverChance = $security->randInt(3, 7);
                
                $bombChance = $this->bombs[$familyRaid->getBombType()]['chance'];
                $weaponChance = $this->weapons[$familyRaid->getWeaponType()]['chance'];
                $bulletsChance = round($familyRaid->getBullets() / 100);
                
                $successChance = $driverChance + $bombChance + $weaponChance + $bulletsChance;
                $sucess = $security->randInt(1, 100);
                
                if($successChance >= 20)
                {
                    if($sucess > 25)
                        $raidSuccess = true;
                    else
                        $raidSuccess = false;
                }
                elseif($successChance >= 15 && $successChance < 20)
                {
                    if($sucess > 30)
                        $raidSuccess = true;
                    else
                        $raidSuccess = false;
                }
                elseif($successChance >= 10 && $successChance < 15)
                {
                    if($sucess > 35)
                        $raidSuccess = true;
                    else
                        $raidSuccess = false;
                }
                elseif($successChance >= 8 && $successChance < 10)
                {
                    if($sucess > 60)
                        $raidSuccess = true;
                    else
                        $raidSuccess = false;
                }
                elseif($successChance >= 5 && $successChance < 8)
                {
                    if($sucess > 75)
                        $raidSuccess = true;
                    else
                        $raidSuccess = false;
                }
                else
                {
                    $raidSuccess = false;
                }
                
                $participants = array($userData->getId(), $familyRaid->getDriverID(), $familyRaid->getBombExpertID(), $familyRaid->getWeaponExpertID());
                if($raidSuccess == true)
                {
                    $stolenChance = $security->randInt(1, 10);
                    if($stolenChance = 9)
                        $stolen = $security->randInt(3500000, 4500000);
                    elseif($stolenChance != 9 && $stolenChance <= 6)
                        $stolen = $security->randInt(2750000, 3625000);
                    else
                        $stolen = $security->randInt(2150000, 3512000);
                    
                    $stolenEach = round($stolen / 4);
                    
                    $m = 5;
                    foreach($participants AS $p)
                    {
                        $missionService = new MissionService();
                        $mTierProgress = $missionService->getMissionTierAndProgressByMission($m, $p);
                        
                        $missionTier = $missionService->missionTiers[$m];
                        $todo = $missionTier['todo'][$mTierProgress['t']];
                        $bank = $missionTier['prizeMoney'][$mTierProgress['t']];
                        $hp = $missionTier['prizeHp'][$mTierProgress['t']];
                        if($mTierProgress['p'] + 1 >= $todo && $todo > $mTierProgress['p'])
                        {
                            $missionService->addToMission5Count($p, $todo - $mTierProgress['p']);
                            $missionService->payoutMissionPrize($bank, $hp, $p);
                            
                            $notification = new NotificationService();
                            $params = "mission=".$missionService->missions[$m]."&bank=".number_format($bank, 0, '', ',')."&hp=".number_format($hp, 0, '', ',');
                            $notification->sendNotification($p, 'USER_ACHIEVED_MISSION', $params);
                        }
                        elseif($todo > $mTierProgress['p'])
                            $missionService->addToMission5Count($p, 1);
                        
                        if($p !== $userData->getId())
                        {
                            $notification = new NotificationService();
                            $params = "state=".$userData->getState()."&stolenAmount=".number_format($stolen, 0, '', ',')."&stolenEach=".number_format($stolenEach, 0, '', ',');
                            $notification->sendNotification($p, 'START_FAMILY_RAID_SUCCESS', $params);
                        }
                    }
                    
                    $this->data->startFamilyRaidSuccess($familyRaid, $stolenEach);
                    
                    $replaces = array(
                        array('part' => $userData->getState(), 'message' => $l['START_FAMILY_RAID_SUCCESS'], 'pattern' => '/{state}/'),
                        array('part' => number_format($stolen, 0, '', ','), 'message' => FALSE, 'pattern' => '/{stolenAmount}/'),
                        array('part' => number_format($stolenEach, 0, '', ','), 'message' => FALSE, 'pattern' => '/{stolenEach}/')
                    );
                    $replacedMessage = $route->replaceMessageParts($replaces);
                    $response = Routing::successMessage($replacedMessage);
                }
                else
                {
                    $this->data->startFamilyRaidFail($familyRaid);
                    
                    $notification = new NotificationService();
                    $params = "state=".$userData->getState();
                    foreach($participants AS $p)
                        if($p !== $userData->getId())
                            $notification->sendNotification($p, 'START_FAMILY_RAID_FAIL', $params);
                    
                    $replacedMessage = $route->replaceMessagePart($userData->getState(), $l['START_FAMILY_RAID_FAIL'], '/{state}/');
                    $response = Routing::errorMessage($replacedMessage);
                }
                return $response;
            }
            elseif($quit)
            {
                $replaces = array(
                    array('part' => $security->getToken(), 'message' => $l['LEADER_QUIT_RAID_CONFIRM'], 'pattern' => '/{securityToken}/'),
                    array('part' => $familyRaidID, 'message' => FALSE, 'pattern' => '/{frid}/'),
                );
                $replacedMessage = $route->replaceMessageParts($replaces);                
                return Routing::successMessage($replacedMessage);
            }
            elseif($quitConfirm)
            {
                $this->data->quitFamilyRaid($familyRaidID);
                return Routing::successMessage($l['LEADER_QUIT_RAID_SUCCESS']);
            }
            elseif($changeDriver)
            {
                $this->data->changeFamilyRaidDriver($familyRaidID, $driverProfile->getId());
                
                // Send notification
                $notification = new NotificationService();
                $params = "state=".$userData->getState();
                $notification->sendNotification($driverProfile->getId(), 'DRIVER_INVITED_TO_FAMILY_RAID', $params);
            }
            elseif($changeBombExpert)
            {
                $this->data->changeFamilyRaidBombExpert($familyRaidID, $bombExpertProfile->getId());
                
                // Send notification
                $notification = new NotificationService();
                $params = "state=".$userData->getState();
                $notification->sendNotification($bombExpertProfile->getId(), 'BOMB_EXPERT_INVITED_TO_FAMILY_RAID', $params);
            }
            elseif($changeWeaponExpert)
            {
                $this->data->changeFamilyRaidWeaponExpert($familyRaidID, $weaponExpertProfile->getId());
                
                // Send notification
                $notification = new NotificationService();
                $params = "state=".$userData->getState();
                $notification->sendNotification($weaponExpertProfile->getId(), 'WEAPON_EXPERT_INVITED_TO_FAMILY_RAID', $params);
            }
            return Routing::successMessage($l['RAID_PARTICIPANT_CHANGED']);
        }
    }
    
    public function driverInteractFamilyRaid($post)
    {
        global $route;
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyRaidLangs();
        $fl = $language->familyLangs();
        $familyRaidID = (int)$post['familyRaidID'];
        if(isset($post['vehicleID'])) $vehicleID = (int)$post['vehicleID'];
        
        $garageService = new GarageService();
        $familyRaid = $this->data->getActiveFamilyRaid();
        if(is_object($familyRaid)) $raidStateID = $familyRaid->getStateID();
        
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
        if($userData->getFamilyID() == 0 || $userData->getFamilyID() == false)
        {
            $error = $fl['FAMILY_DOESNT_EXIST'];
        }
        if(isset($post['driver-accept']))
        {
            if($vehicleID == -1)
            {
                if($userData->getCash() < 15000)
                {
                    $error = $langs['NOT_ENOUGH_MONEY_CASH'];
                }
            }
            else
            {
                if(!$garageService->isVehicleInGarageInState($userData->getStateID(), $vehicleID))
                {
                    $error = $l['VEHICLE_NOT_IN_CURRENT_GARAGE'];
                }
            }
            if(is_object($familyRaid) && isset($raidStateID) && $raidStateID != $userData->getStateID())
            {
                $stateService = new StateService();
                $replacedMessage = $route->replaceMessagePart($stateService->getStateNameById($raidStateID), $l['NOT_IN_SAME_STATE_AS_RAID'], '/{state}/');
                $error = $replacedMessage;
            }
            if(is_object($familyRaid) && $familyRaid->getDriverReady())
            {
                $error = $l['ALREADY_PREPARED'];
            }
        }
        if(is_object($familyRaid) && $familyRaid->getId() != $familyRaidID || !is_object($familyRaid))
        {
            $error = $l['NOT_PARTICIPATING_IN_RAID'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            if(isset($post['driver-accept']))
            {
                $this->data->driverAcceptFamilyRaid($familyRaidID, $vehicleID);
                $replacedMessage = $route->replaceMessagePart(strtolower($l['DRIVER']), $l['PREPARE_RAID_TYPE_SUCCESS'], '/{type}/');
            }
            elseif(isset($post['driver-deny']))
            {
                $this->data->driverQuitFamilyRaid($familyRaidID);
                $replacedMessage = $l['DENY_RAID_SUCCESS'];
            }
            elseif(isset($post['driver-quit']))
            {
                if($familyRaid->getDriverReady())
                {
                    $replaces = array(
                        array('part' => $security->getToken(), 'message' => $l['QUIT_RAID_MESSAGE'], 'pattern' => '/{securityToken}/'),
                        array('part' => $familyRaidID, 'message' => FALSE, 'pattern' => '/{frid}/'),
                        array('part' => "driver", 'message' => FALSE, 'pattern' => '/{typeRaw}/')
                    );
                    $replacedMessage = $route->replaceMessageParts($replaces);
                }
            }
            elseif(isset($post['driver-quit-confirm']))
            {
                $this->data->driverQuitFamilyRaid($familyRaidID);
                $replacedMessage = $l['QUIT_RAID_SUCCESS'];
            }
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function bombExpertInteractFamilyRaid($post)
    {
        global $route;
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyRaidLangs();
        $fl = $language->familyLangs();
        $familyRaidID = (int)$post['familyRaidID'];
        if(isset($post['bombType'])) $bombType = (int)$post['bombType'];
                
        $familyRaid = $this->data->getActiveFamilyRaid();
        if(is_object($familyRaid)) $raidStateID = $familyRaid->getStateID();
        
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
        if($userData->getFamilyID() == 0 || $userData->getFamilyID() == false)
        {
            $error = $fl['FAMILY_DOESNT_EXIST'];
        }
        if(isset($post['bombExpert-accept']))
        {
            if(array_key_exists($bombType, $this->bombs) && $this->bombs[$bombType]['price'] > $userData->getCash())
            {
                $error = $langs['NOT_ENOUGH_MONEY_CASH'];
            }
            if(!array_key_exists($bombType, $this->bombs))
            {
                $error = $l['INVALID_EQUIPMENT'];
            }
            if(is_object($familyRaid) && isset($raidStateID) && $raidStateID != $userData->getStateID())
            {
                $stateService = new StateService();
                $replacedMessage = $route->replaceMessagePart($stateService->getStateNameById($raidStateID), $l['NOT_IN_SAME_STATE_AS_RAID'], '/{state}/');
                $error = $replacedMessage;
            }
            if(is_object($familyRaid) && $familyRaid->getBombExpertReady())
            {
                $error = $l['ALREADY_PREPARED'];
            }
        }
        if(is_object($familyRaid) && $familyRaid->getId() != $familyRaidID || !is_object($familyRaid))
        {
            $error = $l['NOT_PARTICIPATING_IN_RAID'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            if(isset($post['bombExpert-accept']))
            {
                $this->data->bombExpertAcceptFamilyRaid($familyRaidID, $bombType);
                $replacedMessage = $route->replaceMessagePart(strtolower($l['BOMB'])." expert", $l['PREPARE_RAID_TYPE_SUCCESS'], '/{type}/');
            }
            elseif(isset($post['bombExpert-deny']))
            {
                $this->data->bombExpertQuitFamilyRaid($familyRaidID);
                $replacedMessage = $l['DENY_RAID_SUCCESS'];
            }
            elseif(isset($post['bombExpert-quit']))
            {
                if($familyRaid->getBombExpertReady())
                {
                    $replaces = array(
                        array('part' => $security->getToken(), 'message' => $l['QUIT_RAID_MESSAGE'], 'pattern' => '/{securityToken}/'),
                        array('part' => $familyRaidID, 'message' => FALSE, 'pattern' => '/{frid}/'),
                        array('part' => "bombExpert", 'message' => FALSE, 'pattern' => '/{typeRaw}/')
                    );
                    $replacedMessage = $route->replaceMessageParts($replaces);
                }
            }
            elseif(isset($post['bombExpert-quit-confirm']))
            {
                $this->data->bombExpertQuitFamilyRaid($familyRaidID);
                $replacedMessage = $l['QUIT_RAID_SUCCESS'];
            }
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function weaponExpertInteractFamilyRaid($post)
    {
        global $route;
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyRaidLangs();
        $fl = $language->familyLangs();
        $familyRaidID = (int)$post['familyRaidID'];
        if(isset($post['weaponType'])) $weaponType = (int)$post['weaponType'];
        if(isset($post['bullets'])) $bullets = (int)$post['bullets'];
                
        $familyRaid = $this->data->getActiveFamilyRaid();
        if(is_object($familyRaid)) $raidStateID = $familyRaid->getStateID();
        
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
        if($userData->getFamilyID() == 0 || $userData->getFamilyID() == false)
        {
            $error = $fl['FAMILY_DOESNT_EXIST'];
        }
        if(isset($post['weaponExpert-accept']))
        {
            $price = $this->weapons[$weaponType]['price'];
            $price += $bullets*50;
            if(array_key_exists($weaponType, $this->weapons) && $price > $userData->getCash())
            {
                $error = $langs['NOT_ENOUGH_MONEY_CASH'];
            }
            if(!array_key_exists($weaponType, $this->weapons))
            {
                $error = $l['INVALID_EQUIPMENT'];
            }
            if($bullets < 50 || $bullets > 1000)
            {
                $error = $l['BULLETS_INFO'];
            }
            if(is_object($familyRaid) && isset($raidStateID) && $raidStateID != $userData->getStateID())
            {
                $stateService = new StateService();
                $replacedMessage = $route->replaceMessagePart($stateService->getStateNameById($raidStateID), $l['NOT_IN_SAME_STATE_AS_RAID'], '/{state}/');
                $error = $replacedMessage;
            }
            if(is_object($familyRaid) && $familyRaid->getWeaponExpertReady())
            {
                $error = $l['ALREADY_PREPARED'];
            }
        }
        if(is_object($familyRaid) && $familyRaid->getId() != $familyRaidID || !is_object($familyRaid))
        {
            $error = $l['NOT_PARTICIPATING_IN_RAID'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            if(isset($post['weaponExpert-accept']))
            {
                $this->data->weaponExpertAcceptFamilyRaid($familyRaidID, $weaponType, $bullets);
                $replacedMessage = $route->replaceMessagePart(strtolower($langs['WEAPON'])." expert", $l['PREPARE_RAID_TYPE_SUCCESS'], '/{type}/');
            }
            elseif(isset($post['weaponExpert-deny']))
            {
                $this->data->weaponExpertQuitFamilyRaid($familyRaidID);
                $replacedMessage = $l['DENY_RAID_SUCCESS'];
            }
            elseif(isset($post['weaponExpert-quit']))
            {
                if($familyRaid->getWeaponExpertReady())
                {
                    $replaces = array(
                        array('part' => $security->getToken(), 'message' => $l['QUIT_RAID_MESSAGE'], 'pattern' => '/{securityToken}/'),
                        array('part' => $familyRaidID, 'message' => FALSE, 'pattern' => '/{frid}/'),
                        array('part' => "weaponExpert", 'message' => FALSE, 'pattern' => '/{typeRaw}/')
                    );
                    $replacedMessage = $route->replaceMessageParts($replaces);
                }
            }
            elseif(isset($post['weaponExpert-quit-confirm']))
            {
                $this->data->weaponExpertQuitFamilyRaid($familyRaidID);
                $replacedMessage = $l['QUIT_RAID_SUCCESS'];
            }
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function getFamilyRaidMemberList()
    {
        return $this->data->getFamilyRaidMemberList();
    }
    
    public function getActiveFamilyRaid()
    {
        return $this->data->getActiveFamilyRaid();
    }
    
    public function userInsideFamilyRaid()
    {
        return $this->data->userInsideFamilyRaid();
    }
    
    public function userInsideAcceptedFamilyRaid()
    {
        return $this->data->userInsideAcceptedFamilyRaid();
    }
}
