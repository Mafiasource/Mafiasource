<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Entities\Seo;

class SeoDAO extends DBConfig
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
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `seo` WHERE `deleted` = '0' AND `active` = '1'");
            $statement->execute();
            $row = $statement->fetch();
            return $row['total'];
        }
    }
    
    public function getSeoDataByRouteName($rn)
    {
        $statement = $this->dbh->prepare("SELECT `id`,`title_$this->lang` AS `title`,`subject_$this->lang` AS `subject`,`image`,`url`,`description_$this->lang` AS `description`,`keywords_$this->lang` AS `keywords` FROM `seo` WHERE `routename`= :rn  AND `active`='1' AND `deleted`='0'");
        $statement->execute(array(':rn' => $rn));
        $row = $statement->fetch();
        if(isset($row['id']) && $row['id'] > 0)
        {
            $seo = new Seo();
            $seo->setId($row['id']);
            $seo->setTitle($row['title']);
            $seo->setSubject($row['subject']);
            $seo->setImage($row['image']);
            $seo->setUrl($row['url']);
            $seo->setDescription($row['description']);
            $seo->setKeywords($row['keywords']);
            
            return $seo;
        }
    }
}
