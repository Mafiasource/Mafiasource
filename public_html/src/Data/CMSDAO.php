<?PHP

namespace src\Data;

use src\Data\config\DBConfig;

class CMSDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    
    public function __construct()
    {
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getCMSById($id,$lang)
    {
        $statement = $this->dbh->prepare("SELECT `content_".$lang."` FROM `cms` WHERE `id`=:id AND `active`='1' AND `deleted`='0' LIMIT 1");
        $statement->execute(array(':id' => $id));
        $row = $statement->fetch();
        $return = isset($row['content_'.$lang]) ? $row['content_'.$lang] : "";
        return $return;
    }
    
    public function getCMSByName($name,$lang)
    {
        $statement = $this->dbh->prepare("SELECT `content_".$lang."` FROM `cms` WHERE `naam`=:name AND `active`='1' AND `deleted`='0' LIMIT 1");
        $statement->execute(array(':name' => $name));
        $row = $statement->fetch();
        $return = isset($row['content_'.$lang]) ? $row['content_'.$lang] : "";
        return $return;
    }
}
