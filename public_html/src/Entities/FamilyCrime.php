<?PHP

/* Entity for table family_crime */

namespace src\Entities;

class FamilyCrime
{
    private $id;
    private $starterUID;
    private $numParticipants;
    private $participants;
    private $familyID;
    private $stateID;
    private $withMercenaries;
    private $crime;
    
    private $numInCrime;
    private $participantIds;
    private $state;
    private $mercenariesReady;
    private $crimeID;
    private $userInCrime;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getStarterUID(){
		return $this->starterUID;
	}

	public function setStarterUID($starterUID){
		$this->starterUID = $starterUID;
	}

	public function getNumParticipants(){
		return $this->numParticipants;
	}

	public function setNumParticipants($numParticipants){
		$this->numParticipants = $numParticipants;
	}

	public function getParticipants(){
		return $this->participants;
	}

	public function setParticipants($participants){
		$this->participants = $participants;
	}

	public function getFamilyID(){
		return $this->familyID;
	}

	public function setFamilyID($familyID){
		$this->familyID = $familyID;
	}

	public function getStateID(){
		return $this->stateID;
	}

	public function setStateID($stateID){
		$this->stateID = $stateID;
	}
    
    public function getWithMercenaries(){
		return $this->withMercenaries;
	}

	public function setWithMercenaries($withMercenaries){
		$this->withMercenaries = $withMercenaries;
	}

	public function getCrime(){
		return $this->crime;
	}

	public function setCrime($crime){
		$this->crime = $crime;
	}
    
    public function getNumInCrime(){
		return $this->numInCrime;
	}

	public function setNumInCrime($numInCrime){
		$this->numInCrime = $numInCrime;
	}
    
    public function getParticipantIds(){
		return $this->participantIds;
	}

	public function setParticipantIds($participantIds){
		$this->participantIds = $participantIds;
	}
    
    public function getState(){
		return $this->state;
	}

	public function setState($state){
		$this->state = $state;
	}
    
    public function getMercenariesReady(){
		return $this->mercenariesReady;
	}

	public function setMercenariesReady($mercenariesReady){
		$this->mercenariesReady = $mercenariesReady;
	}
    
    public function getCrimeID(){
		return $this->crimeID;
	}

	public function setCrimeID($crimeID){
		$this->crimeID = $crimeID;
	}
    
    public function getUserInCrime(){
		return $this->userInCrime;
	}

	public function setUserInCrime($userInCrime){
		$this->userInCrime = $userInCrime;
	}
}
