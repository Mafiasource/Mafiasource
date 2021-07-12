<?PHP

/* Entity for table poll_answer */

namespace src\Entities;

class PollAnswer
{
    private $id;
    private $questionID;
    private $question;
    private $answer;
    private $votes;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getQuestionID(){
		return $this->questionID;
	}

	public function setQuestionID($questionID){
		$this->questionID = $questionID;
	}

	public function getQuestion(){
		return $this->question;
	}

	public function setQuestion($question){
		$this->question = $question;
	}

	public function getAnswer(){
		return $this->answer;
	}

	public function setAnswer($answer){
		$this->answer = $answer;
	}
    
    public function getVotes(){
		return $this->votes;
	}

	public function setVotes($votes){
		$this->votes = $votes;
	}
}
