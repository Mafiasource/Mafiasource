<?PHP

use src\Business\FamilyService;

require_once __DIR__ . '/.inc.head.php';

$family = new FamilyService();
$familyData = $family->getFamilyDataByName($userData->getFamily());

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->donationShopLangs()); // Extend base langs
$twigVars['familyData'] = $familyData;

echo $twig->render('/src/Views/game/donation-shop.twig', $twigVars);
