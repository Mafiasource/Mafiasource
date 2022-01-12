<?PHP

namespace app\config;
 
require_once __DIR__.'/config.php';

if(extension_loaded('opcache'))
{
    ini_set('opcache.memory_consumption', 128);
    ini_set('opcache.interned_strings_buffer', 8);
    ini_set('opcache.max_accelerated_files', 4000);
    ini_set('opcache.revalidate_freq', 60);
    ini_set('opcache.fast_shutdown', 1);
    ini_set('opcache.enable_cli', 1);
    ##ini_set('opcache.file_cache', __DIR__ . '/../app/cache/opcache');
}

class Routing
{
    private $route;
    private $routeName;
    private $controller;
    private $routeRegex = array();
    
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
    public $routeMap = array();
    public $ajaxRouteMap = array();
    public $allowedLangs = array("nl", "en");
    
    public function __construct()
    {
        $this->settings['twigCache'] = DOC_ROOT . '/app/cache/TwigCompilation/';
        if(DEVELOPMENT == true) $this->settings['twigCache'] = FALSE;
        
        $this->routeRegex[] = $routeGET = "(?:\?.*)?";
        require_once __DIR__.'/routes/routes.php';
        $routeGET = null;
        
        $this->routeMap = $routeMap = $applicationRoutes;
        $routeMap = $applicationRoutes = null;
        
        $this->ajaxRouteMap = $ajaxRouteMap = $ajaxRoutes;
        $ajaxRouteMap = $ajaxRoutes = null;
        
        $requestURI = explode('/', $_SERVER['REQUEST_URI']);
        $routeParams = array();
        for($i = 1; $i < count($requestURI); $i++)
        {
            $val = $requestURI[$i];
            array_push($routeParams, $val);
        }
        
        $endRoute = "";
        foreach($routeParams AS $value)
            $endRoute .= '/'.$value;
        
        $controller = $routeName = FALSE;
        foreach($this->routeMap AS $key => $value)
        {
            if(preg_match('{^'.$value['route'].'$}', $endRoute))
            {
                $controller = $value['controller'];
                $routeName = $key;
                break;
            }
        }
        if($controller == FALSE && $routeName == FALSE)
        {
            foreach($this->ajaxRouteMap AS $key => $value)
            {
                if(preg_match('{^'.$value['route'].'$}', $endRoute))
                {
                    $controller = $value['controller'];
                    $routeName = $key;
                    break;
                }
            }
        }
        
        $this->route =  $endRoute;
        $this->controller = "notfound.php";
        $this->routeName = "not_found";
        
        if($controller != false)
        {
            $this->route = $endRoute;
            $this->controller = $controller;
            $this->routeName = $routeName;
        }
    }
    
    public function __destruct()
    {
        $this->routeMap = null;
        $this->ajaxRouteMap = null;
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
        foreach($this->routeRegex AS $regex)
        {
            $route = preg_replace('/' . $regex . '/', '', $route);
        }
        return $route;
    }
    
    public function getRouteByRouteName($routeName)
    {
        $result = isset($this->routeMap[$routeName]) ? $this->routeMap[$routeName]['route'] : null;
        if(isset($result))
        {
            $result = $this->removeRouteRegex($result);
        }
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
    
    public function headTo($routeName, $addOnRoute = false)
    {
        $addToHeader = "";
        if(isset($addOnRoute) && $addOnRoute != false) $addToHeader = $addOnRoute;
        $result = isset($this->routeMap[$routeName]) ? $this->routeMap[$routeName]['route'] : null;
        if($result != null)
        {
            $result = $this->removeRouteRegex($result);
            
            header("HTTP/2 301 Moved Permanently");
            header('Location: ' . $result . $addToHeader, TRUE, 301);
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
            $what = $parameters[$param];
            if($range !== FALSE && is_array($range) && array_key_exists('min', $range) && array_key_exists('max', $range))
            {
                if($what < $range['min'] || $what > $range['max'])
                    $this->headTo('not_found');
            }
            $what = $this->replaceRouteRegex($what);
            
            return $what;
        }
        return FALSE;
    }
    
    function getLanguageByIp()
    {
        $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $language = "English";
        if(preg_match("/nl$/", $host) || preg_match("/be$/", $host) || preg_match("/arpa$/", $host))
            $language = "Dutch";
        
        return $language;
    }
    
    private function getFirstVisitLang()
    {
        $language = $this->getLanguageByIp();
        if($language == "English")
        {
            setcookie('lang', 'en', time()+9999999, '/', $this->settings['domain'], SSL_ENABLED, true);
            return "en";
        }
        if($language == "Dutch")
        {
            setcookie('lang', 'nl', time()+9999999, '/', $this->settings['domain'], SSL_ENABLED, true);
            return "nl";
        }
    }
    
    public function getLang()
    {
        return isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $this->allowedLangs) ? $_COOKIE['lang'] : $this->getFirstVisitLang();
    }
    
    public function getLangRaw()
    {
        return $this->getLang() == "nl" ? "nl-NL" : "en-EN";
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
                $replaced = preg_replace($part['pattern'], $part['part'], $replaced);
            
            if(!isset($replaced))
                $replaced = preg_replace($part['pattern'], $part['part'], $part['message']);
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
