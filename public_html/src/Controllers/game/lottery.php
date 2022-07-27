<?PHP

use src\Business\LotteryService;
use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.php';

$lottery = new LotteryService();
$possession = new PossessionService();
$possessionId = 18; //Lottery | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId); // Possess table record id
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$tab = "day";
switch($route->getRouteName())
{
    default: case 'lottery':
        $tab = "day";
        $type = 0;
        $ratios = $lottery->dailyPotRatios;
        $ticketPrice = $lottery->dayPrice;
        break;
    case 'lottery-week':
        $tab = "week";
        $type = 1;
        $ratios = $lottery->weeklyPotRatios;
        $ticketPrice = $lottery->weekPrice;
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$ll = $language->lotteryLangs();
$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $ll); // Base langs uitbreiden met lottery langs
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;
$twigVars['ticketsSold'] = $lottery->getRecordsCount($type);
$twigVars['ticketPrice'] = $ticketPrice;
$twigVars['ratios'] = $ratios;
$twigVars['superpot'] = $lottery->weeklyDrawing;
$twigVars['ticket'] = $lottery->getLotteryTicketByType($type);
$twigVars['lastWinners'] = $lottery->getLastLotteryWinnersByType($type);
$twigVars['places'] = array(1 => $ll['FIRST'], $ll['SECOND'], $ll['THIRD'], $ll['FOURTH'], $ll['FIFTH'], $ll['SIXTH'], $ll['SEVENTH'], $ll['EIGHTH'], $ll['NINTH']);

print_r($twig->render('/src/Views/game/lottery.twig', $twigVars));
