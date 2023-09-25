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

/** Front-controller - Main entry point installation of web application **/

use Doctrine\Common\ClassLoader;
use app\config\Routing;
use app\config\Security;
use install\config\InstallService;

// Set correct timezone
ini_set('date.timezone', 'Europe/Amsterdam');

// Include routing: controller > views
require_once __DIR__.'/../app/config/routing.php';
$route = new Routing();

// Set error reporting according to DEVELOPMENT global (/app/config/config.php)
$errRepInt = DEVELOPMENT === true ? 1 : 0;
ini_set('log_errors', "1");
ini_set('display_errors', (string)$errRepInt);
ini_set('display_startup_errors', (string)$errRepInt);
if($errRepInt === 0)
    error_reporting($errRepInt);
else
    error_reporting(-1);

$errRepInt = null;

$stream = @stream_socket_server('tcp://0.0.0.0:7600', $errno, $errmg, STREAM_SERVER_BIND);
if($stream)
{
    require __DIR__.'/../vendor/autoload.php';
    // Enable Autoloading with doctrine
    $classLoader = new ClassLoader('install'   ,   DOC_ROOT);
    $classLoader->register();
    $classLoader = null;
    
    // Start a (non-secure) installation session (Allow 'temp' sessions in install env)
    require_once __DIR__.'/../vendor/SessionManager.php';
    $session = new SessionManager();
    ini_set('session.save_handler', 'files');
    session_set_save_handler($session, true);
    session_save_path(__DIR__ . '/../app/cache/sessions');
    SessionManager::sessionStart("Mafiasource_Install");
    $session = null;
    
    // Security class (Anti CSRF, XSS attacks & more)
    require_once __DIR__.'/../app/config/security.php';
    $security = new Security();
    
    // Define PROTOCOL used throughout the application http / https? see: app/config/config.php
    if($route->settings['ssl'] === true) define('PROTOCOL', "https://"); else define('PROTOCOL', "http://");
    
    // Enable Twig engine & set some custom filters used throughout the application
    $loader = new \Twig\Loader\FilesystemLoader(DOC_ROOT); // Root templates folder to DOC root (Because we have tmpls in app/ and src/ )
    $twig = new \Twig\Environment($loader, [
        'cache' => $route->settings['twigCache'] // Caching? depends on dev mode see: app/config/config.php
    ]);
    $loader = null;
    require_once __DIR__.'/../app/config/twig.filters.php';
    
    $allowedFields = array("game_name", "domain", "db_host", "db_name", "db_user", "db_pwd", "username", "password", "password_check", "email");
    
    if(!isset($_SESSION['install']['fields']))
        $_SESSION['install']['fields'] = array('game_name' => "", 'domain' => "", 'db_host' => "", 'db_name' => "", 'db_user' => "", 'username' => "", 'email' => "");
    
    $acceptPost = true;
    foreach($allowedFields AS $field)
        if(!isset($_POST[$field]))
            $acceptPost = false;
    
    if(isset($_POST['submit_install']) && $acceptPost)
    {
        $dbHost = !empty($_POST['db_host']) ? $_POST['db_host'] : "localhost";
        $installService = new InstallService($dbHost, $_POST['db_name'], $_POST['db_user'], $_POST['db_pwd']);
        
        $response = $installService->validateInstall($_POST);
        
        $route->createActionMessage($response);
        header("HTTP/2 301 Moved Permanently");
        header('Location: /install/index.php', TRUE, 301);
        exit(0);
    }
    elseif(!isset($_SESSION['install']['masterEncryption']) || (isset($_GET['encryption']) && $_GET['encryption'] === "true"))
    {
        $installService = new InstallService("localhost", "ms", "root", "");
        
        $credentialsFile = DOC_ROOT . '/../credentials.php';
        $credentialsReplacesMap = array();
        $masterEncryption = InstallService::generateMasterEncryptionIvAndKey();
        $credentialsReplacesMap[8] = "define('MASTERIV', base64_decode('" . base64_encode($masterEncryption['iv']) . "'));";
        $credentialsReplacesMap[9] = "define('MASTERKEY', base64_decode('" . base64_encode($masterEncryption['key']) . "'));";
        InstallService::replaceLinesByLineNumbers($credentialsFile, $credentialsReplacesMap);
        $_SESSION['install']['masterEncryption'] = true;
        
        $route->createActionMessage(Routing::successMessage("New master encryption keys were generated and written to the credentials file."));
        header("HTTP/2 301 Moved Permanently");
        header('Location: /install/index.php?encryption=encrypted', TRUE, 301);
        exit(0);
    }
    
    $encrypted = isset($_GET['encryption']) && $_GET['encryption'] === "encrypted" ? true : false;
    $message = $route->setActionMessage();
    $twigVars = array(
        'routing' => $route,
        'settings' => $route->settings,
        'securityToken' => $security->getToken(),
        'message' => $message,
        'domain' => $_SERVER['HTTP_HOST'],
        'previousFields' => $_SESSION['install']['fields'],
        'encrypted' => $encrypted,
        'protocol' => PROTOCOL,
        'offline' => OFFLINE
    );
    
    if($encrypted)
        echo $twig->render('/install/Views/encryption.twig', $twigVars);
    else
        echo $twig->render('/install/Views/index.twig', $twigVars);
    
    // Session lockdown after controller did its job
    SessionManager::sessionWriteClose();
    fclose($stream);
}
