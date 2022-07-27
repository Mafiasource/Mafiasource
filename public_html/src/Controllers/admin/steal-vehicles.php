<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

$table = new AdminService("steal_vehicle");
$pagination = new Pagination($table);
$stealVehicles = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['stealVehicles'] = $stealVehicles;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/steal-vehicles.twig', $twigVars));
