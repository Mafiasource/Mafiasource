<?PHP

require_once __DIR__ . '/.inc.head.php';

require_once __DIR__ . '/.inc.online.message.php';
require_once __DIR__ . '/.inc.sliders.php';

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->getAppLangs());

// Render view
echo $twig->render('/src/Views/get-app.twig', $twigVars);
