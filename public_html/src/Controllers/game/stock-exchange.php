<?PHP

use src\Business\StockExchangeService;

require_once __DIR__ . '/.inc.head.php';

$stockExchange = new StockExchangeService();

$open = true;
if(date('H') >= 20 || (date('H') >= 0 && date('H') < 6))
    $open = false;

$tab = "stocks";
switch($route->getRouteName())
{
    default: case 'stock-exchange':
        $tab = "stocks";
        $stocks = $stockExchange->getBusinessStocks();
        break;
    case 'stock-exchange-business':
        $tab = "stocks";
        $name = $route->requestGetParam(4);
        $stock = $stockExchange->getBusinessStockByName($name);
        if(!is_object($stock))
        {
            $route->headTo('not_found');
            exit(0);
        }
        $stockHistory = $stockExchange->getBusinessHistoryByBusinessID($stock->getId());
        $inPosession = $stockExchange->getStocksInPossessionByBusinessID($stock->getId());
        break;
    case 'stock-exchange-news':
        $tab = "news";
        $news = $stockExchange->getBusinessNews();
        break;
    case 'stock-exchange-portfolio':
        $tab = "portfolio";
        $portfolioStocks = $stockExchange->getPortfolioStocks();
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->stockExchangeLangs()); // Extend base langs
$twigVars['open'] = $open;
if(isset($stocks)) $twigVars['stocks'] = $stocks;
if(isset($news)) $twigVars['news'] = $news;
if(isset($stock)) $twigVars['stock'] = $stock;
if(isset($stockHistory)) $twigVars['stockHistory'] = $stockHistory;
if(isset($inPosession)) $twigVars['inPossession'] = $inPosession;
if(isset($portfolioStocks)) $twigVars['portfolio'] = $portfolioStocks;

print_r($twig->render('/src/Views/game/stock-exchange.twig', $twigVars));
