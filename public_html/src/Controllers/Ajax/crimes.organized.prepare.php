<?PHP

use src\Business\UserService;
use src\Business\CrimeService;
use src\Business\GarageService;

// ORG CRIME TYPE 2 & 3 preparations processed here | ALL PREPARATIONS BEFORE COMMIT!

require_once __DIR__ . '/.inc.head.ajax.php';

// This ajax controller will have a longer than useual checkup.
$setupCheck = (isset($_POST['prepare-organized-crime']) && ((isset($_POST['job']) && isset($_POST['username'])) || (isset($_POST['getaway']) && isset($_POST['ground']) && isset($_POST['intel']))));
$readyUpCheck = (isset($_POST['ready-up']) && (isset($_POST['vehicleID']) || isset($_POST['weaponType'])));
$changeParticipantCheck = (
    (isset($_POST['username']) && isset($_POST['change-participant'])) || (isset($_POST['getaway']) && isset($_POST['change-driver'])) || (isset($_POST['ground']) && isset($_POST['change-ground'])) || 
    (isset($_POST['intel']) && isset($_POST['change-intel']))
);
$stopCheck = (isset($_POST['stop']) || isset($_POST['stop-confirm']));
$acceptCheck = (isset($_POST['accept']) && (isset($_POST['vehicleID']) || isset($_POST['weaponType']) || isset($_POST['intelType'])));
$denyCheck = (isset($_POST['deny']) || isset($_POST['deny-confirm']));

$acceptPost = false;
if($setupCheck || $readyUpCheck || $changeParticipantCheck || $stopCheck || $acceptCheck || $denyCheck)
    $acceptPost = true;

if(isset($_POST['id']) && !empty($_POST['security-token']) &&  $acceptPost)
{
    $userService = new UserService();
    $crime = new CrimeService();
    
    if($setupCheck)
        $response = $crime->prepareOrganizedCrime($_POST);
    elseif($readyUpCheck || $changeParticipantCheck || $stopCheck)
        $response = $crime->userInteractOrganizedCrime($_POST);
    elseif($acceptCheck || $denyCheck)
        $response = $crime->participantInteractOrganizedCrime($_POST);
    
    if($_POST['id'] == 2 || isset($_POST['stop-confirm']))
    {
        $crimesPage = $crime->getCrimesPageInfo(true);
        foreach($crimesPage['crimes'] AS $c)
        {
            $c->setActive(false);
            if(isset($_POST['id']) && $_POST['id'] == $c->getId())
                $c->setActive(true);
        }
        $friends = $userService->getFriendsList();
        $garage = new GarageService();
        $vehicles = $garage->getAllVehiclesInGarageByState($userData->getStateID());
        $weapons = $crime->weapons;
    }
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    if(isset($crimesPage)) $twigVars['crimesPage'] = $crimesPage;
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->crimesLangs()); // Extend base langs
    if(isset($friends)) $twigVars['friends'] = $friends;
    if(isset($vehicles)) $twigVars['vehicles'] = $vehicles;
    if(isset($weapons)) $twigVars['weapons'] = $weapons;
    
    if($_POST['id'] == 2 || isset($_POST['stop-confirm']))
        print_r($twig->render('/src/Views/game/Ajax/organized.crimes.twig', $twigVars));
    else
        print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
