<?PHP

header('Content-Type: application/manifest+json');

$twigVars = array('routing' => $route);

// Render view
echo $twig->render('/web/public/app.webmanifest', $twigVars);
