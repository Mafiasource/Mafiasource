<?PHP

use src\Business\CMSService;

require_once __DIR__ . '/.inc.head.php';

$cms = new CMSService();
$linkPartners = $cms->getCMSById(9, $lang);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['linkPartners'] = $linkPartners;

// Render view
print_r($twig->render('/src/Views/link-partners.twig', $twigVars));
