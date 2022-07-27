<?PHP

use src\Business\StateService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['type']) && isset($_POST['fromCity']) && isset($_POST['toCity']))
{
    $state = new StateService();
    $response = $state->calculatePrice($_POST['fromCity'], $_POST['toCity'], $_POST['type']);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
