<?PHP

declare(strict_types=1);

namespace install\config;

use install\config\DBConfig;

class InstallDAO extends DBConfig
{
    protected $con = null;
    private object $dbh;
    public bool $connected = false;

    public function __construct($dbHost, $dbName, $dbUser, $dbPwd)
    {
        $connection = new DBConfig($dbHost, $dbName, $dbUser, $dbPwd);
        
        if($connection->con !== null)
        {
            $this->con = $connection;
            $this->dbh = $connection->con;
            $this->connected = true;
        }
    }

    public function __destruct()
    {
        unset($this->con);
        unset($this->dbh);
        $this->connected = false;
    }
    
    public function installFreshDatabase($file): void
    {
        $this->con->setData($file, array());
    }
    
    public function registerAdministrator($username, $password, $email, $profession = 1): void
    {
        global $security;
        
        // Register game account | Copy paste UserService
        $hash = hash('sha256', $password);
        
        $salt = $security->createSalt();
        
        $hash = hash('sha256', $salt . $hash);
        
        $statement = $this->dbh->prepare(
            "INSERT INTO `user`
            (`username`,`password`,`email`,`ip`,`registerDate`,`restartDate`,`isProtected`,`lang`,`charType`)
            VALUES
            (:username, :password, :email, :ip, :registerDate, :restartDate, '1', :lang, :charType)"
        );
        $statement->execute(array(
            ':username' => $username,
            ':password' => $hash,
            ':email' => 'NULL',
            ':ip' => $_SERVER['REMOTE_ADDR'],
            ':registerDate' => date('Y-m-d H:i:s'),
            ':restartDate' => date('Y-m-d H:i:s'),
            ':lang' => 'en',
            ':charType' => $profession
        ));

        // Set session but on an install environment. Will not login administrator as expected on main app environment.
        $_SESSION['UID'] = $this->dbh->lastInsertId();

        if(isset($_SESSION['UID']) && $_SESSION['UID'] > 0)
        {
            $encrypted = $security->encrypt($email);
            
            $saveDir = DOC_ROOT . '/app/Resources/userCrypts/'.$_SESSION['UID'].'/user/email/';
            $security->storeEncryptionIvAndKey($saveDir, $encrypted['iv'], $encrypted['key']);
            
            $this->con->setData("
                UPDATE `user` SET `statusID`='2', `stateID` = :stateID, `cityID` = :cityID, `lastclick`= :lclick, `position`=:position, `email`= :email WHERE `id` = :uid
            ", array(':stateID' => 1, ':cityID' => 1, ':lclick' => time(), ':position' => ($_SESSION['UID']-1), ':email' => $encrypted['encryption'],
                ':uid' => $_SESSION['UID'])
            );

            $saveDir = DOC_ROOT . '/app/Resources/userSalts/';
            $ourFileName = $saveDir . (int) $_SESSION['UID'] . '.txt';
            $ourFileHandle = fopen($ourFileName, 'w') or die("Failed to write to: ".$saveDir.".");
            $stringData = $salt;
            fwrite($ourFileHandle, $stringData);
            fclose($ourFileHandle);
            chmod($ourFileName, 0600);
            
            $masterEncrypted = $security->masterEncrypt((string)strtolower($email));
            
            $saveDir = DOC_ROOT . '/app/Resources/masterCrypts/user/';
            $ourFileName = $saveDir . "emails.txt";
            $file = fopen($ourFileName, 'r');
            $serializedEmails = fgets($file);
            $serializedEmails = file_get_contents($ourFileName);
            fclose($file);
            $emails = unserialize($serializedEmails);
            $emails[$_SESSION['UID']] = $masterEncrypted;
            
            if(file_exists($ourFileName)) unlink($ourFileName);
            $ourFileHandle = fopen($ourFileName, 'w') or die("Failed to write to: ".$saveDir.".");
            fwrite($ourFileHandle, serialize($emails));
            fclose($ourFileHandle);
            chmod($ourFileName, 0600);
        }
        // //Register game account
        
        // Regiter Administrator account | Copy paste MemberService
        $hash = null; // Re-init
        
        $hash = hash('sha256', $password);
        
        $salt = $security->createSalt();
        
        $hash = hash('sha256', $salt . $hash);
        
        $statement = $this->dbh->prepare(
            "INSERT INTO `member`
            (`email`, `password`, `status`, `naam`, `voornaam`, `adres`, `gemeente`, `postcode`)
            VALUES
            (:email, :password, '2', :naam, :voornaam, :adres, :gemeente, :postcode)"
        );
        $statement->execute(array(
            ':email' => $email,
            ':password' => $hash,
            ':naam' => "Mafiasource",
            ':voornaam' => "Administrator",
            ':adres' => 'Middle Of',
            ':gemeente' => 'NoWhere',
            ':postcode' => '666'
        ));
        
        $newMID = $this->dbh->lastInsertId();

        $saveDir =  DOC_ROOT . '/app/Resources/memberSalts/';
        $ourFileName =  $saveDir . (int) $newMID . '.txt';
        $ourFileHandle = fopen($ourFileName, 'w') or die("Failed to write to: ".$saveDir.".");
        $stringData = $salt;
        fwrite($ourFileHandle, $stringData);
        fclose($ourFileHandle);
        
        if(isset($newMID) && $newMID > 0)
        {
            $position = $this->con->getDataSR("SELECT `position` FROM `member` ORDER BY `position` DESC LIMIT 1")['position'];
            $pos = isset($position) ? $position + 1 : 0;
            $this->con->setData("UPDATE `member` SET `position`= :pos WHERE `id`= :mid AND `active`='1' AND `deleted`='0'", array(':pos' => $pos, ':mid' => $newMID));
        }
    }
}
