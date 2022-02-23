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

/** CRON DB BACKUP ACCORDING TO WEB/INDEX.PHP THIS SHOULD RUN EVERYDAY AT 4:00 A.M. Europe Amsterdam TIME **/

use Doctrine\Common\ClassLoader;
use app\config\Security;
use src\Data\config\DBConfig;

/* Error reporting (debugging) */
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
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

/** ALL DB BACKUP RELATED CODE START FROM HERE **/

/* Automatic DB backup */
$backupType = isset($adminReset) ? "resetBackup" : "autoBackup";
$backupTables = "*"; // Which tables? * = all (seperate = `airplane`, `user`, ..)
if($backupTables == '*')
{
	$extname = "ms";
}
else
{
	$extname = str_replace(",", "_", $backupTables);
	$extname = str_replace(" ", "_", $extname);
}
$saveFile = __DIR__ . '/backup/' . date("d.m.Y_H_i_s") . '_' . $backupType . '_' . $extname;
$backupType = $extname = null;
$return = "";	
if($backupTables == '*')
{
	$backupTables = array();
	$result = $con->getData("SHOW TABLES");
	foreach($result AS $row)
    {
		$backupTables[] = $row[0];
	}
}
else
{
	if(is_array($backupTables))
    {
		$backupTables = explode(',', $backupTables);
	}
}
foreach($backupTables as $table)
{
	$result = $con->getData("SELECT * FROM " . $table);
    
	$return .= "DROP TABLE IF EXISTS `" . $table . "`;";
    
	$row2 = $con->getDataSR("SHOW CREATE TABLE " . $table);
	$return .= "\n\n" . $row2[1] . ";\n\n";
    
    foreach($result AS $row)
    {
        $num_fields = count($row) / 2; // All fields counted double once integer keys, other textual field keys.
		$return .= "INSERT INTO " . $table . " VALUES(";
        foreach($row AS $k => $v)
        {
            if(is_numeric($k)) // Only work with the numeric key values, ()avoids duplicates)
            {
                $v = addslashes((string)$v);
				$v = preg_replace("/\n/", "\\n", $v);
				if(isset($v))
                {
                    $return .= '"' . $v . '"';
                }
                else
                {
                    $return .= '""';
                }
                if($k < (round($num_fields) - 1))
                {
                    $return .= ',';
                }
            }
        }
		$return .= ");\n";
	}
	$return .= "\n\n\n";
}
$handle = fopen($saveFile . ".sql", 'w+');
$backupTables = $con = $result = $row = $row2 = null;
fwrite($handle, $return);
fclose($handle); // /CHECKED & OK */
