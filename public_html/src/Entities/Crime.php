<?PHP

/* Entity for table crime */

namespace src\Entities;

class Crime
{
    private $id;
    private $name;
    private $description;
    private $picture;
    private $level;
    private $minProfit;
    private $maxProfit;
    private $difficulty;
    private $donatorID;
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

	public function getMinProfit(){
		return $this->minProfit;
	}

	public function setMinProfit($minProfit){
		$this->minProfit = $minProfit;
	}

	public function getMaxProfit(){
		return $this->maxProfit;
	}

	public function setMaxProfit($maxProfit){
		$this->maxProfit = $maxProfit;
	}

	public function getDifficulty(){
		return $this->difficulty;
	}

	public function setDifficulty($difficulty){
		$this->difficulty = $difficulty;
	}
    
    public function getDonatorID(){
		return $this->donatorID;
	}

	public function setDonatorID($donatorID){
		$this->donatorID = $donatorID;
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
