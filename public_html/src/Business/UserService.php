<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\UserCoreService;
use src\Business\GarageService;
use src\Business\StateService;
use src\Business\PossessionService;
use src\Business\NotificationService;
use src\Business\DailyChallengeService;
use src\Business\PublicMissionService;
use src\Business\Logic\UploadService;
use src\Data\UserDAO;

class UserService
{
    private $data;

    public $maxLogin24h = 20; // The amount of unsuccessful attempts an IP address can login within 24 hours before receiving 72 hour IP ban.
    public $minLogin24h = 5; // The amount of unsuccessful attempts an IP address can login within 24 houts before receiving a warning.
    public $healCostsPercent = 2500; // Hospital heal costs / each percent damage.
    public $creditsChance = 25; // <= 25 1 in 4 if creditsChanceRand === 100
    public $creditsChanceRand = 100; // rand(1, [100])
    public $creditsWon = 0; // Init
    public $tryAgainMessage = ""; // Init
    public $ipValid = false; // Init
    public $unavailableUsernames = array("Guest", "Gast", "Webmaster", "Admin", "Moderator", "Helpdesk", "None", "Geen");

    public function __construct()
    {
        global $security;
        global $lang;
        global $user;
        $this->data = new UserDAO();
        $this->creditsChanceRand = $security->randInt(1, (int)$this->creditsChanceRand);
        $this->creditsWon = $security->randInt(2, 4);
        $this->tryAgainMessage = $lang == "en" ? "Try again later." : "Probeer later opnieuw.";
        $this->ipValid = $user->ipValid;
        /* Double credits sample: */
        if(strtotime("2021-12-07 14:00:00") < strtotime('now') && strtotime("2021-12-10 14:00:00") > strtotime('now'))
        {
            $this->creditsChance *= 2;
            $this->creditsWon *= 2;
        }
    }

    public function __destruct()
    {
        $this->data = null;
    }

    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }

    static function is_name($i){
        if(!preg_match("#^[A-Za-z0-9-]{3,15}$#s", $i))
            return FALSE;
        
        return TRUE;
    }

    static function is_email($em){
    	$ema = filter_var($em, FILTER_VALIDATE_EMAIL);
    	if(!$ema)
    		return FALSE;
    	
   		return TRUE;
    }

    public function checkUsernameExists($username)
    {
        $nameSet = $this->data->checkUsername($username);
        if($nameSet->rowCount() == 0 && $this->data->getIdByPrivateID($username) == FALSE)
            return "404";
        
        return TRUE;
    }
    
    private function getLoginAttemptsMessage($langs = array())
    {
        $ipAddr = UserCoreService::getIP();
        $tempBan = $this->data->checkTempBannedIP($ipAddr);
        if($tempBan)
            return $langs['TEMPORARILY_IP_BANNED'] . " ";

        global $route;
        $lfc = $this->data->getLoginFailedCountByIP($ipAddr);
        if($lfc >= $this->minLogin24h && $lfc < $this->maxLogin24h)
        {
            $incPossibleSuccess = (int)($this->maxLogin24h - 1) - $lfc;
            $replace = $incPossibleSuccess !== 0 ? $incPossibleSuccess : strtolower($langs['NONE']);
            return $route->replaceMessagePart($replace, $langs['LOGIN_FAILED_WARNING'], '/{attempts}/') . " ";
        }

        return ""; // Nothing to prepend to message
    }

    public function validateLogin($post, $captcha = false)
    {
        if(!in_array($_SERVER['REMOTE_ADDR'], DEVELOPER_IPS))
            return "In development. Visit <a href=https://www.mafiasource.nl><strong>www.mafiasource.nl</strong></a> instead.";
        
        global $security;
        global $language;
        global $langs;
        global $user;
        $l = $language->loginLangs();
        $username = $security->xssEscape($post['username']);
        $pass = $post['password'];
        $code = isset($post['captcha_code']) ? (int)$post['captcha_code'] : null;
        $_SESSION['login-tries'] = !isset($_SESSION['login-tries']) ? 1 : $_SESSION['login-tries']++;
        $permBan = $user->checkPermBannedIP(UserCoreService::getIP());
        // $type 1=Credentials | 2=Violation | 3=Warning | 4=Temp. IP Ban | 5=Perm. IP Ban
        $type = $permBan ? 5 : 2;
        $l['NONE'] = $langs['NONE'];
        $laMsg = $this->getLoginAttemptsMessage($l);
        if($laMsg !== "") // Default LOGIN_FAILED_WARNING | Type 3
            $type = 3;
        
        if($laMsg == $l['TEMPORARILY_IP_BANNED'] . " ")
            $type = $permBan ? 5 : 4;
        
        if($security->checkToken($post['security-token']) ==  FALSE || !$this->ipValid)
            $return = $langs['INVALID_SECURITY_TOKEN']; // Violation | Type 2
        
        if($captcha == true && (!isset($_SESSION['code_captcha']) || $_SESSION['code_captcha'] != $code))
            $return = $langs['WRONG_CAPTCHA']; // Violation | Type 2
        
        if(in_array($type, array(4, 5)) || $permBan)
            $return = $l['TEMPORARILY_IP_BANNED'] . " "; // Type 4 & 5
        
        if(isset($return))
        {
            $this->data->loginFailed($username, $type);
            return $laMsg === $return ? $laMsg : $laMsg . $return;
        }
        
        $id = $this->data->verifyLoginGetIdOnSuccess($username, $pass);
        if($id == FALSE)
        {
            $this->data->loginFailed($username, 1); // Credentials | Type 1
            return $laMsg . $l['WRONG_USERNAME_OR_PASS'];
        }
        
        $this->data->loginUser($username, $id);
        return TRUE;
    }

    public function validateRegister($post)
    {
        if(!in_array($_SERVER['REMOTE_ADDR'], DEVELOPER_IPS))
            return "In development. Visit <a href=https://www.mafiasource.nl><strong>www.mafiasource.nl</strong></a> instead.";
        
        global $security;
        global $language;
        global $langs;
        $l = $language->registerLangs();
        $username = $security->xssEscape($post['username']);
    	$mailadres = $security->xssEscape($post['email']);
    	$pass = $post['password'];
    	$pass_check = $post['password_check'];
    	//$code = (int)$post['captcha_code'];
        $profession = (int)$post['type'];

    	if(!self::is_name($username))
        {
    		$error = $l['INVALID_USERNAME'];
    	}
    	if(!self::is_email($mailadres))
        {
    		$error = $l['INVALID_EMAIL'];
    	}
    	if(strlen($pass) < 5 || strlen($pass) > 50)
        {
    		$error = $l['INVALID_PASS'];
    	}
    	if($pass != $pass_check)
        {
    		$error = $l['PASSES_DONT_MATCH'];
    	}
        if($profession < 1 || $profession > 6)
        {
    		$error = $l['INVALID_PROFESSION'];
    	}
        if(isset($_SESSION['UID']))
        {
            $error = $l['ALREADY_REGISTERED'];
        }
        
    	$nameExists = $this->checkUsernameExists($username);
    	$emailSet = $this->data->checkEmail($mailadres);
    	if($nameExists === TRUE || in_array(ucfirst(strtolower($username)), $this->unavailableUsernames))
        {
    		$error = $l['USERNAME_TAKEN'];
    	}
    	if($emailSet->rowCount() == 1)
        {
    		$error = $l['EMAIL_TAKEN'];
    	}
        
        if(!$this->ipValid)
        {
            $error = $langs['INVALID_SECURITY_TOKEN']; 
        }
        
        $isRg      = $this->data->checkIPRegistered(UserCoreService::getIP());
    	$isRegged  = is_object($isRg) ? $isRg->rowCount() : 0;
    	if($isRegged >= 1)
        {
    		$error = $l['ALREADY_REGISTERED'];
    	}
        /* Block majority of proxies sample:
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) || isset($_SERVER['HTTP_X_FORWARDED']) || isset($_SERVER['HTTP_FORWARDED_FOR']) || isset($_SERVER['HTTP_CLIENT_IP']) ||
            isset($_SERVER['HTTP_VIA'])
        )
        {
            $error = $l['ALREADY_REGISTERED'];
        }
        *
        if(!isset($_SESSION['code_captcha']) || $_SESSION['code_captcha'] != $code)
        {
    		$error = $langs['WRONG_CAPTCHA'];
    	}
        */
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN']; 
        }

        if(isset($error))
        {
            $_SESSION['register']['username'] = $username;
            $_SESSION['register']['email'] = $mailadres;
            $_SESSION['register']['type'] = $profession;
            return $error;
        }
        $this->createUser($username, $pass, $mailadres, $profession);
        return TRUE;
    }
    
    public function validateRestInPeace($post)
    {
        global $security;
        global $userData;
        global $language;
        global $langs;
        $l = $language->restInPeaceLangs();
        $username = $security->xssEscape($post['username']);
        $profession = (int)$post['profession'];
        
    	if(!self::is_name($username))
        {
    		$error = $l['INVALID_USERNAME'];
    	}
        if($profession < 1 || $profession > 6)
        {
    		$error = $l['INVALID_PROFESSION'];
    	}
        
    	$nameExists = $username != $userData->getUsername() ? $this->checkUsernameExists($username) : null;
        
    	if($nameExists === TRUE  || in_array(ucfirst(strtolower($username)), $this->unavailableUsernames))
        {
    		$error = $l['USERNAME_TAKEN'];
    	}
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }

        if(isset($error))
        {
            $_SESSION['rip']['username'] = $username;
            $_SESSION['rip']['profession'] = $profession;
            return $error;
        }
        $this->data->resetDeadUser($username, $profession);
        return TRUE;
    }

    public function validateRecoverPassword($post)
    {
        if(!in_array($_SERVER['REMOTE_ADDR'], DEVELOPER_IPS))
            return "In development. Visit <a href=https://www.mafiasource.nl><strong>www.mafiasource.nl</strong></a> instead.";
        
        global $security;
        global $language;
        global $langs;
        $l = $language->recoverPasswordLangs();
        if(isset($post['username']) && $post['username'] != "") $username = $security->xssEscape($post['username']);
        if(isset($post['email']) && $post['email'] != "") $email = $security->xssEscape($post['email']);
        $code = (int)$post['captcha_code'];
        
        $nameSet = isset($username) ? $this->data->checkUsername($username) : null;
        $emailSet = isset($email) ? $this->data->checkEmail($email) : null;
        
        if(isset($username) && !empty($username) && isset($nameSet) && $nameSet->rowCount() == 0)
        {
            $error = $l['INVALID_USERNAME'];
        }
        elseif(isset($email) && !empty($email) && isset($emailSet) && $emailSet->rowCount() == 0)
        {
    		$error = $l['INVALID_EMAIL'];
    	}
        elseif(empty($username) && empty($email))
        {
            $error = $l['INVALID_USERNAME'];
        }
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
            return $error;
        }
        $n = isset($nameSet) ? $nameSet->fetch() : null;
        $e = isset($emailSet) ? $emailSet->fetch() : null;
        if((isset($n['id']) && $this->data->isPasswordInRecovery($n['id'])) || (isset($e['id']) && $this->data->isPasswordInRecovery($e['id'])))
            return $this->tryAgainMessage;
        
        if(isset($username) && isset($n))
        {
            if($this->data->recoverPassword($n['id'], $username, $n['email']))
                return TRUE;
        }
        elseif(isset($email) && isset($e))
        {
            if($this->data->recoverPassword($e['id'], $e['username'], $email))
                return TRUE;
        }
        return FALSE;
    }
    
    public function validateNewRecoveredPassword($post, $recoverPasswordData)
    {
        global $security;
        global $language;
        global $langs;
        $l = $language->registerLangs();
    	$pass = $post['new_password'];
    	$pass_check = $post['new_password_check'];
    	$code = (int)$post['captcha_code'];
        
    	if(strlen($pass) < 5 || strlen($pass) > 50)
        {
    		$error = $l['INVALID_PASS'];
    	}
    	if($pass != $pass_check)
        {
    		$error = $l['PASSES_DONT_MATCH'];
        }
        if(is_object($recoverPasswordData) && !$recoverPasswordData->getUsername())
        {
            $error = "We were unable to fetch your user data, please contact a Moderator for assistance.";
        }
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
            return $error;
        }
        $this->data->changePasswordByUsername($pass, $recoverPasswordData->getUsername());
        return TRUE;
    }
    
    public function recoverPasswordDeactivatePrivateID($uid = false)
    {
        $this->data->removeRecoverPasswordByUserID($uid);
        $this->data->deactivatePrivateID($uid);
    }

    public function validateEmailChange($post, $changeEmailData, $captcha = false)
    {
        global $security;
        global $language;
        global $langs;
        $l = $language->loginLangs();
        if(is_object($changeEmailData)) $username = $changeEmailData->getUsername();
        $pass     = $post['password'];
        if(isset($post['captcha_code'])) $code     = (int)$post['captcha_code'];
        
        if($username) $id = $this->data->verifyLoginGetIdOnSuccess($username, $pass);
        if(!isset($id) || (isset($id) && $id == FALSE))
        {
            $error = $l['WRONG_USERNAME_OR_PASS'];
        }
        if(is_object($changeEmailData) && !$changeEmailData->getUsername())
        {
            $error = "We were unable to fetch your user data, please contact a Moderator for assistance.";
        }
        if($captcha == true && (!isset($_SESSION['code_captcha']) || $_SESSION['code_captcha'] != $code))
        {
    		$error = $langs['WRONG_CAPTCHA'];
    	}
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        
        if(isset($error))
        {
            return $error;
        }
        $this->data->changeEmail($changeEmailData);
        return TRUE;
    }

    public function searchPlayer($post)
    {
        global $security;
        global $language;
        global $langs;
        $l = $language->onlineToplistLangs();
        $keyword = $security->xssEscape($post['search']);
        
        $searchData = $this->data->searchPlayersByKeyword($keyword);

        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($searchData == FALSE || strlen($keyword) < 3 || strlen($keyword) > 15)
        {
            $error = $l['NO_PLAYERS_FOUND'];
        }

        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        $successMsg = Routing::successMessage($l['USER_SEARCH_SUCCESSFUL']);
        return array('msg' => $successMsg, 'data' => $searchData);
    }

    public function searchPlayerByRank($post)
    {
        global $security;
        global $language;
        global $langs;
        $l = $language->onlineToplistLangs();
        $rank = $security->xssEscape($post['search-rank']);
        
        $searchData = $this->data->searchPlayersByRank($rank);

        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(!isset($searchData) || $searchData == FALSE)
        {
            $error = $l['NO_PLAYERS_FOUND'];
        }

        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        $successMsg = Routing::successMessage($l['USER_SEARCH_SUCCESSFUL']);
        return array('msg' => $successMsg, 'data' => $searchData);
    }

    public function donateMoneyToUser($post)
    {
        global $userData;
        global $security;
        global $language;
        global $langs;
        $l = $language->bankLangs();
        $post['amount'] = (int)$post['amount'];
        $post['message'] = $security->xssEscape($post['message']);
        $code = (int)$post['captcha_code'];
        
        $receiverProfile = $this->data->getUserProfile($post['receiver']);
        if(is_object($receiverProfile)) $receiverID = $receiverProfile->getId() ? $receiverProfile->getId() : FALSE;
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(!isset($_SESSION['code_captcha']) || $_SESSION['code_captcha'] != $code)
        {
    		$error = $langs['WRONG_CAPTCHA'];
    	}
        if($post['amount'] < 100 || $post['amount'] >999999999)
        {
            $error = $langs['BETWEEN_100_AND_999M'];
        }
        if($userData->getBank() < $post['amount'])
        {
            $error = $langs['NOT_ENOUGH_MONEY_BANK'];
        }
        if((isset($receiverID) && is_bool($receiverID) && $receiverID == FALSE ) || !is_object($receiverProfile))
        {
            $error = $langs['PLAYER_DOESNT_EXIST'];
        }
        if(isset($receiverID) && !is_bool($receiverID) && $_SESSION['UID'] == $receiverID)
        {
            $error = $l['CANNOT_SEND_MONEY_SELF'];
        }

        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        global $route;
        $possession = new PossessionService();
        $possessionId = 3; //Mafiasource bank | Possession logic
        $possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID()); // Possess table record id
        $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
        
        $transactionPercent = 5;
        if($receiverProfile->getDonatorID() == 1)
            $transactionPercent = 4;
        elseif($receiverProfile->getDonatorID() == 5)
            $transactionPercent = 3;
        
        $this->data->donateMoneyToUser($post['amount'], $transactionPercent, $post['receiver'], $post['message'], $pData);
        
        $replaces = array(
            array('part' => number_format($post['amount'], 0, '', ','), 'message' => $l['DONATE_MONEY_TO_USER'], 'pattern' => '/{amount}/'),
            array('part' => $receiverProfile->getUsername(), 'message' => FALSE, 'pattern' => '/{username}/'),
            array('part' => $transactionPercent, 'message' => FALSE, 'pattern' => '/{transactionPercent}/')
        );
        
        $replacedMessage = $route->replaceMessageParts($replaces);
        return Routing::successMessage($replacedMessage);
    }

    public function transferMoney($post)
    {
        global $userData;
        global $security;
        global $language;
        global $langs;
        $l = $language->bankLangs();
        $post['amount'] = (int)$post['amount'];
        $actions = array("getMoney", "putMoney");
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($post['amount'] <= 0 || $post['amount'] > 999999999)
        {
            $error = $langs['BETWEEN_1_AND_999M'];
        }
        if(!in_array($post['action'], $actions))
        {
            $error = $l['INVALID_ACTION'];
        }
        if($userData->getBank() < $post['amount'] && $post['action'] == 'getMoney')
        {
            $error = $langs['NOT_ENOUGH_MONEY_BANK'];
        }
        if($userData->getCash() < $post['amount'] && $post['action'] == 'putMoney')
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        $this->data->transferMoney($post['amount'], $post['action']);
        global $route;
        global $lang;
        $to = "contant";
        if($lang == 'en') $to = "pocket";
        if($post['action'] == 'putMoney')
        {
            $to = "bank";
            if($lang == 'en') $to .= " account";
        }
        $replacedMessage = $route->replaceMessagePart(number_format($post['amount'], 0, '', ','), $l['TRANSFER_MONEY_SUCCESS'], '/{amount}/');
        $replacedMessage = $route->replaceMessagePart($to, $replacedMessage, '/{action}/');
        return Routing::successMessage($replacedMessage);
    }

    public function transferSwissMoney($post)
    {
        global $userData;
        global $security;
        global $language;
        global $langs;
        $l = $language->bankLangs();
        $post['amount'] = (int)$post['amount'];
        $actions = array("getMoney", "putMoney");
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($post['amount'] <= 0 || $post['amount'] > 999999999)
        {
            $error = $langs['BETWEEN_1_AND_999M'];
        }
        if(!in_array($post['action'], $actions))
        {
            $error = $l['INVALID_ACTION'];
        }
        if($this->data->getBankPageInfo('nl')->getSwissBank() < $post['amount'] && $post['action'] == 'getMoney')
        {
            $error = $l['NOT_ENOUGH_MONEY_SWISS'];
        }
        if($userData->getBank() < $post['amount'] && $post['action'] == 'putMoney')
        {
            $error = $langs['NOT_ENOUGH_MONEY_BANK'];
        }
        if((($this->data->getBankPageInfo('nl')->getSwissBank() + $post['amount']) > $this->data->getBankPageInfo('nl')->getSwissBankMax())  && $post['action'] == 'putMoney')
        {
            $error = $l['SWISS_BANK_FULL'];
        }
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        global $userData;
        $possession = new PossessionService();
        $possessionId = 3; //Mafiasource bank | Possession logic
        $possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID()); // Possess table record id
        $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
        
        $this->data->transferSwissMoney($post['amount'], $post['action'], $pData);
        global $route;
        global $lang;
        $to = "zwitserse bank";
        if($lang == 'en') $to = "swiss bank";
        if($post['action'] == 'getMoney')
        {
            $to = "bank";
            if($lang == 'en') $to .= " account";
        }
        $replacedMessage = $route->replaceMessagePart(number_format($post['amount'], 0, '', ','), $l['TRANSFER_MONEY_SUCCESS'], '/{amount}/');
        $replacedMessage = $route->replaceMessagePart($to, $replacedMessage, '/{action}/');
        return Routing::successMessage($replacedMessage);
    }

    public function changeAccountSettings($post, $files)
    {
        global $route;
        global $security;
        global $language;
        global $langs;
        global $userData;
        $l = $language->settingsLangs();
        $r = $language->registerLangs();
        if(isset($post['email'])) $post['email'] = $security->xssEscape($post['email']);
        if(isset($post['profile'])) $post['profile'] = $security->xssEscapeAndHtml($post['profile']);

        if($security->checkToken($post['security-token']) === FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        elseif(isset($post['email-change']) && isset($post['email']))
        {
            $emailSet = $this->data->checkEmail($post['email']);
            $check = $emailSet->fetch();
            if($this->data->verifyValidOwner() === FALSE)
            {
                $error = $l ['EMAIL_UNKNOWN_IP_DETECTED'];
            }
            elseif(!self::is_email($post['email']))
            {
                $error = $r['INVALID_EMAIL'];
            }
            elseif(isset($check['id']) && $check['id'] == $_SESSION['UID'])
            {
                $error = $l['SAME_EMAIL_NO_CHANGE'];
            }
            elseif($emailSet->rowCount() == 1)
            {
        		$error = $r['EMAIL_TAKEN'];
        	}
            elseif($this->data->isPrivateIDActive())
            {
                $error = $l['CHANGE_EMAIL_DEACTIVATE_PRIVATEID'];
            }
            elseif($this->data->isEmailInChange($userData->getId()))
            {
                $error = $this->tryAgainMessage;
            }
            /* Developer working on a piece of action, response based code sample:
            elseif($userData->getId() != 1)
            {
                $error = "Temporarily offline due to changes.";
            }
            */
            else
            {
                if(!isset($error))
                {
                    if($this->data->setNewEmailRequest($post['email']))
                    {
                        $coveredEmail = $this->getCoveredEmailByUsername($userData->getUsername());
                        $replacedMessage = $route->replaceMessagePart($coveredEmail, $l['CHANGE_EMAIL_NEED_TO_VERIFY'], '/{coveredEmail}/');
                        $success = $replacedMessage;
                    }
                }
            }
        }
        elseif(isset($post['testament-change']) && isset($post['testament']))
        {
            $check = $this->data->checkUsername($post['testament'])->fetch();
            $fetchID = isset($check['id']) ? $check['id'] : null;
            if(isset($fetchID))
            {
                if($fetchID == $_SESSION['UID'])
                {
                    $error = $l['TESTAMENT_NOT_FOR_OWN'];
                }
                else
                {
                    if(!isset($error))
                    {
                        if($this->data->changeTestament($post['testament']))
                        {
                            $replacedMessage = $route->replaceMessagePart($post['testament'], $l['CHANGE_TESTAMENT_SUCCESS'], '/{username}/');
                            $success = $replacedMessage;
                        }
                    }
                }
            }
            else
            {
                $error = $langs['PLAYER_DOESNT_EXIST'];
            }
        }
        elseif(isset($post['avatar-upload']) && isset($files['avatar']) && $files['avatar']['error']== UPLOAD_ERR_OK)
        {
            $saveDir = DOC_ROOT . '/web/public/images/users/'.$_SESSION['UID'];
            $nameForFile = $userData->getUsername().'_avatar';
            
            $upload = new UploadService($files, 'avatar', $saveDir, $nameForFile, '100');
            $res = $upload->response;
            $newFileName = $upload->uploadedFileName;
            
            if(is_array($res))
            {
                if(isset($res['error']))
                {
                    switch((int)$res['error'])
                    {
                        case 3:
                            $error = $l['UPLOAD_AVATAR_WRONG_FILE'];
                            break;
                        case 5:
                            $error = $l['UPLOAD_AVATAR_FAILED'];
                            break;
                        default:
                            $error = $res['error'];
                            break;
                    }
                }
                else if(isset($res['success']))
                {
                    if($res['success'] === 1)
                        $this->data->updateAvatar($newFileName);
                        $success = $l['UPLOAD_AVATAR_SUCCESS'];
                }
            }
        }
        elseif(isset($post['profile-save']) && isset($post['profile']))
        {
            $maxlength = 1000;
            if($userData->getDonatorID() == 1)
                $maxlength = 1500;
            elseif($userData->getDonatorID() == 5)
                $maxlength = 2000;
            elseif($userData->getDonatorID() == 10)
                $maxlength = 3000;
            
            if(strlen(strip_tags($post['profile'])) > $maxlength)
            {
                $error = $route->replaceMessagePart(number_format($maxlength, 0, '', ','), $l['PROFILE_TO_LONG'], '/{max}/');
            }
            else
            {
                $this->data->updateProfile($post['profile']);
                $success = $l['UPDATE_PROFILE_SUCCESS'];
            }
        }
        elseif(isset($post['password-change']) && isset($post['old_pass']) && isset($post['new_pass']) && isset($post['new_pass_confirm']))
        {
            if($this->data->verifyValidOwner() === FALSE)
            {
                $error = $l['PASSWORD_UNKNOWN_IP_DETECTED'];
            }
            elseif($post['new_pass'] !== $post['new_pass_confirm'])
            {
                $error = $l['PASSWORDS_DONT_MATCH'];
            }
            elseif($this->data->verifyPassword($post['old_pass']) == FALSE)
            {
                $error = $l['OLD_PASSWORD_INCORRECT'];
            }
            elseif(strlen($post['new_pass']) < 5)
            {
                $error = $l['INVALID_NEW_PASS'];
            }
            else
            {
                $this->data->changePassword($post['new_pass']);
                $success = $l['PASSWORD_CHANGE_SUCCESS'];
            }
        }
        elseif( isset($post['activate-privateid']) && isset($post['privateid_pass']) && isset($post['privateid-grade']) &&
            $post['privateid-grade'] >= 1 && $post['privateid-grade'] <= 3)
        {
            if($this->data->verifyValidOwner() === FALSE)
            {
                $error = $l['PRIVATEID_UNKNOWN_IP_DETECTED'];
            }
            elseif($this->data->verifyPassword($post['privateid_pass']) == FALSE)
            {
                $error = $l['OLD_PASSWORD_INCORRECT'];
            }
            elseif($this->data->isPrivateIDActive() === TRUE)
            {
                $error = $l['PRIVATEID_ALREADY_ACTIVE'];
            }
            else
            {
                global $twig;
                $pid = $this->data->generatePrivateID((int)$post['privateid-grade']);
                $success = $route->replaceMessagePart($pid, $l['ACTIVATE_PRIVATEID_SUCCESS'], '/{pid}/');
                $success .= $twig->render("/src/Views/game/js/privateid.toggle.twig", array(
                    'privateID' => "active",
                    'deactivate' => $l['DEACTIVATE']
                ));
            }
        }
        elseif(isset($post['deactivate-privateid']) && isset($post['privateid']))
        {
            if($this->data->verifyValidOwner() === FALSE)
            {
                $error = $l['PRIVATEID_UNKNOWN_IP_DETECTED'];
            }
            elseif($this->data->verifyPrivateID($post['privateid']) === FALSE)
            {
                $error = $l['PRIVATEID_INCORRECT'];
            }
            elseif($this->data->isPrivateIDActive() === FALSE)
            {
                $error = $l['PRIVATEID_NOT_ACTIVE'];
            }
            else
            {
                global $twig;
                $pid = $this->data->deactivatePrivateID();
                $success = $l['DEACTIVATE_PRIVATEID_SUCCESS'];
                $success .= $twig->render("/src/Views/game/js/privateid.toggle.twig", array(
                    'privateID' => "inactive",
                    'deactivate' => $l['DEACTIVATE'] . " (" . $l['NOT_ACTIVE'] . ")",
                    'langs' => $l
                ));
            }
        }

        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        if(isset($success))
        {
            return Routing::successMessage($success);
        }
    }

    public function getExchangeListHP($lang)
    {
        $cash = "contant";
        $rankPoints = "rankpunten";
        $bullets = "kogels";
        $hoes = "straat hoeren";
        if($lang === 'en')
        {
            $cash = "cash";
            $rankPoints = "rank points";
            $bullets = "bullets";
            $hoes = "street hoe's";
        }
        $dbValCash = "cash";
        $dbValRankPoints = "rankpoints";
        $dbValBullets = "bullets";
        $dbValStreetHoes = "whoresStreet";
        $list = array(
            "5" => array("hp" =>"5", "val" => "250000", "what" => $cash, "whatRaw" => $dbValCash),
            "10" => array("hp" =>"10", "val" => "3", "what" => $rankPoints, "whatRaw" => $dbValRankPoints),
            "25" => array("hp" =>"25", "val" => "1500", "what" => $bullets, "whatRaw" => $dbValBullets),
            "50" => array("hp" =>"50", "val" => "300", "what" => $hoes, "whatRaw" => $dbValStreetHoes),
            "100" => array("hp" =>"100", "val" => "7500000", "what" => $cash, "whatRaw" => $dbValCash),
            "200" => array("hp" =>"200", "val" => "66", "what" => $rankPoints, "whatRaw" => $dbValRankPoints),
            "500" => array("hp" =>"500", "val" => "33500", "what" => $bullets, "whatRaw" => $dbValBullets),
            "1000" => array("hp" =>"1000", "val" => "6500", "what" => $hoes, "whatRaw" => $dbValStreetHoes)
        );
        return $list;
    }

    public function getSendListHP()
    {
        $list = array(
            "5" => 5,
            "10" => 10,
            "20" => 20,
            "50" => 50,
            "100" => 100,
            "200" => 200,
            "500" => 500,
            "1000" => 1000
        );
        return $list;
    }

    public function exchangeHonorPoints($post)
    {
        global $userData;
        global $security;
        global $language;
        global $langs;
        $l = $language->honorPointsLangs();
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(!array_key_exists($post['exchange-amount'], $this->getExchangeListHP('nl')))
        {
            $error = $l['NO_EXISTING_REWARD'];
        }
        if($userData->getHonorPoints() < $post['exchange-amount'])
        {
            $error = $l['NOT_ENOUGH_HONOR_POINTS'];
        }
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        global $route;
        global $lang;
        $this->data->exchangeHonorPoints($this->getExchangeListHP($lang)[$post['exchange-amount']]);
        $replacedMessage = $route->replaceMessagePart(number_format($post['exchange-amount'], 0, '', ','), $l['EXCHANGE_HONOR_POINTS_SUCCESS'], '/{exchangeAmount}/');
        $replacedMessage = $route->replaceMessagePart(number_format($this->getExchangeListHP($lang)[$post['exchange-amount']]['val'], 0, '', ','), $replacedMessage, '/{exchangedValue}/');
        $replacedMessage = $route->replaceMessagePart($this->getExchangeListHP($lang)[$post['exchange-amount']]['what'], $replacedMessage, '/{exchangedWhat}/');
        return Routing::successMessage($replacedMessage);
    }

    public function sendHonorPoints($post)
    {
        global $security;
        global $language;
        global $langs;
        global $userData;
        $l = $language->honorPointsLangs();
        
        $receiverProfile = $this->data->getUserProfile($post['username']);
        if(is_object($receiverProfile)) $receiverID = $receiverProfile->getId() ? $receiverProfile->getId() : FALSE;
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(!array_key_exists($post['amount'], $this->getSendListHP()))
        {
            $error = $l['NO_EXISTING_REWARD'];
        }
        if($userData->getHonorPoints() < $post['amount'])
        {
            $error = $l['NOT_ENOUGH_HONOR_POINTS'];
        }
        if((isset($receiverID) && is_bool($receiverID) && $receiverID == FALSE ) || !is_object($receiverProfile))
        {
            $error = $langs['PLAYER_DOESNT_EXIST'];
        }
        if(is_object($receiverProfile) && $receiverProfile->getId() == $userData->getId())
        {
            $error = $l['CANNOT_SEND_HP_TO_SELF'];
        }
        if(strlen($post['message']) > 75)
        {
            $error = $langs['MESSAGE_UNDER_75_CHARS'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        $username = $receiverProfile->getUsername();
        $message = $security->xssEscape($post['message']);
        global $route;
        $this->data->sendHonorPointsTo($receiverID, $post['amount'], $message);
        $replacedMessage = $route->replaceMessagePart(number_format($post['amount'], 0, '', ','), $l['SEND_HONOR_POINTS_SUCCESS'], '/{amount}/');
        $replacedMessage = $route->replaceMessagePart($username, $replacedMessage, '/{username}/');
        return Routing::successMessage($replacedMessage);
    }

    public function healMember($post)
    {
        global $userData;
        global $security;
        global $language;
        global $langs;
        $l = $language->hospitalLangs();
        $fl = $language->familyLangs();
        $member = $security->xssEscape($post['member']);
        
        $memberProfile = $this->data->getUserProfile($member);
        if(is_object($memberProfile)) $costs = (100 - $memberProfile->getHealth()) * $this->healCostsPercent;
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if(!is_object($memberProfile))
        {
            $error = $langs['PLAYER_DOESNT_EXIST'];
        }
        if(is_object($memberProfile) && $memberProfile->getHealth() == 100)
        {
            $error = $l['MEMBER_NOT_WOUNDED'];
        }
        if(is_object($memberProfile) && $memberProfile->getHealth() == 0)
        {
            $error = $l['CANNOT_HEAL_DEAD_MEMBER'];
        }
        if(is_object($memberProfile) && $memberProfile->getFamilyID() != $userData->getFamilyID())
        {
            $error = $fl['USER_NOT_INSIDE_FAMILY'];
        }
        if(isset($costs) && $userData->getCash() < $costs)
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        global $route;
        $possession = new PossessionService();
        $possessionId = 7; // Hospital | Possession logic
        $possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID(), $userData->getCityID()); // Possess table record id
        $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
        
        $this->data->healMember($costs, $memberProfile, $pData);
        
        $replaces = array(
            array('part' => $memberProfile->getUsername(), 'message' => $l['HEAL_MEMBER_SUCCESS'], 'pattern' => '/{member}/'),
            array('part' => number_format($costs, 0, '', ','), 'message' => FALSE, 'pattern' => '/{costs}/'),
        );
        
        $replacedMessage = $route->replaceMessageParts($replaces);
        return Routing::successMessage($replacedMessage);
    }

    public function getGymTrainingTimesByPercent($gymTraining)
    {
        if($gymTraining < 5)
            return array('seconds' => array(1 => '180', '420', '960', '1980'), 'minutes' => array(1 => '3', '7', '16', '33'));
        elseif($gymTraining >= 5 && $gymTraining < 10)
            return array('seconds' => array(1 => '175', '410', '945', '1955'), 'minutes' => array(1 => '2:55', '6:50', '15:45', '32:35'));
        elseif($gymTraining >= 10 && $gymTraining < 15)
            return array('seconds' => array(1 => '170', '400', '930', '1930'), 'minutes' => array(1 => '2:50', '6:40', '15:30', '32:10'));
        elseif($gymTraining >= 15 && $gymTraining < 20)
            return array('seconds' => array(1 => '165', '390', '915', '1905'), 'minutes' => array(1 => '2:45', '6:30', '15:15', '31:45'));
        elseif($gymTraining >= 20 && $gymTraining < 25)
            return array('seconds' => array(1 => '160', '380', '900', '1880'), 'minutes' => array(1 => '2:40', '6:20', '15', '31:20'));
        elseif($gymTraining >= 25 && $gymTraining < 30)
            return array('seconds' => array(1 => '155', '370', '885', '1855'), 'minutes' => array(1 => '2:35', '6:10', '14:45', '30:55'));
        elseif($gymTraining >= 30 && $gymTraining < 35)
            return array('seconds' => array(1 => '150', '360', '870', '1830'), 'minutes' => array(1 => '2:30', '6', '14:30', '30:30'));
        elseif($gymTraining >= 35 && $gymTraining < 40)
            return array('seconds' => array(1 => '145', '350', '855', '1805'), 'minutes' => array(1 => '2:25', '5:50', '14:15', '30:05'));
        elseif($gymTraining >= 40 && $gymTraining < 45)
            return array('seconds' => array(1 => '140', '340', '840', '1780'), 'minutes' => array(1 => '2:20', '5:40', '14', '29:40'));
        elseif($gymTraining >= 45 && $gymTraining < 50)
            return array('seconds' => array(1 => '135', '330', '825', '1755'), 'minutes' => array(1 => '2:15', '5:30', '13:45', '29:15'));
        elseif($gymTraining >= 50 && $gymTraining < 55)
            return array('seconds' => array(1 => '130', '320', '810', '1730'), 'minutes' => array(1 => '2:10', '5:20', '13:30', '28:50'));
        elseif($gymTraining >= 55 && $gymTraining < 60)
            return array('seconds' => array(1 => '125', '310', '795', '1705'), 'minutes' => array(1 => '2:05', '5:10', '13:15', '28:25'));
        elseif($gymTraining >= 60 && $gymTraining < 65)
            return array('seconds' => array(1 => '120', '300', '780', '1680'), 'minutes' => array(1 => '2', '5', '13', '28'));
        elseif($gymTraining >= 65 && $gymTraining < 70)
            return array('seconds' => array(1 => '115', '290', '765', '1655'), 'minutes' => array(1 => '1:55', '4:50', '12:45', '27:35'));
        elseif($gymTraining >= 70 && $gymTraining < 75)
            return array('seconds' => array(1 => '110', '280', '750', '1630'), 'minutes' => array(1 => '1:50', '4:40', '12:30', '27:10'));
        elseif($gymTraining >= 75 && $gymTraining < 80)
            return array('seconds' => array(1 => '105', '270', '735', '1605'), 'minutes' => array(1 => '1:45', '4:30', '12:15', '26:45'));
        elseif($gymTraining >= 80 && $gymTraining < 85)
            return array('seconds' => array(1 => '100', '260', '720', '1580'), 'minutes' => array(1 => '1:40', '4:20', '12', '26:20'));
        elseif($gymTraining >= 85 && $gymTraining < 90)
            return array('seconds' => array(1 => '95', '250', '705', '1555'), 'minutes' => array(1 => '1:35', '4:10', '11:45', '25:55'));
        elseif($gymTraining >= 90 && $gymTraining < 95)
            return array('seconds' => array(1 => '90', '240', '690', '1530'), 'minutes' => array(1 => '1:30', '4', '11:30', '25:30'));
        elseif($gymTraining >= 95 && $gymTraining < 100)
            return array('seconds' => array(1 => '85', '230', '675', '1505'), 'minutes' => array(1 => '1:25', '3:50', '11:15', '25:05'));
        elseif($gymTraining >= 100)
            return array('seconds' => array(1 => '80', '220', '660', '1480'), 'minutes' => array(1 => '1:20', '3:40', '11', '24:40'));
        
        return FALSE;
    }
    
    public function getGymStatsGainedByTraining($training)
    {
        switch($training)
        {
            default:
                break;
            case 1:
                $gymStats = array('power' => 1, 'cardio' => 0);
                break;
            case 2:
                $gymStats = array('power' => 0, 'cardio' => 2);
                break;
            case 3:
                $gymStats = array('power' => 3, 'cardio' => 0);
                break;
            case 4:
                $gymStats = array('power' => 1, 'cardio' => 3);
                break;
        }
        if(isset($gymStats))
            return $gymStats;
        
        return FALSE;
    }

    public function gymTraining($post)
    {
        global $userData;
        global $security;
        global $language;
        global $langs;
        $l = $language->gymLangs();
        $gymPage = $this->data->getGymPageInfo();
        $gymTrainingPercent = ($gymPage->getPower() + $gymPage->getCardio()) / 2;
        
        if(isset($post['push-up']))
        {
            $training = 1;
            $type = $l['PUSH_UPS'];
            $waitingTime = (int)$this->getGymTrainingTimesByPercent($gymTrainingPercent)['seconds'][1];
        }
        elseif(isset($post['cycle']))
        {
            $training = 2;
            $type = $l['CYCLING'];
            $waitingTime = (int)$this->getGymTrainingTimesByPercent($gymTrainingPercent)['seconds'][2];
        }
        elseif(isset($post['bench-press']))
        {
            $training = 3;
            $type = $l['BENCH_PRESSING'];
            $waitingTime = (int)$this->getGymTrainingTimesByPercent($gymTrainingPercent)['seconds'][3];
        }
        elseif(isset($post['run']))
        {
            $training = 4;
            $type = $l['RUNNING'];
            $waitingTime = (int)$this->getGymTrainingTimesByPercent($gymTrainingPercent)['seconds'][4];
        }
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if($userData->getCGymTraining() > time())
        {
            $error = $langs['WAITING_TIME_NOT_PASSED'];
        }
        if(!isset($training))
        {
            $error = $l['GYM_TRAINING_DOESNT_EXIST'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        global $route;
        $stats = $this->getGymStatsGainedByTraining($training);
        
        $dailyChallengeService = new DailyChallengeService();
        $publicMissionService = new PublicMissionService();
        if($stats['power'] > 0)
        {
            $dailyChallengeService->addToDailiesIfActive(6, $stats['power']);
            $publicMissionService->addToPublicMisionIfActive(7, $stats['power']);
        }
        if($stats['cardio'] > 0)
        {
            $dailyChallengeService->addToDailiesIfActive(9, $stats['cardio']);
            $publicMissionService->addToPublicMisionIfActive(16, $stats['cardio']);
        }
        
        $this->data->gymTraining($stats, $waitingTime);
        
        if($stats['power'] > 0 && $stats['cardio'] > 0)
        {
            $replaces = array(
                array('part' => $type, 'message' => $l['TRAIN_GYM_BOTH_STATS_SUCCESS'], 'pattern' => '/{type}/'),
                array('part' => $stats['power'], 'message' => FALSE, 'pattern' => '/{power}/'),
                array('part' => $stats['cardio'], 'message' => FALSE, 'pattern' => '/{cardio}/')
            );
        }
        elseif($stats['power'] > 0 && $stats['cardio'] == 0)
        {
            $replaces = array(
                array('part' => $type, 'message' => $l['TRAIN_GYM_POWER_SUCCESS'], 'pattern' => '/{type}/'),
                array('part' => $stats['power'], 'message' => FALSE, 'pattern' => '/{power}/'),
            );
        }
        elseif($stats['power'] == 0 && $stats['cardio'] > 0)
        {
            $replaces = array(
                array('part' => $type, 'message' => $l['TRAIN_GYM_CARDIO_SUCCESS'], 'pattern' => '/{type}/'),
                array('part' => $stats['cardio'], 'message' => FALSE, 'pattern' => '/{cardio}/'),
            );
        }
        
        $replacedMessage = $route->replaceMessageParts($replaces);
        
        return Routing::successMessage($replacedMessage);
    }
    
    public function gymChangeFastAction($post)
    {
        global $security;
        global $language;
        global $langs;
        $l = $language->gymLangs();
        $id = (int)$post['fastActionID'];
        $fastAction = $security->xssEscape($post['fastAction']);
        if($security->checkToken($post['securityToken']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($id < 1 || $id > 4)
        {
            $error = $l['GYM_TRAINING_DOESNT_EXIST'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        global $route;
        $this->data->gymChangeFastAction($id);
        $replacedMessage = $route->replaceMessagePart($fastAction, $l['GYM_FAST_ACTION_CHANGE_SUCCESS'], '/{type}/');
        return Routing::successMessage($replacedMessage);
    }

    public function handleFriendsBlock($post)
    {
        global $userData;
        global $route;
        global $security;
        global $language;
        global $langs;
        $l = $language->friendsBlockLangs();
        $username = $security->xssEscape($post['username']);
        $userID = $this->data->getIdByUsername($username);

        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userID == FALSE)
        {
            $error = $langs['PLAYER_DOESNT_EXIST'];
        }
        if($userID == $_SESSION['UID'])
        {
            $error = $langs['CANNOT_COMMIT_ACTION_SELF'];
        }
        if($userID != FALSE && isset($post['invite']) && $this->data->checkFriends($userID) == TRUE)
        {
            $error = $l['ALREADY_FRIENDS_WITH_USER'];
        }
        if($userID != FALSE && isset($post['invite']) && $this->data->checkBlock($userID) == TRUE)
        {
            $error = $l['CANNOT_BECOME_FRIENDS'];
        }
        if($userID != FALSE && isset($post['invite']) && $this->data->checkFriendsPending($userID) == TRUE)
        {
            $error = $l['ALREADY_REQUESTED_FRIENDSHIP'];
        }
        if($userID != FALSE && isset($post['block']) && $this->data->checkFriends($userID) == TRUE)
        {
            $error = $l['CANNOT_BLOCK_FRIEND'];
        }
        if($userID != FALSE && isset($post['block']) && $this->data->checkOneWayBlock($userID) == TRUE)
        {
            $error = $l['USER_ALREADY_BLOCKED'];
        }
        if($userID != FALSE)
        {
            $maxFriends = 10;
            if($userData->getDonatorID() == 1)
                $maxFriends = 15;
            elseif($userData->getDonatorID() >= 5)
                $maxFriends = 20;
            
            if((count($this->data->getFriendsBlock()['friends']) + count($this->data->getFriendsBlock()['blocks'])) >= $maxFriends)
            {
                $error = $route->replaceMessagePart($maxFriends, $l['USER_MAX_FRIEND_BLOCK'], '/{max}/');
            }
        }

        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        if(isset($post['invite']))
        {
            $this->data->inviteFriend($userID);
            $replacedMessage = $route->replaceMessagePart($username, $l['INVITE_FRIEND_SUCCESS'], '/{user}/');
            
            $notification = new NotificationService();
            $params = "user=".$userData->getUsername();
            $notification->sendNotification($this->data->getIdByUsername($username), 'USER_INVITED_FRIEND', $params);
        }
        elseif(isset($post['block']))
        {
            $this->data->blockUser($userID);
            $replacedMessage = $route->replaceMessagePart($username, $l['BLOCK_USER_SUCCESS'], '/{user}/');
        }
        return Routing::successMessage($replacedMessage);
    }

    public function interactFriendsList($post)
    {
        global $userData;
        global $route;
        global $security;
        global $language;
        global $langs;
        $l = $language->friendsBlockLangs();
        if(isset($post['friend']))
        {
            $username = $security->xssEscape($post['friend']);
            $friendID = $this->data->getIdByUsername($username);
        }
        else
        {
            $username = $security->xssEscape($post['user']);
            $userID = $this->data->getIdByUsername($username);
        }

        $action = FALSE;
        $allowedActions = array("accept", "deny", "delete", "delete-confirm", "delete-block");
        if(isset($post) && !empty($post))
        {
            foreach($allowedActions AS $act)
            {
                if(isset($post[$act]))
                    $action = $act;
            }
        }

        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(empty($username) || !isset($username) || (!isset($friendID) && !isset($userID)))
        {
            $error = $langs['PLAYER_DOESNT_EXIST'];
        }
        if($action == FALSE)
        {
            $error = $langs['INVALID_ACTION'];
        }
        if($action == 'accept' && isset($friendID))
        {
            $maxFriends = 10;
            if($userData->getDonatorID() == 1)
                $maxFriends = 15;
            elseif($userData->getDonatorID() >= 5)
                $maxFriends = 20;
            
            if((count($this->data->getFriendsBlock()['friends']) + count($this->data->getFriendsBlock()['blocks'])) >= $maxFriends)
            {
                $error = $route->replaceMessagePart($maxFriends, $l['USER_MAX_FRIEND_BLOCK'], '/{max}/');
            }
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        switch($action)
        {
            case 'accept':
                $this->data->acceptFriend($friendID);
                $replacedMessage = $route->replaceMessagePart($username, $l['FRIEND_REQUEST_ACCEPTED'], '/{username}/');
                
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername();
                $notification->sendNotification($this->data->getIdByUsername($username), 'FRIEND_REQUEST_ACCEPTED', $params);

                return Routing::successMessage($replacedMessage);
                break;
            case 'deny':
                $this->data->denyFriend($friendID);
                $replacedMessage = $route->replaceMessagePart($username, $l['FRIEND_REQUEST_DENIED'], '/{username}/');
                
                $notification = new NotificationService();
                $params = "user=".$userData->getUsername();
                $notification->sendNotification($this->data->getIdByUsername($username), 'FRIEND_REQUEST_DENIED', $params);

                return Routing::successMessage($replacedMessage);
                break;
            case 'delete':
                $replacedMessage = $route->replaceMessagePart($username, $l['DELETE_FRIEND_CONFIRM'], '/{username}/');
                $replacedMessage = $route->replaceMessagePart($username, $replacedMessage, '/{fid}/');
                $replacedMessage = $route->replaceMessagePart($security->getToken(), $replacedMessage, '/{securityToken}/');
                return Routing::errorMessage($replacedMessage);
                break;
            case 'delete-confirm':
                $this->data->deleteFriend($friendID);
                $replacedMessage = $route->replaceMessagePart($username, $l['FRIEND_DELETED'], '/{username}/');
                return Routing::successMessage($replacedMessage);
                break;
            case 'delete-block':
                $this->data->deleteBlock($userID);
                $replacedMessage = $route->replaceMessagePart($username, $l['BLOCK_DELETED'], '/{username}/');
                return Routing::successMessage($replacedMessage);
        }
    }
    
    public function getLuckyboxChanceList()
    {
        global $langs;
        
        /** Beware! All chanceInAMillion numeric values have to add up to 1,000,000 combined, make sure you know what you're doing here. **/
        $list = array(
            1 => array(
                'amount' => 3000, // Amount to add in database / show on luckybox page
                'prize' => "Credits", // Prize to show on luckybox page
                'prizeDb' => "credits", // Prize field in user table (database)
                'chanceInAMillion' => 1, // Chance in a million to win prize
                'chanceShow' => "< 0.01" // Chance shown on luckybox page
            ),
            array(
                'amount' => 500000000,
                'prize' => "Bank ".strtolower($langs['MONEY']),
                'prizeDb' => "bank",
                'chanceInAMillion' => 100,
                'chanceShow' => "0.01"
            ),
            array(
                'amount' => 1250,
                'prize' => $langs['HONOR_POINTS'],
                'prizeDb' => "honorPoints",
                'chanceInAMillion' => 200,
                'chanceShow' => "0.02"
            ),
            array(
                'amount' => 250000,
                'prize' => $langs['BULLETS'],
                'prizeDb' => "bullets",
                'chanceInAMillion' => 300,
                'chanceShow' => "0.03"
            ),
            array(
                'amount' => array(6000, 9000),
                'prize' => $langs['WHORES'],
                'prizeDb' => "whoresStreet",
                'chanceInAMillion' => 400,
                'chanceShow' => "0.04"
            ),
            array(
                'amount' => 7,
                'prize' => $langs['LUCKY_BOXES'],
                'prizeDb' => "luckybox",
                'chanceInAMillion' => 2000,
                'chanceShow' => "0.2"
            ),
            array(
                'amount' => array(50000000, 150000000),
                'prize' => "Bank ".strtolower($langs['MONEY']),
                'prizeDb' => "bank",
                'chanceInAMillion' => 7000,
                'chanceShow' => "0.7"
            ),
            array(
                'amount' => array(350, 700),
                'prize' => $langs['HONOR_POINTS'],
                'prizeDb' => "honorPoints",
                'chanceInAMillion' => 10000,
                'chanceShow' => "1"
            ),
            array(
                'amount' => array(18000, 22000),
                'prize' => $langs['BULLETS'],
                'prizeDb' => 'bullets',
                'chanceInAMillion' => 20000,
                'chanceShow' => "2"
            ),
            array(
                'amount' => array(900, 1100),
                'prize' => $langs['WHORES'],
                'prizeDb' => "whoresStreet",
                'chanceInAMillion' => 30000,
                'chanceShow' => "3"
            ),
            array(
                'amount' => 20,
                'prize' => "Credits",
                'prizeDb' => "credits",
                'chanceInAMillion' => 50000,
                'chanceShow' => "5"
            ),
            array(
                'amount' => array(8000000, 12000000),
                'prize' => "Bank ".strtolower($langs['MONEY']),
                'prizeDb' => "bank",
                'chanceInAMillion' => 80000,
                'chanceShow' => "8"
            ),
            array(
                'amount' => array(8, 16),
                'prize' => $langs['HONOR_POINTS'],
                'prizeDb' => "honorPoints",
                'chanceInAMillion' => 120000,
                'chanceShow' => "12"
            ),
            array(
                'amount' => array(600, 1200),
                'prize' => $langs['BULLETS'],
                'prizeDb' => 'bullets',
                'chanceInAMillion' => 180000,
                'chanceShow' => "18"
            ),
            array(
                'amount' => array(30, 60),
                'prize' => $langs['WHORES'],
                'prizeDb' => "whoresStreet",
                'chanceInAMillion' => 499999,
                'chanceShow' => "> 49.99 < 50"
            )
        );
        
        return $list;
    }

    public function openLuckybox($post)
    {
        global $userData;
        global $security;
        global $language;
        global $langs;
        $l = $language->luckyboxLangs();

        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getLuckybox() < 1)
        {
            $error = $l['NO_LUCKY_BOX'];
        }
        /*
        if(isset($_SESSION['luckybox']['opened']) && $_SESSION['luckybox']['opened'] >= time())
        {
            $error = $l['PLEASE_CALM_DOWN'];
        }
        */

        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        global $route;
        $luck = $security->randInt(1, 1000000);
        $chanceList = $this->getLuckyboxChanceList();
        $previousChance = 0;
        foreach($chanceList AS $prize)
        {
            if(!isset($prizeWon))
            {
                if($luck > $previousChance && $luck <= ($previousChance + $prize['chanceInAMillion']))
                    $prizeWon = $prize;
                
                $previousChance += $prize['chanceInAMillion'];
            }
        }
        unset($previousChance);
        // Is prize a random between 2 values?
        if(is_array($prizeWon['amount']))
            $prizeWon['amount'] = $security->randInt($prizeWon['amount'][0], $prizeWon['amount'][1]);
        
        $csshSymbol = ""; // Init empty
        if($prizeWon['prizeDb'] == 'bank')
            $csshSymbol = "$&#8203;"; // User won money, set to show cash symbol
        
        $this->data->openedLuckybox($prizeWon);
        
        $replaces = array(
            array('part' => $csshSymbol.number_format($prizeWon['amount'], 0, '', ','), 'message' => $l['OPEN_BOX_SUCCESS'], 'pattern' => '/{amount}/'),
            array('part' => strtolower($prizeWon['prize']), 'message' => FALSE, 'pattern' => '/{prize}/'),
        );
        $replacedMessage = $route->replaceMessageParts($replaces);
        
        $_SESSION['luckybox']['opened'] = time();
        return Routing::successMessage($replacedMessage);
    }
    
    public function searchCredits($action)
    {
        $credits = false;
        if($this->creditsChanceRand <= $this->creditsChance)
            $credits = $this->creditsWon;
        
        if($credits !== false)
        {
            global $route;
            global $langs;
            
            $publicMissionService = new PublicMissionService();
            $publicMissionService->addToPublicMisionIfActive(14, $credits);
            
            $this->addCreditsFound($credits);
            
            $replaces = array(
                array('part' => number_format($credits, 0, '', ','), 'message' => $langs["FOUND_CREDITS"], 'pattern' => '/{credits}/'),
                array('part' => strtolower($action), 'message' => FALSE, 'pattern' => '/{action}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            
            $replacedMessage = Routing::warningMessage($replacedMessage);
            return $replacedMessage;
        }
        return false;
    }

    public function getTeamMembers()
    {
        return $this->data->getTeamMembers();
    }

    public function getOnlineMembers()
    {
        return $this->data->getOnlineMembers();
    }

    public function getOnlineFamMembers()
    {
        global $userData;
        if($userData->getFamilyID() != 0)
        {
            return $this->data->getOnlineFamMembers();
        }
        return FALSE;
    }

    public function getOnlineTeamMembers()
    {
        return $this->data->getOnlineTeamMembers();
    }

    public function getIdByUsername($username)
    {
        return $this->data->getIdByUsername($username);
    }

    public function getUsernameById($id)
    {
        return $this->data->getUsernameById($id);
    }

    public function createUser($username, $pass, $email, $profession)
    {
        return $this->data->createUser($username, $pass, $email, $profession);
    }

    public function getUserProfile($username)
    {
        return $this->data->getUserProfile($username);
    }

    public function getToplist($from, $to)
    {
        return $this->data->getToplist($from, $to);
    }
    
    public function getRecoverPasswordDataByKey($key)
    {
        return $this->data->getRecoverPasswordDataByKey($key);
    }

    public function getCoveredEmailByUsername($username)
    {
        return $this->data->getCoveredEmailByUsername($username);
    }
    
    public function getChangeEmailDataByKey($key)
    {
        return $this->data->getChangeEmailDataByKey($key);
    }

    public function getHonorPointLogs()
    {
        return $this->data->getHonorPointLogs();
    }

    public function getFriendsBlock($username = FALSE, $limited = FALSE)
    {
        return $this->data->getFriendsBlock($username, $limited);
    }

    public function getFriendsList()
    {
        return $this->data->getFriendsList();
    }
    
    public function removeStatusProtection()
    {
        return $this->data->removeStatusProtection();
    }

    public function getStatusPageInfo()
    {
        return $this->data->getStatusPageInfo();
    }

    public function getBankPageInfo()
    {
        return $this->data->getBankPageInfo();
    }
    
    public function getGymPageInfo()
    {
        return $this->data->getGymPageInfo();
    }
    
    public function addCreditsFound($credits)
    {
        return $this->data->addCreditsFound($credits);
    }
    
    public function isPrivateIDActive()
    {
        return $this->data->isPrivateIDActive();
    }
}
