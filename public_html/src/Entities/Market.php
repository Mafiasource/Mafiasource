<?PHP

/* Entity for table market */

namespace src\Entities;

class Market
{
    private $id;
    private $type;
    private $requested;
    private $userID;
    private $username;
    private $amount;
    private $price;
    private $anonymous;
    private $date;
    
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

	public function getRequested(){
		return $this->requested;
	}

	public function setRequested($requested){
		$this->requested = $requested;
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

	public function getAmount(){
		return $this->amount;
	}

	public function setAmount($amount){
		$this->amount = $amount;
	}

	public function getPrice(){
		return $this->price;
	}

	public function setPrice($price){
		$this->price = $price;
	}
    
    public function getAnonymous(){
		return $this->anonymous;
	}

	public function setAnonymous($anonymous){
		$this->anonymous = $anonymous;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}
}
