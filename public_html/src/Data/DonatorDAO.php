<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Data\StatisticDAO;
use src\Entities\Donator;

class DonatorDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $dateFormat = "%d-%m-%Y %H:%i:%s"; // SQL Format
    private $phpDateFormat = "d-m-Y H:i:s";
    
    public function __construct()
    {
        global $lang;
        global $connection;
        
        $this->con = $connection;
        $this->dbh = $connection->con;
        $this->lang = $lang;
        if($this->lang == 'en')
        {
            $this->dateFormat = "%m-%d-%Y %r"; // SQL Format
            $this->phpDateFormat = "m-d-Y g:i:s A";
        }
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getRecordsCount($type)
    {
        
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(`id`) AS `total` FROM `donator` WHERE `id`>'0'", array(':type' => $type));
            
            return $row['total'];
        }
    }
    
    public function buyStatus($id, $credits)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `user` SET `donatorID`= :did, `credits`=`credits`- :cr WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':did' => $id, ':cr' => $credits, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function buyFamilyVip($famID, $credits)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `user` SET `credits`=`credits`- :cr WHERE `id`= :uid AND `active`='1' AND `deleted`='0'LIMIT 1;
                UPDATE `family` SET `vip`='1' WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(
                ':cr' => $credits, ':uid' => $_SESSION['UID'],
                ':fid' => $famID
            ));
        }
    }
    
    public function buyLuckybox($boxes, $credits)
    {
        $this->con->setData("
            UPDATE `user` SET `credits`=`credits`- :cr, `luckybox`=`luckybox`+ :lb WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
        ", array(':cr' => $credits, ':lb' => $boxes, ':uid' => $_SESSION['UID']));
    }
    
    public function buyHalvingTimes($credits)
    {
        $halvingTime = (time() + (60*60*12));
        $this->con->setData("
            UPDATE `user` SET `credits`=`credits`- :cr, `cHalvingTimes`= :time WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
        ", array(':cr' => $credits, ':time' => $halvingTime, ':uid' => $_SESSION['UID']));
    }
    
    public function buyBribingPolice($credits)
    {
        $bribingTime = (time() + (60*60*8));
        $this->con->setData("
            UPDATE `user` SET `credits`=`credits`- :cr, `cBribingPolice`= :time WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
        ", array(':cr' => $credits, ':time' => $bribingTime, ':uid' => $_SESSION['UID']));
    }
    
    public function getDonationShopData()
    {
        return $this->con->getDataSR("
            SELECT `ground`, `smugglingCapacity` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
        ", array(':uid' => $_SESSION['UID']));
    }
    
    public function buyGround($credits)
    {
        $this->con->setData("
            UPDATE `user` SET `credits`=`credits`- :cr, `ground`=`ground`+1 WHERE `id`= :uid AND `ground`<'5' AND `active`='1' AND `deleted`='0' LIMIT 1
        ", array(':cr' => $credits, ':uid' => $_SESSION['UID']));
    }
    
    public function buySmugglingCapacity($credits)
    {
        $this->con->setData("
            UPDATE `user` SET `credits`=`credits`- :cr, `smugglingCapacity`=`smugglingCapacity`+1 WHERE `id`= :uid AND `smugglingCapacity`<'20' AND `active`='1' AND `deleted`='0' LIMIT 1
        ", array(':cr' => $credits, ':uid' => $_SESSION['UID']));
    }
    
    public function buyNewProfession($credits, $profession)
    {
        $this->con->setData("
            UPDATE `user` SET `credits`=`credits`- :cr, `charType`= :p WHERE `id`= :uid AND `charType`!= :p AND `active`='1' AND `deleted`='0' LIMIT 1
        ", array(':cr' => $credits, ':p' => $profession, ':uid' => $_SESSION['UID']));
    }
    
    public function donationExistsByTxID($txID)
    {
        $row = $this->con->getDataSR("SELECT `id` FROM `donate` WHERE `tx`= :tid", array(':tid' => $txID));
        if(isset($row['id']) && $row['id'] > 0)
            return true;
        
        return false;
    }
    
    public function getDonationData($allTime = false)
    {
        $datePast = date('Y-m-d H:i:s', strtotime("-31 days"));
        if($allTime !== false)
        {
            $statisticDAO = new StatisticDAO();
            $datePast = "2021-12-07 00:00:00";
        }
        $id = $_SESSION['UID'];
        $date = $this->con->getDataSR("
            SELECT `date` FROM `donate` WHERE `userID`= :uid AND `date` >= :datePast AND `active`='1' AND `deleted`='0' ORDER BY `date` ASC LIMIT 1
        ", array(':uid' => $id, ':datePast' => $datePast));
        
        $datePast = isset($date['date']) ? $date['date'] : null;
        
        if(isset($datePast) && $datePast != "0000-00-00 00:00:00")
        {
            $row = $this->con->getDataSR("
                SELECT (SELECT SUM(`credits`) FROM `donate` WHERE `userID`= :uid AND `date`>= :datePast AND `active`='1' AND `deleted`='0') AS `cr`,
                    DATE_FORMAT(MAX(`date`), '".$this->dateFormat."') AS `tDate`
                FROM `donate`
                WHERE `userID`= :uid AND `date`>= :datePast AND `active`='1' AND `deleted`='0' GROUP BY `userID`
            ", array(':uid' => $id, ':datePast' => $datePast));
            
            if(isset($row['cr']))
            {
                $row['tDate'] = date($this->phpDateFormat, strtotime($row['tDate'])+(60*60*24*31));
                return $row;
            }
        }
    }
    
    public function saveCompletedDonation($ppJson)
    {
        global $userData;
        
        $cr = (int)$ppJson->amount->value * 100;
        $credits = $cr > 5000 ? 5000 : $cr;
        if($this->con->setData("
            INSERT INTO `donate` (`userID`, `sandbox`, `tx`, `currency`, `amount`, `net_amount`, `credits`, `date`) VALUES (:uid, :sb, :tx, :cc, :amt, :namt, :cr, NOW())
        ", array(
            ':uid' => $userData->getId(),
            ':sb' => PP_SANDBOX,
            ':tx' => $ppJson->id,
            ':cc' => $ppJson->amount->currency_code,
            ':amt' => $ppJson->amount->value,
            ':namt' => $ppJson->seller_receivable_breakdown->net_amount->value,
            ':cr' => $credits
        )))
            return true;
        
        return false;
    }
    
    public function addDonationCredits($credits)
    {
        $this->con->setData("
            UPDATE `user` SET `credits`=`credits`+ :cr WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
        ", array(':cr' => $credits, ':uid' => $_SESSION['UID']));
    }
    
    public function leaveDonatorList()
    {
        if(isset($_SESSION['UID']))
            $this->con->setData("DELETE FROM `donator_list` WHERE `userID`= :uid", array(':uid' => $_SESSION['UID']));
    }
    
    public function donatorListApplication()
    {
        if(isset($_SESSION['UID']))
            $this->con->setData("INSERT INTO `donator_list` (`userID`) VALUES (:uid)", array(':uid' => $_SESSION['UID']));
    }
    
    public function getDonatorList()
    {
        global $userService;
        
        $list = array();
        $rows = $this->con->getData("SELECT `userID` FROM `donator_list` WHERE `userID`!='0'");
        foreach($rows AS $row)
        {
            $profileData = $userService->getUserProfile($userService->getUsernameById($row['userID']));
            
            $donator = new Donator();
            $donator->setDonator($profileData->getUsername());
            $donator->setColorCode($profileData->getUsernameClassName());
            
            array_push($list, $donator);
        }
        return $list;
    }
}
