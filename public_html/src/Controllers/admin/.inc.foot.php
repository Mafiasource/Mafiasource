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
    'memberObj' => $member
);
