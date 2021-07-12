<?PHP

namespace install\config;

use PDO;

class DBConfig
{
    protected $con = null;
    
    public function __construct($dbHost, $dbName, $dbUser, $dbPwd)
    {
        // Reseting db credentials during install into new definitions
        if(strpos($dbHost, ':'))
            $dbHost = "[" . $dbHost . "]";
        
        define('_DBNAME_INSTALL', $dbName);
        define('_DBUSR_INSTALL', $dbUser);
        define('_DBPWD_INSTALL', $dbPwd);
        define('_PDO_DATABASE_INSTALL',  _DBNAME_INSTALL);
        define('_PDO_CONSTRING_INSTALL', "mysql:host=".$dbHost.";dbname="._PDO_DATABASE_INSTALL);
        define('_PDO_DBUSER_INSTALL',    _DBUSR_INSTALL);
        define('_PDO_DBPASS_INSTALL',    _DBPWD_INSTALL);
        if($this->con == null) $this->connect();
    }
    
    public function __destruct()
    {
        if($this->con != null) $this->closeConnection();
    }
    
    public function connect()
    {
        //Only connect when connection is null to prevent multiple connections in one request.
        if($this->con == null)
        {
            try
            {
                $this->con = new PDO(_PDO_CONSTRING_INSTALL, _PDO_DBUSER_INSTALL, _PDO_DBPASS_INSTALL);
                $this->con->setAttribute(PDO::ERRMODE_SILENT, PDO::ERRMODE_EXCEPTION);
                $this->con->setAttribute(PDO::ATTR_TIMEOUT, 5); // This doesn't work?
            }
            catch(\PDOException $e)
            {
                return false;
            }
        }
        return true;
    }
    
    public function closeConnection()
    {
        $this->con = null;
    }
    
    public function getDataSR($query, $params = array())
    {
        try
        {
            $statement = $this->con->prepare($query);
            $statement->execute($params);
            $result = $statement->fetch();
            $data = $result;
            return $data;
        }
        catch(\PDOException $e)
        {
            return $this->error = $e->getMessage();
        }
    }
    
    public function getData($query, $params = array())
    {
        try
        {
            $statement = $this->con->prepare($query);
            $statement->execute($params);
            $result = $statement->fetchAll();
            $data = array();
            foreach($result AS $row) $data[] = $row;
            return $data;
        }
        catch(\PDOException $e)
        {
            return $this->error = $e->getMessage();
        }
    }
    
    public function setData($query, $params = array())
    {
        $statement = $this->con->prepare($query);
        try
        {
            if($statement->execute($params))
                return true;
        }
        catch(\PDOException $e)
        {
            return $this->error = $e->getMessage();
        }
    }
}
