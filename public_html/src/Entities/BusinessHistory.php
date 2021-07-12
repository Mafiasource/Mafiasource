<?PHP

/* Entity for table business_history */

namespace src\Entities;

class BusinessHistory
{
    private $id;
    private $businessID;
    private $closeDay;
    private $highestDay;
    private $lowestDay;
    private $date;
    
    private $averageDay;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getBusinessID(){
		return $this->businessID;
	}

	public function setBusinessID($businessID){
		$this->businessID = $businessID;
	}

	public function getCloseDay(){
		return $this->closeDay;
	}

	public function setCloseDay($closeDay){
		$this->closeDay = $closeDay;
	}

	public function getHighestDay(){
		return $this->highestDay;
	}

	public function setHighestDay($highestDay){
		$this->highestDay = $highestDay;
	}

	public function getLowestDay(){
		return $this->lowestDay;
	}

	public function setLowestDay($lowestDay){
		$this->lowestDay = $lowestDay;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}
    
    public function getAverageDay(){
		return $this->averageDay;
	}

	public function setAverageDay($averageDay){
		$this->averageDay = $averageDay;
	}
}
