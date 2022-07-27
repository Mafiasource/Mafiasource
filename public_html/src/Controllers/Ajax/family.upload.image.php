<?PHP

use src\Business\FamilyService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(isset($_FILES) && isset($_POST['security-token']) && $famID > 0)
{
    $family = new FamilyService();
    
    $response = $family->uploadFamilyImage($_POST, $_FILES);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
