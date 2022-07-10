<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("ip_ban");
$pagination = new Pagination($table);
$ipBan = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['ip_ban'] = $ipBan;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/ip-ban.twig', $twigVars));
