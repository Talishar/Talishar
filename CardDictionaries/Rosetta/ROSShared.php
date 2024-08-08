<?php


function ROSAbilityType($cardID, $index = -1): string
{
  return match ($cardID) {
    "ROS007", "ROS008", "ROS019", "ROS020" => "I",
    "ROS009" => "AA",
    "ROS018" => "A,I",
    default => ""
  };
}

function ROSAbilityCost($cardID): int
{
  return match ($cardID) {
    "ROS007", "ROS008" => 2,
    "ROS009" => 1,
    default => 0
  };
}

function ROSPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer;
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
    case "ROS019":
    case "ROS020":
      Draw($currentPlayer);
      return "";
    case "ROS033":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ROS016":
      GainHealth(1, $currentPlayer);
      GainHealth(1, $currentPlayer);
      GainHealth(1, $currentPlayer);
      return "";
    case "ROS031":
      if (Decompose($currentPlayer, 2, 1)) {
        BottomDeck($currentPlayer);
        BottomDeck($otherPlayer);
      }
      return "";
    case "ROS247":
      LookAtHand($otherPlayer);
      LootAtArsenal($otherPlayer);
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
    case "ROS018":
      // AddLayer("TRIGGER", $currentPlayer, "DYN214", "-", "-");
      PlayMeldCard($currentPlayer, $cardID);
      return "";
    default:
      return "";
  }
}

function PlayMeldCard($player, $cardID): void
{
  AddDecisionQueue("SETDQCONTEXT", $player, "Choose which side to play");
  AddDecisionQueue("BUTTONINPUT", $player, "Left,Meld,Right");
  AddDecisionQueue("SHOWMODES", $player, $cardID, 1);
  AddDecisionQueue("MODAL", $player, "MELDCARD", 1);
}

function ROSHitEffect($cardID): void
{
  global $currentPlayer;
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
    default:
      break;
  }
}

function GetTrapIndices($player)
{
  return SearchDeck($player, subtype: "Trap");
}