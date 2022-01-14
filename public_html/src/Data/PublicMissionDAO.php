<?PHP

declare(strict_types=1);

namespace src\Data;

use src\Business\PublicMissionService;
use src\Data\config\DBConfig;
use src\Entities\PublicMission;
use src\Entities\Statistic\PublicMissionRank;

class PublicMissionDAO extends DBConfig
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
    
    public function getRecordsCount(): int
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(*) AS `total` FROM `public_mission` WHERE `id` > 0");
            if(isset($row['total'])) return $row['total'];
        }
    }
    
    public function getPublicMission($userID = false): object
    {
        $publicMission = new PublicMission();
        if(isset($_SESSION['UID']))
        {
            if($userID === false)
                $userID = $_SESSION['UID'];
            
            $publicMissionService = new PublicMissionService();
            $pm = $this->con->getDataSR("SELECT `id`, `missionID`, `minAmount`, `rewardType`, `rewardAmount`, `reward2Type`, `reward2Amount` FROM `public_mission` WHERE `id`>'0' ORDER BY `id` DESC LIMIT 1");
            
            $progress = $this->con->getDataSR("SELECT `publicMission` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':uid' => $userID));
            
            if(isset($pm['id']) && $pm['id'] > 0)
            {
                $publicMission->setId($pm['id']);
                $publicMission->setMissionID($pm['missionID']);
                $publicMission->setMissionName($publicMissionService->missions[$publicMission->getMissionID()]);
                $publicMission->setMissionDescription($publicMissionService->missionDescriptions[$this->lang][$publicMission->getMissionID()]);
                $publicMission->setMinAmount($pm['minAmount']);
                $publicMission->setRewardType($publicMissionService->missionRewards[$pm['rewardType']]);
                $publicMission->setRewardDb($publicMissionService->missionRewardDbFields[$pm['rewardType']]);
                $publicMission->setRewardAmount($pm['rewardAmount']);
                $publicMission->setReward2Type($publicMissionService->additionalMissionRewards[$pm['reward2Type']]);
                $publicMission->setReward2Db($publicMissionService->additionalRewardDbFields[$pm['reward2Type']]);
                $publicMission->setReward2Amount($pm['reward2Amount']);
                if(isset($progress['publicMission']))
                    $publicMission->setProgress($progress['publicMission']);
            }
        }
        return $publicMission;
    }
    
    public function addToPublicMisionIfActive($missionID, $count = 1, $userID = false): void
    {
        if(isset($_SESSION['UID']))
        {
            if($userID === false)
                $userID = $_SESSION['UID'];
            
            $pm = $this->getPublicMission($userID);
            if($pm->getMissionID() == $missionID)
                $this->con->setData("
                    UPDATE `user` SET `publicMission`=`publicMission`+ :c WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
                ", array(':c' => $count, ':uid' => $userID));
        }
    }
    
    public function getPublicMissionRanking()
    {
        global $langs;
        $publicMissionService = new PublicMissionService();
        $publicMission = $this->getPublicMission();
        $rows = $this->con->getData("SELECT `username`, `publicMission` FROM `user` WHERE `statusID`<='7' AND `health`>'0' AND `active`='1' AND `deleted`='0' ORDER BY `publicMission` DESC, `id` DESC LIMIT 9");
        
        $list = array();
        $i = 1;
        foreach($rows AS $row)
        {
            $missionRank = new PublicMissionRank();
            $missionRank->setPosition($i);
            $missionRank->setUsername($row['username']);
            $missionRank->setAmount($row['publicMission']);
            if($missionRank->getAmount() == 0)
                $missionRank->setUsername($langs['NONE']);
            
            $prizes = array('rewardAmount' => $publicMission->getRewardAmount(), 'reward2Amount' => $publicMission->getReward2Amount());
            $prizes = $publicMissionService->getPrizesByRank($prizes, (int)$i);
            $missionRank->setReward($prizes['rewardAmount']);
            $missionRank->setAdditionalReward($prizes['reward2Amount']);
            
            array_push($list, $missionRank);
            $i++;
        }
        return $list;
    }
}
