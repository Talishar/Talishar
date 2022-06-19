<?php

include "CardDictionary.php";
include "CoreLogic.php";

function BlessingOfDeliveranceDestroy($amount)
{
  global $mainPlayer;
  $deck = GetDeck($mainPlayer);
  $lifegain = 0;
  $cards = "";
  for($i=0; $i<$amount; ++$i)
  {
    if(count($deck) > $i)
    {
      $cards .= $deck[$i] . ($i < 2 ? "," : "");
      if(CardCost($deck[$i]) >= 3) ++$lifegain;
    }
  }
  RevealCards($cards);
  GainHealth($lifegain, $mainPlayer);
  return "Blessing of Deliverance gained " . $lifegain . " life.";
}

function EmergingPowerDestroy($cardID)
{
  global $mainPlayer;
  $log = "Emerging Power gives the next Guardian attack this turn +" . EffectAttackModifier($cardID) . ".";
  AddCurrentTurnEffect($cardID, $mainPlayer);
  return $log;
}

function PummelHit($player=-1)
{
  global $defPlayer;
  if($player == -1) $player = $defPlayer;
  AddDecisionQueue("FINDINDICES", $player, "HAND");
  AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to discard", 1);
  AddDecisionQueue("CHOOSEHAND", $player, "<-", 1);
  AddDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
  AddDecisionQueue("ADDDISCARD", $player, "HAND", 1);
}

function HandToTopDeck($player)
{
  AddDecisionQueue("FINDINDICES", $player, "HAND");
  AddDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
  AddDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
  AddDecisionQueue("MULTIADDTOPDECK", $player, "-", 1);
}

function KatsuHit($index)
{
  global $mainPlayer;
  AddDecisionQueue("YESNO", $mainPlayer, "to_use_Katsu's_ability");
  AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
  AddDecisionQueue("FINDINDICES", $mainPlayer, "WTR076-1", 1);
  AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
  AddDecisionQueue("DISCARDMYHAND", $mainPlayer, "-", 1);
  AddDecisionQueue("FINDINDICES", $mainPlayer, "WTR076-2", 1);
  AddDecisionQueue("CHOOSEDECK", $mainPlayer, "<-", 1);
  AddDecisionQueue("BANISH", $mainPlayer, "TT", 1);
  AddDecisionQueue("EXHAUSTCHARACTER", $mainPlayer, $index, 1);
}

function RandomHandBottomDeck($player)
{
  $hand = &GetHand($player);
  if(count($hand) == 0) return;
  $index = rand() % count($hand);
  $discarded = $hand[$index];
  unset($hand[$index]);
  $hand = array_values($hand);
  $deck = &GetDeck($player);
  array_push($deck, $discarded);
}

function LordOfWindIndices($player)
{
  $array = [];
  $indices = SearchDiscardForCard($player, "WTR107", "WTR108", "WTR109");
  if($indices != "") array_push($array, $indices);
  $indices = SearchDiscardForCard($player, "WTR110", "WTR111", "WTR112");
  if($indices != "") array_push($array, $indices);
  $indices = SearchDiscardForCard($player, "WTR83");
  if($indices != "") array_push($array, $indices);
  return implode(",", $array);
}

function NaturesPathPilgrimageHit()
{
  global $mainPlayer;
  $deck = &GetDeck($mainPlayer);
  if(!ArsenalFull($mainPlayer) && count($deck) > 0)
  {
    $type = CardType($deck[0]);
    if($type == "A" || $type == "AA")
    {
      AddArsenal($deck[0], $mainPlayer, "DECK", "DOWN");
      array_shift($deck);
    }
  }
}

function UnifiedDecreePlayEffect()
{
  global $mainPlayer;
  $deck = &GetDeck($mainPlayer);
  if(count($deck) == 0) return;
  RevealCards($deck[0]);
  if(CardType($deck[0]) == "AR")
  {
    BanishCardForPlayer($deck[0], $mainPlayer, "DECK", "TCC");
    array_shift($deck);
  }
}

function BottomDeck()
{
  global $currentPlayer;
  $hand = GetHand($currentPlayer);
  if(count($hand) > 0)
  {
    AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
    AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
    AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
    AddDecisionQueue("ADDBOTTOMMYDECK", $currentPlayer, "-", 1);
  }
}

function BottomDeckDraw()
{
  global $currentPlayer;
  $hand = GetHand($currentPlayer);
  if(count($hand) > 0)
  {
    BottomDeck();
    AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
  }
}

function AddCurrentTurnEffect($cardID, $player, $from="", $uniqueID=-1)
{
  global $currentTurnEffects, $combatChain;
  $card = explode("-", $cardID)[0];
  if(CardType($card) == "A" && count($combatChain) > 0 && !IsCombatEffectPersistent($cardID) && $from != "PLAY") { AddCurrentTurnEffectFromCombat($cardID, $player, $uniqueID); return; }
  array_push($currentTurnEffects, $cardID);
  array_push($currentTurnEffects, $player);
  array_push($currentTurnEffects, $uniqueID);
  array_push($currentTurnEffects, CurrentTurnEffectUses($cardID));
}

//This is needed because if you add a current turn effect from combat, it could get deleted as part of the combat resolution
function AddCurrentTurnEffectFromCombat($cardID, $player, $uniqueID=-1)
{
  global $currentTurnEffectsFromCombat;
  array_push($currentTurnEffectsFromCombat, $cardID);
  array_push($currentTurnEffectsFromCombat, $player);
  array_push($currentTurnEffectsFromCombat, $uniqueID);
  array_push($currentTurnEffectsFromCombat, CurrentTurnEffectUses($cardID));
}

function CopyCurrentTurnEffectsFromCombat()
{
  global $currentTurnEffects, $currentTurnEffectsFromCombat;
  for($i=0; $i<count($currentTurnEffectsFromCombat); $i += CurrentTurnEffectPieces())
  {
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i]);
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i+1]);
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i+2]);
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i+3]);
  }
  $currentTurnEffectsFromCombat = [];
}

function RemoveCurrentTurnEffect($index)
{
  global $currentTurnEffects;
  unset($currentTurnEffects[$index+3]);
  unset($currentTurnEffects[$index+2]);
  unset($currentTurnEffects[$index+1]);
  unset($currentTurnEffects[$index]);
  $currentTurnEffects = array_values($currentTurnEffects);
}

function CurrentTurnEffectPieces()
{
  return 4;
}

function CurrentTurnEffectUses($cardID)
{
  switch($cardID)
  {
    case "UPR000": return 3;
    case "UPR088": return 4;
    default: return 1;
  }
}

function AddNextTurnEffect($cardID, $player)
{
  global $nextTurnEffects;
  array_push($nextTurnEffects, $cardID);
  array_push($nextTurnEffects, $player);
}

function IsCombatEffectLimited($index)
{
  global $currentTurnEffects, $combatChain, $mainPlayer, $combatChainState, $CCS_WeaponIndex, $CCS_AttackUniqueID;
  if(count($combatChain) == 0 || $currentTurnEffects[$index+2] == -1) return false;
  $attackSubType = CardSubType($combatChain[0]);
  if(DelimStringContains($attackSubType, "Ally"))
  {
    $allies = &GetAllies($mainPlayer);
    if(count($allies) < $combatChainState[$CCS_WeaponIndex]+5) return false;
    if($allies[$combatChainState[$CCS_WeaponIndex]+5] != $currentTurnEffects[$index+2]) return true;
  }
  else
  {
    return $combatChainState[$CCS_AttackUniqueID] != $currentTurnEffects[$index+2];
  }
  return false;
}

function HasEffect($cardID)
{
  global $currentTurnEffects;
  for($i=0; $i<count($currentTurnEffects); $i += CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i] == $cardID) return true;
  }
  return false;
}

function AddLayer($cardID, $player, $parameter, $target="-", $additionalCosts="-")
{
  global $layers;
  //Layers are on a stack, so you need to push things on in reverse order
  array_unshift($layers, $additionalCosts);
  array_unshift($layers, $target);
  array_unshift($layers, $parameter);
  array_unshift($layers, $player);
  array_unshift($layers, $cardID);
}

function AddDecisionQueue($phase, $player, $parameter, $subsequent=0, $makeCheckpoint=0)
{
  global $decisionQueue;
  $parameter = str_replace(" ", "_", $parameter);
  array_push($decisionQueue, $phase);
  array_push($decisionQueue, $player);
  array_push($decisionQueue, $parameter);
  array_push($decisionQueue, $subsequent);
  array_push($decisionQueue, $makeCheckpoint);
}

function PrependDecisionQueue($phase, $player, $parameter, $subsequent=0, $makeCheckpoint=0)
{
  global $decisionQueue;
  array_unshift($decisionQueue, $makeCheckpoint);
  array_unshift($decisionQueue, $subsequent);
  array_unshift($decisionQueue, $parameter);
  array_unshift($decisionQueue, $player);
  array_unshift($decisionQueue, $phase);
}

  function ProcessDecisionQueue()
  {
    global $turn, $decisionQueue, $dqState;
    if($dqState[0] != "1")
    {
      $count = count($turn);
      if(count($turn) < 3) $turn[2] = "-";
      $dqState[0] = "1";//If the decision queue is currently active/processing
      $dqState[1] = $turn[0];
      $dqState[2] = $turn[1];
      $dqState[3] = $turn[2];
      $dqState[4] = "-";//DQ helptext initial value
      $dqState[5] = "-";//Decision queue multizone indices
      //array_unshift($turn, "-", "-", "-");
    }
    ContinueDecisionQueue("");
  }

  function CloseDecisionQueue()
  {
    global $turn, $decisionQueue, $dqState;
    $dqState[0] = "0";
    $turn[0] = $dqState[1];
    $turn[1] = $dqState[2];
    $turn[2] = $dqState[3];
    $dqState[4] = "-";//Clear the context, just in case
    $dqState[5] = "-";//Clear Decision queue multizone indices
    $decisionQueue = [];
  }

  function ShouldHoldPriorityNow($player)
  {
    global $layerPriority, $layers;
    if($layerPriority[$player-1] != "1") return false;
    $currentLayer = $layers[count($layers) - LayerPieces()];
    $layerType = CardType($currentLayer);
    if(HoldPrioritySetting($player) == 3 && $layerType != "AA" && $layerType != "W") return false;
    return true;
  }

  //Must be called with the my/their context
  function ContinueDecisionQueue($lastResult="")
  {
    global $decisionQueue, $turn, $currentPlayer, $mainPlayerGamestateStillBuilt, $makeCheckpoint, $otherPlayer;
    global $layers, $layerPriority, $dqVars, $dqState, $CS_AbilityIndex, $CS_CharacterIndex, $CS_AdditionalCosts, $lastPlayed;
    if(count($decisionQueue) == 0 || $decisionQueue[0] == "RESUMEPAYING" || $decisionQueue[0] == "RESUMEPLAY" || $decisionQueue[0] == "RESOLVECHAINLINK" || $decisionQueue[0] == "RESOLVECOMBATDAMAGE" || $decisionQueue[0] == "PASSTURN")
    {
      if($mainPlayerGamestateStillBuilt) UpdateMainPlayerGameState();
      else if(count($decisionQueue) > 0 && $currentPlayer != $decisionQueue[1]) { UpdateGameState($currentPlayer); }
      if(count($decisionQueue) == 0 && count($layers) > 0)
      {
        $priorityHeld = 0;
        if($currentPlayer == 1)
        {
          if(ShouldHoldPriorityNow(1)) { AddDecisionQueue("INSTANT", 1, "-"); $priorityHeld = 1; $layerPriority[0] = 0; }
          if(ShouldHoldPriorityNow(2)) { AddDecisionQueue("INSTANT", 2, "-"); $priorityHeld = 1; $layerPriority[1] = 0; }
        }
        else
        {
          if(ShouldHoldPriorityNow(2)) { AddDecisionQueue("INSTANT", 2, "-"); $priorityHeld = 1; $layerPriority[1] = 0; }
          if(ShouldHoldPriorityNow(1)) { AddDecisionQueue("INSTANT", 1, "-"); $priorityHeld = 1; $layerPriority[0] = 0; }
        }
        if($priorityHeld)
        {
          ContinueDecisionQueue("");
        }
        else
        {
          CloseDecisionQueue();
          $cardID = array_shift($layers);
          $player = array_shift($layers);
          $parameter = array_shift($layers);
          $target = array_shift($layers);
          $additionalCosts = array_shift($layers);
          $params = explode("|", $parameter);
          if($currentPlayer != $player)
          {
            if($mainPlayerGamestateStillBuilt) UpdateMainPlayerGameState();
            else UpdateGameState($currentPlayer);
            $currentPlayer = $player;
            $otherPlayer = $currentPlayer == 1 ? 2 : 1;
            BuildMyGamestate($currentPlayer);
          }
          $layerPriority[0] = ShouldHoldPriority(1);
          $layerPriority[1] = ShouldHoldPriority(2);
          if($cardID == "ENDTURN") FinishTurnPass();
          else if($cardID == "RESUMETURN") $turn[0] = "M";
          else
          {
            SetClassState($player, $CS_AbilityIndex, $params[2]);//This is like a parameter to PlayCardEffect and other functions
            if(HasFusion($cardID))
            {
              $lastPlayed[3] = ($additionalCosts != "-" ? "FUSED" : "UNFUSED");
            }
            PlayCardEffect($cardID, $params[0], $params[1], $target, $additionalCosts, $params[3]);
          }
        }
      }
      else if(count($decisionQueue) > 0 && $decisionQueue[0] == "RESUMEPLAY")
      {
        if($currentPlayer != $decisionQueue[1])
        {
          $currentPlayer = $decisionQueue[1];
          $otherPlayer = $currentPlayer == 1 ? 2 : 1;
          BuildMyGamestate($currentPlayer);
        }
        $params = explode("|", $decisionQueue[2]);
        CloseDecisionQueue();

        if($turn[0] == "B")//If a layer is not created
        {
          PlayCardEffect($params[0], $params[1], $params[2], "-", $params[3], $params[4]);
        }
        else
        {
          //params 3 = ability index
          //params 4 = Unique ID
          $layerTarget = GetClassState($currentPlayer, $CS_LayerTarget);
          $additionalCosts = GetClassState($currentPlayer, $CS_AdditionalCosts);
          if($layerTarget == "") $layerTarget = "-";
          if($additionalCosts == "") $additionalCosts = "-";
          AddLayer($params[0], $currentPlayer, $params[1] . "|" . $params[2] . "|" . $params[3] . "|" . $params[4], $layerTarget, $additionalCosts);
          ProcessDecisionQueue();
          return;
        }


      }
      else if(count($decisionQueue) > 0 && $decisionQueue[0] == "RESUMEPAYING")
      {
        $params = explode("-", $decisionQueue[2]);//Parameter
        if($lastResult == "") $lastResult = 0;
        CloseDecisionQueue();
        PlayCard($params[0], $params[1], $lastResult, $params[2]);
      }
      else if(count($decisionQueue) > 0 && $decisionQueue[0] == "RESOLVECHAINLINK")
      {
        CloseDecisionQueue();
        ResolveChainLink();
      }
      else if(count($decisionQueue) > 0 && $decisionQueue[0] == "RESOLVECOMBATDAMAGE")
      {
        CloseDecisionQueue();
        ResolveCombatDamage($lastResult);
      }
      else if(count($decisionQueue) > 0 && $decisionQueue[0] == "PASSTURN")
      {
        CloseDecisionQueue();
        PassTurn();
      }
      else
      {
        CloseDecisionQueue();
        FinalizeAction();
      }
      return;
    }
    $phase = array_shift($decisionQueue);
    $player = array_shift($decisionQueue);
    $parameter = array_shift($decisionQueue);
    $parameter = str_replace("{I}", $dqState[5], $parameter);
    $parameter = str_replace("{0}", $dqVars[0], $parameter);
    $parameter = str_replace("<0>", CardLink($dqVars[0], $dqVars[0]), $parameter);
    $parameter = str_replace("<1>", CardLink($dqVars[1], $dqVars[1]), $parameter);
    $subsequent = array_shift($decisionQueue);
    $makeCheckpoint = array_shift($decisionQueue);
    $turn[0] = $phase;
    $turn[1] = $player;
    $currentPlayer = $player;
    $turn[2] = ($parameter == "<-" ? $lastResult : $parameter);
    $return = "PASS";
    if($subsequent != 1 || is_array($lastResult) || strval($lastResult) != "PASS") $return = DecisionQueueStaticEffect($phase, $player, ($parameter == "<-" ? $lastResult : $parameter), $lastResult);
    if($parameter == "<-" && !is_array($lastResult) && $lastResult == "-1") $return = "PASS";//Collapse the rest of the queue if this decision point has invalid parameters
    if(is_array($return) || strval($return) != "NOTSTATIC")
    {
      if($phase != "SETDQCONTEXT") $dqState[4] = "-";//Clear out context for static states -- context only persists for one choice
      ContinueDecisionQueue($return);
    }
    else
    {
      if($mainPlayerGamestateStillBuilt) UpdateMainPlayerGameState();
    }
  }

  function GetDQHelpText()
  {
    global $dqState;
    return $dqState[4];
  }

  function GetDQMZIndices()
  {
    global $dqState;
    return $dqState[5];
  }

  function FinalizeAction()
  {
    global $currentPlayer, $mainPlayer, $otherPlayer, $actionPoints, $turn, $combatChain, $defPlayer, $makeBlockBackup, $mainPlayerGamestateStillBuilt;
    global $layerPriority;
    if(!$mainPlayerGamestateStillBuilt) UpdateGameState(1);
    BuildMainPlayerGamestate();
    if($turn[0] == "M")
    {
      if(count($combatChain) > 0)//Means we initiated a chain link
      {
        $turn[0] = "B";
        $currentPlayer = $defPlayer;
        $turn[2] = "";
        $makeBlockBackup = 1;
      }
      else {
        if($actionPoints > 0 || ShouldHoldPriority($mainPlayer))
        {
          $turn[0] = "M";
          $currentPlayer = $mainPlayer;
          $turn[2] = "";
        }
        else
        {
          $currentPlayer = $mainPlayer;
          BeginTurnPass();
        }
      }
    }
    else if($turn[0] == "A")
    {
      $turn[0] = "D";
      $currentPlayer = $defPlayer;
      $turn[2] = "";
    }
    else if($turn[0] == "D")
    {
      $turn[0] = "A";
      $currentPlayer = $mainPlayer;
      $turn[2] = "";
    }
    else if($turn[0] == "B")
    {
      $turn[0] = "B";
    }
    return 0;
  }

  function IsReactionPhase()
  {
    global $turn, $dqState;
    if($turn[0] == "A" || $turn[0] == "D") return true;
    if(count($dqState) >= 2 && ($dqState[1] == "A" || $dqState[1] == "D")) return true;
    return false;
  }

//Return whether priority should be held for the player by default/settings
function ShouldHoldPriority($player, $layerCard="")
{
  global $mainPlayer;
  $prioritySetting = HoldPrioritySetting($player);
  if($prioritySetting == 0 || $prioritySetting == 1) return 1;
  if(($prioritySetting == 2 || $prioritySetting == 3) && $player != $mainPlayer) return 1;
  return 0;
}

function GiveAttackGoAgain()
{
  global $combatChainState, $CCS_CurrentAttackGainedGoAgain;
  $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
}

function DefenderTopDeckToArsenal()
{
  global $defPlayer;
  TopDeckToArsenal($defPlayer);
}

function MainTopDeckToArsenal()
{
  global $mainPlayer;
  TopDeckToArsenal($mainPlayer);
}

function TopDeckToArsenal($player)
{
  $deck = &GetDeck($player);
  if(!ArsenalEmpty($player) || count($deck) == 0) return;//Already something there
  AddArsenal(array_shift($deck), $player, "DECK", "DOWN");
  WriteLog("The top card of player " . $player . "'s deck was put in their arsenal.");
}

function DefenderArsenalToBottomOfDeck()
{
  global $defPlayer;
  ArsenalToBottomDeck($defPlayer);
}

function ArsenalToBottomDeck($player)
{
  //TODO: Allow to choose arsenal slot
  $arsenal = &GetArsenal($player);
  if(count($arsenal) == 0) return;
  $index = 0;
  AddBottomDeck($arsenal[$index], $player, "ARS");
  for($i=$index+ArsenalPieces()-1; $i>=$index; --$i)
  {
    unset($arsenal[$i]);
  }
  $arsenal = array_values($arsenal);
}

function DestroyArsenal($player)
{
  $arsenal = &GetArsenal($player);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    WriteLog(CardLink($arsenal[$i], $arsenal[$i]) . " was destroyed from the arsenal.");
    AddGraveyard($arsenal[$i], $player, "ARS");
  }
  $arsenal = [];
}

function DiscardHand($player)
{
  $hand = &GetHand($player);
  for($i=0; $i<count($hand); $i+=HandPieces())
  {
    AddGraveyard($hand[$i], $player, "HAND");
  }
  $hand = [];
}

function DiscardIndex($player, $index)
{
  $hand = &GetHand($player);
  AddGraveyard($hand[$index], $player, "HAND");
  unset($hand[$index]);
  $hand = array_values($hand);
}

function Opt($cardID, $amount)
{
  global $currentPlayer;
  PlayerOpt($currentPlayer, $amount);
}

function OptMain($amount)
{
  global $mainPlayer;
  PlayerOpt($mainPlayer, $amount);
}

function PlayerOpt($player, $amount)
{
  AddDecisionQueue("FINDINDICES", $player, "DECKTOPX," . $amount);
  AddDecisionQueue("MULTIREMOVEDECK", $player, "-", 1);
  AddDecisionQueue("OPT", $player, "<-", 1);
}

function DiscardRandom($player="", $source="")
{
  global $CS_Num6PowDisc, $mainPlayer, $currentPlayer;
  if($player == "") $player = $currentPlayer;
  $hand = &GetHand($player);
  if(count($hand) == 0) return;
  $index = rand() % count($hand);
  $discarded = $hand[$index];
  unset($hand[$index]);
  $hand = array_values($hand);
  AddGraveyard($discarded, $player, "HAND");
  WriteLog(CardLink($discarded, $discarded) . " was randomly discarded.");
  if(AttackValue($discarded) >= 6)
  {
    $character = &GetPlayerCharacter($player);
    if(($character[0] == "WTR001" || $character[0] == "WTR002" || $character[0] == "RVD001") && $character[1] == 2 && $player == $mainPlayer) {//Rhinar
      WriteLog("Rhinar Intimidated.");
      Intimidate();
    }
    IncrementClassState($player, $CS_Num6PowDisc);
  }
  if($discarded == "CRU008" && $source != "" && CardClass($source) == "BRUTE" && CardType($source) == "AA")
  {
    WriteLog("Massacre Intimidated because it was discarded by a Brute attack action card..");
    Intimidate();
  }
  return $discarded;
};

function DefDiscardRandom()
{
  global $defPlayer;
  $hand = &GetHand($defPlayer);
  if(count($hand) == 0) return;
  $index = rand() % count($hand);
  AddGraveyard($hand[$index], $defPlayer, "HAND");
  unset($hand[$index]);
  $hand = array_values($hand);
};

function Intimidate()
{
  global $defPlayer;//For now we'll assume you can only intimidate the opponent
  //$otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $hand = &GetHand($defPlayer);
  if(count($hand) == 0) { WriteLog("Intimidate did nothing because there are no cards in hand."); return; }//Nothing to do if they have no hand
  $index = rand() % count($hand);
  BanishCardForPlayer($hand[$index], $defPlayer, "HAND", "INT");
  unset($hand[$index]);
  $hand = array_values($hand);
  WriteLog("Intimidate banished a card.");
}

//Deprecated: Use BanishCard in CardSetters instead
function Banish($player, $cardID, $from)
{
  BanishCardForPlayer($cardID, $player, $from);
}

?>
