<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['member']))
{
    $userService = new UserService();
    
    $cashMoneyBefore = $userData->getCash();
    
    $response = $userService->healMember($_POST);
    
    $cashMoneyAfter = $user->getUserData()->getCash();

    require_once __DIR__ . '/.moneyAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
