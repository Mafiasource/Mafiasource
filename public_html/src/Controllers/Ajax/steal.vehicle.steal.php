<?PHP

use src\Business\UserService;
use src\Business\UserCoreService;
use src\Business\StealVehicleService;

require_once __DIR__ . '/.inc.head.ajax.php';

$sv = new StealVehicleService();

if(isset($_POST['id']) && isset($_POST['security-token']))
{
    $userService = new UserService();
    
    $userDataBefore = $userData;
    $svDataBefore = $sv->getStealVehiclesPageInfo()['user'];
    require_once __DIR__ . '/.valuesAnimation.php'; //Animate multiple values
    $cashMoneyBefore = $userDataBefore->getCash();
    $profitsBefore = $svDataBefore->getVehiclesProfit();
    $rankpointsBefore = $svDataBefore->getVehiclesRankpoints();
    $levelBefore = $svDataBefore->getVehiclesLv();
    $xpBefore = $svDataBefore->getVehiclesXpRaw();
    $cStealVehiclesBefore =$userDataBefore->getCStealVehicles();
    $cPrisonTimeBefore = $userDataBefore->getCPrisonTime();
    $uRankpointsBefore = $userDataBefore->getRankpoints();
    $uRankIDBefore = $userDataBefore->getRankID();
    
    $response = $sv->stealVehicle($_POST);
    
    $userDataAfter = $user->getUserData();
    $svDataAfter = $sv->getStealVehiclesPageInfo()['user'];
    $cashMoneyAfter = $userDataAfter->getCash();
    $profitsAfter = $svDataAfter->getVehiclesProfit();
    $sfRatioAfter = $svDataAfter->getVehiclesSFRatio();
    $rankpointsAfter = $svDataAfter->getVehiclesRankpoints();
    $levelAfter = $svDataAfter->getVehiclesLv();
    $xpAfter = $svDataAfter->getVehiclesXpRaw();
    $cStealVehiclesAfter = $userDataAfter->getCStealVehicles();
    $cPrisonTimeAfter = $userDataAfter->getCPrisonTime();
    $uRankpointsAfter = $userDataAfter->getRankpoints();
    $uRankIDAfter = $userDataAfter->getRankID();
    
    $rankInfo = UserCoreService::getRankInfoByRankpoints($uRankpointsAfter);

    require_once __DIR__ . '/.moneyAnimation.php';
    if(!isset($response[0]['alert']['message']))
    {
        $rObj = $response;
        $response = null;
        $response[0] = $rObj;
    }
    if($cStealVehiclesAfter !=  $cStealVehiclesBefore) $response[0]['alert']['message'] .= counter("StealVehicles", $cStealVehiclesAfter);
    if($cPrisonTimeAfter != $cPrisonTimeBefore) $response[0]['alert']['message'] .= counterActive("PrisonTime", $cPrisonTimeAfter);
    if($xpAfter != $xpBefore) $response[0]['alert']['message'] .= "<script>$('#vehiclesPercent .progress-bar').css('width', '".$xpAfter."%');</script>";
    if($levelAfter != $levelBefore) valueAnimation("#vehiclesLv", $levelBefore, $levelAfter);
    if($rankpointsBefore != $rankpointsAfter) valueAnimation("#vehicleRankPoints", $rankpointsBefore, $rankpointsAfter);
    if($profitsBefore != $profitsAfter) valueAnimation("#vehicleProfits", $profitsBefore, $profitsAfter);
    if($uRankpointsBefore != $uRankpointsAfter) $response[0]['alert']['message'] .="<script>$('#rankBar .progress-bar').css('width', '".$rankInfo["procenten"]."%');</script>";
    if($uRankIDBefore != $uRankIDAfter) valueAnimationReplace("#rankName", $uRankIDBefore, $uRankIDAfter, $rankInfo["rank"]);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
