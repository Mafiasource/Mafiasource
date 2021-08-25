<?PHP

namespace src\Data;

use src\Business\CasinoService;
use src\Business\PossessionService;
use src\Data\config\DBConfig;
use src\Data\PossessionDAO;

class CasinoDAO extends DBConfig
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
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            $casinoService = new CasinoService();
            $pIds = implode(",", $casinoService->casinoPossessionIds);
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `possess` WHERE `pID` IN (".$pIds.") `deleted` = '0' AND `active` = '1'");
            $statement->execute();
            $row = $statement->fetch();
            return $row['total'];
        }
    }
    
    private function userWonCasinoGamePossessionApply($lossOwner, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            /** Possession logic casino owner's loss | substracr owner if exists and not self **/
            if(is_object($pData)) $casinoOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $casinoOwner > 0 && $casinoOwner != $_SESSION['UID'])
            {
                $possessionData = new PossessionDAO();
                $ownerBank = $this->con->getDataSR("SELECT `bank` FROM `user` WHERE `id`= :oid AND `active`='1' AND `deleted`='0'", array(':oid' => $casinoOwner))['bank'];
                if(($ownerBank - $lossOwner) >= 0)
                    $possessionData->applyLossForOwner($pData, abs($lossOwner), $casinoOwner);
                else
                {
                    // Gone broke
                    global $userService;
                    global $userData;
                    $possessionService = new PossessionService();
                    $statusData = $userService->getStatusPageInfo();
                    $reason = is_object($statusData) && $statusData->getIsProtected() ? 'status' : false;
                    $reason = is_object($pData) && $possessionService->userHasPossessionById($pData->getId()) ? 'self' : $reason;
                    $reason = is_object($pData) && $possessionService->familyHasAmountPossessionsById($pData->getId(), $userData->getFamilyID(), $possessionService->familyCityPossLimit) ? 'family' : $reason;
                    if(isset($reason) && $reason !== false)
                        $uid = 0;
                    else
                        $uid = $_SESSION['UID'];
                    
                    $possessionData->takeOverOwner($pData, $uid, $casinoOwner);
                    
                    if($uid === 0)
                        return array('took-over' => false, 'reason' => $reason);
                    elseif($reason === false)
                        return 'took-over';
                }
            }
        }
    }
    
    public function userLostCasinoGamePossessionApply($profitOwner, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            /** Possession logic for casino owner's gains | pay owner if exists and not self **/
            if(is_object($pData)) $casinoOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $casinoOwner > 0 && $casinoOwner != $_SESSION['UID'])
            {
                $possessionData = new PossessionDAO();
                $possessionData->applyProfitForOwner($pData, abs($profitOwner), $casinoOwner);
            }
        }
    }
    
    public function userPlayedCasinoGame($profits, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            if($profits > 0)
            {
                $this->con->setData("UPDATE `user` SET `cash`=`cash`+ :profits WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':profits' => abs($profits), ':uid' => $_SESSION['UID']));
                
                if($possApplyResponse = $this->userWonCasinoGamePossessionApply($profits, $pData))
                    return $possApplyResponse;
            }
            elseif($profits < 0)
            {
                $this->con->setData("UPDATE `user` SET `cash`=`cash`- :profits WHERE `id`= :uid AND `active`='1' AND `deleted`='0'", array(':profits' => abs($profits), ':uid' => $_SESSION['UID']));
                
                $this->userLostCasinoGamePossessionApply($profits, $pData);
            }
        }
        return true;
    }
}
