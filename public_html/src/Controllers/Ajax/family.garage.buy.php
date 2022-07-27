<?PHP

use src\Business\GarageService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && isset($_POST['type']) && $famID > 0)
{
    $garage = new GarageService();
    
    $response = $garage->buyGarageOption($_POST, $famID);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
