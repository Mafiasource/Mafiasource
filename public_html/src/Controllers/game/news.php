<?PHP

use src\Business\NewsService;

require_once __DIR__ . '/.inc.head.php';

$news = new NewsService();
$latestMessages = $news->getLatestMessages();

$tab = "latest";
switch($route->getRouteName())
{
    case 'latest-news':
        $tab = "latest";
        break;
    case 'news-news':
        $tab = "news";
        break;
    case 'news-updates':
        $tab = "updates";
        break;
}
$latestMessages = $news->getLatestMessages($tab);

require_once __DIR__ . '/.inc.foot.php';

$twigVars['latestMessages'] = $latestMessages;
if(isset($tab) && ($tab == "news" || $tab == "updates" || $tab = "latest")) $twigVars['tab'] = $tab;
$twigVars['langs'] = array_merge($twigVars['langs'], $language->newsLangs()); // Extend base langs

print_r($twig->render('/src/Views/game/news.twig', $twigVars));
