<?PHP

use src\Business\UserService;
use src\Business\HelpsystemService;
use src\Business\MessageService;
use src\Business\NotificationService;
use src\Business\GroundService;
use src\Business\PossessionService;
use src\Business\GarageService;
use src\Business\DonatorService;
use src\Business\CMSService;

require_once __DIR__ . '/.inc.head.ajax.php';

$allowedTabs = array(
    "help", "settings", "luckybox", "friends", "messages", "sendmessage", "notifications",
    "forum.new.topic", "forum.new.reaction", "forum.new.quoted.reaction", "forum.edit.reaction",
    "forum.edit.topic", "market.new.item", "ground", "possession.manage", "vehicle.tune",
    "more.friends", "donate"
);
$tab = "help"; // Standard tab
$content = ""; // Init

require_once __DIR__ . '/.inc.foot.ajax.php';

if(isset($_POST['tab']) && in_array($_POST['tab'],$allowedTabs))
{
    $tab = $security->xssEscape($_POST['tab']);
    if($tab == "help")
    {
        $prevRoute = isset($_SESSION['PREV_ROUTE']) ? $_SESSION['PREV_ROUTE'] : "not_found";
        $routeName = $route->getRouteNameByRoute($prevRoute);
        if(isset($routeName) && strpos($routeName, '-do'))
            $routeName = preg_replace('/-do$/', '', $routeName);
        
        if(isset($routeName) && $routeName != 'family-page' && strpos($routeName, '-page'))
            $routeName = preg_replace('/-page$/', '', $routeName);
        
        if(in_array($routeName, array('in_prison', 'in_prison_raw_paging')))
            $routeName = 'prison';
        elseif($routeName == 'profile-pimp-now')
            $routeName = 'profile';
        elseif($routeName == 'travel-vehicle-id')
            $routeName = 'travel-vehicle';
        elseif($routeName == 'murder-user')
            $routeName = 'murder';
        elseif($routeName == 'smuggling-profit-index-unit')
            $routeName = 'smuggling-profit-index';
        elseif($routeName == 'garage-shop-vehicle-raw')
            $routeName = 'garage-shop-vehicle';
        
        if(strlen($routeName) > 0)
        {
            $helpsystem = new HelpsystemService();
            $content = $helpsystem->getContentByRouteName($routeName);
            if(strlen($content) < 1)
            {
                $content = $langs['NO_CONTENT_YET'];
            }
        }
        $twigVars['prevRoute'] = $prevRoute;
        $string = str_replace(' ', '_', $routeName);
        $string = str_replace('-', '_', $string);
        if(isset($langs[strtoupper($string)]))
        {
            $twigVars['prevPagename'] = $langs[strtoupper(str_replace('-', '_', $routeName))];
        }
        else
        {
            $twigVars['prevPagename'] = ucfirst(str_replace('-', ' ', $routeName));
        }
        $twigVars['helpContent'] = $content;
    }
    elseif($tab == "settings")
    {
        $userService = new UserService();
        $twigVars['profile'] = $userService->getUserProfile($userData->getUsername())->getProfile();
        $twigVars['pidActive'] = $userService->isPrivateIDActive();
        $langs = array_merge($langs, $language->settingsLangs());
    }
    elseif($tab == "luckybox")
    {
        $userService = new UserService();
        $twigVars['luckybox'] = $userData->getLuckybox();
        $twigVars['chanceList'] = $userService->getLuckyboxChanceList();
        $langs = array_merge($langs, $language->luckyboxLangs());
    }
    elseif($tab == "friends")
    {
        $userService = new UserService();
        $twigVars['friendsBlockList'] = $userService->getFriendsBlock();
        $twigVars['userData'] = $userData;
        if(isset($_POST['username'])) $twigVars['inviteFriend'] = $security->xssEscape($_POST['username']);
        $langs = array_merge($langs, $language->friendsBlockLangs());
    }
    elseif($tab == "messages")
    {
        $msg = new MessageService();
        $twigVars['latestMessages'] = $msg->getLatestMessages();
        $twigVars['lastMessage'] = $msg->getLastMessage();
        $langs = array_merge($langs, $language->messagesLangs());
    }
    elseif($tab == "sendmessage")
    {
        $twigVars['receiver'] = $security->xssEscape($_POST['receiver']);
        $langs = array_merge($langs, $language->messagesLangs());
    }
    elseif($tab == "notifications")
    {
        $notification = new NotificationService();
        $twigVars['notifications'] = $notification->getLatestNotifications();
        $notification->setReadNotifications();
        $langs = array_merge($langs, $language->notificationsLangs());
    }
    elseif($tab == "forum.new.topic")
    {
        $twigVars['category'] = $security->xssEscape($_POST['category']);
        $langs = array_merge($langs, $language->forumLangs());
    }
    elseif($tab == "forum.new.reaction")
    {
        $twigVars['topicID'] = (int)round($_POST['topicId']);
        $langs = array_merge($langs, $language->forumLangs());
    }
    elseif($tab == "forum.new.quoted.reaction")
    {
        $twigVars['topicID'] = (int)round($_POST['topicId']);
        $twigVars['quoteContent'] = $security->xssEscapeAndHtml(html_entity_decode($_POST['quoteContent']));
        $langs = array_merge($langs, $language->forumLangs());
    }
    elseif($tab == "forum.edit.reaction")
    {
        $twigVars['topicID'] = (int)round($_POST['topicId']);
        $twigVars['reactionID'] = (int)round($_POST['reactionId']);
        $twigVars['reactionContent'] = $security->xssEscapeAndHtml(html_entity_decode($_POST['reactionContent']));
        $langs = array_merge($langs, $language->forumLangs());
    }
    elseif($tab == "forum.edit.topic")
    {
        $twigVars['topicID'] = (int)round($_POST['topicId']);
        $twigVars['topicTitle'] = $security->xssEscape(html_entity_decode($_POST['topicTitle']));
        $twigVars['topicContent'] = $security->xssEscapeAndHtml(html_entity_decode($_POST['topicContent']));
        $langs = array_merge($langs, $language->forumLangs());
    }
    elseif($tab == "market.new.item")
    {
        $twigVars['category'] = $security->xssEscape($_POST['category']);
        $twigVars['lang'] = $lang;
        $langs = array_merge($langs, $language->marketLangs());
        $langs['PLACE_OR_REQUEST_X_ON_MARKET'] = $route->replaceMessagePart(strtolower($twigVars['category']), $langs['PLACE_OR_REQUEST_X_ON_MARKET'], '/{typeName}/');
    }
    elseif($tab == "ground" && ($_POST['gId'] > 0 && $_POST['gId'] <= 492) && ($_POST['stateId'] > 0 && $_POST['stateId'] <= 6))
    {
        $userService = new UserService();
        $stateID = (int)round($_POST['stateId']);
        $ground = new GroundService($stateID);
        $twigVars['ground'] = $ground->getGroundDataByStateIdAndGroundID($stateID, (int)round($_POST['gId']));
        $twigVars['statusPage'] = $userService->getStatusPageInfo();
        $langs = array_merge($langs, $language->groundLangs());
        if(empty($twigVars['ground'])) exit(0); //Not a valid ground obj, exit script.
    }
    elseif($tab == "possession.manage" && (
        (!isset($_POST['id']) && !isset($_POST['transfer'])) || (((isset($_POST['id']) && $_POST['id'] >= 1) || (isset($_POST['transfer']) && $_POST['transfer'])) >= 1 &&
        ((isset($_POST['id']) && $_POST['id'] <= 213) || (isset($_POST['transfer']) && $_POST['transfer']) <= 213))
    ))
    {
        $userService = new UserService();
        $possession = new PossessionService();
        if(isset($_POST['transfer']))
        {
            $twigVars['transferedPossessions'] = $possession->getTransferedPossessions();
        }
        else
        {
            $twigVars['possessions'] = $possession->getUserPossessionsManagement();
            $twigVars['productionCosts'] = $possession->bfProductionCosts;
            $twigVars['friends'] = $userService->getFriendsList();
            if(isset($_POST['id'])) $twigVars['id'] = (int)round($_POST['id']);
        }
        $langs = array_merge($langs, $language->possessionsLangs());
    }
    elseif($tab == "vehicle.tune" && isset($_POST['id']))
    {
        $garage = new GarageService();
        $twigVars['vehicles'] = $garage->getAllVehiclesInGarageByState($userData->getStateID());
        if(isset($_POST['id'])) $twigVars['id'] = (int)round($_POST['id']);
        $twigVars['tuneShop'] = $garage->tuneShop;
        $langs = array_merge($langs, $language->garageLangs());
    }
    elseif($tab == "more.friends" && isset($_POST['username']))
    {
        $userService = new UserService();
        $username = $security->xssEscape($_POST['username']);
        $twigVars['username'] = $username;
        $twigVars['friends'] = $userService->getFriendsBlock($username);
    }
    elseif($tab == "donate")
    {
        $cms = new CMSService();
        $donatePage = $cms->getCMSById(11, $lang);
        $twigVars['donatePage'] = $donatePage;
        $donator = new DonatorService();
        $dData = $donator->getDonationData();
        $langs = array_merge($langs, $language->donationShopLangs());
        $creditsLimit = 5000;
        if(isset($dData['cr']))
        {
            $creditsLimit -= (int)$dData['cr'];
            $creditsLimit = $creditsLimit > 0 ? $creditsLimit : 0;
            $langs['LIMIT_RESET'] = $route->replaceMessagePart($dData['tDate'], $langs['LIMIT_RESET'], '/{date}/');
            $twigVars['dData'] = $dData;
        }
        $langs['CAN_RECEIVE'] = $route->replaceMessagePart(number_format($creditsLimit, 0, "", ","), $langs['CAN_RECEIVE'], '/{credits}/');
    }
}
$twigVars['langs'] = $langs; // Extend base langs

print_r($twig->render('/src/Views/game/Ajax/tabs/'.$tab.'.twig', $twigVars));
