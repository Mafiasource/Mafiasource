<?PHP

use src\Business\DrugLiquidService;
use src\Business\SmuggleService;

require_once __DIR__ . '/.inc.head.ajax.php';

$allowedTypes = array("Drugs", "Liquids");
if(isset($_POST['security-token']) && isset($_POST['units']) && is_array($_POST['units']) && isset($_POST['type']) && in_array($_POST['type'], $allowedTypes))
{
    $dl = new DrugLiquidService();
    
    switch($_POST['type'])
    {
        default:
        case 'Liquids':
        case 'Drugs':
            $smuggle = new SmuggleService($userData->getCityID(), strtolower($_POST['type']), $userData->getDonatorID());
            break;
    }
    
    $response = $dl->collectUnits($_POST);
    
    switch($_POST['type'])
    {
        case 'Drugs':
            $sPage = $smuggle->getSmugglingPageInfo();
            $units = $dl->getDrugLiquidUnits();
            $type = $_POST['type'];
            break;
        case 'Liquids':
            $sPage = $smuggle->getSmugglingPageInfo(2);
            $units = $dl->getDrugLiquidUnits(2);
            $type = $_POST['type'];
            break;
    }
    
    require_once __DIR__ . '/.inc.foot.ajax.php';
    $twigVars['responseCollect'] = $response;
    
    $twigVars['type'] = $type;
    $twigVars['langs'] = array_merge($twigVars['langs'], $language->drugsLiquidsLangs()); // Extend base langs
    $twigVars['sPage'] = $sPage;
    $twigVars['maxUnits'] = $dl->maxUnits;
    if(isset($units))
        $twigVars['units'] = $units;
    
    print_r($twig->render('/src/Views/game/Ajax/drugs.liquids.twig', $twigVars));
}
