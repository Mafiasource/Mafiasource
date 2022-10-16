<?PHP

use src\Business\MemberService;
use src\Business\AdminService;

$member = new MemberService();
$member->redirectIfLoggedOut();

$admin = new AdminService();
$validTables = $admin->getValidTables();
if(isset($_POST) && !empty($_POST['table']) && in_array($_POST['table'], $validTables) && $security->checkToken($_POST['securityToken']))
{
    $table = $security->xssEscape($_POST['table']);
    $id = (int)$_POST['id'];
    $postNot = array("id", "table", "securityToken");
    foreach($_POST AS $key => $value) if(!in_array($key, $postNot)) $post[$key] = stripslashes($value);
    $table = new AdminService($table);
    $check = $table->saveEditedRow($post, $id, $_FILES);
    
    $twigVars = array(
        'routing' => $route,
        'securityToken' => $security->getToken(),
        'member' => $_SESSION['cp-logon'],
        'check' => $check,
        'rowid' => $id,
        'msg' => 'Fout bij aanpassen record in database.'
    );
    
    print_r($twig->render('/src/Views/admin/Ajax/edit.saved.twig', $twigVars));
    $security->generateNewToken();
    exit(0);
}
else
    print_r($twig->render('/src/Views/admin/Ajax/general.fail.msg.twig', $twigVars = array('msg' => 'Verkeerde gegevens ontvangen.', 'check' => FALSE)));
