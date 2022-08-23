<?PHP

use src\Business\AdminService;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

$resetPossible = false;
if(isset($_POST) && !empty($_POST))
{
    $round = new AdminService("round");
    $search = isset($_POST['round-no']) ? $round->searchRecords($_POST['round-no'], 'round', 'id,round', FALSE) : FALSE;
    $requiredPost = isset($_POST['member-status']) && isset($_POST['round-no']) && isset($_POST['start-date']) && isset($_POST['end-date']) ? true : false;
    
    if(!empty($search))
        $response = $route->errorMessage("Er bestaat al een ronde met deze ronde nummer!");
    elseif(isset($_POST['submit-reset']) && $security->checkToken($_POST['submit-reset']) && $requiredPost)
    {
        $allowedFields = array("member-status", "round-no", "start-date", "end-date", "keep-team", "remove-families", "next-round-date");
        foreach($_POST AS $key => $value) if(in_array($key, $allowedFields) && isset($value)) $data[$key] = $security->xssEscape($value);
        
        $nrd = isset($data['next-round-date']) && !empty($data['next-round-date']) ? $data['next-round-date'] : null;
        $nrd = isset($nrd) && (DateTime::createFromFormat('Y-m-d H:i:s', $nrd) !== false) ? $nrd : "now";
        $data['next-round-date'] = null;
        
        if(!$resetPossible)
            $response = $route->errorMessage("Awh! Activeer deze reset in <strong>/src/Controllers/admin/reset.php</strong> met de <strong>\$resetPossible</strong> variabele.");
        elseif(!isset($data) || !is_array($data) || !array_key_exists($allowedFields[0], $data))
            $response = $route->errorMessage("Je hebt ongeldige instellingen opgegeven!");
        else
            $response = $round->resetMafiasource($data, $nrd);
    }
    elseif(isset($_POST['submit-reset-sure']) && $security->checkToken($_POST['submit-reset-sure']) && $requiredPost)
    {
        $tVars = array('securityToken' => $security->getToken(), 'docRoot' => PROTOCOL.$_SERVER['HTTP_HOST']);
        $responseMsg = $twig->render('/src/Views/admin/Ajax/reset-sure.btns.twig', $tVars);
        
        $response = $route->errorMessage($responseMsg);
    }
    else
        $response = $route->errorMessage("Verkeerde gegevens ontvangen. (Vernieuw de pagina met shortkey: f5 of ctrl + f5)");
    
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/admin/Ajax/.default.response.twig', $twigVars));
    exit(0);
}
else
{
    $table = new AdminService("round");
    
    require_once __DIR__ . '/.inc.foot.php';
    $twigVars['offline'] = OFFLINE;
    $twigVars['previousRound'] = isset($table->getLastRecord()[0][0]) ? $table->getLastRecord()[0][0] : 0;
    
    print_r($twig->render('/src/Views/admin/reset.twig', $twigVars));
}
