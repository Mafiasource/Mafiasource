<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Entities\Family;
use src\Entities\FamilyBfDonationLog;
use src\Entities\FamilyBfSendLog;
use src\Entities\FamilyBrothelWhore;
use src\Entities\User;

class FamilyPropertyDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    private $dateFormat = "%d-%m-%Y %H:%i:%s";
    
    private $familyID;
    
    public function __construct()
    {
        global $lang;
        global $userData;
        global $connection;
        
        $this->con = $connection;
        $this->dbh = $connection->con;
        $this->familyID = $userData->getFamilyID();
        $this->lang = $lang;
        if($this->lang == 'en') $this->dateFormat = "%m-%d-%Y %r";
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getRecordsCount()
    {
        
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(`id`) AS `total` FROM `family_bf_send_log` WHERE `id`>'0' AND `familyID`= :fid", array(':fid' => $this->familyID));
            
            return $row['total'];
        }
    }
    
    public function buyFamilyProperty($property, $costs)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            switch($property)
            {
                default:
                case 'bullet-factory':
                    $this->con->setData("
                        UPDATE `family` SET `bulletFactory`='1', `money`=`money`- :costs WHERE `id`= :fid AND `active`='1' AND `deleted`='0'
                    ", array(':costs' => $costs, ':fid' => $this->familyID));
                    break;
                case 'brothel':
                    $this->con->setData("
                        UPDATE `family` SET `brothel`='1', `money`=`money`- :costs WHERE `id`= :fid AND `active`='1' AND `deleted`='0'
                    ", array(':costs' => $costs, ':fid' => $this->familyID));
                    break;
            }
        }
    }
    
    public function upgradeFamilyProperty($property, $costs)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            switch($property)
            {
                default:
                case 'bullet-factory':
                    $this->con->setData("
                        UPDATE `family` SET `bulletFactory`=`bulletFactory`+'1', `cBulletFactory`= :time, `money`=`money`- :costs WHERE `id`= :fid AND `active`='1' AND `deleted`='0'
                    ", array(':costs' => $costs, ':time' => (time() + 60*60*24), ':fid' => $this->familyID));
                    break;
                case 'brothel':
                    $this->con->setData("
                        UPDATE `family` SET `brothel`=`brothel`+'1', `cBrothel`= :time, `money`=`money`- :costs WHERE `id`= :fid AND `active`='1' AND `deleted`='0'
                    ", array(':costs' => $costs, ':time' => (time() + 60*60*24), ':fid' => $this->familyID));
                    break;
            }
        }
    }
    
    public function setBfProduction($production)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                UPDATE `family` SET `bfProduction`= :p WHERE `id`= :fid AND `active`='1' AND `deleted`='0' AND `bfProduction`<> :p
            ", array(':p' => $production, ':fid' => $this->familyID));
        }
    }
    
    public function donateBulletsToFamily($bullets)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                UPDATE `family` SET `bullets`=`bullets`+ :b WHERE `id`= :fid AND `bulletFactory`>'0' AND `active`='1' AND `deleted`='0';
                UPDATE `user` SET `bullets`=`bullets`- :b WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(
                ':b' => $bullets, ':fid' => $this->familyID,
                ':uid' => $_SESSION['UID']
            ));
            
            $check = $this->con->getDataSR("SELECT `id` FROM `family_bf_donation_log` WHERE `familyID`= :fid AND `userID`= :uid LIMIT 1", array(':fid' => $this->familyID, ':uid' => $_SESSION['UID']));
            if(isset($check['id']) && $check['id'] > 0)
                $this->con->setData("
                    UPDATE `family_bf_donation_log` SET `amount`=`amount`+ :amount, `amountAll`=`amountAll`+ :amount, `lastDonation`= NOW() WHERE `familyID`= :fid AND `userID`= :uid
                ", array(':amount' => $bullets, ':fid' => $this->familyID, ':uid' => $_SESSION['UID']));
            else
                $this->con->setData("
                    INSERT INTO `family_bf_donation_log` (`familyID`, `userID`, `amount`, `amountAll`, `lastDonation`) VALUES (:fid, :uid, :amount, :amount, NOW())
                ", array(':fid' => $this->familyID, ':uid' => $_SESSION['UID'], ':amount' => $bullets));
        }
    }
    
    public function sendBulletsToUser($receiverID, $bullets)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $this->con->setData("
                UPDATE `family` SET `bullets`=`bullets`- :b WHERE `id`= :fid  AND `bulletFactory`>'0' AND `active`='1' AND `deleted`='0';
                UPDATE `user` SET `bullets`=`bullets`+ :b WHERE `id`= :rid AND `active`='1' AND `deleted`='0';
                INSERT INTO `family_bf_send_log` (`familyID`, `senderID`, `receiverID`, `amount`, `date`) VALUES (:fid, :sid, :rid, :b, NOW())
            ", array(
                ':b' => $bullets, ':fid' => $this->familyID,
                ':rid' => $receiverID,
                ':sid' => $_SESSION['UID'],
            ));
        }
    }
    
    public function addWhoresToFamilyBrothel($amount)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `family_brothel_whore` WHERE `userID`= :uid AND `familyID`= :fid LIMIT 1
            ", array(':uid' => $_SESSION['UID'], ':fid' => $this->familyID));
            if(isset($row['id']) && $row['id'] > 0)
                $this->con->setData("
                    UPDATE `family_brothel_whore` SET `whores`=`whores`+ :amount WHERE `id`= :rldid
                ", array(':amount' => $amount, ':rldid' => $row['id']));
            else
                $this->con->setData("
                    INSERT INTO `family_brothel_whore` (`familyID`, `userID`, `whores`) VALUES (:fid, :uid, :amount)
                ", array(':fid' => $this->familyID, ':uid' => $_SESSION['UID'], ':amount' => $amount));
            
            $this->con->setData("
                UPDATE `user` SET `whoresStreet`=`whoresStreet`- :amount WHERE `id`= :uid
            ", array(':amount' => $amount, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function getBrothelWhores()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id`, `whores` FROM `family_brothel_whore` WHERE `familyID`= :fid AND `userID`= :uid LIMIT 1
            ", array(':fid' => $this->familyID, ':uid' => $_SESSION['UID']));
            if(isset($row['id']) && $row['id'] > 0)
                return $row['whores'];
            else
                return 0;
        }
    }
    
    public function takeAwayWhoresFromFamilyBrothel($amount)
    {
        if(isset($_SESSION['UID']))
        {
            if(($this->getBrothelWhores() - $amount) == 0)
                $this->con->setData("
                    DELETE FROM `family_brothel_whore` WHERE `familyID`= :fid AND `userID`= :uid
                ", array(':fid' => $this->familyID, ':uid' => $_SESSION['UID']));
            else
                $this->con->setData("
                    UPDATE `family_brothel_whore` SET `whores`=`whores`- :amount WHERE `familyID`= :fid AND `userID` = :uid
                ", array(':amount' => $amount, ':fid' => $this->familyID, ':uid' => $_SESSION['UID']));
            
            $this->con->setData("
                UPDATE `user` SET `whoresStreet`=`whoresStreet`+ :amount WHERE `id`= :uid
            ", array(':amount' => $amount, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function getFamilyBulletFactoryPageInfo($from, $to, $hasRights = false)
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0 && is_int($from) && is_int($to) && $to <= 50 && $to >=1)
        {
            $bf = $this->con->getDataSR("
                SELECT `bullets`, `bulletFactory`, `bfProduction`, `cBulletFactory` FROM `family` WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':fid' => $this->familyID));
            
            $bulletFactory = new Family();
            $bulletFactory->setBullets($bf['bullets']);
            $bulletFactory->setBulletFactory($bf['bulletFactory']);
            $bulletFactory->setBfProduction($bf['bfProduction']);
            $bulletFactory->setCBulletFactory($bf['cBulletFactory']);
            
            $bfDonationsList = array();
            $bfDonations = $this->con->getData("
                SELECT dl.`id`, dl.`userID`, u.`username`, dl.`amountAll`, DATE_FORMAT(dl.`lastDonation`, '".$this->dateFormat."' ) AS `lastDonation`,
                    (SELECT SUM(`amount`) FROM `family_bf_send_log` WHERE `familyID`= :fid AND `receiverID`=u.`id` AND `active`='1' AND `deleted`='0') AS `received`
                FROM `family_bf_donation_log` AS dl
                LEFT JOIN `user` AS u
                ON(dl.`userID`=u.`id`)
                WHERE dl.`familyID`= :fid
                ORDER BY dl.`amountAll` DESC
            ", array(':fid' => $this->familyID));
            foreach($bfDonations AS $dl)
            {
                $bfDonation = new FamilyBfDonationLog();
                $bfDonation->setFamilyID($this->familyID);
                $bfDonation->setUserID($dl['userID']);
                $bfDonation->setUsername($dl['username']);
                $bfDonation->setAmount($dl['amountAll'] - $dl['received']);
                $bfDonation->setAmountAll($dl['amountAll']);
                $bfDonation->setLastDonation($dl['lastDonation']);
                
                array_push($bfDonationsList, $bfDonation);
            }
            
            if($hasRights)
            {
                $bfSendList = array();
                $bfSent = $this->con->getData("
                    SELECT bfl.`id`, bfl.`senderID`, s.`username` AS `sender`, bfl.`receiverID`, r.`username` AS `receiver`, bfl.`amount`, DATE_FORMAT(bfl.`date`, '".$this->dateFormat."' ) AS `date`
                    FROM `family_bf_send_log` AS bfl
                    LEFT JOIN `user` AS s
                    ON(bfl.`senderID`=s.`id`) AND(bfl.`familyID`=s.`familyID`)
                    LEFT JOIN `user` AS r
                    ON(bfl.`receiverID`=r.`id`) AND(bfl.`familyID`=r.`familyID`)
                    WHERE bfl.`familyID`= :fid
                    ORDER BY bfl.`date` DESC
                    LIMIT $from, $to
                ", array(':fid' => $this->familyID));
                foreach($bfSent AS $sl)
                {
                    $bfSend = new FamilyBfSendLog();
                    $bfSend->setId($sl['id']);
                    $bfSend->setFamilyID($this->familyID);
                    $bfSend->setSenderID($sl['senderID']);
                    $bfSend->setSender($sl['sender']);
                    $bfSend->setReceiverID($sl['receiverID']);
                    $bfSend->setReceiver($sl['receiver']);
                    $bfSend->setAmount($sl['amount']);
                    $bfSend->setDate($sl['date']);
                    
                    array_push($bfSendList, $bfSend);
                }
            }
            if(!isset($bfSendList)) $bfSendList = "";
            
            return array('bf' => $bulletFactory, 'donationLogs' => $bfDonationsList, 'sendLogs' => $bfSendList);
        }
    }
    
    public function getFamilyBrothelPageInfo()
    {
        if(isset($_SESSION['UID']) && $this->familyID != 0)
        {
            $br = $this->con->getDataSR("SELECT `brothel`, `cBrothel` FROM `family` WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':fid' => $this->familyID));
            
            $brothel = new Family();
            $brothel->setBrothel($br['brothel']);
            $brothel->setCBrothel($br['cBrothel']);
            
            $brothelWhores = $this->con->getDataSR("
                SELECT SUM(`whores`) AS `totalWhores`,
                    (SELECT `id` FROM `family_brothel_whore` WHERE `familyID`= :fid AND `userID`= :uid LIMIT 1) AS `id`,
                    (SELECT `whores` FROM `family_brothel_whore` WHERE `familyID`= :fid AND `userID`= :uid LIMIT 1) AS `whores`
                FROM `family_brothel_whore`
                WHERE `familyID`= :fid
            ", array(':fid' => $this->familyID, ':uid' => $_SESSION['UID']));
            
            if(!isset($brothelWhores['id'])) $brothelWhores['id'] = 0;
            if(!isset($brothelWhores['whores'])) $brothelWhores['whores'] = 0;
            if(!isset($brothelWhores['totalWhores'])) $brothelWhores['totalWhores'] = 0;
            
            $brothelWhore = new FamilyBrothelWhore();
            $brothelWhore->setId($brothelWhores['id']);
            $brothelWhore->setFamilyID($this->familyID);
            $brothelWhore->setUserID($_SESSION['UID']);
            $brothelWhore->setWhores($brothelWhores['whores']);
            $brothelWhore->setTotal($brothelWhores['totalWhores']);
            
            return array('brothel' => $brothel, 'whores' => $brothelWhore);
        }
    }
}
