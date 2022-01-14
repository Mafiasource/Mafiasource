<?PHP

namespace src\Data;

use src\Business\SmuggleService;
use src\Data\config\DBConfig;
use src\Entities\User;
use src\Entities\Smuggle;
use src\Entities\SmuggleUnit;

//DB: 1=Drugs, 2=Liquids, 3=Fireworks, 4=Weapons, 5=Exotic Animals

class SmuggleDAO extends DBConfig
{
    protected $con = "";            
    private $dbh = "";
    private $lang = "en";
    private $allowedTypes = array(1, 2, 3, 4, 5); // Hardcoded
    
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
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(*) AS `total` FROM `smuggle` WHERE `deleted` = '0' AND `active` = '1' LIMIT 1");
            return $row['total'];
        }
    }
    
    //Vereenvoudig functie
    public static function gcd($num1, $num2)
    {
       while ($num2 != 0){
         $t = $num1 % $num2;
         $num1 = $num2;
         $num2 = $t;
       }
       return $num1;
    }
    
    public function getSmugglingPageInfo($type)
    {
        if(isset($_SESSION['UID']))
        {
            global $userData;
            
            $row = $this->con->getDataSR("
                SELECT `id`, `smugglingLv`, `smugglingXp`, `smugglingProfit`, `smugglingTrips`, `smugglingBusts`, `smugglingUnits`
                FROM `user`
                WHERE `id` = :uid AND `active`='1' AND `deleted`='0'
                LIMIT 1
            ", array(':uid' => $_SESSION['UID']));
            if($row['id'] > 0)
            {
                $userObj = new User();
                $userObj->setSmugglingLv($row['smugglingLv']);
                $userObj->setSmugglingXp(array('experience' => $row['smugglingXp'], 'class' => "bg-success"));
                $userObj->setSmugglingXpRaw($row['smugglingXp']);
                $userObj->setSmugglingProfit($row['smugglingProfit']);
                $userObj->setSmugglingTrips($row['smugglingTrips']);
                $userObj->setSmugglingBusts($row['smugglingBusts']);
                $userObj->setSmugglingUnits($row['smugglingUnits']);
                $sfRatio = self::gcd($row['smugglingTrips'],$row['smugglingBusts']);
                if($sfRatio != 0)
                    $userObj->setSmugglingSFRatio($row['smugglingTrips']/$sfRatio.':'.$row['smugglingBusts']/$sfRatio);
                else
                    $userObj->setSmugglingSFRatio($row['smugglingTrips'].':'.$row['smugglingBusts']);
                
                if(in_array($type, $this->allowedTypes))
                {
                    $rows2 = $this->con->getData("
                        SELECT `id`, `type`, `name_".$this->lang."` AS `name`, `description_".$this->lang."` AS `description`, `level`,
                        `picture`
                        FROM `smuggle`
                        WHERE `level` <= :smugglingLv AND `type`= :type  AND `active`='1' AND `deleted`='0'
                        ORDER BY `level` ASC
                    ", array(':smugglingLv' => $row['smugglingLv'], ':type' => $type));
                    
                    $carrying = $this->getUnitPossessions($type);
                }
                elseif($type == FALSE || (is_array($type) && array_key_exists('unitID',$type)))
                {
                    $rows2 = $this->con->getData("
                        SELECT `id`, `type`, `name_".$this->lang."` AS `name`
                        FROM `smuggle`
                        WHERE `level` <= :smugglingLv  AND `active`='1' AND `deleted`='0'
                        ORDER BY `level` ASC
                    ", array(':smugglingLv' => $row['smugglingLv']));
                    
                    $carrying = $this->getUnitPossessions(end($rows2)['type']);
                    if($type !== FALSE)
                    {
                        $row3 = $this->con->getDataSR("
                            SELECT `id`, `type`, `name_".$this->lang."` AS `name`
                            FROM `smuggle`
                            WHERE `id`= :id AND `level` <= :smugglingLv  AND `active`='1' AND `deleted`='0'
                            LIMIT 1
                        ", array(':smugglingLv' => $row['smugglingLv'], ':id' => $type['unitID']));
                        
                        $profitIndexUnitSelected = TRUE;
                    }
                }
                if(!isset($profitIndexUnitSelected))
                {
                    $smuggleList = array();
                    $cnt = count($rows2);
                    $i = 1;
                    global $smuggle;
                    $smuggleService = $smuggle;
                    foreach($rows2 AS $row2)
                    {
                        $s = new Smuggle();
                        $s->setId($row2['id']);
                        $s->setType($row2['type']);
                        $s->setName($row2['name']);
                        if(isset($row2['level'])) $s->setLevel($row2['level']);
                        if(isset($row2['description'])) $s->setDescription($row2['description']);
                        if(isset($row2['picture']))
                        {
                            if(file_exists(DOC_ROOT.'/web/public/images/smuggle/'.$row2['picture']))
                                $s->setPicture($row2['picture']);
                        }
                        $s->setActive(false);
                        if($i == $cnt) $s->setActive(true);
                        $inPossess = 0;
                        foreach($carrying AS $key => $val)
                        {
                            if($key == $smuggleService->unitNumbers[$s->getId()])
                                $inPossess += $val;
                        }
                        $unitInfo = new SmuggleUnit();
                        $unitInfo->setInPossession($inPossess);
                        $s->setUnitInfo($unitInfo);
                        array_push($smuggleList, $s);
                        $i++;
                    }
                    
                    $su = new SmuggleUnit();
                    $su->setInPossession(array_sum($carrying));
                    $capacity = (int)(($userData->getRankID() + '1' ) * 100) + ($userData->getSmugglingCapacity() * 100);
                    $su->setMaxCapacity($capacity - $su->getInPossession());
                    
                    return array('user' => $userObj, 'smuggle' => $smuggleList, 'unitsInfo' => $su);
                }
                elseif($profitIndexUnitSelected === TRUE)
                {
                    $smuggleList = array();
                    foreach($rows2 AS $row2)
                    {
                        $s = new Smuggle();
                        $s->setId($row2['id']);
                        $s->setType($row2['type']);
                        $s->setName($row2['name']);
                        if($row3['id'] == $row2['id']) $s->setActive(true);
                        array_push($smuggleList, $s);
                    }
                    return array('user' => $userObj, 'smuggle' => $smuggleList);
                }
            }
        }
    }
    
    public function getUnitPossessions($type)
    {
        if(isset($_SESSION['UID']))
        {
            $carrying = array();
            $possess = $this->con->getData("
                SELECT `unitNr`, `amount` FROM `smuggle_unit` WHERE `userID`= :uid AND `typeNr`= :type
            ", array(':uid' => $_SESSION['UID'], ':type' => $type));
            foreach($possess AS $poss) $carrying[$poss['unitNr']] = $poss['amount'];
            return $carrying;
        }
    }
    
    public function getSmugglingUnitById($id)
    {
        global $smuggle;
        $smuggleService = $smuggle;
        global $userData;
        $row = $this->con->getDataSR("
            SELECT `id`, `type`, `name_".$this->lang."` AS `name`, `level`
            FROM `smuggle`
            WHERE `id`= :id AND `active`='1' AND `deleted`='0'
            LIMIT 1
        ", array(':id' => $id));
        $data = array();
        if(count($row) > 0)
        {
            $s = new Smuggle();
            $s->setId($row['id']);
            $s->setType($row['type']);
            $s->setName($row['name']);
            $s->setLevel($row['level']);
            $s->setActive(true);
            $inPossess = 0;
            $carrying = $this->getUnitPossessions($s->getType());
            foreach($carrying AS $key => $val)
                if($key == $smuggleService->unitNumbers[$s->getId()])
                    $inPossess += $val;
            $unitInfo = new SmuggleUnit();
            $unitInfo->setInPossession($inPossess);
            $s->setUnitInfo($unitInfo);
            $data['smuggle'] = $s;
            
            $su = new SmuggleUnit();
            $su->setInPossession(array_sum($carrying));
            $capacity = (int)(($userData->getRankID() + '1' ) * 100) + ($userData->getSmugglingCapacity() * 100);
            $su->setMaxCapacity($capacity - $su->getInPossession());
            $data['unitsInfo'] = $su;
            
            return $data;
        }
        else
            return FALSE;
    }
    
    public function buyUnits($typeNr, $unitNr, $amount, $price)
    {
        if(isset($_SESSION['UID']))
        {
            $check = $this->con->getDataSR("
                SELECT `amount` FROM `smuggle_unit` WHERE `userID`= :uid AND `typeNr`= :type AND `unitNr`= :unit LIMIT 1
            ",array(':uid' => $_SESSION['UID'], ':type' => $typeNr, ':unit' => $unitNr));
            if($check)
            {
                $this->con->setData("
                    UPDATE `smuggle_unit` SET `amount`=`amount`+ :amnt WHERE `userID`= :uid AND `typeNr`= :type AND `unitNr`= :unit LIMIT 1
                ", array(':amnt' => $amount, ':uid' => $_SESSION['UID'], ':type' => $typeNr, ':unit' => $unitNr));
            }
            else
            {
                $this->con->setData("
                    INSERT INTO `smuggle_unit` (`userID`, `typeNr`, `unitNr`, `amount`) VALUES (:uid, :type, :unit, :amnt)
                ", array(':uid' => $_SESSION['UID'], ':type' => $typeNr, ':unit' => $unitNr, ':amnt' => $amount));
            }
            if($price > 0)
            {
                $this->con->setData("
                    UPDATE `user` SET `cash`=`cash`- :price, `smugglingProfit`=`smugglingProfit`- :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
                ", array(':price' => $price, ':uid' => $_SESSION['UID']));
            }
        }
    }
    
    public function sellUnits($typeNr, $unitNr, $amount, $price, $newLvlData = FALSE)
    {
        if(isset($_SESSION['UID']))
        {
            $check = $this->con->getDataSR("
                SELECT `amount` FROM `smuggle_unit` WHERE `userID`= :uid AND `typeNr`= :type AND `unitNr`= :unit LIMIT 1
            ",array(':uid' => $_SESSION['UID'], ':type' => $typeNr, ':unit' => $unitNr));
            if($check)
            {
                if(($check['amount'] - $amount) == 0)
                {
                    $this->con->setData("
                        DELETE FROM `smuggle_unit` WHERE `userID`= :uid AND `typeNr`= :type AND `unitNr`= :unit LIMIT 1
                    ", array(':uid' => $_SESSION['UID'], ':type' => $typeNr, ':unit' => $unitNr));
                }
                else
                {
                    $this->con->setData("
                        UPDATE `smuggle_unit` SET `amount`=`amount`- :amnt WHERE `userID`= :uid AND `typeNr`= :type AND `unitNr`= :unit LIMIT 1
                    ", array(':amnt' => $amount, ':uid' => $_SESSION['UID'], ':type' => $typeNr, ':unit' => $unitNr));
                }
            }
            if($newLvlData !== FALSE)
            {
                $this->con->setData("
                    UPDATE `user`
                    SET `cash`=`cash`+ :price, `smugglingLv`= :newLv, `smugglingXp`= :newXp, `smugglingTrips`=`smugglingTrips`+'1',
                        `smugglingUnits`=`smugglingUnits`+ :amnt, `smugglingProfit`=`smugglingProfit`+ :price, `rankpoints`=`rankpoints`+'0.5'
                    WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
                ", array(':price' => $price, ':newLv' => $newLvlData['levelAfter'], ':newXp' => $newLvlData['xpAfter'], ':amnt' => $amount, ':uid' => $_SESSION['UID']));
            }
            else
            {
                $this->con->setData("
                    UPDATE `user` SET `cash`=`cash`+ :price, `smugglingProfit`=`smugglingProfit`+ :price WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
                ", array(':price' => $price, ':uid' => $_SESSION['UID']));
            }
        }
    }
    
    public function getSmugglingUnitsInPossession()
    {
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData("SELECT `typeNr`, `amount` FROM `smuggle_unit` WHERE `userID`= :uid AND `amount`> 0", array(':uid' => $_SESSION['UID']));
            
            $list = array();
            foreach($rows AS $row)
            {
                $unitInfo = new SmuggleUnit();
                $unitInfo->setUserID($_SESSION['UID']);
                $unitInfo->setTypeNr($row['typeNr']);
                $unitInfo->setInPossession($row['amount']);
                
                array_push($list, $unitInfo);
            }
            return $list;
        }
    }
    
    public function removeAllSmugglingUnits()
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                DELETE FROM `smuggle_unit` WHERE `userID`= :uid;
                UPDATE `user` SET `smugglingBusts`=`smugglingBusts`+'1' WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $_SESSION['UID']));
        }
    }
}
