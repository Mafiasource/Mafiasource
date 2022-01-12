<?PHP

declare(strict_types=1);

namespace src\Business;

use src\Data\FamilyMercenaryDAO;
use app\config\Routing;
 
class FamilyMercenaryService
{
    public int $price = 1000000;
    
    public function __construct()
    {
        $this->data = new FamilyMercenaryDAO();
        if(strtotime("2022-01-07 00:00:00") < strtotime('now') && strtotime("2022-01-07 23:59:59") > strtotime('now'))
            $this->price /= 2;
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount(): int
    {
        return $this->data->getRecordsCount();
    }
    
    public function buyMercenaries(array $post): array
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyMercenariesLangs();
        $fl = $language->familyLangs();
        $mercenaries = (int)$post['mercenaries'];
        
        $family = new FamilyService();
        $familyData = $family->getFamilyDataByName($userData->getFamily());
        
        $priceTotal = $mercenaries * $this->price;
        
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
        if($mercenaries < 2 || $mercenaries > 999)
        {
            $error = $l['BUY_BETWEEN_2_AND_999_MERCENARIES'];
        }
        if($familyData->getMoney() < $priceTotal)
        {
            $error = $fl['NOT_ENOUGH_MONEY_FAMBANK'];
        }
        $rqstrUsername = $userData->getUsername();
        if(is_object($familyData) && !$userData->GetFamilyBoss() && !$userData->getFamilyUnderboss() &&
            $family->getFamilyPageDataByName($familyData->getName())->getBoss() != $rqstrUsername
            && $family->getFamilyPageDataByName($familyData->getName())->getUnderboss() != $rqstrUsername
        )
        {
            $error = $fl['NO_RIGHTS_FAMILY_BANK'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->buyMercenaries($mercenaries, $this->price);
            
            $replaces = array(
                array('part' => $mercenaries, 'message' => $l['BOUGHT_MERCENARIES_SUCCESS'], 'pattern' => '/{mercenaries}/'),
                array('part' => number_format($priceTotal, 0, "", ","), 'message' => FALSE, 'pattern' => '/{price}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function getMercenaries(): object
    {
        return $this->data->getMercenaries();
    }
    
    public function getPageInfo(int $from, int $to): array
    {
        return $this->data->getPageInfo($from, $to);
    }
}
