<?php
  
  include "WriteLog.php";
  include "GameLogic.php";
  include "GameTerms.php";
  include "HostFiles/Redirector.php";

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];

  include "ParseGamestate.php";

  $myHealth = CharacterHealth($myCharacter[0]);
  $theirHealth = CharacterHealth($theirCharacter[0]);

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

  ProcessDecisionQueue();

  include "WriteGamestate.php";

  header("Location: " . $redirectPath . "/NextTurn.php?gameName=$gameName&playerID=1");

?>

Something is wrong with the XAMPP installation :-(
