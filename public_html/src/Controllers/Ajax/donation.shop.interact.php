<?PHP

use src\Business\DonatorService;
use src\Business\FamilyService;

require_once __DIR__ . '/.inc.head.ajax.php';

$orCheck = false;
$orKeys = array("donator", "vip", "gold-member", "vip-family", "luckybox", "halving-times", "bribing-police", "ground", "smuggling-capacity", "new-profession", "new-name");
foreach($orKeys AS $key)
{
    if(array_key_exists($key, $_POST) && isset($_POST[$key]))
        $orCheck = true;
    
    if($key == "new-profession" && !isset($_POST['profession']))
        $orCheck = false;
}

if(isset($_POST['security-token']) && $orCheck)
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
    $twigVars['shopData'] = $donator->getDonationShopData();
    
    print_r($twig->render('/src/Views/game/Ajax/donation-shop.twig', $twigVars));
}
