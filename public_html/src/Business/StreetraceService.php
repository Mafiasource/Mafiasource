<?PHP

namespace src\Business;

use app\config\Routing;
use src\Data\StreetraceDAO;

class StreetraceService
{
    private $data;
    public $raceTypes = array(
        'highway' => 'Highway',
        'route66' => 'Route66',
        'drift' => 'Drift Race',
        'city' => 'City Race'
    );

    public function __construct()
    {
        $this->data = new StreetraceDAO();
    }

    public function __destruct()
    {
        $this->data = null;
    }

    public function race($post)
    {
        global $security;
        global $userData;
        global $language;
        global $route;
        global $langs;
        $l = $language->streetraceLangs();

        $stake = (int)$post['stake'];
        $type = isset($post['type']) ? $post['type'] : '';
        $vehicleId = (int)$post['vehicle'];

        if($security->checkToken($post['security-token']) == false)
            $error = $langs['INVALID_SECURITY_TOKEN'];
        if($userData->getInPrison())
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        if($userData->getTraveling())
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        if(!array_key_exists($type, $this->raceTypes))
            $error = $l['INVALID_RACE_TYPE'];
        if($stake < 1)
            $error = $l['INVALID_STAKE'];
        if($userData->getCash() < $stake)
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        if(($vehicle = $this->data->getUserVehicle($vehicleId)) == false)
            $error = $l['INVALID_VEHICLE'];

        if(isset($error))
            return Routing::errorMessage($error);

        $playerScore = $this->calculateScore($vehicle, $type);
        $scores = array(
            $userData->getUsername() => $playerScore,
            'Opponent 1' => $this->randomScore($type),
            'Opponent 2' => $this->randomScore($type),
            'Opponent 3' => $this->randomScore($type)
        );
        arsort($scores);
        $position = array_search($userData->getUsername(), array_keys($scores)) + 1;

        $profit = -$stake;
        if($position === 1)
            $profit += $stake * 3;
        elseif($position === 2)
            $profit += $stake;

        $this->data->updateUserCash($profit);

        if($position === 1)
        {
            $msg = $route->replaceMessagePart(number_format($stake * 3, 0, '', ','), $l['RACE_SUCCESS_WON_FIRST'], '/{price}/');
            return Routing::successMessage($msg);
        }
        elseif($position === 2)
        {
            return Routing::successMessage($l['RACE_SUCCESS_EVEN_SECOND']);
        }
        else
        {
            $msg = $route->replaceMessagePart($position, $l['RACE_SUCCESS_LOST_NTH'], '/{nth}/');
            return Routing::errorMessage($msg);
        }
    }

    private function calculateScore($vehicle, $type)
    {
        global $security;
        $hp = $vehicle['horsepower'];
        $ts = $vehicle['topspeed'];
        $ac = $vehicle['acceleration'];
        $ct = $vehicle['control'];
        $br = $vehicle['breaking'];

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
        $score += $security->randInt(0, 100);
        return $score;
    }

    private function randomScore($type)
    {
        global $security;
        $vehicle = array(
            'horsepower' => $security->randInt(200, 800),
            'topspeed' => $security->randInt(200, 350),
            'acceleration' => $security->randInt(20, 80),
            'control' => $security->randInt(10, 100),
            'breaking' => $security->randInt(10, 100)
        );
        return $this->calculateScore($vehicle, $type);
    }
}
