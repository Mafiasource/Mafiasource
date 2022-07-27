<?PHP

use src\Business\PrisonService;

require_once __DIR__ . '/.inc.head.ajax.php';

$allowedActions = array("buy-out", "break-out");

if(isset($_POST['action']) && in_array($_POST['action'], $allowedActions))
{
    $cashMoneyBefore = $userData->getCash();
    $prison = new PrisonService();
    $response = $prison->handleAction($_POST);
    if(is_array($response) && isset($response['error']))
    {
        require_once __DIR__ . '/.inc.foot.ajax.php';
        $twigVars['response'] = $response['error'];
    }
    else
    {
        //Success en nodige succes functies triggeren..
        $userDataAfter = $user->getUserData();
        $cashMoneyAfter = $userDataAfter->getCash();
        require_once __DIR__ . '/.moneyAnimation.php';
        
        require_once __DIR__ . '/.inc.foot.ajax.php';
        $twigVars['response'] = $response;
    }
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
