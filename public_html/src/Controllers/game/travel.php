<?PHP

use src\Business\GarageService;
use src\Business\StateService;
use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.php';

$unableTo = 'travel';
if($lang == 'nl') $unableTo = 'reizen';
require_once ".redirect.if.in.prison.php";

$possession = new PossessionService();
$possessionId = 4; //Reisbureau | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID(), $userData->getCityID()); // Possess table record id
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$tab = "airplane";
switch($route->getRouteName())
{
    case 'travel-airplane':
        $tab = "airplane";
        break;
    case 'travel-train':
        $tab = "train";
        break;
    case 'travel-bus':
        $tab = "bus";
        break;
    case 'travel-vehicle':
    case 'travel-vehicle-id':
        $tab = "vehicle";
        $garage = new GarageService();
        $vehicles = $garage->getAllVehiclesInGarageByState($userData->getStateID());
        $selectedVehicle = $route->requestGetParam(4);
        break;
}

$state = new StateService();
$states = $state->getStates();
$cities = $states[0]->getCities();
$arrayKeys = array_keys($cities);
$cityTo = $cities[$arrayKeys[0]];
$price = $state->calculatePrice($userData->getCityID(), $cityTo->getId(), $tab);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['states'] = $states;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->travelLangs());
$twigVars['price'] = $price;
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;
if(isset($vehicles)) $twigVars['vehicles'] = $vehicles;
if(isset($selectedVehicle)) $twigVars['selectedVehicle'] = $selectedVehicle;

print_r($twig->render('/src/Views/game/travel.twig', $twigVars));
