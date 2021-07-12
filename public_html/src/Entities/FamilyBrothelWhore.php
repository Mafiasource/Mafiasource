<?PHP

/* Entity for table family_brothel_whore */

namespace src\Entities;

class FamilyBrothelWhore
{
    private $id;
    private $familyID;
    private $userID;
    private $whores;
    
    private $total;
    
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

	public function getWhores(){
		return $this->whores;
	}

	public function setWhores($whores){
		$this->whores = $whores;
	}

	public function getTotal(){
		return $this->total;
	}

	public function setTotal($total){
		$this->total = $total;
	}
}
