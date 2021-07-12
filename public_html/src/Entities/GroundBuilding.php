<?PHP

/* Entity for table ground_building */

namespace src\Entities;

class GroundBuilding
{
    private $id;
    private $name;
    private $picture;
    private $price;
    private $income;
    
    private $inPossession;
    private $level;
    
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

	public function getIncome(){
		return $this->income;
	}

	public function setIncome($income){
		$this->income = $income;
	}

	public function getInPossession(){
		return $this->inPossession;
	}

	public function setInPossession($inPossession){
		$this->inPossession = $inPossession;
	}
    
    public function getLevel(){
		return $this->level;
	}

	public function setLevel($level){
		$this->level = $level;
	}
}
