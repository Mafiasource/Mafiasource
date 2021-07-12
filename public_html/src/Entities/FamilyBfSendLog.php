<?PHP

/* Entity for table family_bf_send_log */

namespace src\Entities;

class FamilyBfSendLog
{
    private $id;
    private $familyID;
    private $senderID;
    private $sender;
    private $receiverID;
    private $receiver;
    private $amount;
    private $date;
    
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

	public function getSenderID(){
		return $this->senderID;
	}

	public function setSenderID($senderID){
		$this->senderID = $senderID;
	}

	public function getSender(){
		return $this->sender;
	}

	public function setSender($sender){
		$this->sender = $sender;
	}

	public function getReceiverID(){
		return $this->receiverID;
	}

	public function setReceiverID($receiverID){
		$this->receiverID = $receiverID;
	}

	public function getReceiver(){
		return $this->receiver;
	}

	public function setReceiver($receiver){
		$this->receiver = $receiver;
	}

	public function getAmount(){
		return $this->amount;
	}

	public function setAmount($amount){
		$this->amount = $amount;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}
}
