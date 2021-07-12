<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\NotificationService;
use src\Business\RedLightDistrictService;
use src\Data\FiftyGameDAO;
 
class FiftyGameService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new FiftyGameDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function interactGame($post)
    {
        global $security;
        global $userData;
        global $language;
        global $route;
        global $langs;
        $l        = $language->fiftyGamesLangs();
        $gameID = (int)$post['gameID'];
        
        $gameData = $this->data->getFiftyGameById($gameID);
        
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
        if(is_object($gameData))
        {
            $typeName = $this->data->types[$gameData->getType()];
            switch($gameData->getType())
            {
                default:
                case 0:
                    $hasEnough = $userData->getCash() < $gameData->getAmount() ? false : true;
                    break;
                case 1:
                    $rld = new RedLightDistrictService();
                    $hasEnough = $rld->getRedLightDistrictPageInfo()->getWhoresStreet() < $gameData->getAmount() ? false : true;
                    break;
                case 2:
                    $hasEnough = $userData->getHonorPoints() < $gameData->getAmount() ? false : true;
                    break;
            }
            if($hasEnough === false)
                $error = $replacedMessage = $route->replaceMessagePart(strtolower($typeName), $l['NOT_ENOUGH_AMOUNT_TYPE'], '/{type}/');
            
            if($gameData->getUserID() == $userData->getId())
                $error = $l['CANNOT_PLAY_OWN_GAME'];
        }
        else
            $error = $l['INVALID_GAME'];
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            // Play the game..
            $play = $security->randInt(1, 100);
            if($play > 49) // Challenger lost
            {
                $winnerID = $gameData->getUserID();
                $loserID = $userData->getId();
                $msg = $gameData->getType() == 0 ? $l['FIFTY_GAME_LOST_CASH'] : $l['FIFTY_GAME_LOST'];
                $replaces = array(
                    array('part' => strtolower($typeName), 'message' => $msg, 'pattern' => '/{type}/'),
                    array('part' => number_format($gameData->getAmount(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{amount}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
                
                // Game starter win notification
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername()."&stake=".number_format($gameData->getAmount(), 0, '', ',')."&type=".strtolower($typeName)."&user=".$userData->getUsername();
                if($gameData->getType() == 0)
                    $notification->sendNotification($gameData->getUserID(), 'FIFTY_GAME_CHALLENGE_WIN_CASH', $params);
                else
                    $notification->sendNotification($gameData->getUserID(), 'FIFTY_GAME_CHALLENGE_WIN', $params);
            }
            else // Challenger won
            {
                $winnerID = $userData->getId();
                $loserID = $gameData->getUserID();
                $msg = $gameData->getType() == 0 ? $l['FIFTY_GAME_WON_CASH'] : $l['FIFTY_GAME_WON'];
                $replaces = array(
                    array('part' => strtolower($typeName), 'message' => $msg, 'pattern' => '/{type}/'),
                    array('part' => number_format($gameData->getAmount(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{amount}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
                
                // Game starter lose notification
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername()."&stake=".number_format($gameData->getAmount(), 0, '', ',')."&type=".strtolower($typeName)."&user=".$userData->getUsername();
                if($gameData->getType() == 0)
                    $notification->sendNotification($gameData->getUserID(), 'FIFTY_GAME_CHALLENGE_LOSE_CASH', $params);
                else
                    $notification->sendNotification($gameData->getUserID(), 'FIFTY_GAME_CHALLENGE_LOSE', $params);
            }
            $this->data->finishPlayedFiftyGame($gameData, $winnerID, $loserID);
            if($loserID == $userData->getId())
                return Routing::errorMessage($replacedMessage);
            else
                return Routing::successMessage($replacedMessage);
        }
    }
    
    public function createGame($post)
    {
        global $security;
        global $userData;
        global $language;
        global $route;
        global $langs;
        $l        = $language->fiftyGamesLangs();
        
        $type = (int)$post['type'];
        $amount = (int)$post['amount'];
        $check = $this->data->userHasStartedGameOfType($type);
        
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
        if($type >= 0 && $type < 3) // Cash, whores, honor points.
        {
            $typeName = $this->data->types[$type];
            switch($type)
            {
                default:
                case 0:
                    $hasEnough = $userData->getCash() < $amount ? false : true;
                    if($amount < 100000 || $amount > 100000000)
                        $error = $l['STAKE_BETWEEN_100K_AND_100M'];
                    break;
                case 1:
                    $rld = new RedLightDistrictService();
                    $hasEnough = $rld->getRedLightDistrictPageInfo()->getWhoresStreet() < $amount ? false : true;
                    if($amount < 100 || $amount > 10000)
                        $error = $l['STAKE_BETWEEN_100_AND_10K'];
                    break;
                case 2:
                    $hasEnough = $userData->getHonorPoints() < $amount ? false : true;
                    if($amount < 10 || $amount > 1000)
                        $error = $l['STAKE_BETWEEN_10_AND_1K'];
                    break;
            }
            if($hasEnough === false)
                $error = $replacedMessage = $route->replaceMessagePart(strtolower($typeName), $l['NOT_ENOUGH_AMOUNT_TYPE'], '/{type}/');
            
            if($check === true)
                $error = $l['ALREADY_STARTED_GAME'];
        }
        else
            $error = $l['INVALID_TYPE'];
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->createGame($type, $amount);
            $msg = $type == 0 ? $l['CREATE_GAME_SUCCESS_CASH'] : $l['CREATE_GAME_SUCCESS'];
            $replaces = array(
                array('part' => strtolower($typeName), 'message' => $msg, 'pattern' => '/{type}/'),
                array('part' => number_format($amount, 0, '', ','), 'message' => FALSE, 'pattern' => '/{amount}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function getFiftyGamesByType($typeID)
    {
        return $this->data->getFiftyGamesByType($typeID);
    }
    
    public function getFiftyGameById($id)
    {
        return $this->data->getFiftyGameById($id);
    }
}
