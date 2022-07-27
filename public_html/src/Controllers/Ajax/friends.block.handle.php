<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['username']) && isset($_POST['security-token']) && (isset($_POST['invite']) || isset($_POST['block'])))
{
    $userService = new UserService();
    $response = $userService->handleFriendsBlock($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
