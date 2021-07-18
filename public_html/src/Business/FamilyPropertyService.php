<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\FamilyService;
use src\Business\Logic\game\Statics\FamilyProperty AS FamilyPropertyStatics;
use src\Business\RedLightDistrictService;
use src\Data\FamilyPropertyDAO;
 
class FamilyPropertyService extends FamilyPropertyStatics
{
    private $data;
    
    public function __construct()
    {
        parent::__construct();
        $this->data = new FamilyPropertyDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function getBulletFactoryProductionsByLevel($lvl)
    {
        switch($lvl)
        {
            case 15:
                return $this->bfProductions;
                break;
            case 14: case 13: case 12:
                return array_slice($this->bfProductions, 0, 7, TRUE);
                break;
            case 11: case 10:
                return array_slice($this->bfProductions, 0, 6, TRUE);
                break;
            case 9: case 8:
                return array_slice($this->bfProductions, 0, 5, TRUE);
                break;
            case 7: case 6:
                return array_slice($this->bfProductions, 0, 4, TRUE);
                break;
            case 5: case 4:
                return array_slice($this->bfProductions, 0, 3, TRUE);
                break;
            case 3: case 2: case 1:
                return array_slice($this->bfProductions, 0, 2, TRUE);
                break;
            case 0: default:
                return array_slice($this->bfProductions, 0, 1, TRUE);
                break;
        }
    }
    
    public function buyFamilyProperty($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyPropertiesLangs();
        $fl = $language->familyLangs();
        
        switch($post['property'])
        {
            default:
            case 'bullet-factory':
                $name = $l['BULLET_FACTORY'];
                break;
            case 'brothel':
                $name = $l['BROTHEL'];
                break;
        }
        
        $family = new FamilyService();
        $familyData = $family->getFamilyDataByName($userData->getFamily());
        
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
        if($familyData->getMoney() < $this->price)
        {
            $error = $fl['NOT_ENOUGH_MONEY_FAMBANK'];
        }
        $rqstrUsername = $userData->getUsername();
        if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyBankmanager() &&
            $family->getFamilyPageDataByName($familyData->getName())->getBoss() != $rqstrUsername
            && $family->getFamilyPageDataByName($familyData->getName())->getBankmanager() != $rqstrUsername
        )
        {
            $error = $l['NO_RIGHTS_FAMILY_PROPERTY'];
        }
        if(!isset($post['property']) && (isset($post['property']) && ($post['property'] != 'bullet-factory' || $post['property'] != 'brothel')))
        {
            $error = $l['INVALID_PROPERTY'];
        }
        if( (isset($post['property']) && $post['property'] == 'bullet-factory' && $this->getFamilyBulletFactoryPageInfo()['bf']->getBulletFactory() != 0) ||
            (isset($post['property']) && $post['property'] == 'brothel' && $this->getFamilyBrothelPageInfo()['brothel']->getBrothel() != 0)
        )
        {
            $error = $l['ALREADY_OWN_PROPERTY'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->buyFamilyProperty($post['property'], $this->price);
            
            $replacedMessage = $route->replaceMessagePart(strtolower($name), $l['BOUGHT_PROPERTY_SUCCESS'], '/{property}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function upgradeFamilyProperty($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyPropertiesLangs();
        $fl = $language->familyLangs();
        
        switch($post['property'])
        {
            default:
            case 'bullet-factory':
                $name = $l['BULLET_FACTORY'];
                $bfPI = $this->getFamilyBulletFactoryPageInfo();
                $property = $bfPI['bf']->getBulletFactory();
                $waitingTime = $bfPI['bf']->getCBulletFactory();
                $costs = $this->upgradePrices[$property];
                break;
            case 'brothel':
                $name = $l['BROTHEL'];
                $brothelPI = $this->getFamilyBrothelPageInfo();
                $property = $brothelPI['brothel']->getBrothel();
                $waitingTime = $brothelPI['brothel']->getCBrothel();
                $costs = $this->upgradePrices[$property];
                break;
        }
        
        $family = new FamilyService();
        $familyData = $family->getFamilyDataByName($userData->getFamily());
        
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
        if($familyData->getMoney() < $costs)
        {
            $error = $fl['NOT_ENOUGH_MONEY_FAMBANK'];
        }
        $rqstrUsername = $userData->getUsername();
        if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyBankmanager() &&
            $family->getFamilyPageDataByName($familyData->getName())->getBoss() != $rqstrUsername
            && $family->getFamilyPageDataByName($familyData->getName())->getBankmanager() != $rqstrUsername
        )
        {
            $error = $l['NO_RIGHTS_FAMILY_PROPERTY'];
        }
        if($waitingTime > time())
        {
            $error = $langs['WAITING_TIME_NOT_PASSED'];
        }
        if(!isset($post['property']) && (isset($post['property']) && ($post['property'] != 'bullet-factory' || $post['property'] != 'brothel')))
        {
            $error = $l['INVALID_PROPERTY'];
        }
        if($property == 0 || $property == 15)
        {
            $error = $l['CANNOT_UPGRADE_PROPERTY'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->upgradeFamilyProperty($post['property'], $this->upgradePrices[$property]);
            
            $replaces = array(
                array('part' => strtolower($name), 'message' => $l['UPGRADE_PROPERTY_SUCCESS'], 'pattern' => '/{property}/'),
                array('part' => number_format($this->upgradePrices[$property], 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function produceBullets($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyPropertiesLangs();
        $fl = $language->familyLangs();
        $production = (int)$post['produce'];
        
        $family = new FamilyService();
        $familyData = $family->getFamilyDataByName($userData->getFamily());
        $bf = $this->getFamilyBulletFactoryPageInfo()['bf'];
        
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
        $rqstrUsername = $userData->getUsername();
        if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyBankmanager() &&
            $family->getFamilyPageDataByName($familyData->getName())->getBoss() != $rqstrUsername
            && $family->getFamilyPageDataByName($familyData->getName())->getBankmanager() != $rqstrUsername
        )
        {
            $error = $l['NO_RIGHTS_FAMILY_PROPERTY'];
        }
        if($bf->getBulletFactory() == 0)
        {
            $error = $l['INVALID_PROPERTY'];
        }
        if($production < 0 || $production > 7 || $bf->getBfProduction() == $production || !array_key_exists($production, $this->getBulletFactoryProductionsByLevel($bf->getBulletFactory())))
        {
            $error = $l['INVALID_PRODUCTION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->setBfProduction($this->bfProductions[$production]);
            
            $replacedMessage = $route->replaceMessagePart(number_format($this->bfProductions[$production], 0, '', ','), $l['SET_BF_PRODUCTION_SUCCESS'], '/{production}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function donateBulletsToFamily($post)
    {
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyPropertiesLangs();
        $fl        = $language->familyLangs();
        $bullets   = (int)$post['bullets'];
        $statusData = $userService->getStatusPageInfo();
        $bf = $this->getFamilyBulletFactoryPageInfo()['bf'];
        $capacity = $this->bfCapacities[$bf->getBulletFactory()];
        
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
        if($userData->getFamilyID() == 0 || $userData->getFamilyID() == false)
        {
            $error = $fl['FAMILY_DOESNT_EXIST'];
        }
        if($bullets < 1 || $bullets > 999999)
        {
            $error = $l['BETWEEN_1_AND_999K_BULLETS'];
        }
        if($statusData->getBullets() < $bullets)
        {
            $error = $l['USER_NOT_ENOUGH_BULLETS'];
        }
        if($capacity < ($bullets + $bf->getBullets()))
        {
            $error = $l['NOT_ENOUGH_SPACE_BF'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->donateBulletsToFamily($bullets);
            
            $successMessage = $route->replaceMessagePart(number_format($bullets, 0, '', ','), $l['DONATE_BULLETS_SUCCESS'], '/{bullets}/');
            return Routing::successMessage($successMessage);
        }
    }
    
    public function sendBulletsToUser($post)
    {
        global $security;
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyPropertiesLangs();
        $fl        = $language->familyLangs();
        $amount   = (int)$post['bullets'];
        $username = $security->xssEscape($post['user']);
        $receiver = $userService->getIdByUsername($username);
        $bf = $this->getFamilyBulletFactoryPageInfo()['bf'];
        
        if(isset($username) && is_numeric($receiver) && $receiver != 0) $receiverProfile = $userService->getUserProfile($username);
        $family = new FamilyService();
        $familyData = $family->getFamilyDataByName($userData->getFamily());
        
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
        if($userData->getFamilyID() == 0 || $userData->getFamilyID() == false)
        {
            $error = $fl['FAMILY_DOESNT_EXIST'];
        }
        if(!isset($receiverProfile) || $receiverProfile->getFamilyID() != $userData->getFamilyID())
        {
            $error = $fl['USER_NOT_INSIDE_FAMILY'];
        }
        if(is_object($familyData) && $amount > $bf->getBullets())
        {
            $error = $l['NOT_ENOUGH_FAMILY_BULLETS'];
        }
        if($amount < 100 || $amount > 100000)
        {
            $error = $l['BETWEEN_100_AND_100K_BULLETS'];
        }
        $rqstrUsername = $userData->getUsername();
        if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyBankmanager() &&
            $family->getFamilyPageDataByName($familyData->getName())->getBoss() != $rqstrUsername
            && $family->getFamilyPageDataByName($familyData->getName())->getBankmanager() != $rqstrUsername
        )
        {
            $error = $l['NO_RIGHTS_FAMILY_PROPERTY'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->sendBulletsToUser($receiver, $amount);
            
            $replaces = array(
                array('part' => number_format($amount, 0, '', ','), 'message' => $l['SEND_FAMILY_BULLETS_SUCCESS'], 'pattern' => '/{bullets}/'),
                array('part' => $username, 'message' => FALSE, 'pattern' => '/{user}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function addWhoresToFamilyBrothel($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyPropertiesLangs();
        $fl = $language->familyLangs();
        $rldl        = $language->redLightDistrictLangs();
        $whores = (int)$post['whores'];
        
        $rld = new RedLightDistrictService();
        $brothel = $this->getFamilyBrothelPageInfo();
        $capacity = $this->brothelCapacities[$brothel['brothel']->getBrothel()];
        
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
        if($whores < 1 || $whores > 99999)
        {
            $error = $l['BETWEEN_1_AND_99K_WHORES'];
        }
        if($rld->getRedLightDistrictPageInfo()->getWhoresStreet() < $whores)
        {
            $error = $rldl['NOT_ENOUGH_STREET_WHORES'];
        }
        if($capacity < ($whores + $brothel['whores']->getTotal()))
        {
            $error = $l['NOT_ENOUGH_SPACE_BROTHEL'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->addWhoresToFamilyBrothel($whores);
            
            $replacedMessage = $route->replaceMessagePart($whores, $l['ADD_WHORES_SUCCESS'], '/{whores}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function takeAwayWhoresFromFamilyBrothel($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyPropertiesLangs();
        $fl = $language->familyLangs();
        $rldl            = $language->redLightDistrictLangs();
        $whores = (int)$post['whores'];
        
        $family = new FamilyService();
        $familyData = $family->getFamilyDataByName($userData->getFamily());
        $brothel = $this->getFamilyBrothelPageInfo();
        
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
        if($whores < 1 || $whores > 99999)
        {
            $error = $l['BETWEEN_1_AND_99K_WHORES'];
        }
        if(is_object($familyData) && $whores > $brothel['whores']->getWhores())
        {
            $error = $rldl['NOT_THAT_MUCH_WHORES_WINDOW'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->takeAwayWhoresFromFamilyBrothel($whores);
            
            $replacedMessage = $route->replaceMessagePart($whores, $l['TAKE_AWAY_WHORES_SUCCESS'], '/{whores}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function getFamilyBulletFactoryPageInfo($from = 0, $to = 15, $hasRights = false)
    {
        return $this->data->getFamilyBulletFactoryPageInfo($from, $to, $hasRights);
    }
    
    public function getFamilyBrothelPageInfo()
    {
        return $this->data->getFamilyBrothelPageInfo();
    }
}
