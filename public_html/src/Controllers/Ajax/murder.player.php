<?PHP

use src\Business\UserService;
use src\Business\MurderService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['victim']) && isset($_POST['murder-player']))
{
    $userService = new UserService();
    $murder  = new MurderService();
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    $bankMoneyBefore = $userDataBefore->getBank();
    $healthBefore = $userDataBefore->getHealth();
    
    $response = $murder->murderPlayer($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    $bankMoneyAfter = $userDataAfter->getBank();
    $healthAfter = $userDataAfter->getHealth();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    if($healthAfter != $healthBefore) $response['alert']['message'] .= "<script>$('#healthBar .progress-bar').css('width', '".$healthAfter."%');</script>";
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
