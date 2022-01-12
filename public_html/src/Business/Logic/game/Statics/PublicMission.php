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
    public $missionRewardDbFields = array(1 => "bank", "whoresStreet", "honorPoints", "score");
    // Additional rewards reward2* in public_mission table. 4 credits = small chance ratio,
    // 1 rankpoints handy for prestige ranks that bought all residences already and will need rp.
    public $additionalRewardDbFields = array(1 => "rankpoints", "score", "luckybox", "credits");
    
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
