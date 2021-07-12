<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("member");
$pagination = new Pagination("member", $table);
$members = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['members'] = $members;
$twigVars['pagination'] = $pagination;

echo $twig->render('/src/Views/admin/members.twig', $twigVars);
