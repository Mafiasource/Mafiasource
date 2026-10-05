<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.php';

if(isset($userData) && !empty($userData)) $route->headTo('game');
if(OFFLINE && !in_array($_SERVER['REMOTE_ADDR'], DEVELOPER_IPS)) $route->headTo('not_found');

require_once __DIR__ . '/.inc.statistics.php';

// Handle login
if (
    $_POST !== array()
    && isset($_POST['username']) === true
    && isset($_POST['password']) === true
    && isset($_POST['security-token']) === true
    && isset($_POST['cf-turnstile-response']) === true
)
{
    $userService = new UserService();
    $response = $userService->validateLogin($_POST);
    if(is_bool($response) && $response === true)
    {
        $security->generateNewToken();
        $security->generateNewSession();
        $route->headTo('game');
        exit(0);
    }
    $route->createActionMessage($route->errorMessage($response));
    $route->headTo('login');
    exit(0);
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->loginLangs());

// Render view
print_r($twig->render('/src/Views/login.twig', $twigVars));
