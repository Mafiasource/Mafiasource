<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Entities\Notification;
use src\Languages\Notifications;

class NotificationDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    private $dateFormat = "%d-%m-%Y %H:%i:%s";
    
    public function __construct()
    {
        global $lang;
        global $connection;
        
        $this->con = $connection;
        $this->dbh = $connection->con;
        $this->lang = $lang;
        if($this->lang == 'en') $this->dateFormat = "%m-%d-%Y %r";
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getLatestNotifications()
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                DELETE FROM `notification` 
                WHERE `id` NOT IN
                    (SELECT * FROM (SELECT `id` FROM `notification` WHERE `userID`= :uid ORDER BY `date` DESC, `id` DESC LIMIT 120) temp_tab)
                AND `userID`= :uid AND `read`='1' AND `inInbox`='1'
            ", array(':uid' => $_SESSION['UID']));
            
            $statement = $this->dbh->prepare("SELECT `id`, `notification`, `params`, DATE_FORMAT(`date`, '".$this->dateFormat."' ) AS `dateF`, `read` FROM `notification` WHERE `userID`= :uid ORDER BY `date` DESC, `id` DESC LIMIT 30");
            $statement->execute(array(':uid' => $_SESSION['UID']));
            $list = array();
            global $route;
            foreach($statement AS $row)
            {
                $notifications = new Notifications($row['notification']);
                $notification = $notifications->getNotification();
                if(strpos($row['params'], '&')) $parameters = explode('&', $row['params']);
                if(isset($parameters)) // Multiple parameters
                {
                    foreach($parameters AS $prm)
                    {
                        $pv = explode('=', $prm);
                        if(isset($pv[0]) && isset($pv[1]))
                        {
                            $p = $pv[0]; $v = $pv[1];
                            $notification = $route->replaceMessagePart($v, $notification, '/{'.$p.'}/');
                        }
                    }
                    unset($parameters);
                }
                else
                { // single param
                    $pv = explode('=', $row['params']);
                    if(isset($pv[0]) && isset($pv[1]))
                    {
                        $p = $pv[0]; $v = $pv[1];
                        $notification = $route->replaceMessagePart($v, $notification, '/{'.$p.'}/');
                    }
                }
                
                $n = new Notification();
                $n->setId($row['id']);
                $n->setNotification($notification);
                $n->setDate($row['dateF']);
                $n->setRead(false);
                if($row['read'] == 1) $n->setRead(true);
                array_push($list, $n);
            }
            return $list;
        }
    }
    
    public function setReadNotifications()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `notification` SET `read`='1' WHERE `userID`= :uid AND `read`='0' AND `inInbox`='1' ");
            $statement->execute(array(':uid' => $_SESSION['UID']));
        }
    }
    
    public function sendNotification($userID, $notification, $params = "")
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("INSERT INTO `notification` (`userID`, `notification`, `params`, `date`) VALUES (:uid, :note, :params, NOW())");
            $statement->execute(array(':uid' => $userID, ':note' => $notification, ':params' => $params));
        }
    }
}
