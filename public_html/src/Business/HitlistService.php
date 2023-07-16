<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\NotificationService;
use src\Data\HitlistDAO;

class HitlistService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new HitlistDAO();
    }

    public function __destruct()
    {
        $this->data = null;
    }

    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function orderHitlistRecord($post)
    {
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l        = $language->hitlistLangs();
        $reason = $security->xssEscape($post['reason']);
        $prize = (int)round((int)$post['prize']);
        $anonymous = false;
        if(isset($_POST['anonymous'])) $anonymous = true;
        $price = $anonymous ? $prize *= 1.3 : $prize;
        
        $userProfile = $userService->getUserProfile($post['username']);
        if(is_object($userProfile)) $userID = $userProfile->getId() ? $userProfile->getId() : FALSE;
        if(isset($userID)) $check = $this->data->isUserOnHitlist($userID);
        
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
        if($userData->getCash() < $price)
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if(strlen($reason) > 75)
        {
            $error = $langs['MESSAGE_UNDER_75_CHARS'];
        }
        if($prize < 10000)
        {
            $error = $l['PRIZE_ATLEAST_10K'];
        }
        if(isset($check) && $check == true)
        {
            $error = $l['PLAYER_ALREADY_ON_HITLIST'];
        }
        if(is_object($userProfile) && $userProfile->getHealth() <= 0)
        {
            $error = $l['PLAYER_ALREADY_DEAD'];
        }
        if((isset($userID) && is_bool($userID) && $userID == FALSE ) || !is_object($userProfile))
        {
            $error = $langs['PLAYER_DOESNT_EXIST'];
        }
        if(isset($userID) && !is_bool($userID) && $_SESSION['UID'] == $userID)
        {
            $error = $l['CANNOT_ORDER_SELF_HITLIST'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->orderHitlistRecord($userID, $reason, $prize, $anonymous);
            
            $replaces = array(
                array('part' => $userProfile->getUsername(), 'message' => $l['ORDER_HITLIST_RECORD_SUCCESS'], 'pattern' => '/{user}/'),
                array('part' => number_format($price, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
            );
            
            $replacedMessage = $route->replaceMessageParts($replaces);
            
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function buyOutRecord($post)
    {
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l        = $language->hitlistLangs();
        
        $userProfile = $userService->getUserProfile($post['username']);
        if(is_object($userProfile)) $userID = $userProfile->getId() ? $userProfile->getId() : FALSE;
        if(isset($userID)) $check = $this->data->isUserOnHitlist($userID);
        if($check == true) $hitlistData = $this->data->getHitlistDataByUserID($userID);
        
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
        if(isset($hitlistData) && is_object($hitlistData) && $userData->getCash() < $hitlistData->getPrize())
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if(!isset($hitlistData) || !is_object($hitlistData))
        {
            $error = $l['PLAYER_NOT_ON_HITLIST'];
        }
        if(is_object($userProfile) && $userProfile->getHealth() <= 0)
        {
            $error = $l['PLAYER_ALREADY_DEAD'];
        }
        if((isset($userID) && is_bool($userID) && $userID == FALSE ) || !is_object($userProfile))
        {
            $error = $langs['PLAYER_DOESNT_EXIST'];
        }
        if(isset($userID) && !is_bool($userID) && $_SESSION['UID'] == $userID)
        {
            $error = $l['CANNOT_BUY_OUT_SELF_HITLIST'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->buyOutHitlistRecord($userID, $hitlistData->getPrize());
                    
            $replaces = array(
                array('part' => $userProfile->getUsername(), 'message' => $l['BUY_OUT_HITLIST_RECORD_SUCCESS'], 'pattern' => '/{user}/'),
                array('part' => number_format($hitlistData->getPrize(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
            );
            
            $replacedMessage = $route->replaceMessageParts($replaces);
            
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function getHitlist($from, $to)
    {
        return $this->data->getHitlist($from, $to);
    }
    
    public function isUserOnHitlist($userID)
    {
        return $this->data->isUserOnHitlist($userID);
    }
    
    public function getHitlistDataByUserID($userID)
    {
        return $this->data->getHitlistDataByUserID($userID);
    }
}
