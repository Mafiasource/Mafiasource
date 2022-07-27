<?PHP

use src\Business\UserService;
use src\Business\EquipmentService;
use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.php';

$userService = new UserService();
$possession = new PossessionService();
$possessionId = 5; //Uitrusting winkel | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID(), $userData->getCityID()); // Possess table record id |Stad bezitting
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$tab = "weapons";
switch($route->getRouteName())
{
    default: case 'equipment-stores':
        $tab = "weapons";
        $table = "weapon";
        break;
    case 'equipment-stores-protection':
        $tab = $table = "protection";
        break;
    case 'equipment-stores-airplanes':
        $tab = "airplanes";
        $table = "airplane";
        break;
}
$equipmentService = new EquipmentService($table);
$equipmentPage = $equipmentService->getEquipmentPage();

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['table'] = $table;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->equipmentStoresLangs()); // Extend base langs
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;
$twigVars['count'] = $equipmentService->getRecordsCount();
$twigVars['equipment'] = $equipmentPage;
$twigVars['statusPage'] = $userService->getStatusPageInfo();

print_r($twig->render('/src/Views/game/equipment-stores.twig', $twigVars));
