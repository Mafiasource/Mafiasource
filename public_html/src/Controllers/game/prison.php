<?PHP

use src\Business\PrisonService;
use src\Business\PossessionService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

$prison = new PrisonService();
$possession = new PossessionService();
$possessionId = 10; //Gevangenis | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId); // Possess table record id
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$type = $route->requestGetParam(2);
$par3 = $route->requestGetParam(3);
$unableTo = $route->requestGetParam(4);
if($type == 'in-prison' && $par3 == "unable-to" && $unableTo !== FALSE) $unableTo = urldecode($unableTo);

$famPrison = false;
$famID = 0;
if($type == "family-prison") $famPrison = $userData->getFamilyID();
$pagination = new Pagination($prison, 25, 25);
$inPrison = $prison->fetchPrisoners($pagination->from, $pagination->to, $famPrison);
if($famPrison != false) $famID = $famPrison;

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->prisonLangs()); // Extend base langs
$twigVars['prisoners'] = $inPrison;
$twigVars['pagination'] = $pagination;
$twigVars['famPrison'] = $famID;
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;
if($type == 'in-prison'  && isset($unableTo) && $unableTo != "page") $twigVars['unableTo'] = $unableTo;

print_r($twig->render('/src/Views/game/prison.twig', $twigVars));
