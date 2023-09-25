<?PHP

/** BE AWARE!
 * This security class may sometimes be not as secure as you think.
 * Please always advise the latest reference manuals on security & best-practices.
 * 
 * DISCLAIMER
 * Mafiasource 3 custom encryption is not done by cryptographic professionals.
 **/

/** Security class:
 * - Prevent XSS
 * - Avoid CSRF
 * - Securely generate randoms
 * - Captcha control
 * - SSL check when SSL enabled 
 * - Encryption, master = only file server | user = file server & database
 **/

namespace app\config;

use voku\helper\AntiXSS;

class Security
{
    private $masterIv = MASTERIV; // Master iv SECRET
    private $masterKey = MASTERKEY; // Master key SECRET
    
    protected $token;
    
    public function __construct()
    {
        if(!isset($_SESSION['security_token'])) $_SESSION['security_token'] = $this->createToken();
        $this->token = $_SESSION['security_token'];
    }
    
    public function generateNewSession()
    {
        $_SESSION['regenerate'] = true;
    }
    
    private function createToken()
    {
        return $this->token = $this->randStr(64);
    }
    
    public function generateNewToken()
    {
        $_SESSION['security_token'] = $this->createToken();
        $this->token = $_SESSION['security_token'];
    }
    
    public function getToken()
    {
        return $this->token;
    }
    
    public function checkToken($input)
    {
        if($input === $this->token)
            return TRUE;
        
        return FALSE;
    }
    
    public function randInt($min, $max)
    {
        return random_int($min, $max);
    }
    
    /* Generates a random hexadecimal string of length $len. Crypto secure with random_bytes() */
    public function randStr($len = 64)
    {
        if($len <= 0 || $len >= 256) $len = $this->randInt(6,255);
        
        $str = bin2hex(random_bytes(round($len/2)));
        return substr($str, 0, $len);
    }
    
    /* Generates a random case sensitive string of length $length. Crypto secure with random_int() */
    public function randCaseSensitiveStr($length = 64, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        if($length >= 1)
        {
            $pieces = [];
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; ++$i)
                $pieces []= $keyspace[random_int(0, $max)];
            
            return implode('', $pieces);
        }
        return false;
    }
    
    public function createSalt()
    {
        return substr($this->randStr(32), 0, $this->randInt(12,32));
    }
    
    public function xssEscape($input)
    {
        $output = htmlspecialchars(strip_tags($input));
        return $output;
    }
    
    public function xssEscapeAndHtml($input)
    {
        // https://github.com/voku/anti-xss
        $antiXss = new AntiXSS();
        $antiXss->removeEvilAttributes(array('style'));
        $harmless_html = $antiXss->xss_clean($input);
        if($antiXss->isXssFound())
            error_log('XSS Attempt logged: ' . $input);
        
        return $harmless_html;
    }
    
    public function addToCaptchaCount($num = false)
    {
        if(!isset($_SESSION['captcha_security'])) $_SESSION['captcha_security'] = 0;
        if($num)
            $_SESSION['captcha_security'] += $num;
        else
            $_SESSION['captcha_security'] += 1;
    }
    
    public function resetCaptchaCount()
    {
        $_SESSION['captcha_security'] = 0;
    }
    
    public function checkCaptcha($num = false, $userCS = false)
    {
        $num = isset($num) && is_numeric($num) ? (int)round($num) : 5;
        if($num !== false && $userCS !== false)
        {
            if($userCS >= $num)
                return TRUE;
        }
        else
        {
            if(isset($_SESSION['captcha_security']) && $_SESSION['captcha_security'] >= $num)
                return TRUE;
        }
        return FALSE;
    }
    
    public function checkSSL()
    {
        global $route;
        if($route->settings['ssl'] === true)
        {
            if((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== "off") || $_SERVER['SERVER_PORT'] == 443)
                return true;
            
        	return false;
        }
        else
            return true;
    }
    
    /* Only to be used for non database saved encryptions so we can check for existance efficiently for example email existence check! */
    public function masterEncrypt($str)
    {
        $str = (string)$str;
        $ciphering = "AES-128-CTR";
        $options = 0;
        $encryption = base64_encode(openssl_encrypt($str, $ciphering, $this->masterKey, $options, $this->masterIv));
        
        return $encryption;
    }
    
    public function masterDecrypt($encryption)
    {
        $ciphering = "AES-128-CTR"; 
        $options = 0;
        $decryption = openssl_decrypt(base64_decode($encryption), $ciphering, $this->masterKey, $options, $this->masterIv);
        
        return $decryption;
    }
    
    /* Encrypt sensitive user data */
    public function storeEncryptionIvAndKey($saveDir, $iv, $key)
    {
        if (!file_exists($saveDir))
            mkdir($saveDir, 0755, true);
        
        $ivFileName = $saveDir . "iv.txt";
        if(file_exists($ivFileName)) unlink($ivFileName);
        $ivFileHandle = fopen($ivFileName, 'w') or null;
        if($ivFileHandle)
            fwrite($ivFileHandle, base64_encode($iv));
        
        fclose($ivFileHandle);
        chmod($ivFileName, 0600);
            
        $keyFileName = $saveDir . "key.txt";
        if(file_exists($keyFileName)) unlink($keyFileName);
        $keyFileHandle = fopen($keyFileName, 'w') or null;
        if($keyFileHandle)
            fwrite($keyFileHandle, base64_encode($key));
        
        fclose($keyFileHandle);
        chmod($keyFileName, 0600);
    }
    
    public function encrypt($str)
    {
        $str = (string)$str;
        $ciphering = "AES-128-CTR";
        $ivlen = openssl_cipher_iv_length($ciphering);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $key = openssl_digest($this->randStr(), 'MD5', TRUE);
        $encryption = openssl_encrypt($str, $ciphering, $key, OPENSSL_RAW_DATA, $iv);
        return array('encryption' => base64_encode($encryption), 'iv' => $iv, 'key' => $key);
    }
    
    /* Decrypt sensitive user data */
    public function grabEncryptionIvAndKey($saveDir)
    {
        $iv = $key = "";
        $ivFileName = $saveDir . "iv.txt";
        $ivFile = fopen($ivFileName, 'r') or null;
        if($ivFile)
            $iv = file_get_contents($ivFileName);
        
        fclose($ivFile);
        
        $keyFileName = $saveDir . "key.txt";
        $keyFile = fopen($keyFileName, 'r') or null;
        if($keyFile)
            $key = file_get_contents($keyFileName);
        
        fclose($keyFile);
        
        return array('iv' => base64_decode($iv), 'key' => base64_decode($key));
    }
    
    public function decrypt($encryption, $iv, $key)
    {
        $encryption = base64_decode($encryption);
        $ciphering = "AES-128-CTR";
        $hmac = hash_hmac('sha256', $encryption, $key, true);
        $ciphertext = base64_encode($iv. $hmac . $encryption);
        $check = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($ciphering);
        $iv = substr($check, 0, $ivlen);
        $hmac = substr($check, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($check, $ivlen + $sha2len);
        $decryption = openssl_decrypt($ciphertext_raw, $ciphering, $key, OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, true);
        if(hash_equals($hmac, $calcmac))
        {
            return $decryption;
        }
    }
}
