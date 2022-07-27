<?PHP

use src\Business\GarageService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && (isset($_POST['crusher']) || isset($_POST['converter'])) && $famID > 0)
{
    $garage = new GarageService();
    switch($route->getRouteName())
    {
        case 'buy-family-crusher':
            $response = $garage->buyFamilyCrusherConverter($_POST);
            break;
        case 'buy-family-converter':
            $response = $garage->buyFamilyCrusherConverter($_POST, 'Converter');
            break;
    }
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
