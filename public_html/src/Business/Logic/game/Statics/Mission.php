<?PHP

namespace src\Business\Logic\game\Statics;

use src\Business\VehicleService;
use src\Data\config\DBConfig;

class Mission
{
    public $missions = array(1 => "Gone in 60 seconds", "Crime Time", "Pimpmaster", "Smuggler", "Rob The Bank", "Killfrenzy", "Carjacker", "Gun Runner");
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
        
        8
        =>
        array('todo' => array(1 => 10000, 25000, 50000, 100000, 200000, 300000), 'prizeMoney' => array(1 => 1250000, 7500000, 12500000, 25000000, 50000000, 100000000), 'prizeHp' => array(1 => 15, 35, 80, 150, 225, 300))
    );
    
    public function __construct($fromCron = false)
    {
        if($fromCron === false)
        {
            $vehicleService = new VehicleService();
            $vehicles = $vehicleService->getRecordsCount();
            $this->missionTiers[7] = array('todo' => array(1 => $vehicles), 'prizeMoney' => array(1 => $vehicles * 5000000), 'prizeHp' => array(1 => $vehicles * 15));
        }
        else
            $this->missionTiers[7] = array('todo' => array(1 => 100), 'prizeMoney' => array(1 => 100 * 5000000), 'prizeHp' => array(1 => 100 * 15));
    }
    
    public function payoutMissionPrize($bank, $hp, $userID = false)
    {
        if(isset($_SESSION['UID']) && $userID === false)
            $userID = $_SESSION['UID'];
        
        if($userID !== false)
        {
            $connection = new DBConfig();
            $connection->setData("
                UPDATE `user` SET `bank`=`bank`+ :b, `honorPoints`=`honorPoints`+ :hp WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':b' => $bank, ':hp' => $hp, ':uid' => $userID));
        }
    }
    
    private function getMissionTierByMissionAndProgress($m, $p)
    {
        $missionTiers = $this->missionTiers[$m];
        $tierProgress = array('t' => 1, 'p' => $p);
        foreach($missionTiers['todo'] AS $k => $todo)
        {
            if($p >= $todo)
            {
                if(array_key_exists($k + 1, $missionTiers['todo']))
                    $tierProgress['t'] = $k + 1;
                else
                    $tierProgress['t'] = $k;
            }
        }
        return $tierProgress;
    }
    
    public function getMissionTierAndProgressByMission($mission, $userID = false)
    {
        if(isset($_SESSION['UID']) && $userID === false)
            $userID = $_SESSION['UID'];
        
        if($userID !== false)
        {
            switch($mission)
            {
                default:
                case 1:
                    $qrySelect = "`vehiclesLv` AS `m1`";
                    break;
                case 2:
                    $qrySelect = "`crimesLv` AS `m2`";
                    break;
                case 3:
                    $qrySelect = "`pimpLv` AS `m3`";
                    break;
                case 4:
                    $qrySelect = "`smugglingLv` AS `m4`";
                    break;
                case 5:
                    $qrySelect = "`m5c` AS `m5`";
                    break;
                case 6:
                    $qrySelect = "`kills` AS `m6`";
                    break;
                case 7:
                    $qry = "
                        SELECT COUNT(`id`) AS `m7` FROM `user_mission_carjacker` WHERE `userID`= :uid LIMIT 1
                    ";
                    break;
                case 8:
                    $qrySelect = "`m8c` AS `m8`";
                    break;
            }
            if(!isset($qry))
                $qry = "SELECT ".$qrySelect." FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1";
            
            $connection = new DBConfig();
            $row = $connection->getDataSR($qry, array(':uid' => $userID));
            
            if(isset($row['m' . $mission]) && $row['m' . $mission] >= 0)
                return $this->getMissionTierByMissionAndProgress($mission, $row['m' . $mission]);
        }
    }
}
