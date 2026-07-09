<?php

include "../HostFiles/Redirector.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../CardDictionary.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/PlayerSettings.php";
include_once "../Libraries/LegalHeroesHelper.php";

// Set headers immediately after includes
SetHeaders();

$_POST = json_decode(file_get_contents('php://input'), true);
$gameName = TryPOST("gameName", 0);
$playerID = TryPOST("playerID", 0);
if($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
else if($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
else $authKey = TryPOST("authKey");

$response = new stdClass();
session_write_close();

if($playerID != 1 && $playerID != 2) {
  $response->error = "Invalid player ID";
  echo(json_encode($response));
  exit;
}

if (!file_exists("../Games/" . $gameName . "/GameFile.txt")) {
  echo (json_encode(new stdClass()));
  exit;
}

ob_start();
include "./APIParseGamefile.php";
ob_end_clean();

$yourName = ($playerID == 1 ? $p1DisplayName : $p2DisplayName);
$theirName = ($playerID == 1 ? $p2DisplayName : $p1DisplayName);

$response->badges = [];

$response->amIActive = true; //Is the game waiting on me to do something?

if ($gameStatus == $MGS_ChooseFirstPlayer) $response->amIActive = $playerID == $firstPlayerChooser ? true : false;
else if ($playerID == 1 && $gameStatus < $MGS_ReadyToStart) $response->amIActive = false;
else if ($playerID == 2 && $gameStatus >= $MGS_ReadyToStart) $response->amIActive = false;

$contentCreator = ContentCreators::tryFrom(($playerID == 1 ? $p1ContentCreatorID : $p2ContentCreatorID));
$response->nameColor = ($contentCreator != null ? $contentCreator->NameColor() : "");
$response->displayName = ($yourName != "-" ? $yourName : "Player " . $playerID);



$deckFile = "../Games/" . $gameName . "/p" . $playerID . "Deck.txt";
$handler = @fopen($deckFile, "r");
if($handler) {
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
  $response->deck->demiHero = [];
  $response->deck->modular = [];
  $charCount = count($character);
  for($i = 1; $i < $charCount; ++$i) {
    if (!isset($character[$i])) continue;
    $cardID = $character[$i];
    $subtype = CardSubtype($cardID); // compute once; reused for all slot checks
    if (DelimStringContains($subtype, "Head")) $response->deck->head[] = $cardID;
    else if (DelimStringContains($subtype, "Chest")) $response->deck->chest[] = $cardID;
    else if (DelimStringContains($subtype, "Arms")) $response->deck->arms[] = $cardID;
    else if (DelimStringContains($subtype, "Legs")) $response->deck->legs[] = $cardID;
    else if (IsModular($cardID)) $response->deck->modular[] = $cardID;
    else {
      $handItem = new stdClass();
      $handItem->id = $cardID;
      $is1H = Is1H($cardID); // compute once; used twice below
      $numHands = 2;
      if(DelimStringContains($subtype, "Quiver")) {
        $numHands = 0;
        $handItem->isQuiver = true;
      }
      else if(HasPerched($cardID)) {
        $numHands = 0;
        $handItem->isCompanion = true;
        $handItem->isOffhand = true;
      }
      else if(DelimStringContains($subtype, "Off-Hand")) {
        $numHands = 1;
        $handItem->isOffhand = true;
      }
      else if($is1H) $numHands = 1;
      $handItem->numHands = $numHands;
      $handItem->is1H = $is1H;
      $response->deck->weapons[] = $handItem;
      $response->deck->hands[] = $handItem;
    }
  }

  $response->format = $format;
  $response->legalHeroes = GetLegalHeroes($format);

  $response->deck->cards = GetArray($handler);
  //Remove deck cards that don't belong
  $filteredCards = [];
  foreach ($response->deck->cards as $card) {
    if (CardType($card) === "D") {
      $response->deck->demiHero[] = $card;
    } else {
      $filteredCards[] = $card;
    }
  }
  $response->deck->cards = $filteredCards;

  $response->deck->headSB = GetArray($handler);
  $response->deck->chestSB = GetArray($handler);
  $response->deck->armsSB = GetArray($handler);
  $response->deck->legsSB = GetArray($handler);
  $offhandSB = GetArray($handler);
  $weaponSB = GetArray($handler);
  $response->deck->cardsSB = GetArray($handler);
  //Remove deck cards that don't belong
  $filteredCardsSB = [];
  foreach ($response->deck->cardsSB as $card) {
    if (CardType($card) === "D") {
      $response->deck->demiHero[] = $card;
    } else {
      $filteredCardsSB[] = $card;
    }
  }
  $response->deck->cardsSB = $filteredCardsSB;

  $quiverSB = GetArray($handler);
  $handsSB = array_merge($weaponSB, $offhandSB, $quiverSB);
  $response->deck->handsSB = [];
  $handsSBCount = count($handsSB);
  for ($i = 0; $i < $handsSBCount; ++$i) {
    $handItem = new stdClass();
    $handItem->id = $handsSB[$i];
    $subtype = CardSubtype($handItem->id);
    $is1H = Is1H($handItem->id); // compute once; used twice below
    $numHands = 2;
    if(DelimStringContains($subtype, "Quiver")) {
      $numHands = 0;
      $handItem->isQuiver = true;
    }
    else if(HasPerched($handItem->id)) {
      $numHands = 0;
      $handItem->isCompanion = true;
      $handItem->isOffhand = true;
    }
    else if(DelimStringContains($subtype, "Off-Hand")) {
      $numHands = 1;
      $handItem->isOffhand = true;
    }
    else if($is1H) $numHands = 1;
    $handItem->numHands = $numHands;
    $handItem->is1H = $is1H;
    $response->deck->handsSB[] = $handItem;
  }

  $response->deck->modular = GetArray($handler);

  $cardIndex = [];
  $response->deck->cardDictionary = [];
  
  // Include hero in the dictionary
  $heroId = $response->deck->hero;
  if ($heroId && $heroId !== '-') {
    $heroCard = new stdClass();
    $heroCard->id = $heroId;
    $heroCard->pitch = PitchValue($heroId);
    $heroCard->power = GeneratedPowerValue($heroId);
    $heroCard->blockValue = GeneratedBlockValue($heroId);
    $heroCard->class = CardClass($heroId);
    $heroCard->talent = CardTalent($heroId);
    $heroCard->type = CardType($heroId);
    $heroCard->subtype = CardSubtype($heroId);
    $heroCard->cost = GeneratedCardCost($heroId);
    $heroCard->hasEssenceOfIce = GeneratedHasEssenceOfIce($heroId);
    $heroCard->hasEssenceOfEarth = GeneratedHasEssenceOfEarth($heroId);
    $heroCard->hasEssenceOfLightning = GeneratedHasEssenceOfLightning($heroId);
    $response->deck->cardDictionary[] = $heroCard;
    $cardIndex[$heroId] = "1";
  }
  // Include both main deck and sideboard cards in the dictionary

  foreach ($response->deck->cards as $card) {
    if (!isset($cardIndex[$card])) {
      $cardIndex[$card] = "1";
      $dictionaryCard = new stdClass();
      $dictionaryCard->id = $card;
      $dictionaryCard->pitch = PitchValue($card);
      $dictionaryCard->power = GeneratedPowerValue($card);
      $dictionaryCard->blockValue = GeneratedBlockValue($card);
      $dictionaryCard->class = CardClass($card);
      $dictionaryCard->talent = CardTalent($card);
      $dictionaryCard->type = CardType($card);
      $dictionaryCard->subtype = CardSubtype($card);
      $dictionaryCard->cost = GeneratedCardCost($card);
      $dictionaryCard->hasStealth = GeneratedHasStealth($card);
      $dictionaryCard->hasBloodDebt = GeneratedHasBloodDebt($card);
      $dictionaryCard->hasBoost = GeneratedHasBoost($card);
      $dictionaryCard->hasDecompose = GeneratedHasDecompose($card);
      $dictionaryCard->hasMark = GeneratedHasMark($card);
      $dictionaryCard->hasCharge = GeneratedHasCharge($card);
      $dictionaryCard->hasSuspense = hasSuspense($card);
      $response->deck->cardDictionary[] = $dictionaryCard;
    }
  }
  foreach ($response->deck->cardsSB as $card) {
    if (!isset($cardIndex[$card])) {
      $cardIndex[$card] = "1";
      $dictionaryCard = new stdClass();
      $dictionaryCard->id = $card;
      $dictionaryCard->pitch = PitchValue($card);
      $dictionaryCard->power = GeneratedPowerValue($card);
      $dictionaryCard->blockValue = GeneratedBlockValue($card);
      $dictionaryCard->class = CardClass($card);
      $dictionaryCard->talent = CardTalent($card);
      $dictionaryCard->type = CardType($card);
      $dictionaryCard->subtype = CardSubtype($card);
      $dictionaryCard->cost = GeneratedCardCost($card);
      $dictionaryCard->hasStealth = GeneratedHasStealth($card);
      $dictionaryCard->hasBloodDebt = GeneratedHasBloodDebt($card);
      $dictionaryCard->hasBoost = GeneratedHasBoost($card);
      $dictionaryCard->hasDecompose = GeneratedHasDecompose($card);
      $dictionaryCard->hasMark = GeneratedHasMark($card);
      $dictionaryCard->hasCharge = GeneratedHasCharge($card);
      $dictionaryCard->hasSuspense = hasSuspense($card);
      $response->deck->cardDictionary[] = $dictionaryCard;
    }
  }

  fclose($handler);
}

echo json_encode($response);

exit;
