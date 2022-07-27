<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("family_join_invite");
$pagination = new Pagination($table);
$familyJoinInvite = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['family_join_invite'] = $familyJoinInvite;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/family-join-invite.twig', $twigVars));
