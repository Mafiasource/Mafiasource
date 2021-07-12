<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Entities\Market;

class MarketDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    private $dateFormat = "%d-%m-%Y %H:%i:%s";
    private $typesDb = array(0 => "credits", "whoresStreet", "honorPoints");
    
    public $types = array();
    
    public function __construct()
    {
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
        global $route;
        $this->lang = $route->getLang();
        if($this->lang == 'en') $this->dateFormat = "%m-%d-%Y %r";
        
        global $language;
        global $langs;
        $this->types[0] = "Credits";
        $this->types[1] = $langs['WHORES'];
        $this->types[2] = $langs['HONOR_POINTS'];
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getRecordsCount()
    {
        
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `market` WHERE `deleted` = '0' AND `active` = '1'");
            $statement->execute();
            $row = $statement->fetch();
            return $row['total'];
        }
    }
    
    public function getMarketOffersByType($typeID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT m.`id`, m.`userID`, m.`amount`, m.`price`, m.`anonymous`, m.`date`, u.`username`, DATE_FORMAT( m.`date`, '".$this->dateFormat."' ) AS `putDate`
                FROM `market` AS m
                LEFT JOIN `user` AS u
                ON (m.userID = u.id)
                WHERE `type`= :typeID AND `requested`='0'
                ORDER BY m.`date` DESC
            ");
            $statement->execute(array(':typeID' => $typeID));
            
            $list = array();
            while($row = $statement->fetch())
            {
                $market = new Market();
                $market->setId($row['id']);
                $market->setType($this->types[$typeID]);
                $market->setRequested(false);
                $market->setUserID($row['userID']);
                $market->setUsername($row['username']);
                $market->setAmount($row['amount']);
                $market->setPrice($row['price']);
                $market->setAnonymous(false);
                if($row['anonymous'] == 1) $market->setAnonymous(true);
                $market->setDate($row['putDate']);
                array_push($list,$market);
            }
            return $list;
        }
    }
    
    public function getMarketRequestsByType($typeID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT m.`id`, m.`userID`, m.`amount`, m.`price`, m.`anonymous`, m.`date`, u.`username`, DATE_FORMAT( m.`date`, '".$this->dateFormat."' ) AS `putDate`
                FROM `market` AS m
                LEFT JOIN `user` AS u
                ON (m.userID = u.id)
                WHERE `type`= :typeID AND `requested`='1'
                ORDER BY m.`date` DESC
            ");
            $statement->execute(array(':typeID' => $typeID));
            
            $list = array();
            while($row = $statement->fetch())
            {
                $market = new Market();
                $market->setId($row['id']);
                $market->setType($this->types[$typeID]);
                $market->setRequested(false);
                $market->setUserID($row['userID']);
                $market->setUsername($row['username']);
                $market->setAmount($row['amount']);
                $market->setPrice($row['price']);
                $market->setAnonymous(false);
                if($row['anonymous'] == 1) $market->setAnonymous(true);
                $market->setDate($row['putDate']);
                array_push($list,$market);
            }
            return $list;
        }
    }
    
    public function addMarketItem($typeID,$amount,$price,$anon)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("INSERT INTO `market` (`type`,`requested`,`userID`,`amount`,`price`,`anonymous`,`date`) VALUES (:tid,:req,:uid,:amnt,:price,:anon,:date)");
            $statement->execute(array(':tid' => $typeID, ':req' => 0, ':uid' => $_SESSION['UID'], ':amnt' => $amount, ':price' => $price, ':anon' => $anon, ':date' => date('Y-m-d H:i:s')));
            
            $statement = $this->dbh->prepare("UPDATE `user` SET `".$this->typesDb[$typeID]."`=`".$this->typesDb[$typeID]."`- :amnt WHERE `id`= :uid");
            $statement->execute(array(':amnt' => $amount, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function requestMarketItem($typeID,$amount,$price,$anon)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("INSERT INTO `market` (`type`,`requested`,`userID`,`amount`,`price`,`anonymous`,`date`) VALUES (:tid,:req,:uid,:amnt,:price,:anon,:date)");
            $statement->execute(array(':tid' => $typeID, ':req' => 1, ':uid' => $_SESSION['UID'], ':amnt' => $amount, ':price' => $price, ':anon' => $anon, ':date' => date('Y-m-d H:i:s')));
            
            $statement = $this->dbh->prepare("UPDATE `user` SET `cash`=`cash`- :price WHERE `id`= :uid");
            $statement->execute(array(':price' => $price, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function getMarketItemById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT m.`id`, m.`type`, m.`requested`, m.`userID`, m.`amount`, m.`price`, m.`anonymous`, m.`date`, u.`username`, DATE_FORMAT( m.`date`, '".$this->dateFormat."' ) AS `putDate`
                FROM `market` AS m
                LEFT JOIN `user` AS u
                ON (m.userID = u.id)
                WHERE m.`id`= :id
                LIMIT 1
            ");
            $statement->execute(array(':id' => $id));
            $row = $statement->fetch();
            
            if(isset($row['id']) && $row['id'] > 0)
            {
                $market = new Market();
                $market->setId($row['id']);
                $market->setType($row['type']);
                $market->setRequested($row['requested']);
                $market->setUserID($row['userID']);
                $market->setUsername($row['username']);
                $market->setAmount($row['amount']);
                $market->setPrice($row['price']);
                $market->setAnonymous(false);
                if($row['anonymous'] == 1) $market->setAnonymous(true);
                $market->setDate($row['putDate']);
                
                return $market;
            }
        }
    }
    
    public function buyMarketItem($data)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("DELETE FROM `market` WHERE `id`= :id");
            $statement->execute(array(':id' => $data->getId()));
            
            $statement = $this->dbh->prepare("UPDATE `user` SET `cash`=`cash`+ :price WHERE `id`= :muid ");
            $statement->execute(array(':price' => $data->getPrice(), ':muid' => $data->getUserID()));
            
            $statement = $this->dbh->prepare("UPDATE `user` SET `".$this->typesDb[$data->getType()]."`=`".$this->typesDb[$data->getType()]."`+ :amount, `cash`=`cash`- :price WHERE `id`= :uid ");
            $statement->execute(array(':amount' => $data->getAmount(), ':price' => $data->getPrice(), ':uid' => $_SESSION['UID']));
        }
    }
    
    public function acceptMarketItem($data)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("DELETE FROM `market` WHERE `id`= :id");
            $statement->execute(array(':id' => $data->getId()));
            
            $statement = $this->dbh->prepare("UPDATE `user` SET `".$this->typesDb[$data->getType()]."`=`".$this->typesDb[$data->getType()]."`+ :amount WHERE `id`= :muid ");
            $statement->execute(array(':amount' => $data->getAmount(), ':muid' => $data->getUserID()));
            
            $statement = $this->dbh->prepare("UPDATE `user` SET `cash`=`cash`+ :price, `".$this->typesDb[$data->getType()]."`=`".$this->typesDb[$data->getType()]."`- :amount WHERE `id`= :uid ");
            $statement->execute(array(':price' => $data->getPrice(), ':amount' => $data->getAmount(), ':uid' => $_SESSION['UID']));
        }
    }
}
