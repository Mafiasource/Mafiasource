<?PHP

use src\Business\PossessionService;
use src\Business\StateService;

require_once __DIR__ . '/.inc.head.ajax.php';

$state = new StateService();

if(isset($_POST['city']) && in_array($_POST['city'], $state->allowedCities))
{
    global $security;
    $possession = new PossessionService();
    global $langs;
    $cityID = $state->getCityIdByName($_POST['city']);
    $stateID = $state->getStateIdByCityId($cityID);
    $possessions = $possession->getAllPossessionsByStateAndCity($stateID, $cityID);
    $states = $state->getStates();
    $cities = $state->getCitiesButHomeCity($cityID);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->possessionsLangs()); // Extend base langs
    $twigVars['possessions'] = $possessions;
    $twigVars['states'] = $states;
    $twigVars['cities'] = $cities;
    $twigVars['city'] = $_POST['city'];
    
    print_r($twig->render('/src/Views/game/Ajax/possessions.twig', $twigVars));
}
