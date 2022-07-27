<?PHP

use src\Business\UserService;
use src\Business\FamilyService;
use src\Business\ResidenceService;
use src\Business\PossessionService;
use src\Business\MissionService;

require_once __DIR__ . '/.inc.head.php';

$username = $route->requestGetParam(3);

$userService = new UserService();
$profileData = $userService->getUserProfile($username);
if(!is_object($profileData))
{
    $route->headTo("not_found");
    exit(0);
}
else
{
    $friends = $userService->getFriendsBlock($username, TRUE);
    $residenceService = new ResidenceService();
    $residenceData = $residenceService->getResidenceDataByUserID($profileData->getId());
    $residenceData->setResidence($residenceService->getResidenceNameById($residenceData->getResidence()));
    $possessionService = new PossessionService();
    $possessions = $possessionService->getProfilePossessionsByUserID($profileData->getId());
    $missionService = new MissionService();
    $missions = $missionService->getProfileMissionsByUserID($profileData->getId());
    $commitPimp = false;
    if($route->getRouteName() == "profile-pimp-now") $commitPimp = true;
    if($profileData->getFamilyID() > 0) $family = new FamilyService();
}

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->profileLangs()); // Extend base langs
$twigVars['langs']['PROFILE_HEADING'] = $route->replaceMessagePart($username, $twigVars['langs']['PROFILE_HEADING'], '/{name}/');
$twigVars['profileData'] = $profileData;
$twigVars['friends'] = $friends;
$twigVars['residenceData'] = $residenceData;
$twigVars['possessions'] = $possessions;
$twigVars['missions'] = $missions;
if($userData->getCPimpWhoresFor() < time())
    $twigVars['pimpFor'] = true;
if($profileData->getFamilyID() > 0) $twigVars['familyData'] = $family->getFamilyDataByName($profileData->getFamily());

if(isset($commitPimp)) $twigVars['commitPimp'] = $commitPimp;

print_r($twig->render('/src/Views/game/profile.twig', $twigVars));
