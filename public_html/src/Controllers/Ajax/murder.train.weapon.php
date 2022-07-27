<?PHP

use src\Business\UserService;
use src\Business\MurderService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']))
{
    $userService = new UserService();
    $murder = new MurderService();
    $statusPageBefore = $userService->getStatusPageInfo();
    $userDataBefore = $userData;
    $uWeaponTrainingBefore = $statusPageBefore->getWeaponTraining();
    $cWeaponTrainingBefore = $userDataBefore->getCWeaponTraining();
    
    $response = $murder->trainWeaponTraining($_POST);
    
    $statusPageAfter = $userService->getStatusPageInfo();
    $userDataAfter = $user->getUserData();
    $uWeaponTrainingAfter = $statusPageAfter->getWeaponTraining();
    $cWeaponTrainingAfter = $userDataAfter->getCWeaponTraining();
    
    if($cWeaponTrainingAfter !=  $cWeaponTrainingBefore) $response['alert']['message'] .= counter("WeaponTraining", $cWeaponTrainingAfter);
    if($uWeaponTrainingBefore != $uWeaponTrainingAfter) $response['alert']['message'] .="<script>$('.trainingBar .progress-bar').css('width', '".$uWeaponTrainingAfter."%');</script>";
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
