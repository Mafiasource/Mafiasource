<?PHP

/* Entity for table lottery */

namespace src\Entities;

class Lottery
{
    private $id;
    private $type;
    private $userID;
    
    private $hasTicket;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getUserID(){
		return $this->userID;
	}

	public function setUserID($userID){
		$this->userID = $userID;
	}

	public function getHasTicket(){
		return $this->hasTicket;
	}

	public function setHasTicket($hasTicket){
		$this->hasTicket = $hasTicket;
	}
}
