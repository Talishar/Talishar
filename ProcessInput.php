<?php

  include "WriteLog.php";
  include "GameLogic.php";
  include "GameTerms.php";
  include "HostFiles/Redirector.php";

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];

  //We should also have some information on the type of command
  $mode = $_GET["mode"];
  $buttonInput = isset($_GET["buttonInput"]) ? $_GET["buttonInput"] : "";//The player that is the target of the command - e.g. for changing health total
  $cardID = isset($_GET["cardID"]) ? $_GET["cardID"] : "";
  $chkCount = isset($_GET["chkCount"]) ? $_GET["chkCount"] : 0;
  $chkInput = [];
  for($i=0; $i<$chkCount; ++$i)
  {
    $chk = isset($_GET[("chk" . $i)]) ? $_GET[("chk" . $i)] : "";
    if($chk != "") array_push($chkInput, $chk);
  }

  //First we need to parse the game state from the file
  include "ParseGamestate.php";
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  $skipWriteGamestate = false;

  //Now we can process the command
  switch($mode) {
    case 0: //Subtract health
      $playerHealths[$player] -= 1;
      WriteLog("Player " . $playerID . " reduced player " . ($player+1) . "'s health by 1.");
      break;
    case 1: //Add health
      $playerHealths[$player] += 1;
      WriteLog("Player " . $playerID . " increased player " . ($player+1) . "'s health by 1.");
      break;
    case 2: //Play card from hand
      $found = HasCard($cardID);
      if($found >= 0) {
        //Player actually has the card, now do the effect
        //First remove it from their hand
        unset($myHand[$found]);
        $myHand = array_values($myHand);
        PlayCard($cardID, "HAND");
      }
      break;
    case 3: //Play equipment ability
      $index = $cardID;
      $cardID = $myCharacter[$index];
      $found = HasCard($cardID);
      $myClassState[$CS_CharacterIndex] = $found;
      $combatChainState[$CCS_WeaponIndex] = $found;
      //TODO: validate that the player should actually be able to use this
      if($turn[0] == "B")
      {
        if(HasBladeBreak($cardID)) $myCharacter[$found+1] = 0;//Destroy if blocked and it had blade break;
        else $myCharacter[$found+1] = 1;//Else just exhaust it
        //TODO: Temper
      }
      else
      {
        EquipPayAdditionalCosts($index, "EQUIP");
      }
      PlayCard($cardID, "EQUIP", -1, $found);
      break;
    case 4: //Add something to your arsenal
      $found = HasCard($cardID);
      if($found >= 0) {
        unset($myHand[$found]);
        $myHand = array_values($myHand);
        $myArsenal = $cardID;
        $myClassState[$CS_ArsenalFacing] = "DOWN";
        PassTurn();
      }
      break;
    case 5:
      if($myArsenal != "")
      {
        $cardToPlay = $myArsenal;
        $myArsenal = "";
        PlayCard($cardToPlay, "ARS");
      }
      break;
    case 6://Pitch Deck
      $found = PitchHasCard($cardID);
      if($found >= 0)
      {
        unset($myPitch[$found]);
        $myPitch = array_values($myPitch);
        array_push($myDeck, $cardID);
        PassTurn();//Resume passing the turn
      }
      break;
    case 7://Number input
     if($turn[0] == "DYNPITCH")
     {
       ContinueDecisionQueue($buttonInput);
     }
     break;
    case 8: case 9://OPT TOP
      if($turn[0] == "OPT")
      {
        $options = explode(",", $turn[2]);
        $found = -1;
        for($i=0; $i<count($options); ++$i)
        {
          if($options[$i] == $buttonInput) { $found = $i; break; }
        }
        if($found == -1) break;//Invalid input
        if($mode == 8) array_unshift($myDeck, $buttonInput);
        else if($mode == 9) array_push($myDeck, $buttonInput);
        unset($options[$found]);
        $options = array_values($options);
        if(count($options) > 0) PrependDecisionQueue("OPT", $currentPlayer, implode(",", $options));
        ContinueDecisionQueue($buttonInput);
      }
      break;
    case 10://Item ability
      $index = $cardID;//Overridden to be index instead
      $cardID = $myItems[$index];
      $myClassState[$CS_PlayIndex] = $index;
      $set = CardSet($cardID);
      if($set != "WTR")
      {
        PlayCard($cardID, "PLAY", -1);
      }
      else
      {
        if((!AbilityHasGoAgain($cardID) || CurrentEffectPreventsGoAgain()) && GetAbilityType($cardID) != "I") --$actionPoints;
        ItemActionAbility($index);
        FinalizeAction();//TODO: Make this work
      }
      break;
    case 11://CHOOSEDECK
      $index = $cardID;
      $cardID = $myDeck[$index];
      unset($myDeck[$index]);
      $myDeck = array_values($myDeck);
      //TODO: Shuffle deck here
      ContinueDecisionQueue($cardID);
      break;
    case 12://HANDTOP
      $cardID = $myHand[$buttonInput];
      array_unshift($myDeck, $cardID);
      unset($myHand[$buttonInput]);
      $myHand = array_values($myHand);
      ContinueDecisionQueue($cardID);
      break;
    case 13://HANDBOTTOM
      $cardID = $myHand[$buttonInput];
      array_push($myDeck, $cardID);
      unset($myHand[$buttonInput]);
      $myHand = array_values($myHand);
      ContinueDecisionQueue($cardID);
      break;
    case 14://Banish
      $index = $cardID;
      $cardID = $myBanish[$index];
      if($myBanish[$index+1] == "INST") SetClassState($currentPlayer, $CS_NextNAAInstant, 1);
      unset($myBanish[$index+1]);
      unset($myBanish[$index]);
      $myBanish = array_values($myBanish);
      PlayCard($cardID, "BANISH", -1, $index);
      break;
    case 15://CHOOSECOMBATCHAIN
      $index = $cardID;
      ContinueDecisionQueue($index);
      break;
    case 16://CHOOSEHAND
      $index = $cardID;
      ContinueDecisionQueue($index);
      break;
    case 17://BUTTONINPUT
      ContinueDecisionQueue($buttonInput);
      break;
    case 18://CHOOSEDISCARD
      $index = $cardID;
      ContinueDecisionQueue($index);
      break;
    case 19://MULTICHOOSEDISCARD, MULTICHOOSEHAND, MULTICHOOSEDECK
      $params = explode("-", $turn[2]);
      $maxSelect = intval($params[0]);
      $options = explode(",", $params[1]);
      if(count($chkInput) > $maxSelect)
      {
        WriteLog("You selected " . count($chkInput) . " items, but a maximum of " . $maxSelect . " was allowed. Reverting gamestate prior to that effect.");
        RevertGamestate();
        $skipWriteGamestate = true;
        break;
      }
      for($i=0; $i<count($chkInput); ++$i)
      {
        $found = 0;
        for($j=0; $j < count($options); ++$j)
        {
          if($chkInput[$i] == $options[$j]) { $found = 1; break; }
        }
        if(!$found)
        {
          WriteLog("You selected option " . $chkInput[$i] . " but that was not one of the original options. Reverting gamestate prior to that effect.");
          RevertGamestate();
          $skipWriteGamestate = true;
          break;
        }
      }
      if(!$skipWriteGamestate)
      {
        ContinueDecisionQueue($chkInput);
      }
      break;
    case 20://YESNO
      if($buttonInput == "YES" || $buttonInput == "NO") ContinueDecisionQueue($buttonInput);
      break;
    case 21://Combat chain ability
      $index = $cardID;//Overridden to be index instead
      $cardID = $combatChain[$index];
      $myClassState[$CS_PlayIndex] = $index;
      PlayCard($cardID, "PLAY", -1);
      break;
    case 99: //Pass
      if($turn[0] == "MAYCHOOSEHAND" || $turn[0] == "MAYCHOOSEDISCARD")
      {
        ContinueDecisionQueue("PASS");
      }
      else
      {
        if(Pass($turn, $playerID, $currentPlayer))
        {
          PassTurn();
        }
        WriteLog("Player " . $playerID . " passed.");
      }
      break;
    case 10000:
      RevertGamestate();
      $skipWriteGamestate = true;
      WriteLog("Player " . $playerID . " undid their last action.");
      break;
  }

  if($winner != 0) { $turn[0] = "OVER"; }

  //Now write out the game state
  if(!$skipWriteGamestate)
  {
    include "WriteGamestate.php";
  }

  if($makeCheckpoint) MakeGamestateBackup();

  header("Location: " . $redirectPath . "/NextTurn.php?gameName=$gameName&playerID=" . $playerID);

  exit;

  function PitchHasCard($cardID)
  {
    global $myPitch;
    for($i=0; $i<$myPitch; ++$i)
    {
      if($myPitch[$i] == $cardID) return $i;
    }
    return -1;
  }

  function HasCard($cardID)
  {
      global $myHand, $myCharacter;
      $cardType = CardType($cardID);
      if($cardType == "C" || $cardType == "E" || $cardType == "W")
      {
        for($i=0; $i<count($myCharacter); $i+=CharacterPieces())
        {
          if($myCharacter[$i] == $cardID) {
            return $i;
          }
        }
      }
      else
      {
        for($i=0; $i<count($myHand); ++$i) {
          if($myHand[$i] == $cardID) {
            return $i;
          }
        }
      }
      return -1;
  }

  function Passed(&$turn, $playerID) {
    return $turn[1+$playerID];
  }

  function Pass(&$turn, $playerID, &$currentPlayer) {
    if($turn[0] == "M" || $turn[0] == "ARS")
    {
      return 1;
    }
    else if($turn[0] == "B")
    {
      $currentPlayer = $currentPlayer == 1 ? 2 : 1;
      $turn[0] = "A";
    }
    else if($turn[0] == "A")
    {
      if($turn[2] == "D")
      {
        return ResolveChainLink();
      }
      else
      {
        $currentPlayer = $currentPlayer == 1 ? 2 : 1;
        $turn[0] = "D";
        $turn[2] = "A";
      }
    }
    else if($turn[0] == "D")
    { 
      if($turn[2] == "A")
      {
        return ResolveChainLink();
      }
      else
      {
        $currentPlayer = $currentPlayer == 1 ? 2 : 1;
        $turn[0] = "A";
        $turn[2] = "D";
      }
    }
    return 0;
  }

  function ResolveChainLink()
  {
    global $turn, $actionPoints, $combatChain, $currentPlayer, $mainPlayer, $defPlayer, $playerID, $defHealth, $currentTurnEffects, $currentTurnEffectsFromCombat;
    global $defCharacter, $mainDiscard, $defDiscard, $defAuras;
    global $combatChainState,$CCS_CurrentAttackGainedGoAgain, $actionPoints, $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow;
    global $mainClassState, $defClassState, $CS_AtksWWeapon, $CS_DamagePrevention, $CCS_HitsWithWeapon, $CCS_ChainAttackBuff;
    UpdateGameState($playerID);
    BuildMainPlayerGameState();
    $currentTurnEffectsFromCombat = [];

    $totalAttack = 0;
    $totalDefense = 0;
    $attackType = CardType($combatChain[0]);
    $CanGainAttack = !SearchCurrentTurnEffects("CRU035", $mainPlayer) || $attackType != "AA";
    $SnagActive = SearchCurrentTurnEffects("CRU182", $mainPlayer) && $attackType == "AA";
    //First check combat chain
    for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
    {
      $from = $combatChain[$i+1];
      $resourcesPaid = $combatChain[$i+2];
      if($combatChain[$i] == $mainPlayer)
      {
        $attack = AttackValue($combatChain[$i-1]);
        if($CanGainAttack || $i == 1 || $attack < 0) $totalAttack += $attack;
        $attack = AttackModifier($combatChain[$i-1], $combatChain[$i+1], $combatChain[$i+2], $combatChain[$i+3]) + $combatChain[$i + 4];
        if(($CanGainAttack && !$SnagActive) || $attack < 0) $totalAttack += $attack;
      }
      else
      {
        $defense = BlockValue($combatChain[$i-1]) + BlockModifier($combatChain[$i-1], $from, $resourcesPaid) + $combatChain[$i + 5];
        if($defense > 0) $totalDefense += $defense;
        if(CardType($combatChain[$i-1]) == "E")
        {
          $index = FindDefCharacter($combatChain[$i-1]);
          $totalDefense += $defCharacter[$index+4];
        }
      }
      CombatChainResolutionEffects($combatChain[$i-1], $combatChain[$i]);
    }

    //Now check current turn effects
    for($i=0; $i<count($currentTurnEffects); $i+=2)
    {
      if(IsCombatEffectActive($currentTurnEffects[$i]))
      {
        if($currentTurnEffects[$i+1] == $mainPlayer)
        {
          $attack = EffectAttackModifier($currentTurnEffects[$i]);
          if($CanGainAttack || $attack < 0) $totalAttack += $attack;
        }
        else
        {
          $totalDefense += EffectBlockModifier($currentTurnEffects[$i], "", 0);
        }
      }
    }

    $attack = MainCharacterAttackModifiers();//TODO: If there are both negatives and positives here, this might mess up?...
    if($CanGainAttack || $attack < 0) $totalAttack += $attack;
    $attack = $combatChainState[$CCS_ChainAttackBuff];
    if($CanGainAttack || $attack < 0) $totalAttack += $attack;

    $damage = $totalAttack - $totalDefense;
    $damageDone = DealDamage($defPlayer, $damage, "COMBAT");//Include prevention
    $wasHit = $damageDone > 0;
    WriteLog("Combat resolved with " . ($wasHit ? "a HIT for $damageDone damage." : "NO hit."));
    if($wasHit)//Resolve hit effects
    {
      for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
      {
        if($combatChain[$i] == $mainPlayer)
        {
          ProcessHitEffect($combatChain[$i-1]);
          if($damage >= 4) ProcessCrushEffect($combatChain[$i-1]);
        }
      }
      for($i=0; $i<count($currentTurnEffects); $i+=2)
      {
        if(IsCombatEffectActive($currentTurnEffects[$i]))
        {
          if($currentTurnEffects[$i+1] == $mainPlayer)
          {
            EffectHitEffect($currentTurnEffects[$i]);
          }
        }
      }
      $combatChainState[$CCS_DamageDealt] = $damage;
      ++$combatChainState[$CCS_NumHits];
      ++$combatChainState[$CCS_HitsInRow];
      if(CardType($combatChain[0]) == "W") ++$combatChainState[$CCS_HitsWithWeapon];
      MainCharacterHitAbilities();
      MainCharacterHitEffects();
      AttackDamageAbilities();
    }
    else
    {
        $combatChainState[$CCS_HitsInRow] = 0;
    }
    AddDecisionQueue("FINALIZECHAINLINK", $mainPlayer, "-");
    $turn[0] = "M";
    $currentPlayer = $mainPlayer;
    ProcessDecisionQueue();//Any combat related decision queue logic should be main player gamestate
}

function FinalizeChainLink()
{
    global $turn, $actionPoints, $combatChain, $mainPlayer, $playerID, $defHealth, $currentTurnEffects, $defCharacter, $mainDiscard, $defDiscard;
    global $combatChainState,$CCS_CurrentAttackGainedGoAgain, $actionPoints, $CCS_LastAttack, $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow;
    global $mainClassState, $defClassState, $CS_AtksWWeapon, $CS_DamagePrevention, $CCS_HitsWithWeapon, $CCS_GoesWhereAfterLinkResolves;
    global $CS_LastAttack;
    UpdateGameState($playerID);
    BuildMainPlayerGameState();

    if(!HasGoAgain($combatChain[0]) && ($combatChainState[$CCS_CurrentAttackGainedGoAgain] == 1 || CurrentEffectGrantsGoAgain()) && !CurrentEffectPreventsGoAgain()) ++$actionPoints;

    //Clean up combat effects that were used and are one-time
    for($i = count($currentTurnEffects) - 2; $i >= 0; --$i)
    {
      if(IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectPersistent($currentTurnEffects[$i]))
      {
        RemoveCurrentTurnEffect($i);
      }
    }

    for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
    {
      if($combatChain[$i] != $mainPlayer)
      {
        if(CardType($combatChain[$i-1]) == "E")
        {
          $index = FindDefCharacter($combatChain[$i-1]);
          if(HasBattleworn($combatChain[$i-1])) --$defCharacter[$index+4];
          if(HasTemper($combatChain[$i-1])) --$defCharacter[$index+4];
        }
      }
    }

    if(CardType($combatChain[0]) == "W") ++$mainClassState[$CS_AtksWWeapon];
    $combatChainState[$CCS_LastAttack] = $combatChain[0];
    SetClassState($mainPlayer, $CS_LastAttack, $combatChain[0]);
    for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
    {
      $cardType = CardType($combatChain[$i-1]);
      if($cardType == "W" || $cardType == "E") continue;

      $goesWhere = GoesWhereAfterResolving($combatChain[$i-1]);
      if($i == 1 && $combatChainState[$CCS_GoesWhereAfterLinkResolves] != "GY") { $goesWhere = $combatChainState[$CCS_GoesWhereAfterLinkResolves]; }
      switch($goesWhere) {
        case "BOTDECK": AddBottomMainDeck($combatChain[$i-1], "CC"); break;
        case "HAND": AddMainHand($combatChain[$i-1], "CC"); break;
        case "SOUL": AddSoul($combatChain[$i-1], $combatChain[$i], "CC"); break;
        case "GY": AddGraveyard($combatChain[$i-1], $combatChain[$i], "CC"); break;
        default: break;
      }
    }
    CopyCurrentTurnEffectsFromCombat();
    CheckDestroyTemper();
    UnsetChainLinkBanish();//For things that are banished and playable only to this chain link
    ResetChainLinkState();
    $combatChain = [];
  }

  function PassTurn()
  {
    global $playerID, $currentPlayer, $turn, $myPitch, $theirPitch, $mainPlayer, $mainPlayerGamestateBuilt;
    if(!$mainPlayerGamestateBuilt)
    {
      UpdateGameState($playerID);
      BuildMainPlayerGameState();
    }
    global $mainArsenal, $mainHand;
    AuraBeginEndStepAbilities();
    if(count($myPitch) > 0)
    {
      $currentPlayer = $playerID;
      $turn[0] = "PDECK";
    }
    else if(count($theirPitch) > 0)
    {
      $currentPlayer = $playerID == 1 ? 2 : 1;
      $turn[0] = "PDECK";
    }
    else if(count($mainHand) > 0 && $mainArsenal == "" && $turn[0] != "ARS")//Arsenal
    {
      $currentPlayer = $mainPlayer;
      $turn[0] = "ARS";
    }
    else
    {
      FinalizeTurn();
    }
  }

  function FinalizeTurn()
  {
    global $currentPlayer, $currentTurn, $playerID, $turn, $combatChain, $actionPoints, $mainPlayer, $currentTurnEffects, $nextTurnEffects;
    global $mainHand, $defHand, $mainDeck, $mainItems, $defItems, $defDeck, $mainCharacter, $defCharacter, $mainResources, $defResources;
    global $mainAuras, $defBanish;
    
    //Undo Intimidate
    for($i=0; $i<count($defBanish); $i+=2)
    {
      if($defBanish[$i+1] == "INT")
      {
        array_push($defHand, $defBanish[$i]);
        unset($defBanish[$i+1]);
        unset($defBanish[$i]);
        $defBanish = array_values($defBanish);
        $i -= 2;
      }
    }

    //Draw Cards
    if($mainPlayer == 1 && $currentTurn == 1) //Defender draws up on turn 1
    {
      $toDraw = 4 - count($defHand);
      for($i=0; $i < $toDraw; ++$i)//TODO: 4 -> Intellect
      {
        array_push($defHand, array_shift($defDeck));
      }
    }
    $toDraw = 4 - count($mainHand) + CurrentEffectIntellectModifier();
    for($i=0; $i < $toDraw; ++$i)//TODO: 4 -> Intellect
    {
      array_push($mainHand, array_shift($mainDeck));
    }
    WriteLog("Main player drew " . $toDraw . " cards and now has " . count($mainHand) . " cards.");

    //Reset characters/equipment
    for($i=1; $i<count($mainCharacter); $i+=CharacterPieces())
    {
      if($mainCharacter[$i-1] == "CRU177" && $mainCharacter[$i+1] >= 3) $mainCharacter[$i] = 0;//Destroy Talishar if >= 3 rust counters
      if($mainCharacter[$i] != 0) { $mainCharacter[$i] = 2; $mainCharacter[$i + 4] = CharacterNumUsesPerTurn($mainCharacter[$i-1]); }
    }
    for($i=1; $i<count($defCharacter); $i+=CharacterPieces())
    {
      if($defCharacter[$i] == 1 || $defCharacter[$i] == 2) { $defCharacter[$i] = 2; $defCharacter[$i + 4] = CharacterNumUsesPerTurn($defCharacter[$i-1]); }
    }

    $mainResources[0] = 0;
    $mainResources[1] = 0;
    $defResources[0] = 0;
    $defResources[1] = 0;

    AuraEndTurnAbilities();
    MainCharacterEndTurnAbilities();
    ResetMainClassState();
    ResetCharacterEffects();
    ResetCombatChainState();
    UnsetTurnBanish();

    UpdateMainPlayerGameState();

    //Update all the player neutral stuff
    if($mainPlayer == 2) $currentTurn += 1;
    $turn[0] = "M";
    //$turn[1] = $mainPlayer == 2 ? $turn[1] + 1 : $turn[1];
    $turn[2] = "";
    $turn[3] = "";
    $actionPoints = 1;
    $combatChain = [];//TODO: Add cards to the discard pile?...
    $currentTurnEffects = $nextTurnEffects;
    $nextTurnEffects = [];
    for($i=0; $i<count($currentTurnEffects); $i+=2)
    {
      WriteLog("Start of turn effect for $currentTurnEffects[$i] is now active.");
    }
    $mainPlayer = $mainPlayer == 1 ? 2 : 1;
    $currentPlayer = $mainPlayer;

    BuildMainPlayerGameState();

    //Start of turn effects
    //Handle Auras for the new main player
    AuraStartTurnAbilities();
    //Handle items for the new main player
    for($i=count($mainItems)-ItemPieces(); $i>= 0; $i-=ItemPieces())
    {
      if($mainItems[$i+2] == 1) $mainItems[$i+2] = 2;
      ItemStartTurnAbility($i);
    }
    for($i=0; $i<count($defItems); $i+=ItemPieces())
    {
      if($defItems[$i+2] == 1) $defItems[$i+2] = 2;
    }
    for($i=count($mainCharacter) - CharacterPieces(); $i>=0; $i -= CharacterPieces())
    {
      CharacterStartTurnAbility($i);
    }
    ResetMainClassState();
    UpdateMainPlayerGameState();
  }

  function PlayCard($cardID, $from, $dynCostResolved=-1, $index=-1)
  {
    global $playerID, $myResources, $turn, $currentPlayer, $otherPlayer, $combatChain, $actionPoints, $myAuras, $myPitch, $CS_NumAddedToSoul;
    global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $myClassState, $CS_NumActionsPlayed, $CS_NumNonAttackCards, $CS_NextNAACardGoAgain;
    if($turn[0] != "P") MakeGamestateBackup();
    if($dynCostResolved == -1) WriteLog("Player " . $playerID . " " . PlayTerm($turn[0]) . " " . CardLink($cardID, $cardID));
    //If it's not pitch phase, pay the cost
        if($from == "EQUIP" || $from == "PLAY") $cardType = GetAbilityType($cardID);
        else $cardType = CardType($cardID);
        if($turn[0] != "P")
        {
            if($dynCostResolved >= 0)
            {
              $baseCost = ($from == "PLAY" || $from == "EQUIP" ? AbilityCost($cardID) : (CardCost($cardID) + SelfCostModifier($cardID)));
              if($turn[0] == "B" && $cardType != "I" && !CanPlayAsInstant($cardID)) $myResources[1] = $dynCostResolved;
              else $myResources[1] = ($dynCostResolved > 0 ? $dynCostResolved : $baseCost) + CurrentEffectCostModifiers($cardID) + AuraCostModifier();
              if($myResources[1] < 0) $myResources[1] = 0;
            }
            else
            {
              if($turn[0] == "B") $dynCost = BlockDynamicCost($cardID);
              else $dynCost = DynamicCost($cardID);
              if($dynCost != "") AddDecisionQueue("DYNPITCH", $currentPlayer, $dynCost);
              AddPrePitchDecisionQueue($cardID, $index);
              AddDecisionQueue("RESUMEPAYING", $currentPlayer, $cardID . "-" . $from);
              ProcessDecisionQueue();
              return;
            }
        }
        else if($turn[0] == "P")
        {
          $myResources[0] += PitchValue($cardID);
          if(SearchCharacterForCard($currentPlayer, "MON060") && CardTalent($cardID) == "LIGHT" && GetClassState($currentPlayer, $CS_NumAddedToSoul) > 0)
          { $myResources[0] += 1; }
          array_push($myPitch, $cardID);
          PitchAbility($cardID);
        }
        if($myResources[0] < $myResources[1])
        {
          if($turn[0] != "P")
          {
            $turn[2] = $turn[0];
            $turn[3] = $cardID;
            $turn[4] = $from;
          }
          $turn[0] = "P";
          return;//We know we need to pitch more, short circuit here
        }
        $myResources[0] -= $myResources[1];
        $resourcesPaid = $myResources[1];
        $myResources[1] = 0;
        if($turn[0] == "P")
        {
          $turn[0] = $turn[2];
          $cardID = $turn[3];
          $cardType = CardType($cardID);
          $from = $turn[4];
        }
        //We've paid resources, now pay action points if applicable
        if($turn[0] != "B" || $cardType == "I" || CanPlayAsInstant($cardID))
        {
          $abilityType = GetAbilityType($cardID);
          $goAgainPrevented = CurrentEffectPreventsGoAgain();
          if($from == "PLAY" || $from == "EQUIP")
          {
            $hasGoAgain = AbilityHasGoAgain($cardID);
            if(CanPlayAsInstant($cardID)) { if($hasGoAgain && !$goAgainPrevented) ++$actionPoints; }
            else if(($abilityType == "A" || $abilityType == "AA") && (!$hasGoAgain || $goAgainPrevented)) --$actionPoints;
            if($abilityType == "A") { ResetCombatChainState(); UnsetMyCombatChainBanish(); }
          }
          else
          {
            $hasGoAgain = HasGoAgain($cardID);
            if($myClassState[$CS_NextNAACardGoAgain] && $cardType == "A")
            {
              $hasGoAgain = true;
              $myClassState[$CS_NextNAACardGoAgain] = 0;
            }
            if(CanPlayAsInstant($cardID)) { if($hasGoAgain && !$goAgainPrevented) ++$actionPoints; }
            else if(($cardType == "A" || $cardType == "AA") && (!$hasGoAgain || $goAgainPrevented)) --$actionPoints;
            if($cardType == "A") { ResetCombatChainState(); UnsetMyCombatChainBanish(); }
            if(SearchCurrentTurnEffects("CRU123-DMG", $playerID) && ($cardType == "A" || $cardType == "AA")) LoseHealth(1, $playerID);
          }
          if($cardType == "A" || $abilityType == "A" || $cardType == "AA" || $abilityType == "AA")
          {
            if($cardType == "A") ++$myClassState[$CS_NumNonAttackCards];
            ++$myClassState[$CS_NumActionsPlayed];
          }
        }
        //Pay additional costs
        PayAdditionalCosts($cardID);
        if($cardType == "AA" || $cardType == "W") { AuraAttackAbilities($cardID); }
        AddDecisionQueue("RESUMEPLAY", $currentPlayer, "-");
        $turn[2] = $cardID;
        $turn[3] = $from;
        $turn[4] = $resourcesPaid;
        ProcessDecisionQueue();
  }

  function AddPrePitchDecisionQueue($cardID, $index=-1)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "WTR081":
        if(ComboActive($cardID))
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
          AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
          AddDecisionQueue("MULTIADDDECK", $currentPlayer, "-", 1);
          AddDecisionQueue("LORDOFWIND", $currentPlayer, "-", 1);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        }
        break;
      case "ARC185": case "ARC186": case "ARC187":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHAND");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-");
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "ARC185", 1);
        break;
      case "MON257": case "MON258": case "MON259":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHAND");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-");
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "MON257", 1);
        break;
      case "MON241": case "MON242": case "MON243": case "MON244":
        AddDecisionQueue("IRONHIDE", $currentPlayer, $index, 1);
        break;
      default:
        break;
    }
  }

  function PayAdditionalCosts($cardID)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "WTR159":
        BottomDeck();
        break;
      case "WTR179": case "WTR180": case "WTR181":
        $indices = SearchMyHand("", "", -1, 2);
        AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, $indices);
        AddDecisionQueue("REVEALMYCARD", $currentPlayer, "-");
        break;
      case "WTR182": case "WTR183": case "WTR184":
        $indices = SearchMyHand("", "", 1);
        AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, $indices);
        AddDecisionQueue("REVEALMYCARD", $currentPlayer, "-");
        break;
      case "MON029": case "MON030":
        BanishFromSoul($currentPlayer);
        break;
      case "MON035":
        AddDecisionQueue("VOFTHEVANGUARD", $currentPlayer, "-");
        break;
      case "MON042": case "MON043": case "MON044": case "MON045": case "MON046": case "MON047":
      case "MON048": case "MON049": case "MON050": case "MON051": case "MON052": case "MON053":
      case "MON054": case "MON055": case "MON056": Charge(); break;
      case "MON062":
        BanishFromSoul($currentPlayer);
        BanishFromSoul($currentPlayer);
        BanishFromSoul($currentPlayer);
        break;
      default:
        break;
    }
  }

  function PlayCardEffect($cardID, $from, $resourcesPaid)
  {
    global $turn, $combatChain, $currentPlayer, $myDiscard, $combatChainState, $CCS_AttackPlayedFrom, $myClassState, $CS_PlayIndex;
    global $CS_NextWizardNAAInstant, $CS_CharacterIndex;
    //Figure out where it goes
    if($turn[0] != "B" && $from == "EQUIP" || $from == "PLAY") $cardType = GetAbilityType($cardID);
    else $cardType = CardType($cardID);
    if($from != "PLAY")
    {
      if(GoesOnCombatChain($turn[0], $cardID, $from))
      {
        $index = count($combatChain);
        array_push($combatChain, $cardID);
        array_push($combatChain, $currentPlayer);
        array_push($combatChain, $from);
        array_push($combatChain, $resourcesPaid);
        array_push($combatChain, RepriseActive());
        array_push($combatChain, 0);//Attack modifier
        array_push($combatChain, ResourcesPaidBlockModifier($cardID, $resourcesPaid));//Defense modifier
        OnBlockEffects($index);
        if($index == 0) $combatChainState[$CCS_AttackPlayedFrom] = $from;
      }
      else
      {
        $cardSubtype = CardSubType($cardID);
        if($cardSubtype == "Aura")
        {
          PlayMyAura($cardID);
        }
        else if($cardSubtype == "Item")
        {
          PutItemIntoPlay($cardID);
        }
        else
        {
          $goesWhere = GoesWhereAfterResolving($cardID);
          switch($goesWhere)
          {
            case "GY": array_push($myDiscard, $cardID); break;
            case "SOUL": AddSoul($cardID, $currentPlayer, $from); break;
            default: break;
          }
        }
      }
    }
    //Resolve Effects
    if($turn[0] != "B" || $cardType == "I" || CanPlayAsInstant($cardID))
    {
      if($from != "PLAY")
      {
        ResetCardPlayed($cardID);
        CurrentEffectPlayAbility($cardID);
        if(HasBoost($cardID)) Boost();
      }
      $playText = PlayAbility($cardID, $from, $resourcesPaid);
      if($playText != "") WriteLog("Resolving play ability of " . $cardID . ": " . $playText);
    }

    if($CS_CharacterIndex != -1 && CanPlayAsInstant($cardID))
    {
      RemoveCharacterEffects($currentPlayer, GetClassState($currentPlayer, $CS_CharacterIndex), "INSTANT");
    }
    //Now determine what needs to happen next
    $myClassState[$CS_PlayIndex] = -1;
    $myClassState[$CS_CharacterIndex] = -1;
    ProcessDecisionQueue();
  }

  function CardLink($caption, $cardNumber)
  {
    //$file = "'./" . "PlayerCards" . "/" . ($cardNumber+1) . ".jpg'";//TODO: Replace "PlayerCards" with correct folder parameter
    //return "<div onclick=\"ShowDetail(this," . $file . ")\">" . $caption . "</div>";
    return $cardNumber;
  }

?>

