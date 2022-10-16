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
    $zoekTerm = $security->xssEscape($_POST['search']);
    $zoekenOp = $security->xssEscape($_POST['search-by']);
    $fields = FALSE;
    $skipFields = FALSE;
    $check = FALSE;
    if(!empty($_POST['fields'])) $fields = $security->xssEscape($_POST['fields']);
    if(!empty($_POST['skipFields'])) $skipFields = $security->xssEscape($_POST['skipFields']);
    $table = new AdminService($table);
    $tableRows = $table->searchRecords($zoekTerm, $zoekenOp, $fields, $skipFields);
    if(!empty($tableRows)) $check = TRUE;
    $msg = "Geen records gevonden onder deze zoekopdracht.";
    if($zoekenOp == "0") $msg = "Zoekopdracht mislukt, selecteer een kolom om op te zoeken!";
    
    $twigVars = array(
        'routing' => $route,
        'securityToken' => $security->getToken(),
        'member' => $_SESSION['cp-logon'],
        'memberObj' => $member,
        'check' => $check,
        'msg' => $msg,
        'table' => $tableName,
        'tableRows' => $tableRows,
        'fields' => $fields,
        'skipFields' => $skipFields
    );
    
    print_r($twig->render('/src/Views/admin/Ajax/search.twig', $twigVars));
    exit(0);
}
else
    print_r($twig->render('/src/Views/admin/Ajax/general.fail.msg.twig', $twigVars = array('msg' => 'Verkeerde gegevens ontvangen.', 'check' => FALSE)));
