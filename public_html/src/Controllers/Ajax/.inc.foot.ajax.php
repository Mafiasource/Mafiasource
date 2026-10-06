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
    'CF_TURNSTILE_SITEKEY' => APP_ISOLATED === true ? '' : CF_TURNSTILE_SITEKEY,
    'APP_ISOLATED' => APP_ISOLATED
);

/** Trigger a captcha security +1 count on any ajax success message **/
if (
    APP_ISOLATED === false
    && $_POST !== array()
    && isset($response) === true
    && is_array($response) === true
    && $security->responseHasAlertSuccess($response) === true
) {
    $captchaService = new CaptchaService();
    $captchaService->setUserCaptcha();

    if (isset($_SESSION['captcha_security'])) {
        $security->addToCaptchaCount();
    } else {
        $security->resetCaptchaCount();
    }
}
