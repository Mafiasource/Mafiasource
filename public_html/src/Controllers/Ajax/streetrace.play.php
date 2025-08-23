<?PHP

use src\Business\StreetraceService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['vehicle']) && isset($_POST['stake']) && isset($_POST['type']))
{
    $streetrace = new StreetraceService();
    $response = $streetrace->race($_POST);

    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->streetraceLangs());
    $twigVars['response'] = $response;
    print_r($twig->render('/src/Views/game/Ajax/streetrace.twig', $twigVars));
}
