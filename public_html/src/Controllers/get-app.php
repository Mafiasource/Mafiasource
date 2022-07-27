<?PHP

use src\Business\CMSService;

require_once __DIR__ . '/.inc.head.php';

$cms = new CMSService();
$downloadApp = $cms->getCMSById(10, $lang);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['downloadApp'] = $downloadApp;

// Render view
print_r($twig->render('/src/Views/get-app.twig', $twigVars));
