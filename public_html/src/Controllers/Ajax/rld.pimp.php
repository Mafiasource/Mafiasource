<?PHP

use src\Business\UserService;
use src\Business\RedLightDistrictService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) || (isset($_POST['security-token']) && isset($_POST['pimp-for'])))
{
    $userService = new UserService();
    $rld = new RedLightDistrictService();
    
    if(isset($_POST['pimp-for']))
        $response = $rld->pimpWhores($_POST, $_POST['pimp-for']);
    else
    {
        require_once __DIR__ . '/.valuesAnimation.php';
        
        $userDataBefore = $userData;
        $pimpDataBefore = $rld->getRedLightDistrictPageInfo();
        $attemptsBefore = $pimpDataBefore->getPimpAttempts();
        $amountBefore = $pimpDataBefore->getPimpAmount();
        $levelBefore = $pimpDataBefore->getPimpLv();
        $xpBefore = $pimpDataBefore->getPimpXpRaw();
        $cPimpWhoresBefore = $userDataBefore->getCPimpWhores();
        $whoresStreetBefore = $pimpDataBefore->getWhoresStreet();
        $whoresWindowBefore = $pimpDataBefore->getWhoresRLD();
        $totalWhoresBefore = ($whoresStreetBefore+$whoresWindowBefore);
        
        $response = $rld->pimpWhores($_POST, false);
        
        $userDataAfter = $user->getUserData();
        $pimpDataAfter = $rld->getRedLightDistrictPageInfo();
        $attemptsAfter = $pimpDataAfter->getPimpAttempts();
        $amountAfter = $pimpDataAfter->getPimpAmount();
        $levelAfter = $pimpDataAfter->getPimpLv();
        $xpAfter = $pimpDataAfter->getPimpXpRaw();
        $cPimpWhoresAfter = $userDataAfter->getCPimpWhores();
        $whoresStreetAfter = $pimpDataAfter->getWhoresStreet();
        $whoresWindowAfter = $pimpDataAfter->getWhoresRLD();
        $totalWhoresAfter = ($whoresStreetAfter+$whoresWindowAfter);
        
        if(!isset($response[0]['alert']['message']))
        {
            $rObj = $response;
            $response = null;
            $response[0] = $rObj;
        }
        if($cPimpWhoresAfter !=  $cPimpWhoresBefore) $response[0]['alert']['message'] .= counter("PimpWhores", $cPimpWhoresAfter);
        if($xpAfter != $xpBefore) $response[0]['alert']['message'] .= "<script>$('#pimpPercent .progress-bar').css('width', '".$xpAfter."%');</script>";
        if($levelAfter != $levelBefore) valueAnimation("#pimpLv", $levelBefore, $levelAfter);
        if($attemptsBefore != $attemptsAfter) valueAnimation("#pimpAttempts", $attemptsBefore, $attemptsAfter);
        if($amountBefore != $amountAfter) valueAnimation("#pimpAmount", $amountBefore, $amountAfter);
        if($whoresStreetBefore != $whoresStreetAfter) valueAnimation("#whoresStreet", $whoresStreetBefore, $whoresStreetAfter);
        if($whoresWindowBefore != $whoresWindowAfter) valueAnimation("#whoresWindow", $whoresWindowBefore, $whoresWindowAfter);
        if($totalWhoresBefore != $totalWhoresAfter) valueAnimation("#totalWhores", $totalWhoresBefore, $totalWhoresAfter);
    }
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
