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
                UPDATE `user` SET `donatorID`= :did, `credits`=`credits`- :cr WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':did' => $id, ':cr' => $credits, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function buyFamilyVip($famID, $credits)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `user` SET `credits`=`credits`- :cr WHERE `id`= :uid AND `active`='1' AND `deleted`='0';
                UPDATE `family` SET `vip`='1' WHERE `id`= :fid AND `active`='1' AND `deleted`='0'
            ", array(
                ':cr' => $credits, ':uid' => $_SESSION['UID'],
                ':fid' => $famID
            ));
        }
    }
}
