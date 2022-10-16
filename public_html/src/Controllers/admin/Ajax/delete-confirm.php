<?PHP

use src\Business\MemberService;
use src\Business\AdminService;

$member = new MemberService();
$member->redirectIfLoggedOut();

$admin = new AdminService();
$validTables = $admin->getValidTables();
if(isset($_POST) && !empty($_POST['table']) && in_array($_POST['table'], $validTables) && $security->checkToken($_POST['securityToken']))
{
    $table = $tableName = $security->xssEscape($_POST['table']);
    $id = (int)$_POST['id'];
    $hideArr = explode(' ', $security->xssEscape($_POST['hideString']));
    $showArr = "";
    if(isset($_POST['showString']) && !empty($_POST['showString'])) $showArr = explode(' ', $security->xssEscape($_POST['showString']));
    $table = new AdminService($table);
    $rows = $table->getTableRowById($id);
    
    $twigVars = array(
        'routing' => $route,
        'securityToken' => $security->getToken(),
        'member' => $_SESSION['cp-logon'],
        'rows' => $rows,
        'hide' => $hideArr,
        'show' => $showArr,
        'table' => $tableName,
        'rowid' => $id,
        'check' => TRUE
    );
    
    print_r($twig->render('/src/Views/admin/Ajax/delete-confirm.twig', $twigVars));
    exit(0);
}
else
    print_r($twig->render('/src/Views/admin/Ajax/general.fail.msg.twig', $twigVars = array('msg' => 'Verkeerde gegevens ontvangen.', 'check' => FALSE)));
