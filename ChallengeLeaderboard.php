<?php

include_once 'Header.php';
include_once "./Libraries/UILibraries2.php";
include_once "./Libraries/HTTPLibraries.php";
include_once './includes/functions.inc.php';
include_once "./includes/dbh.inc.php";

$sql = "SELECT playerId,result,count(*) as theCount, (select usersUid from users where users.usersId = challengeresult.playerId) as theUser FROM `challengeresult` WHERE challengeId=3 AND result='1' GROUP BY playerId,result ORDER BY theCount DESC";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  echo ("ERROR");
  exit();
}

mysqli_stmt_execute($stmt);

// "Get result" returns the results from a prepared statement
$challengeResults = mysqli_stmt_get_result($stmt);

echo("<table>");
echo("<tr><th>Player</th><th>Wins</th></tr>");
while ($row = mysqli_fetch_array($challengeResults, MYSQLI_NUM)) {
  echo("<tr>");
  echo("<td>" . $row[2] . "</td>");
  echo("<td>" . $row[3] . "</td>");
  echo("</tr>");
}
echo("</table>");
