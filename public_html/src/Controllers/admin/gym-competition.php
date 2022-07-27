<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("gym_competition");
$pagination = new Pagination($table);
$gymCompetition = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['gymCompetition'] = $gymCompetition;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/gym-competition.twig', $twigVars));
