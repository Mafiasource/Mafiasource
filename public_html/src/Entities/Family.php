<?PHP

/* Entity for table family */

namespace src\Entities;

class Family
{
    private $id;
    private $name;
    private $bossUID;
    private $boss;
    private $underbossUID;
    private $underboss;
    private $bankmanagerUID;
    private $bankmanager;
    private $forummodUID;
    private $forummod;
    private $vip;
    private $money;
    private $image;
    private $icon;
    private $startDate;
    private $join;
    private $leaveCosts;
    private $profile;
    private $crusher;
    private $converter;
    private $bullets;
    private $bulletFactory;
    private $bfProduction;
    private $brothel;
    private $cBulletFactory;
    private $cBrothel;
    
    private $totalMembers;
    private $totalScore;
    private $averageRank;
    private $mercenariesTotal;
    private $mercenariesUsed;
    private $mercenariesAvailable;
    private $active;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getBossUID(){
		return $this->bossUID;
	}

	public function setBossUID($bossUID){
		$this->bossUID = $bossUID;
	}

	public function getBoss(){
		return $this->boss;
	}

	public function setBoss($boss){
		$this->boss = $boss;
	}

	public function getUnderbossUID(){
		return $this->underbossUID;
	}

	public function setUnderbossUID($underbossUID){
		$this->underbossUID = $underbossUID;
	}

	public function getUnderboss(){
		return $this->underboss;
	}

	public function setUnderboss($underboss){
		$this->underboss = $underboss;
	}

	public function getBankmanagerUID(){
		return $this->bankmanagerUID;
	}

	public function setBankmanagerUID($bankmanagerUID){
		$this->bankmanagerUID = $bankmanagerUID;
	}

	public function getBankmanager(){
		return $this->bankmanager;
	}

	public function setBankmanager($bankmanager){
		$this->bankmanager = $bankmanager;
	}

	public function getForummodUID(){
		return $this->forummodUID;
	}

	public function setForummodUID($forummodUID){
		$this->forummodUID = $forummodUID;
	}

	public function getForummod(){
		return $this->forummod;
	}

	public function setForummod($forummod){
		$this->forummod = $forummod;
	}
    
    public function getVip(){
		return $this->vip;
	}

	public function setVip($vip){
		$this->vip = $vip;
	}

	public function getMoney(){
		return $this->money;
	}

	public function setMoney($money){
		$this->money = $money;
	}

	public function getImage(){
		return $this->image;
	}

	public function setImage($image){
		$this->image = $image;
	}

	public function getIcon(){
		return $this->icon;
	}

	public function setIcon($icon){
		$this->icon = $icon;
	}

	public function getStartDate(){
		return $this->startDate;
	}

	public function setStartDate($startDate){
		$this->startDate = $startDate;
	}

	public function getJoin(){
		return $this->join;
	}

	public function setJoin($join){
		$this->join = $join;
	}
    
    public function getLeaveCosts(){
		return $this->leaveCosts;
	}

	public function setLeaveCosts($leaveCosts){
		$this->leaveCosts = $leaveCosts;
	}
    
    public function getProfile(){
		return $this->profile;
	}

	public function setProfile($profile){
		$this->profile = $profile;
	}
    
    public function getCrusher(){
		return $this->crusher;
	}

	public function setCrusher($crusher){
		$this->crusher = $crusher;
	}
    
    public function getConverter(){
		return $this->converter;
	}

	public function setConverter($converter){
		$this->converter = $converter;
	}
    
    public function getBullets(){
		return $this->bullets;
	}

	public function setBullets($bullets){
		$this->bullets = $bullets;
	}
    
 	public function getBulletFactory(){
		return $this->bulletFactory;
	}

	public function setBulletFactory($bulletFactory){
		$this->bulletFactory = $bulletFactory;
	}

	public function getBfProduction(){
		return $this->bfProduction;
	}

	public function setBfProduction($bfProduction){
		$this->bfProduction = $bfProduction;
	}

	public function getBrothel(){
		return $this->brothel;
	}

	public function setBrothel($brothel){
		$this->brothel = $brothel;
	}

	public function getCBulletFactory(){
		return $this->cBulletFactory;
	}

	public function setCBulletFactory($cBulletFactory){
		$this->cBulletFactory = $cBulletFactory;
	}

	public function getCBrothel(){
		return $this->cBrothel;
	}

	public function setCBrothel($cBrothel){
		$this->cBrothel = $cBrothel;
	}

	public function getTotalMembers(){
		return $this->totalMembers;
	}

	public function setTotalMembers($totalMembers){
		$this->totalMembers = $totalMembers;
	}

	public function getTotalScore(){
		return $this->totalScore;
	}

	public function setTotalScore($totalScore){
		$this->totalScore = $totalScore;
	}
    
    public function getAverageRank(){
		return $this->averageRank;
	}

	public function setAverageRank($averageRank){
		$this->averageRank = $averageRank;
	}
    
   	public function getMercenariesTotal(){
		return $this->mercenariesTotal;
	}

	public function setMercenariesTotal($mercenariesTotal){
		$this->mercenariesTotal = $mercenariesTotal;
	}

	public function getMercenariesUsed(){
		return $this->mercenariesUsed;
	}

	public function setMercenariesUsed($mercenariesUsed){
		$this->mercenariesUsed = $mercenariesUsed;
	}

	public function getMercenariesAvailable(){
		return $this->mercenariesAvailable;
	}

	public function setMercenariesAvailable($mercenariesAvailable){
		$this->mercenariesAvailable = $mercenariesAvailable;
	}

	public function getActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
	}
}
