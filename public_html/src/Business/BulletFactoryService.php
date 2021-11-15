<?PHP
 
namespace src\Business;

use app\config\Routing;
use src\Business\PossessionService;
use src\Data\BulletFactoryDAO;
 
class BulletFactoryService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new BulletFactoryDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function getBulletFactories()
    {
        return $this->data->getBulletFactories();
    }
    
    public function buyBullets($post)
    {
        global $route;
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->bulletFactoriesLangs();
        
        $bullets = (int)$post['bullets'];
        $possession = new PossessionService();
        $possessionId = 1; // Bullet factory | Possession logic
        $possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID());
        $bfInfo = $possession->getBulletFactoryInfoByPossessID($possessId);
        $price = $bullets * $bfInfo->getPriceEachBullet();
        
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
        if($price > $userData->getCash())
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if($bullets > $bfInfo->getBullets())
        {
            $error = $l['NOT_THAT_MANY_BULLETS_IN_FACTORY'];
        }
        if($bullets < 1 || $bullets > 9999999)
        {
            $error = $l['BETWEEN_1_AND_9M_BULLETS'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->buyBullets($bfInfo, $bullets);
            $replaces = array(
                array('part' => number_format($bullets, 0, '', ','), 'message' => $l['BOUGHT_BULLETS_SUCCESS'], 'pattern' => '/{bullets}/'),
                array('part' => number_format($price, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/'),
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
}
