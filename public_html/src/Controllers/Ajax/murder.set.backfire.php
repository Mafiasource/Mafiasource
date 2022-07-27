<?PHP

use src\Business\MurderService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['backfire-type']) && isset($_POST['backfire-percentage']) && isset($_POST['backfire-number']) && isset($_POST['set-backfire']))
{
    $murder  = new MurderService();
    $response = $murder->setBackfire($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
