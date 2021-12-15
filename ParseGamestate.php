<?php

  $mainPlayerGamestateBuilt = 0;

  $filename = "./Games/" . $gameName . "/gamestate.txt";

  $handler = fopen($filename, "r");
  $playerHealths = GetArray($handler);

  //Player 1
  $p1Hand = GetArray($handler);
  $p1Deck = GetArray($handler);
  $p1CharEquip = GetArray($handler);
  $p1Resources = GetArray($handler);
  $p1Arsenal = GetArray($handler);
  $p1Items = GetArray($handler);
  $p1Auras = GetArray($handler);
  $p1Discard = GetArray($handler);
  $p1Pitch = GetArray($handler);
  $p1Banish = GetArray($handler);
  $p1ClassState = GetArray($handler);
  $p1CharacterEffects = GetArray($handler);
  $p1Soul = GetArray($handler);
  $p1CardStats = GetArray($handler);
  $p1TurnStats = GetArray($handler);
  $p1Allies = GetArray($handler);

  //Player 2
  $p2Hand = GetArray($handler);
  $p2Deck = GetArray($handler);
  $p2CharEquip = GetArray($handler);
  $p2Resources = GetArray($handler);
  $p2Arsenal = GetArray($handler);
  $p2Items = GetArray($handler);
  $p2Auras = GetArray($handler);
  $p2Discard = GetArray($handler);
  $p2Pitch = GetArray($handler);
  $p2Banish = GetArray($handler);
  $p2ClassState = GetArray($handler);
  $p2CharacterEffects = GetArray($handler);
  $p2Soul = GetArray($handler);
  $p2CardStats = GetArray($handler);
  $p2TurnStats = GetArray($handler);
  $p2Allies = GetArray($handler);

  $winner = trim(fgets($handler));
  $currentPlayer = trim(fgets($handler));
  $currentTurn = trim(fgets($handler));
  $turn = GetArray($handler);
  $actionPoints = trim(fgets($handler));
  $combatChain = GetArray($handler);
  $combatChainState = GetArray($handler);
  $currentTurnEffects = GetArray($handler);
  $currentTurnEffectsFromCombat = GetArray($handler);
  $nextTurnEffects = GetArray($handler);
  $decisionQueue = GetArray($handler);
  $layers = GetArray($handler);
  $layerPriority = GetArray($handler);
  $mainPlayer = trim(fgets($handler));
  $defPlayer = $mainPlayer == 1 ? 2 : 1;
  fclose($handler);

  BuildMyGamestate($playerID);

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
    global $myStateBuiltFor;
    $myStateBuiltFor = $playerID;
    $myHand = $playerID==1 ? $p1Hand : $p2Hand;
    $myDeck = $playerID==1 ? $p1Deck : $p2Deck;
    $myResources = $playerID==1 ? $p1Resources : $p2Resources;
    $myCharacter = $playerID==1 ? $p1CharEquip : $p2CharEquip;
    $myArsenal = $playerID==1 ? $p1Arsenal : $p2Arsenal;
    $myHealth = $playerID==1 ? $playerHealths[0] : $playerHealths[1];
    $myItems = $playerID==1 ? $p1Items : $p2Items;
    $myAuras = $playerID==1 ? $p1Auras : $p2Auras;
    $myDiscard = $playerID==1 ? $p1Discard : $p2Discard;
    $myPitch = $playerID==1 ? $p1Pitch : $p2Pitch;
    $myBanish = $playerID==1 ? $p1Banish : $p2Banish;
    $myClassState = $playerID==1 ? $p1ClassState : $p2ClassState;
    $myCharacterEffects = $playerID==1 ? $p1CharacterEffects : $p2CharacterEffects;
    $mySoul = $playerID==1 ? $p1Soul : $p2Soul;
    $myCardStats = $playerID==1 ? $p1CardStats : $p2CardStats;
    $myTurnStats = $playerID==1 ? $p1TurnStats : $p2TurnStats;
    $theirHand = $playerID==1 ? $p2Hand : $p1Hand;
    $theirDeck = $playerID==1 ? $p2Deck : $p1Deck;
    $theirResources = $playerID==1 ? $p2Resources : $p1Resources;
    $theirCharacter = $playerID==1 ? $p2CharEquip : $p1CharEquip;
    $theirArsenal = $playerID==1 ? $p2Arsenal : $p1Arsenal;
    $theirHealth = $playerID==1? $playerHealths[1] : $playerHealths[0];
    $theirItems = $playerID==1 ? $p2Items : $p1Items;
    $theirAuras = $playerID==1 ? $p2Auras : $p1Auras;
    $theirDiscard = $playerID==1 ? $p2Discard : $p1Discard;
    $theirPitch = $playerID==1 ? $p2Pitch : $p1Pitch;
    $theirBanish = $playerID==1 ? $p2Banish : $p1Banish;
    $theirClassState = $playerID==1 ? $p2ClassState : $p1ClassState;
    $theirCharacterEffects = $playerID==1 ? $p2CharacterEffects : $p1CharacterEffects;
    $theirSoul = $playerID==1 ? $p2Soul : $p1Soul;
    $theirCardStats = $playerID==1 ? $p2CardStats : $p1CardStats;
    $theirTurnStats = $playerID==1 ? $p2TurnStats : $p1TurnStats;
  }

  function GetArray($handler)
  {
    $line = trim(fgets($handler));
    if($line=="") return [];
    return explode(" ", $line);
  }

  function BuildMainPlayerGameState()
  {
    global $mainPlayer, $mainPlayerGamestateBuilt, $mainPlayerGamestateStillBuilt, $playerHealths;
    global $mainHand, $mainDeck, $mainResources, $mainCharacter, $mainArsenal, $mainHealth, $mainAuras, $mainPitch, $mainBanish, $mainClassState, $mainItems;
    global $mainCharacterEffects, $mainDiscard;
    global $defHand, $defDeck, $defResources, $defCharacter, $defArsenal, $defHealth, $defAuras, $defPitch, $defBanish, $defClassState, $defItems;
    global $defCharacterEffects, $defDiscard;
    global $p1Deck, $p1Hand, $p1Resources, $p1CharEquip, $p1Arsenal, $p1Auras, $p1Pitch, $p1Banish, $p1ClassState, $p1Items, $p1CharacterEffects, $p1Discard;
    global $p2Deck, $p2Hand, $p2Resources, $p2CharEquip, $p2Arsenal, $p2Auras, $p2Pitch, $p2Banish, $p2ClassState, $p2Items, $p2CharacterEffects, $p2Discard;
    global $p1Soul, $p2Soul, $mainSoul, $defSoul;
    global $p1CardStats, $p2CardStats, $mainCardStats, $defCardStats;
    global $p1TurnStats, $p2TurnStats, $mainTurnStats, $defTurnStats;

    if($mainPlayerGamestateStillBuilt) return;

    $mainHand = $mainPlayer==1 ? $p1Hand : $p2Hand;
    $mainDeck = $mainPlayer==1 ? $p1Deck : $p2Deck;
    $mainResources = $mainPlayer==1 ? $p1Resources : $p2Resources;
    $mainCharacter = $mainPlayer==1 ? $p1CharEquip : $p2CharEquip;
    $mainArsenal = $mainPlayer==1 ? $p1Arsenal : $p2Arsenal;
    $mainHealth = $mainPlayer==1 ? $playerHealths[0] : $playerHealths[1];
    $mainItems = $mainPlayer==1 ? $p1Items : $p2Items;
    $mainAuras = $mainPlayer==1 ? $p1Auras : $p2Auras;
    $mainPitch = $mainPlayer==1 ? $p1Pitch : $p2Pitch;
    $mainBanish = $mainPlayer==1 ? $p1Banish : $p2Banish;
    $mainClassState = $mainPlayer==1 ? $p1ClassState : $p2ClassState;
    $mainCharacterEffects = $mainPlayer==1 ? $p1CharacterEffects : $p2CharacterEffects;
    $mainDiscard = $mainPlayer==1 ? $p1Discard : $p2Discard;
    $mainSoul = $mainPlayer==1 ? $p1Soul : $p2Soul;
    $mainCardStats = $mainPlayer==1 ? $p1CardStats : $p2CardStats;
    $mainTurnStats = $mainPlayer==1 ? $p1TurnStats : $p2TurnStats;
    $defHand = $mainPlayer==1 ? $p2Hand : $p1Hand;
    $defDeck = $mainPlayer==1 ? $p2Deck : $p1Deck;
    $defResources = $mainPlayer==1 ? $p2Resources : $p1Resources;
    $defCharacter = $mainPlayer==1 ? $p2CharEquip : $p1CharEquip;
    $defArsenal = $mainPlayer==1 ? $p2Arsenal : $p1Arsenal;
    $defHealth = $mainPlayer==1? $playerHealths[1] : $playerHealths[0];
    $defItems = $mainPlayer==1 ? $p2Items : $p1Items;
    $defAuras = $mainPlayer==1 ? $p2Auras : $p1Auras;
    $defPitch = $mainPlayer==1 ? $p2Pitch : $p1Pitch;
    $defBanish = $mainPlayer==1 ? $p2Banish : $p1Banish;
    $defClassState = $mainPlayer == 1 ? $p2ClassState : $p1ClassState;
    $defCharacterEffects = $mainPlayer==1 ? $p2CharacterEffects : $p1CharacterEffects;
    $defDiscard = $mainPlayer==1 ? $p2Discard : $p1Discard;
    $defSoul = $mainPlayer==1 ? $p2Soul : $p1Soul;
    $defCardStats = $mainPlayer==1 ? $p2CardStats : $p1CardStats;
    $defTurnStats = $mainPlayer==1 ? $p2TurnStats : $p1TurnStats;

    $mainPlayerGamestateBuilt = 1;
    $mainPlayerGamestateStillBuilt = 1;
  }

  function UpdateGameState($activePlayer)
  {
    global $mainPlayerGamestateBuilt;
    if($mainPlayerGamestateBuilt == 1) return;//We're on main player game state now
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
    if($activePlayer==1) {
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

  function UpdateMainPlayerGameState()
  {
    global $mainPlayer, $mainPlayerGamestateStillBuilt;
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

    $p1Deck = $mainPlayer==1 ? $mainDeck : $defDeck;
    $p1Hand = $mainPlayer==1 ? $mainHand : $defHand;
    $p1Resources = $mainPlayer==1 ? $mainResources : $defResources;
    $p1CharEquip = $mainPlayer==1 ? $mainCharacter : $defCharacter;
    $p1Arsenal = $mainPlayer == 1 ? $mainArsenal : $defArsenal;
    $playerHealths[0] = $mainPlayer == 1 ? $mainHealth : $defHealth;
    $p1Items = $mainPlayer == 1 ? $mainItems : $defItems;
    $p1Auras = $mainPlayer == 1 ? $mainAuras : $defAuras;
    $p1Pitch = $mainPlayer == 1 ? $mainPitch : $defPitch;
    $p1Banish = $mainPlayer == 1 ? $mainBanish : $defBanish;
    $p1ClassState = $mainPlayer == 1 ? $mainClassState : $defClassState;
    $p1CharacterEffects = $mainPlayer == 1 ? $mainCharacterEffects : $defCharacterEffects;
    $p1Discard = $mainPlayer == 1 ? $mainDiscard : $defDiscard;
    $p1Soul = $mainPlayer == 1 ? $mainSoul : $defSoul;
    $p1CardStats = $mainPlayer == 1 ? $mainCardStats : $defCardStats;
    $p1TurnStats = $mainPlayer == 1 ? $mainTurnStats : $defTurnStats;
    $p2Deck = $mainPlayer==2 ? $mainDeck : $defDeck;
    $p2Hand = $mainPlayer==2 ? $mainHand : $defHand;
    $p2Resources = $mainPlayer==2 ? $mainResources : $defResources;
    $p2CharEquip = $mainPlayer==2 ? $mainCharacter : $defCharacter;
    $p2Arsenal = $mainPlayer == 2 ? $mainArsenal : $defArsenal;
    $playerHealths[1] = $mainPlayer == 2 ? $mainHealth : $defHealth;
    $p2Items = $mainPlayer == 2 ? $mainItems : $defItems;
    $p2Auras = $mainPlayer == 2 ? $mainAuras : $defAuras;
    $p2Pitch = $mainPlayer == 2 ? $mainPitch : $defPitch;
    $p2Banish = $mainPlayer == 2 ? $mainBanish : $defBanish;
    $p2ClassState = $mainPlayer == 2 ? $mainClassState : $defClassState;
    $p2CharacterEffects = $mainPlayer == 2 ? $mainCharacterEffects : $defCharacterEffects;
    $p2Discard = $mainPlayer == 2 ? $mainDiscard : $defDiscard;
    $p2Soul = $mainPlayer == 2 ? $mainSoul : $defSoul;
    $p2CardStats = $mainPlayer == 2 ? $mainCardStats : $defCardStats;
    $p2TurnStats = $mainPlayer == 2 ? $mainTurnStats : $defTurnStats;

    $mainPlayerGamestateStillBuilt = 0;
  }

  function MakeGamestateBackup($filename="gamestateBackup.txt")
  {
    global $gameName;
    $filepath = "./Games/" . $gameName . "/";
    copy($filepath . "gamestate.txt", $filepath . $filename);
  }

  function RevertGamestate($filename="gamestateBackup.txt")
  {
    global $gameName, $skipWriteGamestate;
    $filepath = "./Games/" . $gameName . "/";
    copy($filepath . $filename, $filepath . "gamestate.txt");
    $skipWriteGamestate = true;
  }

?>

