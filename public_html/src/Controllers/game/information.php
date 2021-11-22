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
    case 'information-hall-of-fame':
        $tab = "hall-of-fame";
        $statistic = new StatisticService();
        $statistics = $statistic->getStatisticsPage();
        $hallOfFame = $statistic->getHallOfFamePage();
        $rounds = $statistic->getHallOfFameRounds();
        break;
    case 'information-hall-of-fame-round':
        $tab = "hall-of-fame";
        $round = (int) $route->requestGetParam(4, array('min' => 0, 'max' => 1)); // Increase max with total rounds
        $statistic = new StatisticService();
        $statistics = $statistic->getStatisticsPage($round);
        $hallOfFame = $statistic->getHallOfFamePage($round);
        $rounds = $statistic->getHallOfFameRounds();
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->informationLangs()); // Extend base langs
if(isset($tab) && $tab == "rules") $twigVars['rules'] = $rules;
if(isset($tab) && $tab == "team-members") $twigVars['teamMembers'] = $userService->getTeamMembers();
if(isset($statistics) && $tab == "statistics" || $tab == "hall-of-fame") $twigVars['stats'] = $statistics;
if(isset($hallOfFame) && $tab == "hall-of-fame") $twigVars['hof'] = $hallOfFame;
if(isset($rounds) && $tab == "hall-of-fame") $twigVars['rounds'] = $rounds;
if(isset($round) && $tab == "hall-of-fame") $twigVars['round'] = (string) $round;

echo $twig->render('/src/Views/game/information.twig', $twigVars);
