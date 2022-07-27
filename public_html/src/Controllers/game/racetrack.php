<?PHP

use src\Business\CasinoService;
use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.php';

$possession = new PossessionService();
$possessionId = 14; // Racetrack | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID(), $userData->getCityID()); // Possess table record id |Stad bezitting
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$casinoService = new CasinoService($pData);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->racetrackLangs()); // Extend base langs
$casinoReplaces = array(
    array('part' => number_format($userData->getCash(), 0, '', ','), 'message' => $language->possessionsLangs()['CASINO_INFO'], 'pattern' => '/{money}/'),
    array('part' => number_format($pData->getPossessDetails()->getStake(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{max}/')
);
$twigVars['langs']['RACETRACK_INFO'] = $route->replaceMessageParts($casinoReplaces);
$twigVars['colors'] = $casinoService->racetrackColors;
$twigVars['blackFonts'] = $casinoService->racetrackBlackFontColors;
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;

print_r($twig->render('/src/Views/game/racetrack.twig', $twigVars));
