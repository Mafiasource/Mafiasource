<?PHP

namespace src\Business\Logic\game\Statics;

use src\Business\Logic\game\Statics\Donator AS DonatorStatics;
use src\Data\config\DBConfig;

class Crimes
{
    public static function levelCalculations($userLv, $userXp)
    {
        $levelAfter = $userLv;
        if($userLv == 1){
            $xpGained = 100 / $userLv * 1.50;
        } elseif($userLv == 2){
            $xpGained = 100 / $userLv * 0.75;
        } else {
            $xpGained = 100 / $userLv * 1;
        }
        
        if(strtotime("2023-05-29 14:00:00") < strtotime('now') && strtotime("2023-06-05 14:00:00") > strtotime('now'))
            $xpGained *= 2;
        
        $newXp = $userXp + $xpGained;
        
        if($newXp >= 100 && $newXp < 200 && $userLv != 100)
        {
            $levelAfter++;
            $xpAfter = $newXp - 100;
        }
        elseif($newXp >= 200 && $newXp < 300 && $userLv != 100)
        {
            $levelAfter = $levelAfter + 2;
            $xpAfter = $newXp - 200;
        }
        elseif($newXp >= 300 && $newXp < 400 && $userLv != 100)
        {
            $levelAfter = $levelAfter + 3;
            $xpAfter = $newXp - 300;
        }
        elseif($newXp >= 400 && $newXp < 500 && $userLv != 100)
        {
            $levelAfter = $levelAfter + 4;
            $xpAfter = $newXp - 400;
        }
        elseif($newXp >= 500 && $newXp < 600 && $userLv != 100)
        {
            $levelAfter = $levelAfter + 5;
            $xpAfter = $newXp - 500;
        }
        elseif($userLv != 100)
        {
            $xpAfter = $userXp + $xpGained;
        }
        else
        {
            $xpAfter = 100;
        }
        if($levelAfter == 100) $xpAfter = 100;
        return array('levelAfter' => $levelAfter, 'xpAfter' => $xpAfter);
    }
    
    public function commitCrimeSuccess($uid, $money, $rp, $newLv, $newXp, $waitingTime = false, $hurt = false, $bullets = false) // false params used for organized crimes
    {
        if($waitingTime === false) $waitingTime = 90;
        $addSet = "";
        $addPar = array();
        if(is_numeric($bullets))
        {
            $addSet = ", `bullets`=`bullets`- :bullets";
            if(is_numeric($hurt)) $addSet .= ", `health`=`health`- :hurt";
            $addPar = array(':bullets' => $bullets);
            if(is_numeric($hurt)) $addPar = array_merge($addPar, array(':hurt' => $hurt));
        }
        $connection = new DBConfig();
        $donatorStatics = new DonatorStatics();
        $d = $connection->getDataSR("SELECT `donatorID`, `cHalvingTimes` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':uid' => $uid));
        $waitingTime = $donatorStatics->adjustWaitingTime($waitingTime, $d['donatorID'], $d['cHalvingTimes']);
        $connection->setData("
            UPDATE `user`
            SET `cash`=`cash` + :money, `crimesProfit`=`crimesProfit`+ :money, `crimesSuccess`=`crimesSuccess`+'1',
            `rankpoints`=`rankpoints`+ :rp, `crimesRankpoints`=`crimesRankpoints`+ :rp, `cCrimes` = :wait,
            `crimesLv`= :newLv, `crimesXp`= :newXp ".$addSet."
            WHERE `id`= :uid
        ", array_merge(array(':money' => $money, ':rp' => $rp, ':wait' => (time() + $waitingTime), ':newLv' => $newLv, ':newXp' => $newXp, ':uid' => $uid), $addPar));
    }
    
    public function commitCrimeFail($uid, $waitingTime = false, $hurt = false, $bullets = false) // false params used for organized crimes
    {
        if($waitingTime === false) $waitingTime = 90;
        $addSet = "";
        $addPar = array();
        if(is_numeric($bullets))
        {
            $addSet = ", `bullets`=`bullets`- :bullets";
            if(is_numeric($hurt)) $addSet .= ", `health`=`health`- :hurt";
            $addPar = array(':bullets' => $bullets);
            if(is_numeric($hurt)) $addPar = array_merge($addPar, array(':hurt' => $hurt));
        }
        $connection = new DBConfig();
        $donatorStatics = new DonatorStatics();
        $d = $connection->getDataSR("SELECT `donatorID`, `cHalvingTimes` FROM `user` WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':uid' => $uid));
        $waitingTime = $donatorStatics->adjustWaitingTime($waitingTime, $d['donatorID'], $d['cHalvingTimes']);
        $connection->setData("
            UPDATE `user`
            SET `crimesFail`=`crimesFail`+'1', `cCrimes` = :wait ".$addSet."
            WHERE `id`= :uid
        ", array_merge(array(':wait' => (time() + $waitingTime), ':uid' => $uid), $addPar));
    }
    
    public function removeOrganizedCrimeById($id)
    {
        $connection = new DBConfig();
        $connection->setData("
            DELETE FROM `crime_org_prep` WHERE `id`= :ocpid
        ", array(':ocpid' => $id));
    }
    
    public function sendNotification($userID, $notification, $params = "")
    {
        $connection = new DBConfig();
        $connection->setData("
            INSERT INTO `notification` (`userID`, `notification`, `params`, `date`) VALUES (:uid, :note, :params, NOW())
        ", array(':uid' => $userID, ':note' => $notification, ':params' => $params));
    }
}
