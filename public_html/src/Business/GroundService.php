<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\StateService;
use src\Business\NotificationService;
use src\Business\DonatorService;
use src\Business\Logic\game\Ground\Coords AS GroundCoordsData; // Class full of coordinates data for each state
use src\Business\Logic\game\Ground\IncomeCalculation AS IncomeCalculation;
use src\Data\GroundDAO;

class GroundService extends GroundCoordsData
{
    private $data;
    
    public $groundCoords;
    public $hometown;
    public $limit = 5;
    
    public function __construct($stateID, $groundID = FALSE)
    {
        $this->data = new GroundDAO();
        if($groundID == FALSE) // Only request big data from parent class if on ground map view
        {
            parent::__construct($stateID);
            $this->groundCoords = $this->groundCoords; // From parant class
            $this->hometown = $this->data->getHometownFamilyByStateID($stateID);
        }
        $donator = new DonatorService();
        $shopData = $donator->getDonationShopData();
        if($shopData['ground'] > 0 && $shopData['ground'] <= 5)
            $this->limit += (int)$shopData['ground'];
    }

    public function __destruct()
    {
        $this->data = null;
    }

    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function getGroundMapDataByStateID($stateID)
    {
        $list = array();
        for($i = 1; $i <= count($this->groundCoords); ($i = $i + 1))
        {
            $groundData = $this->data->getGroundDataByStateIdAndGroundID($stateID, $i);
            $groundData->setCoordsLeftPx($this->groundCoords[$i]['left_px']);
            $groundData->setCoordsTopPx($this->groundCoords[$i]['top_px']);
            
            array_push($list, $groundData);
        }
        return $list;
    }
    
    public function buyGround($post, $groundObj)
    {
        global $route;
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->groundLangs();
        
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
        if(100000 > $userData->getCash())
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if(is_object($groundObj) && $groundObj->getUserID() > 0)
        {
            $error = $l['GROUND_ALREADY_OWNED'];
        }
        if(is_object($groundObj) && $groundObj->getStateID() != $userData->getStateID())
        {
            $replacedMessage = $route->replaceMessagePart($groundObj->getState(), $l['USER_TRAVEL_SAME_STATE_AS_MAP'], '/{state}/');
            $error = $replacedMessage;
        }
        if($this->data->getGroundInPossession() >= $this->limit)
        {
            $replacedMessage = $route->replaceMessagePart($this->limit, $l['ALREADY_OWN_MAX_GROUND'], '/{limit}/');
            $error = $replacedMessage;
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->buyGround($groundObj);
            $replacedMessage = $route->replaceMessagePart($groundObj->getState(), $l['BUY_GROUND_SUCCESS'], '/{state}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function buyGroundBuilding($post, $groundObj)
    {
        global $route;
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->groundLangs();
        
        // Post building is checked in controller | minus 1 here: getBuildings is array of objects
        $buildingObj = $groundObj->getBuildings()[(int)$post['building']-1];
        
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
        if(is_object($buildingObj) && $buildingObj->getPrice() > $userData->getCash())
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if(is_object($groundObj) && $groundObj->getStateID() != $userData->getStateID())
        {
            $replacedMessage = $route->replaceMessagePart($groundObj->getState(), $l['USER_BUY_BUILDING_TRAVEL_SAME_STATE_AS_MAP'], '/{state}/');
            $error = $replacedMessage;
        }
        if(is_object($groundObj) && $groundObj->getUserID() != $userData->getId())
        {
            $error = $l['DONT_OWN_THIS_GROUND'];
        }
        if(is_object($groundObj) && $this->data->checkBuildingPossessionOnGround($groundObj, $buildingObj))
        {
            $error = $l['ALREADY_OWN_THIS_BUILDING'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->buyGroundBuilding($groundObj, $buildingObj);
            $replaces = array(
                array('part' => $buildingObj->getName(), 'message' => $l['BUY_GROUND_BUILDING_SUCCESS'], 'pattern' => '/{building}/'),
                array('part' => $groundObj->getState(), 'message' => FALSE, 'pattern' => '/{state}/'),
                array('part' => number_format($buildingObj->getPrice(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function upgradeGroundBuilding($post, $groundObj)
    {
        global $route;
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->groundLangs();
        
        // Post building is checked in controller | minus 1 here: getBuildings is array of objects
        $buildingObj = $groundObj->getBuildings()[(int)$post['building']-1];
        switch($post['building'])
        {
            default: case 1:
                $waitingTime = $groundObj->getCBuilding1();
                break;
            case 2:
                $waitingTime = $groundObj->getCBuilding2();
                break;
            case 3:
                $waitingTime = $groundObj->getCBuilding3();
                break;
            case 4:
                $waitingTime = $groundObj->getCBuilding4();
                break;
            case 5:
                $waitingTime = $groundObj->getCBuilding5();
                break;
        }
        if(is_object($buildingObj))
            $price = round(($buildingObj->getPrice() * ($buildingObj->getLevel()+1)) * 0.75);
        
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
        if(is_object($buildingObj) && $price > $userData->getCash())
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if(is_object($groundObj) && $groundObj->getStateID() != $userData->getStateID())
        {
            $replacedMessage = $route->replaceMessagePart($groundObj->getState(), $l['USER_UPGRADE_BUILDING_TRAVEL_SAME_STATE_AS_MAP'], '/{state}/');
            $error = $replacedMessage;
        }
        if(is_object($groundObj) && $groundObj->getUserID() != $userData->getId())
        {
            $error = $l['DONT_OWN_THIS_GROUND'];
        }
        if($waitingTime >= time())
        {
            $error = $langs['WAITING_TIME_NOT_PASSED'];
        }
        if(is_object($groundObj) && $this->data->checkBuildingPossessionOnGround($groundObj, $buildingObj) && $buildingObj->getLevel() == 5)
        {
            $error = $l['ALREADY_UPGRADED_THIS_BUILDING'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->upgradeGroundBuilding($groundObj, $buildingObj);
            $replaces = array(
                array('part' => $buildingObj->getName(), 'message' => $l['UPGRADE_GROUND_BUILDING_SUCCESS'], 'pattern' => '/{building}/'),
                array('part' => $groundObj->getState(), 'message' => FALSE, 'pattern' => '/{state}/'),
                array('part' => number_format($price, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function bombGround($post, $groundObj)
    {
        global $route;
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l = $language->groundLangs();
        $bombs = (int)$post['bombs'];
        $price = $bombs*10000;
        $airplane = $userService->getStatusPageInfo()->getAirplane();
        
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
        if($airplane == "Geen" || $airplane == "None")
        {
            $error = $l['DONT_OWN_AIRPLANE'];
        }
        if($userData->getCBombardement() > time())
        {
            $error = $langs['WAITING_TIME_NOT_PASSED'];
        }
        if($bombs < 1 || $bombs > 35)
        {
            $error = $l['BOMBS_BETWEEN_1_AND_35'];
        }
        if($price > $userData->getCash())
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if(is_object($groundObj) && $groundObj->getUserID() == $userData->getId())
        {
            $error = $l['CANNOT_BOMB_OWN_GROUND'];
        }
        if(is_object($groundObj) && $groundObj->getStateID() != $userData->getStateID())
        {
            $replacedMessage = $route->replaceMessagePart($groundObj->getState(), $l['USER_BOOMB_TRAVEL_SAME_STATE_AS_MAP'], '/{state}/');
            $error = $replacedMessage;
        }
        if($this->data->getGroundInPossession() >= $this->limit)
        {
            $replacedMessage = $route->replaceMessagePart($this->limit, $l['ALREADY_OWN_MAX_GROUND'], '/{limit}/');
            $error = $replacedMessage;
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $bmbrChance = $security->randInt(1,$bombs);
            $bombSuccess = $security->randInt(1, 70);
            if($bmbrChance >= $bombSuccess)
            {
                $this->data->bombGround($groundObj, $price, TRUE);
                
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername()."&state=".$groundObj->getState();
                $notification->sendNotification($groundObj->getUserID(), 'GROUND_BOMBARDEMENT_SUCCESS', $params);
                
                $replaces = array(
                    array('part' => $groundObj->getState(), 'message' => $l['BOMB_GROUND_SUCCESS'], 'pattern' => '/{state}/'),
                    array('part' => number_format($price, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
                
                return Routing::successMessage($replacedMessage);
            }
            else
            {
                $this->data->bombGround($groundObj, $price);
                
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername()."&state=".$groundObj->getState();
                $notification->sendNotification($groundObj->getUserID(), 'GROUND_BOMBARDEMENT_FAILED', $params);
                
                $replaces = array(
                    array('part' => $groundObj->getState(), 'message' => $l['BOMB_GROUND_FAILED'], 'pattern' => '/{state}/'),
                    array('part' => number_format($price, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
                
                return Routing::errorMessage($replacedMessage);
            }
        }
    }
    
    public static function getIncomeByLevel($baseIncome, $level)
    {
        return IncomeCalculation::getIncomeByLevel($baseIncome, $level);
    }
    
    public function getHometownFamily()
    {
        return $this->hometown;
    }
    
    public function getGroundDataByStateIdAndGroundID($stateID, $groundID)
    {
        return $this->data->getGroundDataByStateIdAndGroundID($stateID, $groundID, TRUE);
    }
}
