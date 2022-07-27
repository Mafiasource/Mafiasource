<?PHP

use src\Business\RedLightDistrictService;
use src\Business\StateService;
use src\Business\PossessionService;

require_once __DIR__ . '/.inc.head.php';

$rld = new RedLightDistrictService();

$possession = new PossessionService();
$possessionId = 2; //Red Light District | Possession logic
$possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID()); // Possess table record id
$pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

$rldPage = $rld->getRedLightDistrictPageInfo();
$rldInfo = $possession->getRLDInfoByPossessID($possessId);
$commit = false;
switch($route->getRouteName())
{
    case 'red-light-district':
        $tab = "pimp";
        break;
    case 'buy-hooker-windows':
        $tab = "rld";
        break;
    case 'red-light-district-do':
        $tab = "pimp";
        $commit = true;
        break;
    default:
        $tab = "pimp";
        break;
}
$state = new StateService();
$states = $state->getStates();

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['states'] = $states;
if(isset($rldPage)) $twigVars['rldPage'] = $rldPage;
if(isset($rldInfo)) $twigVars['rldInfo'] = $rldInfo;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->redLightDistrictLangs()); // Extend base langs
if(isset($commit)) $twigVars['commit'] = $commit;
$userWhoresInfoReplaces = array(
    array('part' => number_format($rldPage->getTotalWhores(),0,'',','), 'message' => $twigVars['langs']['USER_WHORES_INFO'], 'pattern' => '/{totalWhores}/'),
    array('part' => number_format($rldPage->getWhoresStreet(),0,'',','), 'message' => FALSE, 'pattern' => '/{streetWhores}/'),
    array('part' => number_format($rldPage->getWhoresRLD(),0,'',','), 'message' => FALSE, 'pattern' => '/{windowWhores}/')
);
$replacedWhoresInfoMessage = $route->replaceMessageParts($userWhoresInfoReplaces);
$twigVars['userWhoresInfo'] = $replacedWhoresInfoMessage;
$twigVars['possessId'] = $possessId;
$twigVars['possessionData'] = $pData;

print_r($twig->render('/src/Views/game/red-light-district.twig', $twigVars));
