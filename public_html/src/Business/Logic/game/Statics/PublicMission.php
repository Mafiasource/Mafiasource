<?PHP

namespace src\Business\Logic\game\Statics;

use src\Data\config\DBConfig;

class PublicMission
{
    public $missions = array(
        1 => "Gone in 60 Seconds", "Drug Lord", "Crime Time", "Liquid Expert", "Pimp", "Firework Sales", "Power Trainer", "Handling Guns", "Beat the Gym",
        "Animal Smuggler", /* "Dice Gambler", */ 12 => "Prison Breaker", /* "Horse Better", **/ 14 => "Credit Scavenger", /* "Roll the Ball", */
        16 => "Stamina Striver", /* "Fruit Spinner", */ 18 => "Hoes for Bro's", /* "Card Counter", */
    );
    public $missionRewards = array(); // Init
    public $missionRewardDbFields = array(1 => "bank", "whoresStreet", "honorPoints", "score"); // Big Score = rare
    public $additionalMissionRewards = array(); // Init
    public $additionalRewardDbFields = array(1 => "rankpoints", "score", "luckybox", "credits"); // Credits = rare
    
    public function getPrizesByRank($prizes, $rank)
    {
        if(is_int($rank) && $rank >= 1 && $rank <= 9)
        {
            $prizes['rewardAmount'] /= $rank;
            $prizes['reward2Amount'] /= $rank;
            $prizes['rewardAmount'] = round($prizes['rewardAmount']);
            $prizes['reward2Amount'] = round($prizes['reward2Amount']);
            
            return $prizes;
        }
    }
}
