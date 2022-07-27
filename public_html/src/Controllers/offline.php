<?PHP

$offlineMsg = "Please turn on your internet connection.";
if($lang == "nl")
    $offlineMsg = "Leg uw internet verbinding aan a.u.b.";

$twigVars = array('offlineMsg' => $offlineMsg, 'routing' => $route);

// Render view
print_r($twig->render('/src/Views/offline.twig', $twigVars));
