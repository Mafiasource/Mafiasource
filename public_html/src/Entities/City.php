<?PHP

/* Entity for table city */

namespace src\Entities;

class City
{
    private $id;
    private $stateID;
    private $name;
    
   	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
    
    public function getStateID(){
		return $this->stateID;
	}

	public function setStateID($stateID){
		$this->stateID = $stateID;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}
}
