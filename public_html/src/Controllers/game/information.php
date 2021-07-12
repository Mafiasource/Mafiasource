<?PHP

use src\Business\UserService;
use src\Business\CMSService;
use src\Business\StatisticService;

require_once __DIR__ . '/.inc.head.php';

$userService = new UserService();
$cms = new CMSService();
$rules = $cms->getCMSById(8, $lang);

$tab = "statistics";
switch($route->getRouteName())
{
    default: case 'information':
        $tab = "statistics";
        $statistic = new StatisticService();
        $statistics = $statistic->getStatisticsPage();
        break;
    case 'information-rules':
        $tab = "rules";
        break;
    case 'information-team-members':
        $tab = "team-members";
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->informationLangs()); // Extend base langs
if(isset($tab) && $tab == "rules") $twigVars['rules'] = $rules;
if(isset($tab) && $tab == "team-members") $twigVars['teamMembers'] = $userService->getTeamMembers();
if(isset($statistics) && $tab == "statistics") $twigVars['stats'] = $statistics;

echo $twig->render('/src/Views/game/information.twig', $twigVars);
