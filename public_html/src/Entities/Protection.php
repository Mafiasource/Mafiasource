<?PHP

/* Entity for table protection */

namespace src\Entities;

class Protection
{
    private $id;
    private $name;
    private $picture;
    private $price;
    private $protection;
    private $inPossession;
    private $equipped;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getPicture(){
		return $this->picture;
	}

	public function setPicture($picture){
		$this->picture = $picture;
	}

	public function getPrice(){
		return $this->price;
	}

	public function setPrice($price){
		$this->price = $price;
	}

	public function getProtection(){
		return $this->protection;
	}

	public function setProtection($protection){
		$this->protection = $protection;
	}
    
    public function getInPossession(){
		return $this->inPossession;
	}

	public function setInPossession($inPossession){
		$this->inPossession = $inPossession;
	}
    
    public function getEquipped(){
		return $this->equipped;
	}

	public function setEquipped($equipped){
		$this->equipped = $equipped;
	}
}
