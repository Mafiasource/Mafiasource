<?PHP

/* Entity for table poll_question */

namespace src\Entities;

class PollQuestion
{
    private $id;
    private $question;
    private $description;
    private $startDate;
    private $endDate;
    private $votes;
    private $active;
    
    private $answers;
    private $hasVoted;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getQuestion(){
		return $this->question;
	}

	public function setQuestion($question){
		$this->question = $question;
	}
    
    public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getStartDate(){
		return $this->startDate;
	}

	public function setStartDate($startDate){
		$this->startDate = $startDate;
	}

	public function getEndDate(){
		return $this->endDate;
	}

	public function setEndDate($endDate){
		$this->endDate = $endDate;
	}

	public function getVotes(){
		return $this->votes;
	}

	public function setVotes($votes){
		$this->votes = $votes;
	}

	public function getActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
	}
    
    public function getAnswers(){
		return $this->answers;
	}

	public function setAnswers($answers){
		$this->answers = $answers;
	}
    
    public function getHasVoted(){
		return $this->hasVoted;
	}

	public function setHasVoted($hasVoted){
		$this->hasVoted = $hasVoted;
	}
}
