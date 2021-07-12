<?PHP

/* Entity for table forum_category */

namespace src\Entities;

class ForumCategory
{
    private $id;
    private $category;
    private $description;
    private $picture;
    private $viewStatusID;
    private $interactStatusID;
    private $familyForum;
    private $donatorID;
    private $url;
    private $topics;
    private $reactions;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getCategory(){
		return $this->category;
	}

	public function setCategory($category){
		$this->category = $category;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getPicture(){
		return $this->picture;
	}

	public function setPicture($picture){
		$this->picture = $picture;
	}

	public function getViewStatusID(){
		return $this->viewStatusID;
	}

	public function setViewStatusID($viewStatusID){
		$this->viewStatusID = $viewStatusID;
	}

	public function getInteractStatusID(){
		return $this->interactStatusID;
	}

	public function setInteractStatusID($interactStatusID){
		$this->interactStatusID = $interactStatusID;
	}
    
    public function getFamilyForum(){
		return $this->familyForum;
	}

	public function setFamilyForum($familyForum){
		$this->familyForum = $familyForum;
	}
    
    public function getDonatorID(){
		return $this->donatorID;
	}

	public function setDonatorID($donatorID){
		$this->donatorID = $donatorID;
	}
    
    public function getUrl(){
		return $this->url;
	}

	public function setUrl($url){
		$this->url = $url;
	}
    
    public function getTopics(){
		return $this->topics;
	}

	public function setTopics($topics){
		$this->topics = $topics;
	}

	public function getReactions(){
		return $this->reactions;
	}

	public function setReactions($reactions){
		$this->reactions = $reactions;
	}
}
