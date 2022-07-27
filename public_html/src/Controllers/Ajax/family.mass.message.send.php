<?PHP

use src\Business\UserService;
use src\Business\FamilyService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && isset($_POST['mass-message']) && isset($_POST['send-mass-message']) && $famID > 0)
{
    $userService = new UserService();
    $family = new FamilyService();
    
    $response = $family->sendFamilyMassMessage($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    $twigVars['colspan'] = 2;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.in-table.response.twig', $twigVars));
}
