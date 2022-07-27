<?PHP

use src\Business\UserService;
use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.php';

$userService = new UserService();
$possession = new PossessionService();
$possessionId = 3; //Mafiasource bank | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID()); // Possess table record id
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$tab = "bank";
switch($route->getRouteName())
{
    case 'bank':
        $tab = "bank";
        break;
    case 'swiss-bank':
        $tab = "swiss-bank";
        break;
    case 'financial':
        $tab = "financial";
        break;
    case 'bank-logs':
        $tab = "bank-logs";
        break;
    default:
        $tab = "bank";
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->bankLangs()); // Extend base langs
$twigVars['bankData'] = $userService->getBankPageInfo();
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;

print_r($twig->render('/src/Views/game/bank.twig', $twigVars));
