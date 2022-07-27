<?PHP

use src\Business\FamilyService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && isset($_POST['leave-costs']) && isset($_POST['manage-leave-costs']) && $famID > 0)
{
    $family = new FamilyService();
    
    $response = $family->manageFamilyLeaveCosts($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
