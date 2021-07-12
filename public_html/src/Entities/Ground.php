<?PHP

/* Entity for table ground**/

namespace src\Entities;

class Ground
{
    private $id;
    private $gID;
    private $stateID;
    private $state;
    private $userID;
    private $username;
    private $buildings;
    private $building1;
    private $building2;
    private $building3;
    private $building4;
    private $building5;
    private $cBuilding1;
    private $cBuilding2;
    private $cBuilding3;
    private $cBuilding4;
    private $cBuilding5;
    
    private $coordsLeftPx;
    private $coordsTopPx;
    private $picture;
    private $inPossession;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getGID(){
		return $this->gID;
	}

	public function setGID($gID){
		$this->gID = $gID;
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

	public function getUserID(){
		return $this->userID;
	}

	public function setUserID($userID){
		$this->userID = $userID;
	}

	public function getUsername(){
		return $this->username;
	}

	public function setUsername($username){
		$this->username = $username;
	}
    
    public function getBuildings(){
		return $this->buildings;
	}

	public function setBuildings($buildings){
		$this->buildings = $buildings;
	}

	public function getBuilding1(){
		return $this->building1;
	}

	public function setBuilding1($building1){
		$this->building1 = $building1;
	}

	public function getBuilding2(){
		return $this->building2;
	}

	public function setBuilding2($building2){
		$this->building2 = $building2;
	}

	public function getBuilding3(){
		return $this->building3;
	}

	public function setBuilding3($building3){
		$this->building3 = $building3;
	}

	public function getBuilding4(){
		return $this->building4;
	}

	public function setBuilding4($building4){
		$this->building4 = $building4;
	}

	public function getBuilding5(){
		return $this->building5;
	}
    
    public function setBuilding5($building5){
		$this->building5 = $building5;
	}
    
 	public function getCBuilding1(){
		return $this->cBuilding1;
	}

	public function setCBuilding1($cBuilding1){
		$this->cBuilding1 = $cBuilding1;
	}

	public function getCBuilding2(){
		return $this->cBuilding2;
	}

	public function setCBuilding2($cBuilding2){
		$this->cBuilding2 = $cBuilding2;
	}

	public function getCBuilding3(){
		return $this->cBuilding3;
	}

	public function setCBuilding3($cBuilding3){
		$this->cBuilding3 = $cBuilding3;
	}

	public function getCBuilding4(){
		return $this->cBuilding4;
	}

	public function setCBuilding4($cBuilding4){
		$this->cBuilding4 = $cBuilding4;
	}

	public function getCBuilding5(){
		return $this->cBuilding5;
	}

	public function setCBuilding5($cBuilding5){
		$this->cBuilding5 = $cBuilding5;
	}

	public function getCoordsLeftPx(){
		return $this->coordsLeftPx;
	}

	public function setCoordsLeftPx($coordsLeftPx){
		$this->coordsLeftPx = $coordsLeftPx;
	}

	public function getCoordsTopPx(){
		return $this->coordsTopPx;
	}

	public function setCoordsTopPx($coordsTopPx){
		$this->coordsTopPx = $coordsTopPx;
	}
    
    public function getPicture(){
		return $this->picture;
	}

	public function setPicture($picture){
		$this->picture = $picture;
	}
    
	public function getInPossession(){
		return $this->inPossession;
	}

	public function setInPossession($inPossession){
		$this->inPossession = $inPossession;
	}
}
