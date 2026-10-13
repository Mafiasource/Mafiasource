<?PHP

use src\Business\UserService;
use app\config\Routing;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST) && isset($_POST['security-token']))
{
    $userService = new UserService();
    if (APP_ISOLATED === true && isset($_POST['email-change']) === true) {
        $settingsLangs = $language->settingsLangs();
        $response = Routing::errorMessage($settingsLangs['EMAIL_UNAVAILABLE_ISOLATED']);
    } else {
        $response = $userService->changeAccountSettings($_POST, $_FILES);
    }
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/scroll-modal-top.twig', $twigVars));
}
