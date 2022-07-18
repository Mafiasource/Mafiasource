<?PHP

use src\Business\UserService;
use src\Business\DonatorService;

$userService = new UserService();
$donator = new DonatorService();
$donationData = $donator->getDonationData(true);
if(isset($donationData))
{
    require_once __DIR__ . '/.inc.head.ajax.php';
    
    $response = $donator->leaveDonatorList();
    if(isset($_POST['security-token']) && isset($_POST['donator-list']))
        $response = $donator->donatorListApplication($_POST);
    
    $donatorList = $donator->getDonatorList();
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
                
    $twigVars['response'] = $response;
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->donationShopLangs()); // Extend base langs
    $twigVars['donatorList'] = $donatorList;
    
    print_r($twig->render('/src/Views/game/Ajax/donator-list.twig', $twigVars));
}
