<?PHP

/* Entity for standard statistic group with multiple records **/

namespace src\Entities\Statistic;

class StatisticGroup
{
    private $group;
    private $statistics;
    
    public function __construct($group = false){
        $this->group = $group;
    }
    
   	public function getGroup(){
		return $this->group;
	}

	public function setGroup($group){
		$this->group = $group;
	}

	public function getStatistics(){
		return $this->statistics;
	}

	public function setStatistics($statistics){
		$this->statistics = $statistics;
	}
}
