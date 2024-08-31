<?php


function ROSAbilityType($cardID, $index = -1): string
{
  return match ($cardID) {
    "ROS007", "ROS008", "ROS019", "ROS020" => "I",
    "ROS003", "ROS009" => "AA",
    default => ""
  };
}

function ROSAbilityCost($cardID): int
{
  return match ($cardID) {
    "ROS003", "ROS007", "ROS008" => 2,
    "ROS009" => 1,
    default => 0
  };
}

function ROSEffectAttackModifier($cardID): int
{
  return match ($cardID) {
    "ROS052", "ROS053", "ROS054" => 2,
    "ROS248" => 3,
    default => 0,
  };
}

function ROSCombatEffectActive($cardID, $attackID): bool|string
{
  global $mainPlayer;
  return match ($cardID) {
    "ROS052", "ROS053", "ROS054" => true,
    "ROS042", "ROS043", "ROS044" => true,
    "ROS248" => CardSubType($attackID) == "Sword", // this conditional should remove both the buff and 2x attack bonus go again.
    default => "",
  };
}

function ROSPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $CS_DamagePrevention;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);

  switch ($cardID) {
    case "ROS004":
      $xVal = $resourcesPaid / 2;
      for ($i = 0; $i <= $xVal; $i++) {
        AddDecisionQueue("CHOOSECARD", $currentPlayer, "ARC112" . "," . "ELE109");
        AddDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
      }
      AddDecisionQueue("GAINLIFE", $currentPlayer, $xVal + 1);
      return "";
    case "ROS007":
    case "ROS008":
      PlayAura("ELE110", $currentPlayer);
      return "";
    case "ROS016":
      GainHealth(1, $currentPlayer);
      GainHealth(1, $currentPlayer);
      GainHealth(1, $currentPlayer);
      return "";
    case "ROS019":
    case "ROS020":
      Draw($currentPlayer);
      return "";
    case "ROS033":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ROS031":
      Decompose($currentPlayer, 2, 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "FELLINGOFTHECROWN", 1);
      return "";
    case "ROS035":
      IncrementClassState($currentPlayer, $CS_DamagePrevention, 5);
      return "Seeds of Tomorrow is preventing the next 5 damage.";
    case "ROS042":
    case "ROS043":
    case "ROS044":
      Decompose($currentPlayer, 2, 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "ROOTBOUNDCARAPACE", 1);
      return "";
    case "ROS052":
    case "ROS053":
    case "ROS054":
      Decompose($currentPlayer, 2, 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "CADAVEROUSTILLING", 1);
      return "";
    case "ROS055":
    case "ROS056":
    case "ROS057":
      if (GetResolvedAbilityType($cardID, "HAND") == "I") {
        GainHealth(2, $currentPlayer);
      }
      return "";
    case "ROS155":
    case "ROS156":
    case "ROS157":
      $numRuneChantsCreated = match ($cardID) {"ROS155" => 3, "ROS156" => 2, "ROS157" => 1};
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      for($i = 0; $i < $numRuneChantsCreated; ++$i){
        AddDecisionQueue("PLAYAURA", $currentPlayer, "ARC112", 1);
      }
      return "";
    case "ROS247":
      LookAtHand($otherPlayer);
      LookAtArsenal($otherPlayer);
      AddNextTurnEffect($cardID . "-1", $otherPlayer);
      MZMoveCard($currentPlayer, "MYDECK:subtype=Trap", "MYHAND", may: true);
      MZMoveCard($currentPlayer, "MYDECK:subtype=Trap", "MYHAND", may: true);
      MZMoveCard($currentPlayer, "MYDECK:subtype=Trap", "MYHAND", may: true);
      AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
      AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, 2 . "-", 1);
      AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIADDDECK", $currentPlayer, "-", 1);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
      return "";
    case "ROS248":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    default:
      return "";
  }
}

function ROSHitEffect($cardID): void
{
  global $currentPlayer, $defPlayer;
  switch ($cardID) {
    case "ROS082":
    case "ROS083":
    case "ROS084":
      PlayAura("ELE110", $currentPlayer);
      break;
    case "ROS036":
    case "ROS037":
    case "ROS038":
      PlayAura("ELE109", $currentPlayer);
      break;
    case "ROS220":
      if (ArsenalHasFaceDownCard($defPlayer)) {
        SetArsenalFacing("UP", $defPlayer);
      }
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRARS:type=A");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card you want to banish", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZBANISH", $currentPlayer, "THEIRARS", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      break;
    case "ROS221":
      if (ArsenalHasFaceDownCard($defPlayer)) {
        SetArsenalFacing("UP", $defPlayer);
      }
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRARS:type=AA");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card you want to banish", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZBANISH", $currentPlayer, "THEIRARS", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      break;
    case "ROS222":
      if (ArsenalHasFaceDownCard($defPlayer)) {
        SetArsenalFacing("UP", $defPlayer);
      }
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRARS:type=I");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card you want to banish", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZBANISH", $currentPlayer, "THEIRARS", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      break;
    default:
      break;
  }
}

function GetTrapIndices($player)
{
  return SearchDeck($player, subtype: "Trap");
}