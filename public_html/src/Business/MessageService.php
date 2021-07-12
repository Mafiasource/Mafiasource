<?PHP
 
namespace src\Business;

use src\Data\MessageDAO;
use app\config\Routing;

/* Class has some serious cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */

class MessageService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new MessageDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public static function BBParser($in)
    {
    	$in = htmlspecialchars($in);
    	$in = nl2br($in);
   		$in = addslashes($in);
    	$in = str_replace("[color=#", "[color=", $in);
    	$patterns = array(
        	"#\[b\](.*?)\[/b\]#si",
        	"#\[u\](.*?)\[/u\]#si",
        	"#\[ul\](.*?)\[/ul\]#si",
        	"#\[i\](.*?)\[/i\]#si",
        	"#\[s\](.*?)\[/s\]#si",
        	"#\[center\](.*?)\[/center\]#si",
        	"#\[color=(.*?)\](.*?)\[/color\]#si",
        	"#\[size=(.*?)\](.*?)\[/size\]#si",
        	"#\[right\](.*?)\[/right\]#si"
    	);
    	$replaces = array(
        	"<b>\\1</b>",
        	"<u>\\1</u>",
        	"<ul>\\1</ul>",
        	"<i>\\1</i>",
        	"<s>\\1</s>",
        	"<center>\\1</center>",
        	"<font color=\"#\\1\">\\2</font>",
        	"<font size=\"\\1\">\\2</font>",
        	"<div style=\"text-align: right;\" align=\"right\">\\1</div>"
    	); 
    	$in = preg_replace($patterns,$replaces, $in);
        
        $in = preg_replace($patterns,$replaces, $in);
        $in = preg_replace_callback("#\[img\](.*?)\[/img\]#si", "deXSS_img", $in);
        
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
    
    public function replyToMessage($post)
    {
        global $language;
        global $langs;
        $l = $language->messagesLangs();
        global $security;
        $receiver = $security->xssEscape($post['receiver']);
        $message = $security->xssEscape($post['message']);
        global $userService;
        
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
        if(strlen($message) < 2 || strlen($message) > 1000)
        {
            $error = $l['MESSAGE_NOT_IN_RANGE'];
        }
        
        $id = $userService->getIdByUsername($receiver);
        if($id == FALSE)
        {
            $error = $langs['PLAYER_DOESNT_EXIST'];
        }
        if($id == $_SESSION['UID'])
        {
            $error = $l['NO_MESSAGE_TO_SELF'];
        }
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        elseif(!isset($error))
        {
            $this->data->replyToMessage($id, $message);
            return TRUE;
        }
    }
    
    public function getLatestMessages()
    {
        return $this->data->getLatestMessages();
    }
    
    public function getLastMessage()
    {
        return $this->data->getLastMessage();
    }
    
    public function getLastMessagesByReceiverId($rid)
    {
        return $this->data->getLastMessagesByReceiverId($rid);
    }
    
    public function getLastMessagesIdsByReceiverId($rid)
    {
        return $this->data->getLastMessagesIdsByReceiverId($rid);
    }
}
