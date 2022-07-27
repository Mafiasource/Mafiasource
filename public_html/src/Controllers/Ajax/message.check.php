<?PHP

use src\Business\UserService;
use src\Business\MessageService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['receiver']))
{
    $userService = new UserService();
    $msg = new MessageService();
    $messages = $msg->getLastMessagesIdsByReceiverId($userService->getIdByUsername($_POST['receiver']));
    $lastMessage = current($messages);
    
    if(is_object($lastMessage))
    {
        if(!isset($_SESSION['messaging']['LMID'])) $_SESSION['messaging']['LMID'] = $lastMessage->getId();
        $then = $_SESSION['messaging']['LMID'];
        $now = $lastMessage->getId();
        if($then != $now) $_SESSION['messaging']['LMID'] = $lastMessage->getId();
        
        $data = array('IDThen' => $then, 'IDNow' => $now);
        $response = json_encode($data);
        
        require_once __DIR__ . '/.inc.foot.ajax.php';
        $twigVars['response'] = $response;
        
        print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
    }
}
