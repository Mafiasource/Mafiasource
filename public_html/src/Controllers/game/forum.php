<?PHP

use src\Business\ForumService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

/** In-game controller forum **/

$famID = 0;
$category = $route->requestGetParam(3);
if($category == "familie-forum" || $category == "family-forum") $famID = $userData->getFamilyID();
$topic = $route->requestGetParam(4);


$forumTitle = $route->settings['gamename']." Forum";
switch($route->getRouteName())
{
    default: case 'game-forum':
        $forum = new ForumService($famID);
        $categories = $forum->getCategories();
        break;
    case 'game-forum-cat':
        $forum = new ForumService($famID, $category);
        $categories = $forum->getCategories();
        if(($famID > 0 && ($category == "familie-forum" || $category == "family-forum")) || $famID === 0)
        {
            $catID = $forum->getCategoryIdByCategory($category);
            $cat = $forum->getCategory();
            if(is_object($cat))
            {
                $topicID = FALSE;
                $topics = $forum->getTopics();
                if(!empty($cat)) $forumTitle = $cat->getCategory();
            }
            else
                $cat =  FALSE;
        }
        break;
    case 'game-forum-cat-topic' || 'game-forum-cat-topic-page':
        $forum = new ForumService($famID, $category, $topic);
        $categories = $forum->getCategories();
        if(($famID > 0 && ($category == "familie-forum" || $category == "family-forum")) || $famID === 0)
        {
            $catID = $forum->getCategoryIdByCategory($category);
            $cat = $forum->getCategory();
            if(is_object($cat))
            {
                $topicID = $forum->getTopicIdByCategoryAndTopicUrl($category, $topic);
                // $topics = $forum->getTopics();
                $topicData = $forum->getTopicData();
                if(!empty($topicData))
                {
                    $forumTitle = $topicData->getTitle();
                    $pagination = new Pagination($forum, 10, 10);
                    if($pagination->tpages > 1 &&
                        ($pagination->page == 1  && $route->getPrevRouteName() != "game-forum-cat-topic") &&
                        $pagination->page != $pagination->tpages && $route->getPrevRouteName() != "game-forum-cat-topic-page"
                    )
                    {
                        header("Location: " . $route->getRoute() . "/page/" . $pagination->tpages);
                        exit(0);
                    }
                    $topicReactions = $forum->getTopicReactions($pagination->from, $pagination->to);
                    $topicData->setReactions($topicReactions);
                }
            }
            $cat = FALSE; // Reset after status checkup! 
        }
        break;
}

$navigation = $forum->navigate();

require_once __DIR__ . '/.inc.foot.php';

$twigVars['langs'] = array_merge($twigVars['langs'], $language->forumLangs()); // Extend base langs
$twigVars['categories'] = $categories;
$twigVars['navigation'] = $navigation;
$twigVars['forumTitle'] = $forumTitle;
if(isset($pagination)) $twigVars['pagination'] = $pagination;
if(isset($catID) && $catID > 0 && isset($topicID) && $topicID == FALSE) {$twigVars['catID'] = $catID; $twigVars['topics'] = $topics; $twigVars['catUrl'] = $category;}
if(isset($topicID) && $topicID > 0 && isset($catID)) {$twigVars['catID'] = $catID; $twigVars['topicID'] = $topicID;}
if(isset($cat)) $twigVars['catData'] = $cat;
if(isset($topicData)) $twigVars['topicData'] = $topicData;

print_r($twig->render('/src/Views/game/forum.twig', $twigVars));
