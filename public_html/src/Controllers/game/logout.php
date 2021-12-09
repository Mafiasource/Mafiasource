<?PHP

unset($_SESSION['UID']);
setcookie("remember", "", time()-1, "/", $route->settings['domain'], SSL_ENABLED, true);
setcookie("UID", "0", time()-1, "/", $route->settings['domain'], SSL_ENABLED, true);
$route->headTo("home");
exit(0);
