<?PHP

use src\Business\ForumService;
use src\Business\SeoService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = 0;
if(isset($_POST['security-token']) && isset($_POST['category']) && isset($_POST['topic-title']) && isset($_POST['topic-message']))
{
    if($_POST['category'] == 'Family Forum' || $_POST['category'] == 'Familie Forum') $famID = $userData->getFamilyID();
    $forum = new ForumService($famID);
    $response = $forum->createNewTopic($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
