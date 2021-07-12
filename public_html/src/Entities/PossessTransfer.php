<?PHP

/* Entity for table possess_transfer */

namespace src\Entities;

class PossessTransfer
{
    private $possessID;
    private $senderID;
    private $sender;
    private $receiverID;
    private $receiver;
    
	public function getPossessID(){
		return $this->possessID;
	}

	public function setPossessID($possessID){
		$this->possessID = $possessID;
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
}
