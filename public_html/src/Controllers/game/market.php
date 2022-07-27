<?PHP

use src\Business\MarketService;

require_once __DIR__ . '/.inc.head.php';

$market = new MarketService();
$tab = "credits";
$offerRecords = $market->getMarketOffersByType(0); // 0 credits
$requestRecords = $market->getMarketRequestsByType(0);
switch($route->getRouteName())
{
    case 'market':
        $tab = "credits";
        $typeName = "Credits";
        break;
    case 'market-whores':
        $tab = "whores";
        $typeName = $langs['WHORES'];
        $offerRecords = $market->getMarketOffersByType(1); // 1 hoes
        $requestRecords = $market->getMarketRequestsByType(1);
        break;
    case 'market-honor-points':
        $tab = "honor-points";
        $typeName = $langs['HONOR_POINTS'];
        $offerRecords = $market->getMarketOffersByType(2); // 2 honor points
        $requestRecords = $market->getMarketRequestsByType(2);
        break;
    default:
        $tab = "credits";
        $typeName = "Credits";
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['typeName'] = $typeName;
$twigVars['offers'] = $offerRecords;
$twigVars['requests'] = $requestRecords;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->marketLangs()); // Extend base langs
$twigVars['langs']['PLACE_OR_REQUEST_X_ON_MARKET'] = $route->replaceMessagePart(strtolower($typeName), $twigVars['langs']['PLACE_OR_REQUEST_X_ON_MARKET'], '/{typeName}/');
$twigVars['langs']['NO_ITEMS_IN_MARKET'] = $route->replaceMessagePart(strtolower($typeName), $twigVars['langs']['NO_ITEMS_IN_MARKET'], '/{typeName}/');
$twigVars['langs']['NO_REQUESTS_IN_MARKET'] = $route->replaceMessagePart(strtolower($typeName), $twigVars['langs']['NO_REQUESTS_IN_MARKET'], '/{typeName}/');
$twigVars['langs']['ITEMS_SALE_INFO'] = $route->replaceMessagePart(strtolower($typeName), $twigVars['langs']['ITEMS_SALE_INFO'], '/{typeName}/');
$twigVars['langs']['ITEMS_REQUEST_INFO'] = $route->replaceMessagePart(strtolower($typeName), $twigVars['langs']['ITEMS_REQUEST_INFO'], '/{typeName}/');

print_r($twig->render('/src/Views/game/market.twig', $twigVars));
