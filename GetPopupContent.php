<?php

  include "./Libraries/HTTPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];
  $authKey=$_GET["authKey"];
  $popupType=$_GET["popupType"];

  ob_start();
  include "./ParseGamestate.php";
  include "./GameLogic.php";
  include "./Libraries/UILibraries2.php";
  include "./Libraries/StatFunctions.php";
  include "./Libraries/PlayerSettings.php";
  include "./GameTerms.php";
  ob_end_clean();

  $cardSize = 120;
  switch($popupType)
  {
    case "myPitchPopup": echo(CreatePopup("myPitchPopup", $myPitch, 1, 0, "Your Pitch")); break;
    case "myDiscardPopup": echo(CreatePopup("myDiscardPopup", $myDiscard, 1, 0, "Your Discard")); break;
    case "myBanishPopup": echo(CreatePopup("myBanishPopup", [], 1, 0, "Your Banish", 1, BanishUI())); break;
    case "myStatsPopup": echo(CreatePopup("myStatsPopup", [], 1, 0, "Your Game Stats", 1, CardStats($playerID), "./", true)); break;
    case "menuPopup": echo(CreatePopup("menuPopup", [], 1, 0, "Main Menu", 1, MainMenuUI(), "./", true)); break;
    case "mySoulPopup": echo(CreatePopup("mySoulPopup", $mySoul, 1, 0, "My Soul")); break;
    case "theirBanishPopup":
      $theirBanishDisplay = GetTheirBanishForDisplay();
      echo(CreatePopup("theirBanishPopup", $theirBanishDisplay, 1, 0, "Opponent's Banish Zone"));
      break;
    case "theirPitchPopup": echo(CreatePopup("theirPitchPopup", $theirPitch, 1, 0, "Opponent's Pitch Zone")); break;
    case "theirDiscardPopup": echo(CreatePopup("theirDiscardPopup", $theirDiscard, 1, 0, "Opponent's Discard Zone")); break;
    case "theirSoulPopup": echo(CreatePopup("theirSoulPopup", $theirSoul, 1, 0, "Opponent's Soul")); break;
    default: break;
  }

?>
