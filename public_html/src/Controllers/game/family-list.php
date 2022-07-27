<?PHP

use src\Business\FamilyService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

$family = new FamilyService();
$pagination = new Pagination($family, 15, 15);
$famlist = $family->getFamlist($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->familyLangs()); // Extend base langs
$twigVars['famlist'] = $famlist;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/game/family-list.twig', $twigVars));
