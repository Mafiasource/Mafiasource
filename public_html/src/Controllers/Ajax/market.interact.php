<?PHP

use src\Business\MarketService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(!empty($_POST['security-token']) && isset($_POST['id']))
{
    $market = new MarketService();
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    $bankMoneyBefore = $userDataBefore->getBank();
    
    $response = $market->buyOrAcceptMarketItem($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    $bankMoneyAfter = $userDataAfter->getBank();
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    $twigVars['colspan'] = 5;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.in-table.response.twig', $twigVars));
    
    require_once __DIR__ . '/.moneyAnimation.php'; 
}
