<?PHP

//Opgelet, ajax controller zowel voor garage als voor vehicle shop.

use src\Business\GarageService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(!empty($_POST['securityToken']) && isset($_POST['action']) && isset($_POST['id']))
{
    $garage = new GarageService();
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    $bankMoneyBefore = $userDataBefore->getBank();
    
    $response = $garage->interactWithVehicle($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    $bankMoneyAfter = $userDataAfter->getBank();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    echo $twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars);
}
