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
$CardStats_Dynamic3 = 8; //Reserved for future use
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

function GetStatTurnIndex($player)
{
  global $currentTurn, $mainPlayer, $firstPlayer;
  // If player is the first player (goes first)
  if ($player == $firstPlayer) {
    return $currentTurn;
  }
  
  // If player is NOT the first player (goes second, P2)
  // Turn 0 is special: both players on index 0
  if ($currentTurn == 0) {
    return 0;
  }
  
  // For turns 1+:
  // When P2 IS attacking (is main player): log to current turn index
  // When P2 is NOT attacking (defending): log to next turn index
  // Turn 1: P2 attacks (index 1), P2 blocks later (index 2)
  // Turn 2: P2 attacks (index 2), P2 blocks later (index 3)
  if ($player != $mainPlayer) {
    return $currentTurn + 1;
  }
  
  return $currentTurn;
}

function LogPlayCardStats($player, $cardID, $from, $type = "")
{
  global $turn, $CardStats_TimesPlayed, $CardStats_TimesBlocked, $CardStats_TimesPitched,
         $CardStats_TimesHit, $CardStats_TimesCharged, $TurnStats_CardsPlayedOffense,
         $TurnStats_CardsPlayedDefense, $TurnStats_CardsPitched, $TurnStats_CardsBlocked,
         $mainPlayer, $CardStats_TimesKatsuDiscard, $TurnStats_CardsDiscarded,
         $CardStats_TimesDiscarded, $CardStats_TimesActivated, $currentTurn;

  if ($type === "") $type = $turn[0];

  $cardTurnLog   = &GetCardTurnLog($player);
  $cardTurnLog[] = [intval($currentTurn), $cardID, $type];

  $cardStats = &GetCardStats($player);
  $turnStats = &GetTurnStats($player);
  $baseIndex = GetStatTurnIndex($player) * TURN_STAT_PIECES;
  if (count($turnStats) <= $baseIndex) StatsStartTurn();

  $cardStatsCount = count($cardStats);
  $found = false;
  for ($i = 0; $i < $cardStatsCount; $i += CARD_STAT_PIECES) {
    if ($cardStats[$i] === $cardID) { $found = true; break; }
  }
  if (!$found) {
    array_push($cardStats, $cardID, 0, 0, 0, 0, 0, 0, 0, 0, 0);
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
  if (count($turnStats) <= $baseIndex) StatsStartTurn();
  $turnStats[$baseIndex + $TurnStats_ResourcesUsed] += $resourcesUsed;
}

function LogDamageStats($player, $damageThreatened, $damageDealt)
{
  global $TurnStats_DamageThreatened, $TurnStats_DamageDealt;
  $playerSource = $player == 1 ? 2 : 1;
  $baseIndex    = GetStatTurnIndex($playerSource) * TURN_STAT_PIECES;
  $damagerStats = &GetTurnStats($playerSource);
  //WriteLog("DEBUG: Logging damage for player $playerSource at turn " . (GetStatTurnIndex($playerSource)) . " with damage threatened $damageThreatened and damage dealt $damageDealt", highlight:true, highlightColor:"blue");
  if (count($damagerStats) <= $baseIndex) StatsStartTurn();
  $damagerStats[$baseIndex + $TurnStats_DamageThreatened] += $damageThreatened;
  $damagerStats[$baseIndex + $TurnStats_DamageDealt] += $damageDealt;
}

function LogLifeGainedStats($player, $healthGained)
{
  global $TurnStats_LifeGained;
  $baseIndex   = GetStatTurnIndex($player) * TURN_STAT_PIECES;
  $healerStats = &GetTurnStats($player);
  //WriteLog("DEBUG: Logging life gain for player $player at turn " . (GetStatTurnIndex($player)) . " with health gained $healthGained", highlight:true, highlightColor:"blue");
  if (count($healerStats) <= $baseIndex) StatsStartTurn();
  $healerStats[$baseIndex + $TurnStats_LifeGained] += $healthGained;
}

function LogLifeLossStats($player, $healthLost)
{
  global $TurnStats_LifeLost;
  $baseIndex   = GetStatTurnIndex($player) * TURN_STAT_PIECES;
  $healerStats = &GetTurnStats($player);
  //WriteLog("DEBUG: Logging life loss for player $player at turn " . (GetStatTurnIndex($player)) . " with health lost $healthLost", highlight:true, highlightColor:"blue");
  if (count($healerStats) <= $baseIndex) StatsStartTurn();
  $healerStats[$baseIndex + $TurnStats_LifeLost] -= $healthLost;
}

function LogDamagePreventedStats($player, $damagePrevented)
{
  global $TurnStats_DamagePrevented;
  $baseIndex      = GetStatTurnIndex($player) * TURN_STAT_PIECES;
  $preventedStats = &GetTurnStats($player);
  //WriteLog("DEBUG: Logging damage prevented for player $player at turn " . (GetStatTurnIndex($player)) . " with damage prevented $damagePrevented", highlight:true, highlightColor:"blue");
  if (count($preventedStats) <= $baseIndex) StatsStartTurn();
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
  if (count($mainStats) <= $mainBaseIndex) StatsStartTurn();
  if (count($defStats)  <= $defBaseIndex)  StatsStartTurn();
  //WriteLog("DEBUG: Logging combat resolution stats for turn " . GetStatTurnIndex($mainPlayer) . " Main player, and turn " . GetStatTurnIndex($defPlayer) . " Def player, Damage Threatened: $damageThreatened, Damage Blocked: " . min($damageThreatened, $damageBlocked), highlight:true, highlightColor:"blue");
  $capped = $damageThreatened < $damageBlocked ? $damageThreatened : $damageBlocked;
  $mainStats[$mainBaseIndex + $TurnStats_DamageThreatened] += $capped; //Excess is logged in the damage function
  $defStats[$defBaseIndex   + $TurnStats_DamageBlocked]    += $capped; // If I block 3 on a 2 damage attack, I blocked 2 damage, not 3
  $defStats[$defBaseIndex   + $TurnStats_Overblock]        += $damageBlocked > $damageThreatened ? $damageBlocked - $damageThreatened : 0;
}

function LogEndTurnStats($player)
{
  global $TurnStats_ResourcesLeft, $TurnStats_CardsLeft;
  $turnStats = &GetTurnStats($player);
  $baseIndex = GetStatTurnIndex($player) * TURN_STAT_PIECES;
  if (count($turnStats) <= $baseIndex) StatsStartTurn();
  $resources = &GetResources($player);
  $turnStats[$baseIndex + $TurnStats_ResourcesLeft] = $resources[0];
  $hand = &GetHand($player);
  $turnStats[$baseIndex + $TurnStats_CardsLeft] = count($hand);
}

function LogEndLifeStats()
{
  global $p1LifeHistory, $p2LifeHistory, $currentTurn;
  $p1LifeHistory[$currentTurn] = GetHealth(1);
  $p2LifeHistory[$currentTurn] = GetHealth(2);
}

function StatsStartTurn()
{
  $p1Stats = &GetTurnStats(1);
  $p2Stats = &GetTurnStats(2);
  array_push($p1Stats, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
  array_push($p2Stats, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
}
