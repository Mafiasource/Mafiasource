<?PHP

/* Entity for table status */

namespace src\Entities;

class Status
{
    private $id;
    private $status;
    private $description;
    private $colorCode;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status){
		$this->status = $status;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getColorCode(){
		return $this->colorCode;
	}

	public function setColorCode($colorCode){
		$this->colorCode = $colorCode;
	}
}
