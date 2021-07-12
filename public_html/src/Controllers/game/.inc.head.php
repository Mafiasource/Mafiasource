<?PHP

use src\Business\ShoutboxService;
use src\Business\PollService;

if($security->checkSSL() === false) exit(0);

if(!$user->checkLoggedSession() || !is_object($userData))
{
    $route->headTo('home');
    exit(0);
}
elseif(OFFLINE && !in_array($_SERVER['REMOTE_ADDR'], DEVELOPER_IPS))
{
    $route->headTo('not_found');
    exit(0);
}
else
{
    $travelCounter = counterClean("dTravelTime", $userData->getCTravelTime());
    $serverTime = date($user->dateFormat);
    $user->checkStolenVehicleInQueue();
    $shoutboxService = new ShoutboxService();
    $lastShoutboxID = $shoutboxService->getLastMessageID();
    $familyShoutboxService = new ShoutboxService();
    $familyShoutboxService->setFamilyID($userData->getFamilyID());            
    $lastFamilyShoutboxID = $familyShoutboxService->getLastMessageID();
    $pollService = new PollService();
    $unvotedPoll = $pollService->userHasUnvotedPoll();
}
