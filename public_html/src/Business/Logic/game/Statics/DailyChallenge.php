<?PHP

namespace src\Business\Logic\game\Statics;

use src\Data\config\DBConfig;

class DailyChallenge
{
    public $challenges = array(1 => "Carjacker", "Drug Dealer", "Criminal Mind", "Pimpmaster", "Liquids Mule", "Body Builder", "High Roller", "Firework Carrier", "Stamina Master", /** "Illegal Racer", **/ 11 => "Gun Runner", "Competetor", /** "Street Dominator", **/ 14 => "Animal Pirate");
    public $challengeRewardDbFields = array(1 => "cash", "whoresStreet", "weaponExperience", "honorPoints", "score", "luckybox");
}
