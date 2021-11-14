<?PHP
namespace src\Business;

use app\config\Routing;
use src\Data\StatisticDAO;
 
class StatisticService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new StatisticDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;   
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public static function eazyReadNumber($n)
    {
        $n = (0+str_replace(",","",$n));
        
        if(!is_numeric($n)) return false;
        
        if($n>1000000000000) return round(($n/1000000000000),1).'T+';
        else if($n>1000000000) return round(($n/1000000000),1).'B+';
        else if($n>1000000) return round(($n/1000000),1).'M+';
        else if($n>1000) return round(($n/1000),1).'K+';
       
        return number_format($n);
    }
    
    public function getOutgameStatistics()
    {
        return $this->data->getOutgameStatistics();
    }
    
    public function getStatisticsPage($round = "")
    {
        return $this->data->getStatisticsPage($round);
    }
    
    public function getHallOfFamePage($round = "")
    {
        return $this->data->getHallOfFamePage($round);
    }
    
    public function getHallOfFameRounds()
    {
        return $this->data->getHallOfFameRounds();
    }
}
