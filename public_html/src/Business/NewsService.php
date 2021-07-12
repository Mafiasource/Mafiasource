<?PHP
 
namespace src\Business;
 
use src\Data\NewsDAO;
 
class NewsService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new NewsDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getLatestMessages($tab = false)
    {
        return $this->data->getLatestMessages($tab);
    }
}
