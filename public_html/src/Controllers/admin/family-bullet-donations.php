<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("family_bf_donation_log");
$pagination = new Pagination($table);
$familyBulletDonations = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['family_bf_donation_log'] = $familyBulletDonations;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/family-bullet-donations.twig', $twigVars));
