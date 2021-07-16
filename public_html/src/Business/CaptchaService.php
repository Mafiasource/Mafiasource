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
    
    public static function calculateSecurity($count, $success, $fail, $unsolved)
    {
        if($success == 0 && $fail == 0 && (($count == 0 && $unsolved == 0) || ($count >= 1 && $unsolved == 1))) // Init (first captcha)
            return 25;
        
        if($count > 0)
        {
            $unsolvedPercent = round($unsolved  / $count * 100);
            $failPercent = round($fail / $count * 100);
            $successPercent = round($success / $count * 100);
            
            if(($unsolvedPercent >= 95 && $unsolvedPercent <= 100) || ($failPercent >= 95 && $failPercent <= 100))
                return 5;
            elseif($successPercent >= 95 && $successPercent <= 100)
                return 100;
            elseif($failPercent >= 6 && $failPercent <= 94)
                return 100 - $failPercent;
            elseif($unsolvedPercent >= 6 && $unsolvedPercent <= 94)
                return 100 - $unsolvedPercent;
            elseif($successPercent >= 6 && $successPercent <= 94)
                return $successPercent;
            else
                return 3;
        }
    }
    
    public function validateCaptcha($post)
    {
        global $security;
        global $language;
        global $langs;
        $code = (int)$post['captcha_code'];
        
        if(!isset($_SESSION['code_captcha']) || $_SESSION['code_captcha'] != $code)
        {
    		$error = $langs['WRONG_CAPTCHA'];
    	}
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }

        if(isset($error))
        {
            $this->data->addOneSolvedUserCaptchaByType('fail');
            unset($_SESSION['code_captcha']);
            return $error;
        }
        else
        {
            $this->data->addOneSolvedUserCaptchaByType();
            $this->data->resetUserCaptchaSecurity();
            unset($_SESSION['code_captcha']);
            return TRUE;
        }
    }
}
