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
    'CF_TURNSTILE_SITEKEY' => CF_TURNSTILE_SITEKEY,
    'CF_TURNSTILE_LOGIN_SITEKEY' => CF_TURNSTILE_LOGIN_SITEKEY,
    'APP_ISOLATED' => APP_ISOLATED
);
