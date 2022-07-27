<?PHP

use src\Business\GymCompetitionService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['competitionType']) && isset($_POST['stake']) && isset($_POST['security-token']))
{
    $gymCompetition = new GymCompetitionService();
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    
    $response = $gymCompetition->createGymCompetition($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    
    require_once __DIR__ . '/.moneyAnimation.php';

    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
