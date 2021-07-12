<?PHP

use src\Business\UserService;
use src\Business\MessageService;

require_once __DIR__ . '/.inc.head.ajax.php';

$userService = new UserService();
$msg = new MessageService();
$response = $msg->replyToMessage($_POST);
if($response !== TRUE)
{
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    echo $twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars);
}
else
{
    ?>
    <script>
    reloadMessages("<?=$_POST['receiver'];?>");
    </script>
    <?PHP
}
