<?PHP

use src\Business\FamilyService;
use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.php';

$hasRights = $hasBossRights = FALSE;
if($userData->getFamilyBoss() === true || $userData->getFamilyUnderboss() === true) $hasRights = TRUE;
if($userData->getFamilyBoss() === true) $hasBossRights = TRUE;

if($userData->getFamilyID() == 0 || $hasRights === FALSE)
{
    $route->headTo('family-list');
    exit(0);
}

$family = new FamilyService();
$familyData = $family->getFamilyDataByName($userData->getFamily());

$tab = "members";
switch($route->getRouteName())
{
    case 'family-management':
        $tab = "members";
        $joinedMembers = $family->getJoinedMembers();
        $invitedMembers = $family->getInvitedMembers();
        $kickList = $family->getKickList();
        break;
    case 'family-management-profile':
        $tab = "profile";
        $familyPage = $family->getFamilyPageDataByName($userData->getFamily());
        break;
    case 'family-management-mass-message':
        $tab = "mass-message";
        $possession = new PossessionService();
        $possessionId = 12; // Telecobedrijf | Possession logic
        $possessId = $possession->getPossessIdByPossessionId($possessionId); // Possess table record id
        $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
        break;
    case 'family-management-message':
        $tab = "message";
        $familyMessage = $family->getFamilyMessage();
        break;
    case 'family-management-alliances':
        $tab = "alliances";
        $alliances = $family->getAlliances($userData->getFamilyID());
        break;
    case 'family-management-manage-family':
        $tab = "manage-family";
        $familyPage = $family->getFamilyPageDataByName($userData->getFamily());
        $familyMembers = $family->getFamilyMembersByFamilyId($userData->getFamilyID());
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->familyLangs()); // Extend base langs
if($tab === "mass-message")
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->messagesLangs());
$twigVars['familyData'] = $familyData;
$twigVars['hasRights'] = $hasRights;
$twigVars['hasBossRights'] = $hasBossRights;
if(isset($joinedMembers)) $twigVars['joinedMembers'] = $joinedMembers;
if(isset($invitedMembers)) $twigVars['invitedMembers'] = $invitedMembers;
if(isset($kickList)) $twigVars['kickList'] = $kickList;
if(isset($familyPage)) $twigVars['familyPage'] = $familyPage;
if(isset($familyMessage)) $twigVars['familyMessage'] = $familyMessage;
if(isset($familyMembers)) $twigVars['familyMembers'] = $familyMembers;
if(isset($alliances)) $twigVars['alliances'] = $alliances;
if(isset($possessId)) $twigVars['possessId'] = $possessId;
if(isset($pData)) $twigVars['possessionData'] = $pData;

print_r($twig->render('/src/Views/game/family-management.twig', $twigVars));
