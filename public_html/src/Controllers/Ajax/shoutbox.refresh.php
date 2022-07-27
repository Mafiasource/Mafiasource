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
        $famID = $_POST['famID'];
    }
    
    $shoutbox = new ShoutboxService();
    $shoutbox->setFamilyID($famID);
    $messages = $shoutbox->getMessageRows($from, $to);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['messages'] = $messages;
    $twigVars['LOOKS_SILENT'] = $language->shoutboxLangs()['LOOKS_SILENT'];
    
    print_r($twig->render('/src/Views/game/Ajax/shoutbox.twig', $twigVars));
}
