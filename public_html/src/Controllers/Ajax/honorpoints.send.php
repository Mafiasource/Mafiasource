<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['amount']) && isset($_POST['security-token']))
{
    $userService = new UserService();
    
    $valueBefore = $userData->getHonorPoints();
    
    $response = $userService->sendHonorPoints($_POST);
    
    $valueAfter = $user->getUserData()->getHonorPoints();
    $element = "#userHonorPoints";
    
    include_once __DIR__ . '/.valueAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
