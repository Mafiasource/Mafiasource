<?PHP

use src\Business\DonatorService;

if(isset($_POST))
{
    require_once __DIR__ . '/.inc.head.ajax.php';

    $donator = new DonatorService();
    
    $acceptPayPalPost = true;
    $payPalPost = array("amt", "cc", "cm", "item_name", "item_number", "st", "tx");
    foreach($payPalPost AS $ppp)
    {
        if(!isset($_POST[$ppp]))
            $acceptPayPalPost = false;
    }
    
    if(!$acceptPayPalPost && isset($_POST['security-token']) && isset($_POST['donate']))
        $response = $donator->donate($_POST);
    elseif($acceptPayPalPost)
        $response = $donator->validateDonation($_POST);
    else
        exit(1);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
                
    $twigVars['response'] = $response;
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->donationShopLangs()); // Extend base langs
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
