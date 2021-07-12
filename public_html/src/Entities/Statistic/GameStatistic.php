<?PHP

/* Entity for game statistics (not a table) */

namespace src\Entities\Statistic;

class GameStatistic
{
    private $totalMembers;
    private $totalCash;
    private $totalBank;
    private $totalMoney;
    private $averageMoney;
    private $totalFamilies;
    private $totalBullets;
    private $averageBullets;
    private $totalDeathNow;
    private $totalBanned;
    
	public function getTotalMembers(){
		return $this->totalMembers;
	}

	public function setTotalMembers($totalMembers){
		$this->totalMembers = $totalMembers;
	}

	public function getTotalCash(){
		return $this->totalCash;
	}

	public function setTotalCash($totalCash){
		$this->totalCash = $totalCash;
	}

	public function getTotalBank(){
		return $this->totalBank;
	}

	public function setTotalBank($totalBank){
		$this->totalBank = $totalBank;
	}

	public function getTotalMoney(){
		return $this->totalMoney;
	}

	public function setTotalMoney($totalMoney){
		$this->totalMoney = $totalMoney;
	}

	public function getAverageMoney(){
		return $this->averageMoney;
	}

	public function setAverageMoney($averageMoney){
		$this->averageMoney = $averageMoney;
	}

	public function getTotalFamilies(){
		return $this->totalFamilies;
	}

	public function setTotalFamilies($totalFamilies){
		$this->totalFamilies = $totalFamilies;
	}

	public function getTotalBullets(){
		return $this->totalBullets;
	}

	public function setTotalBullets($totalBullets){
		$this->totalBullets = $totalBullets;
	}

	public function getAverageBullets(){
		return $this->averageBullets;
	}

	public function setAverageBullets($averageBullets){
		$this->averageBullets = $averageBullets;
	}

	public function getTotalDeathNow(){
		return $this->totalDeathNow;
	}

	public function setTotalDeathNow($totalDeathNow){
		$this->totalDeathNow = $totalDeathNow;
	}

	public function getTotalBanned(){
		return $this->totalBanned;
	}

	public function setTotalBanned($totalBanned){
		$this->totalBanned = $totalBanned;
	}
}
