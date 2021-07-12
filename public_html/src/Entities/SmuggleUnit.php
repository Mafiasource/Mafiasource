<?PHP

/* Entity for table smuggle_unit */

namespace src\Entities;

class SmuggleUnit
{
    private $userID;
    private $typeNr;
    private $type;
    private $unitNr;
    private $unitName;
    
    private $maxCapacity;
    private $inPossession;
    
    public function getUserID(){
		return $this->userID;
	}

	public function setUserID($userID){
		$this->userID = $userID;
	}

	public function getTypeNr(){
		return $this->typeNr;
	}

	public function setTypeNr($typeNr){
		$this->typeNr = $typeNr;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getUnitNr(){
		return $this->unitNr;
	}

	public function setUnitNr($unitNr){
		$this->unitNr = $unitNr;
	}

	public function getUnitName(){
		return $this->unitName;
	}

	public function setUnitName($unitName){
		$this->unitName = $unitName;
	}

	public function getMaxCapacity(){
		return $this->maxCapacity;
	}

	public function setMaxCapacity($maxCapacity){
		$this->maxCapacity = $maxCapacity;
	}
    
    public function getInPossession(){
		return $this->inPossession;
	}

	public function setInPossession($inPossession){
		$this->inPossession = $inPossession;
	}
}
