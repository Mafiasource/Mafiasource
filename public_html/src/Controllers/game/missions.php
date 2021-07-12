<?PHP

use src\Business\MissionService;
use src\Business\StateService;

require_once __DIR__ . '/.inc.head.php';

$mission = new MissionService();
$state = new StateService();

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->missionsLangs()); // Extend base langs
$twigVars['missions'] = $mission->getMissions();
$twigVars['missionTiers'] = $mission->missionTiers;
$twigVars['tierProgress'] = $mission->getMissionTiersAndProgress();
$twigVars['states'] = $state->getStates();

echo $twig->render('/src/Views/game/missions.twig', $twigVars);
