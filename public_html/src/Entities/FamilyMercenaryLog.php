<?PHP

/* Entity for table family_mercenary_log */

namespace src\Entities;

class FamilyMercenaryLog
{
    private $id;
    private $familyID;
    private $userID;
    private $username;
    private $mercenaries;
    private $date;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getFamilyID(){
		return $this->familyID;
	}

	public function setFamilyID($familyID){
		$this->familyID = $familyID;
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

	public function getMercenaries(){
		return $this->mercenaries;
	}

	public function setMercenaries($mercenaries){
		$this->mercenaries = $mercenaries;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}
}
