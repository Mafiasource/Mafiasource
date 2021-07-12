<?PHP

/* Entity for table family_raid */

namespace src\Entities;

class FamilyRaid
{
    private $id;
    private $familyID;
    private $stateID;
    private $leaderID;
    private $leader;
    private $driverID;
    private $driver;
    private $bombExpertID;
    private $bombExpert;
    private $weaponExpertID;
    private $weaponExpert;
    private $garageID;
    private $vehicle;
    private $bombType;
    private $bomb;
    private $weaponType;
    private $weapon;
    private $bullets;
    private $driverReady;
    private $bombExpertReady;
    private $weaponExpertReady;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getFamilyID(){
		return $this->familyID;
	}

	public function setFamilyID($familyID){
		$this->familyID = $familyID;
	}

	public function getStateID(){
		return $this->stateID;
	}

	public function setStateID($stateID){
		$this->stateID = $stateID;
	}

	public function getLeaderID(){
		return $this->leaderID;
	}

	public function setLeaderID($leaderID){
		$this->leaderID = $leaderID;
	}

	public function getLeader(){
		return $this->leader;
	}

	public function setLeader($leader){
		$this->leader = $leader;
	}

	public function getDriverID(){
		return $this->driverID;
	}

	public function setDriverID($driverID){
		$this->driverID = $driverID;
	}

	public function getDriver(){
		return $this->driver;
	}

	public function setDriver($driver){
		$this->driver = $driver;
	}

	public function getBombExpertID(){
		return $this->bombExpertID;
	}

	public function setBombExpertID($bombExpertID){
		$this->bombExpertID = $bombExpertID;
	}

	public function getBombExpert(){
		return $this->bombExpert;
	}

	public function setBombExpert($bombExpert){
		$this->bombExpert = $bombExpert;
	}

	public function getWeaponExpertID(){
		return $this->weaponExpertID;
	}

	public function setWeaponExpertID($weaponExpertID){
		$this->weaponExpertID = $weaponExpertID;
	}

	public function getWeaponExpert(){
		return $this->weaponExpert;
	}

	public function setWeaponExpert($weaponExpert){
		$this->weaponExpert = $weaponExpert;
	}

	public function getGarageID(){
		return $this->garageID;
	}

	public function setGarageID($garageID){
		$this->garageID = $garageID;
	}

	public function getVehicle(){
		return $this->vehicle;
	}

	public function setVehicle($vehicle){
		$this->vehicle = $vehicle;
	}

	public function getBombType(){
		return $this->bombType;
	}

	public function setBombType($bombType){
		$this->bombType = $bombType;
	}

	public function getBomb(){
		return $this->bomb;
	}

	public function setBomb($bomb){
		$this->bomb = $bomb;
	}

	public function getWeaponType(){
		return $this->weaponType;
	}

	public function setWeaponType($weaponType){
		$this->weaponType = $weaponType;
	}

	public function getWeapon(){
		return $this->weapon;
	}

	public function setWeapon($weapon){
		$this->weapon = $weapon;
	}

	public function getBullets(){
		return $this->bullets;
	}

	public function setBullets($bullets){
		$this->bullets = $bullets;
	}

	public function getDriverReady(){
		return $this->driverReady;
	}

	public function setDriverReady($driverReady){
		$this->driverReady = $driverReady;
	}

	public function getBombExpertReady(){
		return $this->bombExpertReady;
	}

	public function setBombExpertReady($bombExpertReady){
		$this->bombExpertReady = $bombExpertReady;
	}

	public function getWeaponExpertReady(){
		return $this->weaponExpertReady;
	}

	public function setWeaponExpertReady($weaponExpertReady){
		$this->weaponExpertReady = $weaponExpertReady;
	}
}
