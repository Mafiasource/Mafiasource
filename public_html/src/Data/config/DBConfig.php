<?PHP

namespace src\Data\config;

use PDO;

class DBConfig
{
    /** DB Credentials? See /app/config/config.php **/
    protected $database = PDO_DATABASE;
    protected $con = null;
    public $error;
    
    
    public function __construct()
    {
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
                $this->con = new PDO(PDO_CONSTRING, PDO_DBUSER, PDO_DBPASS);
                $this->con->setAttribute(PDO::ERRMODE_SILENT, PDO::ERRMODE_EXCEPTION);
            }
            catch(\PDOException $e)
            {
                $this->error = $e->getMessage();
                die("An error occured while connecting to our database. Please try again later.");
                exit(0);
            }
        }
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
