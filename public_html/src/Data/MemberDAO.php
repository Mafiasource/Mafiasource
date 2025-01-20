<?PHP

namespace src\Data;

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
        
        $file = fopen(DOC_ROOT . '/app/Resources/memberSalts/' . (int) $id . '.txt','r');
        $salt = fgets($file);
        fclose($file);
        
        $hash = hash('sha256', $salt . hash('sha256', $password));
        
        $statement = $this->dbh->prepare("SELECT m.`id`,m.`naam`,m.`voornaam`,m.`email`,m.`password`, s.`status_nl` FROM `member` AS m LEFT JOIN `status` AS s ON (m.status=s.id) WHERE m.`email` = :email AND m.`password` = :password AND m.`active`='1' AND m.`deleted` = '0' AND s.`active`='1' AND (s.`deleted`='0' OR s.`deleted`='-1') LIMIT 0,1");
        $statement->execute(array(':email' => $email, ':password' => $hash));
        $row = $statement->fetch();
        if(isset($row['id']) && $row['id'] > 0)
        {
            $_SESSION['cp-logon']['naam'] = $row['naam'];
            $_SESSION['cp-logon']['voornaam'] = $row['voornaam'];
            $_SESSION['cp-logon']['MID'] = $row['id'];
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
            return TRUE;
        }
    }
    
    public function createMember($email, $password, $naam = "", $voornaam = "", $adres = "", $gemeente = "", $postcode = "")
    {
        $hash = hash('sha256', $password);
        
        global $security;
        $salt = $security->createSalt();
        
        $hash = hash('sha256', $salt . $hash);
        
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
            $file = fopen(DOC_ROOT . '/app/Resources/memberSalts/' . (int) $id . '.txt','r');
            $salt = fgets($file);
            fclose($file);
            
            if(hash_equals($hash, hash('sha256',$salt.$row['email'].$row['password'].$salt)))
            {
                $_SESSION['cp-logon']['naam'] = $row['naam'];
                $_SESSION['cp-logon']['voornaam'] = $row['voornaam'];
                $_SESSION['cp-logon']['MID'] = $row['id'];
                $_SESSION['cp-logon']['status'] = $row['status_nl'];
                $_SESSION['cp-logon']['cookiehash'] = $hash;
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
            $file = fopen(DOC_ROOT . '/app/Resources/memberSalts/' . (int) $_SESSION['cp-logon']['MID'] . '.txt', 'r');
            $salt = fgets($file);
            fclose($file);
            
            $hash = hash('sha256', $salt . hash('sha256', $password));
            
            $statement = $this->dbh->prepare("SELECT `id` FROM `member` WHERE `password` = :password LIMIT 0,1");
            $statement->execute(array(':password' => $hash));
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
    }
    
    public function changePassword($password)
    {
        if(isset($_SESSION['cp-logon']['MID']))
        {
            $hash = hash('sha256', $password);
            
            global $security;
            $salt = $security->createSalt();
            
            $hash = hash('sha256', $salt . $hash);
            
            $statement = $this->dbh->prepare("UPDATE `member` SET `password` = :password WHERE `id` = :id ");
            $statement->execute(array(':password' => $hash, ':id' => $_SESSION['cp-logon']['MID']));
            
            $ourFileName = DOC_ROOT . '/app/Resources/memberSalts/' . (int) $_SESSION['cp-logon']['MID'] . '.txt';
            $ourFileHandle = fopen($ourFileName, 'w') or die("Kan bestand niet opnenen, meld dit aan de administrator.");
            $stringData = $salt;
            fwrite($ourFileHandle, $stringData);
            fclose($ourFileHandle);
        }
    }
}
