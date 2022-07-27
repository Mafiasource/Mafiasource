<?PHP

use src\Business\FamilyService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = $userData->getFamilyID();
if(!empty($_POST['security-token']) && isset($_POST['amount']) && $famID > 0)
{
    $family = new FamilyService();
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    $bankMoneyBefore = $userDataBefore->getBank();
    
    $response = $family->donateToFamily($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    $bankMoneyAfter = $userDataAfter->getBank();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
