<?PHP

use src\Business\DonatorService;
use src\Business\FamilyService;

require_once __DIR__ . '/.inc.head.php';

$donator = new DonatorService();
$family = new FamilyService();
$familyData = $family->getFamilyDataByName($userData->getFamily());

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->donationShopLangs()); // Extend base langs
$twigVars['familyData'] = $familyData;
$twigVars['luckyboxAmnt'] = $donator->luckyboxAmnt;
$twigVars['luckyboxCr'] = $donator->luckyboxCr;
$twigVars['shopData'] = $donator->getDonationShopData();

print_r($twig->render('/src/Views/game/donation-shop.twig', $twigVars));
