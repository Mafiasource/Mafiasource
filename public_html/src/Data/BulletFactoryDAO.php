<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Data\PossessionDAO;
use src\Entities\BulletFactory;
use src\Entities\Possession;
use src\Entities\Possess;

class BulletFactoryDAO extends DBConfig
{
    protected $con = "";            
    private $dbh = "";
    
    public function __construct()
    {
        global $connection;
        $this->con = $connection;                        
        $this->dbh = $connection->con;
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
            $row = $this->con->getDataSR("SELECT COUNT(*) AS `total` FROM `bullet_factory` WHERE `id` > 0");
            return $row['total'];
        }
    }
    
    public function getBulletFactories()
    {
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData("
                SELECT bf.`id`, bf.`possessID`, bf.`bullets`, bf.`priceEachBullet`, bf.`production`, s.`name` AS `state`, u.`username`
                FROM `bullet_factory` AS bf
                LEFT JOIN `possess` AS p
                ON (bf.`possessID`=p.`id`)
                LEFT JOIN `state` AS s
                ON (p.`stateID`=s.`id`)
                LEFT JOIN `user` AS u
                ON (p.`userID`=u.`id`)
                WHERE bf.`id` > 0 AND bf.`id` < 7
            ");
            
            $list = array();
            foreach($rows AS $row)
            {
                $bf = new BulletFactory();
                $bf->setId($row['id']);
                $bf->setPossessID($row['possessID']);
                $bf->setBullets($row['bullets']);
                $bf->setPriceEachBullet($row['priceEachBullet']);
                $bf->setProduction($row['production']);
                $bf->setState($row['state']);
                $bf->setOwner($row['username']);
                
                array_push($list, $bf);
            }
            return $list;
        }
    }
    
    public function buyBullets($bfInfo, $bullets)
    {
        if(isset($_SESSION['UID']))
        {
            $price = $bullets * $bfInfo->getPriceEachBullet();
            $profitOwner = round($price * 0.16, 0);
            $this->con->setData("
                UPDATE `bullet_factory` SET `bullets`=`bullets`- :bullets WHERE `id`= :bfid;
                UPDATE `user` SET `cash`=`cash`- :price, `bullets`=`bullets`+ :bullets WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':bullets' => $bullets, ':bfid' => $bfInfo->getId(), ':price' => $price, ':uid' => $_SESSION['UID']));
            
            /* Possession logic for buying bullets | pay owner if exists and not self */
            /* Bullet Factory & RLD will fetch owner without use of $possession->getPossessionByPossessId() since they're partially operated from a different table! */
            $owner = $this->con->getDataSR("SELECT `userID` FROM `possess` WHERE `id`= :pid AND `active`='1' AND `deleted`='0'", array(':pid' => $bfInfo->getPossessID()));
            if($owner['userID'] > 0 && $owner['userID'] != $_SESSION['UID'])
            {
                $pData = new Possession();
                $possess = new Possess();
                $possess->setId($bfInfo->getPossessID());
                $pData->setPossessDetails($possess);
                $possessionData = new PossessionDAO();
                $possessionData->applyProfitForOwner($pData, abs($profitOwner), $owner['userID']);
            }
        }
    }
}
