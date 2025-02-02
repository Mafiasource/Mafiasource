<?PHP

use src\Business\GarageService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(!empty($_POST['security-token']) && (isset($_POST['sell-garage']) || isset($_POST['sell-family-garage'])))
{
    $garage = new GarageService();
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    $bankMoneyBefore = $userDataBefore->getBank();
    
    $familyID = ($_POST['sell-family-garage'] ?? false) ? $userData->getFamilyID() : false;
    $response = $garage->sellGarage($_POST, $familyID);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    $bankMoneyAfter = $userDataAfter->getBank();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
