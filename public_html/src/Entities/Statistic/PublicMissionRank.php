<?PHP

/* Entity for public mission ranking statistic */

namespace src\Entities\Statistic;

class PublicMissionRank
{
    private $position;
    private $username;
    private $amount;
    private $reward;
    private $additionalReward;
    
   	public function getPosition(){
        return $this->position;
    }

    public function setPosition($position){
        $this->position = $position;
    }

    public function getUsername(){
        return $this->username;
    }

    public function setUsername($username){
        $this->username = $username;
    }

    public function getAmount(){
        return $this->amount;
    }

    public function setAmount($amount){
        $this->amount = $amount;
    }

    public function getReward(){
        return $this->reward;
    }

    public function setReward($reward){
        $this->reward = $reward;
    }

    public function getAdditionalReward(){
        return $this->additionalReward;
    }

    public function setAdditionalReward($additionalReward){
        $this->additionalReward = $additionalReward;
    }
}
