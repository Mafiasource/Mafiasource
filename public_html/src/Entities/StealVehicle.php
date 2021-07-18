<?PHP

/* Entity for table steal_vehicle */

namespace src\Entities;

class StealVehicle
{
    private $id;
    private $name;
    private $description;
    private $picture;
    private $level;
    private $difficulty;
    private $maxRankPoints;
    private $active;
    
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

	public function getLevel(){
		return $this->level;
	}

	public function setLevel($level){
		$this->level = $level;
	}

	public function getDifficulty(){
		return $this->difficulty;
	}

	public function setDifficulty($difficulty){
		$this->difficulty = $difficulty;
	}

	public function getMaxRankPoints(){
		return $this->maxRankPoints;
	}

	public function setMaxRankPoints($maxRankPoints){
		$this->maxRankPoints = $maxRankPoints;
	}

	public function getActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
	}
}
