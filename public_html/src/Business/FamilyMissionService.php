<?PHP

namespace src\Business;

use app\config\Routing;
use src\Data\FamilyMissionDAO;

class FamilyMissionService
{
    private $data;
    
    public $missions = array(1 => "Carjacker", "Killer Family", "Recruiters", "Bullet Mastery", "High End Brothel", "Crush 'N Convert", "Robbery Crew", "Own The World");
    public $missionDescriptions = array(
        'nl' => array(
            1 => "Om deze missie te halen moet je een bepaald level halen met voertuigen stelen.",
            "Ben jij een kille misdadiger? Voor deze missie moet je een bepaald level halen met misdaden.",
            "Wordt de pimpmaster door een bepaald level te bereiken met hoeren pimpen.",
            "Ben jij een onopvallende smokkelaar? Voor deze missie moet je een bepaald level halen met smokkelen.",
            "Kan je samen met je familieleden een aantal keer succesvol de bank beroven?",
            "Ben jij een koelbloedige killer? Voor deze missie moet je een aantal kills maken.",
            "Lukt het jou om in 1 ronde alle voertuigen te stelen?",
            "Wapen tekorten in {state}! Kan jij een transportlijn opzetten? Bezorg tot {amount} wapens zelf in {state}."
        ),
        'en' => array(
            1 => "To complete this mission you must reach a certain level with stealing vehicles.",
            "Are you a cold criminal? For this mission you have to reach a certain level with crimes.",
            "Become the pimp master by reaching a certain level with pimp whores.",
            "Are you an inconspicuous smuggler? For this mission you have to reach a certain level with smuggling.",
            "Can you successfully rob the bank several times with your family members?",
            "Are you a cold-blooded killer? You must complete a number of kills for this mission.",
            "Can you steal all vehicles in 1 round?",
            "Weapon shortages in {state}! Can you set up a transport line? Deliver up to {amount} weapons yourself in {state}."
        )
    );
    public $missionTiers = array(
        1 => array('todo' => array(1 => 5, 15, 30, 50, 75, 100), 'prizeMoney' => array(1 => 1250000, 7500000, 20000000, 35000000, 50000000, 100000000), 'prizeHp' => array(1 => 5, 15, 30, 60, 150, 300)),
        array('todo' => array(1 => 5, 15, 30, 50, 75, 100), 'prizeMoney' => array(1 => 1250000, 7500000, 20000000, 35000000, 50000000, 100000000), 'prizeHp' => array(1 => 5, 15, 30, 60, 150, 300)),
        array('todo' => array(1 => 5, 15, 30, 50, 75, 100), 'prizeMoney' => array(1 => 1250000, 7500000, 20000000, 35000000, 50000000, 100000000), 'prizeHp' => array(1 => 5, 15, 30, 60, 150, 300)),
        array('todo' => array(1 => 5, 15, 30, 50, 75, 100), 'prizeMoney' => array(1 => 1250000, 7500000, 20000000, 35000000, 50000000, 100000000), 'prizeHp' => array(1 => 5, 15, 30, 60, 150, 300)),
        array('todo' => array(1 => 3, 10, 25, 50, 75, 100), 'prizeMoney' => array(1 => 750000, 5000000, 7500000, 25000000, 50000000, 100000000), 'prizeHp' => array(1 => 15, 35, 80, 150, 225, 300)),
        array('todo' => array(1 => 5, 15, 30, 50, 75, 100), 'prizeMoney' => array(1 => 1250000, 7500000, 12500000, 25000000, 50000000, 100000000), 'prizeHp' => array(1 => 15, 35, 80, 150, 225, 300)),
        array('todo' => array(1 => 5, 15, 30, 50, 75, 100), 'prizeMoney' => array(1 => 1250000, 7500000, 12500000, 25000000, 50000000, 100000000), 'prizeHp' => array(1 => 15, 35, 80, 150, 225, 300)),
        array('todo' => array(1 => 10000, 25000, 50000, 100000, 200000, 300000), 'prizeMoney' => array(1 => 1250000, 7500000, 12500000, 25000000, 50000000, 100000000), 'prizeHp' => array(1 => 15, 35, 80, 150, 225, 300)),
    );
    
    public function __construct()
    {
        $this->data = new FamilyMissionDAO();
    }

    public function __destruct()
    {
        $this->data = null;
    }

    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function getMissions()
    {
        global $lang;
        $list = array();
        foreach($this->missions AS $k => $m)
            $list[$k] = array('name' => $m, 'description' => $this->missionDescriptions[$lang][$k]);
        
        return $list;
    }
    
    public function getMissionTiersAndProgress()
    {
        return $this->data->getMissionTiersAndProgress();
    }
    
    public function payoutMissionPrize($bank)
    {
        global $userData;
        return $this->data->payoutMissionPrize($userData->getFamilyID(), $bank);
    }
    
    public function getMissionTierAndProgressByMission($m)
    {
        global $userData;
        return $this->data->getMissionTierAndProgressByMission($userData->getFamilyID(), $m);
    }
    
    public function userHasStolenVehicleBefore($vehicleID)
    {
        return $this->data->userHasStolenVehicleBefore($vehicleID);
    }
    
    public function addNewCarjackerVehicle($vehicleID)
    {
        return $this->data->addNewCarjackerVehicle($vehicleID);
    }
    
    public function addCountToCarjackerVehicle($vehicleID)
    {
        return $this->data->addCountToCarjackerVehicle($vehicleID);
    }
    
    public function addToMission8Count($amount)
    {
        return $this->data->addToMission8Count($amount);
    }
    
    public function getProfileMissionsByUserID($userID)
    {
        return $this->data->getProfileMissionsByUserID($userID);
    }
}
