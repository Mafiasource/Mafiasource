<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Entities\Business;
use src\Entities\BusinessHistory;
use src\Entities\BusinessNews;
use src\Entities\BusinessStock;

class StockExchangeDAO extends DBConfig
{
    protected $con = "";            
    private $dbh = "";
    private $lang = "en";
    private $dateFormat = "%d-%m-%Y"; // SQL Format
    
    public function __construct()
    {
        global $lang;
        global $connection;
        
        $this->con = $connection;                        
        $this->dbh = $connection->con;
        $this->lang = $lang;
        if($this->lang == 'en') $this->dateFormat = "%m-%d-%Y"; // SQL Format
    }
    
    public function __destruct()
    {
        $this->dbh = null;
        $this->con = null;
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(*) AS `total` FROM `business` WHERE `id` > 0");
            return $row['total'];
        }
    }
    
    public function getBusinessStocks()
    {
        if(isset($_SESSION['UID']))
        {
            $businesses = $this->con->getData("SELECT `name`, `last_price`, `close_price`, `high_price`, `low_price` FROM `business` ORDER BY `id` ASC LIMIT 30");
            
            $list = array();
            foreach($businesses AS $b)
            {
                $business = new Business();
                $business->setName($b['name']);
                $business->setLastPrice(number_format($b['last_price'], "2", '.', ','));
                $business->setClosePrice(number_format($b['close_price'], "2", '.', ','));
                $business->setHighPrice(number_format($b['high_price'], "2", '.', ','));
                $business->setLowPrice(number_format($b['low_price'], "2", '.', ','));
                $business->setDifference(number_format($b['last_price'] - $b['close_price'], "2", '.', ','));
                $business->setDiffPercent(number_format((100 * ($b['last_price'] - $b['close_price']) / $b['close_price']),"2",',','.'));
                
                array_push($list, $business);
            }
            return $list;
        }
    }
    
    public function getBusinessStockByName($name)
    {
        if(isset($_SESSION['UID']))
        {
            $b = $this->con->getDataSR("
                SELECT `id`, `name`, `opening_price`, `last_price`, `close_price`, `high_price`, `low_price` FROM `business` WHERE `name`= :name LIMIT 1
            ", array(':name' => $name));
            
            if($b['name'])
            {
                $business = new Business();
                $business->setId($b['id']);
                $business->setName($b['name']);
                $business->setOpeningPrice(number_format($b['opening_price'], "2", '.', ','));
                $business->setLastPrice(number_format($b['last_price'], "2", '.', ','));
                $business->setClosePrice(number_format($b['close_price'], "2", '.', ','));
                $business->setHighPrice(number_format($b['high_price'], "2", '.', ','));
                $business->setLowPrice(number_format($b['low_price'], "2", '.', ','));
                $business->setDifference(number_format($b['last_price'] - $b['close_price'], "2", '.', ','));
                $business->setDiffPercent(number_format((100 * ($b['last_price'] - $b['close_price']) / $b['close_price']),"2",',','.'));
                
                return $business;
            }
        }
        return false;
    }
    
    public function getBusinessHistoryByBusinessID($id)
    {
        if(isset($_SESSION['UID']))
        {
            $businessHistory = $this->con->getData("
                SELECT `close_day`, `highest_day`, `lowest_day`, `date`, DATE_FORMAT(DATE_ADD(`date`, INTERVAL -24 HOUR), '".$this->dateFormat."') AS `dateMinus1d`
                FROM `business_history`
                WHERE `businessID`= :id AND `date`> :datePast ORDER BY `date` ASC, `id` ASC LIMIT 15
            ", array(':id' => $id, ':datePast' => date('Y-m-d', strtotime('-15 days'))));
            
            $list = array();
            foreach($businessHistory AS $b)
            {
                $business = new BusinessHistory();
                $business->setBusinessID($id);
                $business->setCloseDay(number_format($b['close_day'], "2", '.', ','));
                $business->setHighestDay(number_format($b['highest_day'], "2", '.', ','));
                $business->setLowestDay(number_format($b['lowest_day'], "2", '.', ','));
                $business->setAverageDay(number_format((($b['highest_day'] + $b['lowest_day']) / 2), "2", '.', ','));
                $business->setDate($b['dateMinus1d']);
                
                array_push($list, $business);
            }
            return $list;
        }
        return false;
    }
    
    public function getBusinessNews()
    {
        if(isset($_SESSION['UID']))
        {
            $businessNews = $this->con->getData("
                SELECT n.`id`, n.`description_".$this->lang."` AS `description`, b.`name`
                FROM `business_news` AS n
                LEFT JOIN `business` AS b
                ON(n.`businessID`=b.`id`)
                WHERE n.`id`>'0' AND n.`type`>='1' AND n.`type`<='4'
            ");
            
            $list = array();
            foreach($businessNews AS $n)
            {
                $news = new BusinessNews();
                $news->setId($n['id']);
                $news->setDescription($n['description']);
                $news->setBusiness($n['name']);
                
                array_push($list, $news);
            }
            return $list;
        }
    }
    
    public function getStocksInPossession()
    {
        if(isset($_SESSION['UID']))
        {
            $stocks = $this->con->getDataSR("SELECT SUM(`amount`) AS `amnt` FROM `business_stock` WHERE `userID`= :uid", array(':uid' => $_SESSION['UID']));
            return $stocks['amnt'];
        }
        return false;
    }
    
    public function getStocksInPossessionByBusinessID($id)
    {
        if(isset($_SESSION['UID']))
        {
            $stocks = $this->con->getDataSR("SELECT SUM(`amount`) AS `amnt` FROM `business_stock` WHERE `userID`= :uid AND `businessID`= :bid", array(':uid' => $_SESSION['UID'], ':bid' => $id));
            return $stocks['amnt'];
        }
        return false;
    }
    
    public function buyStocks($businessData, $stocks)
    {
        if(isset($_SESSION['UID']))
        {
            $priceSet = $this->con->getDataSR("
                SELECT `id` FROM `business_stock` WHERE `userID`= :uid AND `businessID`= :bid AND `payed_ea`= :pea ORDER BY `id` DESC LIMIT 1
            ", array(':uid' => $_SESSION['UID'], ':bid' => $businessData->getId(), ':pea' => (string)str_replace(',', '.', (string)$businessData->getLastPrice())));
            if(isset($priceSet['id']) && $priceSet['id'] > 0)
                $qry = "UPDATE `business_stock` SET `amount`=`amount`+ :amnt WHERE `userID`= :uid AND `businessID`= :bid AND `payed_ea`= :pea ORDER BY `id` DESC LIMIT 1";
            else
                $qry = "INSERT INTO `business_stock` (`userID`, `businessID`, `payed_ea`, `amount`) VALUES (:uid, :bid, :pea, :amnt)";
            
            $this->con->setData("
                " . $qry . ";
                UPDATE `user` SET `bank`=`bank`- :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(
                ':uid' => $_SESSION['UID'], ':bid' => $businessData->getId(), ':pea' => $businessData->getLastPrice(), ':amnt' => $stocks,
                ':price' => $businessData->getLastPrice() * $stocks
            ));
        }
    }
    
    public function sellStocks($businessData, $stocks)
    {
        if(isset($_SESSION['UID']))
        {
            $totToSell = $stocks;
            while($totToSell > 0)
            {
                $selectMax = $this->con->getDataSR("
                    SELECT `id`, `amount`
                    FROM `business_stock`
                    WHERE `userID`= :uid AND `businessID`= :bid AND `amount`= (SELECT MAX(`amount`) FROM `business_stock` WHERE `userID`= :uid AND `businessID`= :bid)
                    LIMIT 1
                ", array(':uid' => $_SESSION['UID'], ':bid' => $businessData->getId()));
                $sold = $selectMax['amount'];
                $uniqueID = $selectMax['id'];
                if(($sold - $totToSell) > 0)
                {
                    $this->con->setData("UPDATE `business_stock` SET `amount`=`amount`- :amnt WHERE `id`= :sid", array(':amnt' => $totToSell, ':sid' => $uniqueID));
                    $totToSell = 0;
                }
                else
                {
                    $this->con->setData("DELETE FROM `business_stock` WHERE `id`= :sid LIMIT 1", array(':sid' => $uniqueID));
                    $totToSell = $totToSell - $sold;
                }
            }
            $this->con->setData("
                UPDATE `user` SET `bank`=`bank`+ :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $_SESSION['UID'], ':price' => $businessData->getLastPrice() * $stocks));
        }
    }
    
    public function getPortfolioStocks()
    {
        if(isset($_SESSION['UID']))
        {
            $businesses = $this->con->getData("
                SELECT bs.`id` AS `stockID`, b.`id`, b.`name`, b.`last_price`
                FROM `business_stock` AS bs
                LEFT JOIN `business` AS b
                ON(bs.`businessID` = b.`id`)
                WHERE bs.`userID`= :uid
                GROUP BY bs.`businessID`
            ", array(':uid' => $_SESSION['UID']));
            
            $list = array();
            foreach($businesses AS $b)
            {
                $business = new Business();
                $business->setId($b['id']);
                $business->setName($b['name']);
                $business->setLastPrice(number_format($b['last_price'], "2", '.', ','));
                
                $prices = $this->con->getData("
                    SELECT `id`, `amount`, `payed_ea` FROM `business_stock` WHERE `userID`= :uid AND `businessID`= :bid
                ", array(':uid' => $_SESSION['UID'], ':bid' => $b['id']));
                
                $i = $totStocks = $buyPrice = 0;
                $pricesArr = $priceList = array();
                foreach($prices AS $price)
                {
                    $businessStock = new BusinessStock();
                    $businessStock->setId($price['id']);
                    $businessStock->setUserID($_SESSION['UID']);
                    $businessStock->setBusinessID($b['id']);
                    $businessStock->setPriceEa($price['payed_ea']);
                    $businessStock->setAmount($price['amount']);
                    
                    array_push($priceList, $businessStock);
                    
                    $pricesArrKey = isset($pricesArr[$i]) ? $pricesArr[$i] : 0;
                    $pricesArr[$i] = $pricesArrKey + ($businessStock->getAmount() * $businessStock->getPriceEa());
                    $totStocks = $totStocks + $businessStock->getAmount();
                    $i++;
                }
                
                foreach($pricesArr AS $value)
                    $buyPrice = $buyPrice + $value;
                
                $priceNow = $b['last_price'] * $totStocks;
                $profit = $priceNow - $buyPrice;
                if($buyPrice != 0)
                    $percent = number_format(($profit / $buyPrice) * 100 ,"2",',','.');
                else
                    $percent = 0;
                
                $business->setStocks($priceList);
                $business->setStocksCount($totStocks);
                $business->setStocksPrice($buyPrice);
                $business->setStocksProfit($profit);
                $business->setStocksPercent($percent);
                
                array_push($list, $business);
            }
            return $list;
        }
        return false;
    }
}
