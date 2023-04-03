<?PHP

declare(strict_types=1);

namespace install\config;

use app\config\Routing;
use install\config\InstallDAO;

class InstallService
{
    private $data;

    public function __construct($dbHost, $dbName, $dbUser, $dbPwd)
    {
        $this->data = new InstallDAO($dbHost, $dbName, $dbUser, $dbPwd);
    }

    public function __destruct()
    {
        $this->data = null;
    }
    
    static function is_name($i): bool
    {
        if(!preg_match("#^[A-Za-z0-9-]{3,15}$#s", $i))
            return FALSE;
        else
            return TRUE;
    }

    static function is_email($em): bool
    {
    	$ema = filter_var($em, FILTER_VALIDATE_EMAIL);
    	if(!$ema)
    		return FALSE;
    	else
    		return TRUE;
    }
    
    static function replaceLinesByLineNumbers($file, array $replacesMap): void
    {
        $lines = file($file);
        foreach(array_keys($lines) as $lineNumber)
            if(isset($replacesMap[$lineNumber]))
                $lines[$lineNumber] = $replacesMap[$lineNumber] . "\n";
        
        file_put_contents($file, $lines);
    }
    
    static function findAndReplaceInFile($file, $find, $replaceWith): void
    {
        if($find !== $replaceWith)
        {
            $contents = file_get_contents($file);
            $contents = str_replace($find, $replaceWith, $contents);
            file_put_contents($file, $contents);
        }
    }
    
    static function createCronjobs(): void
    {
        function cronjobExists($command)
        {
            $cronjobExists = false;
            exec('crontab -l', $crontab);
            if(isset($crontab) && is_array($crontab))
            {
                $crontab = array_flip($crontab);
                if(isset($crontab[$command]))
                    $cronjobExists = true;
            }
            return $cronjobExists;
        }
        
        $phpPath = PHP_BINARY;
        $hourFile = realpath(DOC_ROOT . '/app/cronjob/hour.php');
        $fiveMuntesFile = realpath(DOC_ROOT . '/app/cronjob/five_minutes.php');
        $oneMunteFile = realpath(DOC_ROOT . '/app/cronjob/one_minute.php');
        $dayFile = realpath(DOC_ROOT . '/app/cronjob/day.php');
        $weekFile = realpath(DOC_ROOT . '/app/cronjob/week.php');
        $dbbackupFile = realpath(DOC_ROOT . '/app/cronjob/dbbackup.php');
        $hourJob = '0 * * * * ' . $phpPath . ' ' . $hourFile;
        $fiveMuntesJob = '*/5 * * * * ' . $phpPath . ' ' . $fiveMuntesFile;
        $oneMunteJob = '* * * * * ' . $phpPath . ' ' . $oneMunteFile;
        $dayJob = '0 0 * * * ' . $phpPath . ' ' . $dayFile;
        $weekJob = '0 19 * * 0 ' . $phpPath . ' ' . $weekFile;
        $dbbackupJob = '0 4 * * * ' . $phpPath . ' ' . $dbbackupFile;
        
        if(!cronjobExists($hourJob))
            exec('echo -e "`crontab -l`\n'.$hourJob.'" | crontab -', $output);
        
        if(!cronjobExists($fiveMuntesJob))
            exec('echo -e "`crontab -l`\n'.$fiveMuntesJob.'" | crontab -', $output);
        
        if(!cronjobExists($oneMunteJob))
            exec('echo -e "`crontab -l`\n'.$oneMunteJob.'" | crontab -', $output);
        
        if(!cronjobExists($dayJob))
            exec('echo -e "`crontab -l`\n'.$dayJob.'" | crontab -', $output);
        
        if(!cronjobExists($weekJob))
            exec('echo -e "`crontab -l`\n'.$weekJob.'" | crontab -', $output);
        
        if(!cronjobExists($dbbackupJob))
            exec('echo -e "`crontab -l`\n'.$dbbackupJob.'" | crontab -', $output);
    }
    
    static function generateMasterEncryptionIvAndKey(): array
    {
        global $security;
        
        $ciphering = "AES-128-CTR";
        $ivLength = openssl_cipher_iv_length($ciphering);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $key = openssl_digest($security->randStr(), 'MD5', TRUE);
        return array('iv' => $iv, 'key' => $key);
    }
    
    public function validateInstall($post): array
    {
        global $security;
        
        $gameName = $_SESSION['install']['fields']['game_name'] = $security->xssEscape($post['game_name']);
        $domain = $_SESSION['install']['fields']['domain'] = $security->xssEscape($post['domain']);
        $dbHost = $_SESSION['install']['fields']['db_host'] = $security->xssEscape($post['db_host']);
        $dbName = $_SESSION['install']['fields']['db_name'] = $post['db_name'];
        $dbUser = $_SESSION['install']['fields']['db_user'] = $post['db_user'];
        $dbPwd = $post['db_pwd'];
        $username = $_SESSION['install']['fields']['username'] = $security->xssEscape($post['username']);
        $password = $post['password'];
        $passwordCheck = $post['password_check'];
        $email = $_SESSION['install']['fields']['email'] = $security->xssEscape($post['email']);
        
        $replacedDomain = preg_replace("#^[^:/.]*[:/]+#i", "", preg_replace("/www./i", "", $domain));
        
        $dbHostValid = false;
        if($dbHost != "")
        {
            $dbHost = preg_replace("#^[^:/.]*[:/]+#i", "", preg_replace("/www./i", "", $dbHost));
            $dbIp = gethostbyname($dbHost);
            
            if(filter_var($dbIp, FILTER_VALIDATE_IP))
                $dbHostValid = true;
            
            if(filter_var($dbHost, FILTER_VALIDATE_IP))
                $dbHostValid = true;
        }
        else
            $dbHostValid = true;
                    
        if($this->data->connected === false || $dbHostValid === false)
        {
            $error = "The database credentials you provided could not establish a connection.";
        }
        if(empty($gameName))
        {
            $error = "Enter a game name.";
        }
        if(empty($domain))
        {
            $error = "Enter a domain name.";
        }
        if(strpos(PROTOCOL . $_SERVER['HTTP_HOST'], $replacedDomain) === false)
        {
            $error = "The provided domain doesn't match the hosting domain. Make sure to visit this installer from your provided domain.";
        }
        if(empty($dbName))
        {
            $error = "Enter a database name.";
        }
        if(empty($dbUser))
        {
            $error = "Enter a database user.";
        }
        if(!self::is_name($username))
        {
    		$error = "You've entered an invalid username. Only letters, numbers or a hyphen character(-), minimal 1 letter. range between 3-15 characters.";
    	}
        if(strlen($password) < 5 || strlen($password) > 50)
        {
            $error = "Administrator password must range between 5 and 50 characters.";
        }
        if($password !== $passwordCheck)
        {
            $error = "Both administrator passwords didn't match.";
        }
        if(!self::is_email($email))
        {
    		$error = "You've entered an invalid email address!";
    	}
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = "Invalid security token, refresh this page and try again.";
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            
            $configFile = DOC_ROOT . '/app/config/config.php';
            $credentialsFile = DOC_ROOT . '/../credentials.php';
            $htaccessFile = DOC_ROOT . '/.htaccess';
            $configReplacesMap = $credentialsReplacesMap = $htaccessReplacesMap = array();
            
            if(!empty($gameName))
                $configReplacesMap[9] = 'define(\'APP_GAMENAME\',     "' . $gameName . '");          // Gamename, obviously';
            
            if(!empty($domain))
            {
                if(PROTOCOL === "https://")
                {
                    $htaccessReplacesMap[90] = '        RewriteCond %{HTTPS} off';
                    $htaccessReplacesMap[91] = '        RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]';
                    $htaccessReplacesMap[113] = '        RewriteCond %{REQUEST_URI} /favicon.ico [NC]';
                    $htaccessReplacesMap[114] = '        RewriteRule (.*) https://%{HTTP_HOST}/web/public/images/favicon.ico [R=301,L]';
                }
                if(strpos($domain, "www.") === false)
                {
                    $configReplacesMap[10] = 'define(\'APP_DOMAIN\',       BASE_DOMAIN);     // Application runs without www.';
                    $htaccessReplacesMap[93] = '    ## www to non www redirect';
                    $htaccessReplacesMap[94] = '    #RewriteCond %{HTTPS}s ^on(s)|off [NC]';
                    $htaccessReplacesMap[95] = '    RewriteCond %{HTTP_HOST} ^www\.(.+)$ [NC]';
                    $htaccessReplacesMap[96] = '    RewriteRule ^(.*)$ http%{ENV:ADDSSL}://%1/$1 [R=301,L]';
                }
                else
                {
                    $configReplacesMap[10] = 'define(\'APP_DOMAIN\',       "www.".BASE_DOMAIN);     // Application runs on www. variant';
                    $htaccessReplacesMap[93] = '    ## Non www to www redirect';
                    $htaccessReplacesMap[94] = '    RewriteCond %{HTTPS}s ^on(s)|off [NC]';
                    $htaccessReplacesMap[95] = '    RewriteCond %{HTTP_HOST} !^(static|www)\.(.*)$ [NC]';
                    $htaccessReplacesMap[96] = '    RewriteRule ^(.*)$ http%1://www.%{HTTP_HOST}/$1 [R=301,L]';
                }
                if(strpos(PROTOCOL . $_SERVER['HTTP_HOST'], $replacedDomain) !== false)
                {
                    $configReplacesMap[7] = 'define(\'BASE_DOMAIN\',      "' .  $replacedDomain . '");       // The primary domain';
                    $htaccessReplacesMap[77] = '    RewriteCond %{HTTP_REFERER} !^' . PROTOCOL . '(www\.)?' . $replacedDomain . '/.*$ [NC]';
                    $htaccessReplacesMap[142] = '    Header always set Content-Security-Policy "object-src \'none\'; script-src \'self\' https://fonts.googleapis.com https://www.gstatic.com https://www.google.com https://www.paypalobjects.com ' . PROTOCOL . 'static.' . $replacedDomain . ' \'unsafe-inline\' \'unsafe-eval\'"';
                }
            }
            
            if(!empty($dbHost) && $dbHostValid)
            {
                if(strpos($dbHost, ':'))
                    $dbHost = "[" . $dbHost . "]";
                
                $configReplacesMap[21] = 'define(\'PDO_CONSTRING\', "mysql:host=' . $dbHost . ';dbname=".PDO_DATABASE); // Db conection string DO NOT CHANGE';
            }
            else
                $configReplacesMap[21] = 'define(\'PDO_CONSTRING\', "mysql:host=localhost;dbname=".PDO_DATABASE); // Db conection string DO NOT CHANGE';
            
            if(!empty($dbName))
                $credentialsReplacesMap[3] = 'define(\'DBNAME\', "' . $dbName . '");';
            
            if(!empty($dbUser))
                $credentialsReplacesMap[4] = 'define(\'DBUSR\', "' . $dbUser . '");';
            
            if(!empty($dbPwd))
                $credentialsReplacesMap[5] = 'define(\'DBPWD\', "' . $dbPwd . '");';
            
            if(!empty($email) && self::is_email($email))
                $htaccessReplacesMap[58] = 'SetEnv SERVER_ADMIN ' . $email;
            
            $findProtocol = PROTOCOL == "https://" ? "http://" : "https://";
            $replaceProtocol = PROTOCOL == "https://" ? "https://" : "http://";
            self::findAndReplaceInFile(DOC_ROOT . '/sw.js', $findProtocol . "static.", $replaceProtocol . "static.");
            self::findAndReplaceInFile(DOC_ROOT . '/web/public/css/game.min.css', $findProtocol . "static.", $replaceProtocol . "static.");
            self::findAndReplaceInFile(DOC_ROOT . '/web/public/css/homepage.min.css', $findProtocol . "static.", $replaceProtocol . "static.");
            if($route->settings['domainBase'] !== $replacedDomain)
            {
                self::findAndReplaceInFile(DOC_ROOT . '/sw.js', $route->settings['domainBase'], $replacedDomain);
                self::findAndReplaceInFile(DOC_ROOT . '/web/public/css/game.min.css', $route->settings['domainBase'], $replacedDomain);
                self::findAndReplaceInFile(DOC_ROOT . '/web/public/css/homepage.min.css', $route->settings['domainBase'], $replacedDomain);
            }
            
            self::replaceLinesByLineNumbers($configFile, $configReplacesMap);
            self::replaceLinesByLineNumbers($credentialsFile, $credentialsReplacesMap);
            self::replaceLinesByLineNumbers($htaccessFile, $htaccessReplacesMap);
            self::createCronjobs();
            if(isset($dbName) && isset($dbUser) && isset($dbPwd))
            {
                $this->data->installFreshDatabase(file_get_contents(__DIR__ . '/clean-database.sql'));
                $this->data->registerAdministrator($username, $password, $email);
            }
            return Routing::successMessage("Success! Visit <a href='" . PROTOCOL . $_SERVER['HTTP_HOST'] . "' target='_blank'><strong>" . $_SERVER['HTTP_HOST'] . "</strong></a> and perform a hard refresh (CTRL+F5) to see if everything is working or <a href='" . PROTOCOL . $_SERVER['HTTP_HOST'] . "/install/'><strong>try again</strong></a>.");
        }
    }
}
