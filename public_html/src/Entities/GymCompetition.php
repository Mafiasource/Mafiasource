<?PHP

/* Entity for table gym_competition */

namespace src\Entities;

class GymCompetition
{
    private $id;
    private $userID;
    private $username;
    private $cityID;
    private $city;
    private $type;
    private $stake;
    private $participantID;
    private $participant;
    private $winnerID;
    private $winner;
    private $startDate;
    private $endDate;
    
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

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getStake(){
		return $this->stake;
	}

	public function setStake($stake){
		$this->stake = $stake;
	}

	public function getParticipantID(){
		return $this->participantID;
	}

	public function setParticipantID($participantID){
		$this->participantID = $participantID;
	}

	public function getParticipant(){
		return $this->participant;
	}

	public function setParticipant($participant){
		$this->participant = $participant;
	}

	public function getWinnerID(){
		return $this->winnerID;
	}

	public function setWinnerID($winnerID){
		$this->winnerID = $winnerID;
	}
    
    public function getWinner(){
		return $this->winner;
	}

	public function setWinner($winner){
		$this->winner = $winner;
	}

	public function getStartDate(){
		return $this->startDate;
	}

	public function setStartDate($startDate){
		$this->startDate = $startDate;
	}

	public function getEndDate(){
		return $this->endDate;
	}

	public function setEndDate($endDate){
		$this->endDate = $endDate;
	}
}
