<?PHP

header('Content-Type: text/xml');

require_once __DIR__ . '/.inc.head.php';

require_once __DIR__ . '/.inc.foot.php';

// Render view
echo $twig->render('sitemap.xml', $twigVars);
