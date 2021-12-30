<?PHP

require_once __DIR__ . '/.inc.head.php';

unset($_SESSION['cp-logon']);
setcookie('rememberme', 'val', time()-25478524, '/', $_SERVER['HTTP_HOST'], SSL_ENABLED, true);
setcookie('MID', 'none', time()-25478524, '/', $_SERVER['HTTP_HOST'], SSL_ENABLED, true);

$route->headTo('admin-login');
exit(0);
