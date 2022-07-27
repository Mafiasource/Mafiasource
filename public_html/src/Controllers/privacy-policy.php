<?PHP

use src\Business\CMSService;

require_once __DIR__ . '/.inc.head.php';

$cms = new CMSService();
$privacyPolicy = $cms->getCMSById(7, $lang);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['privacyPolicy'] = $privacyPolicy;

// Render view
print_r($twig->render('/src/Views/privacy-policy.twig', $twigVars));
