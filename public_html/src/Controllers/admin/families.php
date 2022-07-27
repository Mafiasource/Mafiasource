<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("family");
$pagination = new Pagination($table);

$onlyFields = array('id', 'name', 'bossUID', 'vip', 'money');
$families = $table->getTableRows($pagination->from, $pagination->to, $onlyFields);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['family'] = $families;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/families.twig', $twigVars));
