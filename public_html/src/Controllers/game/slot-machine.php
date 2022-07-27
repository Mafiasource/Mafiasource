<?PHP

use src\Business\CasinoService;
use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.php';

$possession = new PossessionService();
$possessionId = 16; //Slot machine | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID(), $userData->getCityID()); // Possess table record id |Stad bezitting
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$casinoService = new CasinoService($pData);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->slotMachineLangs()); // Extend base langs
$casinoReplaces = array(
    array('part' => number_format($userData->getCash(), 0, '', ','), 'message' => $language->possessionsLangs()['CASINO_INFO'], 'pattern' => '/{money}/'),
    array('part' => number_format($pData->getPossessDetails()->getStake(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{max}/')
);
$twigVars['langs']['SLOT_MACHINE_INFO'] = $route->replaceMessageParts($casinoReplaces);
$twigVars['winningCombinations'] = $casinoService->slotMachineWinningCombinations;
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;

print_r($twig->render('/src/Views/game/slot-machine.twig', $twigVars));
