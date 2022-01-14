<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Entities\PollQuestion;
use src\Entities\PollAnswer;
use src\Entities\PollVote;

class PollDAO extends DBConfig
{
    protected $con = "";            
    private $dbh = "";
    private $lang = "en";
    private $dateFormat = "%d-%m-%Y %H:%i:%s"; // SQL Format
    private $questionSelects = ""; // Init
    private $votesSubQry = "(SELECT COUNT(`id`) FROM `poll_vote` WHERE `questionID`=pa.`questionID` AND `answerID`=pa.`id` AND `active`='1' AND `deleted`='0') AS `votes`";
    private $totalVotesSubQry = "(SELECT COUNT(`id`) FROM `poll_vote` WHERE `questionID`=pq.`id` AND `active`='1' AND `deleted`='0') AS `votes`";
    
    public function __construct()
    {
        global $lang;
        global $connection;
        
        $this->con = $connection;                        
        $this->dbh = $connection->con;
        $this->lang = $lang;
        if($this->lang == 'en') $this->dateFormat = "%m-%d-%Y %r"; // SQL Format
        $this->questionSelects = "pq.`id`, pq.`question_".$this->lang."` AS `question`, pq.`description_".$this->lang."` AS `description`";
    }
    
    public function __destruct()
    {
        $this->dbh = null;
        $this->con = null;
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(*) AS `total` FROM `poll_question` WHERE `deleted` = '0' AND `active` = '1'");
            return $row['total'];
        }
    }
    
    private function userVotedOnQuestionById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `poll_vote` WHERE `userID`= :uid AND `questionID`= :qid AND `date` IS NOT NULL AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $_SESSION['UID'], ':qid' => $id));
            
            if(isset($row['id']) && $row['id'] > 0)
                return true;
        }
        return false;
    }
    
    public function getAllQuestionAnswers($pollQuestion)
    {
        if(is_object($pollQuestion))
        {
            $answers = $this->con->getData("
                SELECT pa.`id`, pa.`answer_".$this->lang."` AS `answer`, " . $this->votesSubQry . "
                FROM `poll_answer` AS pa
                WHERE pa.`questionID`= :qid AND pa.`active`='1' AND pa.`deleted`='0'
            ", array(':qid' => $pollQuestion->getId()));
        }
        return isset($answers) ? $answers : false;
    }
    
    public function getActiveQuestions()
    {
        if(isset($_SESSION['UID']))
        {
            // Select active poll questions (active='1' != current votable poll! / it is determinded by dateEnd field insteadi)
            $rows = $this->con->getData("
                SELECT " . $this->questionSelects . ", DATE_FORMAT(pq.`startDate`, '".$this->dateFormat."') AS `startDate`, " . $this->totalVotesSubQry . "
                FROM `poll_question` AS pq
                WHERE pq.`active`='1' AND pq.`deleted`='0' AND (pq.`endDate` IS NULL OR pq.`endDate`='0000-00-00 00:00:00')
            ");
            
            $list = array();
            $i = 1;
            $unvoted = false;
            foreach($rows AS $row)
            {
                $pollQuestion = new PollQuestion();
                $pollQuestion->setId($row['id']);
                $pollQuestion->setQuestion($row['question']);
                $pollQuestion->setDescription($row['description']);
                $pollQuestion->setStartDate($row['startDate']);
                $pollQuestion->setVotes($row['votes']);
                
                $voted = $this->con->getDataSR("
                    SELECT `id` FROM `poll_vote` WHERE `userID`= :uid AND `questionID`= :qID AND `answerID`>'0' AND `active`='1' AND `deleted`='0'
                ", array(':uid' => $_SESSION['UID'], ':qID' => $pollQuestion->getId()));
                if(isset($voted['id']) && $voted['id'] > 0)
                {
                    $pollQuestion->setActive(false);
                    if($i == (count($rows)))
                        $pollQuestion->setActive(true);
                }
                else
                {
                    $unvoted = $pollQuestion->getId();
                }
                
                // Select all possible answers to question.
                $answers = $this->getAllQuestionAnswers($pollQuestion);
                
                $answerList = array();
                foreach($answers AS $answer)
                {
                    $pollAnswer = new PollAnswer();
                    $pollAnswer->setId($answer['id']);
                    $pollAnswer->setAnswer($answer['answer']);
                    $pollAnswer->setVotes($answer['votes']);
                    
                    array_push($answerList, $pollAnswer);
                }
                
                $pollQuestion->setAnswers($answerList);
                
                // User voted in this poll?
                $voted = $this->userVotedOnQuestionById($pollQuestion->getId());
                
                $pollQuestion->setHasVoted(false);
                if($voted === true)
                    $pollQuestion->setHasVoted(true);
                
                array_push($list, $pollQuestion);
                $i++;
            }
            if(is_numeric($unvoted))
            {
                $i = 0;
                foreach($list AS $p)
                {
                    $list[$i]->setActive(false);
                    if($p->getId() === $unvoted)
                        $list[$i]->setActive(true);
                    
                    $i++;
                }
            }
            
            return $list;
        }
    }
    
    public function getFinishedQuestions()
    {
        if(isset($_SESSION['UID']))
        {
            // Select finished poll questions (history)
            $rows = $this->con->getData("
                SELECT " . $this->questionSelects . ", `startDate`, DATE_FORMAT(pq.`endDate`, '".$this->dateFormat."') AS `endDateFormat`, " . $this->totalVotesSubQry . "
                FROM `poll_question` AS pq
                WHERE pq.`active`='1' AND pq.`deleted`='0' AND pq.`endDate` IS NOT NULL AND pq.`endDate`!='0000-00-00 00:00:00'
                ORDER BY `endDate` DESC
            ");
            
            $list = array();
            foreach($rows AS $row)
            {
                $pollQuestion = new PollQuestion();
                $pollQuestion->setId($row['id']);
                $pollQuestion->setQuestion($row['question']);
                $pollQuestion->setDescription($row['description']);
                $pollQuestion->setStartDate($row['startDate']);
                $pollQuestion->setEndDate($row['endDateFormat']);
                $pollQuestion->setVotes($row['votes']);
                
                // Select all possible answers to question.
                $answers = $this->getAllQuestionAnswers($pollQuestion);
                
                $answerList = array();
                foreach($answers AS $answer)
                {
                    $pollAnswer = new PollAnswer();
                    $pollAnswer->setId($answer['id']);
                    $pollAnswer->setAnswer($answer['answer']);
                    $pollAnswer->setVotes($answer['votes']);
                    
                    array_push($answerList, $pollAnswer);
                }
                
                $pollQuestion->setAnswers($answerList);
                
                array_push($list, $pollQuestion);
            }
            
            return $list;
        }
    }
    
    public function getQuestionById($id)
    {
        if(isset($_SESSION['UID']))
        {
            // Select poll question
            $row = $this->con->getDataSR("
                SELECT " . $this->questionSelects . ", pq.`startDate`, " . $this->totalVotesSubQry . "
                FROM `poll_question` AS pq
                WHERE pq.`id`= :pqid AND pq.`active`='1' AND pq.`deleted`='0' AND (pq.`endDate` IS NULL OR pq.`endDate`='0000-00-00 00:00:00')
            ", array(':pqid' => $id));
            
            if(isset($row['id']) && $row['id'] > 0)
            {
                $pollQuestion = new PollQuestion();
                $pollQuestion->setId($row['id']);
                $pollQuestion->setQuestion($row['question']);
                $pollQuestion->setDescription($row['description']);
                $pollQuestion->setStartDate($row['startDate']);
                $pollQuestion->setVotes($row['votes']);
                $pollQuestion->setActive(true);
                
                // User voted in this poll?
                $voted = $this->userVotedOnQuestionById($pollQuestion->getId());
                
                $pollQuestion->setHasVoted(false);
                if($voted === true)
                    $pollQuestion->setHasVoted(true);
                
                return $pollQuestion;
            }
        }
    }
    
    public function getAnswerById($id)
    {
        if(isset($_SESSION['UID']))
        {
            // Select answer
            $row = $this->con->getDataSR("
                SELECT pa.`id`, pa.`questionID`, pa.`answer_".$this->lang."` AS `answer`, " . $this->votesSubQry . "
                FROM `poll_answer` AS pa
                WHERE pa.`id`= :paid AND pa.`active`='1' AND pa.`deleted`='0'
            ", array(':paid' => $id));
            
            if(isset($row['id']) && $row['id'])
            {
                $pollAnswer = new PollAnswer();
                $pollAnswer->setId($row['id']);
                $pollAnswer->setQuestionID($row['questionID']);
                $pollAnswer->setAnswer($row['answer']);
                $pollAnswer->setVotes($row['votes']);
                
                return $pollAnswer;
            }
        }
    }
    
    public function vote($questionID, $answerID)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                INSERT INTO `poll_vote` (`userID`, `questionID`, `answerID`, `date`) VALUES (:uid, :qid, :aid, NOW())
            ", array(':uid' => $_SESSION['UID'], ':qid' => $questionID, ':aid' => $answerID));
            
            $this->con->setData("UPDATE `poll_vote` SET `position` :liid WHERE `id`= :liid", array(':liid' => $this->dbh->lastInsertId()));
        }
    }
    
    public function userHasUnvotedPoll()
    {
        if(isset($_SESSION['UID']))
        {
            $unvotedPoll = false;
            $polls = $this->getActiveQuestions();
            if(!isset($_SESSION['poll']['unvoted']) || (isset($_SESSION['poll']['unvoted']) && $_SESSION['poll']['unvoted'] === true))
            {
                foreach($polls AS $p)
                {
                    if($p->getHasVoted() === false)
                        $unvotedPoll = true;
                }
                $_SESSION['poll']['unvoted'] = $unvotedPoll;
            }
            elseif(isset($_SESSION['poll']['unvoted']))
            {
                $unvotedPoll = $_SESSION['poll']['unvoted'];
            }
            return $unvotedPoll;
        }
    }
}
