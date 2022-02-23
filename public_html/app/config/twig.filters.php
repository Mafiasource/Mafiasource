<?PHP

use src\Business\SeoService;

/* TWIG GLOBALS */
$twig->addGlobal('docRoot', PROTOCOL . $_SERVER['HTTP_HOST']);
$twig->addGlobal('staticRoot', PROTOCOL . STATIC_SUBDOMAIN . "." . $route->settings['domainBase']);

/* Twig filter functions (Can be used in the entire application by their PHP func name too, required in front-controller) */
function isstr($str)
{
    return is_string($str);
}

function implodeComma($arr)
{
    return implode(',', (array)$arr);
}

function moneyFormat($str)
{
    return "$" . number_format((int)$str,0,'',',');
}

function valueFormat($str)
{
    return number_format((int)$str, 0, '', ',');
}

function strip($in)
{
    return trim(html_entity_decode(preg_replace("/&nbsp;/", "", (string)strip_tags((string)$in))));
}

function removeBreaks($output)
{
    $output = str_replace(array("\r\n", "\r"), "\n", (string)$output);
    $lines = explode("\n", $output);
    $new_lines = array();
    
    foreach ($lines AS  $line)
    {
        if(!empty($line))
            $new_lines[] = trim($line);
    }
    return implode($new_lines);
}

function xssEscapeAndHtml($input)
{
    global $security;
    return $security->xssEscapeAndHtml($input);
}

function secondsToPlaytime($s)
{
    $s = (int)$s;
    $days = round(floor((int)($s / 86400)));
    $hours = round(floor((int)($s / 3600) % 24));
    $minutes = round(floor((int)($s / 60) % 60));
    $seconds = (int)($s % 60);
    if(isset($_COOKIE['lang']) && $_COOKIE['lang'] == "en")
        return 'Online playtime: '.$days.' Days '.$hours.' Hours '.$minutes.' Minutes '.$seconds.' Seconds';
    
    return 'Online speeltijd: '.$days.' Dagen '.$hours.' Uren '.$minutes.' Minuten '.$seconds.' Seconden';
}

function pureHtml($in)
{
    return htmlentities((string)$in);
}

function intToString($int, $l = 4)
{
    if($l < 4) $l = 4;
    if($l > 32) $l = 32;
    return substr(md5((string)$int), 1, $l);
}

function lotteryTicket($int)
{
    return intToString((int)$int, 5);
}

function isJson($string){
   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

/* Remove possible XSS attacks in images */
function noXSS ($input)
{
    $inputTmp = trim(asciiDecode(strtolower($input)));
    if (substr($inputTmp, 0, 11) == 'javascript:')
        $input = "";
    
    return $input;
}

function asciiDecode($input)
{
    preg_match_all("(&#([0-9]{1,3});)", $input, $matches);
    $asciiCodes = array_unique($matches[1]);
    foreach ($asciiCodes as $asciiNr)
        $input = str_replace("&#$asciiNr;", chr($asciiNr), $input);
    
    return $input;
} 

function deXSS_img($m){
    global $security;
    $m[1] = $security->xssEscape($m[1]);
    $m[1] = strtok($m[1],'?');
    $accept = array("png", "gif", "jpg");
    $r = "<img src=\"".noXSS($m[1])."\" alt=\"\">";
    if (!preg_match('/\.('.implode('|', $accept).')$/', $m[1]))
        $r = "<img src=\"/web/public/images/users/nopic.jpg\" alt=\"\">";
    
    return $r;
}
/* //END Remove possible XSS attacks in images */

/* Counter functions */
function counter($i, $fetchedTime)
{
    global $langs;
    $msg = $langs['NOW']."!";
    $verschil = ($fetchedTime - time());
    if($verschil > 0)
    {
        global $twig;
        $msg = $twig->render("src/Views/game/js/time.count.twig", array('el' => $i, 'diff' => $verschil, 'type' => "", 'langs' => $langs));
    }
    return removeBreaks($msg);
}

function counterActive($i, $fetchedTime)
{
    $msg = "";
    $verschil = ($fetchedTime - time());
    if($verschil > 0)
    {
        global $twig;
        $msg = $twig->render("src/Views/game/js/time.count.twig", array('el' => $i, 'diff' => $verschil, 'type' => "A"));
    }
    return removeBreaks($msg);
}

function counterClean($i, $fetchedTime)
{
    $msg = "0";
    $verschil = ($fetchedTime - time());
    if($verschil > 0)
    {
        global $twig;
        $msg = $twig->render("src/Views/game/js/time.count.twig", array('el' => $i, 'diff' => $verschil, 'type' => "C"));
    }
    return removeBreaks($msg);
}

function counterPrisonMoney($i, $fetchedTime, $costs = 250)
{
    $msg = "0";
    $verschil = ($fetchedTime - time())*$costs;
    if($verschil > 0)
    {
        global $twig;
        $msg = $twig->render("src/Views/game/js/time.count.twig", array('el' => $i, 'diff' => $verschil, 'type' => "PM", 'langs' => false, 'costs' => $costs));
    }
    return removeBreaks($msg);
}

function countdownHmsTime($i, $fetchedTime)
{
    global $langs;
    $date = new \DateTime(date('H:i:s', $fetchedTime));
    $hours = number_format(floor($fetchedTime / (60*60)), 0);
    $minutes = $date->format('i');
    $seconds = $date->format('s');
    
    $msg = $langs['NONE'];
    $verschil = (time() - $fetchedTime);
    if($verschil > 0)
    {
        global $twig;
        $msg = $twig->render("src/Views/game/js/countdown.hms.twig", array('el' => $i, 'seconds' => $seconds, 'minutes' => $minutes, 'hours' => $hours, 'langs' => $langs));
    }
    return removeBreaks($msg);
}
/* //END counter functions */

/* Twig Filters */
$twig->addFilter(new \Twig\TwigFilter('var_dump', function ($in) { return var_dump($in); } ));
$twig->addFilter(new \Twig\TwigFilter('isstr', function ($in) { return isstr($in); } ));
$twig->addFilter(new \Twig\TwigFilter('implode', function ($in) { return implodeComma($in); } ));
$twig->addFilter(new \Twig\TwigFilter('lower', function ($in) { return strtolower((string)$in); } ));
$twig->addFilter(new \Twig\TwigFilter('moneyFormat', function ($in) { return moneyFormat($in); } ));
$twig->addFilter(new \Twig\TwigFilter('valueFormat', function ($in) { return valueFormat($in); } ));
$twig->addFilter(new \Twig\TwigFilter('strip', function ($in) { return strip($in); } ));
$twig->addFilter(new \Twig\TwigFilter('htmlEsc', function ($in) { return xssEscapeAndHtml($in); } ));
$twig->addFilter(new \Twig\TwigFilter('secondsToPlaytime', function ($in) { return secondsToPlaytime($in); } ));
$twig->addFilter(new \Twig\TwigFilter('pureHtml', function ($in) { return pureHtml($in); } ));
$twig->addFilter(new \Twig\TwigFilter('count', function ($i, $ft) { return counter($i, $ft); } ));
$twig->addFilter(new \Twig\TwigFilter('countAndActive', function ($i, $ft) { return counterActive($i, $ft); } ));
$twig->addFilter(new \Twig\TwigFilter('countClean', function ($i, $ft) { return counterClean($i, $ft); } ));
$twig->addFilter(new \Twig\TwigFilter('countPrisonMoney', function ($i, $ft, $costs = 250) { return counterPrisonMoney($i, $ft, $costs); } ));
$twig->addFilter(new \Twig\TwigFilter('countdownHmsTime', function($i, $ft) { return countdownHmsTime($i, $ft); } ));
$twig->addFilter(new \Twig\TwigFilter('seoUrl', function($in) {return SeoService::seoUrl($in); } ));
$twig->addFilter(new \Twig\TwigFilter('lotteryTicket', function($in) {return lotteryTicket($in); } ));
