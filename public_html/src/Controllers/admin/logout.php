<?PHP

require_once __DIR__ . '/.inc.head.php';

unset($_SESSION['cp-logon']);
setcookie('rememberme', 'val', time()-25478524, '/', $_SERVER['HTTP_HOST'], SSL_ENABLED, 1);
setcookie('UID', 'none', time()-25478524, '/', $_SERVER['HTTP_HOST'], SSL_ENABLED, 1);

$route->headTo('admin-login');
exit(0);
