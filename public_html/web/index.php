<?PHP

// Mafiasource online mafia RPG, this software is inspired by Crimeclub.
// Copyright © 2016 Michael Carrein, 2006 Crimeclub.nl
//
// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the “Software”),
// to deal in the Software without restriction, including without limitation
// the rights to use, copy, modify, merge, publish, distribute, sublicense,
// and/or sell copies of the Software, and to permit persons to whom the
// Software is furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included
// in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS
// OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
// NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
// DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
// OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR
// THE USE OR OTHER DEALINGS IN THE SOFTWARE.

/** Front-controller - Main entry point for entire web application **/

use Doctrine\Common\ClassLoader;
use app\config\Routing;
use app\config\Security;
use src\Business\Logic\SeoURL;
use src\Business\UserCoreService;
use src\Data\config\DBConfig;
use src\Languages\GetLanguageContent;

// Set correct timezone
ini_set('date.timezone', 'Europe/Amsterdam');

// Set memory limit
ini_set ('memory_limit', '128M');

// Daily maintenance 1 min: (/app/cronjob/dbbackup.php)
if(date('H') == 4 && date('i') == 00)
{
    die('Daily maintenance is running please refresh in a minute.');
    exit(0);
}

// Include routing: controllers > views
require_once __DIR__ . '/../app/config/routing.php';
$route = new Routing();

// Set error reporting according to DEVELOPMENT global (/app/config/config.php)
$errRepInt = DEVELOPMENT === true ? 1 : 0;
error_reporting(-1);
ini_set('log_errors', 1);
ini_set('display_errors', $errRepInt);
ini_set('display_startup_errors', $errRepInt);
$errRepInt = null;

$stream = @stream_socket_server('tcp://0.0.0.0:7600', $errno, $errmg, STREAM_SERVER_BIND);
if($stream && $_SERVER['HTTP_HOST'] == $route->settings['domain'])
{
    // Routing fetched a valid controller?
    if($route->getController() != FALSE)
    {
        require __DIR__ . '/../vendor/autoload.php';
        // Enable Autoloading with doctrine
        $classLoader = new ClassLoader('src'   ,   DOC_ROOT);
        $classLoader->register();
        $classLoader = null;
        // Start a session except for cookieless routes
        if(!str_starts_with($route->getRouteName(), '_'))
        {
            require_once __DIR__ . '/../vendor/SessionManager.php';
            $session = new SessionManager();
            ini_set('session.save_handler', 'files');
            session_set_save_handler($session, true);
            session_save_path(__DIR__ . '/../app/cache/sessions');
            SessionManager::sessionStart(SeoURL::format($route->settings['gamename']), 0, '/', $route->settings['domain'], SSL_ENABLED);
            $session = $seoURL = null;
        }
        // Security class (Anti CSRF, XSS attacks & more)
        require_once __DIR__ . '/../app/config/security.php';
        $security = new Security();
        
        // Define PROTOCOL used throughout the application http / https? see: app/config/config.php
        if($route->settings['ssl'] === true) define('PROTOCOL', 'https://'); else define('PROTOCOL', 'http://');
        
        // Enable Twig engine & set some custom filters used throughout the application
        $loader = new \Twig\Loader\FilesystemLoader(DOC_ROOT); // Root templates folder to DOC root (Because we have tmpls in app/ and src/ )
        $twig = new \Twig\Environment($loader, [
            'cache' => $route->settings['twigCache'] // Caching? depends on dev mode see: app/config/config.php
        ]);
        $loader = null;
        require_once __DIR__ . '/../app/config/twig.filters.php';
        
        // Db connection | Init globally to avoid multiple sql connections in one request | only to be used in all DAO classes or cronjobs!
        $connection = new DBConfig();
        
        // UserCoreService class requires above $connection for its underlying UserCoreDAO class, $user obj immediately used first in GetLanguageContent class
        $lang = $route->getLang(); // Default, used first in UserCoreDAO constructor
        $user = new UserCoreService();
        $userData = $user->getUserData();
        
        // Get preferred language class & contents
        $uriLang = str_replace('/','', $route->requestGetParam(1));
        $lang = $route->adjustLang($lang); // Preferred
        require_once DOC_ROOT . '/src/Languages/lang.'.$lang.'.php'; // Require user's preferred language

        $language = new GetLanguageContent(); // Class mostly used in all service classes (Business layer)
        $langs = $language->langMap; // Base langs available on every page, contents depend on a player in- or out-game

        // Check if controller actually exists and include it
        $controller = __DIR__ . '/../src/Controllers/' . $route->getController();
        if(file_exists($controller))
        {
            set_error_handler(function ($severity, $message, $file, $line) {
                $errorsEnabled = (bool)(error_reporting() & $severity);
                $errorsEnabled = (bool)($errorsEnabled & DEVELOPMENT);
                if(in_array($severity, array(E_USER_ERROR, E_RECOVERABLE_ERROR)) && $errorsEnabled)
                    throw new \ErrorException($message, 0, $severity, $file, $line);
                
                throw new \Exception($message);
            }, E_ALL);
            
            try
            {
                $denyPrevRouteSaves = array('notfound.php', 'languageSelect.php', 'game/rest.in.peace.php');
                include_once $controller;
                if(!in_array($route->getController(), $denyPrevRouteSaves)) $route->setPrevRoute(); // Save previous route
            }
            catch(Exception $exc)
            {
                error_log($exc);
                $errMsg = 'An unexpected error occurred. Please accept our apologies as we will resolve this issue asap.';
                if($lang === 'nl')
                    $errMsg = 'Er is een onverwachte fout opgetreden. Onze excuses, we zullen dit probleem zo snel mogelijk oplossen.';
                
                die($errMsg);
            }
            restore_error_handler();
        }
        // Session lockdown after controller did its job except for cookieless routes
        if(str_starts_with($route->getRouteName(), '_'))
        {
            header('Access-Control-Allow-Origin: *');
            header('Cache-Control: max-age=2419200, public, must-revalidate');
            header_remove('Cookie');
            header_remove('Set-Cookie');
        }
        else
            SessionManager::sessionWriteClose();
    }
    fclose($stream);
    exit(0);
}
print_r('<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">');
