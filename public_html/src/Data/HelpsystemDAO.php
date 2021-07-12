<?PHP
 
namespace src\Data;

use src\Data\config\DBConfig;
 
//CMS Data klasse
 
class HelpsystemDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    
    public function __construct()
    {
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
        global $route;
        $this->lang = $route->getLang();
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getContentByRouteName($name)
    {
        $statement = $this->dbh->prepare("SELECT `content_".$this->lang."` AS `content` FROM `helpsystem` WHERE `routename`=:name LIMIT 1");
        $statement->execute(array(':name' => $name));
        $row = $statement->fetch();
        if(isset($row['content']) && $row['content'] != "")
            return $row['content'];
        
        return false;
    }
}
