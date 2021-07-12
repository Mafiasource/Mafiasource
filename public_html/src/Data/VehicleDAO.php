<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Entities\Vehicle;

class VehicleDAO extends DBConfig
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
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `vehicle` WHERE `stealLv` <='100' AND `active` = '1' AND `deleted` = '0' LIMIT 1");
            $statement->execute();
            $row = $statement->fetch();
            return $row['total'];
        }
    }
    
    public function getRandomVehicleByLv($userLv)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `name`, `price`, `picture` FROM `vehicle` WHERE `active` = '1' AND `deleted` = '0' AND `stealLv` <= :userLv ORDER BY RAND() LIMIT 1");
            $statement->execute(array(':userLv' => $userLv));
            $row = $statement->fetch();
            
            global $userData;
            if($userData->getDonatorID() >= 1)
                $row['price'] *= 0.95;
            
            $vehicle = new Vehicle();
            $vehicle->setId($row['id']);
            $vehicle->setName($row['name']);
            $vehicle->setPrice($row['price']);
            $vehicle->setPicture($row['picture']);
            
            return $vehicle;
        }
    }
}
