<?PHP

use src\Business\FiftyGameService;
use src\Business\RedLightDistrictService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['type']) && isset($_POST['amount']))
{
    $fiftyGame = new FiftyGameService();
    $type = (int)$_POST['type'];
    
    require_once __DIR__ . '/.valuesAnimation.php';
    $userDataBefore = $userData;
    switch($type)
    {
        default:
        case 0:
            $cashMoneyBefore = $userDataBefore->getCash();
            break;
        case 1:
            $rld = new RedLightDistrictService();
            $whoresBefore = $rld->getRedLightDistrictPageInfo()->getWhoresStreet();
            break;
        case 2:
            $honorPointsBefore = $userDataBefore->getHonorPoints();
            break;
    }
    
    $response = $fiftyGame->createGame($_POST);
    
    $userDataAfter = $user->getUserData();
    switch($type)
    {
        default:
        case 0:
            $cashMoneyAfter = $userDataAfter->getCash();
            break;
        case 1:
            $rld = new RedLightDistrictService();
            $whoresAfter = $rld->getRedLightDistrictPageInfo()->getWhoresStreet();
            break;
        case 2:
            $honorPointsAfter = $userDataAfter->getHonorPoints();
            break;
    }
    
    require_once __DIR__ . '/.moneyAnimation.php';
    if(isset($cashMoneyBefore) && isset($cashMoneyAfter) && $cashMoneyBefore != $cashMoneyAfter) valueAnimation("#fiftyStakeAmount", $cashMoneyBefore, $cashMoneyAfter);
    if(isset($whoresBefore) && isset($whoresAfter) && $whoresBefore != $whoresAfter) valueAnimation("#fiftyStakeAmount", $whoresBefore, $whoresAfter);
    if(isset($honorPointsBefore) && isset($honorPointsAfter) && $honorPointsBefore != $honorPointsAfter) valueAnimation("#fiftyStakeAmount", $honorPointsBefore, $honorPointsAfter);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
