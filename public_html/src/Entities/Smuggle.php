<?PHP

/* Entity for table smuggle */

namespace src\Entities;

class Smuggle
{
    private $id;
    private $type;
    private $name;
    private $description;
    private $picture;
    private $level;
    private $unitInfo;    
    private $active;
    
   	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
    
    public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
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
    
    public function getUnitInfo(){
        return $this->unitInfo;
    }
    
    public function setUnitInfo($unitInfo)
    {
        $this->unitInfo = $unitInfo;
    }

	public function getActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
	}
}
