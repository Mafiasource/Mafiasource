<?PHP

/* Entity for table user & user_mission_carjacker */

namespace src\Entities;

class Mission
{
    private $id;
    private $name;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}
}
