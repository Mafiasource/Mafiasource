<?PHP

namespace src\Data;

use src\Business\UserCoreService;
use src\Business\MessageService;
use src\Business\SeoService;
use src\Data\config\DBConfig;
use src\Data\PossessionDAO;
use src\Entities\Family;
use src\Entities\User;
use src\Entities\FamilyDonationLog;
use src\Entities\FamilyBankLog;
use src\Entities\FamilyAlliance;

class FamilyDAO extends DBConfig
{
    protected $con = "";
    private $dbh = "";
    private $lang = "en";
    private $dateFormat = "%d-%m-%Y %H:%i:%s";
    private $phpDateFormat = "d-m-Y H:i:s";
    
    public function __construct()
    {
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
        global $route;
        $this->lang = $route->getLang();
        if($this->lang == 'en') $this->dateFormat = "%m-%d-%Y %r";
        if($this->lang == "en") $this->phpDateFormat = "m-d-Y g:i:s A";
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['UID']))
        {
            global $route;
            global $userData;
            
            switch($route->getRouteName())
            {
                case 'family-bank-manage':
                case 'family-bank-manage-page':
                    $row = $this->con->getDataSR("SELECT COUNT(`id`) AS `total` FROM `family_bank_log` WHERE `familyID`= :fid LIMIT 1", array(':fid' => $userData->getFamilyID()));
                    break;
                default:
                    $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `family` WHERE `active`='1' AND `deleted`='0' LIMIT 1");
                    $statement->execute();
                    $row = $statement->fetch();
                    break;
            }
            return $row['total'];
        }
    }
    
    public function checkFamilyExists($familyName)
    {
        if(isset($_SESSION['UID']))
        {
            if($this->getFamilyDataByName($familyName) !== FALSE) return TRUE;
            else return FALSE;
        }
        else return FALSE;
    }
    
    public function getFamlist($from, $to)
    {
        if(isset($_SESSION['UID']) && is_int($from) && is_int($to) && $to <= 50)
        {
            $from = (int)round($from);
            $to = (int)round($to);
            $statement = $this->dbh->prepare("
                SELECT `id`, `name`, `icon`, `money`, `vip`, `join`,
                (SELECT COUNT(`id`) FROM `user` WHERE `familyID`=`family`.`id`) AS `totalMembers`,
                (SELECT SUM(`score`) FROM `user` WHERE `familyID`=`family`.`id` AND `statusID` < 8) AS `totalScore`
                FROM `family`
                WHERE `active`='1' AND `deleted`='0'
                ORDER BY `totalScore` DESC, `money` DESC, `totalMembers` DESC
                LIMIT $from, $to
            ");
            $statement->execute();
            $list = array();
            $i = $from;
            foreach($statement AS $row)
            {
                $family = new Family();
                $family->setId($row['id']);
                $family->setName($row['name']);
                $family->setVip(false);
                if($row['vip'] == 1) $family->setVip(true);
                $family->setMoney($row['money']);
                $family->setIcon($row['icon']);
                $family->setTotalMembers($row['totalMembers']);
                $family->setTotalScore($row['totalScore']);
                $family->setJoin(false);
                if($row['join'] == 1) $family->setJoin(true);
                
                array_push($list, $family);
                $i++;
            }
            return $list;
        }
    }
    
    public function createFamily($familyName, $seedMoney)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("INSERT INTO `family` (`name`, `bossUID`, `money`, `startDate`) VALUES (:fname, :uid, :money, :startDate)");
            $statement->execute(array(':fname' => $familyName, ':uid' => $_SESSION['UID'], ':money' => $seedMoney, ':startDate' => date('Y-m-d H:i:s')));
            
            $familyID = $this->dbh->lastInsertId();
            
            $statement = $this->dbh->prepare("UPDATE `family` SET `position`= :id WHERE `id`= :id AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':id' => $familyID));
                        
            $statement = $this->dbh->prepare("UPDATE `user` SET `cash`=`cash`- :money, `familyID`= :fid WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':money' => $seedMoney, ':fid' => $familyID, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function getFamilyDataByName($name)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id`, `name`, `money`, `vip`, `image`,`icon`, `join`, `leaveCosts` FROM `family` WHERE `name`= :name AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':name' => $name));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $family = new Family();
                $family->setId($row['id']);
                $family->setName($row['name']);
                $family->setVip(false);
                if($row['vip'] == 1) $family->setVip(true);
                $family->setMoney($row['money']);
                $family->setImage(false);
                if(file_exists(DOC_ROOT . '/web/public/images/families/'.$row['id'].'/uploads/'.$row['image'])) $family->setImage($row['image']);
                $family->setIcon(false);
                if(file_exists(DOC_ROOT . '/web/public/images/families/'.$row['id'].'/uploads/'.$row['icon'])) $family->setIcon($row['icon']);
                $family->setJoin(false);
                if($row['join'] == 1) $family->setJoin(true);
                $family->setLeaveCosts($row['leaveCosts']);
                
                return $family;
            }
            else return FALSE;
        }
        else return FALSE;
    }
    
    public function userJoinedFamily($famID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `family_join_invite` WHERE `userID`= :uid AND `familyID`= :fid AND `type`='Join' AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':fid' => $famID));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return TRUE;
            else
                return FALSE;
        }
    }
    
    public function userInvitedToFamily($famID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `family_join_invite` WHERE `userID`= :uid AND `familyID`= :fid AND `type`='Invite' AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':uid' => $_SESSION['UID'], ':fid' => $famID));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return TRUE;
            else
                return FALSE;
        }
    }
    
    public function getFamilyInvitations()
    {
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData("
                SELECT f.`id`, f.`name`, f.`icon`, f.`money`, f.`vip`,
                (SELECT COUNT(`id`) FROM `user` WHERE `familyID`=f.`id`) AS `totalMembers`,
                (SELECT SUM(`score`) FROM `user` WHERE `familyID`=f.`id` AND `statusID` < 8) AS `totalScore`
                FROM `family` AS f
                LEFT JOIN `family_join_invite` AS fji
                ON (f.`id`=fji.`familyID`)
                WHERE f.`active`='1' AND f.`deleted`='0' AND fji.`userID`= :uid AND
                    fji.`type`='Invite' AND fji.`active`='1' AND fji.`deleted`='0'
                ORDER BY `totalScore` DESC, f.`money` DESC, `totalMembers` DESC
            ", array(':uid' => $_SESSION['UID']));
            
            $list = array();
            foreach($rows AS $row)
            {
                $family = new Family();
                $family->setId($row['id']);
                $family->setName($row['name']);
                $family->setVip(false);
                if($row['vip'] == 1) $family->setVip(true);
                $family->setMoney($row['money']);
                $family->setIcon(false);
                if(file_exists(DOC_ROOT . '/web/public/images/families/'.$row['id'].'/uploads/'.$row['icon'])) $family->setIcon($row['icon']);
                $family->setTotalMembers($row['totalMembers']);
                $family->setTotalScore($row['totalScore']);
                
                array_push($list, $family);
            }
            return $list;
        }
    }
    
    public function joinFamily($famID)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("INSERT INTO `family_join_invite` (`type`, `userID`, `familyID`) VALUES (:type, :uid, :fid)");
            $statement->execute(array(':type' => "Join", ':uid' => $_SESSION['UID'], ':fid' => $famID));
        }
    }
    
    public function leaveFamily($famID, $costs)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `family` SET `money`=`money`+ :c WHERE `id`= :fid AND `leaveCosts`= :c AND `active`='1' AND `deleted`='0' LIMIT 1;
                UPDATE `user` SET `cash`=`cash`- :c WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(
                ':c' => $costs, ':fid' => $famID,
                ':uid' => $_SESSION['UID']
            ));
            $this->removeFamilyMember($famID, $_SESSION['UID'], true);
        }
    }
    
    public function inviteMemberToFamily($fid, $uid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("INSERT INTO `family_join_invite` (`type`, `userID`, `familyID`) VALUES (:type, :uid, :fid)");
            $statement->execute(array(':type' => "Invite", ':uid' => $uid, ':fid' => $fid));
        }
    }
    
    public function acceptFamilyInvitation($fid, $uid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `user` SET `familyID`= :fid WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':fid' => $fid, ':uid' => $uid));
            
            $statement = $this->dbh->prepare("DELETE FROM `family_join_invite` WHERE `userID`= :uid AND `familyID`= :fid");
            $statement->execute(array(':uid' => $uid, ':fid' => $fid));
        }
    }
    
    public function denyFamilyInvitation($fid, $uid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("DELETE FROM `family_join_invite` WHERE `userID`= :uid AND `familyID`= :fid");
            $statement->execute(array(':uid' => $uid, ':fid' => $fid));
        }
    }
    
    public function searchFamiliesByKeyword($keyword)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT `id`, `name`, `icon`, `money`, `vip`, `join`,
                (SELECT COUNT(`id`) FROM `user` WHERE `familyID`=`family`.`id`) AS `totalMembers`,
                (SELECT SUM(`score`) FROM `user` WHERE `familyID`=`family`.`id` AND `statusID` < 8) AS `totalScore`
                FROM `family`
                WHERE `active`='1' AND `deleted`='0' AND `name` LIKE :keyword
                ORDER BY `totalScore` DESC, `money` DESC
            ");
            $statement->execute(array(':keyword' => "%".$keyword."%"));
            $list = array();
            $i = 1;
            foreach($statement AS $row)
            {
                $family = new Family();
                $family->setId($row['id']);
                $family->setName($row['name']);
                $family->setVip(false);
                if($row['vip'] == 1) $family->setVip(true);
                $family->setMoney($row['money']);
                $family->setIcon(false);
                if(file_exists(DOC_ROOT . '/web/public/images/families/'.$row['id'].'/uploads/'.$row['icon'])) $family->setIcon($row['icon']);
                $family->setTotalMembers($row['totalMembers']);
                $family->setTotalScore($row['totalScore']);
                $family->setJoin(false);
                if($row['join'] == 1) $family->setJoin(true);
                
                array_push($list, $family);
                $i++;
            }
            if(!empty($list))
            {
                return $list;
            }
            else return FALSE;
        }
        else return FALSE;
    }
    
    public function getFamilyPageDataByName($name)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT f.`id`, f.`name`, f.`bossUID`, b.`username` AS `boss`, f.`underbossUID`, ub.`username` AS `underboss`,
                    f.`bankmanagerUID`, bb.`username` AS `bankmanager`, f.`forummodUID`, fm.`username` AS `forummod`, f.`money`,
                    DATE_FORMAT( f.`startDate`, '".$this->dateFormat."' ) AS `startDate`, f.`join`, f.`leaveCosts`, f.`profile`,
                    f.`vip`, (SELECT COUNT(`id`) FROM `user` WHERE `familyID`=f.`id`) AS `totalMembers`, f.`image`, f.`icon`,
                    (SELECT SUM(`score`) FROM `user` WHERE `familyID`=f.`id` AND `statusID`<'8') AS `totalScore`,
                    (SELECT SUM(`rankpoints`) FROM `user` WHERE `familyID`=f.`id` AND `statusID`<'8') AS `totalRank`,
                    f.`bulletFactory`, f.`brothel`
                FROM `family` AS f
                LEFT JOIN `user` AS b
                ON (f.`bossUID`=b.`id`)
                LEFT JOIN `user` AS ub
                ON (f.`underbossUID`=ub.`id`)
                LEFT JOIN `user` AS bb
                ON (f.`bankmanagerUID`=bb.`id`)
                LEFT JOIN `user` AS fm
                ON (f.`forummodUID`=fm.`id`)
                WHERE f.`name`= :name AND f.`active`='1' AND f.`deleted`='0' LIMIT 1
            ");
            $statement->execute(array(':name' => $name));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
            {
                $family = new Family();
                $family->setId($row['id']);
                $family->setName($row['name']);
                $family->setBoss($row['boss']);
                $family->setUnderboss($row['underboss']);
                $family->setBankmanager($row['bankmanager']);
                $family->setForummod($row['forummod']);
                $family->setVip(false);
                if($row['vip'] == 1) $family->setVip(true);
                $family->setMoney($row['money']);
                $family->setImage(false);
                if(file_exists(DOC_ROOT . '/web/public/images/families/'.$row['id'].'/uploads/'.$row['image'])) $family->setImage($row['image']);
                $family->setIcon(false);
                if(file_exists(DOC_ROOT . '/web/public/images/families/'.$row['id'].'/uploads/'.$row['icon'])) $family->setIcon($row['icon']);
                $family->setStartDate($row['startDate']);
                $family->setJoin(false);
                if($row['join'] == 1) $family->setJoin(true);
                $family->setLeaveCosts($row['leaveCosts']);
                $family->setProfile(stripslashes($row['profile']));
                $family->setTotalMembers($row['totalMembers']);
                $family->setTotalScore($row['totalScore']);
                $averageRankInfo = UserCoreService::getRankInfoByRankpoints($row['totalRank'] / $row['totalMembers']);
                $family->setAverageRank($averageRankInfo['rank']);
                $family->setBulletFactory($row['bulletFactory']);
                $family->setBrothel($row['brothel']);
                
                return $family;
            }
            else return FALSE;
        }
        else return FALSE;
    }
    
    public function getFamilyMembersByFamilyId($id)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT u.`id`, u.`username`, u.`avatar`, u.`rankpoints`, u.`cash`, u.`bank`, u.`familyID`, f.`name` AS `familyName`, u.`health`, u.`honorPoints`, u.`kills`,
                    u.`statusID`, u.`donatorID`, st.`status_".$this->lang."` AS `status`, d.`donator_".$this->lang."` AS `donator`, u.`lastclick`, u.`whoresStreet`,
                    (SELECT SUM(`whores`) FROM `rld_whore` WHERE `userID`=u.`id`) AS `rld_whores`, u.`restartDate`, u.`isProtected`
                FROM `user` AS u
                LEFT JOIN `family` AS f
                ON (u.`familyID`=f.`id`)
                LEFT JOIN `status` AS st
                ON (u.statusID=st.id)
                LEFT JOIN `donator` AS d
                ON (u.donatorID=d.id)
                WHERE u.`deleted`='0' AND u.`active`='1' AND u.familyID= :fid
                ORDER BY u.`score` DESC, u.`honorPoints` DESC, u.`whoresStreet` DESC, u.`rankpoints` DESC, u.`power` DESC, u.`cardio` DESC, u.`crimesLv` DESC, u.`vehiclesLv` DESC,
                    u.`pimpLv` DESC, u.`smugglingLv` DESC, u.`id` ASC
            ");
            $statement->execute(array(':fid' => $id));
            $list = array();
            $i = 0;
            foreach($statement AS $row)
            {
                if($row['statusID'] < 7 || $row['statusID'] == 8)
                {
                    $className = SeoService::seoUrl($row['status']);
                }
                else
                {
                    $className = SeoService::seoUrl($row['donator']);
                }
                $userObj = new User();
                $userObj->setId($row['id']);
                $userObj->setUsername($row['username']);
                $userObj->setUsernameClassName($className);
                $userObj->setLastclick($row['lastclick']);
                if($row['familyID'] > 0)
                {
                    $userObj->setFamily($row['familyName']);
                    $userObj->setFamilyID($row['familyID']);
                }
                else
                {
                    $userObj->setFamily("Geen");
                    if($this->lang == 'en') $userObj->setFamily("None");
                    $userObj->setFamilyID($row['familyID']);
                }
                $userObj->setHonorPoints($row['honorPoints']);
                $userObj->setWhoresStreet($row['whoresStreet']);
                $userObj->setKills($row['kills']);
                $userObj->setTotalWhores($row['whoresStreet'] + $row['rld_whores']);
                $userObj->setIsProtected(false);
                if($row['isProtected'] == 1 && strtotime($row['restartDate']) > strtotime(date('Y-m-d H:i:s', strtotime("-3 days"))))
                {
                    $userObj->setIsProtected(date($this->phpDateFormat, strtotime($row['restartDate'])+(60*60*24*3)));
                }
                $cappedRankpoints = UserCoreService::getCappedRankpoints(
                    $row['rankpoints'], $userObj->getKills(), $userObj->getHonorPoints(), $userObj->getTotalWhores(), $userObj->getIsProtected()
                );
                $userObj->setRankpoints($cappedRankpoints);
                $rankInfo = UserCoreService::getRankInfoByRankpoints($userObj->getRankpoints());
                $userObj->setRankID($rankInfo['rankID']);
                $userObj->setRankname($rankInfo['rank']);
                $userObj->setMoneyRank(UserCoreService::getMoneyRank($row['cash']+$row['bank']));
                $userObj->setHealth($row['health']); //Raw variabele met HTML in de string
                $userObj->setHealthBar(array('health' => $row['health'], 'class' => "bg-success"));
                $userObj->setAvatar(FALSE);
                if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['id'].'/uploads/'.$row['avatar'])) $userObj->setAvatar($row['avatar']);
                $userObj->setScorePosition($i+1);
                
                array_push($list, $userObj);
                $i++;
            }
            return $list;
        }
    }
    
    public function donateToFamily($fid, $amount)
    {
        if(isset($_SESSION['UID']))
        {
            $donatedAmount = $amount*0.95;
            $statement = $this->dbh->prepare("UPDATE `family` SET `money`=`money`+ :amount WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':amount' => $donatedAmount, ':fid' => $fid));
            
            $statement = $this->dbh->prepare("UPDATE `user` SET `bank`=`bank`- :amount WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':amount' => $amount, ':uid' => $_SESSION['UID']));
            
            $check = $this->dbh->prepare("SELECT `id` FROM `family_donation_log` WHERE `familyID`= :fid AND `userID`= :uid LIMIT 1");
            $check->execute(array(':fid' => $fid, ':uid' => $_SESSION['UID']));
            $checked = $check->fetch();
            if(isset($checked['id']) && $checked['id'] > 0)
            {
                $statement = $this->dbh->prepare("
                    UPDATE `family_donation_log` SET `amount`=`amount`+ :amount, `amountAll`=`amountAll`+ :amount, lastDonation= NOW() WHERE `familyID`= :fid AND `userID`= :uid LIMIT 1
                ");
                $statement->execute(array(':amount' => $donatedAmount, ':fid' => $fid, ':uid' => $_SESSION['UID']));
            }
            else
            {
                $statement = $this->dbh->prepare("
                    INSERT INTO `family_donation_log` (`familyID`, `userID`, `amount`, `amountAll`, `lastDonation`) VALUES (:fid, :uid, :amount, :amount, NOW())
                ");
                $statement->execute(array(':fid' => $fid, ':uid' => $_SESSION['UID'], ':amount' => $donatedAmount));
            }
        }
    }
    
    public function getFamilyDonationsByFamilyId($id)
    {
        if(isset($_SESSION['UID']) && $id > 0)
        {
            $statement = $this->dbh->prepare("
                SELECT u.`id`, u.`username`, fd.`amountAll`, DATE_FORMAT( fd.`lastDonation`, '".$this->dateFormat."' ) AS `lastDonationF`,
                    (SELECT SUM(`amount`) FROM `family_bank_log` WHERE `familyID`= :fid AND `receiverID`=u.`id`) AS `received`
                FROM `family_donation_log` AS fd
                LEFT JOIN `user` AS u
                ON (fd.`userID`=u.`id`)
                WHERE fd.`familyID`= :fid
                ORDER BY fd.`amountAll` DESC, fd.`lastDonation` DESC, fd.`id` ASC
            ");
            $statement->execute(array(':fid' => $id));
            $list = array();
            $i = 0;
            while($row = $statement->fetch())
            {
                $fdl = new FamilyDonationLog();
                $fdl->setId($i+1);
                $fdl->setUsername($row['username']);
                $fdl->setAmount($row['amountAll'] - $row['received']);
                $fdl->setAmountAll($row['amountAll']);
                $fdl->setLastDonation($row['lastDonationF']);
                
                array_push($list, $fdl);
                $i++;
            }
            return $list;
        }
    }
    
    public function bankTransferToUser($fid, $receiver, $amount)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `family` SET `money`=`money`- :amount WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':amount' => $amount, ':fid' => $fid));
            
            $statement = $this->dbh->prepare("UPDATE `user` SET `bank`=`bank`+ :amount WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':amount' => $amount, ':uid' => $receiver));
            
            $statement = $this->dbh->prepare("INSERT INTO `family_bank_log` (`familyID`, `senderID`, `receiverID`, `amount`, `date`) VALUES (:fid, :sid, :rid, :amount, NOW())");
            $statement->execute(array(':fid' => $fid, ':sid' => $_SESSION['UID'], ':rid' => $receiver, ':amount' => $amount));
        }
    }
    
    public function getFamilyBankLogsByFamilyId($id, $from, $to)
    {
        if(isset($_SESSION['UID']) && $id > 0)
        {
            $statement = $this->dbh->prepare("
                SELECT u.`username` AS `sender`, u2.`username` AS `receiver`, fb.`amount`, DATE_FORMAT( fb.`date`, '".$this->dateFormat."' ) AS `dateF`
                FROM `family_bank_log` AS fb
                LEFT JOIN `user` AS u
                ON (fb.`senderID`=u.`id`) 
                LEFT JOIN `user` AS u2
                ON (fb.`receiverID`=u2.`id`)
                WHERE fb.`familyID`= :fid
                ORDER BY fb.`date` DESC
                LIMIT $from, $to
            ");
            $statement->execute(array(':fid' => $id));
            $list = array();
            $i = 0;
            while($row = $statement->fetch())
            {
                $fbl = new FamilyBankLog();
                $fbl->setId($i+1);
                $fbl->setSender($row['sender']);
                $fbl->setReceiver($row['receiver']);
                $fbl->setAmount($row['amount']);
                $fbl->setDate($row['dateF']);
                
                array_push($list, $fbl);
                $i++;
            }
            return $list;
        }
    }
    
    public function checkJoinedUser($fid, $uid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `family_join_invite` WHERE `userID`= :uid AND `familyID`= :fid AND `type`='Join' AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':uid' => $uid, ':fid' => $fid));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return TRUE;
            else
                return FALSE;
        }
    }
    
    public function checkInvitedUser($fid, $uid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `family_join_invite` WHERE `userID`= :uid AND `familyID`= :fid AND `type`='Invite' AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':uid' => $uid, ':fid' => $fid));
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                return TRUE;
            else
                return FALSE;
        }
    }
    
    public function getKickList($fid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT u.`username`
                FROM `user` AS u
                LEFT JOIN `family` AS f
                ON (u.`familyID`=f.`id`)
                LEFT JOIN `status` AS st
                ON (u.statusID=st.id)
                LEFT JOIN `donator` AS d
                ON (u.donatorID=d.id)
                WHERE u.`deleted`='0' AND u.`active`='1' AND u.familyID= :fid AND u.`id`!=f.`bossUID` AND u.`id`!=f.`underbossUID` AND u.`id`!=f.`bankmanagerUID`
                ORDER BY u.`score` DESC, u.`honorPoints` DESC, u.`whoresStreet` DESC, u.`rankpoints` DESC, u.`power` DESC, u.`cardio` DESC, u.`crimesLv` DESC, u.`vehiclesLv` DESC,
                    u.`pimpLv` DESC, u.`smugglingLv` DESC, u.`id` ASC
            ");
            $statement->execute(array(':fid' => $fid));
            $list = array();
            $i = 0;
            foreach($statement AS $row)
            {
                $userObj = new User();
                $userObj->setUsername($row['username']);
                $userObj->setScorePosition($i+1);
                
                array_push($list, $userObj);
                $i++;
            }
            return $list;
        }
    }
    
    public function getJoinedMembers($fid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT u.`id`, u.`username`, u.`avatar`, u.`rankpoints`, u.`kills`, u.`honorPoints`, u.`whoresStreet`,
                    u.`statusID`, u.`donatorID`, st.`status_".$this->lang."` AS `status`, d.`donator_".$this->lang."` AS `donator`, u.`lastclick`,
                    (SELECT SUM(`whores`) FROM `rld_whore` WHERE `userID`=u.`id`) AS `rld_whores`, u.`restartDate`, u.`isProtected`
                FROM `user` AS u
                LEFT JOIN `family_join_invite` AS f
                ON (u.`id`=f.`userID`)
                LEFT JOIN `status` AS st
                ON (u.statusID=st.id)
                LEFT JOIN `donator` AS d
                ON (u.donatorID=d.id)
                WHERE u.`deleted`='0' AND u.`active`='1' AND u.familyID='0' AND
                    f.`familyID`= :fid AND f.`type`='Join' AND f.`active`='1' AND f.`deleted`='0'
                ORDER BY u.`score` DESC, u.`honorPoints` DESC, u.`whoresStreet` DESC, u.`rankpoints` DESC, u.`power` DESC, u.`cardio` DESC, u.`crimesLv` DESC, u.`vehiclesLv` DESC,
                    u.`pimpLv` DESC, u.`smugglingLv` DESC, u.`id` ASC
            ");
            $statement->execute(array(':fid' => $fid));
            $list = array();
            $i = 0;
            foreach($statement AS $row)
            {
                if($row['statusID'] < 7 || $row['statusID'] == 8)
                {
                    $className = SeoService::seoUrl($row['status']);
                }
                else
                {
                    $className = SeoService::seoUrl($row['donator']);
                }
                $userObj = new User();
                $userObj->setId($row['id']);
                $userObj->setUsername($row['username']);
                $userObj->setUsernameClassName($className);
                $userObj->setLastclick($row['lastclick']);
                $userObj->setHonorPoints($row['honorPoints']);
                $userObj->setWhoresStreet($row['whoresStreet']);
                $userObj->setKills($row['kills']);
                $userObj->setTotalWhores($row['whoresStreet'] + $row['rld_whores']);
                $userObj->setIsProtected(false);
                if($row['isProtected'] == 1 && strtotime($row['restartDate']) > strtotime(date('Y-m-d H:i:s', strtotime("-3 days"))))
                {
                    $userObj->setIsProtected(date($this->phpDateFormat, strtotime($row['restartDate'])+(60*60*24*3)));
                }
                $cappedRankpoints = UserCoreService::getCappedRankpoints(
                    $row['rankpoints'], $userObj->getKills(), $userObj->getHonorPoints(), $userObj->getTotalWhores(), $userObj->getIsProtected()
                );
                $userObj->setRankpoints($cappedRankpoints);
                $rankInfo = UserCoreService::getRankInfoByRankpoints($userObj->getRankpoints());
                $userObj->setRankID($rankInfo['rankID']);
                $userObj->setRankname($rankInfo['rank']);
                $userObj->setScorePosition($i+1);
                $userObj->setAvatar(FALSE);
                if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['id'].'/uploads/'.$row['avatar'])) $userObj->setAvatar($row['avatar']);
                
                array_push($list, $userObj);
                $i++;
            }
            return $list;
        }
    }
    
    public function getInvitedMembers($fid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("
                SELECT u.`id`, u.`username`, u.`avatar`, u.`rankpoints`, u.`kills`, u.`honorPoints`, u.`whoresStreet`,
                    u.`statusID`, u.`donatorID`, st.`status_".$this->lang."` AS `status`, d.`donator_".$this->lang."` AS `donator`, u.`lastclick`,
                    (SELECT SUM(`whores`) FROM `rld_whore` WHERE `userID`=u.`id`) AS `rld_whores`, u.`restartDate`, u.`isProtected`
                FROM `user` AS u
                LEFT JOIN `family_join_invite` AS f
                ON (u.`id`=f.`userID`)
                LEFT JOIN `status` AS st
                ON (u.statusID=st.id)
                LEFT JOIN `donator` AS d
                ON (u.donatorID=d.id)
                WHERE u.`deleted`='0' AND u.`active`='1' AND u.familyID='0' AND
                    f.`familyID`= :fid AND f.`type`='Invite' AND f.`active`='1' AND f.`deleted`='0'
                ORDER BY u.`score` DESC, u.`honorPoints` DESC, u.`whoresStreet` DESC, u.`rankpoints` DESC, u.`power` DESC, u.`cardio` DESC, u.`crimesLv` DESC, u.`vehiclesLv` DESC,
                    u.`pimpLv` DESC, u.`smugglingLv` DESC, u.`id` ASC
            ");
            $statement->execute(array(':fid' => $fid));
            $list = array();
            $i = 0;
            foreach($statement AS $row)
            {
                if($row['statusID'] < 7 || $row['statusID'] == 8)
                {
                    $className = SeoService::seoUrl($row['status']);
                }
                else
                {
                    $className = SeoService::seoUrl($row['donator']);
                }
                $userObj = new User();
                $userObj->setId($row['id']);
                $userObj->setUsername($row['username']);
                $userObj->setUsernameClassName($className);
                $userObj->setLastclick($row['lastclick']);
                $userObj->setHonorPoints($row['honorPoints']);
                $userObj->setWhoresStreet($row['whoresStreet']);
                $userObj->setKills($row['kills']);
                $userObj->setTotalWhores($row['whoresStreet'] + $row['rld_whores']);
                $userObj->setIsProtected(false);
                if($row['isProtected'] == 1 && strtotime($row['restartDate']) > strtotime(date('Y-m-d H:i:s', strtotime("-3 days"))))
                {
                    $userObj->setIsProtected(date($this->phpDateFormat, strtotime($row['restartDate'])+(60*60*24*3)));
                }
                $cappedRankpoints = UserCoreService::getCappedRankpoints(
                    $row['rankpoints'], $userObj->getKills(), $userObj->getHonorPoints(), $userObj->getTotalWhores(), $userObj->getIsProtected()
                );
                $userObj->setRankpoints($cappedRankpoints);
                $rankInfo = UserCoreService::getRankInfoByRankpoints($userObj->getRankpoints());
                $userObj->setRankID($rankInfo['rankID']);
                $userObj->setRankname($rankInfo['rank']);
                $userObj->setScorePosition($i+1);
                $userObj->setAvatar(FALSE);
                if(file_exists(DOC_ROOT . '/web/public/images/users/'.$row['id'].'/uploads/'.$row['avatar'])) $userObj->setAvatar($row['avatar']);
                
                array_push($list, $userObj);
                $i++;
            }
            return $list;
        }
    }
    
    public function acceptJoinedUser($fid, $uid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `user` SET `familyID`= :fid WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':fid' => $fid, ':uid' => $uid));
            
            $statement = $this->dbh->prepare("DELETE FROM `family_join_invite` WHERE `userID`= :uid AND `familyID`= :fid");
            $statement->execute(array(':uid' => $uid, ':fid' => $fid));
        }
    }
    
    public function denyJoinedUser($fid, $uid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("DELETE FROM `family_join_invite` WHERE `userID`= :uid AND `familyID`= :fid");
            $statement->execute(array(':uid' => $uid, ':fid' => $fid));
        }
    }
    
    public function deleteInvitedUser($fid, $uid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("DELETE FROM `family_join_invite` WHERE `userID`= :uid AND `familyID`= :fid");
            $statement->execute(array(':uid' => $uid, ':fid' => $fid));
        }
    }
    
    public function kickFamilyMember($fid, $uid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->removeFamilyMember($fid, $uid);
        }
    }
    
    private function removeFamilyMember($fid, $uid, $leftMember = false)
    {
        if(isset($_SESSION['UID']))
        {
            global $userData;
            $this->con->setData("
                UPDATE `user` SET `familyID`='0' WHERE `id`= :uid AND `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1;
                UPDATE `family_raid` SET `driverID`=0, `driverReady`=0, `garageID`=0 WHERE `driverID`= :uid AND `familyID`= :fid AND `active`='1' AND `deleted`='1' LIMIT 1;
                UPDATE `family_raid` SET `bombExpertID`=0, `bombExpertReady`=0, `bombType`=0 WHERE `bombExpertID`= :uid AND `familyID`= :fid AND `active`='1' AND `deleted`='1' LIMIT 1;
                UPDATE `family_raid` SET `weaponExpertID`=0, `weaponExpertReady`=0, `weaponType`=0, `bullets`=0 WHERE `weaponExpertID`= :uid AND `familyID`= :fid AND `active`='1' AND
                    `deleted`='1' LIMIT 1;
                DELETE FROM `family_raid` WHERE `leaderID`= :uid AND `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1;
                DELETE FROM `family_donation_log` WHERE `familyID`= :fid AND `userID`= :uid LIMIT 1;
                DELETE FROM `family_bf_donation_log` WHERE `familyID`= :fid AND `userID`= :uid LIMIT 1
            ", array(':fid' => $fid, ':uid' => $uid));
            if($userData->getFamilyBoss() || $leftMember === true)
            {
                $this->con->setData("
                    UPDATE `family` SET `underbossUID`=NULL WHERE `id`= :fid AND `underbossUID`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1;
                    UPDATE `family` SET `bankmanagerUID`=NULL WHERE `id`= :fid AND `bankmanagerUID`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1;
                    UPDATE `family` SET `forummodUID`=NULL WHERE `id`= :fid AND `forummodUID`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
                ", array(':fid' => $fid, ':uid' => $uid));
            }
            $brothelWhores = $this->con->getDataSR("
                SELECT `whores` FROM `family_brothel_whore` WHERE `userID`= :uid AND `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':uid' => $uid, ':fid' => $fid));
            if(isset($brothelWhores['whores']) && $brothelWhores['whores'] > 0)
            {
                $this->con->setData("
                    UPDATE `user` SET `whoresStreet`=`whoresStreet`+'".$brothelWhores['whores']."' WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1;
                    DELETE FROM `family_brothel_whore` WHERE `userID`= :uid AND `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1;
                ");
            }
            $famCrimes = $this->con->getData("
                SELECT `id`, `starterUID`, `participants`, `num_participants`, `stateID` FROM `family_crime` WHERE `familyID`= :fid
            ", array(':fid' => $fid));
            if(count($famCrimes))
            {
                foreach($famCrimes AS $fc)
                {
                    $participants = unserialize($fc['participants']);
                    $crimeID = $fc['id'];
                    if(in_array($uid, $participants))
                    {
                        $k = array_search($uid, $participants);
                        unset($participants[$k]);
                        $this->con->setData("
                            UPDATE `family_crime` SET `participants`= :p WHERE `id`= :fcid AND `active`='1' AND `deleted`='0'
                        ", array(':p' => serialize($participants), ':fcid' => $crimeID));
                        $check = $this->con->getDataSR("
                            SELECT `participants` FROM `family_crime` WHERE `familyID`= :fid AND `id`= :fcid LIMIT 1
                        ", array(':fid' => $fid, ':fcid' => $crimeID));
                        $participantsCheck = unserialize($check['participants']);
                        $x = (array)$participantsCheck;
                        $this->con->setData("UPDATE `family_crime` SET `starterUID`='0' WHERE `id`= :fcid AND `starterUID`= :uid LIMIT 1", array(':fcid' => $crimeID, ':uid' => $uid));
                        if(empty($x))
                            $this->con->setData("DELETE FROM `family_crime` WHERE `id`= :fcid AND `familyID`= :fid LIMIT 1", array(':fcid' => $crimeID, ':fid' => $fid));
                    }
                }
            }
        }
    }
    
    public function changeJoinpolicy($fid, $join)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `family` SET `join`= :join WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':join' => $join, ':fid' => $fid));
        }
    }
    
    public function updateFamilyImage($image, $fid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `family` SET `image`= :image WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':image' => $image, ':fid' => $fid));
        }
    }
    
    public function updateFamilyIcon($icon, $fid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `family` SET `icon`= :image WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':image' => $icon, ':fid' => $fid));
        }
    }
    
    public function updateFamilyProfile($profile, $fid)
    {
        if(isset($_SESSION['UID']))
        {
            $statement = $this->dbh->prepare("UPDATE `family` SET `profile`= :profile WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1");
            $statement->execute(array(':profile' => $profile, ':fid' => $fid));
        }
    }
    
    public function getFamilyMessage($fid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `message` FROM `family` WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':fid' => $fid));
            if(isset($row['message']) && !empty($row['message']))
                return $row['message'];
            else
                return false;
        }
    }
    
    public function updateFamilyMessage($message, $fid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `family` SET `message`= :msg WHERE `id`= :fid AND (`bossUID`= :uid OR `underbossUID`= :uid) AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':msg' => $message, ':fid' => $fid, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function manageFamilyTop($field, $uid, $fid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `family` SET `".$field."`= :pid WHERE `id`= :fid AND `bossUID`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':pid' => $uid, ':fid' => $fid, ':uid' => $_SESSION['UID']));
        }
    }
    public function getAlliances($fid)
    {
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData("
                SELECT  fa.`id`, fa.`requesterFamilyID`, f1.`name` AS `family`, f2.`name` AS `allianceFamily`, f2.`id` AS `alianceFamilyID`, f1.`icon` AS `familyIcon`,
                        f2.`icon` AS `allianceFamilyIcon`, fa.`active`
                FROM `family_alliance` AS fa
                RIGHT JOIN `family` AS f1
                ON (fa.`familyID`=f1.`id`)
                RIGHT JOIN `family` AS f2
                ON (fa.`allianceFamilyID`=f2.`id`)
                WHERE fa.`deleted`='0' AND fa.`familyID`= :fid AND f1.`active`='1' AND f1.`deleted`='0' AND f2.`active`='1' AND f2.`deleted`='0'
                ORDER BY `id` DESC
            ", array(':fid' => $fid));
            
            $list = array();
            foreach($rows AS $row)
            {
                $famAlliance = new FamilyAlliance();
                $famAlliance->setId($row['id']);
                $famAlliance->setFamily($row['family']);
                $famAlliance->setFamilyIcon($row['familyIcon']);
                $famAlliance->setAllianceFamilyID($row['alianceFamilyID']);
                $famAlliance->setAllianceFamily($row['allianceFamily']);
                $famAlliance->setAllianceFamilyIcon($row['allianceFamilyIcon']);
                $famAlliance->setRequesterFamilyID($row['requesterFamilyID']);
                $famAlliance->setAccepted(false);
                if($row['active'] == 1)
                    $famAlliance->setAccepted(true);
                
                array_push($list, $famAlliance);
            }
            return $list;
        }
    }
    
    public function hasAllianceWithFamily($fid, $oid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `family_alliance` WHERE `familyID`= :fid AND `allianceFamilyID`= :oid AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':fid' => $fid, ':oid' => $oid));
            
            if(isset($row['id']) && $row['id'] > 0)
                return true;
            else
                return false;
        }
    }
    
    public function hasAllianceRecordWithFamily($fid, $oid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `id` FROM `family_alliance` WHERE `familyID`= :fid AND `allianceFamilyID`= :oid AND (`active`='1' OR `active`='0') AND `deleted`='0' LIMIT 1
            ", array(':fid' => $fid, ':oid' => $oid));
            
            if(isset($row['id']) && $row['id'] > 0)
                return true;
            else
                return false;
        }
    }
    
    public function requestFamilyAlliance($fid, $oid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                INSERT INTO `family_alliance` (`familyID`, `allianceFamilyID`, `requesterFamilyID`) VALUES (:fid, :oid, :fid);
                INSERT INTO `family_alliance` (`familyID`, `allianceFamilyID`, `requesterFamilyID`) VALUES (:oid, :fid, :fid)
            ", array(':fid' => $fid, ':oid' => $oid));
        }
    }
    
    public function acceptFamilyAlliance($fid, $oid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `family_alliance` SET `active`='1' WHERE `familyID`= :fid AND `allianceFamilyID`= :oid AND `requesterFamilyID`= :oid AND `active`='0' AND `deleted`='0' LIMIT 1;
                UPDATE `family_alliance` SET `active`='1' WHERE `familyID`= :oid AND `allianceFamilyID`= :fid AND `requesterFamilyID`= :oid AND `active`='0' AND `deleted`='0' LIMIT 1
            ", array(':fid' => $fid, ':oid' => $oid));
        }
    }
    
    public function removeFamilyAlliance($fid, $oid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                DELETE FROM `family_alliance` WHERE `familyID`= :fid AND `allianceFamilyID`= :oid AND `deleted`='0' LIMIT 1;
                DELETE FROM `family_alliance` WHERE `familyID`= :oid AND `allianceFamilyID`= :fid AND `deleted`='0' LIMIT 1
            ", array(':fid' => $fid, ':oid' => $oid));
        }
    }
    
    public function getFamilyPageAlliancesById($fid)
    {
        if(isset($_SESSION['UID']))
        {
            $rows = $this->con->getData("
                SELECT  fa.`id`, fa.`requesterFamilyID`, f1.`name` AS `family`, f2.`name` AS `allianceFamily`, f2.`id` AS `alianceFamilyID`, f1.`icon` AS `familyIcon`,
                        f2.`icon` AS `allianceFamilyIcon`, fa.`active`
                FROM `family_alliance` AS fa
                RIGHT JOIN `family` AS f1
                ON (fa.`familyID`=f1.`id`)
                RIGHT JOIN `family` AS f2
                ON (fa.`allianceFamilyID`=f2.`id`)
                WHERE fa.`active`='1' AND fa.`deleted`='0' AND fa.`familyID`= :fid AND f1.`active`='1' AND f1.`deleted`='0' AND f2.`active`='1' AND f2.`deleted`='0'
                ORDER BY `id` ASC
            ", array(':fid' => $fid));
            
            $list = array();
            foreach($rows AS $row)
            {
                $famAlliance = new FamilyAlliance();
                $famAlliance->setId($row['id']);
                $famAlliance->setFamily($row['family']);
                $famAlliance->setFamilyIcon($row['familyIcon']);
                $famAlliance->setAllianceFamilyID($row['alianceFamilyID']);
                $famAlliance->setAllianceFamily($row['allianceFamily']);
                $famAlliance->setAllianceFamilyIcon(false);
                if(file_exists(DOC_ROOT . '/web/public/images/families/'.$row['alianceFamilyID'].'/uploads/'.$row['allianceFamilyIcon']))
                    $famAlliance->setAllianceFamilyIcon($row['allianceFamilyIcon']);
                
                $famAlliance->setRequesterFamilyID($row['requesterFamilyID']);
                $famAlliance->setAccepted(false);
                if($row['active'] == 1)
                    $famAlliance->setAccepted(true);
                
                array_push($list, $famAlliance);
            }
            return $list;
        }
    }
    
    public function manageFamilyLeaveCosts($fid, $costs)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `family` SET `leaveCosts`= :costs WHERE `id`= :fid AND (`bossUID`= :uid OR `underbossUID`= :uid) AND `active`='1' AND `deleted`='0' LIMIT 1
            ", array(':costs' => $costs, ':fid' => $fid, ':uid' => $_SESSION['UID']));
        }
    }
    
    public function sendFamilyMassMessage($fid, $message, $pData)
    {
        if(isset($_SESSION['UID']))
        {
            global $route;
            global $security; 
            $profitOwner = $pData->getPossessDetails()->getStake();
            $messageService = new MessageService();
            $members = $this->con->getData("
                SELECT `username` AS `receiver` FROM `user` WHERE `id`!= :uid AND `familyID`= :fid AND `active`='1' AND `deleted`='0'
            ", array(':uid' => $_SESSION['UID'], ':fid' => $fid));
            foreach($members AS $m)
            {
                $message = "[b]*** " . $route->settings['gamename'] . " ***[/b]\n\r" . $message;
                $fakePost = array('security-token' => $security->getToken(), 'message' => $message, 'receiver' => $m['receiver']);
                @$messageService->replyToMessage($fakePost);
            }
            $this->con->setData("UPDATE `family` SET `money`=`money`- :costs WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':costs' => $profitOwner, ':fid' => $fid));
            
            /** Possession logic for buy out player | pay owner if exists and not self **/
            if(is_object($pData)) $telecomOwner = $pData->getPossessDetails()->getUserID();
            if(is_object($pData) && $telecomOwner > 0 && $telecomOwner != $_SESSION['UID'])
            {
                $possessionData = new PossessionDAO();
                $possessionData->applyProfitForOwner($pData, $profitOwner, $telecomOwner);
            }
        }
    }
    
    public function getRandomFamilyMemberButNotSelfByFamilyID($fid)
    {
        if(isset($_SESSION['UID']))
        {
            $row = $this->con->getDataSR("
                SELECT `username` FROM `user` WHERE `id`!= :uid AND `familyID`= :fid AND `active`='1' AND `deleted`='0' ORDER BY RAND() LIMIT 1
            ", array(':uid' => $_SESSION['UID'], ':fid' => $fid));
            if(isset($row['username']) && strlen($row['username']))
                return $row['username'];
            elseif($this->lang == 'en')
                return 'You';
            elseif($this->lang == 'nl')
                return 'Je';
        }
    }
    
    public function abolishFamily($fid)
    {
        if(isset($_SESSION['UID']))
        {
            $this->con->setData("
                UPDATE `family` SET `active`='0', `deleted`='1' WHERE `id`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1;
                UPDATE `user` SET `familyID`='0' WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0';
                DELETE FROM `family_alliance` WHERE `familyID`= :fid OR `allianceFamilyID`= :fid;
                DELETE FROM `family_bank_log` WHERE `familyID`= :fid;
                DELETE FROM `family_bf_donation_log` WHERE `familyID`= :fid;
                DELETE FROM `family_bf_send_log` WHERE `familyID`= :fid;
                DELETE FROM `family_crime` WHERE `familyID`= :fid;
                DELETE FROM `family_donation_log` WHERE `familyID`= :fid;
                DELETE FROM `family_join_invite` WHERE `familyID`= :fid;
                DELETE FROM `family_crime` WHERE `familyID`= :fid;
                DELETE FROM `family_raid` WHERE `familyID`= :fid;
                DELETE FROM `shoutbox_en` WHERE `familyID`= :fid;
                DELETE FROM `shoutbox_nl` WHERE `familyID`= :fid
            ", array(':fid' => $fid));
            $brothelWhores = $this->con->getData("
                SELECT `id`, `userID`, `whores` FROM `family_brothel_whore` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0'
            ", array(':fid' => $fid));
            foreach($brothelWhores AS $bw)
                $this->con->setData("
                    UPDATE `user` SET `whoresStreet`=`whoresStreet`+'".$bw['whores']."' WHERE `id`= :uid AND `active`='1' AND `deleted`='0' LIMIT 1;
                    DELETE FROM `family_brothel_whore` WHERE `userID`= :uid AND `familyID`= :fid AND `active`='1' AND `deleted`='0'
                ", array(':uid' => $bw['userID'], ':fid' => $fid));
            
            $familyGarage = $this->con->getDataSR("SELECT `id` FROM `family_garage` WHERE `familyID`= :fid AND `active`='1' AND `deleted`='0' LIMIT 1", array(':fid' => $fid));
            if(isset($familyGarage['id']) && $familyGarage['id'] > 0)
            {
                $this->con->setData("
                    DELETE FROM `garage` WHERE `famGarageID`= :fgid;
                    DELETE FROM `family_garage` WHERE `familyID`= :fid
                ", array(
                    ':fgid' => $familyGarage['id'],
                    ':fid' => $fid)
                );
            }
        }
    }
}
