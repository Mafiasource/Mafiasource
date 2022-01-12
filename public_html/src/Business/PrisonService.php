<?PHP
 
namespace src\Business;

use app\config\Routing;
use src\Business\PossessionService;
use src\Business\PublicMissionService;
use src\Data\PrisonDAO;

/* Class has some cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */

class PrisonService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new PrisonDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function handleAction($post)
    {
        global $route;
        global $language;
        global $langs;
        $l        = $language->prisonLangs();
        global $security;
        global $userData;
        
        $pid = (int)$post['pid'];
        $prisonerInfo = $this->data->getPrisonerByPID($pid);
        
        if($post['securityToken'] != $security->getToken())
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($prisonerInfo == FALSE || (is_object($prisonerInfo) && $prisonerInfo->getTime() < time()))
        {
            $error = $l['USER_NOT_IN_PRISON'];
        }
        if(is_object($prisonerInfo) && $prisonerInfo !== FALSE && $prisonerInfo !== null && $prisonerInfo->getUserID() == $_SESSION['UID'] && $post['action'] == 'break-out')
        {
            $error = $l['CANNOT_BREAK_SELF_OUT'];
        }
        if(is_object($prisonerInfo) && $prisonerInfo !== FALSE && $prisonerInfo !== null && $prisonerInfo->getUserID() != $_SESSION['UID'] && $post['action'] == 'break-out' && $userData->getCPrisonTime() > time())
        {
            $error = $l['NO_BREAK_USER_JAILED'];
        }
        if(is_object($prisonerInfo) && $prisonerInfo !== FALSE && $prisonerInfo !== null) $priceToBuyOut = ($prisonerInfo->getTime() - time()) * 250;
        if(is_object($prisonerInfo) && $prisonerInfo->getUserID() == $_SESSION['UID']) $priceToBuyOut *= 3;
        if(isset($priceToBuyOut) && $priceToBuyOut > $userData->getCash() && $post['action'] == 'buy-out')
        {
            $error = $l['NOT_ENOUGH_CASH_TO_BUY_OUT'];
        }
        if($userData->getTraveling())
        {
            $error = $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        
        if(isset($error))
        {
            return array("error" => Routing::errorMessage($error));
        }
        else
        {
            switch($post['action'])
            {
                
                case 'buy-out':
                    $possession = new PossessionService();
                    $possessionId = 10; //Gevangenis | Possession logic
                    $possessId = $possession->getPossessIdByPossessionId($possessionId); // Possess table record id
                    $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
                    
                    $this->data->buyOutPlayerByPID($pid, $priceToBuyOut, $pData);
                    
                    $username = $prisonerInfo->getUsername();
                    if($_SESSION['UID'] == $prisonerInfo->getUserID())
                    {
                        global $lang;
                        $username = "yourself";
                        if($lang == 'nl') $username = "jezelf";
                    }
                    $replacedMessage = $route->replaceMessagePart($username, $l['USER_BOUGHT_OUT_PRISON'], '/{playerName}/');
                    $replacedMessage = Routing::successMessage($replacedMessage);
                    $replacedMessage['alert']['message'] .= "<script type='text/javascript'>$('#pRecord_".$prisonerInfo->getId()."').remove();</script>";
                    return $replacedMessage;
                    break;
                case 'break-out':
                    $username = $prisonerInfo->getUsername();
                    $success = $security->randInt(0, 100);
                    if($userData->getCharType() == 2)
                        $success = $security->randInt(5, 100);
                    
                    if($success >= 30)
                    {
                        $publicMissionService = new PublicMissionService();
                        $publicMissionService->addToPublicMisionIfActive(12);
                        
                        $rankpoints = round((($prisonerInfo->getTime() - time()) / 9) * 0.1, 1);
                        $this->data->successfulBreakOutPlayerByPID($pid, $rankpoints);
                        $replacedMessage = $route->replaceMessagePart($username, $l['USER_BREAK_OUT_OF_PRISON'], '/{playerName}/');
                        $replacedMessage = Routing::successMessage($replacedMessage);
                        $replacedMessage['alert']['message'] .= "<script type='text/javascript'>$('#pRecord_".$prisonerInfo->getId()."').remove();</script>";
                    }
                    else
                    {
                        $this->data->failedBreakOutPlayerByPrisonerObject($prisonerInfo);
                        $replacedMessage = $route->replaceMessagePart($username, $l['USER_BREAK_OUT_OF_PRISON_FAIL'], '/{playerName}/');
                        $replacedMessage = Routing::errorMessage($replacedMessage);
                    }
                    return $replacedMessage;
                    break;
            }
        }
    }
    
    public function fetchPrisoners($from, $to, $fam = false)
    {
        return $this->data->fetchPrisoners($from, $to, $fam);
    }
    
    public function putUserInPrison($uid, $time)
    {
        return $this->data->putUserInPrison($uid, $time);
    }
    
    public function isUserInPrison($userID)
    {
        return $this->data->isUserInPrison($userID);
    }
}
