<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\GarageService;
use src\Data\UserCoreDAO;

class UserCoreService
{
    private $data;

    public $ipValid = false; // Init
    public $dateFormat = "j M, H:i:s"; // PHP format
    
    public function __construct()
    {
        global $lang;
        
        $this->data = new UserCoreDAO();
        $validationFlags = APP_ISOLATED === true
            ? 0
            : FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
        $validatedIp = filter_var(self::getIP(), FILTER_VALIDATE_IP, $validationFlags);
        $serverAddress = $_SERVER['SERVER_ADDR'] ?? '';
        $serverIsLocal = $serverAddress === '::1' || $serverAddress === '127.0.0.1';
        $this->ipValid = $validatedIp !== false || $serverIsLocal === true;
        $this->dateFormat = $lang === 'en' ? "M j, g:i:s A" : $this->dateFormat; // PHP format
    }

    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getUsersCount()
    {
        return $this->data->getUsersCount();
    }
    
    static function getIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
    }

    public static function getRateLimitIP()
    {
        $remoteAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        if (filter_var($remoteAddress, FILTER_VALIDATE_IP) === false) {
            return 'invalid';
        }

        if (self::isAddressInRanges($remoteAddress, TRUSTED_CLOUDFLARE_PROXY_CIDRS) === true) {
            return self::getValidatedHeaderAddress('HTTP_CF_CONNECTING_IP', $remoteAddress);
        }
        if (self::isAddressInRanges($remoteAddress, TRUSTED_PROXY_CIDRS) === true) {
            return self::getForwardedAddress($remoteAddress);
        }

        return $remoteAddress;
    }

    private static function getValidatedHeaderAddress($header, $fallback)
    {
        $address = $_SERVER[$header] ?? '';
        return filter_var($address, FILTER_VALIDATE_IP) === false ? $fallback : $address;
    }

    private static function getForwardedAddress($fallback)
    {
        $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
        if (is_string($forwardedFor) === false || $forwardedFor === '') {
            return $fallback;
        }

        $addresses = array_reverse(array_map('trim', explode(',', $forwardedFor)));
        foreach ($addresses as $address) {
            if (filter_var($address, FILTER_VALIDATE_IP) !== false
                && self::isAddressInRanges($address, TRUSTED_PROXY_CIDRS) === false) {
                return $address;
            }
        }

        return $fallback;
    }

    private static function isAddressInRanges($address, array $ranges)
    {
        foreach ($ranges as $range) {
            if (self::isAddressInRange($address, $range) === true) {
                return true;
            }
        }

        return false;
    }

    private static function isAddressInRange($address, $range)
    {
        if (is_string($range) === false || $range === '') {
            return false;
        }

        $parts = explode('/', $range, 2);
        $network = $parts[0];
        $addressBinary = inet_pton($address);
        $networkBinary = inet_pton($network);
        if ($addressBinary === false || $networkBinary === false || strlen($addressBinary) !== strlen($networkBinary)) {
            return false;
        }

        $prefix = isset($parts[1]) === true ? filter_var($parts[1], FILTER_VALIDATE_INT) : strlen($networkBinary) * 8;
        return self::binaryAddressMatchesPrefix($addressBinary, $networkBinary, $prefix);
    }

    private static function binaryAddressMatchesPrefix($address, $network, $prefix)
    {
        $maximumPrefix = strlen($address) * 8;
        if (is_int($prefix) === false || $prefix < 0 || $prefix > $maximumPrefix) {
            return false;
        }

        $wholeBytes = intdiv($prefix, 8);
        if (substr($address, 0, $wholeBytes) !== substr($network, 0, $wholeBytes)) {
            return false;
        }
        if ($prefix % 8 === 0) {
            return true;
        }

        $mask = 0xFF << (8 - ($prefix % 8));
        return (ord($address[$wholeBytes]) & $mask) === (ord($network[$wholeBytes]) & $mask);
    }

    public function notIngame()
    {
        if(preg_match('{^/forum/game-forum.*$}', $_SERVER['REQUEST_URI']))
            return TRUE;
        
        if(preg_match('{^/game.*$}', $_SERVER['REQUEST_URI']))
            return FALSE;
        
        return TRUE;
    }
    
    public function checkLoggedSession($update = true)
    {
        $ipAddr = self::getIP();
        if($this->data->checkPermBannedIP($ipAddr) === true || $this->ipValid === false)
            return FALSE;
        
        if(!isset($_SESSION['UID']) && isset($_COOKIE['remember']) && isset($_COOKIE['UID']))
        {
            if($this->data->cookieLoginAbuseRequiresPermanentBan($ipAddr))
            {
                $this->data->addPermanentBannedIP($ipAddr);
                return FALSE;
            }
            $this->data->verifyCookieHash($_COOKIE['remember'], $_COOKIE['UID']); // Sets SESSION UID when valid (to be re-checked underneath)
        }
        
        if(isset($_SESSION['UID']) && $this->data->checkUser($_SESSION['UID'], $update))
            return TRUE;
        
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
    
    public function checkPermBannedIP($ipAddr)
    {
        return $this->data->checkPermBannedIP($ipAddr);
    }
}
