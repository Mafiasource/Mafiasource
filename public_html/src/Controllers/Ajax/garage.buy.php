<?PHP

use src\Business\GarageService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(!empty($_POST['security-token']) && isset($_POST['type']))
{
    $garage = new GarageService();
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    $bankMoneyBefore = $userDataBefore->getBank();
    
    $response = $garage->buyGarageOption($_POST);
    
    $userDataAfter = $user->getUserData($lang);
    $cashMoneyAfter = $userDataAfter->getCash();
    $bankMoneyAfter = $userDataAfter->getBank();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    echo $twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars);
}
