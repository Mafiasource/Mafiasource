<?PHP

/* Entity for table donator */

namespace src\Entities;

class Donator
{
    private $id;
    private $donator;
    private $colorCode;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getDonator(){
		return $this->donator;
	}

	public function setDonator($donator){
		$this->donator = $donator;
	}

	public function getColorCode(){
		return $this->colorCode;
	}

	public function setColorCode($colorCode){
		$this->colorCode = $colorCode;
	}
}
