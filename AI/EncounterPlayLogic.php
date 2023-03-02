<?php

//Every single function with the exception of the PlayCardAttempt() functions at the bottom of this file are entirely used to make sure the card/ability the AI wants to play is actually legal before it attempts anything.
//This might be a bit to dig through, but you don't really need to understand any of this to create priority arrays.
//If you do have questions though, just ping me, @Etasus, and I can help you out.

function CardIsBlockable($storedPriorityNode)
{
  global $combatChain, $combatChainState, $CCS_NumChainLinks;
  switch($combatChain[0])
  {
    case "CRU054": return !(ComboActive() && CardCost($storedPriorityNode[0]) < $combatChainState[$CCS_NumChainLinks]);
    case "CRU056": return false; //I have no idea how to make Heron's Flight work, so I'm just gonna say it's unblockable. This is so edge case that no one will know for a while lmfaooooo
    case "CRU057":
    case "CRU058":
    case "CRU059": return !(ComboActive() && AttackValue($storedPriorityNode[0]) > $combatChainState[$CCS_NumChainLinks]);
    default: return true;
  }
}

function CardIsPlayable($storedPriorityNode, $hand, $resources)
{
  if(CardIsPrevented($storedPriorityNode[0])) return false;
  switch($storedPriorityNode[1])
  {
    case "Hand":
      $index = $storedPriorityNode[2];
      $baseCost = CardCost($storedPriorityNode[0]);
      break;
    case "Arsenal":
      if(ArsenalIsFrozen($storedPriorityNode)) return false;
      $index = -1;
      $baseCost = CardCost($storedPriorityNode[0]);
      break;
    case "Character":
      if(CharacterIsUsed($storedPriorityNode)) return false;
      $index = -1;
      $baseCost = AbilityCost($storedPriorityNode[0]);
      break;
    case "Item":
      $index = -1;
      $baseCost = AbilityCost($storedPriorityNode[0]);
      break;
    case "Ally":
      $index = -1;
      $baseCost = AbilityCost($storedPriorityNode[0]);
      break;
    default:
      WriteLog("ERROR: AI is storedPriorityNode an uncheckable card for playability. Please log a bug report.");
      return false;
  }
  $finalCost = $baseCost + RogSelfCostMod($storedPriorityNode[0]) + RogCharacterCostMod($storedPriorityNode[0]) + RogAuraCostMod($storedPriorityNode[0]) + RogEffectCostMod($storedPriorityNode[0]);
  $totalPitch = $resources[0];
  for($i = 0; $i < count($hand); ++$i)
  {
    if($i != $index) $totalPitch = $totalPitch + PitchValue($hand[$i]);
  }
  return $finalCost <= $totalPitch;
}

function ReactionCardIsPlayable($storedPriorityNode, $hand, $resources)
{
  return CardIsPlayable($storedPriorityNode, $hand, $resources) && ReactionRequirementsMet($storedPriorityNode);
}

function CardIsPitchable($storedPriorityNode)
{
  global $currentTurnEffects, $currentPlayer, $CS_PlayUniqueID, $turn;
  for ($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "ELE035": return CardCost($storedPriorityNode[0]) != 0;
        default:
          break;
      }
    }
  }
  return true;
}

function CardIsArsenalable($storedPriorityNode)
{
  return true; //currently there are no cards in the game that prevent something from being arsenaled. When there is, this is an easy entry point to set it up.
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
  $from = "-"; //I currently don't want to figure out how "from" works, so I'm just gonna do this and hope for the best. If something breaks, we'll fix it.
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
        case "CRU034": return AttackValue($cardID) <= 3;
        case "ELE035": return CardCost($cardID) == 0;
        default:
          break;
      }
    }
  }
  return false;
}

function CharacterIsUsed($storedPriorityNode)
{
  global $currentPlayer;
  $character = &GetPlayerCharacter($currentPlayer);
  if($character[$storedPriorityNode[2]+5] < 1 || $character[$storedPriorityNode[2]+1] != 2) return true;
  else return false;
}

function ArsenalIsFrozen($storedPriorityNode)
{
  global $currentPlayer;
  $arsenal = &GetArsenal($currentPlayer);
  if($arsenal[$storedPriorityNode[2]+4] == 1) return true;
  else return false;
}

function ReactionRequirementsMet($storedPriorityNode)
{
  global $combatChain, $combatChainState, $CCS_NumChainLinks, $mainPlayer, $currentPlayer, $CS_NumNonAttackCards, $CS_AtksWWeapon, $CS_NumFusedIce, $CS_NumFusedEarth, $CS_NumFusedLightning;
  switch($storedPriorityNode[0])
  {
    case "WTR080": return HasCombo($combatChain[0]);
    case "WTR082": return CardType($combatChain[0]) == "AA" && ClassContains($combatChain[0], "NINJA", $mainPlayer);
    case "WTR118":
    case "WTR120":
    case "WTR121":
    case "WTR123": case "WTR124": case "WTR125":
    case "WTR132": case "WTR133": case "WTR134":
    case "WTR135": case "WTR136": case "WTR137":
    case "WTR138": case "WTR139": case "WTR140": return CardType($combatChain[0]) == "W";
    case "WTR154": return CardType($combatChain[0]) == "AA" && CardCost($CombatChain[0]) <= 1;
    case "WTR206": case "WTR207": case "WTR208": return CardSubtype($combatChain[0]) == "Club" || CardSubtype($combatChain[0]) == "Hammer" || (CardType($combatChain[0]) == "AA" && CardCost($combatChain[0]) >= 2);
    case "WTR209": case "WTR210": case "WTR211": return CardSubtype($combatChain[0]) == "Sword" || CardSubtype($combatChain[0]) == "Dagger" || (CardType($combatChain[0]) == "AA" && CardCost($combatChain[0]) <= 1);
    case "CRU082": return CardSubtype($combatChain[0]) == "Sword";
    case "CRU083":
    case "CRU088": case "CRU089": case "CRU090": return CardType($combatChain[0]) == "W";
    case "CRU186": return CardType($combatChain[0]) == "AA";
    case "MON057": case "MON058": case "MON059": return true;
    case "ELE225": return CardType($combatChain[0]) == "AA" && GetClassState($currentPlayer, $CS_NumNonAttackCards) > 0;
    case "EVR054": return CardType($combatChain[0]) == "W" && !Is1H($combatChain[0]);
    case "EVR060": case "EVR061": case "EVR062": return CardType($combatChain[0]) == "W" && Is1H($combatChain[0]);
    case "EVR063": case "EVR064": case "EVR065": return GetClassState($currentPlayer, $CS_AtksWWeapon) >= 1;
    case "DVR013":
    case "DVR014":
    case "DVR023": return CardSubtype($combatChain[0]) == "Sword";
    case "UPR050": return CardType($combatChain[0]) == "AA" && (ClassContains($combatChain[0], "NINJA", $mainPlayer) || TalentContains($combatChain[0], "DRACONIC", $mainPlayer));
    case "UPR087": return CardType($combatChain[0]) == "AA";
    case "UPR159": return CardType($combatChain[0]) == "AA" && AttackValue($combatChain[0]) <= 2;
    case "UPR162": case "UPR163": case "UPR164": return CardType($combatChain[0]) == "AA" && CardCost($combatChain[0]) == 0;
    case "DYN079": case "DYN080": case "DYN081": return CardSubtype($combatChain[0]) == "Sword" || CardSubtype($combatChain[0]) == "Dagger";
    case "DYN117": case "DYN118": return ClassContains($combatChain[0], "ASSASSIN", $mainPlayer) && CardType($combatChain[0]) == "AA";
    case "DYN148": case "DYN149": case "DYN150": return ClassContains($combatChain[0], "ASSASSIN", $mainPlayer) && CardType($combatChain[0]) == "AA" && ContractType($combatChain[0]) != "";
    case "ELE143": return GetClassState($player, $CS_NumFusedEarth) > 0;
    case "ELE172": return GetClassState($player, $CS_NumFusedIce) > 0;
    case "ELE183": case "ELE184": case "ELE185": return CardType($combatChain[0]) == "AA" && CardCost($combatChain[0]) <= 1;
    case "ELE201": return GetClassState($player, $CS_NumFusedLightning) > 0;
    default: return false;
  }
}


//This is just a way for me to seperate the actual act of playing a card from the main AI function block. They basically just check where the card is being played from, and then make the relevant inputs.
function BlockCardAttempt($storedPriorityNode)
{
  global $currentPlayer;
  switch($storedPriorityNode[1])
  {
    case "Hand":
      ProcessInput($currentPlayer, 27, "", $storedPriorityNode[2], 0, "");
      CacheCombatResult();
      break;
    case "Character":
      ProcessInput($currentPlayer, 3, "", $storedPriorityNode[2], 0, "");
      CacheCombatResult();
      break;
    default: WriteLog("ERROR: AI attempting to block with an unblockable card. Please log a bug report."); break;
  }
}

function PlayCardAttempt($storedPriorityNode)
{
  global $currentPlayer;
  switch($storedPriorityNode[1])
  {
    case "Hand":
      ProcessInput($currentPlayer, 27, "", $storedPriorityNode[2], 0, "");
      CacheCombatResult();
      break;
    case "Arsenal":
      ProcessInput($currentPlayer, 5, "", $storedPriorityNode[2], 0, "");
      CacheCombatResult();
      break;
    case "Character":
      ProcessInput($currentPlayer, 3, "", $storedPriorityNode[2], 0, "");
      CacheCombatResult();
      break;
    case "Item":
      ProcessInput($currentPlayer, 10, "", $storedPriorityNode[2], 0, "");
      CacheCombatResult();
      break;
    case "Ally":
      ProcessInput($currentPlayer, 24, "", $storedPriorityNode[2], 0, "");
      CacheCombatResult();
      break;
    default: WriteLog("ERROR: AI attempting to play an unplayable card. Please log a bug report."); PassInput(); break;
  }
}

function PitchCardAttempt($storedPriorityNode)
{
  global $currentPlayer;
  switch($storedPriorityNode[1])
  {
    case "Hand":
      ProcessInput($currentPlayer, 27, "", $storedPriorityNode[2], 0, "");
      CacheCombatResult();
      break;
    default: WriteLog("ERROR: AI attempting to pitch an unpitchable card. Please log a bug report."); break;
  }
}

function ArsenalCardAttempt($storedPriorityNode)
{
  global $currentPlayer;
  switch($storedPriorityNode[1])
  {
    case "Hand":
      ProcessInput($currentPlayer, 4, "", $storedPriorityNode[0], 0, "");
      CacheCombatResult();
      break;
    default: WriteLog("ERROR: AI attempting to arsenal an unarsenalable card. Please log a bug report."); break;
  }
}
?>
