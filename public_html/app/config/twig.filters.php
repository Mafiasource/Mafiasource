<?PHP

use src\Business\SeoService;

/* TWIG GLOBALS */
$twig->addGlobal('docRoot', PROTOCOL.$_SERVER['HTTP_HOST']);
$twig->addGlobal('staticRoot', PROTOCOL.STATIC_SUBDOMAIN.".".$route->settings['domainBase']);

/* Twig filter functions (Can be used in the entire application by their PHP func name too, set in front-controller) */
function isstr($str)
{
    if(!is_numeric($str))
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

function implodeComma($arr)
{
    return implode(',',$arr);
}

function moneyFormat($str)
{
    return "$".number_format($str,0,'',',');
}

function valueFormat($str)
{
    return number_format($str,0,'',',');
}

function strip($in)
{
    return trim(html_entity_decode(preg_replace("/&nbsp;/", "", strip_tags($in))));
}

function removeBreaks($output)
{
    $output = str_replace(array("\r\n", "\r"), "\n", $output);
    $lines = explode("\n", $output);
    $new_lines = array();
    
    foreach ($lines as $i => $line) {
        if(!empty($line))
            $new_lines[] = trim($line);
    }
    return implode($new_lines);
}

function ucfirstt($string)
{
    return ucfirst(strtolower($string));
}

function DOMinnerHTML(DOMNode $element) 
{ 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    foreach ($children as $child) 
    { 
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }

    return $innerHTML; 
} 

function xssEscapeAndHtml($input)
{
    global $security;
    return $security->xssEscapeAndHtml($input);
}

function secondsToPlaytime($s)
{
    $days = floor($s / 86400);
    $hours = floor(($s / 3600) % 24);
    $minutes = floor(($s / 60) % 60);
    $seconds = $s % 60;
    if(isset($_COOKIE['lang']) && $_COOKIE['lang'] == "en")
    {
        return 'Online playtime: '.$days.' Days '.$hours.' Hours '.$minutes.' Minutes '.$seconds.' Seconds';
    }
    else
    {
        return 'Online speeltijd: '.$days.' Dagen '.$hours.' Uren '.$minutes.' Minuten '.$seconds.' Seconden';
    }
}

function pureHtml($in)
{
    return htmlentities($in);
}

function uniqueString($in, $l = 4)
{
    if($l < 4) $l = 4;
    if($l > 32) $l = 32;
    return substr(md5(uniqid($in, true)), 0, $l);
}

function intToString($i, $l = 4)
{
    if($l < 4) $l = 4;
    if($l > 32) $l = 32;
    return substr(md5($i), 1, $l);
}

function lotteryTicket($in)
{
    return intToString($in, 5);
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
    if (!preg_match('/\.('.implode('|', $accept).')$/', $m[1], $matches))
        $r = "<img src=\"/web/public/images/users/nopic.jpg\" alt=\"\">";
    else
    	$r = "<img src=\"".noXSS($m[1])."\" alt=\"\">";
    
    return $r;
}
/* //END Remove possible XSS attacks in images */

/* Counter functions | UGLY TO DO */
function counter($i, $fetchedTime)
{
    global $langs;
    $verschil = ($fetchedTime - time());
    if($verschil > 0)
    {
        $msg = "
        <script type='text/javascript'>
             var seconds". $i."=". $verschil.";
             var timeoutC".$i." = '';
             function displayC". $i."()
             {
               seconds". $i."=seconds". $i."-1;
               if(seconds". $i." < 0)
               {
                  var countdown". $i." = document.all? document.all[\"c".$i."\"] : document.getElementById ? document.getElementById (\"c". $i."\") 
                  : \"\";
                  countdown". $i.".innerHTML=\"".$langs['NOW']."!\";
               }
               else
               {
                var countdown". $i." = document.all? document.all[\"c".$i."\"] : document.getElementById ? document.getElementById (\"c". $i."\") 
                : \"\";
                if (countdown". $i.")
                {
                    countdown". $i.".innerHTML=seconds". $i."+'!';
                    timeoutC".$i." = setTimeout('displayC". $i."()',1000);
                }
               }
             }  
            displayC". $i."();
        </script>
        ";
    }
    else
    {
        $msg = $langs['NOW']."!";
    }
    return removeBreaks($msg);
}

function counterActive($i, $fetchedTime)
{
    $verschil = ($fetchedTime - time());
    if($verschil > 0)
    {
        $msg = "
        <script type='text/javascript'>
             var seconds". $i."=". $verschil.";
             var timeoutCA".$i." = '';
             function displayCA". $i."()
             {
               seconds". $i."=seconds". $i."-1;
               if(seconds". $i." < 0)
               {
                  var countdown". $i." = document.all? document.all[\"c".$i."\"] : document.getElementById ? document.getElementById (\"c". $i."\") 
                  : \"\";
                  countdown". $i.".innerHTML=\"\";
                  var par = countdown". $i.".parentNode;
                  par.classList.add('active');
               }
               else
               {
                var countdown". $i." = document.all? document.all[\"c".$i."\"] : document.getElementById ? document.getElementById (\"c". $i."\") 
                : \"\";
                if (countdown". $i.")
                {
                    countdown". $i.".innerHTML=seconds". $i.";
                    timeoutCA".$i." = setTimeout('displayCA". $i."()',1000);
                }
               }
             }  
            displayCA". $i."();
        </script>
        ";
    }
    else
    {
        $msg = "";
    }
    return removeBreaks($msg);
}

function counterClean($i, $fetchedTime)
{
    $verschil = ($fetchedTime - time());
    if($verschil > 0)
    {
        $msg = "
        <script type='text/javascript'>
             var seconds". $i."=". $verschil.";
             var timeoutCC".$i." = '';
             function displayCC". $i."()
             {
               seconds". $i."=seconds". $i."-1;
               if(seconds". $i." < 0)
               {
                  var countdown". $i." = document.all? document.all[\"c".$i."\"] : document.getElementById ? document.getElementById (\"c". $i."\") 
                  : \"\";
                  countdown". $i.".innerHTML=\"0\";
               }
               else
               {
                var countdown". $i." = document.all? document.all[\"c".$i."\"] : document.getElementById ? document.getElementById (\"c". $i."\") 
                : \"\";
                if (countdown". $i.")
                {
                    countdown". $i.".innerHTML=seconds". $i."+'';
                    timeoutCC".$i." = setTimeout('displayCC". $i."()',1000);
                }
               }
             }  
            displayCC". $i."();
        </script>
        ";
    }
    else
    {
        $msg = "0";
    }
    return removeBreaks($msg);
}

function counterPrisonMoney($i, $fetchedTime, $costs = 250)
{
    $verschil = ($fetchedTime - time())*$costs;
    if($verschil > 0)
    {
        $msg = "
        <script type='text/javascript'>
             var seconds" . $i . "=" . $verschil . ";
             var timeoutCPM" . $i . " = '';
             function displayCPM" . $i . "()
             {
               seconds" . $i . "=seconds" . $i . "-" . $costs . ";
               if(seconds". $i." < 0)
               {
                  var countdown". $i ." = document.all? document.all[\"c" . $i . "\"] : document.getElementById ? document.getElementById (\"c" . $i . "\") 
                  : \"\";
                  countdown". $i .".innerHTML=\"0\";
               }
               else
               {
                var countdown" . $i . " = document.all? document.all[\"c" . $i . "\"] : document.getElementById ? document.getElementById (\"c" . $i . "\") 
                : \"\";
                if (countdown" . $i . ")
                {
                    countdown" . $i . ".innerHTML=number_format(seconds" . $i . ",0,'',',')+'';
                    timeoutCPM" . $i . " = setTimeout('displayCPM" . $i . "()',1000);
                }
               }
             }  
            displayCPM" . $i . "();
        </script>
        ";
    }
    else
    {
        $msg = "0";
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
    
    $verschil = (time() - $fetchedTime);
    if($verschil > 0)
    {
        $msg = "
        <script type='text/javascript'>
             var seconds". $i ."=". $seconds .";
             var minutes". $i ."=". $minutes .";
             var hours". $i ."=". $hours .";
             var timeoutCHT". $i ." = '';
             function displayCHT". $i ."()
             {
               var secTrail = '';
               var minTrail = '';
               var hourTrail = '';
               if(seconds". $i ." == 0)
               {
                if(minutes". $i ." == 0)
                {
                    hours". $i ."=hours". $i ."-1;
                    minutes". $i ."=60;
                }
                minutes". $i ."=minutes". $i ."-1;
                seconds". $i ."=60;
               }
               seconds". $i ."=seconds". $i ."-1;
               if(String(hours". $i .").length == 1)
               {
                 hourTrail = '0';
               }
               if(String(minutes". $i .").length == 1)
               {
                 minTrail = '0';
               }
               if(String(seconds". $i .").length == 1)
               {
                 secTrail = '0';
               }
               if(seconds". $i ." == 0 && minutes". $i ." == 0 && hours". $i ." == 0)
               {
                  var countdown". $i ." = document.all? document.all[\"c". $i ."\"] : document.getElementById ? document.getElementById (\"c". $i ."\") 
                  : \"\";
                  countdown". $i.".innerHTML=\" ".$langs['NONE']." \";
               }
               else
               {
                var countdown". $i ." = document.all? document.all[\"c". $i ."\"] : document.getElementById ? document.getElementById (\"c". $i ."\") 
                : \"\";
                if (countdown". $i .")
                {
                    countdown". $i.".innerHTML=hourTrail+hours". $i ."+':'+minTrail+minutes". $i ."+':'+secTrail+seconds". $i ."+'';
                    timeoutCHT". $i ." = setTimeout('displayCHT". $i ."()',1000);
                }
               }
             }  
            displayCHT". $i."();
        </script>
        ";
    }
    else
    {
        $msg = $langs['NONE'];
    }
    return removeBreaks($msg);
}
/* //END counter functions */

/* Twig Filters */
$twig->addFilter(new \Twig\TwigFilter('var_dump', function ($in) { return var_dump($in); } ));
$twig->addFilter(new \Twig\TwigFilter('isstr', function ($in) { return isstr($in); } ));
$twig->addFilter(new \Twig\TwigFilter('ucfirst', function ($in) { return ucfirstt($in); } )); /** !!!???! **/
$twig->addFilter(new \Twig\TwigFilter('implode', function ($in) { return implodeComma($in); } )); /** !!!???! **/
$twig->addFilter(new \Twig\TwigFilter('lower', function ($in) { return strtolower($in); } ));
$twig->addFilter(new \Twig\TwigFilter('moneyFormat', function ($in) { return moneyFormat($in); } ));
$twig->addFilter(new \Twig\TwigFilter('valueFormat', function ($in) { return valueFormat($in); } ));
$twig->addFilter(new \Twig\TwigFilter('strip', function ($in) { return strip($in); } ));
$twig->addFilter(new \Twig\TwigFilter('strip_tags', function ($in) { return strip_tags($in); } ));
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
