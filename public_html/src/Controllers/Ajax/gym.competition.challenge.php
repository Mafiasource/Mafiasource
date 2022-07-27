<?PHP

use src\Business\UserService;
use src\Business\GymCompetitionService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['competitionID']) && isset($_POST['security-token']))
{
    $userService = new UserService();
    $gymCompetition = new GymCompetitionService();
    
    $userDataBefore = $userData;
    $cashMoneyBefore = $userDataBefore->getCash();
    
    $response = $gymCompetition->challengeCompetition($_POST);
    
    $userDataAfter = $user->getUserData();
    $cashMoneyAfter = $userDataAfter->getCash();
    
    require_once __DIR__ . '/.moneyAnimation.php';

    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
