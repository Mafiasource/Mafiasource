<?PHP

declare(strict_types=1);

namespace src\Business\Logic;

use src\Business\UserCoreService;

class LoginAbuseService
{
    public $maxLogin24h = 20; // Unsuccessful attempts within 24 hours before a 72 hour IP ban.
    public $minLogin24h = 5; // Unsuccessful attempts within 24 hours before warning.

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getAttemptsMessage($langs = array())
    {
        $ipAddr = UserCoreService::getIP();
        if($this->data->checkTempBannedIP($ipAddr))
            return $langs['TEMPORARILY_IP_BANNED'] . " ";

        global $route;
        $lfc = $this->data->getLoginFailedCountByIP($ipAddr);
        if($lfc >= $this->minLogin24h && $lfc < $this->maxLogin24h)
        {
            $incPossibleSuccess = (int)($this->maxLogin24h - 1) - $lfc;
            $replace = $incPossibleSuccess !== 0 ? $incPossibleSuccess : strtolower($langs['NONE']);
            return $route->replaceMessagePart($replace, $langs['LOGIN_FAILED_WARNING'], '/{attempts}/') . " ";
        }

        return "";
    }

    public function getFailureType($attemptsMessage, $tempBannedMessage, $permBan = false)
    {
        $type = $permBan ? 5 : 2;
        if($attemptsMessage !== "")
            $type = 3;

        if($attemptsMessage == $tempBannedMessage . " ")
            $type = $permBan ? 5 : 4;

        return $type;
    }
}
