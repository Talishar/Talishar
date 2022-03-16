<?php

  include "WriteLog.php";
  include "GameLogic.php";
  include "GameTerms.php";
  include "HostFiles/Redirector.php";
  include "Libraries/StatFunctions.php";
  include "Libraries/PlayerSettings.php";
  include "AI/CombatDummy.php";
  include "Libraries/HTTPLibraries.php";
  include "Libraries/SHMOPLibraries.php";
  include "WriteReplay.php";

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];

  include "ParseGamestate.php";
  include "MenuFiles/ParseGamefile.php";

  array_push($layerPriority, ShouldHoldPriority(1));
  array_push($layerPriority, ShouldHoldPriority(2));

  $myHealth = CharacterHealth($myCharacter[0]);
  $theirHealth = CharacterHealth($theirCharacter[0]);
  StartReplay();

  $mainPlayer = $firstPlayer;
  $currentPlayer = $firstPlayer;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  StatsStartTurn();

  if($myCharacter[0] == "ARC001" || $myCharacter[0] == "ARC002")
  {
      $items = SearchMyDeck("", "Item", 2);
      AddDecisionQueue("CHOOSEDECK", 1, $items);
      AddDecisionQueue("PUTPLAY", 1, "-");
  }
  if($theirCharacter[0] == "ARC001" || $theirCharacter[0] == "ARC002")
  {
      $items = SearchTheirDeck("", "Item", 2);
      AddDecisionQueue("CHOOSEDECK", 2, $items);
      AddDecisionQueue("PUTPLAY", 2, "-");
  }

  AddDecisionQueue("STARTTURNABILITIES", $mainPlayer, "-");

  ProcessDecisionQueue();

  DoGamestateUpdate();
  include "WriteGamestate.php";

  WriteCache($gameName, strval(round(microtime(true) * 1000)));

  header("Location: " . $redirectPath . "/NextTurn3.php?gameName=$gameName&playerID=1");

?>

Something is wrong with the XAMPP installation :-(
