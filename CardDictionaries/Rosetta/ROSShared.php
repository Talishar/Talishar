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
function ROSAbilityType($cardID): string
{
  return match ($cardID) {
    "ROS007", "ROS008", "ROS019", "ROS020", "ROS021", "ROS030", "ROS071", "ROS073", "ROS164", "ROS212", "ROS213",
    "ROS214", "ROS249", "ROS250", "ROS163" => "I",
    "ROS015", "ROS115", "ROS116", "ROS165" => "A",
    "ROS003", "ROS009" => "AA",
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
    "ROS003", "ROS007", "ROS008", "ROS249" => 2,
    "ROS009", "ROS071", "ROS250" => 1,
    "ROS021" => HasAuraWithSigilInName($currentPlayer) ? 0 : 1,
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
  return match ($cardID) {
    "ROS015", "ROS115", "ROS116", "ROS165" => true,
    default => false,
  };
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
    "ROS066", "ROS112", "ROS129" => 1,
    "ROS052", "ROS053", "ROS054", "ROS065", "ROS111", "ROS128" => 2,
    "ROS064", "ROS110", "ROS127", "ROS248" => 3,
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
    "ROS110", "ROS111", "ROS112" => CardType($attackID) == "AA" && CardCost($attackID) <= 1,
    "ROS127", "ROS128", "ROS129", "ROS119" => ClassContains($attackID, "RUNEBLADE", $mainPlayer),
    "ROS118" => CardType($attackID) == "AA" && ClassContains($attackID, "RUNEBLADE", $mainPlayer),
    "ROS248" => CardSubType($attackID) == "Sword", // this conditional should remove both the buff and 2x attack bonus go again.
    "ROS049", "ROS050", "ROS051" => true, //blossoming decay
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
  global $currentPlayer, $CS_DamagePrevention, $CS_NumLightningPlayed, $CCS_NextInstantBouncesAura, $combatChainState, $CS_ArcaneDamageTaken;
  global $currentPlayer, $CS_DamagePrevention, $CS_NumLightningPlayed, $CS_ActionsPlayed;
  global $combatChainState;
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
      $ampAmount = GetClassState($currentPlayer, $CS_NumLightningPlayed);
      AddCurrentTurnEffect($cardID . "," . $ampAmount, $currentPlayer, "ABILITY");
      return CardLink($cardID, $cardID) . " is amping " . $ampAmount;
    case "ROS120":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      IncrementClassState($currentPlayer, $CS_DamagePrevention, 2);
      return "";
    case "ROS248":
    case "ROS033":
    case "ROS165":
    case "ROS118":
    case "ROS119":
    case "ROS169":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ROS030":
      IncrementClassState($currentPlayer, $CS_DamagePrevention, 2);
      return "";
    case "ROS031":
      $decomposed = Decompose($currentPlayer, 2, 1);
      if ($decomposed) {
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "FELLINGOFTHECROWN", 1);
      }
      return "";
    case "ROS032":
      $decomposed = Decompose($currentPlayer, 2, 1);
      if ($decomposed) {
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "PLOWUNDER", 1);
      }
      return "";
    case "ROS035":
      IncrementClassState($currentPlayer, $CS_DamagePrevention, 5);
      return CardLink($cardID, $cardID) . " is preventing the next 5 damage.";
    case "ROS039":
    case "ROS040":
    case "ROS041":
      $decomposed = Decompose($currentPlayer, 2, 1);
      if ($decomposed) {
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "SUMMERSFALL", 1);
      }
      return "";
    case "ROS042":
    case "ROS043":
    case "ROS044":
      $decomposed = Decompose($currentPlayer, 2, 1);
      if ($decomposed) {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "ROOTBOUNDCARAPACE", 1);
      }
      return "";
    case "ROS049":
    case "ROS050":
    case "ROS051":
      $decomposed = Decompose($currentPlayer, 2, 1);
      if ($decomposed) {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "BLOSSOMINGDECAY", 1);
      }
      return "";
    case "ROS052":
    case "ROS053":
    case "ROS054":
      $decomposed = Decompose($currentPlayer, 2, 1);
      if ($decomposed) {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "CADAVEROUSTILLING", 1);
      }
      return "";
    case "ROS055":
    case "ROS056":
    case "ROS057":
      if (GetResolvedAbilityType($cardID, "HAND") == "I") {
        GainHealth(2, $currentPlayer);
      }
      return "";
    case "ROS067": //fertile ground red
      $earthCountInBanish = SearchCount(SearchBanish($currentPlayer, talent: "EARTH"));
      WriteLog($earthCountInBanish . " earth cards in banish");
      if ($earthCountInBanish >= 4) {
        GainHealth(5, $currentPlayer);
      } else {
        GainHealth(2, $currentPlayer);
      }
      return "";
    case "ROS068": //fertile ground yellow
      $earthCountInBanish = SearchCount(SearchBanish($currentPlayer, talent: "EARTH"));
      if ($earthCountInBanish >= 4) {
        GainHealth(4, $currentPlayer);
      } else {
        GainHealth(2, $currentPlayer);
      }
      return "";
    case "ROS069": //fertile ground blue
      $earthCountInBanish = SearchCount(SearchBanish($currentPlayer, talent: "EARTH"));
      if ($earthCountInBanish >= 4) {
        GainHealth(3, $currentPlayer);
      } else {
        GainHealth(2, $currentPlayer);
      }
      return "";
    case "ROS071":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ROS073":
      IncrementClassState($currentPlayer, $CS_DamagePrevention, 2);
      return "";
    case "ROS078":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      Writelog(CardLink($cardID, $cardID) . " is amping 1");
      return "";
    case "ROS079":
    case "ROS080":
    case "ROS081":
      $combatChainState[$CCS_NextInstantBouncesAura] = 1;
      return "";
    case "ROS104":
    case "ROS105":
    case "ROS106":
      if (GetResolvedAbilityType($cardID, "HAND") == "I") {
        IncrementClassState($currentPlayer, $CS_DamagePrevention, 2);
        return CardLink($cardID, $cardID) . " is preventing the next 2 damage.";
      }
      return "";
    case "ROS110":
    case "ROS111":
    case "ROS112":
      AddCurrentTurnEffect($cardID, $currentPlayer); //electrostatic dicharge
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
    case "ROS116":
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
      AddCurrentTurnEffect("ROS163", $currentPlayer);
      return "";
    case "ROS179":
    case "ROS180":
    case "ROS181":
      $numCardsShuffled = match ($cardID) {
        "ROS179" => 3,
        "ROS180" => 2,
        "ROS181" => 1
      };
      $actions = SearchDiscard($currentPlayer, "A");
      PlayAura("DYN244", $currentPlayer);
      if ($actions == "") return "";
      AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, $numCardsShuffled . "-" . $actions);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "REMEMBRANCE", 1);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      return "";
    case "ROS186":
    case "ROS187":
    case "ROS188":
      if (GetResolvedAbilityType($cardID, "HAND") == "I") {
        AddCurrentTurnEffect($cardID, $currentPlayer, from: "ABILITY");
      } else {
        DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      }
      return "";
    case "ROS192":
    case "ROS193":
    case "ROS194":
      $ampAmount = match ($cardID) {
        "ROS192" => 3,
        "ROS193" => 2,
        "ROS194" => 1
      };
      AddCurrentTurnEffect($cardID . "," . $ampAmount, $currentPlayer, "PLAY");
      return CardLink($cardID, $cardID) . " is amping " . $ampAmount;
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
    case "ROS204":
    case "ROS205":
    case "ROS206":
      if (GetResolvedAbilityType($cardID, "HAND") == "I") {
        AddCurrentTurnEffect($cardID, $currentPlayer, from: "ABILITY");
      } else {
        DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      }
      return "";
    case "ROS217":
      AddCurrentTurnEffect($cardID, $currentPlayer);
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
    case "ROS115":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      AddDecisionQueue("GAINRESOURCES", $currentPlayer, "1", 1);
      return "";
    case "ROS164":
      GainResources($currentPlayer, 1);
      return "";
    case "ROS173":
    case "ROS174":
    case "ROS175":
      $sigils = SearchAura($currentPlayer, nameIncludes: "Sigil");
      $numSigils = count(explode(",", $sigils));
      DealArcane(ArcaneDamage($cardID) + $numSigils, 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "ROS212":
    case "ROS213":
    case "ROS214":
      IncrementClassState($currentPlayer, $CS_DamagePrevention);
      return "";
    case "ROS231":
    case "ROS232":
    case "ROS233":
      if (GetClassState($currentPlayer, $CS_ArcaneDamageTaken) > 0) {
        $HealthGain = match ($cardID) {
          "ROS231" => 4,
          "ROS232" => 3,
          "ROS233" => 2
        };
        GainHealth($HealthGain, $currentPlayer);
      } else {
        GainHealth(1, $currentPlayer);
      }
      return "";
    case "ROS244":
      if (IsHeroAttackTarget()) AskWager($cardID);
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
    case "ROS250":
      PlayAura("MON104", $currentPlayer, 1);
      return "";
    case "ROS252":
      PutPermanentIntoPlay($currentPlayer, $cardID);
      return "";
    default:
      return "";
  }
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
    case "ROS117":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
      AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $defPlayer, "-", 1);
      AddDecisionQueue("DISCARDCARD", $defPlayer, "HAND-".$defPlayer, 1);
      break;
    case "ROS216":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRAURAS");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
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
