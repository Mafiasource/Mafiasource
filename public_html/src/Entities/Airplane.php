<?PHP

/* Entity for table airplane */

namespace src\Entities;

class Airplane
{
    private $id;
    private $name;
    private $picture;
    private $price;
    private $power;
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

	public function getPower(){
		return $this->power;
	}

	public function setPower($power){
		$this->power = $power;
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