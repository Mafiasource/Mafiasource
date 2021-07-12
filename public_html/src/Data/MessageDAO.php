<?PHP

namespace src\Data;

use src\Business\MessageService;
use src\Business\SeoService;
use src\Data\config\DBConfig;
use src\Entities\Message;

class MessageDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    private $dateFormat = "%d-%m-%Y %H:%i:%s";
    
    public $familyID = 0;
    
    public function __construct()
    {
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
        global $route;
        $this->lang = $route->getLang();
        if($this->lang == 'en') $this->dateFormat = "%m-%d-%Y %r";
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getLatestMessages()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT m.`id`, u.`username`, u2.`username` AS `usernameReceiver`, m.`senderID`,m.`receiverID`, m.`message`, MAX(m.`date`) AS `dateMax`,
                DATE_FORMAT( MAX(m.`date`), '".$this->dateFormat."' ) AS `date`, m.`read` AS `read`, u.`statusID`,u.donatorID, st.`status_".$this->lang."` AS `status`, d.`donator_".$this->lang."` AS `donator`,
                u2.`statusID` AS `statusIDReceiver`, u2.`donatorID` AS `donatorIDReceiver`, st2.`status_".$this->lang."` AS `statusReceiver`, d2.`donator_".$this->lang."` AS `donatorReceiver`
                FROM `message` AS m
                LEFT JOIN `user` AS u
                ON (m.`senderID`=u.`id`)
                LEFT JOIN `user` AS u2
                ON (m.`receiverID`=u2.`id`)
                LEFT JOIN `status` AS st
                ON (u.statusID=st.id)
                LEFT JOIN `status` AS st2
                ON (u2.statusID=st2.id)
                LEFT JOIN `donator` AS d
                ON (u.donatorID=d.id)
                LEFT JOIN `donator` AS d2
                ON (u2.donatorID=d2.id)
                JOIN (SELECT MAX(`date`) AS `date` FROM `message` GROUP BY `cID`) m2
                ON m.date = m2.date
                WHERE m.`active`='1' AND m.`deleted`='0'
                AND (m.`receiverID`= :uid  AND m.`inReceiverInbox`='1') OR (m.`senderID`= :uid  AND m.`inSenderInbox`='1')
                GROUP BY m.`cID`
                ORDER BY `dateMax` DESC, `id` DESC
                LIMIT 20
            ");
            $statement->execute(array(':uid' => $_SESSION['UID']));
            $list = array();
            while($row = $statement->fetch())
            {
                if(isset($row['senderID']) && $row['senderID'] == $_SESSION['UID'])
                {
                    $username = $row['usernameReceiver'];
                    $donaID = $row['donatorIDReceiver'];
                    if($row['statusIDReceiver'] < 7 || $row['statusIDReceiver'] == 8)
                    {
                        $className = SeoService::seoUrl($row['statusReceiver']);
                    }
                    else
                    {
                        $className = SeoService::seoUrl($row['donatorReceiver']);
                    }
                }
                else
                {
                    $username = $row['username'];
                    $donaID = $row['donatorID'];
                    if($row['statusID'] < 7 || $row['statusID'] == 8)
                    {
                        $className = SeoService::seoUrl($row['status']);
                    }
                    else
                    {
                        $className = SeoService::seoUrl($row['donator']);
                    }
                }
                $msg = new Message();
                $msg->setId($row['id']);
                $msg->setSenderUsername($username);
                $msg->setSenderUsernameClassName($className);
                $msg->setSenderDonatorID($donaID);
                $msg->setDate($row['date']);
                $msg->setRead(false);
                if($row['read'] == 1)$msg->setRead(true);
                array_push($list,$msg);
            }
            return $list;
        }
    }
    
    public function getLastMessage()
    {
        if(isset($_SESSION['UID']))
        {
            $response = false;
            $statement = $this->dbh->prepare("
                SELECT m.`senderID`, m.`receiverID`, MAX(m.`date`) AS `dateMax`
                FROM `message` AS m
                JOIN (SELECT MAX(`date`) AS `date` FROM `message` GROUP BY `cID`) m2
                ON m.date = m2.date
                WHERE m.`active`='1' AND m.`deleted`='0'
                AND (m.`receiverID`= :uid  AND m.`inReceiverInbox`='1') OR (m.`senderID`= :uid  AND m.`inSenderInbox`='1') GROUP BY m.`cID` ORDER BY `dateMax` DESC, m.`id` DESC LIMIT 1
            ");
            $statement->execute(array(':uid' => $_SESSION['UID']));
            $row = $statement->fetch();
            if(isset($row['senderID']) && isset($row['receiverID']))
            {
                $sid = ($row['senderID'] == $_SESSION['UID']) ? $row['receiverID'] : $row['senderID'];
                $response = $this->getLastMessagesByReceiverId($sid);
            }
            return $response;
        }
    }
    
    public function getLastMessagesByReceiverId($rid)
    {
        if(isset($_SESSION['UID']))
        {
            global $userData;
            $receiverData = $userData;
            $statement = $this->dbh->prepare("
                SELECT  m.`id`, u.`username`, u.`avatar`, m.`senderID`, m.`receiverID`, m.`message`, DATE_FORMAT( m.`date`, '".$this->dateFormat."' ) AS `dateFormat`,
                    `date` AS `dateRaw`, m.`read`,u.`statusID`,u.`donatorID`, st.`status_".$this->lang."` AS `status`, d.`donator_".$this->lang."` AS `donator`
                FROM `message` AS m
                LEFT JOIN `user` AS u
                ON (m.`senderID`=u.`id`)
                LEFT JOIN `status` AS st
                ON (u.statusID=st.id)
                LEFT JOIN `donator` AS d
                ON (u.donatorID=d.id)
                WHERE m.`active`='1' AND m.`deleted`='0'
                AND ((m.`receiverID`= :uid AND m.`senderID`= :rid AND m.`inReceiverInbox`='1') OR (m.`senderID` = :uid  AND m.`receiverID`= :rid  AND m.`inSenderInbox`='1'))
                ORDER BY `dateRaw` DESC
                LIMIT 20
            ");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':rid' => $rid));
            $list = array();
            foreach($statement AS $row)
            {
                $receiver = null;
                if(isset($row['senderID']) && $row['senderID'] != $_SESSION['UID'])
                {
                    if($row['statusID'] < 7 || $row['statusID'] == 8)
                    {
                        $className = SeoService::seoUrl($row['status']);
                    }
                    else
                    {
                        $className = SeoService::seoUrl($row['donator']);
                    }
                    $receiver = true;
                    $username = $row['username'];
                    $avatar = $row['avatar'];
                    $sID = $row['senderID'];
                    $donaID = $row['donatorID'];
                    $statement = $this->dbh->prepare("UPDATE `message` SET `read`='1' WHERE `id`= :mid AND `read`='0'");
                    $statement->execute(array(':mid' => $row['id']));
                }
                elseif($row['senderID'] == $_SESSION['UID'])
                {
                    if($receiverData->getStatusID() < 7 || $receiverData->getStatusID() == 8)
                    {
                        $className = SeoService::seoUrl($receiverData->getStatus());
                    }
                    else
                    {
                        $className = SeoService::seoUrl($receiverData->getDonator());
                    }
                    $username = $receiverData->getUsername();
                    $avatar = $receiverData->getAvatar();
                    $sID = $_SESSION['UID'];
                    $donaID = $receiverData->getDonatorID();
                }
                $msg = new Message();
                $msg->setId($row['id']);
                $msg->setSenderID($sID);
                $msg->setSenderUsername($username);
                $msg->setSenderAvatar($avatar);
                $msg->setSenderUsernameClassName($className);
                $msg->setSenderDonatorID($donaID);
                $msg->setMessage(MessageService::BBParser($row['message']));
                $msg->setDate($row['dateFormat']);
                $msg->setRead($row['read']);
                if($receiver != null) $msg->setReceiver(true);
                array_push($list, $msg);
            }
            $list = array_reverse($list);
            return $list;
        }
    }
    
    public function getLastMessagesIdsByReceiverId($rid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT `id` FROM `message` WHERE `active`='1' AND `deleted`='0'
                    AND ((`receiverID`= :uid AND `senderID`= :rid AND `inReceiverInbox`='1') OR (`senderID` = :uid  AND `receiverID`= :rid  AND `inSenderInbox`='1'))
                ORDER BY `date` DESC
                LIMIT 20
            ");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':rid' => $rid));
            
            $list = array();
            foreach($statement AS $row)
            {
                $msg = new Message();
                $msg->setId($row['id']);
                
                array_push($list,$msg);
            }
            $list = array_reverse($list);
            return $list;
        }
    }
    
    public function replyToMessage($receiverID, $message)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `cID` FROM `message` WHERE (`senderID`= :uid AND `receiverID`= :rid) OR (`senderID`= :rid AND `receiverID`= :uid) AND active='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':rid' => $receiverID));
            $row = $statement->fetch();
            $statement = $this->dbh->prepare("INSERT INTO `message` (`senderID`,`receiverID`,`message`,`date`) VALUES (:sID,:rID,:msg,:date)");
            $statement->execute(array(':sID' => $_SESSION['UID'], ':rID' => $receiverID, ':msg' => $message, ':date' => date('Y-m-d H:i:s')));
            $id = $this->dbh->lastInsertId();
            if(isset($row['cID']) && $row['cID'] > 0)
            {
                $statement = $this->dbh->prepare("UPDATE `message` SET `cID` = :cID WHERE `id` = :id");
                $statement->execute(array(':cID' => $row['cID'], ':id' => $id));
            }
            else
            {
                $statement = $this->dbh->prepare("UPDATE `message` SET `cID` = :cID WHERE `id` = :id");
                $statement->execute(array(':cID' => $id, ':id' => $id));
            }
        }
    }
}
