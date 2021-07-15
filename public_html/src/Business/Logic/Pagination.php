<?PHP

namespace src\Business\Logic;

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
    
    public function __construct($table, $rpp = false, $to = false)
    {
        if(isset($rpp) && $rpp != FALSE) $this->recordsPerPage = $rpp;
        if(isset($to) && $to != FALSE) $this->to = $to;
        
        $tpages = (int)ceil($table->getRecordsCount() / $this->recordsPerPage);
        $this->tpages = $tpages;
        $requestURI = explode('/', $_SERVER["REQUEST_URI"]);
        $parameters = array();
        for($i = 1; $i < count($requestURI); $i++)
        {
            $val = $requestURI[$i];
            array_push($parameters,$val);
        }
        
        if(isset($parameters[2]) && $parameters[2] == "page") $this->page = $accPage = $parameters[3];
        if(isset($parameters[3]) && $parameters[3] == "page") $this->page = $accPage = $parameters[4];
        if(isset($parameters[4]) && $parameters[4] == "page") $this->page = $accPage = $parameters[5];
        if(isset($parameters[5]) && $parameters[5] == "page") $this->page = $accPage = $parameters[6];
        if(isset($parameters[6]) && $parameters[6] == "page") $this->page = $accPage = $parameters[7];
        if(isset($parameters[7]) && $parameters[7] == "page") $this->page = $accPage = $parameters[8];
        if(isset($parameters[8]) && $parameters[8] == "page") $this->page = $accPage = $parameters[9];
        // Need more? add..
        
        if(isset($accPage))
        {
            if($this->page != 1) $this->from = ($this->page - 1) * $this->recordsPerPage;
            if($this->page != 1) $this->to = $this->recordsPerPage;
        }
        else
            $this->page = 1;
        
        $uri = PROTOCOL.$_SERVER['HTTP_HOST']."/".$parameters[0]."/".$parameters[1];
        if(isset($parameters[2]) && $parameters[2] != "page") $uri = PROTOCOL.$_SERVER['HTTP_HOST']."/".$parameters[0]."/".$parameters[1]."/".$parameters[2];
        if(isset($parameters[2]) && $parameters[2] != "page" && isset($parameters[3]) && $parameters[3] != "page") $uri = PROTOCOL.$_SERVER['HTTP_HOST']."/".$parameters[0]."/".$parameters[1]."/".$parameters[2]."/".$parameters[3];
        if(isset($parameters[2]) && $parameters[2] != "page" && isset($parameters[3]) && $parameters[3] != "page" && isset($parameters[4]) && $parameters[4] != "page") $uri = PROTOCOL.$_SERVER['HTTP_HOST']."/".$parameters[0]."/".$parameters[1]."/".$parameters[2]."/".$parameters[3]."/".$parameters[4];
        if(isset($parameters[2]) && $parameters[2] != "page" && isset($parameters[3]) && $parameters[3] != "page" && isset($parameters[4]) && $parameters[4] != "page" && isset($parameters[5]) && $parameters[5] != "page") $uri = PROTOCOL.$_SERVER['HTTP_HOST']."/".$parameters[0]."/".$parameters[1]."/".$parameters[2]."/".$parameters[3]."/".$parameters[4]."/".$parameters[5];
        if(isset($parameters[2]) && $parameters[2] != "page" && isset($parameters[3]) && $parameters[3] != "page" && isset($parameters[4]) && $parameters[4] != "page" && isset($parameters[5]) && $parameters[5] != "page" && isset($parameters[6]) && $parameters[6] != "page") $uri = PROTOCOL.$_SERVER['HTTP_HOST']."/".$parameters[0]."/".$parameters[1]."/".$parameters[2]."/".$parameters[3]."/".$parameters[4]."/".$parameters[5]."/".$parameters[6];
        if(isset($parameters[2]) && $parameters[2] != "page" && isset($parameters[3]) && $parameters[3] != "page" && isset($parameters[4]) && $parameters[4] != "page" && isset($parameters[5]) && $parameters[5] != "page" && isset($parameters[6]) && $parameters[6] != "page" && isset($parameters[7]) && $parameters[7] != "page") $uri = PROTOCOL.$_SERVER['HTTP_HOST']."/".$parameters[0]."/".$parameters[1]."/".$parameters[2]."/".$parameters[3]."/".$parameters[4]."/".$parameters[5]."/".$parameters[6]."/".$parameters[7];
        if(isset($parameters[2]) && $parameters[2] != "page" && isset($parameters[3]) && $parameters[3] != "page" && isset($parameters[4]) && $parameters[4] != "page" && isset($parameters[5]) && $parameters[5] != "page" && isset($parameters[6]) && $parameters[6] != "page" && isset($parameters[7]) && $parameters[7] != "page" && isset($parameters[8]) && $parameters[8] != "page") $uri = PROTOCOL.$_SERVER['HTTP_HOST']."/".$parameters[0]."/".$parameters[1]."/".$parameters[2]."/".$parameters[3]."/".$parameters[4]."/".$parameters[5]."/".$parameters[6]."/".$parameters[7]."/".$parameters[8];
        // Need more? add..
        $this->uri = $uri;
        
        if($tpages > 1) $this->pagination = TRUE;
        
        return $this;
    }
}
