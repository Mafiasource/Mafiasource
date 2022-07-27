<?PHP

use src\Business\UserService;
use src\Business\MurderService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['username']) && isset($_POST['time']) && isset($_POST['hire-detective']))
{
    $userService = new UserService();
    $murder  = new MurderService();
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    
    $response = $murder->hireDetective($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
