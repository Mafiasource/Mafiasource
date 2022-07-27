<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("forum_status");
$pagination = new Pagination($table);
$forumStats = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['forumStats'] = $forumStats;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/forum-statuses.twig', $twigVars));
