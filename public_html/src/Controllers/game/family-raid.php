<?PHP

use src\Business\FamilyRaidService;
use src\Business\GarageService;

require_once __DIR__ . '/.inc.head.php';

if($userData->getFamilyID() == 0)
{
    $route->headTo('family-list');
    exit(0);
}

$famRaid = new FamilyRaidService();
$members = $famRaid->getFamilyRaidMemberList();
$familyRaid = $famRaid->getActiveFamilyRaid();
if(is_object($familyRaid) && $familyRaid->getDriver() == $userData->getUsername())
{
    $garage = new GarageService();
    $vehicles = $garage->getAllVehiclesInGarageByState($userData->getStateID());
}
$available = true;
if($userData->getCFamilyRaid() > time())
    $available = false;

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->familyRaidLangs()); // Extend base langs
$twigVars['members'] = $members;
$twigVars['familyRaid'] = $familyRaid;
$twigVars['bombs'] = $famRaid->bombs;
$twigVars['weapons'] = $famRaid->weapons;
$twigVars['available'] = $available;
if(isset($vehicles))
    $twigVars['vehicles'] = $vehicles;
if($available === false)
    $twigVars['waitingTime'] = $userData->getCFamilyRaid();

print_r($twig->render('/src/Views/game/family-raid.twig', $twigVars));
