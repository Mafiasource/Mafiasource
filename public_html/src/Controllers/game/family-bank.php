<?PHP

use src\Business\FamilyService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($userData->getFamilyID() == 0)
{
    $route->headTo('family-list');
    exit(0);
}

$family = new FamilyService();
$familyData = $family->getFamilyDataByName($userData->getFamily());

$hasRights = FALSE;
if($userData->getFamilyBoss() === true || $userData->getFamilyBankmanager() === true) $hasRights = TRUE;

$tab = "bank";
switch($route->getRouteName())
{
    default:
    case 'family-bank':
        $tab = "bank";
        $familyDonations = $family->getFamilyDonationsByFamilyId($userData->getFamilyID());
        break;
    case 'family-bank-manage':
    case 'family-bank-manage-page':
        $tab = "manage";
        $pagination = new Pagination($family, 15, 15);
        $bankManageRights = FALSE;
        $familyBankLogs = $family->getFamilyBankLogsByFamilyId($userData->getFamilyID(), $pagination->from, $pagination->to);
        if($hasRights === TRUE)
        {
            $familyMembers = $family->getFamilyMembersByFamilyId($userData->getFamilyID());
        }
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->familyLangs()); // Extend base langs
$twigVars['familyData'] = $familyData;
$twigVars['hasRights'] = $hasRights;
if(isset($pagination)) $twigVars['pagination'] = $pagination;
if(isset($familyDonations)) $twigVars['familyDonations'] = $familyDonations;
if(isset($familyBankLogs)) $twigVars['familyBankLogs'] = $familyBankLogs;
if(isset($familyMembers)) $twigVars['familyMembers'] = $familyMembers; 

print_r($twig->render('/src/Views/game/family-bank.twig', $twigVars));
