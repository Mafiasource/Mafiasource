<?PHP

use src\Business\PossessionService;
use src\Business\BulletFactoryService;

require_once __DIR__ . '/.inc.head.php';

$possession = new PossessionService();
$possessionId = 1; //Bullet Factory | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID()); // Possess table record id
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$bfInfo = $possession->getBulletFactoryInfoByPossessID($possessId);
$bf = new BulletFactoryService();
$bulletFactories = $bf->getBulletFactories();

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->bulletFactoriesLangs()); // Extend base langs
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;
$twigVars['bfInfo'] = $bfInfo;
$twigVars['bulletFactories'] = $bulletFactories;

print_r($twig->render('/src/Views/game/bullet-factories.twig', $twigVars));
