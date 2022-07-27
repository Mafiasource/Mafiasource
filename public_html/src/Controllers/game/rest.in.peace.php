<?PHP

use src\Business\UserService;
use src\Business\ShoutboxService;
use src\Business\PollService;

/** Altered inc.head with custom redirection(s) **/
if($security->checkSSL() === false) exit(0);
if(!$user->checkLoggedSession())
{
    $route->headTo('home');
    exit(0);
}
else
{
    if(!is_object($userData) || $userData->getHealth() > 0)
    {
        $route->headTo('home');
        exit(0);
    }
    $travelCounter = counterClean("dTravelTime", $userData->getCTravelTime());
    $serverTime = date($user->dateFormat);
    $shoutboxService = new ShoutboxService();
    $familyShoutboxService = new ShoutboxService();
    $familyShoutboxService->setFamilyID($userData->getFamilyID());            
    $lastShoutboxID = $shoutboxService->getLastMessageID();
    $lastFamilyShoutboxID = $familyShoutboxService->getLastMessageID();
    $pollService = new PollService();
    $unvotedPoll = $pollService->userHasUnvotedPoll();
    /** //END Altered inc.head **/
    
    if(isset($_POST['security-token']) && isset($_POST['username']) && isset($_POST['profession']) && isset($_POST['submit-rip']))
    {
        $userService = new UserService();
        $response = $userService->validateRestInPeace($_POST);
        if(is_bool($response) && $response == TRUE)
        {
            $route->headTo('game');
            exit(0);
        }
        else
            $route->createActionMessage($route->errorMessage($response));
        
        $route->headTo('rest_in_peace');
        exit(0);
    }
    $username = isset($_SESSION['rip']['username']) ? $_SESSION['rip']['username'] : $userData->getUsername();
    $profession = isset($_SESSION['rip']['profession']) ? $_SESSION['rip']['profession'] : $userData->getCharType();
    
    require_once __DIR__ . '/.inc.foot.php';
    
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->restInPeaceLangs());
    $twigVars['username'] = $username;
    $twigVars['profession'] = $profession;
    
    print_r($twig->render('/src/Views/game/rest.in.peace.twig', $twigVars));
}
