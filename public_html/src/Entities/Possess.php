<?PHP

/* Entity for table possess */

namespace src\Entities;

class Possess
{
    private $id;
    private $pID;
    private $stateID;
    private $state;
    private $cityID;
    private $city;
    private $userID;
    private $username;
    private $profit;
    private $profitHour;
    private $stake;
    private $isOwner;
    
   	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getPID(){
		return $this->pID;
	}

	public function setPID($pID){
		$this->pID = $pID;
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

	public function getCityID(){
		return $this->cityID;
	}

	public function setCityID($cityID){
		$this->cityID = $cityID;
	}
    
    public function getCity(){
		return $this->city;
	}

	public function setCity($city){
		$this->city = $city;
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

	public function getProfit(){
		return $this->profit;
	}

	public function setProfit($profit){
		$this->profit = $profit;
	}

	public function getProfitHour(){
		return $this->profitHour;
	}

	public function setProfitHour($profitHour){
		$this->profitHour = $profitHour;
	}

	public function getStake(){
		return $this->stake;
	}

	public function setStake($stake){
		$this->stake = $stake;
	}

	public function getIsOwner(){
		return $this->isOwner;
	}

	public function setIsOwner($isOwner){
		$this->isOwner = $isOwner;
	}
}
