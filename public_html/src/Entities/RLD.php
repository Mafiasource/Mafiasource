<?PHP

/* Entity for table rld */

namespace src\Entities;

class RLD
{
    private $id;
    private $possessID;
    private $windows;
    private $windowsUsed;
    private $priceEachWindow;
    
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

	public function getWindows(){
		return $this->windows;
	}

	public function setWindows($windows){
		$this->windows = $windows;
	}
    
    public function getWindowsUsed(){
		return $this->windowsUsed;
	}

	public function setWindowsUsed($windowsUsed){
		$this->windowsUsed = $windowsUsed;
	}

	public function getPriceEachWindow(){
		return $this->priceEachWindow;
	}

	public function setPriceEachWindow($priceEachWindow){
		$this->priceEachWindow = $priceEachWindow;
	}
}
