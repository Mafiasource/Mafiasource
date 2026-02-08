<?PHP

namespace src\Business;

use app\config\Routing;
use src\Data\CaptchaDAO;
use src\Data\CrimeDAO;

class CaptchaService
{
    private $data;

    public function __construct()
    {
        $this->data = new CaptchaDAO();
    }

    public function __destruct()
    {
        $this->data = null;
    }

    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function getUserCaptcha()
    {
        return $this->data->getUserCaptcha();
    }
    
    public function setUserCaptcha()
    {
        return $this->data->setUserCaptcha();
    }
    
    public function addOneUnsolvedUserCaptcha()
    {
        return $this->data->addOneUnsolvedUserCaptcha();
    }
    
    public function addOneSolvedUserCaptchaByType($type)
    {
        if(in_array($type, array('success', 'fail')))
            return $this->data->addOneSolvedUserCaptchaByType($type); 
    }

    public static function calculateSecurity(int $count, int $success, int $fail, int $unsolved): int
    {
        $MAX_INTERVAL = 100;  // best-case: challenge every 100 successful actions
        $MIN_INTERVAL = 5;    // worst-case: challenge every 5 successful actions
        $INIT_INTERVAL = 25;  // first-time / not enough history yet
        $FAIL_WEIGHT = 1.0;
        $UNSOLVED_WEIGHT = 1.5;

        if ($count <= 0) {
            return $INIT_INTERVAL;
        }

        $success = max(0, $success);
        $fail = max(0, $fail);
        $unsolved = max(0, $unsolved);

        if ($success === 0 && $fail === 0 && ($unsolved === 0 || $unsolved === 1)) {
            return $INIT_INTERVAL;
        }

        $countF = (float)$count;

        $successPct  = ($success / $countF) * 100.0;
        $failPct     = ($fail / $countF) * 100.0;
        $unsolvedPct = ($unsolved / $countF) * 100.0;

        $badScore = ($failPct * $FAIL_WEIGHT) + ($unsolvedPct * $UNSOLVED_WEIGHT);

        if ($unsolvedPct >= 95.0 || $failPct >= 95.0) {
            return $MIN_INTERVAL;
        }

        if (($failPct * $FAIL_WEIGHT + $unsolvedPct * $UNSOLVED_WEIGHT) >= 95.0) {
            return $MIN_INTERVAL;
        }

        $badScoreClamped = max(0.0, min(100.0, $badScore));

        $interval = (int)round($MAX_INTERVAL - $badScoreClamped);

        if ($successPct >= 95.0 && $failPct <= 5.0 && $unsolvedPct <= 5.0) {
            $interval = $MAX_INTERVAL;
        }

        if ($interval < $MIN_INTERVAL) $interval = $MIN_INTERVAL;
        if ($interval > $MAX_INTERVAL) $interval = $MAX_INTERVAL;

        return $interval;
    }

    public function validateCaptcha($post)
    {
        global $security;
        global $langs;

        if( $security->checkToken($post['security-token']) ==  FALSE ||
            $security->validateCFTurnstile($post['cf-turnstile-response']) == FALSE
        ) {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }

        if(isset($error))
        {
            $this->data->addOneSolvedUserCaptchaByType('fail');
            return $error;
        }
        else
        {
            $this->data->addOneSolvedUserCaptchaByType();
            $this->data->resetUserCaptchaSecurity();
            $security->generateNewToken();
            $security->generateNewSession();
            return TRUE;
        }
    }
}
