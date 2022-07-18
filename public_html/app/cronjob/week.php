<?PHP

// Mafiasource online mafia RPG, this software is inspired by Crimeclub.
// Copyright © 2016 Michael Carrein, 2006 Crimeclub.nl
//
// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the “Software”),
// to deal in the Software without restriction, including without limitation
// the rights to use, copy, modify, merge, publish, distribute, sublicense,
// and/or sell copies of the Software, and to permit persons to whom the
// Software is furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included
// in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS
// OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
// NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
// DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
// OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR
// THE USE OR OTHER DEALINGS IN THE SOFTWARE.

/** CRON WEEK RUNS EVERY FRIDAY AT 7 P.M. Europe Amsterdam TIME **/

use Doctrine\Common\ClassLoader;
use app\config\Security;
use src\Data\config\DBConfig;

/* Error reporting (debugging) */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

/* Set correct timezone */
ini_set('date.timezone', 'Europe/Amsterdam');

/* Define game_doc_root & database credentials (included in config) */
require_once __DIR__ . '/../config/config.php';

/* Enable Autoloading with doctrine */
require_once GAME_DOC_ROOT . '/vendor/autoload.php';
$srcLoader = new ClassLoader('src'   ,  GAME_DOC_ROOT);
$srcLoader->register();

/* Open db connection */
$con = new DBConfig();

/* Security class (for true randomness) * /
require_once GAME_DOC_ROOT.'/app/config/security.php';
$security = new Security(); */

/** ALL CRON WEEK RELATED CODE START FROM HERE **/
/**
/* Shoutbox reset * /
$con->setData("
    DELETE FROM `shoutbox_en` WHERE `date`< :date;
    DELETE FROM `shoutbox_nl` WHERE `date`< :date
", array(':date' => date('Y-m-d H:i:s', strtotime('-7 days')))); // /CHECKED & OK
**/

/* Remove dormant users (2 years+) */
$rows = $con->getData("SELECT `id`, `username`, `familyID` FROM `user` WHERE `lastclick`<= :timePast", array(':timePast' => strtotime('-2 years')));
if(is_array($rows) && count($rows))
{
    // Function to delete all files and directories in provided $str directory
    function deleteAll($str)
    {
        if(is_file($str))
            return unlink($str);
        elseif (is_dir($str))
        {
            $scan = glob(rtrim($str, '/').'/*');
            foreach($scan as $index=>$path)
                deleteAll($path);
            
            return @rmdir($str);
        }
    }
    // Should a data removal be requested soon: move everything underneath to its own function somwehere | TO DO
    // Remove by: one array of ID, username and familyID OR multiple arrays of ID, username and familyID
    $ids = array();
    $con->setData("TRUNCATE TABLE `user_removed_week`"); // Not to be applied on individual removal request
    foreach($rows AS $row)
    {
        $con->setData("INSERT INTO `user_removed_week` (`username`) VALUES (:username)", array(':username' => $row['username'])); // Not to be applied on individual removal request
        // Start targeted removal | Edited copy paste of FamilyDAO->removeFamilyMember() TO DO
        $famCrimes = $con->getData("
            SELECT `id`, `starterUID`, `participants`, `num_participants`, `stateID` FROM `family_crime` WHERE `familyID`= :fid
        ", array(':fid' => $row['familyID']));
        if(count($famCrimes))
        {
            foreach($famCrimes AS $fc)
            {
                $participants = unserialize($fc['participants']);
                $crimeID = $fc['id'];
                if(in_array($row['id'], $participants))
                {
                    $k = array_search($row['id'], $participants);
                    unset($participants[$k]);
                    $con->setData("
                        UPDATE `family_crime` SET `participants`= :p WHERE `id`= :fcid AND `active`='1' AND `deleted`='0'
                    ", array(':p' => serialize($participants), ':fcid' => $crimeID));
                    $check = $con->getDataSR("
                        SELECT `participants` FROM `family_crime` WHERE `familyID`= :fid AND `id`= :fcid LIMIT 1
                    ", array(':fid' => $row['familyID'], ':fcid' => $crimeID));
                    $participantsCheck = unserialize($check['participants']);
                    $x = (array)$participantsCheck;
                    $con->setData("UPDATE `family_crime` SET `starterUID`='0' WHERE `id`= :fcid AND `starterUID`= :uid LIMIT 1", array(':fcid' => $crimeID, ':uid' => $row['id']));
                    if(empty($x))
                        $con->setData("DELETE FROM `family_crime` WHERE `id`= :fcid AND `familyID`= :fid LIMIT 1", array(':fcid' => $crimeID, ':fid' => $row['familyID']));
                }
            }
        }
        // Edited copy paste part of UserDAO->resetUser() TO DO
        $userGarages = $con->getData("SELECT `id` FROM `user_garage` WHERE `userID`= :uid", array(':uid' => $row['id']));
        foreach($userGarages AS $g)
            $con->setData("DELETE FROM `garage` WHERE `userGarageID`= :ugid", array(':ugid' => $g['id']));
        
        $con->setData("DELETE FROM `user_garage` WHERE `userID`= :uid", array(':uid' => $row['id']));
        
        // Reset user familyID for family members of inactive family boss
        $famBoss = $con->getDataSR("SELECT `bossUID` FROM `family` WHERE `id`= :fid", array(':fid' => $row['familyID']));
        $famBoss = isset($famBoss['bossUID']) ? $famBoss['bossUID'] : null;
        if(isset($famBoss) && $famBoss == $row['id'])
            $con->setData("UPDATE `user` SET `familyID`='0' WHERE `familyID`= :fid", array(':fid' => $row['familyID']));
        
        // Remove disk drive files..
        // Remove email in masterCrypts emails file by id | Copy paste part of UserDAO->createUser() && ->changeEmail() TO DO
        $cryptFile = GAME_DOC_ROOT . '/app/Resources/masterCrypts/user/emails.txt';
        $serializedCrypts = file_get_contents($cryptFile);
        $cryptsArr = unserialize($serializedCrypts);
        $crypts = is_array($cryptsArr) && !empty($cryptsArr) ? $cryptsArr : array();
        unset($crypts[$row['id']]);
        
        if(file_exists($cryptFile)) unlink($cryptFile);
        $ourFileHandle = fopen($cryptFile, 'w');
        fwrite($ourFileHandle, serialize($crypts));
        fclose($ourFileHandle);
        chmod($cryptFile, 0600);
        
        // Remove userCrypts
        deleteAll(GAME_DOC_ROOT . '/app/Resources/userCrypts/' . $row['id']);
        
        // Remove password salt
        $saltFile = GAME_DOC_ROOT . '/app/Resources/userSalts/' . $row['id'] . '.txt';
        if(file_exists($saltFile)) unlink($saltFile);
        
        // Remove PrivateID in salt file by id | Copy paste part of UserDAO->deactivatePrivateID() TO DO
        $saltFile = GAME_DOC_ROOT . '/app/Resources/privateSalts/salts.txt';
        $serializedSalts = file_get_contents($saltFile);
        $saltsArr = unserialize($serializedSalts);
        $salts = is_array($saltsArr) && !empty($saltsArr) ? $saltsArr : array();
        unset($salts[$row['id']]);
        
        if(file_exists($saltFile)) unlink($saltFile);
        $ourFileHandle = fopen($saltFile, 'w');
        fwrite($ourFileHandle, serialize($salts));
        fclose($ourFileHandle);
        chmod($saltFile, 0600);
            
        // Remove avatars
        deleteAll($avatarsDir = GAME_DOC_ROOT . '/web/public/images/users/' . $row['id']);
        
        // Targeted removal loop finish, add userID to $ids to globally remove underneath
        $ids[] = (int)$row['id'];
    }
    if(isset($ids) && !empty($ids))
    {
        // Continue global removal (WHERE userID IN (1, 2, 3, ...)) | No copy paste literal hard removal without active or deleted checks.
        $inIds = "";
        $iter = 0;
        $inParams = array();
        foreach($ids AS $item)
        {
            $key = ":id".$iter++;
            $inIds .= ($inIds ? "," : "") . $key; // :id0, :id1, :id2, ..
            $inParams[$key] = $item; // bound param key=value ie. :id0=1, :id1=2, :id2=3, ..
        }
        $con->setData("
            DELETE FROM `bank_log` WHERE `senderID` IN (".$inIds.") OR `receiverID` IN (".$inIds.");
            DELETE FROM `business_stock` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `change_email` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `crime_org_prep` WHERE `userID` IN (" . $inIds . ");
            UPDATE `crime_org_prep` SET `participantID`='0', `participantReady`='0', `garageID`='0' WHERE `participantID` IN (" . $inIds . ");
            UPDATE `crime_org_prep` SET `participant2ID`='0', `participant2Ready`='0', `weaponType`='0' WHERE `participant2ID` IN (" . $inIds . ");
            UPDATE `crime_org_prep` SET `participant3ID`='0', `participant3Ready`='0', `intelType`='0' WHERE `participant3ID` IN (" . $inIds . ");
            DELETE FROM `detective` WHERE `userID` IN (" . $inIds . ") OR `victimID` IN (" . $inIds . ");
            DELETE FROM `donator_list` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `drug_liquid` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `equipment` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `family` WHERE `bossUID` IN (" . $inIds . ");
            UPDATE `family` SET `underbossUID`='0' WHERE `underbossUID` IN (" . $inIds . ");
            UPDATE `family` SET `bankmanagerUID`='0' WHERE `bankmanagerUID` IN (" . $inIds . ");
            UPDATE `family` SET `forummodUID`='0' WHERE `forummodUID` IN (" . $inIds . ");
            DELETE FROM `family_bank_log` WHERE `senderID` IN (" . $inIds . ") OR `receiverID` IN (" . $inIds . ");
            DELETE FROM `family_bf_donation_log` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `family_bf_send_log` WHERE `senderID` IN (" . $inIds . ") OR `receiverID` IN (" . $inIds . ");
            DELETE FROM `family_brothel_whore` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `family_crime` WHERE `starterUID` IN (" . $inIds . ");
            DELETE FROM `family_donation_log` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `family_join_invite` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `family_mercenary_log` WHERE `userID` IN (" . $inIds . ");
            UPDATE `family_raid` SET `driverID`=0, `driverReady`=0, `garageID`=0 WHERE `driverID` IN (" . $inIds . ");
            UPDATE `family_raid` SET `bombExpertID`=0, `bombExpertReady`=0, `bombType`=0 WHERE `bombExpertID` IN (" . $inIds . ");
            UPDATE `family_raid` SET `weaponExpertID`=0, `weaponExpertReady`=0, `weaponType`=0, `bullets`=0 WHERE `weaponExpertID` IN (" . $inIds . ");
            DELETE FROM `family_raid` WHERE `leaderID` IN (" . $inIds . ");
            DELETE FROM `fifty_game` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `forum_reaction` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `forum_read` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `forum_topic` WHERE `starterUID` IN (" . $inIds . ");
            UPDATE `ground` SET `userID`='0' WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `gym_competition` WHERE `userID` IN (" . $inIds . ") OR `participantID` IN (" . $inIds . ");
            DELETE FROM `hitlist` WHERE `ordererID` IN (" . $inIds . ") OR `userID` IN (" . $inIds . ");
            DELETE FROM `honorpoint_log` WHERE `senderID` IN (" . $inIds . ") OR `receiverID` IN (" . $inIds . ");
            DELETE FROM `login` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `lottery` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `lottery_winner` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `market` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `message` WHERE `senderID` IN (" . $inIds . ") OR `receiverID` IN (" . $inIds . ");
            DELETE FROM `murder_log` WHERE `attackerID` IN (" . $inIds . ") OR `victimID` IN (" . $inIds . ");
            DELETE FROM `notification` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `poll_vote` WHERE `userID` IN (" . $inIds . ");
            UPDATE `possess` SET `userID`='0' WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `possess_transfer` WHERE `senderID` IN (" . $inIds . ") OR `receiverID` IN (" . $inIds . ");
            DELETE FROM `prison` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `recover_password` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `rld_whore` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `shoutbox_en` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `shoutbox_nl` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `smuggle_unit` WHERE `userID` IN (" . $inIds . ");
            UPDATE `user` SET `referralOf`='0' WHERE `referralOf` IN (" . $inIds . ");
            DELETE FROM `user` WHERE `id` IN (" . $inIds . ");
            DELETE FROM `user_friend_block` WHERE `inviterID` IN (" . $inIds . ") OR `userID` IN (" . $inIds . ");
            DELETE FROM `user_mission_carjacker` WHERE `userID` IN (" . $inIds . ");
            DELETE FROM `user_residence` WHERE `userID` IN (" . $inIds . ")
        ", $inParams);
    }
}
