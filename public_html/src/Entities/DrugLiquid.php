<?PHP

/* Entity for table drug_liquid */

namespace src\Entities;

class DrugLiquid
{
    private $id;
    private $userID;
    private $typeID;
    private $type;
    private $smuggleID;
    private $smuggleName;
    private $units;
    private $time;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getUserID(){
		return $this->userID;
	}

	public function setUserID($userID){
		$this->userID = $userID;
	}

	public function getTypeID(){
		return $this->typeID;
	}

	public function setTypeID($typeID){
		$this->typeID = $typeID;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getSmuggleID(){
		return $this->smuggleID;
	}

	public function setSmuggleID($smuggleID){
		$this->smuggleID = $smuggleID;
	}

	public function getSmuggleName(){
		return $this->smuggleName;
	}

	public function setSmuggleName($smuggleName){
		$this->smuggleName = $smuggleName;
	}

	public function getUnits(){
		return $this->units;
	}

	public function setUnits($units){
		$this->units = $units;
	}

	public function getTime(){
		return $this->time;
	}

	public function setTime($time){
		$this->time = $time;
	}
}
