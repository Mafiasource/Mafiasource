<?PHP

namespace src\Business;

use src\Data\CMSDAO;

class CMSService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new CMSDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getCMSById($id, $lang)
    {
        return $this->data->getCMSById($id, $lang);
    }
    
    public function getCMSByName($name, $lang)
    {
        return $this->data->getCMSByName($name, $lang);
    }
}
