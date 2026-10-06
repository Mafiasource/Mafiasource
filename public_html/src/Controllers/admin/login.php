<?PHP
use src\Business\MemberService;

$member = new MemberService();
$member->redirectIfLoggedIn();

if (isset($_POST['submit-login']) === true && (LOGIN_TURNSTILE_ENABLED === false || isset($_POST['cf-turnstile-response']) === true))
{
    $remember = "";
    if (isset($_POST['remember']) === true) $remember = $_POST['remember'];
    $turnstileToken = $_POST['cf-turnstile-response'] ?? null;
    $check = $member->login($_POST['email'], $_POST['password'], $_POST['security-token'], $remember, $turnstileToken);
    if (is_bool($check) === true && $check === true)
    {
        $security->generateNewToken();
        $route->headTo('admin');
        exit(0);
    }
    else
    {
        $security->generateNewToken();
        $route->createActionMessage($route::errorMessage($check));
        $route->headTo('admin-login');
        exit(0);
    }
}
else
{
    require_once __DIR__ . '/.inc.foot.php';
    
    print_r($twig->render('/src/Views/admin/login.twig', $twigVars));
}
