<?PHP

use src\Business\CaptchaService;

$twigVars = array(
    'routing' => $route,
    'settings' => $route->settings,
    'securityToken' => $security->getToken(),
    'langs' => $langs,
    'lang' => $lang,
    'userData' => $userData,
    'time' => time(),
);

/** Trigger a captcha security +1 count on any ajax success message **/
if(!empty($_POST) && isset($response) &&
    (
        is_array($response) && (isset($response['alert']['success']) && $response['alert']['success'] == true) ||
        (isset($response[0]['alert']['success']) && $response[0]['alert']['success'] == true)
    )
)
{
    $captchaService = new CaptchaService();
    $captchaService->setUserCaptcha(); // Add 1 `security` to user's captcha table record TRUSTED
    if(isset($_SESSION['captcha_security'])) // Untrusted session variable checkup
        $security->addToCaptchaCount(); // Only adds 1 to the session variable captcha_security
    else
        $security->resetCaptchaCount(); // Only resets session variable captcha_security to 0;
}
