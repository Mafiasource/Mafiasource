<?PHP

/* Entity for table business_stock */

namespace src\Entities;

class BusinessStock
{
    private $id;
    private $userID;
    private $businessID;
    private $priceEa;
    private $amount;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getUserID(){
		return $this->userID;
	}

	public function setUserID($userID){
		$this->userID = $userID;
	}

	public function getBusinessID(){
		return $this->businessID;
	}

	public function setBusinessID($businessID){
		$this->businessID = $businessID;
	}

	public function getPriceEa(){
		return $this->priceEa;
	}

	public function setPriceEa($priceEa){
		$this->priceEa = $priceEa;
	}

	public function getAmount(){
		return $this->amount;
	}

	public function setAmount($amount){
		$this->amount = $amount;
	}
}
