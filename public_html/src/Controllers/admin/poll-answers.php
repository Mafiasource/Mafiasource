<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("poll_answer");
$pagination = new Pagination($table);
$pollQuestions = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['poll_answer'] = $pollQuestions;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/poll-answers.twig', $twigVars));
