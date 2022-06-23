<?PHP
 
namespace src\Business;

use app\config\Routing;
use src\Business\NotificationService;
use src\Business\DailyChallengeService;
use src\Business\PublicMissionService;
use src\Data\GymCompetitionDAO;
 
class GymCompetitionService
{
    private $data;
    private $rates;
    public $competitionNames;
    
    public function __construct()
    {
        $this->data = new GymCompetitionDAO();
        global $language;
        $l = $language->gymLangs();
        $this->competitionNames = array(1 => $l['ARM_WRESTLING'], $l['SPRINT'], $l['TUG_OF_WAR'], $l['TRIATLON'], $l['WRESTLE']);
        $this->rates = array(
            1 => 
            array('power' => 1, 'cardio' => 0),       //ARM_WRESTLING
            array('power' => 0, 'cardio' => 1),       //SPRINT
            array('power' => 0.9, 'cardio' => 0.1),   //TUG_OF_WAR
            array('power' => 0.25, 'cardio' => 0.75), //TRIATLON
            array('power' => 0.85, 'cardio' => 0.15)  //WRESTLE
        );
    }
    
    public function __destruct()
    {
        $this->data = null;   
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function createGymCompetition($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->gymLangs();
        
        $id = (int)$post['competitionType'];
        $stake = (int)$post['stake'];
        $check = $this->data->userHasOpenCompetition();
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if(($id < 1 || $id > 5) || !in_array($id, array_keys($this->competitionNames)))
        {
            $error = $l['COMPETITION_DOESNT_EXIST'];
        }
        if($stake > $userData->getCash())
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if($stake < 50 || $stake > 5000000)
        {
            $error = $l['STAKE_BETWEEN_50_5M'];
        }
        if($check)
        {
            $error = $l['COMPETITION_ALREADY_STARTED_COMPETITION'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->createGymCOmpetition($id, $stake, $userData->getCityID());
            $replaces = array(
                array('part' => strtolower($this->competitionNames[$id]), 'message' => $l['COMPETITION_CREATE_COMPETITION_SUCCESS'], 'pattern' => '/{competition}/'),
                array('part' => number_format($stake, 0, '', ','), 'message' => FALSE, 'pattern' => '/{stake}/'),
                array('part' => $userData->getCity(), 'message' => FALSE, 'pattern' => '/{location}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function getOpenCompetitions()
    {
        return $this->data->getOpenCompetitions();
    }
    
    public function challengeCompetition($post)
    {
        global $route;
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->gymLangs();
        
        $id = (int)$post['competitionID'];
        $competition = $this->data->getCompetitionById($id);
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if((
            is_object($competition) && $competition->getId() != $id) || (is_object($competition) && !in_array($competition->getType(), array_keys($this->competitionNames))) ||
            !is_object($competition)
        )
        {
            $error = $l['COMPETITION_DOESNT_EXIST'];
        }
        else
        {
            if(is_object($competition) && $competition->getCityID() != $userData->getCityID())
            {
                $replacedMessage = $route->replaceMessagePart($competition->getCity(), $l['COMPETITION_IN_OTHER_CITY'], '/{location}/');
                $error = $replacedMessage;
            }
        }
        if(is_object($competition) && $competition->getStake() > $userData->getCash())
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if(is_object($competition) && $competition->getUserID() == $userData->getId())
        {
            $error = $l['COMPETITION_CANNOT_CHALLENGE_SELF'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $userService;
            
            $loserScore = $winnerScore = 10;
            $challengerGymPage = $userService->getGymPageInfo();
            $challengerPower = $challengerGymPage->getPower();
            $challengerCardio = $challengerGymPage->getCardio();
            
            $starterGymStats = $this->data->getOpponentsGymStatsByUserID($competition->getUserID());
            $starterPower = $starterGymStats->getPower();
            $starterCardio = $starterGymStats->getCardio();
            
            $rates = $this->rates[$competition->getType()];
            
            $c_pPoints = $challengerPower * $rates['power'];
            $c_cPoints = $challengerCardio * $rates['cardio'];
            
            $s_pPoints = $starterPower * $rates['power'];
            $s_cPoints = $starterCardio * $rates['cardio'];
            
            $c_pointsTotal = $c_pPoints + $c_cPoints;
            
            $s_pointsTotal = $s_pPoints + $s_cPoints;
            
            if($c_pointsTotal > $s_pointsTotal) // Challenger wins
            {
                $pointsDifference = $c_pointsTotal - $s_pointsTotal;
                $winnerID = $userData->getId();
                $loserID = $competition->getUserID();
            }
            elseif($c_pointsTotal == $s_pointsTotal) // Draw
            {
                $pointsDifference = $c_pointsTotal;
                $winnerID = $loserID = -1;
            }
            else // Starter wins
            {
                $pointsDifference = $s_pointsTotal - $c_pointsTotal;
                $winnerID = $competition->getUserID();
                $loserID = $userData->getId();
            }
            // Calculate score with a cap
            if(($pointsDifference*2) > 100 && ($pointsDifference*2) < 1000)
                $winnerScore = $pointsDifference*2;
            elseif(($pointsDifference*2) > 1000)
                $winnerScore = ($pointsDifference/2)+1000;
            else
                $winnerScore = $pointsDifference+25;
            
            if($winnerID && $loserID == -1) $loserScore = $winnerScore; // Draw
            
            $dailyChallengeService = new DailyChallengeService();
            $publicMissionService = new PublicMissionService();
            if($winnerID == $userData->getId())
            {
                $dailyChallengeService->addToDailiesIfActive(12, $winnerScore);
                $dailyChallengeService->addToDailiesIfActive(12, $loserScore, $loserID);
                
                $publicMissionService->addToPublicMisionIfActive(9, $winnerScore);
                $publicMissionService->addToPublicMisionIfActive(9, $loserScore, $loserID);
            }
            elseif($loserID == $userData->getId())
            {
                $dailyChallengeService->addToDailiesIfActive(12, $loserScore);
                $dailyChallengeService->addToDailiesIfActive(12, $winnerScore, $winnerID);
                
                $publicMissionService->addToPublicMisionIfActive(9, $loserScore);
                $publicMissionService->addToPublicMisionIfActive(9, $winnerScore, $winnerID);
            }
            else // Draw!
            {
                $dailyChallengeService->addToDailiesIfActive(12, $winnerScore);
                $dailyChallengeService->addToDailiesIfActive(12, $winnerScore, $competition->getUserID());
                
                $publicMissionService->addToPublicMisionIfActive(9, $winnerScore);
                $publicMissionService->addToPublicMisionIfActive(9, $winnerScore, $competition->getUserID());
            }
            
            $this->data->updateChallengedCompetition($competition, $winnerID, round($winnerScore), $loserID, round($loserScore));
            
            if($userData->getId() == $winnerID)
            {
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername()."&stake=".number_format($competition->getStake(), 0, '', ',')."&scorePoints=".number_format($loserScore, 0, '', ',');
                $notification->sendNotification($competition->getUserID(), 'GYM_COMPETITION_CHALLENGE_LOSE', $params);
                
                $replaces = array(
                    array('part' => number_format($competition->getStake(), 0, '', ','), 'message' => $l['COMPETITION_CHALLENGE_WIN'], 'pattern' => '/{profits}/'),
                    array('part' => number_format($winnerScore, 0, '', ','), 'message' => FALSE, 'pattern' => '/{scorePoints}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
                return Routing::successMessage($replacedMessage);
            }
            elseif($competition->getUserID() == $winnerID)
            {
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername()."&profits=".number_format($competition->getStake(), 0, '', ',')."&scorePoints=".number_format($winnerScore, 0, '', ',');
                $notification->sendNotification($competition->getUserID(), 'GYM_COMPETITION_CHALLENGE_WIN', $params);
                
                $replaces = array(
                    array('part' => number_format($competition->getStake(), 0, '', ','), 'message' => $l['COMPETITION_CHALLENGE_LOSE'], 'pattern' => '/{stake}/'),
                    array('part' => number_format($loserScore, 0, '', ','), 'message' => FALSE, 'pattern' => '/{scorePoints}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
                return Routing::errorMessage($replacedMessage);
            }
            elseif($winnerScore == $loserScore) // Draw
            {
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername()."&stake=".number_format($competition->getStake(), 0, '', ',')."&scorePoints=".number_format($winnerScore, 0, '', ',');
                $notification->sendNotification($competition->getUserID(), 'GYM_COMPETITION_CHALLENGE_DRAW', $params);
                
                $replacedMessage = $route->replaceMessagePart(number_format($winnerScore, 0, '', ','), $l['COMPETITION_CHALLENGE_DRAW'], '/{scorePoints}/');
                return Routing::errorMessage($replacedMessage);
            }
        }
    }
}
