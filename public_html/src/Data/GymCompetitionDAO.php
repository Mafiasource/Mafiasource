<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Business\GymCompetitionService;
use src\Entities\GymCompetition;
use src\Entities\User;

class GymCompetitionDAO extends DBConfig
{
    protected $con = "";            
    private $dbh = "";
    private $lang = "en";
    private $dateFormat = "%d-%m-%y %H:%i:%s";
    
    public function __construct()
    {
        global $lang;
        global $connection;
        
        $this->con = $connection;                        
        $this->dbh = $connection->con;
        $this->lang = $lang;
        if($this->lang == 'en') $this->dateFormat = "%m-%d-%y %r";
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(*) AS `total` FROM `gym_competition` WHERE `deleted` = '0' AND `active` = '1' AND `participantID`='0' AND `winnerID`='0'");
            if(isset($row['total'])) return $row['total'];
        }
    }
    
    public function userHasOpenCompetition()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `gym_competition` WHERE `userID`= :uid AND `participantID`='0' AND `winnerID`='0' AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $_SESSION['UID']));
            if(isset($row['id']) && $row['id'] > 0)
                return TRUE;
            else
                return FALSE;
            
        }
    }
    
    public function createGymCompetition($competitionID, $stake, $cityID)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                INSERT INTO `gym_competition` (`userID`,`cityID`,`type`,`stake`,`startDate`) VALUES (:uid,:cityID,:type,:stake,NOW())
            ", array(':uid' => $_SESSION['UID'], ':cityID' => $cityID, ':type' => $competitionID, ':stake' => $stake));
            $this->con->setData("
                UPDATE `user` SET `cash`=`cash`- :stake WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':stake' => $stake, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function getOpenCompetitions()
    {
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData("
                SELECT gc.`id`, gc.`stake`, gc.`type`, DATE_FORMAT( gc.`startDate`, '".$this->dateFormat."' ) AS `startDate`,
                    u.`username` AS `starterName`, c.`name` AS `city`
                FROM `gym_competition` AS gc
                LEFT JOIN `user` AS u
                ON (gc.`userID`=u.`id`)
                LEFT JOIN `city` AS c
                ON (gc.`cityID`=c.`id`)
                WHERE gc.`participantID`='0' AND gc.`winnerID`='0' AND gc.`active`='1' AND gc.`deleted`='0'
            ");
            $gymCompetitionService = new GymCompetitionService();
            $list = array();
            foreach($rows AS $row)
            {
                $comp = new GymCompetition();
                $comp->setId($row['id']);
                $comp->setCity($row['city']);
                $comp->setStake($row['stake']);
                $comp->setUsername($row['starterName']);
                $comp->setStartDate($row['startDate']);
                $comp->setType($gymCompetitionService->competitionNames[$row['type']]);
                array_push($list, $comp);
            }
            return $list;
        }
    }
    
    public function getCompetitionById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT gc.`id`, gc.`stake`, gc.`type`, DATE_FORMAT( gc.`startDate`, '".$this->dateFormat."' ) AS `startDate`,
                    u.`username` AS `starterName`, u.`id` AS `starterUID`, c.`name` AS `city`, c.`id` AS `cityID`
                FROM `gym_competition` AS gc
                LEFT JOIN `user` AS u
                ON (gc.`userID`=u.`id`)
                LEFT JOIN `city` AS c
                ON (gc.`cityID`=c.`id`)
                WHERE gc.`participantID`='0' AND gc.`winnerID`='0' AND gc.`active`='1' AND gc.`deleted`='0' AND gc.`id`= :id
            ", array(':id' => $id));
            
            if(isset($row['id']) && $row['id'] > 0)
            {
                $comp = new GymCompetition(); //return obj
                $comp->setId($row['id']);
                $comp->setCityID($row['cityID']);
                $comp->setCity($row['city']);
                $comp->setStake($row['stake']);
                $comp->setUserID($row['starterUID']);
                $comp->setUsername($row['starterName']);
                $comp->setStartDate($row['startDate']);
                $comp->setType($row['type']);
                
                return $comp;
            }
        }
    }
    
    public function getOpponentsGymStatsByUserID($id)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `power`, `cardio` FROM `user` WHERE `id`= :uid", array(':uid' => $id));
            $userObj = new User();
            $userObj->setPower($row['power']);
            $userObj->setCardio($row['cardio']);
            return $userObj;
        }
    }
    
    public function updateChallengedCompetition($competition, $winnerID, $winnerScore, $loserID, $loserScore)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `gym_competition` SET `participantID` = :uid, `winnerID`= :wid, `endDate`= NOW() WHERE `id`= :compId
            ", array(':uid' => $_SESSION['UID'], ':wid' => $winnerID, ':compId' => $competition->getId()));
            
            if($winnerID != -1 && $loserID != -1)
            {
                if($winnerID != $_SESSION['UID']) //Starter won
                {
                    //Stake (*2) for winning starter. (Stake was subtracted in creating competition)
                    $this->con->setData("
                        UPDATE `user`
                        SET `cash`=`cash`+ :profits, `gymProfit`=`gymProfit`+ :profits, `score`=`score`+ :wScore, `gymScorePointsEarned`=`gymScorePointsEarned`+ :wScore,
                            `gymCompetitionWin`=`gymCompetitionWin`+'1'
                        WHERE `id`= :wid
                    ", array(':profits' => ($competition->getStake()*2), ':wScore' => $winnerScore, ':wid' => $winnerID));
                    
                    //Only need to remove money from participant if starter won
                    $this->con->setData("
                        UPDATE `user`
                        SET `cash`=`cash`- :stake, `gymProfit`=`gymProfit`- :stake, `score`=`score`+:lScore, `gymScorePointsEarned`=`gymScorePointsEarned`+ :lScore,
                            `gymCompetitionLoss`=`gymCompetitionLoss`+'1'
                        WHERE `id`= :lid
                    ", array(':stake' => $competition->getStake(), ':lScore' => $loserScore, ':lid' => $loserID));
                }
                else //Participant won
                {
                    $this->con->setData("
                        UPDATE `user`
                        SET `cash`=`cash`+ :profits, `gymProfit`=`gymProfit`+ :profits, `score`=`score`+ :wScore, `gymScorePointsEarned`=`gymScorePointsEarned`+ :wScore,
                            `gymCompetitionWin`=`gymCompetitionWin`+'1'
                        WHERE `id`= :wid
                    ", array(':profits' => $competition->getStake(), ':wScore' => $winnerScore, ':wid' => $winnerID));
                }
            }
            elseif($winnerScore == $loserScore) //Unlucky draw
            {
                 $this->con->setData("
                    UPDATE `user`
                    SET `score`=`score`+ :score, `gymScorePointsEarned`=`gymScorePointsEarned`+ :score
                    WHERE `id`= :uid
                ", array(':score' => $winnerScore, ':uid' => $_SESSION['UID'])); //For the participant NO money remove or add in draw.
                
                $this->con->setData("
                    UPDATE `user`
                    SET `score`=`score`+ :score, `gymScorePointsEarned`=`gymScorePointsEarned`+ :score, `cash`=`cash`+ :stake
                    WHERE `id`= :uid
                ", array(':score' => $loserScore, ':stake' => $competition->getStake(), ':uid' => $competition->getUserID())); //Starter, give back stake in draw
            }
        }
    }
}
