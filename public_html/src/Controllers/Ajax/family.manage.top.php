<?PHP

use src\Business\UserService;
use src\Business\FamilyService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && (isset($_POST['boss']) || isset($_POST['boss-confirm']) || isset($_POST['bankmanager']) || isset($_POST['underboss']) || isset($_POST['forummod'])) && $famID > 0 )
{
    $userService = new UserService();
    $family = new FamilyService();
    
    $response = $family->manageFamilyTop($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
