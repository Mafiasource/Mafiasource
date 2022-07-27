<?PHP

use src\Business\UserService;
use src\Business\GymCompetitionService;

require_once __DIR__ . '/.inc.head.php';

$commit = false;
switch($route->getRouteName())
{
    case 'gym-do':
        $commit = true;
        break;
}
$userService = new UserService();
$gymPage = $userService->getGymPageInfo();
$percent = ($gymPage->getPower() + $gymPage->getCardio()) / 2;
$waitingTimes = $userService->getGymTrainingTimesByPercent($percent);
$gymCompetition = new GymCompetitionService();

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->gymLangs()); // Extend base langs
$twigVars['gPage'] = $gymPage;
$twigVars['waitingTimes'] = $waitingTimes;
$twigVars['competitions'] = $gymCompetition->getOpenCompetitions();
if(isset($commit)) $twigVars['commit'] = $commit;

print_r($twig->render('/src/Views/game/gym.twig', $twigVars));
