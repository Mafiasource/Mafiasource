<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.php';

$userService = new UserService();
$tab = "honor-points";
switch($route->getRouteName())
{
    case 'honor-points':
        $tab = "honor-points";
        $exchangeList = $userService->getExchangeListHP($lang);
        $hpLogs = $userService->getHonorPointLogs();
        break;
    case 'send-honor-points':
        $tab = "send-honor-points";
        break;
    default:
        $tab = "honor-points";
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->honorPointsLangs()); // Extend base langs
$twigVars['langs']["YOU_HAVE_X_HONOR_POINTS"] = $route->replaceMessagePart(
    number_format($userService->getStatusPageInfo()->getHonorPoints(), 0, '', ','),
    $twigVars['langs']["YOU_HAVE_X_HONOR_POINTS"], '/{honorPoints}/'
);
if(isset($exchangeList)) $twigVars['exchangeList'] = $exchangeList;
if(isset($hpLogs)) $twigVars['hpLogs'] = $hpLogs;

print_r($twig->render('/src/Views/game/honor-points.twig', $twigVars));
