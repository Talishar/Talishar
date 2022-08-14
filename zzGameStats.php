<?php

include_once 'Header.php';
include "CardDictionary.php";
include "./Libraries/UILibraries2.php";
include "./Libraries/HTTPLibraries.php";
include_once './includes/functions.inc.php';
include_once "./includes/dbh.inc.php";

if (!isset($_SESSION["useruid"])) {
  echo ("Please login to view this page.");
  exit;
}
$forIndividual = TryGet("forPlayer", false);
$forIndividual = ($forIndividual ? true : false);//If it evaluates to true, explicitly cast it to boolean
$useruid = $_SESSION["useruid"];
$userid = $_SESSION["userid"];
if (!$forIndividual && $useruid != "OotTheMonk" && $useruid != "Kugane" && $useruid != "Kugane2" && $useruid != "PvtVoid" && $useruid != "grog" && $useruid != "underscore" && $useruid != "HelpMeJace2" && $useruid != "Matt" && $useruid != "jacob") exit;

$numDays = TryGet("numDays", 365);

echo ("<script src=\"./jsInclude.js\"></script>");

echo ("<style>

table {
  border: 3px solid black;
  border-radius: 10px;
  border-spacing: 0;
  border-collapse: collapse;
  background: rgba(74, 74, 74);
  font-size: 1em;
}

td {
  border-bottom: 1px solid black;
  text-align: center;
  vertical-align: middle;
  height: 50px;
  padding: 10px;
}

tr:hover {
  background-color: darkred;
}

h3 {
  text-align: center;
  font-size: 1.25em;
  padding-top: 15px;
}
</style>");

echo ("<div id=\"cardDetail\" style=\"z-index:100000; display:none; position:fixed;\"></div>");

$sql = "SELECT Hero,sum(Count) AS Total FROM
(
select WinningHero As Hero,count(WinningHero) AS Count
from completedgame
where WinningHero<>\"DUMMY\" and LosingHero<>\"DUMMY\" and CompletionTime >= DATE(NOW() - INTERVAL $numDays DAY)
group by WinningHero
union all
select LosingHero As Hero,count(LosingHero) AS Count
from completedgame
where WinningHero<>\"DUMMY\" and LosingHero<>\"DUMMY\" and CompletionTime >= DATE(NOW() - INTERVAL $numDays DAY)
group by LosingHero
) AS internalQuery
GROUP BY Hero
ORDER BY Total DESC";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  //header("location: ../Signup.php?error=stmtfailed");
  echo ("ERROR");
  exit();
}

//mysqli_stmt_bind_param($stmt, "ss", $username, $email);
mysqli_stmt_execute($stmt);

// "Get result" returns the results from a prepared statement
$playData = mysqli_stmt_get_result($stmt);


$sql = "SELECT WinningHero,count(WinningHero) AS Count
FROM completedgame
WHERE WinningHero<>\"DUMMY\" and LosingHero<>\"DUMMY\" and CompletionTime >= DATE(NOW() - INTERVAL $numDays DAY)
GROUP by WinningHero
ORDER BY Count";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  //header("location: ../Signup.php?error=stmtfailed");
  echo ("ERROR");
  exit();
}

//mysqli_stmt_bind_param($stmt, "ss", $username, $email);
mysqli_stmt_execute($stmt);

// "Get result" returns the results from a prepared statement
$winData = mysqli_stmt_get_result($stmt);

$blitzPlays = 0;
$ccPlays = 0;
$gameData = [];
while ($row = mysqli_fetch_array($playData, MYSQLI_NUM)) {
  array_push($gameData, []);
  $index = count($gameData) - 1;
  $gameData[$index][0] = $row[0];
  $gameData[$index][1] = $row[1];
  if (CharacterHealth($row[0]) > 25) $ccPlays += $row[1];
  else $blitzPlays += $row[1];
}

while ($row = mysqli_fetch_array($winData, MYSQLI_NUM)) {
  $heroID = $row[0];
  for ($i = 0; $i < count($gameData) && $gameData[$i][0] != $heroID; ++$i);
  array_push($gameData[$i], $row[1]);
}
echo ("<h3>Click Heroes For More Details</h3>");
echo ("<section class='game-stats' style='display: flex; justify-content: center;'>");
echo ("<div class='game-stats-div'>");
echo ("<table>");
echo ("<tr><td>Hero</td><td>Num Wins</td><td>Num Plays</td><td>Win %</td><td>Played %</td></tr>");

foreach ($gameData as $row) {
  //while ($row = mysqli_fetch_array($playData, MYSQLI_NUM)) {
  if (CharacterHealth($row[0]) <= 25) continue; //Filter out blitz heroes for now
  //if(CharacterHealth($row[0]) > 25) continue;//Filter out cc heroes for now
  $formatDenominator = (CharacterHealth($row[0]) > 25 ? $ccPlays : $blitzPlays);
  $winPercent = (((count($row) > 2 ? $row[2] : 0) / $row[1]) * 100);
  $playPercent = ($row[1] / $formatDenominator * 100);
  echo ("<tr>");
  echo ("<td><a href='./zzHeroStats.php?heroID=$row[0]'>" . CardLink($row[0], $row[0], true) . "</a></td>");
  echo ("<td>" . (count($row) > 2 ? $row[2] : 0) . "</td>");
  echo ("<td>" . $row[1] . "</td>");
  echo ("<td>" . number_format($winPercent, 2, ".", "") . "% </td>");
  echo ("<td>" . number_format($playPercent, 2, ".", "") . "% </td>");
  echo ("</tr>");
}
echo ("</table>");
echo("</div>");
echo ("</section>");

include_once 'Footer.php';
