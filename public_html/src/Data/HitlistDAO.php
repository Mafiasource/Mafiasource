<?PHP

namespace src\Data;

use src\Business\HitlistService;
use src\Data\config\DBConfig;
use src\Entities\Hitlist;

class HitlistDAO extends DBConfig
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

    public function getRecordsCount()
    {
        
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(*) AS `total` FROM `hitlist`");
            if(isset($row['total'])) return $row['total'];
        }
    }
    
    public function getHitlist($from, $to)
    {
        if(isset($_SESSION['UID']) && is_int($from) && is_int($to) && $to <= 50 && $to >=1)
        {
            $rows = $this->con->getData("
                SELECT h.`id`, h.`reason`, h.`prize`, h.`anonymous`, h.`ordererID`, o.`username` AS `orderer`, h.`userID`, u.`username`
                FROM `hitlist` AS h
                LEFT JOIN `user` AS o
                ON (h.`ordererID`=o.`id`)
                LEFT JOIN `user` AS u
                ON (h.userID=u.id)
                WHERE o.`active`='1' AND o.`deleted`='0' AND u.`active`='1' AND u.`deleted`='0'
                ORDER BY h.`prize` DESC
                LIMIT $from,$to
            ");
            $list = array();
            foreach($rows AS $row)
            {
                $hitlist = new Hitlist();
                $hitlist->setId($row['id']);
                $hitlist->setOrdererID($row['ordererID']);
                $hitlist->setOrderer($row['orderer']);
                $hitlist->setUserID($row['userID']);
                $hitlist->setUsername($row['username']);
                $hitlist->setPrize($row['prize']);
                $hitlist->setReason($row['reason']);
                $hitlist->setAnonymous(false);
                if($row['anonymous'] == 1)
                    $hitlist->setAnonymous(true);
                
                array_push($list, $hitlist);
            }
            return $list;
        }
        return false;
    }
    
    public function getHitlistDataByUserID($uid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT h.`id`, h.`reason`, h.`prize`, h.`anonymous`, h.`ordererID`, o.`username` AS `orderer`, h.`userID`, u.`username`
                FROM `hitlist` AS h
                LEFT JOIN `user` AS o
                ON (h.`ordererID`=o.`id`)
                LEFT JOIN `user` AS u
                ON (h.userID=u.id)
                WHERE h.`userID`= :uid AND o.`active`='1' AND o.`deleted`='0' AND u.`active`='1' AND u.`deleted`='0'
                LIMIT 1
            ", array(':uid' => $uid));
            
            if(isset($row['id']) && $row['id'] > 0)
            {
                $hitlist = new Hitlist();
                $hitlist->setId($row['id']);
                $hitlist->setOrdererID($row['ordererID']);
                $hitlist->setOrderer($row['orderer']);
                $hitlist->setUserID($row['userID']);
                $hitlist->setUsername($row['username']);
                $hitlist->setPrize($row['prize']);
                $hitlist->setReason($row['reason']);
                $hitlist->setAnonymous(false);
                if($row['anonymous'] == 1)
                    $hitlist->setAnonymous(true);
                
                return $hitlist;
            }
        }
        return false;
    }
    
    public function isUserOnHitlist($userID)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `hitlist` WHERE `userID`= :uid ORDER BY `id` DESC LIMIT 1
            ", array(':uid' => $userID));
            if(isset($row['id']) && $row['id'] > 0)
                return true;
        }
        return false;
    }
    
    public function orderHitlistRecord($uid, $reason, $prize, $anonymous = false)
    {
        if(isset($_SESSION['UID']))
        {
            $price = $prize;
            if($anonymous !== false)
                $price *= 1.3;
            
            $this->con->setData("
                INSERT INTO `hitlist` (`ordererID`, `userID`, `prize`, `reason`, `anonymous`) VALUES (:oid, :uid, :prize, :reason, :anon);
                UPDATE `user` SET `cash`=`cash`- :price WHERE `id`= :oid AND `active`='1' AND `deleted`='0'
            ", array(
                ':oid' => $_SESSION['UID'], ':uid' => $uid, ':prize' => $prize, ':reason' => $reason, ':anon' => $anonymous,
                ':price' => $price
            ));
        }
    }
    
    public function buyOutHitlistRecord($userID, $price)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `user` SET `cash`=`cash`- :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0';
                DELETE FROM `hitlist` WHERE `userID`= :huid
            ", array(':price' => $price, ':uid' => $_SESSION['UID'], ':huid' => $userID));
        }
    }
}
