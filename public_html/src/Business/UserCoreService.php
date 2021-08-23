<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\GarageService;
use src\Data\UserCoreDAO;

class UserCoreService
{
    private $data;

    public $dateFormat = "j M, H:i:s"; // PHP format
    
    public function __construct()
    {
        $this->data = new UserCoreDAO();
        global $route;
        $lang = $route->getLang();
        if($lang == 'en') $this->dateFormat = "M j, g:i:s A"; // PHP format
    }

    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getUsersCount()
    {
        return $this->data->getUsersCount();
    }

    public function notIngame()
    {
        if(preg_match('{^/forum/game-forum.*$}', $_SERVER['REQUEST_URI']))
            return TRUE;
        
        if(strpos($_SERVER['REQUEST_URI'], 'game'))
            return FALSE;
        
        return TRUE;
    }
    
    public function checkLoggedSession($update = true)
    {
        if(isset($_SESSION['UID']))
            if($this->data->checkUser($_SESSION['UID'], $update))
                return TRUE;
            else
                return FALSE;
        else
            return FALSE;
    }
    
    public static function getCappedRankpoints($rank, $kills, $honorPoints, $whores, $protected)
    {
        $rankpoints = $rank;
        if($rank >= 510)
        {
            if($rank >= 860)
            {
                if($rank >= 1310)
                {
                    if($kills < 15 || $honorPoints < 1500 || $whores < 10000 || $protected == true)
                        $rankpoints = 1309;
                }
                if($kills < 5 || $honorPoints < 500 || $whores < 5000)
                    $rankpoints = 859;
            }
            if($kills < 2 || $honorPoints < 200 || $whores < 2000)
                $rankpoints = 509;
        }
        return $rankpoints;
    }
    
    public static function getRankInfoByRankpoints($rank)
    {
        if($rank < 5)
    		$rankID = 0;
    	elseif($rank < 12)
    		$rankID = 1;
    	elseif($rank < 22)
    		$rankID = 2;
    	elseif($rank < 47)
    		$rankID = 3;
    	elseif($rank < 77)
    		$rankID = 4;
    	elseif($rank < 110)
    		$rankID = 5;
    	elseif($rank < 160)
    		$rankID = 6;
    	elseif($rank < 260)
    		$rankID = 7;
    	elseif($rank < 510)
    		$rankID = 8;
    	elseif($rank < 860)
    		$rankID = 9;
    	elseif($rank < 1310)
    		$rankID = 10;
    	else
    		$rankID = 11;
    	
    	$ranken = array(
    		array('rank' => "Scum", 'rankID' => 0, 'procenten' => self::procentRank(5, 5, $rank)),
    		array('rank' => "Pee Wee", 'rankID' => 1, 'procenten' => self::procentRank(12, 7, $rank)),
    		array('rank' => "Thug", 'rankID' => 2, 'procenten' => self::procentRank(22, 10, $rank)),
    		array('rank' => "Gangster", 'rankID' => 3, 'procenten' => self::procentRank(47, 25, $rank)),
    		array('rank' => "Hitman", 'rankID' => 4, 'procenten' => self::procentRank(77, 30, $rank)),
    		array('rank' => "Assassin", 'rankID' => 5, 'procenten' => self::procentRank(110, 33, $rank)),
    		array('rank' => "Boss", 'rankID' => 6, 'procenten' => self::procentRank(160, 50, $rank)),
    		array('rank' => "Godfather", 'rankID' => 7, 'procenten' => self::procentRank(260, 100, $rank)),
    		array('rank' => "Legendary Godfather", 'rankID' => 8, 'procenten' => self::procentRank(510, 250, $rank)),
    		array('rank' => "Don", 'rankID' => 9, 'procenten' => self::procentRank(860, 350, $rank)),
    		array('rank' => "Respectable Don", 'rankID' => 10, 'procenten' => self::procentRank(1310, 450, $rank)),
    		array('rank' => "Legendary Don", 'rankID' => 11, 'procenten' => 100),
    	);
    	return $ranken[$rankID];
    }

    public static function getMoneyRank($geld)
    {
    	if($geld < 100000)
    		return "Straydog";
    	elseif($geld < 500000)
    		return "Respectable Man";
    	elseif($geld < 1000000)
    		return "Lower Class";
    	elseif($geld < 2500000)
    		return "Middle Class";
    	elseif($geld < 5000000)
    		return "Wealthy";
    	elseif($geld < 10000000)
    		return "Upper Class";
    	elseif($geld < 25000000)
    		return "Rich";
    	elseif($geld < 50000000)
    		return "Very Rich";
    	elseif($geld < 100000000)
    		return "Dangerously Rich";
    	else
    		return "Notoriously Rich";
    }
    
    public static function procentRank($total, $stap, $rankNow)
    {
    	$todo = $total - $rankNow;
    	return round(100 -(($todo / $stap) * 100), 0);
    }
    
    public function checkStolenVehicleInQueue()
    {
        global $route;
        global $userData;
        if (strpos($route->getRoute(), 'steal-vehicles') === false)
        {
            if(isset($_SESSION['steal-vehicles'])) $svData = $_SESSION['steal-vehicles'];
            if(isset($svData))
            {
                global $security;
                $garage = new GarageService();
                $fakePost = array('securityToken' => $security->getToken());
                $garage->addVehicleToGarage($fakePost, $userData->getStateID());
            }
        }
    }
    
    public function getUserData()
    {
        return $this->data->getUserData();
    }
    
    public function getPrisonersCount()
    {
        return $this->data->getPrisonersCount();
    }
    
    public function getOnlinePlayers()
    {
        return $this->data->getOnlinePlayers();
    }
    
    public function getStatusAndDonatorColors()
    {
        return $this->data->getStatusAndDonatorColors();
    }
}
