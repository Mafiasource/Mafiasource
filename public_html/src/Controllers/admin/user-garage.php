<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("user_garage");
$pagination = new Pagination($table);
$userGarage = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['user_garage'] = $userGarage;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/user-garage.twig', $twigVars));
