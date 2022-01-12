<?PHP
 
namespace src\Business;

use app\config\Routing;
use src\Business\Logic\game\Statics\DailyChallenge AS DailyChallengeStatics;
use src\Data\DailyChallengeDAO;
 
class DailyChallengeService extends DailyChallengeStatics
{
    private $data;
    
    public $challengeDescriptions = array(
        'nl' => array(1 =>
            "voertuigen stelen.",
            "drugs smokkelen.",
            "succesvolle spontane misdaden plegen.",
            "hoeren pimpen voor jezelf en/of anderen.",
            "drank smokkelen.",
            "kracht trainen bij de sportschool.",
            "geld vergokken in bezitbare casino's naar keuze.",
            "vuurwerk smokkelen.",
            "cardio trainen bij de sportschool.",
            /* "keer streetracen zonder te moeten winnen.", */
            11 => "wapens smokkelen.",
            "score verdienen met sportschool wedstrijden.",
            /* "score verdienen met streetracen.", */
            14 => "exotische dieren smokkelen."
        ),
        'en' => array(1 =>
            "steal a certain amount of vehicles.",
            "smuggle a certain amount of drugs.",
            "commit a certain amount of successful spontaneous crimes.",
            "pimp a certain amount of hoes for yourself and/or others.",
            "smuggle a certain amount of liquids.",
            "train a certain amount of power at the gym.",
            "spend a certain amount of cash at possessable casino's of your choice.",
            "smuggle a certain amount of fireworks.",
            "train a certain amount of cardio at the gym.",
            /* "race a certain amount of streetraces without having to win.", */
            11 => "smuggle a certain amount of weapons.",
            "achieve a certain amount of score with gym competitions.",
            /* "achieve a certain amount of score by streetracing.", */
            14 => "smuggle a certain amount of exotic animals."
        )
    );
    public $challengeRewards;
    
    public function __construct()
    {
        $this->data = new DailyChallengeDAO();
        
        global $lang;
        if($lang == 'nl')
            foreach($this->challengeDescriptions['nl'] AS $k => $v)
                $this->challengeDescriptions['nl'][$k] = "Om deze uitdaging te halen moet je een bepaald aantal " . $v;
        elseif($lang == 'en')
            foreach($this->challengeDescriptions['en'] AS $k => $v)
                $this->challengeDescriptions['en'][$k] = "To complete this challenge you need to " . $v;
        
        global $language;
        global $langs;
        $l = $language->dailyChallengesLangs();
        $this->challengeRewards = array(1 => $langs['CASH'], $langs['WHORES'], $langs['WEAPON'] . " " . $l['EXPERIENCE'], $langs['HONOR_POINTS'], "Score", $langs['LUCKY_BOXES']);
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function getDailyChallenges()
    {
        return $this->data->getDailyChallenges();
    }
    
    public function addToDailiesIfActive($challengeID, $count = 1, $userID = false)
    {
        global $userData;
        
        $response = $this->data->addToDailiesIfActive($challengeID, $count, $userID);
        
        if($userID === false)
            $userID = $userData->getId();
        
        if(is_object($response['prizePayout']))
        {
            // Send notification
            $notification = new NotificationService();
            $params = "challenge=".$response['prizePayout']->getChallengeName()."&prizeAmount=".number_format($response['prizePayout']->getRewardAmount(), 0, '', ',')."&prize=".$response['prizePayout']->getRewardType();
            $notification->sendNotification($userID, 'DAILY_CHALLENGE_COMPLETED', $params);
        }
        
        if(is_numeric($response['luckyPayout']))
        {
            // Send notification
            $notification = new NotificationService();
            $params = "luckies=".$response['luckyPayout'];
            $notification->sendNotification($userID, 'ALL_DAILY_CHALLENGES_COMPLETED', $params);
        }
    }
    
    public function getLuckyboxCombo()
    {
        return $this->data->getLuckyboxCombo();
    }
}
