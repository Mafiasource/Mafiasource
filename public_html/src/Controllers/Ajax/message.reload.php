<?PHP

use src\Business\UserService;
use src\Business\MessageService;

require_once __DIR__ . '/.inc.head.ajax.php';

global $language;
$langs = $language->messagesLangs();
$userService = new UserService();
$msg = new MessageService();
$messages = $msg->getLastMessagesByReceiverId($userService->getIdByUsername($_POST['receiver']));
if(!empty($messages))
{
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['lastMessage'] = $messages;
    $twigVars['langs'] = $langs;
    
    print_r($twig->render('/src/Views/game/Ajax/message.reload.twig', $twigVars));
}
