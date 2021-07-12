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

/** CRON DAY RUNS EVERYDAY AT 0 A.M. Europe Amsterdam TIME **/

use Doctrine\Common\ClassLoader;
use app\config\Security;
use src\Business\Logic\game\Statics\DailyChallenge AS DailyChallengeStatics;
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
require_once GAME_DOC_ROOT . '/vendor/Doctrine/Common/ClassLoader.php';
$srcLoader = new ClassLoader('src'   ,  GAME_DOC_ROOT);
$srcLoader->register();

/* Open db connection */
$con = new DBConfig();

/* Security class (for true randomness) */
require_once GAME_DOC_ROOT.'/app/config/security.php';
$security = new Security();

/** ALL CRON DAY RELATED CODE START FROM HERE **/

/* Family days */
// /TODO

/* Family interest */
$con->setData("
    UPDATE `family` SET `money`=`money`+ (`money` * 0.01) WHERE `vip`='0' AND `active`='1' AND `deleted`='0';
    UPDATE `family` SET `money`=`money`+ (`money` * 0.02) WHERE `vip`='1' AND `active`='1' AND `deleted`='0'
"); // /CHECKED & OK

/* Banker Players interest and luckyboxes for all */
$con->setData("
    UPDATE `user` SET `bank`=`bank`+ (`bank` * 0.03) WHERE `charType`='5' AND `health`>'0' AND `active`='1' AND `deleted`='0';
    UPDATE `user` SET `bank`=`bank`+ (`bank` * 0.01) WHERE `charType`<>'5' AND `health`>'0' AND `active`='1' AND `deleted`='0';
    UPDATE `user` SET `luckybox`=`luckybox`+'1' WHERE `donatorID`='0' AND `luckybox`<'5' AND `health`>'0' AND `active`='1' AND `deleted`='0';
    UPDATE `user` SET `luckybox`=`luckybox`+'1' WHERE `donatorID`='1' AND `luckybox`<'7' AND `health`>'0' AND `active`='1' AND `deleted`='0';
    UPDATE `user` SET `luckybox`=`luckybox`+'1' WHERE `donatorID`='5' AND `luckybox`<'10' AND `health`>'0' AND `active`='1' AND `deleted`='0';
    UPDATE `user` SET `luckybox`=`luckybox`+'1' WHERE `donatorID`='10' AND `luckybox`<'15' AND `health`>'0' AND `active`='1' AND `deleted`='0'
"); // /CHECKED & OK

/* Banned players score -1 */
$con->setData("UPDATE `user` SET `score`='-1' WHERE `statusID`='8' AND `active`='1' AND `deleted`='0'"); // /CHECKED & OK

/* Business stocks add to history */
$businesses = $con->getData("SELECT `id`, `high_price`, `low_price`, `last_price` FROM `business`");
foreach($businesses AS $b)
{
    $con->setData("
        INSERT INTO `business_history` (`businessID`, `close_day`, `highest_day`, `lowest_day`, `date`) VALUES (:bid, :cp, :hp, :lp, NOW())
    ", array(':bid' => $b['id'], ':cp' => $b['last_price'], ':hp' => $b['high_price'], ':lp' => $b['low_price']));
}
$con->setData("UPDATE `business` SET `close_price`=`last_price`, `high_price`=`last_price`, `low_price`=`last_price`"); // /CHECKED & OK

/* Inactive dead players reset + onlinetime */
$inactiveDeadPlayers = $con->getData("
    SELECT `id`, `username`, `charType` FROM `user` WHERE `lastclick`<'".(time() - (60 * 60 * 24 * 7))."' AND `statusID`<='7' AND `health`<='0' AND `active`='1' AND `deleted`='0'
");
foreach($inactiveDeadPlayers AS $p)
{
    $stateID = $security->randInt(1, 6);
    $min = 1;
    if($stateID > 1)
        $min = (($stateID - 1) * 3) + 1;
    
    $max = $min + 2;
    $cityID = $security->randInt($min, $max);
    // Queries copy pasted UserDAO->resetDeadUser() + added new location
    $con->setData("
        DELETE FROM `business_stock` WHERE `userID`= :uid;
        DELETE FROM `crime_org_prep` WHERE `userID`= :uid;
        UPDATE `crime_org_prep` SET `participantID`='0' WHERE `participantID`= :uid;
        UPDATE `crime_org_prep` SET `participant2ID`='0' WHERE `participant2ID`= :uid;
        UPDATE `crime_org_prep` SET `participant3ID`='0' WHERE `participant3ID`= :uid;
        DELETE FROM `detective` WHERE `userID`= :uid;
        DELETE FROM `drug_liquid` WHERE `userID`= :uid;
        DELETE FROM `equipment` WHERE `userID`= :uid;
        DELETE FROM `fifty_game` WHERE `userID`= :uid AND `type`!='2';
        UPDATE `ground` SET `userID`='0' WHERE `userID`= :uid;
        DELETE FROM `gym_competition` WHERE `userID`= :uid;
        DELETE FROM `market` WHERE `userID`= :uid AND ((`type`!='0' AND `type`!='2') OR ((`type`='0' OR `type`='2') AND `requested`='1'));
        UPDATE `possess` SET `userID`='0', `profit`='0', `profit_hour`='0', `stake`='50000' WHERE `userID`= :uid;
        DELETE FROM `possess_transfer` WHERE `senderID`= :uid OR `receiverID`= :uid;
        DELETE FROM `prison` WHERE `userID`= :uid;
        DELETE FROM `rld_whore` WHERE `userID`= :uid;
        DELETE FROM `smuggle_unit` WHERE `userID`= :uid;
        UPDATE `user`
          SET `username`= :u, `restartDate`= NOW(), `isProtected`='1', `charType`= :p, `health`='100', `rankpoints`='0', `cash`='2500', `bank`='10000', `whoresStreet`='0',
            `bullets`='0', `weapon`='0', `protection`='0', `airplane`='0', `weaponExperience`='0', `weaponTraining`='0', `residence`='0', `residenceHistory`='', `power`='0',
            `cardio`='0', `luckybox`='0', `cCrimes`='0', `cWeaponTraining`='0', `cGymTraining`='0', `cStealVehicles`='0', `cPimpWhores`='0', `cFamilyRaid`='0',
            `cFamilyCrimes`='0', `cBombardement`='0', `cTravelTime`='0', `stateID`='" . $stateID . "', `cityID`='" . $cityID . "'
        WHERE `id`= :uid AND `statusID`<='7' AND `health`<='0' AND `active`='1' AND `deleted`='0';
        DELETE FROM `user_residence` WHERE `userID`= :uid
    ", array(':u' => $p['username'], ':p' => $p['charType'], ':uid' => $p['id']));
    
    $userGarages = $con->getData("SELECT `id` FROM `user_garage` WHERE `userID`= :uid", array(':uid' => $p['id']));
    foreach($userGarages AS $g)
        $con->setData("DELETE FROM `garage` WHERE `userGarageID`= :ugid", array(':ugid' => $g['id']));
    
    $con->setData("DELETE FROM `user_garage` WHERE `userID`= :uid", array(':uid' => $p['id']));
} // /CHECKED & OK

/* Daily Challenges | NEW NOK */
$dailyStatics = new DailyChallengeStatics();
$prizeDifficulties = array("easy", "medium", "hard");
$dailies = array_rand($dailyStatics->challenges, 3);
$prizeDb = array_rand($dailyStatics->challengeRewardDbFields, 3);
if($security->randInt(1, 3) == 1)
    $prizeDifficulties[] = "extra-hard";

function getDefaultPrizeMultipliers($easyPrizes)
{
    return array(
        'medium' => array(round($easyPrizes[0] * 3), round($easyPrizes[1] * 3), round($easyPrizes[2] * 3), round($easyPrizes[3] * 3), round($easyPrizes[4] * 3), round($easyPrizes[5] * 3),
            round($easyPrizes[6] * 3), round($easyPrizes[7] * 3), round($easyPrizes[8] * 3)),
        'hard' => array(round($easyPrizes[0] * 6), round($easyPrizes[1] * 6), round($easyPrizes[2] * 6), round($easyPrizes[3] * 6), round($easyPrizes[4] * 6), round($easyPrizes[5] * 6),
            round($easyPrizes[6] * 6), round($easyPrizes[7] * 6), round($easyPrizes[8] * 6)),
        'extra-hard' => array(round($easyPrizes[0] * 10), round($easyPrizes[1] * 10), round($easyPrizes[2] * 10), round($easyPrizes[3] * 10), round($easyPrizes[4] * 10),
            round($easyPrizes[5] * 10), round($easyPrizes[6] * 10), round($easyPrizes[7] * 10), round($easyPrizes[8] * 10)),
    );
}
$difficulties = array_rand($prizeDifficulties, 3);
$con->setData("
    UPDATE `user`
    SET `dailyCompletedDays`='1'
    WHERE (`daily1Amount`<(SELECT `amount` FROM `daily_challenge` WHERE `id`='1') OR
        `daily2Amount`<(SELECT `amount` FROM `daily_challenge` WHERE `id`='2') OR
        `daily3Amount`<(SELECT `amount` FROM `daily_challenge` WHERE `id`='3')) AND `dailyCompletedDays`!='1' AND `active`='1' AND `deleted`='0';
    TRUNCATE TABLE `daily_challenge`;
    UPDATE `user` SET `daily1Amount`='0', `daily2Amount`='0', `daily3Amount`='0'
");
foreach($dailies AS $k => $id)
{
    $easyAmnt = array($security->randInt(20,40), $security->randInt(18, 42), $security->randInt(22, 38));
    $mediumAmnt = array($security->randInt(30,60), $security->randInt(28, 62), $security->randInt(32, 58));
    $hardAmnt = array($security->randInt(50,70), $security->randInt(48, 72), $security->randInt(52, 68));
    $extraHardAmnt = array($security->randInt(60,90), $security->randInt(58, 92), $security->randInt(62, 88));
    
    $m = 1;
    $d = false;
    if($id == 2 || $id == 5 || $id == 8 || $id == 11 || $id == 14)
        $m = 100;
    elseif($id == 4)
        $m = 2;
    elseif($id == 6 || $id == 9)
        $d = 2;
    elseif($id == 7)
        $m = 10000;
    elseif($id == 12 || $id == 13)
        $m = 3;
    
    if(is_int($d) && $d !== false)
    {
        $easyAmnt = array(round($easyAmnt[0] / $d), round($easyAmnt[1] / $d), round($easyAmnt[2] / $d));
        $mediumAmnt = array(round($mediumAmnt[0] / $d), round($mediumAmnt[1] / $d), round($mediumAmnt[2] / $d));
        $hardAmnt = array(round($hardAmnt[0] / $d), round($hardAmnt[1] / $d), round($hardAmnt[2] / $d));
        $extraHardAmnt = array(round($extraHardAmnt[0] / $d), round($extraHardAmnt[1] / $d), round($extraHardAmnt[2] / $d));
    }
    elseif(is_int($m))
    {
        $easyAmnt = array(round($easyAmnt[0] * $m), round($easyAmnt[1] * $m), round($easyAmnt[2] * $m));
        $mediumAmnt = array(round($mediumAmnt[0] * $m), round($mediumAmnt[1] * $m), round($mediumAmnt[2] * $m));
        $hardAmnt = array(round($hardAmnt[0] * $m), round($hardAmnt[1] * $m), round($hardAmnt[2] * $m));
        $extraHardAmnt = array(round($extraHardAmnt[0] * $m), round($extraHardAmnt[1] * $m), round($extraHardAmnt[2] * $m));
    }
    
    switch($prizeDb[$k])
    {
        case 1:
            $easyPrizes = array(2500000, 2750000, 3000000, 3250000, 3500000, 3750000, 4000000, 4250000, 4500000);
            break;
        case 2:
            $easyPrizes = array(50, 55, 60, 65, 70, 75, 80, 85, 90);
            break;
        case 3:
            $easyPrizes = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
            $prizeAmnt = array(
                'easy' => $easyPrizes,
                'medium' => array(round($easyPrizes[0] * 2), round($easyPrizes[1] * 2), round($easyPrizes[2] * 2), round($easyPrizes[3] * 2), round($easyPrizes[4] * 2),
                    round($easyPrizes[5] * 2), round($easyPrizes[6] * 2), round($easyPrizes[7] * 2), round($easyPrizes[8] * 2)),
                'hard' => array(round($easyPrizes[0] * 3), round($easyPrizes[1] * 3), round($easyPrizes[2] * 3), round($easyPrizes[3] * 3), round($easyPrizes[4] * 3),
                    round($easyPrizes[5] * 3), round($easyPrizes[6] * 3), round($easyPrizes[7] * 3), round($easyPrizes[8] * 3)),
                'extra-hard' => array(round($easyPrizes[0] * 5), round($easyPrizes[1] * 5), round($easyPrizes[2] * 5), round($easyPrizes[3] * 5), round($easyPrizes[4] * 5),
                    round($easyPrizes[5] * 5), round($easyPrizes[6] * 5), round($easyPrizes[7] * 5), round($easyPrizes[8] * 5)),
            );
            break;
        case 4:
            $easyPrizes = array(3, 5, 7, 9, 11, 13, 15, 17, 19);
            break;
        case 5:
            $easyPrizes = array(8800, 9100, 9400, 9700, 10000, 10300, 10600, 10900, 11200);
            break;
        case 6:
            $easyPrizes = array(1, 2);
            $prizeAmnt = array(
                'easy' => $easyPrizes,
                'medium' => array(2, 3),//array(round($easyPrizes[0] * 2), round($easyPrizes[1] * 2), round($easyPrizes[2] * 2)),
                'hard' => array(3, 4),//array(round($easyPrizes[0] * 3), round($easyPrizes[1] * 3), round($easyPrizes[2] * 3)),
                'extra-hard' => array(4, 5)//array(round($easyPrizes[0] * 4), round($easyPrizes[1] * 4), round($easyPrizes[2] * 4)),
            );
            break;
    }
    
    if(!isset($prizeAmnt))
    {
        $defaultMultipliers = getDefaultPrizeMultipliers($easyPrizes);
        $prizeAmnt = array(
            'easy' => $easyPrizes,
            'medium' => $defaultMultipliers['medium'],
            'hard' => $defaultMultipliers['hard'],
            'extra-hard' => $defaultMultipliers['extra-hard']
        );
    }
    
    switch($difficulties[$k])
    {
        default:
        case 1:
            $amount = $mediumAmnt[array_rand($mediumAmnt)];
            $prize = $prizeAmnt['medium'][array_rand($prizeAmnt['medium'])];
            break;
        case 0:
            $amount = $easyAmnt[array_rand($easyAmnt)];
            $prize = $prizeAmnt['easy'][array_rand($prizeAmnt['easy'])];
            break;
        case 2:
            $amount = $hardAmnt[array_rand($hardAmnt)];
            $prize = $prizeAmnt['hard'][array_rand($prizeAmnt['hard'])];
            break;
        case 3:
            $amount = $extraHardAmnt[array_rand($extraHardAmnt)];
            $prize = $prizeAmnt['extra-hard'][array_rand($prizeAmnt['extra-hard'])];
            break;
        
    }
    // Insert challenge
    $con->setData("
        INSERT INTO `daily_challenge` (`challengeID`, `amount`, `rewardType`, `rewardAmount`) VALUES (:cid, :amnt, :rwrd, :rwrdAmnt)
    ", array(':cid' => $id, ':amnt' => $amount, ':rwrd' => $prizeDb[$k], ':rwrdAmnt' => $prize));
    unset($prizeAmnt); // Loop finish unset $prizeAmnt because of the !isset($prizeAmnt) check above
} // /CHECKED & OK **/
