<?php

function GetStringArray($line)
{
  $line = trim($line);
  if($line == "") return [];
  return explode(" ", $line);
}

if(!isset($filename) || !str_contains($filename, "gamestate.txt")) $filename = "./Games/" . $gameName . "/gamestate.txt";
if(!isset($filepath)) $filepath = "./Games/" . $gameName . "/";

ParseGamestate();

function GamestateSanitize($input)
{
  $output = str_replace(",", "<44>", $input);
  $output = str_replace(" ", "_", $output);
  return $output;
}

function GamestateUnsanitize($input)
{
  $output = str_replace("<44>", ",", $input);
  $output = str_replace("_", " ", $output);
  return $output;
}

function ParseGamestate()
{
  global $gameName, $playerHealths;
  global $p1Hand, $p1Deck, $p1CharEquip, $p1Resources, $p1Arsenal, $p1Items, $p1Auras, $p1Discard, $p1Pitch, $p1Banish;
  global $p1ClassState, $p1CharacterEffects, $p1Soul, $p1CardStats, $p1TurnStats, $p1Allies, $p1Permanents, $p1Settings;
  global $p2Hand, $p2Deck, $p2CharEquip, $p2Resources, $p2Arsenal, $p2Items, $p2Auras, $p2Discard, $p2Pitch, $p2Banish;
  global $p2ClassState, $p2CharacterEffects, $p2Soul, $p2CardStats, $p2TurnStats, $p2Allies, $p2Permanents, $p2Settings;
  global $landmarks, $winner, $firstPlayer, $currentPlayer, $currentTurn, $turn, $actionPoints, $combatChain, $combatChainState;
  global $currentTurnEffects, $currentTurnEffectsFromCombat, $nextTurnEffects, $decisionQueue, $dqVars, $dqState;
  global $layers, $layerPriority, $mainPlayer, $defPlayer, $lastPlayed, $chainLinks, $chainLinkSummary, $p1Key, $p2Key;
  global $permanentUniqueIDCounter, $inGameStatus, $animations, $currentPlayerActivity;
  global $p1TotalTime, $p2TotalTime, $lastUpdateTime, $roguelikeGameID, $events, $EffectContext;
  global $mainPlayerGamestateStillBuilt, $mpgBuiltFor, $myStateBuiltFor, $playerID;
  global $p1Inventory, $p2Inventory, $p1IsAI, $p2IsAI, $AIHasInfiniteHP;

  $mainPlayerGamestateStillBuilt = 0;
  $mpgBuiltFor = -1;
  $myStateBuiltFor = -1;

  $gamestateContent = ReadCache(GamestateID($gameName));
  $gamestateContent = explode("\r\n", $gamestateContent);
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
  $chainLinks = array();
  for ($i = 0; $i < $numChainLinks; ++$i) {
    $chainLink = GetStringArray($gamestateContent[57+$i]);
    array_push($chainLinks, $chainLink);
  }
  $chainLinkSummary = GetStringArray($gamestateContent[57+$numChainLinks]);
  $p1Key = trim($gamestateContent[58+$numChainLinks]);
  $p2Key = trim($gamestateContent[59+$numChainLinks]);
  $permanentUniqueIDCounter = trim($gamestateContent[60+$numChainLinks]);
  $inGameStatus = trim($gamestateContent[61+$numChainLinks]); //Game status -- 0 = START, 1 = PLAY, 2 = OVER
  $animations = GetStringArray($gamestateContent[62+$numChainLinks]); //Animations
  $currentPlayerActivity = trim($gamestateContent[63+$numChainLinks]); //Current Player activity status -- 0 = active, 2 = inactive
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
  global $myStateBuiltFor, $mainPlayerGamestateStillBuilt;
  DoGamestateUpdate();
  $mainPlayerGamestateStillBuilt = 0;
  $myStateBuiltFor = $playerID == 1 ? 1 : 2;
  $myHand = $playerID == 1 ? $p1Hand : $p2Hand;
  $myDeck = $playerID == 1 ? $p1Deck : $p2Deck;
  $myResources = $playerID == 1 ? $p1Resources : $p2Resources;
  $myCharacter = $playerID == 1 ? $p1CharEquip : $p2CharEquip;
  $myArsenal = $playerID == 1 ? $p1Arsenal : $p2Arsenal;
  $myHealth = $playerID == 1 ? $playerHealths[0] : $playerHealths[1];
  $myItems = $playerID == 1 ? $p1Items : $p2Items;
  $myAuras = $playerID == 1 ? $p1Auras : $p2Auras;
  $myDiscard = $playerID == 1 ? $p1Discard : $p2Discard;
  $myPitch = $playerID == 1 ? $p1Pitch : $p2Pitch;
  $myBanish = $playerID == 1 ? $p1Banish : $p2Banish;
  $myClassState = $playerID == 1 ? $p1ClassState : $p2ClassState;
  $myCharacterEffects = $playerID == 1 ? $p1CharacterEffects : $p2CharacterEffects;
  $mySoul = $playerID == 1 ? $p1Soul : $p2Soul;
  $myCardStats = $playerID == 1 ? $p1CardStats : $p2CardStats;
  $myTurnStats = $playerID == 1 ? $p1TurnStats : $p2TurnStats;
  $theirHand = $playerID == 1 ? $p2Hand : $p1Hand;
  $theirDeck = $playerID == 1 ? $p2Deck : $p1Deck;
  $theirResources = $playerID == 1 ? $p2Resources : $p1Resources;
  $theirCharacter = $playerID == 1 ? $p2CharEquip : $p1CharEquip;
  $theirArsenal = $playerID == 1 ? $p2Arsenal : $p1Arsenal;
  $theirHealth = $playerID == 1 ? $playerHealths[1] : $playerHealths[0];
  $theirItems = $playerID == 1 ? $p2Items : $p1Items;
  $theirAuras = $playerID == 1 ? $p2Auras : $p1Auras;
  $theirDiscard = $playerID == 1 ? $p2Discard : $p1Discard;
  $theirPitch = $playerID == 1 ? $p2Pitch : $p1Pitch;
  $theirBanish = $playerID == 1 ? $p2Banish : $p1Banish;
  $theirClassState = $playerID == 1 ? $p2ClassState : $p1ClassState;
  $theirCharacterEffects = $playerID == 1 ? $p2CharacterEffects : $p1CharacterEffects;
  $theirSoul = $playerID == 1 ? $p2Soul : $p1Soul;
  $theirCardStats = $playerID == 1 ? $p2CardStats : $p1CardStats;
  $theirTurnStats = $playerID == 1 ? $p2TurnStats : $p1TurnStats;
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
  DoGamestateUpdate();
  $mpgBuiltFor = $mainPlayer;
  $mainHand = $mainPlayer == 1 ? $p1Hand : $p2Hand;
  $mainDeck = $mainPlayer == 1 ? $p1Deck : $p2Deck;
  $mainResources = $mainPlayer == 1 ? $p1Resources : $p2Resources;
  $mainCharacter = $mainPlayer == 1 ? $p1CharEquip : $p2CharEquip;
  $mainArsenal = $mainPlayer == 1 ? $p1Arsenal : $p2Arsenal;
  $mainHealth = $mainPlayer == 1 ? $playerHealths[0] : $playerHealths[1];
  $mainItems = $mainPlayer == 1 ? $p1Items : $p2Items;
  $mainAuras = $mainPlayer == 1 ? $p1Auras : $p2Auras;
  $mainPitch = $mainPlayer == 1 ? $p1Pitch : $p2Pitch;
  $mainBanish = $mainPlayer == 1 ? $p1Banish : $p2Banish;
  $mainClassState = $mainPlayer == 1 ? $p1ClassState : $p2ClassState;
  $mainCharacterEffects = $mainPlayer == 1 ? $p1CharacterEffects : $p2CharacterEffects;
  $mainDiscard = $mainPlayer == 1 ? $p1Discard : $p2Discard;
  $mainSoul = $mainPlayer == 1 ? $p1Soul : $p2Soul;
  $mainCardStats = $mainPlayer == 1 ? $p1CardStats : $p2CardStats;
  $mainTurnStats = $mainPlayer == 1 ? $p1TurnStats : $p2TurnStats;
  $defHand = $mainPlayer == 1 ? $p2Hand : $p1Hand;
  $defDeck = $mainPlayer == 1 ? $p2Deck : $p1Deck;
  $defResources = $mainPlayer == 1 ? $p2Resources : $p1Resources;
  $defCharacter = $mainPlayer == 1 ? $p2CharEquip : $p1CharEquip;
  $defArsenal = $mainPlayer == 1 ? $p2Arsenal : $p1Arsenal;
  $defHealth = $mainPlayer == 1 ? $playerHealths[1] : $playerHealths[0];
  $defItems = $mainPlayer == 1 ? $p2Items : $p1Items;
  $defAuras = $mainPlayer == 1 ? $p2Auras : $p1Auras;
  $defPitch = $mainPlayer == 1 ? $p2Pitch : $p1Pitch;
  $defBanish = $mainPlayer == 1 ? $p2Banish : $p1Banish;
  $defClassState = $mainPlayer == 1 ? $p2ClassState : $p1ClassState;
  $defCharacterEffects = $mainPlayer == 1 ? $p2CharacterEffects : $p1CharacterEffects;
  $defDiscard = $mainPlayer == 1 ? $p2Discard : $p1Discard;
  $defSoul = $mainPlayer == 1 ? $p2Soul : $p1Soul;
  $defCardStats = $mainPlayer == 1 ? $p2CardStats : $p1CardStats;
  $defTurnStats = $mainPlayer == 1 ? $p2TurnStats : $p1TurnStats;

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

  $p1Deck = $mpgBuiltFor == 1 ? $mainDeck : $defDeck;
  $p1Hand = $mpgBuiltFor == 1 ? $mainHand : $defHand;
  $p1Resources = $mpgBuiltFor == 1 ? $mainResources : $defResources;
  $p1CharEquip = $mpgBuiltFor == 1 ? $mainCharacter : $defCharacter;
  $p1Arsenal = $mpgBuiltFor == 1 ? $mainArsenal : $defArsenal;
  $playerHealths[0] = $mpgBuiltFor == 1 ? $mainHealth : $defHealth;
  $p1Items = $mpgBuiltFor == 1 ? $mainItems : $defItems;
  $p1Auras = $mpgBuiltFor == 1 ? $mainAuras : $defAuras;
  $p1Pitch = $mpgBuiltFor == 1 ? $mainPitch : $defPitch;
  $p1Banish = $mpgBuiltFor == 1 ? $mainBanish : $defBanish;
  $p1ClassState = $mpgBuiltFor == 1 ? $mainClassState : $defClassState;
  $p1CharacterEffects = $mpgBuiltFor == 1 ? $mainCharacterEffects : $defCharacterEffects;
  $p1Discard = $mpgBuiltFor == 1 ? $mainDiscard : $defDiscard;
  $p1Soul = $mpgBuiltFor == 1 ? $mainSoul : $defSoul;
  $p1CardStats = $mpgBuiltFor == 1 ? $mainCardStats : $defCardStats;
  $p1TurnStats = $mpgBuiltFor == 1 ? $mainTurnStats : $defTurnStats;
  $p2Deck = $mpgBuiltFor == 2 ? $mainDeck : $defDeck;
  $p2Hand = $mpgBuiltFor == 2 ? $mainHand : $defHand;
  $p2Resources = $mpgBuiltFor == 2 ? $mainResources : $defResources;
  $p2CharEquip = $mpgBuiltFor == 2 ? $mainCharacter : $defCharacter;
  $p2Arsenal = $mpgBuiltFor == 2 ? $mainArsenal : $defArsenal;
  $playerHealths[1] = $mpgBuiltFor == 2 ? $mainHealth : $defHealth;
  $p2Items = $mpgBuiltFor == 2 ? $mainItems : $defItems;
  $p2Auras = $mpgBuiltFor == 2 ? $mainAuras : $defAuras;
  $p2Pitch = $mpgBuiltFor == 2 ? $mainPitch : $defPitch;
  $p2Banish = $mpgBuiltFor == 2 ? $mainBanish : $defBanish;
  $p2ClassState = $mpgBuiltFor == 2 ? $mainClassState : $defClassState;
  $p2CharacterEffects = $mpgBuiltFor == 2 ? $mainCharacterEffects : $defCharacterEffects;
  $p2Discard = $mpgBuiltFor == 2 ? $mainDiscard : $defDiscard;
  $p2Soul = $mpgBuiltFor == 2 ? $mainSoul : $defSoul;
  $p2CardStats = $mpgBuiltFor == 2 ? $mainCardStats : $defCardStats;
  $p2TurnStats = $mpgBuiltFor == 2 ? $mainTurnStats : $defTurnStats;
}

function MakeGamestateBackup($filename = "gamestateBackup.txt")
{
  global $filepath;
  if(!file_exists($filepath . "gamestate.txt")) WriteLog("Cannot copy gamestate file; it does not exist.");
  
  // Handle special backups (like preBlockBackup.txt, beginTurnGamestate.txt, etc.)
  if ($filename != "gamestateBackup.txt") {
    $result = copy($filepath . "gamestate.txt", $filepath . $filename);
    if(!$result) WriteLog("Copy of gamestate into " . $filename . " failed.");
    return;
  }
  
  // Multi-level undo: Rotate backups
  // Shift all existing backups: 0->1, 1->2, 2->3, 3->4, delete 4
  for ($i = MAX_UNDO_BACKUPS - 1; $i > 0; $i--) {
    $oldFile = $filepath . "gamestateBackup_" . ($i - 1) . ".txt";
    $newFile = $filepath . "gamestateBackup_" . $i . ".txt";
    if (file_exists($oldFile)) {
      copy($oldFile, $newFile);
    }
  }
  
  // Save current state as backup 0 (most recent)
  $result = copy($filepath . "gamestate.txt", $filepath . "gamestateBackup_0.txt");
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
    return;
  }
  
  // Multi-level undo: Revert to backup N steps back
  $targetBackup = $stepsBack - 1; // stepsBack=1 means backup_0, stepsBack=2 means backup_1, etc.
  $backupFile = $filepath . "gamestateBackup_" . $targetBackup . ".txt";
  
  if(!file_exists($backupFile)) {
    WriteLog("Cannot undo further: Please revert to start of this/previousturn instead.");
    return;
  }
  // apply current settings to the backup
  $gamestateBackup = file($backupFile);
  $gamestateBackup[18] = implode(" ", $p1Settings) . "\r\n";
  $gamestateBackup[36] = implode(" ", $p2Settings) . "\r\n";
  file_put_contents($backupFile, $gamestateBackup);
  // Restore the target backup as current gamestate
  copy($backupFile, $filepath . "gamestate.txt");
  $skipWriteGamestate = true;
  $gamestate = file_get_contents($backupFile);
  WriteGamestateCache($gameName, $gamestate);
  
  // Shift backups: Remove the reverted backups and shift remaining ones
  // If we reverted 2 steps, backups 0 and 1 are gone, backup 2 becomes 0, backup 3 becomes 1, etc.
  for ($i = 0; $i < MAX_UNDO_BACKUPS; $i++) {
    $sourceIndex = $i + $stepsBack;
    $sourceFile = $filepath . "gamestateBackup_" . $sourceIndex . ".txt";
    $targetFile = $filepath . "gamestateBackup_" . $i . ".txt";
    
    if ($sourceIndex < MAX_UNDO_BACKUPS && file_exists($sourceFile)) {
      copy($sourceFile, $targetFile);
    } else {
      // No more backups to shift, delete this slot
      if (file_exists($targetFile)) {
        unlink($targetFile);
      }
    }
  }
  $result = copy($filepath . "gamestate.txt", $filepath . $filename);
  if(!$result) WriteLog("Copy of gamestate into " . $filename . " failed.");
}

function SaveReplay() {
  return !IsDevEnvironment();
}

function MakeStartTurnBackup()
{
  global $mainPlayer, $currentTurn, $filepath;
  $lastTurnFN = $filepath . "lastTurnGamestate.txt";
  $thisTurnFN = $filepath . "beginTurnGamestate.txt";
  if (file_exists($thisTurnFN)) copy($thisTurnFN, $lastTurnFN);
  copy($filepath . "gamestate.txt", $thisTurnFN);
  $startGameFN = $filepath . "startGamestate.txt";
  if ((IsPatron(1) || IsPatron(2)) && $currentTurn == 0 && !file_exists($startGameFN)) {
    copy($filepath . "gamestate.txt", $startGameFN);
  }
  if (SaveReplay()) {
    $numberedTurnFN = $filepath . "turn_$mainPlayer-$currentTurn" . "_Gamestate.txt";
    copy($filepath . "gamestate.txt", $numberedTurnFN);
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
  
  // Check if beginTurnGamestate exists (it should at the start of a rematch)
  $beginTurnFile = $filepath . "beginTurnGamestate.txt";
  if (!file_exists($beginTurnFile)) {
    return; // Nothing to reset if beginTurnGamestate doesn't exist
  }
  
  $beginTurnContent = file_get_contents($beginTurnFile);
  
  // Populate all undo backup slots with beginTurnGamestate content
  for ($i = 0; $i < MAX_UNDO_BACKUPS; $i++) {
    $backupFile = $filepath . "gamestateBackup_" . $i . ".txt";
    file_put_contents($backupFile, $beginTurnContent);
  }
  
  // Overwrite lastTurnGamestate with beginTurnGamestate as well
  $lastTurnFile = $filepath . "lastTurnGamestate.txt";
  file_put_contents($lastTurnFile, $beginTurnContent);
}
