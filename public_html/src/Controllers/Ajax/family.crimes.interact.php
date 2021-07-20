<?PHP

use src\Business\UserService;
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
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    echo $twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars);
}
