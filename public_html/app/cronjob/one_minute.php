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

/** CRON ONE MINUTE RUNS EVERY MINUTE **/

use Doctrine\Common\ClassLoader;
use app\config\Security;
use src\Business\Logic\game\Statics\Crimes AS CrimesStatics;
use src\Business\Logic\game\Statics\Mission AS MissionStatics;
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

/* Security class (for true randomness) */
require_once GAME_DOC_ROOT.'/app/config/security.php';
$security = new Security();

/** ALL CRON ONE MINUTE RELATED CODE START FROM HERE **/

/* Business stocks randomly up/down small amount */
if(date('H') >= 6 && date('H') <= 24)
{
    $businesses = $con->getData("SELECT `id`, `last_price`, `high_price`, `low_price` FROM `business`");
    foreach($businesses AS $b)
    {
        $change = $security->randInt(1, 10);
        if($change >= 9)
            $value = $security->randInt(1, 7) / 100;
        else
            $value = $security->randInt(1, 4) / 100;
        
        $upDown = $security->randInt(1, 3);
        if($upDown === 1) // Up we go
        {
            if($b['last_price'] + $value > $b['high_price'])
                $con->setData("UPDATE `business` SET `high_price`=`last_price`+ :val WHERE `id`= :id", array(':val' => $value, ':id' => $b['id']));
            
            $con->setData("UPDATE `business` SET `last_price`=`last_price`+ :val WHERE `id`= :id AND `last_price`<'500'", array(':val' => $value, ':id' => $b['id']));
        }
        elseif($upDown === 3) // Down we fall
        {
            if($b['last_price'] - $value < $b['low_price'])
                $con->setData("UPDATE `business` SET `low_price`=`last_price`- :val WHERE `id`= :id", array(':val' => $value, ':id' => $b['id']));
            
            $con->setData("UPDATE `business` SET `last_price`=`last_price`- :val WHERE `id`= :id AND `last_price`>'10'", array(':val' => $value, ':id' => $b['id']));
        }
    }
} // /CHECKED & OK

/* Prepared, ready & commited Organized crimes type 3 */
$crimesStatics = new CrimesStatics();
$crimes = $con->getData("
    SELECT cop.`id`, cop.`orgCrimeID`, cop.`userID`, cop.`participantID`, cop.`participant2ID`, cop.`participant3ID`, cop.`weaponType`, cop.`intelType`,
        co.`minProfit`, co.`maxProfit`, co.`difficulty`, co.`waitingTimeCompletion`, co.`travelTimeCompletion`, co.`prisonTimeBusted`
    FROM `crime_org_prep` AS cop
    LEFT JOIN `crime_org` AS co
    ON(cop.`orgCrimeID`=co.`id`)
    WHERE ((cop.`commitTime`> :time) OR (cop.`commitTime`>'0' AND `commitTime`< :time)) AND cop.`job`='0' AND cop.`userReady`='1' AND
        cop.`participantReady`='1' AND cop.`participant2Ready`='1' AND cop.`participant3Ready`='1' AND cop.`garageID`!='0' AND
        cop.`weaponType`!='0' AND cop.`intelType`!='0' AND  co.`type`='3' AND co.`active`='1' AND co.`deleted`='0'
", array(':time' => (time() - 120)));
foreach($crimes AS $oc)
{
    $chanceVal = $oc['weaponType'] + $oc['intelType'];
    $participants = $oc['userID'] . "," . $oc['participantID'] . "," . $oc['participant2ID'] . "," . $oc['participant3ID'];
    $members = $con->getData("SELECT `id`, `username`, `crimesLv`, `crimesXp` FROM `user` WHERE `id` IN(".$participants.") AND `active`='1' AND `deleted`='0'");
    $success = $heat = $hurt = $busted = false;
    $chance = $security->randInt($chanceVal, 100);
    if($oc['difficulty'] < ($chance))
    {
        $chance2 = $security->randInt(0, 100);
        if($chance2 > 70) $success = true;
        
        if($success === false)
        {
            $chanceHeat = $security->randInt(0, 100);
            if($chanceHeat < 40)
            {
                $chanceBusted = $security->randInt(0, 100);
                if($chanceBusted <= 60)
                    $busted = true;
                else
                    $heat = true;
            }
            elseif($chanceHeat >= 40 && $chanceHeat < 70)
                null;
            elseif($chanceHeat >= 70)
                $hurt = true;
        }
    }
    else
        $success = true;
    
    if($success === true || $hurt === true || $heat === true)
    {
        $hurtPercent = $bulletsSpend = array(1 => false, false, false, false);
        if($hurt === true || $heat === true)
        {
            $hurtGrade = $security->randInt(1, 100);
            if($hurtGrade > 0 && $hurtGrade <= 25)
            {
                $hurtFrom = 1;
                $hurtTo = 2;
                $bulletsFrom = 2;
                $bulletsTo = 6;
                $failChance = $security->randInt(80, 120);
            }
            elseif($hurtGrade > 25 && $hurtGrade <= 50)
            {
                $hurtFrom = 1;
                $hurtTo = 3;
                $bulletsFrom = 3;
                $bulletsTo = 16;
                $failChance = $security->randInt(50, 100);
            }
            elseif($hurtGrade > 50 && $hurtGrade <= 75)
            {
                $hurtFrom = 2;
                $hurtTo = 4;
                $bulletsFrom = 4;
                $bulletsTo = 27;
                $failChance = $security->randInt(20, 50);
            }
            else
            {
                $hurtFrom = 3;
                $hurtTo = 8;
                $bulletsFrom = 5;
                $bulletsTo = 50;
                $failChance = $security->randInt(1, 30);
            }
            $hurtPercent = array(
                1 => $security->randInt($hurtFrom, $hurtTo), $security->randInt($hurtFrom, $hurtTo), $security->randInt($hurtFrom, $hurtTo),
                $security->randInt($hurtFrom, $hurtTo)
            );
            $bulletsSpend = array(
                1 => $security->randInt($bulletsFrom, $bulletsTo), $security->randInt($bulletsFrom, $bulletsTo), $security->randInt($bulletsFrom, $bulletsTo),
                $security->randInt($bulletsFrom, $bulletsTo)
            );
            
            $failedChance = $security->randInt(1, 100);
            if($failedChance > $failChance)
                $failed = true;
        }
        if(isset($failed) && $failed === true)
        {
            $i = 1;
            foreach($members AS $m)
            {
                $crimesStatics->commitCrimeFail($m['id'], $oc['waitingTimeCompletion'], $hurtPercent[$i], $bulletsSpend[$i]);
                // Send notification
                $params = "hurtPercent=".$hurtPercent[$i]."&bullets=".$bulletsSpend[$i];
                $crimesStatics->sendNotification($m['id'], 'ORGANIZED_CRIME_3_FAILED_AND_HURT', $params);
                
                $i++;
            }
        }
        else
        {
            $maxRp = 3;
            $minRp = 2;
            $stolenMoney = $security->randInt($oc['minProfit'], $oc['maxProfit']);
            $stolenMoney = $stolenMoney / 4;
            $rpCollected = array(1 => $security->randInt($minRp, $maxRp), $security->randInt($minRp, $maxRp), $security->randInt($minRp, $maxRp), $security->randInt($minRp, $maxRp));
            $newLvlData = array();
            if($hurt === true)
            {
                $i = 1;
                foreach($members AS $m)
                {
                    // Send notification
                    $params = "stolenMoney=".number_format($stolenMoney, 0, '', ',')."&rankpoints=".$rpCollected[$i]."&hurtPercent=".$hurtPercent[$i]."&bullets=".$bulletsSpend[$i];
                    $crimesStatics->sendNotification($m['id'], 'ORGANIZED_CRIME_3_SUCCESS_BUT_HURT', $params);
                    
                    $i++;
                }
            }
            elseif($heat === true)
            {
                $i = 1;
                foreach($members AS $m)
                {
                    // Send notification
                    $params = "stolenMoney=".number_format($stolenMoney, 0, '', ',')."&rankpoints=".$rpCollected[$i]."&bullets=".$bulletsSpend[$i];
                    $crimesStatics->sendNotification($m['id'], 'ORGANIZED_CRIME_3_SUCCESS_BUT_HEAT', $params);
                    
                    $i++;
                }
                foreach($hurtPercent AS $k => $v) $hurtPercent[$k] = false; // Only bullets fired not hurt whatever got set, reset
            }
            else
            {
                $i = 1;
                foreach($members AS $m)
                {
                    // Send notification
                    $params = "stolenMoney=".number_format($stolenMoney, 0, '', ',')."&rankpoints=".$rpCollected[$i];
                    $crimesStatics->sendNotification($m['id'], 'ORGANIZED_CRIME_3_SUCCESS', $params);
                    
                    $i++;
                }
            }
            $mission = 2;
            $missionStatics = new MissionStatics($fromCron = true);
            $i = 1;
            foreach($members AS $m)
            {
                // Mission check each user only on any success (xp gain) and before its data apply (commitCrimeSuccess)
                $newLvlData[$m['id']] = CrimesStatics::levelCalculations($m['crimesLv'], $m['crimesXp']);
                if($m['crimesLv'] < $newLvlData[$m['id']]['levelAfter'])
                {
                    $mTierProgress = $missionStatics->getMissionTierAndProgressByMission($mission, $m['id']);
                    
                    $missionTier = $missionStatics->missionTiers[$mission];
                    $todo = $missionTier['todo'][$mTierProgress['t']];
                    $bank = $missionTier['prizeMoney'][$mTierProgress['t']];
                    $hp = $missionTier['prizeHp'][$mTierProgress['t']];
                    if($mTierProgress['p'] + 1 >= $todo && $todo > $mTierProgress['p'])
                    {
                        $missionStatics->payoutMissionPrize($bank, $hp, $m['id']);
                        
                        $params = "mission=".$missionStatics->missions[$mission]."&bank=".number_format($bank, 0, '', ',')."&hp=".number_format($hp, 0, '', ',');
                        $crimesStatics->sendNotification($m['id'], 'USER_ACHIEVED_MISSION', $params);
                    }
                }
                $crimesStatics->commitCrimeSuccess(
                    $m['id'], $stolenMoney, $rpCollected[$i], $newLvlData[$m['id']]['levelAfter'], $newLvlData[$m['id']]['xpAfter'], $oc['waitingTimeCompletion'], $hurtPercent[$i],
                    $bulletsSpend[$i]
                );
                
                $i++;
            }
        }
    }
    elseif($busted === true)
    {
        $imprisoned = $security->randInt(1, 7);
        if($imprisoned == 1)
            $inPrison = array($members[0]);
        elseif($imprisoned == 2)
            $inPrison = array($members[1]);
        elseif($imprisoned == 3)
            $inPrison = array($members[2]);
        elseif($imprisoned == 4)
            $inPrison = array($members[3]);
        elseif($imprisoned == 5)
            $inPrison = array($members[0], $members[1]);
        elseif($imprisoned == 6)
            $inPrison = array($members[0], $members[1], $members[2]);
        else
            $inPrison = array($members[0], $members[1], $members[2], $members[3]);
        
        $arrestedList = $othersArrestedList = $arrestedUsersArr = array();
        foreach($members AS $m)
        {
            if(in_array($m, $inPrison))
            {
                $arrestedList[] = $m;
                $arrestedUsersArr[] = $m['username'];
                $con->setData("
                    DELETE FROM `prison` WHERE `userID`= :uid;
                    INSERT INTO `prison` (`userID`, `time`) VALUES (:uid, :time)
                ", array(':uid' => $m['id'], ':time' => (time() + $oc['prisonTimeBusted'])));
            }
            else
                $othersArrestedList[] = $m;
        }
        $arrestedUsers = implode(', ', $arrestedUsersArr);
        foreach($arrestedList AS $m)
        {
            // Send notification
            $params = "prisonTime=".$oc['prisonTimeBusted'];
            $crimesStatics->sendNotification($m['id'], 'ORGANIZED_CRIME_3_ARRESTED', $params);
        }
        foreach($othersArrestedList AS $m)
        {
            // Send notification
            $params = "users=".$arrestedUsers."&prisonTime=".$oc['prisonTimeBusted'];
            $crimesStatics->sendNotification($m['id'], 'ORGANIZED_CRIME_3_OTHERS_ARRESTED', $params);
        }
    }
    else
    {
        foreach($members AS $m)
        {
            $crimesStatics->commitCrimeFail($m['id'], $oc['waitingTimeCompletion']);
            // Send notification
            $crimesStatics->sendNotification($m['id'], 'ORGANIZED_CRIME_3_FAILED');
        }
    }
    $crimesStatics->removeOrganizedCrimeById($oc['id']);
} // /CHECKED & OK


//Check if the user is inactive for an week. then throw user into prison to fill up
$randPrisoners = $security->randInt(3, 5);
$inactivePlayers = $con->getData("
    SELECT `id` FROM `user` WHERE `lastclick` < '".(time() - (60*60*24*7))."' AND `statusID`<='7' AND `health`>'0' ORDER BY RAND() LIMIT 0, ".(int)$randPrisoners
);
//Check if there's at least one user online
$user = $con->getDataSR("SELECT `id` FROM `user` WHERE `lastclick` > '".(time() - 360)."' LIMIT 0, 1");
if(isset($user['id']) && $user['id'] > 0)
{
    //Checking if prison is empty
    $prison = $con->getDataSR("SELECT COUNT(*) AS `total` FROM `prison` WHERE `time`>= :time LIMIT 1", array(':time' => time()));
    if($prison['total'] == 0)
    {
        foreach($inactivePlayers AS $p) // Insert each user
            $con->setData("
                DELETE FROM `prison` WHERE `userID`= :uid;
                INSERT INTO `prison` (`userID`, `time`) VALUES (:uid, :pTime)
            ", array(':uid' => $p['id'], ':pTime' => time() + $security->randInt(60, 120)));
    }
} // /CHECKED & OK
