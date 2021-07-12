<?PHP

/* Entity for table business_news */

namespace src\Entities;

class BusinessNews
{
    private $id;
    private $description;
    private $businessID;
    private $type;
    
    private $business;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getBusinessID(){
		return $this->businessID;
	}

	public function setBusinessID($businessID){
		$this->businessID = $businessID;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}
    
    public function getBusiness(){
		return $this->business;
	}

	public function setBusiness($business){
		$this->business = $business;
	}
}
