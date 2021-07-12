<?PHP

/* Entity for table users_garage */

namespace src\Entities;

class UserGarage
{
    private $id;
    private $stateID;
    private $state;
    private $userID;
    private $size;
    
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
    
    public function getState(){
		return $this->state;
	}

	public function setState($state){
		$this->state = $state;
	}

	public function getUserID(){
		return $this->userID;
	}

	public function setUserID($userID){
		$this->userID = $userID;
	}

	public function getSize(){
		return $this->size;
	}

	public function setSize($size){
		$this->size = $size;
	}
}
