<?PHP

require_once __DIR__ . '/.inc.head.php';

require_once __DIR__ . '/.inc.statistics.php';
require_once __DIR__ . '/.inc.sliders.php';

header('HTTP/2 404 Not Found', true, 404);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->notFoundLangs());

// Render view
print_r($twig->render('/src/Views/notfound.twig', $twigVars));
