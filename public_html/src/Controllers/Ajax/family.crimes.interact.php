<?PHP

use src\Business\UserService;
use src\Business\GarageService;
use src\Business\FamilyCrimeService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && isset($_POST['crime']) && (isset($_POST['join']) || isset($_POST['start']) || isset($_POST['leave'])) && $famID > 0)
{
    $userService = new UserService();
    $famCrime = new FamilyCrimeService();
    
    $userDataBefore = $userData;
    $cFamilyCrimesBefore = $userDataBefore->getCFamilyCrimes();
    $cPrisonTimeBefore = $userDataBefore->getCPrisonTime();
    
    $response = $famCrime->interactFamilyCrime($_POST);
    
    $userDataAfter = $user->getUserData();
    $cFamilyCrimesAfter = $userDataAfter->getCFamilyCrimes();
    $cPrisonTimeAfter = $userDataAfter->getCPrisonTime();
    
    if($cFamilyCrimesBefore !=  $cFamilyCrimesAfter) $response['alert']['message'] .= counter("FamilyCrimes", $cFamilyCrimesAfter);
    if($cPrisonTimeAfter != $cPrisonTimeBefore) $response['alert']['message'] .= counterActive("PrisonTime", $cPrisonTimeAfter);
    
    $garage = new GarageService();
    $hasGarage = $garage->hasFamilyGarage();
    $famCrimes = $famCrime->getFamilyCrimes();
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['responseCrimes'] = $response;
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->familyCrimeLangs()); // Extend base langs
    $twigVars['hasGarage'] = $hasGarage;
    $twigVars['crimes'] = $famCrime->crimeNames;
    $twigVars['familyCrimes'] = $famCrimes;
    $twigVars['insideFamilyCrime'] = $famCrime->userInsideFamilyCrime();
    
    print_r($twig->render('/src/Views/game/Ajax/family.crimes.twig', $twigVars));
}
