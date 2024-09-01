<?php

/**
 * Defines the type of a particular card's non-play ablity. For example: Fyendal's Spring Tunic's ablity is an instant so we would return "I"
 * Other types include "AR" (Attack Reaction), "A" (Action), "AA" (Attack Action).
 * This function is meant to handle cards from the Rosetta set.
 *
 * @param string $cardID - an id that maps to a FaB card
 * @param integer $index
 * @return string - the type that defines when the corresponding ability may be played
 */
function ROSAbilityType($cardID, $index = -1): string
{
  return match ($cardID) {
    "ROS007", "ROS008", "ROS019", "ROS020", "ROS021" => "I",
    "ROS003", "ROS009" => "AA",
    default => ""
  };
}

/**
 * Defines the resource cost of a particular card's non-play ablity. For example: Star Fall's (ROS009) ability cost's 1 resource to activate.
 * Novel additional costs (ie. Destroying Gold) is handled by PayAddtionalCosts().
 * This function is meant to handle cards from the Rosetta set.
 *
 * @param string $cardID - an id that maps to a FaB card
 * @return integer the number of resources which must be paid for the ability
 */
function ROSAbilityCost($cardID): int
{
  global $currentPlayer;
  return match ($cardID) {
    "ROS003", "ROS007", "ROS008" => 2,
    "ROS009" => 1,
    "ROS021" => HasAuraWithSigilInName($currentPlayer) ? 0 : 1,
    default => 0
  };
}

/**
 * If an active effect would add attack value to current or future attack, this defies how much attack value it will add.
 * This function is meant to handle cards from the Rosetta set.
 * 
 * @param string $cardID - an id that maps to a FaB card
 * @return integer the number of attack value that will be added
 */
function ROSEffectAttackModifier($cardID): int
{
  return match ($cardID) {
    "ROS052", "ROS053", "ROS054" => 2,
    "ROS248" => 3,
    default => 0,
  };
}

/**
 * Defines if an combat effect should activate given certain characteristics of the board state.
 * This function is meant to handle cards from the Rosetta set.
 *
 * @param string $cardID - the id effect that is being evaluate
 * @param string $attackID - the id of the card that is doing tha actual attack
 * @return boolean true if the effect is active and should be applied, false otherwise
 */
function ROSCombatEffectActive($cardID, $attackID): bool|string
{
  global $mainPlayer;
  return match ($cardID) {
    "ROS052", "ROS053", "ROS054" => true,
    "ROS042", "ROS043", "ROS044" => true,
    "ROS248" => CardSubType($attackID) == "Sword", // this conditional should remove both the buff and 2x attack bonus go again.
    default => false,
  };
}

function ROSPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $CS_DamagePrevention, $CS_NumLightningPlayed;
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
    case "ROS021":
      $ampAmmount = GetClassState($currentPlayer, $CS_NumLightningPlayed);
      AddCurrentTurnEffect($cardID . "," . $ampAmmount, $currentPlayer, "ABILITY");
      return "Volzar is amping " . $ampAmmount;
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
    case "ROS039":
    case "ROS040":
    case "ROS041":
      Decompose($currentPlayer, 2, 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "SUMMERSFALL", 1);
      return "";
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
      $numRunechantsCreated = match ($cardID) {"ROS155" => 3, "ROS156" => 2, "ROS157" => 1};
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      for($i = 0; $i < $numRunechantsCreated; ++$i){
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

/**
 * Volzar needs to know if you control an aura with "Sigil" in its name
 *
 * @param integer $player - presumably the current player, the one who has activated volzar
 * @return boolean true if a aura with sigil is found, false if no aura contains the name sigil
 */
function HasAuraWithSigilInName($player)
{
  $auras = &GetAuras($player);
  for($i=0; $i<count($auras); $i+=AuraPieces())
  {
    if(CardNameContains($auras[$i], "Sigil", $player, partial: true)) return true;
  }
  return false;
}