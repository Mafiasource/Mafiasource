<?PHP

use src\Business\StateService;

require_once __DIR__ . '/.inc.head.ajax.php';

$allowedMediums = array("airplane", "train", "bus", "vehicle");

if(isset($_POST['type']) && in_array($_POST['type'],$allowedMediums))
{
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    $cPrisonTimeBefore = $userDataBefore->getCPrisonTime();
    $cTravelTimeBefore = $userDataBefore->getCTravelTime();
    
    $state = new StateService();
    $response = $state->handleTravel($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    $cPrisonTimeAfter = $userDataAfter->getCPrisonTime();
    $cTravelTimeAfter = $userDataAfter->getCTravelTime();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    if(is_array($response) && isset($response['error']))
    {
        if($cPrisonTimeAfter != $cPrisonTimeBefore) $response['error']['alert']['message'] .= counterActive("PrisonTime", $cPrisonTimeAfter);
        if($cTravelTimeAfter != $cTravelTimeBefore) $response['error']['alert']['message'] .= counterActive("TravelTime", $cTravelTimeAfter);
    }
    else
    {
        if($cPrisonTimeAfter != $cPrisonTimeBefore) $response['alert']['message'] .= counterActive("PrisonTime", $cPrisonTimeAfter);
        if($cTravelTimeAfter != $cTravelTimeBefore) $response['alert']['message'] .= counterActive("TravelTime", $cTravelTimeAfter);
    }

    require_once __DIR__ . '/.inc.foot.ajax.php';
    if(is_array($response) && isset($response['error']))
        $twigVars['response'] = $response['error'];
    else
        $twigVars['response'] = $response;

    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
