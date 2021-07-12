<?PHP

use src\Business\FamilyCrimeService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && isset($_POST['participants']) && isset($_POST['crime']) && $famID > 0)
{
    $famCrime = new FamilyCrimeService();
    
    $response = $famCrime->organizeFamilyCrime($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    echo $twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars);
}
