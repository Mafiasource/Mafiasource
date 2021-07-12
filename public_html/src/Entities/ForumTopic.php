<?PHP

/* Entity for table forum_topic */

namespace src\Entities;

class ForumTopic
{
    private $id;
    private $categoryID;
    private $category;
    private $starterUID;
    private $starter;
    private $starterClassName;
    private $starterAvatar;
    private $starterPostsCnt; 
    private $starterDonatorID;
    private $familyID;
    private $lang;
    private $title;
    private $content;
    private $date;
    private $status;
    private $statusID;
    private $statusPicture;
    private $lastMsgTime;
    private $cleanUrl;
    private $reactions;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getCategoryID(){
		return $this->categoryID;
	}

	public function setCategoryID($categoryID){
		$this->categoryID = $categoryID;
	}
    
    public function getCategory(){
		return $this->category;
	}

	public function setCategory($category){
		$this->category = $category;
	}

	public function getStarterUID(){
		return $this->starterUID;
	}

	public function setStarterUID($starterUID){
		$this->starterUID = $starterUID;
	}
    
    public function getStarter(){
		return $this->starter;
	}

	public function setStarter($starter){
		$this->starter = $starter;
	}
    
    public function getStarterClassName(){
		return $this->starterClassName;
	}

	public function setStarterClassName($starterClassName){
		$this->starterClassName = $starterClassName;
	}
    
    public function getStarterAvatar(){
		return $this->starterAvatar;
	}

	public function setStarterAvatar($starterAvatar){
		$this->starterAvatar = $starterAvatar;
	}
    
    public function getStarterPostsCnt(){
		return $this->starterPostsCnt;
	}

	public function setStarterPostsCnt($starterPostsCnt){
		$this->starterPostsCnt = $starterPostsCnt;
	}
    
    public function getStarterDonatorID(){
		return $this->starterDonatorID;
	}

	public function setStarterDonatorID($starterDonatorID){
		$this->starterDonatorID = $starterDonatorID;
	}

	public function getFamilyID(){
		return $this->familyID;
	}

	public function setFamilyID($familyID){
		$this->familyID = $familyID;
	}

	public function getLang(){
		return $this->lang;
	}

	public function setLang($lang){
		$this->lang = $lang;
	}

	public function getTitle(){
		return $this->title;
	}

	public function setTitle($title){
		$this->title = $title;
	}

	public function getContent(){
		return $this->content;
	}

	public function setContent($content){
		$this->content = $content;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}

	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status){
		$this->status = $status;
	}
    
    public function getStatusID(){
		return $this->statusID;
	}

	public function setStatusID($statusID){
		$this->statusID = $statusID;
	}
    
    public function getStatusPicture(){
		return $this->statusPicture;
	}

	public function setStatusPicture($statusPicture){
		$this->statusPicture = $statusPicture;
	}

	public function getLastMsgTime(){
		return $this->lastMsgTime;
	}

	public function setLastMsgTime($lastMsgTime){
		$this->lastMsgTime = $lastMsgTime;
	}

	public function getCleanUrl(){
		return $this->cleanUrl;
	}

	public function setCleanUrl($cleanUrl){
		$this->cleanUrl = $cleanUrl;
	}
    
    public function getReactions(){
		return $this->reactions;
	}

	public function setReactions($reactions){
		$this->reactions = $reactions;
	}
}
