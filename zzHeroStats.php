<?php

  include "CardDictionary.php";
  include "./Libraries/UILibraries2.php";
  require_once "./includes/dbh.inc.php";

  $heroID=$_GET["heroID"];

  echo("<script src=\"./jsInclude.js\"></script>");

  echo("<style>td {
  border: 1px solid black;
}</style>");
echo("<div id=\"cardDetail\" style=\"z-index:100000; display:none; position:fixed;\"></div>");

echo("<div>Detailed stats for " . CardLink($heroID, $heroID) . "</div>");

    $sql = "SELECT WinningHero,LosingHero,count(WinningHero) AS Count,WinnerDeck
FROM completedgame
WHERE WinningHero=\"$heroID\" and LosingHero<>\"DUMMY\"
GROUP by LosingHero
ORDER BY Count";
  	$stmt = mysqli_stmt_init($conn);
  	if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo("ERROR");
  		exit();
  	}
  	mysqli_stmt_execute($stmt);
  	$winData = mysqli_stmt_get_result($stmt);


    $sql = "SELECT WinningHero,LosingHero,WinnerDeck
FROM completedgame
WHERE WinningHero=\"$heroID\" and LosingHero<>\"DUMMY\"";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo("ERROR");
      exit();
    }
    mysqli_stmt_execute($stmt);
    $winCardData = mysqli_stmt_get_result($stmt);


        $sql = "SELECT WinningHero,LosingHero,count(LosingHero) AS Count,LoserDeck
    FROM completedgame
    WHERE WinningHero<>\"DUMMY\" and LosingHero=\"$heroID\"
    GROUP by WinningHero
    ORDER BY Count";
      	$stmt = mysqli_stmt_init($conn);
      	if (!mysqli_stmt_prepare($stmt, $sql)) {
          echo("ERROR");
      		exit();
      	}
      	mysqli_stmt_execute($stmt);
      	$loseData = mysqli_stmt_get_result($stmt);

        $sql = "SELECT WinningHero,LosingHero,LoserDeck
    FROM completedgame
    WHERE WinningHero<>\"DUMMY\" and LosingHero=\"$heroID\"";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          echo("ERROR");
          exit();
        }
        mysqli_stmt_execute($stmt);
        $loseCardData = mysqli_stmt_get_result($stmt);


  $gameData = [];
  $cardData = [];
  while ($row = mysqli_fetch_array($winData, MYSQLI_NUM)) {
    array_push($gameData, []);
    $index = count($gameData)-1;
    $gameData[$index][0] = $row[1];
    $gameData[$index][1] = $row[2];
    $gameData[$index][2] = 0;
  }

  while ($row = mysqli_fetch_array($winCardData, MYSQLI_NUM)) {
    if(count($row) < 3) continue;//no deck
    $deck = explode("\r\n", $row[2]);
    if(count($deck) == 1) $deck = explode("\n", $row[2]);
    if(count($deck) == 1) continue;
    $character = explode(" ", $deck[0]);
    $cards = explode(" ", $deck[1]);
    for($i=0; $i<count($cards); ++$i)
    {
      $card = $cards[$i];
      if($i > 0 && $card == $cards[$i-1]) continue;//Make sure cards count only once; assumes deck is sorted
      if(!array_key_exists($card, $cardData))
      {
        $cardData[$card] = [];
        $cardData[$card][0] = 0;
        $cardData[$card][1] = 0;
      }
      ++$cardData[$card][0];
      ++$cardData[$card][1];
    }
  }

  while ($row = mysqli_fetch_array($loseData, MYSQLI_NUM)) {
    $heroID = $row[0];
    for($i=0; $i<count($gameData) && $gameData[$i][0] != $heroID; ++$i);
    //$value = (count($row) < 3 ? 0 : $row[2]);
    if($i < count($gameData))
    {
      $gameData[$i][2] = $row[2];
    }
    else {
      array_push($gameData, []);
      $index = count($gameData)-1;
      $gameData[$index][0] = $row[0];
      $gameData[$index][1] = 0;//If we get here, there were no wins
      $gameData[$index][2] = $row[2];
    }

  }

  while ($row = mysqli_fetch_array($loseCardData, MYSQLI_NUM)) {
        //Parse Cards
        if(count($row) < 3) continue;//no deck
        $deck = explode("\r\n", $row[2]);
        if(count($deck) == 1) $deck = explode("\n", $row[2]);
        if(count($deck) == 1) continue;
        $character = explode(" ", $deck[0]);
        $cards = explode(" ", $deck[1]);
        for($i=0; $i<count($cards); ++$i)
        {
          if($i > 0 && $cards[$i] == $cards[$i-1]) continue;//Make sure cards count only once; assumes deck is sorted
          $card = $cards[$i];
          if(!array_key_exists($card, $cardData))
          {
            $cardData[$card] = [];
            $cardData[$card][0] = 0;
            $cardData[$card][1] = 0;
          }
          ++$cardData[$card][1];
        }
  }


  echo("<div style='display:inline-block;'>");
  echo("<table>");
  echo("<tr><td>Opposing Hero</td><td>Num Wins</td><td>Num Losses</td><td>Win %</td></tr>");

  $totalWins = 0;
  $totalGames = 0;
  $deckTotalWins = 0;
  $deckTotalGames = 0;
  foreach ($gameData as $row) {
  //while ($row = mysqli_fetch_array($playData, MYSQLI_NUM)) {
    echo("<tr>");
    echo("<td><a href='./zzHeroStats.php?heroID=$row[0]'>" . CardLink($row[0], $row[0]) . "</a></td>");
    echo("<td>" . $row[1] . "</td>");
    echo("<td>" . $row[2] . "</td>");
    echo("<td>" . (($row[1] / ($row[1] + $row[2])) * 100) . "% </td>");
    echo("</tr>");
    $totalWins += $row[1];
    $totalGames += $row[1] + $row[2];
    if(count($row) > 3 && count(explode(" ", $row[3])) > 1)
    {
      $deckTotalWins += $row[1];
      $deckTotalGames += $row[1] + $row[2];
    }
  }
  echo("</table>");
  echo("</div>");

  $totalWinrate = $totalWins / $totalGames;
  $deckTotalWinrate = ($deckTotalGames > 0 ? $deckTotalWins / $deckTotalGames : 0);

  $sortedCardData = [];
  while(count($cardData) > 0)
  {
    $maxWinrate = -1;
    $bestKey = "";
    foreach($cardData as $key => $card)
    {
      $winRate = $card[0] / $card[1];
      if($winRate > $maxWinrate)
      {
        $maxWinrate = $winRate;
        $bestKey = $key;
      }
    }
    $sortedCardData[$bestKey] = [];
    $sortedCardData[$bestKey][0] = $cardData[$bestKey][0];
    $sortedCardData[$bestKey][1] = $cardData[$bestKey][1];
    unset($cardData[$bestKey]);
  }

  echo("<div style='display:inline-block;'>");
  echo("<table>");
  echo("<tr><td>Card</td><td>Num Plays</td><td>Win Rate</td><td>Relative Win Rate</td></tr>");
  foreach($sortedCardData as $key => $card)
  {
    if($card[1] < 10) continue;
    echo("<tr>");
    echo("<td>" . CardLink($key, $key) . "</td>");
    echo("<td>" . $card[1] . "</td>");
    echo("<td>" . number_format($card[0] / $card[1], 2, ".", "") . "</td>");
    echo("<td>" . number_format((($card[0] / $card[1]) - $totalWinrate) * 100, 2, ".", "") . "%</td>");
    echo("</tr>");
  }
  echo("</table>");
  echo("</div>");
?>
