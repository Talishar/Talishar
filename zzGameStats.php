<?php

  include "CardDictionary.php";
  include "./Libraries/UILibraries2.php";
  require_once "./includes/dbh.inc.php";

  echo("<script src=\"./jsInclude.js\"></script>");

  echo("<style>td {
  border: 1px solid black;
}</style>");
echo("<div id=\"cardDetail\" style=\"z-index:100000; display:none; position:fixed;\"></div>");

  $sql = "SELECT Hero,sum(Count) AS Total FROM
(
select WinningHero As Hero,count(WinningHero) AS Count
from completedgame
where WinningHero<>\"DUMMY\" and LosingHero<>\"DUMMY\"
group by WinningHero
union all
select LosingHero As Hero,count(LosingHero) AS Count
from completedgame
where WinningHero<>\"DUMMY\" and LosingHero<>\"DUMMY\"
group by LosingHero
) AS internalQuery
GROUP BY Hero
ORDER BY Total DESC";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 	//header("location: ../Signup.php?error=stmtfailed");
    echo("ERROR");
		exit();
	}

	//mysqli_stmt_bind_param($stmt, "ss", $username, $email);
	mysqli_stmt_execute($stmt);

	// "Get result" returns the results from a prepared statement
	$playData = mysqli_stmt_get_result($stmt);


    $sql = "SELECT WinningHero,count(WinningHero) AS Count
FROM completedgame
WHERE WinningHero<>\"DUMMY\" and LosingHero<>\"DUMMY\"
GROUP by WinningHero
ORDER BY Count";
  	$stmt = mysqli_stmt_init($conn);
  	if (!mysqli_stmt_prepare($stmt, $sql)) {
  	 	//header("location: ../Signup.php?error=stmtfailed");
      echo("ERROR");
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
    $index = count($gameData)-1;
    $gameData[$index][0] = $row[0];
    $gameData[$index][1] = $row[1];
    if(CharacterHealth($row[0]) > 25) $ccPlays += $row[1];
    else $blitzPlays += $row[1];
  }

  while ($row = mysqli_fetch_array($winData, MYSQLI_NUM)) {
    $heroID = $row[0];
    for($i=0; $i<count($gameData) && $gameData[$i][0] != $heroID; ++$i);
    array_push($gameData[$i], $row[1]);
  }

  echo("<table>");
  echo("<tr><td>Hero</td><td>Num Wins</td><td>Num Plays</td><td>Win %</td><td>Played %</td></tr>");

  foreach ($gameData as $row) {
  //while ($row = mysqli_fetch_array($playData, MYSQLI_NUM)) {
    echo("<tr>");
    echo("<td>" . CardLink($row[0], $row[0]) . "</td>");
    echo("<td>" . (count($row) > 2 ? $row[2] : 0) . "</td>");
    echo("<td>" . $row[1] . "</td>");
    echo("<td>" . (((count($row) > 2 ? $row[2] : 0) / $row[1]) * 100) . "% </td>");
    $formatDenominator = (CharacterHealth($row[0]) > 25 ? $ccPlays : $blitzPlays);
    echo("<td>" . ($row[1] / $formatDenominator * 100) . "% </td>");
    echo("</tr>");
  }
  echo("</table>");

?>
