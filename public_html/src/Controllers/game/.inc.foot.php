<?PHP

$message = $route->setActionMessage();
$twigVars = array(
    'routing' => $route,
    'settings' => $route->settings,
    'securityToken' => $security->getToken(),
    'langs' => $langs,
    'lang' => $lang,
    'message' => $message,
    'userData' => $userData,
    'online' => $user->getOnlinePlayers(),
    'prisonersCount' => $user->getPrisonersCount(),
    'time' => time(),
    'serverTime' => $serverTime,
    'statusDonatorColors' => $user->getStatusAndDonatorColors(),
    'lastShoutboxID' => $lastShoutboxID,
    'lastFamilyShoutboxID' => $lastFamilyShoutboxID,
    'unvotedPoll' => $unvotedPoll,
    'offline' => OFFLINE
);
$twigVars['langs']['TRAVELING'] = $route->replaceMessagePart($travelCounter, $twigVars['langs']['TRAVELING'], '/{sec}/');
if(strtotime("2022-01-28 14:00:00") < strtotime('now') && strtotime("2022-02-01 14:00:00") > strtotime('now'))
{
    $twigVars['eventName'] = "Levels XP x2";
    $twigVars['eventCountdown'] = countdownHmsTime("EventCountdown", strtotime("2022-02-01 14:00:00") - time());
}
if(strtotime("2021-12-07 14:00:00") < strtotime('now') && strtotime("2021-12-10 14:00:00") > strtotime('now'))
{
    $twigVars['eventName'] = "Credits x2";
    $twigVars['eventCountdown'] = countdownHmsTime("EventCountdown", strtotime("2021-12-10 14:00:00") - time());
}
if(strtotime("2022-11-28 14:00:00") < strtotime('now') && strtotime("2022-12-02 14:00:00") > strtotime('now'))
{
    $twigVars['eventName'] = $twigVars['langs']['WAITING_TIMES'] . " /2";
    $twigVars['eventCountdown'] = countdownHmsTime("EventCountdown", strtotime("2022-12-02 14:00:00") - time());
}
if(strtotime("2022-01-07 00:00:00") < strtotime('now') && strtotime("2022-01-07 23:59:59") > strtotime('now'))
{
    $twigVars['eventName'] = $twigVars['langs']['MERCENARIES'] . " /2";
    $twigVars['eventCountdown'] = countdownHmsTime("EventCountdown", strtotime("2022-01-07 23:59:59") - time());
}
