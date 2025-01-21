<?PHP

use src\Business\UserService;
use src\Business\SmuggleService;

require_once __DIR__ . '/.inc.head.ajax.php';

$tabs = array(1 => 'drugs', 'liquids', 'fireworks', 'weapons', 'exotic-animals');

if(isset($_POST['id']) && isset($_POST['amount']) && isset($_POST['type']) && in_array($_POST['type'], $tabs) &&
    isset($_POST['security-token']) && $_POST['id'] > 0 && $_POST['id'] <= 54 && (isset($_POST['buy']) || isset($_POST['sell']))
)
{
    $userService = new UserService();
    $smuggle = new SmuggleService($userData->getCityID(), $_POST['type'], $userData->getDonatorID());
    
    require_once __DIR__ . '/.valuesAnimation.php'; //Animate multiple values
    
    $userDataBefore = $userData;
    $sDataBefore = $smuggle->getSmugglingPageInfo(array_search(htmlentities($_POST['type'], ENT_QUOTES, 'UTF-8'), $tabs));
    $cashMoneyBefore = $userDataBefore->getCash();
    $profitsBefore = $sDataBefore['user']->getSmugglingProfit();
    $smugglingUnitsBefore = $sDataBefore['user']->getSmugglingUnits();
    $inPossessionBefore = $sDataBefore['unitsInfo']->getInPossession();
    $maxCapacityBefore = $sDataBefore['unitsInfo']->getMaxCapacity();
    $levelBefore = $sDataBefore['user']->getSmugglingLv();
    $xpBefore = $sDataBefore['user']->getSmugglingXpRaw();
    
    $response = $smuggle->buyOrSellSmuggleUnits($_POST);
    
    $userDataAfter = $user->getUserData();
    $sDataAfter = $smuggle->getSmugglingPageInfo(array_search(htmlentities($_POST['type'], ENT_QUOTES, 'UTF-8'), $tabs));
    $cashMoneyAfter = $userDataAfter->getCash();
    $profitsAfter = $sDataAfter['user']->getSmugglingProfit();
    $smugglingUnitsAfter = $sDataAfter['user']->getSmugglingUnits();
    $inPossessionAfter = $sDataAfter['unitsInfo']->getInPossession();
    $maxCapacityAfter = $sDataAfter['unitsInfo']->getMaxCapacity();
    $levelAfter = $sDataAfter['user']->getSmugglingLv();
    $xpAfter = $sDataAfter['user']->getSmugglingXpRaw();

    require_once __DIR__ . '/.moneyAnimation.php';
    if(!isset($response[0]['alert']['message']))
    {
        $rObj = $response;
        $response = null;
        $response[0] = $rObj;
    }
    if($xpAfter != $xpBefore) $response[0]['alert']['message'] .= "<script>$('#smugglingPercent .progress-bar').css('width', '".$xpAfter."%');</script>";
    if($levelAfter != $levelBefore) valueAnimation("#smugglingLv", $levelBefore, $levelAfter);
    if($smugglingUnitsBefore != $smugglingUnitsAfter) valueAnimation("#smugglingUnitsSmuggled", $smugglingUnitsBefore, $smugglingUnitsAfter);
    if($profitsBefore != $profitsAfter) valueAnimation("#smugglingProfits", $profitsBefore, $profitsAfter);
    if($inPossessionBefore != $inPossessionAfter) valueAnimation("#smugglingUnitsPossession", $inPossessionBefore, $inPossessionAfter);
    if($maxCapacityBefore != $maxCapacityAfter) valueAnimation("#smugglingUnitsAvailable", $maxCapacityBefore, $maxCapacityAfter);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
