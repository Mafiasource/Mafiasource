<?PHP

/* Entity for table bullet_factory */

namespace src\Entities;

class BulletFactory
{
    private $id;
    private $possessID;
    private $bullets;
    private $priceEachBullet;
    private $production;
    private $producing;
    
    private $stateID;
    private $state;
    private $ownerID;
    private $owner;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getPossessID(){
		return $this->possessID;
	}

	public function setPossessID($possessID){
		$this->possessID = $possessID;
	}

	public function getBullets(){
		return $this->bullets;
	}

	public function setBullets($bullets){
		$this->bullets = $bullets;
	}

	public function getPriceEachBullet(){
		return $this->priceEachBullet;
	}

	public function setPriceEachBullet($priceEachBullet){
		$this->priceEachBullet = $priceEachBullet;
	}

	public function getProduction(){
		return $this->production;
	}

	public function setProduction($production){
		$this->production = $production;
	}
    
    public function getProducing(){
		return $this->producing;
	}

	public function setProducing($producing){
		$this->producing = $producing;
	}
    
 	public function getStateID(){
		return $this->stateID;
	}

	public function setStateID($stateID){
		$this->stateID = $stateID;
	}

	public function getState(){
		return $this->state;
	}

	public function setState($state){
		$this->state = $state;
	}

	public function getOwnerID(){
		return $this->ownerID;
	}

	public function setOwnerID($ownerID){
		$this->ownerID = $ownerID;
	}

	public function getOwner(){
		return $this->owner;
	}

	public function setOwner($owner){
		$this->owner = $owner;
	}
}