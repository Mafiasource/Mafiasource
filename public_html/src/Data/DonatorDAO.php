<?PHP

namespace src\Data;

use src\Data\config\DBConfig;

class DonatorDAO extends DBConfig
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
            UPDATE `user` SET `cHalvingTimes`= :time WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
        ", array(':uid' => $_SESSION['UID'], ':time' => $halvingTime));
    }
    
    public function buyBribingPolice($credits)
    {
        $bribingTime = (time() + (60*60*8));
        $this->con->setData("
            UPDATE `user` SET `cBribingPolice`= :time WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
        ", array(':uid' => $_SESSION['UID'], ':time' => $bribingTime));
    }
}
