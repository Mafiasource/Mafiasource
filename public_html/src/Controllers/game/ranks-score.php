<?PHP

require_once __DIR__ . '/.inc.head.php';

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->ranksScoreLangs()); // Extend base langs

echo $twig->render('/src/Views/game/ranks-score.twig', $twigVars);
