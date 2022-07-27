<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['exchange-amount']) && isset($_POST['security-token']))
{
    $userService = new UserService();
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    $bankMoneyBefore = $userDataBefore->getBank();
    $valueBefore = $userDataBefore->getHonorPoints();
    
    $response = $userService->exchangeHonorPoints($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    $bankMoneyAfter = $userDataAfter->getBank();
    $valueAfter = $userDataAfter->getHonorPoints();
    
    $element = "#userHonorPoints";
    
    require_once __DIR__ . '/.moneyAnimation.php';
    include_once __DIR__ . '/.valueAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
