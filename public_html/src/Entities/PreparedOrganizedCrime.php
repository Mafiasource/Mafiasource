<?PHP

/* Entity for table crime_org_prep */

namespace src\Entities;

class PreparedOrganizedCrime
{
    private $id;
    private $orgCrimeID;
    private $userID;
    private $username;
    private $job;
    private $participantID;
    private $participant;
    private $participant2ID;
    private $participant2;
    private $participant3ID;
    private $participant3;
    private $userReady;
    private $participantReady;
    private $participant2Ready;
    private $participant3Ready;
    private $garageID;
    private $weaponType;
    private $intelType;
    private $commitTime;
    
    private $vehicle;
    private $weapon;
    private $intel;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
    
    public function getOrgCrimeID(){
		return $this->orgCrimeID;
	}

	public function setOrgCrimeID($orgCrimeID){
		$this->orgCrimeID = $orgCrimeID;
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
    
    public function getJob(){
		return $this->job;
	}

	public function setJob($job){
		$this->job = $job;
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

	public function getParticipant2ID(){
		return $this->participant2ID;
	}

	public function setParticipant2ID($participant2ID){
		$this->participant2ID = $participant2ID;
	}

	public function getParticipant2(){
		return $this->participant2;
	}

	public function setParticipant2($participant2){
		$this->participant2 = $participant2;
	}

	public function getParticipant3ID(){
		return $this->participant3ID;
	}

	public function setParticipant3ID($participant3ID){
		$this->participant3ID = $participant3ID;
	}

	public function getParticipant3(){
		return $this->participant3;
	}

	public function setParticipant3($participant3){
		$this->participant3 = $participant3;
	}
    
    public function getUserReady(){
		return $this->userReady;
	}

	public function setUserReady($userReady){
		$this->userReady = $userReady;
	}

	public function getParticipantReady(){
		return $this->participantReady;
	}

	public function setParticipantReady($participantReady){
		$this->participantReady = $participantReady;
	}

	public function getParticipant2Ready(){
		return $this->participant2Ready;
	}

	public function setParticipant2Ready($participant2Ready){
		$this->participant2Ready = $participant2Ready;
	}

	public function getParticipant3Ready(){
		return $this->participant3Ready;
	}

	public function setParticipant3Ready($participant3Ready){
		$this->participant3Ready = $participant3Ready;
	}

	public function getGarageID(){
		return $this->garageID;
	}

	public function setGarageID($garageID){
		$this->garageID = $garageID;
	}

	public function getWeaponType(){
		return $this->weaponType;
	}

	public function setWeaponType($weaponType){
		$this->weaponType = $weaponType;
	}

	public function getIntelType(){
		return $this->intelType;
	}

	public function setIntelType($intelType){
		$this->intelType = $intelType;
	}
    
    public function getCommitTime(){
		return $this->commitTime;
	}

	public function setCommitTime($commitTime){
		$this->commitTime = $commitTime;
	}

	public function getVehicle(){
		return $this->vehicle;
	}

	public function setVehicle($vehicle){
		$this->vehicle = $vehicle;
	}

	public function getWeapon(){
		return $this->weapon;
	}

	public function setWeapon($weapon){
		$this->weapon = $weapon;
	}

	public function getIntel(){
		return $this->intel;
	}

	public function setIntel($intel){
		$this->intel = $intel;
	}
}
