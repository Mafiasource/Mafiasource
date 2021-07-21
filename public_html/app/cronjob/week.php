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

/** CRON WEEK RUNS EVERY FRIDAY AT 7 P.M. Europe Amsterdam TIME **/

use Doctrine\Common\ClassLoader;
use app\config\Security;
use src\Data\config\DBConfig;

/* Error reporting (debugging) */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

/* Set correct timezone */
ini_set('date.timezone', 'Europe/Amsterdam');

/* Define game_doc_root & database credentials (included in config) */
require_once __DIR__ . '/../config/config.php';

/* Enable Autoloading with doctrine */
require_once GAME_DOC_ROOT . '/vendor/autoload.php';
$srcLoader = new ClassLoader('src'   ,  GAME_DOC_ROOT);
$srcLoader->register();

/* Open db connection */
$con = new DBConfig();

/* Security class (for true randomness) * /
require_once GAME_DOC_ROOT.'/app/config/security.php';
$security = new Security(); */

/** ALL CRON WEEK RELATED CODE START FROM HERE **/
/**
/* Shoutbox reset * /
$con->setData("
    DELETE FROM `shoutbox_en` WHERE `date`< :date;
    DELETE FROM `shoutbox_nl` WHERE `date`< :date
", array(':date' => date('Y-m-d H:i:s', strtotime('-7 days')))); // /CHECKED & OK
**/
