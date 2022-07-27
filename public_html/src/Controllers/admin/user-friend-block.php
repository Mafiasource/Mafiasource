<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("user_friend_block");
$pagination = new Pagination($table);
$friendBlock = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['user_friend_block'] = $friendBlock;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/user-friend-block.twig', $twigVars));
