<?PHP

use src\Business\PollService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && isset($_POST['question']))
{
    $poll = new PollService();
    
    $response = $poll->vote($_POST);
    
    $activePolls = $poll->getActiveQuestions();
    foreach($activePolls AS $ap)
    {
        $ap->setActive(false);
        if($ap->getId() == $_POST['question'])
            $ap->setActive(true);
    }
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->pollLangs()); // Extend base langs
    $twigVars['response'] = $response;
    $twigVars['activePolls'] = $activePolls;
    
    print_r($twig->render('/src/Views/game/Ajax/poll.twig', $twigVars));
}
