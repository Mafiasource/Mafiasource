<?PHP

use src\Business\UserService;
use src\Business\FamilyService;
use src\Business\FamilyPropertyService;
use src\Business\RedLightDistrictService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($userData->getFamilyID() == 0)
{
    $route->headTo('family-list');
    exit(0);
}

$hasRights = FALSE;
if($userData->getFamilyBoss() === true || $userData->getFamilyBankmanager() === true) $hasRights = TRUE;

$famProperty = new FamilyPropertyService();

$tab = "bullet-factory";
switch($route->getRouteName())
{
    default:
    case 'family-properties':
    case 'family-properties-page':
        $tab = "bullet-factory";
        $userService = new UserService();
        $family = new FamilyService();
        $pagination = new Pagination($famProperty, 15, 15);
        $pageInfo = $famProperty->getFamilyBulletFactoryPageInfo($pagination->from, $pagination->to, $hasRights);
        $capacity = $famProperty->bfCapacities[$pageInfo['bf']->getBulletFactory()];
        $productions = $famProperty->getBulletFactoryProductionsByLevel($pageInfo['bf']->getBulletFactory());
        $productionCosts = $famProperty->bfProductionPrices;
        $statusData = $userService->getStatusPageInfo();
        $familyMembers = $family->getFamilyMembersByFamilyId($userData->getFamilyID());
        break;
    case 'family-properties-brothel':
        $tab = "brothel";
        $rld = new RedLightDistrictService();
        $pageInfo = $famProperty->getFamilyBrothelPageInfo();
        $capacity = $famProperty->brothelCapacities[$pageInfo['brothel']->getBrothel()];
        $streetWhores = $rld->getRedLightDistrictPageInfo()->getWhoresStreet();
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->familyPropertiesLangs()); // Extend base langs
$twigVars['pageInfo'] = $pageInfo;
$twigVars['hasRights'] = $hasRights;
$twigVars['price'] = $famProperty->price;
$twigVars['capacity'] = $capacity;
$twigVars['upgradePrices'] = $famProperty->upgradePrices;
if(isset($pagination)) $twigVars['pagination'] = $pagination;
if(isset($productions)) $twigVars['productions'] = $productions;
if(isset($productionCosts)) $twigVars['productionCosts'] = $productionCosts;
if(isset($statusData)) $twigVars['statusData'] = $statusData;
if(isset($familyMembers)) $twigVars['familyMembers'] = $familyMembers;
if(isset($streetWhores)) $twigVars['streetWhores'] = $streetWhores;

print_r($twig->render('/src/Views/game/family-properties.twig', $twigVars));
