<?PHP

use src\Business\FiftyGameService;
use src\Business\RedLightDistrictService;

require_once __DIR__ . '/.inc.head.php';

$fiftyGame = new FiftyGameService();
                
$tab = "cash";
switch($route->getRouteName())
{
    default: case 'fifty-games':
        $tab = "cash";
        $typeName = $langs['CASH'];
        $gameRecords = $fiftyGame->getFiftyGamesByType(0); // 0 cash
        $amountPossession = $userData->getCash();
        break;
    case 'fifty-games-whores':
        $tab = "whores";
        $typeName = $langs['WHORES'];
        $gameRecords = $fiftyGame->getFiftyGamesByType(1); // 1 hoes
        $rld = new RedLightDistrictService();
        $amountPossession = $rld->getRedLightDistrictPageInfo()->getWhoresStreet();
        break;
    case 'fifty-games-honor-points':
        $tab = "honor-points";
        $typeName = $langs['HONOR_POINTS'];
        $gameRecords = $fiftyGame->getFiftyGamesByType(2); // 2 honor points
        $amountPossession = $userData->getHonorPoints();
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['typeName'] = $typeName;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->fiftyGamesLangs()); // Extend base langs
$twigVars['fiftyGames'] = $gameRecords;
$twigVars['amountPossession'] = $amountPossession;

print_r($twig->render('/src/Views/game/fifty-games.twig', $twigVars));
