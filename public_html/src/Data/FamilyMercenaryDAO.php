<?PHP

declare(strict_types=1);

namespace src\Data;

use src\Business\FamilyMercenaryService;
use src\Data\config\DBConfig;
use src\Entities\Family;
use src\Entities\FamilyMercenaryLog;

class FamilyMercenaryDAO extends DBConfig
{
    protected $con = null;
    private $lang = "en";
    private string $dateFormat = "%d-%m-%Y %H:%i:%s";
    
    private int $familyID;
    private string $mercsQuery = "
        SELECT COALESCE(SUM(`mercenaries`), 0) AS `total`,
        (SELECT `mercenariesUsed` FROM `family` WHERE `id`= :fid AND `active`='1' AND `deleted`='0') AS `used`
        FROM `family_mercenary_log` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0'
    ";
    
    public function __construct()
    {
        global $lang;
        global $userData;
        global $connection;
        
        $this->con = $connection;
        $this->familyID = (int)$userData->getFamilyID();
        $this->lang = $lang;
        if($this->lang == 'en') $this->dateFormat = "%m-%d-%Y %r";
        
    }
    
    public function __destruct()
    {
        $this->con = null;
    }
    
    public function getRecordsCount(): int
    {
        if(isset($_SESSION['UID']) && $this->familyID !== 0)
        {
            $row = $this->con->getDataSR("
                SELECT COUNT(*) AS `total` FROM `family_mercenary_log` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':fid' => $this->familyID));
            return (int)$row['total'];
        }
    }
    
    public function getMercenaries(): object
    {
        if(isset($_SESSION['UID']) && $this->familyID !== 0)
        {
            $row = $this->con->getDataSR($this->mercsQuery, array(':fid' => $this->familyID));
            
            if((isset($row['total']) && $row['total'] > 0) || $row['total'] == 0 && $row['used'] == 0)
            {
                $family = new Family();
                $family->setId($this->familyID);
                $family->setMercenariesTotal($row['total']);
                $family->setMercenariesUsed($row['used']);
                $family->setMercenariesAvailable($family->getMercenariesTotal() - $family->getMercenariesUsed());
                
                return $family;
            }
        }
    }
    
    public function getPageInfo(int $from, int $to): array
    {
        if(isset($_SESSION['UID']) && $this->familyID !== 0 && is_int($from) && is_int($to) && $to <= 50 && $to >=1)
        {
            $familyMercenaries = $this->getMercenaries();
            
            if(is_object($familyMercenaries))
            {
                $rows = $this->con->getData("
                    SELECT fml.`id`, fml.`userID`, u.`username`, fml.`mercenaries`, DATE_FORMAT( fml.`date`, '".$this->dateFormat."' ) AS `boughtDate`
                    FROM `family_mercenary_log` AS fml
                    LEFT JOIN `user` AS u
                    ON(fml.`userID`=u.`id`)
                    WHERE fml.`familyID`= :fid AND fml.`active`='1' AND fml.`deleted`='0'
                    ORDER BY fml.`date` DESC
                    LIMIT $from, $to
                ", array(':fid' => $this->familyID));
                
                $mercenaryLog = array();
                foreach($rows AS $row2)
                {
                    $familyMercenaryLog = new FamilyMercenaryLog();
                    $familyMercenaryLog->setId($row2['id']);
                    $familyMercenaryLog->setFamilyID($this->familyID);
                    $familyMercenaryLog->setUserID($row2['userID']);
                    $familyMercenaryLog->setUsername($row2['username']);
                    $familyMercenaryLog->setMercenaries($row2['mercenaries']);
                    $familyMercenaryLog->setDate($row2['boughtDate']);
                    
                    array_push($mercenaryLog, $familyMercenaryLog);
                }
                return array('family' => $familyMercenaries, 'mercenaryLog' => $mercenaryLog);
            }
        }
    }
    
    public function buyMercenaries(int $mercenaries, int $priceEa): void
    {
        if(isset($_SESSION['UID']) && $this->familyID !== 0)
        {
            $this->con->setData("
                INSERT INTO `family_mercenary_log` (`familyID`, `userID`, `mercenaries`, `date`) VALUES (:fid, :uid, :mercs, NOW());
                UPDATE `family` SET `money`=`money`- :price WHERE `id`= :fid AND `active`='1' AND `deleted`='0'
            ", array(
                ':fid' => $this->familyID, ':uid' => $_SESSION['UID'], ':mercs' => $mercenaries,
                ':price' => round($mercenaries * $priceEa)
            ));
        }
    }
}
