<?PHP

require_once __DIR__ . '/.inc.head.php';

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->screenshotsLangs());
$twigVars['screenshots'] = array(
    array('name' => "Blackjack", 'screen' => "blackjack"),
    array('name' => "Estate Agency", 'screen' => "estate-agency"),
    array('name' => "Ownable Ground", 'screen' => "ground-area"),
    array('name' => "Missions", 'screen' => "missions"),
    array('name' => "Stock Exchange", 'screen' => "stock-exchange")
);

// Render view
print_r($twig->render('/src/Views/screenshots.twig', $twigVars));
