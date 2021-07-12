<?PHP

/* Entity for table lottery_winner */

namespace src\Entities;

class LotteryWinner
{
    private $id;
    private $type;
    private $userID;
    private $username;
    private $prize;
    private $place;
    
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

	public function getPlace(){
		return $this->place;
	}

	public function setPlace($place){
		$this->place = $place;
	}
}
