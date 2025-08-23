<?PHP

namespace src\Data;

use src\Business\GarageService;
use src\Data\config\DBConfig;

class StreetraceDAO extends DBConfig
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

    public function getUserVehicle($garageId)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT g.`id`, v.`horsepower`, v.`topspeed`, v.`acceleration`, v.`control`, v.`breaking`, g.`tires`, g.`engine`, g.`exhaust`, g.`shockAbsorbers` FROM `garage` AS g LEFT JOIN `user_garage` AS ug ON (g.`userGarageID` = ug.`id`) LEFT JOIN `vehicle` AS v ON (g.`vehicleID` = v.`id`) WHERE g.`id` = :gid AND ug.`userID` = :uid AND g.`active`='1' AND g.`deleted`='0' AND v.`active`='1' AND v.`deleted`='0'");
            $statement->execute(array(':gid' => $garageId, ':uid' => $_SESSION['UID']));
            $row = $statement->fetch();
            if(isset($row['id']))
            {
                $garageService = new GarageService();
                foreach(array_keys($garageService->tuneShop) AS $tune)
                {
                    $field = GarageService::getTuneDbField($tune);
                    $row['horsepower'] += $garageService->tuneShop[$tune][$row[$field]]['pk'];
                    $row['topspeed'] += $garageService->tuneShop[$tune][$row[$field]]['ts'];
                    $row['acceleration'] += $garageService->tuneShop[$tune][$row[$field]]['ac'];
                    $row['control'] += $garageService->tuneShop[$tune][$row[$field]]['ct'];
                    $row['breaking'] += $garageService->tuneShop[$tune][$row[$field]]['br'];
                }
                return $row;
            }
        }
        return false;
    }

    public function updateUserCash($amount)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData(
                "UPDATE `user` SET `cash` = `cash` + :amount WHERE `id` = :uid LIMIT 1",
                array(':amount' => $amount, ':uid' => $_SESSION['UID'])
            );
        }
    }
}
