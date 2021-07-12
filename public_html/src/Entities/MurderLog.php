<?PHP

/* Entity for table murder_log */

namespace src\Entities;

class MurderLog
{
    private $id;
    private $attackerID;
    private $attacker;
    private $victimID;
    private $victim;
    private $time;
    private $result;
    
    private $date;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getAttackerID(){
		return $this->attackerID;
	}

	public function setAttackerID($attackerID){
		$this->attackerID = $attackerID;
	}

	public function getAttacker(){
		return $this->attacker;
	}

	public function setAttacker($attacker){
		$this->attacker = $attacker;
	}

	public function getVictimID(){
		return $this->victimID;
	}

	public function setVictimID($victimID){
		$this->victimID = $victimID;
	}

	public function getVictim(){
		return $this->victim;
	}

	public function setVictim($victim){
		$this->victim = $victim;
	}

	public function getTime(){
		return $this->time;
	}

	public function setTime($time){
		$this->time = $time;
	}

	public function getResult(){
		return $this->result;
	}

	public function setResult($result){
		$this->result = $result;
	}
    
    public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}
}
