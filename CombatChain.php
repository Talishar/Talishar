<?php

function ProcessHitEffect($cardID)
{
  WriteLog("Processing hit effect for " . CardLink($cardID, $cardID));
  global $combatChainState, $CCS_ChainLinkHitEffectsPrevented, $currentPlayer, $combatChain;
  if(CardType($combatChain[0]) && (SearchAuras("CRU028", 1) || SearchAuras("CRU028", 2))) return;
  if($combatChainState[$CCS_ChainLinkHitEffectsPrevented]) return;
  $set = CardSet($cardID);
  $class = CardClass($cardID);

  if($cardID == "CRU097") {
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    $otherCharacter = &GetPlayerCharacter($otherPlayer);
    if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer)) {
      return ProcessHitEffect($otherCharacter[0]);
    }
  }
  if($set == "WTR") return WTRHitEffect($cardID);
  else if($set == "ARC") {
    switch ($class) {
      case "MECHANOLOGIST": return ARCMechanologistHitEffect($cardID);
      case "RANGER": return ARCRangerHitEffect($cardID);
      case "RUNEBLADE": return ARCRunebladeHitEffect($cardID);
      case "WIZARD": return ARCWizardHitEffect($cardID);
      case "GENERIC": return ARCGenericHitEffect($cardID);
    }
  }
  else if($set == "CRU") return CRUHitEffect($cardID);
  else if ($set == "MON") {
    switch ($class) {
      case "BRUTE": return MONBruteHitEffect($cardID);
      case "ILLUSIONIST": return MONIllusionistHitEffect($cardID);
      case "RUNEBLADE": return MONRunebladeHitEffect($cardID);
      case "WARRIOR": return MONWarriorHitEffect($cardID);
      case "GENERIC": return MONGenericHitEffect($cardID);
      case "NONE": return MONTalentHitEffect($cardID);
      default: return "";
    }
  }
  else if($set == "ELE") {
    switch ($class) {
      case "GUARDIAN": return ELEGuardianHitEffect($cardID);
      case "RANGER": return ELERangerHitEffect($cardID);
      case "RUNEBLADE": return ELERunebladeHitEffect($cardID);
      default: return ELETalentHitEffect($cardID);
    }
  }
  else if ($set == "EVR") return EVRHitEffect($cardID);
  else if ($set == "UPR") return UPRHitEffect($cardID);
  else if ($set == "DYN") return DYNHitEffect($cardID);
  else if ($set == "OUT") return OUTHitEffect($cardID);
}

function AttackModifier($cardID, $from = "", $resourcesPaid = 0, $repriseActive = -1)
{
  global $mainPlayer, $mainPitch, $CS_Num6PowDisc, $combatChain, $combatChainState, $mainAuras, $CS_CardsBanished;
  global $CS_NumCharged, $CCS_NumBoosted, $defPlayer, $CS_ArcaneDamageTaken;
  global $CS_NumNonAttackCards, $CS_NumPlayedFromBanish, $CS_NumAuras, $CS_AtksWWeapon;
  if($repriseActive == -1) $repriseActive = RepriseActive();
  switch($cardID) {
    case "WTR003": return (GetClassState($mainPlayer, $CS_Num6PowDisc) > 0 ? 1 : 0);
    case "WTR040":
      $pitch = &GetPitch($mainPlayer);
      return CountPitch($pitch, 3) >= 2 ? 2 : 0;
    case "WTR080": return 1;
    case "WTR081": return (ComboActive() ? $resourcesPaid : 0);
    case "WTR082": return 1;
    case "WTR083": return (ComboActive() ? 1 : 0);
    case "WTR084": return (ComboActive() ? 1 : 0);
    case "WTR086": case "WTR087": case "WTR088": return (ComboActive() ? NumAttacksHit() : 0);
    case "WTR089": case "WTR090": case "WTR091": return (ComboActive() ? 3 : 0);
    case "WTR095": case "WTR096": case "WTR097": return (ComboActive() ? 1 : 0);
    case "WTR104": case "WTR105": case "WTR106": return (ComboActive() ? 2 : 0);
    case "WTR110": case "WTR111": case "WTR112": return (ComboActive() ? 1 : 0);
    case "WTR120": return 3;
    case "WTR121": return 1;
    case "WTR123": return $repriseActive ? 6 : 4;
    case "WTR124": return $repriseActive ? 5 : 3;
    case "WTR125": return $repriseActive ? 4 : 2;
    case "WTR132": return CardType($combatChain[0]) == "W" && $repriseActive ? 3 : 0;
    case "WTR133": return CardType($combatChain[0]) == "W" && $repriseActive ? 2 : 0;
    case "WTR134": return CardType($combatChain[0]) == "W" && $repriseActive ? 1 : 0;
    case "WTR135": return 3;
    case "WTR136": return 2;
    case "WTR137": return 1;
    case "WTR138": return 3;
    case "WTR139": return 2;
    case "WTR140": return 1;
    case "WTR176":case "WTR177":case "WTR178": return NumCardsNonEquipBlocking() < 2 ? 1 : 0;
    case "WTR206": return 4;
    case "WTR207": return 3;
    case "WTR208": return 2;
    case "WTR209": return 3;
    case "WTR210": return 2;
    case "WTR211": return 1;
    case "ARC077": return GetClassState($mainPlayer, $CS_NumNonAttackCards) > 0 ? 3 : 0;
    case "ARC188": case "ARC189": case "ARC190": return HitsInRow() > 0 ? 2 : 0;
    case "CRU016": case "CRU017": case "CRU018": return GetClassState($mainPlayer, $CS_Num6PowDisc) > 0 ? 1 : 0;
    case "CRU056": return ComboActive() ? 2 : 0;
    case "CRU057": case "CRU058": case "CRU059": return ComboActive() ? 1 : 0;
    case "CRU060": case "CRU061": case "CRU062": return ComboActive() ? 1 : 0;
    case "CRU063": case "CRU064": case "CRU065": return NumChainLinks() >= 3 ? 2 : 0;
    case "CRU073": return NumAttacksHit();
    case "CRU083": return 3;
    case "CRU112": case "CRU113": case "CRU114": return $combatChainState[$CCS_NumBoosted];
    case "CRU186": return 1;
    case "MON031": return GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 3 : 0;
    case "MON039": case "MON040": case "MON041": return GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 3 : 0;
    case "MON057": return GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 3 : 0;
    case "MON058": return GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 2 : 0;
    case "MON059": return GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 1 : 0;
    case "MON155": return GetClassState($mainPlayer, $CS_NumPlayedFromBanish) > 0 ? 2 : 0;
    case "MON171": case "MON172": case "MON173": return GetClassState($defPlayer, $CS_ArcaneDamageTaken) > 0 ? 2 : 0;
    case "MON254": case "MON255": case "MON256": return GetClassState($mainPlayer, $CS_CardsBanished) > 0 ? 2 : 0;
    case "MON284": case "MON285": case "MON286": return NumCardsNonEquipBlocking() < 2 ? 1 : 0;
    case "MON287": case "MON288": case "MON289": return NumCardsNonEquipBlocking();
    case "MON290": case "MON291": case "MON292": return count($mainAuras) >= 1 ? 1 : 0;
    case "ELE082": case "ELE083": case "ELE084": return GetClassState($defPlayer,  $CS_ArcaneDamageTaken) >= 1 ? 2 : 0;
    case "ELE134": case "ELE135": case "ELE136": return $from == "ARS" ? 1 : 0;
    case "ELE202":
      $pitch = &GetPitch($mainPlayer);
      return CountPitch($pitch, 3) >= 1 ? 1 : 0;
    case "EVR038": return (ComboActive() ? 3 : 0);
    case "EVR040": return (ComboActive() ? 2 : 0);
    case "EVR041": case "EVR042": case "EVR043": return (ComboActive() ? CountCardOnChain("EVR041", "EVR042", "EVR043") : 0);
    case "EVR063": return 3;
    case "EVR064": return 2;
    case "EVR065": return 1;
    case "EVR105": return (GetClassState($mainPlayer, $CS_NumAuras) >= 2 ? 1 : 0);
    case "EVR116": case "EVR117": case "EVR118": return (GetClassState($mainPlayer, $CS_NumAuras) > 0 ? 3 : 0);
    case "DVR002": return GetClassState($mainPlayer, $CS_AtksWWeapon) >= 1 ? 1 : 0;
    case "RVD009": return IntimidateCount($mainPlayer) > 0 ? 2 : 0;
    case "UPR048": return (NumPhoenixFlameChainLinks() >= 2 ? 2 : 0);
    case "UPR050": return 1;
    case "UPR098": return (RuptureActive() ? 3 : 0);
    case "UPR101": return (NumDraconicChainLinks() >= 2 ? 1 : 0);
    case "UPR162": return 3;
    case "UPR163": return 2;
    case "UPR164": return 1;
    case "DYN047": return (ComboActive() ? 2 : 0);
    case "DYN056": case "DYN057": case "DYN058": return (ComboActive() ? 1 : 0);
    case "DYN059": case "DYN060": case "DYN061": return (ComboActive() ? 4 : 0);
    case "DYN079": return 3 + (NumEquipBlock() > 0 ? 1 : 0);
    case "DYN080": return 2 + (NumEquipBlock() > 0 ? 1 : 0);
    case "DYN081": return 1 + (NumEquipBlock() > 0 ? 1 : 0);
    case "DYN115": case "DYN116": return NumEquipBlock() > 0 ? 1 : 0;
    case "DYN148": return 3;
    case "DYN149": return 2;
    case "DYN150": return 1;
    case "OUT005": case "OUT006": return NumEquipBlock() > 0 ? 1 : 0;
    case "OUT007": case "OUT008": return NumEquipBlock() > 0 ? 1 : 0;
    case "OUT009": case "OUT010": return NumEquipBlock() > 0 ? 1 : 0;
    case "OUT018": case "OUT019": case "OUT020": return (NumAttackReactionsPlayed() > 0 ? 4 : 0);
    case "OUT021": case "OUT022": case "OUT023": return 3;
    case "OUT042": return 3;
    case "OUT043": return 2;
    case "OUT044": return 1;
    case "OUT051": return (ComboActive() ? 2 : 0);
    case "OUT054": return 1;
    case "OUT062": case "OUT063": case "OUT064": return (ComboActive() ? 1 : 0);
    case "OUT074": case "OUT075": case "OUT076": return (ComboActive() ? 2 : 0);
    case "OUT133": case "OUT134": case "OUT135": return NumCardsDefended() < 2 ? 3 : 0;
    case "OUT154": return 3;
    case "OUT155": return 2;
    case "OUT156": return 1;
    case "OUT181": return 1;
    case "OUT207": case "OUT208": case "OUT209": return (NumActionsBlocking() > 0 ? 2 : 0);
    case "OUT210": case "OUT211": case "OUT212": return (NumActionsBlocking() > 0 ? -2 : 0);
    default: return 0;
  }
}

function BlockModifier($cardID, $from, $resourcesPaid)
{
  global $defPlayer, $CS_CardsBanished, $mainPlayer, $CS_ArcaneDamageTaken, $combatChain, $chainLinks;
  $blockModifier = 0;
  $cardType = CardType($cardID);
  if($cardType == "AA") $blockModifier += CountCurrentTurnEffects("ARC160-1", $defPlayer);
  if($cardType == "AA") $blockModifier += CountCurrentTurnEffects("EVR186", $defPlayer);
  if($cardType == "E" && (SearchCurrentTurnEffects("DYN095", $mainPlayer) || SearchCurrentTurnEffects("DYN096", $mainPlayer) || SearchCurrentTurnEffects("DYN097", $mainPlayer))) $blockModifier -= 1;
  if(SearchCurrentTurnEffects("ELE114", $defPlayer) && ($cardType == "AA" || $cardType == "A") && (TalentContains($cardID, "ICE", $defPlayer) || TalentContains($cardID, "EARTH", $defPlayer) || TalentContains($cardID, "ELEMENTAL", $defPlayer))) $blockModifier += 1;
  $defAuras = &GetAuras($defPlayer);
  for($i = 0; $i < count($defAuras); $i += AuraPieces()) {
    if($defAuras[$i] == "WTR072" && CardCost($cardID) >= 3) $blockModifier += 4;
    if($defAuras[$i] == "WTR073" && CardCost($cardID) >= 3) $blockModifier += 3;
    if($defAuras[$i] == "WTR074" && CardCost($cardID) >= 3) $blockModifier += 2;
    if($defAuras[$i] == "WTR046" && $cardType == "E") $blockModifier += 1;
    if($defAuras[$i] == "ELE109" && $cardType == "A") $blockModifier += 1;
  }
  switch($cardID) {
    case "WTR212": case "WTR213": case "WTR214":
      $blockModifier += $from == "ARS" ? 1 : 0;
      break;
    case "WTR051": case "WTR052": case "WTR053":
      $blockModifier += ($resourcesPaid >= 6 ? 3 : 0);
      break;
    case "ARC150":
      $blockModifier += (DefHasLessHealth() ? 1 : 0);
      break;
    case "CRU187":
      $blockModifier += ($from == "ARS" ? 2 : 0);
      break;
    case "MON075": case "MON076": case "MON077":
      return GetClassState($mainPlayer, $CS_CardsBanished) >= 3 ? 2 : 0;
    case "MON290": case "MON291": case "MON292":
      return count($defAuras) >= 1 ? 1 : 0;
    case "ELE227": case "ELE228": case "ELE229":
      return GetClassState($mainPlayer, $CS_ArcaneDamageTaken) > 0 ? 1 : 0;
    case "EVR050": case "EVR051": case "EVR052":
      return (CardCost($combatChain[0]) == 0 && CardType($combatChain[0]) == "AA" ? 2 : 0);
    case "DYN045":
      $blockModifier += (count($chainLinks) >= 3 ? 4 : 0);
      break;
    case "DYN036": case "DYN037": case "DYN038":
      $blockModifier += SearchCharacter($defPlayer, subtype: "Off-Hand", class: "GUARDIAN") != "" ? 4 : 0;
      break;
    default: break;
  }
  return $blockModifier;
}

function PlayBlockModifier($cardID)
{
  switch($cardID) {
    case "CRU189": return 4;
    case "CRU190": return 3;
    case "CRU191": return 2;
    case "ELE125": return 4;
    case "ELE126": return 3;
    case "ELE127": return 2;
    default: return 0;
  }
}

?>
