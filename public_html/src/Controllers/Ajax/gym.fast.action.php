<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['fastAction']) && isset($_POST['fastActionID']) && isset($_POST['securityToken']))
{
    $userService = new UserService();
    $response = $userService->gymChangeFastAction($_POST);

    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
