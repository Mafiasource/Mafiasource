<?PHP

use src\Business\StatisticService;

$statistic = new StatisticService();
$statistics = $statistic->getOutgameStatistics();
$bestPlayer = $statistics['bestPlayer'];
$newestMember = $statistics['newestMember'];
$bestFam = $statistics['bestFam'];
$richestFam = $statistics['richestFam'];
$killerking = $statistics['killerking'];
$honored = $statistics['honored'];
$playersKilled = "<span class='red'>" . $statistics['playersKilled'] . "</span>";
$unitsSmuggled = $statistics['unitsSmuggled'];
$creditsWon = $statistics['creditsWon'] . " Credits";
$roundInfo = "Eerste ronde sinds 28 Dec";
if($lang == "en") $roundInfo = "First round since Dec 28th";
$langs["STATS"] = $cms->getCMSById(5, $lang);
$langs["STATS"] = $route->replaceMessagePart("<strong>".$bestPlayer."</strong>", $langs["STATS"], '/{bestPlayer}/');
$langs["STATS"] = $route->replaceMessagePart("<strong>".$newestMember."</strong>", $langs["STATS"], '/{newestMember}/');
$langs["STATS"] = $route->replaceMessagePart("<strong>".$bestFam."</strong>", $langs["STATS"], '/{bestFam}/');
$langs["STATS"] = $route->replaceMessagePart("<strong>".$richestFam."</strong>", $langs["STATS"], '/{richestFam}/');
$langs["STATS"] = $route->replaceMessagePart("<strong>".$killerking."</strong>", $langs["STATS"], '/{killerking}/');
$langs["STATS"] = $route->replaceMessagePart("<strong>".$honored."</strong>", $langs["STATS"], '/{honored}/');
$langs["STATS"] = $route->replaceMessagePart("<strong>".$playersKilled."</strong>", $langs["STATS"], '/{playersKilled}/');
$langs["STATS"] = $route->replaceMessagePart("<strong>".$unitsSmuggled."</strong>", $langs["STATS"], '/{unitsSmuggled}/');
$langs["STATS"] = $route->replaceMessagePart("<strong>".$creditsWon."</strong>", $langs["STATS"], '/{creditsWon}/');
$langs["STATS"] = $route->replaceMessagePart("<strong>".$roundInfo."</strong>", $langs["STATS"], '/{roundInfo}/');
