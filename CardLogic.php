<?php

include "CardDictionary.php";
include "CoreLogic.php";

function BlessingOfDeliveranceDestroy($amount)
{
  global $mainPlayer, $mainDeck, $mainHealth;
  $log = "Blessing of Deliverance revealed ";
  $lifegain = 0;
  for($i=0; $i<$amount; ++$i)
  {
    if(count($mainDeck) > $i)
    {
      $log .= $mainDeck[$i] . ($i < 2 ? "," : "") . " ";
      if(CardCost($mainDeck[$i]) >= 3) ++$lifegain;
    }
  }
  $log .= "and gained " . $lifegain . " life.";
  $mainHealth += $lifegain;
  return $log;
}

function EmergingPowerDestroy($cardID)
{
  global $mainPlayer;
  $log = "Emerging Power gives the next Guardian attack this turn +" . EffectAttackModifier($cardID) . ".";
  AddCurrentTurnEffect($cardID, $mainPlayer);
  return $log;
}

function PummelHit()
{
  global $defPlayer;
  AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
  AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
  AddDecisionQueue("MULTIREMOVEHAND", $defPlayer, "-", 1);
}

function KatsuHit($index)
{
  global $mainPlayer;
  AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_use_Katsu's_ability");
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
  $index = rand() % count($hand);
  $discarded = $hand[$index];
  unset($hand[$index]);
  $hand = array_values($hand);
  $deck = &GetDeck($player);
  array_push($deck, $discarded);
}

function LordOfWindIndices()
{
  $array = [];
  $indices = SearchMyDiscardForCard("WTR107", "WTR108", "WTR109");
  if($indices != "") array_push($array, $indices);
  $indices = SearchMyDiscardForCard("WTR110", "WTR111", "WTR112");
  if($indices != "") array_push($array, $indices);
  $indices = SearchMyDiscardForCard("WTR83");
  if($indices != "") array_push($array, $indices);
  return implode(",", $array);
}

function NaturesPathPilgrimageHit()
{
  global $mainPlayer, $mainDeck;
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
  global $myDeck, $mainPlayer;
  if(count($myDeck) == 0) return;
  WriteLog("Unified Decree reveals " . $myDeck[0] . ".");
  if(CardType($myDeck[0]) == "AR")
  {
    BanishCardForPlayer($myDeck[0], $mainPlayer, "DECK", "TCC");
    array_shift($myDeck);
  }
}

function BottomDeck()
{
  global $myHand, $playerID;
  if(count($myHand) > 0)
  {
    AddDecisionQueue("MAYCHOOSEHAND", $playerID, GetMyHandIndices());
    AddDecisionQueue("DISCARDMYHAND", $playerID, "-", 1);
    AddDecisionQueue("ADDBOTTOMMYDECK", $playerID, "-", 1);
  }
}

function BottomDeckDraw()
{
  global $myHand, $playerID;
  if(count($myHand) > 0)
  {
    BottomDeck();
    AddDecisionQueue("DRAW", $playerID, "-", 1);
  }
}

function AddCurrentTurnEffect($cardID, $player)
{
  global $currentTurnEffects, $combatChain;
  $card = explode("-", $cardID)[0];
  if(CardType($card) == "A" && count($combatChain) > 0 && !IsCombatEffectPersistent($cardID)) { AddCurrentTurnEffectFromCombat($cardID, $player); return; }
  array_push($currentTurnEffects, $cardID);
  array_push($currentTurnEffects, $player);
}

//This is needed because if you add a current turn effect from combat, it could get deleted as part of the combat resolution
function AddCurrentTurnEffectFromCombat($cardID, $player)
{
  global $currentTurnEffectsFromCombat;
  array_push($currentTurnEffectsFromCombat, $cardID);
  array_push($currentTurnEffectsFromCombat, $player);
}

function CopyCurrentTurnEffectsFromCombat()
{
  global $currentTurnEffects, $currentTurnEffectsFromCombat;
  for($i=0; $i<count($currentTurnEffectsFromCombat); $i += 2)
  {
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i]);
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i+1]);
  }
  $currentTurnEffectsFromCombat = [];
}

function RemoveCurrentTurnEffect($index)
{
  global $currentTurnEffects;
  unset($currentTurnEffects[$index+1]);
  unset($currentTurnEffects[$index]);
  $currentTurnEffects = array_values($currentTurnEffects);
}

function CurrentTurnEffectPieces()
{
  return 2;
}

function AddNextTurnEffect($cardID, $player)
{
  global $nextTurnEffects;
  array_push($nextTurnEffects, $cardID);
  array_push($nextTurnEffects, $player);
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

function AddLayer($cardID, $player, $parameter, $target="-")
{
  global $layers;
  //Layers are on a stack, so you need to push things on in reverse order
  array_unshift($layers, $target);
  array_unshift($layers, $parameter);
  array_unshift($layers, $player);
  array_unshift($layers, $cardID);
}

function AddDecisionQueue($phase, $player, $parameter, $subsequent=0, $makeCheckpoint=0)
{
  global $decisionQueue;
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
    global $turn, $decisionQueue;
    if($turn[0] != "INSTANT")//Or anything that can cause a card to be played
    {
      $count = count($turn);
      if(count($turn) < 3) $turn[2] = "";
      array_unshift($turn, "", "", "");
    }
    ContinueDecisionQueue("");
  }

  //Must be called with the my/their context
  function ContinueDecisionQueue($lastResult="")
  {
    global $decisionQueue, $turn, $currentPlayer, $mainPlayerGamestateBuilt, $makeCheckpoint, $otherPlayer, $p2ClassState, $myClassState, $theirClassState;
    global $layers, $layerPriority;
    if(count($decisionQueue) == 0 || $decisionQueue[0] == "RESUMEPAYING" || $decisionQueue[0] == "RESUMEPLAY")
    {
      if($mainPlayerGamestateBuilt) UpdateMainPlayerGameState();
      else if(count($decisionQueue) > 0 && $currentPlayer != $decisionQueue[1]) { UpdateGameState($currentPlayer); }
      if(count($decisionQueue) == 0 && count($layers) > 0)
      {
        $priorityHeld = 0;
        if($layerPriority[0] == "1") { AddDecisionQueue("INSTANT", 1, "-", 1); $priorityHeld = 1; $layerPriority[0] = 0; }
        if($layerPriority[1] == "1") { AddDecisionQueue("INSTANT", 2, "-", 1); $priorityHeld = 1; $layerPriority[1] = 0; }
        if($priorityHeld)
        {
          ContinueDecisionQueue("");
        }
        else
        {
          array_shift($turn);
          array_shift($turn);
          array_shift($turn);
          $decisionQueue = [];
          $cardID = array_shift($layers);
          $player = array_shift($layers);
          $parameter = array_shift($layers);
          $target = array_shift($layers);
          $params = explode("-", $parameter);
          if($currentPlayer != $player)
          {
            $currentPlayer = $player;
            $otherPlayer = $currentPlayer == 1 ? 2 : 1;
            BuildMyGamestate($currentPlayer);
          }
          $layerPriority[0] = ShouldHoldPriority(1);
          $layerPriority[1] = ShouldHoldPriority(2);
          PlayCardEffect($cardID, $params[0], $params[1], $target);
        }
      }
      else if(count($decisionQueue) > 0 && $decisionQueue[0] == "RESUMEPLAY")
      {
        array_shift($turn);
        array_shift($turn);
        array_shift($turn);
        if($currentPlayer != $decisionQueue[1])
        {
          $currentPlayer = $decisionQueue[1];
          $otherPlayer = $currentPlayer == 1 ? 2 : 1;
          BuildMyGamestate($currentPlayer);
        }
        $params = explode("-", $decisionQueue[2]);
        $decisionQueue = [];
        PlayCardEffect($params[0], $params[1], $params[2]);
      }
      else if(count($decisionQueue) > 0 && $decisionQueue[0] == "RESUMEPAYING")
      {
        array_shift($turn);
        array_shift($turn);
        array_shift($turn);
        $params = explode("-", $decisionQueue[2]);//Parameter
        $decisionQueue = [];
        if($lastResult == "") $lastResult = 0;
        PlayCard($params[0], $params[1], $lastResult);
      }
      else
      {
        array_shift($turn);
        array_shift($turn);
        array_shift($turn);
        FinalizeAction();
      }
      return;
    }
    $phase = array_shift($decisionQueue);
    $player = array_shift($decisionQueue);
    $parameter = array_shift($decisionQueue);
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
      ContinueDecisionQueue($return);
    }
    else
    {
      if($mainPlayerGamestateBuilt) UpdateMainPlayerGameState();
    }
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

//Return whether priority should be held for the player by default/settings
function ShouldHoldPriority($player)
{
  $char = GetPlayerCharacter($player);
  if($char[0] == "ARC113" || $char[0] == "ARC114") return 1;
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
  if(ArsenalFull($player) || count($deck) == 0) return;//Already something there
  AddArsenal(array_shift($deck), $player, "DECK", "DOWN");
  WriteLog("The top card of player " . $player . "'s deck was put in their arsenal.");
}

function DefenderArsenalToBottomOfDeck()
{
  global $defArsenal, $defDeck;
  array_push($defDeck, $defArsenal);
  $defArsenal = "";
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

function Opt($cardID, $amount)
{
  global $myDeck, $turn, $currentPlayer;
  if($amount <= 0) return;
  $cards = "";
  for($i=0; $i<$amount; ++$i)
  {
    $cards .= array_shift($myDeck);
    if($i < $amount-1) $cards .= ",";
  }
  AddDecisionQueue("OPT", $currentPlayer, $cards);
}

function OptMain($amount)
{
  global $mainDeck, $turn, $mainPlayer;
  if($amount <= 0) return;
  $cards = "";
  for($i=0; $i<$amount; ++$i)
  {
    $cards .= array_shift($mainDeck);
    if($i < $amount-1) $cards .= ",";
  }
  AddDecisionQueue("OPT", $mainPlayer, $cards);
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
  if(AttackValue($discarded) >= 6)
  {
    $character = &GetPlayerCharacter($player);
    if(($character[0] == "WTR001" || $character[0] == "WTR002") && $player == $mainPlayer) {//Rhinar
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
  global $defHand,$defDiscard;
  if(count($defHand) == 0) return;
  $index = rand() % count($defHand);
  array_push($defDiscard, $defHand[$index]);
  //TODO: other discard stuff
  unset($defHand[$index]);
  $defHand = array_values($defHand);
};

function Intimidate()
{
  global $defPlayer, $theirHand, $currentPlayer;//For now we'll assume you can only intimidate the opponent
  if(count($theirHand) == 0) { WriteLog("Intimidate did nothing because there are no cards in hand."); return; }//Nothing to do if they have no hand
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $index = rand() % count($theirHand);
  BanishCardForPlayer($theirHand[$index], $otherPlayer, "HAND", "INT");
  unset($theirHand[$index]);
  $theirHand = array_values($theirHand);
  WriteLog("Intimidate triggered " . count($theirHand));
  UpdateGameState($currentPlayer);
}

//Deprecated: Use BanishCard in CardSetters instead
function Banish($player, $cardID, $from)
{
  global $playerID, $myBanish, $theirBanish;
  if($playerID == $player)
  {
    array_push($myBanish, $cardID);
    array_push($myBanish, $from);
  }
  else
  {
    array_push($theirBanish, $cardID);
    array_push($theirBanish, $from);
  }
  //TODO: Banish stuff, e.g. Levia
}

?>

