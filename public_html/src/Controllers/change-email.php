<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.php';

include_once __DIR__ . '/.inc.statistics.php';

$userService = new UserService();
$key = $route->requestGetParam(3);
$changeEmailData = $userService->getChangeEmailDataByKey($key);

if($changeEmailData == FALSE) $route->headTo('not_found');
if(OFFLINE && !in_array($_SERVER['REMOTE_ADDR'], DEVELOPER_IPS)) $route->headTo('not_found');

$langs = array_merge($langs, $language->changeEmailLangs());

// Handle form
if(isset($_POST) && !empty($_POST) && isset($_POST['password']))
{
    $response = $userService->validateEmailChange($_POST, $changeEmailData);
    //var_dump($response);
    if(is_bool($response) && $response === TRUE)
    {
        $route->createActionMessage($route->successMessage($langs['CHANGE_EMAIL_SUCCESS']));
        $route->headTo('home');
    }
    else
    {
        $route->createActionMessage($route->errorMessage($response));
        header("HTTP/2 301 Moved Permanently");
        header('Location: ' . $route->getRoute(), TRUE, 301);
        exit(0);
    }
}

require_once __DIR__ . '/.inc.foot.php';

//$twigVars['langs'] = array_merge($twigVars['langs'], $language->changeEmailLangs()); // Done above already
$twigVars['changeEmailData'] = $changeEmailData;

// Render view
echo $twig->render('/src/Views/change-email.twig', $twigVars);
