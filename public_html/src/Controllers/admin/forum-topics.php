<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

$table = new AdminService("forum_topic");
$pagination = new Pagination($table);
$forumTopics = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['forumTopics'] = $forumTopics;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/forum-topics.twig', $twigVars));
