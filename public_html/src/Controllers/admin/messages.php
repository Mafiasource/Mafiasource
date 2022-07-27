<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$table = new AdminService("message");
$pagination = new Pagination($table);

$onlyFields = array('id', 'cID', 'senderID', 'receiverID', 'message', 'date');
$messages = $table->getTableRows($pagination->from, $pagination->to, $onlyFields);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['message'] = $messages;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/messages.twig', $twigVars));
