<?PHP

/* Entity for table public_mission */

namespace src\Entities;

class PublicMission
{
    private $id;
    private $missionID;
    private $minAmount;
    private $rewardType;
    private $rewardAmount;
    private $reward2Type;
    private $reward2Amount;
    
    private $missionName;
    private $missionDescription;
    private $progress;
    private $rewardDb;
    private $reward2Db;
    
	public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getMissionID(){
        return $this->missionID;
    }

    public function setMissionID($missionID){
        $this->missionID = $missionID;
    }

    public function getMinAmount(){
        return $this->minAmount;
    }

    public function setMinAmount($minAmount){
        $this->minAmount = $minAmount;
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

    public function getReward2Type(){
        return $this->reward2Type;
    }

    public function setReward2Type($reward2Type){
        $this->reward2Type = $reward2Type;
    }

    public function getReward2Amount(){
        return $this->reward2Amount;
    }

    public function setReward2Amount($reward2Amount){
        $this->reward2Amount = $reward2Amount;
    }

    public function getMissionName(){
        return $this->missionName;
    }

    public function setMissionName($missionName){
        $this->missionName = $missionName;
    }

    public function getMissionDescription(){
        return $this->missionDescription;
    }

    public function setMissionDescription($missionDescription){
        $this->missionDescription = $missionDescription;
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

    public function getReward2Db(){
        return $this->reward2Db;
    }

    public function setReward2Db($reward2Db){
        $this->reward2Db = $reward2Db;
    }
}
