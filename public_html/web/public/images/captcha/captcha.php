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

use app\config\Routing;
use src\Business\Logic\SeoURL;

require_once __DIR__.'/../../../../app/config/routing.php';
require_once __DIR__.'/../../../../vendor/SessionManager.php';
require_once __DIR__.'/../../../../src/Business/Logic/SeoURL.php';
$route = new Routing();

// Set error reporting according to DEVELOPMENT global (/app/config/config.php)
$errRepInt = DEVELOPMENT === true ? 1 : 0;
error_reporting(-1);
ini_set('log_errors', 1);
ini_set('display_errors', $errRepInt);
ini_set('display_startup_errors', $errRepInt);
$errRepInt = null;

// Start session
$session = new SessionManager();
$seoURL = new SeoURL();
ini_set('session.save_handler', 'files');
session_set_save_handler($session, true);
session_save_path(__DIR__ . '/../../../../app/cache/sessions');
SessionManager::sessionStart(SeoURL::format($route->settings['gamename']), 0, '/', $route->settings['domain'], SSL_ENABLED);
$session = $seoURL = null;

$imageWidth = 150;
$imageHeight = 50;
$charactersCount = rand(3, 4);
$rFont = rand(1, 7);
$font = __DIR__."/fonts/" . $rFont . "-webfont.ttf";

$possibleNumbers = "0123456789";
$randomDots = rand(30,40);
$randomLines = rand(15,20);
if(rand(1, 2) == 2)
{
    $captchaTextColor = "0x".rand(835014,935014)."";
    $captchaNoiseColor = "0x".rand(135014,535014)."";
}
else
{
    $captchaTextColor = "0x".rand(842864,942864)."";
    $captchaNoiseColor = "0x".rand(242864,642864)."";
}

$image = @imagecreate($imageWidth, $imageHeight);

$background_color = imagecolorallocate($image, 255, 255, 255);

$textColorArr = hexrgb($captchaTextColor); 
$textColor = imagecolorallocate($image, $textColorArr['red'], 
$textColorArr['green'], $textColorArr['blue']);

$noiseColorArr = hexrgb($captchaNoiseColor); 
$noiseColor = imagecolorallocate($image, $noiseColorArr['red'], 
$noiseColorArr['green'], $noiseColorArr['blue']);

for($i = 0; $i < $randomDots; $i++)
{
    imagefilledellipse($image, mt_rand(0, $imageWidth),
    mt_rand(0, $imageHeight), 2, 3, $noiseColor);
}

for($i = 0; $i < $randomLines; $i++)
{
    imageline($image, mt_rand(0, $imageWidth), mt_rand(0, $imageHeight),
    mt_rand(0, $imageWidth), mt_rand(0, $imageHeight), $noiseColor);
}

$code = "";
$len = strlen($possibleNumbers);
$letter = $possibleNumbers[rand(0, $len - 1)];
for ($i = 0; $i < $charactersCount; $i++)
{
    $letter = $possibleNumbers[rand(0, $len - 1)];
    imagettftext($image, rand(24, 36), rand(-28, 28), rand(16, 21) + ($i * rand(25, 35)), rand(28, 42), $textColor, $font , $letter);
    $code .= $letter;
}

header('Content-Type: image/jpeg');
imagejpeg($image);
imagedestroy($image);
$_SESSION['code_captcha'] = $code;

function hexrgb ($hexstr) 
{
    $int = hexdec($hexstr);
    return array(
        "red" => 0xFF & ($int >> 0x10),
        "green" => 0xFF & ($int >> 0x8),
        "blue" => 0xFF & $int
    );
}
