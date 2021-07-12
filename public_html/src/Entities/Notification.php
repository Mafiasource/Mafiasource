<?PHP

/* Entity for table notification */

namespace src\Entities;

class Notification
{
    private $id;
    private $userID;
    private $title;
    private $notification;
    private $read;
    private $inInbox;
    private $date;
    
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

	public function getTitle(){
		return $this->title;
	}

	public function setTitle($title){
		$this->title = $title;
	}

	public function getNotification(){
		return $this->notification;
	}

	public function setNotification($notification){
		$this->notification = $notification;
	}

	public function getRead(){
		return $this->read;
	}

	public function setRead($read){
		$this->read = $read;
	}

	public function getInInbox(){
		return $this->inInbox;
	}

	public function setInInbox($inInbox){
		$this->inInbox = $inInbox;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}
}
