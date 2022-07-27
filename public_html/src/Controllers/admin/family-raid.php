<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("family_raid");
$pagination = new Pagination($table);

$onlyFields = array('id', 'stateID', 'familyID', 'leaderID', 'driverID', 'bombExpertID', 'weaponExpertID');
$familyRaid = $table->getTableRows($pagination->from, $pagination->to, $onlyFields);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['family_raid'] = $familyRaid;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/family-raid.twig', $twigVars));
