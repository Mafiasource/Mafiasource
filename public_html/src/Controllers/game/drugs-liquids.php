<?PHP

use src\Business\DrugLiquidService;
use src\Business\SmuggleService;

require_once __DIR__ . '/.inc.head.php';

$drugLiquid = new DrugLiquidService();
$tab = "drugs";
switch($route->getRouteName())
{
    default: case 'drugs-liquids':
        $tab = "drugs";
        $smuggle = new SmuggleService($userData->getCityID(), $tab, $userData->getDonatorID());
        $sPage = $smuggle->getSmugglingPageInfo();
        $units = $drugLiquid->getDrugLiquidUnits();
        break;
    case 'drugs-liquids-liquids':
        $tab = "liquids";
        $smuggle = new SmuggleService($userData->getCityID(), $tab, $userData->getDonatorID());
        $sPage = $smuggle->getSmugglingPageInfo(2);
        $units = $drugLiquid->getDrugLiquidUnits(2);
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->drugsLiquidsLangs()); // Extend base langs
$twigVars['sPage'] = $sPage;
$twigVars['maxUnits'] = $drugLiquid->maxUnits;
if(isset($units)) $twigVars['units'] = $units;

print_r($twig->render('/src/Views/game/drugs-liquids.twig', $twigVars));
