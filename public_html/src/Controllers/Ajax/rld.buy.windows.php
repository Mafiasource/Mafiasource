<?PHP

use src\Business\RedLightDistrictService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['amount']) && isset($_POST['security-token']))
{
    $rld = new RedLightDistrictService();
    
    require_once __DIR__ . '/.valuesAnimation.php';
    
    $userDataBefore = $userData;
    $pimpDataBefore = $rld->getRedLightDistrictPageInfo();
    $cashMoneyBefore = $userDataBefore->getCash();
    $whoresStreetBefore = $pimpDataBefore->getWhoresStreet();
    $whoresWindowBefore = $pimpDataBefore->getWhoresRLD();
    $totalWhoresBefore = ($whoresStreetBefore+$whoresWindowBefore);
    
    $response = $rld->placeWhoresBehindWindow($_POST);
    
    $userDataAfter = $user->getUserData();
    $pimpDataAfter = $rld->getRedLightDistrictPageInfo();
    $cashMoneyAfter = $userDataAfter->getCash();
    $whoresStreetAfter = $pimpDataAfter->getWhoresStreet();
    $whoresWindowAfter = $pimpDataAfter->getWhoresRLD();
    $totalWhoresAfter = ($whoresStreetAfter+$whoresWindowAfter);

    require_once __DIR__ . '/.moneyAnimation.php';

    if($whoresStreetBefore != $whoresStreetAfter) valueAnimation("#whoresStreet", $whoresStreetBefore, $whoresStreetAfter);
    if($whoresWindowBefore != $whoresWindowAfter) valueAnimation("#whoresWindow", $whoresWindowBefore, $whoresWindowAfter);
    if($totalWhoresBefore != $totalWhoresAfter) valueAnimation("#totalWhores", $totalWhoresBefore, $totalWhoresAfter);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
