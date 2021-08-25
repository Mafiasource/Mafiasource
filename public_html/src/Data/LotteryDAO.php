<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Data\PossessionDAO;
use src\Entities\Lottery;
use src\Entities\LotteryWinner;

class LotteryDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    
    public function __construct()
    {
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getRecordsCount($type)
    {
        
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(`id`) AS `total` FROM `lottery` WHERE `type`= :type", array(':type' => $type));
            
            return $row['total'];
        }
    }
    
    public function getLotteryTicketByType($typeID)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `id` FROM `lottery` WHERE `type`= :type AND `userID`= :uid LIMIT 1", array(':type' => $typeID, ':uid' => $_SESSION['UID']));
            
            if(isset($row['id']) && $row['id'] > 0)
            {
                $lottery = new Lottery();
                $lottery->setId($row['id']);
                $lottery->setType($typeID);
                $lottery->setUserID($_SESSION['UID']);
                
                return $lottery;
            }
        }
        return false;
    }
    
    public function buyTicket($type, $price, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            $profitOwner = $price;
            $this->con->setData("
                INSERT INTO `lottery` (`type`, `userID`) VALUES (:type, :uid);
                UPDATE `user` SET `cash`=`cash`- :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(
                ':type' => $type, ':uid' => $_SESSION['UID'],
                ':price' => $price
            ));
            
            /** Possession logic for buying lottery ticket | pay owner if exists and not self **/
            if(is_object($pData)) $lotteryOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $lotteryOwner > 0 && $lotteryOwner != $_SESSION['UID'])
            {
                $possessionData = new PossessionDAO();
                $possessionData->applyProfitForOwner($pData, $profitOwner, $lotteryOwner);
            }
        }
    }
    
    public function getLastLotteryWinnersByType($type)
    {
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData("
                SELECT l.`id`, l.`userID`, l.`prize`, l.`place`, u.`username`
                FROM `lottery_winner` AS l
                LEFT JOIN `user` AS u
                ON(l.`userID`=u.`id`)
                WHERE l.`type`= :type AND l.`place`>='1'
                ORDER BY `place` ASC
            ", array(':type' => $type));
            
            $list = array();
            foreach($rows AS $row)
            {
                $lottery = new LotteryWinner();
                $lottery->setId($row['id']);
                $lottery->setType($type);
                $lottery->setUserID($row['userID']);
                $lottery->setUsername($row['username']);
                $lottery->setPrize($row['prize']);
                $lottery->setPlace($row['place']);
                
                array_push($list, $lottery);
            }
            return $list;
        }
    }
}
