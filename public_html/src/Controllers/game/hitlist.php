<?PHP

use src\Business\HitlistService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

$hitlist = new HitlistService();
$pagination = new Pagination($hitlist);
$records = $hitlist->getHitlist($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->hitlistLangs()); // Expand base langs
$twigVars['hitlist'] = $records;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/game/hitlist.twig', $twigVars));
