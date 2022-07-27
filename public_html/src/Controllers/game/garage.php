<?PHP

use src\Business\GarageService;
use src\Business\PossessionService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

$stateID = $userData->getStateID();

$possession = new PossessionService();
$possessionId = 8; //Garage | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId, $stateID); // Possess table record id |Staat bezitting
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$garage = new GarageService();

$tab = "vehicles";
switch($route->getRouteName())
{
    default: case 'garage': case 'garage-page':
        $tab = "vehicles";
        $hasGarage = $garage->hasGarageInState($stateID);
        $garageOptions = $garage->getGarageOptions();
        $pagination = new Pagination($garage, 5, 5);
        $vehicles = $garage->getVehiclesInGarageByState($stateID,$pagination->from, $pagination->to);
        if($hasGarage != FALSE)
        {
            $garageData = $garage->garageOptions[$garage->getGarageSizeByState($stateID)];
            $spaceLeft = $garage->hasSpaceLeftInGarage($stateID);
            if($spaceLeft != FALSE) $spaceLeftNum = $garage->spaceLeftInGarage($stateID, $garageData['space']);
        }
        break;
    case 'garage-shop': case 'garage-shop-page': case 'garage-shop-vehicle-raw':
        $tab = "shop";
        $possessionId = 9; //Voertuig Handelszaak | Possession logic
        $possessId = $possession->getPossessIdByPossessionId($possessionId, $stateID, $userData->getCityID()); // Possess table record id |City
        $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
        $pagination = new Pagination($garage, 6, 6);
        $vehicles = $garage->getVehiclesInShop($pagination->from, $pagination->to);
        break;
    case 'garage-shop-vehicle':
        $tab = "shop";
        $possessionId = 9; //Voertuig Handelszaak | Possession logic
        $possessId = $possession->getPossessIdByPossessionId($possessionId, $stateID, $userData->getCityID()); // Possess table record id |City
        $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
        $vehicleID = $route->requestGetParam(6, array('min' => 1, 'max' => 116));
        $vehicle = $garage->getShopVehicleInfo($vehicleID);
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->garageLangs()); // Extend base langs
if(isset($hasGarage))
{
    $twigVars['hasGarage'] = $hasGarage;
    $twigVars['garageOptions'] = $garageOptions;
    if(isset($garageData)) $twigVars['garageData'] = $garageData;
    if(isset($spaceLeft))
    {
        $twigVars['spaceLeft'] = $spaceLeft;
        if($spaceLeft == FALSE)
        {
            $twigVars['langs']['NO_SPACE_LEFT_GARAGE_IN_STATE'] = $route->replaceMessagePart($userData->getState(), $twigVars['langs']['NO_SPACE_LEFT_GARAGE_IN_STATE'], '/{state}/');
        }
        else
        {
            $twigVars['langs']['X_SPACE_LEFT_GARAGE_IN_STATE'] = $route->replaceMessagePart($spaceLeftNum, $twigVars['langs']['X_SPACE_LEFT_GARAGE_IN_STATE'], '/{x}/');
            $twigVars['langs']['X_SPACE_LEFT_GARAGE_IN_STATE'] = $route->replaceMessagePart($userData->getState(), $twigVars['langs']['X_SPACE_LEFT_GARAGE_IN_STATE'], '/{state}/');
        }
    }
}
if(isset($pagination)) $twigVars['pagination'] = $pagination;
if(isset($vehicles)) $twigVars['vehicles'] = $vehicles;
if(isset($vehicle)) $twigVars['vehicle'] = $vehicle;
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;

print_r($twig->render('/src/Views/game/garage.twig', $twigVars));
