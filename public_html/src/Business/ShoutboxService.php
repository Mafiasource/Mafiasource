<?PHP
 
namespace src\Business;

use src\Data\ShoutboxDAO;
use app\config\Routing;

/* Class has some serious cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */
 
class ShoutboxService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new ShoutboxDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public static function parseMessage($in)
    {
        $in = htmlspecialchars($in);
    	//$in = nl2br($in);
    	$in = addslashes($in);
        
        $patterns = array(
        	"#\[9\](.*?)\[/9\]#si",
        	"#\[8\](.*?)\[/8\]#si",
        	"#\[7\](.*?)\[/7\]#si",
        	"#\[6\](.*?)\[/6\]#si",
        	"#\[2=(.*?)\](.*?)\[/2\]#si",
        	"#\[1=(.*?)\](.*?)\[/1\]#si",
        	"#\[IMG\](.*?)\[/IMG\]#si",
        	"#\[vid\](.*?)\[/vid\]#si",
        	"#\[right\](.*?)\[/right\]#si"
    	);
    	$replaces = array(
        	"<b>\\1</b>",
        	"<u>\\1</u>",
        	"<i>\\1</i>",
        	"<s>\\1</s>",
        	"<font color=\"#\\1\">\\2</font>",
        	"<a href=\"\\1\" TARGET=\"\\_blank\"><img src='".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/foto/web/public/images/icons/monitor_go.png' style='margin-bottom:-4px;'/>&nbsp;<font color=#63C5FF><b>Link</b></font> \\2</a>",
        	"<a href=\"\\1\" TARGET=\"\\_blank\"><img src='".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/foto/web/public/images/icons/tag_blue.png'/>&nbsp;<font color=#767676><b>Afbeelding</b></font></a>",
        	"<a href=\"\\1\" TARGET=\"\\_blank\"><img src='".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/foto/web/public/images/icons/youtube_play.png' style='margin-left:-7px;display:inline-block;'/>&nbsp;<font color=#767676><b>Filmpje</b></font></a>",
        	"<div style=\"text-align: right;\" align=\"right\">\\1</div>"
    	); 
    	$in = preg_replace($patterns,$replaces, $in);
        
        // Special chars
        $specials = array(
            "&lt;3" => "&#9829;"
        );
        foreach($specials AS $key => $val)
        {
            $replace = $val;
            $in = str_ireplace($key,$replace,$in);
    	}
        
    	$smileys = array(
			":D" => "lol",
			":)" => "smile",
			";)" => "wink",
			":s" => "confused", 
			":|" => "neutral",
			":P" => "razz",
			":(" => "sad",
			":O" => "surprised",
			":twisted:" => "twisted",
			":cry:" => "cry",
			":mad:" => "mad",
			"8)" => "cool",
			":x" => "silenced",
			":?:" => "question",
			":!:" => "exclaim",
			":no:" => "naugthy",
			":huh:" => "ehh",
			":$" => "ashamed",
			":applause:" => "applause",
			":grin:" => "biggrin",
			":rolleyes:" => "rolleyes",
			":whistle:" => "whistle",
			"(A)" => "angel"
		);
    	foreach($smileys AS $key => $val)
        {
    		$replace = "<img src=\"/web/public/images/smileys/".$val.".gif\" alt=\"\">";
    		$in = str_ireplace($key, $replace, $in);
    	}
    	return htmlspecialchars_decode(stripslashes($in));
    }
    
    public function postMessage($post)
    {
        global $userData;
        global $language;
        global $langs;
        $l               = $language->shoutboxLangs();
        global $security;
        $famID           = (int)$_POST['famID'];
        $message = $security->xssEscape($post['message']);
        
        if(!isset($_SESSION['UID']))
        {
            global $route;
            $route->headTo('home');
            exit(0);
        }
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(strlen($message) < 2 || strlen($message) > 200)
        {
            $error = $l['MESSAGE_NOT_IN_RANGE'];
        }
        if($famID != 0)
        {
            if($famID != $userData->getFamilyID())
            {
                $error = $l['INVALLID_FAMILY'];
            }
        }
        if($this->data->checkLastShoutMessageSender($famID) == $_SESSION['UID'] && $userData->getStatus() >=7)
        {
            $error = $l['WAIT_TILL_SOMEBODY_ELSE_POSTED'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $this->data->postMessage($famID, $message);
            return Routing::successMessage($langs['POST_SUCCESS']);
        }
    }
    
    public function setFamilyID($famID)
    {
        $this->data->familyID = $famID;
    }
    
    public function getLastMessageID()
    {
        return $this->data->getLastMessageID();
    }
    
    public function getMessageRows($from, $to)
    {
        return $this->data->getMessageRows($from, $to);
    }
    
    public function getMessageRowsIds($from, $to)
    {
        return $this->data->getMessageRowsIds($from, $to);
    }
}
