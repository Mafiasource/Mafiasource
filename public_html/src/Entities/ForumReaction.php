<?PHP

/* Entity for table forum_reaction */

namespace src\Entities;

class ForumReaction
{
    private $id;
    private $topicID;
    private $reactorUID;
    private $reactor;
    private $reactorClassName;
    private $reactorAvatar;
    private $reactorPostsCnt;
    private $reactorDonatorID;
    private $content;
    private $quoteContent;
    private $date;
    private $lastEditTime;
    
   	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getTopicID(){
		return $this->topicID;
	}

	public function setTopicID($topicID){
		$this->topicID = $topicID;
	}

	public function getReactorUID(){
		return $this->reactorUID;
	}

	public function setReactorUID($reactorUID){
		$this->reactorUID = $reactorUID;
	}

	public function getReactor(){
		return $this->reactor;
	}

	public function setReactor($reactor){
		$this->reactor = $reactor;
	}
    
    public function getReactorClassName(){
		return $this->reactorClassName;
	}

	public function setReactorClassName($reactorClassName){
		$this->reactorClassName = $reactorClassName;
	}
    
    public function getReactorAvatar(){
		return $this->reactorAvatar;
	}

	public function setReactorAvatar($reactorAvatar){
		$this->reactorAvatar = $reactorAvatar;
	}
    
    public function getReactorPostsCnt(){
		return $this->reactorPostsCnt;
	}

	public function setReactorPostsCnt($reactorPostsCnt){
		$this->reactorPostsCnt = $reactorPostsCnt;
	}
    
    public function getReactorDonatorID(){
		return $this->reactorDonatorID;
	}

	public function setReactorDonatorID($reactorDonatorID){
		$this->reactorDonatorID = $reactorDonatorID;
	}

	public function getContent(){
		return $this->content;
	}

	public function setContent($content){
		$this->content = $content;
	}
    
    public function getQuoteContent(){
		return $this->quoteContent;
	}

	public function setQuoteContent($quoteContent){
		$this->quoteContent = $quoteContent;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}

	public function getLastEditTime(){
		return $this->lastEditTime;
	}

	public function setLastEditTime($lastEditTime){
		$this->lastEditTime = $lastEditTime;
	}
}
