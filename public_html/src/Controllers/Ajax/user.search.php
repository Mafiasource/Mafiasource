<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.ajax.php';

global $language;
global $langs;
$l = $language->onlineToplistLangs();

if(isset($_POST['security-token']) && (isset($_POST['search']) || isset($_POST['search-rank'])))
{
    $userService = new UserService();
    
    if(isset($_POST['search']))
        $response = $actionMessage = $userService->searchPlayer($_POST);
    else
        $response = $actionMessage = $userService->searchPlayerByRank($_POST);
    
    if(isset($response["msg"]) && isset($response["data"]))
    {
        $actionMessage = $response["msg"];
        $data = $response["data"];
    }
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $actionMessage;
    if(isset($data)) $twigVars['members'] = $data;
    $twigVars['langs'] = array_merge($langs, $l);
    
    print_r($twig->render('/src/Views/game/Ajax/user.search.twig', $twigVars));
}
