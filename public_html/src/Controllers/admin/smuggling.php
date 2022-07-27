<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

$table = new AdminService("smuggle");
$pagination = new Pagination($table);
$smuggling = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['smuggling'] = $smuggling;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/smuggling.twig', $twigVars));
