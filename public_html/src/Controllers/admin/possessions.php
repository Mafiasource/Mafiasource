<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("possession");
$pagination = new Pagination("possession", $table);
$possessions = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['possessions'] = $possessions;
$twigVars['pagination'] = $pagination;

echo $twig->render('/src/Views/admin/possessions.twig', $twigVars);
