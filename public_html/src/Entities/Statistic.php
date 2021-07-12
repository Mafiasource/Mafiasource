<?PHP

/* Entity for statistics (no table) */

namespace src\Entities;

class Statistic
{
    private $gameStatistic;
    private $richestStatistic;
    private $mostHonoredStatistic;
    private $newestMemberStatistic;
    private $killerkingStatistic;
    private $prisonBreakingStatistic;
    private $carjackingStatistic;
    private $crimesStatistic;
    private $pimpingStatistic;
    private $smugglingStatistic;
    private $populationStatistic;
    private $referralStatistic;
    
	public function getGameStatistic(){
		return $this->gameStatistic;
	}

	public function setGameStatistic($gameStatistic){
		$this->gameStatistic = $gameStatistic;
	}

	public function getRichestStatistic(){
		return $this->richestStatistic;
	}

	public function setRichestStatistic($richestStatistic){
		$this->richestStatistic = $richestStatistic;
	}

	public function getMostHonoredStatistic(){
		return $this->mostHonoredStatistic;
	}

	public function setMostHonoredStatistic($mostHonoredStatistic){
		$this->mostHonoredStatistic = $mostHonoredStatistic;
	}

	public function getNewestMemberStatistic(){
		return $this->newestMemberStatistic;
	}

	public function setNewestMemberStatistic($newestMemberStatistic){
		$this->newestMemberStatistic = $newestMemberStatistic;
	}

	public function getKillerkingStatistic(){
		return $this->killerkingStatistic;
	}

	public function setKillerkingStatistic($killerkingStatistic){
		$this->killerkingStatistic = $killerkingStatistic;
	}

	public function getPrisonBreakingStatistic(){
		return $this->prisonBreakingStatistic;
	}

	public function setPrisonBreakingStatistic($prisonBreakingStatistic){
		$this->prisonBreakingStatistic = $prisonBreakingStatistic;
	}

	public function getCarjackingStatistic(){
		return $this->carjackingStatistic;
	}

	public function setCarjackingStatistic($carjackingStatistic){
		$this->carjackingStatistic = $carjackingStatistic;
	}

	public function getCrimesStatistic(){
		return $this->crimesStatistic;
	}

	public function setCrimesStatistic($crimesStatistic){
		$this->crimesStatistic = $crimesStatistic;
	}

	public function getPimpingStatistic(){
		return $this->pimpingStatistic;
	}

	public function setPimpingStatistic($pimpingStatistic){
		$this->pimpingStatistic = $pimpingStatistic;
	}

	public function getSmugglingStatistic(){
		return $this->smugglingStatistic;
	}

	public function setSmugglingStatistic($smugglingStatistic){
		$this->smugglingStatistic = $smugglingStatistic;
	}

	public function getPopulationStatistic(){
		return $this->populationStatistic;
	}

	public function setPopulationStatistic($populationStatistic){
		$this->populationStatistic = $populationStatistic;
	}

	public function getReferralStatistic(){
		return $this->referralStatistic;
	}

	public function setReferralStatistic($referralStatistic){
		$this->referralStatistic = $referralStatistic;
	}
}
