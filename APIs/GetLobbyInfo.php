<?php

  include_once "../AccountFiles/AccountSessionAPI.php";
  include_once "../CardDictionary.php";
  include_once "../Libraries/HTTPLibraries.php";
  include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";

  session_start();

  $gameName = $_POST["gameName"];
  $playerID = $_POST["playerID"];
  if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
  else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
  else if (isset($_POST["authKey"])) $authKey = $_POST["authKey"];

  session_write_close();

  if (!file_exists("../Games/" . $gameName . "/GameFile.txt")) {
    echo(json_encode(new stdClass()));
    exit;
  }

  ob_start();
  include "./APIParseGamefile.php";
  ob_end_clean();


  $yourName = ($playerID == 1 ? $p1uid : $p2uid);
  $theirName = ($playerID == 1 ? $p2uid : $p1uid);

  $response = new stdClass();
  $response->badges = [];

  $response->amIActive = true;//Is the game waiting on me to do something?

  if ($gameStatus == $MGS_ChooseFirstPlayer) $response->amIActive = $playerID == $firstPlayerChooser ? true : false;
  else if ($playerID == 1 && $gameStatus < $MGS_ReadyToStart) $response->amIActive = false;
  else if ($playerID == 2 && $gameStatus >= $MGS_ReadyToStart) $response->amIActive = false;

  $contentCreator = ContentCreators::tryFrom(($playerID == 1 ? $p1ContentCreatorID : $p2ContentCreatorID));
  $response->nameColor = ($contentCreator != null ? $contentCreator->NameColor() : "");
  $response->displayName = ($yourName != "-" ? $yourName : "Player " . $playerID);



  $deckFile = "../Games/" . $gameName . "/p" . $playerID . "Deck.txt";
  $handler = fopen($deckFile, "r");
  if ($handler) {
    $character = GetArray($handler);
    $response->overlayURL = ($contentCreator != null ? $contentCreator->HeroOverlayURL($character[0]) : "");
    $response->deck = new stdClass();
    $response->deck->hero = $character[0];

    $response->deck->weapons = [];
    $response->deck->head = [];
    $response->deck->chest = [];
    $response->deck->arms = [];
    $response->deck->legs = [];
    $response->deck->offhand = [];
    for ($i = 1; $i < count($character); ++$i) {
      switch (CardSubtype($character[$i])) {
        case "Head":
          array_push($response->deck->head, $character[$i]);
          break;
        case "Chest":
          array_push($response->deck->chest, $character[$i]);
          break;
        case "Arms":
          array_push($response->deck->arms, $character[$i]);
          break;
        case "Legs":
          array_push($response->deck->legs, $character[$i]);
          break;
        case "Off-Hand":
          array_push($response->deck->offhand, $character[$i]);
          break;
        default:
          array_push($response->deck->weapons, $character[$i]);
          break;
      }
    }

    $response->deck->cards = GetArray($handler);
    $response->deck->headSB = GetArray($handler);
    $response->deck->chestSB = GetArray($handler);
    $response->deck->armsSB = GetArray($handler);
    $response->deck->legsSB = GetArray($handler);
    $response->deck->offhandSB = GetArray($handler);
    $response->deck->weaponSB = GetArray($handler);
    $response->deck->cardsSB = GetArray($handler);

    fclose($handler);
  }



  echo json_encode($response);
  exit;

?>
