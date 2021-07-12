<?PHP
 
namespace src\Business;
 
use src\Data\HelpsystemDAO;
 
class HelpsystemService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new HelpsystemDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getContentByRouteName($name)
    {
        return $this->data->getContentByRouteName($name);
    }
}
