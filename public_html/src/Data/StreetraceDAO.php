<?PHP

namespace src\Data;

use PDOException;
use src\Business\GarageService;
use src\Data\config\DBConfig;
use src\Entities\Streetrace;
use src\Entities\StreetraceParticipant;

class StreetraceDAO extends DBConfig
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

    public function getUserVehicle($garageId, $stateId)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT g.`id`, v.`name` AS `vehicleName`, v.`horsepower`, v.`topspeed`, v.`acceleration`, v.`control`, v.`breaking`, g.`tires`, g.`engine`, g.`exhaust`, g.`shockAbsorbers` FROM `garage` AS g LEFT JOIN `user_garage` AS ug ON (g.`userGarageID` = ug.`id`) LEFT JOIN `vehicle` AS v ON (g.`vehicleID` = v.`id`) WHERE g.`id` = :gid AND ug.`userID` = :uid AND ug.`stateID` = :stateID AND g.`active`='1' AND g.`deleted`='0' AND v.`active`='1' AND v.`deleted`='0'");
            $statement->execute(array(':gid' => $garageId, ':uid' => $_SESSION['UID'], ':stateID' => $stateId));
            $row = $statement->fetch();
            if(isset($row['id']))
            {
                $garageService = new GarageService();
                foreach(array_keys($garageService->tuneShop) AS $tune)
                {
                    $field = GarageService::getTuneDbField($tune);
                    $row['horsepower'] += $garageService->tuneShop[$tune][$row[$field]]['pk'];
                    $row['topspeed'] += $garageService->tuneShop[$tune][$row[$field]]['ts'];
                    $row['acceleration'] += $garageService->tuneShop[$tune][$row[$field]]['ac'];
                    $row['control'] += $garageService->tuneShop[$tune][$row[$field]]['ct'];
                    $row['breaking'] += $garageService->tuneShop[$tune][$row[$field]]['br'];
                }
                return $row;
            }
        }
        return false;
    }

    public function userHasOpenRace()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR(
                "SELECT g.`id` FROM `streetrace_game` AS g LEFT JOIN `streetrace_participant` AS p ON (p.`gameID` = g.`id`) WHERE p.`userID` = :uid AND g.`status`='open' LIMIT 1",
                array(':uid' => $_SESSION['UID'])
            );
            if(isset($row['id']))
                return $row['id'];
        }
        return false;
    }

    public function createRace($type, $stake, $requiredPlayers, array $vehicle, $stateId)
    {
        if(!isset($_SESSION['UID']))
            return false;

        try
        {
            $this->dbh->beginTransaction();

            $statement = $this->dbh->prepare(
                "INSERT INTO `streetrace_game` (`organizerID`, `stateID`, `type`, `stake`, `requiredPlayers`, `status`, `created`) VALUES (:uid, :stateID, :type, :stake, :requiredPlayers, 'open', NOW())"
            );
            $statement->execute(array(
                ':uid' => $_SESSION['UID'],
                ':stateID' => $stateId,
                ':type' => $type,
                ':stake' => $stake,
                ':requiredPlayers' => $requiredPlayers
            ));
            $raceId = (int)$this->dbh->lastInsertId();

            $this->insertParticipant($raceId, $vehicle, $stake);

            $this->dbh->commit();

            return $raceId;
        }
        catch(PDOException $e)
        {
            $this->dbh->rollBack();
            throw $e;
        }
    }

    public function addParticipant($raceId, array $vehicle, $stake)
    {
        if(!isset($_SESSION['UID']))
            return false;

        try
        {
            $this->dbh->beginTransaction();

            $this->insertParticipant($raceId, $vehicle, $stake);

            $this->dbh->commit();
            return true;
        }
        catch(PDOException $e)
        {
            $this->dbh->rollBack();
            throw $e;
        }
    }

    private function insertParticipant($raceId, array $vehicle, $stake)
    {
        $statement = $this->dbh->prepare(
            "INSERT INTO `streetrace_participant` (`gameID`, `userID`, `vehicleGarageID`, `vehicleName`, `horsepower`, `topspeed`, `acceleration`, `control`, `breaking`, `joined`) VALUES (:raceID, :uid, :vehicleID, :vehicleName, :horsepower, :topspeed, :acceleration, :control, :breaking, NOW())"
        );
        $statement->execute(array(
            ':raceID' => $raceId,
            ':uid' => $_SESSION['UID'],
            ':vehicleID' => $vehicle['id'],
            ':vehicleName' => $vehicle['vehicleName'],
            ':horsepower' => $vehicle['horsepower'],
            ':topspeed' => $vehicle['topspeed'],
            ':acceleration' => $vehicle['acceleration'],
            ':control' => $vehicle['control'],
            ':breaking' => $vehicle['breaking']
        ));

        $update = $this->dbh->prepare("UPDATE `user` SET `cash` = `cash` - :stake WHERE `id` = :uid LIMIT 1");
        $update->execute(array(':stake' => $stake, ':uid' => $_SESSION['UID']));
    }

    public function removeParticipant($raceId, $stake)
    {
        if(!isset($_SESSION['UID']))
            return false;

        try
        {
            $this->dbh->beginTransaction();

            $delete = $this->dbh->prepare("DELETE FROM `streetrace_participant` WHERE `gameID` = :raceID AND `userID` = :uid LIMIT 1");
            $delete->execute(array(':raceID' => $raceId, ':uid' => $_SESSION['UID']));

            $refund = $this->dbh->prepare("UPDATE `user` SET `cash` = `cash` + :stake WHERE `id` = :uid LIMIT 1");
            $refund->execute(array(':stake' => $stake, ':uid' => $_SESSION['UID']));

            $this->dbh->commit();
            return true;
        }
        catch(PDOException $e)
        {
            $this->dbh->rollBack();
            throw $e;
        }
    }

    public function cancelRaceIfEmpty($raceId)
    {
        $count = $this->countParticipants($raceId);
        if($count === 0)
        {
            $statement = $this->dbh->prepare("UPDATE `streetrace_game` SET `status`='cancelled', `finished`=NOW() WHERE `id` = :raceID LIMIT 1");
            $statement->execute(array(':raceID' => $raceId));
        }
    }

    public function getOpenRaces($stateId)
    {
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData(
                "SELECT g.`id`, g.`type`, g.`stake`, g.`requiredPlayers`, g.`created`, g.`organizerID`, g.`stateID`, u.`username` AS `organizerName`, s.`name` AS `stateName`, (SELECT COUNT(*) FROM `streetrace_participant` AS sp WHERE sp.`gameID` = g.`id`) AS `participants` FROM `streetrace_game` AS g LEFT JOIN `user` AS u ON (g.`organizerID` = u.`id`) LEFT JOIN `state` AS s ON (g.`stateID` = s.`id`) WHERE g.`status`='open' AND g.`stateID` = :stateID ORDER BY g.`created` ASC",
                array(':stateID' => $stateId)
            );
            $list = array();
            foreach($rows AS $row)
            {
                $race = new Streetrace();
                $race->setId($row['id']);
                $race->setType($row['type']);
                $race->setStake($row['stake']);
                $race->setRequiredPlayers($row['requiredPlayers']);
                $race->setCreated($row['created']);
                $race->setOrganizerID($row['organizerID']);
                $race->setOrganizerName($row['organizerName']);
                $race->setStateID($row['stateID']);
                $race->setStateName($row['stateName']);
                $race->setParticipantCount((int)$row['participants']);
                $list[] = $race;
            }
            return $list;
        }
        return array();
    }

    public function getUsersOpenRace()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR(
                "SELECT g.`id`, g.`type`, g.`stake`, g.`requiredPlayers`, g.`created`, g.`organizerID`, g.`stateID`, u.`username` AS `organizerName`, s.`name` AS `stateName` FROM `streetrace_game` AS g LEFT JOIN `streetrace_participant` AS sp ON (sp.`gameID` = g.`id`) LEFT JOIN `user` AS u ON (g.`organizerID` = u.`id`) LEFT JOIN `state` AS s ON (g.`stateID` = s.`id`) WHERE sp.`userID` = :uid AND g.`status`='open' LIMIT 1",
                array(':uid' => $_SESSION['UID'])
            );

            if(isset($row['id']))
            {
                $race = new Streetrace();
                $race->setId($row['id']);
                $race->setType($row['type']);
                $race->setStake($row['stake']);
                $race->setRequiredPlayers($row['requiredPlayers']);
                $race->setCreated($row['created']);
                $race->setOrganizerID($row['organizerID']);
                $race->setOrganizerName($row['organizerName']);
                $race->setStateID($row['stateID']);
                $race->setStateName($row['stateName']);
                $participants = $this->getRaceParticipants($row['id']);
                $race->setParticipants($participants);
                $race->setParticipantCount(count($participants));
                return $race;
            }
        }
        return false;
    }

    public function getRaceById($raceId)
    {
        $row = $this->con->getDataSR(
            "SELECT g.`id`, g.`type`, g.`stake`, g.`requiredPlayers`, g.`status`, g.`created`, g.`started`, g.`finished`, g.`organizerID`, g.`stateID`, u.`username` AS `organizerName`, s.`name` AS `stateName` FROM `streetrace_game` AS g LEFT JOIN `user` AS u ON (g.`organizerID` = u.`id`) LEFT JOIN `state` AS s ON (g.`stateID` = s.`id`) WHERE g.`id` = :raceID",
            array(':raceID' => $raceId)
        );

        if(isset($row['id']))
        {
            $race = new Streetrace();
            $race->setId($row['id']);
            $race->setType($row['type']);
            $race->setStake($row['stake']);
            $race->setRequiredPlayers($row['requiredPlayers']);
            $race->setStatus($row['status']);
            $race->setCreated($row['created']);
            $race->setStarted($row['started']);
            $race->setFinished($row['finished']);
            $race->setOrganizerID($row['organizerID']);
            $race->setOrganizerName($row['organizerName']);
            $race->setStateID($row['stateID']);
            $race->setStateName($row['stateName']);
            $participants = $this->getRaceParticipants($row['id'], true);
            $race->setParticipants($participants);
            $race->setParticipantCount(count($participants));
            return $race;
        }
        return false;
    }

    public function getRaceParticipants($raceId, $includeResults = false)
    {
        $order = $includeResults ? "ORDER BY sp.`position` ASC, sp.`joined` ASC" : "ORDER BY sp.`joined` ASC";
        $statement = $this->dbh->prepare(
            "SELECT sp.`id`, sp.`userID`, u.`username`, sp.`vehicleName`, sp.`vehicleGarageID`, sp.`horsepower`, sp.`topspeed`, sp.`acceleration`, sp.`control`, sp.`breaking`, sp.`score`, sp.`position`, sp.`prize`, sp.`joined` FROM `streetrace_participant` AS sp LEFT JOIN `user` AS u ON (sp.`userID` = u.`id`) WHERE sp.`gameID` = :raceID " . $order
        );
        $statement->execute(array(':raceID' => $raceId));

        $participants = array();
        foreach($statement AS $row)
        {
            $participant = new StreetraceParticipant();
            $participant->setId($row['id']);
            $participant->setGameID($raceId);
            $participant->setUserID($row['userID']);
            $participant->setUsername($row['username']);
            $participant->setVehicleGarageID($row['vehicleGarageID']);
            $participant->setVehicleName($row['vehicleName']);
            $participant->setHorsepower($row['horsepower']);
            $participant->setTopspeed($row['topspeed']);
            $participant->setAcceleration($row['acceleration']);
            $participant->setControl($row['control']);
            $participant->setBreaking($row['breaking']);
            $participant->setScore($row['score']);
            $participant->setPosition($row['position']);
            $participant->setPrize($row['prize']);
            $participant->setJoined($row['joined']);
            $participants[] = $participant;
        }
        return $participants;
    }

    public function countParticipants($raceId)
    {
        $row = $this->con->getDataSR(
            "SELECT COUNT(*) AS `total` FROM `streetrace_participant` WHERE `gameID` = :raceID",
            array(':raceID' => $raceId)
        );
        return isset($row['total']) ? (int)$row['total'] : 0;
    }

    public function getUserParticipant($raceId)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `userID`, `vehicleName`, `horsepower`, `topspeed`, `acceleration`, `control`, `breaking`, `score`, `position`, `prize` FROM `streetrace_participant` WHERE `gameID` = :raceID AND `userID` = :uid LIMIT 1");
            $statement->execute(array(':raceID' => $raceId, ':uid' => $_SESSION['UID']));
            $row = $statement->fetch();
            if(isset($row['id']))
            {
                $participant = new StreetraceParticipant();
                $participant->setId($row['id']);
                $participant->setGameID($raceId);
                $participant->setUserID($row['userID']);
                $participant->setVehicleName($row['vehicleName']);
                $participant->setHorsepower($row['horsepower']);
                $participant->setTopspeed($row['topspeed']);
                $participant->setAcceleration($row['acceleration']);
                $participant->setControl($row['control']);
                $participant->setBreaking($row['breaking']);
                $participant->setScore($row['score']);
                $participant->setPosition($row['position']);
                $participant->setPrize($row['prize']);
                return $participant;
            }
        }
        return false;
    }

    public function completeRace($raceId, array $results)
    {
        try
        {
            $this->dbh->beginTransaction();

            $updateParticipant = $this->dbh->prepare("UPDATE `streetrace_participant` SET `score` = :score, `position` = :position, `prize` = :prize WHERE `id` = :id LIMIT 1");
            $updateUser = $this->dbh->prepare("UPDATE `user` SET `cash` = `cash` + :prize WHERE `id` = :uid LIMIT 1");
            foreach($results AS $result)
            {
                $updateParticipant->execute(array(
                    ':score' => $result['score'],
                    ':position' => $result['position'],
                    ':prize' => $result['prize'],
                    ':id' => $result['participantId']
                ));

                if($result['prize'] > 0)
                {
                    $updateUser->execute(array(':prize' => $result['prize'], ':uid' => $result['userId']));
                }
            }

            $statement = $this->dbh->prepare("UPDATE `streetrace_game` SET `status`='finished', `started` = COALESCE(`started`, NOW()), `finished` = NOW() WHERE `id` = :raceID LIMIT 1");
            $statement->execute(array(':raceID' => $raceId));

            $this->dbh->commit();
            return true;
        }
        catch(PDOException $e)
        {
            $this->dbh->rollBack();
            throw $e;
        }
    }

    public function getUserLastResult()
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR(
                "SELECT g.`id` FROM `streetrace_game` AS g LEFT JOIN `streetrace_participant` AS sp ON (sp.`gameID` = g.`id`) WHERE sp.`userID` = :uid AND g.`status`='finished' ORDER BY g.`finished` DESC LIMIT 1",
                array(':uid' => $_SESSION['UID'])
            );
            if(isset($row['id']))
                return $this->getRaceById($row['id']);
        }
        return false;
    }
}
