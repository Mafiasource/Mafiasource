<?PHP

namespace src\Data;

use src\Business\UserCoreService;
use src\Business\UserService;
use src\Business\StateService;
use src\Business\GroundService;
use src\Business\SeoService;
use src\Business\DonatorService;
use src\Data\config\DBConfig;
use src\Data\CrimeDAO;
use src\Data\PossessionDAO;
use src\Entities\User;
use src\Entities\UserFriendBlock;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class UserDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    private $dateFormat = "%d-%m-%Y %H:%i:%s"; // SQL Format
    private $phpDateFormat = "d-m-Y H:i:s";

    public function __construct()
    {
        global $lang;
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
        $this->lang = $lang;
        if($this->lang == 'en')
        {
            $this->dateFormat = "%m-%d-%Y %r"; // SQL Format
            $this->phpDateFormat = "m-d-Y g:i:s A";
        }
    }

    public function __destruct()
    {
        $this->dbh = null;
    }

    public function getRecordsCount()
    {
        $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `user` WHERE `active` = '1' AND `deleted` = '0' AND `statusID` < 8");
        $statement->execute();
        $row = $statement->fetch();
        $return = isset($row['total']) && $row['total'] > 0 ? $row['total'] : 0;
        return $return;
    }
    
    public function checkUsername($username)
    {
        $statement = $this->dbh->prepare("SELECT `id`, `email` FROM `user` WHERE `username`= :username AND `active`='1' AND `deleted`='0' LIMIT 1");
        $statement->execute(array(':username' => $username));
        return $statement;
    }
    
    public function checkEmail($email)
    { // Emails are encrypted, used in recoverPassword (see how we decrypt there) called through $userService->validateRecoverPassword
        global $security;
        $email = $security->masterEncrypt($email);
        
        $saveDir = DOC_ROOT . "/app/Resources/masterCrypts/user/";
        $ourFileName = $saveDir . "emails.txt";
        $file = fopen($ourFileName, "r");
        $serializedEmails = fgets($file);
        $serializedEmails = file_get_contents($ourFileName);
        fclose($file);
        $emails = unserialize($serializedEmails);
        
        $uid = array_search($email, $emails);
        
        $statement = $this->dbh->prepare("SELECT `id`, `username` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1");
        $statement->execute(array(':uid' => $uid));
        return $statement;
    }

    public function checkIPRegistered($ip)
    {
        $statement = $this->dbh->prepare("SELECT `id` FROM `user` WHERE `ip`= :ip AND `active`='1' AND `deleted`='0' LIMIT 1");
        $statement->execute(array(':ip' => $ip));
        if($statement->rowCount()) return $statement;
        
        $statement = $this->dbh->prepare("SELECT `id` FROM `login` WHERE `ip`= :ip LIMIT 1");
        $statement->execute(array(':ip' => $ip));
        if($statement->rowCount()) return $statement;
    }

    public function checkLoginGetIdOnSuccess($username, $pass)
    {
        $id = $this->getIdByUsername($username);
        if($id !== FALSE)
        {
            $file = fopen(DOC_ROOT . "/app/Resources/userSalts/".$id.".txt", "r");
            $salt = fgets($file);
            fclose($file);
            
            $hash = hash('sha256', $salt . hash('sha256', $pass));

            $statement = $this->dbh->prepare("SELECT `id` FROM `user` WHERE `username` = :username AND `password` = :password  AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':username' => $username, ':password' => $hash));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] == $id)
                return $id;
        }
        return FALSE;
    }
    
    public function loginUser($username, $id)
    { // Beware! Only use loginUser function after correct validation! ie. checkLogin($username, $pass)
        $tries = 0;
        if(isset($_SESSION['login-tries'])) $tries = $_SESSION['login-tries'];
        $_SESSION['UID'] = $id;
        global $route;
        if(!isset($_COOKIE['username'])) setcookie('username', $username, time()+25478524, '/', $route->settings['domain'], SSL_ENABLED, true);
        $statement = $this->dbh->prepare("INSERT INTO `login` (`userID`,`ip`,`date`,`time`,`tries`) VALUES (:id, :ip, :date, :time, :tries)");
        $statement->execute(array(':id' => $_SESSION['UID'], ':ip' => $_SERVER['REMOTE_ADDR'], ':date' => date('Y-m-d H:i:s'), ':time' => time(), ':tries' => $tries));
        return TRUE;
    }

    public function checkValidOwner($id = false, $ip = false)
    {
        if(isset($_SESSION['UID']))
        {
            if(!isset($id))
            {
                $id = $_SESSION['UID'];
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            $statement = $this->dbh->prepare("SELECT COUNT(*) FROM `login` WHERE `ip`= :ip AND `date` < :datePast AND `userID`= :uid ");
            $statement->execute(array(':ip' => $ip, ':datePast' => date('Y-m-d H:i:s', strtotime('-24 years')), ':uid' => $id));
            if($statement->rowCount() >= 1)
                return TRUE;
            else
                return FALSE;
        }
    }
    
    public function getIdByUsername($username)
    {
        $statement = $this->dbh->prepare("SELECT `id` FROM `user` WHERE `username` = :username AND `active`='1' AND `deleted`='0'");
        $statement->execute(array(':username' => $username));
        $row = $statement->fetch();
        if(isset($row['id']) && $row['id'] > 0)
            return $row['id'];
        else
            return FALSE;
    }

    public function getUsernameById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `username` FROM `user` WHERE `id` = :id AND `active`='1' AND `deleted`='0'");
            $statement->execute(array(':id' => $id));
            $row = $statement->fetch();
            if(isset($row['username']) && strlen($row['username']) > 0)
                return $row['username'];
            else
                return FALSE;
        }
    }
    
    public function createUser($username, $pass, $email, $profession)
    { // This function is one of the 2 password related functions for users, here the passwords get's created the first time
        $hash = hash('sha256', $pass);
        
        global $security;
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
            ':lang' => $this->lang,
            ':charType' => $profession
        ));

        // Set session uid
        $_SESSION['UID'] = $this->dbh->lastInsertId();

        if(isset($_SESSION['UID']) && $_SESSION['UID'] > 0)
        {
            $encrypted = $security->encrypt($email);
            
            $saveDir = DOC_ROOT . "/app/Resources/userCrypts/".$_SESSION['UID']."/user/email/";
            $security->storeEncryptionIvAndKey($saveDir, $encrypted['iv'], $encrypted['key']);
            
            // Random state
            $state = new StateService();
            $startState = $security->randInt(1,6);
            $startCity = $state->getRandCityIdByStateId($startState);
            $this->con->setData("
                UPDATE `user` SET `stateID` = :stateID, `cityID` = :cityID, `lastclick`= :lclick, `position`=:position, `email`= :email WHERE `id` = :uid
            ", array(':stateID' => $startState, ':cityID' => $startCity, ':lclick' => time(), ':position' => ($_SESSION['UID']-1), ':email' => $encrypted['encryption'],
                ':uid' => $_SESSION['UID'])
            );
            
            // Registered through referral link?
            if(isset($_SESSION['register']['referral']) && $rid = $this->getIdByUsername($_SESSION['register']['referral']))
            {
                $this->con->setData("
                    UPDATE `user` SET `referralOf`= :rid WHERE `id`= :uid AND `active`='1' AND `deleted`='0';
                    UPDATE `user` SET `referrals`=`referrals`+'1', `referralProfits`=`referralProfits`+'1000000', `bank`=`bank`+'1000000' WHERE `id`= :rid AND `active`='1' AND `deleted`='0'
                ", array(':uid' => $_SESSION['UID'], ':rid' => $rid));
            }
            
            $ourFileName = DOC_ROOT . "/app/Resources/userSalts/".$_SESSION['UID'].".txt";
            $ourFileHandle = fopen($ourFileName, 'w') or die("Kan geheim bestand niet aanmaken, meld dit aan de administrator samen met de URL: ".$_SERVER['REQUEST_URI'].".");
            $stringData = $salt;
            fwrite($ourFileHandle, $stringData);
            fclose($ourFileHandle);
            chmod($ourFileName, 0600);
            
            $masterEncrypted = $security->masterEncrypt($email);
            
            $saveDir = DOC_ROOT . "/app/Resources/masterCrypts/user/";
            $ourFileName = $saveDir . "emails.txt";
            $file = fopen($ourFileName, "r");
            $serializedEmails = fgets($file);
            $serializedEmails = file_get_contents($ourFileName);
            fclose($file);
            $emails = unserialize($serializedEmails);
            $emails[$_SESSION['UID']] = $masterEncrypted;
            
            if(file_exists($ourFileName)) unlink($ourFileName);
            $ourFileHandle = fopen($ourFileName, 'w') or die("Kan geheim bestand niet aanmaken, meld dit aan de administrator samen met de URL ".$_SERVER['REQUEST_URI'].".");
            fwrite($ourFileHandle, serialize($emails));
            fclose($ourFileHandle);
            chmod($ourFileName, 0600);
        }
    }
    
    public function resetUser($userID)
    {
        $this->con->setData("
            DELETE FROM `business_stock` WHERE `userID`= :uid;
            DELETE FROM `crime_org_prep` WHERE `userID`= :uid;
            UPDATE `crime_org_prep` SET `participantID`='0' WHERE `participantID`= :uid;
            UPDATE `crime_org_prep` SET `participant2ID`='0' WHERE `participant2ID`= :uid;
            UPDATE `crime_org_prep` SET `participant3ID`='0' WHERE `participant3ID`= :uid;
            DELETE FROM `detective` WHERE `userID`= :uid;
            DELETE FROM `drug_liquid` WHERE `userID`= :uid;
            DELETE FROM `equipment` WHERE `userID`= :uid;
            DELETE FROM `fifty_game` WHERE `userID`= :uid AND `type`!='2';
            UPDATE `ground` SET `userID`='0' WHERE `userID`= :uid;
            DELETE FROM `gym_competition` WHERE `userID`= :uid;
            DELETE FROM `market` WHERE `userID`= :uid AND ((`type`!='0' AND `type`!='2') OR ((`type`='0' OR `type`='2') AND `requested`='1'));
            UPDATE `possess` SET `userID`='0', `profit`='0', `profit_hour`='0', `stake`='50000' WHERE `userID`= :uid;
            DELETE FROM `possess_transfer` WHERE `senderID`= :uid OR `receiverID`= :uid;
            DELETE FROM `prison` WHERE `userID`= :uid;
            DELETE FROM `rld_whore` WHERE `userID`= :uid;
            DELETE FROM `smuggle_unit` WHERE `userID`= :uid;
            DELETE FROM `user_residence` WHERE `userID`= :uid
        ", array(':uid' => $userID));
        
        $userGarages = $this->con->getData("SELECT `id` FROM `user_garage` WHERE `userID`= :uid", array(':uid' => $userID));
        foreach($userGarages AS $g)
            $this->con->setData("DELETE FROM `garage` WHERE `userGarageID`= :ugid", array(':ugid' => $g['id']));
        
        $this->con->setData("DELETE FROM `user_garage` WHERE `userID`= :uid", array(':uid' => $userID));
    }
    
    public function resetDeadUser($username, $profession)
    {
        if(isset($_SESSION['UID']))
        {
            $this->resetUser($_SESSION['UID']);
            $this->con->setData("
                UPDATE `user`
                  SET `username`= :u, `restartDate`= NOW(), `isProtected`='1', `charType`= :p, `health`='100', `rankpoints`='0', `cash`='2500', `bank`='10000', `whoresStreet`='0',
                    `bullets`='0', `weapon`='0', `protection`='0', `airplane`='0', `weaponExperience`='0', `weaponTraining`='0', `residence`='0', `residenceHistory`='', `power`='0',
                    `cardio`='0', `luckybox`='0', `cCrimes`='0', `cWeaponTraining`='0', `cGymTraining`='0', `cStealVehicles`='0', `cPimpWhores`='0', `cFamilyRaid`='0',
                    `cFamilyCrimes`='0', `cBombardement`='0', `cTravelTime`='0'
                WHERE `id`= :uid AND `statusID`<='7' AND `health`<='0' AND `active`='1' AND `deleted`='0';
            ", array(':u' => $username, ':p' => $profession, ':uid' => $_SESSION['UID']));
        }
    }
    
    public static function email($sendFrom, $sendFromName, $message, $css, $sendTo, $subject)
    { // All app email to user through here:
        global $twig;
        $message = $twig->render('/app/Resources/Views/email.twig', array('css' => $css, 'message' => $message));
        $mail = new PHPMailer();
        try {
            if($sendFrom !== EMAIL_ADDR)
                $sendFrom = EMAIL_ADDR;
            
            $emailPort = 587;
            if(is_int(EMAIL_PORT))
                $emailPort = EMAIL_PORT;
            
            //Server settings
            $mail->SMTPDebug = FALSE;
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = $sendFrom;
            $mail->Password   = EMAIL_PWD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $emailPort;
        
            //Recipients
            $mail->setFrom($sendFrom, $sendFromName);
            $mail->addAddress($sendTo);
            $mail->addReplyTo($sendFrom, $sendFromName);
            if(isset($bbcEmail) && strlen($bbcEmail))
                $mail->addBCC($bbcEmail);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->AltBody = strip_tags($message);
            
            $mail->send();
            return TRUE;
        } catch (Exception $e) {
            die("Email could not be sent. SMTP server not configured correctly, contact Administrator for help. Error: {$mail->ErrorInfo}");
            exit(0);
        }
        return FALSE;
    }

    public function setNewEmailRequest($email)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `email`, `username` FROM `user` WHERE `id` = :id AND `active`='1' AND `deleted`='0'");
            $statement->execute(array(':id' => $_SESSION['UID']));
            $row = $statement->fetch();
            if(isset($row['email']) && $row['email'] != "")
            {
                global $security;
                $key = $security->randStr();
                
                $encrypted = $security->encrypt($email);
                
                $saveDir = DOC_ROOT . "/app/Resources/userCrypts/".$row['id']."/change_email/new_mail/";
                $security->storeEncryptionIvAndKey($saveDir, $encrypted['iv'], $encrypted['key']);
                
                $statement = $this->dbh->prepare("INSERT INTO `change_email` (`userID`, `key`, `new_mail`, `date`) VALUES (:id, :key, :newMail, :date)");
                $statement->execute(array(':id' => $_SESSION['UID'], ':key' => $key, ':newMail' => $encrypted['encryption'], ':date' => date('Y-m-d H:i:s')));
                
                global $route;
                $sendFrom = 'no-reply@'.strtolower($route->settings['domainBase']);
                $sendFromName = $route->settings['gamename'];
                $css = '';
                $sendTo = $row['email'];
                
                if(!UserService::is_email($sendTo))
                {
                    $saveDir = DOC_ROOT . "/app/Resources/userCrypts/".$row['id']."/user/email/";
                    $cryptKeys = $security->grabEncryptionIvAndKey($saveDir);
                    $sendTo = $security->decrypt($sendTo, $cryptKeys['iv'], $cryptKeys['key']);
                }
                
                global $language;
                $langs = $language->settingsLangs();
                
                $replaces = array(
                    array('part' => $row['username'], 'message' => $langs['CHANGE_EMAIL_MESSAGE'], 'pattern' => '/{username}/'),
                    array('part' => $key, 'message' => FALSE, 'pattern' => '/{key}/'),
                    array('part' => $email, 'message' => FALSE, 'pattern' => '/{newEmail}/'),
                );
                $replacedMessage = $route->replaceMessageParts($replaces);

                $message = $replacedMessage;
                $subject = $langs['CHANGE_EMAIL_SUBJECT'];
                
                if(self::email($sendFrom, $sendFromName, $message, $css, $sendTo, $subject)) return TRUE;
                else return FALSE;
            }
            else return FALSE;
        }
    }

    public function getCoveredEmailByUsername($username)
    {
        $statement = $this->dbh->prepare("SELECT `id`, `email` FROM `user` WHERE `username` = :username AND `active` ='1' AND `deleted`='0'");
        $statement->execute(array(':username' => $username));
        $row = $statement->fetch();
        if(isset($row['email']) && $row['email'] != "")
        {
            if(UserService::is_email($row['email']))
            {
                return preg_replace('/(?<=.).(?=.*@)/u', '*', $row['email']);
            }
            else
            {
                global $security;
                $saveDir = DOC_ROOT . "/app/Resources/userCrypts/".$row['id']."/user/email/";
                $cryptKeys = $security->grabEncryptionIvAndKey($saveDir);
                $decryptedEmail = $security->decrypt($row['email'], $cryptKeys['iv'], $cryptKeys['key']);
                
                return preg_replace('/(?<=.).(?=.*@)/u', '*', $decryptedEmail);
            }
        }
    }
    
    public function getChangeEmailDataByKey($key)
    {
        $this->con->setData("DELETE FROM `change_email` WHERE `date` < :datePast", array(':datePast' => date('Y-m-d H:i:s', strtotime('-2 hours'))));
        
        $row = $this->con->getDataSR("
            SELECT ce.`id`, ce.`new_mail`, u.`id` AS `uid`, u.`username` FROM `change_email` AS ce LEFT JOIN `user` AS u ON (ce.`userID`=u.`id`) WHERE ce.`key`= :key
        ", array(':key' => $key));
        if(isset($row['id']) && $row['id'] > 0)
        {
            global $security;
            
            $saveDir = DOC_ROOT . "/app/Resources/userCrypts/".$row['uid']."/change_email/new_mail/";
            $cryptKeys = $security->grabEncryptionIvAndKey($saveDir);
            $decryptedEmail = $security->decrypt($row['new_mail'], $cryptKeys['iv'], $cryptKeys['key']);
            
            $userObj = new User();
            $userObj->setId($row['uid']);
            $userObj->setUsername($row['username']);
            $userObj->setEmail($decryptedEmail);
            
            return $userObj;
        }
        else
            return FALSE;
    }
    
    public function changeEmail($changeEmailData)
    {
        if(is_object($changeEmailData))
        {
            global $security;
            $encrypted = $security->encrypt($changeEmailData->getEmail());
            
            $saveDir = DOC_ROOT . "/app/Resources/userCrypts/".$changeEmailData->getId()."/user/email/";
            $security->storeEncryptionIvAndKey($saveDir, $encrypted['iv'], $encrypted['key']);
            
            $this->con->setData("
                UPDATE `user` SET `email`= :email WHERE `id`= :uid;
                DELETE FROM `change_email` WHERE `userID`= :uid
            ", array(':email' => $encrypted['encryption'], ':uid' => $changeEmailData->getId()));
            
            $masterEncrypted = $security->masterEncrypt($changeEmailData->getEmail());
            
            $saveDir = DOC_ROOT . "/app/Resources/masterCrypts/user/";
            $ourFileName = $saveDir . "emails.txt";
            $file = fopen($ourFileName, "r");
            $serializedEmails = fgets($file);
            $serializedEmails = file_get_contents($ourFileName);
            fclose($file);
            $emails = unserialize($serializedEmails);
            $emails[$changeEmailData->getId()] = $masterEncrypted;
            
            if(file_exists($ourFileName)) unlink($ourFileName);
            $ourFileHandle = fopen($ourFileName, 'w') or die("Kan geheim bestand niet aanmaken, meld dit aan de administrator samen met de URL ".$_SERVER['REQUEST_URI'].".");
            fwrite($ourFileHandle, serialize($emails));
            fclose($ourFileHandle);
            chmod($ourFileName, 0600);
        }
    }

    public function changeTestament($testament)
    {
        if(isset($_SESSION['UID']))
        {
            $id = $this->getIdByUsername($testament);
            if($id != FALSE)
            {
                $statement = $this->dbh->prepare("UPDATE `user` SET `testamentHolder`= :id WHERE `id`= :uid AND `active`='1' AND `deleted`='0'");
                if($statement->execute(array(':id' => $id, ':uid' => $_SESSION['UID'])))
                    return TRUE;
            }
        }
    }

    public function updateAvatar($avatar)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `user` SET `avatar` = :avatar WHERE `id` = :id AND `active`='1' AND `deleted`='0'");
            $statement->execute(array(':avatar' => $avatar, ':id' => $_SESSION['UID']));
        }
    }

    public function updateProfile($profile)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `user` SET `profile` = :profile WHERE `id` = :id AND `active`='1' AND `deleted`='0'");
            $statement->execute(array(':profile' => addslashes($profile), ':id' => $_SESSION['UID']));
        }
    }
    
    public function checkPassword($pass)
    {
        if(isset($_SESSION['UID']))
        {
            $file = fopen(DOC_ROOT . "/app/Resources/userSalts/".$_SESSION['UID'].".txt", "r");
            $salt = fgets($file);
            fclose($file);

            $hash = hash('sha256', $salt . hash('sha256', $pass));

            $statement = $this->dbh->prepare("SELECT `id` FROM `user` WHERE `id` = :id AND `password` = :password  AND `active`='1' AND `deleted`='0'LIMIT 1");
            $statement->execute(array(':id' => $_SESSION['UID'], ':password' => $hash));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return TRUE;
            else
                return FALSE;
        }
        else
            return FALSE;
    }
    
    public function changePassword($pass)
    { // Change password from in-game
        if(isset($_SESSION['UID']))
        {
            global $userData;
            $this->changePasswordByUsername($pass, $userData->getUsername(), $_SESSION['UID']);
        }
    }
    
    public function changePasswordByUsername($pass, $username, $id = FALSE)
    { // This function is one of the 2 password related functions for users, here we are editing passwords
    // This passchange function is related to the recovery option and also used for the ingame pass change option
        if($id == FALSE) $id = $this->getIdByUsername($username); //Avoid another query
        // Remove old salt..
        unlink(DOC_ROOT . "/app/Resources/userSalts/".$id.".txt");
        
        $hash = hash('sha256', $pass);
        
        global $security;
        $salt = $security->createSalt();
        
        $hash = hash('sha256', $salt . $hash);

        $statement = $this->dbh->prepare("UPDATE `user` SET `password`= :hash WHERE `id`= :uid");
        $statement->execute(array(':hash' => $hash, ':uid' => $id));

        // Save new salt
        $ourFileName = DOC_ROOT . "/app/Resources/userSalts/".$id.".txt";
        $ourFileHandle = fopen($ourFileName, 'w') or die("Kan geheim bestand niet aanmaken, meld dit aan de administrator samen met de URL ".$_SERVER['REQUEST_URI'].".");
        $stringData = $salt;
        fwrite($ourFileHandle, $stringData);
        fclose($ourFileHandle);
        chmod($ourFileName, 0600);
        
        $this->con->setData("DELETE FROM `recover_password` WHERE `userID`= :uid", array(':uid' => $id));
    }
    
    public function recoverPassword($id, $username, $email)
    {
        global $route;
        global $security;
        // Insert new recover_password record with unique key linked to account id
        $key = $security->randStr();
        $this->con->setData("INSERT INTO `recover_password` (`userID`, `key`, `date`) VALUES (:uid, :key, NOW())", array(':uid' => $id, ':key' => $key));
        // Send email in the user preferred language en/nl
        $sendFrom = 'no-reply@'.strtolower($route->settings['domainBase']);
        $sendFromName = $route->settings['gamename'];
        $css = '';
        $sendTo = $email;
        
        if(!UserService::is_email($sendTo))
        {
            $saveDir = DOC_ROOT . "/app/Resources/userCrypts/".$id."/user/email/";
            $cryptKeys = $security->grabEncryptionIvAndKey($saveDir);
            $sendTo = $security->decrypt($sendTo, $cryptKeys['iv'], $cryptKeys['key']);
        }

        global $language;
        $langs = $language->recoverPasswordLangs();
        
        $replaces = array(
            array('part' => $username, 'message' => $langs['RECOVER_PASSWORD_EMAIL_MESSAGE'], 'pattern' => '/{username}/'), //
            array('part' => $key, 'message' => FALSE, 'pattern' => '/{key}/'),
        );
        $replacedMessage = $route->replaceMessageParts($replaces);

        $message = $replacedMessage;
        $subject = $langs['RECOVER_PASSWORD_EMAIL_SUBJECT'];

        if(self::email($sendFrom, $sendFromName, $message, $css, $sendTo, $subject)) return TRUE;
        else return FALSE;
    }

    public function getRecoverPasswordDataByKey($key)
    {
        $this->con->setData("DELETE FROM `recover_password` WHERE `date` < :datePast", array(':datePast' => date('Y-m-d H:i:s', strtotime('-2 hours'))));
        
        $row = $this->con->getDataSR("
            SELECT re.`id`, u.`username` FROM `recover_password` AS re LEFT JOIN `user` AS u ON (re.`userID`=u.`id`) WHERE re.`key`= :key
        ", array(':key' => $key));
        if($row['id'] > 0)
        {
            $userObj = new User();
            $userObj->setUsername($row['username']);
            
            return $userObj;
        }
        else
            return FALSE;
    }
/* Cleanup TO DO */
    public function getOnlineMembers()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT u.`id`, u.`username`, u.`avatar`, u.`donatorID`, u.`statusID`, s.`status_".$this->lang."` AS `status`, d.`donator_".$this->lang."` AS `donator`
                FROM `user` AS u
                LEFT JOIN `status` AS s
                ON (u.`statusID`=s.`id`)
                LEFT JOIN `donator` AS d
                ON (u.`donatorID`=d.`id`)
                WHERE u.`lastclick`> :timePast AND u.`statusID` = '7' AND u.`active`='1' AND u.`deleted`='0'
            ");
            $statement->execute(array(':timePast' => (time() - 360)));
            $list = array();
            foreach($statement AS $row)
            {
                if($row['statusID'] < 7 || $row['statusID'] == 8)
                    $className = SeoService::seoUrl($row['status']);
                else
                    $className = SeoService::seoUrl($row['donator']);
                $arr = array();
                $arr['id'] = $row['id'];
                $arr['username'] = $row['username'];
                $arr['usernameClassName'] = $className;
                $arr['donatorID'] = $row['donatorID'];
                $arr['avatar'] = FALSE;
                if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['id'].'/uploads/'.$row['avatar'])) $arr['avatar'] = $row['avatar'];
                
                array_push($list, $arr);
            }
            return $list;
        }
    }

    public function getOnlineFamMembers()
    {
        if(isset($_SESSION['UID']))
        {
            global $userData;
            $statement = $this->dbh->prepare("
                SELECT u.`id`, u.`username`, u.`avatar`, u.`donatorID`, u.`statusID`, s.`status_".$this->lang."` AS `status`, d.`donator_".$this->lang."` AS `donator`
                FROM `user` AS u
                LEFT JOIN `status` AS s
                ON (u.`statusID`=s.`id`)
                LEFT JOIN `donator` AS d
                ON (u.`donatorID`=d.`id`)
                WHERE u.`lastclick`> :timePast AND u.`familyID` = :famID AND u.`active`='1' AND u.`deleted`='0'
            ");
            $statement->execute(array(':timePast' => (time() - 360), ':famID' => $userData->getFamilyID()));
            $list = array();
            foreach($statement AS $row)
            {
                if($row['statusID'] < 7 || $row['statusID'] == 8)
                    $className = SeoService::seoUrl($row['status']);
                else
                    $className = SeoService::seoUrl($row['donator']);
                $arr = array();
                $arr['id'] = $row['id'];
                $arr['username'] = $row['username'];
                $arr['usernameClassName'] = $className;
                $arr['donatorID'] = $row['donatorID'];
                $arr['avatar'] = FALSE;
                if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['id'].'/uploads/'.$row['avatar'])) $arr['avatar'] = $row['avatar'];
                
                array_push($list, $arr);
            }
            return $list;
        }
    }

    public function getOnlineTeamMembers()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT u.`id`, u.`username`, u.`avatar`, u.`donatorID`, u.`statusID`, s.`status_".$this->lang."` AS `status`, d.`donator_".$this->lang."` AS `donator`
                FROM `user` AS u
                LEFT JOIN `status` AS s
                ON (u.`statusID`=s.`id`)
                LEFT JOIN `donator` AS d
                ON (u.`donatorID`=d.`id`)
                WHERE u.`lastclick`> :timePast AND u.`statusID` < '7' AND u.`active`='1' AND u.`deleted`='0'
            ");
            $statement->execute(array(':timePast' => (time() - 360)));
            $list = array();
            foreach($statement AS $row)
            {
                if($row['statusID'] < 7 || $row['statusID'] == 8)
                    $className = SeoService::seoUrl($row['status']);
                else
                    $className = SeoService::seoUrl($row['donator']);
                $arr = array();
                $arr['id'] = $row['id'];
                $arr['username'] = $row['username'];
                $arr['usernameClassName'] = $className;
                $arr['donatorID'] = $row['donatorID'];
                $arr['avatar'] = FALSE;
                if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['id'].'/uploads/'.$row['avatar'])) $arr['avatar'] = $row['avatar'];
                
                array_push($list, $arr);
            }
            return $list;
        }
    }
/* // Cleanup TO DO */
    public function getToplist($from, $to, $keyword = false, $rankAdd = false)
    {
        if(isset($_SESSION['UID']) && is_int($from) && is_int($to))
        {
            $cond = "WHERE";
            if($keyword !== false) $cond .= " u.`username` LIKE :keyword AND ";
            elseif($rankAdd !== false) $cond .= " " . $rankAdd . " AND";
            
            $statement = $this->dbh->prepare("
                SELECT u.`id`, u.`username`, u.`avatar`, u.`rankpoints`, u.`cash`, u.`bank`, u.`familyID`, f.`name` AS `familyName`, u.`health`, u.`kills`,
                    u.`statusID`, u.donatorID, st.`status_".$this->lang."` AS `status`, d.`donator_".$this->lang."` AS `donator`, u.`whoresStreet`, u.`honorPoints`,
                    (SELECT SUM(`whores`) FROM `rld_whore` WHERE `userID`=u.`id`) AS `rld_whores`, u.`restartDate`, u.`isProtected`
                FROM `user` AS u
                LEFT JOIN `family` AS f
                ON (u.`familyID`=f.`id`)
                LEFT JOIN `status` AS st
                ON (u.`statusID`=st.`id`)
                LEFT JOIN `donator` AS d
                ON (u.`donatorID`=d.`id`)
                $cond u.`active`='1' AND u.`deleted`='0'
                ORDER BY u.`score` DESC, u.`honorPoints` DESC, u.`whoresStreet` DESC, u.`rankpoints` DESC, u.`power` DESC, u.`cardio` DESC, u.`crimesLv` DESC,
                    u.`vehiclesLv` DESC, u.`pimpLv` DESC, u.`smugglingLv` DESC, u.`id` ASC
                LIMIT $from, $to
            ");
            $params = array();
            if($keyword != false) $params = array(':keyword' => "%".$keyword."%");
            $statement->execute($params);
            $list = array();
            $i = $from;
            foreach($statement AS $row)
            {
                if($row['statusID'] < 7 || $row['statusID'] == 8)
                    $className = SeoService::seoUrl($row['status']);
                else
                    $className = SeoService::seoUrl($row['donator']);
                $userObj = new User();
                $userObj->setId($row['id']);
                $userObj->setUsername($row['username']);
                $userObj->setUsernameClassName($className);
                if($row['familyID'] > 0)
                {
                    $userObj->setFamily($row['familyName']);
                    $userObj->setFamilyID($row['familyID']);
                }
                else
                {
                    $userObj->setFamily("Geen");
                    if($this->lang == 'en') $userObj->setFamily("None");
                    $userObj->setFamilyID($row['familyID']);
                }
                $userObj->setHonorPoints($row['honorPoints']);
                $userObj->setWhoresStreet($row['whoresStreet']);
                $userObj->setKills($row['kills']);
                $userObj->setTotalWhores($row['whoresStreet'] + $row['rld_whores']);
                $userObj->setIsProtected(false);
                if($row['isProtected'] == 1 && strtotime($row['restartDate']) > strtotime(date('Y-m-d H:i:s', strtotime("-3 days"))))
                {
                    $userObj->setIsProtected(date($this->phpDateFormat, strtotime($row['restartDate'])+(60*60*24*3)));
                }
                $cappedRankpoints = UserCoreService::getCappedRankpoints(
                    $row['rankpoints'], $userObj->getKills(), $userObj->getHonorPoints(), $userObj->getTotalWhores(), $userObj->getIsProtected()
                );
                $userObj->setRankpoints($cappedRankpoints);
                $rankInfo = UserCoreService::getRankInfoByRankpoints($userObj->getRankpoints());
                $userObj->setRankID($rankInfo['rankID']);
                $userObj->setRankname($rankInfo['rank']);
                $userObj->setDonatorID($row['donatorID']);
                $userObj->setMoneyRank(UserCoreService::getMoneyRank($row['cash']+$row['bank']));
                $userObj->setHealth($row['health']);
                $userObj->setHealthBar(array('health' => $row['health'], 'class' => "bg-success"));
                $userObj->setAvatar(FALSE);
                if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['id'].'/uploads/'.$row['avatar'])) $userObj->setAvatar($row['avatar']);
                $userObj->setScorePosition($i + 1);
                
                array_push($list, $userObj);
                $i++;
            }
            if(!empty($list)) return $list;
            else return FALSE;
        }
        else return FALSE;
    }
    
    public function getStatusPageInfo()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT  u.`id`, u.`kills`, u.`deaths`, u.`headshots`, u.`honorPoints`, u.`whoresStreet`, u.`warns`, u.`weaponExperience`, u.`weaponTraining`, u.`power`, u.`cardio`,
                    (SELECT COUNT(`id`) FROM `ground` WHERE `userID`= u.`id`) AS `ground`, u.`activeTime`, w.`name` AS `weapon`, p.`name` AS `protection`, a.`name` AS `airplane`,
                    u.`bullets`, u.`restartDate`, u.`isProtected`, (SELECT SUM(`whores`) FROM `rld_whore` WHERE `userID`=u.`id`) AS `rld_whores`
                FROM `user` AS u
                LEFT JOIN `weapon` AS w
                ON (u.`weapon`=w.`id`)
                LEFT JOIN `protection` AS p
                ON (u.`protection`=p.`id`)
                LEFT JOIN `airplane` AS a
                ON (u.`airplane`=a.`id`)
                WHERE u.`id`= :uid AND u.`active`='1' AND u.`deleted`='0'
            ");
            $statement->execute(array(':uid' => $_SESSION['UID']));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $userObj = new User();
                $userObj->setIsProtected(false);
                if($row['isProtected'] == 1 && strtotime($row['restartDate']) > strtotime(date('Y-m-d H:i:s', strtotime("-3 days"))))
                    $userObj->setIsProtected(date($this->phpDateFormat, strtotime($row['restartDate'])+(60*60*24*3)));
                
                $userObj->setKills($row['kills']);
                $userObj->setDeaths($row['deaths']);
                $userObj->setHeadshots($row['headshots']);
                $userObj->setHonorPoints($row['honorPoints']);
                $userObj->setWhoresStreet($row['whoresStreet']);
                $userObj->setWhoresRLD($row['rld_whores']);
                $userObj->setWarns($row['warns']);
                $userObj->setWeaponExperience($row['weaponExperience']);
                $userObj->setWeaponExperienceBar(array('experience' => $row['weaponExperience'], 'class' => "bg-success"));
                $userObj->setWeaponTraining($row['weaponTraining']);
                $userObj->setWeaponTrainingBar(array('training' => $row['weaponTraining'], 'class' => "bg-success"));
                $userObj->setPower($row['power']);
                $userObj->setCardio($row['cardio']);
                $gymTraining = 100;
                if((($row['power']+$row['cardio'])/2) < 100) $gymTraining = (($row['power']+$row['cardio'])/2);
                $userObj->setGymTrainingBar(array('training' => $gymTraining, 'class' => "bg-success"));
                $userObj->setGround($row['ground']);
                $userObj->setActiveTime($row['activeTime']);
                $userObj->setBullets($row['bullets']);
                $userObj->setWeapon($row['weapon']);
                $userObj->setProtection($row['protection']);
                $userObj->setAirplane($row['airplane']);
                
                return $userObj;
            }
        }
    }

    public function getBankPageInfo()
    {
        if(isset($_SESSION['UID']))
        {
            $buildingLvSelect = "";
            for($i = 1; $i <= 5; $i++)
            {
                $buildingLvSelect .= "(SELECT COUNT(`building1`) FROM `ground` WHERE `userID`= u.`id` AND `building1` = '".$i."' AND `active`='1' AND `deleted`='0') AS `b_lv".$i."_1`,
                    (SELECT COUNT(`building2`) FROM `ground` WHERE `userID`= u.`id` AND `building2` = '".$i."' AND `active`='1' AND `deleted`='0') AS `b_lv".$i."_2`,
                    (SELECT COUNT(`building3`) FROM `ground` WHERE `userID`= u.`id` AND `building3` = '".$i."' AND `active`='1' AND `deleted`='0') AS `b_lv".$i."_3`,
                    (SELECT COUNT(`building4`) FROM `ground` WHERE `userID`= u.`id` AND `building4` = '".$i."' AND `active`='1' AND `deleted`='0') AS `b_lv".$i."_4`,
                    (SELECT COUNT(`building5`) FROM `ground` WHERE `userID`= u.`id` AND `building5` = '".$i."' AND `active`='1' AND `deleted`='0') AS `b_lv".$i."_5`,";
            }
            $buildingLvSelect = substr_replace($buildingLvSelect ,"", -1);
            $statement = $this->dbh->prepare("
                SELECT  u.`id`, u.`donatorID`, u.`swissBank`, u.`swissBankMax`, (SELECT SUM(`whores`) FROM `rld_whore` WHERE `userID`=u.`id`) AS `rld_whores`, u.`whoresStreet`,
                    (SELECT COUNT(`building1`) FROM `ground` WHERE `userID`= u.`id` AND `building1` >= '1' AND `active`='1' AND `deleted`='0') AS `b1`,
                    (SELECT COUNT(`building2`) FROM `ground` WHERE `userID`= u.`id` AND `building2` >= '1' AND `active`='1' AND `deleted`='0') AS `b2`,
                    (SELECT COUNT(`building3`) FROM `ground` WHERE `userID`= u.`id` AND `building3` >= '1' AND `active`='1' AND `deleted`='0') AS `b3`,
                    (SELECT COUNT(`building4`) FROM `ground` WHERE `userID`= u.`id` AND `building4` >= '1' AND `active`='1' AND `deleted`='0') AS `b4`,
                    (SELECT COUNT(`building5`) FROM `ground` WHERE `userID`= u.`id` AND `building5` >= '1' AND `active`='1' AND `deleted`='0') AS `b5`,
                    
                    ".$buildingLvSelect."
                    
                FROM `user` AS u
                WHERE u.`id`= :uid AND u.`active`='1' AND u.`deleted`='0'
            ");
            $statement->execute(array(':uid' => $_SESSION['UID']));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $gb = $this->con->getData("SELECT `income` FROM `ground_building` WHERE `id` > '0' AND `id` <= '5' AND `active`='1' AND `deleted`='0' ORDER BY `id` ASC");
                $userObj = new User();
                $userObj->setWhoresStreet($row['whoresStreet'] * 15);
                $userObj->setWhoresRLD($row['rld_whores'] * 20);
                if($row['donatorID'] >= 10)
                {
                    $userObj->setWhoresStreet($row['whoresStreet'] * 18);
                    $userObj->setWhoresRLD($row['rld_whores'] * 23);
                }
                $userObj->setSwissBank($row['swissBank']);
                $userObj->setSwissBankMax($row['swissBankMax']);
                $ground = array();
                $ground['b1']['count'] = $row['b1'];
                $ground['b2']['count'] = $row['b2'];
                $ground['b3']['count'] = $row['b3'];
                $ground['b4']['count'] = $row['b4'];
                $ground['b5']['count'] = $row['b5'];
                
                for($i = 1; $i <= 5; $i++)
                {
                    $income = $gb[$i-1]['income'];
                    $ground['b'.$i]['income'] = $row['b_lv1_'.$i] * $income + $row['b_lv2_'.$i] * GroundService::getIncomeByLevel($income, 2)
                        + $row['b_lv3_'.$i] * GroundService::getIncomeByLevel($income, 3) + $row['b_lv4_'.$i] * GroundService::getIncomeByLevel($income, 4)
                        + $row['b_lv5_'.$i] * GroundService::getIncomeByLevel($income, 5);
                }
                
                $userObj->setGround($ground);
                $bankLogs = array();
                $statement2 = $this->dbh->prepare("
                    SELECT bl.`amount`, bl.`message`, DATE_FORMAT( bl.`date`, '".$this->dateFormat."' ) AS `date`, u.`username`
                    FROM `bank_log` AS bl
                    LEFT JOIN `user` AS u
                    ON (bl.`senderID`=u.`id`)
                    WHERE bl.`receiverID`= :uid
                    ORDER BY bl.`date` DESC
                    LIMIT 10
                ");
                $statement2->execute(array(':uid' => $_SESSION['UID']));
                $i = 0;
                foreach($statement2 AS $row2)
                {
                    $bankLogs['received'][$i]['username'] = $row2['username'];
                    $bankLogs['received'][$i]['date'] = $row2['date'];
                    $bankLogs['received'][$i]['amount'] = $row2['amount'];
                    $bankLogs['received'][$i]['message'] = $row2['message'];
                    $i++;
                }
                $statement3 = $this->dbh->prepare("
                    SELECT bl.`amount`, bl.`message`, DATE_FORMAT( bl.`date`, '".$this->dateFormat."' ) AS `date`, u.`username`
                    FROM `bank_log` AS bl
                    LEFT JOIN `user` AS u
                    ON (bl.`receiverID`=u.`id`)
                    WHERE bl.`senderID`= :uid
                    ORDER BY bl.`date` DESC
                    LIMIT 10
                ");
                $statement3->execute(array(':uid' => $_SESSION['UID']));
                $j = 0;
                foreach($statement3 AS $row3)
                {
                    $bankLogs['sent'][$j]['username'] = $row3['username'];
                    $bankLogs['sent'][$j]['date'] = $row3['date'];
                    $bankLogs['sent'][$j]['amount'] = $row3['amount'];
                    $bankLogs['sent'][$j]['message'] = $row3['message'];
                    $j++;
                }
                $userObj->setBankLogs($bankLogs);
                
                return $userObj;
            }
        }
    }

    public function donateMoneyToUser($amount, $transactionPercent, $receiver, $message, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            $id = $this->getIdByUsername($receiver);
            if($id)
            {
                $amountForUser = round($amount * ((100 - $transactionPercent) / 100), 0);
                $profitOwner = $amount - $amountForUser;
                $statement = $this->dbh->prepare("
                    UPDATE `user` SET `bank`=`bank`- :amount WHERE `id`= :uid AND `active`='1' AND `deleted`='0';
                    UPDATE `user` SET `bank`=`bank`+ :amountUsr WHERE `id`= :pid AND `active`='1' AND `deleted`='0'
                ");
                $statement->execute(array(':amount' => $amount, ':uid' => $_SESSION['UID'], ':amountUsr' => $amountForUser, ':pid' => $id));

                $statement = $this->dbh->prepare("INSERT INTO `bank_log` (`senderID`, `receiverID`, `amount`, `message`, `date`) VALUES (:uid, :pid, :amount, :message, :date)");
                $statement->execute(array(':uid' => $_SESSION['UID'], ':pid' => $id, ':amount' => $amount, ':message' => $message, ':date' => date('Y-m-d H:i:s')));
                
                /** Possession logic for bank donation to another user | pay owner if exists and not self **/
                if(is_object($pData)) $bankOwner = $pData->getPossessDetails()->getUserID();
                if(is_object($pData) && $bankOwner > 0 && $bankOwner != $_SESSION['UID'])
                {
                    $possessionData = new PossessionDAO();
                    $possessionData->applyProfitForOwner($pData, $profitOwner, $bankOwner);
                }
            }
        }
    }

    public function transferMoney($amount, $action)
    {
        if(isset($_SESSION['UID']))
        {
            if($action == 'getMoney')
            {
                $statement = $this->dbh->prepare("UPDATE `user` SET `bank`=`bank`- :amount, `cash`=`cash`+ :amount WHERE `id`= :uid AND `active`='1' AND `deleted`='0'");
                $statement->execute(array(':amount' => $amount, ':uid' => $_SESSION['UID']));
            }
            else
            {
                $statement = $this->dbh->prepare("UPDATE `user` SET `bank`=`bank`+ :amount, `cash`=`cash`- :amount WHERE `id`= :uid AND `active`='1' AND `deleted`='0'");
                $statement->execute(array(':amount' => $amount, ':uid' => $_SESSION['UID']));
            }
        }
    }

    public function transferSwissMoney($amount, $action, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            if($action == 'getMoney')
            {
                $statement = $this->dbh->prepare("UPDATE `user` SET `swissBank`=`swissBank`- :amount, `bank`=`bank`+ :amount WHERE `id`= :uid AND `active`='1' AND `deleted`='0'");
                $statement->execute(array(':amount' => $amount, ':uid' => $_SESSION['UID']));
            }
            else
            {
                $profitOwner = round($amount * 0.05, 0);
                
                $statement = $this->dbh->prepare("UPDATE `user` SET `swissBank`=`swissBank`+ :amount, `bank`=`bank`- :amount WHERE `id`= :uid AND `active`='1' AND `deleted`='0'");
                $statement->execute(array(':amount' => ($amount*0.95), ':uid' => $_SESSION['UID']));
                
                /** Possession logic for bank transfer to swiss bank | pay owner if exists and not self **/
                if(is_object($pData)) $bankOwner = $pData->getPossessDetails()->getUserID();
                if(is_object($pData) && $bankOwner > 0 && $bankOwner != $_SESSION['UID'])
                {
                    $possessionData = new PossessionDAO();
                    $possessionData->applyProfitForOwner($pData, $profitOwner, $bankOwner);
                }
            }
        }
    }

    public function getUserProfile($username)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT  u.`id`, u.`username`, u.`lastclick`, u.`charType`, b.`profession_".$this->lang."` AS `profession`, u.`statusID`, st.`status_".$this->lang."` AS `status`,
                        u.`donatorID`, d.`donator_".$this->lang."` AS `donator`, u.`familyID`, f.`name` AS `family`, u.`rankpoints`, u.`cash`, u.`bank`, u.`health`,
                        (SELECT COUNT(`id`) FROM `ground` WHERE `userID`= u.`id`) AS `ground`, u.`luckybox`, u.`avatar`, u.`lang`, u.`honorPoints`, u.`kills`, u.`deaths`,
                        u.`headshots`, u.`profile`, u.`score`, u.`crimesLv`, u.`crimesXp`, u.`vehiclesLv`, u.`pimpLv`, u.`smugglingLv`, u.`referrals`, u.`whoresStreet`,
                        (SELECT SUM(`whores`) FROM `rld_whore` WHERE `userID`=u.`id`) AS `rld_whores`, u.`restartDate`, u.`isProtected`
                FROM `user` AS u
                LEFT JOIN `profession` AS b
                ON (u.charType=b.id)
                LEFT JOIN `status` AS st
                ON (u.statusID=st.id)
                LEFT JOIN `donator` AS d
                ON (u.donatorID=d.id)
                LEFT JOIN `family` AS f
                ON (u.familyID=f.id)
                WHERE u.`username` = :username
                AND u.`active`='1' AND u.`deleted`='0'
            ");

            $statement->execute(array(':username' => $username));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $statementScore = $this->dbh->prepare("SELECT COUNT(`id`) AS `no` FROM `user` WHERE `score`> :score");
                $statementScore->execute(array(':score' => $row['score']));
                $scoreRow = $statementScore->fetch();
                $scorePosition = $scoreRow['no'] + 1;
                if($row['statusID'] < 7 || $row['statusID'] == 8)
                    $className = SeoService::seoUrl($row['status']);
                else
                    $className = SeoService::seoUrl($row['donator']);
                
                $userObj = new User();
                $userObj->setId($row['id']);
                $userObj->setUsername($row['username']);
                $userObj->setUsernameClassName($className);
                $userObj->setLastclick($row['lastclick']);
                $userObj->setCharType($row['charType']);
                $userObj->setProfession($row['profession']);
                $userObj->setReferrals($row['referrals']);
                $userObj->setStatus($row['status']);
                $userObj->setStatusID($row['statusID']);
                $userObj->setDonator($row['donator']);
                $userObj->setDonatorID($row['donatorID']);
                if($row['familyID'] > 0)
                {
                    $userObj->setFamily($row['family']);
                    $userObj->setFamilyID($row['familyID']);
                }
                else
                {
                    $userObj->setFamily("Geen");
                    if($this->lang == 'en') $userObj->setFamily("None");
                    $userObj->setFamilyID($row['familyID']);
                }
                $userObj->setHonorPoints($row['honorPoints']);
                $userObj->setWhoresStreet($row['whoresStreet']);
                $userObj->setKills($row['kills']);
                $userObj->setTotalWhores($row['whoresStreet'] + $row['rld_whores']);
                $userObj->setIsProtected(false);
                if($row['isProtected'] == 1 && strtotime($row['restartDate']) > strtotime(date('Y-m-d H:i:s', strtotime("-3 days"))))
                    $userObj->setIsProtected(date($this->phpDateFormat, strtotime($row['restartDate'])+(60*60*24*3)));
                
                $cappedRankpoints = UserCoreService::getCappedRankpoints(
                    $row['rankpoints'], $userObj->getKills(), $userObj->getHonorPoints(), $userObj->getTotalWhores(), $userObj->getIsProtected()
                );
                $userObj->setRankpoints($cappedRankpoints);
                $rankInfo = UserCoreService::getRankInfoByRankpoints($userObj->getRankpoints());
                $userObj->setRankID($rankInfo['rankID']);
                $userObj->setRankname($rankInfo['rank']);
                $userObj->setScore($row['score']);
                $userObj->setScorePosition($scorePosition);
                $userObj->setCash($row['cash']);
                $userObj->setBank($row['bank']);
                $userObj->setMoneyRank(UserCoreService::getMoneyRank($row['cash']+$row['bank']));
                $userObj->setHealth($row['health']);
                $userObj->setHealthBar(array('health' => $row['health'], 'class' => "bg-success"));
                $userObj->setLang($row['lang']);
                $userObj->setAvatar(FALSE);
                if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['id'].'/uploads/'.$row['avatar'])) $userObj->setAvatar($row['avatar']);
                $userObj->setGround($row['ground']);
                $userObj->setDeaths($row['deaths']);
                $userObj->setHeadshots($row['headshots']);
                $userObj->setLastOnline(date('d-m-Y H:i', $row['lastclick']));
                $userObj->setCrimesLv($row['crimesLv']);
                $userObj->setCrimesXpRaw($row['crimesXp']); // Used in organized crimes
                $userObj->setVehiclesLv($row['vehiclesLv']);
                $userObj->setPimpLv($row['pimpLv']);
                $userObj->setSmugglingLv($row['smugglingLv']);
                $userObj->setProfile(stripslashes($row['profile']));
                
                return $userObj;
            }
        }
        return false;
    }
    
    public function exchangeHonorPoints($arr)
    {
        $validExchanges = array("cash", "rankpoints", "bullets", "whoresStreet");
        if(isset($_SESSION['UID']))
        {
            if(in_array($arr['whatRaw'],$validExchanges))
            {
                $statement = $this->dbh->prepare("UPDATE `user` SET `".$arr['whatRaw']."`=`".$arr['whatRaw']."`+ :val, `honorPoints`=`honorPoints`- :hp WHERE `id`= :uid");
                $statement->execute(array(':val' => $arr['val'], ':hp' => $arr['hp'], ':uid' => $_SESSION['UID']));
            }
        }
    }

    public function sendHonorPointsTo($receiverID, $amount, $message)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                UPDATE `user` SET `honorpoints`=`honorpoints`+ :amount WHERE `id` = :rid;
                UPDATE `user` SET `honorpoints`=`honorpoints`- :amount WHERE `id` = :uid
            ");
            $statement->execute(array('amount' => $amount, ':rid' => $receiverID, ':uid' => $_SESSION['UID']));

            $statement = $this->dbh->prepare("INSERT INTO `honorpoint_log` (`senderID`, `receiverID`, `amount`, `message`, `date`) VALUES (:uid, :rid, :amount, :message, NOW())");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':rid' => $receiverID, ':amount' => $amount, ':message' => $message));
        }
    }
    
    public function getHonorPointLogs()
    {
        if(isset($_SESSION['UID']))
        {
            $hpLogs = array();
            $statement2 = $this->dbh->prepare("
                SELECT hl.*, DATE_FORMAT( hl.`date`, '".$this->dateFormat."' ) AS `date`, u.`username`
                FROM `honorpoint_log` AS hl
                LEFT JOIN `user` AS u
                ON (hl.`senderID`=u.`id`)
                WHERE hl.`receiverID`= :uid
                ORDER BY hl.`date` DESC
                LIMIT 10
            ");
            $statement2->execute(array(':uid' => $_SESSION['UID']));
            $i = 0;
            foreach($statement2 AS $row2)
            {
                $hpLogs['received'][$i]['username'] = $row2['username'];
                $hpLogs['received'][$i]['date'] = $row2['date'];
                $hpLogs['received'][$i]['amount'] = $row2['amount'];
                $hpLogs['received'][$i]['message'] = $row2['message'];
                $i++;
            }
            $statement3 = $this->dbh->prepare("
                SELECT hl.*, DATE_FORMAT( hl.`date`, '".$this->dateFormat."' ) AS `date`, u.`username`
                FROM `honorpoint_log` AS hl
                LEFT JOIN `user` AS u
                ON (hl.`receiverID`=u.`id`)
                WHERE hl.`senderID`= :uid
                ORDER BY hl.`date` DESC
                LIMIT 10
            ");
            $statement3->execute(array(':uid' => $_SESSION['UID']));
            $j = 0;
            foreach($statement3 AS $row3)
            {
                $hpLogs['sent'][$j]['username'] = $row3['username'];
                $hpLogs['sent'][$j]['date'] = $row3['date'];
                $hpLogs['sent'][$j]['amount'] = $row3['amount'];
                $hpLogs['sent'][$j]['message'] = $row3['message'];
                $j++;
            }
            return $hpLogs;
        }
    }
    
    public function healMember($costs, $memberProfile, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            $profitOwner = $costs;
            $this->con->setData("
                UPDATE `user` SET `health`='100' WHERE `id`= :mid AND `active`='1' AND `deleted`='0';
                UPDATE `user` SET `cash`=`cash`- :costs WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':mid' => $memberProfile->getId(), ':costs' => $costs, ':uid' => $_SESSION['UID']));
            
            /** Possession logic for healing a player | pay owner if exists and not self **/
            if(is_object($pData)) $hospitalOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $hospitalOwner > 0 && $hospitalOwner != $_SESSION['UID'])
            {
                $possessionData = new PossessionDAO();
                $possessionData->applyProfitForOwner($pData, $profitOwner, $hospitalOwner);
            }
        }
    }
    
    public function getGymPageInfo()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT `id`, `gymFastAction`, `power`, `cardio`, `gymCompetitionWin`, `gymCompetitionLoss`, `gymProfit`, `gymScorePointsEarned`
                FROM `user`
                WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ");
            $statement->execute(array(':uid' => $_SESSION['UID']));
            $row = $statement->fetch();
            if($row['id'] > 0)
            {
                $userObj = new User();
                $userObj->setPower($row['power']);
                $userObj->setCardio($row['cardio']);
                $gymTraining = 100;
                if((($row['power']+$row['cardio'])/2) < 100) $gymTraining = (($row['power']+$row['cardio'])/2);
                $userObj->setGymTrainingBar(array('training' => $gymTraining, 'class' => "bg-success"));
                $userObj->setGymFastAction($row['gymFastAction']);
                $userObj->setGymCompetitionWin($row['gymCompetitionWin']);
                $userObj->setGymCompetitionLoss($row['gymCompetitionLoss']);
                $wlRatio = CrimeDAO::gcd($row['gymCompetitionWin'], $row['gymCompetitionLoss']);
                if($wlRatio != 0)
                    $userObj->setGymCompetitionWLRatio($row['gymCompetitionWin']/$wlRatio.':'.$row['gymCompetitionLoss']/$wlRatio);
                else
                    $userObj->setGymCompetitionWLRatio($row['gymCompetitionWin'].':'.$row['gymCompetitionLoss']);
                $userObj->setGymProfit($row['gymProfit']);
                $userObj->setGymScorePointsEarned($row['gymScorePointsEarned']);
                
                return $userObj;
            }
        }
    }
    
    public function gymChangeFastAction($id)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `user` SET `gymFastAction`= :id WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':id' => $id, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function gymTraining($stats, $waitingTime)
    {
        if(isset($_SESSION['UID']))
        {
            global $userData;
            $donatorService = new DonatorService();
            $waitingTime = $donatorService->adjustWaitingTime($waitingTime, $userData->getDonatorID());
            $this->con->setData("
                UPDATE `user` SET `power`=`power`+ :p, `cardio`=`cardio`+ :c, `cGymTraining`= :time WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':p' => $stats['power'], ':c' => $stats['cardio'], ':time' => (time() + $waitingTime), ':uid' => $_SESSION['UID']));
        }
    }
    
    public function getTeamMembers()
    {
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData("
                SELECT `id`, `status_".$this->lang."` AS `status`, `description_".$this->lang."` AS `description`
                FROM `status`
                WHERE `id`< '7' AND `active`='1' AND (`deleted`='0' OR `deleted`='-1')
                ORDER BY `id` ASC, `position` DESC
            ");
            
            $list = array();
            foreach($rows AS $status)
            {
                $sl = array($status['status'] => array('statusDescription' => $status['description']));
                $statement = $this->dbh->prepare("SELECT `id`, `username` FROM `user` WHERE `statusID`= :sid AND `active`='1' AND `deleted`='0'");
                $statement->execute(array(':sid' => $status['id']));
                foreach($statement AS $row)
                {
                    $arr = array();
                    $arr['id'] = $row['id'];
                    $arr['username'] = $row['username'];
                    
                    array_push($sl[$status['status']], $arr);
                }
                array_push($list, $sl);
            }
            return $list;
        }
    }

    public function getFriendsBlock($username = FALSE, $limited = FALSE)
    {
        if(isset($_SESSION['UID']))
        {
            $uid = $_SESSION['UID'];
            if($username != FALSE) $fetchedID = $this->getIdByUsername($username);
            if($username !== FALSE && $fetchedID !== FALSE) $uid = $fetchedID;
            $list = array();
            $sql = "SELECT fb.`id`, fb.`inviterID`, fb.`active`, u.`id` AS `fid`, u.`username`, u.`statusID`, u.`donatorID`, u.`avatar`, st.`status_".$this->lang."` AS `status`,
                            d.`donator_".$this->lang."` AS `donator`
                    FROM `user_friend_block` AS fb
                    LEFT JOIN `user` AS u
                    ON (fb.`friendID`=u.`id`)
                    LEFT JOIN `status` AS st
                    ON (u.`statusID`=st.`id`)
                    LEFT JOIN `donator` AS d
                    ON (u.`donatorID`=d.`id`)
                    WHERE fb.`userID`= :uid AND fb.`type`='1' AND fb.`deleted`='0'
            ";
            if(isset($fetchedID)) $sql .= " AND fb.`active`=1";
            if($limited == TRUE) $sql .= " LIMIT 0, 5";
            $statement = $this->dbh->prepare($sql);
            $statement->execute(array(':uid' => $uid));
            $friends = array();
            while($row = $statement->fetch())
            { // Friends
                if($row['statusID'] < 7 || $row['statusID'] == 8)
                {
                    $className = SeoService::seoUrl($row['status']);
                }
                else
                {
                    $className = SeoService::seoUrl($row['donator']);
                }
                $user = new UserFriendBlock();
                $user->setId($row['fid']);
                $user->setInviterID($row['inviterID']);
                $user->setUsername($row['username']);
                $user->setUsernameClassName($className);
                $user->setDonatorID($row['donatorID']);
                $user->setActive("active");
                if($row['active'] == 0) $user->setActive("inactive");
                $user->setAvatar(FALSE);
                if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['fid'].'/uploads/'.$row['avatar'])) $user->setAvatar($row['avatar']);
                
                array_push($friends, $user);
            }
            if($uid == $_SESSION['UID'])
            { // Blocks
                $statement = $this->dbh->prepare("
                    SELECT fb.`id`, fb.`inviterID`, u.`id` AS `fid`, u.`username`, u.`statusID`, u.`donatorID`, u.`avatar`, st.`status_".$this->lang."` AS `status`,
                            d.`donator_".$this->lang."` AS `donator`
                    FROM `user_friend_block` AS fb
                    LEFT JOIN `user` AS u
                    ON (fb.`friendID`=u.`id`)
                    LEFT JOIN `status` AS st
                    ON (u.`statusID`=st.`id`)
                    LEFT JOIN `donator` AS d
                    ON (u.`donatorID`=d.`id`)
                    WHERE fb.`userID`= :uid AND fb.`type`='0' AND fb.`active`='1' AND fb.`deleted`='0'
                ");
                $statement->execute(array(':uid' => $_SESSION['UID']));
                $blocks = array();
                while($row = $statement->fetch())
                {
                    if($row['statusID'] < 7 || $row['statusID'] == 8)
                        $className = SeoService::seoUrl($row['status']);
                    else
                        $className = SeoService::seoUrl($row['donator']);
                    $user = new UserFriendBlock();
                    $user->setId($row['fid']);
                    $user->setInviterID($row['inviterID']);
                    $user->setUsername($row['username']);
                    $user->setUsernameClassName($className);
                    $user->setAvatar(FALSE);
                    if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['fid'].'/uploads/'.$row['avatar'])) $user->setAvatar($row['avatar']);
                    
                    array_push($blocks, $user);
                }
            }
            else $blocks = "";
            $list = array('friends' => $friends, 'blocks' => $blocks);
            return $list;
        }
    }
    
    public function getFriendsList()
    { // Minimal friendlist for verious select tags
        if(isset($_SESSION['UID']))
        {
            $sql = "SELECT fb.`id`, fb.`inviterID`, fb.`active`, u.`id` AS `fid`, u.`username`
                    FROM `user_friend_block` AS fb
                    LEFT JOIN `user` AS u
                    ON (fb.`friendID`=u.`id`)
                    WHERE fb.`userID`= :uid AND fb.`type`='1' AND fb.`deleted`='0'
            ";
            $statement = $this->dbh->prepare($sql);
            $statement->execute(array(':uid' => $_SESSION['UID']));
            $friends = array();
            while($row = $statement->fetch())
            {
                $user = new UserFriendBlock();
                $user->setId($row['fid']);
                $user->setInviterID($row['inviterID']);
                $user->setUsername($row['username']);
                $user->setActive("active");
                if($row['active'] == 0) $user->setActive("inactive");
                
                array_push($friends, $user);
            }
            return $friends;
        }
    }

    public function checkFriends($userID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `user_friend_block` WHERE `userID` = :uid AND `friendID` = :fid AND `type`='1' AND `active`='1' AND `deleted`='0'");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':fid' => $userID));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return TRUE;
            else
                return FALSE;
        }
    }

    public function checkFriendsPending($userID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `user_friend_block` WHERE `userID` = :uid AND `friendID` = :fid AND `type`='1' AND `active`='0' AND `deleted`='0'");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':fid' => $userID));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return TRUE;
            else
                return FALSE;
        }
    }

    public function checkOneWayBlock($userID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `user_friend_block` WHERE `userID` = :uid AND `friendID` = :fid AND `type`='0' AND `active`='1' AND `deleted`='0'");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':fid' => $userID));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return TRUE;
            else
                return FALSE;
        }
    }

    public function checkBlock($userID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT `id`
                FROM `user_friend_block`
                WHERE ((`userID` = :uid AND `friendID` = :fid) OR (`userID` = :fid AND `friendID` = :uid)) AND `type`='0' AND `active`='1' AND `deleted`='0'
            ");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':fid' => $userID));
            $row = $statement->fetch();
            
            if(isset($row['id']) && $row['id'] > 0)
                return TRUE;
            else
                return FALSE;
        }
    }

    public function inviteFriend($userID)
    { //2 records inserted, a convenience
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("INSERT INTO `user_friend_block` (`inviterID`, `userID`, `friendID`, `type`, `active`) VALUES (:uid, :uid, :fid, '1', '0')");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':fid' => $userID));

            $statement = $this->dbh->prepare("INSERT INTO `user_friend_block` (`inviterID`, `userID`, `friendID`, `type`, `active`) VALUES (:fid, :uid, :fid, '1', '0')");
            $statement->execute(array(':uid' => $userID, ':fid' => $_SESSION['UID']));
        }
    }

    public function blockUser($userID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("INSERT INTO `user_friend_block` (`inviterID`, `userID`, `friendID`, `type`, `active`) VALUES (:uid, :uid, :fid, '0', '1')");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':fid' => $userID));
        }
    }

    public function acceptFriend($userID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                UPDATE `user_friend_block` SET `active`='1' WHERE `type`='1' AND ((`userID`= :uid AND `friendID`= :fid) OR (`userID`= :fid AND `friendID`= :uid))
            ");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':fid' => $userID));
        }
    }

    public function denyFriend($userID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                DELETE FROM `user_friend_block` WHERE `type`='1' AND `active`='0' AND ((`userID`= :uid AND `friendID`= :fid) OR (`userID`= :fid AND `friendID`= :uid))
            ");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':fid' => $userID));
        }
    }

    public function deleteFriend($userID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("DELETE FROM `user_friend_block` WHERE `type`='1' AND ((`userID`= :uid AND `friendID`= :fid) OR (`userID`= :fid AND `friendID`= :uid))");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':fid' => $userID));
        }
    }

    public function deleteBlock($userID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("DELETE FROM `user_friend_block` WHERE `type`='0' AND `inviterID`= :uid AND `friendID`= :fid");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':fid' => $userID));
        }
    }
    
    public function openedLuckybox($prize)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `user` SET `luckybox`=`luckybox`- 1, `".$prize['prizeDb']."`=`".$prize['prizeDb']."`+ :amount WHERE `id`= :uid AND `luckybox`>'0' AND `active`='1' AND `deleted`='0'
            ", array(':amount' => $prize['amount'], ':uid' => $_SESSION['UID']));
            if($prize['prizeDb'] == "credits")
                $this->con->setData("
                    UPDATE `user` SET `creditsWon`=`creditsWon`+ :amount WHERE `id`= :uid AND `luckybox`>='0' AND `active`='1' AND `deleted`='0'
                ", array(':amount' => $prize['amount'], ':uid' => $_SESSION['UID']));
        }
    }
    
    public function removeStatusProtection()
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("UPDATE `user` SET `isProtected`='0' WHERE `id`= :uid AND `isProtected`='1' AND `active`='1' AND `deleted`='0'", array(':uid' => $_SESSION['UID']));
        }
    }
    
    public function addCreditsFound($credits)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `user` SET `credits`=`credits`+ :c, `creditsWon`=`creditsWon`+ :c WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':c' => $credits, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function resetMafiasource()
    { // Not used anywhere but very usefull. Change  `restartDate`='2020-12-28 14:00:00'  before executing
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                TRUNCATE TABLE `bank_log`;
                UPDATE `bullet_factory` SET `bullets`='10000', `priceEachBullet`='2500', `production`='0';
                UPDATE `business` SET `last_price`=`opening_price`, `close_price`=`opening_price`, `high_price`=`opening_price`, `low_price`=`opening_price`;
                -- TRUNCATE TABLE `business_history`;
                TRUNCATE TABLE `business_stock`;
                TRUNCATE TABLE `change_email`;
                TRUNCATE TABLE `crime_org_prep`;
                TRUNCATE TABLE `detective`;
                TRUNCATE TABLE `drug_liquid`;
                TRUNCATE TABLE `equipment`;
                TRUNCATE TABLE `family`;
                TRUNCATE TABLE `family_alliance`;
                TRUNCATE TABLE `family_bank_log`;
                TRUNCATE TABLE `family_bf_donation_log`;
                TRUNCATE TABLE `family_bf_send_log`;
                TRUNCATE TABLE `family_brothel_whore`;
                TRUNCATE TABLE `family_crime`;
                TRUNCATE TABLE `family_donation_log`;
                TRUNCATE TABLE `family_garage`;
                TRUNCATE TABLE `family_join_invite`;
                TRUNCATE TABLE `family_mercenary_log`;
                TRUNCATE TABLE `family_raid`;
                TRUNCATE TABLE `fifty_game`;
                TRUNCATE TABLE `forum_reaction`;
                TRUNCATE TABLE `forum_read`;
                TRUNCATE TABLE `forum_topic`;
                TRUNCATE TABLE `garage`;
                UPDATE `ground` SET `userID`='0', `building1`='0', `building2`='0', `building3`='0', `building4`='0', `building5`='0', `cBuilding1`='0', `cBuilding2`='0',
                    `cBuilding3`='0', `cBuilding4`='0', `cBuilding5`='0';
                TRUNCATE TABLE `gym_competition`;
                TRUNCATE TABLE `hitlist`;
                TRUNCATE TABLE `honorpoint_log`;
                TRUNCATE TABLE `login`;
                TRUNCATE TABLE `lottery`;
                TRUNCATE TABLE `lottery_winner`;
                TRUNCATE TABLE `market`;
                TRUNCATE TABLE `message`;
                TRUNCATE TABLE `murder_log`;
                -- TRUNCATE TABLE `news`;
                TRUNCATE TABLE `notification`;
                -- TRUNCATE TABLE `poll_answer`;
                -- TRUNCATE TABLE `poll_question`;
                -- TRUNCATE TABLE `poll_vote`;
                UPDATE `possess` SET `userID`='0', `profit`='0', `profit_hour`='0', `stake`='50000';
                TRUNCATE TABLE `possess_transfer`;
                TRUNCATE TABLE `prison`;
                TRUNCATE TABLE `recover_password`;
                UPDATE `rld` SET `windows`='1', `priceEachWindow`='150';
                TRUNCATE TABLE `rld_whore`;
                TRUNCATE TABLE `shoutbox_en`;
                TRUNCATE TABLE `shoutbox_nl`;
                TRUNCATE TABLE `smuggle_unit`;
                -- TRUNCATE TABLE `user`;
                UPDATE `user`
                  SET `restartDate`='2020-12-28 14:00:00', `isProtected`='1', `activeTime`='0', `referralProfits`='0', `warns`='0', `forumPosts`='0', `familyID`='0', `rankpoints`='0', `health`='100', `score`='0',
                    `cash`='2500', `bank`='10000', `swissBank`='0', `swissBankMax`='100000000', `prisonBusts`='0', `honorPoints`='0', `whoresStreet`='0', `kills`='0', `deaths`='0', `headshots`='0',
                    `bullets`='10', `weapon`='0', `protection`='0', `airplane`='0', `weaponExperience`='0', `weaponTraining`='0', `residence`='0', `residenceHistory`='', `power`='0', `cardio`='0',
                    `gymCompetitionWin`='0', `gymCompetitionLoss`='0', `gymProfit`='0', `gymScorePointsEarned`='0', `daily1Amount`='0', `daily2Amount`='0', `daily3Amount`='0', `dailyCompletedDays`='1',
                    `luckybox`='0', `credits`='0', `creditsWon`='0',
                    `crimesLv`='1', `crimesXp`='0,00', `crimesProfit`='0', `crimesSuccess`='0', `crimesFail`='0', `crimesRankpoints`='0',
                    `vehiclesLv`='1', `vehiclesXp`='0,00', `vehiclesProfit`='0', `vehiclesSuccess`='0', `vehiclesFail`='0', `vehiclesRankpoints`='0',
                    `pimpLv`='1', `pimpXp`='0,00', `pimpProfit`='0', `pimpAttempts`='0', `pimpAmount`='0',
                    `smugglingLv`='1', `smugglingXp`='0,00', `smugglingProfit`='0', `smugglingTrips`='0', `smugglingUnits`='0', `smugglingBusts`='0',
                    `m5c`='0', `m8c`='0', `lrsID_nl`='0', `lrfsID_nl`='0', `lrsID_en`='0', `lrfsID_en`='0', `cCrimes`='0', `cWeaponTraining`='0', `cGymTraining`='0', `cStealVehicles`='0', `cPimpWhores`='0',
                    `cFamilyRaid`='0', `cFamilyCrimes`='0', `cBombardement`='0', `cTravelTime`='0', `cPimpWhoresFor`='0';
                UPDATE `user` SET `donatorID`='0' WHERE `donatorID`='1';
				UPDATE `user` SET `donatorID`='1' WHERE `donatorID`='5';
				UPDATE `user` SET `donatorID`='5' WHERE `donatorID`='10';
                TRUNCATE TABLE `user_captcha`;
                TRUNCATE TABLE `user_friend_block`;
                TRUNCATE TABLE `user_garage`;
                TRUNCATE TABLE `user_mission_carjacker`;
                TRUNCATE TABLE `user_residence`;
            ");
        }
    }
}
