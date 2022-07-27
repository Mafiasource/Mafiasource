<?PHP

use src\Business\GarageService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($userData->getFamilyID() == 0)
{
    $route->headTo('family-list');
    exit(0);
}

$hasRights = FALSE;
if($userData->getFamilyBoss() === true || $userData->getFamilyUnderboss() === true) $hasRights = TRUE;

$tab = "vehicles";
$garage = new GarageService();
$hasGarage = $garage->hasFamilyGarage();
switch($route->getRouteName())
{
    default:
    case 'family-garage':
    case 'family-garage-page':
        $tab = "vehicles";
        $garageOptions = $garage->getGarageOptions(TRUE);
        $pagination = new Pagination($garage, 15, 15);
        $vehicles = $garage->getVehiclesInFamilyGarage($pagination->from, $pagination->to);
        if($hasGarage != FALSE)
        {
            $garageData = $garage->familyGarageOptions[$garage->getFamilyGarageSize()];
            $spaceLeft = $garage->hasSpaceLeftInFamilyGarage();
            if($spaceLeft != FALSE) $spaceLeftNum = $garage->spaceLeftInFamilyGarage($garageData['space']);
        }
        break;
    case 'family-garage-crusher-converter':
        $tab = "crusher-converter";
        $garageOptions = $garage->getGarageOptions(TRUE);
        $hasRights = FALSE;
        if($userData->getFamilyBoss() === true || $userData->getFamilyBankmanager() === true) $hasRights = TRUE;
        $crusherConverter = $garage->getFamilyCrusherConverter();
        break;
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->garageLangs()); // Extend base langs
if(isset($hasGarage))
{
    $twigVars['hasGarage'] = $hasGarage;
    $twigVars['garageOptions'] = $garageOptions;
    if(isset($garageData)) $twigVars['garageData'] = $garageData;
    if(isset($spaceLeft))
    {
        $twigVars['spaceLeft'] = $spaceLeft;
        if($spaceLeft != FALSE)
        {
            $twigVars['langs']['X_SPACE_LEFT_FAMILY_GARAGE'] = $route->replaceMessagePart($spaceLeftNum, $twigVars['langs']['X_SPACE_LEFT_FAMILY_GARAGE'], '/{x}/');
        }
    }
}
if(isset($pagination)) $twigVars['pagination'] = $pagination;
if(isset($vehicles)) $twigVars['vehicles'] = $vehicles;
$twigVars['hasRights'] = $hasRights;
if($tab == "crusher-converter")
{
    if(isset($crusherConverter))
    {
        $twigVars['crusherConverter'] = $crusherConverter;
        $twigVars['langs']['FAMILY_CAN_CRUSH_X_VEHICLES'] = $route->replaceMessagePart(
            number_format($crusherConverter->getCrusher(), 0, '', ','), $twigVars['langs']['FAMILY_CAN_CRUSH_X_VEHICLES'], '/{capacity}/'
        );
        $twigVars['langs']['FAMILY_CAN_CONVERT_X_VEHICLES'] = $route->replaceMessagePart(
            number_format($crusherConverter->getConverter(), 0, '', ','), $twigVars['langs']['FAMILY_CAN_CONVERT_X_VEHICLES'], '/{capacity}/'
        );
    }
    $twigVars['crushers'] = $garage->familyCrushers;
    $twigVars['converters'] = $garage->familyConverters;
}

print_r($twig->render('/src/Views/game/family-garage.twig', $twigVars));
