<?php

function GetStringArray($line)
{
  $line = trim($line);
  if($line == "") return [];
  return explode(" ", $line);
}

if(!is_numeric($gameName)) exit;
if(!isset($filename) || !str_contains($filename, "gamestate.txt")) $filename = "./Games/" . $gameName . "/gamestate.txt";
if(!isset($filepath)) $filepath = "./Games/" . $gameName . "/";

ParseGamestate();

function GamestateSanitize($input)
{
  return str_replace([",", " "], ["<44>", "_"], $input);
}

function GamestateUnsanitize($input)
{
  return str_replace(["<44>", "_"], [",", " "], $input);
}

function ParseGamestate()
{
  global $gameName, $playerHealths;
  global $p1Hand, $p1Deck, $p1CharEquip, $p1Resources, $p1Arsenal, $p1Items, $p1Auras, $p1Discard, $p1Pitch, $p1Banish;
  global $p1ClassState, $p1CharacterEffects, $p1Soul, $p1CardStats, $p1TurnStats, $p1Allies, $p1Permanents, $p1Settings;
  global $p2Hand, $p2Deck, $p2CharEquip, $p2Resources, $p2Arsenal, $p2Items, $p2Auras, $p2Discard, $p2Pitch, $p2Banish;
  global $p2ClassState, $p2CharacterEffects, $p2Soul, $p2CardStats, $p2TurnStats, $p2Allies, $p2Permanents, $p2Settings;
  global $p1CardTurnLog, $p2CardTurnLog, $p1LifeHistory, $p2LifeHistory;
  global $landmarks, $winner, $firstPlayer, $currentPlayer, $currentTurn, $turn, $actionPoints, $combatChain, $combatChainState;
  global $currentTurnEffects, $currentTurnEffectsFromCombat, $nextTurnEffects, $decisionQueue, $dqVars, $dqState;
  global $layers, $layerPriority, $mainPlayer, $defPlayer, $lastPlayed, $chainLinks, $chainLinkSummary, $p1Key, $p2Key;
  global $permanentUniqueIDCounter, $inGameStatus, $animations, $currentPlayerActivity;
  global $p1TotalTime, $p2TotalTime, $lastUpdateTime, $roguelikeGameID, $events, $EffectContext;
  global $mainPlayerGamestateStillBuilt, $mpgBuiltFor, $myStateBuiltFor, $playerID;
  global $p1Inventory, $p2Inventory, $p1IsAI, $p2IsAI, $AIHasInfiniteHP, $attackQueue;

  $mainPlayerGamestateStillBuilt = 0;
  $mpgBuiltFor = -1;
  $myStateBuiltFor = -1;

  // explode once; avoids a redundant O(n) substr_count scan on the same string
  $gamestateContent = explode("\r\n", ReadGamestateCache($gameName));
  if (count($gamestateContent) < 60) {
    global $filename;
    $gsFile = (isset($filename) && str_contains($filename, "gamestate.txt"))
      ? $filename : "./Games/" . $gameName . "/gamestate.txt";
    $fileContent = @file_get_contents($gsFile);
    if ($fileContent !== false) {
      $gamestateContent = explode("\r\n", $fileContent);
      if (count($gamestateContent) >= 60) {
        WriteGamestateCache($gameName, $fileContent);
      }
    }
  }
  if(count($gamestateContent) < 60) exit;

  $playerHealths = GetStringArray($gamestateContent[0]); // 1

  //Player 1
  $p1Hand = GetStringArray($gamestateContent[1]); // 2
  $p1Deck = GetStringArray($gamestateContent[2]); // 3
  $p1CharEquip = GetStringArray($gamestateContent[3]); // 4
  $p1Resources = GetStringArray($gamestateContent[4]); // 5
  $p1Arsenal = GetStringArray($gamestateContent[5]); // 6
  $p1Items = GetStringArray($gamestateContent[6]); // 7
  $p1Auras = GetStringArray($gamestateContent[7]); // 8
  $p1Discard = GetStringArray($gamestateContent[8]); // 9
  $p1Pitch = GetStringArray($gamestateContent[9]); // 10
  $p1Banish = GetStringArray($gamestateContent[10]); // 11
  $p1ClassState = GetStringArray($gamestateContent[11]); // 12
  $p1CharacterEffects = GetStringArray($gamestateContent[12]); // 13
  $p1Soul = GetStringArray($gamestateContent[13]); // 14
  $p1CardStats = GetStringArray($gamestateContent[14]); // 15
  $p1TurnStats = GetStringArray($gamestateContent[15]); // 16
  $p1Allies = GetStringArray($gamestateContent[16]); // 17
  $p1Permanents = GetStringArray($gamestateContent[17]); // 18
  $p1Settings = GetStringArray($gamestateContent[18]); // 19

  //Player 2
  $p2Hand = GetStringArray($gamestateContent[19]); // 20
  $p2Deck = GetStringArray($gamestateContent[20]); // 21
  $p2CharEquip = GetStringArray($gamestateContent[21]); // 22
  $p2Resources = GetStringArray($gamestateContent[22]); // 23
  $p2Arsenal = GetStringArray($gamestateContent[23]); // 24
  $p2Items = GetStringArray($gamestateContent[24]); // 25
  $p2Auras = GetStringArray($gamestateContent[25]); // 26
  $p2Discard = GetStringArray($gamestateContent[26]); // 27
  $p2Pitch = GetStringArray($gamestateContent[27]); // 28
  $p2Banish = GetStringArray($gamestateContent[28]); // 29
  $p2ClassState = GetStringArray($gamestateContent[29]); // 30
  $p2CharacterEffects = GetStringArray($gamestateContent[30]); // 31
  $p2Soul = GetStringArray($gamestateContent[31]); // 32
  $p2CardStats = GetStringArray($gamestateContent[32]); // 33
  $p2TurnStats = GetStringArray($gamestateContent[33]); // 34
  $p2Allies = GetStringArray($gamestateContent[34]); // 35
  $p2Permanents = GetStringArray($gamestateContent[35]); // 36
  $p2Settings = GetStringArray($gamestateContent[36]); // 37

  $landmarks = GetStringArray($gamestateContent[37]);
  $winner = trim($gamestateContent[38]);
  $firstPlayer = trim($gamestateContent[39]);
  $currentPlayer = trim($gamestateContent[40]);
  $currentTurn = trim($gamestateContent[41]);
  $turn = GetStringArray($gamestateContent[42]);
  $actionPoints = trim($gamestateContent[43]);
  $combatChain = GetStringArray($gamestateContent[44]);
  $combatChainState = GetStringArray($gamestateContent[45]);
  $currentTurnEffects = GetStringArray($gamestateContent[46]);
  $currentTurnEffectsFromCombat = GetStringArray($gamestateContent[47]);
  $nextTurnEffects = GetStringArray($gamestateContent[48]);
  $decisionQueue = GetStringArray($gamestateContent[49]);
  $dqVars = GetStringArray($gamestateContent[50]);
  $dqState = GetStringArray($gamestateContent[51]);
  $layers = GetStringArray($gamestateContent[52]);
  $layerPriority = GetStringArray($gamestateContent[53]);
  $mainPlayer = trim($gamestateContent[54]);
  $defPlayer = $mainPlayer == 1 ? 2 : 1;
  $lastPlayed = GetStringArray($gamestateContent[55]);
  $numChainLinks = isset($gamestateContent[56]) ? trim($gamestateContent[56]) : 0;
  if (!is_numeric($numChainLinks)) $numChainLinks = 0;
  $chainLinks = [];
  for ($i = 0; $i < $numChainLinks; ++$i) {
    $chainLinks[] = GetStringArray($gamestateContent[57+$i]);
  }
  $chainLinkSummary = GetStringArray($gamestateContent[57+$numChainLinks]);
  $p1Key = trim($gamestateContent[58+$numChainLinks]);
  $p2Key = trim($gamestateContent[59+$numChainLinks]);
  $permanentUniqueIDCounter = trim($gamestateContent[60+$numChainLinks]);
  $inGameStatus = trim($gamestateContent[61+$numChainLinks]); //Game status -- 0 = START, 1 = PLAY, 2 = OVER
  $animations = GetStringArray($gamestateContent[62+$numChainLinks]); //Animations
  $currentPlayerActivity = trim($gamestateContent[63+$numChainLinks]); // Not Used - Current Player activity status -- 0 = active, 2 = inactive
  //64 + numChainLinks unused
  //65 + numChainLinks unused
  $p1TotalTime = trim($gamestateContent[66+$numChainLinks]); //Player 1 total time
  $p2TotalTime = trim($gamestateContent[67+$numChainLinks]); //Player 2 total time
  $lastUpdateTime = trim($gamestateContent[68+$numChainLinks]); //Last update time
  $roguelikeGameID = trim($gamestateContent[69+$numChainLinks]); //Roguelike game id
  $events = GetStringArray($gamestateContent[70+$numChainLinks]); //Events
  $EffectContext = trim($gamestateContent[71+$numChainLinks]);
  $p1Inventory = GetStringArray($gamestateContent[72+$numChainLinks]);
  $p2Inventory = GetStringArray($gamestateContent[73+$numChainLinks]);
  $p1IsAI = trim($gamestateContent[74+$numChainLinks]);
  $p2IsAI = trim($gamestateContent[75+$numChainLinks]);
  $AIHasInfiniteHP = isset($gamestateContent[76+$numChainLinks]) ? trim($gamestateContent[76+$numChainLinks]) == "1" : false;
  $p1CardTurnLog = isset($gamestateContent[77+$numChainLinks]) ? json_decode(trim($gamestateContent[77+$numChainLinks]), true) ?? [] : [];
  $p2CardTurnLog = isset($gamestateContent[78+$numChainLinks]) ? json_decode(trim($gamestateContent[78+$numChainLinks]), true) ?? [] : [];
  $attackQueue = GetStringArray($gamestateContent[79+$numChainLinks] ?? "");
  $p1LifeHistory = isset($gamestateContent[80+$numChainLinks]) ? json_decode(trim($gamestateContent[80+$numChainLinks]), true) ?? [] : [];
  $p2LifeHistory = isset($gamestateContent[81+$numChainLinks]) ? json_decode(trim($gamestateContent[81+$numChainLinks]), true) ?? [] : [];

  BuildMyGamestate($playerID);
}

function DoGamestateUpdate()
{
  global $mainPlayerGamestateStillBuilt, $myStateBuiltFor;
  if ($mainPlayerGamestateStillBuilt == 1) UpdateMainPlayerGameStateInner();
  else if ($myStateBuiltFor != -1) UpdateGameStateInner();
}

function BuildMyGamestate($playerID)
{
  global $p1Deck, $p1Hand, $p1Resources, $p1CharEquip, $p1Arsenal, $playerHealths, $p1Auras, $p1Pitch, $p1Banish, $p1ClassState, $p1Items;
  global $p1CharacterEffects, $p1Discard, $p1CardStats, $p1TurnStats;
  global $p2Deck, $p2Hand, $p2Resources, $p2CharEquip, $p2Arsenal, $p2Auras, $p2Pitch, $p2Banish, $p2ClassState, $p2Items;
  global $p2CharacterEffects, $p2Discard, $p2CardStats, $p2TurnStats;
  global $myDeck, $myHand, $myResources, $myCharacter, $myArsenal, $myHealth, $myAuras, $myPitch, $myBanish, $myClassState, $myItems;
  global $myCharacterEffects, $myDiscard, $myCardStats, $myTurnStats;
  global $theirDeck, $theirHand, $theirResources, $theirCharacter, $theirArsenal, $theirHealth, $theirAuras, $theirPitch, $theirBanish, $theirClassState, $theirItems;
  global $theirCharacterEffects, $theirDiscard, $theirCardStats, $theirTurnStats;
  global $p1Soul, $p2Soul, $mySoul, $theirSoul;
  global $p1CardTurnLog, $p2CardTurnLog, $myCardTurnLog, $theirCardTurnLog;
  global $myStateBuiltFor, $mainPlayerGamestateStillBuilt;
  DoGamestateUpdate();
  $mainPlayerGamestateStillBuilt = 0;
  $myStateBuiltFor = $playerID == 1 ? 1 : 2;
  if ($playerID == 1) {
    $myHand = $p1Hand;
    $myDeck = $p1Deck;
    $myResources = $p1Resources;
    $myCharacter = $p1CharEquip;
    $myArsenal = $p1Arsenal;
    $myHealth = $playerHealths[0];
    $myItems = $p1Items;
    $myAuras = $p1Auras;
    $myDiscard = $p1Discard;
    $myPitch = $p1Pitch;
    $myBanish = $p1Banish;
    $myClassState = $p1ClassState;
    $myCharacterEffects = $p1CharacterEffects;
    $mySoul = $p1Soul;
    $myCardStats = $p1CardStats;
    $myTurnStats = $p1TurnStats;
    $myCardTurnLog = $p1CardTurnLog;
    $theirHand = $p2Hand;
    $theirDeck = $p2Deck;
    $theirResources = $p2Resources;
    $theirCharacter = $p2CharEquip;
    $theirArsenal = $p2Arsenal;
    $theirHealth = $playerHealths[1];
    $theirItems = $p2Items;
    $theirAuras = $p2Auras;
    $theirDiscard = $p2Discard;
    $theirPitch = $p2Pitch;
    $theirBanish = $p2Banish;
    $theirClassState = $p2ClassState;
    $theirCharacterEffects = $p2CharacterEffects;
    $theirSoul = $p2Soul;
    $theirCardStats = $p2CardStats;
    $theirTurnStats = $p2TurnStats;
    $theirCardTurnLog = $p2CardTurnLog;
  } else {
    $myHand = $p2Hand;
    $myDeck = $p2Deck;
    $myResources = $p2Resources;
    $myCharacter = $p2CharEquip;
    $myArsenal = $p2Arsenal;
    $myHealth = $playerHealths[1];
    $myItems = $p2Items;
    $myAuras = $p2Auras;
    $myDiscard = $p2Discard;
    $myPitch = $p2Pitch;
    $myBanish = $p2Banish;
    $myClassState = $p2ClassState;
    $myCharacterEffects = $p2CharacterEffects;
    $mySoul = $p2Soul;
    $myCardStats = $p2CardStats;
    $myTurnStats = $p2TurnStats;
    $myCardTurnLog = $p2CardTurnLog;
    $theirHand = $p1Hand;
    $theirDeck = $p1Deck;
    $theirResources = $p1Resources;
    $theirCharacter = $p1CharEquip;
    $theirArsenal = $p1Arsenal;
    $theirHealth = $playerHealths[0];
    $theirItems = $p1Items;
    $theirAuras = $p1Auras;
    $theirDiscard = $p1Discard;
    $theirPitch = $p1Pitch;
    $theirBanish = $p1Banish;
    $theirClassState = $p1ClassState;
    $theirCharacterEffects = $p1CharacterEffects;
    $theirSoul = $p1Soul;
    $theirCardStats = $p1CardStats;
    $theirTurnStats = $p1TurnStats;
    $theirCardTurnLog = $p1CardTurnLog;
  }
}

function BuildMainPlayerGameState()
{
  global $mainPlayer, $mainPlayerGamestateStillBuilt, $playerHealths, $mpgBuiltFor, $defPlayer;
  global $mainHand, $mainDeck, $mainResources, $mainCharacter, $mainArsenal, $mainHealth, $mainAuras, $mainPitch, $mainBanish, $mainClassState, $mainItems;
  global $mainCharacterEffects, $mainDiscard;
  global $defHand, $defDeck, $defResources, $defCharacter, $defArsenal, $defHealth, $defAuras, $defPitch, $defBanish, $defClassState, $defItems;
  global $defCharacterEffects, $defDiscard;
  global $p1Deck, $p1Hand, $p1Resources, $p1CharEquip, $p1Arsenal, $p1Auras, $p1Pitch, $p1Banish, $p1ClassState, $p1Items, $p1CharacterEffects, $p1Discard;
  global $p2Deck, $p2Hand, $p2Resources, $p2CharEquip, $p2Arsenal, $p2Auras, $p2Pitch, $p2Banish, $p2ClassState, $p2Items, $p2CharacterEffects, $p2Discard;
  global $p1Soul, $p2Soul, $mainSoul, $defSoul;
  global $p1CardStats, $p2CardStats, $mainCardStats, $defCardStats;
  global $p1TurnStats, $p2TurnStats, $mainTurnStats, $defTurnStats;
  global $p1CardTurnLog, $p2CardTurnLog, $mainCardTurnLog, $defCardTurnLog;
  DoGamestateUpdate();
  $mpgBuiltFor = $mainPlayer;
  if ($mainPlayer == 1) {
    $mainHand = $p1Hand;
    $mainDeck = $p1Deck;
    $mainResources = $p1Resources;
    $mainCharacter = $p1CharEquip;
    $mainArsenal = $p1Arsenal;
    $mainHealth = $playerHealths[0];
    $mainItems = $p1Items;
    $mainAuras = $p1Auras;
    $mainPitch = $p1Pitch;
    $mainBanish = $p1Banish;
    $mainClassState = $p1ClassState;
    $mainCharacterEffects = $p1CharacterEffects;
    $mainDiscard = $p1Discard;
    $mainSoul = $p1Soul;
    $mainCardStats = $p1CardStats;
    $mainTurnStats = $p1TurnStats;
    $mainCardTurnLog = $p1CardTurnLog;
    $defHand = $p2Hand;
    $defDeck = $p2Deck;
    $defResources = $p2Resources;
    $defCharacter = $p2CharEquip;
    $defArsenal = $p2Arsenal;
    $defHealth = $playerHealths[1];
    $defItems = $p2Items;
    $defAuras = $p2Auras;
    $defPitch = $p2Pitch;
    $defBanish = $p2Banish;
    $defClassState = $p2ClassState;
    $defCharacterEffects = $p2CharacterEffects;
    $defDiscard = $p2Discard;
    $defSoul = $p2Soul;
    $defCardStats = $p2CardStats;
    $defTurnStats = $p2TurnStats;
    $defCardTurnLog = $p2CardTurnLog;
  } else {
    $mainHand = $p2Hand;
    $mainDeck = $p2Deck;
    $mainResources = $p2Resources;
    $mainCharacter = $p2CharEquip;
    $mainArsenal = $p2Arsenal;
    $mainHealth = $playerHealths[1];
    $mainItems = $p2Items;
    $mainAuras = $p2Auras;
    $mainPitch = $p2Pitch;
    $mainBanish = $p2Banish;
    $mainClassState = $p2ClassState;
    $mainCharacterEffects = $p2CharacterEffects;
    $mainDiscard = $p2Discard;
    $mainSoul = $p2Soul;
    $mainCardStats = $p2CardStats;
    $mainTurnStats = $p2TurnStats;
    $mainCardTurnLog = $p2CardTurnLog;
    $defHand = $p1Hand;
    $defDeck = $p1Deck;
    $defResources = $p1Resources;
    $defCharacter = $p1CharEquip;
    $defArsenal = $p1Arsenal;
    $defHealth = $playerHealths[0];
    $defItems = $p1Items;
    $defAuras = $p1Auras;
    $defPitch = $p1Pitch;
    $defBanish = $p1Banish;
    $defClassState = $p1ClassState;
    $defCharacterEffects = $p1CharacterEffects;
    $defDiscard = $p1Discard;
    $defSoul = $p1Soul;
    $defCardStats = $p1CardStats;
    $defTurnStats = $p1TurnStats;
    $defCardTurnLog = $p1CardTurnLog;
  }

  $mainPlayerGamestateStillBuilt = 1;
}

function UpdateGameStateInner()
{
  global $myStateBuiltFor;
  global $p1Deck, $p1Hand, $p1Resources, $p1CharEquip, $p1Arsenal, $playerHealths, $p1Auras, $p1Pitch, $p1Banish, $p1ClassState, $p1Items;
  global $p1CharacterEffects, $p1Discard, $p1CardStats, $p1TurnStats;
  global $p2Deck, $p2Hand, $p2Resources, $p2CharEquip, $p2Arsenal, $p2Auras, $p2Pitch, $p2Banish, $p2ClassState, $p2Items;
  global $p2CharacterEffects, $p2Discard, $p2CardStats, $p2TurnStats;
  global $myDeck, $myHand, $myResources, $myCharacter, $myArsenal, $myHealth, $myAuras, $myPitch, $myBanish, $myClassState, $myItems;
  global $myCharacterEffects, $myDiscard, $myCardStats, $myTurnStats;
  global $theirDeck, $theirHand, $theirResources, $theirCharacter, $theirArsenal, $theirHealth, $theirAuras, $theirPitch, $theirBanish, $theirClassState, $theirItems;
  global $theirCharacterEffects, $theirDiscard, $theirCardStats, $theirTurnStats;
  global $p1Soul, $p2Soul, $mySoul, $theirSoul;
  global $p1CardTurnLog, $p2CardTurnLog, $myCardTurnLog, $theirCardTurnLog;
  $activePlayer = $myStateBuiltFor;
  if ($activePlayer == 1) {
    $p1Deck = $myDeck;
    $p1Hand = $myHand;
    $p1Resources = $myResources;
    $p1CharEquip = $myCharacter;
    $p1Arsenal = $myArsenal;
    $playerHealths[0] = $myHealth;
    $p1Items = $myItems;
    $p1Auras = $myAuras;
    $p1Pitch = $myPitch;
    $p1Banish = $myBanish;
    $p1ClassState = $myClassState;
    $p1CharacterEffects = $myCharacterEffects;
    $p1Discard = $myDiscard;
    $p1Soul = $mySoul;
    $p1CardStats = $myCardStats;
    $p1TurnStats = $myTurnStats;
    $p1CardTurnLog = $myCardTurnLog;
    $p2Deck = $theirDeck;
    $p2Hand = $theirHand;
    $p2Resources = $theirResources;
    $p2CharEquip = $theirCharacter;
    $p2Arsenal = $theirArsenal;
    $playerHealths[1] = $theirHealth;
    $p2Items = $theirItems;
    $p2Auras = $theirAuras;
    $p2Pitch = $theirPitch;
    $p2Banish = $theirBanish;
    $p2ClassState = $theirClassState;
    $p2CharacterEffects = $theirCharacterEffects;
    $p2Discard = $theirDiscard;
    $p2Soul = $theirSoul;
    $p2CardStats = $theirCardStats;
    $p2TurnStats = $theirTurnStats;
    $p2CardTurnLog = $theirCardTurnLog;
  } else {
    $p2Deck = $myDeck;
    $p2Hand = $myHand;
    $p2Resources = $myResources;
    $p2CharEquip = $myCharacter;
    $p2Arsenal = $myArsenal;
    $playerHealths[1] = $myHealth;
    $p2Items = $myItems;
    $p2Auras = $myAuras;
    $p2Pitch = $myPitch;
    $p2Banish = $myBanish;
    $p2ClassState = $myClassState;
    $p2CharacterEffects = $myCharacterEffects;
    $p2Discard = $myDiscard;
    $p2Soul = $mySoul;
    $p2CardStats = $myCardStats;
    $p2TurnStats = $myTurnStats;
    $p2CardTurnLog = $myCardTurnLog;
    $p1Deck = $theirDeck;
    $p1Hand = $theirHand;
    $p1Resources = $theirResources;
    $p1CharEquip = $theirCharacter;
    $p1Arsenal = $theirArsenal;
    $playerHealths[0] = $theirHealth;
    $p1Items = $theirItems;
    $p1Auras = $theirAuras;
    $p1Pitch = $theirPitch;
    $p1Banish = $theirBanish;
    $p1ClassState = $theirClassState;
    $p1CharacterEffects = $theirCharacterEffects;
    $p1Discard = $theirDiscard;
    $p1Soul = $theirSoul;
    $p1CardStats = $theirCardStats;
    $p1TurnStats = $theirTurnStats;
    $p1CardTurnLog = $theirCardTurnLog;
  }
}

function UpdateMainPlayerGameStateInner()
{
  global $mainPlayerGamestateStillBuilt, $mpgBuiltFor;
  global $mainHand, $mainDeck, $mainResources, $mainCharacter, $mainArsenal, $mainHealth, $mainAuras, $mainPitch, $mainBanish, $mainClassState, $mainItems;
  global $mainCharacterEffects, $mainDiscard;
  global $defHand, $defDeck, $defResources, $defCharacter, $defArsenal, $defHealth, $defAuras, $defPitch, $defBanish, $defClassState, $defItems;
  global $defCharacterEffects, $defDiscard;
  global $p1Deck, $p1Hand, $p1Resources, $p1CharEquip, $p1Arsenal, $playerHealths, $p1Auras, $p1Pitch, $p1Banish, $p1ClassState, $p1Items;
  global $p1CharacterEffects, $p1Discard;
  global $p2Deck, $p2Hand, $p2Resources, $p2CharEquip, $p2Arsenal, $p2Auras, $p2Pitch, $p2Banish, $p2ClassState, $p2Items;
  global $p2CharacterEffects, $p2Discard;
  global $p1Soul, $p2Soul, $mainSoul, $defSoul;
  global $p1CardStats, $p2CardStats, $mainCardStats, $defCardStats;
  global $p1TurnStats, $p2TurnStats, $mainTurnStats, $defTurnStats;
  global $p1CardTurnLog, $p2CardTurnLog, $mainCardTurnLog, $defCardTurnLog;

  if ($mpgBuiltFor == 1) {
    $p1Deck = $mainDeck;
    $p1Hand = $mainHand;
    $p1Resources = $mainResources;
    $p1CharEquip = $mainCharacter;
    $p1Arsenal = $mainArsenal;
    $playerHealths[0] = $mainHealth;
    $p1Items = $mainItems;
    $p1Auras = $mainAuras;
    $p1Pitch = $mainPitch;
    $p1Banish = $mainBanish;
    $p1ClassState = $mainClassState;
    $p1CharacterEffects = $mainCharacterEffects;
    $p1Discard = $mainDiscard;
    $p1Soul = $mainSoul;
    $p1CardStats = $mainCardStats;
    $p1TurnStats = $mainTurnStats;
    $p1CardTurnLog = $mainCardTurnLog;
    $p2Deck = $defDeck;
    $p2Hand = $defHand;
    $p2Resources = $defResources;
    $p2CharEquip = $defCharacter;
    $p2Arsenal = $defArsenal;
    $playerHealths[1] = $defHealth;
    $p2Items = $defItems;
    $p2Auras = $defAuras;
    $p2Pitch = $defPitch;
    $p2Banish = $defBanish;
    $p2ClassState = $defClassState;
    $p2CharacterEffects = $defCharacterEffects;
    $p2Discard = $defDiscard;
    $p2Soul = $defSoul;
    $p2CardStats = $defCardStats;
    $p2TurnStats = $defTurnStats;
    $p2CardTurnLog = $defCardTurnLog;
  } else {
    $p1Deck = $defDeck;
    $p1Hand = $defHand;
    $p1Resources = $defResources;
    $p1CharEquip = $defCharacter;
    $p1Arsenal = $defArsenal;
    $playerHealths[0] = $defHealth;
    $p1Items = $defItems;
    $p1Auras = $defAuras;
    $p1Pitch = $defPitch;
    $p1Banish = $defBanish;
    $p1ClassState = $defClassState;
    $p1CharacterEffects = $defCharacterEffects;
    $p1Discard = $defDiscard;
    $p1Soul = $defSoul;
    $p1CardStats = $defCardStats;
    $p1TurnStats = $defTurnStats;
    $p1CardTurnLog = $defCardTurnLog;
    $p2Deck = $mainDeck;
    $p2Hand = $mainHand;
    $p2Resources = $mainResources;
    $p2CharEquip = $mainCharacter;
    $p2Arsenal = $mainArsenal;
    $playerHealths[1] = $mainHealth;
    $p2Items = $mainItems;
    $p2Auras = $mainAuras;
    $p2Pitch = $mainPitch;
    $p2Banish = $mainBanish;
    $p2ClassState = $mainClassState;
    $p2CharacterEffects = $mainCharacterEffects;
    $p2Discard = $mainDiscard;
    $p2Soul = $mainSoul;
    $p2CardStats = $mainCardStats;
    $p2TurnStats = $mainTurnStats;
    $p2CardTurnLog = $mainCardTurnLog;
  }
}

function SaveGamestateSnapshot($destination)
{
  global $filepath, $lastWrittenGamestate;
  if (isset($lastWrittenGamestate)) {
    return file_put_contents($destination, $lastWrittenGamestate) !== false;
  }
  return copy($filepath . "gamestate.txt", $destination);
}

function MakeGamestateBackup($filename = "gamestateBackup.txt")
{
  global $filepath, $lastWrittenGamestate;
  if(!isset($lastWrittenGamestate) && !file_exists($filepath . "gamestate.txt")) WriteLog("Cannot copy gamestate file; it does not exist.");

  // Handle special backups (like preBlockBackup.txt, beginTurnGamestate.txt, etc.)
  if ($filename != "gamestateBackup.txt") {
    $result = SaveGamestateSnapshot($filepath . $filename);
    if(!$result) WriteLog("Copy of gamestate into " . $filename . " failed.");
    return;
  }
  
  // Multi-level undo: Rotate backups
  // Shift all existing backups: 0->1, 1->2, 2->3, 3->4, delete 4
  $backupPrefix = $filepath . "gamestateBackup_";
  for ($i = MAX_UNDO_BACKUPS - 1; $i > 0; $i--) {
    @rename($backupPrefix . ($i - 1) . ".txt", $backupPrefix . $i . ".txt");
  }
  
  // Save current state as backup 0 (most recent)
  $result = SaveGamestateSnapshot($filepath . "gamestateBackup_0.txt");
  if(!$result) WriteLog("Copy of gamestate into gamestateBackup_0.txt failed.");
}

function RevertGamestate($filename = "gamestateBackup.txt", $stepsBack = 1)
{
  global $gameName, $skipWriteGamestate, $filepath, $p1Settings, $p2Settings;
  
  // Handle special backups (like preBlockBackup.txt, beginTurnGamestate.txt, lastTurnGamestate.txt)
  if ($filename != "gamestateBackup.txt") {
    if(!file_exists($filepath . $filename)) return;
    copy($filepath . $filename, $filepath . "gamestate.txt");
    $skipWriteGamestate = true;
    $gamestate = file_get_contents($filepath . $filename);
    WriteGamestateCache($gameName, $gamestate);
    $GLOBALS['lastWrittenGamestate'] = $gamestate; // keep in-memory mirror of gamestate.txt current
    return;
  }
  
  // Multi-level undo: Revert to backup N steps back
  $targetBackup = $stepsBack - 1; // stepsBack=1 means backup_0, stepsBack=2 means backup_1, etc.
  $backupFile = $filepath . "gamestateBackup_" . $targetBackup . ".txt";
  
  if(!file_exists($backupFile)) {
    WriteLog("Cannot undo further: Please revert to start of this/previous turn instead.");
    return;
  }
  // apply current settings to the backup
  $gamestateBackup = file($backupFile);
  $gamestateBackup[18] = implode(" ", $p1Settings) . "\r\n";
  $gamestateBackup[36] = implode(" ", $p2Settings) . "\r\n";
  // Clear pending NAA from both players on undo
  // p1ClassState = line 11, p2ClassState = line 29, CS_PendingNAACard = index 122.
  foreach ([11, 29] as $csLine) {
    if (isset($gamestateBackup[$csLine])) {
      $csParts = explode(" ", trim($gamestateBackup[$csLine]));
      if (isset($csParts[122])) $csParts[122] = "-";
      $gamestateBackup[$csLine] = implode(" ", $csParts) . "\r\n";
    }
  }
  $gamestate = implode('', $gamestateBackup);
  file_put_contents($backupFile, $gamestate);
  if (!file_exists($backupFile)) {
    WriteLog("Cannot undo further: the game session was cleaned up before the undo could complete.");
    return;
  }
  // Restore the target backup as current gamestate
  copy($backupFile, $filepath . "gamestate.txt");
  $skipWriteGamestate = true;
  WriteGamestateCache($gameName, $gamestate);
  $GLOBALS['lastWrittenGamestate'] = $gamestate; // keep in-memory mirror of gamestate.txt current
  
  // Shift backups: Remove the reverted backups and shift remaining ones
  // If we reverted 2 steps, backups 0 and 1 are gone, backup 2 becomes 0, backup 3 becomes 1, etc.
  for ($i = 0; $i < MAX_UNDO_BACKUPS; $i++) {
    $sourceIndex = $i + $stepsBack;
    $sourceFile = $filepath . "gamestateBackup_" . $sourceIndex . ".txt";
    $targetFile = $filepath . "gamestateBackup_" . $i . ".txt";

    $renamed = $sourceIndex < MAX_UNDO_BACKUPS && @rename($sourceFile, $targetFile);
    if (!$renamed) {
      // No more backups to shift, delete this slot
      @unlink($targetFile);
    }
  }
  $result = SaveGamestateSnapshot($filepath . $filename);
  if(!$result) WriteLog("Copy of gamestate into " . $filename . " failed.");
}

function SaveReplay() {
  return true;
}

function MakeStartChainLinkBackup()
{
  global $filepath;
  SaveGamestateSnapshot($filepath . "startChainLinkGamestate.txt");
}

function MakeStartTurnBackup()
{
  global $mainPlayer, $currentTurn, $filepath;
  $lastTurnFN = $filepath . "lastTurnGamestate.txt";
  $thisTurnFN = $filepath . "beginTurnGamestate.txt";
  @rename($thisTurnFN, $lastTurnFN);
  SaveGamestateSnapshot($thisTurnFN);
  $startGameFN = $filepath . "startGamestate.txt";
  if ((IsPatron(1) || IsPatron(2)) && $currentTurn == 0 && !file_exists($startGameFN)) {
    SaveGamestateSnapshot($startGameFN);
  }
  if (SaveReplay()) {
    $numberedTurnFN = $filepath . "turn_$mainPlayer-$currentTurn" . "_Gamestate.txt";
    SaveGamestateSnapshot($numberedTurnFN);
    $commandFile = fopen("$filepath/commandfile.txt", "a");
    fwrite($commandFile, "$mainPlayer StartTurn $currentTurn 0\r\n");
    fclose($commandFile);
  }
}

function GetAvailableUndoSteps()
{
  global $filepath;
  $availableSteps = 0;
  
  for ($i = 0; $i < MAX_UNDO_BACKUPS; $i++) {
    $backupFile = $filepath . "gamestateBackup_" . $i . ".txt";
    if (file_exists($backupFile)) {
      $availableSteps++;
    } else {
      break; // No more consecutive backups
    }
  }
  
  return $availableSteps;
}

function ResetUndoBackupsForRematch()
{
  global $filepath;
  
  // Delete all undo backup slots so undoing cannot reach the previous game.
  for ($i = 0; $i < MAX_UNDO_BACKUPS; $i++) {
    $backupFile = $filepath . "gamestateBackup_" . $i . ".txt";
    if (file_exists($backupFile)) {
      unlink($backupFile);
    }
  }
}
