<?PHP

/* Entity for table detective */

namespace src\Entities;

class Detective
{
    private $id;
    private $userID;
    private $username;
    private $victimID;
    private $victim;
    private $startDate;
    private $hours;
    private $timeFound;
    private $shadow;
    private $foundCityID;
    private $foundCity;
    private $cityID;
    private $city;
    
    private $timeLeft;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
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

	public function getVictimID(){
		return $this->victimID;
	}

	public function setVictimID($victimID){
		$this->victimID = $victimID;
	}

	public function getVictim(){
		return $this->victim;
	}

	public function setVictim($victim){
		$this->victim = $victim;
	}

	public function getStartDate(){
		return $this->startDate;
	}

	public function setStartDate($startDate){
		$this->startDate = $startDate;
	}

	public function getHours(){
		return $this->hours;
	}

	public function setHours($hours){
		$this->hours = $hours;
	}

	public function getTimeFound(){
		return $this->timeFound;
	}

	public function setTimeFound($timeFound){
		$this->timeFound = $timeFound;
	}

	public function getShadow(){
		return $this->shadow;
	}

	public function setShadow($shadow){
		$this->shadow = $shadow;
	}

	public function getFoundCityID(){
		return $this->foundCityID;
	}

	public function setFoundCityID($foundCityID){
		$this->foundCityID = $foundCityID;
	}

	public function getFoundCity(){
		return $this->foundCity;
	}

	public function setFoundCity($foundCity){
		$this->foundCity = $foundCity;
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
    
    public function getTimeLeft(){
		return $this->timeLeft;
	}

	public function setTimeLeft($timeLeft){
		$this->timeLeft = $timeLeft;
	}
 }
 