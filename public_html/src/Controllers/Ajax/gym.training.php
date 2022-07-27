<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && (isset($_POST['push-up']) || isset($_POST['cycle']) || isset($_POST['bench-press']) || isset($_POST['run'])))
{
    $userService = new UserService();
    
    $userDataBefore = $userData;
    $gymDataBefore = $userService->getGymPageInfo();
    require_once __DIR__ . '/.valuesAnimation.php';
    $powerBefore = $gymDataBefore->getPower();
    $cardioBefore = $gymDataBefore->getCardio();
    $gymTrainingBefore = ($powerBefore + $cardioBefore) / 2;
    $cGymTrainingBefore = $userDataBefore->getCGymTraining();
    
    $response = $userService->gymTraining($_POST);
    
    $userDataAfter = $user->getUserData();
    $gymDataAfter = $userService->getGymPageInfo();
    $powerAfter = $gymDataAfter->getPower();
    $cardioAfter = $gymDataAfter->getCardio();
    $gymTrainingAfter = ($powerAfter + $cardioAfter) / 2;
    $cGymTrainingAfter = $userDataAfter->getCGymTraining();
    
    if($cGymTrainingAfter !=  $cGymTrainingBefore) $response['alert']['message'] .= counter("GymTraining", $cGymTrainingAfter);
    if($powerBefore != $powerAfter) valueAnimation("#gymPower", $powerBefore, $powerAfter);
    if($cardioBefore != $cardioAfter) valueAnimation("#gymCardio", $cardioBefore, $cardioAfter);
    if($gymTrainingAfter >= 100)
    {
        if($gymTrainingAfter != $gymTrainingBefore) $response['alert']['message'] .= "<script>$('#gymTrainingBar .progress-bar').css('width', '100%');</script>";
    }
    else
    {
        if($gymTrainingAfter != $gymTrainingBefore) $response['alert']['message'] .= "<script>$('#gymTrainingBar .progress-bar').css('width', '".$gymTrainingAfter."%');</script>";
    }
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
