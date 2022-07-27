<?PHP

use src\Business\UserService;
use src\Business\GarageService;
use src\Business\FamilyCrimeService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && isset($_POST['participants']) && isset($_POST['crime']) && $famID > 0)
{
    $famCrime = new FamilyCrimeService();
    
    $response = $famCrime->organizeFamilyCrime($_POST);
    
    $userService = new UserService();
    $garage = new GarageService();
    $hasGarage = $garage->hasFamilyGarage();
    $famCrimes = $famCrime->getFamilyCrimes();
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['responseOrganize'] = $response;
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->familyCrimeLangs()); // Extend base langs
    $twigVars['hasGarage'] = $hasGarage;
    $twigVars['crimes'] = $famCrime->crimeNames;
    $twigVars['familyCrimes'] = $famCrimes;
    $twigVars['insideFamilyCrime'] = $famCrime->userInsideFamilyCrime();

    print_r($twig->render('/src/Views/game/Ajax/family.crimes.twig', $twigVars));
}
