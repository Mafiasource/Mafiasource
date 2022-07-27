<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("login_fail");
$pagination = new Pagination($table);
$login = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['login'] = $login;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/user-login-fail.twig', $twigVars));
