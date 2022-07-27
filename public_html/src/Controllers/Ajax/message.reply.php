<?PHP

use src\Business\UserService;
use src\Business\MessageService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['sendMessage']) && isset($_POST['receiver']) && isset($_POST['message']))
{
    $userService = new UserService();
    $msg = new MessageService();
    $response = $msg->replyToMessage($_POST);
    if($response !== TRUE)
    {
        require_once __DIR__ . '/.inc.foot.ajax.php';
        $twigVars['response'] = $response;
        
        print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
    }
    else
    {
        ?>
        <script type="text/javascript">
        reloadMessages("<?=$security->xssEscape($_POST['receiver']);?>");
        </script>
        <?PHP
    }
}
