<?PHP

/* Entity for table garage */

namespace src\Entities;

class Garage
{
    private $id;
    private $userGarageID;
    private $famGarageID;
    private $value;
    private $damage;
    private $repairCosts;
    private $vehicle;
    
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
    
    public function getValue(){
		return $this->value;
	}

	public function setValue($value){
		$this->value = $value;
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
    
    public function getVehicle(){
		return $this->vehicle;
	}

	public function setVehicle($vehicle){
		$this->vehicle = $vehicle;
	}
}
