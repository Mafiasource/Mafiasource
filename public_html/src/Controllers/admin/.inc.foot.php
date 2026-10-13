<?PHP

$message = $route->setActionMessage();

$twigVars = array(
    'routing' => $route,
    'securityToken' => $security->getToken(),
    'langs' => $langs,
    'lang' => $lang,
    'langRaw' => $route->getLangRaw(),
    'message' => $message,
    'member' => isset($_SESSION['cp-logon']) ? $_SESSION['cp-logon'] : false,
    'memberObj' => $member,
    'CF_TURNSTILE_SITEKEY' => APP_ISOLATED === true ? '' : CF_TURNSTILE_SITEKEY,
    'CF_TURNSTILE_LOGIN_SITEKEY' => LOGIN_TURNSTILE_ENABLED === true ? CF_TURNSTILE_LOGIN_SITEKEY : '',
    'LOGIN_TURNSTILE_ENABLED' => LOGIN_TURNSTILE_ENABLED,
    'APP_ISOLATED' => APP_ISOLATED
);
