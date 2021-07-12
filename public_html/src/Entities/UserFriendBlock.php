<?PHP

/* Entity for table user_friend_block */

namespace src\Entities;

Class UserFriendBlock
{
    private $id;
    private $inviterID;
    private $userID;
    private $username;
    private $usernameClassName;
    private $donatorID;
    private $avatar;
    private $type;
    private $active;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getInviterID(){
		return $this->inviterID;
	}

	public function setInviterID($inviterID){
		$this->inviterID = $inviterID;
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

	public function getUsernameClassName(){
		return $this->usernameClassName;
	}

	public function setUsernameClassName($usernameClassName){
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

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
	}
}
