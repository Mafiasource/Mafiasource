<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("user");
$pagination = new Pagination($table);

$onlyFields = array('id', 'username', 'email', 'ip', 'registerDate', 'statusID');
$users = $table->getTableRows($pagination->from, $pagination->to, $onlyFields);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['users'] = $users;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/users.twig', $twigVars));
