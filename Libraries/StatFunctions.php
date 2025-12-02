<?php

//Card ID + each card stat
function CardStatPieces()
{
  return 10;
}

function TurnStatPieces()
{
  return 16;
}

$CardStats_TimesPlayed = 1;
$CardStats_TimesBlocked = 2;
$CardStats_TimesPitched = 3;
$CardStats_TimesHit = 4;
$CardStats_TimesCharged = 5;
$CardStats_TimesDiscarded = 6; 
$CardStats_Dynamic2 = 7; //Reserved for future use
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

function LogPlayCardStats($player, $cardID, $from, $type="")
{
  global $turn, $currentTurn, $CardStats_TimesPlayed, $CardStats_TimesBlocked, $CardStats_TimesPitched, $CardStats_TimesHit, $CardStats_TimesCharged, $TurnStats_CardsPlayedOffense, $TurnStats_CardsPlayedDefense;
  global $TurnStats_CardsPitched, $TurnStats_CardsBlocked, $mainPlayer, $CardStats_TimesKatsuDiscard, $TurnStats_CardsDiscarded, $CardStats_TimesDiscarded;
  if($type == "") $type = $turn[0];
  $cardStats = &GetCardStats($player);
  $turnStats = &GetTurnStats($player);
  $baseIndex = $currentTurn * TurnStatPieces();
  $found = 0;
  $i = 0;
  for($i = 0; $i<count($cardStats) && !$found; $i += CardStatPieces())
  {
    if($cardStats[$i] == $cardID) { $found = 1; $i -= CardStatPieces(); }
  }
  if(!$found) array_push($cardStats, $cardID, 0, 0, 0, 0, 0, 0, 0, 0, 0);
  switch($type)
  {
    case "P": ++$cardStats[$i + $CardStats_TimesPitched]; ++$turnStats[$baseIndex + $TurnStats_CardsPitched]; break;
    case "B": ++$cardStats[$i + $CardStats_TimesBlocked]; if($from != "PLAY" && $from != "EQUIP") ++$turnStats[$baseIndex + $TurnStats_CardsBlocked]; break;
    case "HIT": ++$cardStats[$i + $CardStats_TimesHit]; break;
    case "CHARGE": ++$cardStats[$i + $CardStats_TimesCharged]; break;
    case "KATSUDISCARD":  ++$cardStats[$i + $CardStats_TimesKatsuDiscard]; break;
    case "DISCARD": ++$cardStats[$i + $CardStats_TimesDiscarded]; ++$turnStats[$baseIndex + $TurnStats_CardsDiscarded]; break;
    default:
      if ($from != "PLAY")
      {
        // From "PLAY" means it was already played, don't account for it a second time.
        ++$cardStats[$i + $CardStats_TimesPlayed];
      }
      if($from != "PLAY" && $from != "EQUIP")
      {
        switch ($player) {
          case $mainPlayer:
            ++$turnStats[$baseIndex + $TurnStats_CardsPlayedOffense];
            break;
          default:
            ++$turnStats[$baseIndex + $TurnStats_CardsPlayedDefense];
            break;
        }
      }
      break;
  }
}

function LogResourcesUsedStats($player, $resourcesUsed)
{
  global $currentTurn, $TurnStats_ResourcesUsed;
  $turnStats = &GetTurnStats($player);
  $baseIndex = $currentTurn * TurnStatPieces();
  if(count($turnStats) <= $baseIndex) StatsStartTurn();
  $turnStats[$baseIndex + $TurnStats_ResourcesUsed] += $resourcesUsed;
}

function LogDamageStats($player, $damageThreatened, $damageDealt)
{
  global $currentTurn, $TurnStats_DamageThreatened, $TurnStats_DamageDealt;
  //WriteLog("Logging damage stats for player $player: Threatened $damageThreatened, Dealt $damageDealt", highlight:true);
  $baseIndex = $currentTurn * TurnStatPieces();
  $damagerStats = &GetTurnStats($player == 1 ? 2 : 1);
  if(count($damagerStats) <= $baseIndex) StatsStartTurn();
  $damagerStats[$baseIndex + $TurnStats_DamageThreatened] += $damageThreatened;
  $damagerStats[$baseIndex + $TurnStats_DamageDealt] += $damageDealt;
}

function LogLifeGainedStats($player, $healthGained)
{
  global $currentTurn, $TurnStats_LifeGained;
  $baseIndex = $currentTurn * TurnStatPieces();
  $healerStats = &GetTurnStats($player);
  if(count($healerStats) <= $baseIndex) StatsStartTurn();
  $healerStats[$baseIndex + $TurnStats_LifeGained] += $healthGained;
}

function LogLifeLossStats($player, $healthLost)
{
  global $currentTurn, $TurnStats_LifeLost;
  $baseIndex = $currentTurn * TurnStatPieces();
  $healerStats = &GetTurnStats($player);
  if(count($healerStats) <= $baseIndex) StatsStartTurn();
  $healerStats[$baseIndex + $TurnStats_LifeLost] -= $healthLost;
}

function LogDamagePreventedStats($player, $damagePrevented)
{
  global $currentTurn, $TurnStats_DamagePrevented;
  //WriteLog("Player $player stats added: $damagePrevented damage prevented", highlight:true);
  $baseIndex = $currentTurn * TurnStatPieces();
  $preventedStats = &GetTurnStats($player);
  if(count($preventedStats) <= $baseIndex) StatsStartTurn();
  $preventedStats[$baseIndex + $TurnStats_DamagePrevented] += $damagePrevented;
}

function LogCombatResolutionStats($damageThreatened, $damageBlocked)
{
  global $currentTurn, $mainPlayer, $defPlayer, $TurnStats_DamageThreatened, $TurnStats_DamageBlocked, $TurnStats_Overblock;
  $baseIndex = $currentTurn * TurnStatPieces();
  $mainStats = &GetTurnStats($mainPlayer);
  $defStats = &GetTurnStats($defPlayer);
  if(count($mainStats) <= $baseIndex) StatsStartTurn();
  if(count($defStats) <= $baseIndex) StatsStartTurn();
  $mainStats[$baseIndex + $TurnStats_DamageThreatened] += $damageThreatened > $damageBlocked ? $damageBlocked : $damageThreatened;//Excess is logged in the damage function
  $defStats[$baseIndex + $TurnStats_DamageBlocked] += $damageBlocked;
  $defStats[$baseIndex + $TurnStats_Overblock] += $damageBlocked > $damageThreatened ? $damageBlocked - $damageThreatened : 0;
}

function LogEndTurnStats($player)
{
  global $currentTurn, $TurnStats_ResourcesLeft, $TurnStats_CardsLeft;
  $turnStats = &GetTurnStats($player);
  $baseIndex = $currentTurn * TurnStatPieces();
  if(count($turnStats) <= $baseIndex) StatsStartTurn();
  $resources = &GetResources($player);
  $turnStats[$baseIndex + $TurnStats_ResourcesLeft] = $resources[0];
  $hand = &GetHand($player);
  $turnStats[$baseIndex + $TurnStats_CardsLeft] = count($hand);
}

function StatsStartTurn()
{
  $p1Stats = &GetTurnStats(1);
  $p2Stats = &GetTurnStats(2);
  for($i=0; $i<TurnStatPieces(); ++$i)
  {
    array_push($p1Stats, 0);
    array_push($p2Stats, 0);
  }
}
