<?PHP

/* Entity for table garage */

namespace src\Entities;

class Garage
{
    private $id;
    private $userGarageID;
    private $famGarageID;
    private $vehicleID;
    private $vehicleName;
    private $vehicleValue;
    private $vehiclePicture;
    private $damage;
    private $repairCosts;
    
    public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getUserGarageID(){
		return $this->userGarageID;
	}

	public function setUserGarageID($userGarageID){
		$this->userGarageID = $userGarageID;
	}

	public function getFamGarageID(){
		return $this->famGarageID;
	}

	public function setFamGarageID($famGarageID){
		$this->famGarageID = $famGarageID;
	}
    
	public function getVehicleID(){
		return $this->vehicleID;
	}

	public function setVehicleID($vehicleID){
		$this->vehicleID = $vehicleID;
	}

	public function getVehicleName(){
		return $this->vehicleName;
	}

	public function setVehicleName($vehicleName){
		$this->vehicleName = $vehicleName;
	}
    
    public function getVehicleValue(){
		return $this->vehicleValue;
	}

	public function setVehicleValue($vehicleValue){
		$this->vehicleValue = $vehicleValue;
	}

	public function getVehiclePicture(){
		return $this->vehiclePicture;
	}

	public function setVehiclePicture($vehiclePicture){
		$this->vehiclePicture = $vehiclePicture;
	}

	public function getDamage(){
		return $this->damage;
	}

	public function setDamage($damage){
		$this->damage = $damage;
	}
    
    public function getRepairCosts(){
		return $this->repairCosts;
	}

	public function setRepairCosts($repairCosts){
		$this->repairCosts = $repairCosts;
	}
}
