<?PHP

require_once __DIR__ . '/.inc.head.php';

$offlineMsg = "Please turn on your internet connection.";
if($lang == "nl")
    $offlineMsg = "Leg uw internet verbinding aan a.u.b.";

require_once __DIR__ . '/.inc.foot.php';

$twigVars['offlineMsg'] = $offlineMsg;

// Render view
echo $twig->render('/src/Views/offline.twig', $twigVars);
