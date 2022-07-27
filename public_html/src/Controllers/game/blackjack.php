<?PHP

use src\Business\CasinoService;
use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.php';

$possession = new PossessionService();
$possessionId = 17; // Blackjack | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID(), $userData->getCityID()); // Possess table record id |Stad bezitting
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$casinoService = new CasinoService($pData);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->blackjackLangs()); // Extend base langs
$casinoReplaces = array(
    array('part' => number_format($userData->getCash(), 0, '', ','), 'message' => $language->possessionsLangs()['CASINO_INFO'], 'pattern' => '/{money}/'),
    array('part' => number_format($pData->getPossessDetails()->getStake(), 0, '', ','), 'message' => FALSE, 'pattern' => '/{max}/')
);
$twigVars['langs']['BLACKJACK_INFO'] = $route->replaceMessageParts($casinoReplaces);
$twigVars['winningCombinations'] = $casinoService->slotMachineWinningCombinations;
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;

unset($_SESSION['blackjack']); // Always unset any possibly unfinished session var 'blackjack' on bj page request

print_r($twig->render('/src/Views/game/blackjack.twig', $twigVars));
