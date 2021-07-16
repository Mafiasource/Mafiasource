<?PHP

namespace src\Business;

use src\Business\SmuggleService;
use src\Data\DrugLiquidDAO;
use app\config\Routing;

class DrugLiquidService
{
    private $data;
    
    public $maxUnits = 5;
    
    public $collectedUnitsAmount = 0;
    public $collectedUnits = 0;
    public $unfinishedUnits = 0;
    public $invalidUnits = 0;
    public $selectedUnits = 0;

    public function __construct()
    {
        $this->data = new DrugLiquidDAO();
        
        global $userData;
        if($userData->getDonatorID() == 1)
            $this->maxUnits = 7;
        elseif($userData->getDonatorID() == 5)
            $this->maxUnits = 15;
        elseif($userData->getDonatorID() == 10)
            $this->maxUnits = 20;
    }

    public function __destruct()
    {
        $this->data = null;
    }

    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function getDrugLiquidUnits($type = 1)
    {
        return $this->data->getDrugLiquidUnits($type);
    }
    
    public function buyUnits($post)
    {
        global $smuggle;
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->drugsLiquidsLangs();
        
        $priceEa = 5000;
        $type = $security->xssEscape($post['type']);
        $smuggleID = (int)$post['unit-type'];
        switch($type)
        {
            default:
            case 'Drugs':
                $typeID = 1;
                break;
            case 'Liquids':
                $typeID = 2;
                break;
        }
        $sPage = $smuggle->getSmugglingPageInfo($typeID);
        $dlUnits = $this->data->getDrugLiquidUnits($typeID);
        if(isset($post['create-one']))
        {
            $unitAmount = 1;
            $price = $priceEa;
        }
        elseif(isset($post['create-max']))
        {
            $unitAmount = $this->maxUnits - count($dlUnits);
            $price = $unitAmount * $priceEa;
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
        if($price > $userData->getCash())
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if($unitAmount <= 0)
        {
            $error = $l['NO_UNITS_LEFT_TO_PRODUCE'];
        }
        $unit = false;
        foreach($sPage['smuggle'] AS $s)
            if($s->getId() == $smuggleID)
                $unit = $s;
        
        if($unit === false)
        {
            $error = $l['INVALID_UNIT_TYPE'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->buyUnits($typeID, $smuggleID, $unitAmount);
            
            if($unitAmount == 1)
            {
                $replaces = array(
                    array('part' => $unit->getName(), 'message' => $l['BOUGHT_ONE_UNIT_SUCCESS'], 'pattern' => '/{unit}/'),
                    array('part' => number_format($price, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/'),
                );
            }
            else
            {
                $replaces = array(
                    array('part' => $unit->getName(), 'message' => $l['BOUGHT_MAX_UNITS_SUCCESS'], 'pattern' => '/{unit}/'),
                    array('part' => number_format($unitAmount, 0, '', ','), 'message' => FALSE, 'pattern' => '/{unitAmount}/'),
                    array('part' => number_format($price, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/'),
                    
                );
            }
            $replacedMessage = $route->replaceMessageParts($replaces);
            
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function collectUnits($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->drugsLiquidsLangs();
        
        $type = $security->xssEscape($post['type']);
        $units = isset($post['units']) ? $post['units'] : false;
        switch($type)
        {
            default:
            case 'Drugs':
                $typeID = 1;
                break;
            case 'Liquids':
                $typeID = 2;
                break;
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
        if($units === false)
        {
            $error = $l['SELECT_VALID_UNITS'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $collectedUnitIds = "";
            for ($i=0; $i < count($_POST['units']);$i++) // Loop through selected units
            {
                $id = (int)$post['units'][$i];
                $unit = $this->data->getDrugLiquidUnitByIdAndType($id, $typeID);
                
                // Update units and counters accordingly
                if(!is_object($unit))
                    $this->invalidUnits++;
                else
                {
                    if($unit->getTime() > time())
                        $this->unfinishedUnits++;
                    else
                    {
                        global $smuggle;
                        $sData = $smuggle->getSmugglingUnitById($unit->getSmuggleID());
                        if(!isset($spaceLeft)) $spaceLeft = $sData['unitsInfo']->getMaxCapacity();
                        if(($this->collectedUnitsAmount + $unit->getUnits()) < $spaceLeft)
                        {
                            $this->data->collectUnit($sData, $unit);
                            $collectedUnitIds .= $id . ", ";
                            $this->collectedUnitsAmount += $unit->getUnits();
                            $this->collectedUnits++;
                        }
                    }
                }
                $this->selectedUnits++;
            }
            $collectedUnitIds = substr($collectedUnitIds, 0, -2);
            $this->data->removeUnits($collectedUnitIds);
            
            global $route;
            $replacedMessages = array();
            if($this->collectedUnits > 0)
            {
                $replaces = array(
                    array('part' => number_format($this->collectedUnits, 0, '', ','), 'message' => $l['COLLECTED_UNITS_SUCCESS'], 'pattern' => '/{units}/'),
                    array('part' => number_format($this->collectedUnitsAmount, 0, '', ','), 'message' => FALSE, 'pattern' => '/{amount}/'),
                    
                );
                
                $replacedMessage = $route->replaceMessageParts($replaces);
                $replacedMessages[] = Routing::successMessage($replacedMessage);
            }
            if($this->unfinishedUnits > 0)
            {
                $replacedMessages[] = Routing::errorMessage($route->replaceMessagePart(number_format($this->unfinishedUnits, 0, '', ','), $l['UNFINISHED_UNITS_NOT_COLLECTED'], '/{units}/'));
            }
            if($this->invalidUnits > 0)
            {
                $replacedMessages[] = Routing::errorMessage($route->replaceMessagePart(number_format($this->invalidUnits, 0, '', ','), $l['INVALID_UNITS_NOT_COLLECTED'], '/{units}/'));
            }
            if(($this->selectedUnits - $this->collectedUnits) > 0 && $this->unfinishedUnits != ($this->selectedUnits - $this->collectedUnits) && $this->invalidUnits == 0)
            {
                $couldntHold = $this->selectedUnits - $this->collectedUnits;
                $replacedMessages[] = Routing::errorMessage($route->replaceMessagePart(number_format($couldntHold, 0, '', ','), $l['CAPACITY_FULL_UNITS_NOT_COLLECTED'], '/{units}/'));
            }
            return $replacedMessages;
        }
    }
}
