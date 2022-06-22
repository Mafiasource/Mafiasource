<?PHP

namespace src\Business;

use src\Business\SeoService;
use src\Data\ForumDAO;
use app\config\Routing;

/* Class has some serious cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */
 
class ForumService
{
    private $data;
    
    public function __construct($familyID, $category = false, $topicUrl = false, $reactionID = false)
    {
        $this->data = new ForumDAO($familyID);
        if($category != false && $topicUrl == false && $reactionID == false)
        {
            $categoryID = $this->getCategoryIdByCategory($category);
            if($categoryID > 0)$this->data = new ForumDAO($familyID, $categoryID);
        }
        elseif($category != false && $topicUrl != false && $reactionID == false)
        {
            $categoryID = $this->getCategoryIdByCategory($category);
            $topicID = $this->data->getTopicIdByCategoryIdAndTopicUrl($categoryID, $topicUrl);
            if($categoryID > 0 && $topicID > 0)$this->data = new ForumDAO($familyID, $categoryID, $topicID);
        }
        elseif($category != false && $topicUrl != false && $reactionID != false)
        {
            $categoryID = $this->getCategoryIdByCategory($category);
            $topicID = $this->data->getTopicIdByCategoryIdAndTopicUrl($categoryID, $topicUrl);
            $reactionID = $this->data->getReactionIdById($reactionID);
            if($categoryID > 0 && $topicID > 0 && $reactionID > 0)$this->data = new ForumDAO($familyID, $categoryID, $topicID, $reactionID);
        }
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public static function convertEmoticons($in)
    {
    	$in = htmlspecialchars($in);
    	//$in = nl2br($in);
   		$in = addslashes($in);
        
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
    
    public function navigate()
    {
        global $route;
        $base = $route->requestGetParam(1);
        $category = $route->requestGetParam(3);
        $topic = $route->requestGetParam(4);
        if($base == "forum")
        {
            $category = $route->requestGetParam(2);
            $topic = $route->requestGetParam(3);
        }
        if($category !== FALSE && $topic === FALSE && $base == "game" && $this->getCategoryIdByCategory($category) > 0) //Ingame route
        {
            $categoryID = $this->getCategoryIdByCategory($category);
            return "<p><a href='/game/forum'>&raquo; Forum</a>&nbsp;<a href='/game/forum/".$category."'>&raquo; ".$this->data->getCategoryNameByCategoryID($categoryID)."</a></p>";
        }
        elseif($category !== FALSE && $topic !== FALSE && $base == "game" && $this->getCategoryIdByCategory($category) > 0 && $this->getTopicIdByCategoryAndTopicUrl($category, $topic)) //Ingame route
        {
            $categoryID = $this->getCategoryIdByCategory($category);
            return "<p><a href='/game/forum'>&raquo; Forum</a>&nbsp;<a href='/game/forum/".$category."'>&raquo; ".$this->data->getCategoryNameByCategoryID($categoryID)."</a>&nbsp;<a href='/game/forum/".$category."/".$topic."'>&raquo; ".self::convertEmoticons($this->data->getTopicTitleByCategoryIdAndTopicUrl($categoryID, $topic))."</a></p>";
        }
        elseif($category !== FALSE && $topic === FALSE && $base == "forum" && $this->getCategoryIdByCategory($category) > 0) //Outgame route
        {
            $categoryID = $this->getCategoryIdByCategory($category);
            return "<p><a href='/forum'>&raquo; Forum</a>&nbsp;<a href='/forum/".$category."'>&raquo; ".$this->data->getCategoryNameByCategoryID($categoryID)."</a></p>";
        }
        elseif($category !== FALSE && $topic !== FALSE && $base == "forum" && $this->getCategoryIdByCategory($category) > 0 && $this->getTopicIdByCategoryAndTopicUrl($category, $topic)) //Outgame route
        {
            $categoryID = $this->getCategoryIdByCategory($category);
            return "<p><a href='/forum'>&raquo; Forum</a>&nbsp;<a href='/forum/".$category."'>&raquo; ".$this->data->getCategoryNameByCategoryID($categoryID)."</a>&nbsp;<a href='/forum/".$category."/".$topic."'>&raquo; ".self::convertEmoticons($this->data->getTopicTitleByCategoryIdAndTopicUrl($categoryID, $topic))."</a></p>";
        }
        else return '';
    }
    
    public function getCategoryIdByCategory($category)
    {
        switch($category)
        {
            case 'ideeen-forum': // Dutch SeoURL hardcode fix
                $categoryID = 3;
                break;
            default:
                $categoryID = $this->data->getCategoryIdByCategory(SeoService::deSeoUrl($category));
                break;
        }
        return $categoryID;
    }
    
    public static function createUniqueId($l = 6)
    {
        global $security;
        return $security->randStr($l);
    }
    
    public function createNewTopic($post)
    {
        global $userData;
        global $language;
        global $langs;
        $l        = $language->forumLangs();
        global $security;
        $title    = $security->xssEscape($post['topic-title']);
        $message  = $security->xssEscapeAndHtml($post['topic-message']);
        $cleanUri = SeoService::seoUrl($post['topic-title']);
        
        $categoryData = $this->data->getCategoryById($this->data->getCategoryIdByCategory($post['category']));
        
        if($_POST['security-token'] != $security->getToken())
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(strlen(strip_tags($message)) < 2 || strlen(strip_tags($message)) > 10000)
        {
            $error = $l['TOPIC_TOO_SHORT_OR_LONG'];
        }
        if(strlen($title) < 2 || strlen($title) > 100)
        {
            $error = $l['TITLE_TOO_SHORT_OR_LONG'];
        }
        if($categoryData == FALSE || empty($categoryData))
        {
            $error = $l['INVALID_CATEGORY_SELECTED'];
        }
        if(is_object($categoryData) && ($categoryData->getInteractStatusID() < $userData->getStatusID() || $categoryData->getDonatorID() > $userData->getDonatorID()))
        {
            $error = $l['NO_PERMISSIONS_TO_CREATE_TOPIC'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $successMsg = $l['TOPIC_ADDED_SUCCESS'];
            $successMsg .= "
                <script type='text/javascript'>
                    if($('textarea#articleField').length)
                    {
                        $('textarea#articleField').val('');
                    }
                    if($('input[name=topic-title]').length)
                    {
                        $('input[name=topic-title]').val('');
                    }
                    window.setTimeout(function(){
                        window.location.href = '".$route->getPrevRoute()."';
                    }, 1);
                </script>
            ";
            if(is_numeric($this->data->getTopicIdByCategoryIdAndTopicUrl($categoryData->getId(), $cleanUri)))
            {
                $uniqueStrAdd = self::createUniqueId(4);
                $newCleanUri = $cleanUri."-".$uniqueStrAdd;
                if(!is_numeric($this->data->getTopicIdByCategoryIdAndTopicUrl($categoryData->getId(), $newCleanUri)))
                {
                    $this->data->createNewTopic($categoryData->getId(), $title, $message, $newCleanUri);
                    return Routing::successMessage($successMsg);
                }
                else
                {
                    return Routing::errorMessage($langs['INVALID_SECURITY_TOKEN']); // WTF Dude
                }
            }
            else
            {
                $this->data->createNewTopic($categoryData->getId(), $title, $message, $cleanUri);
                return Routing::successMessage($successMsg);
            }
        }
    }
    
    public function editTopic($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->forumLangs();
            global $security;
            $title    = $security->xssEscape($post['topic-title']);
            $message  = $security->xssEscapeAndHtml($post['edited-message']);
            
            $topicData = $this->data->getTopicDataByTopicId($post['topicID']);
            
            if($_POST['security-token'] != $security->getToken())
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if(strlen(strip_tags($message)) < 2 || strlen(strip_tags($message)) > 10000)
            {
                $error = $l['TOPIC_TOO_SHORT_OR_LONG'];
            }
            if($topicData == FALSE || empty($topicData))
            {
                $error = $l['TOPIC_DOESNT_EXIST'];
            }
            if(is_object($topicData) && in_array($topicData->getStatusID(), array('1', '3', '5')))
            {
                $error = $l['TOPIC_CLOSED'];
            }
            if(is_object($topicData) && $topicData->getStarterUID() != $_SESSION['UID'])
            {
                $error = $l['TOPIC_DOESNT_EXIST'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $this->data->editTopic((int)$post['topicID'], $title, $message);
                $successMsg = $l['REACTION_EDITED_SUCCESS'];
                $successMsg .= "
                    <script type='text/javascript'>
                        if($('textarea#quickReply').length)
                        {
                            $('textarea#quickReply').val('');
                        }
                        if($('textarea#replyField').length)
                        {
                            $('textarea#replyField').val('');
                        }
                        window.setTimeout(function(){
                            window.location.href = '".$route->getPrevRoute()."';
                        }, 1);
                    </script>
                ";
                return Routing::successMessage($successMsg);
            }
        }
    }
    
    public function replyToTopic($post)
    {
        global $language;
        global $langs;
        $l        = $language->forumLangs();
        global $security;
        $message  = $security->xssEscapeAndHtml($post['reply-message']);
        
        $topicData = $this->data->getTopicDataByTopicId($post['topicID']);
        
        if($_POST['security-token'] != $security->getToken())
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(strlen(strip_tags($message)) < 2 || strlen(strip_tags($message)) > 10000)
        {
            $error = $l['REPLY_TOO_SHORT_OR_LONG'];
        }
        if($topicData == FALSE || empty($topicData))
        {
            $error = $l['TOPIC_DOESNT_EXIST'];
        }
        if(isset($_SESSION['UID']) && $this->data->checkLastReaction($post['topicID']) == $_SESSION['UID'])
        {
            $error = $l['EDIT_YOUR_LAST_REACTION'];
        }
        if(is_object($topicData) && in_array($topicData->getStatusID(), array('1', '3', '5')))
        {
            $error = $l['TOPIC_CLOSED'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            $this->data->replyToTopic($post['topicID'], $message);
            $successMsg = $l['REACTION_ADDED_SUCCESS'];
            $successMsg .= "
                <script type='text/javascript'>
                    if($('textarea#quickReply').length)
                    {
                        $('textarea#quickReply').val('');
                    }
                    if($('textarea#replyField').length)
                    {
                        $('textarea#replyField').val('');
                    }
                    window.setTimeout(function(){
                        window.location.href = '".$route->getPrevRoute()."';
                    }, 1);
                </script>
            ";
            return Routing::successMessage($successMsg);
        }
    }
    
    public function editReactionInTopic($post)
    {
        if(isset($_SESSION['UID']))
        {
            global $language;
            global $langs;
            $l        = $language->forumLangs();
            global $security;
            $message  = $security->xssEscapeAndHtml($post['edited-message']);
            
            $topicData = $this->data->getTopicDataByTopicId($post['topicID']);
            $reactionData = $this->data->getReactionDataByTopicAndReactionId($post['topicID'], $post['reactionID']);
            
            if($_POST['security-token'] != $security->getToken())
            {
                $error = $langs['INVALID_SECURITY_TOKEN'];
            }
            if(strlen(strip_tags($message)) < 2 || strlen(strip_tags($message)) > 10000)
            {
                $error = $l['REPLY_TOO_SHORT_OR_LONG'];
            }
            if($topicData == FALSE || empty($topicData))
            {
                $error = $l['TOPIC_DOESNT_EXIST'];
            }
            if($reactionData == FALSE || empty($reactionData))
            {
                $error = $l['REACTION_DOESNT_EXIST'];
            }
            if(is_object($topicData) && in_array($topicData->getStatusID(), array('1', '3', '5')))
            {
                $error = $l['TOPIC_CLOSED'];
            }
            if(is_object($reactionData) && $reactionData->getReactorUID() != $_SESSION['UID'])
            {
                $error = $l['REACTION_DOESNT_EXIST'];
            }
            
            if(isset($error))
            {
                return Routing::errorMessage($error);
            }
            else
            {
                global $route;
                $this->data->editReactionInTopic((int)$post['reactionID'], $message);
                $successMsg = $l['REACTION_EDITED_SUCCESS'];
                $successMsg .= "
                    <script type='text/javascript'>
                        if($('textarea#quickReply').length)
                        {
                            $('textarea#quickReply').val('');
                        }
                        if($('textarea#replyField').length)
                        {
                            $('textarea#replyField').val('');
                        }
                        window.setTimeout(function(){
                            window.location.href = '".$route->getPrevRoute()."';
                        }, 1);
                    </script>
                ";
                return Routing::successMessage($successMsg);
            }
        }
    }
    
    public function getCategories()
    {
        return $this->data->getCategories();
    }
    
    public function getCategory()
    {
        return $this->data->getCategory();
    }
    
    public function getCategoryById($id)
    {
        return $this->data->getCategoryById($id);
    }
    
    public function getTopicIdByCategoryAndTopicUrl($category, $url)
    {
        $categoryID = $this->getCategoryIdByCategory($category);
        if($categoryID > 0)$topicID = $this->data->getTopicIdByCategoryIdAndTopicUrl($categoryID, $url);
        if(isset($topicID) && $topicID > 0) return $topicID;
    }
    
    public function getTopics()
    {
        return $this->data->getTopics();
    }
    
    public function getTopicData()
    {
        return $this->data->getTopicData();
    }
    
    public function getTopicDataByTopicId($id)
    {
        return $this->data->getTopicDataByTopicId($id);
    }
    
    public function getTopicReactions($from, $to)
    {
        return $this->data->getTopicReactions($from, $to);
    }
    
    public function getTopicReactionsByTopicId($topicID, $from, $to)
    {
        return $this->data->getTopicReactionsByTopicId($topicID, $from, $to);
    }
}
