<?PHP

use src\Business\ShoutboxService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['from']) && isset($_POST['to']) && isset($_POST['famID']))
{
    $famID = 0;
    $from = (int)$_POST['from'];
    $to = (int)$_POST['to'];
    if($_POST['famID'] != 0)
    {
        if($_POST['famID'] != $userData->getFamilyID())
        {
            exit(0);
        }
        $famID = (int)$_POST['famID'];
    }
    
    $shoutbox = new ShoutboxService();
    $shoutbox->setFamilyID($famID);
    $messages = $shoutbox->getMessageRowsIds($from, $to);
    $lastMessage = current($messages);
    
    if(is_object($lastMessage))
    {
        if(!isset($_SESSION['shoutbox']['LMID'])) $_SESSION['shoutbox']['LMID'] = $lastMessage->getId();
        $then = $_SESSION['shoutbox']['LMID'];
        $now = $lastMessage->getId();
        if($then != $now) $_SESSION['shoutbox']['LMID'] = $lastMessage->getId();
        
        $data = array('IDThen' => $then, 'IDNow' => $now);
        $response = json_encode($data);
        
        require_once __DIR__ . '/.inc.foot.ajax.php';
        $twigVars['response'] = $response;
        
        print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
    }
}
