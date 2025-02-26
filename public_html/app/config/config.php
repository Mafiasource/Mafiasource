<?PHP

/** Configuration file for some global values used throughout this entire application **/

require_once __DIR__ . '/../../../credentials.php'; // Require credentials

// Game, app and web settings
define('BASE_DOMAIN',      "mafiasource.nl");       // The primary domain
define('STATIC_SUBDOMAIN', "static");               // Static subdomain prefix | Has to be a valid subdomain rooted to the same dir of the application's root folder
define('APP_GAMENAME',     "Mafiasource");          // Gamename, obviously
define('APP_DOMAIN',       "www.".BASE_DOMAIN);     // Application runs on www. variant
define('APP_FB_PAGE',      "Mafiasource");          // Facebook page UNUSED
define('APP_TW_PAGE',      "Mafiasource");          // Twitter page UNUSED
define('APP_GP_PAGE',      "+MafiasourceBe-Nl-En"); // Google plus page UNUSED
define('SSL_ENABLED',      true);                   // HTTPS? true :-) / false :-( ?
define('DEVELOPMENT',      true);                   // Development mode true = on | false = off
define('OFFLINE',          false);                  // Website online / offline for userlogin / game access
define('DEVELOPER_IPS',    json_decode(DEV_IPS));   // Array containing developer IP addresses

// Database credentials
define('PDO_DATABASE',  DBNAME);                                      // Db name
define('PDO_CONSTRING', "mysql:host=".DBHOST.";dbname=".PDO_DATABASE); // Db conection string DO NOT CHANGE
define('PDO_DBUSER',    DBUSR);                                       // Db user, generally a user with the least permissions
define('PDO_DBPASS',    DBPWD);                                       // Db password

// Others
define('DOC_ROOT',      $_SERVER['DOCUMENT_ROOT']);
define('GAME_DOC_ROOT', realpath(__DIR__ . "/../../") . "/"); // DOC_ROOT identical but with absolute path for cronjobs

// Mail server settings
define('EMAIL_HOST', "mail.mafiasource.nl");
define('EMAIL_ADDR', "no-reply@mafiasource.nl");
define('EMAIL_PWD',  "noreplypwd");
define('EMAIL_PORT', false);
