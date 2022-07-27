<?PHP

use src\Business\MurderService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

$murder = new MurderService();
$pagination = new Pagination($murder, 15, 15);
$logs = $murder->getFamilyMurderLog($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->murderLogLangs()); // Extend base langs
$twigVars['logs'] = $logs;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/game/family-history.twig', $twigVars));
