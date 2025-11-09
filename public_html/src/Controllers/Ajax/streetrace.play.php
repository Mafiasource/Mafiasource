<?PHP

use src\Business\StreetraceService;

require_once __DIR__ . '/.inc.head.ajax.php';

$action = isset($_POST['action']) ? $_POST['action'] : 'organize';
$required = array('security-token');
if($action === 'join')
    $required = array_merge($required, array('race', 'vehicle'));
elseif($action === 'leave')
    $required = array_merge($required, array('race'));
else
    $required = array_merge($required, array('vehicle', 'stake', 'type', 'requiredPlayers'));

$missing = false;
foreach($required AS $field)
{
    if(!isset($_POST[$field]))
    {
        $missing = true;
        break;
    }
}

if($missing === false)
{
    $streetrace = new StreetraceService();
    $response = $streetrace->race($_POST);

    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->streetraceLangs());
    $twigVars['response'] = $response;
    print_r($twig->render('/src/Views/game/Ajax/streetrace.twig', $twigVars));
}
