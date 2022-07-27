<?PHP

use src\Business\UserService;
use src\Business\GarageService;
use src\Business\FamilyCrimeService;

require_once __DIR__ . '/.inc.head.php';

if($userData->getFamilyID() == 0)
{
    $route->headTo('family-list');
    exit(0);
}

$userService = new UserService();
$garage = new GarageService();
$hasGarage = $garage->hasFamilyGarage();

$famCrime = new FamilyCrimeService();
$famCrimes = $famCrime->getFamilyCrimes();

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->familyCrimeLangs()); // Extend base langs
$twigVars['hasGarage'] = $hasGarage;
$twigVars['crimes'] = $famCrime->crimeNames;
$twigVars['familyCrimes'] = $famCrimes;
$twigVars['insideFamilyCrime'] = $famCrime->userInsideFamilyCrime();

print_r($twig->render('/src/Views/game/family-crimes.twig', $twigVars));
