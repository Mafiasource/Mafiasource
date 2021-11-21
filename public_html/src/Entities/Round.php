<?PHP

/* Entity for table city */

namespace src\Entities;

class Round
{
    private $id;
    private $round;
    private $startDate;
    private $endDate;
    private $hofJson;
    private $dbbackup;
    private $active;
    
    private $roundName;
    private $roundDatabase;
    
   	public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getRound(){
        return $this->round;
    }

    public function setRound($round){
        $this->round = $round;
    }

    public function getStartDate(){
        return $this->startDate;
    }

    public function setStartDate($startDate){
        $this->startDate = $startDate;
    }

    public function getEndDate(){
        return $this->endDate;
    }

    public function setEndDate($endDate){
        $this->endDate = $endDate;
    }

    public function getHofJson(){
        return $this->hofJson;
    }

    public function setHofJson($hofJson){
        $this->hofJson = $hofJson;
    }

    public function getDbbackup(){
        return $this->dbbackup;
    }

    public function setDbbackup($dbbackup){
        $this->dbbackup = $dbbackup;
    }

    public function getActive(){
        return $this->active;
    }

    public function setActive($active){
        $this->active = $active;
    }
    
    public function getRoundName(){
        return $this->roundName;
    }

    public function setRoundName($roundName){
        $this->roundName = $roundName;
    }
    
    public function getRoundDatabase(){
        return $this->roundDatabase;
    }

    public function setRoundDatabase($roundDatabase){
        $this->roundDatabase = $roundDatabase;
    }
}
