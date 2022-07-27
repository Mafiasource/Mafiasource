<?PHP

header('Content-Type: text/xml');

// Render view
print_r($twig->render('/web/public/browserconfig.xml'));
