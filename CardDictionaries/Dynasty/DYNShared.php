<?php

function DYNAbilityCost($cardID)
{
  switch($cardID) {
    case "DYN001": return 3;
    case "DYN005": return 3;
    case "DYN025": return 3;
    case "DYN067": return 1;
    case "DYN068": return 3;
    case "DYN069": case "DYN070": return 1;
    case "DYN115": case "DYN116": return 2;
    case "DYN151": return 1;
    case "DYN172": return 3;
    case "DYN192": return 2;
    case "DYN193": return 3;
    case "DYN235": return 1;
    case "DYN242": return 1;
    case "DYN243": return 2;
    case "DYN612": return 2;
    default: return 0;
  }
}

function DYNAbilityType($cardID, $index = -1)
{
  switch($cardID) {
    case "DYN001": return "A";
    case "DYN005": return "AA";
    case "DYN046": return "I";
    case "DYN067": return "AA";
    case "DYN068": return "AA";
    case "DYN069": case "DYN070": return "AA";
    case "DYN088": return "AA";
    case "DYN025": return "I";
    case "DYN115": case "DYN116": return "AA";
    case "DYN117": return "AR";
    case "DYN118": return "AR";
    case "DYN151": return "A";
    case "DYN171": return "I";
    case "DYN172": return "A";
    case "DYN192": return "A";
    case "DYN193": return "A";
    case "DYN235": return "I";
    case "DYN240": return "A";
    case "DYN241": return "A";
    case "DYN242": case "DYN243": return "A";
    case "DYN492a": return "AA";
    case "DYN612": return "AA";
    default: return "";
  }
}

function DYNAbilityHasGoAgain($cardID)
{
  switch($cardID) {
    case "DYN151": case "DYN192": case "DYN240": case "DYN243": return true;
    default: return false;
  }
}

function DYNEffectAttackModifier($cardID)
{
  global $mainPlayer;
  $params = explode(",", $cardID);
  $cardID = $params[0];
  if(count($params) > 1) $parameter = $params[1];
  switch($cardID) {
    case "DYN007": return 6;
    case "DYN013": return 3;
    case "DYN014": return 2;
    case "DYN015": return 1;
    case "DYN019": case "DYN020": case "DYN021": return 3;
    case "DYN022": return 4;
    case "DYN023": return 3;
    case "DYN024": return 2;
    case "DYN028": return 1;
    case "DYN046": return 2;
    case "DYN049": return 1;
    case "DYN053": return 3;
    case "DYN054": return 2;
    case "DYN055": return 1;
    case "DYN071": return 4;
		case "DYN073": return 3;
    case "DYN074": return 2;
    case "DYN075": return 1;
    case "DYN076": return (NumEquipBlock() > 0 ? 3 : 0);
    case "DYN077": return (NumEquipBlock() > 0 ? 2 : 0);
    case "DYN078": return (NumEquipBlock() > 0 ? 1 : 0);
    case "DYN082": return 6;
    case "DYN083": return 5;
    case "DYN084": return 4;
    case "DYN085": return (NumEquipBlock() > 0 ? 3 : 0);
    case "DYN086": return (NumEquipBlock() > 0 ? 2 : 0);
    case "DYN087": return (NumEquipBlock() > 0 ? 1 : 0);
    case "DYN089-UNDER": return 1;
    case "DYN091-1": return 3;
    case "DYN148": return 3;
    case "DYN149": return 2;
    case "DYN150": return 1;
    case "DYN155": return 3;
    case "DYN165": case "DYN166": case "DYN167": return 2;
    case "DYN168": return 3;
    case "DYN169": return 2;
    case "DYN170": return 1;
    case "DYN176": case "DYN177": case "DYN178": return 2;
    case "DYN185-BUFF": case "DYN186-BUFF": case "DYN187-BUFF": return 1;
    default:
      return 0;
  }
}

function DYNCombatEffectActive($cardID, $attackID)
{
  global $combatChainState, $CCS_IsBoosted, $mainPlayer;
  $params = explode(",", $cardID);
  $cardID = $params[0];
  switch($cardID) {
    case "DYN007": return true;
    case "DYN013": case "DYN014": case "DYN015": return AttackValue($attackID) >= 6;//Specifies base attack
    case "DYN019": case "DYN020": case "DYN021": return true;
    case "DYN022": case "DYN023": case "DYN024": return ClassContains($attackID, "BRUTE", $mainPlayer);
    case "DYN028": return ClassContains($attackID, "GUARDIAN", $mainPlayer);
    case "DYN046": return CardNameContains($attackID, "Crouching Tiger", $mainPlayer);
    case "DYN049": return CardNameContains($attackID, "Crouching Tiger", $mainPlayer);
    case "DYN053": case "DYN054": case "DYN055": return CardNameContains($attackID, "Crouching Tiger", $mainPlayer);
    case "DYN065": return true;
    case "DYN068": return true;
    case "DYN071": return CardSubType($attackID) == "Axe";
    case "DYN073": case "DYN074": case "DYN075": return TypeContains($attackID, "W", $mainPlayer);
    case "DYN076": case "DYN077": case "DYN078":
      $subtype = CardSubType($attackID);
      return ($subtype == "Sword") || ($subtype == "Dagger");
    case "DYN082": case "DYN083": case "DYN084": return CardSubType($attackID) == "Axe";
    case "DYN085": case "DYN086": case "DYN087": return (CardSubType($attackID) == "Sword" || CardSubType($attackID) == "Dagger");
    case "DYN089-UNDER":
      $character = &GetPlayerCharacter($mainPlayer);
      $index = FindCharacterIndex($mainPlayer, "DYN492a");
      return $attackID == "DYN492a" && $character[$index + 2] >= 1;
      case "DYN091-1": return $combatChainState[$CCS_IsBoosted];
    case "DYN148": case "DYN149": case "DYN150": return true;
    case "DYN154": return true;
    case "DYN155": return CardSubType($attackID) == "Arrow";
    case "DYN165": case "DYN166": case "DYN167": return true;
    case "DYN168": case "DYN169": case "DYN170": return CardSubType($attackID) == "Arrow";
    case "DYN176": case "DYN177": case "DYN178": return true;
    case "DYN185-BUFF": case "DYN186-BUFF": case "DYN187-BUFF": return CardType($attackID) == "AA" && ClassContains($attackID, "RUNEBLADE", $mainPlayer);
    case "DYN185-HIT": case "DYN186-HIT": case "DYN187-HIT": return CardType($attackID) == "AA" && ClassContains($attackID, "RUNEBLADE", $mainPlayer);
    default:
      return false;
  }
}

function DYNPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
  global $currentPlayer, $CS_PlayIndex, $CS_NumContractsCompleted, $combatChainState, $CCS_NumBoosted, $CS_NumCrouchingTigerPlayedThisTurn;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $rv = "";
  switch($cardID) {
    case "DYN001":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDECK:cardID=ARC159");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("ATTACKWITHIT", $currentPlayer, "-", 1);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
      return "";
    case "DYN007":
      if(ModifiedAttackValue($additionalCosts, $currentPlayer, "HAND", source:$cardID) >= 6) AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN009": AddCurrentTurnEffect($cardID, $currentPlayer); return "";
    case "DYN002": PutPermanentIntoPlay($currentPlayer, $cardID); return "";
    case "DYN003": PutPermanentIntoPlay($currentPlayer, $cardID); return "";
    case "DYN004": PutPermanentIntoPlay($currentPlayer, $cardID); return "";
    case "DYN016": case "DYN017": case "DYN018": if(ModifiedAttackValue($additionalCosts, $currentPlayer, "HAND", source:$cardID) >= 6) GiveAttackGoAgain(); return "";
    case "DYN019": case "DYN020": case "DYN021": if(ModifiedAttackValue($additionalCosts, $currentPlayer, "HAND", source:$cardID) >= 6) AddCurrentTurnEffect($cardID, $currentPlayer); return "";
    case "DYN022": case "DYN023": case "DYN024": AddCurrentTurnEffect($cardID, $currentPlayer); return "";
    case "DYN028": AddCurrentTurnEffect($cardID, $currentPlayer); return "";
    case "DYN030": case "DYN031": case "DYN032":
      if(!IsAllyAttacking()) {
        if(SearchCombatChainLink($currentPlayer, subtype:"Off-Hand", class:"GUARDIAN") != "") {
          AddDecisionQueue("MULTIZONEINDICES", $otherPlayer, "MYHAND", 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $otherPlayer, "<-", 1);
          AddDecisionQueue("MZDISCARD", $otherPlayer, "HAND,".$currentPlayer, 1);
          AddDecisionQueue("MZREMOVE", $otherPlayer, "-", 1);
          AddDecisionQueue("ELSE", $otherPlayer, "-");
          AddDecisionQueue("TAKEDAMAGE", $otherPlayer, "1-".$cardID, 1);
        }
      }
      return "";
    case "DYN039": case "DYN040": case "DYN041":
      if($cardID == "DYN039") $maxDef = 3;
      else if($cardID == "DYN040") $maxDef = 2;
      else $maxDef = 1;
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=E;subtype=Off-Hand;hasNegCounters=true;maxDef=" . $maxDef . ";class=GUARDIAN");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $currentPlayer, "GETCARDINDEX", 1);
      AddDecisionQueue("MODDEFCOUNTER", $currentPlayer, "1", 1);
      return "";
    case "DYN042": case "DYN043": case "DYN044":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=E;subtype=Off-Hand;class=GUARDIAN");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which Guardian Off-Hand to buff", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
      return "";
    case "DYN046":
      AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      return "";
    case "DYN049":
      AddPlayerHand("DYN065", $currentPlayer, "NA");
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN062": case "DYN063": case "DYN064":
      if($cardID == "DYN062") $amount = 3;
      else if($cardID == "DYN063") $amount = 2;
      else $amount = 1;
      for($i=0; $i < $amount; $i++) BanishCardForPlayer("DYN065", $currentPlayer, "-", "TT", $currentPlayer);
      return "";
    case "DYN068":
      CacheCombatResult();
      if(IsWeaponGreaterThanTwiceBasePower()) AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN071":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN072":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=W;subtype=Sword");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a Sword to gain a +1 counter");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDATTACKCOUNTERS", $currentPlayer, "1", 1);
      return "";
    case "DYN076": case "DYN077": case "DYN078":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN079": case "DYN080": case "DYN081":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
		case "DYN082": case "DYN083": case "DYN084":
      if($cardID == "DYN082") $amount = 3;
      else if($cardID == "DYN083") $amount = 2;
      else $amount = 1;
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN085": case "DYN086": case "DYN087":
      if($cardID == "DYN085") $amount = 3;
      else if($cardID == "DYN086") $amount = 2;
      else $amount = 1;
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN090":
      $numBoosted = $combatChainState[$CCS_NumBoosted];
      if(IsHeroAttackTarget() && $numBoosted > 0)
      {
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        AddDecisionQueue("PASSPARAMETER", $otherPlayer, $numBoosted, 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
        AddDecisionQueue("APPENDLASTRESULT", $otherPlayer, "-{0}", 1);
        AddDecisionQueue("PREPENDLASTRESULT", $otherPlayer, "{0}-", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose $numBoosted card(s)", 1);
        AddDecisionQueue("MULTICHOOSEHAND", $otherPlayer, "<-", 1);
        AddDecisionQueue("IMPLODELASTRESULT", $otherPlayer, ",", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "1");
        AddDecisionQueue("REVEALHANDCARDS", $otherPlayer, "<-", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{1}", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card", 1);
        AddDecisionQueue("SPECIFICCARD", $otherPlayer, "PULSEWAVEHARPOONFILTER", 1);
        AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
        AddDecisionQueue("ADDCARDTOCHAINASDEFENDINGCARD", $otherPlayer, "HAND", 1);
      }
      return "";
    case "DYN091":
      AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
      AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
      return "";
    case "DYN092":
      $conditionsMet = CheckIfConstructNitroMechanoidConditionsAreMet($currentPlayer);
      if ($conditionsMet != "") return $conditionsMet;
      $char = &GetPlayerCharacter($currentPlayer);
      // Add the new weapon stuff so we can put cards under it
      PutCharacterIntoPlayForPlayer("DYN492a", $currentPlayer);
      // We don't want function calls in every iteration check
      $charCount = count($char);
      $charPieces = CharacterPieces();
      $mechanoidIndex = $charCount - $charPieces; // we pushed it, so should be the last element
      //Congrats, you have met the requirement to summon the mech! Let's remove the old stuff
      for ($i = $charCount - $charPieces; $i >= 0; $i -= $charPieces) {
        if(CardType($char[$i]) != "C" && $char[$i] != "DYN492a") {
          if($char[$i] == "DYN089") AddCurrentTurnEffect($char[$i] . "-UNDER", $currentPlayer);
          RemoveCharacterAndAddAsSubcardToCharacter($currentPlayer, $i, $mechanoidIndex);
        }
      }

      $hyperDrivers = SearchMultizone($currentPlayer, "MYITEMS:isSameName=ARC036");
      $hyperDrivers = str_replace("MYITEMS-", "", $hyperDrivers); // MULTICHOOSEITEMS expects indexes only but SearchItems does not have a sameName parameter
      AddDecisionQueue("MULTICHOOSEITEMS", $currentPlayer, "3-" . $hyperDrivers. "-3");
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "CONSTRUCTNITROMECHANOID," . $mechanoidIndex, 1);
      //Now add the remaining new stuff
      PutCharacterIntoPlayForPlayer("DYN492b", $currentPlayer);//Armor
      PutItemIntoPlayForPlayer("DYN492c", $currentPlayer);//Item
      return "";
    case "DYN095": case "DYN096": case "DYN097": AddCurrentTurnEffect($cardID, $currentPlayer); return "";
    case "DYN123":
      if(GetClassState($currentPlayer, $CS_NumContractsCompleted) > 0) {
        PutItemIntoPlayForPlayer("EVR195", $currentPlayer, 0, 4);
      }
      return "";
    case "DYN130": case "DYN131": case "DYN132":
      if($cardID == "DYN130") $amount = -4;
      else if($cardID == "DYN131") $amount = -3;
      else $amount = -2;
      $options = GetChainLinkCards(($currentPlayer == 1 ? 2 : 1), "", "C");
      if($options != "") {
        AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, $options);
        AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $currentPlayer, $amount, 1);
      }
      return "";
    case "DYN148": case "DYN149": case "DYN150":
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      AddDecisionQueue("DECKCARDS", $otherPlayer, "0", 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want sink <0>", 1);
      AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_sink_the_opponent's_card", 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("WRITELOG", $currentPlayer, "Sunk the top card", 1);
      AddDecisionQueue("FINDINDICES", $otherPlayer, "TOPDECK", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $otherPlayer, "<-", 1);
      AddDecisionQueue("ADDBOTDECK", $otherPlayer, "-", 1);
      AddDecisionQueue("ELSE", $currentPlayer, "-");
      AddDecisionQueue("WRITELOG", $currentPlayer, "Left the card on top", 1);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN151":
      $deck = new Deck($currentPlayer);
      AddDecisionQueue("DECKCARDS", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      if(ArsenalFull($currentPlayer)) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Top of your deck: <0>");
        AddDecisionQueue("OK", $currentPlayer, "", 1);
        return "Your arsenal is full";
      }
      if(CardSubType($deck->Top()) != "Arrow") {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Top of your deck: <0>");
        AddDecisionQueue("OK", $currentPlayer, "", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "NO");
      } else {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to put <0> in your arsenal", 1);
        AddDecisionQueue("YESNO", $currentPlayer, "", 1);
      }
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "SANDSCOURGREATBOW");
      return "";
    case "DYN155": AddCurrentTurnEffect($cardID, $currentPlayer); return "";
    case "DYN165": case "DYN166": case "DYN167": if(HasAimCounter()) AddCurrentTurnEffect($cardID, $currentPlayer); return "";
    case "DYN168": case "DYN169": case "DYN170":
      $arsenal = &GetArsenal($currentPlayer);
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYARS:faceUp=true", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card you want to buff and a aim counter on", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZADDCOUNTERANDEFFECT", $currentPlayer, $cardID, 1);
      return "";
    case "DYN171": AddCurrentTurnEffect($cardID, $currentPlayer); return "";
    case "DYN172":
      Draw($currentPlayer);
      if(SearchCardList($additionalCosts, $currentPlayer, "A") != "" && SearchCardList($additionalCosts, $currentPlayer, "AA") != "") PlayAura("ARC112", $currentPlayer);
      return $rv;
    case "DYN173":
      if(SearchCardList($additionalCosts, $currentPlayer, "A") != "" && SearchCardList($additionalCosts, $currentPlayer, "AA") != "" && IsHeroAttackTarget()) AddCurrentTurnEffect($cardID, $currentPlayer, $from);
      return "";
    case "DYN174":
      if(SearchCardList($additionalCosts, $currentPlayer, "AA") != "") {
        MZChooseAndDestroy($otherPlayer, "MYALLY");
        MZChooseAndDestroy($currentPlayer, "MYALLY");
      }
      if(SearchCardList($additionalCosts, $currentPlayer, "A") != "") {
        MZChooseAndDestroy($otherPlayer, "MYAURAS");
        MZChooseAndDestroy($currentPlayer, "MYAURAS");
      }
      return "";
    case "DYN175":
      $numRunechants = DestroyAllThisAura($currentPlayer, "ARC112");
      $auras = &GetAuras($currentPlayer);
      $index = count($auras) - AuraPieces();
      $auras[$index+2] = $numRunechants;
      return "";
    case "DYN176": case "DYN177": case "DYN178":
      if(SearchCardList($additionalCosts, $currentPlayer, "AA") != "") AddCurrentTurnEffect($cardID, $currentPlayer);
      if(SearchCardList($additionalCosts, $currentPlayer, "A") != "") PlayAura("ARC112", $currentPlayer, 2, true);
      return "";
    case "DYN182": case "DYN183": case "DYN184":
      if(SearchCardList($additionalCosts, $currentPlayer, "A") != "") DealArcane(1, 2, "PLAYCARD", $cardID);
      return "";
    case "DYN185": case "DYN186": case "DYN187":
      if($cardID == "DYN185") $amount = 3;
      else if($cardID == "DYN186") $amount = 2;
      else $amount = 1;
      AddCurrentTurnEffect($cardID . "-HIT", $currentPlayer);
      if(SearchCardList($additionalCosts, $currentPlayer, "AA") != "") AddCurrentTurnEffect($cardID . "-BUFF", $currentPlayer);
      return "";
    case "DYN188": case "DYN189": case "DYN190":
      $deck = new Deck($currentPlayer);
      if($deck->Reveal(1)) if(ColorContains($deck->Top(), PitchValue($cardID), $currentPlayer)) PlayAura("ARC112", $currentPlayer, 1, true);
      return "";
    case "DYN192":
      DealArcane(1, 1, "ABILITY", $cardID, resolvedTarget: $target);
      AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "DYN192,");
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "<-");
      return "";
    case "DYN193":
      PlayerOpt($currentPlayer, 1, false);
      PlayAura("DYN244", $currentPlayer);
      return "";
  	case "DYN194": case "DYN195": case "DYN197": case "DYN198": case "DYN199": case "DYN203": case "DYN204": case "DYN205":
    case "DYN206": case "DYN207": case "DYN208": DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target); return "";
    case "DYN196": AddCurrentTurnEffect($cardID, $currentPlayer); return "";
    case "DYN209": case "DYN210": case "DYN211": AddCurrentTurnEffect($cardID, $currentPlayer); return "";
    case "DYN212": Transform($currentPlayer, "MON104", "DYN612"); return "";
    case "DYN215":
      AddDecisionQueue("INPUTCARDNAME", $currentPlayer, "-");
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      AddDecisionQueue("WRITELOG", $currentPlayer, "📣<b>{0}</b> was chosen");
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "DYN215-{0}");
      AddDecisionQueue("ADDCURRENTEFFECT", $otherPlayer, "DYN215-{0}");
      return "";
    case "DYN221": case "DYN222": case "DYN223":
      if($from == "PLAY") return "";
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      $auras = &GetAuras($currentPlayer);
      $uniqueID = $auras[count($auras) - AuraPieces() + 6];
      if($cardID == "DYN221") $maxCost = 3;
      else if($cardID == "DYN222") $maxCost = 2;
      else $maxCost = 1;
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRAURAS:maxCost=" . $maxCost);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZBANISH", $currentPlayer, "AURAS," . $cardID . "-" . $uniqueID, 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      return "";
    case "DYN224": case "DYN225": case "DYN226":
      if(SearchAuras("MON104", $currentPlayer)) GiveAttackGoAgain();
      return "";
    case "DYN227": case "DYN228": case "DYN229":
      if(SearchAuras("MON104", $currentPlayer)) AddCurrentTurnEffect("DYN227", $currentPlayer);
      return "";
    case "DYN230": case "DYN231": case "DYN232":
      $deck = new Deck($currentPlayer);
      if($deck->Reveal(1) && ColorContains($deck->Top(), PitchValue($cardID), $currentPlayer)) PlayAura("MON104", $currentPlayer, 1, true);
      return "";
    case "DYN235":
      BottomDeck($currentPlayer, false, shouldDraw:true);
      return "";
    case "DYN240":
      $rv = "";
      if($from == "PLAY") {
        DestroyItemForPlayer($currentPlayer, GetClassState($currentPlayer, $CS_PlayIndex));
        if(IsRoyal($currentPlayer))
        {
          $rv .= CardLink($cardID, $cardID) . " revealed the opponent's hand";
          $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
          AddDecisionQueue("REVEALHANDCARDS", $otherPlayer, "-", 1);
        }
        AddDecisionQueue("INPUTCARDNAME", $currentPlayer, "-");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("WRITELOG", $currentPlayer, "📣<b>{0}</b> was chosen");
        AddDecisionQueue("ADDCURRENTANDNEXTTURNEFFECT", $otherPlayer, "DYN240-{0}");
      }
      return $rv;
    case "DYN241":
      if($from == "PLAY") {
        DestroyItemForPlayer($currentPlayer, GetClassState($currentPlayer, $CS_PlayIndex), true);
        PutItemIntoPlayForPlayer((IsRoyal($currentPlayer) ? "DYN243": "CRU197"), $currentPlayer);
        $deck = new Deck($currentPlayer);
        $deck->AddBottom("DYN241", "PLAY");
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
      }
      return "";
    case "DYN242":
      if($from == "PLAY") {
        DestroyItemForPlayer($currentPlayer, GetClassState($currentPlayer, $CS_PlayIndex));
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose any number of heroes");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Target_Opponent,Target_Both_Heroes,Target_Yourself,Target_No_Heroes");
        AddDecisionQueue("PLAYERTARGETEDABILITY", $currentPlayer, "IMPERIALWARHORN", 1);
      }
      return "";
    case "DYN243":
      $rv = "";
      if($from == "PLAY") {
        DestroyItemForPlayer($currentPlayer, GetClassState($currentPlayer, $CS_PlayIndex));
        Draw($currentPlayer);
      }
      return $rv;
    case "DYN612":
      $soul = &GetSoul($currentPlayer);
      if(count($soul) > 0){
        AddDecisionQueue("FINDINDICES", $currentPlayer, "SOUL");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to banish");
        AddDecisionQueue("MAYCHOOSEMYSOUL", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIBANISHSOUL", $currentPlayer, "-", 1);
        AddDecisionQueue("THREATENARCANE", $currentPlayer, $cardID, 1);
      }
      return "";
    default: return "";
  }
}

function DYNHitEffect($cardID, $from, $attackID)
{
  global $mainPlayer, $defPlayer, $combatChainState, $CCS_DamageDealt, $CCS_NumBoosted, $combatChain;
  switch($cardID) {
    case "DYN047":
      if(ComboActive()) {
        $numLinks = NumChainLinksWithName("Crouching Tiger");
        for($i=0; $i < $numLinks; $i++) BanishCardForPlayer("DYN065", $mainPlayer, "-", "TT", $mainPlayer);
      }
      break;
    case "DYN050": case "DYN051": case "DYN052": BanishCardForPlayer("DYN065", $mainPlayer, "-", "TT", $mainPlayer); break;
    case "DYN067": if(IsHeroAttackTarget() && !SearchAuras("DYN246", $mainPlayer)) PlayAura("DYN246", $mainPlayer); break;
    case "DYN107": case "DYN108": case "DYN109":
      MZMoveCard($mainPlayer, "MYHAND:subtype=Item;class=MECHANOLOGIST;maxCost=" . $combatChainState[$CCS_NumBoosted], "MYITEMS", may:true);
      break;
    case "DYN115": case "DYN116": if(IsHeroAttackTarget()) AddCurrentTurnEffect($cardID, $defPlayer); break;
    case "DYN117": if(IsHeroAttackTarget() && ClassContains($attackID, "ASSASSIN", $mainPlayer)) GiveAttackGoAgain(); break;
    case "DYN118":
      if(IsHeroAttackTarget()) {
        $deck = new Deck($defPlayer);
        if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); break; }
        $deck->BanishTop("Source-" . $combatChain[0], banishedBy:$combatChain[0]);
      }
      break;
    case "DYN119":
      if(IsHeroAttackTarget()) {
        $deck = new Deck($defPlayer);
        if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); break; }
        if($deck->RemainingCards() < $combatChainState[$CCS_DamageDealt]) $deck->BanishTop(banishedBy:$cardID, amount:$deck->RemainingCards());
        else $deck->BanishTop(banishedBy:$cardID, amount:$combatChainState[$CCS_DamageDealt]);
      }
      break;
    case "DYN120":
      if(IsHeroAttackTarget()) {
        $deck = new Deck($defPlayer);
        if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); }
        else $deck->BanishTop(banishedBy:$cardID);
        MZMoveCard($mainPlayer, "THEIRARS", "THEIRBANISH,ARS,-," . $mainPlayer, true);
      }
      break;
    case "DYN121": if(IsHeroAttackTarget() && IsRoyal($defPlayer))
      {
        PlayerLoseHealth($defPlayer, GetHealth($defPlayer));
      }
      break;
    case "DYN122":
      if(IsHeroAttackTarget()) {
        $deck = new Deck($defPlayer);
        if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); }
        else $deck->BanishTop(banishedBy:$cardID);
        MZMoveCard($mainPlayer, "THEIRHAND", "THEIRBANISH,HAND,-," . $mainPlayer);
      }
      break;
    case "DYN124": case "DYN125": case "DYN126": case "DYN127": case "DYN128": case "DYN129":
    case "DYN133": case "DYN134": case "DYN135": case "DYN136": case "DYN137": case "DYN138":
    case "DYN139": case "DYN140": case "DYN141": case "DYN142": case "DYN143": case "DYN144":
    case "DYN145": case "DYN146": case "DYN147":
      if(IsHeroAttackTarget()) {
        $deck = new Deck($defPlayer);
        if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); break; }
        $deck->BanishTop(banishedBy:$cardID);
      }
      break;
    case "DYN153": AddCurrentTurnEffectFromCombat($cardID, $mainPlayer); break;
    case "DYN154": if(HasAimCounter() && IsHeroAttackTarget()) AddNextTurnEffect($cardID, $defPlayer); break;
    case "DYN156": case "DYN157": case "DYN158":
      if(IsHeroAttackTarget()){
        AddDecisionQueue("FINDINDICES", $defPlayer, "EQUIP");
        AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
        AddDecisionQueue("MODDEFCOUNTER", $defPlayer, "-1", 1);
      }
      break;
    case "DYN162": case "DYN163": case "DYN164":
      if(HasAimCounter() && IsHeroAttackTarget()) {
        MZChooseAndDestroy($mainPlayer, "THEIRARS");
        break;
      }
    default: break;
  }
}

function IsRoyal($player)
{
  $mainCharacter = &GetPlayerCharacter($player);
  if(SearchCharacterForCard($player, "DYN234")) return true;
  switch($mainCharacter[0]) {
    case "DYN001": return true;
    default: break;
  }
  return false;
}

function HasSurge($cardID)
{
  switch($cardID) {
    case "DYN194": case "DYN195": case "DYN197": case "DYN198": case "DYN199":
    case "DYN203": case "DYN204": case "DYN205": case "DYN206": case "DYN207": case "DYN208":
    case "ROS166"://destructive aethertide
    case "ROS167"://eternal inferno
    case "ROS173": case "ROS174": case "ROS175":
    case "ROS176": case "ROS177": case "ROS178":
    case "ROS189": case "ROS190": case "ROS191":
    case "ROS195": case "ROS196": case "ROS197"://open the floodgates
    case "ROS198": case "ROS199": case "ROS200":
    case "ROS201": case "ROS202": case "ROS203":
    case "ROS207": case "ROS208": case "ROS209":
      return true;
    default: return false;
  }
}

function ContractType($cardID)
{
  switch($cardID)
  {
    case "DYN119": return "YELLOWPITCH";
    case "DYN120": return "REDPITCH";
    case "DYN122": return "BLUEPITCH";
    case "DYN124": case "DYN125": case "DYN126": return "COST1ORLESS";
    case "DYN127": case "DYN128": case "DYN129": return "COST2ORMORE";
    case "DYN133": case "DYN134": case "DYN135": return "AA";
    case "DYN136": case "DYN137": case "DYN138": return "BLOCK2ORLESS";
    case "DYN139": case "DYN140": case "DYN141": return "REACTIONS";
    case "DYN142": case "DYN143": case "DYN144": return "GOAGAIN";
    case "DYN145": case "DYN146": case "DYN147": return "NAA";
    case "EVO236": return "NONACTION";
    default: return "";
  }
}

function ContractCompleted($player, $cardID)
{
  global $CS_NumContractsCompleted, $EffectContext;
  WriteLog("Player " . $player . " completed the contract for " . CardLink($cardID, $cardID));
  IncrementClassState($player, $CS_NumContractsCompleted);
  if($EffectContext == "HVY246") AddCurrentTurnEffect("HVY246", $player);
  switch($cardID)
  {
    case "DYN119": case "DYN120": case "DYN122":
    case "DYN124": case "DYN125": case "DYN126":
    case "DYN127": case "DYN128": case "DYN129":
    case "DYN133": case "DYN134": case "DYN135":
    case "DYN136": case "DYN137": case "DYN138":
    case "DYN139": case "DYN140": case "DYN141":
    case "DYN142": case "DYN143": case "DYN144":
    case "DYN145": case "DYN146": case "DYN147":
    case "EVO236":
      PutItemIntoPlayForPlayer("EVR195", $player);
      break;
    default: break;
  }
}

function CheckContracts($banishedBy, $cardBanished)
{
  global $CombatChain, $chainLinks;
  for($i = 0; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
    $chainCard = $CombatChain->Card($i, cardNumber:true);
    if($chainCard->PlayerID() != $banishedBy) continue;
    $contractType = ContractType($chainCard->ID());
    if($contractType != "" && CheckContract($contractType, $cardBanished)) ContractCompleted($banishedBy, $chainCard->ID());
  }
  for($i = 0; $i < count($chainLinks); ++$i) {
    for($j = 0; $j < count($chainLinks[$i]); $j += ChainLinksPieces()) {
      if($chainLinks[$i][$j+1] != $banishedBy) continue;
      if($chainLinks[$i][$j+2] == 0) continue;
      $contractType = ContractType($chainLinks[$i][$j]);
      if($contractType != "" && CheckContract($contractType, $cardBanished)) ContractCompleted($banishedBy, $chainLinks[$i][$j]);
    }
  }
}

function ImperialWarHorn($player, $term)
{
  AddDecisionQueue("MULTIZONEINDICES", $player, $term . "ALLY&" . $term . "AURAS&"  . $term . "ITEMS&LANDMARK");
  AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to destroy", 1);
  AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
  AddDecisionQueue("MZDESTROY", $player, "-", 1);
}

function CheckContract($contractType, $cardBanished)
{
  switch($contractType) {
    case "REDPITCH": return PitchValue($cardBanished) == 1;
    case "YELLOWPITCH": return PitchValue($cardBanished) == 2;
    case "BLUEPITCH": return PitchValue($cardBanished) == 3;
    case "COST1ORLESS": return CardCost($cardBanished) <= 1 && CardCost($cardBanished) >= 0;
    case "COST2ORMORE": return CardCost($cardBanished) >= 2;
    case "AA": return CardType($cardBanished) == "AA";
    case "GOAGAIN": return HasGoAgain($cardBanished);
    case "NAA": return CardType($cardBanished) == "A";
    case "BLOCK2ORLESS": return BlockValue($cardBanished) <= 2 && BlockValue($cardBanished) >= 0;
    case "REACTIONS": return CardType($cardBanished) == "AR" || CardType($cardBanished) == "DR";
    case "NONACTION": return !IsActionCard($cardBanished);
    default: return false;
    }
}
