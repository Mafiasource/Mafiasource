<?PHP

use src\Business\LotteryService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['type']))
{
    $lottery = new LotteryService();
    $cashMoneyBefore = $userData->getCash();
    
    $response = $lottery->buyTicket($_POST);
    
    switch($_POST['type'])
    {
        default: case 0:
            $tab = "day";
            $type = 0;
            $ratios = $lottery->dailyPotRatios;
            $ticketPrice = $lottery->dayPrice;
            break;
        case 1:
            $tab = "week";
            $type = 1;
            $ratios = $lottery->weeklyPotRatios;
            $ticketPrice = $lottery->weekPrice;
            break;
    }
    
    $cashMoneyAfter = $user->getUserData()->getCash();
        
    require_once __DIR__ . '/.moneyAnimation.php';
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $ll = $language->lotteryLangs();
    $twigVars['tab'] = $tab;
    $twigVars['langs'] = array_merge($twigVars['langs'], $ll); // Extend base langs
    $twigVars['response'] = $response;
    $twigVars['ticketsSold'] = $lottery->getRecordsCount($type);
    $twigVars['ticketPrice'] = $ticketPrice;
    $twigVars['ratios'] = $ratios;
    $twigVars['superpot'] = $lottery->weeklyDrawing;
    $twigVars['ticket'] = $lottery->getLotteryTicketByType($type);
    $twigVars['type'] = $type;
    $twigVars['places'] = array(1 => $ll['FIRST'], $ll['SECOND'], $ll['THIRD'], $ll['FOURTH'], $ll['FIFTH'], $ll['SIXTH'], $ll['SEVENTH'], $ll['EIGHTH'], $ll['NINTH']);
    
    print_r($twig->render('/src/Views/game/Ajax/lottery.twig', $twigVars));
}
