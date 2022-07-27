<?PHP

use src\Business\GarageService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && $famID > 0 && (isset($_POST['crush-convert']) || isset($_POST['sell'])))
{
    $garage = new GarageService();
    
    $response = $garage->interactWithFamilyVehicles($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    $twigVars['colspan'] = 4; // Required for bootstrap in-table response.
    
    print_r($twig->render('/src/Views/game/Ajax/.default.in-table.response.twig', $twigVars)); // First bootstrap in table response
}
