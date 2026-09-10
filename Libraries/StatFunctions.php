<?php

define('CARD_STAT_PIECES', 10);
define('TURN_STAT_PIECES', 15);

function CardStatPieces() { return CARD_STAT_PIECES; }
function TurnStatPieces()  { return TURN_STAT_PIECES; }

$CardStats_TimesPlayed = 1;
$CardStats_TimesBlocked = 2;
$CardStats_TimesPitched = 3;
$CardStats_TimesHit = 4;
$CardStats_TimesCharged = 5;
$CardStats_TimesDiscarded = 6;
$CardStats_TimesActivated = 7; //Tracks weapon/arena card activations from play
$CardStats_TimesPassiveTriggered = 8; //Tracks passive equipment/character effect triggers (e.g. Tiger Stripe Shuko buff, Valiant Dynamo refresh)
$CardStats_TimesKatsuDiscard = 9;
$TurnStats_DamageThreatened = 0;
$TurnStats_DamageDealt = 1;
$TurnStats_CardsPlayedOffense = 2;
$TurnStats_CardsPlayedDefense = 3;
$TurnStats_CardsPitched = 4;
$TurnStats_CardsBlocked = 5;
$TurnStats_ResourcesUsed = 6;
$TurnStats_ResourcesLeft = 7;
$TurnStats_CardsLeft = 8;
$TurnStats_DamageBlocked = 9;
$TurnStats_Overblock = 10;
$TurnStats_LifeGained = 11;
$TurnStats_DamagePrevented = 12;
$TurnStats_LifeLost = 13;
$TurnStats_CardsDiscarded = 14;

function &GetTurnCount($player)
{
  global $p1TurnCount, $p2TurnCount;
  if ($player == 1) return $p1TurnCount;
  return $p2TurnCount;
}

function CountAttackingTurns($player)
{
  $count = &GetTurnCount($player);
  return intval($count);
}

function IncrementTurnCount($player)
{
  $count = &GetTurnCount($player);
  $count = intval($count) + 1;
}

function GetStatTurnIndex($player)
{
  global $mainPlayer, $firstPlayer;
  $count = CountAttackingTurns($player);
  if ($player == $mainPlayer) {
    return $player == $firstPlayer ? ($count > 0 ? $count - 1 : 0) : $count;
  }
  if ($player == $firstPlayer) return $count;
  // Turn 0 belongs to the opening turn for both players. Instants, pitches,
  // blocks, and other defensive activity here must not spill into turn 1.
  return $count == 0 ? 0 : $count + 1;
}

function EnsureTurnStatBlock($player)
{
  $turnStats = &GetTurnStats($player);
  $required = (GetStatTurnIndex($player) + 1) * TURN_STAT_PIECES;
  for ($i = count($turnStats); $i < $required; ++$i) $turnStats[] = 0;
}

function AddTurnStat(&$turnStats, $slot, $amount)
{
  $turnStats[$slot] = intval($turnStats[$slot] ?? 0) + intval($amount);
}

function LogPlayCardStats($player, $cardID, $from, $type = "")
{
  global $turn, $CardStats_TimesPlayed, $CardStats_TimesBlocked, $CardStats_TimesPitched,
         $CardStats_TimesHit, $CardStats_TimesCharged, $TurnStats_CardsPlayedOffense,
         $TurnStats_CardsPlayedDefense, $TurnStats_CardsPitched, $TurnStats_CardsBlocked,
         $mainPlayer, $CardStats_TimesKatsuDiscard, $TurnStats_CardsDiscarded,
         $CardStats_TimesDiscarded, $CardStats_TimesActivated, $CardStats_TimesPassiveTriggered, $currentTurn;

  if ($type === "") {
    $type = $turn[0];
  }

  $cardTurnLog   = &GetCardTurnLog($player);
  $cardTurnLog[] = [intval($currentTurn), $cardID, $type];

  $cardStats = &GetCardStats($player);
  $turnStats = &GetTurnStats($player);
  $baseIndex = GetStatTurnIndex($player) * TURN_STAT_PIECES;
  EnsureTurnStatBlock($player);

  $cardStatsCount = count($cardStats);
  $found = false;
  for ($i = 0; $i < $cardStatsCount; $i += CARD_STAT_PIECES) {
    if ($cardStats[$i] === $cardID) { $found = true; break; }
  }
  if (!$found) {
    $cardStats[] = $cardID;
    $cardStats[] = 0; $cardStats[] = 0; $cardStats[] = 0; $cardStats[] = 0;
    $cardStats[] = 0; $cardStats[] = 0; $cardStats[] = 0; $cardStats[] = 0; $cardStats[] = 0;
    // Loop exited with $i === $cardStatsCount, which is the start of the new entry.
  }

  switch ($type) {
    case "P":
      ++$cardStats[$i + $CardStats_TimesPitched];
      ++$turnStats[$baseIndex + $TurnStats_CardsPitched];
      break;
    case "B":
      ++$cardStats[$i + $CardStats_TimesBlocked];
      if ($from !== "PLAY" && $from !== "EQUIP") ++$turnStats[$baseIndex + $TurnStats_CardsBlocked];
      break;
    case "HIT":
      ++$cardStats[$i + $CardStats_TimesHit];
      break;
    case "CHARGE":
      ++$cardStats[$i + $CardStats_TimesCharged];
      break;
    case "KATSUDISCARD":
      ++$cardStats[$i + $CardStats_TimesKatsuDiscard];
      break;
    case "DISCARD":
      ++$cardStats[$i + $CardStats_TimesDiscarded];
      ++$turnStats[$baseIndex + $TurnStats_CardsDiscarded];
      break;
    case "PASSIVE":
      ++$cardStats[$i + $CardStats_TimesPassiveTriggered];
      break;
    default:
      if ($from == "PLAY" || $from == "EQUIP")
      {
        ++$cardStats[$i + $CardStats_TimesActivated];
      }
      else
      {
        ++$cardStats[$i + $CardStats_TimesPlayed];
        $offDefIndex = $baseIndex + ($player === $mainPlayer ? $TurnStats_CardsPlayedOffense : $TurnStats_CardsPlayedDefense);
        if (isset($turnStats[$offDefIndex])) ++$turnStats[$offDefIndex];
      }
      break;
  }
}

function LogResourcesUsedStats($player, $resourcesUsed)
{
  global $TurnStats_ResourcesUsed;
  $turnStats = &GetTurnStats($player);
  $baseIndex = GetStatTurnIndex($player) * TURN_STAT_PIECES;
  EnsureTurnStatBlock($player);
  AddTurnStat($turnStats, $baseIndex + $TurnStats_ResourcesUsed, $resourcesUsed);
}

function LogDamageStats($player, $damageThreatened, $damageDealt)
{
  global $TurnStats_DamageThreatened, $TurnStats_DamageDealt;
  $playerSource = $player == 1 ? 2 : 1;
  $baseIndex    = GetStatTurnIndex($playerSource) * TURN_STAT_PIECES;
  $damagerStats = &GetTurnStats($playerSource);
  //WriteLog("DEBUG: Logging damage for player $playerSource at turn " . (GetStatTurnIndex($playerSource)) . " with damage threatened $damageThreatened and damage dealt $damageDealt", highlight:true, highlightColor:"blue");
  EnsureTurnStatBlock($playerSource);
  AddTurnStat($damagerStats, $baseIndex + $TurnStats_DamageThreatened, $damageThreatened);
  AddTurnStat($damagerStats, $baseIndex + $TurnStats_DamageDealt, $damageDealt);
}

// Called on FinalizeDamage() and $damageDealt is $type == ARCANE only tracks damage dealt to opposing players
function LogArcaneDamageStats($player, $damageDealt)
{
  global $p1ArcaneDamageDealt, $p2ArcaneDamageDealt;
  $playerSource = $player == 1 ? 2 : 1;
  $turnIndex = GetStatTurnIndex($playerSource);
  if ($playerSource == 1) {
    AddTurnStat($p1ArcaneDamageDealt, $turnIndex, $damageDealt);
  } else {
    AddTurnStat($p2ArcaneDamageDealt, $turnIndex, $damageDealt);
  }
}

function LogLifeGainedStats($player, $healthGained)
{
  global $TurnStats_LifeGained;
  $baseIndex   = GetStatTurnIndex($player) * TURN_STAT_PIECES;
  $healerStats = &GetTurnStats($player);
  //WriteLog("DEBUG: Logging life gain for player $player at turn " . (GetStatTurnIndex($player)) . " with health gained $healthGained", highlight:true, highlightColor:"blue");
  EnsureTurnStatBlock($player);
  AddTurnStat($healerStats, $baseIndex + $TurnStats_LifeGained, $healthGained);
}

function LogLifeLossStats($player, $healthLost)
{
  global $TurnStats_LifeLost;
  $baseIndex   = GetStatTurnIndex($player) * TURN_STAT_PIECES;
  $healerStats = &GetTurnStats($player);
  //WriteLog("DEBUG: Logging life loss for player $player at turn " . (GetStatTurnIndex($player)) . " with health lost $healthLost", highlight:true, highlightColor:"blue");
  EnsureTurnStatBlock($player);
  AddTurnStat($healerStats, $baseIndex + $TurnStats_LifeLost, -intval($healthLost));
}

function LogDamagePreventedStats($player, $damagePrevented)
{
  global $TurnStats_DamagePrevented;
  $baseIndex      = GetStatTurnIndex($player) * TURN_STAT_PIECES;
  $preventedStats = &GetTurnStats($player);
  //WriteLog("DEBUG: Logging damage prevented for player $player at turn " . (GetStatTurnIndex($player)) . " with damage prevented $damagePrevented", highlight:true, highlightColor:"blue");
  EnsureTurnStatBlock($player);
  $slot = $baseIndex + $TurnStats_DamagePrevented;
  $preventedStats[$slot] = ($preventedStats[$slot] ?? 0) + (int)$damagePrevented;
}

function LogCombatResolutionStats($damageThreatened, $damageBlocked)
{
  global $mainPlayer, $defPlayer, $TurnStats_DamageThreatened, $TurnStats_DamageBlocked, $TurnStats_Overblock;
  $mainBaseIndex = GetStatTurnIndex($mainPlayer) * TURN_STAT_PIECES;
  $defBaseIndex  = GetStatTurnIndex($defPlayer)  * TURN_STAT_PIECES;
  $mainStats     = &GetTurnStats($mainPlayer);
  $defStats      = &GetTurnStats($defPlayer);
  EnsureTurnStatBlock($mainPlayer);
  EnsureTurnStatBlock($defPlayer);
  //WriteLog("DEBUG: Logging combat resolution stats for turn " . GetStatTurnIndex($mainPlayer) . " Main player, and turn " . GetStatTurnIndex($defPlayer) . " Def player, Damage Threatened: $damageThreatened, Damage Blocked: " . min($damageThreatened, $damageBlocked), highlight:true, highlightColor:"blue");
  $damageThreatened = intval($damageThreatened);
  $damageBlocked    = intval($damageBlocked);
  $capped = $damageThreatened < $damageBlocked ? $damageThreatened : $damageBlocked;
  AddTurnStat($mainStats, $mainBaseIndex + $TurnStats_DamageThreatened, $capped); //Excess is logged in the damage function
  AddTurnStat($defStats, $defBaseIndex + $TurnStats_DamageBlocked, $capped); // If I block 3 on a 2 damage attack, I blocked 2 damage, not 3
  AddTurnStat($defStats, $defBaseIndex + $TurnStats_Overblock, $damageBlocked > $damageThreatened ? $damageBlocked - $damageThreatened : 0);
}

function LogEndTurnStats($player)
{
  global $TurnStats_ResourcesLeft, $TurnStats_CardsLeft;
  $turnStats = &GetTurnStats($player);
  $baseIndex = GetStatTurnIndex($player) * TURN_STAT_PIECES;
  EnsureTurnStatBlock($player);
  $resources = &GetResources($player);
  $turnStats[$baseIndex + $TurnStats_ResourcesLeft] = $resources[0];
  $hand = &GetHand($player);
  $turnStats[$baseIndex + $TurnStats_CardsLeft] = count($hand);
}

function LogEndLifeStats()
{
  global $p1LifeHistory, $p2LifeHistory;
  $p1LifeHistory[GetStatTurnIndex(1)] = GetHealth(1);
  $p2LifeHistory[GetStatTurnIndex(2)] = GetHealth(2);
}

function StatsStartTurn()
{
  EnsureTurnStatBlock(1);
  EnsureTurnStatBlock(2);
}
