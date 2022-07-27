<?PHP

use src\Business\DailyChallengeService;

require_once __DIR__ . '/.inc.head.php';

$dc = new DailyChallengeService();
$challenges = $dc->getDailyChallenges();
$luckies = $dc->getLuckyboxCombo();

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->dailyChallengesLangs()); // Extend base langs
$twigVars['langs']['DAILY_INFO'] = $route->replaceMessagePart($luckies, $twigVars['langs']['DAILY_INFO'], '/{luckies}/');
$twigVars['challenges'] = $challenges;

print_r($twig->render('/src/Views/game/daily-challenges.twig', $twigVars));
