<?PHP
 
namespace src\Business;

use app\config\Routing;
use src\Business\PossessionService;
use src\Data\EquipmentDAO;
 
class EquipmentService
{
    private $data;
    private $table;
    
    public function __construct($table = "weapon")
    {
        $this->data = new EquipmentDAO($table);
        $this->table = $table;
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function getEquipmentPage()
    {
        return $this->data->getEquipmentPage();
    }
    
    public function buyEquipment($post)
    {
        global $userService;
        global $userData;
        global $language;
        global $langs;
        $l = $language->equipmentStoresLangs();
        global $route;
        global $security;
        
        $id = (int)$post['id'];
        $equipmentPage = $this->data->getEquipmentPage();
        $idToCheck = $id;
        if($this->table !== 'weapon') $idToCheck = $id-1;
        if(array_key_exists($idToCheck, $equipmentPage))
        {
            $equipment = $equipmentPage[$idToCheck];
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
        if(!isset($equipment) || $id == 0)
        {
            $error = $l['EQUIPMENT_DOESNT_EXIST'];
        }
        if(is_object($equipment) && $equipment->getPrice() > $userData->getCash())
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if($this->table == 'weapon')
        {
            $statusPage = $userService->getStatusPageInfo();
            $avgWpnExpTrain = ($statusPage->getWeaponExperience() + $statusPage->getWeaponTraining()) / 2;
            if(is_object($equipment) && $avgWpnExpTrain < $equipment->getWpnExpTrain())
            {
                $error = $l['NOT_ENOUGH_WEAPON_EXP_TRAIN'];
            }
        }
        if(is_object($equipment) && $equipment->getInPossession() == true)
        {
            $error = $l['ALREADY_OWN_EQUIPMENT'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $possession = new PossessionService();
            $possessionId = 5; //Uitrusting winkel | Possession logic
            $possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID(), $userData->getCityID()); // Possess table record id |Stad bezitting
            $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

            $this->data->buyEquipment($id, $pData);
            $replaces = array(
                array('part' => $equipment->getName(), 'message' => $l['BOUGHT_EQUIPMENT_SUCCESS'], 'pattern' => '/{name}/'),
                array('part' => number_format($equipment->getPrice(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function sellEquipment($post)
    {
        global $userData;
        global $language;
        global $langs;
        $l = $language->equipmentStoresLangs();
        global $route;
        global $security;
        
        $id = (int)$post['id'];
        $equipmentPage = $this->data->getEquipmentPage();
        $idToCheck = $id;
        if($this->table !== 'weapon') $idToCheck = $id-1;
        if(array_key_exists($idToCheck, $equipmentPage))
        {
            $equipment = $equipmentPage[$idToCheck];
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
        if(!isset($equipment) || $id == 0)
        {
            $error = $l['EQUIPMENT_DOESNT_EXIST'];
        }
        if(isset($equipment) && is_object($equipment) && $equipment->getInPossession() == false)
        {
            $error = $l['DONT_OWN_EQUIPMENT'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->sellEquipment($id);
            $replaces = array(
                array('part' => $equipment->getName(), 'message' => $l['SOLD_EQUIPMENT_SUCCESS'], 'pattern' => '/{name}/'),
                array('part' => number_format(($equipment->getPrice()*0.6), 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function equipEquipment($post)
    {
        global $userData;
        global $language;
        global $langs;
        $l = $language->equipmentStoresLangs();
        global $route;
        global $security;
        
        $id = (int)$post['id'];
        $equipmentPage = $this->data->getEquipmentPage();
        $idToCheck = $id;
        if($this->table !== 'weapon') $idToCheck = $id-1;
        if(array_key_exists($idToCheck, $equipmentPage))
        {
            $equipment = $equipmentPage[$idToCheck];
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
        if(!isset($equipment))
        {
            $error = $l['EQUIPMENT_DOESNT_EXIST'];
        }
        if(is_object($equipment) && $equipment->getInPossession() == false)
        {
            $error = $l['DONT_OWN_EQUIPMENT'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->equipEquipment($id);
            $replacedMessage = $route->replaceMessagePart($equipment->getName(), $l['EQUIP_EQUIPMENT_SUCCESS'], '/{name}/');
            return Routing::successMessage($replacedMessage);
        }
    }
}
