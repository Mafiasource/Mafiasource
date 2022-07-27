<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("cms");
$pagination = new Pagination($table);
$cms = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['cms'] = $cms;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/cms.twig', $twigVars));
