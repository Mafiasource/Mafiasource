<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

$table = new AdminService("helpsystem");
$pagination = new Pagination($table);
$helpsystem = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['helpsystem'] = $helpsystem;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/helpsystem.twig', $twigVars));
