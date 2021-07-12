<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("family_mercenary_log");
$pagination = new Pagination("family_mercenary_log", $table);
$familyMercenaries = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['family_mercenary_log'] = $familyMercenaries;
$twigVars['pagination'] = $pagination;

echo $twig->render('/src/Views/admin/family-mercenaries.twig', $twigVars);
