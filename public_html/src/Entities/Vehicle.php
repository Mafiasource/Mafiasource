<?PHP

/* Entity for table vehicle */

namespace src\Entities;

class Vehicle
{
    private $id;
    private $name;
    private $description;
    private $picture;
    private $price;
    private $horsepower;
    private $topspeed;
    private $acceleration;
    private $control;
    private $breaking;
    private $stealLv;
    
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

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
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

	public function getHorsepower(){
		return $this->horsepower;
	}

	public function setHorsepower($horsepower){
		$this->horsepower = $horsepower;
	}

	public function getTopspeed(){
		return $this->topspeed;
	}

	public function setTopspeed($topspeed){
		$this->topspeed = $topspeed;
	}

	public function getAcceleration(){
		return $this->acceleration;
	}

	public function setAcceleration($acceleration){
		$this->acceleration = $acceleration;
	}

	public function getControl(){
		return $this->control;
	}

	public function setControl($control){
		$this->control = $control;
	}

	public function getBreaking(){
		return $this->breaking;
	}

	public function setBreaking($breaking){
		$this->breaking = $breaking;
	}

	public function getStealLv(){
		return $this->stealLv;
	}

	public function setStealLv($stealLv){
		$this->stealLv = $stealLv;
	}
}
