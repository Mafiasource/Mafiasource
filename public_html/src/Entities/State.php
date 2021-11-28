<?PHP

/* Entity for table state */

namespace src\Entities;

class State
{
    private $id;
    private $name;
    
    private $cities;
    
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
    
    public function getCities(){
		return $this->cities;
	}

	public function setCities($cities){
		$this->cities = $cities;
	}
}
