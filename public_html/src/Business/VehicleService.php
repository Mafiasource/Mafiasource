<?PHP
 
namespace src\Business;
 
use src\Data\VehicleDAO;
 
class VehicleService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new VehicleDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function getRandomVehicleByLv($userLv)
    {
        return $this->data->getRandomVehicleByLv($userLv);
    }
}