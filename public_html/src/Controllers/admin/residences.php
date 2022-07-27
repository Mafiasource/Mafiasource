<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

$table = new AdminService("residence");
$pagination = new Pagination($table);
$residences = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['residence'] = $residences;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/residences.twig', $twigVars));
