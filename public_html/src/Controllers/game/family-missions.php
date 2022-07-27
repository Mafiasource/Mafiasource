<?PHP

require_once __DIR__ . '/.inc.head.php';

require_once __DIR__ . '/.inc.foot.php';

//$twigVars['langs'] = array_merge($twigVars['langs'], $language->statusLangs()); // Extend base langs

print_r($twig->render('/src/Views/game/family-missions.twig', $twigVars));
