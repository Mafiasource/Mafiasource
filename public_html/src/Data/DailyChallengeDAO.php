<?PHP

namespace src\Data;

use src\Business\DailyChallengeService;
use src\Data\config\DBConfig;
use src\Entities\DailyChallenge;

class DailyChallengeDAO extends DBConfig
{
    protected $con = "";            
    private $dbh = "";
    private $lang = "en";
    
    public function __construct()
    {
        global $lang;
        global $connection;
        
        $this->con = $connection;                        
        $this->dbh = $connection->con;
        $this->lang = $lang;
    }
    
    public function __destruct()
    {
        $this->dbh = null;
        $this->con = null;
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(*) AS `total` FROM `daily_challenge` WHERE `id` > 0");
            if(isset($row['total'])) return $row['total'];
        }
    }
    
    public function getDailyChallenges($userID = false)
    {
        if(isset($_SESSION['UID']))
        {
            if($userID === false)
                $userID = $_SESSION['UID'];
            
            $dailyChallengeService = new DailyChallengeService();
            $dailyChallenges = $this->con->getData("SELECT `id`, `challengeID`, `amount`, `rewardType`, `rewardAmount` FROM `daily_challenge` WHERE `id`> 0 ORDER BY `id` ASC");
            
            $progress = $this->con->getDataSR("SELECT `daily1Amount`, `daily2Amount`, `daily3Amount` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':uid' => $userID));
            $list = array();
            foreach($dailyChallenges AS$k => $dc)
            {
                if($k >= 0 && $k < 3)
                {
                    $dailyChallenge = new DailyChallenge();
                    $dailyChallenge->setId($dc['id']);
                    $dailyChallenge->setChallengeID($dc['challengeID']);
                    $dailyChallenge->setChallengeName($dailyChallengeService->challenges[$dailyChallenge->getChallengeID()]);
                    $dailyChallenge->setChallengeDescription($dailyChallengeService->challengeDescriptions[$this->lang][$dailyChallenge->getChallengeID()]);
                    $dailyChallenge->setAmount($dc['amount']);
                    $dailyChallenge->setRewardType($dailyChallengeService->challengeRewards[$dc['rewardType']]);
                    $dailyChallenge->setRewardDb($dailyChallengeService->challengeRewardDbFields[$dc['rewardType']]);
                    $dailyChallenge->setRewardAmount($dc['rewardAmount']);
                    if(isset($progress['daily' . ($k + 1) . 'Amount'])) $dailyChallenge->setProgress($progress['daily' . ($k + 1) . 'Amount']);
                    
                    array_push($list, $dailyChallenge);
                }
            }
            return $list;
        }
    }
    
    public function addToDailiesIfActive($challengeID, $count = 1, $userID = false)
    {
        if(isset($_SESSION['UID']))
        {
            if($userID === false)
                $userID = $_SESSION['UID'];
            
            $completed = $completedNow = array(1 => false, false, false);
            $dailies = $this->getDailyChallenges($userID);
            foreach($dailies AS $k => $d)
            {
                $c = $count;
                if($d->getChallengeID() == $challengeID)
                    $dbFieldNo = $k + 1;
                else
                    $c = 0;
                
                if($d->getProgress() + $c >= $d->getAmount())
                {
                    $completed[$k + 1] = true;
                    if($d->getProgress() < $d->getAmount())
                    {
                        $completedNow[$k + 1] = $d;
                    }
                }
            }
            
            $prizePayout = $luckyPayout = false;
            if(isset($dbFieldNo) && is_numeric($dbFieldNo) && $dbFieldNo > 0 && $dbFieldNo < 4)
            {
                if(is_object($completedNow[$dbFieldNo]))
                {
                    // Pay out prize
                    $prizePayout = $completedNow[$dbFieldNo];
                    
                    if($prizePayout->getRewardDb() == "luckybox")
                    {
                        $dailyStreak = $this->con->getDataSR("SELECT `donatorID`, `luckybox` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':uid' => $userID));
                        if(isset($dailyStreak['luckybox']))
                        {
                            if($dailyStreak['luckybox'] + $prizePayout->getRewardAmount() > 15 && $dailyStreak['donatorID'] == 10)
                                $upToLuckies = 15 - $dailyStreak['luckybox'];
                            elseif($dailyStreak['luckybox'] + $prizePayout->getRewardAmount() > 10 && $dailyStreak['donatorID'] == 5)
                                $upToLuckies = 10 - $dailyStreak['luckybox'];
                            elseif($dailyStreak['luckybox'] + $prizePayout->getRewardAmount() > 7 && $dailyStreak['donatorID'] == 1)
                                $upToLuckies = 7 - $dailyStreak['luckybox'];
                            elseif($dailyStreak['luckybox'] + $prizePayout->getRewardAmount() > 5 && $dailyStreak['donatorID'] == 0)
                                $upToLuckies = 5 - $dailyStreak['luckybox'];
                            else
                                $upToLuckies = $prizePayout->getRewardAmount();
                            
                            $prizeLuckyPayout = $upToLuckies > 0 ? $upToLuckies : 0;
                            $prizePayout->setRewardAmount($prizeLuckyPayout);
                            $this->con->setData("
                                UPDATE `user` SET `".$prizePayout->getRewardDb()."`=`".$prizePayout->getRewardDb()."`+ :amnt WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                            ", array(':amnt' => $prizePayout->getRewardAmount(), ':uid' => $userID));
                        }
                    }
                    if($prizePayout->getRewardDb() == "weaponExperience")
                    {
                        $wpe = $this->con->getDataSR("SELECT `weaponExperience` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':uid' => $userID));
                        if(isset($wpe['weaponExperience']))
                        {
                            if($wpe['weaponExperience'] + $prizePayout->getRewardAmount() > 100)
                                $upToWpe = 100 - $wpe['weaponExperience'];
                            else
                                $upToWpe = $prizePayout->getRewardAmount();
                            
                            $prizePayout->setRewardAmount($upToWpe);
                            $this->con->setData("
                                UPDATE `user` SET `".$prizePayout->getRewardDb()."`=`".$prizePayout->getRewardDb()."`+ :amnt WHERE `id`= :uid AND `weaponExperience`<'100' AND `active`='1' AND `deleted`='0'
                            ", array(':amnt' => $prizePayout->getRewardAmount(), ':uid' => $userID));
                        }
                    }
                    if($prizePayout->getRewardDb() != "luckybox" && $prizePayout->getRewardDb() != "weaponExperience")
                    {
                        $this->con->setData("
                            UPDATE `user` SET `".$prizePayout->getRewardDb()."`=`".$prizePayout->getRewardDb()."`+ :amnt WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                        ", array(':amnt' => $prizePayout->getRewardAmount(), ':uid' => $userID));
                    }
                    
                    if(!in_array(false, $completed))
                    {
                        // Pay out Luckyboxes
                        $dailyStreak = $this->con->getDataSR("SELECT `donatorID`, `luckybox`, `dailyCompletedDays` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':uid' => $userID));
                        if(isset($dailyStreak['dailyCompletedDays']))
                        {
                            $ds = $dailyStreak['dailyCompletedDays'];
                            if($ds > 4)
                                $luckies = 5;
                            elseif($ds === 0)
                                $luckies = 1;
                            else
                                $luckies = $ds;
                            
                            if($dailyStreak['luckybox'] + $luckies > 15 && $dailyStreak['donatorID'] == 10)
                                $upToLuckies = 15 - $dailyStreak['luckybox'];
                            elseif($dailyStreak['luckybox'] + $luckies > 10 && $dailyStreak['donatorID'] == 5)
                                $upToLuckies = 10 - $dailyStreak['luckybox'];
                            elseif($dailyStreak['luckybox'] + $luckies > 7 && $dailyStreak['donatorID'] == 1)
                                $upToLuckies = 7 - $dailyStreak['luckybox'];
                            elseif($dailyStreak['luckybox'] + $luckies > 5 && $dailyStreak['donatorID'] == 0)
                                $upToLuckies = 5 - $dailyStreak['luckybox'];
                            else
                                $upToLuckies = $luckies;
                            
                            $luckyPayout = $upToLuckies > 0 ? $upToLuckies : 0;
                            $this->con->setData("
                                UPDATE `user` SET `luckybox`=`luckybox`+ :l, `dailyCompletedDays`=`dailyCompletedDays`+'1' WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                            ", array(':l' => $upToLuckies, ':uid' => $userID));
                        }
                    }
                }
                $this->con->setData("
                    UPDATE `user` SET `daily" . $dbFieldNo . "Amount`=`daily" . $dbFieldNo . "Amount`+ :c WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(':c' => $count, ':uid' => $userID));
            }
            return array('prizePayout' => $prizePayout, 'luckyPayout' => $luckyPayout);
        }
    }
    
    public function getLuckyboxCombo()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT IF(`dailyCompletedDays` < 5, `dailyCompletedDays`, 5) AS `dailyCompletedDays` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':uid' => $_SESSION['UID']));
            if(isset($row['dailyCompletedDays']) && $row['dailyCompletedDays'] > 0)
                return $row['dailyCompletedDays'];
        }
    }
}
