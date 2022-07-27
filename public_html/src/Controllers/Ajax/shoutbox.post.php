<?PHP

use src\Business\ShoutboxService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['message']) && isset($_POST['security-token']) && isset($_POST['famID']))
{
    $shoutbox = new ShoutboxService();
    
    $response = $shoutbox->postMessage($_POST);
    
    $response['alert']['message'] .= "
    <script type='text/javascript'>
    $(document).ready(function(){
        checkShout();
    });
    </script>
    ";
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}