<?PHP

use src\Business\FamilyMercenaryService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

$hasRights = $hasBossRights = FALSE;
if($userData->getFamilyBoss() === true || $userData->getFamilyUnderboss() === true) $hasRights = TRUE;

if($userData->getFamilyID() == 0 || $hasRights === FALSE)
{
    $route->headTo('family-list');
    exit(0);
}

$famMercService = new FamilyMercenaryService();

$pagination = new Pagination($famMercService, 15, 15);
$pageInfo = $famMercService->getPageInfo($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->familyMercenariesLangs()); // Extend base langs
$twigVars['langs']['MERCENARIES_INFO'] = $route->replaceMessagePart(number_format($famMercService->price, 0, '', ','), $twigVars['langs']['MERCENARIES_INFO'], '/{price}/');
$twigVars['pageInfo'] = $pageInfo;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/game/family-mercenaries.twig', $twigVars));
