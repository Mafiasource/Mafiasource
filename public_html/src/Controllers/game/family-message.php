<?PHP

use src\Business\FamilyService;

require_once __DIR__ . '/.inc.head.php';

if($userData->getFamilyID() == 0)
{
    $route->headTo('family-list');
    exit(0);
}

$hasRights = $hasBossRights = FALSE;
if($userData->getFamilyBoss() === true || $userData->getFamilyUnderboss() === true) $hasRights = TRUE;

$family = new FamilyService();
$familyMessage = $family->getFamilyMessage();


require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->familyLangs()); // Extend base langs
$twigVars['hasRights'] = $hasRights;
$twigVars['familyMessage'] = $familyMessage;

print_r($twig->render('/src/Views/game/family-message.twig', $twigVars));
