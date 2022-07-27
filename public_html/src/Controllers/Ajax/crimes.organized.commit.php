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
    $crimesDataBefore = $crime->getCrimesPageInfo(true)['user'];
    require_once __DIR__ . '/.valuesAnimation.php';
    $cashMoneyBefore = $userDataBefore->getCash();
    $profitsBefore = $crimesDataBefore->getCrimesProfit();
    $sfRatioBefore = $crimesDataBefore->getCrimesSFRatio();
    $rankpointsBefore = $crimesDataBefore->getCrimesRankpoints();
    $levelBefore = $crimesDataBefore->getCrimesLv();
    $xpBefore = $crimesDataBefore->getCrimesXpRaw();
    $cCrimesBefore = $userDataBefore->getCCrimes();
    $cPrisonTimeBefore = $userDataBefore->getCPrisonTime();
    $cTravelTimeBefore = $userDataBefore->getCTravelTime();
    $uRankpointsBefore = $userDataBefore->getRankpoints();
    $uRankIDBefore = $userDataBefore->getRankID();
    $bulletsBefore = $crimesDataBefore->getBullets();;
    
    $response = $crime->commitOrganizedCrime($_POST);
    
    $userDataAfter = $user->getUserData();
    $crimesDataAfter = $crime->getCrimesPageInfo(true)['user'];
    $cashMoneyAfter = $userDataAfter->getCash();
    $profitsAfter = $crimesDataAfter->getCrimesProfit();
    $sfRatioAfter = $crimesDataAfter->getCrimesSFRatio();
    $rankpointsAfter = $crimesDataAfter->getCrimesRankpoints();
    $levelAfter = $crimesDataAfter->getCrimesLv();
    $xpAfter = $crimesDataAfter->getCrimesXpRaw();
    $cCrimesAfter = $userDataAfter->getCCrimes();
    $cPrisonTimeAfter = $userDataAfter->getCPrisonTime();
    $cTravelTimeAfter = $userDataAfter->getCTravelTime();
    $uRankpointsAfter = $userDataAfter->getRankpoints();
    $uRankIDAfter = $userDataAfter->getRankID();
    $bulletsAfter = $crimesDataAfter->getBullets();
    
    $rankInfo = UserCoreService::getRankInfoByRankpoints($uRankpointsAfter);
    
    require_once __DIR__ . '/.moneyAnimation.php';
    if($cCrimesAfter !=  $cCrimesBefore) $response['alert']['message'] .= counter("Crimes", $cCrimesAfter);
    if($cPrisonTimeAfter != $cPrisonTimeBefore) $response['alert']['message'] .= counterActive("PrisonTime", $cPrisonTimeAfter);
    if($cTravelTimeAfter != $cTravelTimeBefore) $response['alert']['message'] .= counterActive("TravelTime", $cTravelTimeAfter);
    if($xpAfter != $xpBefore) $response['alert']['message'] .= "<script>$('#crimesPercent .progress-bar').css('width', '".$xpAfter."%');</script>";
    if($levelAfter != $levelBefore) valueAnimation("#crimesLv", $levelBefore, $levelAfter);
    if($rankpointsBefore != $rankpointsAfter) valueAnimation("#crimeRankPoints", $rankpointsBefore, $rankpointsAfter);
    if($profitsBefore != $profitsAfter) valueAnimation("#crimeProfits", $profitsBefore, $profitsAfter);
    if($uRankpointsBefore != $uRankpointsAfter) $response['alert']['message'] .="<script>$('#rankBar .progress-bar').css('width', '".$rankInfo["procenten"]."%');</script>";
    if($uRankIDBefore != $uRankIDAfter) valueAnimationReplace("#rankName", $uRankIDBefore, $uRankIDAfter, $rankInfo["rank"]);
    if($bulletsBefore != $bulletsAfter) valueAnimation("#crimeBullets", $bulletsBefore, $bulletsAfter);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
