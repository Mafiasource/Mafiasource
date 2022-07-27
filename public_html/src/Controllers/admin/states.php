<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("state");
$pagination = new Pagination($table);
$states = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['states'] = $states;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/states.twig', $twigVars));
