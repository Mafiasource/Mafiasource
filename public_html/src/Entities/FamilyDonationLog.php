<?PHP

/* Entity for table family_donation_log */

namespace src\Entities;

class FamilyDonationLog
{
    private $id;
    private $familyID;
    private $userID;
    private $username;
    private $amount;
    private $amountAll;
    private $lastDonation;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getFamilyID(){
		return $this->familyID;
	}

	public function setFamilyID($familyID){
		$this->familyID = $familyID;
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

	public function getAmount(){
		return $this->amount;
	}

	public function setAmount($amount){
		$this->amount = $amount;
	}

	public function getAmountAll(){
		return $this->amountAll;
	}

	public function setAmountAll($amountAll){
		$this->amountAll = $amountAll;
	}

	public function getLastDonation(){
		return $this->lastDonation;
	}

	public function setLastDonation($lastDonation){
		$this->lastDonation = $lastDonation;
	}
}
