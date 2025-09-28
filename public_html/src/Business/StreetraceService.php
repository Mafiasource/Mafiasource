<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\NotificationService;
use src\Data\StreetraceDAO;
use src\Entities\Streetrace;
use src\Entities\StreetraceParticipant;

class StreetraceService
{
    private $data;
    public $raceTypes = array(
        'highway' => 'Highway',
        'route66' => 'Route66',
        'drift' => 'Drift Race',
        'city' => 'City Race'
    );
    public $playerOptions = array(2, 3, 4);
    private $payoutMultipliers = array(
        2 => array(1 => 2),
        3 => array(1 => 2, 2 => 1),
        4 => array(1 => 3, 2 => 1)
    );

    public function __construct()
    {
        $this->data = new StreetraceDAO();
    }

    public function __destruct()
    {
        $this->data = null;
    }

    public function getOverview()
    {
        return array(
            'openRaces' => $this->data->getOpenRaces(),
            'userRace' => $this->data->getUsersOpenRace(),
            'lastResult' => $this->data->getUserLastResult()
        );
    }

    public function race($post)
    {
        $action = isset($post['action']) ? $post['action'] : 'organize';
        switch($action)
        {
            case 'join':
                return $this->joinRace($post);
            case 'leave':
                return $this->leaveRace($post);
            default:
                return $this->organizeRace($post);
        }
    }

    private function organizeRace($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->streetraceLangs();

        $stake = isset($post['stake']) ? (int)$post['stake'] : 0;
        $type = isset($post['type']) ? $post['type'] : '';
        $vehicleId = isset($post['vehicle']) ? (int)$post['vehicle'] : 0;
        $requiredPlayers = isset($post['requiredPlayers']) ? (int)$post['requiredPlayers'] : 0;

        if($security->checkToken($post['security-token']) == false)
            $error = $langs['INVALID_SECURITY_TOKEN'];
        if($userData->getInPrison())
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        if($userData->getTraveling())
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        if(!array_key_exists($type, $this->raceTypes))
            $error = $l['INVALID_RACE_TYPE'];
        if(!in_array($requiredPlayers, $this->playerOptions))
            $error = $l['INVALID_PLAYER_COUNT'];
        if($stake < 1)
            $error = $l['INVALID_STAKE'];
        if($userData->getCash() < $stake)
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        if(!isset($error) && $this->data->userHasOpenRace())
            $error = $l['ALREADY_PART_OF_RACE'];
        if(!isset($error) && ($vehicle = $this->data->getUserVehicle($vehicleId)) == false)
            $error = $l['INVALID_VEHICLE'];

        if(isset($error))
            return Routing::errorMessage($error);

        try
        {
            $this->data->createRace($type, $stake, $requiredPlayers, $vehicle);
            return Routing::successMessage($l['ORGANIZE_RACE_SUCCESS']);
        }
        catch(\Exception $e)
        {
            return Routing::errorMessage($l['INVALID_RACE']);
        }
    }

    private function joinRace($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        global $route;
        $l = $language->streetraceLangs();

        $raceId = isset($post['race']) ? (int)$post['race'] : 0;
        $vehicleId = isset($post['vehicle']) ? (int)$post['vehicle'] : 0;

        if($security->checkToken($post['security-token']) == false)
            $error = $langs['INVALID_SECURITY_TOKEN'];
        if($userData->getInPrison())
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        if($userData->getTraveling())
            $error = $langs['CANT_DO_THAT_TRAVELLING'];

        $race = $this->data->getRaceById($raceId);
        if(!$race instanceof Streetrace || $race->getStatus() !== 'open')
            $error = $l['INVALID_RACE'];
        elseif($race->getParticipantCount() >= $race->getRequiredPlayers())
            $error = $l['RACE_ALREADY_FULL'];

        $openRaceId = $this->data->userHasOpenRace();
        if(!isset($error) && $openRaceId && $openRaceId != $raceId)
            $error = $l['ALREADY_PART_OF_RACE'];

        if(!isset($error) && $this->data->getUserParticipant($raceId))
            $error = $l['ALREADY_PART_OF_RACE'];

        if(!isset($error) && ($vehicle = $this->data->getUserVehicle($vehicleId)) == false)
            $error = $l['INVALID_VEHICLE'];

        if(!isset($error) && $userData->getCash() < $race->getStake())
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];

        if(isset($error))
            return Routing::errorMessage($error);

        try
        {
            $this->data->addParticipant($raceId, $vehicle, $race->getStake());
            $race = $this->data->getRaceById($raceId);
            if($race instanceof Streetrace && $race->getParticipantCount() >= $race->getRequiredPlayers())
            {
                $results = $this->prepareRaceResults($race);
                $this->data->completeRace($raceId, $results);
                $this->notifyRaceResults($race, $results);
                foreach($results AS $result)
                {
                    if($result['userId'] == $_SESSION['UID'])
                    {
                        return $this->formatResultMessage($result['position'], $result['prize'], $race->getStake(), $route, $l);
                    }
                }
            }
            return Routing::successMessage($l['JOIN_RACE_SUCCESS']);
        }
        catch(\Exception $e)
        {
            return Routing::errorMessage($l['INVALID_RACE']);
        }
    }

    private function leaveRace($post)
    {
        global $security;
        global $language;
        global $langs;
        $l = $language->streetraceLangs();

        $raceId = isset($post['race']) ? (int)$post['race'] : 0;

        if($security->checkToken($post['security-token']) == false)
            $error = $langs['INVALID_SECURITY_TOKEN'];

        $race = $this->data->getRaceById($raceId);
        if(!$race instanceof Streetrace || $race->getStatus() !== 'open')
            $error = $l['INVALID_RACE'];

        $participant = null;
        if(!isset($error))
            $participant = $this->data->getUserParticipant($raceId);
        if(!isset($error) && !$participant)
            $error = $l['NO_PART_OF_RACE'];

        if(isset($error))
            return Routing::errorMessage($error);

        try
        {
            $this->data->removeParticipant($raceId, $race->getStake());
            $this->data->cancelRaceIfEmpty($raceId);
            return Routing::successMessage($l['LEAVE_RACE_SUCCESS']);
        }
        catch(\Exception $e)
        {
            return Routing::errorMessage($l['INVALID_RACE']);
        }
    }

    private function prepareRaceResults(Streetrace $race)
    {
        global $security;
        $participants = $race->getParticipants();
        $results = array();
        $scored = array();
        foreach($participants AS $participant)
        {
            if(!$participant instanceof StreetraceParticipant)
                continue;

            $score = $this->calculateParticipantScore($participant, $race->getType());
            $scored[] = array(
                'participant' => $participant,
                'score' => $score,
                'tiebreaker' => $security->randInt(0, 1000000)
            );
        }

        usort($scored, function($a, $b) {
            if($a['score'] === $b['score'])
                return $b['tiebreaker'] <=> $a['tiebreaker'];
            return $b['score'] <=> $a['score'];
        });

        $multipliers = $this->getPayoutMultipliers($race->getRequiredPlayers());
        $position = 1;
        foreach($scored AS $row)
        {
            /** @var StreetraceParticipant $participant */
            $participant = $row['participant'];
            $prize = isset($multipliers[$position]) ? $race->getStake() * $multipliers[$position] : 0;
            $participant->setScore($row['score']);
            $participant->setPosition($position);
            $participant->setPrize($prize);
            $results[] = array(
                'participantId' => $participant->getId(),
                'userId' => $participant->getUserID(),
                'score' => $row['score'],
                'position' => $position,
                'prize' => $prize,
                'participant' => $participant
            );
            $position++;
        }
        return $results;
    }

    private function calculateParticipantScore(StreetraceParticipant $participant, $type)
    {
        $hp = $participant->getHorsepower();
        $ts = $participant->getTopspeed();
        $ac = $participant->getAcceleration();
        $ct = $participant->getControl();
        $br = $participant->getBreaking();

        switch($type)
        {
            case 'highway':
                $score = $ts * 2 + $hp + $ac;
                break;
            case 'route66':
                $score = $hp * 2 + $ts + $ac;
                break;
            case 'drift':
                $score = $ct * 2 + $br + $ac;
                break;
            case 'city':
            default:
                $score = $ac * 2 + $ct + $br;
                break;
        }
        return (int)$score;
    }

    private function getPayoutMultipliers($requiredPlayers)
    {
        if(isset($this->payoutMultipliers[$requiredPlayers]))
            return $this->payoutMultipliers[$requiredPlayers];
        return array(1 => $requiredPlayers);
    }

    private function notifyRaceResults(Streetrace $race, array $results)
    {
        if(empty($results))
            return;

        global $language;
        $l = $language->streetraceLangs();
        $raceLabel = isset($this->raceTypes[$race->getType()]) ? $this->raceTypes[$race->getType()] : ucfirst($race->getType());
        $raceName = $raceLabel . ' ' . $l['TITLE'];

        $notification = new NotificationService();

        foreach($results AS $result)
        {
            if(!isset($result['participant']) || !$result['participant'] instanceof StreetraceParticipant)
                continue;

            $messageKey = $result['prize'] > 0 ? 'STREETRACE_RESULT_PRIZE' : 'STREETRACE_RESULT_LOSS';

            $params = 'race=' . $raceName;
            $params .= '&placeOrdinal=' . $this->formatEnglishPlacement($result['position']);
            $params .= '&placeNl=' . $this->formatDutchPlacement($result['position']);
            $params .= '&prize=' . number_format($result['prize'], 0, '', ',');

            $notification->sendNotification($result['userId'], $messageKey, $params);
        }
    }

    private function formatEnglishPlacement($position)
    {
        $suffix = 'th';
        if(($position % 100) < 11 || ($position % 100) > 13)
        {
            switch($position % 10)
            {
                case 1:
                    $suffix = 'st';
                    break;
                case 2:
                    $suffix = 'nd';
                    break;
                case 3:
                    $suffix = 'rd';
                    break;
            }
        }
        return $position . $suffix . ' place';
    }

    private function formatDutchPlacement($position)
    {
        return $position . 'e plaats';
    }

    private function formatResultMessage($position, $prize, $stake, $route, $l)
    {
        if($position === 1)
        {
            $msg = $route->replaceMessagePart(number_format($prize, 0, '', ','), $l['RACE_SUCCESS_WON_FIRST'], '/{price}/');
            return Routing::successMessage($msg);
        }
        if($prize === $stake)
            return Routing::successMessage($l['RACE_SUCCESS_EVEN_SECOND']);
        if($prize > 0)
        {
            $msg = $route->replaceMessageParts(array(
                array('part' => $position, 'message' => $l['RACE_SUCCESS_WON_NTH'], 'pattern' => '/{nth}/'),
                array('part' => number_format($prize, 0, '', ','), 'message' => false, 'pattern' => '/{price}/')
            ));
            return Routing::successMessage($msg);
        }
        $msg = $route->replaceMessagePart($position, $l['RACE_SUCCESS_LOST_NTH'], '/{nth}/');
        return Routing::errorMessage($msg);
    }
}
