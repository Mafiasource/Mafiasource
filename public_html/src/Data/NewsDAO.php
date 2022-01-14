<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Entities\News;

class NewsDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    
    public function __construct()
    {
        global $lang;
        global $connection;
        
        $this->con = $connection;
        $this->dbh = $connection->con;
        $this->lang = $lang;
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getLatestMessages($tab=false)
    {
        if(isset($_SESSION['UID']))
        {
            $add = "";
            $limit = 5;
            if($tab != false && ($tab == 'news' || $tab == 'updates'))
            {
                if($tab == 'updates') $tab = "update";
                $add = "AND `type`='".$tab."'";
                $limit = 15;
            }
            $statement = $this->dbh->prepare("SELECT `id`,`title_".$this->lang."` AS `title`, `type`, `article_".$this->lang."` AS `article`, `date` FROM `news` WHERE `active`='1' AND `deleted`='0' ".$add." ORDER BY `date` DESC, `position` ASC LIMIT ".$limit."");
            $statement->execute();
            $list = array();
            while($row = $statement->fetch())
            {
                $news = new News();
                $news->setId($row['id']);
                $news->setType($row['type']);
                $news->setTitle($row['title']);
                $news->setArticle($row['article']);
                $news->setDate($row['date']);
                array_push($list,$news);
            }
            return $list;
        }
    }
}
