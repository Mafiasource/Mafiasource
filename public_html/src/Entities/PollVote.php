<?PHP

/* Entity for table poll_vote */

namespace src\Entities;

class PollVote
{
    private $id;
    private $userID;
    private $questionID;
    private $answerID;
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

	public function getQuestionID(){
		return $this->questionID;
	}

	public function setQuestionID($questionID){
		$this->questionID = $questionID;
	}

	public function getAnswerID(){
		return $this->answerID;
	}

	public function setAnswerID($answerID){
		$this->answerID = $answerID;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}
}
