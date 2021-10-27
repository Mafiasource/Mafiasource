<?PHP
 
namespace src\Business;

use app\config\Routing;
use src\Data\StockExchangeDAO;

class StockExchangeService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new StockExchangeDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function interactStock($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->stockExchangeLangs();
        
        $stocks = (int)$post['stocks'];
        $business = $security->xssEscape($post['business']);
        $businessData = $this->data->getBusinessStockByName($business);
        
        $buy = isset($post['buy']) ? $post['buy'] : null;
        $sell = isset($post['sell']) ? $post['sell'] : null;
        
        $maxStocks = self::getStockLimitByDonatorID($userData->getDonatorID());
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if($stocks < 100 || $stocks > $maxStocks)
        {
            $error = $l['AMOUNT_RANGE_BETWEEN_100_AND_5M'];
        }
        
        if(isset($buy))
        {
            if($stocks % 100 != 0)
                $error = $l['NOT_DEVISABLE_BY_100'];
            
            if($stocks * $businessData->getLastPrice() > $userData->getBank())
                $error = $langs['NOT_ENOUGH_MONEY_BANK'];
            
            if(($this->data->getStocksInPossession() + $stocks) > $maxStocks)
                $error = $l['CANNOT_BUY_OVER_LIMIT'];
        }
        
        if(isset($sell))
        {
            if($stocks % 100 != 0)
                $error = $l['NOT_DEVISABLE_BY_100'];
            
            if($this->data->getStocksInPossessionByBusinessID($businessData->getId()) < $stocks)
                $error = $l['DONT_OWN_THAT_MANY'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            
            $price = $stocks * $businessData->getLastPrice();
            if(isset($buy))
            {
                $this->data->buyStocks($businessData, $stocks);
                
                $replaces = array(
                    array('part' => number_format($stocks, 0, '', ','), 'message' => $l['BOUGHT_STOCKS_SUCCESS'], 'pattern' => '/{stocks}/'),
                    array('part' => $businessData->getName(), 'message' => FALSE, 'pattern' => '/{business}/'),
                    array('part' => number_format($price, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
            }
            elseif(isset($sell))
            {
                $this->data->sellStocks($businessData, $stocks);
                $replaces = array(
                    array('part' => number_format($stocks, 0, '', ','), 'message' => $l['SOLD_STOCKS_SUCCESS'], 'pattern' => '/{stocks}/'),
                    array('part' => $businessData->getName(), 'message' => FALSE, 'pattern' => '/{business}/'),
                    array('part' => number_format($price, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
            }
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public static function getStockLimitByDonatorID($id)
    {
        switch($id)
        {
            default:
            case 0:
                return 1000000;
                break;
            case 1:
                return 2500000;
                break;
            case 5:
            case 10:
                return 5000000;
                break;
        }
    }
    
    public function getBusinessStocks()
    {
        return $this->data->getBusinessStocks();
    }
    
    public function getBusinessStockByName($name)
    {
        return $this->data->getBusinessStockByName($name);
    }
    
    public function getBusinessHistoryByBusinessID($id)
    {
        return $this->data->getBusinessHistoryByBusinessID($id);
    }
    
    public function getBusinessNews()
    {
        return $this->data->getBusinessNews();
    }
    
    public function getStocksInPossessionByBusinessID($id)
    {
        return $this->data->getStocksInPossessionByBusinessID($id);
    }
    
    public function getPortfolioStocks()
    {
        return $this->data->getPortfolioStocks();
    }
}
