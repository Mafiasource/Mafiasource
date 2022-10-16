<?PHP

use src\Business\CMSService;
use src\Business\SeoService;

if($security->checkSSL() === false) exit(0);

$loggedSession = $user->checkLoggedSession(false) ? true : false;
if(!$userData)
{
    if($loggedSession)
        $userData = $user->getUserData();
}

$cookie = "false";
$username = "";
if(isset($_COOKIE['cookies-accepted']) && $_COOKIE['cookies-accepted'] == "accepted") $cookie = "true";

$cms = new CMSService();
$seo = new SeoService();
$seoData = $seo->getSeoDataByRouteName($route->getRouteName());
$pageTitle =  "Online Mafia RPG - " . ucfirst($route->settings['domainBase']);
$pageSubject = "Free to play text-based online mafia RPG";
$pageImage = PROTOCOL.STATIC_SUBDOMAIN.".".$route->settings['domainBase']."/web/public/images/mafiasource.jpg";
$pageUrl = PROTOCOL.$_SERVER['HTTP_HOST'].$route->getRoute();
$pageDescription = $route->settings['gamename'] . " is a free to play text-based online mafia RPG. Conquer the states and cities of America together with friends and family.";
$pageKeywords = $route->settings['gamename'] . ",online,mafia,rpg,crimclub,criminal,gangster,maffia,mafia rpg,mob boss,maffiabaas,online mafia rpg";
$pageAuthor = "@Mafiasource";
if(isset($seoData) && is_object($seoData))
{
    if($seoData->getTitle()) $pageTitle = $seoData->getTitle();
    if($seoData->getSubject()) $pageSubject = $seoData->getSubject();
    if($seoData->getImage()) $pageImage = $seoData->getImage();
    if($seoData->getUrl()) $pageUrl = $seoData->getUrl();
    if($seoData->getDescription()) $pageDescription = substr($seoData->getDescription(), 0, 150)."...";
    if($seoData->getKeywords()) $pageKeywords = $seoData->getKeywords();
}

$ingame = true;
if($user->notIngame()) $ingame = false;
