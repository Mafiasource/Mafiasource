<?PHP

namespace src\Business\Logic\admin;

use app\config\Routing;

class Pagination
{
    public $pagination = FALSE;
    public $from = 0;
    public $to = 15;
    public $recordsPerPage = 15;
    public $page = 1;
    public $uri = '';
    public $tpages = 0;
    
    public function __construct($table)
    {
        $tpages = (int)ceil($table->getRecordsCount() / $this->recordsPerPage);
        $this->tpages = $tpages;
        $requestURI = explode('/', $_SERVER["REQUEST_URI"]);
        $parameters = array();
        for($i = 1; $i < count($requestURI); $i++)
        {
            $val = $requestURI[$i];
            array_push($parameters,$val);
        }
        if(isset($parameters[2]) && $parameters[2] == "pagina") $this->page = $accPage = $parameters[3];
        if(isset($parameters[3]) && $parameters[3] == "pagina") $this->page = $accPage = $parameters[4];
        if(isset($accPage))
        {
            if($this->page != 1) $this->from = ($this->page - 1) * $this->recordsPerPage;
            if($this->page != 1) $this->to = $this->recordsPerPage;
        }
        else
            $this->page = 1;
        
        $uri = PROTOCOL.$_SERVER['HTTP_HOST']."/".$parameters[0]."/".$parameters[1];
        if(isset($parameters[2]) && $parameters[2] != "pagina") $uri = PROTOCOL.$_SERVER['HTTP_HOST']."/".$parameters[0]."/".$parameters[1]."/".$parameters[2];
        $this->uri = $uri;
        
        if($tpages > 1) $this->pagination = TRUE;
        
        return $this;
    }
}
