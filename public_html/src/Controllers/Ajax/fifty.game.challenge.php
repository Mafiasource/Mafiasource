<?PHP

use src\Business\FiftyGameService;
use src\Business\RedLightDistrictService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['gameID']))
{
    $fiftyGame = new FiftyGameService();
    $gameData = $fiftyGame->getFiftyGameById((int)$_POST['gameID']);
    
    if(is_object($gameData))
    {
        require_once __DIR__ . '/.valuesAnimation.php';
        $userDataBefore = $userData;
        switch($gameData->getType())
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
        
        $response = $fiftyGame->interactGame($_POST);
        
        $userDataAfter = $user->getUserData();
        switch($gameData->getType())
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
}
