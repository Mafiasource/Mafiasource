<?PHP

if(isset($_SESSION['steal-vehicles'])) unset($_SESSION['steal-vehicles']);
if(isset($_SESSION['messaging'])) unset($_SESSION['messaging']);
if(isset($_SESSION['smuggle'])) unset($_SESSION['smuggle']);
if(isset($_SESSION['rip'])) unset($_SESSION['rip']);
if(isset($_SESSION['UID'])) unset($_SESSION['UID']);
setcookie("remember", "", time()-1, "/", $route->settings['domain'], SSL_ENABLED, true);
setcookie("UID", "0", time()-1, "/", $route->settings['domain'], SSL_ENABLED, true);
$route->headTo("home");
exit(0);
