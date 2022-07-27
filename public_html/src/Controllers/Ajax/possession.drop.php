<?PHP

use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['id']) && isset($_POST['security-token']))
{
    $possession = new PossessionService();
    
    $response = $possession->dropPossessionConfirmed($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
