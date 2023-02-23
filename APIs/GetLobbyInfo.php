<?php

<<<<<<< HEAD
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
    $response->deck->heroName = CardName($character[0]);

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
          $weapon = new stdClass();
          $weapon->id = $character[$i];
          $weapon->is1H = Is1H($weapon->id);
          array_push($response->deck->weapons, $weapon);
          break;
      }
    }

    $response->deck->cards = GetArray($handler);
    $response->deck->headSB = GetArray($handler);
    $response->deck->chestSB = GetArray($handler);
    $response->deck->armsSB = GetArray($handler);
    $response->deck->legsSB = GetArray($handler);
    $response->deck->offhandSB = GetArray($handler);
    $weaponSB = GetArray($handler);
    $response->deck->weaponSB = [];
    for($i=0; $i<count($weaponSB); ++$i)
    {
      $weapon = new stdClass();
      $weapon->id = $weaponSB[$i];
      $weapon->is1H = Is1H($weapon->id);
      array_push($response->deck->weaponSB, $weapon);
    }
    $response->deck->cardsSB = GetArray($handler);

    fclose($handler);
  }



  echo json_encode($response);
  exit;

?>
=======
ob_start();
include "../HostFiles/Redirector.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../CardDictionary.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";
include "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/PlayerSettings.php";
ob_end_clean();

SetHeaders();


$_POST = json_decode(file_get_contents('php://input'), true);
$gameName = TryPOST("gameName", 0);
$playerID = TryPOST("playerID", 0);
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
else $authKey = TryPOST("authKey");

$response = new stdClass();

session_write_close();

if (!file_exists("../Games/" . $gameName . "/GameFile.txt")) {
  echo (json_encode(new stdClass()));
  exit;
}

ob_start();
include "./APIParseGamefile.php";
ob_end_clean();

$yourName = ($playerID == 1 ? $p1uid : $p2uid);
$theirName = ($playerID == 1 ? $p2uid : $p1uid);

$response->badges = [];

$response->amIActive = true; //Is the game waiting on me to do something?

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
  $response->deck->heroName = CardName($character[0]);

  $response->deck->weapons = [];
  $response->deck->head = [];
  $response->deck->chest = [];
  $response->deck->arms = [];
  $response->deck->legs = [];
  $response->deck->offhand = [];
  $response->deck->quiver = [];
  $response->deck->hands = [];
  for ($i = 1; $i < count($character); ++$i) {
    $subtype = CardSubtype($character[$i]);
    switch ($subtype) {
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
      default:
        $handItem = new stdClass();
        $handItem->id = $character[$i];
        $handItem->is1H = Is1H($handItem->id);
        $numHands = 2;
        if($subtype == "Quiver") $numHands = 0;
        else if($subtype == "Off-Hand") $numHands = 1;
        else if(Is1H($handItem->id)) $numHands = 1;
        $handItem->numHands = $numHands;
        array_push($response->deck->weapons, $handItem);
        array_push($response->deck->hands, $handItem);
        break;
    }
  }

  $response->format = $format;

  $response->deck->cards = GetArray($handler);
  $response->deck->headSB = GetArray($handler);
  $response->deck->chestSB = GetArray($handler);
  $response->deck->armsSB = GetArray($handler);
  $response->deck->legsSB = GetArray($handler);
  $offhandSB = GetArray($handler);
  $weaponSB = GetArray($handler);
  $response->deck->cardsSB = GetArray($handler);
  $quiverSB = GetArray($handler);
  $handsSB = array_merge($weaponSB, $offhandSB, $quiverSB);
  $response->deck->handsSB = [];
  for ($i = 0; $i < count($handsSB); ++$i) {
    $handItem = new stdClass();
    $handItem->id = $handsSB[$i];
    $subtype = CardSubtype($handItem->id);
    $numHands = 2;
    if($subtype == "Quiver") $numHands = 0;
    else if($subtype == "Off-Hand") $numHands = 1;
    else if(Is1H($handItem->id)) $numHands = 1;
    $handItem->numHands = $numHands;
    $handItem->is1H = Is1H($handItem->id);
    array_push($response->deck->handsSB, $handItem);
  }

  fclose($handler);
}

echo json_encode($response);

exit;
>>>>>>> 1ef0ba3a750457c881a809d2569d3200f0cb5504
