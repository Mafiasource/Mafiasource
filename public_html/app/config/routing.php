<?PHP

namespace app\config;

use src\Business\UserCoreService;

require_once __DIR__.'/config.php';

class Routing
{
    private $route;
    private $routeName = "not_found";
    private $controller = "notfound.php";
    private $routeRegex = array();
    private $routeLang = "(?:\/(nl|en))?";
    private $routeGet = "(?:\?.*)?";
    
    public $routeMap = array();
    public $ajaxRouteMap = array();
    public $allowedLangs = array("nl", "en");
    public $allowedFileLangs = array("nl" => "nl.php", "en" => "en.php");
    
    public $settings = array(
        'domainBase' => BASE_DOMAIN, // see config.php
        'gamename' => APP_GAMENAME, // see config.php
        'domain' => APP_DOMAIN, // see config.php
        'fbPage' => APP_FB_PAGE,// see config.php
        'twPage' => APP_TW_PAGE, // see config.php
        'gpPage' => APP_GP_PAGE, // see config.php
        
        //Api settings & other core web settings
        'ssl' => SSL_ENABLED, // see config.php
        'twigCache' => FALSE, // Init, DO NOT CHANGE see config.php 'DEVELOPMENT'
    );
    
    public function __construct()
    {
        $this->settings['twigCache'] = DEVELOPMENT === false ? DOC_ROOT . '/app/cache/TwigCompilation/' : FALSE;
        require_once __DIR__.'/routes/routes.php';
        
        $endRoute = $_SERVER['REQUEST_URI'];
        $this->setRoute($endRoute, $this->routeMap);
        if($this->routeName == "not_found")
            $this->setRoute($endRoute, $this->ajaxRouteMap, true);
    }
    
    public function __destruct()
    {
        $this->routeLang = $this->routeGet = $this->routeMap = $this->ajaxRouteMap = null;
    }
    
    private function setRoute($route, $routeMap, $ajaxRoute = false)
    {
        $this->route = $route;
        $routeName = $ajaxRoute === false ? $this->getRouteNameByRoute($route) : $this->getAjaxRouteNameByRoute($route);
        if(isset($routeName) && strlen($routeName))
        {
            $this->controller = $routeMap[$routeName]['controller'];
            $this->routeName = $routeName;
        }
    }
    
    public function getRoute()
    {
        return $this->route;
    }
    
    public function getRouteName()
    {
        return $this->routeName;
    }
    
    public function getController()
    {
        return $this->controller;
    }
    
    public function getReplacedRoute()
    {
        return $this->replaceRouteRegex($this->route);
    }
    
    private function removeRouteRegex($route)
    {
        foreach($this->routeRegex AS $regex)
        {
            if(strpos($route, $regex) !== false)
                $route = str_replace($regex, '', $route);
        }
        return $route;
    }
    
    private function replaceRouteRegex($route)
    {
        $routeLang = substr($route, 1, 2);
        $expl = explode('/', $route);
        $lastPar = end($expl);
        foreach($this->routeRegex AS $regex)
        {
            if($regex == $this->routeLang && in_array($routeLang, $this->allowedLangs))
                $return = preg_replace('/' . $regex . '/', '', $route, 1);
            
            if($regex == $this->routeGet && preg_match("/" . $regex . "/", $lastPar))
                $return = str_replace($lastPar, preg_replace('/' . $regex . '/', '', $lastPar), $route);
            
            $return = isset($return) ? $return : $route;
        }
        return $return;
    }
    
    public function getRouteByRouteName($routeName)
    {
        $result = isset($this->routeMap[$routeName]) ? $this->routeMap[$routeName]['route'] : null;
        if(isset($result))
            $result = $this->removeRouteRegex($result);
        
        return $result;
    }
    
    public function getRouteNameByRoute($route)
    {
        foreach($this->routeMap AS $key => $value)
        {
            if(preg_match('{^'.$value['route'].'$}', $route))
                return $key;
        }
    }
    
    public function getAjaxRouteByRouteName($routeName)
    {
        return isset($this->ajaxRouteMap[$routeName]) ? $this->ajaxRouteMap[$routeName]['route'] : null;
    }
    
    public function getAjaxRouteNameByRoute($route)
    {
        foreach($this->ajaxRouteMap AS $key => $value)
        {
            if(preg_match('{^'.$value['route'].'$}', $route))
                return $key;
        }
    }
    
    public function getPrevRouteName()
    {
        return isset($_SESSION['PREV_ROUTE_NAME']) ? $_SESSION['PREV_ROUTE_NAME'] : $this->routeName;
    }
    
    private function setPrevRouteNameByRoute($route)
    {
        if(isset($_SESSION['PREV_ROUTE']) && preg_match('{^'.$route.'$}', $_SESSION['PREV_ROUTE']))
        {
            $_SESSION['PREV_ROUTE_NAME'] = $this->routeName;
        }
        return TRUE;
    }
    
    public function getPrevRoute()
    {
        return isset($_SESSION['PREV_ROUTE']) ? $_SESSION['PREV_ROUTE'] : "/";
    }
    
    public function setPrevRoute()
    {
        $result = isset($this->routeMap[$this->routeName]) ? $this->routeMap[$this->routeName]['route'] : null;
        if($result != null)
        {
            $_SESSION['PREV_ROUTE'] = $_SERVER['REQUEST_URI'];
            $this->setPrevRouteNameByRoute($result);
        }
        return TRUE;
    }
    
    public function headTo($routeName, $addAfterRoute = "")
    {
        $result = isset($this->routeMap[$routeName]) ? $this->routeMap[$routeName]['route'] : null;
        if($result != null)
        {
            $result = $this->removeRouteRegex($result);
            
            header("HTTP/2 301 Moved Permanently");
            header('Location: ' . $result . $addAfterRoute, TRUE, 301);
            exit(0);
        }
    }
    
    public function requestGetParam($param, $range=FALSE)
    {
        $requestURI = explode('/', $this->getRoute());
        $parameters = array();
        for($i = 0; $i < count($requestURI); $i++)
        {
            $val = $requestURI[$i];
            array_push($parameters, $val);
        }
        if(isset($parameters[$param]))
        {
            $what = $what = $this->replaceRouteRegex($parameters[$param]);
            if($range !== FALSE && is_array($range) && array_key_exists('min', $range) && array_key_exists('max', $range))
            {
                if($what < $range['min'] || $what > $range['max'])
                    $this->headTo('not_found');
            }
            
            return $what;
        }
        return FALSE;
    }
    
    function getLanguageByIp()
    {
        $user = new UserCoreService();
        $host = $user->ipValid ? gethostbyaddr(UserCoreService::getIP()) : null;
        $language = "English";
        if(isset($host) && (preg_match("/nl$/", $host) || preg_match("/be$/", $host) || preg_match("/arpa$/", $host)))
            $language = "Dutch";
        
        return $language;
    }
    
    private function getFirstVisitLang()
    {
        $language = $this->getLanguageByIp();
        $lang = "en";
        
        if($language == "Dutch")
            $lang = "nl";
        
        setcookie('lang', $lang, time()+9999999, '/', $this->settings['domain'], SSL_ENABLED, true);
        return $lang;
    }
    
    public function setLang($lang)
    {
        if(in_array($lang, $this->allowedLangs))
            setcookie('lang', $lang, time()+9999999, '/', $this->settings['domain'], SSL_ENABLED, true);
    }
    
    public function getLang()
    {
        return isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $this->allowedLangs) ? $_COOKIE['lang'] : $this->getFirstVisitLang();
    }

    public function getLangFile(string $lang): string
    {
        return $this->allowedFileLangs[$lang] ?? $this->allowedFileLangs['en'];
    }
    
    public function adjustLang($lang)
    {
        global $uriLang;
        
        if(in_array($uriLang, $this->allowedLangs) && $this->requestGetParam(1) != "game")
        { // Outgame multilingual SEO purposes
            $lang = $uriLang;
            $this->setLang($uriLang);
        }
        $lang = $this->adjustUserLang($lang);
        return $lang;
    }
    
    private function adjustUserLang($lang)
    {
        global $userData;
        
        if(!isset($_SESSION['lang']['setAfterLogin']) && is_object($userData))
        {
            if($loggedInLang = $userData->getLang() != $lang && in_array($userData->getLang(), $this->allowedLangs))
            { // Re-set preferred lang for logged in game user
                $lang = $userData->getLang();
                $this->setLang($lang);
            }
            if($loggedInLang || ($userData->getLang() == $lang && in_array($lang, $this->allowedLangs)))
                $_SESSION['lang']['setAfterLogin'] = true;
        }
        return $lang;
    }
    
    public function getLangRaw()
    {
        global $lang;
        return isset($lang) && $lang == "nl" ? "nl-NL" : "en-EN";
    }
    
    public function createActionMessage($msg)
    {
        $_SESSION['message'] = $msg;
    }
    
    public function setActionMessage()
    {
        $message = "";
        if(isset($_SESSION['message'])) $message = $_SESSION['message'];
        unset($_SESSION['message']);
        return $message;
    }
    
    public function replaceMessagePart($part, $message, $pattern)
    {
        return preg_replace($pattern, $part, $message);
    }
    
    public function replaceMessageParts($message)
    {
        foreach($message AS $part)
        {
            if(isset($replaced))
                $replaced = preg_replace((string)$part['pattern'], (string)$part['part'], (string)$replaced);
            
            if(!isset($replaced))
                $replaced = preg_replace((string)$part['pattern'], (string)$part['part'], (string)$part['message']);
        }
        return $replaced;
    }
    
    public static function errorMessage($msg)
    {
        return array('alert' => array('danger' => true, 'message' => $msg));
    }
    
    public static function successMessage($msg)
    {
        return array('alert' => array('success' => true, 'message' => $msg));
    }
    
    public static function warningMessage($msg)
    {
        return array('alert' => array('warning' => true, 'message' => $msg));
    }
}
