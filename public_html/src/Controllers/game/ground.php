<?PHP

use src\Business\GroundService;
use src\Business\StateService;

require_once __DIR__ . '/.inc.head.php';

$ground = new GroundService($userData->getStateID());
$state = new StateService();
$states = $state->getStates();

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->groundLangs()); // Extend base langs
$twigVars['groundCoords'] = $ground->groundCoords;
$twigVars['hometownFamily'] = $ground->getHometownFamily();
$twigVars['states'] = $states;
$twigVars['groundMap'] = $ground->getGroundMapDataByStateID($userData->getStateID());

print_r($twig->render('/src/Views/game/ground.twig', $twigVars));
