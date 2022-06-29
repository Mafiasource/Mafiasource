<?PHP

header('Content-Type: text/xml');

// Render view
echo $twig->render('sitemap.xml');
