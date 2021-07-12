<?PHP

/* Entity for table family_garage */

namespace src\Entities;

class FamilyGarage
{
    private $id;
    private $familyID;
    private $size;
    
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

	public function getSize(){
		return $this->size;
	}

	public function setSize($size){
		$this->size = $size;
	}
}
