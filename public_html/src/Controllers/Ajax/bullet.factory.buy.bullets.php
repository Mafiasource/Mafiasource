<?PHP

use src\Business\BulletFactoryService;
use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['bullets']))
{
    $bf = new BulletFactoryService();
    
    $possession = new PossessionService();
    $possessionId = 1; //Bullet Factory | Possession logic
    $possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID()); // Possess table record id
    $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
    
    $userDataBefore = $userData;
    $bfInfoBefore = $possession->getBulletFactoryInfoByPossessID($possessId);
    require_once __DIR__ . '/.valuesAnimation.php';
    $cashMoneyBefore = $userDataBefore->getCash();
    $factoryBulletsBefore = $bfInfoBefore->getBullets();
        
    $response = $bf->buyBullets($_POST);
    
    $userDataAfter = $user->getUserData();
    $bfInfoAfter = $possession->getBulletFactoryInfoByPossessID($possessId);
    $cashMoneyAfter = $userDataAfter->getCash();
    $factoryBulletsAfter = $bfInfoAfter->getBullets();

    require_once __DIR__ . '/.moneyAnimation.php';

    if($factoryBulletsBefore != $factoryBulletsAfter)
    {
        valueAnimation("#factoryBullets", $factoryBulletsBefore, $factoryBulletsAfter);
        valueAnimation("#listedFactoryBullets", $factoryBulletsBefore, $factoryBulletsAfter);
    }
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
