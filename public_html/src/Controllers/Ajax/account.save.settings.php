<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST) && isset($_POST['security-token']))
{
    $userService = new UserService();
    $response = $userService->changeAccountSettings($_POST, $_FILES);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/scroll-modal-top.twig', $twigVars));
}
