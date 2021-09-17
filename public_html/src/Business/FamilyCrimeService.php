<?PHP

namespace src\Business;

use src\Business\PrisonService;
use src\Business\VehicleService;
use src\Business\GarageService;
use src\Data\FamilyCrimeDAO;
use app\config\Routing;

/* Class has some cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */
 
class FamilyCrimeService
{
    private $data;
    public $crimeNames;
    public $singleCrimeNames;
    
    public function __construct()
    {
        $this->data = new FamilyCrimeDAO();
        global $language;
        global $langs;
        global $lang;
        $l = $language->familyCrimeLangs();
        $this->crimeNames = $this->singleCrimeNames = array(1 => "Garage " . strtolower($langs['RAID']), "Showroom " . strtolower($langs['RAID']), $l['CAR_FAIR'] . " " . strtolower($langs['RAID']));
        if($lang == 'nl')
            foreach($this->crimeNames AS $k => $v)
                $this->crimeNames[$k] = $v."len";
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    private static function singleStolenVehicle($randCar, $familyID)
    {
        global $route;
        global $security;
        global $language;
        $l = $language->familyCrimeLangs();
        $garageService = new GarageService();
        
        $randDmg = $security->randInt(0,95);
        $garageService->addFamilyVehicleToGarage(array('vehicleID' => $randCar->getId(), 'dmg' => $randDmg), $familyID);
        $replaces = array(
            array('part' => $randCar->getName(), 'message' => $l['FAMILY_CRIME_SUCCESS'], 'pattern' => '/{carName}/'),
            array('part' => $randDmg, 'message' => FALSE, 'pattern' => '/{damage}/'),
            array('part' => PROTOCOL.STATIC_SUBDOMAIN.'.'.$route->settings['domainBase'].'/foto/web/public/images/vehicle/'.$randCar->getPicture(), 'message' => FALSE, 'pattern' => '/{imageSrc}/')
        );
        return $route->replaceMessageParts($replaces);
    }
    
    private static function singleStolenVehicleSold($randCar, $familyID)
    {
        global $route;
        global $security;
        global $language;
        $l = $language->familyCrimeLangs();
        $garageService = new GarageService();
        
        $randDmg = $security->randInt(0,95);
        $stolenValue = round(($randCar->getPrice()/100) * (100-$randDmg));
        $garageService->sellStolenFamilyVehicle($familyID, $stolenValue);
        $replaces = array(
            array('part' => $randCar->getName(), 'message' => $l['FAMILY_CRIME_SUCCESS_GARAGE_FULL'], 'pattern' => '/{carName}/'),
            array('part' => $randDmg, 'message' => FALSE, 'pattern' => '/{damage}/'),
            array('part' => number_format($stolenValue, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/'),
            array('part' => PROTOCOL.STATIC_SUBDOMAIN.'.'.$route->settings['domainBase'].'/foto/web/public/images/vehicle/'.$randCar->getPicture(), 'message' => FALSE, 'pattern' => '/{imageSrc}/')
        );
        return $route->replaceMessageParts($replaces);
    }
    
    private static function multipleStolenVehicles($randCars, $spaceLeft, $familyID)
    {
        global $route;
        global $security;
        global $language;
        $l = $language->familyCrimeLangs();
        $garageService = new GarageService();
        
        $i = $spaceLeft;
        $vehicles = array();
        foreach($randCars AS $randCar)
        {
            $randDmg = $security->randInt(0,95);
            $stolenValue = round(($randCar->getPrice()/100) * (100-$randDmg));
            if($i >= 1)
            {
                $garageService->addFamilyVehicleToGarage(array('vehicleID' => $randCar->getId(), 'dmg' => $randDmg), $familyID);
                $v = self::stolenVehicleMessage($randCar, $randDmg);
            }
            else
            {
                $garageService->sellStolenFamilyVehicle($familyID, $stolenValue);
                $v = self::stolenVehicleSoldMessage($randCar, $randDmg, $stolenValue);
            }
            array_push($vehicles, $v);
            $i--;
        }
        
        $v1 = $vehicles[0];
        $v2 = isset($vehicles[1]) ? $vehicles[1] : null;
        $v3 = isset($vehicles[2]) ? $vehicles[2] : null;
        
        $replaces = array(
            array('part' => $v1, 'message' => $l['FAMILY_CRIME_SUCCESS_MULTIPLE'], 'pattern' => '/{add}/'),
            array('part' => $v2, 'message' => FALSE, 'pattern' => '/{add2}/'),
            array('part' => $v3, 'message' => FALSE, 'pattern' => '/{add3}/')
        );
        return $route->replaceMessageParts($replaces);
    }
    
    private static function stolenVehicleMessage($randCar, $randDmg)
    {
        global $route;
        global $language;
        $l = $language->familyCrimeLangs();
        
        $vReplaces = array(
            array('part' => $randCar->getName(), 'message' => $l['FAMILY_CRIME_MULTIPLE_ADD'], 'pattern' => '/{carName}/'),
            array('part' => $randDmg, 'message' => FALSE, 'pattern' => '/{damage}/'),
            array('part' => PROTOCOL.STATIC_SUBDOMAIN.'.'.$route->settings['domainBase'].'/foto/web/public/images/vehicle/'.$randCar->getPicture(), 'message' => FALSE, 'pattern' => '/{imageSrc}/')
        );
        return $route->replaceMessageParts($vReplaces);
    }
    
    private static function stolenVehicleSoldMessage($randCar, $randDmg, $stolenValue)
    {
        global $route;
        global $language;
        $l = $language->familyCrimeLangs();
        
        $vReplaces = array(
            array('part' => $randCar->getName(), 'message' => $l['FAMILY_CRIME_MULTIPLE_GARAGE_FULL_ADD'], 'pattern' => '/{carName}/'),
            array('part' => $randDmg, 'message' => FALSE, 'pattern' => '/{damage}/'),
            array('part' => number_format($stolenValue, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/'),
            array('part' => PROTOCOL.STATIC_SUBDOMAIN.'.'.$route->settings['domainBase'].'/foto/web/public/images/vehicle/'.$randCar->getPicture(), 'message' => FALSE, 'pattern' => '/{imageSrc}/')
        );
        return $route->replaceMessageParts($vReplaces);
    }
    
    public function getFamilyCrimes()
    {
        return $this->data->getFamilyCrimes();
    }
    
    public function organizeFamilyCrime($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyCrimeLangs();
        $fl = $language->familyLangs();
        
        $crime = (int)$post['crime'];
        $participants = (int)$post['participants'];
        $mercenaries = false;
        if(isset($_POST['mercenaries'])) $mercenaries = true;
        
        $check = $this->data->userInsideFamilyCrime();
        
        $garageService = new GarageService();
        
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
        if(!$garageService->hasFamilyGarage())
        {
            $error = $l['NO_FAMILY_GARAGE'];
        }
        if(!is_numeric($participants) || ($participants < 3 || $participants > 23) )
        {
            $error = $l['INVALID_PARTICIPANTS_SELECTED'];
        }
        if(!is_numeric($crime) || ($crime < 1 || $crime > 3) || !in_array($crime, array_keys($this->crimeNames)))
        {
            $error = $l['INVALID_CRIME_SELECTED'];
        }
        if($check)
        {
            $error = $l['ALREADY_INSIDE_FAMILY_CRIME'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            
            $startSerialized = 'a:0:{}';
            $prtcpnts = unserialize($startSerialized);
            $newPrtcpnts = $prtcpnts;
            $newPrtcpnts[] = $userData->getId();
            $serializedParticipants = serialize($newPrtcpnts);
            
            $this->data->organizeFamilyCrime($participants, $serializedParticipants, $crime, $userData->getStateID(), $mercenaries);
            
            $replaces = array(
                array('part' => strtolower($this->singleCrimeNames[$crime]), 'message' => $l['ORGANIZE_FAMILY_CRIME_SUCCESS'], 'pattern' => '/{crime}/'),
                array('part' => $userData->getState(), 'message' => FALSE, 'pattern' => '/{state}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function userInsideFamilyCrime()
    {
        return $this->data->userInsideFamilyCrime();
    }
    
    public function interactFamilyCrime($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->familyCrimeLangs();
        $fl = $language->familyLangs();
        
        $familyID = $userData->getFamilyID();
        
        $crime = (int)$post['crime'];
        $famCrimeData = $this->data->getFamilyCrimeById($crime);
        
        $join = isset($post['join']);
        $leave = isset($post['leave']);
        $start = isset($post['start']);
        
        $garageService = new GarageService();
        
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
        if($familyID == 0 || $familyID == false)
        {
            $error = $fl['FAMILY_DOESNT_EXIST'];
        }
        if(!$garageService->hasFamilyGarage())
        {
            $error = $l['NO_FAMILY_GARAGE'];
        }
        /* JOIN ERRORS */
        if($join)
        {
            if(is_object($famCrimeData) && $famCrimeData->getNumInCrime() == $famCrimeData->getNumParticipants())
            {
                $error = $l['FAMILY_CRIME_FULL'];
            }
            if($this->userInsideFamilyCrime())
            {
                $error = $l['ALREADY_INSIDE_FAMILY_CRIME'];
            }
            if(is_object($famCrimeData) && $famCrimeData->getStateID() != $userData->getStateID())
            {
                $error = $l['NOT_INSIDE_SAME_STATE'];
            }
            // check dubbel accounts TODO
        }
        /* LEAVE ERRORS */
        elseif($leave)
        {
            if(is_object($famCrimeData) && !$famCrimeData->getUserInCrime())
            {
                $error = $l['NOT_PART_OF_FAMILY_CRIME'];
            }
        }
        /* START ERRORS */
        elseif($start)
        {
            global $route;
            if(is_object($famCrimeData) && !$famCrimeData->getUserInCrime())
            {
                $error = $l['NOT_PART_OF_FAMILY_CRIME'];
            }
            if(is_object($famCrimeData) && (
                    ($famCrimeData->getWithMercenaries() === false && ($famCrimeData->getNumInCrime() < $famCrimeData->getNumParticipants())) || 
                    ($famCrimeData->getWithMercenaries() === true && ($famCrimeData->getMercenariesReady() + $famCrimeData->getNumInCrime() < $famCrimeData->getNumParticipants()))
                )
            )
            {
                $error = $l['NOT_ENOUGH_MEMBERS_YET'];
            }
            if(is_object($famCrimeData))
            {
                global $userService;
                $prison = new PrisonService();
                $participants = unserialize($famCrimeData->getParticipantIds());
                $i = 0;
                foreach($participants AS $p)
                {
                    if($prison->isUserInPrison($p))
                    {
                        if((isset($error) && $i === 0) || !isset($error))
                            $error = "";
                        
                        $error = $l['ONE_OR_MORE_IN_PRISON'];
                        $i++;
                    }
                    $wt = $this->data->getWaitingTimeByUserID($p);
                    if($wt == false || $wt > time())
                    {
                        if((isset($error) && $i === 0 && $error === $l['ONE_OR_MORE_IN_PRISON']) || !isset($error))
                            $error = "";
                        
                        $replaces = array(
                            array('part' => $userService->getUsernameById($p), 'message' => $l['MEMBER_NOT_READY'], 'pattern' => '/{user}/'),
                            array('part' => "<span id='cFamCrimeWaitTime_".$p."'></span>".counterClean("FamCrimeWaitTime_".$p, $wt), 'message' => FALSE, 'pattern' => '/{waitingTime}/')
                        );
                        $replacedMessage = $route->replaceMessageParts($replaces);
                        $error .= $replacedMessage."&nbsp;";
                        $i++;
                    }
                }
            }
        }
        if(!isset($famCrimeData) || empty($famCrimeData))
        {
            $error = $l['INVALID_CRIME_SELECTED'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            if($join)
            {
                $newParticipants = unserialize($famCrimeData->getParticipantIds());
    			$newParticipants[] = $userData->getId();
                $this->data->joinFamilyCrime($famCrimeData->getId(), serialize($newParticipants));
                
                return Routing::successMessage($l['JOINED_FAMILY_CRIME']);
            }
            elseif($leave)
            {
                $newParticipants = unserialize($famCrimeData->getParticipantIds());
                $k = array_search($userData->getId(), $newParticipants);
                unset($newParticipants[$k]);
                $deleted = $this->data->leaveFamilyCrime($famCrimeData->getId(), serialize($newParticipants));
                
                if($deleted == true)
                    return Routing::successMessage($l['LEFT_FAMILY_CRIME_AS_LAST']);
                else
                    return Routing::successMessage($l['LEFT_FAMILY_CRIME']);
            }
            elseif($start)
            {
                $mercs = $famCrimeData->getMercenariesReady();
                if($famCrimeData->getWithMercenaries() === true)
                    $this->data->useFamilyMercenariesInCrime($mercs);
                
                $chance = $security->randInt(0, 100);
                if($chance <= 70/** 50 **/)
                {
                    $vehicleService = new VehicleService();
                    switch($famCrimeData->getCrimeID())
                    {
                        case 1:
                            $randCar1 = $vehicleService->getRandomVehicleByLv(25);
                            $randCar2 = $vehicleService->getRandomVehicleByLv(25);
                            $randCar3 = $vehicleService->getRandomVehicleByLv(25);
                            break;
                        case 2:
                            $randCar1 = $vehicleService->getRandomVehicleByLv(50);
                            $randCar2 = $vehicleService->getRandomVehicleByLv(50);
                            $randCar3 = $vehicleService->getRandomVehicleByLv(50);
                            break;
                        case 3:
                            $randCar1 = $vehicleService->getRandomVehicleByLv(75);
                            $randCar2 = $vehicleService->getRandomVehicleByLv(75);
                            $randCar3 = $vehicleService->getRandomVehicleByLv(75);
                            break;
                    }
                    $garageData = $garageService->familyGarageOptions[$garageService->getFamilyGarageSize()];
                    $spaceLeftNum = $garageService->spaceLeftInFamilyGarage($garageData['space']);
                    
                    $stealChance = $security->randInt(0, 100);
                    if($stealChance >= 50 /** 50 **/) // Stolen 1 vehicle in successful crime...
                        if($spaceLeftNum >= 1) // ...to put in family garage
                            $msgSuccess = self::singleStolenVehicle($randCar1, $familyID);
                        else // ...to sell straight away
                            $msgSuccess = self::singleStolenVehicleSold($randCar1, $familyID);
                    
                    elseif($stealChance >= 20 && $stealChance < 50) // Stolen 2 vehicles
                        $msgSuccess = self::multipleStolenVehicles(array($randCar1, $randCar2), $spaceLeftNum, $familyID);
                    else // Stolen 3 vehicles
                        $msgSuccess = self::multipleStolenVehicles(array($randCar1, $randCar2, $randCar3), $spaceLeftNum, $familyID);
                    
                    $participantHp = is_array($participants) && !empty($participants) ? count($participants) : 1;
                    if(isset($mercs) && $mercs >= 2)
                        $participantHp += $mercs;
                    
                    foreach($participants AS $p)
                        $this->data->updateUserFamilyCrimeTime($p, 150, $participantHp, true);
                    
                    $this->data->deleteFamilyCrimeById($famCrimeData->getId());
                    
                    return Routing::successMessage($msgSuccess);
                }
                else
                {
                    $msgFail = $l['FAMILY_CRIME_FAILED']." ";
                    $inPrison = array();
                    foreach($participants AS $p)
                    {
                        $this->data->updateUserFamilyCrimeTime($p, 150);
                        
                        $prisonChance = $security->randInt(1,5);
                        if($prisonChance === 3)
                        {
                            $prison->putUserInPrison($p, (time()+90));
                            $inPrison[] = $userService->getUsernameById($p);
                        }
                    }
                    if(count($inPrison) > 0){
                        $msgFail .= implode(", ", $inPrison)." ".$l['ARE_ALSO_IMPRISONED'];
                    }
                    $this->data->deleteFamilyCrimeById($famCrimeData->getId());
                    
                    return Routing::errorMessage($msgFail);
                }
            }
        }
    }
}
