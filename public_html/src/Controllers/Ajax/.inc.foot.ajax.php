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
    'CF_TURNSTILE_SITEKEY' => CF_TURNSTILE_SITEKEY
);

/** Trigger a captcha security +1 count on any ajax success message **/
if (!empty($_POST) && isset($response) && is_array($response) && $security->responseHasAlertSuccess($response)) {
    $captchaService = new CaptchaService();
    $captchaService->setUserCaptcha();

    if (isset($_SESSION['captcha_security'])) {
        $security->addToCaptchaCount();
    } else {
        $security->resetCaptchaCount();
    }
}
