<?PHP

use src\Business\StealVehicleService;

require_once __DIR__ . '/.inc.head.php';

$sv = new StealVehicleService();
$svPage = $sv->getStealVehiclesPageInfo();
$commit = false;
if($route->getRouteName() == "steal-vehicles-do") $commit = true;

require_once __DIR__ . '/.inc.foot.php';

if(isset($svPage)) $twigVars['svPage'] = $svPage;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->stealVehiclesLangs()); // Extend base langs
if(isset($commit)) $twigVars['commit'] = $commit;


print_r($twig->render('/src/Views/game/steal-vehicles.twig', $twigVars));
