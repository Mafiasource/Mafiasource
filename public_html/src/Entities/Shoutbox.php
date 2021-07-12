<?PHP

/* Entity for table shoutbox */

namespace src\Entities;

class Shoutbox
{
    private $id;
    private $userID;
    private $username;
    private $usernameClassName;
    private $donatorID;
    private $avatar;
    private $FamilyID;
    private $message;
    private $date;
    private $deleted;
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
    
    public function getUsername(){
		return $this->username;
	}

	public function setUsername($username){
		$this->username = $username;
	}
    
    public function getUsernameClassName()
    {
        return $this->usernameClassName;
    }
    
    public function setUsernameClassName($usernameClassName)
    {
        $this->usernameClassName = $usernameClassName;
    }
    
    public function getDonatorID(){
		return $this->donatorID;
	}

	public function setDonatorID($donatorID){
		$this->donatorID = $donatorID;
	}
    
    public function getAvatar(){
		return $this->avatar;
	}

	public function setAvatar($avatar){
		$this->avatar = $avatar;
	}
    
	public function getFamilyID(){
		return $this->FamilyID;
	}

	public function setFamilyID($FamilyID){
		$this->FamilyID = $FamilyID;
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

	public function getDeleted(){
		return $this->deleted;
	}

	public function setDeleted($deleted){
		$this->deleted = $deleted;
	}
}
