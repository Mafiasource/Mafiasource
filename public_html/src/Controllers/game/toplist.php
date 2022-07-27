<?PHP

use src\Business\UserService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

$userService = new UserService();
$tab = "toplist";
$pagination = new Pagination($userService, 25, 25);
$toplist = $userService->getToplist($pagination->from, $pagination->to);

$view = "block";
switch($route->getRouteName())
{
    case "toplist-list":
    case "toplist-list-page":
        $view = "list";
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->onlineToplistLangs()); // Extend base langs
$twigVars['tab'] = $tab;
$twigVars['toplist'] = $toplist;
$twigVars['pagination'] = $pagination;
$twigVars['view'] = $view;

print_r($twig->render('/src/Views/game/toplist.twig', $twigVars));
