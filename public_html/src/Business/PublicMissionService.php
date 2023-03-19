<?PHP

declare(strict_types=1);

namespace src\Business;

use app\config\Routing;
use src\Business\Logic\game\Statics\PublicMission AS PublicMissionStatics;
use src\Data\PublicMissionDAO;
 
class PublicMissionService extends PublicMissionStatics
{
    private $data;
    
    public $missionDescriptions = array(
        'nl' => array(1 =>
            "voertuigen stelen.",
            "drugs smokkelen.",
            "succesvolle spontane misdaden plegen.",
            "drank smokkelen.",
            "hoeren pimpen voor jezelf.",
            "vuurwerk smokkelen.",
            "kracht trainen bij de sportschool.",
            "wapens smokkelen.",
            "score verdienen met sportschool wedstrijden.",
            "exotische dieren smokkelen.",
            /* "winst maken met dobbelen.", */
            12 => "gevangenen uitbreken.",
            /* "winst maken met racetrack.", */
            14 => "credits verzamelen met misdaden, voertuigene stelen, hoeren pimpen en smokkelen.",
            /* "winst maken met roulette.", */
            16 => "cardio trainen bij de sportschool.",
            /* "winst maken met slotmachine.", */
            18 => "hoeren pimpen voor anderen.",
            /* "winst maken met blackjack.", */
        ),
        'en' => array(1 =>
            "steal a certain amount of vehicles.",
            "smuggle a certain amount of drugs.",
            "commit a certain amount of successful spontaneous crimes.",
            "smuggle a certain amount of liquids.",
            "pimp a certain amount of hoes for yourself.",
            "smuggle a certain amount of fireworks.",
            "train a certain amount of power at the gym.",
            "smuggle a certain amount of weapons.",
            "achieve a certain amount of score with gym competitions.",
            "smuggle a certain amount of exotic animals.",
            /* "make profits with dobbling.", */
            12 => "break out a certain amount of prisoners.",
            /* "make profits with racetrack.", */
            14 => "collect a certain amount of credits with crimes, stealing vehicles, pimping hoes and smuggling.",
            /* "make profits with roulette.", */
            16 => "train a certain amount of cardio at the gym.",
            /* "make profits with slotmachine.", */
            18 =>  "pimp a certain amount of hoes for others.",
            /* "make profits with blackjack.", */
        )
    );
    
    public function __construct()
    {
        $this->data = new PublicMissionDAO();
        
        global $lang;
        if($lang == 'nl')
            foreach($this->missionDescriptions['nl'] AS $k => $v)
                $this->missionDescriptions['nl'][$k] = "Om deel te nemen aan deze publieke missie moet je een bepaald aantal " . $v;
        elseif($lang == 'en')
            foreach($this->missionDescriptions['en'] AS $k => $v)
                $this->missionDescriptions['en'][$k] = "To compete in this public mission you need to " . $v;
        
        global $language;
        global $langs;
        $this->missionRewards = array(1 => "Bank", $langs['WHORES'], $langs['HONOR_POINTS'], "Score");
        $this->additionalMissionRewards = array(1 => $langs['RANK_POINTS'], "Score", $langs['LUCKY_BOXES'], "Credits"); // Credits low chance ratio
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount(): int
    {
        return $this->data->getRecordsCount();
    }
    
    public function getPublicMission(): object
    {
        return $this->data->getPublicMission();
    }
    
    public function addToPublicMisionIfActive($missionID, $count = 1, $userID = false): void
    {
        $this->data->addToPublicMisionIfActive($missionID, $count, $userID);
    }
    
    public function getPublicMissionRanking(): array
    {
        return $this->data->getPublicMissionRanking();
    }
}
