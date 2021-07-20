<?PHP

namespace src\Data;

use src\Business\UserCoreService;
use src\Business\SeoService;
use src\Business\CaptchaService;
use src\Data\config\DBConfig;
use src\Entities\User;
use src\Entities\Status;
use src\Entities\Donator;

class UserCoreDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    
    public function __construct()
    {
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
        global $route;
        $this->lang = $route->getLang();
    }

    public function __destruct()
    {
        $this->con = null;
        $this->dbh = null;
    }
    
    public function checkUser($id, $update) // Rename to managePlayerstateRedirections ??
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("SELECT `statusID`, `lastclick` FROM `user` WHERE `id`= :id AND `active`='1' AND `deleted`='0'", array(':id' => $id));

            if(isset($row['statusID']) && !empty($row['statusID']))
            {
                global $route;
                global $security;
                $captchaService = new CaptchaService();
                $userCaptcha = $captchaService->getUserCaptcha();
                
                $denyPrevControllers = array("not_found", "languageSelect.php", "game/captcha.test.php", "game/rest.in.peace.php");
                
                if($row['statusID'] == 8 && $route->getRouteName() != "home")
                { // Banned
                    unset($_SESSION['UID']);
                    $route->headTo('home');
                    exit(0);
                }
                elseif($security->checkCaptcha($userCaptcha->getSecurityTodo(), $userCaptcha->getSecurity()) && $route->getRouteName() != "captcha_test" && $route->getRouteName() != "rest_in_peace")
                {
                    if(!in_array($route->getController(), $denyPrevControllers))
                        $route->setPrevRoute();
                    
                    $route->headTo('captcha_test');
                    exit(0);
                }
                elseif($this->getUserData()->getHealth() <= 0 && $route->getRouteName() != "rest_in_peace" && $route->getRouteName() != "captcha_test")
                {
                    if(!in_array($route->getController(), $denyPrevControllers))
                        $route->setPrevRoute();
                    
                    $route->headTo('rest_in_peace');
                    exit(0);
                }
                else
                {
                    if($update == true)
                    {
                        if(!isset($_SESSION['activeTimeCounter'])) $_SESSION['activeTimeCounter'] = 0;
                        if(($row['lastclick'] + 30) > time())
                            $_SESSION['activeTimeCounter'] += time()-$row['lastclick'];
                        
                        $this->setUserData();
                    }
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    public function getUserData()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT  u.`id`, u.`username`, u.`lastclick`, u.`charType`, b.`profession_".$this->lang."` AS `profession`, u.`statusID`, st.`status_".$this->lang."` AS `status`,
                        u.`donatorID`, u.`honorPoints`, u.`credits`, d.`donator_".$this->lang."` AS `donator`, u.`familyID`, f.`name` AS `family`, f.`bossUID`, f.`underbossUID`,
                        f.`bankmanagerUID`, f.`forummodUID`, u.`stateID`, s.`name` AS `state`, u.`cityID`, u.`rankpoints`, u.`cash`, u.`bank`, u.`health`, u.`luckybox`,
                        u.`cCrimes`, u.`cWeaponTraining`, u.`cGymTraining`, u.`cStealVehicles`, u.`cPimpWhores`, u.`cFamilyRaid`, u.`cFamilyCrimes`, u.`cBombardement`, u.`cTravelTime`,
                        u.`avatar`, (SELECT COUNT(id) FROM `message` WHERE `receiverID`= :uid AND `read`= '0' AND `active`='1' AND `deleted`='0') AS `messagesCount`,
                        (SELECT COUNT(id) FROM `notification` WHERE `userID`= :uid AND `read`= '0') AS `notificationsCount`, u.`weaponTraining`,
                        c.`name` AS `city`, u.`cPimpWhoresFor`, (SELECT `time` FROM `prison` WHERE `userID`= u.`id`) AS `prisonTime`, u.`lrsID_".$this->lang."` AS `lrsID`,
                        u.`lrfsID_".$this->lang."` AS `lrfsID`, u.`lang`, u.`kills`, u.`whoresStreet`, u.`restartDate`, u.`isProtected`,
                        (SELECT SUM(`whores`) FROM `rld_whore` WHERE `userID`=u.`id`) AS `rld_whores`
                FROM `user` AS u
                LEFT JOIN `state` AS s
                ON (u.stateID=s.id)
                LEFT JOIN `city` AS c
                ON (u.cityID=c.id)
                LEFT JOIN `profession` AS b
                ON (u.charType=b.id)
                LEFT JOIN `status` AS st
                ON (u.statusID=st.id)
                LEFT JOIN `donator` AS d
                ON (u.donatorID=d.id)
                LEFT JOIN `family` AS f
                ON (u.familyID=f.id)
                WHERE u.`id` = :uid AND u.`statusID` < '8' AND u.`active`='1' AND u.`deleted`='0'
            ");
            $statement->execute(array(':uid' => $_SESSION['UID']));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $dateFormat = "d-m-Y H:i:s";
                if($this->lang == "en")
                    $dateFormat = "m-d-Y g:i:s A";
                
                if($row['statusID'] < 7 || $row['statusID'] == 8)
                    $className = SeoService::seoUrl($row['status']);
                else
                    $className = SeoService::seoUrl($row['donator']);
                
                $userObj = new User();
                $userObj->setId($_SESSION['UID']);
                $userObj->setUsername($row['username']);
                $userObj->setUsernameClassName($className);
                $userObj->setLastclick($row['lastclick']);
                $userObj->setLang($row['lang']);                
                $userObj->setCharType($row['charType']);
                $userObj->setProfession($row['profession']);
                $userObj->setStatus($row['status']);
                $userObj->setStatusID($row['statusID']);
                $userObj->setDonator($row['donator']);
                $userObj->setDonatorID($row['donatorID']);
                if($row['familyID'] > 0)
                {
                    $userObj->setFamily($row['family']);
                    $userObj->setFamilyID($row['familyID']);
                }
                else
                {
                    $userObj->setFamily("Geen");
                    if($this->lang == 'en') $userObj->setFamily("None");
                    $userObj->setFamilyID($row['familyID']);
                }
                $userObj->setState($row['state']);
                $userObj->setStateID($row['stateID']);
                $userObj->setCity($row['city']);
                $userObj->setCityID($row['cityID']);
                $userObj->setHonorPoints($row['honorPoints']);
                $userObj->setCredits($row['credits']);
                $userObj->setKills($row['kills']);
                $userObj->setTotalWhores($row['whoresStreet'] + $row['rld_whores']);
                $userObj->setIsProtected(false);
                if($row['isProtected'] == 1 && strtotime($row['restartDate']) > strtotime(date('Y-m-d H:i:s', strtotime("-3 days"))))
                {
                    $userObj->setIsProtected(date($dateFormat, strtotime($row['restartDate'])+(60*60*24*3)));
                }
                $cappedRankpoints = UserCoreService::getCappedRankpoints(
                    $row['rankpoints'], $userObj->getKills(), $userObj->getHonorPoints(), $userObj->getTotalWhores(), $userObj->getIsProtected()
                );
                $userObj->setRankpoints($cappedRankpoints);
                $rankInfo = UserCoreService::getRankInfoByRankpoints($userObj->getRankpoints());
                $userObj->setRankID($rankInfo['rankID']);
                $userObj->setRankname($rankInfo['rank']);
                $userObj->setRankpercent($rankInfo['procenten']);
                $userObj->setRankpercentBar(array('rankpercent' => $rankInfo['procenten'], 'class' => "bg-info"));
                $userObj->setWeaponTraining($row['weaponTraining']);
                $userObj->setCash($row['cash']);
                $userObj->setBank($row['bank']);
                $userObj->setMoneyRank(UserCoreService::getMoneyRank($row['cash']+$row['bank']));
                $userObj->setHealth($row['health']);
                $userObj->setHealthBar(array('health' => $row['health'], 'class' => "bg-success"));
                $userObj->setLuckybox($row['luckybox']);
                $userObj->setLastReadShoutboxID($row['lrsID']);
                $userObj->setLastReadFamilyShoutboxID($row['lrfsID']);
                $userObj->setCCrimes($row['cCrimes']);
                $userObj->setCWeaponTraining($row['cWeaponTraining']);
                $userObj->setCGymTraining($row['cGymTraining']);
                $userObj->setCStealVehicles($row['cStealVehicles']);
                $userObj->setCPimpWhores($row['cPimpWhores']);
                $userObj->setCFamilyRaid($row['cFamilyRaid']);
                $userObj->setCFamilyCrimes($row['cFamilyCrimes']);
                $userObj->setCBombardement($row['cBombardement']);
                $userObj->setCTravelTime($row['cTravelTime']);
                $userObj->setCPrisonTime($row['prisonTime']);
                $userObj->setCPimpWhoresFor($row['cPimpWhoresFor']);
                $userObj->setAvatar(false);
                if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['id'].'/uploads/'.$row['avatar'])) $userObj->setAvatar($row['avatar']);
                $userObj->setMessagesCount($row['messagesCount']);
                $userObj->setNotificationsCount($row['notificationsCount']);
                $userObj->setTraveling(false);
                if($row['cTravelTime'] >= time()) $userObj->setTraveling(true);
                $userObj->setInPrison(false);
                if($row['prisonTime'] >= time()) $userObj->setInPrison(true);
                // THESE ARE NOT YET USED EVERYWHERE TO DO
                if($row['bossUID'] == $_SESSION['UID']) $userObj->setFamilyBoss(true);
                if($row['underbossUID'] == $_SESSION['UID']) $userObj->setFamilyUnderboss(true);
                if($row['bankmanagerUID'] == $_SESSION['UID']) $userObj->setFamilyBankmanager(true);
                if($row['forummodUID'] == $_SESSION['UID']) $userObj->setFamilyForummod(true);
                // /THESE ARE NOT YET USED EVERYWHERE TO DO
                
                return $userObj;
            }
        }
    }

    public function setUserData()
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                UPDATE `user` SET `lastclick`= :lclick, `activeTime`=`activeTime`+ :atime WHERE `id`= :uid AND `active`='1' AND `deleted`='0';
                UPDATE `user` SET `lang`= :lang WHERE `id`= :uid AND `lang`!= :lang AND `active`='1' AND `deleted`='0'                
            ");
            $statement->execute(array(
                ':lclick' => time(), ':atime' => $_SESSION['activeTimeCounter'], ':uid' => $_SESSION['UID'],
                ':lang' => $this->lang
            ));
            $_SESSION['activeTimeCounter'] = 0;
        }
    }
    
    public function getPrisonersCount()
    {
        $statement = $this->dbh->prepare("SELECT COUNT(`id`) AS `inPrison` FROM `prison` WHERE `time` > :time ");
        $statement->execute(array(':time' => time()));
        return $statement->fetch()['inPrison'];
    }
    
    public function getOnlinePlayers()
    {
        $statement = $this->dbh->prepare("SELECT COUNT(`id`) AS `online` FROM `user` WHERE `lastclick`> :timePast  AND `active`='1' AND `deleted`='0'");
        $statement->execute(array(':timePast' => (time() - 360)));
        $row = $statement->fetch();
        $return = isset($row['online']) && $row['online'] >= 0 ? $row['online'] : 0;
        return $return;
    }
    
    public function getStatusAndDonatorColors()
    {
        $sRows = $this->con->getData("SELECT `status_".$this->lang."` AS `status`, `colorCode` FROM `status` WHERE `active`='1' AND (`deleted`='0' OR `deleted`='-1')");
        $statusList = array();
        foreach($sRows AS $s)
        {
            $status = new Status();
            $status->setStatus($s['status']);
            $status->setColorCode($s['colorCode']);
            
            array_push($statusList, $status);
        }
        
        $dRows = $this->con->getData("SELECT `donator_".$this->lang."` AS `donator`, `colorCode` FROM `donator` WHERE `active`='1' AND `deleted`='0'");
        $donatorList = array();
        foreach($dRows AS $d)
        {
            $donator = new Donator();
            $donator->setDonator($d['donator']);
            $donator->setColorCode($d['colorCode']);
            
            array_push($donatorList, $donator);
        }
        
        return array('statusColors' => $statusList, 'donatorColors' => $donatorList);
    }
    
    public function getUsersCount()
    { // Exact copy of UserDAO->getRecordsCount() | FOR PAGES WHERE THE GIANT UserService & DAO ARE EXCLUDED
        $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `user` WHERE `active` = '1' AND `deleted` = '0' AND `statusID` < 8");
        $statement->execute();
        $row = $statement->fetch();
        $return = isset($row['total']) && $row['total'] > 0 ? $row['total'] : 0;
        return $return;
    }
}
