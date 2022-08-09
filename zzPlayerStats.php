<?php

include_once "CardDictionary.php";
include_once "./Libraries/UILibraries2.php";
include_once "./Libraries/HTTPLibraries.php";
include_once './includes/functions.inc.php';
include_once "./includes/dbh.inc.php";

if (!isset($_SESSION["userid"])) {
  echo ("Please login to view this page.");
  exit;
}
$userID = $_SESSION["userid"];

if (!isset($_SESSION["isPatron"])) {
  echo ("Please subscribe to our Patreon to access this page.");
  exit;
}

$numDays = TryGet("numDays", 365);

echo ("<script src=\"./jsInclude.js\"></script>");

echo ("<style>

td {
  border-bottom: 1px solid black;
  text-align: center;
  vertical-align: middle;
  border-spacing: 0;
  border-collapse: collapse;
  height: 50px;
  padding: 5px;
}

tr:hover {
  background-color: DarkRed;
}

h3 {
  text-align: center;
  font-size: 1.25em;
  padding-bottom: 10px;
}

</style>");
echo ("<div id=\"cardDetail\" style=\"z-index:100000; display:none; position:fixed;\"></div>");

$sql = "SELECT Hero,sum(Count) AS Total FROM
(
select WinningHero As Hero,count(WinningHero) AS Count
from completedgame
where WinningPID = '$userID'
group by WinningHero
union all
select LosingHero As Hero,count(LosingHero) AS Count
from completedgame
where LosingPID = '$userID'
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
where WinningPID = '$userID'
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

echo ("<div style='height:75vh; overflow-y:scroll;'><table>");
echo ("<tr><td>Hero</td><td>Num Wins</td><td>Num Plays</td><td>Win %</td><td>Played %</td></tr>");

echo ("<h3>CC Heroes</h3>");
foreach ($gameData as $row) {
  //while ($row = mysqli_fetch_array($playData, MYSQLI_NUM)) {
  if (CharacterHealth($row[0]) <= 25) continue; //Filter out blitz heroes for now
  //if(CharacterHealth($row[0]) > 25) continue;//Filter out cc heroes for now
  $formatDenominator = (CharacterHealth($row[0]) > 25 ? $ccPlays : $blitzPlays);
  $winPercent = (((count($row) > 2 ? $row[2] : 0) / $row[1]) * 100);
  $playPercent = ($row[1] / $formatDenominator * 100);
  echo ("<tr>");
  //echo ("<td><a href='./zzHeroStats.php?heroID=$row[0]'>" . CardLink($row[0], $row[0]) . "</a></td>");
  echo ("<td>" . CardLink($row[0], $row[0], true) . "</td>");
  echo ("<td>" . (count($row) > 2 ? $row[2] : 0) . "</td>");
  echo ("<td>" . $row[1] . "</td>");
  echo ("<td>" . number_format($winPercent, 2, ".", "") . "% </td>");
  echo ("<td>" . number_format($playPercent, 2, ".", "") . "% </td>");
  echo ("</tr>");
}
echo ("</table>");

echo ("<BR>");
echo ("<h3>Young Heroes</h3>");
echo ("<table>");
echo ("<tr><td>Hero</td><td>Num Wins</td><td>Num Plays</td><td>Win %</td><td>Played %</td></tr>");

foreach ($gameData as $row) {
  //while ($row = mysqli_fetch_array($playData, MYSQLI_NUM)) {
  if (CharacterHealth($row[0]) > 25) continue; //Filter out cc heroes for now
  $formatDenominator = (CharacterHealth($row[0]) > 25 ? $ccPlays : $blitzPlays);
  $winPercent = (((count($row) > 2 ? $row[2] : 0) / $row[1]) * 100);
  $playPercent = ($row[1] / $formatDenominator * 100);
  echo ("<tr>");
  //echo ("<td><a href='./zzHeroStats.php?heroID=$row[0]'>" . CardLink($row[0], $row[0]) . "</a></td>");
  echo ("<td>" . CardLink($row[0], $row[0], true) . "</td>");
  echo ("<td>" . (count($row) > 2 ? $row[2] : 0) . "</td>");
  echo ("<td>" . $row[1] . "</td>");
  echo ("<td>" . number_format($winPercent, 2, ".", "") . "% </td>");
  echo ("<td>" . number_format($playPercent, 2, ".", "") . "% </td>");
  echo ("</tr>");
}
echo ("</table><div>");
