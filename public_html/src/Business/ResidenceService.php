<?PHP
 
namespace src\Business;

use app\config\Routing;
use src\Business\PossessionService;
use src\Data\ResidenceDAO;
 
class ResidenceService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new ResidenceDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function buyResidence($post)
    {
        global $userData;
        global $language;
        global $langs;
        $l = $language->estateAgencyLangs();
        global $route;
        global $security;
        
        $id = (int)$post['id'];
        $residencePage = $this->data->getResidencePage();
        
        $residence = array_key_exists($id-1, $residencePage) ? $residencePage[$id-1] : null;
        
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
        if(!isset($residence) || !isset($id) || $id == 0)
        {
            $error = $l['RESIDENCE_DOESNT_EXIST'];
        }
        if(isset($residence))
        {
            if(is_object($residence) && $residence->getPrice() > $userData->getCash())
            {
                $error = $langs['NOT_ENOUGH_MONEY_CASH'];
            }
            if(is_object($residence) && $residence->getInPossession() == true)
            {
                $error = $l['ALREADY_OWN_RESIDENCE'];
            }
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $possession = new PossessionService();
            $possessionId = 6; //Makelaardij | Possession logic
            $possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID()); // Possess table record id |Staat bezitting
            $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
            
            $this->data->buyResidence($id, $pData);
            $replaces = array(
                array('part' => $residence->getName(), 'message' => $l['BOUGHT_RESIDENCE_SUCCESS'], 'pattern' => '/{name}/'),
                array('part' => number_format($residence->getPrice(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function sellResidence($post)
    {
        global $userData;
        global $language;
        global $langs;
        $l = $language->estateAgencyLangs();
        global $route;
        global $security;
        
        $id = (int)$post['id'];
        $residencePage = $this->data->getResidencePage();
        if(array_key_exists($id-1, $residencePage))
        {
            $residence = $residencePage[$id-1];
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
        if(!isset($residence) || $id == 0)
        {
            $error = $l['RESIDENCE_DOESNT_EXIST'];
        }
        if(isset($residence) && is_object($residence) && $residence->getInPossession() == false)
        {
            $error = $l['DONT_OWN_RESIDENCE'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->sellResidence($id);
            $replaces = array(
                array('part' => $residence->getName(), 'message' => $l['SOLD_RESIDENCE_SUCCESS'], 'pattern' => '/{name}/'),
                array('part' => number_format(($residence->getPrice()*0.6), 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function equipResidence($post)
    {
        global $userData;
        global $language;
        global $langs;
        $l = $language->estateAgencyLangs();
        global $route;
        global $security;
        
        $id = (int)$post['id'];
        $residencePage = $this->data->getResidencePage();
        if(array_key_exists($id-1, $residencePage))
        {
            $residence = $residencePage[$id-1];
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
        if(!isset($residence))
        {
            $error = $l['RESIDENCE_DOESNT_EXIST'];
        }
        if(is_object($residence) && $residence->getInPossession() == false)
        {
            $error = $l['DONT_OWN_RESIDENCE'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->equipResidence($id);
            $replacedMessage = $route->replaceMessagePart($residence->getName(), $l['EQUIP_RESIDENCE_SUCCESS'], '/{name}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function getResidenceData()
    {
        return $this->data->getResidenceData();
    }
    
    public function getResidenceDataByUserID($userID)
    {
        return $this->data->getResidenceDataByUserID($userID);
    }
    
    public function getResidenceNameById($id)
    {
        return $this->data->getResidenceNameById($id);
    }
    
    public function getResidencePage()
    {
        return $this->data->getResidencePage();
    }
}
