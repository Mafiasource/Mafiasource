<?PHP

header('Content-Type: text/xml');

// Render view
print_r($twig->render('sitemap.xml'));
