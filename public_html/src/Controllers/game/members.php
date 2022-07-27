<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.php';

$userService = new UserService();
$tab = "online";
$online = $userService->getOnlineMembers();
$onlineFam = $userService->getOnlineFamMembers();
$onlineTeam = $userService->getOnlineTeamMembers(); 

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->onlineToplistLangs()); // Extend base langs
$twigVars['tab'] = $tab;
$twigVars['onlineMembers'] = $online;
$twigVars['onlineFam'] = $onlineFam;
$twigVars['onlineTeam'] = $onlineTeam;

print_r($twig->render('/src/Views/game/members.twig', $twigVars));
