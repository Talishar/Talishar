<?php

function CardIsBlockable($checking)
{
  global $combatChain, $combatChainState, $CCS_NumChainLinks;
  switch($combatChain[0])
  {
    case "CRU054": return !(ComboActive() == 1 && CardCost($checking[0]) < $combatChainState[$CCS_NumChainLinks]);
    case "CRU056": return false; //I have no idea how to make Heron's Flight work, so I'm just gonna say it's unblockable. This is so edge case that no one will know for a while lmfaooooo
    case "CRU057":
    case "CRU058":
    case "CRU059": return !(ComboActive() == 1 && AttackValue($checking[0]) > $combatChainState[$CCS_NumChainLinks]);
    default: return true;
  }
}

function CardIsPlayable($checking, $hand, $resources)
{
  if(CardIsPrevented($checking[0])) return false;
  switch($checking[1])
  {
    case "Hand":
      $index = $checking[2];
      $baseCost = CardCost($checking[0]);
      break;
    case "Arsenal":
      if(ArsenalIsFrozen($checking)) return false;
      $index = -1;
      $baseCost = CardCost($checking[0]);
      break;
    case "Character":
      if(CharacterIsUsed($checking)) return false;
      $index = -1;
      $baseCost = AbilityCost($checking[0]);
      break;
    default:
      WriteLog("ERROR: AI is checking an uncheckable card for playability. Please submit a bug report.");
      return false;
  }
  $finalCost = $baseCost + RogSelfCostMod($checking[0]) + RogCharacterCostMod($checking[0]) + RogAuraCostMod($checking[0]) + RogEffectCostMod($checking[0]);
  for($i = 0; $i < count($hand); ++$i)
  {
    if($i != $index) $totalPitch = $totalPitch + PitchValue($hand[$i]);
  }
  return $finalCost <= $totalPitch;
}

function RogSelfCostMod($cardID)
{
  global $CS_NumCharged, $currentPlayer, $combatChain, $CS_LayerTarget;
  switch ($cardID) {
    case "ARC080":
      return (-1 * NumRunechants($currentPlayer));
    case "ARC082":
      return (-1 * NumRunechants($currentPlayer));
    case "ARC088":
    case "ARC089":
    case "ARC090":
      return (-1 * NumRunechants($currentPlayer));
    case "ARC094":
    case "ARC095":
    case "ARC096":
      return (-1 * NumRunechants($currentPlayer));
    case "ARC097":
    case "ARC098":
    case "ARC099":
      return (-1 * NumRunechants($currentPlayer));
    case "ARC100":
    case "ARC101":
    case "ARC102":
      return (-1 * NumRunechants($currentPlayer));
    case "MON032":
      return (-1 * (2 * GetClassState($currentPlayer, $CS_NumCharged)));
    case "MON084":
    case "MON085":
    case "MON086":
      return TalentContains($combatChain[GetClassState($currentPlayer, $CS_LayerTarget)], "SHADOW") ? -1 : 0;
    case "DYN104":
    case "DYN105":
    case "DYN106":
      $numHypers = 0;
      $numHypers += CountItem("ARC036", $currentPlayer);
      $numHypers += CountItem("DYN111", $currentPlayer);
      $numHypers += CountItem("DYN112", $currentPlayer);
      return $numHypers > 0 ? -1 : 0;
    default:
      return 0;
  }
}

function RogCharacterCostMod($cardID) //this currently serves no purpose except to give us an entry point for future effects as we make them, like Kassai.
{
  global $currentPlayer;
  $modifier = 0;
  return $modifier;
}

function RogAuraCostMod($cardID)
{
  global $currentPlayer;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $myAuras = &GetAuras($currentPlayer);
  $theirAuras = &GetAuras($otherPlayer);
  $modifier = 0;
  for ($i = count($myAuras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    switch ($myAuras[$i]) {
      case "ELE111":
        $modifier += 1;
        break;
      default:
        break;
    }
  }

  for ($i = count($theirAuras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    switch ($theirAuras[$i]) {
      case "ELE146":
        $modifier += 1;
        break;
      default:
        break;
    }
  }
  return $modifier;
}

function RogEffectCostMod($cardID)
{
  global $currentTurnEffects, $currentPlayer, $CS_PlayUniqueID;
  $from = "-"; //I currently don'e want to figure out how "from" works, so I'm just gonna do this and hope for the best. If something breaks, we'll fix it.
  $costModifier = 0;
  for ($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "WTR060":
        case "WTR061":
        case "WTR062":
          if (IsAction($cardID)) $costModifier += 1;
          break;
        case "WTR075":
          if (ClassContains($cardID, "GUARDIAN", $currentPlayer) && CardType($cardID) == "AA") $costModifier -= 1;
          break;
        case "WTR152":
          if (CardType($cardID) == "AA") $costModifier -= 2;
          break;
        case "CRU081":
          if (CardType($cardID) == "W" && CardSubType($cardID) == "Sword") $costModifier -= 1;
          break;
        case "CRU085-2":
        case "CRU086-2":
        case "CRU087-2":
          if (CardType($cardID) == "DR") $costModifier += 1;
          break;
        case "CRU141-AA":
          if (CardType($cardID) == "AA") $costModifier -= CountAura("ARC112", $currentPlayer);
          break;
        case "CRU141-NAA":
          if (CardType($cardID) == "A") $costModifier -= CountAura("ARC112", $currentPlayer);
          break;
        case "ARC060":
        case "ARC061":
        case "ARC062":
          if (CardType($cardID) == "AA" || GetAbilityType($cardID, -1, $from) == "AA") $costModifier += 1;
          break;
        case "ELE035-1":
          $costModifier += 1;
          break;
        case "ELE038":
        case "ELE039":
        case "ELE040":
          $costModifier += 1;
          break;
        case "ELE144":
          $costModifier += 1;
          break;
        case "EVR179":
          if (IsStaticType(CardType($cardID), $from, $cardID)) $costModifier -= 1;
          break;
        case "UPR000":
          if (TalentContains($cardID, "DRACONIC", $currentPlayer) && $from != "PLAY" && $from != "EQUIP") $costModifier -= 1;
          break;
        case "UPR075":
        case "UPR076":
        case "UPR077":
          if (GetClassState($currentPlayer, $CS_PlayUniqueID) == $currentTurnEffects[$i + 2]) --$costModifier;
          break;
        case "UPR166":
          if (IsStaticType(CardType($cardID), $from, $cardID) && DelimStringContains(CardSubType($cardID), "Staff")) $costModifier -= 3;
          break;
        case "ROGUE519":
          if (IsStaticType(CardType($cardID), $from, $cardID)) $costModifier -= 1;
          break;
        default:
          break;
      }
      if ($remove == 1) RemoveCurrentTurnEffect($i);
    }
  }
  return $costModifier;
}

function CardIsPrevented($cardID)
{
  global $currentTurnEffects, $currentPlayer, $CS_PlayUniqueID, $turn;
  for ($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "CRU032":
        case "CRU033":
        case "CRU034": return AttackValue($cardID) <= 3 && $turn[0] = "M";
        case "ELE035": return CardCost($cardID) == 0 && ($turn[0] = "M" || $turn[0] = "P");
        default:
          break;
      }
    }
  }
}

function CharacterIsUsed($checking)
{
  global $currentPlayer;
  $character = &GetPlayerCharacter($currentPlayer);
  if($character[$checking[2]+5] < 1 || $character[$checking[2]+1] != 2) return true;
  else return false;
}

function ArsenalIsFrozen($checking)
{
  global $currentPlayer;
  $arsenal = &GetArsenal($currentPlayer);
  if($arsenal[$checking[2]+4] == 1) return true;
  else return false;
}

function BlockCardAttempt($attempt)
{
  global $currentPlayer;
  switch($attempt[1])
  {
    case "Hand":
      ProcessInput($currentPlayer, 27, "", $attempt[2], 0, "");
      CacheCombatResult();
      break;
    case "Character":
      ProcessInput($currentPlayer, 3, "", $attempt[2], 0, "");
      CacheCombatResult();
      break;
    default: WriteLog("ERROR: AI attempting to block with an unblockable card. Please log a bug report."); break;
  }
}

function PlayCardAttempt($attempt)
{
  global $currentPlayer;
  switch($attempt[1])
  {
    case "Hand":
      ProcessInput($currentPlayer, 27, "", $attempt[2], 0, "");
      CacheCombatResult();
      break;
    case "Arsenal":
      ProcessInput($currentPlayer, 5, "", $attempt[2], 0, "");
      CacheCombatResult();
      break;
    case "Character":
      ProcessInput($currentPlayer, 3, "", $attempt[2], 0, "");
      CacheCombatResult();
      break;
    default: WriteLog("ERROR: AI attempting to play an unplayable card. Please log a bug report."); break;
  }
}
?>
