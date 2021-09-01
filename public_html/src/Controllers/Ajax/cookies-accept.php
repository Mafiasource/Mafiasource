<?PHP

/* OUTGAME AJAX CONTROLLER! */
if(isset($_POST['type']) && $_POST['type'] == "accept")
{
    setcookie('cookies-accepted', 'accepted', time()+60*60*24*365, '/', $route->settings['domain'], SSL_ENABLED, true);
}
