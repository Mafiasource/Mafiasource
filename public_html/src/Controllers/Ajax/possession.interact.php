<?PHP

use src\Business\UserService;
use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.ajax.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : null;
if( isset($id) && isset($_POST['security-token']) &&
    (
      (isset($_POST['buy'])) || (isset($_POST['drop'])) || (isset($_POST['transfer']) && isset($_POST['username'])) ||
      (isset($_POST['change-bullet-price']) && isset($_POST['bullet-price'])) || (isset($_POST['produce'])) ||
      (isset($_POST['buy-windows'])) || (isset($_POST['change-window-price']) && isset($_POST['window-price'])) ||
      (isset($_POST['change-stake']) && isset($_POST['stake'])) ||
      (isset($_POST['accept-transfer'])) || (isset($_POST['deny-transfer']))
    )
)
{
    $userService = new UserService();
    $possession = new PossessionService();
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    $bankMoneyBefore = $userDataBefore->getBank();
    
    $response = $possession->interactWithPossession($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    $bankMoneyAfter = $userDataAfter->getBank();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    if(isset($_POST['drop']) && $_POST['drop'] != 'false')
    {
        $twigVars['id'] = $id;
        print_r($twig->render('/src/Views/game/Ajax/possession.drop.twig', $twigVars));
    }
    else
        print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
