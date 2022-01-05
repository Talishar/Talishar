<?php
  
  include "WriteLog.php";
  include "GameLogic.php";
  include "GameTerms.php";
  include "HostFiles/Redirector.php";
  include "Libraries/StatFunctions.php";
  include "Libraries/PlayerSettings.php";
  include "AI/CombatDummy.php";
  include "Libraries/HTTPLibraries.php";

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];

  include "ParseGamestate.php";

  array_push($layerPriority, ShouldHoldPriority(1));
  array_push($layerPriority, ShouldHoldPriority(2));

  $myHealth = CharacterHealth($myCharacter[0]);
  $theirHealth = CharacterHealth($theirCharacter[0]);

  $chooser = 1;
  if($p2CharEquip[0] != "DUMMY")
  {
    $p1roll = 0; $p2roll = 0;
    $tries = 10;
    while($p1roll == $p2roll && $tries > 0)
    {
      $p1roll = rand(1,6) + rand(1, 6);
      $p2roll = rand(1,6) + rand(1, 6);
      WriteLog("Player 1 rolled $p1roll and Player 2 rolled $p2roll.");
      --$tries;
    }
    $chooser = ($p1roll > $p2roll ? 1 : 2);
  }
  WriteLog("Player $chooser chooses who goes first.");
  AddDecisionQueue("CHOOSEFIRSTPLAYER", $chooser, "Go_first,Go_second");
  AddDecisionQueue("SETFIRSTPLAYER", $chooser, "-");

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

  include "WriteGamestate.php";

  header("Location: " . $redirectPath . "/NextTurn2.php?gameName=$gameName&playerID=1");

?>

Something is wrong with the XAMPP installation :-(
