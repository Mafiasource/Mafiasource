<?PHP

use src\Business\UserService;
use src\Business\CrimeService;
use src\Business\GarageService;

require_once __DIR__ . '/.inc.head.php';

$crime = new CrimeService();

$tab = "spontaneous";
$commit = false;
switch($route->getRouteName())
{
    case 'crimes':
        $tab = "spontaneous";
        $crimesPage = $crime->getCrimesPageInfo();
        break;
    case 'organized-crimes':
        $tab = "organized";
        $crimesPage = $crime->getCrimesPageInfo(true);
        $userService = new UserService();
        $friends = $userService->getFriendsList();
        $garage = new GarageService();
        $vehicles = $garage->getAllVehiclesInGarageByState($userData->getStateID());
        $weapons = $crime->weapons;
        $intel = $crime->intel;
        break;
    case 'crimes-do':
        $tab = "spontaneous";
        $crimesPage = $crime->getCrimesPageInfo();
        $commit = true;
        break;
    default:
        $tab = "spontaneous";
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
if(isset($crimesPage)) $twigVars['crimesPage'] = $crimesPage;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->crimesLangs()); // Extend base langs
if(isset($commit)) $twigVars['commit'] = $commit;
if(isset($friends)) $twigVars['friends'] = $friends;
if(isset($vehicles)) $twigVars['vehicles'] = $vehicles;
if(isset($weapons)) $twigVars['weapons'] = $weapons;
if(isset($intel)) $twigVars['intel'] = $intel;

print_r($twig->render('/src/Views/game/crimes.twig', $twigVars));
