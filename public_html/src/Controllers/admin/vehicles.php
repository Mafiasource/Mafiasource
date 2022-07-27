<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

$table = new AdminService("vehicle");
$pagination = new Pagination($table);
$vehicles = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['vehicle'] = $vehicles;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/vehicles.twig', $twigVars));
