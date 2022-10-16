<?PHP

use src\Business\MemberService;
use src\Business\AdminService;

$member = new MemberService();
$member->redirectIfLoggedOut();

$admin = new AdminService();
$validTables = $admin->getValidTables();
if(isset($_POST) && !empty($_POST['table']) && in_array($_POST['table'],$validTables) && $security->checkToken($_POST['securityToken']))
{
    $table = $security->xssEscape($_POST['table']);
    $id = (int)$_POST['id'];
    $table = new AdminService($table);
    $check = $table->activateRow($id);
    $action = "actief";
    $click = array("done" => "activate", "todo" => "deactivate", "color" => "green", "glyphdone" => "fa-power-off", "glyphtodo" => "fa-check");
    
    $twigVars = array(
        'routing' => $route,
        'securityToken' => $security->getToken(),
        'member' => $_SESSION['cp-logon'],
        'check' => $check,
        'action' => $action,
        'rowid' => $id,
        'click' => $click,
        'msg' => 'Fout bij opslaan status record in database.'
    );
    
    print_r($twig->render('/src/Views/admin/Ajax/activate.deactivate.twig',$twigVars));
}
else
    print_r($twig->render('/src/Views/admin/Ajax/general.fail.msg.twig',$twigVars = array('msg' => 'Verkeerde gegevens ontvangen.', 'check' => FALSE)));
