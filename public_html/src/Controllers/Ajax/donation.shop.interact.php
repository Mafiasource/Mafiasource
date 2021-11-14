<?PHP

use src\Business\DonatorService;
use src\Business\FamilyService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && (isset($_POST['donator']) || isset($_POST['vip']) || isset($_POST['gold-member']) || isset($_POST['vip-family']) || isset($_POST['luckybox'])))
{
    $donator = new DonatorService();
    $family = new FamilyService();
    
    $response = $donator->interactDonationShop($_POST);
    
    $userData = $user->getUserData(); // re-set & get userData
    $familyData = $family->getFamilyDataByName($userData->getFamily());
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->donationShopLangs()); // Extend base langs
    $twigVars['response'] = $response;
    $twigVars['familyData'] = $familyData;
    $twigVars['luckyboxAmnt'] = $donator->luckyboxAmnt;
    $twigVars['luckyboxCr'] = $donator->luckyboxCr;
    
    echo $twig->render('/src/Views/game/Ajax/donation-shop.twig', $twigVars);
}
