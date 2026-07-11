<?PHP

namespace src\Data;

use src\Business\Logic\PasswordHasher;
use src\Business\Logic\LoginAbuseService;
use src\Business\UserCoreService;
use src\Data\config\DBConfig;

class MemberDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    
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
    
    public function login($email, $password, $remember = false)
    {
        $id = $this->getIdByEmail($email);
        if(!$id)
            return FALSE;
        
        $saltFile = DOC_ROOT . '/app/Resources/memberSalts/' . (int) $id . '.txt';
        $salt = file_exists($saltFile) ? trim((string)file_get_contents($saltFile)) : '';
        
        $statement = $this->dbh->prepare("SELECT m.`id`,m.`naam`,m.`voornaam`,m.`email`,m.`password`, s.`status_nl` FROM `member` AS m LEFT JOIN `status` AS s ON (m.status=s.id) WHERE m.`email` = :email AND m.`active`='1' AND m.`deleted` = '0' AND s.`active`='1' AND (s.`deleted`='0' OR s.`deleted`='-1') LIMIT 0,1");
        $statement->execute(array(':email' => $email));
        $row = $statement->fetch();
        if(isset($row['id']) && $row['id'] > 0 && $this->verifyPasswordHash($password, $row['password'], $salt))
        {
            $_SESSION['cp-logon']['naam'] = $row['naam'];
            $_SESSION['cp-logon']['voornaam'] = $row['voornaam'];
            $_SESSION['cp-logon']['MID'] = $row['id'];
            if(PasswordHasher::needsRehash($row['password']))
            {
                $row['password'] = $this->setPasswordHash($row['id'], $password);
            }
            $_SESSION['cp-logon']['status'] = $row['status_nl'];
            $hash2 = hash('sha256',$salt.$row['email'].$row['password'].$salt);
            if($remember == 'remember-me')
            {
                global $route;
                setcookie('rememberme', $hash2, time()+25478524, '/', $route->settings['domain'], SSL_ENABLED, true);
                setcookie('MID', $row['id'], time()+25478524, '/', $route->settings['domain'], SSL_ENABLED, true);
            }
            $_SESSION['cp-logon']['cookiehash'] = $hash2;
            if(!isset($_COOKIE['email']))
            {
                global $route;
                setcookie('email', $email, time()+25478524, '/', $route->settings['domain'], SSL_ENABLED, true);
            }

            $this->logSuccessfulLogin($row['id'], 0);
            return TRUE;
        }
    }

    private function logSuccessfulLogin($memberID, $cookieLogin = 0)
    {
        $this->con->setData("
            INSERT INTO `login_admin` (`memberID`,`ip`,`date`,`time`,`cookieLogin`) VALUES (:id, :ip, :date, :time, :cookieLogin)
        ", array(
            ':id' => $memberID,
            ':ip' => UserCoreService::getIP(),
            ':date' => date('Y-m-d H:i:s'),
            ':time' => time(),
            ':cookieLogin' => $cookieLogin
        ));
    }

    public function loginFailed($email, $type)
    {
        $this->con->setData("
            INSERT INTO `login_admin_fail` (`email`,`ip`,`date`,`time`,`type`) VALUES (:email, :ip, :date, :time, :type)
        ", array(':email' => $email, ':ip' => UserCoreService::getIP(), ':date' => date('Y-m-d H:i:s'), ':time' => time(), ':type' => $type));
    }

    public function getLoginFailedCountByIP($ipAddr, $type = false)
    {
        $prms = array(':ip' => $ipAddr, ':datePast' => date('Y-m-d H:i:s', strtotime('-24 hours')));
        $whereAdd = "";
        if($type != false && $type >= 1 && $type <= 5)
        {
            $whereAdd = "AND `type`= :type";
            $prms[':type'] = $type;
        }
        $row = $this->con->getDataSR("
            SELECT COUNT(`id`) AS `total` FROM `login_admin_fail` WHERE `ip`= :ip AND `date`> :datePast AND `type` NOT IN (4, 5) $whereAdd LIMIT 1
        ", $prms);
        if(isset($row['total']) && $row['total'] >= 0)
            return (int)$row['total'];

        return 0;
    }

    public function checkTempBannedIP($ipAddr)
    {
        $loginAbuse = new LoginAbuseService($this);
        $qry = "SELECT COUNT(`id`) AS `total` FROM `login_admin_fail` WHERE `ip`= :ip AND `date`> :datePast AND `date`< :dateTo AND `type` NOT IN (4, 5) LIMIT 1";
        $prms = array(':ip' => $ipAddr, ':datePast' => date('Y-m-d H:i:s', strtotime('-72 hours')), ':dateTo' => date('Y-m-d H:i:s', strtotime('-48 hours')));
        $row = $this->con->getDataSR($qry, $prms);
        if(!isset($row['total']) || (isset($row['total']) && $row['total'] < $loginAbuse->maxLogin24h))
        {
            $prms[':datePast'] = date('Y-m-d H:i:s', strtotime('-48 hours'));
            $prms[':dateTo'] = date('Y-m-d H:i:s', strtotime('-24 hours'));
            $row = $this->con->getDataSR($qry, $prms);
        }
        if(!isset($row['total']) || (isset($row['total']) && $row['total'] < $loginAbuse->maxLogin24h))
        {
            $qry = "SELECT COUNT(`id`) AS `total` FROM `login_admin_fail` WHERE `ip`= :ip AND `date`> :datePast AND `type` NOT IN (4, 5) LIMIT 1";
            $prms[':datePast'] = date('Y-m-d H:i:s', strtotime('-24 hours'));
            unset($prms[':dateTo']);
            $row = $this->con->getDataSR($qry, $prms);
        }

        if(isset($row['total']) && $row['total'] >= $loginAbuse->maxLogin24h)
            return TRUE;

        return FALSE;
    }
    
    public function createMember($email, $password, $naam = "", $voornaam = "", $adres = "", $gemeente = "", $postcode = "")
    {
        $hash = PasswordHasher::hash($password);
        
        global $security;
        $salt = $security->createSalt();
        
        $statement = $this->dbh->prepare("
            INSERT INTO `member`
            (`email`, `password`, `status`, `naam`, `voornaam`, `adres`, `gemeente`, `postcode`)
            VALUES
            (:email, :password, '3', :naam, :voornaam, :adres, :gemeente, :postcode)
        ");
        $statement->execute(array(
            ':email' => $email,
            ':password' => $hash,
            ':naam' => $naam,
            ':voornaam' => $voornaam,
            ':adres' => $adres,
            ':gemeente' => $gemeente,
            ':postcode' => $postcode
        ));
        
        $newMID = $this->dbh->lastInsertId();
        
        $ourFileName = DOC_ROOT . '/app/Resources/memberSalts/' . (int) $newMID . '.txt';
        $ourFileHandle = fopen($ourFileName, 'w') or die("Kan bestand niet aanmaken, meld dit aan de administrator samen met de URL waar de fout plaatsvond.");
        $stringData = $salt;
        fwrite($ourFileHandle, $stringData);
        fclose($ourFileHandle);
        
        if(isset($newMID) && $newMID > 0)
        {
            $position = $this->con->getDataSR("SELECT `position` FROM `member` ORDER BY `position` DESC LIMIT 1")['position'];
            $pos = isset($position) ? $position + 1 : 0;
            $this->con->setData("UPDATE `member` SET `position`= :pos WHERE `id`= :mid AND `active`='1' AND `deleted`='0'", array(':pos' => $pos, ':mid' => $newMID));
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    public function emailExists($email)
    {
        $statement = $this->dbh->prepare("SELECT `id` FROM `member` WHERE `email` = :email  AND `active`='1' AND `deleted` = '0' LIMIT 1");
        $statement->execute(array(':email' => $email));
        $row = $statement->fetch();
        if(isset($row['id']) && $row['id'] > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    public function getIdByEmail($email)
    {
        $statement = $this->dbh->prepare("SELECT `id` FROM `member` WHERE `email` = :email AND `active`='1' AND `deleted` = '0' LIMIT 1");
        $statement->execute(array(':email' => $email));
        $row = $statement->fetch();
        if(isset($row['id']) && $row['id'] > 0)
        {
            return $row['id'];
        }
        else
        {
            return FALSE;
        }
    }
    
    public function verifyCookieHash($hash, $id)
    {
        if(!isset($_SESSION['cp-logon']['cookiehash']))
        {
            $statement = $this->dbh->prepare("SELECT m.`id`,m.`naam`,m.`voornaam`,m.`email`,m.`password`, s.`status_nl` FROM `member` AS m LEFT JOIN `status` AS s ON (m.status=s.id) WHERE m.`id` = :id AND m.`active`='1' AND m.`deleted` = '0' AND s.`active`='1' AND (s.`deleted`='0' OR s.`deleted`='-1') LIMIT 1");
            $statement->execute(array(':id' => $id));
            $row = $statement->fetch();
            $saltFile = DOC_ROOT . '/app/Resources/memberSalts/' . (int) $id . '.txt';
            // Empty salt matches the remember-me hash emitted at login for accounts without legacy salt files.
            $salt = file_exists($saltFile) ? trim((string) file_get_contents($saltFile)) : '';
            
            if(hash_equals($hash, hash('sha256',$salt.$row['email'].$row['password'].$salt)))
            {
                $_SESSION['cp-logon']['naam'] = $row['naam'];
                $_SESSION['cp-logon']['voornaam'] = $row['voornaam'];
                $_SESSION['cp-logon']['MID'] = $row['id'];
                $_SESSION['cp-logon']['status'] = $row['status_nl'];
                $_SESSION['cp-logon']['cookiehash'] = $hash;

                $this->logSuccessfulLogin($row['id'], 1);
                return TRUE;
            }
        }
        else
        {
            if(hash_equals($hash, $_SESSION['cp-logon']['cookiehash']))
                return TRUE;
        }
        return FALSE;
    }
    
    public function getStatus($id = "")
    {
        if(isset($_SESSION['cp-logon']['MID']))
        {
            if($id == '') $id = $_SESSION['cp-logon']['MID'];
            $statement = $this->dbh->prepare("SELECT `status` FROM `member` WHERE `id`= :id");
            $statement->execute(array(':id' => $id));
            $row = $statement->fetch();
            if(isset($row['status']) && $row['status'] > 0)
            {
                return $row['status'];
            }
        }
    }
    
    public function saveNewAccountSettings($data)
    {
        if(isset($_SESSION['cp-logon']['MID']))
        {
            $paramsArr = array();
            $editStr = "";
            foreach($data AS $key => $value)
            {
                $editStr .= "`".$key."`= :".$key.", ";
                $paramsArr[":".$key.""] = $value;
            }
            $editStr = rtrim($editStr,', ');
            $paramsArr[':id'] = (int)$_SESSION['cp-logon']['MID'];
            global $route;
            $statement = $this->dbh->prepare("UPDATE `member` SET ".$editStr." WHERE `id`= :id");
            if($statement->execute($paramsArr))
            {
                return $route->successMessage("Account instellingen succesvol opgeslagen!");
            }
            else
            {
                return $route->errorMessage("Fout bij het opslaan van de account instellingen.");
            }
        }
    }
    
    public function verifyPassword($password)
    {
        if(isset($_SESSION['cp-logon']['MID']))
        {
            $id = (int) $_SESSION['cp-logon']['MID'];
            $saltFile = DOC_ROOT . '/app/Resources/memberSalts/' . $id . '.txt';
            $salt = file_exists($saltFile) ? trim((string)file_get_contents($saltFile)) : '';
            
            $statement = $this->dbh->prepare("SELECT `id`, `password` FROM `member` WHERE `id` = :id LIMIT 1");
            $statement->execute(array(':id' => $id));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0 && $this->verifyPasswordHash($password, $row['password'], $salt))
            {
                if(PasswordHasher::needsRehash($row['password']))
                    $this->setPasswordHash($id, $password);
                return TRUE;
            }
            return FALSE;
        }
    }
    
    public function changePassword($password)
    {
        if(isset($_SESSION['cp-logon']['MID']))
        {
            $this->setPasswordHash((int)$_SESSION['cp-logon']['MID'], $password);
        }
    }
    private function verifyPasswordHash(string $password, string $hash, string $salt): bool
    {
        if(PasswordHasher::verify($password, $hash))
            return TRUE;

        return $salt !== '' && PasswordHasher::verifyLegacySha256Salt($password, $hash, $salt);
    }

    private function setPasswordHash(int $id, string $password): string
    {
        $hash = PasswordHasher::hash($password);
        $statement = $this->dbh->prepare("UPDATE `member` SET `password` = :password WHERE `id` = :id ");
        $statement->execute(array(':password' => $hash, ':id' => $id));
        return $hash;
    }
}
