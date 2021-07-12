<?PHP

/* Entity for table weapon */

namespace src\Entities;

class Weapon
{
    private $id;
    private $name;
    private $picture;
    private $price;
    private $wpnExpTrain;
    private $damage;
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

	public function getWpnExpTrain(){
		return $this->wpnExpTrain;
	}

	public function setWpnExpTrain($wpnExpTrain){
		$this->wpnExpTrain = $wpnExpTrain;
	}

	public function getDamage(){
		return $this->damage;
	}

	public function setDamage($damage){
		$this->damage = $damage;
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
