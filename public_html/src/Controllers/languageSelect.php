<?PHP

/**
 * Language controller
 * THIS IS A REDIRECT CONTROLLER FOR LANGUAGE CHANGE
 * User will redirect to it's previous saved valid route. (simply put, the page the user came from)
 **/

if($route->getRoute() == "/set/language/nl")
{
    if(isset($_COOKIE['lang']) && $_COOKIE['lang'] != 'nl')
        $langSet = "nl";
}
elseif($route->getRoute() == "/set/language/en")
{
    if(isset($_COOKIE['lang']) && $_COOKIE['lang'] != 'en')
        $langSet = "en";
}
else
    $route->headTo('not_found');

if(isset($langSet))
{
    $route->createActionMessage($route->successMessage($langs['CHANGE_LANG_SUCCESS']));
    setcookie('lang', $langSet, time()+60*60*24*365, '/', $route->settings['domain'], SSL_ENABLED, true);
    
    /* Redirect to previous route */
    $allowedLangs = $route->allowedLangs;
    $prevRoute = $route->getPrevRoute();
    if(($key = array_search($langSet, $allowedLangs)) !== false)
    {
        // Outgame multilingual SEO purposes
        unset($allowedLangs[$key]);
        $searchLang = isset($allowedLangs[1]) ? $allowedLangs[1] : $allowedLangs[0];
    }
    $prevRoute = isset($searchLang) && substr($prevRoute, 0, 3) == "/" . $searchLang ? preg_replace("/" . $searchLang . "/", $langSet, $prevRoute, 1) : $prevRoute;
    
    header("HTTP/2 302 Found");
    header('Location: ' . $prevRoute);
    exit(0);
}
