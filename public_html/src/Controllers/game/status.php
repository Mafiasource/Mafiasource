<?PHP

use src\Business\UserService;
use src\Business\ResidenceService;

require_once __DIR__ . '/.inc.head.php';

$userService = new UserService();
$residence = new ResidenceService();
$residenceData = $residence->getResidenceDataByUserID($userData->getId());
$residenceData->setResidence($residence->getResidenceNameById($residenceData->getResidence()));

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->statusLangs()); // Extend base langs
$twigVars['statusData'] = $userService->getStatusPageInfo();
$twigVars['residenceData'] = $residenceData;

print_r($twig->render('/src/Views/game/status.twig', $twigVars));
