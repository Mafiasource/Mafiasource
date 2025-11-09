<?PHP

namespace src\Entities;

class Streetrace
{
    private $id;
    private $organizerID;
    private $organizerName;
    private $type;
    private $stake;
    private $requiredPlayers;
    private $status;
    private $created;
    private $started;
    private $finished;
    private $stateID;
    private $stateName;
    private $participantCount = 0;
    private $participants = array();

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getOrganizerID()
    {
        return $this->organizerID;
    }

    public function setOrganizerID($organizerID)
    {
        $this->organizerID = $organizerID;
    }

    public function getOrganizerName()
    {
        return $this->organizerName;
    }

    public function setOrganizerName($organizerName)
    {
        $this->organizerName = $organizerName;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getStake()
    {
        return $this->stake;
    }

    public function setStake($stake)
    {
        $this->stake = $stake;
    }

    public function getRequiredPlayers()
    {
        return $this->requiredPlayers;
    }

    public function setRequiredPlayers($requiredPlayers)
    {
        $this->requiredPlayers = $requiredPlayers;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getStarted()
    {
        return $this->started;
    }

    public function setStarted($started)
    {
        $this->started = $started;
    }

    public function getFinished()
    {
        return $this->finished;
    }

    public function setFinished($finished)
    {
        $this->finished = $finished;
    }

    public function getStateID()
    {
        return $this->stateID;
    }

    public function setStateID($stateID)
    {
        $this->stateID = $stateID;
    }

    public function getStateName()
    {
        return $this->stateName;
    }

    public function setStateName($stateName)
    {
        $this->stateName = $stateName;
    }

    public function getParticipantCount()
    {
        return $this->participantCount;
    }

    public function setParticipantCount($participantCount)
    {
        $this->participantCount = $participantCount;
    }

    public function getParticipants()
    {
        return $this->participants;
    }

    public function setParticipants($participants)
    {
        $this->participants = $participants;
    }
}
