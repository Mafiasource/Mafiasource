<?PHP

$online = $user->getOnlinePlayers();
if(isset($langs["ONLINE_MSG"])) $langs["ONLINE_MSG"] = $route->replaceMessagePart($online, $langs["ONLINE_MSG"], '/{online}/');
