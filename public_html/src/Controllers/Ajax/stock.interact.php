<?PHP

use src\Business\StockExchangeService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['stocks']) && isset($_POST['business']) && (isset($_POST['sell']) || isset($_POST['buy'])))
{
    $stockExchange = new StockExchangeService();
    require_once __DIR__ . '/.valuesAnimation.php';
    $userDataBefore = $userData;
    $businessDataBefore = $stockExchange->getBusinessStockByName(htmlentities($_POST['business'], ENT_QUOTES, 'UTF-8'));
    $businessID = $businessDataBefore->getId();
    $bankMoneyBefore = $userDataBefore->getBank();
    $stocksBefore = $stockExchange->getStocksInPossessionByBusinessID($businessID);
    $priceBefore = $businessDataBefore->getLastPrice();
    
    $response = $stockExchange->interactStock($_POST);
    
    $userDataAfter = $user->getUserData();
    $businessDataAfter = $stockExchange->getBusinessStockByName(htmlentities($_POST['business'], ENT_QUOTES, 'UTF-8'));
    $bankMoneyAfter = $userDataAfter->getBank();
    $stocksAfter = $stockExchange->getStocksInPossessionByBusinessID($businessID);
    $priceAfter = $businessDataAfter->getLastPrice();
    
    require_once __DIR__ . '/.moneyAnimation.php';
    if($stocksBefore != $stocksAfter) valueAnimation("#stockAmount", $stocksBefore, $stocksAfter);
    if($priceBefore != $priceAfter) valueAnimation("#stockPrice", $priceBefore, $priceAfter);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
