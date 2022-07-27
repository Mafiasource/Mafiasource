<?PHP

use src\Business\GarageService;

require_once __DIR__ . '/.inc.head.ajax.php';

$garage = new GarageService();

if(isset($_POST['securityToken']) && isset($_SESSION['steal-vehicles']))
{
    $stateID = $userData->getStateID();
    $response = $garage->addVehicleToGarage($_POST, $stateID);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
