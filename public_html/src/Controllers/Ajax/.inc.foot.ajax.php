<?PHP

$twigVars = array(
    'routing' => $route,
    'settings' => $route->settings,
    'securityToken' => $security->getToken(),
    'langs' => $langs,
    'lang' => $lang,
    'userData' => $userData,
    'time' => time(),
);
