<?php

  error_reporting(E_ALL);

  include "WriteLog.php";
  include "WriteReplay.php";
  include "GameLogic.php";
  include "GameTerms.php";
  include "HostFiles/Redirector.php";
  include "Libraries/SHMOPLibraries.php";
  include "Libraries/StatFunctions.php";
  include "Libraries/UILibraries.php";
  include "Libraries/PlayerSettings.php";
  include "AI/CombatDummy.php";
  include "Libraries/HTTPLibraries.php";

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
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
  $mainPlayerGamestateStillBuilt = 0;
  $makeCheckpoint = 0;
  $makeBlockBackup = 0;

  if($playerID == 3) ExitProcessInput();
  if(!IsModeAsync($mode) && $currentPlayer != $playerID) ExitProcessInput();

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
      $found = -1;
      if($index != "")
      {
        $cardID = $myCharacter[$index];
        $found = HasCard($cardID);
      }
      if($index != -1 && IsPlayable($myCharacter[$found], $turn[0], "CHAR", $index))
      {
        $myClassState[$CS_CharacterIndex] = $index;
        $myClassState[$CS_PlayIndex] = $index;
        if($turn[0] == "B")
        {
          if(HasBladeBreak($cardID))
          {
            $myCharacter[$index+1] = 0;//Destroy if blocked and it had blade break;
            CharacterDestroyEffect($cardID, $currentPlayer);
          }
          else if($cardID == "MON187")
          {
            $myCharacter[$index+1] = 0;
            BanishCardForPlayer($cardID, $currentPlayer, "EQUIP", "NA");
          }
          else $myCharacter[$index+6] = 1;//Else just put it on the combat chain
        }
        else
        {
          EquipPayAdditionalCosts($index, "EQUIP");
        }
        PlayCard($cardID, "EQUIP", -1, $index);
      }
      break;
    case 4: //Add something to your arsenal
      $found = HasCard($cardID);
      if($turn[0] == "ARS" && $found >= 0) {
        unset($myHand[$found]);
        $myHand = array_values($myHand);
        AddArsenal($cardID, $currentPlayer, "HAND", "DOWN");
        PassTurn();
      }
      break;
    case 5:
      $index = $cardID;
      if($index < count($myArsenal))
      {
        $cardToPlay = $myArsenal[$index];
        for($i=$index+ArsenalPieces()-1; $i>=$index; --$i)
        {
          unset($myArsenal[$i]);
        }
        $myArsenal = array_values($myArsenal);
        WriteLog("Card played from arsenal.");
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
    case 8: case 9://OPT, CHOOSETOP, CHOOSEBOTTOM
      if($turn[0] == "OPT" || $turn[0] == "CHOOSETOP" || $turn[0] == "CHOOSEBOTTOM")
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
        if(count($options) > 0) PrependDecisionQueue($turn[0], $currentPlayer, implode(",", $options));
        ContinueDecisionQueue($buttonInput);
      }
      break;
    case 10://Item ability
      $index = $cardID;//Overridden to be index instead
      if($index >= count($myItems)) break;//Item doesn't exist
      $cardID = $myItems[$index];
      if(!IsPlayable($cardID, $turn[0], "PLAY", $index)) break;//Item not playable
      --$myItems[$index+3];
      $myClassState[$CS_PlayIndex] = $index;
      $set = CardSet($cardID);
      PlayCard($cardID, "PLAY", -1);
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
      if($myBanish[$index+1] == "MON212" && CardTalent($theirCharacter[0]) == "LIGHT") AddCurrentTurnEffect("MON212", $currentPlayer);
      SetClassState($currentPlayer, $CS_PlayIndex, $index);
      PlayCard($cardID, "BANISH", -1, $index);
      break;
    case 15: case 16: case 18: //CHOOSE (15 and 18 deprecated)
      $index = $cardID;
      ContinueDecisionQueue($index);
      break;
    case 17://BUTTONINPUT
      ContinueDecisionQueue($buttonInput);
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
    case 22://Aura ability
      $index = $cardID;//Overridden to be index instead
      if($index >= count($myAuras)) break;//Item doesn't exist
      $cardID = $myAuras[$index];
      if(!IsPlayable($cardID, $turn[0], "PLAY", $index)) break;//Aura ability not playable
      $myAuras[$index+1] = 1;//Set status to used - for now
      $myClassState[$CS_PlayIndex] = $index;
      PlayCard($cardID, "PLAY", -1);
      break;
    case 23://CHOOSECARD
      if($turn[0] == "CHOOSECARD")
      {
        $options = explode(",", $turn[2]);
        $found = -1;
        for($i=0; $i<count($options); ++$i)
        {
          if($options[$i] == $buttonInput) { $found = $i; break; }
        }
        if($found == -1) break;//Invalid input
        unset($options[$found]);
        $options = array_values($options);
        ContinueDecisionQueue($buttonInput);
      }
      break;
    case 24: //Ally Ability
      $allies = &GetAllies($currentPlayer);
      $index = $cardID;//Overridden to be index instead
      if($index >= count($allies)) break;//Ally doesn't exist
      $cardID = $allies[$index];
      if(!IsPlayable($cardID, $turn[0], "PLAY", $index)) break;//Ally not playable
      $allies[$index+1] = 1;
      $myClassState[$CS_PlayIndex] = $index;
      PlayCard($cardID, "PLAY", -1);
      break;
    case 25: //Landmark Ability
      $index = $cardID;
      if($index >= count($landmarks)) break;//Landmark doesn't exist
      $cardID = $landmarks[$index];
      if(!IsPlayable($cardID, $turn[0], "PLAY", $index)) break;//Landmark not playable
      $myClassState[$CS_PlayIndex] = $index;
      PlayCard($cardID, "PLAY", -1);
      break;
    case 26: //Change setting
      $params = explode("-", $buttonInput);
      ChangeSetting($playerID, $params[0], $params[1]);
      break;
    case 99: //Pass
      PassInput();
      break;
    case 10000:
      RevertGamestate();
      $skipWriteGamestate = true;
      WriteLog("Player " . $playerID . " undid their last action.");
      break;
    case 10001:
      RevertGamestate("preBlockBackup.txt");
      $skipWriteGamestate = true;
      WriteLog("Player " . $playerID . " undid their blocks.");
      break;
    case 10002:
      WriteLog("Player " . $playerID . " manually added one action point.");
      ++$actionPoints;
      break;
    case 100000: //Rematch
      header("Location: " . $redirectPath . "/Start.php?gameName=$gameName&playerID=" . $playerID);
      exit;
    case 100001: //Main Menu
      header("Location: " . $redirectPath . "/MainMenu.php");
      exit;
    case 100002: //Concede
      DealDamage($playerID, 9999, "MANUAL");
    default:break;
  }

  CacheCombatResult();

  if($winner != 0) { $turn[0] = "OVER"; $currentPlayer = 1; }
  CombatDummyAI();//Only does anything if applicable

  //Now write out the game state
  if(!$skipWriteGamestate)
  {
    //if($mainPlayerGamestateStillBuilt) UpdateMainPlayerGamestate();
    //else UpdateGameState(1);
    DoGamestateUpdate();
    include "WriteGamestate.php";
  }

  if($makeCheckpoint) MakeGamestateBackup();
  if($makeBlockBackup) MakeGamestateBackup("preBlockBackup.txt");

  WriteCache($gameName, $playerID);

  ExitProcessInput();

  function IsModeAsync($mode)
  {
    switch($mode)
    {
      case 26: return true;
      case 10000: return true;
      case 100001: return true;
    }
    return false;
  }

  function ExitProcessInput()
  {
    global $playerID, $redirectPath, $gameName;
    if(UseNewUI($playerID))
    {
      header("Location: " . $redirectPath . "/NextTurn2.php?gameName=$gameName&playerID=" . $playerID);
    }
    else
    {
      header("Location: " . $redirectPath . "/NextTurn.php?gameName=$gameName&playerID=" . $playerID);
    }
    exit;
  }

  function PitchHasCard($cardID)
  {
    global $currentPlayer;
    return SearchPitchForCard($currentPlayer, $cardID);
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

  function PassInput()
  {
    global $turn, $currentPlayer;
    if($turn[0] == "MAYCHOOSEMULTIZONE" || $turn[0] == "MAYCHOOSEHAND" || $turn[0] == "MAYCHOOSEDISCARD" || $turn[0] == "INSTANT")
    {
      ContinueDecisionQueue("PASS");
    }
    else
    {
      if(Pass($turn, $currentPlayer, $currentPlayer))
      {
        BeginTurnPass();
      }
      WriteLog("Player " . $currentPlayer . " passed.");
    }
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
    global $mainCharacter, $defCharacter, $mainDiscard, $defDiscard, $defAuras, $mainAuras;
    global $combatChainState, $actionPoints, $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow;
    global $mainClassState, $defClassState, $CS_AtksWWeapon, $CS_DamagePrevention, $CCS_HitsWithWeapon, $CCS_ChainAttackBuff;
    global $CCS_LinkTotalAttack, $CCS_LinkBaseAttack, $CS_HitsWithWeapon, $CCS_WeaponIndex, $CS_EffectContext, $chainLinkSummary;
    UpdateGameState($currentPlayer);
    BuildMainPlayerGameState();

    $totalAttack = 0;
    $totalDefense = 0;
    EvaluateCombatChain($totalAttack, $totalDefense);
    for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
    {
      CombatChainResolutionEffects($combatChain[$i-1], $combatChain[$i]);
    }

    $combatChainState[$CCS_LinkTotalAttack] = $totalAttack;

    LogCombatResolutionStats($totalAttack, $totalDefense);

    $damage = $totalAttack - $totalDefense;
    $damageDone = DealDamage($defPlayer, $damage, "COMBAT", $combatChain[0]);//Include prevention
    $wasHit = $damageDone > 0;
    WriteLog("Combat resolved with " . ($wasHit ? "a HIT for $damageDone damage." : "NO hit."));
    array_push($chainLinkSummary, $damageDone);
    array_push($chainLinkSummary, $totalAttack);

    if($wasHit)//Resolve hit effects
    {
      $combatChainState[$CCS_DamageDealt] = $damage;
      ++$combatChainState[$CCS_NumHits];
      ++$combatChainState[$CCS_HitsInRow];
      if(CardType($combatChain[0]) == "W")
      {
        ++$combatChainState[$CCS_HitsWithWeapon];
        IncrementClassState($mainPlayer, $CS_HitsWithWeapon);
      }
      for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
      {
        if($combatChain[$i] == $mainPlayer)
        {
          SetClassState($mainPlayer, $CS_EffectContext, $combatChain[$i-1]);
          ProcessHitEffect($combatChain[$i-1]);
          if($damage >= 4) ProcessCrushEffect($combatChain[$i-1]);
          AddDecisionQueue("CLEAREFFECTCONTEXT", $mainPlayer, "-");
        }
      }
      for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i>=0; $i-=CurrentTurnPieces())
      {
        if(IsCombatEffectActive($currentTurnEffects[$i]))
        {
          if($currentTurnEffects[$i+1] == $mainPlayer)
          {
            $shouldRemove = EffectHitEffect($currentTurnEffects[$i]);
            if($shouldRemove == 1)
            {
              for($j = $i+CurrentTurnPieces()-1; $j >= $i; --$j) unset($currentTurnEffects[$j]);
            }
          }
        }
      }
      $currentTurnEffects = array_values($currentTurnEffects);//In case any were removed
      MainCharacterHitAbilities();
      MainCharacterHitEffects();
      ArsenalHitEffects();
      AuraHitEffects($combatChain[0]);
      AttackDamageAbilities();
    }
    else
    {
      for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
      {
        if($combatChain[$i] == $mainPlayer)
        {
          SetClassState($mainPlayer, $CS_EffectContext, $combatChain[$i-1]);
          ProcessMissEffect($combatChain[$i-1]);
          AddDecisionQueue("CLEAREFFECTCONTEXT", $mainPlayer, "-");
        }
      }
      $combatChainState[$CCS_HitsInRow] = 0;
    }
    AddDecisionQueue("FINALIZECHAINLINK", $mainPlayer, "-");
    $turn[0] = "M";
    $currentPlayer = $mainPlayer;
    ProcessDecisionQueue();//Any combat related decision queue logic should be main player gamestate
}

function FinalizeChainLink($chainClosed=false)
{
    global $turn, $actionPoints, $combatChain, $mainPlayer, $playerID, $defHealth, $currentTurnEffects, $defCharacter, $mainDiscard, $defDiscard, $currentPlayer, $defPlayer;
    global $combatChainState, $actionPoints, $CCS_LastAttack, $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow;
    global $mainClassState, $defClassState, $CS_AtksWWeapon, $CS_DamagePrevention, $CCS_HitsWithWeapon, $CCS_GoesWhereAfterLinkResolves;
    global $CS_LastAttack, $CCS_LinkTotalAttack, $CCS_AttackTarget, $CS_NumSwordAttacks, $chainLinks;
    UpdateGameState($currentPlayer);
    BuildMainPlayerGameState();

    if(DoesAttackHaveGoAgain() && !$chainClosed) { ++$actionPoints; }

    //Clean up combat effects that were used and are one-time
    for($i = count($currentTurnEffects) - 2; $i >= 0; --$i)
    {
      if(IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectPersistent($currentTurnEffects[$i]))
      {
        RemoveCurrentTurnEffect($i);
      }
    }

    $nervesOfSteelActive = $combatChainState[$CCS_LinkTotalAttack] <= 2 && SearchAuras("EVR023", $defPlayer);
    for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
    {
      if($combatChain[$i] != $mainPlayer)
      {
        if(CardType($combatChain[$i-1]) == "E")
        {
          $index = FindDefCharacter($combatChain[$i-1]);
          if(!$nervesOfSteelActive)
          {
            if(HasBattleworn($combatChain[$i-1])) --$defCharacter[$index+4];
            if(HasTemper($combatChain[$i-1])) --$defCharacter[$index+4];
          }
          if($combatChain[$i-1] == "MON089" && $combatChainState[$CCS_LinkTotalAttack] >= 6) DestroyCharacter($defPlayer, $index);
        }
      }
    }

    if(CardType($combatChain[0]) == "W")
    {
      ++$mainClassState[$CS_AtksWWeapon];
      if(CardSubtype($combatChain[0]) == "Sword") ++$mainClassState[$CS_NumSwordAttacks];
    }
    $combatChainState[$CCS_LastAttack] = $combatChain[0];
    SetClassState($mainPlayer, $CS_LastAttack, $combatChain[0]);
    array_push($chainLinks, array());
    $CLIndex = count($chainLinks) - 1;
    for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
    {
      $cardType = CardType($combatChain[$i-1]);
      if($cardType == "W" || $cardType == "E" || $cardType == "C") continue;

      $goesWhere = GoesWhereAfterResolving($combatChain[$i-1], "COMBATCHAIN");
      if($i == 1 && $combatChainState[$CCS_GoesWhereAfterLinkResolves] != "GY") { $goesWhere = $combatChainState[$CCS_GoesWhereAfterLinkResolves]; }
      switch($goesWhere) {
        case "BOTDECK": AddBottomMainDeck($combatChain[$i-1], "CC"); break;
        case "HAND": AddMainHand($combatChain[$i-1], "CC"); break;
        case "SOUL": AddSoul($combatChain[$i-1], $combatChain[$i], "CC"); break;
        case "GY": /*AddGraveyard($combatChain[$i-1], $combatChain[$i], "CC");*/ break;//Things that would go to the GY stay on till the end of the chain
        case "BANISH": BanishCardForPlayer($combatChain[$i-1], $mainPlayer, "CC", "NA"); break;
        default: break;
      }
      array_push($chainLinks[$CLIndex], $combatChain[$i-1]);//Card ID
      array_push($chainLinks[$CLIndex], $combatChain[$i]);//Player ID
      array_push($chainLinks[$CLIndex], ($goesWhere == "GY" ? "1" : "0"));//Still on chain? 1 = yes, 0 = no
    }
    CopyCurrentTurnEffectsFromCombat();
    CheckDestroyTemper();
    UnsetChainLinkBanish();//For things that are banished and playable only to this chain link
    $combatChain = [];
    if($chainClosed)
    {
      ResetCombatChainState();
      $turn[0] = "M";
      FinalizeAction();
    }
    else
    {
      ResetChainLinkState();
    }
  }

  function BeginTurnPass()
  {
    global $mainPlayer, $defPlayer, $decisionQueue;
    WriteLog("Main player has passed on the turn. Beginning end of turn step.");
    Heave();
    if(ShouldHoldPriority($defPlayer) || count($decisionQueue) > 0)
    {
      AddLayer("ENDTURN", $mainPlayer, "-");
      ProcessDecisionQueue("");
    }
    else
    {
      FinishTurnPass();
    }
  }

  function FinishTurnPass()
  {
    ItemEndTurnAbilities();
    AuraBeginEndStepAbilities();
    LandmarkBeginEndStepAbilities();
    BeginEndStepEffects();
    PassTurn();
  }

  function PassTurn()
  {
    global $playerID, $currentPlayer, $turn, $mainPlayer, $mainPlayerGamestateStillBuilt;
    if(!$mainPlayerGamestateStillBuilt)
    {
      UpdateGameState($currentPlayer);
      BuildMainPlayerGameState();
    }
    ResetCombatChainState();
    $MyPitch = GetPitch($playerID);
    $TheirPitch = GetPitch(($playerID == 1 ? 2 : 1));
    $MainHand = GetHand($mainPlayer);
    if(count($MyPitch) > 0)
    {
      $currentPlayer = $playerID;
      $turn[0] = "PDECK";
    }
    else if(count($TheirPitch) > 0)
    {
      $currentPlayer = $playerID == 1 ? 2 : 1;
      $turn[0] = "PDECK";
    }
    else if(count($MainHand) > 0 && !ArsenalFull($mainPlayer) && $turn[0] != "ARS")//Arsenal
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
    global $currentPlayer, $currentTurn, $playerID, $turn, $combatChain, $actionPoints, $mainPlayer, $defPlayer, $currentTurnEffects, $nextTurnEffects;
    global $mainHand, $defHand, $mainDeck, $mainItems, $defItems, $defDeck, $mainCharacter, $defCharacter, $mainResources, $defResources;
    global $mainAuras, $defBanish, $firstPlayer, $lastPlayed;
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
    EndTurnBloodDebt();//This has to be before resetting character, because of sleep dart effects

    LogEndTurnStats($mainPlayer);

    //Draw Cards
    if($mainPlayer == $firstPlayer && $currentTurn == 1) //Defender draws up on turn 1
    {
      $toDraw = CharacterIntellect($defCharacter[0]) - count($defHand);
      for($i=0; $i < $toDraw; ++$i)
      {
        Draw($defPlayer, false);
      }
    }
    $toDraw = CharacterIntellect($mainCharacter[0]) - count($mainHand) + CurrentEffectIntellectModifier();
    for($i=0; $i < $toDraw; ++$i)
    {
      Draw($mainPlayer, false);
    }
    if($toDraw > 0) WriteLog("Main player drew " . $toDraw . " cards and now has " . count($mainHand) . " cards.");

    //Reset characters/equipment
    for($i=1; $i<count($mainCharacter); $i+=CharacterPieces())
    {
      if($mainCharacter[$i-1] == "CRU177" && $mainCharacter[$i+1] >= 3) $mainCharacter[$i] = 0;//Destroy Talishar if >= 3 rust counters
      if($mainCharacter[$i+6] == 1) $mainCharacter[$i] = 0;//Destroy if it was flagged for destruction
      if($mainCharacter[$i] != 0) { $mainCharacter[$i] = 2; $mainCharacter[$i + 4] = CharacterNumUsesPerTurn($mainCharacter[$i-1]); }
    }
    for($i=1; $i<count($defCharacter); $i+=CharacterPieces())
    {
      if($defCharacter[$i] == 1 || $defCharacter[$i] == 2) { $defCharacter[$i] = 2; $defCharacter[$i + 4] = CharacterNumUsesPerTurn($defCharacter[$i-1]); }
    }
    $mainAllies = &GetAllies($mainPlayer);
    for($i=0; $i<count($mainAllies); $i+=AllyPieces())
    {
      if($mainAllies[$i+1] == 1) { $mainAllies[$i+1] = 2; $mainAllies[$i+2] = AllyHealth($mainAllies[$i]); }
    }

    //Reset Auras
    for($i=0; $i<count($mainAuras); $i+=AuraPieces())
    {
      $mainAuras[$i+1] = 2;//If it were destroyed, it wouldn't be in the auras array
    }

    $mainResources[0] = 0;
    $mainResources[1] = 0;
    $defResources[0] = 0;
    $defResources[1] = 0;
    $lastPlayed = [];

    ArsenalEndTurn($mainPlayer);
    ArsenalEndTurn($defPlayer);
    CurrentEffectEndTurnAbilities();
    AuraEndTurnAbilities();
    MainCharacterEndTurnAbilities();
    ResetMainClassState();
    ResetCharacterEffects();
    UnsetTurnBanish();
    AuraEndTurnCleanup();

    DoGamestateUpdate();

    //Update all the player neutral stuff
    if($mainPlayer == 2) { $currentTurn += 1; }
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
    $defPlayer = $mainPlayer;
    $mainPlayer = ($mainPlayer == 1 ? 2 : 1);
    $currentPlayer = $mainPlayer;

    BuildMainPlayerGameState();

    //Start of turn effects
    if($mainPlayer == 1) StatsStartTurn();
    StartTurnAbilities();

    ResetMainClassState();
    DoGamestateUpdate();
    ProcessDecisionQueue();
  }

  function PlayCard($cardID, $from, $dynCostResolved=-1, $index=-1)
  {
    global $playerID, $turn, $currentPlayer, $combatChain, $actionPoints, $CS_NumAddedToSoul;
    global $combatChainState, $CS_NumActionsPlayed, $CS_NumNonAttackCards, $CS_NextNAACardGoAgain, $CS_NumPlayedFromBanish, $CS_DynCostResolved;
    global $CS_NumAttackCards, $CS_NumBloodDebtPlayed, $layerPriority, $CS_NumWizardNonAttack, $CS_LayerTarget, $lastPlayed, $CS_PlayIndex;
    $resources = &GetResources($currentPlayer);
    $pitch = &GetPitch($currentPlayer);
    $dynCostResolved = intval($dynCostResolved);
    if($turn[0] != "P") MakeGamestateBackup();
    $layerPriority[0] = ShouldHoldPriority(1);
    $layerPriority[1] = ShouldHoldPriority(2);
    if($dynCostResolved == -1)
    {
      WriteLog("Player " . $playerID . " " . PlayTerm($turn[0]) . " " . CardLink($cardID, $cardID), $turn[0] != "P" ? $currentPlayer : 0);
      LogPlayCardStats($currentPlayer, $cardID, $from);
      if($turn[0] != "P" && $turn[0] != "B") { $lastPlayed = []; $lastPlayed[0] = $cardID; $lastPlayed[1] = $currentPlayer; }
    }
    //If it's not pitch phase, pay the cost
        //if($from == "EQUIP" || $from == "PLAY") $cardType = GetAbilityType($cardID);
        //else $cardType = CardType($cardID);
        if($turn[0] != "P")
        {
            if($dynCostResolved >= 0)
            {
              SetClassState($currentPlayer, $CS_DynCostResolved, $dynCostResolved);
              $baseCost = ($from == "PLAY" || $from == "EQUIP" ? AbilityCost($cardID) : (CardCost($cardID) + SelfCostModifier($cardID)));
              if($turn[0] == "B" && CardType($cardID) != "I") $resources[1] = $dynCostResolved;
              else $resources[1] = ($dynCostResolved > 0 ? $dynCostResolved : $baseCost) + CurrentEffectCostModifiers($cardID, $from) + AuraCostModifier() + CharacterCostModifier($cardID, $from) + BanishCostModifier($from, $index);
              if($resources[1] < 0) $resources[1] = 0;
              LogResourcesUsedStats($currentPlayer, $resources[1]);
            }
            else
            {
              if($turn[0] == "B") $dynCost = BlockDynamicCost($cardID);
              else $dynCost = DynamicCost($cardID);
              //GetLayerTarget($cardID);
              if($turn[0] != "B") AddPrePitchDecisionQueue($cardID, $index);
              if($dynCost != "") AddDecisionQueue("DYNPITCH", $currentPlayer, $dynCost);
              AddPostPitchDecisionQueue($cardID, $from, $index);
              if($dynCost == "") AddDecisionQueue("PASSPARAMETER", $currentPlayer, 0);
              AddDecisionQueue("RESUMEPAYING", $currentPlayer, $cardID . "-" . $from . "-" . $index);
              ProcessDecisionQueue();
              return;
            }
        }
        else if($turn[0] == "P")
        {
          $pitchValue = PitchValue($cardID);
          if($pitchValue == 1)
          {
            $talismanOfRecompenseIndex = GetItemIndex("EVR191", $currentPlayer);
            if($talismanOfRecompenseIndex > -1)
            {
              WriteLog("Talisman of Recompense gained 3 instead of 1 and destroyed itself.");
              DestroyItemForPlayer($currentPlayer, $talismanOfRecompenseIndex);
              $pitchValue = 3;
            }
          }
          $resources[0] += $pitchValue;
          if(SearchCharacterActive($currentPlayer, "MON060") && CardTalent($cardID) == "LIGHT" && GetClassState($currentPlayer, $CS_NumAddedToSoul) > 0)
          { $resources[0] += 1; }
          array_push($pitch, $cardID);
          AddThisCardPitch($currentPlayer, $cardID);
          PitchAbility($cardID);
        }
        if($resources[0] < $resources[1])
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
        $resources[0] -= $resources[1];
        $resourcesPaid = $resources[1];
        $resources[1] = 0;
        if($turn[0] == "P")
        {
          $turn[0] = $turn[2];
          $cardID = $turn[3];
          $from = $turn[4];
        }
        $cardType = CardType($cardID);
        //We've paid resources, now pay action points if applicable
        if($turn[0] != "B")// || $cardType == "I" || CanPlayAsInstant($cardID))
        {
          $abilityType = GetAbilityType($cardID);
          $goAgainPrevented = CurrentEffectPreventsGoAgain();
          //if($from == "PLAY" || $from == "EQUIP")
          if(IsStaticType($cardType, $from, $cardID))
          {
            $canPlayAsInstant = CanPlayAsInstant($cardID, $index, $from);
            $hasGoAgain = AbilityHasGoAgain($cardID);
            if($canPlayAsInstant) { if($hasGoAgain && !$goAgainPrevented) ++$actionPoints; }
            else if(($abilityType == "A") && (!$hasGoAgain || $goAgainPrevented)) --$actionPoints;
            else if($abilityType == "AA") --$actionPoints;//Always resolve this after combat chain
            if($abilityType == "A" && !$canPlayAsInstant) { ResetCombatChainState(); UnsetMyCombatChainBanish(); RemoveEffectsOnChainClose(); }
            PayAbilityAdditionalCosts($cardID);
            ActivateAbilityEffects();
          }
          else
          {
            $canPlayAsInstant = CanPlayAsInstant($cardID, $index, $from);
            $hasGoAgain = HasGoAgain($cardID);
            if(GetClassState($currentPlayer, $CS_NextNAACardGoAgain) && $cardType == "A")
            {
              $hasGoAgain = true;
              SetClassState($currentPlayer, $CS_NextNAACardGoAgain, 0);
            }
            if($cardType == "A") $hasGoAgain = CurrentEffectGrantsNonAttackActionGoAgain($cardID) || $hasGoAgain;
            if($canPlayAsInstant) { if($hasGoAgain && !$goAgainPrevented) ++$actionPoints; }
            else if(($cardType == "A") && (!$hasGoAgain || $goAgainPrevented)) --$actionPoints;
            else if($cardType == "AA") --$actionPoints;//Always resolve this after combat chain
            if($cardType == "A" && !$canPlayAsInstant) { ResetCombatChainState(); UnsetMyCombatChainBanish(); RemoveEffectsOnChainClose(); }
            if(SearchCurrentTurnEffects("CRU123-DMG", $playerID) && ($cardType == "A" || $cardType == "AA")) LoseHealth(1, $playerID);
            CombatChainPlayAbility($cardID);
          }
          if($cardType == "A" || $abilityType == "A" || $cardType == "AA" || $abilityType == "AA")
          {
            if($cardType == "A")
            {
              IncrementClassState($currentPlayer, $CS_NumNonAttackCards);
              if(CardClass($cardID) == "WIZARD") { IncrementClassState($currentPlayer, $CS_NumWizardNonAttack); }
            }
            IncrementClassState($currentPlayer, $CS_NumActionsPlayed);
          }
          if($from == "BANISH") IncrementClassState($currentPlayer, $CS_NumPlayedFromBanish);
          if(HasBloodDebt($cardID)) IncrementClassState($currentPlayer, $CS_NumBloodDebtPlayed);
          PayAdditionalCosts($cardID, $from);
        }
        if($cardType == "AA") IncrementClassState($currentPlayer, $CS_NumAttackCards);//Played or blocked

        if($from == "BANISH")
        {
          $index = GetClassState($currentPlayer, $CS_PlayIndex);
          $banish = &GetBanish($currentPlayer);
          for($i=$index+BanishPieces()-1; $i>=$index; --$i)
          {
            unset($banish[$i]);
          }
          $banish = array_values($banish);
        }

        //Pay additional costs
        if($turn[0] == "M" && ($cardType == "AA" || $abilityType == "AA")) GetTargetOfAttack();
        if($turn[0] == "B")//If a layer is not created
        {
          AddDecisionQueue("RESUMEPLAY", $currentPlayer, $cardID . "-" . $from . "-" . $resourcesPaid);
        }
        else
        {
          AddLayer($cardID, $currentPlayer, $from . "-" . $resourcesPaid, GetClassState($currentPlayer, $CS_LayerTarget));
        }
        ProcessDecisionQueue();
  }

  function GetLayerTarget($cardID)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "CRU164":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "LAYER,TYPE-I-MAXCOST-1");
        AddDecisionQueue("MULTIZONEFORMAT", $currentPlayer, "LAYER", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETLAYERTARGET", $currentPlayer, "-", 1);
        break;
      case "MON084": case "MON085": case "MON086":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "CCAA");
        AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETLAYERTARGET", $currentPlayer, "-", 1);
        break;
      default: break;
    }
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
        HandToTopDeck($currentPlayer);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "ARC185", 1);
        break;
      case "CRU188":
        AddDecisionQueue("COUNTITEM", $currentPlayer, "CRU197");
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, "4");
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_pay_4_copper", 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "CRU197-4", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "CRU188", 1);
        AddDecisionQueue("COUNTITEM", $currentPlayer, "EVR195");//TODO: Gold
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, "2");
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_pay_2_silver", 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "EVR195-2", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "CRU188", 1);
        break;
      case "MON199":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MULTIHAND");
        AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
        AddDecisionQueue("SOULREAPING", $currentPlayer, "-", 1);
        break;
      case "MON257": case "MON258": case "MON259":
        HandToTopDeck($currentPlayer);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "MON257", 1);
        break;
      case "EVR161": case "EVR162": case "EVR163":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "LIFEOFPARTY");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIZONEDESTROY", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "EVR161", 1);
        break;
      default:
        break;
    }
    $auras = &GetAuras($currentPlayer);
    for($i=0; $i<count($auras); $i+=AuraPieces())
    {
      switch($auras[$i])
      {
        case "ELE175":
          $type = CardType($cardID);
          if($type == "A" || $type == "AA")
          {
            AddDecisionQueue("YESNO", $currentPlayer, "Do_you_want_to_pay_1_to_give_this_action_go_again", 0, 1);
            AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
            AddDecisionQueue("PASSPARAMETER", $currentPlayer, 1, 1);
            AddDecisionQueue("PAYRESOURCES", $currentPlayer, "<-", 1);
            AddDecisionQueue("PREPITCHGIVEGOAGAIN", $currentPlayer, $type, 1);
          }
          break;
        default: break;
      }
    }
  }

  function AddPostPitchDecisionQueue($cardID, $from, $index=-1)
  {
    global $currentPlayer;
    if(RequiresDieRoll($cardID, $from)) RollDie($currentPlayer);
    switch($cardID)
    {
      case "MON089":
        AddDecisionQueue("PHANTASMALFOOTSTEPS", $currentPlayer, $index, 1);
        break;
      case "MON241": case "MON242": case "MON243": case "MON244":
        AddDecisionQueue("IRONHIDE", $currentPlayer, $index, 1);
        break;
      case "ELE203":
        AddDecisionQueue("RAMPARTOFTHERAMSHEAD", $currentPlayer, $index, 1);
        break;
      default:
        break;
    }
  }

  function GetTargetOfAttack()
  {
    global $mainPlayer, $combatChainState, $CCS_AttackTarget;
    $defPlayer = $mainPlayer == 1 ? 2 : 1;
    $numTargets = 1;
    $targets = "THEIRCHAR-0";//Their hero
    $auras = &GetAuras($defPlayer);
    $arcLightIndex = -1;
    for($i=0; $i<count($auras); $i+=AuraPieces())
    {
      if(HasSpectra($auras[$i]))
      {
        $targets .= ",THEIRAURAS-" . $i;
        ++$numTargets;
        if($auras[$i] == "MON005") $arcLightIndex = $i;
      }
    }
    $allies = &GetAllies($defPlayer);
    for($i=0; $i<count($allies); $i+=AllyPieces())
    {
      $targets .= ",THEIRALLY-" . $i;
      ++$numTargets;
    }
    if($arcLightIndex > -1) $targets = "THEIRAURAS-" . $arcLightIndex;
    if($numTargets > 1)
    {
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, $targets);
      AddDecisionQueue("PROCESSATTACKTARGET", $mainPlayer, "-");
    }
    else
    {
      $combatChainState[$CCS_AttackTarget] = "THEIRCHAR-0";
    }
  }

  function PayAbilityAdditionalCosts($cardID)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "MON000":
        for($i=0; $i<2; ++$i)
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "HANDPITCH,2");
          AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
          AddDecisionQueue("ADDDISCARD", $currentPlayer, "HAND", 1);
        }
        break;
      default: break;
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
        $indices = SearchHand($currentPlayer, "", "", -1, 2);
        AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, $indices);
        AddDecisionQueue("REVEALMYCARD", $currentPlayer, "-");
        break;
      case "WTR182": case "WTR183": case "WTR184":
        $indices = SearchHand($currentPlayer, "", "", 1);
        AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, $indices);
        AddDecisionQueue("REVEALMYCARD", $currentPlayer, "-");
        break;
      case "MON001": case "MON002":
        BanishFromSoul($currentPlayer);
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
      case "MON156":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MON156");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
        AddDecisionQueue("GIVEATTACKGOAGAIN", $currentPlayer, "-", 1);
        break;
      case "MON195": case "MON196": case "MON197":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
        AddDecisionQueue("ALLCARDTALENTORPASS", $currentPlayer, "SHADOW", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
        break;
      case "MON198":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "GY");
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "6-", 1);
        AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "<-", 1, 1);
        AddDecisionQueue("VALIDATECOUNT", $currentPlayer, "6", 1);
        AddDecisionQueue("SOULHARVEST", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "GY,-", 1);
        break;
      case "MON247":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MULTIHANDAA");
        AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REVEALHANDCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("ROUSETHEANCIENTS", $currentPlayer, "-", 1);
        break;
      case "ELE118":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "ARSENAL");
        AddDecisionQueue("CHOOSEARSENAL", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEARSENAL", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
        break;
      case "ELE234":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
        AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
        break;
      default:
        break;
    }
  }

  function PlayCardEffect($cardID, $from, $resourcesPaid, $target="-")
  {
    global $turn, $combatChain, $currentPlayer, $combatChainState, $CCS_AttackPlayedFrom, $CS_PlayIndex;
    global $CS_CharacterIndex, $CS_NumNonAttackCards, $CS_PlayCCIndex, $CS_NumAttacks, $CCS_NumChainLinks, $CCS_LinkBaseAttack;
    global $currentTurnEffectsFromCombat, $CCS_WeaponIndex, $CS_EffectContext;
    $character = &GetPlayerCharacter($currentPlayer);
    $definedCardType = CardType($cardID);
    //Figure out where it goes
    $openedChain = false;
    $isBlock = $turn[0] == "B";//This can change over the course of the function; for example if a phantasm gets popped
    if($turn[0] != "B" && $from == "EQUIP" || $from == "PLAY") $cardType = GetAbilityType($cardID);
    else $cardType = $definedCardType;
    if(GoesOnCombatChain($turn[0], $cardID, $from))
    {
      $index = AddCombatChain($cardID, $currentPlayer, $from, $resourcesPaid);
      if($index == 0)
      {
        $currentTurnEffectsFromCombat = [];
        $combatChainState[$CCS_AttackPlayedFrom] = $from;
        AuraAttackAbilities($cardID);
        ArsenalAttackAbilities();
        OnAttackEffects($cardID);
        ProcessAttackTarget();
        ++$combatChainState[$CCS_NumChainLinks];
        IncrementClassState($currentPlayer, $CS_NumAttacks);
        $attackValue = AttackValue($cardID);
        $combatChainState[$CCS_LinkBaseAttack] = $attackValue;
        if($definedCardType == "AA" && SearchCharacterActive($currentPlayer, "CRU002") && $attackValue >= 6) KayoStaticAbility();
        $openedChain = true;
        if($definedCardType != "AA") $combatChainState[$CCS_WeaponIndex] = GetClassState($currentPlayer, $CS_PlayIndex);
      }
      SetClassState($currentPlayer, $CS_PlayCCIndex, $index);
    }
    else if($from != "PLAY")
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
      else if($cardSubtype == "Landmark")
      {
        PlayLandmark($cardID, $currentPlayer);
      }
      else if($definedCardType != "C" && $definedCardType != "E" && $definedCardType != "W")
      {
        $goesWhere = GoesWhereAfterResolving($cardID, $from);
        switch($goesWhere)
        {
          case "BOTDECK": AddBottomDeck($cardID, $currentPlayer, $from); break;
          case "HAND": AddPlayerHand($cardID, $currentPlayer, $from); break;
          case "GY": AddGraveyard($cardID, $currentPlayer, $from); break;
          case "SOUL": AddSoul($cardID, $currentPlayer, $from); break;
          case "BANISH": BanishCardForPlayer($cardID, $currentPlayer, $from, "NA"); break;
          default: break;
        }
      }
    }
    //Resolve Effects
    if(!$isBlock)// || $cardType == "I" || CanPlayAsInstant($cardID))
    {
      if($from != "PLAY")
      {
        ResetCardPlayed($cardID);
        CurrentEffectPlayAbility($cardID);
        AuraPlayAbilities($cardID, $from);
        ArsenalPlayCardAbilities($cardID);
        if(HasBoost($cardID)) Boost();
        CharacterPlayCardAbilities($cardID, $from);
      }
      SetClassState($currentPlayer, $CS_EffectContext, $cardID);
      $playText = PlayAbility($cardID, $from, $resourcesPaid, $target);
      AddDecisionQueue("CLEAREFFECTCONTEXT", $currentPlayer, "-");
      if($playText != "") WriteLog("Resolving play ability of " . $cardID . ": " . $playText);
      if($openedChain) ProcessAttackTargetAfterResolve();
    }

    if($CS_CharacterIndex != -1 && CanPlayAsInstant($cardID))
    {
      RemoveCharacterEffects($currentPlayer, GetClassState($currentPlayer, $CS_CharacterIndex), "INSTANT");
    }
    //Now determine what needs to happen next
    SetClassState($currentPlayer, $CS_PlayIndex, -1);
    SetClassState($currentPlayer, $CS_CharacterIndex, -1);
    ResetThisCardPitch($currentPlayer);
    ProcessDecisionQueue();
  }

  function ProcessAttackTarget()
  {
    global $combatChainState, $CCS_AttackTarget, $defPlayer;
    $target = explode("-", $combatChainState[$CCS_AttackTarget]);
    if($target[0] == "THEIRAURAS")
    {
      $auras = &GetAuras($defPlayer);
      if(HasSpectra($auras[$target[1]]))
      {
        DestroyAura($defPlayer, $target[1]);
        CloseCombatChain();
      }
    }
  }

  function ProcessAttackTargetAfterResolve()
  {
    global $combatChainState, $CCS_AttackTarget, $defPlayer;
    $target = explode("-", $combatChainState[$CCS_AttackTarget]);
    if($target[0] == "THEIRALLY")
    {
      $index = $target[1];
      $totalAttack = 0;
      $totalDefense = 0;
      $chainAttackModifiers = [];
      EvaluateCombatChain($totalAttack, $totalDefense, $chainAttackModifiers);
      $allies = &GetAllies($defPlayer);
      $allies[$index+2] -= $totalAttack;
      if($allies[$index+2] <= 0) DestroyAlly($defPlayer, $index);
      CloseCombatChain("-");//This makes it NOT close the chain, so it does resolve go again and other chain effects
    }
  }

?>
