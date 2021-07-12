<?PHP

/**
 * Language controller
 * THIS IS A REDIRECT CONTROLLER FOR LANGUAGE CHANGE
 * User will redirect to it's previous saved valid route. (simply put, the page the user came from)
 **/

if($route->getRoute() == "/set/language/nl")
{
    if(isset($_COOKIE['lang']) && $_COOKIE['lang'] != 'nl')
    {
        $route->createActionMessage($route->successMessage($langs['CHANGE_LANG_SUCCESS']));
        setcookie('lang', 'nl', time()+60*60*24*365, '/', $route->settings['domain'], SSL_ENABLED, true);
    }
}
elseif($route->getRoute() == "/set/language/en")
{
    if(isset($_COOKIE['lang']) && $_COOKIE['lang'] != 'en')
    {
        $route->createActionMessage($route->successMessage($langs['CHANGE_LANG_SUCCESS']));
        setcookie('lang', 'en', time()+60*60*24*365, '/', $route->settings['domain'], SSL_ENABLED, true);
    }
}
else
{
    $route->headTo('not_found');
}

/* Redirect to previous route */
$prevRoute = $route->getPrevRoute();
header("HTTP/2 302 Found");
header('Location: ' . $prevRoute);
exit(0);
