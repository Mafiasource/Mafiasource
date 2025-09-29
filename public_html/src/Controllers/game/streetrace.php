<?PHP

use src\Business\GarageService;
use src\Business\StreetraceService;

require_once __DIR__ . '/.inc.head.php';

$garage = new GarageService();
$vehicles = $garage->getAllVehiclesInGarageByState($userData->getStateID());
$streetrace = new StreetraceService();
$overview = $streetrace->getOverview();

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->streetraceLangs());
$twigVars['vehicles'] = $vehicles;
$twigVars['raceTypes'] = $streetrace->raceTypes;
$twigVars['playerOptions'] = $streetrace->playerOptions;
$twigVars['openRaces'] = $overview['openRaces'];
$twigVars['userRace'] = $overview['userRace'];
$twigVars['lastResult'] = $overview['lastResult'];

print_r($twig->render('/src/Views/game/streetrace.twig', $twigVars));
