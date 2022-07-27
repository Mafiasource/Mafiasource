<?PHP

use src\Business\SmuggleService;
use src\Business\StateService;

//DB: 1=Drugs, 2=Liquids, 3=Fireworks, 4=Weapons, 5=Exotic Animals
//BEWARE! $smuggle = SmuggleService class initiated below and is used as a global variable in the underlying Service & DAO class to reduce load.

require_once __DIR__ . '/.inc.head.php';

$tab = "drugs";
switch($route->getRouteName())
{
    case 'smuggling':
        $tab = "drugs";
        $smuggle = new SmuggleService($userData->getCityID(), $tab, $userData->getDonatorID());
        $prices = $smuggle->prices;
        $sPage = $smuggle->getSmugglingPageInfo();
        break;
    case 'smuggling-liquids':
        $tab = "liquids";
        $smuggle = new SmuggleService($userData->getCityID(), $tab, $userData->getDonatorID());
        $prices = $smuggle->prices;
        $sPage = $smuggle->getSmugglingPageInfo(2);
        break;
    case 'smuggling-fireworks':
        $tab = "fireworks";
        $smuggle = new SmuggleService($userData->getCityID(), $tab, $userData->getDonatorID());
        $prices = $smuggle->prices;
        $sPage = $smuggle->getSmugglingPageInfo(3);
        break;
    case 'smuggling-weapons':
        $tab = "weapons";
        $smuggle = new SmuggleService($userData->getCityID(), $tab, $userData->getDonatorID());
        $prices = $smuggle->prices;
        $sPage = $smuggle->getSmugglingPageInfo(4);
        break;
    case 'smuggling-exotic-animals':
        $tab = "exotic-animals";
        $smuggle = new SmuggleService($userData->getCityID(), $tab, $userData->getDonatorID());
        $prices = $smuggle->prices;
        $sPage = $smuggle->getSmugglingPageInfo(5);
        break;
    case 'smuggling-profit-index':
    case 'smuggling-profit-index-unit':
    default:
        $tab = "profit-index";
        $unitID = $route->requestGetParam(4, array('min' => 1, 'max' => 54));
        $c = array();
        $state = new StateService();
        $cities = $state->getCities();
        foreach($cities AS $city) $c[$city->getId()] = $city->getName();
        $cities = $c;
        $cities1 = array_slice($c, 0, 9, TRUE);
        $cities2 = array_slice($c, 9, 17, TRUE);
        if($unitID !== FALSE && is_numeric($unitID))
        {
            $smuggle = new SmuggleService(FALSE, SmuggleService::getTypeByUnitID($unitID), $userData->getDonatorID());
        }
        else
        {
            $smuggle = new SmuggleService(FALSE, 'drugs', $userData->getDonatorID());
            $captId = end($smuggle->getSmugglingPageInfo(FALSE)['smuggle'])->getId();
            $smuggle = new SmuggleService(FALSE, SmuggleService::getTypeByUnitID($captId), $userData->getDonatorID());
        }
        $prices = $smuggle->prices;
        $unitNumbers = $smuggle->unitNumbers;
        $typeNames = $smuggle->typeNames;
        if($unitID === FALSE) $sPage = $smuggle->getSmugglingPageInfo(FALSE);
        else $sPage = $smuggle->getSmugglingPageInfo(array('unitID' => $unitID));
        break;
}

if(isset($prices)) $pricesArr = unserialize($prices);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
if(isset($sPage)) $twigVars['sPage'] = $sPage;
if(isset($pricesArr)) $twigVars['prices'] = $pricesArr;
if(isset($unitNumbers)) $twigVars['unitNumbers'] = $unitNumbers;
if(isset($typeNames)) $twigVars['typeNames'] = $typeNames;
if(isset($cities)) $twigVars['cities'] = $cities;
if(isset($cities1)) $twigVars['cities1'] = $cities1;
if(isset($cities2)) $twigVars['cities2'] = $cities2;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->smugglingLangs()); // Extend base langs

print_r($twig->render('/src/Views/game/smuggling.twig', $twigVars));
