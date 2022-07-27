<?PHP

require_once __DIR__ . '/.inc.head.php';

if($userData->getFamilyID() > 0)
{
    $route->headTo('family-list');
    exit(0);
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->familyLangs()); // Extend base langs

print_r($twig->render('/src/Views/game/family-create.twig', $twigVars));
