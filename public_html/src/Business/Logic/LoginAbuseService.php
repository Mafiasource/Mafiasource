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

    public function getLoginAbuseState($langs = array(), $permBan = false)
    {
        $type = $permBan ? 5 : 2;
        $blocked = $permBan;
        $message = "";
        $ipAddr = UserCoreService::getIP();
        $lfc = $this->data->getLoginFailedCountByIP($ipAddr);
        $attemptsLeft = max(0, (int)($this->maxLogin24h - 1) - $lfc);

        if($this->data->checkTempBannedIP($ipAddr))
        {
            $type = $permBan ? 5 : 4;
            $blocked = true;
            $message = $langs['TEMPORARILY_IP_BANNED'] . " ";
        }
        else
        {
            global $route;
            if($lfc >= $this->minLogin24h && $lfc < $this->maxLogin24h)
            {
                $type = $permBan ? 5 : 3;
                $replace = $attemptsLeft !== 0 ? $attemptsLeft : strtolower($langs['NONE']);
                $message = $route->replaceMessagePart($replace, $langs['LOGIN_FAILED_WARNING'], '/{attempts}/') . " ";
            }
        }

        return array(
            'message' => $message,
            'type' => $type,
            'blocked' => $blocked,
            'attemptsLeft' => $attemptsLeft,
        );
    }
}
