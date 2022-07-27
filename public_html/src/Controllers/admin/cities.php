<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("city");
$pagination = new Pagination($table);
$cities = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['cities'] = $cities;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/cities.twig', $twigVars));
