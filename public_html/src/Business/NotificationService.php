<?PHP
 
namespace src\Business;
 
use src\Data\NotificationDAO;
 
class NotificationService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new NotificationDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getLatestNotifications()
    {
        return $this->data->getLatestNotifications();
    }
    
    public function setReadNotifications()
    {
        return $this->data->setReadNotifications();
    }
    
    public function sendNotification($userID, $notification, $params = "")
    {
        return $this->data->sendNotification($userID, $notification, $params);
    }
}
