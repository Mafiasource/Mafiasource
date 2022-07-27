<?PHP

use src\Business\ForumService;
use src\Business\Logic\Pagination;

require_once __DIR__ . '/.inc.head.php';

/* Out-game controller forum */

$famID = 0;
$reqPar3 = $route->requestGetParam(3);
$category = $route->requestGetParam(2);
$topic = $reqPar3;
if(in_array($uriLang, $route->allowedLangs)) // uriLang front-controller global
{
    $category = $reqPar3;
    $topic = $route->requestGetParam(4);
}
if(isset($userData) && ($category == "familie-forum" || $category == "family-forum")) $famID = $userData->getFamilyID();

$forumTitle = $route->settings['gamename']." Forum";
switch($route->getRouteName())
{
    default: case 'forum':
        $forum = new ForumService($famID);
        $categories = $forum->getCategories();
        break;
    case 'forum-cat':
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
    case 'forum-cat-topic' || 'forum-cat-topic-page':
        $forum = new ForumService($famID, $category, $topic);
        $categories = $forum->getCategories();
        if(($famID > 0 && ($category == "familie-forum" || $category == "family-forum")) || $famID === 0)
        {
            $catID = $forum->getCategoryIdByCategory($category);
            $cat = $forum->getCategory();
            if(is_object($cat))
            {
                $topicID = $forum->getTopicIdByCategoryAndTopicUrl($category, $topic);
                $topicData = $forum->getTopicData();
                if(!empty($topicData))
                {
                    $forumTitle = $topicData->getTitle();
                    $pagination = new Pagination($forum, 10, 10);
                    if($pagination->tpages > 1 &&
                        ($pagination->page == 1 && $route->getPrevRouteName() != "forum-cat-topic" && $route->getPrevRouteName() != "forum-cat-topic-page") &&
                        $pagination->page != $pagination->tpages && $route->getPrevRouteName() != "forum-cat-topic-page"
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

$twigVars['langs'] = array_merge($twigVars['langs'], $language->forumLangs());
$twigVars['categories'] = $categories;
$twigVars['navigation'] = $navigation;
$twigVars['forumTitle'] = $forumTitle;
if(isset($pagination)) $twigVars['pagination'] = $pagination;
if(isset($catID) && $catID > 0 && isset($topicID) && $topicID == FALSE) {$twigVars['catID'] = $catID; $twigVars['topics'] = $topics; $twigVars['catUrl'] = $category;}
if(isset($topicID) && $topicID > 0 && isset($catID)) {$twigVars['catID'] = $catID; $twigVars['topicID'] = $topicID;}
if(isset($cat)) $twigVars['catData'] = $cat;
if(isset($topicData)) $twigVars['topicData'] = $topicData;

print_r($twig->render('/src/Views/forum.twig', $twigVars));
