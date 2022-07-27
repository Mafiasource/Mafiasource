<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['amount']) && isset($_POST['security-token']) && isset($_POST['action']))
{
    $userService = new UserService();
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    $bankMoneyBefore = $userDataBefore->getBank();
    
    $response = $userService->transferSwissMoney($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    $bankMoneyAfter = $userDataAfter->getBank();
    
    $bankData = $userService->getBankPageInfo();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    if(isset($twigVars['response']['alert']['message']))
        $twigVars['response']['alert']['message'] .= $twig->render("/src/Views/game/js/swiss.bank.transfer.twig", array(
            'selectTag' => "select#swissBankTransferAction",
            'inputField' => "input#swissBankTransferValueField",
            'userData' => $userDataAfter,
            'bankData' => $bankData,
            'swissLeft' => Round($bankData->getSwissBankMax() - $bankData->getSwissBank()),
            'action' => isset($_POST['action']) && $_POST['action'] === 'putMoney' ? "putMoney" : "getMoney"
        ));
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
