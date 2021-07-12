<?PHP

namespace src\Data;

use src\Business\CaptchaService;
use src\Data\config\DBConfig;
use src\Entities\UserCaptcha;

class CaptchaDAO extends DBConfig
{
    protected $con = ""; // Init
    private $dbh = ""; // Init, old query con var, slightly longer writing
    private $userCaptchaQry = "SELECT `id`, `security`, `count`, `success`, `fail`, `unsolved` FROM `user_captcha` WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1";

    public function __construct()
    {
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
    }

    public function __destruct()
    {
        $this->dbh = null;
    }

    public function getRecordsCount()
    {
        $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `user_captcha` WHERE `active`='1' AND `deleted`='0' LIMIT 1");
        $statement->execute();
        $row = $statement->fetch();
        if(isset($row['total'])) return $row['total'];
    }
    
    public function getUserCaptcha()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR($this->userCaptchaQry, array(':uid' => $_SESSION['UID']));
            $id = isset($row['id']) ? $row['id'] : $_SESSION['UID'];
            $security = isset($row['security']) ? $row['security'] : 0;
            $count = isset($row['count']) ? $row['count'] : 0;
            $success = isset($row['success']) ? $row['success'] : 0;
            $fail = isset($row['fail']) ? $row['fail'] : 0;
            $unsolved = isset($row['unsolved']) ? $row['unsolved'] : 0;
            
            $captcha = new UserCaptcha();
            $captcha->setId($id);
            $captcha->setSecurity($security);
            $captcha->setCount($count);
            $captcha->setSuccess($success);
            $captcha->setFail($fail);
            $captcha->setUnsolved($unsolved);
            $captchaTodoCalc = CaptchaService::calculateSecurity($captcha->getCount(), $captcha->getSuccess(), $captcha->getFail(), $captcha->getUnsolved());                        
            if(isset($_SESSION['captcha-test']['todo']))
                $captchaTodo = $_SESSION['captcha-test']['todo'];
            else
                $captchaTodo = $captchaTodoCalc;
            
            $captcha->setSecurityTodo($captchaTodo);
            $_SESSION['captcha-test']['todo'] = $captchaTodo; // Save value until we reset (Avoid falling into other values though calculateSecurity() )
            
            return $captcha;
        }
    }
    
    public function setUserCaptcha() // Add to the security count in database (Non session based for game users)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR($this->userCaptchaQry, array(':uid' => $_SESSION['UID']));
            if(isset($row['id']) && $row['id'] == $_SESSION['UID'])
                $qry = "UPDATE `user_captcha` SET `security`=`security`+'1' WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1";
            else
                $qry = "INSERT INTO `user_captcha` (`id`, `security`) VALUES (:uid, 1)";
            
            $this->con->setData($qry, array(':uid' => $_SESSION['UID']));
        }
    }
    
    public function resetUserCaptchaSecurity() // Only to be triggered on successful captcha validation
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `user_captcha` SET `security`='0' WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':uid' => $_SESSION['UID']));
            $_SESSION['captcha-test']['todo'] = null; // Reset saved value after successful captcha solving (success)
        }
    }
    
    public function addOneUnsolvedUserCaptcha()
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `user_captcha` SET `count`=`count`+'1', `unsolved`=`unsolved`+'1' WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':uid' => $_SESSION['UID']));
        }
    }
    
    public function addOneSolvedUserCaptchaByType($type = "success")
    {
        if(isset($_SESSION['UID']))
        {
            $allowedTypes = array('success', 'fail');
            if(in_array($type, $allowedTypes))
            {
                $row = $this->con->getDataSR($this->userCaptchaQry, array(':uid' => $_SESSION['UID']));
                if(isset($row['id']) && $row['id'] == $_SESSION['UID'])
                    $this->con->setData("UPDATE `user_captcha` SET `".$type."`=`".$type."`+'1', `unsolved`=`unsolved`-'1' WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':uid' => $_SESSION['UID']));
            }
        }
    }
}
