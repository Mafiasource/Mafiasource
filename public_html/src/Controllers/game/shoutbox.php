<?PHP

use src\Business\ShoutboxService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

$famID = 0;
switch($route->getRouteName())
{
    case 'family-shoutbox':
        if($userData->getFamilyID() > 0) $famID = $userData->getFamilyID();
        break;
    case 'family-shoutbox-page':
        if($userData->getFamilyID() > 0) $famID = $userData->getFamilyID();
        break;
}

$shoutbox = new ShoutboxService();
$shoutbox->setFamilyID($famID);
$pagination = new Pagination($shoutbox);
$messages = $shoutbox->getMessageRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->shoutboxLangs()); // Extend base langs
$twigVars['shoutbox'] = $famID; // 0 standaard shoutbox
$twigVars['messages'] = $messages;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/game/shoutbox.twig', $twigVars));
