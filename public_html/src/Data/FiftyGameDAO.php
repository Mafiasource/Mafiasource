<?PHP

namespace src\Data;

use src\Data\config\DBConfig;
use src\Entities\FiftyGame;

class FiftyGameDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    private $dateFormat = "%d-%m-%Y %H:%i:%s";
    private $typesDb = array(0 => "cash", "whoresStreet", "honorPoints");
    
    public $types = array();
    
    public function __construct()
    {
        global $lang;
        global $langs;
        global $connection;
        
        $this->con = $connection;
        $this->dbh = $connection->con;
        $this->lang = $lang;
        if($this->lang == 'en') $this->dateFormat = "%m-%d-%Y %r";
        
        $this->types[0] = $langs['CASH'];
        $this->types[1] = $langs['WHORES'];
        $this->types[2] = $langs['HONOR_POINTS'];
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getRecordsCount()
    {
        
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `fifty_game` WHERE `deleted` = '0' AND `active` = '1'");
            $statement->execute();
            $row = $statement->fetch();
            if(isset($row['total'])) return $row['total'];
        }
    }
    
    public function getFiftyGamesByType($typeID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT fg.`id`, fg.`userID`, fg.`amount`, fg.`date`, u.`username`, DATE_FORMAT( fg.`date`, '".$this->dateFormat."' ) AS `putDate`
                FROM `fifty_game` AS fg
                LEFT JOIN `user` AS u
                ON (fg.userID = u.id)
                WHERE fg.`type`= :typeID
                ORDER BY fg.`date` DESC, fg.`id` DESC
            ");
            $statement->execute(array(':typeID' => $typeID));
            
            $list = array();
            while($row = $statement->fetch())
            {
                $game = new FiftyGame();
                $game->setId($row['id']);
                $game->setType($this->types[$typeID]);
                $game->setUserID($row['userID']);
                $game->setUsername($row['username']);
                $game->setAmount($row['amount']);
                $game->setDate($row['putDate']);
                array_push($list, $game);
            }
            return $list;
        }
        return false;
    }
    
    public function getFiftyGameById($id)
    {
        if(isset($_SESSION['UID']))
        {
            $g = $this->con->getDataSR("SELECT `type`, `userID`, `amount` FROM `fifty_game` WHERE `id`= :gid LIMIT 1", array(':gid' => $id));
            
            if(isset($g['userID']) && $g['userID'] >= 1 && $g['amount'] >= 10 && $g['amount'] <= 100000000)
            {
                $game = new FiftyGame();
                $game->setId($id);
                $game->setType($g['type']);
                $game->setUserID($g['userID']);
                $game->setAmount($g['amount']);
                
                return $game;
            }
        }
        return false;
    }
    
    public function finishPlayedFiftyGame($gameData, $winner, $loser)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("DELETE FROM `fifty_game` WHERE `id`= :gid;", array(':gid' => $gameData->getId()));
            if($winner == $_SESSION['UID'])
                $operator = "+";
            else
                $operator = "-";
            // This query covers challenger + or - it's stake
            $this->con->setData("
                UPDATE `user` SET `" . $this->typesDb[$gameData->getType()] . "`=`" . $this->typesDb[$gameData->getType()] . "`" . $operator . " :amnt WHERE `id`= :uid AND `active`='1' AND `deleted`='0';
            ", array(':amnt' => $gameData->getAmount(), ':uid' => $_SESSION['UID']));
            
            if($operator === "-" && $loser == $_SESSION['UID']) // Challenger lost against user, give user double the winnings (since starter of game staked amount)
                $this->con->setData("
                    UPDATE `user` SET `" . $this->typesDb[$gameData->getType()] . "`=`" . $this->typesDb[$gameData->getType()] . "`+ :amnt WHERE `id`= :wid AND `active`='1' AND `deleted`='0';
                ", array(':amnt' => round($gameData->getAmount()*2), ':wid' => $winner));
        }
    }
    
    public function userHasStartedGameOfType($type)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `id` FROM `fifty_game` WHERE `type`= :type AND `userID`= :uid", array(':type' => $type, ':uid' => $_SESSION['UID']));
            if(isset($row['id']) && $row['id'] > 0)
                return true;
        }
        return false;
    }
    
    public function createGame($type, $amount)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                INSERT INTO `fifty_game` (`type`, `userID`, `amount`, `date`) VALUES (:type, :uid, :amnt, NOW());
                UPDATE `user` SET `" . $this->typesDb[$type] . "`=`" . $this->typesDb[$type] . "`- :amnt WHERE `id`= :uid AND `active`='1' AND `deleted`='0'
            ", array(
                ':type' => $type, ':uid' => $_SESSION['UID'], ':amnt' => $amount
            ));
        }
    }
}
