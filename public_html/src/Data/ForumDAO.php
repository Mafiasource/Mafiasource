<?PHP

namespace src\Data;

use src\Business\ForumService;
use src\Business\SeoService;
use src\Data\config\DBConfig;
use src\Entities\ForumCategory;
use src\Entities\ForumTopic;
use src\Entities\ForumReaction;

/* Class has some cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */

class ForumDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    private $dateFormat = "%d-%m-%Y %H:%i:%s";
    private $categoryID = 0; //Init
    private $topicID = 0; //Init
    private $familyID = 0; //Init
    
    public function __construct($familyID = false, $categoryID = false,$topicID = false,$reactionID = false)
    {
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
        global $route;
        $this->lang = $route->getLang();
        if($this->lang == 'en') $this->dateFormat = "%m-%d-%Y %r";
        if($familyID != false) $this->familyID = $familyID;
        if($categoryID != false) $this->categoryID = $categoryID;
        if($topicID != false) $this->topicID = $topicID;
        if($reactionID != false) $this->reactionID = $reactionID;
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getRecordsCount()
    {
        $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `forum_reaction` WHERE `deleted` = '0' AND `active` = '1' AND `topicID`= :tid");
        $statement->execute(array(':tid' => $this->topicID));
        $row = $statement->fetch();
        if(isset($row['total'])) return $row['total'];
    }
    
    public function getCategoryIdByCategory($category)
    {
        $statement = $this->dbh->prepare("SELECT `id` FROM `forum_category` WHERE `category_".$this->lang."`= :cat AND `active` = '1' AND `deleted`='0' LIMIT 1");
        $statement->execute(array(':cat' => $category));
        $row = $statement->fetch();
        if(isset($row['id']) && $row['id'] > 0)
        {
            return $row['id'];
        }
    }
    
    public function getCategoryNameByCategoryID($categoryID)
    {
        $statement = $this->dbh->prepare("SELECT `category_".$this->lang."` AS `category` FROM `forum_category` WHERE `id`= :cid AND `active` = '1' AND `deleted`='0' LIMIT 1");
        $statement->execute(array(':cid' => $categoryID));
        $row = $statement->fetch();
        if(isset($row['category']) && !empty($row['category']))
        {
            return $row['category'];
        }
    }
    
    public function getCategories()
    {
        global $userData;
        $famID = 0;
        if(is_object($userData) && $userData->getFamilyID() != 0) $famID = $userData->getFamilyID();
        $statement = $this->dbh->prepare("
            SELECT c.`id`,c.`category_".$this->lang."` AS `category`, c.`description_".$this->lang."` AS `description`, c.`picture`, c.`familyForum`, c.`donatorID`,
            IF(c.`familyForum`>0, (SELECT COUNT(*) FROM `forum_topic` WHERE `categoryID`=c.`id` AND `familyID`= '".$famID."' AND `lang`= '".$this->lang."' AND `active`='1' AND `deleted`='0'),
                (SELECT COUNT(*) FROM `forum_topic` WHERE `categoryID`=c.`id` AND `lang`= '".$this->lang."' AND `active`='1' AND `deleted`='0')) AS `topicsCnt`, c.`viewStatusID`,
            (SELECT COUNT(*) FROM `forum_reaction` WHERE `topicID`IN (
                SELECT `id` FROM `forum_topic`
                WHERE `categoryID`=c.`id` AND `lang`= '".$this->lang."' AND `active` = '1' AND `deleted`='0') AND `active`='1' AND `deleted`='0'
            ) AS `reactionsCnt`
            FROM `forum_category` AS c
            WHERE c.`active`='1' AND c.`deleted`='0'
            ORDER BY c.`position` ASC
        ");
        $statement->execute();
        $list = array();
        
        foreach($statement AS $row)
        {
            if((isset($row['viewStatusID']) && is_object($userData) && $userData->getStatusID() <= $row['viewStatusID'])
                || (isset($row['viewStatusID']) && $row['viewStatusID'] == 7)
            )
            {
                if(($row['familyForum'] == 1 && is_object($userData) && $userData->getFamilyID() != 0) || $row['familyForum'] == 0)
                {
                    if((is_object($userData) && $userData->getDonatorID() >= $row['donatorID'])
                        || (isset($row['donatorID']) && $row['donatorID'] == 0)
                    )
                    {
                        $forum = new ForumCategory();
                        $forum->setId($row['id']);
                        $forum->setCategory($row['category']);
                        $forum->setDescription($row['description']);
                        $forum->setPicture($row['picture']);
                        $forum->setFamilyForum(false);
                        if($row['familyForum'] == 1) $forum->setFamilyForum(true);
                        $forum->setUrl(SeoService::seoUrl($row['category']));
                        $forum->setTopics($row['topicsCnt']);
                        $forum->setReactions($row['reactionsCnt']);
                        $forum->setDonatorID($row['donatorID']);
                        
                        array_push($list, $forum);
                    }
                }
            }
        }
        return $list;
    }
    
    public function getCategory()
    {
        global $userData;
        $statement = $this->dbh->prepare("
            SELECT c.`id`,c.`category_".$this->lang."` AS `category`, c.`description_".$this->lang."` AS `description`, c.`picture`, c.`familyForum`,
            (SELECT COUNT(*) FROM `forum_topic` WHERE `categoryID`=c.`id` AND `lang`= '".$this->lang."' AND `active`='1' AND `deleted`='0') AS `topicsCnt`, c.`viewStatusID`,
            c.`interactStatusID`, c.`donatorID`, (SELECT COUNT(*) FROM `forum_reaction` WHERE `topicID`IN (
                SELECT `id` FROM `forum_topic`
                WHERE `categoryID`=c.`id` AND `lang`= '".$this->lang."' AND `active` = '1' AND `deleted`='0')  AND `active`='1' AND `deleted`='0'
            ) AS `reactionsCnt`
            FROM `forum_category` AS c
            WHERE c.`active`='1' AND c.`deleted`='0' AND `id`= :cid
            ORDER BY c.`position` ASC
        ");
        $statement->execute(array(':cid' => $this->categoryID));
        $row = $statement->fetch();
        if((isset($row['viewStatusID']) && is_object($userData) && $userData->getStatusID() <= $row['viewStatusID'])
            || (isset($row['viewStatusID']) && $row['viewStatusID'] == 7)
        )
        {
            if(($row['familyForum'] == 1 && is_object($userData) && $userData->getFamilyID() != 0) || $row['familyForum'] == 0)
            {
                if((is_object($userData) && $userData->getDonatorID() >= $row['donatorID'])
                    || (isset($row['donatorID']) && $row['donatorID'] == 0)
                )
                {
                    $forum = new ForumCategory();
                    $forum->setId($row['id']);
                    $forum->setCategory($row['category']);
                    $forum->setDescription($row['description']);
                    $forum->setPicture($row['picture']);
                    $forum->setFamilyForum(false);
                    if($row['familyForum'] == 1) $forum->setFamilyForum(true);
                    $forum->setUrl(SeoService::seoUrl($row['category']));
                    $forum->setTopics($row['topicsCnt']);
                    $forum->setReactions($row['reactionsCnt']);
                    $forum->setViewStatusID($row['viewStatusID']);
                    $forum->setInteractStatusID($row['interactStatusID']);
                    $forum->setDonatorID($row['donatorID']);
                }
            }
        }
        if(isset($forum)) return $forum; else return FALSE;
    }
    
    public function getCategoryById($id)
    {
        global $userData;
        $statement = $this->dbh->prepare("
            SELECT c.`id`,c.`category_".$this->lang."` AS `category`, c.`description_".$this->lang."` AS `description`, c.`picture`, c.`familyForum`,
            (SELECT COUNT(*) FROM `forum_topic` WHERE `categoryID`=c.`id` AND `lang`= '".$this->lang."' AND `active`='1' AND `deleted`='0') AS `topicsCnt`, c.`viewStatusID`,
            c.`interactStatusID`, c.`donatorID`,(SELECT COUNT(*) FROM `forum_reaction` WHERE `topicID`IN (
                SELECT `id` FROM `forum_topic`
                WHERE `categoryID`=c.`id` AND `lang`= '".$this->lang."' AND `active` = '1' AND `deleted`='0') AND `active`='1' AND `deleted`='0'
            ) AS `reactionsCnt`
            FROM `forum_category` AS c
            WHERE c.`active`='1' AND c.`deleted`='0' AND `id`= :cid
            ORDER BY c.`position` ASC
        ");
        $statement->execute(array(':cid' => $id));
        $row = $statement->fetch();
        if((isset($row['viewStatusID']) && is_object($userData) && $userData->getStatusID() <= $row['viewStatusID'])
            || (isset($row['viewStatusID']) && $row['viewStatusID'] == 7)
        )
        {
            if(($row['familyForum'] == 1 && is_object($userData) && $userData->getFamilyID() != 0) || $row['familyForum'] == 0)
            {
                if((is_object($userData) && $userData->getDonatorID() >= $row['donatorID'])
                    || (isset($row['donatorID']) && $row['donatorID'] == 0)
                )
                {
                    $forum = new ForumCategory();
                    $forum->setId($row['id']);
                    $forum->setCategory($row['category']);
                    $forum->setDescription($row['description']);
                    $forum->setPicture($row['picture']);
                    $forum->setFamilyForum(false);
                    if($row['familyForum'] == 1) $forum->setFamilyForum(true);
                    $forum->setUrl(SeoService::seoUrl($row['category']));
                    $forum->setTopics($row['topicsCnt']);
                    $forum->setReactions($row['reactionsCnt']);
                    $forum->setViewStatusID($row['viewStatusID']);
                    $forum->setInteractStatusID($row['interactStatusID']);
                    $forum->setDonatorID($row['donatorID']);
                }
            }
        }
        if(isset($forum)) return $forum; else return FALSE;
    }
    
    public function getTopicIdByCategoryIdAndTopicUrl($categoryID, $url)
    {
        $statement = $this->dbh->prepare("SELECT `id` FROM `forum_topic` WHERE `categoryID`= :cid AND `lang`= :lang AND `cleanUrl`= :url AND `deleted` = '0' AND `active` = '1' ");
        $statement->execute(array(':cid' => $categoryID, ':lang' => $this->lang, ':url' => $url));
        $row = $statement->fetch();
        if(isset($row['id']) && $row['id'] > 0)
        {
            return $row['id'];
        } else return FALSE;
    }
    
    public function getTopicTitleByCategoryIdAndTopicUrl($categoryID, $url)
    {
        $statement = $this->dbh->prepare("SELECT `title` FROM `forum_topic` WHERE `categoryID`= :cid AND `lang`= :lang AND `cleanUrl`= :url AND `deleted` = '0' AND `active` = '1' ");
        $statement->execute(array(':cid' => $categoryID, ':lang' => $this->lang, ':url' => $url));
        $row = $statement->fetch();
        if(isset($row['title']) && !empty($row['title']))
        {
            return $row['title'];
        } else return FALSE;
    }
    
    public function getTopics()
    {
        global $userData;
        $statement = $this->dbh->prepare("
            SELECT
                t.`id`, t.`categoryID`, t.`starterUID`, t.`familyID`, t.`title`, t.`lastMsgTime`, t.`cleanUrl`, c.`viewStatusID`, c.`category_".$this->lang."` AS `category`,
                DATE_FORMAT( t.`date`, '".$this->dateFormat."' ) AS `date`, u.`username` AS `starter`, COUNT(r.`id`) AS `reactions`, fs.`status_".$this->lang."` AS `topicStatus`,
                fs.id AS `topicStatusID`, fs.`picture_unread`, fs.`picture_read`, c.`familyForum`, c.`donatorID`
            FROM `forum_topic` AS t
            LEFT JOIN `forum_category` AS c
            ON (t.`categoryID`=c.`id`)
            LEFT JOIN `forum_status` AS fs
            ON (fs.id=t.status)
            LEFT JOIN `user` AS u
            ON (u.id=t.starterUID)
            LEFT JOIN `forum_reaction` AS r
            ON (t.`id`=r.`topicID`)
            WHERE t.`active`='1' AND t.`deleted`='0' AND t.`categoryID`= :cid AND t.`lang`= :lang AND t.`familyID`= :fid
            GROUP BY t.id
            ORDER BY t.`status` DESC, t.`lastMsgTime` DESC, t.`date` DESC
        ");
        $statement->execute(array(':cid' => $this->categoryID,':lang' => $this->lang, ':fid' => $this->familyID));
        $list = array();
        
        foreach($statement AS $row)
        {
            if((isset($row['viewStatusID']) && is_object($userData) && $userData->getStatusID() <= $row['viewStatusID'])
                || (isset($row['viewStatusID']) && $row['viewStatusID'] == 7)
            )
            {
                if(($row['familyForum'] == 1 && is_object($userData) && $userData->getFamilyID() != 0) || $row['familyForum'] == 0)
                {
                    if((is_object($userData) && $userData->getDonatorID() >= $row['donatorID'])
                        || (isset($row['donatorID']) && $row['donatorID'] == 0)
                    )
                    {
                        $picture = 'picture_unread';
                        if(isset($_SESSION['UID']))
                        {
                            $checkRead = $this->dbh->prepare("SELECT `id` FROM `forum_read` WHERE `topicID` = :tid AND `userID`=:uid");
                            $checkRead->execute(array(':tid' => $row['id'], ':uid' => $_SESSION['UID']));
                            $checkRead = $checkRead->fetch();
                            if(isset($checkRead['id']) && $checkRead['id'] > 0) $picture = 'picture_read';
                        }
                        $forum = new ForumTopic();
                        $forum->setId($row['id']);
                        $forum->setCategoryID($row['categoryID']);
                        $forum->setCategory($row['category']);
                        $forum->setStarterUID($row['starterUID']);
                        $forum->setStarter($row['starter']);
                        $forum->setFamilyID($row['familyID']);
                        $forum->setTitle(ForumService::convertEmoticons($row['title']));
                        $forum->setDate($row['date']);
                        $forum->setStatus($row['topicStatus']);
                        $forum->setStatusID($row['topicStatusID']);
                        $forum->setStatusPicture($row[$picture]);
                        $forum->setLastMsgTime($row['lastMsgTime']);
                        $forum->setCleanUrl($row['cleanUrl']);
                        $forum->setReactions($row['reactions']);
                        
                        array_push($list, $forum);
                    }
                }
            }
        }
        return $list;
    }
    
    public function getTopicData()
    {
        global $userData;
        $statement = $this->dbh->prepare("
            SELECT
                t.`id`,t.`title`, t.`content`, u.`username` AS `starter`, u.`forumPosts`, s.`id` AS `statusID`, d.`id` AS `donatorID`, t.`starterUID`, s.`status_".$this->lang."` AS `status`,
                d.`donator_".$this->lang."` AS `donator`, DATE_FORMAT( t.`date`, '".$this->dateFormat."' ) AS `date`, c.`viewStatusID`,c.`donatorID` AS `categoryDonatorID`,
                c.`category_".$this->lang."` AS `category`, c.`id` AS `categoryID`, u.avatar, fs.`status_".$this->lang."` AS `topicStatus`, fs.id AS `topicStatusID`, fs.`picture_unread`,
                fs.`picture_read`, c.`familyForum`
            FROM `forum_topic` AS t
            LEFT JOIN `forum_category` AS c
            ON (t.`categoryID`=c.`id`)
            LEFT JOIN `forum_status` AS fs
            ON (fs.id=t.status)
            LEFT JOIN `user` AS u
            ON (t.`starterUID`=u.`id`)
            LEFT JOIN `status` AS s
            ON (u.statusID=s.id)
            LEFT JOIN `donator` AS d
            ON (u.donatorID=d.id)
            WHERE t.`id`= :tid AND t.`active`='1' AND t.`deleted`='0' AND t.`lang`= :lang AND t.`familyID`= :fid
        ");
        $statement->execute(array(':tid' => $this->topicID, ':lang' => $this->lang, ':fid' => $this->familyID));
        $row = $statement->fetch();
        
        if(isset($row['id']) && $row['id'] == $this->topicID)
        {
            if((isset($row['viewStatusID']) && is_object($userData) && $userData->getStatusID() <= $row['viewStatusID'])
                || (isset($row['viewStatusID']) && $row['viewStatusID'] == 7)
            )
            {
                if(($row['familyForum'] == 1 && is_object($userData) && $userData->getFamilyID() != 0) || $row['familyForum'] == 0)
                {
                    if((is_object($userData) && $userData->getDonatorID() >= $row['categoryDonatorID'])
                        || (isset($row['categoryDonatorID']) && $row['categoryDonatorID'] == 0)
                    )
                    {
                        if($row['statusID'] < 7 || $row['statusID'] == 8)
                        {
                            $className = SeoService::seoUrl($row['status']);
                        }
                        else
                        {
                            $className = SeoService::seoUrl($row['donator']);
                        }
                        $picture = 'picture_unread';
                        if(isset($_SESSION['UID']))
                        {
                            $checkRead = $this->dbh->prepare("SELECT `id` FROM `forum_read` WHERE `topicID` = :tid AND `userID`=:uid");
                            $checkRead->execute(array(':tid' => $row['id'], ':uid' => $_SESSION['UID']));
                            $checkRead = $checkRead->fetch();
                            if(isset($checkRead['id']) && $checkRead['id'] > 0) $picture = 'picture_read';
                        }
                        $forum = new ForumTopic();
                        $forum->setId($row['id']);
                        $forum->setCategoryID($row['categoryID']);
                        $forum->setCategory($row['category']);
                        $forum->setStarterUID($row['starterUID']);
                        $forum->setStarter($row['starter']);
                        $forum->setStarterClassName($className);
                        $forum->setStarterAvatar(FALSE);
                        if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['starterUID'].'/uploads/'.$row['avatar'])) $forum->setStarterAvatar($row['avatar']);
                        $forum->setStarterPostsCnt($row['forumPosts']);
                        $forum->setStarterDonatorID($row['donatorID']);
                        $forum->setTitle(ForumService::convertEmoticons($row['title']));
                        $forum->setContent(ForumService::convertEmoticons($row['content']));
                        $forum->setDate($row['date']);
                        $forum->setStatus($row['topicStatus']);
                        $forum->setStatusID($row['topicStatusID']);
                        $forum->setStatusPicture($row[$picture]);
                        $this->userReadForumTopic($row['id']);
                        
                        return $forum;
                    }
                }
            }
        }
        return FALSE;
    }
    
    public function getTopicDataByTopicId($id)
    {
        global $userData;
        $statement = $this->dbh->prepare("
            SELECT
                t.`id`,t.`title`, t.`content`, u.username AS `starter`, u.`forumPosts`, s.`id` AS `statusID`, d.`id` AS `donatorID`, s.`status_".$this->lang."` AS `status`,
                d.`donator_".$this->lang."` AS `donator`, t.`starterUID`, DATE_FORMAT( t.`date`, '".$this->dateFormat."' ) AS `date`, c.`viewStatusID`,c.`donatorID` AS `categoryDonatorID`,
                c.`category_".$this->lang."` AS `category`, c.`id` AS `categoryID`, u.avatar, fs.`status_".$this->lang."` AS `topicStatus`, fs.id AS `topicStatusID`, fs.`picture_unread`,
                fs.`picture_read`, c.`familyForum`
            FROM `forum_topic` AS t
            LEFT JOIN `forum_category` AS c
            ON (t.`categoryID`=c.`id`)
            LEFT JOIN `forum_status` AS fs
            ON (fs.id=t.status)
            LEFT JOIN `user` AS u
            ON (t.`starterUID`=u.`id`)
            LEFT JOIN `status` AS s
            ON (u.statusID=s.id)
            LEFT JOIN `donator` AS d
            ON (u.donatorID=d.id)
            WHERE t.`id`= :tid AND t.`active`='1' AND t.`deleted`='0' AND t.`lang`= :lang AND t.`familyID`= :fid
        ");
        $statement->execute(array(':tid' => $id, ':lang' => $this->lang, ':fid' => $this->familyID));
        $row = $statement->fetch();
        
        if(isset($row['id']) && $row['id'] == $id)
        {
            if( ((is_object($userData) && $userData->getStatusID() <= $row['viewStatusID'] && $row['categoryDonatorID'] <= $userData->getDonatorID()) ||
                (!is_object($userData) && $row['viewStatusID'] == 7 && $row['categoryDonatorID'] == 0 && $row['familyForum'])) &&
                (($row['familyForum'] == 1 && is_object($userData) && $userData->getFamilyID() != 0) || $row['familyForum'] == 0)
            )
            {
                //TopicData
                if($row['statusID'] < 7 || $row['statusID'] == 8)
                {
                    $className = SeoService::seoUrl($row['status']);
                }
                else
                {
                    $className = SeoService::seoUrl($row['donator']);
                }
                $picture = 'picture_unread';
                if(isset($_SESSION['UID']))
                {
                    $checkRead = $this->dbh->prepare("SELECT `id` FROM `forum_read` WHERE `topicID` = :tid AND `userID`=:uid");
                    $checkRead->execute(array(':tid' => $row['id'], ':uid' => $_SESSION['UID']));
                    $checkRead = $checkRead->fetch();
                    if(isset($checkRead['id']) && $checkRead['id'] > 0) $picture = 'picture_read';
                }
                $forum = new ForumTopic();
                $forum->setId($row['id']);
                $forum->setCategoryID($row['categoryID']);
                $forum->setCategory($row['category']);
                $forum->setStarterUID($row['starterUID']);
                $forum->setStarter($row['starter']);
                $forum->setStarterClassName($className);
                $forum->setStarterAvatar(FALSE);
                if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['starterUID'].'/uploads/'.$row['avatar'])) $forum->setStarterAvatar($row['avatar']);
                $forum->setStarterPostsCnt($row['forumPosts']);
                $forum->setStarterDonatorID($row['donatorID']);
                $forum->setTitle(ForumService::convertEmoticons($row['title']));
                $forum->setContent(ForumService::convertEmoticons($row['content']));
                $forum->setDate($row['date']);
                $forum->setStatus($row['topicStatus']);
                $forum->setStatusID($row['topicStatusID']);
                $forum->setStatusPicture($row[$picture]);
                $this->userReadForumTopic($row['id']);
                
                return $forum;
            } else return FALSE;
        } else return FALSE;
    }
    
    public function getTopicReactions($from, $to)
    {
        $statement = $this->dbh->prepare("
            SELECT r.`id`,r.`content`, r.`userID`, DATE_FORMAT( r.`date`, '".$this->dateFormat."' ) AS `date`, r.`lastEditTime`, u.`username` AS `reactor`, u.`avatar`, u.`forumPosts`,
            s.`id` AS `statusID`,d.`id` AS `donatorID`, s.`status_".$this->lang."` AS `status`, d.`donator_".$this->lang."` AS `donator`, r.`lastEditTime`, c.`viewStatusID`
            FROM `forum_reaction` AS r
            LEFT JOIN `forum_topic` AS t
            ON (r.`topicID`=t.`id`)
            LEFT JOIN `forum_category` AS c
            ON (t.`categoryID`=c.`id`)
            LEFT JOIN `user` AS u
            ON (r.`userID`=u.`id`)
            LEFT JOIN `status` AS s
            ON (u.statusID=s.id)
            LEFT JOIN `donator` AS d
            ON (u.donatorID=d.id)
            WHERE r.`topicID`= :tid AND r.`active`='1' AND r.`deleted`='0'
            ORDER BY r.`date` ASC
            LIMIT $from, $to
        ");
        $statement->execute(array(':tid' => $this->topicID));
        $reactions = array();
        foreach($statement AS $r)
        {
            if($r['statusID'] < 7 || $r['statusID'] == 8)
            {
                $className = SeoService::seoUrl($r['status']);
            }
            else
            {
                $className = SeoService::seoUrl($r['donator']);
            }
            $reaction = new ForumReaction();
            $reaction->setId($r['id']);
            $reaction->setTopicID($this->topicID);
            $reaction->setReactorUID($r['userID']);
            $reaction->setReactor($r['reactor']);
            $reaction->setReactorClassName($className);
            $reaction->setReactorAvatar(FALSE);
            if(file_exists(DOC_ROOT . '/web/public/images/users/'.$r['userID'].'/uploads/'.$r['avatar'])) $reaction->setReactorAvatar($r['avatar']);
            $reaction->setReactorPostsCnt($r['forumPosts']);
            $reaction->setReactorDonatorID($r['donatorID']);
            $reaction->setContent(ForumService::convertEmoticons($r['content']));
            $reaction->setQuoteContent("<small class='darkgray'>".$r['reactor']." | ".$r['date']."</small><br />".ForumService::convertEmoticons($r['content']));
            $reaction->setDate($r['date']);
            $reaction->setLastEditTime($r['lastEditTime']);
            array_push($reactions,$reaction);
        }
        return $reactions;
    }
    
    public function getTopicReactionsByTopicId($topicID, $from, $to)
    {
        $statement = $this->dbh->prepare("
            SELECT r.`id`,r.`content`, r.`userID`, DATE_FORMAT( r.`date`, '".$this->dateFormat."' ) AS `date`, r.`lastEditTime`, u.`username` AS `reactor`, u.`avatar`, u.`forumPosts`,
            s.`id` AS `statusID`,d.`id` AS `donatorID`, s.`status_".$this->lang."` AS `status`, d.`donator_".$this->lang."` AS `donator`, r.`lastEditTime`, c.`viewStatusID`
            FROM `forum_reaction` AS r
            LEFT JOIN `forum_topic` AS t
            ON (r.`topicID`=t.`id`)
            LEFT JOIN `forum_category` AS c
            ON (t.`categoryID`=c.`id`)
            LEFT JOIN `user` AS u
            ON (r.`userID`=u.`id`)
            LEFT JOIN `status` AS s
            ON (u.statusID=s.id)
            LEFT JOIN `donator` AS d
            ON (u.donatorID=d.id)
            WHERE r.`topicID`= :tid AND r.`active`='1' AND r.`deleted`='0'
            ORDER BY r.`date` ASC
            LIMIT $from, $to
        ");
        $statement->execute(array(':tid' => $topicID));
        $reactions = array();
        foreach($statement AS $r)
        {
            if($r['statusID'] < 7 || $r['statusID'] == 8)
            {
                $className = SeoService::seoUrl($r['status']);
            }
            else
            {
                $className = SeoService::seoUrl($r['donator']);
            }
            $reaction = new ForumReaction();
            $reaction->setId($r['id']);
            $reaction->setTopicID($topicID);
            $reaction->setReactorUID($r['userID']);
            $reaction->setReactorClassName($className);
            $reaction->setReactorAvatar(FALSE);
            if(file_exists(DOC_ROOT . '/web/public/images/users/'.$r['userID'].'/uploads/'.$r['avatar'])) $reaction->setReactorAvatar($r['avatar']);
            $reaction->setReactorPostsCnt($r['forumPosts']);
            $reaction->setReactorDonatorID($r['donatorID']);
            $reaction->setContent(ForumService::convertEmoticons($r['content']));
            $reaction->setDate($r['date']);
            $reaction->setLastEditTime($r['lastEditTime']);
            array_push($reactions,$reaction);
        }
        return $reactions;
    }
    
    public function getReactionDataByTopicAndReactionId($topicID, $reactionID)
    {
        $statement = $this->dbh->prepare("
            SELECT r.`id`,r.`content`, r.`userID`, DATE_FORMAT( r.`date`, '".$this->dateFormat."' ) AS `date`, r.`lastEditTime`, u.`username` AS `reactor`, u.`avatar`, u.`forumPosts`,
            s.`id` AS `statusID`,d.`id` AS `donatorID`, s.`status_".$this->lang."` AS `status`, d.`donator_".$this->lang."` AS `donator`, r.`lastEditTime`, c.`viewStatusID`
            FROM `forum_reaction` AS r
            LEFT JOIN `forum_topic` AS t
            ON (r.`topicID`=t.`id`)
            LEFT JOIN `forum_category` AS c
            ON (t.`categoryID`=c.`id`)
            LEFT JOIN `user` AS u
            ON (r.`userID`=u.`id`)
            LEFT JOIN `status` AS s
            ON (u.statusID=s.id)
            LEFT JOIN `donator` AS d
            ON (u.donatorID=d.id)
            WHERE r.`topicID`= :tid AND r.`id`= :rid AND r.`active`='1' AND r.`deleted`='0'
            ORDER BY r.`date` ASC
            LIMIT 1
        ");
        $statement->execute(array(':tid' => $topicID, ':rid' => $reactionID));
        
        if($r = $statement->fetch())
        {
            if($r['statusID'] < 7 || $r['statusID'] == 8)
            {
                $className = SeoService::seoUrl($r['status']);
            }
            else
            {
                $className = SeoService::seoUrl($r['donator']);
            }
            $reaction = new ForumReaction();
            $reaction->setId($r['id']);
            $reaction->setTopicID($topicID);
            $reaction->setReactorUID($r['userID']);
            $reaction->setReactor($r['reactor']);
            $reaction->setReactorClassName($className);
            $reaction->setReactorAvatar(FALSE);
            if(file_exists(DOC_ROOT . '/web/public/images/users/'.$r['userID'].'/uploads/'.$r['avatar'])) $reaction->setReactorAvatar($r['avatar']);
            $reaction->setReactorPostsCnt($r['forumPosts']);
            $reaction->setReactorDonatorID($r['donatorID']);
            $reaction->setContent(ForumService::convertEmoticons($r['content']));
            $reaction->setDate($r['date']);
            $reaction->setLastEditTime($r['lastEditTime']);
            return $reaction;
        }
    }
    
    public function checkLastReaction($id)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `userID` FROM `forum_reaction` WHERE `topicID`= :tid  AND `active`='1' AND `deleted`='0' ORDER BY `date` DESC LIMIT 1");
            $statement->execute(array(':tid' => $id));
            $row = $statement->fetch();
            if(isset($row['userID']) && $row['userID'] > 0)
            {
                return $row['userID'];
            }
        }
    }
    
    public function createNewTopic($categoryID, $title, $message, $cleanUri)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("INSERT INTO `forum_topic` (`categoryID`,`starterUID`,`familyID`,`lang`,`title`,`content`,`date`,`lastMsgTime`,`cleanUrl`) VALUES (:cid,:uid,:fid,:lang,:title,:msg,:date,:lmt,:curl)");
            $statement->execute(array(':cid' => $categoryID,':uid' => $_SESSION['UID'], ':fid' => $this->familyID, ':lang' => $this->lang, ':title' => $title, ':msg' => $message, ':date' => date('Y-m-d H:i:s'), ':lmt' => time(), ':curl' => $cleanUri));
            $this->countOneUserForumPost();
            $this->userReadForumTopic($this->dbh->lastInsertId());
        }
    }
    
    public function editTopic($id, $title, $message)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `forum_topic` SET `title`= :title, `content`= :content WHERE `id`= :tid AND `starterUID`= :uid AND `active`='1' AND `deleted`='0'");
            $statement->execute(array(':title' => $title, ':content' => $message, ':tid' => $id, ':uid' => $_SESSION['UID']));
            
            $this->userReadForumTopic($id);
        }
    }
    
    public function replyToTopic($id, $message)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("INSERT INTO `forum_reaction` (`topicID`,`userID`,`content`,`date`) VALUES (:tid,:uid,:content,:date)");
            $statement->execute(array(':tid' => $id, ':uid' => $_SESSION['UID'], ':content' => $message, ':date' => date('Y-m-d H:i:s')));
            
            $statement = $this->dbh->prepare("UPDATE `forum_topic` SET `lastMsgTime`= :time WHERE `id` = :tid  AND `active`='1' AND `deleted`='0'");
            $statement->execute(array(':time' => time(), ':tid' => $id));
            
            $this->countOneUserForumPost();
            $this->truncateReadForumTopic($id);
            $this->userReadForumTopic($id);
        }
    }
    
    public function editReactionInTopic($id, $message)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `forum_reaction` SET `content`= :content, `lastEditTime`= :time WHERE `id`= :rid AND `userID`= :uid AND `active`='1' AND `deleted`='0'");
            $statement->execute(array(':content' => $message, ':time' => date('Y-m-d H:i:s'), ':rid' => $id, ':uid' => $_SESSION['UID']));
            
            $this->userReadForumTopic($id);
        }
    }
    
    public function countOneUserForumPost()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `user` SET `forumPosts`=`forumPosts`+'1' WHERE `id` = :uid  AND `active`='1' AND `deleted`='0'");
            $statement->execute(array(':uid' => $_SESSION['UID']));
        }
    }
    
    public function userReadForumTopic($tid)
    {
        if(isset($_SESSION['UID']) && $tid > 0)
        {
            $statement = $this->dbh->prepare("INSERT INTO `forum_read` (`topicID`,`userID`) VALUES (:tid,:uid)");
            $statement->execute(array(':tid' => $tid, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function truncateReadForumTopic($tid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("DELETE FROM `forum_read` WHERE `topicID`= :tid");
            $statement->execute(array(':tid' => $tid));
        }
    }
}
