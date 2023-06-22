<?php


include_once 'MenuBar.php';
include_once "CardDictionary.php";
include_once "./Libraries/UILibraries2.php";
include_once './includes/functions.inc.php';
include_once "./includes/dbh.inc.php";

  $conn = GetDBConnection();
  $sql = "SELECT usersId, usersUid FROM users";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo("Problem loading user IDs");
    exit();
  }

  //mysqli_stmt_bind_param($stmt, "ss", $username, $email);
  mysqli_stmt_execute($stmt);

	$resultData = mysqli_stmt_get_result($stmt);
  $userData = [];
  while ($row = mysqli_fetch_assoc($resultData)) {
  	//return $row;
    //array_push($userData, []);
    $userID = intval($row["usersId"]);
    $userData[$userID] = [];
    $userData[$userID][0] = 1300;//Initial Elo
    $userData[$userID][1] = 0;//Num rated games
    $userData[$userID][2] = 0;//Num rated wins
    $userData[$userID][3] = $row["usersUid"];//Num rated wins
  }

	mysqli_stmt_close($stmt);



  $sql = "SELECT WinningPID, LosingPID FROM completedgame where WinningPID != 'NULL' AND LosingPID != 'NULL' AND WinningPID != LosingPID AND NumTurns > 1 ORDER BY CompletionTime ASC";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo("Problem loading completed games");
    exit();
  }

  mysqli_stmt_execute($stmt);

	$resultData = mysqli_stmt_get_result($stmt);
  $gameData = [];
  while ($row = mysqli_fetch_assoc($resultData)) {
    $WinningPID = intval($row["WinningPID"]);
    $LosingPID = intval($row["LosingPID"]);

    $winnerElo = $userData[$WinningPID][0];
    $winnerNumGames = $userData[$WinningPID][1];
    $winnerNumWins = $userData[$WinningPID][2];
    $loserElo = $userData[$LosingPID][0];
    $loserNumGames = $userData[$LosingPID][1];
    $loserNumWins = $userData[$LosingPID][2];
    $winnerNewElo = CalculateElo($winnerElo, $winnerNumGames, $winnerNumWins, $loserElo, $loserNumGames, $loserNumWins, 1);
    $loserNewElo = CalculateElo($loserElo, $loserNumGames, $loserNumWins, $winnerElo, $winnerNumGames, $winnerNumWins, 2);

    $userData[$WinningPID][0] = $winnerNewElo;
    $userData[$LosingPID][0] = $loserNewElo;
    ++$userData[$WinningPID][1];//Num rated games
    ++$userData[$LosingPID][1];//Num rated games
    ++$userData[$WinningPID][2];//Num rated wins
  }
  mysqli_stmt_close($stmt);


	mysqli_close($conn);


  echo("<div style='height:800px; overflow-y:scroll;'>");
  foreach ($userData as $key => $user) {
    echo("User Name: " . $userData[$key][3] . " Elo: " . intval($userData[$key][0]) . " Num Games: " . $userData[$key][1] . " Num Wins: " . $userData[$key][2] . "<BR>");
  }
  echo("</div>");

  function CalculateElo($p1Elo, $p1NumGames, $p1Wins, $p2Elo, $p2NumGames, $p2Wins, $winner)
  {
    $p1EffectiveGames = EffectiveNumGames($p1Elo, $p1NumGames);
    if($p1NumGames <= 8 || $p2NumGames <= 8 || $p1Wins == 0 || $p2Wins == 0 || $p1Wins == $p1NumGames || $p2Wins == $p2NumGames) {
      return EloSpecialFormula($p1Elo, $p1NumGames, $p1Wins, $p2Elo, $p2NumGames, $p2Wins, $winner, $p1EffectiveGames);
    }
    else {
      return EloStandardFormula($p1Elo, $p1NumGames, $p1Wins, $p2Elo, $p2NumGames, $p2Wins, $winner, $p1EffectiveGames);
    }
  }

  function EloStandardFormula($p1Elo, $p1NumGames, $p1Wins, $p2Elo, $p2NumGames, $p2Wins, $winner, $p1EffectiveGames)
  {
    $gameScore = ($winner == 1 ? 1 : 0);

    $winExpectation = 1 / (1 + pow(10, -1*($p1Elo - $p2Elo)/400));
    $kFactor = 800 / ($p1EffectiveGames + 1);
    $newRating = $p1Elo + $kFactor * ($gameScore - $winExpectation);
    return $newRating;
  }

  function EloSpecialFormula($p1Elo, $p1NumGames, $p1Wins, $p2Elo, $p2NumGames, $p2Wins, $winner, $p1EffectiveGames)
  {
    $p1EffectiveGames = intval($p1EffectiveGames);
    $gameScore = ($winner == 1 ? 1 : 0);
    if($p1NumGames > 0 && $p1Wins == $p1NumGames)
    {
      $auxElo = $p1Elo - 400;
      $auxScore = $gameScore + $p1EffectiveGames;
    }
    else if($p1NumGames > 0 and $p1Wins == 0)
    {
      $auxElo = $p1Elo + 400;
      $auxScore = $gameScore;
    }
    else
    {
      $auxElo = $p1Elo;
      $auxScore = $gameScore + ($p1EffectiveGames / 2);
    }

    $accuracyParameter = pow(10, -7);//z1
    $eloSet = [];
    $eloSet[0] = $auxElo - 400;
    $eloSet[1] = $auxElo + 400;
    $eloSet[2] = $p2Elo - 400;
    $eloSet[3] = $p2Elo + 400;

    $newEloEstimate = ($p1EffectiveGames * $auxElo + $p2Elo + 400 * (2 * $gameScore - 1)) / ($p1EffectiveGames + 1);//M

    while(ObjectiveFunction($newEloEstimate, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames) > $accuracyParameter)
    {
      $maxEl = -9999;//z1
      for($i=0; $i<count($eloSet); ++$i)
      {
        if($eloSet[$i] < $newEloEstimate && $eloSet[$i] > $maxEl) $maxEl = $eloSet[$i];
      }
      //End z1 calculation
      if(abs(ObjectiveFunction($newEloEstimate, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames) - ObjectiveFunction($maxEl, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames)) < $accuracyParameter)
      {
        $newEloEstimate = $maxEl;
      }
      else {
        $mStar = $newEloEstimate - ObjectiveFunction($newEloEstimate, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames) * ($newEloEstimate - $maxEl) / (ObjectiveFunction($newEloEstimate, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames) - ObjectiveFunction($maxEl, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames));
        if($mStar < $maxEl)
        {
          $newEloEstimate = $maxEl;
        }
        else if($maxEl <= $mStar && $mStar < $newEloEstimate)
        {
          $newEloEstimate = $mStar;
        }
        else {
          break;
        }
      }
    }

    while(ObjectiveFunction($newEloEstimate, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames) < (-1 *$accuracyParameter))
    {
      $minEl = 9999;//z2
      for($i=0; $i<count($eloSet); ++$i)
      {
        if($eloSet[$i] > $newEloEstimate && $eloSet[$i] < $minEl) $minEl = $eloSet[$i];
      }
      //End z2 calculation
      if(abs(ObjectiveFunction($newEloEstimate, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames) - ObjectiveFunction($minEl, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames)) < $accuracyParameter)
      {
        $newEloEstimate = $minEl;
      }
      else {
        $mStar = $newEloEstimate - ObjectiveFunction($newEloEstimate, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames) * ($minEl - $newEloEstimate) / (ObjectiveFunction($minEl, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames) - ObjectiveFunction($newEloEstimate, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames));
        if($mStar > $minEl)
        {
          $newEloEstimate = $minEl;
        }
        else if($newEloEstimate < $mStar && $mStar <= $minEl)
        {
          $newEloEstimate = $mStar;
        }
        else {
          break;
        }
      }
    }


    if(abs(ObjectiveFunction($newEloEstimate, $p2Elo, $auxElo, $auxScore, $p1EffectiveGames) <= $accuracyParameter) && abs($newEloEstimate - $p2Elo) > 400 && abs($newEloEstimate - $auxElo) > 400)
    {
      $maxEl = -9999;//z1
      for($i=0; $i<count($eloSet); ++$i)
      {
        if($eloSet[$i] < $newEloEstimate && $eloSet[$i] > $maxEl) $maxEl = $eloSet[$i];
      }
      //End z1 calculation

      $minEl = 9999;//z2
      for($i=0; $i<count($eloSet); ++$i)
      {
        if($eloSet[$i] > $newEloEstimate && $eloSet[$i] < $minEl) $minEl = $eloSet[$i];
      }
      //End z2 calculation

      if($maxEl <= $p1Elo && $p1Elo <= $minEl)
      {
        $newEloEstimate = $p1Elo;
      }
      else if($p1Elo < $maxEl) {
        $newEloEstimate = $maxEl;
      }
      else
      {
        $newEloEstimate = $minEl;
      }
    }

    return $newEloEstimate;
  }

	//f(R1) = N’ * PWe(R1, R’) + PWe(R1, R_opponent) - S’
  function ObjectiveFunction($elo, $oppElo, $auxElo, $auxScore, $effectiveGames)
  {
    return $effectiveGames * ProvisionWinExpectation($elo, $auxElo) + ProvisionWinExpectation($elo, $oppElo) - $auxScore;
  }

  function ProvisionWinExpectation($p1Elo, $p2Elo)
  {
    if($p1Elo <= ($p2Elo - 400))
    {
      return 0;
    }
    else if(($p2Elo - 400) < $p1Elo && $p1Elo < ($p2Elo + 400))
    {
      return 0.5 + ($p1Elo - $p2Elo) / 800;
    }
    else
    {
      return 1;
    }
  }

  //ELO = Floating point
  //numGames = integer
  //Returns: Effective Games (Integer)
  function EffectiveNumGames($elo, $numGames)
  {
    $nStar = $numGames;
    if($elo > 2355) {
      $nStar = 50;
    }
    else {
      $nStar = 50 / sqrt(0.622 + 0.00000739 * pow(2569 - $elo, 2));
      $nStar = intval($nStar);
    }
    return min($numGames, $nStar);
  }
