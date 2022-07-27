<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.ajax.php';

if((isset($_POST['friend']) || isset($_POST['user'])) && isset($_POST['security-token']) && (isset($_POST['deny']) || isset($_POST['accept']) || isset($_POST['delete']) || isset($_POST['delete-confirm']) || isset($_POST['delete-block'])))
{
    $userService = new UserService();
    $response = $userService->interactFriendsList($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
