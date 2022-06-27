<?PHP

header('Content-Type: application/manifest+json');

require_once __DIR__ . '/.inc.head.php';

require_once __DIR__ . '/.inc.foot.php';

// Render view
echo $twig->render('/web/public/app.webmanifest', $twigVars);
