<?PHP

use src\Business\UserService;
use src\Business\PossessionService;
use src\Business\FamilyService;

require_once __DIR__ . '/.inc.head.php';

$userService = new UserService();
$possession = new PossessionService();
$possessionId = 7; // Hospital | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID(), $userData->getCityID()); // Possess table record id
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$family = new FamilyService();
if($userData->getFamilyID() !== 0)
    $familyMembers = $family->getFamilyMembersByFamilyId($userData->getFamilyID());

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->hospitalLangs()); // Extend base langs
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;
$twigVars['healCostsPercent'] = $userService->healCostsPercent;
if(isset($familyMembers)) $twigVars['familyMembers'] = $familyMembers;

print_r($twig->render('/src/Views/game/hospital.twig', $twigVars));
