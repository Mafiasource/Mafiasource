<?PHP

//Opgelet, ajax controller zowel voor garage als voor vehicle shop.

use src\Business\GarageService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(!empty($_POST['securityToken']) && isset($_POST['action']) && isset($_POST['id']))
{
    $garage = new GarageService();
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    $bankMoneyBefore = $userDataBefore->getBank();
    $tuned = $_POST['action'] == "tune" ? true : null;
    if($tuned)
    {
        require_once __DIR__ . '/.valuesAnimation.php'; //Animate multiple values
        
        $id = (int)$_POST['id'];
        $vehicleDataBefore = $garage->getVehicleInGarageById($id);
        $hpBefore = $vehicleDataBefore->getVehicle()->getHorsepower();
        $tsBefore = $vehicleDataBefore->getVehicle()->getTopspeed();
        $acBefore = $vehicleDataBefore->getVehicle()->getAcceleration();
        $ctBefore = $vehicleDataBefore->getVehicle()->getControl();
        $brBefore = $vehicleDataBefore->getVehicle()->getBreaking();
    }
    
    $response = $garage->interactWithVehicle($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    $bankMoneyAfter = $userDataAfter->getBank();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    
    if($tuned)
    {
        $vehicleDataAfter = $garage->getVehicleInGarageById($id);
        $hpAfter = $vehicleDataAfter->getVehicle()->getHorsepower();
        $tsAfter = $vehicleDataAfter->getVehicle()->getTopspeed();
        $acAfter = $vehicleDataAfter->getVehicle()->getAcceleration();
        $ctAfter = $vehicleDataAfter->getVehicle()->getControl();
        $brAfter = $vehicleDataAfter->getVehicle()->getBreaking();
        
        if(!isset($response[0]['alert']['message']))
        {
            $rObj = $response;
            $response = null;
            $response[0] = $rObj;
        }
        if($acAfter != $acBefore) $response[0]['alert']['message'] .= $twig->render('/src/Views/game/js/progress.twig', array('id' => "accelerationPercent-" . $id, 'percent' => $acAfter));
        if($ctAfter != $ctBefore) $response[0]['alert']['message'] .= $twig->render('/src/Views/game/js/progress.twig', array('id' => "controlPercent-" . $id, 'percent' => $ctAfter));
        if($brAfter != $brBefore) $response[0]['alert']['message'] .= $twig->render('/src/Views/game/js/progress.twig', array('id' => "breakingPercent-" . $id, 'percent' => $brAfter));
        if($hpBefore != $hpAfter) valueAnimation("#horsepower-" . $id, $hpBefore, $hpAfter);
        if($tsBefore != $tsAfter) valueAnimation("#topspeed-" . $id, $tsBefore, $tsAfter);
    }
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    if($tuned)
    {
        $selectedTune = $garage->getSelectedTune($_POST);
        $twigVars['v'] = $vehicleDataAfter;
        $twigVars['id'] = $id;
        $twigVars['tuneShop'] = $garage->tuneShop;
        $twigVars['langs'] = array_merge($langs, $language->garageLangs());
        if(is_array($selectedTune) && array_key_exists(0, array_keys($selectedTune))) $twigVars['activeCarousel'] = array_keys($selectedTune)[0];
        
        print_r($twig->render('/src/Views/game/Ajax/garage.tune.shop.twig', $twigVars));
    }
    else
        print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
