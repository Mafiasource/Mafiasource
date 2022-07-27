<?PHP

use src\Business\UserService;
use src\Business\UserCoreService;
use src\Business\FamilyRaidService;

require_once __DIR__ . '/.inc.head.ajax.php';

$leaderCheck =
    (isset($_POST['new-driver']) && isset($_POST['change-driver'])) ||
    (isset($_POST['new-bombExpert']) && isset($_POST['change-bombExpert'])) ||
    (isset($_POST['new-weaponExpert']) && isset($_POST['change-weaponExpert'])) ||
    (isset($_POST['quit']) || (isset($_POST['quit']) || isset($_POST['start'])) || isset($_POST['quit-confirm']));

$driverCheck = (isset($_POST['vehicleID']) && isset($_POST['driver-accept']) || isset($_POST['driver-deny'])) ||
    (isset($_POST['driver-quit']) || isset($_POST['driver-quit-confirm']));

$bombExpertCheck = (isset($_POST['bombType']) && isset($_POST['bombExpert-accept']) || isset($_POST['bombExpert-deny'])) ||
    (isset($_POST['bombExpert-quit']) || isset($_POST['bombExpert-quit-confirm']));

$weaponExpertCheck =
    (isset($_POST['weaponType']) && isset($_POST['bullets']) && isset($_POST['weaponExpert-accept']) || isset($_POST['weaponExpert-deny'])) ||
    (isset($_POST['weaponExpert-quit']) || isset($_POST['weaponExpert-quit-confirm']));

$acceptPost = false;
if($leaderCheck || $driverCheck || $bombExpertCheck || $weaponExpertCheck)
    $acceptPost = true;

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && isset($_POST['familyRaidID']) && $famID > 0 && $acceptPost)
{
    $famRaid = new FamilyRaidService();
    
    require_once __DIR__ . '/.valuesAnimation.php';
    $userDataBefore = $userData;
    $cFamilyRaidBefore = $userDataBefore->getCFamilyRaid();
    $cashMoneyBefore = $userDataBefore->getCash();
    $bankMoneyBefore = $userDataBefore->getBank();
    $uRankpointsBefore = $userDataBefore->getRankpoints();
    $uRankIDBefore = $userDataBefore->getRankID();
    
    if($leaderCheck)
    {
        $userService = new UserService();
        $response = $famRaid->leaderInteractFamilyRaid($_POST);
    }
    elseif($driverCheck)
        $response = $famRaid->driverInteractFamilyRaid($_POST);
    elseif($bombExpertCheck)
        $response = $famRaid->bombExpertInteractFamilyRaid($_POST);
    elseif($weaponExpertCheck)
        $response = $famRaid->weaponExpertInteractFamilyRaid($_POST);
    
    $userDataAfter = $user->getUserData();
    $cFamilyRaidAfter = $userDataAfter->getCFamilyRaid();
    $cashMoneyAfter = $userDataAfter->getCash();
    $bankMoneyAfter = $userDataAfter->getBank();
    $uRankpointsAfter = $userDataAfter->getRankpoints();
    $uRankIDAfter = $userDataAfter->getRankID();
    
    $rankInfo = UserCoreService::getRankInfoByRankpoints($uRankpointsAfter);
    
    require_once __DIR__ . '/.moneyAnimation.php';
    if($cFamilyRaidBefore !=  $cFamilyRaidAfter) $response['alert']['message'] .= counter("FamilyRaid", $cFamilyRaidAfter);
    if($uRankpointsBefore != $uRankpointsAfter) $response['alert']['message'] .="<script>$('#rankBar .progress-bar').css('width', '".$rankInfo["procenten"]."%');</script>";
    if($uRankIDBefore != $uRankIDAfter) valueAnimationReplace("#rankName", $uRankIDBefore, $uRankIDAfter, $rankInfo["rank"]);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
