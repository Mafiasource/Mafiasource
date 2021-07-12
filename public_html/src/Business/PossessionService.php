<?PHP
 
namespace src\Business;

use src\Business\UserService;
use src\Business\NotificationService;
use src\Business\Logic\game\Statics\BulletFactory AS BulletFactoryStatics;
use src\Data\PossessionDAO;
use app\config\Routing;

/* Class has some cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */
 
class PossessionService
{
    private $data;
    
    public $familyCountryPossLimit = 1;
    public $familyStatePossLimit = 2;
    public $familyCityPossLimit = 3;
    
    public $bfProductionCosts;
    
    public function __construct()
    {
        $this->data = new PossessionDAO();
        $bulletFactoryStatics = new BulletFactoryStatics();
        $this->bfProductionCosts = $bulletFactoryStatics->productionPrices;
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function interactWithPossession($post) // One function for possession.interact.php ajax controller with various outcomes:
    {
        if(isset($post['buy'])) return $this->buyPossession($post);
        if(isset($post['drop'])) return $this->dropPossession($post);
        if(isset($post['transfer'])) return $this->transferPossessionRequest($post);
        if(isset($post['accept-transfer'])) return $this->acceptTransferedPossession($post);
        if(isset($post['deny-transfer'])) return $this->denyTransferedPossession($post); 
        if(isset($post['change-bullet-price'])) return $this->changeBulletPrice($post); 
        if(isset($post['produce'])) return $this->produceBullets($post);
        if(isset($post['buy-windows'])) return $this->buyWindows($post);
        if(isset($post['change-window-price'])) return $this->changeWindowPrice($post);
        if(isset($post['change-stake'])) return $this->changeStake($post);
    }
    
    public function buyPossession($post)
    {
        global $language;
        global $langs;
        $l        = $language->possessionsLangs();
        global $security;
        global $userService;
        global $userData;
        
        $pData = $this->data->getPossessionByPossessId((int)$post['id']);
        $psData = is_object($pData) ? $pData->getPossessDetails() : FALSE;
        $statusData = $userService->getStatusPageInfo();
        
        if(is_object($psData) && $psData->getCityID() != 0)
            $familyPossAmount = $this->familyCityPossLimit; // 3
        elseif(is_object($psData) && $psData->getStateID() != 0)
            $familyPossAmount = $this->familyStatePossLimit; // 2
        elseif(is_object($psData) && $psData->getStateID() == 0)
            $familyPossAmount = $this->familyCountryPossLimit; // 1
        
        if($_POST['security-token'] != $security->getToken())
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(is_object($pData)&& $pData->getPrice() > $userData->getCash())
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if(is_object($statusData) && $statusData->getIsProtected())
        {
            $error = $l['CANT_BUY_WITH_PROTECTION'];
        }
        if(is_object($pData) && $this->data->userHasPossessionById($pData->getId()))
        {
            $error = $l['ALREADY_OWN_SAME_POSSESSION'];
        }
        if(is_object($pData) && $this->data->familyHasAmountPossessionsById($pData->getId(), $userData->getFamilyID(), $familyPossAmount))
        {
            $error = $l['FAMILY_MAX_POSSESSION'];
        }
        if(is_object($psData) && $psData->getStateID() == 0 && $psData->getCityID() == 0 && $this->data->familyHasAmountCountryPossessions($userData->getFamilyID(), $familyPossAmount))
        {
            $error = $l['FAMILY_MAX_COUNTRY_POSSESSION'];
        }
        if(is_object($psData) && $psData->getStateID() == 0 && $psData->getCityID() == 0 && $this->data->userHasCountryPossession())
        {
            $error = $l['USER_ALREADY_OWN_COUNTRY_POSSESSION'];
        }
        if(is_object($psData) && $psData->getStateID() != 0 && $psData->getStateID() != $userData->getStateID())
        {
            $error = $l['CANT_BUY_FROM_DIFFERENT_STATE'];
        }
        if(is_object($psData) && $psData->getCityID() != 0 && $psData->getCityID() != $userData->getCityID())
        {
            $error = $l['CANT_BUY_FROM_DIFFERENT_CITY'];
        }
        if(is_object($psData) && $psData->getUserID() != 0)
        {
            $error = $l['POSSESSION_HAS_OWNER_ALREADY'];
        }
        if(!is_object($pData) || !is_object($psData) || $pData == FALSE || $psData == FALSE)
        {
            $error = $l['UNKNOWN_POSSESSION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->buyPossessionByPossessId($psData->getId(),$pData->getPrice());
            
            $dezeThis = array("Gevangenis", "Loterij", "Kogelfabriek", "Mafiasource Bank", "Makelaardij", "Garage Makelaardij", "Uitrusting Winkels", "Voertuig Handelszaak", "Dobbel Tafel", "Race Track", "Roulette", "Fruitmachine", "Blackjack");
            $name = strtolower($langs['THIS'])." <b>".$pData->getName();
            if(in_array($pData->getName(), $dezeThis)) $name = strtolower($langs['DEZETHIS'])." <b>".$pData->getName();
            $replacedMessage = $route->replaceMessagePart($name, $l['BOUGHT_POSSESSION_SUCCESS'], '/{pName}/');
            $replacedMessage = $route->replaceMessagePart("<b>".number_format($pData->getPrice(), 0, '', ',')."</b>", $replacedMessage, '/{price}/');
            if($psData->getStateID() == 0)
            {
                $replacedMessage = $route->replaceMessagePart($langs['THE_UNITED_STATES']."</b>", $replacedMessage, '/{location}/');
            }
            elseif($psData->getStateID() != 0 && $psData->getCityID() == 0)
            {
                $replacedMessage = $route->replaceMessagePart($psData->getState()."</b>", $replacedMessage, '/{location}/');
            }
            elseif($psData->getStateID() != 0 && $psData->getCityID() != 0)
            {
                $replacedMessage = $route->replaceMessagePart($psData->getState().", ".$psData->getCity()."</b>", $replacedMessage, '/{location}/');
            }
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function dropPossession($post)
    {
        global $language;
        global $langs;
        $l        = $language->possessionsLangs();
        global $security;
        global $userData;
        $id = (int)$post['id'];
        
        $pData = $this->data->getPossessionByPossessId($id);
        $psData = is_object($pData) ? $pData->getPossessDetails() : FALSE;
        
        if($_POST['security-token'] != $security->getToken())
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if( !is_object($pData) || !is_object($psData) || $pData == FALSE || $psData == FALSE ||
            ((is_object($psData) && $psData->getUserID() != $userData->getId()) || (is_object($psData) && $psData->getId() != $id))
        )
        {
            $error = $l['UNKNOWN_POSSESSION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $replaces = array(
                array('part' => $psData->getId(), 'message' => $l['DROP_POSSESS_CONFIRM'], 'pattern' => '/{id}/'),
                array('part' => $security->getToken(), 'message' => FALSE, 'pattern' => '/{securityToken}/')
                
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::errorMessage($replacedMessage);
        }
    }
    
    public function dropPossessionConfirmed($post)
    {
        global $language;
        global $langs;
        $l        = $language->possessionsLangs();
        global $security;
        global $userData;
        $id = (int)$post['id'];
        
        $pData = $this->data->getPossessionByPossessId($id);
        $psData = is_object($pData) ? $pData->getPossessDetails() : FALSE;
        
        if($_POST['security-token'] != $security->getToken())
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if( !is_object($pData) || !is_object($psData) || $pData == FALSE || $psData == FALSE ||
            ((is_object($psData) && $psData->getUserID() != $userData->getId()) || (is_object($psData) && $psData->getId() != $id))
        )
        {
            $error = $l['UNKNOWN_POSSESSION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->dropPossession($psData->getId());
            
            $replacedMessage = $route->replaceMessagePart($pData->getName(), $l['DROP_POSSESS_SUCCESS'], '/{pName}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function transferPossessionRequest($post)
    {
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l = $language->possessionsLangs();
        $username = $security->xssEscape($post['username']);
        $receiver = $userService->getIdByUsername($username);
        $id = (int)$post['id'];
        
        if(isset($username) && is_numeric($receiver) && $receiver != 0) $receiverProfile = $userService->getUserProfile($username);
        $pData = $this->data->getPossessionByPossessId($id);
        $psData = is_object($pData) ? $pData->getPossessDetails() : FALSE;
        
        if(is_object($psData) && $psData->getCityID() != 0)
            $familyPossAmount = $this->familyCityPossLimit; // 3
        elseif(is_object($psData) && $psData->getStateID() != 0)
            $familyPossAmount = $this->familyStatePossLimit; // 2
        elseif(is_object($psData) && $psData->getStateID() == 0)
            $familyPossAmount = $this->familyCountryPossLimit; // 1
        
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
        if(is_object($pData) && isset($receiverProfile) && $this->data->receiverHasPossessionById($receiverProfile->getId(), $pData->getId()))
        {
            $error = $l['RECEIVER_ALREADY_OWN_SAME_POSSESSION'];
        }
        if(is_object($pData) && isset($receiverProfile) && $this->data->familyHasAmountPossessionsById($pData->getId(), $receiverProfile->getFamilyID(), $familyPossAmount) && $userData->getFamilyID() != $receiverProfile->getFamilyID())
        {
            $error = $l['RECEIVER_FAMILY_MAX_POSSESSION'];
        }
        if(is_object($psData) && isset($receiverProfile) && $psData->getStateID() == 0 && $psData->getCityID() == 0 && $this->data->familyHasAmountCountryPossessions($receiverProfile->getFamilyID(), $familyPossAmount) && $userData->getFamilyID() != $receiverProfile->getFamilyID())
        {
            $error = $l['RECEIVER_FAMILY_MAX_COUNTRY_POSSESSION'];
        }
        if(is_object($psData) && isset($receiverProfile) && $psData->getStateID() == 0 && $psData->getCityID() == 0 && $this->data->receiverHasCountryPossession($receiverProfile->getId()))
        {
            $error = $l['RECEIVER_ALREADY_OWN_COUNTRY_POSSESSION'];
        }
        if(is_object($psData) && isset($receiverProfile) && $this->data->possessTransferedToReceiver($psData->getId(), $receiverProfile->getId()))
        {
            $error = $l['ALREADY_TRANSFERED_TO_RECEIVER'];
        }
        if(!isset($receiverProfile))
        {
            $error = $langs['PLAYER_DOESNT_EXIST'];
        }
        if(isset($receiverProfile) && $receiverProfile->getId() == $userData->getId())
        {
            $error = $langs['CANNOT_COMMIT_ACTION_SELF'];
        }
        if( !is_object($pData) || !is_object($psData) || $pData == FALSE || $psData == FALSE ||
            ((is_object($psData) && $psData->getUserID() != $userData->getId()) || (is_object($psData) && $psData->getId() != $id))
        )
        {
            $error = $l['UNKNOWN_POSSESSION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->transferPossessionRequest($psData->getId(), $receiverProfile->getId());
            
            if($psData->getStateID() == 0)
                $location = $langs['THE_UNITED_STATES'];
            elseif($psData->getStateID() != 0 && $psData->getCityID() == 0)
                $location = $psData->getState();
            elseif($psData->getStateID() != 0 && $psData->getCityID() != 0)
                $location = $psData->getState().", ".$psData->getCity();
            
            // Send notification
            $notification = new NotificationService();
            $params = "id=".$psData->getId()."&possession=".$pData->getName()."&location=".$location."&user=".$userData->getUsername();
            $notification->sendNotification($receiverProfile->getId(), 'POSSESS_TRANSFER_REQUEST', $params);
            
            $replaces = array(
                array('part' => $pData->getName(), 'message' => $l['TRANSFER_POSSESS_REQUEST_SUCCESS'], 'pattern' => '/{pName}/'),
                array('part' => $location, 'message' => FALSE, 'pattern' => '/{location}/'),
                array('part' => $receiverProfile->getUsername(), 'message' => FALSE, 'pattern' => '/{user}/')
                
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function acceptTransferedPossession($post)
    {
        global $route;
        global $language;
        global $langs;
        $l        = $language->possessionsLangs();
        global $security;
        global $userService;
        global $userData;
        
        $pData = $this->data->getPossessionByPossessId((int)$post['id']);
        $psData = is_object($pData) ? $pData->getPossessDetails() : FALSE;
        $statusData = $userService->getStatusPageInfo();
        
        if(is_object($psData) && $psData->getCityID() != 0)
            $familyPossAmount = $this->familyCityPossLimit; // 3
        elseif(is_object($psData) && $psData->getStateID() != 0)
            $familyPossAmount = $this->familyStatePossLimit; // 2
        elseif(is_object($psData) && $psData->getStateID() == 0)
            $familyPossAmount = $this->familyCountryPossLimit; // 1
        
        if(is_object($psData)) $transferedPoss = $this->data->getTransferedPossessionByPossessID($psData->getId());
        if(is_object($transferedPoss)) $senderID = $transferedPoss->getSenderID();
        if(isset($senderID) && is_numeric($senderID)) $sender = $userService->getUsernameById($senderID);
        if(isset($sender) && UserService::is_name($sender)) $senderProfile = $userService->getUserProfile($sender);
        
        if($_POST['security-token'] != $security->getToken())
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($statusData->getIsProtected())
        {
            $error = $l['CANT_RECEIVE_WITH_PROTECTION'];
        }
        if(is_object($pData) && $this->data->userHasPossessionById($pData->getId()))
        {
            $error = $l['ALREADY_OWN_SAME_POSSESSION'];
        }
        if(is_object($pData) && isset($senderProfile) && $this->data->familyHasAmountPossessionsById($pData->getId(), $userData->getFamilyID(), $familyPossAmount) && $userData->getFamilyID() != $senderProfile->getFamilyID())
        {
            $error = $l['FAMILY_MAX_POSSESSION'];
        }
        if(is_object($psData) && isset($senderProfile) && $psData->getStateID() == 0 && $psData->getCityID() == 0 && $this->data->familyHasAmountCountryPossessions($userData->getFamilyID(), $familyPossAmount) && $userData->getFamilyID() != $senderProfile->getFamilyID())
        {
            $error = $l['FAMILY_MAX_COUNTRY_POSSESSION'];
        }
        if(is_object($psData) && $psData->getStateID() == 0 && $psData->getCityID() == 0 && $this->data->userHasCountryPossession())
        {
            $error = $l['USER_ALREADY_OWN_COUNTRY_POSSESSION'];
        }
        if(is_object($psData) && isset($senderID) && is_numeric($senderID) && $psData->getUserID() != $senderID)
        {
            $this->data->removePossessTransferByPossessID($psData->getId());
            $error = $route->replaceMessagePart($sender, $l['SENDER_DOESNT_OWN_POSSESSION'], '/{sender}/');
        }
        if(!is_object($pData) || !is_object($psData) || $pData == FALSE || $psData == FALSE || !is_object($transferedPoss))
        {
            $error = $l['UNKNOWN_POSSESSION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->acceptTransferedPossession($psData->getId(), $senderID);
            
            if($psData->getStateID() == 0)
                $location = $langs['THE_UNITED_STATES'];
            elseif($psData->getStateID() != 0 && $psData->getCityID() == 0)
                $location = $psData->getState();
            elseif($psData->getStateID() != 0 && $psData->getCityID() != 0)
                $location = $psData->getState().", ".$psData->getCity();
            
            // Send notification
            $notification = new NotificationService();
            $params = "possession=".$pData->getName()."&location=".$location."&user=".$userData->getUsername();
            $notification->sendNotification($senderID, 'POSSESS_TRANSFER_ACCEPTED', $params);
            
            $replaces = array(
                array('part' => $pData->getName(), 'message' => $l['ACCEPT_TRANSFER_POSSESS_SUCCESS'], 'pattern' => '/{pName}/'),
                array('part' => $location, 'message' => FALSE, 'pattern' => '/{location}/'),
                array('part' => $sender, 'message' => FALSE, 'pattern' => '/{sender}/')
                
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function denyTransferedPossession($post)
    {
        global $route;
        global $language;
        global $langs;
        $l        = $language->possessionsLangs();
        global $security;
        global $userService;
        global $userData;
        
        $pData = $this->data->getPossessionByPossessId((int)$post['id']);
        $psData = is_object($pData) ? $pData->getPossessDetails() : FALSE;
        
        if(is_object($psData)) $transferedPoss = $this->data->getTransferedPossessionByPossessID($psData->getId());
        if(is_object($transferedPoss)) $senderID = $transferedPoss->getSenderID();
        if(isset($senderID) && is_numeric($senderID)) $sender = $userService->getUsernameById($senderID);
        
        if($_POST['security-token'] != $security->getToken())
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(is_object($psData) && isset($senderID) && is_numeric($senderID) && $psData->getUserID() != $senderID)
        {
            $this->data->removePossessTransferByPossessID($psData->getId());
            $error = $route->replaceMessagePart($sender, $l['SENDER_DOESNT_OWN_POSSESSION'], '/{sender}/');
        }
        if(!is_object($pData) || !is_object($psData) || $pData == FALSE || $psData == FALSE || !is_object($transferedPoss))
        {
            $error = $l['UNKNOWN_POSSESSION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->denyTransferedPossession($psData->getId(), $senderID);
            
            if($psData->getStateID() == 0)
                $location = $langs['THE_UNITED_STATES'];
            elseif($psData->getStateID() != 0 && $psData->getCityID() == 0)
                $location = $psData->getState();
            elseif($psData->getStateID() != 0 && $psData->getCityID() != 0)
                $location = $psData->getState().", ".$psData->getCity();
            
            // Send notification
            $notification = new NotificationService();
            $params = "possession=".$pData->getName()."&location=".$location."&user=".$userData->getUsername();
            $notification->sendNotification($senderID, 'POSSESS_TRANSFER_DENIED', $params);
            
            $replaces = array(
                array('part' => $pData->getName(), 'message' => $l['DENY_TRANSFER_POSSESS_SUCCESS'], 'pattern' => '/{pName}/'),
                array('part' => $location, 'message' => FALSE, 'pattern' => '/{location}/'),
                array('part' => $sender, 'message' => FALSE, 'pattern' => '/{sender}/')
                
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function changeBulletPrice($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->possessionsLangs();
        $id = (int)$post['id'];
        $price = (int)$post['bullet-price'];
        
        $pData = $this->data->getPossessionByPossessId($id);
        $psData = is_object($pData) ? $pData->getPossessDetails() : FALSE;
        $bfData = is_object($pData) ? $pData->getBulletFactoryDetails() : FALSE;
        
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
        if(is_object($pData) && is_object($bfData) && $bfData->getBullets() > 10000000  && $price < 2500)
        {
            $error = $l['PRICE_2500_IF_OVER_10M_BULLETS'];
        }
        if(is_object($pData) && is_object($bfData) && $bfData->getPriceEachBullet() == $price)
        {
            $error = $l['PRICE_ALREADY_SET'];
        }
        if($price < 200 || $price > 2500)
        {
            $error = $l['BETWEEN_200_AND_2500_BULLET_PRICE'];
        }
        if( !is_object($pData) || !is_object($psData) || !is_object($bfData) || $pData == FALSE || $psData == FALSE || $bfData == FALSE ||
            ((is_object($psData) && $psData->getUserID() != $userData->getId()) || (is_object($psData) && $psData->getId() != $id))
        )
        {
            $error = $l['UNKNOWN_POSSESSION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->changeBulletPrice($psData->getId(), $price);
            
            $replacedMessage = $route->replaceMessagePart(number_format($price, 0, '', ','), $l['CHANGE_BULLET_PRICE_SUCCESS'], '/{price}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function produceBullets($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->possessionsLangs();
        $id = (int)$post['id'];
        $production = (int)$post['produce'];
        
        $pData = $this->data->getPossessionByPossessId($id);
        $psData = is_object($pData) ? $pData->getPossessDetails() : FALSE;
        $bfData = is_object($pData) ? $pData->getBulletFactoryDetails() : FALSE;
        
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
        if($production < 0 || $production > 1000000 || $bfData->getProduction() == $production || !array_key_exists($production, $this->bfProductionCosts))
        {
            $error = $l['INVALID_PRODUCTION'];
        }
        if( !is_object($pData) || !is_object($psData) || !is_object($bfData) || $pData == FALSE || $psData == FALSE || $bfData == FALSE ||
            ((is_object($psData) && $psData->getUserID() != $userData->getId()) || (is_object($psData) && $psData->getId() != $id))
        )
        {
            $error = $l['UNKNOWN_POSSESSION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->produceBullets($psData->getId(), $production);
            
            $replacedMessage = $route->replaceMessagePart(number_format($production, 0, '', ','), $l['SET_BF_PRODUCTION_SUCCESS'], '/{production}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function buyWindows($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->possessionsLangs();
        $id = (int)$post['id'];
        
        $pData = $this->data->getPossessionByPossessId($id);
        $psData = is_object($pData) ? $pData->getPossessDetails() : FALSE;
        $rldData = is_object($pData) ? $pData->getRedLightDistrictDetails() : FALSE;
        
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
        if($userData->getBank() < 1000000)
        {
            $error = $langs['NOT_ENOUGH_MONEY_BANK'];
        }
        if( !is_object($pData) || !is_object($psData) || !is_object($rldData) || $pData == FALSE || $psData == FALSE || $rldData == FALSE ||
            ((is_object($psData) && $psData->getUserID() != $userData->getId()) || (is_object($psData) && $psData->getId() != $id))
        )
        {
            $error = $l['UNKNOWN_POSSESSION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->buyWindows($psData->getId());
            
            return Routing::successMessage($l['BUY_WINDOWS_SUCCESS']);
        }
    }
    
    public function changeWindowPrice($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->possessionsLangs();
        $id = (int)$post['id'];
        $price = (int)$post['window-price'];
        
        $pData = $this->data->getPossessionByPossessId($id);
        $psData = is_object($pData) ? $pData->getPossessDetails() : FALSE;
        $rldData = is_object($pData) ? $pData->getRedLightDistrictDetails() : FALSE;
        
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
        if(is_object($pData) && is_object($rldData) && $rldData->getPriceEachWindow() == $price)
        {
            $error = $l['PRICE_ALREADY_SET'];
        }
        if($price < 50 || $price > 300)
        {
            $error = $l['BETWEEN_50_AND_300_WINDOW_PRICE'];
        }
        if( !is_object($pData) || !is_object($psData) || !is_object($rldData) || $pData == FALSE || $psData == FALSE || $rldData == FALSE ||
            ((is_object($psData) && $psData->getUserID() != $userData->getId()) || (is_object($psData) && $psData->getId() != $id))
        )
        {
            $error = $l['UNKNOWN_POSSESSION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->changeWindowPrice($psData->getId(), $price);
            
            $replacedMessage = $route->replaceMessagePart(number_format($price, 0, '', ','), $l['CHANGE_WINDOW_PRICE_SUCCESS'], '/{price}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function changeStake($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->possessionsLangs();
        $id = (int)$post['id'];
        $stake = (int)$post['stake'];
        
        $pData = $this->data->getPossessionByPossessId($id);
        $psData = is_object($pData) ? $pData->getPossessDetails() : FALSE;
        
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
        if(is_object($psData) && $psData->getStake() == $stake)
        {
            $error = $l['PRICE_ALREADY_SET'];
        }
        if($stake < 1000 || $stake > 500000)
        {
            if($pData->getId() == 12)
                $error = $l['BETWEEN_1K_AND_500K_MASS_MESSAGE'];
            else
                $error = $l['BETWEEN_1K_AND_500K_STAKE'];
        }
        if( !is_object($pData) || !is_object($psData) || $pData == FALSE || $psData == FALSE ||
            ((is_object($psData) && $psData->getUserID() != $userData->getId()) || (is_object($psData) && $psData->getId() != $id)) ||
            (is_object($pData) && ($pData->getId() < 12 || $pData->getId() > 17))
        )
        {
            $error = $l['UNKNOWN_POSSESSION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->changeStake($psData->getId(), $stake);
            if($pData->getId() == 12)
                $replacedMessage = $route->replaceMessagePart(number_format($stake, 0, '', ','), $l['CHANGE_MASS_MESSAGE_PRICE_SUCCESS'], '/{price}/');
            else
                $replacedMessage = $route->replaceMessagePart(number_format($stake, 0, '', ','), $l['CHANGE_STAKE_SUCCESS'], '/{stake}/');
            
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function getAllPossessionsByStateAndCity($stateID, $cityID)
    {
        return $this->data->getAllPossessionsByStateAndCity($stateID, $cityID);
    }
    
    public function getPossessIdByPossessionId($id, $stateID = false, $cityID = false)
    {
        return $this->data->getPossessIdByPossessionId($id, $stateID, $cityID);
    }
    
    public function getPossessionByPossessId($id)
    {
        return $this->data->getPossessionByPossessId($id);
    }
    
    public function userHasPossessionById($id)
    {
        return $this->data->userHasPossessionById($id);
    }
    
    public function userHasCountryPossession()
    {
        return $this->data->userHasCountryPossession();
    }
    
    public function familyHasAmountPossessionsById($id, $familyID, $amount = 2)
    {
        return $this->data->familyHasAmountPossessionsById($id, $familyID, $amount);
    }
    
    public function familyHasAmountCountryPossessions($familyID, $amount = 1)
    {
        return $this->data->familyHasAmountCountryPossessions($familyID, $amount);
    }
    
    public function getUserPossessionsManagement()
    {
        return $this->data->getUserPossessionsManagement();
    }
    
    public function getTransferedPossessions()
    {
        return $this->data->getTransferedPossessions();
    }
    
    public function getRLDInfoByPossessID($id)
    {
        return $this->data->getRLDInfoByPossessID($id);
    }
    
    public function getBulletFactoryInfoByPossessID($id)
    {
        return $this->data->getBulletFactoryInfoByPossessID($id);
    }
    
    public function getProfilePossessionsByUserID($userID)
    {
        return $this->data->getProfilePossessionsByUserID($userID);
    }
}
