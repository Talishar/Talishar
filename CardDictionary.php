<?php

include "Constants.php";
include "CardDictionaries/WelcomeToRathe/WTRShared.php";
include "CardDictionaries/ArcaneRising/ARCShared.php";
include "CardDictionaries/ArcaneRising/ARCGeneric.php";
include "CardDictionaries/ArcaneRising/ARCMechanologist.php";
include "CardDictionaries/ArcaneRising/ARCRanger.php";
include "CardDictionaries/ArcaneRising/ARCRuneblade.php";
include "CardDictionaries/ArcaneRising/ARCWizard.php";
include "CardDictionaries/CrucibleOfWar/CRUShared.php";
include "CardDictionaries/Monarch/MONShared.php";
include "CardDictionaries/Monarch/MONGeneric.php";
include "CardDictionaries/Monarch/MONBrute.php";
include "CardDictionaries/Monarch/MONIllusionist.php";
include "CardDictionaries/Monarch/MONRuneblade.php";
include "CardDictionaries/Monarch/MONWarrior.php";
include "CardDictionaries/Monarch/MONTalent.php";
include "CardDictionaries/TalesOfAria/ELEShared.php";
include "CardDictionaries/TalesOfAria/ELEGuardian.php";
include "CardDictionaries/TalesOfAria/ELERanger.php";
include "CardDictionaries/TalesOfAria/ELERuneblade.php";
include "CardDictionaries/TalesOfAria/ELETalent.php";
include "CardDictionaries/Everfest/EVRShared.php";
include "CardDictionaries/Uprising/UPRShared.php";
include "CardDictionaries/card_names.php";
include "CardDictionaries/ClassicBattles/DVRShared.php";
include "CardDictionaries/ClassicBattles/RVDShared.php";
include "CardDictionaries/Dynasty/DYNShared.php";


function CardType($cardID)
{
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  if ($set == "WTR") {
    return WTRCardType($cardID);
  } else if ($set == "ARC") {
    switch ($class) {
      case "MECHANOLOGIST":
        return ARCMechanologistCardType($cardID);
      case "RANGER":
        return ARCRangerCardType($cardID);
      case "RUNEBLADE":
        return ARCRunebladeCardType($cardID);
      case "WIZARD":
        return ARCWizardCardType($cardID);
      case "GENERIC":
        return ARCGenericCardType($cardID);
      default:
        return "";
    }
  } else if ($set == "CRU") {
    return CRUCardType($cardID);
  } else if ($set == "MON") {
    switch ($class) {
      case "BRUTE":
        return MONBruteCardType($cardID);
      case "ILLUSIONIST":
        return MONIllusionistCardType($cardID);
      case "RUNEBLADE":
        return MONRunebladeCardType($cardID);
      case "WARRIOR":
        return MONWarriorCardType($cardID);
      case "GENERIC":
        return MONGenericCardType($cardID);
      case "NONE":
        return MONTalentCardType($cardID);
      default:
        return "";
    }
  } else if ($set == "ELE") {
    switch ($class) {
      case "GUARDIAN":
        return ELEGuardianCardType($cardID);
      case "RANGER":
        return ELERangerCardType($cardID);
      case "RUNEBLADE":
        return ELERunebladeCardType($cardID);
      default:
        return ELETalentCardType($cardID);
    }
  } else if ($set == "EVR") {
    return EVRCardType($cardID);
  } else if ($set == "UPR") {
    return UPRCardType($cardID);
  } else if ($set == "DVR") {
    return DVRCardType($cardID);
  } else if ($set == "RVD") {
    return RVDCardType($cardID);
  } else if ($set == "DYN") {
    return DYNCardType($cardID);
  }
  switch ($cardID) {
    case "DUMMY":
      return "C";
    case "OVRPVE001":
      return "C";
    case "OVRPVE002":
      return "C";
    case "OVRPVE003":
      return "C";
    case "OVRPVE004":
      return "W";
    case "OVRPVE005":
      return "B";
    case "OVRPVE006":
      return "S";
    case "OVRPVE007":
      return "S";
    case "OVRPVE008":
      return "S";
    case "OVRPVE009":
      return "S";
    case "OVRPVE010":
      return "S";
    case "OVRPVE011":
      return "S";
    case "OVRPVE012":
      return "S";
    case "OVRPVE013":
      return "S";
    case "OVRPVE014":
      return "S";
    case "OVRPVE015":
      return "S";
    case "OVRPVE016":
      return "S";
    case "OVRPVE017":
      return "S";
    default:
      return "";
  }
}

function CardSubType($cardID)
{
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  if ($set == "WTR") {
    return WTRCardSubtype($cardID);
  } else if ($set == "ARC") {
    switch ($class) {
      case "MECHANOLOGIST":
        return ARCMechanologistCardSubType($cardID);
      case "RANGER":
        return ARCRangerCardSubType($cardID);
      case "RUNEBLADE":
        return ARCRunebladeCardSubType($cardID);
      case "WIZARD":
        return ARCWizardCardSubType($cardID);
      case "GENERIC":
        return ARCGenericCardSubType($cardID);
    }
  } else if ($set == "CRU") {
    return CRUCardSubtype($cardID);
  } else if ($set == "MON") {
    switch ($class) {
      case "BRUTE":
        return MONBruteCardSubType($cardID);
      case "ILLUSIONIST":
        return MONIllusionistCardSubType($cardID);
      case "RUNEBLADE":
        return MONRunebladeCardSubType($cardID);
      case "WARRIOR":
        return MONWarriorCardSubType($cardID);
      case "GENERIC":
        return MONGenericCardSubType($cardID);
      case "NONE":
        return MONTalentCardSubType($cardID);
      default:
        return "";
    }
  } else if ($set == "ELE") {
    switch ($class) {
      case "GUARDIAN":
        return ELEGuardianCardSubType($cardID);
      case "RANGER":
        return ELERangerCardSubType($cardID);
      case "RUNEBLADE":
        return ELERunebladeCardSubType($cardID);
      default:
        return ELETalentCardSubType($cardID);
    }
  } else if ($set == "EVR") {
    return EVRCardSubtype($cardID);
  } else if ($set == "UPR") {
    return UPRCardSubtype($cardID);
  } else if ($set == "DVR") {
    return DVRCardSubtype($cardID);
  } else if ($set == "RVD") {
    return RVDCardSubtype($cardID);
  } else if ($set == "DYN") {
    return DYNCardSubtype($cardID);
  }
  switch ($cardID) {
    default:
      return "";
  }
}

function CharacterHealth($cardID)
{
  switch ($cardID) {
    case "DUMMY":
      return 1000;
    case "WTR001":
    case "WTR038":
    case "WTR076":
    case "WTR113":
      return 40;
    case "ARC001":
    case "ARC038":
    case "ARC075":
      return 40;
    case "ARC113":
      return 30;
    case "ARC114":
      return 15;
    case "CRU002":
      return 19;
    case "CRU046":
      return 20;
    case "CRU047":
      return 17;
    case "CRU118":
      return 20;
    case "MON001":
    case "MON029":
    case "MON119":
    case "MON153":
      return 40;
    case "ELE001":
    case "ELE031":
    case "ELE062":
      return 40;
    case "EVR017":
      return 40;
    case "EVR019":
      return 21;
    case "EVR120":
      return 18;
    case "UPR001":
      return 40;
    case "UPR002":
      return 20;
    case "UPR044":
      return 40;
    case "UPR045":
      return 20;
    case "UPR102":
      return 36;
    case "UPR103":
      return 18;
    case "DYN001":
      return 15;
    default:
      return 20;
  }
}

function CharacterIntellect($cardID)
{
  global $currentPlayer;
  if ($cardID == "CRU097") {
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    $otherCharacter = &GetPlayerCharacter($otherPlayer);
    if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer)) {
      return CharacterIntellect($otherCharacter[0]);
    }
  }
  switch ($cardID) {
    case "CRU099":
      return 3;
    default:
      return 4;
  }
}

function CardSet($cardID)
{
  return substr($cardID, 0, 3);
}

function CardClass($cardID)
{
  $set = substr($cardID, 0, 3);
  $number = intval(substr($cardID, 3));
  switch ($set) {
    case "WTR":
      if ($number >= 1 && $number <= 37) return "BRUTE";
      else if ($number >= 38 && $number <= 75) return "GUARDIAN";
      else if ($number >= 76 && $number <= 112) return "NINJA";
      else if ($number >= 113 && $number <= 149) return "WARRIOR";
      else return "GENERIC";
    case "ARC":
      if ($number == 0) return "GENERIC";
      else if ($number >= 1 && $number <= 37) return "MECHANOLOGIST";
      else if ($number >= 38 && $number <= 74) return "RANGER";
      else if ($number >= 75 && $number <= 112) return "RUNEBLADE";
      else if ($number >= 113 && $number <= 149) return "WIZARD";
      else return "GENERIC";
    case "CRU":
      if ($number == 0) return "GENERIC";
      else if ($number >= 1 && $number <= 21) return "BRUTE";
      else if ($number >= 22 && $number <= 44) return "GUARDIAN";
      else if ($number >= 45 && $number <= 75) return "NINJA";
      else if ($number >= 76 && $number <= 96) return "WARRIOR";
      else if ($number == 97) return "SHAPESHIFTER";
      else if ($number >= 98 && $number <= 117) return "MECHANOLOGIST";
      else if ($number == 118) return "MERCHANT";
      else if ($number >= 119 && $number <= 137) return "RANGER";
      else if ($number >= 138 && $number <= 157) return "RUNEBLADE";
      else if ($number >= 158 && $number <= 176) return "WIZARD";
      else return "GENERIC";
    case "MON":
      if ($number == 0) return "NONE";
      else if ($number >= 1 && $number <= 28) return "ILLUSIONIST"; //Light
      else if ($number >= 29 && $number <= 59) return "WARRIOR"; //Light
      else if ($number >= 60 && $number <= 87) return "NONE"; //Light
      else if ($number >= 88 && $number <= 104) return "ILLUSIONIST";
      else if ($number >= 105 && $number <= 118) return "WARRIOR";
      else if ($number >= 119 && $number <= 152) return "BRUTE"; //Shadow
      else if ($number >= 153 && $number <= 186) return "RUNEBLADE"; //Shadow
      else if ($number >= 187 && $number <= 220) return "NONE"; //Shadow
      else if ($number >= 221 && $number <= 228) return "BRUTE";
      else if ($number >= 229 && $number <= 237) return "RUNEBLADE";
      else if ($number == 404) return "ILLUSIONIST";
      else if ($number == 405) return "WARRIOR";
      else if ($number == 406) return "BRUTE";
      else if ($number == 407) return "RUNEBLADE";
      else return "GENERIC";
    case "ELE":
      if ($number == 0) return "NONE";
      else if ($number >= 1 && $number <= 30) return "GUARDIAN";
      else if ($number >= 31 && $number <= 61) return "RANGER";
      else if ($number >= 31 && $number <= 90) return "RUNEBLADE";
      else if ($number >= 202 && $number <= 212) return "GUARDIAN";
      else if ($number >= 213 && $number <= 221) return "RANGER";
      else if ($number >= 222 && $number <= 232) return "RUNEBLADE";
      else if ($number >= 233) return "GENERIC";
      else return "NONE";
    case "EVR":
      if ($number == 0) return "GUARDIAN";
      else if ($number >= 1 && $number <= 16) return "BRUTE";
      else if ($number >= 17 && $number <= 36) return "GUARDIAN";
      else if ($number >= 37 && $number <= 52) return "NINJA";
      else if ($number >= 53 && $number <= 68) return "WARRIOR";
      else if ($number >= 69 && $number <= 84) return "MECHANOLOGIST";
      else if ($number >= 85 && $number <= 86) return "MERCHANT";
      else if ($number >= 87 && $number <= 102) return "RANGER";
      else if ($number >= 103 && $number <= 119) return "RUNEBLADE";
      else if ($number >= 120 && $number <= 136) return "WIZARD";
      else if ($number >= 137 && $number <= 153) return "ILLUSIONIST";
      else return "GENERIC";
    case "UPR":
      if ($number == 0) return "NONE";
      else if ($number >= 1 && $number <= 43) return "ILLUSIONIST";
      else if ($number >= 44 && $number <= 83) return "NINJA";
      else if ($number >= 84 && $number <= 101) return "NONE";
      else if ($number >= 102 && $number <= 135) return "WIZARD";
      else if ($number >= 136 && $number <= 150) return "NONE";
      else if ($number >= 151 && $number <= 157) return "ILLUSIONIST";
      else if ($number >= 158 && $number <= 164) return "NINJA";
      else if ($number >= 165 && $number <= 181) return "WIZARD";
      else if ($number >= 182 && $number <= 223) return "GENERIC";
      else if ($number >= 406 && $number <= 417) return "ILLUSIONIST";
      else if ($number >= 439 && $number <= 441) return "ILLUSIONIST";
      else if ($number == 551) return "ILLUSIONIST";
      else return "NONE";
    case "DVR":
      if ($number >= 2) return "WARRIOR";
      else if ($number = 5) return "WARRIOR";
      else if ($number >= 7 && $number <= 12) return "WARRIOR";
      else if ($number >= 15 && $number <= 18) return "WARRIOR";
      else if ($number >= 20 && $number <= 21) return "WARRIOR";
      else return "GENERIC";
    case "RVD":
      if ($number >= 3) return "BRUTE";
      else if ($number >= 7 && $number <= 17) return "BRUTE";
      else if ($number = 21) return "BRUTE";
      else if ($number = 23) return "BRUTE";
      else if ($number = 25) return "BRUTE";
      else return "GENERIC";
    case "DYN":
      if ($number = 30) return "WARRIOR,WIZARD";
      else return "GENERIC";
    default:
      return 0;
  }
}

function CardTalent($cardID)
{
  $set = substr($cardID, 0, 3);
  if ($set == "MON") return MONCardTalent($cardID);
  else if ($set == "ELE") return ELECardTalent($cardID);
  else if ($set == "UPR") return UPRCardTalent($cardID);
  else if ($set == "DYN") return DYNCardTalent($cardID);
  return "NONE";
}

//Minimum cost of the card
function CardCost($cardID)
{
  global $currentPlayer;
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  if ($cardID == "CRU097") {
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    $otherCharacter = &GetPlayerCharacter($otherPlayer);
    if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer)) {
      return CardCost($otherCharacter[0]);
    }
  }
  if ($set == "WTR") {
    return WTRCardCost($cardID);
  } else if ($set == "ARC") {
    switch ($class) {
      case "MECHANOLOGIST":
        return ARCMechanologistCardCost($cardID);
      case "RANGER":
        return ARCRangerCardCost($cardID);
      case "RUNEBLADE":
        return ARCRunebladeCardCost($cardID);
      case "WIZARD":
        return ARCWizardCardCost($cardID);
      case "GENERIC":
        return ARCGenericCardCost($cardID);
    }
  } else if ($set == "CRU") {
    return CRUCardCost($cardID);
  } else if ($set == "MON") {
    switch ($class) {
      case "BRUTE":
        return MONBruteCardCost($cardID);
      case "ILLUSIONIST":
        return MONIllusionistCardCost($cardID);
      case "RUNEBLADE":
        return MONRunebladeCardCost($cardID);
      case "WARRIOR":
        return MONWarriorCardCost($cardID);
      case "GENERIC":
        return MONGenericCardCost($cardID);
      case "NONE":
        return MONTalentCardCost($cardID);
      default:
        return "";
    }
  } else if ($set == "ELE") {
    switch ($class) {
      case "GUARDIAN":
        return ELEGuardianCardCost($cardID);
      case "RANGER":
        return ELERangerCardCost($cardID);
      case "RUNEBLADE":
        return ELERunebladeCardCost($cardID);
      default:
        return ELETalentCardCost($cardID);
    }
  } else if ($set == "EVR") {
    return EVRCardCost($cardID);
  } else if ($set == "UPR") {
    return UPRCardCost($cardID);
  } else if ($set == "DVR") {
    return DVRCardCost($cardID);
  } else if ($set == "RVD") {
    return RVDCardCost($cardID);
  } else if ($set == "DYN") {
    return DYNCardCost($cardID);
  }
  switch ($cardID) {
    default:
      return 0;
  }
}

function AbilityCost($cardID)
{
  global $currentPlayer;
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  $subtype = CardSubtype($cardID);
  if ($cardID == "CRU097") {
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    $otherCharacter = &GetPlayerCharacter($otherPlayer);
    if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer)) {
      return AbilityCost($otherCharacter[0]);
    }
  }
  if ($class == "ILLUSIONIST" && $subtype == "Aura") {
    if (SearchCharacterForCard($currentPlayer, "MON003")) return 0;
    if (SearchCharacterForCard($currentPlayer, "MON088")) return 3;
  }
  if (DelimStringContains($subtype, "Dragon")) {
    if (SearchCharacterActive($currentPlayer, "UPR003")) return 0;
  }
  if ($set == "ARC") {
    return ARCAbilityCost($cardID);
  } else if ($set == "CRU") {
    return CRUAbilityCost($cardID);
  } else if ($set == "MON") {
    return MONAbilityCost($cardID);
  } else if ($set == "ELE") {
    return ELEAbilityCost($cardID);
  } else if ($set == "EVR") {
    return EVRAbilityCost($cardID);
  } else if ($set == "UPR") {
    return UPRAbilityCost($cardID);
  } else if ($set == "DVR") {
    return DVRAbilityCost($cardID);
  } else if ($set == "RVD") {
    return RVDAbilityCost($cardID);
  } else if ($set == "DYN") {
    return DYNAbilityCost($cardID);
  }
  return CardCost($cardID);
}

function ResourcesPaidBlockModifier($cardID, $amountPaid)
{
  switch ($cardID) {
    case "MON241":
    case "MON242":
    case "MON243":
    case "MON244":
    case "RVD005":
    case "RVD006":
      return ($amountPaid >= 1 ? 2 : 0);
    case "UPR203":
    case "UPR204":
    case "UPR205":
      return ($amountPaid >= 1 ? 2 : 0);
    default:
      return 0;
  }
}

function ResourcesPaidAttackModifier($cardID, $amountPaid)
{
  switch ($cardID) {
    case "UPR191":
    case "UPR192":
    case "UPR193":
      return ($amountPaid >= 2 ? 2 : 0);
    default:
      return 0;
  }
}

function DynamicCost($cardID)
{
  global $currentPlayer;
  switch ($cardID) {
    case "WTR051":
    case "WTR052":
    case "WTR053":
      return "2,6";
    case "ARC009":
      return "0,2,4,6,8,10,12";
    case "MON231":
      return "0,2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36,38,40";
    case "EVR022":
      return "3,4,5,6,7,8,9,10,11,12";
    case "EVR124":
      return GetIndices(SearchCount(SearchAura(($currentPlayer == 1 ? 2 : 1), "", "", 0)) + 1);
    case "UPR109":
      return "0,2,4,6,8,10,12,14,16,18,20";
    default:
      return "";
  }
}

function PitchValue($cardID)
{
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  if ($set == "WTR") {
    return WTRPitchValue($cardID);
  } else if ($set == "ARC") {
    switch ($class) {
      case "MECHANOLOGIST":
        return ARCMechanologistPitchValue($cardID);
      case "RANGER":
        return ARCRangerPitchValue($cardID);
      case "RUNEBLADE":
        return ARCRunebladePitchValue($cardID);
      case "WIZARD":
        return ARCWizardPitchValue($cardID);
      case "GENERIC":
        return ARCGenericPitchValue($cardID);
    }
  } else if ($set == "CRU") {
    return CRUPitchValue($cardID);
  } else if ($set == "MON") {
    switch ($class) {
      case "BRUTE":
        return MONBrutePitchValue($cardID);
      case "ILLUSIONIST":
        return MONIllusionistPitchValue($cardID);
      case "RUNEBLADE":
        return MONRunebladePitchValue($cardID);
      case "WARRIOR":
        return MONWarriorPitchValue($cardID);
      case "GENERIC":
        return MONGenericPitchValue($cardID);
      case "NONE":
        return MONTalentPitchValue($cardID);
      default:
        return "";
    }
  } else if ($set == "ELE") {
    switch ($class) {
      case "GUARDIAN":
        return ELEGuardianPitchValue($cardID);
      case "RANGER":
        return ELERangerPitchValue($cardID);
      case "RUNEBLADE":
        return ELERunebladePitchValue($cardID);
      default:
        return ELETalentPitchValue($cardID);
    }
  } else if ($set == "EVR") {
    return EVRPitchValue($cardID);
  } else if ($set == "UPR") {
    return UPRPitchValue($cardID);
  } else if ($set == "DVR") {
    return DVRPitchValue($cardID);
  } else if ($set == "RVD") {
    return RVDPitchValue($cardID);
  } else if ($set == "DYN") {
    return DYNPitchValue($cardID);
  }
  switch ($cardID) {
    default:
      return 3;
  }
}

function BlockValue($cardID)
{
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  if ($set == "WTR") {
    return WTRBlockValue($cardID);
  } else if ($set == "ARC") {
    switch ($class) {
      case "MECHANOLOGIST":
        return ARCMechanologistBlockValue($cardID);
      case "RANGER":
        return ARCRangerBlockValue($cardID);
      case "RUNEBLADE":
        return ARCRunebladeBlockValue($cardID);
      case "WIZARD":
        return ARCWizardBlockValue($cardID);
      case "GENERIC":
        return ARCGenericBlockValue($cardID);
    }
  } else if ($set == "CRU") {
    return CRUBlockValue($cardID);
  } else if ($set == "MON") {
    switch ($class) {
      case "BRUTE":
        return MONBruteBlockValue($cardID);
      case "ILLUSIONIST":
        return MONIllusionistBlockValue($cardID);
      case "RUNEBLADE":
        return MONRunebladeBlockValue($cardID);
      case "WARRIOR":
        return MONWarriorBlockValue($cardID);
      case "GENERIC":
        return MONGenericBlockValue($cardID);
      case "NONE":
        return MONTalentBlockValue($cardID);
      default:
        return "";
    }
  } else if ($set == "ELE") {
    switch ($class) {
      case "GUARDIAN":
        return ELEGuardianBlockValue($cardID);
      case "RANGER":
        return ELERangerBlockValue($cardID);
      case "RUNEBLADE":
        return ELERunebladeBlockValue($cardID);
      default:
        return ELETalentBlockValue($cardID);
    }
  } else if ($set == "EVR") {
    return EVRBlockValue($cardID);
  } else if ($set == "UPR") {
    return UPRBlockValue($cardID);
  } else if ($set == "DVR") {
    return DVRBlockValue($cardID);
  } else if ($set == "RVD") {
    return RVDBlockValue($cardID);
  } else if ($set == "DYN") {
    return DYNBlockValue($cardID);
  }
  switch ($cardID) {
    default:
      return 3;
  }
}

function AttackValue($cardID)
{
  global $combatChainState, $CCS_NumBoosted, $mainPlayer;
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  $subtype = CardSubtype($cardID);
  if ($class == "ILLUSIONIST" && $subtype == "Aura") {
    if (SearchCharacterForCard($mainPlayer, "MON003")) return 1;
    if (SearchCharacterForCard($mainPlayer, "MON088")) return 4;
  }
  if ($set == "WTR") {
    return WTRAttackValue($cardID);
  } else if ($set == "ARC") {
    switch ($class) {
      case "MECHANOLOGIST":
        return ARCMechanologistAttackValue($cardID);
      case "RANGER":
        return ARCRangerAttackValue($cardID);
      case "RUNEBLADE":
        return ARCRunebladeAttackValue($cardID);
      case "WIZARD":
        return ARCWizardAttackValue($cardID);
      case "GENERIC":
        return ARCGenericAttackValue($cardID);
    }
  } else if ($set == "CRU") {
    return CRUAttackValue($cardID);
  } else if ($set == "MON") {
    switch ($class) {
      case "BRUTE":
        return MONBruteAttackValue($cardID);
      case "ILLUSIONIST":
        return MONIllusionistAttackValue($cardID);
      case "RUNEBLADE":
        return MONRunebladeAttackValue($cardID);
      case "WARRIOR":
        return MONWarriorAttackValue($cardID);
      case "GENERIC":
        return MONGenericAttackValue($cardID);
      case "NONE":
        return MONTalentAttackValue($cardID);
      default:
        return "";
    }
  } else if ($set == "ELE") {
    switch ($class) {
      case "GUARDIAN":
        return ELEGuardianAttackValue($cardID);
      case "RANGER":
        return ELERangerAttackValue($cardID);
      case "RUNEBLADE":
        return ELERunebladeAttackValue($cardID);
      default:
        return ELETalentAttackValue($cardID);
    }
  } else if ($set == "EVR") {
    return EVRAttackValue($cardID);
  } else if ($set == "UPR") {
    return UPRAttackValue($cardID);
  } else if ($set == "DVR") {
    return DVRAttackValue($cardID);
  } else if ($set == "RVD") {
    return RVDAttackValue($cardID);
  } else if ($set == "DYN") {
    return DYNAttackValue($cardID);
  }
  switch ($cardID) {
    default:
      return 0;
  }
}

// Natural go again or ability go again. Attacks that gain go again should be in CoreLogic (due to hypothermia)
function HasGoAgain($cardID)
{
  $set = CardSet($cardID);
  if ($set == "WTR") {
    return WTRHasGoAgain($cardID);
  } else if ($set == "ARC") {
    return ARCHasGoAgain($cardID);
  } else if ($set == "CRU") {
    return CRUHasGoAgain($cardID);
  } else if ($set == "MON") {
    return MONHasGoAgain($cardID);
  } else if ($set == "ELE") {
    return ELEHasGoAgain($cardID);
  } else if ($set == "EVR") {
    return EVRHasGoAgain($cardID);
  } else if ($set == "UPR") {
    return UPRHasGoAgain($cardID);
  } else if ($set == "DVR") {
    return DVRHasGoAgain($cardID);
  } else if ($set == "RVD") {
    return RVDHasGoAgain($cardID);
  } else if ($set == "DYN") {
    return DYNHasGoAgain($cardID);
  }
  switch ($cardID) {

    default:
      return false;
  }
}

function GetAbilityType($cardID, $index = -1)
{
  global $currentPlayer;
  $set = CardSet($cardID);
  $subtype = CardSubtype($cardID);
  if ($cardID == "CRU097") {
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    $otherCharacter = &GetPlayerCharacter($otherPlayer);
    if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer)) {
      return GetAbilityType($otherCharacter[0], $index);
    }
  }
  if (ClassContains($cardID, "ILLUSIONIST", $currentPlayer) && $subtype == "Aura") {
    if (SearchCharacterForCard($currentPlayer, "MON003")) return "AA";
    if (SearchCharacterForCard($currentPlayer, "MON088")) return "AA";
  }
  if (DelimStringContains($subtype, "Dragon")) {
    if (SearchCharacterActive($currentPlayer, "UPR003")) return "AA";
  }
  if ($set == "WTR") {
    return WTRAbilityType($cardID, $index);
  } else if ($set == "ARC") {
    return ARCAbilityType($cardID, $index);
  } else if ($set == "CRU") {
    return CRUAbilityType($cardID, $index);
  } else if ($set == "MON") {
    return MONAbilityType($cardID, $index);
  } else if ($set == "ELE") {
    return ELEAbilityType($cardID, $index);
  } else if ($set == "EVR") {
    return EVRAbilityType($cardID, $index);
  } else if ($set == "UPR") {
    return UPRAbilityType($cardID, $index);
  } else if ($set == "DVR") {
    return DVRAbilityType($cardID, $index);
  } else if ($set == "RVD") {
    return RVDAbilityType($cardID, $index);
  } else if ($set == "DYN") {
    return DYNAbilityType($cardID, $index);
  }
  switch ($cardID) {
    default:
      return "";
  }
}

function GetAbilityTypes($cardID)
{
  switch ($cardID) {
    case "ARC003":
      return "A,AA";
    default:
      return "";
  }
}

function GetAbilityNames($cardID, $index = -1)
{
  global $currentPlayer;
  switch ($cardID) {
    case "ARC003":
      $character = &GetPlayerCharacter($currentPlayer);
      if ($index == -1) return "";
      $rv = "Add_a_steam_counter";
      if ($character[$index + 2] > 0) $rv .= ",Attack";
      return $rv;
    default:
      return "";
  }
}

function GetAbilityIndex($cardID, $index, $abilityName)
{
  $names = explode(",", GetAbilityNames($cardID, $index));
  for ($i = 0; $i < count($names); ++$i) {
    if ($abilityName == $names[$i]) return $i;
  }
  return 0;
}

function GetResolvedAbilityType($cardID)
{
  global $currentPlayer, $CS_AbilityIndex;
  $abilityIndex = GetClassState($currentPlayer, $CS_AbilityIndex);
  $abilityTypes = GetAbilityTypes($cardID);
  if ($abilityTypes == "" || $abilityIndex == "-") return GetAbilityType($cardID);
  else {
    $abilityTypes = explode(",", $abilityTypes);
    return $abilityTypes[$abilityIndex];
  }
}

function IsPlayable($cardID, $phase, $from, $index = -1, &$restriction = null, $player = "")
{
  global $currentPlayer, $CS_NumActionsPlayed, $combatChainState, $CCS_BaseAttackDefenseMax;
  global $CCS_ResourceCostDefenseMin, $CCS_CardTypeDefenseRequirement, $actionPoints, $mainPlayer, $playerID;
  global $combatChain;
  if ($player == "") $player = $currentPlayer;
  $myArsenal = &GetArsenal($player);
  $myAllies = &GetAllies($player);
  $myCharacter = &GetPlayerCharacter($player);
  $myHand = &GetHand($player);
  $restriction = "";
  $cardType = CardType($cardID);
  $subtype = CardSubType($cardID);
  if ($phase == "P" && $from != "HAND") return false;
  if ($phase == "B" && $from == "BANISH") return false;
  if ($phase == "B" && $cardType == "E" && $myCharacter[$index + 6] == 1) {
    $restriction = "On combat chain";
    return false;
  }
  if ($from == "CHAR" && $phase != "B" && $myCharacter[$index + 8] == "1") {
    $restriction = "Frozen";
    return false;
  }
  if ($from == "PLAY" && $subtype == "Ally" && $phase != "B" && $myAllies[$index + 3] == "1") {
    $restriction = "Frozen";
    return false;
  }
  if ($from == "ARS" && $phase != "B" && $myArsenal[$index + 4] == "1") {
    $restriction = "Frozen";
    return false;
  }
  if ($phase != "P" && $cardType == "DR" && IsAllyAttackTarget() && $currentPlayer != $mainPlayer) return false;
  if ($phase != "P" && $cardType == "AR" && IsAllyAttacking() && $currentPlayer == $mainPlayer) return false;
  if (count($combatChain) > 0 && ($phase == "B" || (($phase == "D" || $phase == "INSTANT") && $cardType == "DR")) && $from == "HAND") {
    if (CachedDominateActive() && CachedNumBlockedFromHand() >= 1) return false;
    if (CachedTotalAttack() <= 2 && (SearchCharacterForCard($mainPlayer, "CRU047") || SearchCurrentTurnEffects("CRU047-SHIYANA", $mainPlayer)) && (SearchCharacterActive($mainPlayer, "CRU047") || SearchCharacterActive($mainPlayer, "CRU097")) && CardType($combatChain[0]) == "AA") return false;
  }
  if ($phase == "B" && $from == "ARS" && !($cardType == "AA" && SearchCurrentTurnEffects("ARC160-2", $player))) return false;
  if ($phase == "B" || $phase == "D") {
    if ($cardType == "AA") {
      $baseAttackMax = $combatChainState[$CCS_BaseAttackDefenseMax];
      if ($baseAttackMax > -1 && AttackValue($cardID) > $baseAttackMax) return false;
    }
    $resourceMin = $combatChainState[$CCS_ResourceCostDefenseMin];
    if ($resourceMin > -1 && CardCost($cardID) < $resourceMin && $cardType != "E") return false;
    if ($combatChainState[$CCS_CardTypeDefenseRequirement] == "Attack_Action" && $cardType != "AA") return false;
    if ($combatChainState[$CCS_CardTypeDefenseRequirement] == "Non-attack_Action" && $cardType != "A") return false;
  }
  if ($from != "PLAY" && $phase == "B" && $cardType != "DR") return BlockValue($cardID) > -1;
  if ($phase == "P" && IsPitchRestricted($cardID, $restriction, $from, $index)) return false;
  if ($from != "PLAY" && $phase == "P" && PitchValue($cardID) > 0) return true;
  $isStaticType = IsStaticType($cardType, $from, $cardID);
  if ($isStaticType) {
    $cardType = GetAbilityType($cardID, $index);
  }
  if ($cardType == "") return false;
  if (RequiresDiscard($cardID) || $cardID == "WTR159") {
    if ($from == "HAND" && count($myHand) < 2) return false; //TODO: Account for where it was from
    else if (count($myHand) < 1) return false;
  }
  if ($phase != "B" && $phase != "P" && IsPlayRestricted($cardID, $restriction, $from, $index, $player)) return false;
  if ($phase == "M" && $subtype == "Arrow" && $from != "ARS") return false;
  if ($phase == "D" && $subtype == "Trap" && $from != "ARS") return false;
  if (SearchCurrentTurnEffects("ARC044", $player) && !$isStaticType && $from != "ARS") return false;
  if (SearchCurrentTurnEffects("ARC043", $player) && ($cardType == "A" || $cardType == "AA") && GetClassState($player, $CS_NumActionsPlayed) >= 1) return false;
  if (($cardType == "I" || CanPlayAsInstant($cardID, $index, $from)) && CanPlayInstant($phase)) return true;
  if (($cardType == "A" || $cardType == "AA") && $actionPoints < 1) return false;
  switch ($cardType) {
    case "A":
      return $phase == "M";
    case "AA":
      return $phase == "M";
    case "AR":
      return $phase == "A";
    case "DR":
      if($phase != "D") return false;
      if(!IsDefenseReactionPlayable($cardID, $from)) { $restriction = "Defense reaction not playable."; return false; }
      return true;
    default:
      return false;
  }
}

function GoesWhereAfterResolving($cardID, $from = null, $player = "")
{
  global $currentPlayer, $CS_NumWizardNonAttack, $CS_NumBoosted, $mainPlayer, $combatChainState, $CCS_AttackPlayedFrom;
  if ($player == "") $player = $currentPlayer;
  $otherPlayer = $player == 2 ? 1 : 2;
  if (($from == "COMBATCHAIN" || $from == "CHAINCLOSING") && $player != $mainPlayer && CardType($cardID) != "DR") return "GY"; //If it was blocking, don't put it where it would go if it was played
  $subtype = CardSubType($cardID);
  if (DelimStringContains($subtype, "Invocation") || $cardID == "UPR439" || $cardID == "UPR440" || $cardID == "UPR441") return "-";
  switch ($cardID) {
    case "WTR163":
      return "BANISH";
    case "CRU163":
      return GetClassState($player, $CS_NumWizardNonAttack) >= 2 ? "HAND" : "GY";
    case "MON063":
      return "SOUL";
    case "MON064":
      return "SOUL";
    case "MON231":
      return "BANISH";
    case "ELE113":
      return "BANISH";
    case "ELE140":
    case "ELE141":
    case "ELE142":
      return "BANISH";
    case "MON087":
      $theirChar = GetPlayerCharacter($otherPlayer);
      if (TalentContains($theirChar[0], "SHADOW") && PlayerHasLessHealth($player)) return "SOUL";
      else return "GY";
    case "MON192":
      if ($from == "BANISH") return "HAND";
    case "EVR082":
    case "EVR083":
    case "EVR084":
      return (GetClassState($player, $CS_NumBoosted) > 0 ? "BOTDECK" : "GY");
    case "EVR134":
    case "EVR135":
    case "EVR136":
      return ($player != $mainPlayer ? "BOTDECK" : "GY");
    case "UPR160":
      if ($from == "COMBATCHAIN" && !SearchCurrentTurnEffects($cardID, $player)) {
        AddCurrentTurnEffect($cardID, $player);
        return "BANISH,TCC";
      } else {
        SearchCurrentTurnEffects($cardID, $player, 1);
        return "GY";
      }
    default:
      return "GY";
  }
}

function CanPlayInstant($phase)
{
  if ($phase == "M") return true;
  //if($phase == "B") return true;
  if ($phase == "A") return true;
  if ($phase == "D") return true;
  if ($phase == "INSTANT") return true;
  return false;
}

function IsPitchRestricted($cardID, &$restriction, $from = "", $index = -1)
{
  global $playerID;
  if (SearchCurrentTurnEffects("ELE035-3", $playerID) && CardCost($cardID) == 0) {
    $restriction = "ELE035";
    return true;
  }
  return false;
}

function IsPlayRestricted($cardID, &$restriction, $from = "", $index = -1, $player = "")
{
  global $playerID, $myClassState, $theirClassState, $CS_NumBoosted, $combatChain, $combatChainState, $currentPlayer, $mainPlayer, $CS_Num6PowBan, $myDiscard;
  global $CS_DamageTaken, $CS_NumFusedEarth, $CS_NumFusedIce, $CS_NumFusedLightning, $CS_NumNonAttackCards, $CS_DamageDealt, $CS_NumAttacks;
  global $CS_NumAttackCards, $CS_NumBloodDebtPlayed, $layers, $CS_HitsWithWeapon, $CS_AtksWWeapon, $CS_CardsEnteredGY, $turn, $CS_NumRedPlayed, $CS_NumPhantasmAADestroyed;

  if ($player == "") $player = $currentPlayer;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $myCharacter = &GetPlayerCharacter($player);
  $myHand = &GetHand($player);
  $myArsenal = &GetArsenal($player);
  $myItems = &GetItems($player);
  $mySoul = &GetSoul($player);

  if (SearchCurrentTurnEffects("CRU032", $playerID) && CardType($cardID) == "AA" && AttackValue($cardID) <= 3) {
    $restriction = "CRU032";
    return true;
  }
  if (SearchCurrentTurnEffects("MON007", $playerID) && $from == "BANISH") {
    $restriction = "MON007";
    return true;
  }
  if (SearchCurrentTurnEffects("ELE036", $playerID) && CardType($cardID) == "E") {
    $restriction = "ELE036";
    return true;
  }
  if (SearchCurrentTurnEffects("ELE035-3", $playerID) && CardCost($cardID) == 0 && !IsStaticType(CardType($cardID), $from, $cardID)) {
    $restriction = "ELE035";
    return true;
  }
  if (CardType($cardID) == "A" && $from != "PLAY" && GetClassState($playerID, $CS_NumNonAttackCards) == 1 && (SearchItemsForCard("EVR071", 1) != "" || SearchItemsForCard("EVR071", 2) != "")) {
    $restriction = "EVR071";
    return true;
  }
  if ($turn[0] != "B" && $turn[0] != "P" && $playerID != $mainPlayer && SearchAlliesActive($mainPlayer, "UPR415")) {
    $restriction = "UPR415";
    return true;
  }
  switch ($cardID) {
    case "ARC004":
      return $myClassState[$CS_NumBoosted] < 1;
    case "ARC005":
      return $myClassState[$CS_NumBoosted] < 1;
    case "ARC008":
      return $myClassState[$CS_NumBoosted] < 3;
    case "ARC010":
      return (count($combatChain) > 0 && $from == "PLAY" && $myItems[$index + 1] > 0 && (CardSubtype($combatChain[0]) != "Pistol" || $myItems[$index + 2] != 2));
    case "ARC018":
      return (count($combatChain) > 0 && $from == "PLAY" && $myItems[$index + 1] > 0 && (CardType($combatChain[0]) != "AA" || $myItems[$index + 2] != 2));
    case "ARC041":
      return !ArsenalHasFaceDownCard($player); //Restricted if you don't have a face down card
    case "WTR209":
    case "WTR210":
    case "WTR211":
      if (count($combatChain) == 0) return true;
      $subtype = CardSubtype($combatChain[0]);
      if ($subtype == "Sword" || $subtype == "Dagger" || (CardType($combatChain[0]) == "AA" && CardCost($combatChain[0]) <= 1)) return false;
      return true;
    case "WTR080":
      if (count($combatChain) == 0) return true;
      return !HasCombo($combatChain[0]);
    case "WTR082":
      return count($combatChain) == 0 || !ClassContains($combatChain[0], "NINJA", $player) || CardType($combatChain[0]) != "AA";
    case "WTR116":
      return GetClassState($player, $CS_HitsWithWeapon) == 0;
    case "WTR120":
    case "WTR121":
    case "WTR123":
    case "WTR124":
    case "WTR125":
    case "WTR135":
    case "WTR136":
    case "WTR137":
      if (count($combatChain) == 0) return true;
      $type = CardType($combatChain[0]);
      return $type != "W";
    case "WTR138":
    case "WTR139":
    case "WTR140":
      if (count($combatChain) == 0) return true;
      $type = CardType($combatChain[0]);
      return $type != "W";
    case "WTR132":
    case "WTR133":
    case "WTR134":
      if (count($combatChain) == 0) return true;
      if (!RepriseActive()) return false;
      $type = CardType($combatChain[0]);
      return $type != "W";
    case "WTR150":
      $index = FindMyCharacter($cardID);
      return $myCharacter[$index + 2] < 3;
    case "WTR154":
      if (count($combatChain) == 0) return true;
      if (CardType($combatChain[0]) != "AA") return true;
      if (CardCost($combatChain[0]) > 1) return true;
      return false;
    case "WTR206":
    case "WTR207":
    case "WTR208":
      if (count($combatChain) == 0) return true;
      $subtype = CardSubtype($combatChain[0]);
      if ($subtype == "Club" || $subtype == "Hammer" || (CardType($combatChain[0]) == "AA" && CardCost($combatChain[0]) >= 2)) return false;
      return true;
    case "CRU082":
    case "CRU083":
      if (count($combatChain) == 0) return true;
      $type = CardType($combatChain[0]);
      return $type != "W";
    case "CRU088":
    case "CRU089":
    case "CRU090":
      if (count($combatChain) == 0) return true;
      $type = CardType($combatChain[0]);
      return $type != "W";
    case "CRU097":
      $otherCharacter = &GetPlayerCharacter($otherPlayer);
      if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer)) {
        return IsPlayRestricted($otherCharacter[0], $restriction, $from, $index, $player);
      }
      return false;
    case "CRU125":
      return !HasTakenDamage($player);
    case "CRU143":
      return SearchCount(SearchDiscard($player, "AA", "", -1, -1, "RUNEBLADE")) == 0;
    case "CRU164":
      return count($layers) == 0 || SearchLayer($player, "I", "", 1) == "";
    case "CRU186":
      if (count($combatChain) == 0) return true;
      $type = CardType($combatChain[0]);
      return $type != "AA";
    case "MON000":
      return $from == "PLAY" && SearchCount(SearchHand($player, "", "", -1, -1, "", "", false, false, 2)) < 2;
    case "MON001":
    case "MON002":
      return count($mySoul) == 0;
    case "MON029":
    case "MON030":
      return count($mySoul) == 0;
    case "MON062":
      return count($mySoul) < 3;
    case "MON084":
    case "MON085":
    case "MON086":
      return count($combatChain) == 0;
    case "MON144":
    case "MON145":
    case "MON146":
      return GetClassState($player, $CS_Num6PowBan) == 0;
    case "MON126":
    case "MON127":
    case "MON128":
    case "MON129":
    case "MON130":
    case "MON131":
    case "MON132":
    case "MON133":
    case "MON134":
    case "MON135":
    case "MON136":
    case "MON137":
    case "MON141":
    case "MON142":
    case "MON143":
    case "MON147":
    case "MON148":
    case "MON149":
    case "MON150":
    case "MON151":
    case "MON152":
      return count($myDiscard) < 3;
    case "MON189":
      return SearchCount(SearchBanish($player, "", "", -1, -1, "", "", true)) < 6;
    case "MON190":
      return $myClassState[$CS_NumBloodDebtPlayed] < 6;
    case "MON198":
      $discard = GetDiscard($player);
      return count($discard) < 6;
    case "MON230":
      return GetClassState($player, $CS_NumAttackCards) == 0 || GetClassState($player, $CS_NumNonAttackCards) == 0;
    case "MON238":
      return $myClassState[$CS_DamageTaken] == 0 && $theirClassState[$CS_DamageTaken] == 0;
    case "MON303":
      return SearchDiscard($player, "AA", "", 2) == "";
    case "MON304":
      return SearchDiscard($player, "AA", "", 1) == "";
    case "MON305":
      return SearchDiscard($player, "AA", "", 0) == "";
    case "ELE031":
    case "ELE032":
    case "ELE115":
      return !ArsenalHasFaceDownCard($player);
    case "ELE118":
      return $from == "ARS" || ArsenalEmpty($player);
    case "ELE140":
    case "ELE141":
    case "ELE142":
      return SowTomorrowIndices($player, $cardID) == "";
    case "ELE143":
      return $from == "PLAY" && GetClassState($player, $CS_NumFusedEarth) == 0;
    case "ELE147":
      $weapons = "";
      $auraWeapons = (SearchCharacterForCard($mainPlayer, "MON003") || SearchCharacterForCard($mainPlayer, "MON088"));
      if ($auraWeapons) {
        $auras = GetAuras($mainPlayer);
        for ($i = 0; $i < count($auras); $i += AuraPieces()) {
          if (ClassContains($auras[$i], "ILLUSIONIST", $mainPlayer)) {
            $weapons .= "AURAS";
            break;
          }
        }
      }
      if (count($layers) != 0 && count($combatChain) == 0) {
        $abilityType = GetResolvedAbilityType($layers[0]);
        return !($weapons == "AURAS"
          || CardSubType($layers[0]) == "Dagger"
          || CardSubType($layers[0]) == "Hammer"
          || CardSubType($layers[0]) == "Sword"
          || CardSubType($layers[0]) == "Club"
          || CardSubType($layers[0]) == "Scythe"
          || CardSubType($layers[0]) == "Axe"
          || CardSubType($layers[0]) == "Flail"
          || (CardSubType($layers[0]) == "Pistol" && $abilityType == "AA")
          || CardType($layers[0]) == "AA"
          || DelimStringContains(CardSubType($layers[0]), "Ally")); // TODO: Need to not work on Pistol activation
      } else {
        return count($combatChain) == 0;
      }
    case "ELE172":
      return $from == "PLAY" && GetClassState($player, $CS_NumFusedIce) == 0;
    case "ELE183":
    case "ELE184":
    case "ELE185":
      if (count($layers) == 0 && count($combatChain) == 0) return true;
      for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
        if (CardType($combatChain[$i]) == "AA" && CardCost($combatChain[$i]) <= 1) return false;
      }
      // for ($i = 0; $i < count($layers); $i += LayerPieces()) {
      //   if (CardType($layers[$i]) == "AA" && CardCost($layers[$i]) <= 1) return false;
      // }
      return true;
    case "ELE201":
      return $from == "PLAY" && GetClassState($player, $CS_NumFusedLightning) == 0;
    case "ELE224":
      if ($player == $mainPlayer) return GetClassState($player, $CS_NumAttacks) == 0; // Attacked
      else return GetClassState($player, $CS_NumAttackCards) == 0; // Blocked/Played
    case "ELE225":
      return count($combatChain) == 0 || CardType($combatChain[0]) != "AA" || GetClassState($currentPlayer, $CS_NumNonAttackCards) == 0;
    case "ELE233":
      return count($myHand) != 1;
    case "ELE234":
      return count($myHand) == 0;
    case "ELE236":
      return !HasTakenDamage($player);
    case "EVR054":
      return count($combatChain) == 0 || CardType($combatChain[0]) != "W" || Is1H($combatChain[0]);
    case "EVR060":
    case "EVR061":
    case "EVR062":
      return count($combatChain) == 0 || CardType($combatChain[0]) != "W" || !Is1H($combatChain[0]);
    case "EVR063":
    case "EVR064":
    case "EVR065":
      return GetClassState($player, $CS_AtksWWeapon) < 1;
    case "EVR137":
      return $player != $mainPlayer;
    case "EVR173":
    case "EVR174":
    case "EVR175":
      return GetClassState($player, $CS_DamageDealt) == 0;
    case "EVR176":
      $hand = &GetHand($player);
      return $from == "PLAY" && count($hand) < 4;
    case "EVR178":
      $hand = &GetHand($player);
      return ($from == "PLAY" && count($hand) > 0);
    case "EVR053":
      return !HelmOfSharpEyePlayable();
    case "EVR181":
      return $from == "PLAY" && (GetClassState(1, $CS_CardsEnteredGY) == 0 && GetClassState(2, $CS_CardsEnteredGY) == 0 || count($combatChain) == 0 || CardType($combatChain[0]) != "AA");
    case "DVR013":
      return (count($combatChain) == 0 || CardType($combatChain[0]) != "W" || CardSubType($combatChain[0]) != "Sword");
    case "DVR014":
    case "DVR023":
      return count($combatChain) == 0 || CardSubType($combatChain[0]) != "Sword";
    case "UPR050":
      return (count($combatChain) == 0 || CardType($combatChain[0]) != "AA" || (!ClassContains($combatChain[0], "NINJA", $player) && !TalentContains($combatChain[0], "DRACONIC", $currentPlayer)));
    case "UPR084":
      return GetClassState($player, $CS_NumRedPlayed) == 0;
    case "UPR085":
      return GetClassState($player, $CS_NumRedPlayed) == 0;
    case "UPR087":
      return count($combatChain) == 0 || CardType($combatChain[0]) != "AA";
    case "UPR089":
      return ($player != $mainPlayer || NumDraconicChainLinks() < 4);
    case "UPR151":
      $char = &GetPlayerCharacter($player);
      return ($char[$index + 2] < 2 && !SearchCurrentTurnEffects($cardID, $player));
    case "UPR153":
      return GetClassState($player, $CS_NumPhantasmAADestroyed) < 1;
    case "UPR159":
      return count($combatChain) == 0 || AttackValue($combatChain[0]) > 2 || CardType($combatChain[0]) != "AA";
    case "UPR162":
    case "UPR163":
    case "UPR164":
      return count($combatChain) == 0 || CardType($combatChain[0]) != "AA" || CardCost($combatChain[0]) > 0;
    case "UPR165":
      return GetClassState($player, $CS_NumNonAttackCards) == 0;
    case "UPR166":
      $char = &GetPlayerCharacter($player);
      return $char[$index + 2] < 2;
    case "UPR167":
      return $player == $mainPlayer;
    case "UPR169":
      return SearchLayer($otherPlayer, "A") == "";
      //Mandatory Target Ash
    case "UPR006": case "UPR007": case "UPR008":
    case "UPR009": case "UPR010": case "UPR011":
    case "UPR012": case "UPR013": case "UPR014":
    case "UPR015": case "UPR016": case "UPR017":
    case "UPR036": case "UPR037": case "UPR038":
    case "UPR039": case "UPR040": case "UPR041":
      $myAsh = &GetPermanents($player);
      $ash = 0;
      for ($i = 0; $i < count($myAsh); ++$i) {
        if ($myAsh[$i] == "UPR043") {
          ++$ash;
        }
      }
      return $ash < 1;
      //Once per turn instants restriction
    case "MON281":
    case "MON282":
    case "MON283":
    case "ELE195":
    case "ELE196":
    case "ELE197":
      return SearchCurrentTurnEffects($cardID, $player);
    default:
      return false;
  }
}

function IsDefenseReactionPlayable($cardID, $from)
{
  global $combatChain, $mainPlayer;
  if ($combatChain[0] == "ARC159" && CardType($cardID) == "DR") return false;
  if ($combatChain[0] == "MON245") if (!ExudeConfidenceReactionsPlayable()) return false;
  if ($from == "HAND" && CardSubType($combatChain[0]) == "Arrow" && SearchCharacterForCard($mainPlayer, "EVR087")) return false;
  if (CurrentEffectPreventsDefenseReaction($from)) return false;
  return true;
}

function IsAction($cardID)
{
  $cardType = CardType($cardID);
  if ($cardType == "A" || $cardType == "AA") return true;
  $abilityType = GetAbilityType($cardID);
  if ($abilityType == "A" || $abilityType == "AA") return true;
  return false;
}

function GoesOnCombatChain($phase, $cardID, $from)
{
  global $layers;
  if ($phase != "B" && $from == "EQUIP" || $from == "PLAY") $cardType = GetResolvedAbilityType($cardID);
  elseif ($phase == "M" && $cardID == "MON192" && $from == "BANISH") $cardType = GetResolvedAbilityType($cardID);
  else $cardType = CardType($cardID);
  if ($cardType == "I") return false; //Instants as yet never go on the combat chain
  if ($phase == "B" && count($layers) == 0) return true; //Anything you play during these combat phases would go on the chain
  if (($phase == "A" || $phase == "D") && $cardType == "A") return false; //Non-attacks played as instants never go on combat chain
  if ($cardType == "AR") return true;
  if ($cardType == "DR") return true;
  if ($phase == "M" && $cardType == "AA") return true; //If it's an attack action, it goes on the chain
  return false;
}

function IsStaticType($cardType, $from = "", $cardID = "")
{
  if ($cardType == "C" || $cardType == "E" || $cardType == "W") return true;
  if ($from == "PLAY") return true;
  if ($cardID != "" && $from == "BANISH" && AbilityPlayableFromBanish($cardID)) return true;
  return false;
}

function HasBladeBreak($cardID)
{
  switch ($cardID) {
    case "WTR079":
      return true;
    case "WTR150":
      return true;
    case "WTR155":
    case "WTR156":
    case "WTR157":
    case "WTR158":
      return true;
    case "ARC041":
      return true;
    case "CRU122":
      return true;
    case "MON060":
      return true;
    case "ELE144":
      return true;
    case "ELE204":
      return true;
    case "ELE213":
      return true;
    case "ELE224":
      return true;
    case "EVR037":
      return true;
    case "EVR086":
      return true;
    case "DVR003":
    case "DVR006":
      return true;
    case "RVD003":
      return true;
    case "UPR136":
      return true;
    case "UPR158":
      return true;
    case "UPR182":
      return true;
    default:
      return false;
  }
}

function HasBattleworn($cardID)
{
  switch ($cardID) {
    case "WTR004":
    case "WTR005":
      return true;
    case "WTR041":
    case "WTR042":
      return true;
    case "WTR080":
      return true;
    case "WTR116":
    case "WTR117":
      return true;
    case "ARC004":
      return true;
    case "ARC078":
      return true;
    case "ARC150":
      return true;
    case "CRU053":
      return true;
    case "MON107":
    case "MON108":
      return true;
    case "MON122":
      return true;
    case "MON230":
      return true;
    case "EVR001":
      return true;
    case "EVR053":
      return true;
    case "DVR005":
      return true;
    default:
      return false;
  }
}

function HasTemper($cardID)
{
  switch ($cardID) {
    case "CRU025":
      return true;
    case "CRU081":
      return true;
    case "CRU141":
      return true;
    case "EVR018":
      return true;
    case "EVR020":
      return true;
    case "UPR084":
      return true;
    default:
      return false;
  }
}

function RequiresDiscard($cardID)
{
  switch ($cardID) {
    case "WTR006":
    case "WTR007":
    case "WTR008":
    case "WTR011":
    case "WTR012":
    case "WTR013":
    case "WTR014":
    case "WTR015":
    case "WTR016":
    case "WTR020":
    case "WTR021":
    case "WTR022":
    case "WTR029":
    case "WTR030":
    case "WTR031":
    case "WTR035":
    case "WTR036":
    case "WTR037":
    case "CRU010":
    case "CRU011":
    case "CRU012":
    case "CRU019":
    case "CRU020":
    case "CRU021":
      return true;
    default:
      return false;
  }
}

function ETASteamCounters($cardID)
{
  switch ($cardID) {
    case "ARC017":
      return 1;
    case "ARC007":
    case "ARC019":
      return 2;
    case "ARC036":
      return 3;
    case "ARC035":
      return 4;
    case "ARC037":
      return 5;
    case "CRU104":
      return 0;
    case "CRU105":
      return 0;
    case "EVR069":
      return 1;
    case "EVR071":
      return 1;
    case "EVR072":
      return 3;
    default:
      return 0;
  }
}

function AbilityHasGoAgain($cardID)
{
  global $currentPlayer;
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  $subtype = CardSubtype($cardID);
  if ($cardID == "CRU097") {
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    $otherCharacter = &GetPlayerCharacter($otherPlayer);
    if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer)) {
      return AbilityHasGoAgain($otherCharacter[0]);
    }
  }
  if ($class == "ILLUSIONIST" && $subtype == "Aura") {
    if (SearchCharacterForCard($currentPlayer, "MON088")) return true;
  }
  if ($set == "WTR") {
    return WTRAbilityHasGoAgain($cardID);
  } else if ($set == "ARC") {
    return ARCAbilityHasGoAgain($cardID);
  } else if ($set == "CRU") {
    return CRUAbilityHasGoAgain($cardID);
  } else if ($set == "MON") {
    return MONAbilityHasGoAgain($cardID);
  } else if ($set == "ELE") {
    return ELEAbilityHasGoAgain($cardID);
  } else if ($set == "EVR") {
    return EVRAbilityHasGoAgain($cardID);
  } else if ($set == "UPR") {
    return UPRAbilityHasGoAgain($cardID);
  } else if ($set == "DYN") {
    return DYNAbilityHasGoAgain($cardID);
  }
  switch ($cardID) {
    case "RVD004":
      return true;
    case "DVR004":
      return true;
    default:
      return false;
  }
}

function DoesEffectGrantDominate($cardID)
{
  global $combatChainState, $CCS_AttackFused, $currentPlayer;
  if ($cardID == "CRU097") {
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    $otherCharacter = &GetPlayerCharacter($otherPlayer);
    if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer)) {
      return DoesEffectGrantDominate($otherCharacter[0]);
    }
  }
  switch ($cardID) {
    case "WTR038":
    case "WTR039":
      return true;
    case "WTR197":
      return true;
    case "ARC011":
    case "ARC012":
    case "ARC013":
      return true;
    case "ARC019":
      return true;
    case "ARC038":
    case "ARC039":
      return true;
    case "CRU013":
    case "CRU014":
    case "CRU015":
      return true;
    case "CRU038":
    case "CRU039":
    case "CRU040":
      return true;
    case "CRU094-2":
    case "CRU095-2":
    case "CRU096-2":
      return true;
    case "CRU106":
    case "CRU107":
    case "CRU108":
      return true;
    case "MON109":
      return true;
    case "MON129":
    case "MON130":
    case "MON131":
      return true;
    case "MON132":
    case "MON133":
    case "MON134":
      return true;
    case "MON195":
    case "MON196":
    case "MON197":
      return true;
    case "MON223":
    case "MON224":
    case "MON225":
      return true;
    case "MON278":
    case "MON279":
    case "MON280":
      return true;
    case "MON406":
      return true;
    case "ELE005":
      return true;
    case "ELE016":
    case "ELE017":
    case "ELE018":
      return true;
    case "ELE033-2":
      return true;
    case "ELE056":
    case "ELE057":
    case "ELE058":
      return true;
    case "ELE092-DOMATK":
      return true;
    case "ELE097":
    case "ELE098":
    case "ELE099":
      return true;
    case "ELE154":
    case "ELE155":
    case "ELE156":
      return $combatChainState[$CCS_AttackFused] == 1;
    case "ELE166":
    case "ELE167":
    case "ELE168":
      return true;
    case "ELE205":
      return true;
    case "EVR017":
      return true;
    case "EVR019":
      return true;
    case "UPR091":
      return true;
    default:
      return false;
  }
}

function CharacterNumUsesPerTurn($cardID)
{
  switch ($cardID) {
    case "ELE034":
      return 2;
    default:
      return 1;
  }
}

//Active (2 = Always Active, 1 = Yes, 0 = No)
function CharacterDefaultActiveState($cardID)
{
  switch ($cardID) {
    case "WTR117": // Refraction Bolters
      return 1;
    case "ARC152": // Vest of the First Fist
      return 1;
    case "CRU053": // Breeze Rider Boots
      return 1;
    case "CRU161": // Metacarpus Node
      return 1;
    case "MON122": // Hooves of the Shadowbeast
      return 0;
    case "ELE174": // Mark of Lightning
      return 1;
    case "ELE173": case "MON061": case "MON090":
    case "MON188": case "MON302": case "MON400": // Spellvoid Equipments
    case "MON401": case "MON402":
      return 1;
    case "EVR037": // Mask of the Pouncing Lynx
      return 1;
    case "UPR004": case "UPR047": case "UPR125":
    case "UPR184": case "UPR185": case "UPR186": // Quell
      return 0;
    default:
      return 2;
  }
}

//Hold priority for triggers (2 = Always hold, 1 = Hold, 0 = Don't Hold)
function AuraDefaultHoldTriggerState($cardID)
{
  switch ($cardID) {
    case "WTR046":
      return 0 ; //Forged for War
    case "WTR047":
      return 0; //Show Time!
    case "WTR054": case "WTR055": case "WTR056":
      return 0; //Blessing of Deliverance
    case "WTR069": case "WTR070": case "WTR071":
      return 0; //Emerging Power
    case "WTR072": case "WTR073": case "WTR074":
      return 0; //Stonewall Confidence
    case "WTR075":
      return 0; //Seismic Surge
    case "ARC112":
      return 0; //Runechant
    case "CRU028":
      return 0; //Stamp Authority
    case "CRU029": case "CRU030": case "CRU031":
      return 0; //Towering Titan
    case "CRU038": case "CRU039": case "CRU040":
      return 0; //Emerging Dominance
    case "CRU075":
      return 0; //Zen Token
    case "CRU144":
      return 0; //Runeblood Barrier
    case "MON186":
      return 0; //Soul Shackle
    case "ELE025": case "ELE026": case "ELE027":
      return 0; //Emerging Avalanche
    case "ELE028": case "ELE029": case "ELE030":
      return 0; //Strength of Sequoia
    case "ELE206": case "ELE207": case "ELE208":
      return 0; //Embolden
    case "ELE109": case "ELE110": case "ELE111":
      return 0; //Embodiment of Earth and Lightning, Frostbite
    case "EVR107": case "EVR108": case "EVR109":
      return 0; //Runeblood incantation
    case "EVR131": case "EVR132": case "EVR133":
      return 0; //Pyroglyphic Protection
    case "UPR190":
      return 0; //Fog Down
    case "UPR218": case "UPR219": case "UPR220":
      return 0; //Sigil of Protection
    default:
      return 2;
  }
}

function ItemDefaultHoldTriggerState($cardID)
{
  switch ($cardID) {
    case "ARC007": // Teklo Core
    case "ARC035": // Dissipation Shield
    case "EVR069": // Dissolution Sphere
    case "EVR071": // Signal Jammer
      return 0;
    default:
      return 2;
  }
}

function IsCharacterActive($player, $index)
{
  $character = &GetPlayerCharacter($player);
  return $character[$index + 9] == "1";
}

function ArsenalNumUsesPerTurn($cardID)
{
  switch ($cardID) {
    default:
      return 1;
  }
}

function HasReprise($cardID)
{
  switch ($cardID) {
    case "WTR118":
    case "WTR120":
    case "WTR121":
    case "WTR123":
    case "WTR124":
    case "WTR125":
    case "WTR132":
    case "WTR133":
    case "WTR134":
    case "WTR135":
    case "WTR136":
    case "WTR137":
    case "WTR138":
    case "WTR139":
    case "WTR140":
    case "CRU083":
    case "CRU088":
    case "CRU089":
    case "CRU090":
      return true;
    default:
      return false;
  }
}

//Is it active AS OF THIS MOMENT?
function RepriseActive()
{
  return CachedNumBlockedFromHand() > 0;
}

function HasCombo($cardID)
{
  switch ($cardID) {
    case "WTR081":
    case "WTR083":
    case "WTR084":
    case "WTR085":
    case "WTR086":
    case "WTR087":
    case "WTR088":
    case "WTR089":
    case "WTR090":
    case "WTR091":
    case "WTR095":
    case "WTR096":
    case "WTR097":
    case "WTR104":
    case "WTR105":
    case "WTR106":
    case "WTR110":
    case "WTR111":
    case "WTR112":
      return true;
    case "CRU054":
    case "CRU055":
    case "CRU056":
    case "CRU057":
    case "CRU058":
    case "CRU059":
    case "CRU060":
    case "CRU061":
    case "CRU062":
      return true;
    case "EVR038":
    case "EVR040":
    case "EVR041":
    case "EVR042":
    case "EVR043":
      return true;
  }
  return false;
}

function ComboActive($cardID = "")
{
  global $combatChainState, $CCS_LastAttack, $combatChain;
  if ($cardID == "" && count($combatChain) > 0) $cardID = $combatChain[0];
  if ($cardID == "") return false;
  $LA = $combatChainState[$CCS_LastAttack];
  if ($LA == "NA") return false;
  switch ($cardID) {
    case "WTR081":
      return $LA == "WTR083";
    case "WTR083":
      return $LA == "WTR110" || $LA == "WTR111" || $LA == "WTR112";
    case "WTR084":
      return $LA == "WTR104" || $LA == "WTR105" || $LA == "WTR106";
    case "WTR085":
      return $LA == "WTR095" || $LA == "WTR096" || $LA == "WTR097";
    case "WTR086":
    case "WTR087":
    case "WTR088":
      return $LA == "WTR095" || $LA == "WTR096" || $LA == "WTR097";
    case "WTR089":
    case "WTR090":
    case "WTR091":
      return $LA == "WTR104" || $LA == "WTR105" || $LA == "WTR106";
    case "WTR095":
    case "WTR096":
    case "WTR097":
      return $LA == "WTR098" || $LA == "WTR099" || $LA == "WTR100";
    case "WTR104":
    case "WTR105":
    case "WTR106":
      return $LA == "WTR101" || $LA == "WTR102" || $LA == "WTR103";
    case "WTR110":
    case "WTR111":
    case "WTR112":
      return $LA == "WTR107" || $LA == "WTR108" || $LA == "WTR109";
    case "CRU054":
      return $LA == "CRU057" || $LA == "CRU058" || $LA == "CRU059";
    case "CRU055":
      return $LA == "CRU055" || $LA == "CRU060" || $LA == "CRU061" || $LA == "CRU062";
    case "CRU056":
      return $LA == "CRU057" || $LA == "CRU058" || $LA == "CRU059";
    case "CRU057":
    case "CRU058":
    case "CRU059":
      return $LA == "CRU066" || $LA == "CRU067" || $LA == "CRU068";
    case "CRU060":
    case "CRU061":
    case "CRU062":
      return $LA == "CRU069" || $LA == "CRU070" || $LA == "CRU071";
    case "EVR038":
      return $LA == "CRU060" || $LA == "CRU061" || $LA == "CRU062" || $LA == "CRU055";
    case "EVR040":
      return $LA == "EVR041" || $LA == "EVR042" || $LA == "EVR043";
    case "EVR041":
    case "EVR042":
    case "EVR043":
      return $LA == "EVR041" || $LA == "EVR042" || $LA == "EVR043";
  }
  return false;
}

function HasBloodDebt($cardID)
{
  switch ($cardID) {
      //Shadow Brute
    case "MON123";
      return true;
    case "MON124";
      return true;
    case "MON125";
      return true;
    case "MON126":
    case "MON127":
    case "MON128";
      return true;
    case "MON129":
    case "MON130":
    case "MON131";
      return true;
    case "MON135":
    case "MON136":
    case "MON137";
      return true;
    case "MON138":
    case "MON139":
    case "MON140";
      return true;
    case "MON141":
    case "MON142":
    case "MON143";
      return true;
    case "MON144":
    case "MON145":
    case "MON146";
      return true;
    case "MON147":
    case "MON148":
    case "MON149";
      return true;
      //Shadow Runeblade
    case "MON156":
    case "MON158":
    case "MON159":
    case "MON160":
    case "MON161":
    case "MON165":
    case "MON166":
    case "MON167":
    case "MON168":
    case "MON169":
    case "MON170":
    case "MON171":
    case "MON172":
    case "MON173":
    case "MON174":
    case "MON175":
    case "MON176":
    case "MON177":
    case "MON178":
    case "MON179":
    case "MON180":
    case "MON181":
    case "MON182":
    case "MON183":
    case "MON184":
    case "MON185":
      return true;
      //Shadow
    case "MON187":
    case "MON191":
    case "MON192":
    case "MON194":
    case "MON200":
    case "MON201":
    case "MON202":
    case "MON203":
    case "MON204":
    case "MON205":
    case "MON209":
    case "MON210":
    case "MON211":
      return true;
    default:
      return false;
  }
}

function PlayableFromBanish($cardID)
{
  global $currentPlayer, $CS_NumNonAttackCards, $CS_Num6PowBan;
  switch ($cardID) {
      //Shadow Brute
    case "MON123":
      return GetClassState($currentPlayer, $CS_Num6PowBan) > 0;
      //Shadow Runeblade
    case "MON156":
    case "MON158":
      return true;
    case "MON159":
    case "MON160":
    case "MON161":
      return GetClassState($currentPlayer, $CS_NumNonAttackCards) > 0;
    case "MON165":
    case "MON166":
    case "MON167":
      return true;
    case "MON168":
    case "MON169":
    case "MON170":
      return GetClassState($currentPlayer, $CS_NumNonAttackCards) > 0;
    case "MON171":
    case "MON172":
    case "MON173":
      return true;
    case "MON174":
    case "MON175":
    case "MON176":
      return true;
    case "MON177":
    case "MON178":
    case "MON179":
      return true;
    case "MON180":
    case "MON181":
    case "MON182":
      return true;
    case "MON183":
    case "MON184":
    case "MON185":
      return true;
      //Shadow
    case "MON190":
      return true; //Eclipse - Since play is restricted by num played, it's fine to not restrict this
    case "MON191":
    case "MON194":
      return true;
    case "MON200":
    case "MON201":
    case "MON202":
    case "MON203":
    case "MON204":
    case "MON205":
    case "MON209":
    case "MON210":
    case "MON211":
      return true;
  }
}

function AbilityPlayableFromBanish($cardID)
{
  switch ($cardID) {
    case "MON192":
      return true;
    default:
      return false;
  }
}

function RequiresDieRoll($cardID, $from, $player)
{
  global $turn;
  if (GetDieRoll($player) > 0) return false;
  if ($turn[0] == "B") return false;
  $type = CardType($cardID);
  if ($type == "AA" && AttackValue($cardID) >= 6 && (SearchCharacterActive($player, "CRU002") || SearchCurrentTurnEffects("CRU002-SHIYANA", $player))) return true;
  switch ($cardID) {
    case "WTR004":
    case "WTR005":
      return true;
    case "WTR010":
      return true;
    case "WTR162":
      return $from == "PLAY";
    case "CRU009":
      return true;
    case "EVR004":
      return true;
    case "EVR014":
    case "EVR015":
    case "EVR016":
      return true;
  }
  return false;
}

function SpellVoidAmount($cardID)
{
  switch ($cardID) {
    case "ELE173":
      return 2;
    case "MON061":
      return 2;
    case "MON090":
      return 1;
    case "MON188":
      return 2;
    case "MON302":
      return 1;
    case "MON400":
      return 1;
    case "MON401":
      return 1;
    case "MON402":
      return 1;
  }
  return 0;
}

function IsSpecialization($cardID)
{
  switch ($cardID) {
    case "WTR006":
    case "WTR009":
    case "WTR043":
    case "WTR047":
    case "WTR081":
    case "WTR083":
    case "WTR119":
    case "WTR121":
      return true;
    case "ARC007":
    case "ARC009":
    case "ARC043":
    case "ARC046":
    case "ARC080":
    case "ARC083":
    case "ARC118":
    case "ARC121":
      return true;
    case "CRU000":
    case "CRU074":
      return true;
    case "MON005":
    case "MON007":
    case "MON035":
    case "MON189":
    case "MON190":
    case "MON198":
    case "MON199":
      return true;
    case "ELE004":
    case "ELE036":
    case "ELE066":
      return true;
    case "EVR003":
    case "EVR039":
    case "EVR055":
    case "EVR070":
      return true;
    case "DVR008":
    case "RVD008":
      return true;
    case "UPR090":
    case "UPR091":
    case "UPR109":
    case "UPR126":
      return true;
    default:
      return false;
  }
}

function Is1H($cardID)
{
  switch ($cardID) {
    case "WTR078":
    case "CRU049":
      return true;
    case "CRU004":
    case "CRU005":
      return true;
    case "CRU051":
    case "CRU052":
      return true;
    case "CRU079":
    case "CRU080":
      return true;
    case "MON105":
    case "MON106":
      return true;
    case "ELE003":
    case "ELE202":
      return true;
    default:
      return false;
  }
}

function AbilityPlayableFromCombatChain($cardID)
{
  switch ($cardID) {
    case "MON245":
      return true;
    case "MON281":
    case "MON282":
    case "MON283":
      return true;
    case "ELE195":
    case "ELE196":
    case "ELE197":
      return true;
    case "EVR157":
      return true;
    default:
      return false;
  }
}

function CardCaresAboutPitch($cardID)
{
  global $currentPlayer;
  if(SearchCurrentTurnEffects("ELE001-SHIYANA", $currentPlayer) || SearchCurrentTurnEffects("ELE002-SHIYANA", $currentPlayer)) return true;
  switch ($cardID) {
    case "ELE001":
    case "ELE002":
    case "ELE003":
      return true;
    default:
      return false;
  }
}

function CardHasAltArt($cardID)
{
  switch ($cardID) {
    case "WTR002": case "WTR162": case "WTR224": 
      return true;
    case "MON155": case "MON215": case "MON216": 
    case "MON217": case "MON219": case "MON220": 
      return true;
    case "ELE146": 
      return true;
    case "UPR006": case "UPR007": case "UPR008":
    case "UPR009": case "UPR010": case "UPR011":
    case "UPR012": case "UPR013": case "UPR014":
    case "UPR015": case "UPR016": case "UPR017":
      return true;
    case "UPR042": case "UPR043": case "UPR169":
      return true;
    case "UPR406": case "UPR407": case "UPR408":
    case "UPR409": case "UPR410": case "UPR411":
    case "UPR412": case "UPR413": case "UPR414":
    case "UPR415": case "UPR416": case "UPR417":
      return true;
    case "DYN234": 
      return true;
  default:
      return false;
  }
}

function isIyslander($character)
{
  switch ($character) {
    case 'EVR120':
    case 'UPR102':
    case 'UPR103':
      return true;
    default:
      return false;
  }
}