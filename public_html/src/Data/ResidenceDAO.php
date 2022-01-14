<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Data\PossessionDAO;
use src\Entities\Residence;
use src\Entities\User;

class ResidenceDAO extends DBConfig
{
    protected $con = "";            
    private $dbh = "";
    private $lang = "en";
    
    public function __construct()
    {
        global $lang;
        global $connection;
        
        $this->con = $connection;                        
        $this->dbh = $connection->con;
        $this->lang = $lang;
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(*) AS `total` FROM `residence` WHERE `deleted` = '0' AND `active` = '1'");
            return $row['total'];
        }
    }
    
    public function getResidenceData()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `residence`, `residenceHistory` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':uid' => $_SESSION['UID']));
            
            $userObj = new User();
            $userObj->setResidence($row['residence']);
            $userObj->setResidenceHistory($row['residenceHistory']);
            return $userObj;
        }
    }
    
    public function getResidenceDataByUserID($userID)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `residence` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':uid' => $userID));
            
            $userObj = new User();
            $userObj->setResidence($row['residence']);
            return $userObj;
        }
    }
    
    public function getResidenceNameById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `name_".$this->lang."` AS `name` FROM `residence` WHERE `id`= :id AND `deleted` = '0' AND `active` = '1'
            ", array(':id' => $id));
            
            if(isset($row['name']) && $row['name'])
                return $row['name'];
            else
                return false;
        }
    }
    
    public function getResidencePage()
    {
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData("
                SELECT `id`, `name_".$this->lang."` AS `name`, `picture`, `price`, `defence`, `rankpoints` FROM `residence` WHERE `deleted` = '0' AND `active` = '1'
            ");
            
            $list = array();
            
            global $userData;
            foreach($rows AS $row)
            {
                if($userData->getDonatorID() >= 10)
                    $row['price'] *= 0.9;
                
                $checkPossession = $this->con->getDataSR("
                    SELECT `id` FROM `user_residence` WHERE `userID`= :uid AND `residenceID`= :rid
                ", array(':uid' => $_SESSION['UID'], ':rid' => $row['id']));
                
                $residence = new Residence();
                $residence->setId($row['id']);
                $residence->setName($row['name']);
                $residence->setPicture($row['picture']);
                $residence->setPrice($row['price']);
                $residence->setDefence($row['defence']);
                $residence->setRankpoints($row['rankpoints']);
                $residence->setInPossession(false);
                if(!empty($checkPossession) && $checkPossession['id'] > 0)
                    $residence->setInPossession(true);
                
                $residence->setEquipped(false);
                if($this->getResidenceData()->getResidence() == $row['id'])
                    $residence->setEquipped(true);
                
                array_push($list, $residence);
            }
            
            return $list;
        }
    }
    
    public function buyResidence($residenceID, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            $id = $residenceID;
            
            $row = $this->con->getDataSR("
                SELECT `id`, `price`, `rankpoints` FROM `residence` WHERE `deleted` = '0' AND `active` = '1' AND `id`= :rid
            ", array(':rid' => $id));
            
            global $userData;
            if($userData->getDonatorID() >= 10)
                    $row['price'] *= 0.9;
            
            $profitOwner = round($row['price'] * 0.08, 0);
            
            $residenceData = $this->getResidenceData();
            $h = $residenceData->getResidenceHistory();
            
            if(!preg_match("/_".$id."/", $h))
            {
                $newResidenceHistory = $h . "_".$id;
                $this->con->setData("
                    UPDATE `user` SET `cash`=`cash`- :price, `residence`= :rid, `residenceHistory`= :history, `rankpoints`=`rankpoints`+ :rp WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(':price' => $row['price'], ':rid' => $residenceID, ':history' => $newResidenceHistory, ':rp' => $row['rankpoints'], ':uid' => $_SESSION['UID']));
            }
            else
            {
                $this->con->setData("
                    UPDATE `user` SET `cash`=`cash`- :price, `residence`= :rid WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(':price' => $row['price'], ':rid' => $residenceID, ':uid' => $_SESSION['UID']));
            }
        
            $this->con->setData("INSERT INTO `user_residence` (`userID`,`residenceID`) VALUES (:uid, :rid)", array(':uid' => $_SESSION['UID'], ':rid' => $id));
            
            /** Possession logic for buying residences | pay owner if exists and not self **/
            if(is_object($pData)) $agencyOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $agencyOwner > 0 && $agencyOwner != $_SESSION['UID'])
            {
                $possessionData = new PossessionDAO();
                $possessionData->applyProfitForOwner($pData, $profitOwner, $agencyOwner);
            }
        }
    }
    
    public function sellResidence($residenceID)
    {
        if(isset($_SESSION['UID']))
        {
            $id = $residenceID;
            
            $row = $this->con->getDataSR("
                SELECT `id`, `price` FROM `residence` WHERE `deleted` = '0' AND `active` = '1' AND `id`= :rid
            ", array(':rid' => $id));
            
            global $userData;
            if($userData->getDonatorID() >= 10)
                    $row['price'] *= 0.9;
            
            $this->con->setData("
                UPDATE `user` SET `cash`=`cash`+ :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':price' => ($row['price']*0.6), ':uid' => $_SESSION['UID']));
            
            $this->con->setData("
                UPDATE `user` SET `residence`='0' WHERE `id`= :uid AND `residence`= :rid AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $_SESSION['UID'], ':rid' => $id));
        
            $this->con->setData("DELETE FROM `user_residence` WHERE `userID`= :uid AND `residenceID`= :rid", array(':uid' => $_SESSION['UID'], ':rid' => $id));
        }
    }
    
    public function equipResidence($residenceID)
    {
        if(isset($_SESSION['UID']))
        {
            $id = $residenceID;
            
            $this->con->setData("
                UPDATE `user` SET `residence`= :rid WHERE `id`= :uid AND `residence`!= :rid AND `active`='1' AND `deleted`='0'
            ", array(':rid' => $id, ':uid' => $_SESSION['UID']));
        }
    }
}
