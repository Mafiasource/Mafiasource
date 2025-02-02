<?PHP
 
namespace src\Business;

use app\config\Routing;
use src\Business\FamilyService;
use src\Business\PossessionService;
use src\Data\GarageDAO;

/* User & Family garages + User garage shop */
/* Class has some cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */
 
class GarageService
{
    private $data;
    private $familyID;
    
    public $allowedOptions = array('small', 'medium', 'large', 'extra-large');
    public $garageOptions = array(
        'small' => array('space' => 5, 'price' => 100000),
        'medium' => array('space' => 10, 'price' => 300000),
        'large' => array('space' => 20, 'price' => 1000000),
        'extra-large' => array('space' => 35, 'price' => 2500000)
    );
    public $familyGarageOptions = array(
        'small' => array('space' => 15, 'price' => 100000),
        'medium' => array('space' => 45, 'price' => 300000),
        'large' => array('space' => 120, 'price' => 1000000),
        'extra-large' => array('space' => 300, 'price' => 2500000)
    );
    public $familyCrushers;
    public $familyConverters;
    public $tuneShop = array(
        'tires' => array(
            1 => array('name' => "Dayton", 'price' => 50000, 'pk' => 0, 'ts' => 1, 'ac' => 1, 'ct' => 1, 'br' => 0),
            array('name' => "Bridgestone", 'price' => 200000, 'pk' => 0, 'ts' => 1, 'ac' => 2, 'ct' => 2, 'br' => 0),
            array('name' => "Michelin", 'price' => 300000, 'pk' => 0, 'ts' => 1, 'ac' => 2, 'ct' => 3, 'br' => 1)
        ),
        'engine' => array(
            1 => array('name' => "V8", 'price' => 100000, 'pk' => 6, 'ts' => 3, 'ac' => 2, 'ct' => 1, 'br' => 0),
            array('name' => "V10", 'price' => 300000, 'pk' => 13, 'ts' => 4, 'ac' => 2, 'ct' => 1, 'br' => 0),
            array('name' => "V12", 'price' => 600000, 'pk' => 28, 'ts' => 5, 'ac' => 3, 'ct' => 1, 'br' => 0)
        ),
        'exhaust' => array(
            1 => array('name' => "Carbon", 'price' => 50000, 'pk' => 2, 'ts' => 1, 'ac' => 1, 'ct' => 1, 'br' => 1),
            array('name' => "Skunk2 Racing Power", 'price' => 200000, 'pk' => 5, 'ts' => 2, 'ac' => 2, 'ct' => 1, 'br' => 1),
            array('name' => "Invidia Q300 Titanium", 'price' => 400000, 'pk' => 9, 'ts' => 3, 'ac' => 3, 'ct' => 1, 'br' => 2)
        ),
        'shock_absorbers' => array(
            1 => array('name' => "Basic Twin Tube", 'price' => 50000, 'pk' => 0, 'ts' => 1, 'ac' => 1, 'ct' => 1, 'br' => 1),
            array('name' => "Acceleration Sensitive", 'price' => 75000, 'pk' => 0, 'ts' => 1, 'ac' => 2, 'ct' => 2, 'br' => 1),
            array('name' => "Coilover", 'price' => 130000, 'ts' => 1, 'pk' => 0, 'ac' => 1, 'ct' => 3, 'br' => 2)
        ),
    );
    
    public function __construct()
    {
        $this->data = new GarageDAO();
        
        global $userData;
        global $language;
        $l = $language->garageLangs();
        $this->familyCrushers = $this->familyConverters = array(
            1 => array('size' => $l['SMALL'], 'capacity' => 1000, 'price' => 7000000),
            2 => array('size' => $l['MEDIUM'], 'capacity' => 2500, 'price' => 16500000),
            3 => array('size' => $l['LARGE'], 'capacity' => 5000, 'price' => 32500000)
        );
        $this->familyID = $userData->getFamilyID();
        foreach(array_keys($this->tuneShop) AS $tune)
            $this->tuneShop[$tune][0] = array('name' => "Standard", 'price' => 0, 'pk' => 0, 'ts' => 0, 'ac' => 0, 'ct' => 0, 'br' => 0);
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function getGarageOptions($famGarage = FALSE)
    {
        if($famGarage == FALSE)
            return $this->garageOptions;
        else
            return $this->familyGarageOptions;
    }
    
    public static function getTuneDbField($tune)
    {
        $tuneDb = $tune;
        if($tune == "shock_absorbers")
            $tuneDb = "shockAbsorbers";
        
        return $tuneDb;
    }
    
    public function getSelectedTune($post)
    {
        $selectedTune = false;
        $allowedTunes = array();
        foreach(array_keys($this->tuneShop) AS $tune)
            $allowedTunes[] = $tune;
        
        foreach($post AS $key => $val)
        {
            foreach($allowedTunes AS $tune)
                if(preg_match("{^(buy|sell)-" . $tune . "-[1-3]$}", $key, $matches))
                    $selectedTune = array($tune => substr($matches[0], -1), 'action' => $matches[1]);
        }
        return $selectedTune;
    }
    
    public function addVehicleToGarage($post, $stateID)
    {
        if(isset($_SESSION['steal-vehicles'])) $svData = $_SESSION['steal-vehicles'];
        global $language;
        global $langs;
        $l = $language->stealVehiclesLangs();
        global $security;
        
        if($security->checkToken($post['securityToken']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        elseif($this->hasGarageInState($stateID) == FALSE)
        {
            $error = $l['STORE_VEHICLE_NO_GARAGE'];
        }
        elseif($this->hasSpaceLeftInGarage($stateID) == FALSE)
        {
            $error = $l['NOT_ENOUGH_SPACE_GARAGE'];
        }
        elseif(empty($svData) || !is_numeric($svData['vehicleID']) || !is_numeric($svData['dmg']))
        {
            $error = $l['NO_VEHICLE_TO_STORE'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            global $userData;
            $this->data->addVehicleToGarage($svData, $stateID);
            $replacedMessage = $route->replaceMessagePart($userData->getState(), $l['VEHICLE_STORED_IN_GARAGE'], '/{state}/');
            return Routing::successMessage($replacedMessage);
            
        }
    }
    
    public function sellVehicleImmediately($post)
    {
        if(isset($_SESSION['steal-vehicles'])) $svData = $_SESSION['steal-vehicles'];
        global $language;
        global $langs;
        $l = $language->stealVehiclesLangs();
        global $security;
        
        if($security->checkToken($post['securityToken']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        elseif(empty($svData) || !is_numeric($svData['vehicleID']) || !is_numeric($svData['dmg']))
        {
            $error = $l['NO_VEHICLE_TO_STORE'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $stolenValue = $svData['stolenValue'];
            global $route;
            $this->sellStolenVehicle($stolenValue);
            $lg = $language->garageLangs();
            $replacedMessage = $route->replaceMessagePart(number_format($stolenValue, 0, '', ','), $lg['SELL_VEHICLE_SUCCESS'], '/{price}/');
            return Routing::successMessage($replacedMessage);
            
        }
    }
    
    public function buyGarageOption($post, $famID = FALSE)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l        = $language->garageLangs();
        $stateID  = $userData->getStateID();
        $price    = $this->garageOptions[$post['type']]['price'];
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(!in_array($post['type'], $this->allowedOptions))
        {
            $error = $l['GARAGE_OPTION_DOESNT_EXIST'];
        }
        if($famID == FALSE)
        {
            if($this->hasGarageInState($stateID) != FALSE)
            {
                $error = $l['HAS_GARAGE_IN_STATE_ALREADY'];
            }
            if($userData->getCash() < $price)
            {
                $error = $langs['NOT_ENOUGH_MONEY_CASH'];
            }
        }
        else
        {
            $family = new FamilyService();
            $famData = $family->getFamilyDataByName($userData->getFamily());
            $fl = $language->familyLangs();
            $price    = $this->familyGarageOptions[$post['type']]['price'];
            
            if($userData->getFamilyBoss() !== true && $userData->getFamilyUnderboss() !== true)
            {
                $error = $fl['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            if(!is_object($famData))
            {
                $error = $fl['FAMILY_DOESNT_EXIST'];
            }
            if($this->data->hasFamilyGarage($famID) != FALSE)
            {
                $error = $l['HAS_FAMILY_GARAGE_ALREADY'];
            }
            if(is_object($famData) && $famData->getMoney() < $price)
            {
                $error = $fl['NOT_ENOUGH_MONEY_FAMBANK'];
            }
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $possession = new PossessionService();
            $possessionId = 8; //Garage | Possession logic
            $possessId = $possession->getPossessIdByPossessionId($possessionId, $stateID); // Possess table record id |Staat bezitting
            $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
            
            if($famID == FALSE)
            {
                $this->data->buyGarageInState($post['type'], $price, $stateID, $pData);
                $replacedMessage = $route->replaceMessagePart($userData->getState(), $l['GARAGE_BOUGHT_IN_STATE'], '/{state}/');
            }
            else
            {
                $this->data->buyFamilyGarage($famID, $post['type'], $price, $pData);
                $replacedMessage = $l['FAMILY_GARAGE_BOUGHT'];
            }
            
            return Routing::successMessage($replacedMessage);
            
        }
    }
    
    public function sellGarage($post, $famID = FALSE)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l          = $language->garageLangs();
        $stateID    = $userData->getStateID();
        $size       = $this->data->getGarageSizeByState($stateID);
        $garageData = $size !== FALSE ? $this->garageOptions[$size] : false;
        $spaceLeft  = $garageData !== false ? $this->data->spaceLeftInGarage($stateID, $garageData['space']) : 0;
        $price      = $garageData !== false ? round($garageData['price'] * 0.7) : 0;
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($famID == FALSE)
        {
            if($size !== FALSE && !in_array($size, $this->allowedOptions))
            {
                $error = $l['GARAGE_OPTION_DOESNT_EXIST'];
            }
            if($size === FALSE)
            {
                $error = $l['NO_GARAGE_IN_STATE'];
            }
            if($size !== FALSE && $spaceLeft != $garageData['space'])
            {
                $error = $l['SELL_OR_MOVE_VEHICLES_FIRST'];
                if($stateID == 1)
                    $error .= " " .$l['ADD_TO_SELL_OR_MOVE_VEHICLES_FIRST'];
            }
        }
        else
        {
            $family = new FamilyService();
            $famData = $family->getFamilyDataByName($userData->getFamily());
            $fl = $language->familyLangs();
            $size       = $this->getFamilyGarageSize();
            $garageData = $size !== FALSE ? $this->familyGarageOptions[$size] : false;
            $spaceLeft  = $garageData !== false ? $this->spaceLeftInFamilyGarage($garageData['space']) : 0;
            $price      = $garageData !== false ? round($garageData['price'] * 0.7) : 0;
            
            if($size !== FALSE && !in_array($size, $this->allowedOptions))
            {
                $error = $l['GARAGE_OPTION_DOESNT_EXIST'];
            }
            if($size === FALSE)
            {
                $error = $l['NO_FAMILY_GARAGE'];
            }
            if($userData->getFamilyBoss() !== true && $userData->getFamilyUnderboss() !== true)
            {
                $error = $fl['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            if(!is_object($famData))
            {
                $error = $fl['FAMILY_DOESNT_EXIST'];
            }
            if($size !== FALSE && $spaceLeft != $garageData['space'])
            {
                $error = $l['SELL_OR_CRUSH_VEHICLES_FIRST'];
            }
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            
            if($famID == FALSE)
            {
                $this->data->sellGarageInState($size, $price, $stateID);
                $replacedMessage = $route->replaceMessagePart($userData->getState(), $l['GARAGE_SOLD_IN_STATE'], '/{state}/');
            }
            else
            {
                $this->data->sellFamilyGarage($famID, $size, $price);
                $replacedMessage = $l['FAMILY_GARAGE_SOLD'];
            }
            
            return Routing::successMessage($replacedMessage);
            
        }
    }
    
    public function interactWithVehicle($post)
    {
        global $language;
        global $langs;
        $l        = $language->garageLangs();
        global $security;
        global $userData;
        $stateID  = $userData->getStateID();
        $garageID = (int)$post['id'];
        $action   = $security->xssEscape($post['action']);
        $vehicleData = $this->data->getVehicleInGarageById($garageID);
        
        $allowedActions = array("repair", "sell", "tune", "buy", "bought");
        
        if($security->checkToken($post['securityToken']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(!in_array($action, $allowedActions))
        {
            $error = $langs['INVALID_ACTION'];
        }
        if($action == 'repair' || $action == 'sell' || $action == 'tune')
        {
            if($this->hasGarageInState($stateID) == FALSE)
            {
                $error = $l['NO_GARAGE_IN_STATE'];
            }
            if($this->data->isVehicleInGarageInState($stateID, $garageID) == FALSE)
            {
                $error = $l['VEHICLE_NOT_IN_CURRENT_GARAGE'];
            }
            if($this->data->userOwnsVehicle($garageID) == FALSE || !is_object($vehicleData))
            {
                $error = $l['VEHICLE_NOT_OWNED_BY_USER'];
            }
            if(is_object($vehicleData)) $exist = $this->data->getShopVehicleById($vehicleData->getVehicle()->getId());
            if(isset($exist) && !is_object($exist))
            {
                $error = $l['VEHICLE_DOESNT_EXIST'];
            }
        }
        else
        {
            $vehicleData = $this->data->getShopVehicleById($garageID);
            if(!is_object($vehicleData))
            {
                $error = $l['VEHICLE_DOESNT_EXIST'];
            }
        }
            
        if(is_object($vehicleData))
        {
            switch($action)
            {
                /* BEGIN GARAGE */
                case 'repair':
                    if($vehicleData->getRepairCosts() > $userData->getCash())
                    {
                        $error = $langs['NOT_ENOUGH_MONEY_CASH'];
                    }
                    if($vehicleData->getRepairCosts() == 0)
                    {
                        $error = $l['NO_REPAIR_NEEDED_FOR_VEHICLE'];
                    }
                    break;
                case 'tune':
                    $selectedTune = $this->getSelectedTune($post);
                    
                    if($vehicleData->getDamage() != 0)
                    {
                        $error = $l['TUNE_VEHICLE_DAMAGED'];
                    }
                    if(!is_array($selectedTune) || $selectedTune == false)
                    {
                        $error = $l['ITEM_DOESNT_EXIST'];
                    }
                    else
                    {
                        $tune = array_keys($selectedTune)[0];
                        $tuneAction = $selectedTune['action'];
                        $tuneItem = $selectedTune[$tune];
                        $tuneShopPrice = $this->tuneShop[$tune][$tuneItem]['price'];
                        $tunePrice = isset($tuneAction) && $tuneAction == "buy" ? $tuneShopPrice : round($tuneShopPrice * 0.7);
                        switch($tune)
                        {
                            default: case "tires":
                                $inPossession = $vehicleData->getTires() > 0 ? $vehicleData->getTires() : false;
                                break;
                            case "engine":
                                $inPossession = $vehicleData->getEngine() > 0 ? $vehicleData->getEngine() : false;
                                break;
                            case "exhaust":
                                $inPossession = $vehicleData->getExhaust() > 0 ? $vehicleData->getExhaust() : false;
                                break;
                            case "shock_absorbers":
                                $inPossession = $vehicleData->getShockAbsorbers() > 0 ? $vehicleData->getShockAbsorbers() : false;
                                break;
                        }
                        if($tuneAction == "buy")
                        {
                            if($tunePrice > $userData->getCash() && $inPossession == false)
                            {
                                $error = $langs['NOT_ENOUGH_MONEY_CASH'];
                            }
                            if($inPossession >= 1)
                            {
                                $error = $l['TUNE_ITEM_IN_POSSESSION'];
                            }
                        }
                        if($tuneAction == "sell" && (string)$inPossession !== (string)$tuneItem)
                        {
                            $error = $l['TUNE_ITEM_NOT_IN_POSSESSION'];
                        }
                    }
                    break;
                case 'sell':
                    if($vehicleData->getTires() > 0 || $vehicleData->getEngine() > 0 || $vehicleData->getExhaust() > 0 || $vehicleData->getShockAbsorbers() > 0)
                    {
                        $error = $l['CANNOT_SELL_TUNED_VEHICLE'];
                    }
                    break;
                /* //EIND GARAGE */
                
                /* BEGIN SHOP */
                case 'buy':
                    if($vehicleData->getPrice() > $userData->getCash())
                    {
                        $error = $langs['NOT_ENOUGH_MONEY_CASH'];
                    }
                    $garages = $this->data->getGaragesWithFreeSpace();
                    if($garages == FALSE)
                    {
                        $error = $l['NO_GARAGE_WITH_FREE_SPACE'];
                    }
                    break;
                case 'bought':
                    if($vehicleData->getPrice() > $userData->getCash())
                    {
                        $error = $langs['NOT_ENOUGH_MONEY_CASH'];
                    }
                    $garageID = (int)$post['garage'];
                    if($this->data->hasSpaceLeftInGarage($this->data->getStateIDByGarageID($garageID)) === FALSE)
                    {
                        $error = $l['NO_GARAGE_WITH_FREE_SPACE'];
                    }
                    break;
                /* //EINDE SHOP */
                default:
                    break;
            }
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $possession = new PossessionService();
            $possessionId = 8; //Garage | Possession logic
            $possessId = $possession->getPossessIdByPossessionId($possessionId, $stateID); // Possess table record id |Staat bezitting
            $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
            switch($action)
            {
                /* GARAGE ... */
                case 'repair':
                    $this->data->repairVehicle($vehicleData, $pData);
                    $successMessage = $route->replaceMessagePart(number_format($vehicleData->getRepairCosts(), 0, '', ','), $l['REPAIR_VEHICLE_SUCCESS'], '/{costs}/');
                    break;
                case 'sell':
                    $this->data->sellVehicle($vehicleData);
                    $successMessage = $route->replaceMessagePart(number_format($vehicleData->getValue(), 0, '', ','), $l['SELL_VEHICLE_SUCCESS'], '/{price}/');
                    break;
                case 'tune':
                    $tuneData = array('tuneDb' => self::getTuneDbField($tune), 'item' => $tuneItem, 'price' => $tunePrice);
                    switch($tuneAction)
                    {
                        default: case 'buy':
                            $tuneMessage = $l['BUY_VEHICLE_TUNE_ITEM_SUCCESS'];
                            $priceTag = "costs";
                            $this->data->buyVehicleTuneUpgrade($vehicleData, $tuneData, $pData);
                            break;
                        case 'sell':
                            $tuneMessage = $l['SELL_VEHICLE_TUNE_ITEM_SUCCESS'];
                            $priceTag = "price";
                            $this->data->sellVehicleTuneUpgrade($vehicleData, $tuneData, $pData);
                            break;
                    }
                    $replaces = array(
                        array('part' => $this->tuneShop[$tune][$tuneItem]['name'], 'message' => $tuneMessage, 'pattern' => '/{itemName}/'),
                        array('part' => strtolower($l[strtoupper($tune)]), 'message' => FALSE, 'pattern' => '/{type}/'),
                        array('part' => number_format($tunePrice, 0, '', ','), 'message' => FALSE, 'pattern' => '/{' . $priceTag . '}/'),
                    );
                    $successMessage = $route->replaceMessageParts($replaces);
                    break;
                /* SHOP ... */
                case 'buy':
                    $garagesSelection = "<select name='garage' class='garage-selection'>
                                            <option value='0'>".$langs['MAKE_A_CHOICE']."</option>";
                    foreach($garages AS $g)
                    {
                        $garagesSelection .= "<option value ='".$g->getId()."'>".$g->getState()." - ".$g->getSize()."</option>";
                    }
                    $garagesSelection .= "</select><br /><br />";
                    $replaces = array(
                        array('part' => $garagesSelection, 'message' => $l['BUY_VEHICLE_CHOOSE_GARAGE'], 'pattern' => '/{garagesSelection}/'),
                        array('part' => $vehicleData->getName(), 'message' => FALSE, 'pattern' => '/{vehicle}/'),
                        array('part' => $vehicleData->getId(), 'message' => FALSE, 'pattern' => '/{vehicleID}/'),
                        array('part' => $security->getToken(), 'message' => FALSE, 'pattern' => '/{securityToken}/')
                    );
                    $successMessage = $route->replaceMessageParts($replaces);
                    break;
                case 'bought':
                    $possession = new PossessionService();
                    $possessionId = 9; //Voertuig Handelszaak | Possession logic
                    $possessId = $possession->getPossessIdByPossessionId($possessionId, $stateID, $userData->getCityID()); // Possess table record id |Stad bezitting
                    $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
                    
                    $this->data->buyVehicle($vehicleData, $garageID, $pData);
                    $replaces = array(
                        array('part' => $vehicleData->getName(), 'message' => $l['BOUGHT_VEHICLE_SUCCESS'], 'pattern' => '/{vehicle}/'),
                        array('part' => number_format($vehicleData->getPrice(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
                    );
                    $successMessage = $route->replaceMessageParts($replaces);
                    break;
                default:
                    break;
            }
            return Routing::successMessage($successMessage);
        }
    }
    
    public function buyFamilyCrusherConverter($post, $type = 'Crusher')
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l        = $language->garageLangs();
        $fl = $language->familyLangs();
        if($type == 'Crusher')
            $cid = (int)$post['crusher'];
        elseif($type == 'Converter')
            $cid = (int)$post['converter'];
            
        $family = new FamilyService();
        $famData = $family->getFamilyDataByName($userData->getFamily());
        $famID = $famData->getId();
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(!array_key_exists($cid, $this->familyCrushers))
        {
            $error = $l['ITEM_DOESNT_EXIST'];
        }
        else
        {
            // Item exists check for everything else from here
            if($userData->getFamilyBoss() !== true && $userData->getFamilyBankmanager() !== true)
            {
                $error = $fl['NO_RIGHTS_FAMILY_MANAGEMENT'];
            }
            if(!is_object($famData))
            {
                $error = $fl['FAMILY_DOESNT_EXIST'];
            }
            if(is_object($famData) && $famData->getMoney() < $this->familyCrushers[$cid]['price'])
            {
                $error = $fl['NOT_ENOUGH_MONEY_FAMBANK'];
            }
            $crusherConverter = $this->data->getFamilyCrusherConverter($famID);
            if($type == 'Crusher' && is_object($crusherConverter) && $crusherConverter->getCrusher())
            {
                $error = $l['FAMILY_BOUGHT_ITEM_ALREADY'];
            }
            if($type == 'Converter' && is_object($crusherConverter) && $crusherConverter->getConverter())
            {
                $error = $l['FAMILY_BOUGHT_ITEM_ALREADY'];
            }
            if($this->data->hasFamilyGarage($famID) == FALSE)
            {
                $error = $l['NO_FAMILY_GARAGE'];
            }
        }
        
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            if($type == 'Crusher')
            {
                $this->data->buyFamilyCrusher($famID, $this->familyCrushers[$cid]);
                $replaces = array(
                    array('part' => number_format($this->familyCrushers[$cid]['capacity'], 0, '', ','), 'message' => $l['FAMILY_CRUSHER_BOUGHT'], 'pattern' => '/{capacity}/'),
                    array('part' => number_format($this->familyCrushers[$cid]['price'], 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
            }
            elseif($type == 'Converter')
            {
                $this->data->buyFamilyConverter($famID, $this->familyConverters[$cid]);
                $replaces = array(
                    array('part' => number_format($this->familyConverters[$cid]['capacity'], 0, '', ','), 'message' => $l['FAMILY_CONVERTER_BOUGHT'], 'pattern' => '/{capacity}/'),
                    array('part' => number_format($this->familyConverters[$cid]['price'], 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
            }
            
            return Routing::successMessage($replacedMessage);
            
        }
    }
    
    public function interactWithFamilyVehicles($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l        = $language->garageLangs();
        if(isset($post['vehicles'])) $vhcls = $post['vehicles'];
        
        $family = new FamilyService();
        $famData = $family->getFamilyDataByName($userData->getFamily());
        $famID = $famData->getId();
        $crusherConverter = $this->data->getFamilyCrusherConverter($famID);
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getFamilyBoss() !== true && $userData->getFamilyUnderboss() !== true)
        {
            $error = $fl['NO_RIGHTS_FAMILY_MANAGEMENT'];
        }
        if(!is_object($famData))
        {
            $error = $fl['FAMILY_DOESNT_EXIST'];
        }
        if($this->data->hasFamilyGarage($famID) == FALSE)
        {
            $error = $l['NO_FAMILY_GARAGE'];
        }
        if(isset($vhcls) && count($vhcls) >= 1 && is_object($famData))
        {
            $vehicles = array();
            foreach($vhcls AS $id)
            {
    			if(!ctype_digit($id))
                {
    				$error = $l['VEHICLE_DOESNT_EXIST'];
    				break;
    			}
                if($this->data->isVehicleInFamilyGarage($id, $famID) == FALSE)
                {
                    $error = $l['VEHICLE_DOESNT_EXIST'];
                    break;
                }
                array_push($vehicles, (int)$id); // Sanitize userInput for later use (db)
            }
        }
        elseif((isset($vhcls) && count($vhcls) == 0) || !isset($vhcls))
        {
            $error = $l['SELECT_ONE_OR_MORE_VEHICLES'];
        }
        if(isset($vhcls) && isset($post['crush-convert']))
        {
            if( $crusherConverter->getCrusher() < 1  ||
                $crusherConverter->getConverter() < 1)
            {
                $error = $l['NOT_ENOUGH_CRUSH_CONVERT_CAPACITY'];
            }
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            if(isset($vehicles)) // Sanitized version of $vhcls / $post['vehicles']
            {
                global $route;
                $implodeStr = implode(",", $vehicles);
                if(isset($post['sell']))
                {
                    $money = 0;
                    foreach($vehicles AS $id)
                    {
                        $vehicleData = $this->data->getFamilyVehicleById($id, $famID);
                        $money += round($vehicleData->getValue(), 0);
                    }
                    $this->data->sellFamilyVehicles($implodeStr, $money, $famID);
                    
                    $replacedMessage = $route->replaceMessagePart(number_format($money, 0, '', ','), $l['SELL_FAMILY_VEHICLES_SUCCESS'], '/{money}/');
                    return Routing::successMessage($replacedMessage);
                }
                elseif(isset($post['crush-convert']))
                {
                    $bullets = 0;
                    $crushConvertCapLeft = $crusherConverter->getCrusher();
                    if($crusherConverter->getConverter() < $crushConvertCapLeft)
                        $crushConvertCapLeft = $crusherConverter->getConverter();
                    
                    $unhandledList = array();
                    $capReached = false;
                    $i = 0;
                    foreach($vehicles AS $id)
                    {
                        if($crushConvertCapLeft == $i)
                            $capReached = true;
                        
                        if(!$capReached)
                        {
                            $vehicleData = $this->data->getFamilyVehicleById($id, $famID);
                            $bullets += round($vehicleData->getValue() / 2500, 0);
                        }
                        else
                        {
                            if(($key = array_search($id, $vehicles)) !== false)
                            {
                                unset($vehicles[$key]);
                                $unhandledList[] = $id;
                            }
                        }
                        $i++;
                    }
                    if($capReached)
                        $implodeStr = implode(",", $vehicles);
                    
                    $this->data->crushConvertFamilyVehicles($implodeStr, $bullets, count($vehicles), $famID);
                    
                    if($capReached)
                    {
                        $replaces = array(
                            array('part' => number_format($bullets, 0, '', ','), 'message' => $l['CRUSH_CONVERT_FAMILY_VEHICLES_CAP_SUCCESS'], 'pattern' => '/{bullets}/'),
                            array('part' => number_format(count($unhandledList), 0, '', ','), 'message' => FALSE, 'pattern' => '/{unhandled}/')
                        );
                        $replacedMessage = $route->replaceMessageParts($replaces);
                    }
                    else
                        $replacedMessage = $route->replaceMessagePart(number_format($bullets, 0, '', ','), $l['CRUSH_CONVERT_FAMILY_VEHICLES_SUCCESS'], '/{bullets}/');
                    
                    return Routing::successMessage($replacedMessage);
                }
            }
        }
    }
    
    public function hasGarageInState($stateID)
    {
        return $this->data->hasGarageInState($stateID);
    }
    
    public function hasFamilyGarage()
    {
        $famID = $this->familyID;
        if($famID > 0)
            return $this->data->hasFamilyGarage($famID);
    }
    
    public function sellStolenVehicle($value)
    {
        return $this->data->sellStolenVehicle($value);
    }
    
    public function sellStolenFamilyVehicle($familyID, $value)
    {
        return $this->data->sellStolenFamilyVehicle($familyID, $value);
    }
    
    public function addFamilyVehicleToGarage($vData, $familyID)
    {
        return $this->data->addFamilyVehicleToGarage($vData, $familyID);
    }
    
    public function hasSpaceLeftInGarage($stateID)
    {
        return $this->data->hasSpaceLeftInGarage($stateID);
    }
    
    public function hasSpaceLeftInFamilyGarage()
    {
        $famID = $this->familyID;
        if($famID > 0)
            return $this->data->hasSpaceLeftInFamilyGarage($famID);
    }
    
    public function spaceLeftInGarage($stateID, $maxSpace)
    {
        return $this->data->spaceLeftInGarage($stateID, $maxSpace);
    }
    
    public function spaceLeftInFamilyGarage($maxSpace)
    {
        $famID = $this->familyID;
        if($famID > 0)
            return $this->data->spaceLeftInFamilyGarage($famID, $maxSpace);
    }
    
    public function getGarageSizeByState($stateID)
    {
        return $this->data->getGarageSizeByState($stateID);
    }
    
    public function getFamilyGarageSize()
    {
        $famID = $this->familyID;
        if($famID > 0)
            return $this->data->getFamilyGarageSize($famID);
    }
    
    public function isVehicleInGarageInState($stateID, $garageID)
    {
        return $this->data->isVehicleInGarageInState($stateID, $garageID);
    }
    
    public function getVehiclesInGarageByState($stateID, $from, $to)
    {
        return $this->data->getVehiclesInGarageByState($stateID, $from, $to);
    }
    
    public function getAllVehiclesInGarageByState($stateID)
    {
        return $this->data->getAllVehiclesInGarageByState($stateID);
    }
    
    public function moveVehicleToGarageInState($garageID, $stateID)
    {
        return $this->data->moveVehicleToGarageInState($garageID, $stateID);
    }
    
    public function getVehiclesInFamilyGarage($from, $to)
    {
        $famID = $this->familyID;
        if($famID > 0)
            return $this->data->getVehiclesInFamilyGarage($famID, $from, $to);
    }
    
    public function getVehiclesInShop($from, $to)
    {
        return $this->data->getVehiclesInShop($from, $to);
    }
    
    public function getShopVehicleInfo($id)
    {
        return $this->data->getShopVehicleInfo($id);
    }
    
    public function getFamilyCrusherConverter()
    {
        $famID = $this->familyID;
        if($famID > 0)
            return $this->data->getFamilyCrusherConverter($famID);
    }
    
    public function getVehicleInGarageById($id)
    {
        return $this->data->getVehicleInGarageById($id);
    }
}
