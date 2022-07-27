<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.php';

include_once __DIR__ . '/.inc.statistics.php';

$userService = new UserService();
$reqPar3 = $route->requestGetParam(3);
$key = $reqPar3 == "key" ? $route->requestGetParam(4) : $reqPar3;
$changeEmailData = $userService->getChangeEmailDataByKey($key);

if($changeEmailData == FALSE) $route->headTo('not_found');
if(OFFLINE && !in_array($_SERVER['REMOTE_ADDR'], DEVELOPER_IPS)) $route->headTo('not_found');

$langs = array_merge($langs, $language->changeEmailLangs());

// Handle form
if(isset($_POST) && !empty($_POST) && isset($_POST['password']) && isset($_POST['security-token']))
{
    $response = $userService->validateEmailChange($_POST, $changeEmailData);
    if(is_bool($response) && $response === TRUE)
    {
        $route->createActionMessage($route->successMessage($langs['CHANGE_EMAIL_SUCCESS']));
        $route->headTo('home');
        exit(0);
    }
    $route->createActionMessage($route->errorMessage($response));
    header("HTTP/2 301 Moved Permanently");
    header('Location: ' . $route->getRoute(), TRUE, 301);
    exit(0);
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['changeEmailData'] = $changeEmailData;

// Render view
print_r($twig->render('/src/Views/change-email.twig', $twigVars));
