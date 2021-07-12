<?PHP
 
namespace src\Business;

use app\config\Routing;
use src\Data\PollDAO;
 
class PollService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new PollDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function vote($post)
    {
        global $security;
        global $language;
        global $langs;
        $l = $language->pollLangs();
        
        $questionID = (int)$post['question'];
        $poll = $this->data->getQuestionById($questionID);
        if(isset($post['question_answer']))
        {
            $answerID = (int)$post['question_answer'];
            $answer = $this->data->getAnswerById($answerID);
        }
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(!is_object($poll))
        {
            $error = $l['INVALID_POLL'];
        }
        if(isset($answer) && (!is_object($answer) || (is_object($answer) && $answer->getQuestionID() !== $poll->getId())))
        {
            $error = $l['INVALID_ANSWER'];
        }
        if(!isset($answer))
        {
            $error = $l['SELECT_ANSWER'];
        }
        if(is_object($poll) && $poll->getHasVoted())
        {
            $error = $l['ALREADY_VOTED_THIS_QUESTION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->vote($poll->getId(), $answer->getId());
            return Routing::successMessage($l['VOTE_SUCCESS']);
        }
    }
    
    public function getActiveQuestions()
    {
        return $this->data->getActiveQuestions();
    }
    
    public function getFinishedQuestions()
    {
        return $this->data->getFinishedQuestions();
    }
    
    public function userHasUnvotedPoll()
    {
        return $this->data->userHasUnvotedPoll();
    }
}
