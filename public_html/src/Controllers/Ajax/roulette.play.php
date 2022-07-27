<?PHP

use src\Business\UserService;
use src\Business\CasinoService;
use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.ajax.php';

$possession = new PossessionService();
$possessionId = 15; //Roulette | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID(), $userData->getCityID()); // Possess table record id |Stad bezitting
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$casinoService = new CasinoService($pData);

$acceptPost = true;
for($i = 0; $i <= 36; $i++)
    if(!isset($_POST['n' . $i]))
        $acceptPost = false;
        
foreach($casinoService->rouletteComboFields AS $k => $v)
    if(!isset($_POST[$v]))
        $acceptPost = false;

if(isset($_POST['security-token']) && $acceptPost === true && isset($_POST['play-roulette']))
{
    $userService = new UserService();
    
    require_once __DIR__ . '/.valuesAnimation.php';
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    
    $response = $casinoService->playRoulette($_POST, $pData);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    $twigVars['colspan'] = 3;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.in-table.response.twig', $twigVars));
    
    require_once __DIR__ . '/.moneyAnimation.php';
    if(isset($cashMoneyBefore) && isset($cashMoneyAfter) && $cashMoneyBefore != $cashMoneyAfter) valueAnimation("#casinoStakeAmount", $cashMoneyBefore, $cashMoneyAfter);
}
