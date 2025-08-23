<?PHP

use src\Business\GarageService;
use src\Business\StreetraceService;

require_once __DIR__ . '/.inc.head.php';

$garage = new GarageService();
$vehicles = $garage->getAllVehiclesInGarageByState($userData->getStateID());
$streetrace = new StreetraceService();

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->streetraceLangs());
$twigVars['vehicles'] = $vehicles;
$twigVars['raceTypes'] = $streetrace->raceTypes;

print_r($twig->render('/src/Views/game/streetrace.twig', $twigVars));
