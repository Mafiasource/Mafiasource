<?PHP

use src\Business\CMSService;

require_once __DIR__ . '/.inc.head.php';

$cms = new CMSService();
$termsAndConditions = $cms->getCMSById(6, $lang);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['termsAndConditions'] = $termsAndConditions;

// Render view
print_r($twig->render('/src/Views/terms-and-conditions.twig', $twigVars));
