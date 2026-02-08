<?PHP

use src\Business\CaptchaService;
use src\Business\ShoutboxService;
use src\Business\PollService;

/** Altered inc.head with custom redirection(s) **/
if($security->checkSSL() === false) exit(0);
if(!$user->checkLoggedSession())
{
    $route->headTo('home');
    exit(0);
}
else
{
    $captchaService = new CaptchaService();
    $userCaptcha = $captchaService->getUserCaptcha();
    if(is_object($userCaptcha) && !$security->checkCaptcha($userCaptcha->getSecurityTodo(), $userCaptcha->getSecurity()))
    {
        header("Location: " . $route->getPrevRoute());
        exit(0);
    }
    if(empty($_POST)) $captchaService->addOneUnsolvedUserCaptcha();
    if(!is_object($userData))
    {
        $route->headTo('home');
        exit(0);
    }
    $travelCounter = counterClean("dTravelTime", $userData->getCTravelTime());
    $serverTime = date($user->dateFormat);
    $user->checkStolenVehicleInQueue();
    $shoutboxService = new ShoutboxService();
    $familyShoutboxService = new ShoutboxService();
    $familyShoutboxService->setFamilyID($userData->getFamilyID());            
    $lastShoutboxID = $shoutboxService->getLastMessageID();
    $lastFamilyShoutboxID = $familyShoutboxService->getLastMessageID();
    $pollService = new PollService();
    $unvotedPoll = $pollService->userHasUnvotedPoll();
    /** //END Altered inc.head **/
    
    if(isset($_POST['security-token']) && isset($_POST['cf-turnstile-response']) && isset($_POST['submit-captcha']))
    {
        $response = $captchaService->validateCaptcha($_POST);
        if(is_bool($response) && $response == TRUE)
        {
            $prevRoute = $route->getPrevRoute();
            header("Location: " . $prevRoute);
            exit(0);
        }
        else
            $route->createActionMessage($route->errorMessage($response));
        
        $route->headTo('captcha_test');
        exit(0);
    }
    
    require_once __DIR__ . '/.inc.foot.php';
    
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->captchaTestLangs());
    
    echo $twig->render('/src/Views/game/captcha.test.twig', $twigVars);
}
