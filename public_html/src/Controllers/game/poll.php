<?PHP

use src\Business\PollService;

require_once __DIR__ . '/.inc.head.php';

$poll = new PollService();

$tab = "poll";
switch($route->getRouteName())
{
    default:
    case 'poll':
        $tab = "poll";
        $activePolls = $poll->getActiveQuestions();
        break;
    case 'poll-history':
        $tab = "poll-history";
        $finishedPolls = $poll->getFinishedQuestions();
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->pollLangs()); // Extend base langs
if(isset($activePolls)) $twigVars['activePolls'] = $activePolls;
if(isset($finishedPolls)) $twigVars['finishedPolls'] = $finishedPolls;

print_r($twig->render('/src/Views/game/poll.twig', $twigVars));
