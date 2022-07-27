<?PHP

use src\Business\UserService;
use src\Business\FamilyRaidService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && isset($_POST['driver']) && isset($_POST['bombExpert']) && isset($_POST['weaponExpert']) && $famID > 0)
{
    $userService = new UserService();
    $famRaid = new FamilyRaidService();
    
    $response = $famRaid->organizeFamilyRaid($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
