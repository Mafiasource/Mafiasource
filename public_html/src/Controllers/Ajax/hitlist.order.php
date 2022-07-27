<?PHP

use src\Business\UserService;
use src\Business\HitlistService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['username']) && isset($_POST['prize']) && isset($_POST['reason']) && isset($_POST['order-hitlist-record']))
{
    $userService = new UserService();
    $hitlist = new HitlistService();
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    
    $response = $hitlist->orderHitlistRecord($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
