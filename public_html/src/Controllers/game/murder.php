<?PHP

use src\Business\UserService;
use src\Business\PossessionService;
use src\Business\MurderService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

$userService = new UserService();
$murder = new MurderService();

$commit = false;
$tab = "murder";
switch($route->getRouteName())
{
    default: case 'murder':
        $tab = "murder";
        $username = $route->requestGetParam(4);
        break;
    case 'murder-backfire':
        $tab = "backfire";
        $bfSettings = $murder->getBackfireSettings();
        break;
    case 'murder-detective':
        $tab = "detective";
        $possession = new PossessionService();
        $possessionId = 11; //Detectieve desk | Possession logic
        $possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID(), $userData->getCityID()); // Possess table record id |Stad bezitting
        $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
        $hourCosts = $murder->getDetectivreHourCosts();
        $detectives = $murder->getDetectives();
        break;
    case 'murder-mercenaries':
        $tab = "mercenaries";
        break;
    case 'murder-weapon-training':
        $tab = "weapon-training";
        break;
    case 'weapon-training-do':
        $tab = "weapon-training";
        $commit = true;
        break;
    case 'murder-logs':
    case 'murder-logs-page':
        $tab = "logs";
        $pagination = new Pagination($murder, 15, 15);
        $logs = $murder->getMurderLog($pagination->from, $pagination->to);
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->murderLangs()); // Extend base langs
if(isset($logs)) $twigVars['langs'] = array_merge($twigVars['langs'], $language->murderLogLangs()); // Extend base langs
$twigVars['statusPage'] = $userService->getStatusPageInfo();
if(isset($username)) $twigVars['username'] = $username;
if(isset($commit)) $twigVars['commit'] = $commit;
if(isset($bfSettings)) $twigVars['bf'] = $bfSettings;
if(isset($hourCosts)) $twigVars['hourCosts'] = $hourCosts;
if(isset($detectives)) $twigVars['detectives'] = $detectives;
if(isset($possessId)) $twigVars['possessId'] = $possessId;
if(isset($pData)) $twigVars['possessionData'] = $pData;
if(isset($logs)) $twigVars['logs'] = $logs;
if(isset($pagination)) $twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/game/murder.twig', $twigVars));
