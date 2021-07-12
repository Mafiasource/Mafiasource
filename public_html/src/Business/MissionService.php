<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\Logic\game\Statics\Mission AS MissionStatics;
use src\Data\MissionDAO;

class MissionService extends MissionStatics
{
    private $data;
    
    public function __construct()
    {
        parent::__construct();
        $this->data = new MissionDAO();
    }

    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getMissions()
    {
        global $lang;
        $list = array();
        foreach($this->missions AS $k => $m)
            $list[$k] = array('name' => $m, 'description' => $this->missionDescriptions[$lang][$k]);
        
        return $list;
    }
    
    public function getMissionTiersAndProgress()
    {
        return $this->data->getMissionTiersAndProgress();
    }
    
    public function userHasStolenVehicleBefore($vehicleID)
    {
        return $this->data->userHasStolenVehicleBefore($vehicleID);
    }
    
    public function addNewCarjackerVehicle($vehicleID)
    {
        return $this->data->addNewCarjackerVehicle($vehicleID);
    }
    
    public function addCountToCarjackerVehicle($vehicleID)
    {
        return $this->data->addCountToCarjackerVehicle($vehicleID);
    }
    
    public function addToMission5Count($userID, $amount)
    {
        return $this->data->addToMission5Count($userID, $amount);
    }
    
    public function addToMission8Count($amount)
    {
        return $this->data->addToMission8Count($amount);
    }
    
    public function getProfileMissionsByUserID($userID)
    {
        return $this->data->getProfileMissionsByUserID($userID);
    }
}
