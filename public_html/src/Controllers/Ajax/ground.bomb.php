<?PHP

use src\Business\UserService;
use src\Business\UserCoreService;
use src\Business\GroundService;
use src\Business\StateService;

require_once __DIR__ . '/.inc.head.ajax.php';

$state = new StateService();

if(isset($_POST['state']) && in_array($_POST['state'], $state->allowedStates) && isset($_POST['groundID']) && isset($_POST['bombs']) && isset($_POST['security-token']))
{
    $userService = new UserService();
    $stateID = $state->getStateIdByName($_POST['state']);
    $groundID = (int)$_POST['groundID'];
    $ground = new GroundService($stateID, $groundID);
    $groundArea = $ground->getGroundDataByStateIdAndGroundID($stateID, $groundID);
    if(is_object($groundArea))
    {
        $userDataBefore = $userData;
        $cashMoneyBefore = $userDataBefore->getCash();
        $cBombardementBefore = $userDataBefore->getCBombardement();
        $uRankpointsBefore = $userDataBefore->getRankpoints();
        $uRankIDBefore = $userDataBefore->getRankID();
        
        $response = $ground->bombGround($_POST, $groundArea);
        
        $userDataAfter = $user->getUserData();
        $cashMoneyAfter = $userDataAfter->getCash();
        $cBombardementAfter = $userDataAfter->getCBombardement();
        $uRankpointsAfter = $userDataAfter->getRankpoints();
        $uRankIDAfter = $userDataAfter->getRankID();
        
        $rankInfo = UserCoreService::getRankInfoByRankpoints($uRankpointsAfter);
        
        require_once __DIR__ . '/.moneyAnimation.php';
        if($cBombardementAfter !=  $cBombardementBefore) $response['alert']['message'] .= counter("Bombardement", $cBombardementAfter);
        if($uRankpointsBefore != $uRankpointsAfter) $response['alert']['message'] .="<script>$('#rankBar .progress-bar').css('width', '".$rankInfo["procenten"]."%');</script>";
        if($uRankIDBefore != $uRankIDAfter) valueAnimationReplace("#rankName", $uRankIDBefore, $uRankIDAfter, $rankInfo["rank"]);
        
        require_once __DIR__ . '/.inc.foot.ajax.php';
        $twigVars['bombGroundResponse'] = $response;
        $twigVars['langs'] = array_merge($twigVars['langs'], $language->groundLangs()); // Extend base langs
        $twigVars['ground'] = $ground->getGroundDataByStateIdAndGroundID($stateID, $groundID);
        $twigVars['statusPage'] = $userService->getStatusPageInfo();
        
        print_r($twig->render('/src/Views/game/Ajax/ground.area.twig', $twigVars));
    }
}
