<?PHP

/* Entity for table daily_challenge */

namespace src\Entities;

class DailyChallenge
{
    private $id;
    private $challengeID;
    private $amount;
    private $rewardType;
    private $rewardAmount;
    
    private $challengeName;
    private $challengeDescription;
    private $progress;
    private $rewardDb;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getChallengeID(){
		return $this->challengeID;
	}

	public function setChallengeID($challengeID){
		$this->challengeID = $challengeID;
	}

	public function getAmount(){
		return $this->amount;
	}

	public function setAmount($amount){
		$this->amount = $amount;
	}

	public function getRewardType(){
		return $this->rewardType;
	}

	public function setRewardType($rewardType){
		$this->rewardType = $rewardType;
	}

	public function getRewardAmount(){
		return $this->rewardAmount;
	}

	public function setRewardAmount($rewardAmount){
		$this->rewardAmount = $rewardAmount;
	}

	public function getChallengeName(){
		return $this->challengeName;
	}

	public function setChallengeName($challengeName){
		$this->challengeName = $challengeName;
	}

	public function getChallengeDescription(){
		return $this->challengeDescription;
	}

	public function setChallengeDescription($challengeDescription){
		$this->challengeDescription = $challengeDescription;
	}
    
    public function getProgress(){
		return $this->progress;
	}

	public function setProgress($progress){
		$this->progress = $progress;
	}
    
    public function getRewardDb(){
		return $this->rewardDb;
	}

	public function setRewardDb($rewardDb){
		$this->rewardDb = $rewardDb;
	}
}
