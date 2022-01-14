<?PHP

$message = $route->setActionMessage();
if(isset($_COOKIE['username'])) $username = $_COOKIE['username'];
$twigVars = array(
    'routing' => $route,
    'settings' => $route->settings,
    'securityToken' => $security->getToken(),
    'langs' => $langs,
    'lang' => $lang,
    'langRaw' => $route->getLangRaw(),
    'message' => $message,
    'cookiesAccept' => $cookie,
    'username' => $username,
    'ingame' => $ingame,
    'statusDonatorColors' => $user->getStatusAndDonatorColors(),
    'PAGE_TITLE' => $pageTitle,
    'PAGE_IMAGE' => $pageImage,
    "PAGE_URL" => $pageUrl,
    "PAGE_DESCRIPTION" => $pageDescription,
    "PAGE_SUBJECT" => $pageSubject,
    "AUTHOR" => $pageAuthor,
    "PAGE_KEYWORDS" => $pageKeywords,
    'offline' => OFFLINE
);
if(isset($userData)) $twigVars['userData'] = $userData;
