<?PHP

header('Content-Type: application/manifest+json');

$twigVars = array('routing' => $route);

// Render view
print_r($twig->render('/web/public/app.webmanifest', $twigVars));
