<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("garage");
$pagination = new Pagination($table);

$onlyFields = array('id', 'userGarageID', 'famGarageID', 'vehicleID',
    /* Fields to always include!! -> */'position', 'active', 'deleted');
$garage = $table->getTableRows($pagination->from, $pagination->to, $onlyFields);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['garage'] = $garage;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/garage.twig', $twigVars));
