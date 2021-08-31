<?PHP

/* Entity for table garage */

namespace src\Entities;

class Garage
{
    private $id;
    private $userGarageID;
    private $famGarageID;
    private $damage;
    private $tires;
    private $engine;
    private $exhaust;
    private $shockAbsorbers;
    
    private $value;
    private $repairCosts;
    private $vehicle;
    
    public function getId(){
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function getUserGarageID(){
        return $this->userGarageID;
    }
    
    public function setUserGarageID($userGarageID){
        $this->userGarageID = $userGarageID;
    }
    
    public function getFamGarageID(){
        return $this->famGarageID;
    }
    
    public function setFamGarageID($famGarageID){
        $this->famGarageID = $famGarageID;
    }
    
    public function getDamage(){
        return $this->damage;
    }
    
    public function setDamage($damage){
        $this->damage = $damage;
    }
    
    public function getTires(){
        return $this->tires;
    }
    
    public function setTires($tires){
        $this->tires = $tires;
    }
    
    public function getEngine(){
        return $this->engine;
    }
    
    public function setEngine($engine){
        $this->engine = $engine;
    }
    
    public function getExhaust(){
        return $this->exhaust;
    }
    
    public function setExhaust($exhaust){
        $this->exhaust = $exhaust;
    }
    
    public function getShockAbsorbers(){
        return $this->shockAbsorbers;
    }
    
    public function setShockAbsorbers($shockAbsorbers){
        $this->shockAbsorbers = $shockAbsorbers;
    }
    
    public function getValue(){
        return $this->value;
    }
    
    public function setValue($value){
        $this->value = $value;
    }        
    
    public function getRepairCosts(){
        return $this->repairCosts;
    }
    
    public function setRepairCosts($repairCosts){
        $this->repairCosts = $repairCosts;
    }
    
    public function getVehicle(){
        return $this->vehicle;
    }
    
    public function setVehicle($vehicle){
        $this->vehicle = $vehicle;
    }
}
