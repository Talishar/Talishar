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
    "ROS007", "ROS008", "ROS019", "ROS020", "ROS021", "ROS213" => "I",
    "ROS015" => "A",
    "ROS003", "ROS009" => "AA",
    "ROS071", "ROS163" => "I",
    default => ""
  };
}

/**
 * Defines the resource cost of a particular card's non-play ability. For example: Star Fall's (ROS009) ability cost's 1 resource to activate.
 * Novel additional costs (ie. Destroying Gold) is handled by PayAdditionalCosts().
 * This function is meant to handle cards from the Rosetta set.
 *
 * @param string $cardID - an id that maps to a FaB card
 * @return integer - the number of resources which must be paid for the ability
 */
function ROSAbilityCost($cardID): int
{
  global $currentPlayer;
  return match ($cardID) {
    "ROS015" => 3,
    "ROS003", "ROS007", "ROS008" => 2,
    "ROS009" => 1,
    "ROS021" => HasAuraWithSigilInName($currentPlayer) ? 0 : 1,
    "ROS071" => 1,
    default => 0
  };
}

/**
 * Sub function for AbilityHasGoAgain that will indicate whether or not a cards sub ability has go again
 * This function is meant to handle cards from the Rosetta set.
 *
 * @param string $cardID - an id that maps to a FaB card
 * @return boolean - true if the ability should have go again and false if not
 */
function ROSAbilityHasGoAgain($cardID): bool
{
  switch ($cardID) {
    case "ROS015":
      return true;
    default:
      return false;
  }
}

/**
 * If an active effect would add attack value to current or future attack, this defies how much attack value it will add.
 * This function is meant to handle cards from the Rosetta set.
 *
 * @param string $cardID - an id that maps to a FaB card
 * @return integer - the number of attack value that will be added
 */
function ROSEffectAttackModifier($cardID): int
{
  return match ($cardID) {
    "ROS066", "ROS129" => 1,
    "ROS052", "ROS053", "ROS054", "ROS065", "ROS128" => 2,
    "ROS064", "ROS127", "ROS248" => 3,
    default => 0,
  };
}

/**
 * Defines if an combat effect should activate given certain characteristics of the board state.
 * This function is meant to handle cards from the Rosetta set.
 *
 * @param string $cardID - the id effect that is being evaluate
 * @param string $attackID - the id of the card that is doing tha actual attack
 * @return bool - true if the effect is active and should be applied, false otherwise
 */
function ROSCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer, $CombatChain;
  return match ($cardID) {
    "ROS042", "ROS043", "ROS044", "ROS052", "ROS053", "ROS054" => true,
    "ROS064", "ROS065", "ROS066" => true,
    "ROS127", "ROS128", "ROS129" => ClassContains($attackID, "RUNEBLADE", $mainPlayer),
    "ROS248" => CardSubType($attackID) == "Sword", // this conditional should remove both the buff and 2x attack bonus go again.
    default => false,
  };
}

/**
 * Defines the on resolution effects of cards and abilities
 * This function is meant to handle cards from the Rosetta set.
 *
 * @param string $cardID - the id effect that is being evaluate
 * @param string $from - caps string that indicates where an effect is coming from PLAY/ABLITY are common values
 * @param string $resourcesPaid - the number of resources that are paid into the effect. useful for cards with variable costs.
 * @param string $target - for when a card has multiple possble targets
 * @param string $additionalCosts - list of cards that is defined by a broader context usually to give a bonus effect (brutes discarding a card then checkin if the card is a 6 is a common use case)
 * @return string - a log message that will be displayed upon resolution
 */
function ROSPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $CS_DamagePrevention, $CS_NumLightningPlayed;
  global $combatChain;
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
    case "ROS015":
      AddCurrentTurnEffect($cardID . "-AMP", $currentPlayer, from: "ABILITY");
      if (SearchCardList($additionalCosts, $currentPlayer, talent: "EARTH") != "") {
        AddCurrentTurnEffect($cardID, $currentPlayer, from: "ABILITY");
      }
      return CardLink($cardID, $cardID) . " is amping 1";
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
      return CardLink($cardID, $cardID) . " is amping " . $ampAmmount;
    case "ROS248":
    case "ROS033":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ROS031":
      Decompose($currentPlayer, 2, 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "FELLINGOFTHECROWN", 1);
      return "";
    case "ROS035":
      IncrementClassState($currentPlayer, $CS_DamagePrevention, 5);
      return CardLink($cardID, $cardID) . " is preventing the next 5 damage.";
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
    case "ROS071"://Lightning greaves
      AddCurrentTurnEffect($cardID, $currentPlayer);
      SetClassState($currentPlayer, $CS_LightningGreaves, 1);

      return "";
    case "ROS075"://Eclectic Magnetism
      // WriteLog(CardName($combatChain[0]) . "is the active chain link");
      AddCurrentTurnEffect($cardID, $currentPlayer);
      SetClassState($currentPlayer, $CS_NextNAAInstantEclecticMag, 1);
      //turn it off when the chain link ends?
      return "";
    case "ROS078"://High Voltage
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ROS204":
    case "ROS205":
    case "ROS206":
      if (GetResolvedAbilityType($cardID, "HAND") == "I") {
        AddCurrentTurnEffect("ROS204-AMP", $currentPlayer, from: "ABILITY");
      }
      return "";
    case "ROS104":
    case "ROS105":
    case "ROS106":
      if (GetResolvedAbilityType($cardID, "HAND") == "I") {
        IncrementClassState($currentPlayer, $CS_DamagePrevention, 2);
        return CardLink($cardID, $cardID) . " is preventing the next 2 damage.";
      }
      return "";
    case "ROS121":
    case "ROS122":
    case "ROS123":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      AddDecisionQueue("PLAYAURA", $currentPlayer, "ARC112", 1);
      return "";
    case "ROS127":
    case "ROS128":
    case "ROS129":
      AddCurrentTurnEffect($cardID, $currentPlayer);

      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIZONEINDICES", $otherPlayer, "MYAURAS", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $otherPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $otherPlayer, "-", 1);
      return "";
    case "ROS143":
    case "ROS144":
    case "ROS145":
      PlayAura("ARC112", $currentPlayer);
      return "";
    case "ROS155":
    case "ROS156":
    case "ROS157":
      $numRunechantsCreated = match ($cardID) {
        "ROS155" => 3,
        "ROS156" => 2,
        "ROS157" => 1
      };
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      for ($i = 0; $i < $numRunechantsCreated; ++$i) {
        AddDecisionQueue("PLAYAURA", $currentPlayer, "ARC112", 1);
      }
      return "";
    case "ROS163":
      SetClassState($currentPlayer, $CS_AmpWhenSigilLeaves, 1);
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
    case "ROS166"://destructive aethertide
    case "ROS167"://eternal inferno
    case "ROS176":
    case "ROS177":
    case "ROS178":
    case "ROS189":
    case "ROS190":
    case "ROS191":
    case "ROS195":
    case "ROS196":
    case "ROS197"://open the floodgates
    case "ROS198":
    case "ROS199":
    case "ROS200":
    case "ROS201":
    case "ROS202":
    case "ROS203":
    case "ROS207":
    case "ROS208":
    case "ROS209":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "ROS173":
    case "ROS174":
    case "ROS175":
      $sigils = SearchAura($currentPlayer, nameIncludes: "Sigil");
      $numSigils = count(explode(",", $sigils));
      DealArcane(ArcaneDamage($cardID) + $numSigils, 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "ROS223":
    case "ROS224":
    case "ROS225":
      $baseLife = match ($cardID) {
        "ROS223" => 2,
        "ROS224" => 1,
        "ROS225" => 0
      };
      $cardsInGraveyard = SearchCount(CombineThreeSearches(
        SearchDiscardForCard(1, "ROS223"),
        SearchDiscardForCard(1, "ROS224"),
        SearchDiscardForCard(1, "ROS225")
      ));
      GainHealth($cardsInGraveyard + $baseLife, $currentPlayer);
      return "";
    case "ROS213":
      IncrementClassState($currentPlayer, $CS_DamagePrevention);
      break;
    default:
      return "";
  }
  return "";
}

function ROSHitEffect($cardID): void
{
  global $currentPlayer, $defPlayer;
  switch ($cardID) {
    case "ROS036":
    case "ROS037":
    case "ROS038":
      PlayAura("ELE109", $currentPlayer);
      break;
    case "ROS082":
    case "ROS083":
    case "ROS084":
      PlayAura("ELE110", $currentPlayer);
      break;
    case "ROS121":
    case "ROS122":
    case "ROS123":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      AddDecisionQueue("PLAYAURA", $currentPlayer, "ARC112", 1);
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
    case "ROS243":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRBANISH:isIntimidated=true");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an intimidated card to put into the graveyard (The cards were intimated in left to right order)", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZADDZONE", $currentPlayer, "THEIRDISCARD", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "THEIRBANISH", 1);
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
 * Splatter Skull is going to use this list to destroy an opponent's card.
 *
 * @return array a list of indices of cards that are flagged as intimidated.
 */
function GetIntimidatedCards($player)
{
  $rv = "";
  $banish = &GetBanish($player);
  for ($i = count($banish) - BanishPieces(); $i >= 0; $i -= BanishPieces()) {
    if ($banish[$i + 1] == "INT") {
      if ($rv == "") $rv .= $i;
      else $rv .= "," . $i;
    }
  }
}

/**
 * Volzar needs to know if you control an aura with "Sigil" in its name
 *
 * @param integer $player - presumably the current player, the one who has activated volzar
 * @return boolean - true if a aura with sigil is found, false if no aura contains the name sigil
 */
function HasAuraWithSigilInName($player)
{
  $auras = &GetAuras($player);
  for ($i = 0; $i < count($auras); $i += AuraPieces()) {
    if (CardNameContains($auras[$i], "Sigil", $player, partial: true)) return true;
  }
  return false;
}
