<?PHP

/* Entity for table crime_org */

namespace src\Entities;

class OrganizedCrime
{
    private $id;
    private $name;
    private $description;
    private $picture;
    private $typeID;
    private $type;
    private $minProfit;
    private $maxProfit;
    private $difficulty;
    private $maxRankPoints;
    private $waitingTimeCompletion;
    private $travelTimeCompletion;
    private $prisonTimeBusted;
    private $active;
    
    private $preparedCrime;
    
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

	public function getTypeID(){
		return $this->typeID;
	}

	public function setTypeID($typeID){
		$this->typeID = $typeID;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
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

	public function getMaxRankPoints(){
		return $this->maxRankPoints;
	}

	public function setMaxRankPoints($maxRankPoints){
		$this->maxRankPoints = $maxRankPoints;
	}
    
    public function getWaitingTimeCompletion(){
		return $this->waitingTimeCompletion;
	}

	public function setWaitingTimeCompletion($waitingTimeCompletion){
		$this->waitingTimeCompletion = $waitingTimeCompletion;
	}
    
    public function getTravelTimeCompletion(){
		return $this->travelTimeCompletion;
	}

	public function setTravelTimeCompletion($travelTimeCompletion){
		$this->travelTimeCompletion = $travelTimeCompletion;
	}
    
    public function getPrisonTimeBusted(){
		return $this->prisonTimeBusted;
	}

	public function setPrisonTimeBusted($prisonTimeBusted){
		$this->prisonTimeBusted = $prisonTimeBusted;
	}

	public function getActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
	}
    
    public function getPreparedCrime(){
		return $this->preparedCrime;
	}

	public function setPreparedCrime($preparedCrime){
		$this->preparedCrime = $preparedCrime;
	}
}
