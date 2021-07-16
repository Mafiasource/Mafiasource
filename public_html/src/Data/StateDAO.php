<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Entities\State;
use src\Entities\City;

//State but also City DAO here.. Most of the times states and cities are connected to each other

class StateDAO extends DBConfig
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
    
    public function getStates()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `name` FROM `state` WHERE `active`='1' AND `deleted`='0' ORDER BY `position` ASC");
            $statement->execute();
            $list = array();
            foreach($statement AS $row)
            {
                $state = new State();
                $state->setId($row['id']);
                $state->setName($row['name']);
                array_push($list, $state);
            }
            return $list;
        }
    }
    
    public function getCitiesButHomeCity($cityID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `stateID`, `name` FROM `city` WHERE `active`='1' AND `deleted`='0' AND `id`!= :cityID ORDER BY `position` ASC");
            $statement->execute(array(':cityID' => $cityID));
            $list = array();
            foreach($statement AS $row)
            {
                $city = new City();
                $city->setId($row['id']);
                $city->setStateID($row['stateID']);
                $city->setName($row['name']);
                array_push($list, $city);
            }
            return $list;
        }
    }
    
    public function getCities()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `stateID`, `name` FROM `city` WHERE `active`='1' AND `deleted`='0' ORDER BY `position` ASC");
            $statement->execute();
            $list = array();
            foreach($statement AS $row)
            {
                $city = new City();
                $city->setId($row['id']);
                $city->setStateID($row['stateID']);
                $city->setName($row['name']);
                array_push($list, $city);
            }
            return $list;
        }
    }
    
    public function getStateNameById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `name` FROM `state` WHERE `active`='1' AND `deleted`='0' AND `id` = :stateID LIMIT 1");
            $statement->execute(array(':stateID' => $id));
            $row = $statement->fetch();
            if(isset($row) && !empty($row))
            {
                return $row['name'];
            }
            else
            {
                return FALSE;
            }
        }
    }
    
    public function getCityNameById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `name` FROM `city` WHERE `active`='1' AND `deleted`='0' AND `id` = :cityID LIMIT 1");
            $statement->execute(array(':cityID' => $id));
            $row = $statement->fetch();
            if(isset($row) && !empty($row))
            {
                return $row['name'];
            }
            else
            {
                return FALSE;
            }
        }
    }
    
    public function getStateIdByName($name)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `state` WHERE `active`='1' AND `deleted`='0' AND `name` = :name LIMIT 1");
            $statement->execute(array(':name' => $name));
            $row = $statement->fetch();
            if(isset($row) && !empty($row))
            {
                return $row['id'];
            }
            else
            {
                return FALSE;
            }
        }
    }
    
    public function getCityIdByName($name)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `city` WHERE `active`='1' AND `deleted`='0' AND `name` = :name LIMIT 1");
            $statement->execute(array(':name' => $name));
            $row = $statement->fetch();
            if(isset($row) && !empty($row))
            {
                return $row['id'];
            }
            else
            {
                return FALSE;
            }
        }
    }
    
    public function getStateIdByCityId($id)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `stateID` FROM `city` WHERE `active`='1' AND `deleted`='0' AND `id` = :cityID LIMIT 1");
            $statement->execute(array(':cityID' => $id));
            $row = $statement->fetch();
            if(isset($row) && !empty($row))
            {
                return $row['stateID'];
            }
            else
            {
                return FALSE;
            }
        }
    }
    
    public function travelTo($stateID, $cityID, $sec, $price, $pData)
    {
        if(isset($_SESSION['UID']) && $stateID != 0 && $stateID != null && $cityID != 0 && $cityID != null)
        {
            $profitOwner = $price;
            $statement = $this->dbh->prepare("UPDATE `user` SET `stateID`= :sid, `cityID`= :cid, `cTravelTime`= :ttime, `cash`=`cash`- :price WHERE `id`= :uid");
            $statement->execute(array(':sid' => $stateID, ':cid' => $cityID, ':ttime' => (time()+$sec), ':price' => $price, ':uid' => $_SESSION['UID']));
            
            /** Possession logic for traveling agency | pay owner if exists but not if owner = self **/
            if(is_object($pData)) $agencyOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $agencyOwner > 0 && $agencyOwner != $_SESSION['UID'])
            {
                $this->con->setData("
                    UPDATE `possess` SET `profit`=`profit`+ :profit, `profit_hour`=`profit_hour`+ :profit WHERE `id`= :pid AND `active`='1' AND `deleted`='0';
                    UPDATE `user` SET `bank`=`bank`+ :profit WHERE `id`= :oid AND `active`='1' AND `deleted`='0'
                ", array(':profit' => $profitOwner, ':pid' => $pData->getPossessDetails()->getId(), ':oid' => $agencyOwner));
            }
        }
    }
    
    /** OLD 
    public function getRandCityIdByStateId($stateID)
    {
        if(isset($_SESSION['UID']))
        {
            global $security;
            $statement = $this->dbh->prepare("SELECT `id` FROM `city` WHERE `stateID` = :stateID AND `active`='1' AND `deleted` = '0'");
            $statement->execute(array(':stateID' => $stateID));
            $list = array();
            while($row = $statement->fetch())
            {
                $lijst = array();
                $lijst['id'] = $row['id'];
                array_push($list,$lijst);
            }
            $rand = $security->randInt(1, 3);
            $i=1;
            foreach($list AS $city)
            {
                if($rand == $i) return $city;
                $i++;
            }
        }
    }
    **/
}
