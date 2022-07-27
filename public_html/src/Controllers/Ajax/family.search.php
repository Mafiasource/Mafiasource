<?PHP

use src\Business\FamilyService;
use src\Languages\GetLanguageContent;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['search']))
{
    $family = new FamilyService();
    
    $response = $actionMessage = $family->searchFamily($_POST);
    
    if(isset($response["msg"]) && isset($response["data"]))
    {
        $actionMessage = $response["msg"];
        $data = $response["data"];
    }
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $actionMessage;
    if(isset($data)) $twigVars['families'] = $data;
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->familyLangs()); // Extend base langs
    $twigVars['userData'] = $userData;
    
    print_r($twig->render('/src/Views/game/Ajax/family.search.twig', $twigVars));
}
