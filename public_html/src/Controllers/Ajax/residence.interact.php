<?PHP

use src\Business\UserCoreService;
use src\Business\ResidenceService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && (isset($_POST['id']) && isset($_POST['buy']) || isset($_POST['sell']) || isset($_POST['equip'])))
{
    require_once __DIR__ . '/.valuesAnimation.php';
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    $uRankpointsBefore = $userDataBefore->getRankpoints();
    $uRankIDBefore = $userDataBefore->getRankID();
    
    $residence = new ResidenceService();
    if(isset($_POST['buy']))
        $response = $residence->buyResidence($_POST);
    else if(isset($_POST['sell']))
        $response = $residence->sellResidence($_POST);
    else if(isset($_POST['equip']))
        $response = $residence->equipResidence($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    $uRankpointsAfter = $userDataAfter->getRankpoints();
    $uRankIDAfter = $userDataAfter->getRankID();
    
    $rankInfo = UserCoreService::getRankInfoByRankpoints($uRankpointsAfter);
    
    require_once __DIR__ . '/.moneyAnimation.php';
    if($uRankpointsBefore != $uRankpointsAfter) $response['alert']['message'] .="<script>$('#rankBar .progress-bar').css('width', '".$rankInfo["procenten"]."%');</script>";
    if($uRankIDBefore != $uRankIDAfter) valueAnimationReplace("#rankName", $uRankIDBefore, $uRankIDAfter, $rankInfo["rank"]);
    $response['alert']['message'] .= "<script>var elem = $('html, body');elem.stop().animate({scrollTop:0}, '300', 'swing');</script>";
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/scroll-top.twig', $twigVars));
}
