<?PHP

use src\Business\UserService;
use src\Business\GroundService;
use src\Business\StateService;

require_once __DIR__ . '/.inc.head.ajax.php';

$state = new StateService();

if(isset($_POST['state']) && in_array($_POST['state'], $state->allowedStates) && isset($_POST['groundID']) && isset($_POST['building']) && isset($_POST['security-token']))
{
    $userService = new UserService();
    $stateID = $state->getStateIdByName($_POST['state']);
    $groundID = (int)$_POST['groundID'];
    $building = (int)$_POST['building'];
    $ground = new GroundService($stateID, $groundID);
    $groundArea = $ground->getGroundDataByStateIdAndGroundID($stateID, $groundID, true);
    if(is_object($groundArea) && $building >= 1 && $building <= 5)
    {
        $cashMoneyBefore = $userData->getCash();
        
        $response = $ground->buyGroundBuilding($_POST, $groundArea);
        
        $cashMoneyAfter = $user->getUserData()->getCash();
        
        require_once __DIR__ . '/.moneyAnimation.php';
    
        require_once __DIR__ . '/.inc.foot.ajax.php';
        $twigVars['buyBuildingResponse'] = $response;
        $twigVars['langs'] = array_merge($twigVars['langs'], $language->groundLangs()); // Extend base langs
        $twigVars['ground'] = $ground->getGroundDataByStateIdAndGroundID($stateID, $groundID);
        $twigVars['statusPage'] = $userService->getStatusPageInfo();
        
        print_r($twig->render('/src/Views/game/Ajax/ground.area.twig', $twigVars));
    }
}
