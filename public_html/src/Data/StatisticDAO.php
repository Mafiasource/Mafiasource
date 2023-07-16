<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Business\StatisticService;
use src\Entities\Statistic\GameStatistic;
use src\Entities\Statistic\GeneralStatistic;
use src\Entities\Statistic\StatisticGroup;
use src\Entities\Statistic;
use src\Entities\User;
use src\Entities\Round;

class StatisticDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    private $dateFormat = "%d-%m-%y %H:%i:%s";
    private $phpDateFormat = "d-m-Y H:i:s";
    
    public $roundStartDate = "2023-05-12 22:30:00"; // Fill with current round start date
    
    public function __construct()
    {
        global $lang;
        global $connection;
        
        $this->con = $connection;                        
        $this->dbh = $connection->con;
        $this->lang = $lang;
        if($this->lang == 'en')
        {
            $this->dateFormat = "%m-%d-%y %r";
            $this->phpDateFormat = "m-d-Y g:i:s A";
        }
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT COUNT(*) AS `total` FROM `user` WHERE  `active`='1' AND `deleted`='0'");
            return $row['total'];
        }
    }
    
    public function getOutgameStatistics()
    {
        global $langs;
        
        $statistics = $this->con->getDataSR("
            SELECT `username` AS `bestPlayer`,
                (SELECT `username` FROM `user` WHERE `statusID`<'8' AND `active`='1' AND `deleted`='0' ORDER BY `registerDate` DESC LIMIT 1) AS `newestMember`,
                (
                    SELECT f.`name`
                    FROM `family` AS f
                    JOIN (SELECT SUM(`score`) AS `totalScore`, `familyID` FROM `user` GROUP BY `familyID`) AS u
                    ON(f.`id` = u.`familyID`)
                    WHERE f.`active`='1' AND f.`deleted`='0'
                    ORDER BY `totalScore` DESC
                    LIMIT 1
                ) AS `bestFam`,
                (SELECT `name` FROM `family` WHERE `active`='1' AND `deleted`='0' ORDER BY `money` DESC LIMIT 1) AS `richestFam`,
                (SELECT `username` FROM `user` WHERE `statusID`<'8' AND `active`='1' AND `deleted`='0' ORDER BY `kills` DESC LIMIT 1) AS `killerking`,
                (SELECT `username` FROM `user` WHERE `statusID`<'8' AND `active`='1' AND `deleted`='0' ORDER BY `honorPoints` DESC LIMIT 1) AS `honored`,
                (SELECT COUNT(`id`) AS `kills` FROM `user` WHERE `health`<='0' AND `statusID`<'8' AND `active`='1' AND `deleted`='0' LIMIT 1) AS `playersKilled`,
                (SELECT SUM(`smugglingUnits`) FROM `user` WHERE `statusID`<'8' AND `active`='1' AND `deleted`='0' LIMIT 1) AS `unitsSmuggled`,
                (SELECT SUM(`creditsWon`) AS `creditsWon` FROM `user` WHERE `statusID`<'8' AND `active`='1' AND `deleted`='0' LIMIT 1) AS `creditsWon`
            FROM `user`
            WHERE `statusID`<'8' AND `active`='1' AND `deleted`='0'
            ORDER BY `score` DESC
            LIMIT 1
        ");
        $bestPlayer = (isset($statistics['bestPlayer']) && !empty($statistics['bestPlayer'])) ? $statistics['bestPlayer'] : $langs['NONE'];
        $newestMember = (isset($statistics['newestMember']) && !empty($statistics['newestMember'])) ? $statistics['newestMember'] : $langs['NONE'];
        $bestFam = (isset($statistics['bestFam']) && !empty($statistics['bestFam'])) ? $statistics['bestFam'] : $langs['NONE'];
        $richestFam = (isset($statistics['richestFam']) && !empty($statistics['richestFam'])) ? $statistics['richestFam'] : $langs['NONE'];
        $killerking = (isset($statistics['killerking']) && !empty($statistics['killerking'])) ? $statistics['killerking'] : $langs['NONE'];
        $honored = (isset($statistics['honored']) && !empty($statistics['honored'])) ? $statistics['honored'] : $langs['NONE'];
        $playersKilled = (isset($statistics['playersKilled']) && !empty($statistics['playersKilled'])) ? $statistics['playersKilled'] : 0;
        $unitsSmuggled = (isset($statistics['unitsSmuggled']) && !empty($statistics['unitsSmuggled'])) ? $statistics['unitsSmuggled'] : 0;
        $creditsWon = (isset($statistics['creditsWon']) && !empty($statistics['creditsWon'])) ? $statistics['creditsWon'] : 0;
        
        return array(
            'bestPlayer' => $bestPlayer,
            'newestMember' => $newestMember,
            'bestFam' => $bestFam,
            'richestFam' => $richestFam,
            'killerking' => $killerking,
            'honored' => $honored,
            'playersKilled' => number_format($playersKilled, 0, '', ','),
            'unitsSmuggled' => StatisticService::eazyReadNumber($unitsSmuggled),
            'creditsWon' => StatisticService::eazyReadNumber($creditsWon)
        );
    }
    
    public function getStatisticsPage($round = "")
    {
        if($round !== "")
        {
            $row = $this->con->getDataSR("SELECT `hofJson` FROM `round` WHERE `round`= :rnd AND `active`='1' AND `deleted`='0' LIMIT 1", array(':rnd' => $round));
            if(isset($row['hofJson']))
                $hof = json_decode($row['hofJson']);
        }
        $statistic = new Statistic();
        if(isset($hof) && is_object($hof))
        {
            if(isset($hof->game)) $statistic->setGameStatistic($hof->game);
            if(isset($hof->richest)) $statistic->setRichestStatistic($hof->richest);
            if(isset($hof->mostHonored)) $statistic->setMostHonoredStatistic($hof->mostHonored);
            if(isset($hof->killerking)) $statistic->setKillerkingStatistic($hof->killerking);
            if(isset($hof->prisonBreaking)) $statistic->setPrisonBreakingStatistic($hof->prisonBreaking);
            if(isset($hof->carjacking)) $statistic->setCarjackingStatistic($hof->carjacking);
            if(isset($hof->crimes)) $statistic->setCrimesStatistic($hof->crimes);
            if(isset($hof->pimping)) $statistic->setPimpingStatistic($hof->pimping);
            if(isset($hof->smuggling)) $statistic->setSmugglingStatistic($hof->smuggling);
            if(isset($hof->referral)) $statistic->setReferralStatistic($hof->referral);
        }
        else
        {
            /* Game statistics */
            $gameStatsRow = $this->con->getDataSR("
                SELECT COUNT(`id`) AS `totalMembers`, SUM(`cash`) AS `totalCash`, SUM(`bank`) AS `totalBank`,
                    (SELECT COUNT(`id`) FROM `family` WHERE `active`='1' AND `deleted`='0') AS `totalFamilies`, SUM(`bullets`) AS `totalBullets`,
                    (SELECT COUNT(`id`) FROM `user` WHERE `health`<='0' AND `statusID`<='7' AND `active`='1' AND `deleted`='0') AS `totalDeathNow`,
                    (SELECT COUNT(`id`) FROM `user` WHERE `statusID`='8' AND `active`='1' AND `deleted`='0') AS `totalBanned`
                FROM `user`
                WHERE `active`='1' AND `deleted`='0'
            ");
            $gameStatistic = new GameStatistic();
            $gameStatistic->setTotalMembers($gameStatsRow['totalMembers']);
            $gameStatistic->setTotalCash($gameStatsRow['totalCash']);
            $gameStatistic->setTotalBank($gameStatsRow['totalBank']);
            $gameStatistic->setTotalMoney(round($gameStatsRow['totalCash'] + $gameStatsRow['totalBank']));
            $gameStatistic->setAverageMoney(round(($gameStatsRow['totalCash'] + $gameStatsRow['totalBank']) / $gameStatsRow['totalMembers']));
            $gameStatistic->setTotalFamilies($gameStatsRow['totalFamilies']);
            $gameStatistic->setTotalBullets($gameStatsRow['totalBullets']);
            $gameStatistic->setAverageBullets(round($gameStatsRow['totalBullets'] / $gameStatsRow['totalMembers']));
            $gameStatistic->setTotalDeathNow($gameStatsRow['totalDeathNow']);
            $gameStatistic->setTotalBanned($gameStatsRow['totalBanned']);
            $statistic->setGameStatistic($gameStatistic);
            
            /* Richest Statistics */
            $richestRows = $this->con->getData(
                "SELECT `username`, `cash`, `bank` FROM `user` WHERE `statusID`<='7' AND `health`>'0' AND `active`='1' AND `deleted`='0' ORDER BY round(`cash`+`bank`) DESC LIMIT 0,10"
            );
            $richestList = array();
            foreach($richestRows AS $r)
            {
                $richestStatistic = new GeneralStatistic();
                $richestStatistic->setKey($r['username']);
                $richestStatistic->setValue($r['cash']+$r['bank']);
                array_push($richestList, $richestStatistic);
            }
            $statistic->setRichestStatistic($richestList);
            
            /* Most Honored Member Statistics */
            $mostHonoredRows = $this->con->getData(
                "SELECT `username`, `honorPoints` FROM `user` WHERE `statusID`<='7' AND `health`>'0' AND `active`='1' AND `deleted`='0' ORDER BY `honorPoints` DESC LIMIT 0,10"
            );
            $mostHonoredList = array();
            foreach($mostHonoredRows AS $r)
            {
                $mostHonoredStatistic = new GeneralStatistic();
                $mostHonoredStatistic->setKey($r['username']);
                $mostHonoredStatistic->setValue($r['honorPoints']);
                array_push($mostHonoredList, $mostHonoredStatistic);
            }
            $statistic->setMostHonoredStatistic($mostHonoredList);
            
            /* Newest Member Statistics */
            $newestMemberRows = $this->con->getData(
                "SELECT `username`, DATE_FORMAT(`registerDate`, '".$this->dateFormat."' ) AS `regDate` FROM `user` WHERE `statusID`<='7' AND `health`>'0' AND `active`='1' AND `deleted`='0' ORDER BY `id` DESC LIMIT 0,10"
            );
            $newestMemberList = array();
            foreach($newestMemberRows AS $r)
            {
                $newestMemberStatistic = new GeneralStatistic();
                $newestMemberStatistic->setKey($r['username']);
                $newestMemberStatistic->setValue($r['regDate']);
                array_push($newestMemberList, $newestMemberStatistic);
            }
            $statistic->setNewestMemberStatistic($newestMemberList);
            
            /* Killerking Statistics */
            $killerkingRows = $this->con->getData(
                "SELECT `username`, `kills` FROM `user` WHERE `statusID`<='7' AND `health`>'0' AND `active`='1' AND `deleted`='0' ORDER BY `kills` DESC LIMIT 0,10"
            );
            $killerkingList = array();
            foreach($killerkingRows AS $r)
            {
                $killerkingStatistic = new GeneralStatistic();
                $killerkingStatistic->setKey($r['username']);
                $killerkingStatistic->setValue($r['kills']);
                array_push($killerkingList, $killerkingStatistic);
            }
            $statistic->setKillerkingStatistic($killerkingList);
            
            /* Prison Breaking Statistics */
            $prisonBustsRows = $this->con->getData(
                "SELECT `username`, `prisonBusts` FROM `user` WHERE `statusID`<='7' AND `health`>'0' AND `active`='1' AND `deleted`='0' ORDER BY `prisonBusts` DESC LIMIT 0,10"
            );
            $prisonBustsList = array();
            foreach($prisonBustsRows AS $r)
            {
                $prisonBustsStatistic = new GeneralStatistic();
                $prisonBustsStatistic->setKey($r['username']);
                $prisonBustsStatistic->setValue($r['prisonBusts']);
                array_push($prisonBustsList, $prisonBustsStatistic);
            }
            $statistic->setPrisonBreakingStatistic($prisonBustsList);
            
            /* Carjacking Statistics */
            $carjackingRows = $this->con->getData(
                "SELECT `username`, `vehiclesSuccess` FROM `user` WHERE `statusID`<='7' AND `health`>'0' AND `active`='1' AND `deleted`='0' ORDER BY `vehiclesSuccess` DESC LIMIT 0,10"
            );
            $carjackingList = array();
            foreach($carjackingRows AS $r)
            {
                $carjackingStatistic = new GeneralStatistic();
                $carjackingStatistic->setKey($r['username']);
                $carjackingStatistic->setValue($r['vehiclesSuccess']);
                array_push($carjackingList, $carjackingStatistic);
            }
            $statistic->setCarjackingStatistic($carjackingList);
            
            /* Crimes Statistics */
            $crimesRows = $this->con->getData(
                "SELECT `username`, `crimesSuccess` FROM `user` WHERE `statusID`<='7' AND `health`>'0' AND `active`='1' AND `deleted`='0' ORDER BY `crimesSuccess` DESC LIMIT 0,10"
            );
            $crimesList = array();
            foreach($crimesRows AS $r)
            {
                $crimesStatistic = new GeneralStatistic();
                $crimesStatistic->setKey($r['username']);
                $crimesStatistic->setValue($r['crimesSuccess']);
                array_push($crimesList, $crimesStatistic);
            }
            $statistic->setCrimesStatistic($crimesList);
            
            /* Pimping Statistics */
            $pimpingRows = $this->con->getData(
                "SELECT `username`, `pimpAmount` FROM `user` WHERE `statusID`<='7' AND `health`>'0' AND `active`='1' AND `deleted`='0' ORDER BY `pimpAmount` DESC LIMIT 0,10"
            );
            $pimpingList = array();
            foreach($pimpingRows AS $r)
            {
                $pimpingStatistic = new GeneralStatistic();
                $pimpingStatistic->setKey($r['username']);
                $pimpingStatistic->setValue($r['pimpAmount']);
                array_push($pimpingList, $pimpingStatistic);
            }
            $statistic->setPimpingStatistic($pimpingList);
            
            /* Smuggling Statistics */
            $smugglingRows = $this->con->getData(
                "SELECT `username`, `smugglingUnits` FROM `user` WHERE `statusID`<='7' AND `health`>'0' AND `active`='1' AND `deleted`='0' ORDER BY `smugglingUnits` DESC LIMIT 0,10"
            );
            $smugglingList = array();
            foreach($smugglingRows AS $r)
            {
                $smugglingStatistic = new GeneralStatistic();
                $smugglingStatistic->setKey($r['username']);
                $smugglingStatistic->setValue($r['smugglingUnits']);
                array_push($smugglingList, $smugglingStatistic);
            }
            $statistic->setSmugglingStatistic($smugglingList);
            
            /* Population Statistics */
            $states = $this->con->getData("SELECT `id`, `name` FROM `state` WHERE `id`>'0' AND `active`='1' AND `deleted`='0'");
            $populationStatisticsList = array();
            foreach($states AS $state)
            {
                $statisticGroup = new StatisticGroup($state['name']);
                $populationRows = $this->con->getData(
                    "SELECT c.`name` AS `city`, (SELECT COUNT(`id`) FROM `user` WHERE `stateID`='".$state['id']."' AND `cityID`=c.`id` AND `statusID`<='7' AND `health`>'0' AND `active`='1' AND `deleted`='0') AS `population` FROM `city` AS c WHERE c.`stateID`='".$state['id']."' ORDER BY `id` ASC LIMIT 0,3"
                );
                $populationList = array();
                foreach($populationRows AS $r)
                {
                    $populationStatistic = new GeneralStatistic();
                    $populationStatistic->setKey($r['city']);
                    $populationStatistic->setValue($r['population']);
                    array_push($populationList, $populationStatistic);
                }
                $statisticGroup->setStatistics($populationList);
                array_push($populationStatisticsList, $statisticGroup);
            }
            $statistic->setPopulationStatistic($populationStatisticsList);
            
            /* Referral profits Statistics */
            $referralRows = $this->con->getData(
                "SELECT `username`, `referralProfits` FROM `user` WHERE `statusID`<='7' AND `health`>'0' AND `active`='1' AND `deleted`='0' ORDER BY `referralProfits` DESC LIMIT 0,10"
            );
            $referralList = array();
            foreach($referralRows AS $r)
            {
                $referralStatistic = new GeneralStatistic();
                $referralStatistic->setKey($r['username']);
                $referralStatistic->setValue($r['referralProfits']);
                array_push($referralList, $referralStatistic);
            }
            $statistic->setReferralStatistic($referralList);
        }
        
        return $statistic;
    }
    
    private function getHallOfFameJsonByRound($round = 0)
    {
        $row = $this->con->getDataSR("
            SELECT `hofJson`, DATE_FORMAT(`startDate`, '".$this->dateFormat."' ) AS `sDate`, DATE_FORMAT(`endDate`, '".$this->dateFormat."' ) AS `eDate`
            FROM `round` WHERE `round`= :rnd AND `active`='1' AND `deleted`='0' LIMIT 1
        ", array(':rnd' => $round));
        $hof = isset($row['hofJson']) && isJson($row['hofJson']) ? json_decode($row['hofJson']) : null;
        if(isset($hof) && is_object($hof))
        {
            $hof->startDate = $row['sDate'];
            $hof->endDate = $row['eDate'];
            return $hof;
        }
        
        return false;
    }
    
    public function getHallOfFamePage($round = "")
    {
        $userDAO = new UserDAO();
        $familyData = new FamilyDAO();
        $hofMembers = $userDAO->getToplist(0, 10);
        $hofFamilies = $familyData->getFamlist(0, 5);
        $startDate = date($this->phpDateFormat, strtotime($this->roundStartDate));
        $endDate = false;
        
        if(is_int($round) || $round == 0)
        {
            $hof = $this->getHallOfFameJsonByRound($round);
            $hofMembers = isset($hof->members) ? $hof->members : false;
            $hofFamilies = isset($hof->families) ? $hof->families : false;
            $startDate = isset($hof->startDate) ? $hof->startDate : false;
            $endDate = isset($hof->endDate) ? $hof->endDate : false;
        }
            
        return array('members' => $hofMembers, 'families' => $hofFamilies, 'startDate' => $startDate, 'endDate' => $endDate);
    }
    
    public function getHallOfFameRounds()
    {
        // Fetch all previous rounds current one excluded.
        $rows = $this->con->getData("SELECT `id`, `round` FROM `round` WHERE `endDate`!='NULL' AND`active`='1' AND `deleted`='0' ORDER BY `position` ASC LIMIT 0, 30");
        $rounds = array();
        foreach($rows AS $row)
        {
            if(is_array($this->getHallOfFamePage($row['round'])))
            {
                $round = new Round();
                $round->setId($row['id']);
                $round->setRound($row['round']);
                $round->setRoundName($row['round']);
                if($this->lang == 'nl')
                    $round->setRoundName($row['round']);
                
                if($row['round'] == 0) // a round 0 gets viewed as "Beta"
                    $round->setRoundName("Beta");
                
                array_push($rounds, $round);
            }
        }
        return $rounds;
    }
}
