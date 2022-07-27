<?PHP

use src\Business\GroundService;
use src\Business\StateService;

require_once __DIR__ . '/.inc.head.ajax.php';

$state = new StateService();

if(isset($_POST['state']) && in_array($_POST['state'], $state->allowedStates))
{
    global $security;
    global $langs;
    $stateID = $state->getStateIdByName($_POST['state']);
    $ground = new GroundService($stateID);
    $groundMap = $ground->getGroundMapDataByStateID($stateID);
    $states = $state->getStates();
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->groundLangs()); // Extend base langs
    $twigVars['groundCoords'] = $ground->groundCoords;
    $twigVars['hometownFamily'] = $ground->getHometownFamily();
    $twigVars['states'] = $states;
    $twigVars['groundMap'] = $groundMap;
    
    print_r($twig->render('/src/Views/game/Ajax/ground.twig', $twigVars));
}
