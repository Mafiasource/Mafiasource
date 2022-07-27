<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']))
{
    $userService = new UserService();
    
    $userDataBefore = $userData;
    $bankMoneyBefore = $userDataBefore->getBank();
        
    $response = $userService->openLuckybox($_POST);
    
    $userDataAfter = $user->getUserData();
    $bankMoneyAfter = $userDataAfter->getBank();

    require_once __DIR__ . '/.moneyAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->luckyboxLangs()); // Extend base langs
    $twigVars['response'] = $response;
    $twigVars['luckybox'] = $userDataAfter->getLuckybox();
    $twigVars['chanceList'] = $userService->getLuckyboxChanceList();
    
    print_r($twig->render('/src/Views/game/Ajax/luckybox.twig', $twigVars));
}
