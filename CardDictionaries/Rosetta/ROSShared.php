<?php

function ROSAbilityType($cardID): string
{
  return match ($cardID) {
    "ROS007", "ROS008", "ROS019", "ROS020", "ROS021", "ROS027",
    "ROS030", "ROS071", "ROS073", "ROS164", "ROS212", "ROS213",
    "ROS214", "ROS249", "ROS250", "ROS163" => "I",
    "ROS015", "ROS115", "ROS116", "ROS165" => "A",
    "ROS003", "ROS009" => "AA",
    default => ""
  };
}

function ROSAbilityCost($cardID): int
{
  global $currentPlayer;
  return match ($cardID) {
    "ROS015" => 3,
    "ROS003", "ROS007", "ROS008", "ROS027", "ROS249" => 2,
    "ROS009", "ROS071", "ROS250" => 1,
    "ROS021" => HasAuraWithSigilInName($currentPlayer) ? 0 : 1,
    default => 0
  };
}

function ROSAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "ROS015", "ROS115", "ROS116", "ROS165" => true,
    default => false,
  };
}

function ROSEffectAttackModifier($cardID): int
{
  return match ($cardID) {
    "ROS066", "ROS112", "ROS129" => 1,
    "ROS065", "ROS111", "ROS128" => 2,
    "ROS064", "ROS110", "ROS127", "ROS248" => 3,
    default => 0,
  };
}

function ROSCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match ($cardID) {
    "ROS064", "ROS065", "ROS066", "ROS012", "ROS076" => true,
    "ROS110", "ROS111", "ROS112" => CardType($attackID) == "AA" && CardCost($attackID) <= 1,
    "ROS127", "ROS128", "ROS129", "ROS119" => ClassContains($attackID, "RUNEBLADE", $mainPlayer),
    "ROS118" => CardType($attackID) == "AA" && ClassContains($attackID, "RUNEBLADE", $mainPlayer),
    "ROS010-GOAGAIN" => TypeContains($attackID, "AA", $mainPlayer) || TypeContains($attackID, "A", $mainPlayer), //Arc Lightning giving next action go again
    "ROS248" => CardSubType($attackID) == "Sword", // this conditional should remove both the buff and 2x attack bonus go again.
    default => false,
  };
}

function ROSPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $CS_DamagePrevention, $CS_NumLightningPlayed, $CCS_NextInstantBouncesAura, $combatChainState, $CS_ArcaneDamageTaken;
  global $currentPlayer, $CS_DamagePrevention, $CS_NumLightningPlayed, $CS_ActionsPlayed, $CCS_EclecticMag, $CS_DamageTaken;
  global $combatChainState, $turn;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);

  switch ($cardID) {
    case "ROS004":
      $xVal = $resourcesPaid / 2;
      for ($i = 0; $i <= $xVal; $i++) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which aura to create:");
        AddDecisionQueue("CHOOSECARD", $currentPlayer, "ARC112" . "," . "ELE109");
        AddDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
      }
      AddDecisionQueue("GAINLIFE", $currentPlayer, $xVal + 1);
      return "";
    case "ROS007":
    case "ROS008":
      PlayAura("ELE110", $currentPlayer);
      return "";
    case "ROS010":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect($cardID . "-GOAGAIN", $currentPlayer);
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
    case "ROS027":
      if($from == "HAND") {
        PutItemIntoPlayForPlayer("ROS027", $otherPlayer); //Work around for player to put it in play themselves (Mostly for Blitz LSS precons)
      }
      else{
        if($target != "-") AddCurrentTurnEffect($cardID, $currentPlayer, $from, GetMZCard($currentPlayer, $target));
        if(!SearchCurrentTurnEffects($cardID . "-1", $currentPlayer)) AddCurrentTurnEffect($cardID . "-1", $currentPlayer);  
      }
      return "";
    case "ROS030":
      IncrementClassState($currentPlayer, $CS_DamagePrevention, 2);
      return "";
    case "ROS031":
      $rv = Decompose($currentPlayer, "FELLINGOFTHECROWN");
      return $rv;
    case "ROS032":
      $rv = Decompose($currentPlayer, "PLOWUNDER");
      return $rv;
    case "ROS033":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ROS035":
      IncrementClassState($currentPlayer, $CS_DamagePrevention, 5);
      return CardLink($cardID, $cardID) . " is preventing the next 5 damage.";
    case "ROS039":
    case "ROS040":
    case "ROS041":
      $rv = Decompose($currentPlayer, "SUMMERSFALL");
      return $rv;
    case "ROS042":
    case "ROS043":
    case "ROS044":
      $rv = Decompose($currentPlayer, "ROOTBOUNDCARAPACE");
      return $rv;
    case "ROS049":
    case "ROS050":
    case "ROS051":
      $rv = Decompose($currentPlayer, "BLOSSOMINGDECAY");
      return $rv;
    case "ROS052":
    case "ROS053":
    case "ROS054":
      $rv = Decompose($currentPlayer, "CADAVEROUSTILLING");
      return $rv;
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
    case "ROS075":
      $combatChainState[$CCS_EclecticMag] = 1;
      return "";
    case "ROS076":
      AddCurrentTurnEffect($cardID, $currentPlayer);
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
    case "ROS085":
    case "ROS086":
    case "ROS087":
      $minCost = match ($cardID) {
        "ROS085" => 0,
        "ROS086" => 1,
        "ROS087" => 2
      };
      $options = SearchCombatChainLink($currentPlayer, "AA", minCost: $minCost);
      if($options != "" && SearchLayersForPhase("FINALIZECHAINLINK") == -1) {
        $max = count(explode(",", $options));
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "COMBATCHAINLINK:type=AA;minCost=".$minCost);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("MZSETDQVAR", $currentPlayer, "1", 1);
        AddDecisionQueue("WRITELOGCARDLINK", $currentPlayer, "{1} was chosen.", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID . "-{1}", 1);
        if($max > 1) {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "COMBATCHAINLINK:type=AA;minCost=".$minCost);
          AddDecisionQueue("REMOVEPREVIOUSCHOICES", $currentPlayer, "{0}", 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZSETDQVAR", $currentPlayer, "1", 1);
          AddDecisionQueue("WRITELOGCARDLINK", $currentPlayer, "{1} was chosen.", 1);
          AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID . "-{1}", 1);  
        }
      }
      return "";
    case "ROS101":
    case "ROS102":
    case "ROS103":
      if (GetClassState($otherPlayer, $CS_DamageTaken) > 0) GiveAttackGoAgain();
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
      AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      return "";
    case "ROS115":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      AddDecisionQueue("GAINRESOURCES", $currentPlayer, "1", 1);
      return "";
    case "ROS118":
    case "ROS119":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ROS120":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      IncrementClassState($currentPlayer, $CS_DamagePrevention, 2);
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
      $numRunechants = match ($cardID) {
        "ROS155" => 3,
        "ROS156" => 2,
        "ROS157" => 1
      };
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS&COMBATCHAINLINK:subtype=Aura");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      AddDecisionQueue("PLAYAURA", $currentPlayer, "ARC112-" . $numRunechants, 1);
      return "";
    case "ROS163":
      AddCurrentTurnEffect("ROS163", $currentPlayer);
      return "";
    case "ROS164":
      GainResources($currentPlayer, 1);
      return "";
    case "ROS165":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ROS169":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      IncrementClassState($currentPlayer, $CS_DamagePrevention, 2);
      return "";
    case "ROS170":
    case "ROS171":
    case "ROS172":
      if (GetResolvedAbilityType($cardID, "HAND") == "I" && $from == "HAND") {
        AddCurrentTurnEffect($cardID, $currentPlayer, from: "ABILITY");
      } else {
        DealArcane(ArcaneDamage($cardID), 2, "PLAYCARD", $cardID, resolvedTarget: $target);
      }
      return "";
    case "ROS173":
    case "ROS174":
    case "ROS175":
      $numSigils = 0;
      $sigils = SearchAura($currentPlayer, nameIncludes: "Sigil");
      if($sigils != "") $numSigils = count(explode(",", $sigils));
      DealArcane(ArcaneDamage($cardID) + $numSigils, 0, "PLAYCARD", $cardID, resolvedTarget: $target);
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
      if (GetResolvedAbilityType($cardID, "HAND") == "I" && $from == "HAND") {
        AddCurrentTurnEffect($cardID, $currentPlayer, from: "ABILITY");
      } else {
        DealArcane(ArcaneDamage($cardID), 2, "PLAYCARD", $cardID, resolvedTarget: $target);
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
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "ROS204":
    case "ROS205":
    case "ROS206":
      if (GetResolvedAbilityType($cardID, "HAND") == "I" && $from == "HAND") {
        AddCurrentTurnEffect($cardID, $currentPlayer, from: "ABILITY");
      } else {
        DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      }
      return "";
    case "ROS207":
    case "ROS208":
    case "ROS209":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "ROS217":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ROS223":
    case "ROS224":
    case "ROS225":
      $baseLife = match ($cardID) {
        "ROS223" => 3,
        "ROS224" => 2,
        "ROS225" => 1
      };
      $cardsInGraveyard = SearchCount(SearchDiscardForCard($currentPlayer, "ROS223", "ROS224", "ROS225"))-1; //-1 so it doesn't count itself as the card on Talishar goes in the graveyard before it finish resolving
      GainHealth($cardsInGraveyard + $baseLife, $currentPlayer); 
      return "";
    case "ROS212":
    case "ROS213":
    case "ROS214":
      IncrementClassState($currentPlayer, $CS_DamagePrevention);
      return "";
    case "ROS218":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "DECK");
      AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDDISCARD", $currentPlayer, "DECK", 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("WRITELOG", $currentPlayer, "<0> was chosen.", 1);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      return "";
    case "ROS219":
      AddNextTurnEffect($cardID, $currentPlayer);
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
    case "ROS248":
      AddCurrentTurnEffect($cardID, $currentPlayer);
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
      $myAuras = &GetAuras($currentPlayer);
      if (count($myAuras) > 0) {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDINDICES", $defPlayer, "HAND", 1);
        AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $defPlayer, "-", 1);
        AddDecisionQueue("DISCARDCARD", $defPlayer, "HAND-".$defPlayer, 1);
      }
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

function HasAuraWithSigilInName($player)
{
  $auras = &GetAuras($player);
  for ($i = 0; $i < count($auras); $i += AuraPieces()) {
    if (CardNameContains($auras[$i], "Sigil", $player, partial: true)) return true;
  }
  return false;
}
