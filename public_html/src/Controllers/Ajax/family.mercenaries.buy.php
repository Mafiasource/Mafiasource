<?PHP

use src\Business\FamilyMercenaryService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && isset($_POST['mercenaries']) && $famID > 0)
{
    $famMerc = new FamilyMercenaryService();
    
    $response = $famMerc->buyMercenaries($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
