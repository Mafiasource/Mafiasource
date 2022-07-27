<?PHP

use src\Business\UserService;
use src\Business\EquipmentService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['table']) && in_array($_POST['table'], array('weapon', 'protection', 'airplane'))
  && isset($_POST['id']) &&  (isset($_POST['buy']) || isset($_POST['sell']) || isset($_POST['equip']))
)
{
    $userService = new UserService();
    
    $cashMoneyBefore = $userData->getCash();
    
    $equipment = new EquipmentService($_POST['table']);
    if(isset($_POST['buy']))
        $response = $equipment->buyEquipment($_POST);
    else if(isset($_POST['sell']))
        $response = $equipment->sellEquipment($_POST);
    else if(isset($_POST['equip']))
        $response = $equipment->equipEquipment($_POST);
    
    $cashMoneyAfter = $user->getUserData()->getCash();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/scroll-top.twig', $twigVars));
}
