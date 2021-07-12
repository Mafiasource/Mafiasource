<?PHP

/* Entity for table hitlist */

namespace src\Entities;

class Hitlist
{
    private $id;
    private $ordererID;
    private $orderer;
    private $userID;
    private $username;
    private $prize;
    private $reason;
    private $anonymous;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getOrdererID(){
		return $this->ordererID;
	}

	public function setOrdererID($ordererID){
		$this->ordererID = $ordererID;
	}

	public function getOrderer(){
		return $this->orderer;
	}

	public function setOrderer($orderer){
		$this->orderer = $orderer;
	}

	public function getUserID(){
		return $this->userID;
	}

	public function setUserID($userID){
		$this->userID = $userID;
	}

	public function getUsername(){
		return $this->username;
	}

	public function setUsername($username){
		$this->username = $username;
	}

	public function getPrize(){
		return $this->prize;
	}

	public function setPrize($prize){
		$this->prize = $prize;
	}

	public function getReason(){
		return $this->reason;
	}

	public function setReason($reason){
		$this->reason = $reason;
	}

	public function getAnonymous(){
		return $this->anonymous;
	}

	public function setAnonymous($anonymous){
		$this->anonymous = $anonymous;
	}
}
