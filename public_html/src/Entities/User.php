<?PHP

/* Entity for table user */

namespace src\Entities;

class User
{
    private $id;
    private $username;
    private $usernameClassName;
    private $password;
    private $email;
    private $ip;
    private $registerDate;
    private $restartDate;
    private $isProtected;
    private $lastclick;
    private $activeTime;
    private $lang;
    private $charType;
    private $profession;
    private $referralOf;
    private $referrals;
    private $referralProfits;
    private $warns;
    private $avatar;
    private $profile;
    private $status;
    private $statusID;
    private $donator;
    private $donatorID;
    private $state;
    private $stateID;
    private $city;
    private $cityID;
    private $family;
    private $familyID;
    private $rankpoints;
    private $rankID;
    private $rankname;
    private $rankpercent;
    private $rankpercentBar;
    private $health;
    private $healthBar;
    private $scorePosition;
    private $score;
    private $cash;
    private $bank;
    private $swissBank;
    private $swissBankMax;
    private $moneyRank;
    private $prisonBusts;
    private $honorPoints;
    private $whoresStreet;
    private $whoresRLD;
    private $kills;
    private $deaths;
    private $headshots;
    private $bullets;
    private $weapon;
    private $protection;
    private $airplane;
    private $weaponExperience;
    private $weaponExperienceBar;
    private $weaponTraining;
    private $weaponTrainingBar;
    private $backfireType;
    private $backfireNumber;
    private $residence;
    private $residenceHistory;
    private $gymFastAction;
    private $gymTrainingBar;
    private $power;
    private $cardio;
    private $gymCompetitionWin;
    private $gymCompetitionLoss;
    private $gymCompetitionWLRatio;
    private $gymProfit;
    private $gymScorePointsEarned;
    private $luckybox;
    private $credits;
    private $creditsWon;
    private $crimesLv;
    private $crimesXp;
    private $crimesXpRaw;
    private $crimesProfit;
    private $crimesSuccess;
    private $crimesFail;
    private $crimesRankpoints;
    private $crimesSFRatio;
    private $vehiclesLv;
    private $vehiclesXp;
    private $vehiclesXpRaw;
    private $vehiclesProfit;
    private $vehiclesSuccess;
    private $vehiclesFail;
    private $vehiclesRankpoints;
    private $vehiclesSFRatio;
    private $pimpLv;
    private $pimpXp;
    private $pimpXpRaw;
    private $pimpProfit;
    private $pimpAttempts;
    private $pimpAmount;
    private $smugglingLv;
    private $smugglingXp;
    private $smugglingXpRaw;
    private $smugglingProfit;
    private $smugglingTrips;
    private $smugglingUnits;
    private $smugglingBusts;
    private $smugglingSFRatio;
    private $lastReadShoutboxID;
    private $lastReadFamilyShoutboxID;
    private $cHalvingTimes;
    private $cBribingPolice;
    private $cCrimes;
    private $cWeaponTraining;
    private $cGymTraining;
    private $cStealVehicles;
    private $cPimpWhores;
    private $cFamilyRaid;
    private $cFamilyCrimes;
    private $cBombardement;
    private $cTravelTime;
    private $cPrisonTime;
    private $cPimpWhoresFor;
    private $position;
    private $active;
    
    private $lastOnline;
    private $totalWhores;
    private $whoresList;
    private $ground;
    private $messagesCount;
    private $notificationsCount;
    private $inPrison;
    private $traveling;
    private $bankLogs;
    private $familyBoss;
    private $familyUnderboss;
    private $familyBankmanager;
    private $familyForummod;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getUsername(){
		return $this->username;
	}

	public function setUsername($username){
		$this->username = $username;
	}

	public function getUsernameClassName(){
		return $this->usernameClassName;
	}

	public function setUsernameClassName($usernameClassName){
		$this->usernameClassName = $usernameClassName;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setPassword($password){
		$this->password = $password;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getIp(){
		return $this->ip;
	}

	public function setIp($ip){
		$this->ip = $ip;
	}

	public function getRegisterDate(){
		return $this->registerDate;
	}

	public function setRegisterDate($registerDate){
		$this->registerDate = $registerDate;
	}

	public function getRestartDate(){
		return $this->restartDate;
	}

	public function setRestartDate($restartDate){
		$this->restartDate = $restartDate;
	}

	public function getIsProtected(){
		return $this->isProtected;
	}

	public function setIsProtected($isProtected){
		$this->isProtected = $isProtected;
	}

	public function getLastclick(){
		return $this->lastclick;
	}

	public function setLastclick($lastclick){
		$this->lastclick = $lastclick;
	}

	public function getActiveTime(){
		return $this->activeTime;
	}

	public function setActiveTime($activeTime){
		$this->activeTime = $activeTime;
	}

	public function getLang(){
		return $this->lang;
	}

	public function setLang($lang){
		$this->lang = $lang;
	}

	public function getCharType(){
		return $this->charType;
	}

	public function setCharType($charType){
		$this->charType = $charType;
	}

	public function getProfession(){
		return $this->profession;
	}

	public function setProfession($profession){
		$this->profession = $profession;
	}

	public function getReferralOf(){
		return $this->referralOf;
	}

	public function setReferralOf($referralOf){
		$this->referralOf = $referralOf;
	}

	public function getReferrals(){
		return $this->referrals;
	}

	public function setReferrals($referrals){
		$this->referrals = $referrals;
	}

	public function getReferralProfits(){
		return $this->referralProfits;
	}

	public function setReferralProfits($referralProfits){
		$this->referralProfits = $referralProfits;
	}

	public function getWarns(){
		return $this->warns;
	}

	public function setWarns($warns){
		$this->warns = $warns;
	}

	public function getAvatar(){
		return $this->avatar;
	}

	public function setAvatar($avatar){
		$this->avatar = $avatar;
	}

	public function getProfile(){
		return $this->profile;
	}

	public function setProfile($profile){
		$this->profile = $profile;
	}

	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status){
		$this->status = $status;
	}

	public function getStatusID(){
		return $this->statusID;
	}

	public function setStatusID($statusID){
		$this->statusID = $statusID;
	}

	public function getDonator(){
		return $this->donator;
	}

	public function setDonator($donator){
		$this->donator = $donator;
	}

	public function getDonatorID(){
		return $this->donatorID;
	}

	public function setDonatorID($donatorID){
		$this->donatorID = $donatorID;
	}

	public function getState(){
		return $this->state;
	}

	public function setState($state){
		$this->state = $state;
	}

	public function getStateID(){
		return $this->stateID;
	}

	public function setStateID($stateID){
		$this->stateID = $stateID;
	}

	public function getCity(){
		return $this->city;
	}

	public function setCity($city){
		$this->city = $city;
	}

	public function getCityID(){
		return $this->cityID;
	}

	public function setCityID($cityID){
		$this->cityID = $cityID;
	}

	public function getFamily(){
		return $this->family;
	}

	public function setFamily($family){
		$this->family = $family;
	}

	public function getFamilyID(){
		return $this->familyID;
	}

	public function setFamilyID($familyID){
		$this->familyID = $familyID;
	}

	public function getRankpoints(){
		return $this->rankpoints;
	}

	public function setRankpoints($rankpoints){
		$this->rankpoints = $rankpoints;
	}

	public function getRankID(){
		return $this->rankID;
	}

	public function setRankID($rankID){
		$this->rankID = $rankID;
	}

	public function getRankname(){
		return $this->rankname;
	}

	public function setRankname($rankname){
		$this->rankname = $rankname;
	}

	public function getRankpercent(){
		return $this->rankpercent;
	}

	public function setRankpercent($rankpercent){
		$this->rankpercent = $rankpercent;
	}

	public function getRankpercentBar(){
		return $this->rankpercentBar;
	}

	public function setRankpercentBar($rankpercentBar){
		$this->rankpercentBar = $rankpercentBar;
	}

	public function getHealth(){
		return $this->health;
	}

	public function setHealth($health){
		$this->health = $health;
	}

	public function getHealthBar(){
		return $this->healthBar;
	}

	public function setHealthBar($healthBar){
		$this->healthBar = $healthBar;
	}

	public function getScorePosition(){
		return $this->scorePosition;
	}

	public function setScorePosition($scorePosition){
		$this->scorePosition = $scorePosition;
	}

	public function getScore(){
		return $this->score;
	}

	public function setScore($score){
		$this->score = $score;
	}

	public function getCash(){
		return $this->cash;
	}

	public function setCash($cash){
		$this->cash = $cash;
	}

	public function getBank(){
		return $this->bank;
	}

	public function setBank($bank){
		$this->bank = $bank;
	}

	public function getSwissBank(){
		return $this->swissBank;
	}

	public function setSwissBank($swissBank){
		$this->swissBank = $swissBank;
	}

	public function getSwissBankMax(){
		return $this->swissBankMax;
	}

	public function setSwissBankMax($swissBankMax){
		$this->swissBankMax = $swissBankMax;
	}

	public function getMoneyRank(){
		return $this->moneyRank;
	}

	public function setMoneyRank($moneyRank){
		$this->moneyRank = $moneyRank;
	}

	public function getPrisonBusts(){
		return $this->prisonBusts;
	}

	public function setPrisonBusts($prisonBusts){
		$this->prisonBusts = $prisonBusts;
	}

	public function getHonorPoints(){
		return $this->honorPoints;
	}

	public function setHonorPoints($honorPoints){
		$this->honorPoints = $honorPoints;
	}

	public function getWhoresStreet(){
		return $this->whoresStreet;
	}

	public function setWhoresStreet($whoresStreet){
		$this->whoresStreet = $whoresStreet;
	}

	public function getWhoresRLD(){
		return $this->whoresRLD;
	}

	public function setWhoresRLD($whoresRLD){
		$this->whoresRLD = $whoresRLD;
	}

	public function getKills(){
		return $this->kills;
	}

	public function setKills($kills){
		$this->kills = $kills;
	}

	public function getDeaths(){
		return $this->deaths;
	}

	public function setDeaths($deaths){
		$this->deaths = $deaths;
	}

	public function getHeadshots(){
		return $this->headshots;
	}

	public function setHeadshots($headshots){
		$this->headshots = $headshots;
	}

	public function getBullets(){
		return $this->bullets;
	}

	public function setBullets($bullets){
		$this->bullets = $bullets;
	}

	public function getWeapon(){
		return $this->weapon;
	}

	public function setWeapon($weapon){
		$this->weapon = $weapon;
	}

	public function getProtection(){
		return $this->protection;
	}

	public function setProtection($protection){
		$this->protection = $protection;
	}

	public function getAirplane(){
		return $this->airplane;
	}

	public function setAirplane($airplane){
		$this->airplane = $airplane;
	}

	public function getWeaponExperience(){
		return $this->weaponExperience;
	}

	public function setWeaponExperience($weaponExperience){
		$this->weaponExperience = $weaponExperience;
	}

	public function getWeaponExperienceBar(){
		return $this->weaponExperienceBar;
	}

	public function setWeaponExperienceBar($weaponExperienceBar){
		$this->weaponExperienceBar = $weaponExperienceBar;
	}

	public function getWeaponTraining(){
		return $this->weaponTraining;
	}

	public function setWeaponTraining($weaponTraining){
		$this->weaponTraining = $weaponTraining;
	}

	public function getWeaponTrainingBar(){
		return $this->weaponTrainingBar;
	}

	public function setWeaponTrainingBar($weaponTrainingBar){
		$this->weaponTrainingBar = $weaponTrainingBar;
	}

	public function getBackfireType(){
		return $this->backfireType;
	}

	public function setBackfireType($backfireType){
		$this->backfireType = $backfireType;
	}

	public function getBackfireNumber(){
		return $this->backfireNumber;
	}

	public function setBackfireNumber($backfireNumber){
		$this->backfireNumber = $backfireNumber;
	}

	public function getResidence(){
		return $this->residence;
	}

	public function setResidence($residence){
		$this->residence = $residence;
	}

	public function getResidenceHistory(){
		return $this->residenceHistory;
	}

	public function setResidenceHistory($residenceHistory){
		$this->residenceHistory = $residenceHistory;
	}

	public function getGymFastAction(){
		return $this->gymFastAction;
	}

	public function setGymFastAction($gymFastAction){
		$this->gymFastAction = $gymFastAction;
	}

	public function getGymTrainingBar(){
		return $this->gymTrainingBar;
	}

	public function setGymTrainingBar($gymTrainingBar){
		$this->gymTrainingBar = $gymTrainingBar;
	}

	public function getPower(){
		return $this->power;
	}

	public function setPower($power){
		$this->power = $power;
	}

	public function getCardio(){
		return $this->cardio;
	}

	public function setCardio($cardio){
		$this->cardio = $cardio;
	}

	public function getGymCompetitionWin(){
		return $this->gymCompetitionWin;
	}

	public function setGymCompetitionWin($gymCompetitionWin){
		$this->gymCompetitionWin = $gymCompetitionWin;
	}

	public function getGymCompetitionLoss(){
		return $this->gymCompetitionLoss;
	}

	public function setGymCompetitionLoss($gymCompetitionLoss){
		$this->gymCompetitionLoss = $gymCompetitionLoss;
	}

	public function getGymCompetitionWLRatio(){
		return $this->gymCompetitionWLRatio;
	}

	public function setGymCompetitionWLRatio($gymCompetitionWLRatio){
		$this->gymCompetitionWLRatio = $gymCompetitionWLRatio;
	}

	public function getGymProfit(){
		return $this->gymProfit;
	}

	public function setGymProfit($gymProfit){
		$this->gymProfit = $gymProfit;
	}

	public function getGymScorePointsEarned(){
		return $this->gymScorePointsEarned;
	}

	public function setGymScorePointsEarned($gymScorePointsEarned){
		$this->gymScorePointsEarned = $gymScorePointsEarned;
	}

	public function getLuckybox(){
		return $this->luckybox;
	}

	public function setLuckybox($luckybox){
		$this->luckybox = $luckybox;
	}

	public function getCredits(){
		return $this->credits;
	}

	public function setCredits($credits){
		$this->credits = $credits;
	}

	public function getCreditsWon(){
		return $this->creditsWon;
	}

	public function setCreditsWon($creditsWon){
		$this->creditsWon = $creditsWon;
	}

	public function getCrimesLv(){
		return $this->crimesLv;
	}

	public function setCrimesLv($crimesLv){
		$this->crimesLv = $crimesLv;
	}

	public function getCrimesXp(){
		return $this->crimesXp;
	}

	public function setCrimesXp($crimesXp){
		$this->crimesXp = $crimesXp;
	}

	public function getCrimesXpRaw(){
		return $this->crimesXpRaw;
	}

	public function setCrimesXpRaw($crimesXpRaw){
		$this->crimesXpRaw = $crimesXpRaw;
	}

	public function getCrimesProfit(){
		return $this->crimesProfit;
	}

	public function setCrimesProfit($crimesProfit){
		$this->crimesProfit = $crimesProfit;
	}

	public function getCrimesSuccess(){
		return $this->crimesSuccess;
	}

	public function setCrimesSuccess($crimesSuccess){
		$this->crimesSuccess = $crimesSuccess;
	}

	public function getCrimesFail(){
		return $this->crimesFail;
	}

	public function setCrimesFail($crimesFail){
		$this->crimesFail = $crimesFail;
	}

	public function getCrimesRankpoints(){
		return $this->crimesRankpoints;
	}

	public function setCrimesRankpoints($crimesRankpoints){
		$this->crimesRankpoints = $crimesRankpoints;
	}

	public function getCrimesSFRatio(){
		return $this->crimesSFRatio;
	}

	public function setCrimesSFRatio($crimesSFRatio){
		$this->crimesSFRatio = $crimesSFRatio;
	}

	public function getVehiclesLv(){
		return $this->vehiclesLv;
	}

	public function setVehiclesLv($vehiclesLv){
		$this->vehiclesLv = $vehiclesLv;
	}

	public function getVehiclesXp(){
		return $this->vehiclesXp;
	}

	public function setVehiclesXp($vehiclesXp){
		$this->vehiclesXp = $vehiclesXp;
	}

	public function getVehiclesXpRaw(){
		return $this->vehiclesXpRaw;
	}

	public function setVehiclesXpRaw($vehiclesXpRaw){
		$this->vehiclesXpRaw = $vehiclesXpRaw;
	}

	public function getVehiclesProfit(){
		return $this->vehiclesProfit;
	}

	public function setVehiclesProfit($vehiclesProfit){
		$this->vehiclesProfit = $vehiclesProfit;
	}

	public function getVehiclesSuccess(){
		return $this->vehiclesSuccess;
	}

	public function setVehiclesSuccess($vehiclesSuccess){
		$this->vehiclesSuccess = $vehiclesSuccess;
	}

	public function getVehiclesFail(){
		return $this->vehiclesFail;
	}

	public function setVehiclesFail($vehiclesFail){
		$this->vehiclesFail = $vehiclesFail;
	}

	public function getVehiclesRankpoints(){
		return $this->vehiclesRankpoints;
	}

	public function setVehiclesRankpoints($vehiclesRankpoints){
		$this->vehiclesRankpoints = $vehiclesRankpoints;
	}

	public function getVehiclesSFRatio(){
		return $this->vehiclesSFRatio;
	}

	public function setVehiclesSFRatio($vehiclesSFRatio){
		$this->vehiclesSFRatio = $vehiclesSFRatio;
	}

	public function getPimpLv(){
		return $this->pimpLv;
	}

	public function setPimpLv($pimpLv){
		$this->pimpLv = $pimpLv;
	}

	public function getPimpXp(){
		return $this->pimpXp;
	}

	public function setPimpXp($pimpXp){
		$this->pimpXp = $pimpXp;
	}

	public function getPimpXpRaw(){
		return $this->pimpXpRaw;
	}

	public function setPimpXpRaw($pimpXpRaw){
		$this->pimpXpRaw = $pimpXpRaw;
	}

	public function getPimpProfit(){
		return $this->pimpProfit;
	}

	public function setPimpProfit($pimpProfit){
		$this->pimpProfit = $pimpProfit;
	}

	public function getPimpAttempts(){
		return $this->pimpAttempts;
	}

	public function setPimpAttempts($pimpAttempts){
		$this->pimpAttempts = $pimpAttempts;
	}

	public function getPimpAmount(){
		return $this->pimpAmount;
	}

	public function setPimpAmount($pimpAmount){
		$this->pimpAmount = $pimpAmount;
	}

	public function getSmugglingLv(){
		return $this->smugglingLv;
	}

	public function setSmugglingLv($smugglingLv){
		$this->smugglingLv = $smugglingLv;
	}

	public function getSmugglingXp(){
		return $this->smugglingXp;
	}

	public function setSmugglingXp($smugglingXp){
		$this->smugglingXp = $smugglingXp;
	}

	public function getSmugglingXpRaw(){
		return $this->smugglingXpRaw;
	}

	public function setSmugglingXpRaw($smugglingXpRaw){
		$this->smugglingXpRaw = $smugglingXpRaw;
	}

	public function getSmugglingProfit(){
		return $this->smugglingProfit;
	}

	public function setSmugglingProfit($smugglingProfit){
		$this->smugglingProfit = $smugglingProfit;
	}

	public function getSmugglingTrips(){
		return $this->smugglingTrips;
	}

	public function setSmugglingTrips($smugglingTrips){
		$this->smugglingTrips = $smugglingTrips;
	}

	public function getSmugglingUnits(){
		return $this->smugglingUnits;
	}

	public function setSmugglingUnits($smugglingUnits){
		$this->smugglingUnits = $smugglingUnits;
	}

	public function getSmugglingBusts(){
		return $this->smugglingBusts;
	}

	public function setSmugglingBusts($smugglingBusts){
		$this->smugglingBusts = $smugglingBusts;
	}

	public function getSmugglingSFRatio(){
		return $this->smugglingSFRatio;
	}

	public function setSmugglingSFRatio($smugglingSFRatio){
		$this->smugglingSFRatio = $smugglingSFRatio;
	}
    
	public function getLastReadShoutboxID(){
		return $this->lastReadShoutboxID;
	}

	public function setLastReadShoutboxID($lastReadShoutboxID){
		$this->lastReadShoutboxID = $lastReadShoutboxID;
	}

	public function getLastReadFamilyShoutboxID(){
		return $this->lastReadFamilyShoutboxID;
	}

	public function setLastReadFamilyShoutboxID($lastReadFamilyShoutboxID){
		$this->lastReadFamilyShoutboxID = $lastReadFamilyShoutboxID;
	}
    
    public function getCHalvingTimes(){
		return $this->cHalvingTimes;
	}

	public function setCHalvingTimes($cHalvingTimes){
		$this->cHalvingTimes = $cHalvingTimes;
	}
    
    public function getCBribingPolice(){
		return $this->cBribingPolice;
	}

	public function setCBribingPolice($cBribingPolice){
		$this->cBribingPolice = $cBribingPolice;
	}

	public function getCCrimes(){
		return $this->cCrimes;
	}

	public function setCCrimes($cCrimes){
		$this->cCrimes = $cCrimes;
	}

	public function getCWeaponTraining(){
		return $this->cWeaponTraining;
	}

	public function setCWeaponTraining($cWeaponTraining){
		$this->cWeaponTraining = $cWeaponTraining;
	}

	public function getCGymTraining(){
		return $this->cGymTraining;
	}

	public function setCGymTraining($cGymTraining){
		$this->cGymTraining = $cGymTraining;
	}

	public function getCStealVehicles(){
		return $this->cStealVehicles;
	}

	public function setCStealVehicles($cStealVehicles){
		$this->cStealVehicles = $cStealVehicles;
	}

	public function getCPimpWhores(){
		return $this->cPimpWhores;
	}

	public function setCPimpWhores($cPimpWhores){
		$this->cPimpWhores = $cPimpWhores;
	}

	public function getCFamilyRaid(){
		return $this->cFamilyRaid;
	}

	public function setCFamilyRaid($cFamilyRaid){
		$this->cFamilyRaid = $cFamilyRaid;
	}

	public function getCFamilyCrimes(){
		return $this->cFamilyCrimes;
	}

	public function setCFamilyCrimes($cFamilyCrimes){
		$this->cFamilyCrimes = $cFamilyCrimes;
	}

	public function getCBombardement(){
		return $this->cBombardement;
	}

	public function setCBombardement($cBombardement){
		$this->cBombardement = $cBombardement;
	}

	public function getCTravelTime(){
		return $this->cTravelTime;
	}

	public function setCTravelTime($cTravelTime){
		$this->cTravelTime = $cTravelTime;
	}

	public function getCPrisonTime(){
		return $this->cPrisonTime;
	}

	public function setCPrisonTime($cPrisonTime){
		$this->cPrisonTime = $cPrisonTime;
	}

	public function getCPimpWhoresFor(){
		return $this->cPimpWhoresFor;
	}

	public function setCPimpWhoresFor($cPimpWhoresFor){
		$this->cPimpWhoresFor = $cPimpWhoresFor;
	}

	public function getPosition(){
		return $this->position;
	}

	public function setPosition($position){
		$this->position = $position;
	}

	public function getActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
	}
    
    public function getLastOnline(){
		return $this->lastOnline;
	}

	public function setLastOnline($lastOnline){
		$this->lastOnline = $lastOnline;
	}
    
    public function getTotalWhores(){
		return $this->totalWhores;
	}

	public function setTotalWhores($totalWhores){
		$this->totalWhores = $totalWhores;
	}

	public function getWhoresList(){
		return $this->whoresList;
	}

	public function setWhoresList($whoresList){
		$this->whoresList = $whoresList;
	}
    
	public function getGround(){
		return $this->ground;
	}

	public function setGround($ground){
		$this->ground = $ground;
	}

	public function getMessagesCount(){
		return $this->messagesCount;
	}

	public function setMessagesCount($messagesCount){
		$this->messagesCount = $messagesCount;
	}

	public function getNotificationsCount(){
		return $this->notificationsCount;
	}

	public function setNotificationsCount($notificationsCount){
		$this->notificationsCount = $notificationsCount;
	}

	public function getInPrison(){
		return $this->inPrison;
	}

	public function setInPrison($inPrison){
		$this->inPrison = $inPrison;
	}

	public function getTraveling(){
		return $this->traveling;
	}

	public function setTraveling($traveling){
		$this->traveling = $traveling;
	}

	public function getBankLogs(){
		return $this->bankLogs;
	}

	public function setBankLogs($bankLogs){
		$this->bankLogs = $bankLogs;
	}

	public function getFamilyBoss(){
		return $this->familyBoss;
	}

	public function setFamilyBoss($familyBoss){
		$this->familyBoss = $familyBoss;
	}

	public function getFamilyUnderboss(){
		return $this->familyUnderboss;
	}

	public function setFamilyUnderboss($familyUnderboss){
		$this->familyUnderboss = $familyUnderboss;
	}

	public function getFamilyBankmanager(){
		return $this->familyBankmanager;
	}

	public function setFamilyBankmanager($familyBankmanager){
		$this->familyBankmanager = $familyBankmanager;
	}

	public function getFamilyForummod(){
		return $this->familyForummod;
	}

	public function setFamilyForummod($familyForummod){
		$this->familyForummod = $familyForummod;
	}
}
