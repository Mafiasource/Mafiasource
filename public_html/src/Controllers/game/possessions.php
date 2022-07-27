<?PHP

use src\Business\PossessionService;
use src\Business\StateService;

require_once __DIR__ . '/.inc.head.php';

$possessionService = new PossessionService();

$possessions = $possessionService->getAllPossessionsByStateAndCity($userData->getStateID(), $userData->getCityID());
$state = new StateService();
$states = $state->getStates();
$cities = $state->getCitiesButHomeCity($userData->getCityID());

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->possessionsLangs()); // Extend base langs
$twigVars['possessions'] = $possessions;
$twigVars['states'] = $states;
$twigVars['cities'] = $cities;
$twigVars['city'] = $userData->getCity();

print_r($twig->render('/src/Views/game/possessions.twig', $twigVars));
