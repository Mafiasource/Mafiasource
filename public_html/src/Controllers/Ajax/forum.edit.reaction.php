<?PHP

use src\Business\ForumService;

require_once __DIR__ . '/.inc.head.ajax.php';

$famID = 0;
if(isset($_POST['topicID']) && isset($_POST['reactionID']) && isset($_POST['edited-message']) && isset($_POST['security-token']))
{
    if(isset($_POST['family-forum'])) $famID = $userData->getFamilyID();
    $forum = new ForumService($famID);
    $response = $forum->editReactionInTopic($_POST);
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/game/Ajax/.default.response.twig', $twigVars));
}
