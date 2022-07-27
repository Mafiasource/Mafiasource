<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

$table = new AdminService("forum_reaction");
$pagination = new Pagination($table);
$forumReactions = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['forumReactions'] = $forumReactions;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/forum-reactions.twig', $twigVars));
