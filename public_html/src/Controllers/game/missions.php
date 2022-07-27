<?PHP

use src\Business\PublicMissionService;
use src\Business\MissionService;
use src\Business\StateService;

require_once __DIR__ . '/.inc.head.php';

switch($route->getRouteName())
{
    default:
    case 'missions':
        $tab = "missions";
        $mission = new MissionService();
        $state = new StateService();
        break;
    case 'missions-public-mission':
        $tab = "public";
        $publicMission = new PublicMissionService();
        $futureHourDate = date('Y-m-d H:00:00', strtotime("+1 hour"));
        $futureHourTime = strtotime($futureHourDate);
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->missionsLangs()); // Extend base langs
if(isset($mission) && is_object($mission))
{
    $twigVars['missions'] = $mission->getMissions();
    $twigVars['missionTiers'] = $mission->missionTiers;
    $twigVars['tierProgress'] = $mission->getMissionTiersAndProgress();
}
if(isset($state) && is_object($state)) $twigVars['states'] = $state->getStates();
if(isset($publicMission) && is_object($publicMission))
{
    $twigVars['publicMission'] = $publicMission->getPublicMission();
    $twigVars['ranking'] = $publicMission->getPublicMissionRanking();
    $twigVars['futureHourTime'] = $futureHourTime;
}

print_r($twig->render('/src/Views/game/missions.twig', $twigVars));
