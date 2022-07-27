<?PHP

use src\Business\UserService;
use src\Business\UserCoreService;
use src\Business\CrimeService;

require_once __DIR__ . '/.inc.head.ajax.php';

$crime = new CrimeService();

if(isset($_POST['id']) && isset($_POST['security-token']))
{
    $userService = new UserService();
    
    $userDataBefore = $userData;
    $crimesDataBefore = $crime->getCrimesPageInfo()['user'];
    require_once __DIR__ . '/.valuesAnimation.php';
    $cashMoneyBefore = $userDataBefore->getCash();
    $profitsBefore = $crimesDataBefore->getCrimesProfit();
    $sfRatioBefore = $crimesDataBefore->getCrimesSFRatio();
    $rankpointsBefore = $crimesDataBefore->getCrimesRankpoints();
    $levelBefore = $crimesDataBefore->getCrimesLv();
    $xpBefore = $crimesDataBefore->getCrimesXpRaw();
    $cCrimesBefore = $userDataBefore->getCCrimes();
    $cPrisonTimeBefore = $userDataBefore->getCPrisonTime();
    $uRankpointsBefore = $userDataBefore->getRankpoints();
    $uRankIDBefore = $userDataBefore->getRankID();
    
    $response = $crime->commitCrime($_POST);
    
    $userDataAfter = $user->getUserData();
    $crimesDataAfter = $crime->getCrimesPageInfo()['user'];
    $cashMoneyAfter = $userDataAfter->getCash();
    $profitsAfter = $crimesDataAfter->getCrimesProfit();
    $sfRatioAfter = $crimesDataAfter->getCrimesSFRatio();
    $rankpointsAfter = $crimesDataAfter->getCrimesRankpoints();
    $levelAfter = $crimesDataAfter->getCrimesLv();
    $xpAfter = $crimesDataAfter->getCrimesXpRaw();
    $cCrimesAfter = $userDataAfter->getCCrimes();
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
    if($cCrimesAfter !=  $cCrimesBefore) $response[0]['alert']['message'] .= counter("Crimes", $cCrimesAfter);
    if($cPrisonTimeAfter != $cPrisonTimeBefore) $response[0]['alert']['message'] .= counterActive("PrisonTime", $cPrisonTimeAfter);
    if($xpAfter != $xpBefore) $response[0]['alert']['message'] .= "<script>$('#crimesPercent .progress-bar').css('width', '".$xpAfter."%');</script>";
    if($levelAfter != $levelBefore) valueAnimation("#crimesLv", $levelBefore, $levelAfter);
    if($rankpointsBefore != $rankpointsAfter) valueAnimation("#crimeRankPoints", $rankpointsBefore, $rankpointsAfter);
    if($profitsBefore != $profitsAfter) valueAnimation("#crimeProfits", $profitsBefore, $profitsAfter);
    if($uRankpointsBefore != $uRankpointsAfter) $response[0]['alert']['message'] .="<script>$('#rankBar .progress-bar').css('width', '".$rankInfo["procenten"]."%');</script>";
    if($uRankIDBefore != $uRankIDAfter) valueAnimationReplace("#rankName", $uRankIDBefore, $uRankIDAfter, $rankInfo["rank"]);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
