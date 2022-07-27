<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("market");
$pagination = new Pagination($table);
$market = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['market'] = $market;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/market.twig', $twigVars));
