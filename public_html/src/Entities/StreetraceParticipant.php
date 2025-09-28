<?PHP

namespace src\Entities;

class StreetraceParticipant
{
    private $id;
    private $gameID;
    private $userID;
    private $username;
    private $vehicleGarageID;
    private $vehicleName;
    private $horsepower;
    private $topspeed;
    private $acceleration;
    private $control;
    private $breaking;
    private $score = 0;
    private $position = 0;
    private $prize = 0;
    private $joined;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getGameID()
    {
        return $this->gameID;
    }

    public function setGameID($gameID)
    {
        $this->gameID = $gameID;
    }

    public function getUserID()
    {
        return $this->userID;
    }

    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getVehicleGarageID()
    {
        return $this->vehicleGarageID;
    }

    public function setVehicleGarageID($vehicleGarageID)
    {
        $this->vehicleGarageID = $vehicleGarageID;
    }

    public function getVehicleName()
    {
        return $this->vehicleName;
    }

    public function setVehicleName($vehicleName)
    {
        $this->vehicleName = $vehicleName;
    }

    public function getHorsepower()
    {
        return $this->horsepower;
    }

    public function setHorsepower($horsepower)
    {
        $this->horsepower = $horsepower;
    }

    public function getTopspeed()
    {
        return $this->topspeed;
    }

    public function setTopspeed($topspeed)
    {
        $this->topspeed = $topspeed;
    }

    public function getAcceleration()
    {
        return $this->acceleration;
    }

    public function setAcceleration($acceleration)
    {
        $this->acceleration = $acceleration;
    }

    public function getControl()
    {
        return $this->control;
    }

    public function setControl($control)
    {
        $this->control = $control;
    }

    public function getBreaking()
    {
        return $this->breaking;
    }

    public function setBreaking($breaking)
    {
        $this->breaking = $breaking;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function setScore($score)
    {
        $this->score = $score;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPrize()
    {
        return $this->prize;
    }

    public function setPrize($prize)
    {
        $this->prize = $prize;
    }

    public function getJoined()
    {
        return $this->joined;
    }

    public function setJoined($joined)
    {
        $this->joined = $joined;
    }
}
