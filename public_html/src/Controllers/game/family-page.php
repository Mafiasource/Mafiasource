<?PHP

use src\Business\FamilyService;
use src\Business\FamilyPropertyService;

require_once __DIR__ . '/.inc.head.php';

$family = new FamilyService();

$familyName = $route->requestGetParam(3);

if($family->checkFamilyExists($familyName) !== TRUE)
{
    $route->headTo("not_found");
    exit(0);
}
else
{
    $familyPage = $family->getFamilyPageDataByName($familyName);
    $famID = $familyPage->getId();
    $familyAlliances = $family->getFamilyPageAlliancesById($famID);
    $familyMembers = $family->getFamilyMembersByFamilyId($famID);
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->familyLangs()); // Extend base langs
$twigVars['langs']['FAMILY_PAGE_HEADING'] = $route->replaceMessagePart($familyName, $twigVars['langs']['FAMILY_PAGE_HEADING'], '/{familyName}/');
$twigVars['familyPage'] = $familyPage;
$twigVars['familyMembers'] = $familyMembers;
$twigVars['familyAlliances'] = $familyAlliances;

print_r($twig->render('/src/Views/game/family-page.twig', $twigVars));
