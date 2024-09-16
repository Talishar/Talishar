<?php

  include "Libraries/HTTPLibraries.php";

  $playerID = $_GET["playerID"];
  $gameName = $_GET["gameName"];
  if (!IsGameNameValid($gameName)) {
    echo ("Invalid game name.");
    exit;
  }

  include "./Libraries/SHMOPLibraries.php";
  include "./ParseGamestate.php";
  include_once "./includes/functions.inc.php";
  include "./GameLogic.php";
  include "./Libraries/UILibraries.php";
  include "./Libraries/StatFunctions.php";
  include "./Libraries/PlayerSettings.php";
  include_once 'Assets/patreon-php-master/src/PatreonDictionary.php';
  include "./GameTerms.php";
  include "./HostFiles/Redirector.php";

  echo(SerializeGameResult($playerID, "", file_get_contents("./Games/" . $gameName . "/p" . $playerID . "Deck.txt"), $gameName, includeFullLog:false));

?>
