<?PHP

/* Entity for table message */

namespace src\Entities;

class Message
{
    private $id;
    private $senderID;
    private $senderUsername;
    private $senderUsernameClassName;
    private $senderDonatorID;
    private $senderAvatar;
    private $receiverID;
    private $receiverUsername;
    private $receiverUsernameClassName;
    private $receiverDonatorID;
    private $receiverAvatar;
    private $message;
    private $date;
    private $read;
    private $receiver;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getSenderID(){
		return $this->senderID;
	}

	public function setSenderID($senderID){
		$this->senderID = $senderID;
	}

	public function getSenderUsername(){
		return $this->senderUsername;
	}

	public function setSenderUsername($senderUsername){
		$this->senderUsername = $senderUsername;
	}

	public function getSenderUsernameClassName(){
		return $this->senderUsernameClassName;
	}

	public function setSenderUsernameClassName($senderUsernameClassName){
		$this->senderUsernameClassName = $senderUsernameClassName;
	}

	public function getSenderDonatorID(){
		return $this->senderDonatorID;
	}

	public function setSenderDonatorID($senderDonatorID){
		$this->senderDonatorID = $senderDonatorID;
	}

	public function getSenderAvatar(){
		return $this->senderAvatar;
	}

	public function setSenderAvatar($senderAvatar){
		$this->senderAvatar = $senderAvatar;
	}

	public function getReceiverID(){
		return $this->receiverID;
	}

	public function setReceiverID($receiverID){
		$this->receiverID = $receiverID;
	}

	public function getReceiverUsername(){
		return $this->receiverUsername;
	}

	public function setReceiverUsername($receiverUsername){
		$this->receiverUsername = $receiverUsername;
	}

	public function getReceiverUsernameClassName(){
		return $this->receiverUsernameClassName;
	}

	public function setReceiverUsernameClassName($receiverUsernameClassName){
		$this->receiverUsernameClassName = $receiverUsernameClassName;
	}

	public function getReceiverDonatorID(){
		return $this->receiverDonatorID;
	}

	public function setReceiverDonatorID($receiverDonatorID){
		$this->receiverDonatorID = $receiverDonatorID;
	}

	public function getReceiverAvatar(){
		return $this->receiverAvatar;
	}

	public function setReceiverAvatar($receiverAvatar){
		$this->receiverAvatar = $receiverAvatar;
	}

	public function getMessage(){
		return $this->message;
	}

	public function setMessage($message){
		$this->message = $message;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}

	public function getRead(){
		return $this->read;
	}

	public function setRead($read){
		$this->read = $read;
	}

	public function getReceiver(){
		return $this->receiver;
	}

	public function setReceiver($receiver){
		$this->receiver = $receiver;
	}
}
