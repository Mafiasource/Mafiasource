<?PHP

use src\Business\FamilyService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['family']) && isset($_POST['security-token']))
{
    $family = new FamilyService();
    
    $response = $family->leaveFamily($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
