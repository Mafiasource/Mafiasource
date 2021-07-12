<?PHP

/* Entity for table possession */

namespace src\Entities;

class Possession
{
    private $id;
    private $name;
    private $picture;
    private $price;
    
    private $possessDetails;
    private $bulletFactoryDetails;
    private $redLightDistrictDetails;
    
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
    public function getPossessDetails(){
		return $this->possessDetails;
	}

	public function setPossessDetails($possessDetails){
		$this->possessDetails = $possessDetails;
	}
    
	public function getBulletFactoryDetails(){
		return $this->bulletFactoryDetails;
	}

	public function setBulletFactoryDetails($bulletFactoryDetails){
		$this->bulletFactoryDetails = $bulletFactoryDetails;
	}

	public function getRedLightDistrictDetails(){
		return $this->redLightDistrictDetails;
	}

	public function setRedLightDistrictDetails($redLightDistrictDetails){
		$this->redLightDistrictDetails = $redLightDistrictDetails;
	}
}
