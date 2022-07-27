<?PHP

use src\Business\UserService;
use src\Business\SeoService;

require_once __DIR__ . '/.inc.head.php';

require_once __DIR__ . '/.inc.sliders.php';

if(isset($userData) && !empty($userData)) $route->headTo('game');
if(OFFLINE && !in_array($_SERVER['REMOTE_ADDR'], DEVELOPER_IPS)) $route->headTo('not_found');

$userService = new UserService();
$reqPar3 = $route->requestGetParam(3);
$referralLink = $reqPar3 == "referral" ? $route->requestGetParam(4) : $reqPar3;
$referral = isset($_SESSION['register']['referral']) ? $_SESSION['register']['referral'] : $referralLink;
if(isset($_SESSION['register']['referral']) && $referralLink != false && $_SESSION['register']['referral'] != $referralLink) $referral = $referralLink;
if(strpos($referral, '?')) $referral = substr($referral, 0, strpos($referral, "?"));
if(!is_object($userService->getUserProfile($referral))) $referral = false;
if($referral != false) $_SESSION['register']['referral'] = $referral;

// Honeypotted
$blockPost = false;
if(isset($_POST['referral-username']))
{
    if(isset($_POST['email']) && !empty($_POST['email']))
        $blockPost = $_POST['email'];
    
    $_POST['email'] = $_POST['referral-username'];
    unset($_POST['referral-username']);
}
if(
    isset($_POST) && !empty($_POST) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) &&
    isset($_POST['password_check']) /*&& isset($_POST['captcha_code'])*/ && isset($_POST['type']) && isset($_POST['security-token']) && $blockPost === false
)
{
    $response = $userService->validateRegister($_POST);
    if(is_bool($response) && $response === true)
    {
        $l = $language->registerLangs();
        $security->generateNewToken();
        $security->generateNewSession();
        $route->createActionMessage($route->successMessage($l['REGISTERED_SUCCESSFUL']));
        $route->headTo('game');
        exit(0);
    }
    $route->createActionMessage($route->errorMessage($response));
    $route->headTo('register');
    exit(0);
}
elseif($blockPost !== false) //Honeypot
{
    $route->createActionMessage($route->errorMessage($langs['INVALID_SECURITY_TOKEN']));
    $route->headTo('register');
    exit(0);
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->registerLangs());
if(isset($_SESSION['register']['username'])) $twigVars['regUsername'] = $_SESSION['register']['username'];
if(isset($_SESSION['register']['email'])) $twigVars['regEmail'] = $_SESSION['register']['email'];
if(isset($_SESSION['register']['type'])) $twigVars['regType'] = $_SESSION['register']['type'];
if(isset($referral)) $twigVars['referral'] = $referral;

// Render view
print_r($twig->render('/src/Views/register.twig', $twigVars));
