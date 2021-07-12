<?PHP

/* Entity for table family_alliance */

namespace src\Entities;

class FamilyAlliance
{
    private $id;
    private $familyID;
    private $family;
    private $familyIcon;
    private $allianceFamilyID;
    private $allianceFamily;
    private $allianceFamilyIcon;
    private $requesterFamilyID;
    private $accepted;
    
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

	public function getFamily(){
		return $this->family;
	}

	public function setFamily($family){
		$this->family = $family;
	}
    
    public function getFamilyIcon(){
		return $this->familyIcon;
	}

	public function setFamilyIcon($familyIcon){
		$this->familyIcon = $familyIcon;
	}

	public function getAllianceFamilyID(){
		return $this->allianceFamilyID;
	}

	public function setAllianceFamilyID($allianceFamilyID){
		$this->allianceFamilyID = $allianceFamilyID;
	}

	public function getAllianceFamily(){
		return $this->allianceFamily;
	}

	public function setAllianceFamily($allianceFamily){
		$this->allianceFamily = $allianceFamily;
	}
    
    public function getAllianceFamilyIcon(){
		return $this->allianceFamilyIcon;
	}

	public function setAllianceFamilyIcon($allianceFamilyIcon){
		$this->allianceFamilyIcon = $allianceFamilyIcon;
	}
    
    public function getRequesterFamilyID(){
		return $this->requesterFamilyID;
	}

	public function setRequesterFamilyID($requesterFamilyID){
		$this->requesterFamilyID = $requesterFamilyID;
	}
    

	public function getAccepted(){
		return $this->accepted;
	}

	public function setAccepted($accepted){
		$this->accepted = $accepted;
	}
}
