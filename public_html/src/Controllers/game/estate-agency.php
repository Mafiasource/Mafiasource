<?PHP

use src\Business\PossessionService;
use src\Business\ResidenceService;

require_once __DIR__ . '/.inc.head.php';

$possession = new PossessionService();
$possessionId = 6; //Makelaardij | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID()); // Possess table record id |Staat bezitting
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$residence = new ResidenceService();
$residences = $residence->getResidencePage();

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->estateAgencyLangs()); // Extend base langs
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;
$twigVars['residence'] = $residences;

print_r($twig->render('/src/Views/game/estate-agency.twig', $twigVars));
