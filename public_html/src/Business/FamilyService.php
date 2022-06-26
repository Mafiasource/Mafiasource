<?PHP

namespace src\Business;

use src\Business\NotificationService;
use src\Business\PossessionService;
use src\Business\Logic\UploadService;
use src\Data\FamilyDAO;
use app\config\Routing;

/* Class has some cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */
 
class FamilyService
{
    private $data;
    
    public $userFamilyID; // User's famid. can be 0 / false
    
    public function __construct()
    {
        $this->data = new FamilyDAO();
        global $userData;
        $this->userFamilyID = $userData->getFamilyID();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    static function is_name($i){
        if(!preg_match("#^[A-Za-z0-9-]{3,15}$#s", $i))
            return FALSE;
        else
            return TRUE;
    }
    
    public function checkFamilyExists($familyName)
    {
        return $this->data->checkFamilyExists($familyName);
    }
    
    public function getFamlist($from, $to)
    {
        return $this->data->getFamlist($from, $to);
    }
    
    public function createFamily($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userService;
            global $userData;
            
            $familyName = $security->xssEscape($post['familyname']);
            $seedMoney = (int)$post['seedmoney'];
            $familyData = $this->data->getFamilyDataByName($familyName);
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            $userProfile = $userService->getUserProfile($userData->getUsername());
            if(isset($userProfile) && $userProfile->getFamilyID() != 0)
            {
                $error = $l['ALREADY_PART_OF_A_FAMILY'];
            }
            if(isset($userProfile) && ($userProfile->getRankID() < 8 || $userProfile->getCrimesLv() < 10 || $userProfile->getVehiclesLv() < 10 || $userProfile->getPimpLv() < 10 ||
                $userProfile->getSmugglingLv() < 10)
            )
            {
                $error = $l['CANNOT_CREATE_FAMILY'];
            }
            if($this->is_name($familyName) == FALSE)
            {
                $error = $l['INVALID_FAMILYNAME'];
            }
            if($familyData !== FALSE)
            {
                $error = $l['FAMILYNAME_ALREADY_TAKEN'];
            }
            if(isset($userProfile) && $seedMoney > $userProfile->getCash())
            {
                $error = $langs['NOT_ENOUGH_MONEY_CASH'];
            }
            if($seedMoney < 0 || $seedMoney > 999999999)
            {
                $error = $langs['BETWEEN_0_AND_999M'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $this->data->createFamily($familyName, $seedMoney);
                $successMessage = $route->replaceMessagePart($familyName, $l['CREATE_FAMILY_SUCCESS'], '/{familyName}/');
                $successMessage = $route->replaceMessagePart(number_format($seedMoney, 0, '', ','), $successMessage, '/{seedMoney}/');
                return Routing::successMessage($successMessage);
            }
        }
    }
    
    public function joinFamily($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            $family   = $security->xssEscape($post['family']);
            global $userData;
            
            $familyData = $this->data->getFamilyDataByName($family);
            
            if($security->checkToken($post['security-token']) == FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if(is_object($familyData))
            {
                $pl = $language->possessionsLangs();
                $possessionService = new PossessionService();
                $userPossessions = $possessionService->getProfilePossessionsByUserID($userData->getId());
                foreach($userPossessions AS $pData)
                {
                    $psData = $pData->getPossessDetails();
                    if(is_object($psData))
                    {
                        if($psData->getCity() != "")
                            $familyPossAmount = $possessionService->familyCityPossLimit; // 3
                        elseif($psData->getState() != "")
                            $familyPossAmount = $possessionService->familyStatePossLimit; // 2
                        elseif($psData->getState() == "")
                            $familyPossAmount = $possessionService->familyCountryPossLimit; // 1
                        
                        if($possessionService->familyHasAmountPossessionsById($psData->getPID(), $familyData->getId(), $familyPossAmount))
                        {
                            $error = $pl['FAMILY_MAX_POSSESSION']; // TODO new replaced msg
                        }
                        if($psData->getState() == "" && $psData->getCity() == "" && $possessionService->familyHasAmountCountryPossessions($familyData->getId(), $familyPossAmount))
                        {
                            $error = $pl['FAMILY_MAX_COUNTRY_POSSESSION']; // TODO To be changed?
                        }
                    }
                }
            }
            if(is_object($familyData) && $familyData->getJoin() == FALSE)
            {
                $error = $l['NO_JOINS_ALLOWED'];
            }
            if(is_object($familyData) && $this->data->userJoinedFamily($familyData->getId()) == TRUE)
            {
                $error = $l['ALREADY_ATTEMPTED_JOIN'];
            }
            if(is_object($familyData) && $this->data->userInvitedToFamily($familyData->getId()) == TRUE)
            {
                $error = $l['ALREADY_INVITED_TO_FAMILY'];
            }
            if($this->userFamilyID != 0)
            {
                $error = $l['ALREADY_PART_OF_A_FAMILY'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                global $userService;
                $this->data->joinFamily($familyData->getId());
                $replacedMessage = $route->replaceMessagePart($familyData->getName(), $l['FAMILY_JOINED_SUCCESSFUL'], '/{familyName}/');
                
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername();
                $bossUID = $userService->getIdByUsername($this->getFamilyPageDataByName($familyData->getName())->getBoss());
                $notification->sendNotification($bossUID, 'USER_JOINED_FAMILY', $params);
                if($underbossUID = $userService->getIdByUsername($this->getFamilyPageDataByName($familyData->getName())->getUnderboss()))
                    $notification->sendNotification($underbossUID, 'USER_JOINED_FAMILY', $params);
                    
                return Routing::successMessage($replacedMessage);
            }
        }
    }
    
    public function leaveFamily($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            $family   = $security->xssEscape($post['family']);
            global $userData;
            
            $familyData = $this->data->getFamilyDataByName($family);
            $fid = $this->userFamilyID;
            
            if($security->checkToken($post['security-token']) == FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if(!isset($familyData) || $familyData == FALSE || $fid == 0 || $familyData->getId() != $fid)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if($familyData->getId() == $fid && $userData->getFamilyBoss())
            {
                $error = $l['FAMILY_BOSS_CANNOT_LEAVE'];
            }
            if($userData->getCash() < $familyData->getLeaveCosts())
            {
                $error = $langs['NOT_ENOUGH_MONEY_CASH'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $this->data->leaveFamily($familyData->getId(), $familyData->getLeaveCosts());
                
                $replaces = array(
                    array('part' => $familyData->getName(), 'message' => $l['LEFT_FAMILY_SUCCESSFUL'], 'pattern' => '/{familyName}/'),
                    array('part' => number_format($familyData->getLeaveCosts(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{leaveCosts}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
                return Routing::successMessage($replacedMessage);
            }
        }
    }

    public function handleFamilyInvitation($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userData;
            $family = $security->xssEscape($post['family']);
            
            $committedAction = $langs['ACCEPTED'];
            if(isset($post['deny'])) $committedAction = $langs['DENIED'];
            
            $familyData = $this->data->getFamilyDataByName($family);
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if(is_object($familyData))
            {
                $pl = $language->possessionsLangs();
                $possessionService = new PossessionService();
                $userPossessions = $possessionService->getProfilePossessionsByUserID($userData->getId());
                foreach($userPossessions AS $pData)
                {
                    $psData = $pData->getPossessDetails();
                    if(is_object($psData))
                    {
                        if($psData->getCity() != "")
                            $familyPossAmount = $possessionService->familyCityPossLimit; // 3
                        elseif($psData->getState() != "")
                            $familyPossAmount = $possessionService->familyStatePossLimit; // 2
                        elseif($psData->getState() == "")
                            $familyPossAmount = $possessionService->familyCountryPossLimit; // 1
                        
                        if($possessionService->familyHasAmountPossessionsById($psData->getPID(), $familyData->getId(), $familyPossAmount))
                        {
                            $error = $pl['FAMILY_MAX_POSSESSION']; // TODO new replaced msg
                        }
                        if($psData->getState() == "" && $psData->getCity() == "" && $possessionService->familyHasAmountCountryPossessions($familyData->getId(), $familyPossAmount))
                        {
                            $error = $pl['FAMILY_MAX_COUNTRY_POSSESSION']; // TODO To be changed?
                        }
                    }
                }
            }
            if($this->userFamilyID != 0)
            {
                $error = $l['ALREADY_IN_DIFFERENT_FAMILY'];
            }
            if(is_object($familyData) && $this->data->checkInvitedUser($familyData->getId(), $userData->getId()) != TRUE)
            {
                $error = $l['INVALID_FAMILY_INVITATION'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $successMessage = $route->replaceMessagePart($family, $l['HANDLE_INVITATION_SUCCESS'], '/{family}/');
                $successMessage = $route->replaceMessagePart(strtolower($committedAction), $successMessage, '/{committedAction}/');
                if($committedAction != 'Geweigerd' && $committedAction != 'Denied')
                {
                    $successMessage = $route->replaceMessagePart($l['NOW_MEMBER_OF_FAMILY']." ".$familyData->getName(), $successMessage, '/{add}/');
                    
                    $this->data->acceptFamilyInvitation($familyData->getId(), $userData->getId());
                    return Routing::successMessage($successMessage);
                }
                else
                {
                    $successMessage = $route->replaceMessagePart('', $successMessage, '/{add}/');
                    
                    $this->data->denyFamilyInvitation($familyData->getId(), $userData->getId());
                    return Routing::errorMessage($successMessage);
                }
            }
        }
    }

    public function getFamilyInvitations()
    {
        return $this->data->getFamilyInvitations();
    }

    public function searchFamily($post)
    {
        global $language;
        global $langs;
        $l        = $language->familyLangs();
        global $security;
        $keyword = $security->xssEscape($post['search']);
        
        $searchData = $this->data->searchFamiliesByKeyword($keyword);
        
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($searchData == FALSE)
        {
            $error = $l['NO_FAMILIES_FOUND'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $successMsg = Routing::successMessage($l['FAMILY_SEARCH_SUCCESSFUL']);
            return array('msg' => $successMsg, 'data' => $searchData);
        }
    }

    public function donateToFamily($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userData;
            $amount   = (int)$post['amount'];
            
            $fid = $this->userFamilyID;
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($fid == 0)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if($amount > $userData->getBank())
            {
                $error = $langs['NOT_ENOUGH_MONEY_BANK'];
            }
            if($amount < 1 || $amount > 999999999)
            {
                $error = $langs['BETWEEN_1_AND_999M'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $this->data->donateToFamily($fid, $amount);
                $successMessage = $route->replaceMessagePart(number_format($amount, 0, '', ','), $l['DONATE_FAMILY_SUCCESS'], '/{amount}/');
                return Routing::successMessage($successMessage);
            }
        }
    }
    
    public function bankTransferToUser($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userService;
            global $userData;
            $amount   = (int)$post['amount'];
            $username = $security->xssEscape($post['user']);
            $receiver = $userService->getIdByUsername($username);
            
            if(isset($username) && is_numeric($receiver) && $receiver != 0) $receiverProfile = $userService->getUserProfile($username);
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            $fid = $this->userFamilyID;
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($fid == 0)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if(!isset($receiverProfile) || (is_object($receiverProfile) && $receiverProfile->getFamilyID() != $fid))
            {
                $error = $l['USER_NOT_INSIDE_FAMILY'];
            }
            if(is_object($familyData) && $amount > $familyData->getMoney())
            {
                $error = $l['NOT_ENOUGH_MONEY_FAMBANK'];
            }
            if($amount < 100 || $amount > 999999999)
            {
                $error = $langs['BETWEEN_100_AND_999M'];
            }
            $rqstrUsername = $userData->getUsername();
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyBankmanager() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $rqstrUsername
                && $this->getFamilyPageDataByName($familyData->getName())->getBankmanager() != $rqstrUsername
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_BANK'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $this->data->bankTransferToUser($fid, $receiver, $amount);
                $successMessage = $route->replaceMessagePart(number_format($amount, 0, '', ','), $l['BANK_TRANSFER_FAMILY_SUCCESS'], '/{amount}/');
                $successMessage = $route->replaceMessagePart($username, $successMessage, '/{user}/');
                return Routing::successMessage($successMessage);
            }
        }
    }
    
    public function handleFamilyJoin($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userService;
            global $userData;
            $username = $security->xssEscape($post['username']);
            
            $commitedAction = $langs['ACCEPTED'];
            if(isset($post['deny'])) $commitedAction = $langs['DENIED'];
            
            $userProfile = $userService->getUserProfile($username);
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if(is_object($familyData) && is_object($userProfile))
            {
                $pl = $language->possessionsLangs();
                $possessionService = new PossessionService();
                $userPossessions = $possessionService->getProfilePossessionsByUserID($userProfile->getId());
                foreach($userPossessions AS $pData)
                {
                    $psData = $pData->getPossessDetails();
                    if(is_object($psData))
                    {
                        if( $psData->getCity() != "")
                            $familyPossAmount = $possessionService->familyCityPossLimit; // 3
                        elseif($psData->getState() != "")
                            $familyPossAmount = $possessionService->familyStatePossLimit; // 2
                        elseif($psData->getState() == "")
                            $familyPossAmount = $possessionService->familyCountryPossLimit; // 1
                        
                        if($possessionService->familyHasAmountPossessionsById($psData->getPID(), $familyData->getId(), $familyPossAmount))
                        {
                            $error = $pl['FAMILY_MAX_POSSESSION']; // TODO new replaced message
                        }
                        if($psData->getState() == "" && $psData->getCity() == "" && $possessionService->familyHasAmountCountryPossessions($familyData->getId(), $familyPossAmount))
                        {
                            $error = $pl['RECEIVER_ALREADY_OWN_COUNTRY_POSSESSION']; // TODO To be changed?
                        }
                    }
                }
            }
            if(is_object($userProfile) && $userProfile->getFamilyID() != 0)
            {
                $error = $l['USER_ALREADY_IN_DIFFERENT_FAMILY'];
            }
            if(is_object($familyData) && $this->data->checkJoinedUser($familyData->getId(), $userProfile->getId()) != TRUE)
            {
                $error = $l['USER_DID_NOT_JOIN'];
            }
            $rqstrUsername = $userData->getUsername();
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $rqstrUsername
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $rqstrUsername
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $successMessage = $route->replaceMessagePart($username, $l['HANDLE_JOIN_SUCCESS'], '/{user}/');
                $successMessage = $route->replaceMessagePart(strtolower($commitedAction), $successMessage, '/{commitedAction}/');
                if($commitedAction != 'Geweigerd' && $commitedAction != 'Denied')
                {
                    $notification = new NotificationService();
                    $params = "family=".$familyData->getName();
                    $notification->sendNotification($userProfile->getId(), 'ACCEPTED_JOINED_USER_TO_FAMILY', $params);
                    
                    $this->data->acceptJoinedUser($familyData->getId(), $userProfile->getId());
                    return Routing::successMessage($successMessage);
                }
                else
                {
                    $notification = new NotificationService();
                    $params = "family=".$familyData->getName();
                    $notification->sendNotification($userProfile->getId(), 'DENIED_JOINED_USER_TO_FAMILY', $params);
                    
                    $this->data->denyJoinedUser($familyData->getId(), $userProfile->getId());
                    return Routing::errorMessage($successMessage);
                }
            }
        }
    }
    
    public function handleFamilyInvite($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userService;
            global $userData;
            $username = $security->xssEscape($post['username']);
            $userID = $userService->getIdByUsername($username);
            
            $commitedAction = FALSE;
            if(isset($post['delete'])) $commitedAction = $langs['DELETED'];
            
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if(is_object($familyData) && is_numeric($userID))
            {
                $pl = $language->possessionsLangs();
                $possessionService = new PossessionService();
                $userPossessions = $possessionService->getProfilePossessionsByUserID($userID);
                foreach($userPossessions AS $pData)
                {
                    $psData = $pData->getPossessDetails();
                    if(is_object($psData))
                    {
                        if($psData->getCity() != "")
                            $familyPossAmount = $possessionService->familyCityPossLimit; // 3
                        elseif($psData->getState() != "")
                            $familyPossAmount = $possessionService->familyStatePossLimit; // 2
                        elseif($psData->getState() == "")
                            $familyPossAmount = $possessionService->familyCountryPossLimit; // 1
                        
                        if($possessionService->familyHasAmountPossessionsById($psData->getPID(), $familyData->getId(), $familyPossAmount))
                        {
                            $error = $pl['FAMILY_MAX_POSSESSION']; // TODO new replaced msg
                        }
                        if($psData->getState() == "" && $psData->getCity() == "" && $possessionService->familyHasAmountCountryPossessions($familyData->getId(), $familyPossAmount))
                        {
                            $error = $pl['RECEIVER_ALREADY_OWN_COUNTRY_POSSESSION']; // TODO To be changed?
                        }
                    }
                }
            }
            if(is_object($familyData) && $this->data->checkInvitedUser($familyData->getId(), $userID) != TRUE)
            {
                $error = $l['USER_NOT_INVITED'];
            }
            $rqstrUsername = $userData->getUsername();
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $rqstrUsername
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $rqstrUsername
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $successMessage = $route->replaceMessagePart($username, $l['HANDLE_INVITE_SUCCESS'], '/{user}/');
                $successMessage = $route->replaceMessagePart(strtolower($commitedAction), $successMessage, '/{commitedAction}/');
                $this->data->deleteInvitedUser($familyData->getId(), $userID);
                return Routing::successMessage($successMessage);
            }
        }
    }
    
    public function kickFamilyMember($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userService;
            global $userData;
            $username = $security->xssEscape($post['user']);
            
            $notKickable = TRUE;
            foreach($this->getKickList() AS $kl) if($kl->getUsername() == $username) $notKickable = FALSE;
            
            $userProfile = $userService->getUserProfile($username);
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if(is_object($userProfile) && is_object($familyData) && $userProfile->getFamily() != $familyData->getName() || $notKickable === TRUE)
            {
                $error = $l['USER_NOT_INSIDE_FAMILY'];
            }
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $userData->getUsername()
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $userData->getUsername()
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $this->data->kickFamilyMember($familyData->getId(), $userProfile->getId());
                $successMessage = $route->replaceMessagePart($username, $l['KICK_FAMILY_MEMBER_SUCCESS'], '/{user}/');
                return Routing::successMessage($successMessage);
            }
        }
    }
    
    public function inviteMemberToFamily($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userService;
            global $userData;
            $username = $security->xssEscape($post['username']);
            
            $userProfile = $userService->getUserProfile($username);
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if(is_object($familyData) && is_object($userProfile))
            {
                $pl = $language->possessionsLangs();
                $possessionService = new PossessionService();
                $userPossessions = $possessionService->getProfilePossessionsByUserID($userProfile->getId());
                foreach($userPossessions AS $pData)
                {
                    $psData = $pData->getPossessDetails();
                    if(is_object($psData))
                    {
                        if($psData->getCity() != "")
                            $familyPossAmount = $possessionService->familyCityPossLimit; // 3
                        elseif($psData->getState() != "")
                            $familyPossAmount = $possessionService->familyStatePossLimit; // 2
                        elseif($psData->getState() == "")
                            $familyPossAmount = $possessionService->familyCountryPossLimit; // 1
                        
                        if($possessionService->familyHasAmountPossessionsById($psData->getPID(), $familyData->getId(), $familyPossAmount))
                        {
                            $error = $pl['FAMILY_MAX_POSSESSION'];// TODO new replaced msg
                        }
                        if($psData->getState() == "" && $psData->getCity() == "" && $possessionService->familyHasAmountCountryPossessions($familyData->getId(), $familyPossAmount))
                        {
                            $error = $pl['RECEIVER_ALREADY_OWN_COUNTRY_POSSESSION']; // TODO To be changed?
                        }
                    }
                }
            }
            if(!is_object($userProfile))
            {
                $error = $langs['PLAYER_DOESNT_EXIST'];
            }
            if(is_object($userProfile) && $userProfile->getFamilyID() > 0)
            {
                $error = $l['USER_ALREADY_IN_DIFFERENT_FAMILY'];
            }
            if(is_object($userProfile) && is_object($familyData) && $this->data->checkJoinedUser($familyData->getId(), $userProfile->getId()) == TRUE)
            {
                $error = $l['USER_ALREADY_ATTEMPTED_JOIN'];
            }
            if(is_object($userProfile) && is_object($familyData) && $this->data->checkInvitedUser($familyData->getId(), $userProfile->getId()) == TRUE)
            {
                $error = $l['USER_ALREADY_INVITED_TO_FAMILY'];
            }
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $userData->getUsername()
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $userData->getUsername()
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $this->data->inviteMemberToFamily($familyData->getId(), $userProfile->getId());
                $successMessage = $route->replaceMessagePart($userProfile->getUsername(), $l['INVITE_FAMILY_MEMBER_SUCCESS'], '/{user}/');
                
                $notification = new NotificationService();
                $params = "family=".$familyData->getName();
                $notification->sendNotification($userProfile->getId(), 'USER_INVITED_TO_FAMILY', $params);
                    
                return Routing::successMessage($successMessage);
            }
        }
    }
    
    public function changeJoinpolicy($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userData;
            $join     = (int)$post['join'];
            
            $openClosed = $l['OPEN'];
            if($join == 0) $openClosed = $l['CLOSED'];
            
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if($join != 0 && $join != 1)
            {
                $error = $langs['INVALID_ACTION'];
            }
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $userData->getUsername()
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $userData->getUsername()
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $this->data->changeJoinpolicy($familyData->getId(), $join);
                $successMessage = $route->replaceMessagePart($openClosed, $l['CHANGE_JOINPOLICY_SUCCESS'], '/{openclosed}/');
                return Routing::successMessage($successMessage);
            }
        }
    }
    
    public function uploadFamilyImage($post, $files)
    {
        if(isset($_SESSION['UID']))
        {
            global $route;
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userData;
            
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            
            if($files['image']['size'] == 0)
            {
                $errorMessage = $route->replaceMessagePart(strtolower($l['IMAGE']), $l['UPLOAD_FAMILY_IMAGE_FAILED'], '/{type}/');
               	$error = $errorMessage;
            }
            
            if(isset($files['image']) && $files['image']['error']== UPLOAD_ERR_OK && is_object($familyData))
            {
                $saveDir = DOC_ROOT . '/web/public/images/families/'.$familyData->getId();
                $nameForFile = $familyData->getName().'_family_image';
                
                $upload = new UploadService($files, 'image', $saveDir, $nameForFile, '250');
                $res = $upload->response;
                $newFileName = $upload->uploadedFileName;
                
                if(is_array($res))
                {
                    if(isset($res['error']))
                    {
                        switch((int)$res['error'])
                        {
                            case 3:
                                $errorMessage = $route->replaceMessagePart(strtolower($l['IMAGE']), $l['UPLOAD_FAMILY_IMAGE_WRONG_FILE'], '/{type}/');
                            	$error = $errorMessage;
                                break;
                            case 5:
                                $errorMessage = $route->replaceMessagePart(strtolower($l['IMAGE']), $l['UPLOAD_FAMILY_IMAGE_FAILED'], '/{type}/');
                                $error = $errorMessage;
                                break;
                            default:
                                $error = $res['error'];
                                break;
                        }
                    }
                    else if(isset($res['success']))
                    {
                        if($res['success'] === 1)
                        {
                            $this->data->updateFamilyImage($newFileName, $familyData->getId());
                            $successMessage = $route->replaceMessagePart(strtolower($l['IMAGE']), $l['UPLOAD_FAMILY_IMAGE_SUCCESS'], '/{type}/');
                            $success = $successMessage;
                        }
                    }
                }
            }
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $userData->getUsername()
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $userData->getUsername()
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                return Routing::successMessage($success);
            }
        }
    }
    
    public function uploadFamilyIcon($post, $files)
    {
        if(isset($_SESSION['UID']))
        {
            global $route;
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userData;
            
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            
            if($files['icon']['size'] == 0)
            {
                $errorMessage = $route->replaceMessagePart('icon', $l['UPLOAD_FAMILY_IMAGE_FAILED'], '/{type}/');
               	$error = $errorMessage;
            }
            
            if(isset($files['icon']) && $files['icon']['error']== UPLOAD_ERR_OK && is_object($familyData))
            {
                $saveDir = DOC_ROOT . '/web/public/images/families/'.$familyData->getId();
                $nameForFile = $familyData->getName().'_family_icon';
                
                $upload = new UploadService($files, 'icon', $saveDir, $nameForFile, '38', true, '38');
                $res = $upload->response;
                $newFileName = $upload->uploadedFileName;
                
                if(is_array($res))
                {
                    if(isset($res['error']))
                    {
                        switch((int)$res['error'])
                        {
                            case 3:
                                $errorMessage = $route->replaceMessagePart('icon', $l['UPLOAD_FAMILY_IMAGE_WRONG_FILE'], '/{type}/');
                                $error = $errorMessage;
                                break;
                            case 5:
                                $errorMessage = $route->replaceMessagePart('icon', $l['UPLOAD_FAMILY_IMAGE_FAILED'], '/{type}/');
                                $error = $errorMessage;
                                break;
                            default:
                                $error = $res['error'];
                                break;
                        }
                    }
                    else if(isset($res['success']))
                    {
                        if($res['success'] === 1)
                        {
                            $this->data->updateFamilyIcon($newFileName, $familyData->getId());
                            $successMessage = $route->replaceMessagePart('icon', $l['UPLOAD_FAMILY_IMAGE_SUCCESS'], '/{type}/');
                        	$success = $successMessage;
                        }
                    }
                }
            }
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $userData->getUsername()
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $userData->getUsername()
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                return Routing::successMessage($success);
            }
        }
    }
    
    public function updateFamilyProfile($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userData;
            
            $profile = $security->xssEscapeAndHtml($post['profile']);
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            $maxlength = 1000;
            if($familyData->getVip())
                $maxlength = 2000;
            
            if(strlen(strip_tags($profile)) > $maxlength)
            {
                global $route;
                $error = $route->replaceMessagePart(number_format($maxlength, 0, '', ','), $l['FAMILY_PROFILE_TO_LONG'], '/{max}/');
            }
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $userData->getUsername()
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $userData->getUsername()
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                $this->data->updateFamilyProfile($profile, $familyData->getId());
                $successMessage = $l['UPDATE_FAMILY_PROFILE_SUCCESS'];
                return Routing::successMessage($successMessage);
            }
        }
    }
    
    public function updateFamilyMessage($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userData;
            
            $message = $security->xssEscapeAndHtml($post['message']);
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            $maxlength = 10000;
            if(strlen(strip_tags($message)) > $maxlength)
            {
                $error = $l['FAMILY_MESSAGE_TO_LONG'];
            }
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $userData->getUsername()
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $userData->getUsername()
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                $this->data->updateFamilyMessage($message, $familyData->getId());
                $successMessage = $l['UPDATE_FAMILY_MESSAGE_SUCCESS'];
                return Routing::successMessage($successMessage);
            }
        }
    }
    
    public function requestFamilyAlliance($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userData;
            
            $family = $security->xssEscape($post['family']);
            $invitedFamData = $this->data->getFamilyPageDataByName($family);
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE || $invitedFamData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if(is_object($familyData) && is_object($invitedFamData) && $familyData->getId() === $invitedFamData->getId())
            {
                $error = $l['NO_ALLIANCE_WITH_SELF'];
            }
            if(is_object($familyData) && is_object($invitedFamData) && $this->data->hasAllianceRecordWithFamily($familyData->getId(), $invitedFamData->getId()))
            {
                $error = $l['ALREADY_PENDING_OR_ALLIANCE'];
            }
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $userData->getUsername()
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $userData->getUsername()
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                global $userService;
                $this->data->requestFamilyAlliance($familyData->getId(), $invitedFamData->getId());
                
                $notification = new NotificationService();
                $params = "family=".$familyData->getName();
                $bossUID = $userService->getIdByUsername($invitedFamData->getBoss());
                $notification->sendNotification($bossUID, 'FAMILY_ALLIANCE_REQUESTED', $params);
                if($underbossUID = $userService->getIdByUsername($invitedFamData->getUnderboss()))
                    $notification->sendNotification($underbossUID, 'FAMILY_ALLIANCE_REQUESTED', $params);
                
                $replacedMessage = $route->replaceMessagePart($invitedFamData->getName(), $l['FAMILY_ALLIANCE_INVITE_SUCCESS'], '/{family}/');
                return Routing::successMessage($replacedMessage);
            }
        }
    }
    
    public function handleFamilyAlliance($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userData;
            
            $family = $security->xssEscape($post['family']);
            $allianceFamData = $this->data->getFamilyPageDataByName($family);
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            
            $accepted = isset($post['accept']) ? $post['accept'] : null;
            $denied = isset($post['deny']) ? $post['deny'] : null;
            $removed = isset($post['remove']) ? $post['remove'] : null;
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE || $allianceFamData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if(is_object($familyData) && is_object($allianceFamData) && $familyData->getId() === $allianceFamData->getId())
            {
                $error = $l['NO_ALLIANCE_WITH_SELF'];
            }
            if($accepted)
            {
                if(is_object($familyData) && is_object($allianceFamData) && $this->data->hasAllianceWithFamily($familyData->getId(), $allianceFamData->getId()))
                {
                    $error = $l['ALREADY_ACTIVE_ALLIANCE'];
                }
            }
            elseif($denied || $removed)
            {
                if(is_object($familyData) && is_object($allianceFamData) && !$this->data->hasAllianceRecordWithFamily($familyData->getId(), $allianceFamData->getId()))
                {
                    $error = $l['NO_PENDING_OR_ALLIANCE'];
                }
            }
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $userData->getUsername()
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $userData->getUsername()
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                global $userService;
                if($accepted)
                {
                    $this->data->acceptFamilyAlliance($familyData->getId(), $allianceFamData->getId());
                    
                    $notification = new NotificationService();
                    $params = "family=".$familyData->getName();
                    $bossUID = $userService->getIdByUsername($allianceFamData->getBoss());
                    $notification->sendNotification($bossUID, 'FAMILY_ALLIANCE_ACCEPTED', $params);
                    if($underbossUID = $userService->getIdByUsername($allianceFamData->getUnderboss()))
                        $notification->sendNotification($underbossUID, 'FAMILY_ALLIANCE_ACCEPTED', $params);
                    
                    $replaces = array(
                        array('part' => $allianceFamData->getName(), 'message' => $l['FAMILY_HANDLE_ALLIANCE_SUCCESS'], 'pattern' => '/{family}/'),
                        array('part' => strtolower($langs['ACCEPTED']), 'message' => FALSE, 'pattern' => '/{type}/')
                    );
                    $replacedMessage = $route->replaceMessageParts($replaces);
                }
                elseif($denied)
                {
                    $this->data->removeFamilyAlliance($familyData->getId(), $allianceFamData->getId());
                    
                    $notification = new NotificationService();
                    $params = "family=".$familyData->getName();
                    $bossUID = $userService->getIdByUsername($allianceFamData->getBoss());
                    $notification->sendNotification($bossUID, 'FAMILY_ALLIANCE_DENIED', $params);
                    if($underbossUID = $userService->getIdByUsername($allianceFamData->getUnderboss()))
                        $notification->sendNotification($underbossUID, 'FAMILY_ALLIANCE_DENIED', $params);
                        
                    $replaces = array(
                        array('part' => $allianceFamData->getName(), 'message' => $l['FAMILY_HANDLE_ALLIANCE_SUCCESS'], 'pattern' => '/{family}/'),
                        array('part' => strtolower($langs['DENIED']), 'message' => FALSE, 'pattern' => '/{type}/')
                    );
                    $replacedMessage = $route->replaceMessageParts($replaces);
                }
                elseif($removed)
                {
                    $this->data->removeFamilyAlliance($familyData->getId(), $allianceFamData->getId());
                    $replaces = array(
                        array('part' => $allianceFamData->getName(), 'message' => $l['FAMILY_HANDLE_ALLIANCE_SUCCESS'], 'pattern' => '/{family}/'),
                        array('part' => strtolower($langs['DELETED']), 'message' => FALSE, 'pattern' => '/{type}/')
                    );
                    $replacedMessage = $route->replaceMessageParts($replaces);
                }
                return Routing::successMessage($replacedMessage);
            }
        }
    }
    
    public function manageFamilyTop($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userService;
            global $userData;
            
            $bossConfirmed = isset($post['boss-confirm']) ? $security->xssEscape($post['boss-confirm']) : null;
            $boss = isset($post['boss']) ? $security->xssEscape($post['boss']) : null;
            $bankmanager = isset($post['bankmanager']) ? $security->xssEscape($post['bankmanager']) : null;
            $underboss = isset($post['underboss']) ? $security->xssEscape($post['underboss']) : null;
            $forummod = isset($post['forummod']) ? $security->xssEscape($post['forummod']) : null;
            
            if($bossConfirmed !== null) $username =  $bossConfirmed;
            elseif($boss !== null) $username = $boss;
            elseif($bankmanager !== null) $username = $bankmanager;
            elseif($underboss !== null) $username = $underboss;
            elseif($forummod !== null) $username = $forummod;
            
            $userProfile = $userService->getUserProfile($username);
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if(is_object($familyData) &&  $this->getFamilyPageDataByName($familyData->getName())->getBoss() == $username ||
                $this->getFamilyPageDataByName($familyData->getName())->getBankmanager() == $username ||
                $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() == $username ||
                $this->getFamilyPageDataByName($familyData->getName())->getForummod() == $username
            )
            {
                $error = $l['USER_ALREADY_IN_FAMILY_TOP'];
            }
            if((is_object($userProfile) && is_object($familyData) && $userProfile->getFamily() != $familyData->getName()) || (!is_object($userProfile) && $username !== '0'))
            {
                $error = $l['USER_NOT_INSIDE_FAMILY'];
            }
            if(($boss !== null && $boss === '0') || $bossConfirmed !== null && $bossConfirmed === '0')
            {
                $error = $l['FAMILY_BOSS_REQUIRED'];
            }
            if(is_object($familyData) && !$userData->GetFamilyBoss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $userData->getUsername()
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            if(is_object($familyData) && $familyData->getVip() == 0 && isset($forummod))
            {
                $error = $l['FAMILY_NEEDS_VIP_STATUS'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                
                $userID = 0;
                if($username !== '0') $userID = $userProfile->getId();
                if(isset($boss))
                {
                    $replaces = array(
                        array('part' => $boss, 'message' => $l['FAMILY_BOSS_CHANGE_CONFIRM'], 'pattern' => '/{username}/'),
                        array('part' => $security->getToken(), 'message' => FALSE, 'pattern' => '/{securityToken}/')
                    );
                    $replacedMessage = $route->replaceMessageParts($replaces);
                }
                elseif(isset($bossConfirmed))
                    $this->data->manageFamilyTop("bossUID", $userID, $familyData->getId());
                elseif(isset($bankmanager))
                    $this->data->manageFamilyTop("bankmanagerUID", $userID, $familyData->getId());
                elseif(isset($underboss))
                    $this->data->manageFamilyTop("underbossUID", $userID, $familyData->getId());
                elseif(isset($forummod))
                    $this->data->manageFamilyTop("forummodUID", $userID, $familyData->getId());
                
                if(!isset($replacedMessage))
                {
                    if($bossConfirmed !== null) $status = strtolower($l['BOSS']);
                    elseif($bankmanager !== null) $status = strtolower($l['BANKMANAGER']);
                    elseif($underboss !== null) $status = strtolower($l['UNDERBOSS']);
                    elseif($forummod !== null) $status = "forum mod";
                    
                    if($username !== '0')
                    {
                        $replaces = array(
                            array('part' => $username, 'message' => $l['FAMILY_TOP_STATUS_SET_TO_USER'], 'pattern' => '/{username}/'),
                            array('part' => $status, 'message' => FALSE, 'pattern' => '/{statusName}/')
                        );
                        $replacedMessage = $route->replaceMessageParts($replaces);
                    }
                    else
                        $replacedMessage = $route->replaceMessagePart($status, $l['FAMILY_TOP_STATUS_SET_EMPTY'], '/{statusName}/');
                    
                    return Routing::successMessage($replacedMessage);
                }
                return Routing::errorMessage($replacedMessage);
            }
        }
    }
    
    public function manageFamilyLeaveCosts($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            global $security;
            global $userData;
            $amount   = (int)$post['leave-costs'];
            
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            $fid = $this->userFamilyID;
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($fid == 0)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if($familyData == FALSE)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if($amount < 0 || $amount > 100000)
            {
                $error = $l['BETWEEN_0_AND_100K'];
            }
            $rqstrUsername = $userData->getUsername();
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $rqstrUsername
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $rqstrUsername
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $this->data->manageFamilyLeaveCosts($fid, round($amount));
                $replacedMessage = $route->replaceMessagePart(number_format(round($amount), 0, '', ','), $l['MANAGE_LEAVE_COSTS_SUCCESS'], '/{costs}/');
                return Routing::successMessage($replacedMessage);
            }
        }
    }
    
    public function sendFamilyMassMessage($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            $ml       = $language->messagesLangs();
            $pl       = $language->possessionsLangs();
            global $security;
            global $userData;
            $message   = $security->xssEscape($post['mass-message']);
            
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            $fid = $this->userFamilyID;
            $possession = new PossessionService();
            $possessionId = 12; // Telecobedrijf | Possession logic
            $possessId = $possession->getPossessIdByPossessionId($possessionId); // Possess table record id
            $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($fid == 0)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if($familyData == FALSE || (is_object($familyData) && $familyData->getVip() == 0))
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            if(strlen($message) < 2 || strlen($message) > 3000)
            {
                $error = $ml['MESSAGE_NOT_IN_RANGE'];
            }
            $rqstrUsername = $userData->getUsername();
            if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $rqstrUsername
                && $this->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $rqstrUsername
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            if(is_object($pData) && $pData->getPossessDetails()->getStake() > $familyData->getMoney())
            {
                $error = $l['NOT_ENOUGH_MONEY_FAMBANK'];
            }
            if(!is_object($pData))
            {
                $error = $pl['UNKNOWN_POSSESSION'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                $this->data->sendFamilyMassMessage($fid, $message, $pData);
                $l['SEND_FAMILY_MASS_MESSAGE_SUCCESS'] .= '<script type="text/javascript">$("textarea[name=mass-message]").val("");</script>';
                return Routing::successMessage($l['SEND_FAMILY_MASS_MESSAGE_SUCCESS']);
            }
        }
    }
    
    public function abolishFamily($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $security;
            global $userData;
            global $language;
            global $langs;
            $l        = $language->familyLangs();
            
            $abolishConfirmed = isset($post['abolish-confirm']) ? $security->xssEscape($post['abolish-confirm']) : null;
            $abolish = isset($post['abolish']) ? $security->xssEscape($post['abolish']) : null;
            
            $familyData = $this->data->getFamilyDataByName($userData->getFamily());
            $fid = $this->userFamilyID;
            
            if($security->checkToken($post['security-token']) ==  FALSE)
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if($familyData == FALSE || !is_object($familyData) || $fid == 0)
            {
                $error = $l['FAMILY_DOESNT_EXIST'];
            }
            $rqstrUsername = $userData->getUsername();
            if(is_object($familyData) && !$userData->GetFamilyBoss() &&
                $this->getFamilyPageDataByName($familyData->getName())->getBoss() != $rqstrUsername
            )
            {
                $error = $l['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                
                if(isset($abolish))
                {
                    $randomMember = $this->data->getRandomFamilyMemberButNotSelfByFamilyID($fid);
                    $replaces = array(
                        array('part' => $randomMember, 'message' => $l['ABOLISH_FAMILY_CONFIRM'], 'pattern' => '/{username}/'),
                        array('part' => $security->getToken(), 'message' => FALSE, 'pattern' => '/{securityToken}/')
                    );
                    $replacedMessage = $route->replaceMessageParts($replaces);
                }
                elseif(isset($abolishConfirmed))
                    $this->data->abolishFamily($fid);
                
                if(!isset($replacedMessage))
                {
                    return Routing::successMessage($l['ABOLISH_FAMILY_SUCCESS']);
                }
                return Routing::errorMessage($replacedMessage);
            }
        }
    }
    
    public function getAlliances($familyID)
    {
        return $this->data->getAlliances($familyID);
    }
    
    public function getFamilyDataByName($familyName)
    {
        return $this->data->getFamilyDataByName($familyName);
    }
    
    public function getFamilyPageDataByName($familyName)
    {
        return $this->data->getFamilyPageDataByName($familyName);
    }
    
    public function getFamilyMembersByFamilyId($id)
    {
        return $this->data->getFamilyMembersByFamilyId($id);
    }
    
    public function getFamilyDonationsByFamilyId($id)
    {
        return $this->data->getFamilyDonationsByFamilyId($id);
    }
    
    public function getFamilyBankLogsByFamilyId($id, $from, $to)
    {
        return $this->data->getFamilyBankLogsByFamilyId($id, $from, $to);
    }
    
    public function getKickList()
    {
        return $this->data->getKickList($this->userFamilyID);
    }
    
    public function getJoinedMembers()
    {
        return $this->data->getJoinedMembers($this->userFamilyID);
    }
    
    public function getInvitedMembers()
    {
        return $this->data->getInvitedMembers($this->userFamilyID);
    }
    
    public function getFamilyMessage()
    {
        return $this->data->getFamilyMessage($this->userFamilyID);
    }
    
    public function getFamilyPageAlliancesById($familyID)
    {
        return $this->data->getFamilyPageAlliancesById($familyID);
    }
    
    public function getImplodedFamilyMemberIds($familyID)
    {
        return $this->data->getImplodedFamilyMemberIds($familyID);
    }
}
