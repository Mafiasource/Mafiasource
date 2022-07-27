<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("family_brothel_whore");
$pagination = new Pagination($table);
$familyBrothelWhores = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['family_brothel_whore'] = $familyBrothelWhores;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/family-brothel-whores.twig', $twigVars));
