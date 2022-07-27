<?PHP

use src\Business\PrisonService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['from']) && isset($_POST['to']) && isset($_POST['famID']))
{
    $famID = 0;
    $from = (int)round($_POST['from']);
    if($_POST['famID'] != 0 && $_POST['famID'] != false)
    {
        if($_POST['famID'] != $userData->getFamilyID())
        {
            exit(0);
        }
        $famID = $_POST['famID'];
    }
    
    $page = round($from / 25) + 1;
    $reqUri = $_SERVER['REQUEST_URI'];
    $_SERVER['REQUEST_URI'] = '/game/prison/page/'.$page; // Set fake Req URI pagination purposes
    $prison = new PrisonService();
    $pagination = new Pagination($prison, 25, 25);
    $inPrison = $prison->fetchPrisoners($pagination->from, $pagination->to, $famID);
    $_SERVER['REQUEST_URI'] = $reqUri;// Reset fake Req URI pagination purposes
    $reqUri = null;
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['prisoners'] = $inPrison;
    $twigVars['langs'] =  array_merge($twigVars['langs'], $language->prisonLangs());
    $twigVars['time'] = time();
    $twigVars['pagination'] = $pagination;
    
    print_r($twig->render('/src/Views/game/Ajax/prison.twig', $twigVars));
}
