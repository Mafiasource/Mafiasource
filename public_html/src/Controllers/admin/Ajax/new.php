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
    $table = new AdminService($table);
    $id = $table->getLastInsertId();
    $check = $table->createToEditRow();
    
    $twigVars = array(
        'routing' => $route,
        'securityToken' => $security->getToken(),
        'member' => $_SESSION['cp-logon'],
        'check' => (bool)$check,
        'rows' => $check,
        'rowid' => $id,
        'table' => $tableName,
        'uploadDir' => strtolower($tableName),
        'msg' => 'Fout bij ophalen record in de database.',
        'newRowMessage' => "Record werd toegevoegd in de database, je kan het item meteen aanpassen."
    );
    
    print_r($twig->render('/src/Views/admin/Ajax/edit.twig', $twigVars));
    exit(0);
}
else
    print_r($twig->render('/src/Views/admin/Ajax/general.fail.msg.twig', $twigVars = array('msg' => 'Verkeerde gegevens ontvangen.', 'check' => FALSE)));
