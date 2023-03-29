<?php

  function EVRAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "EVR053": return 1;
      case "EVR070": return 0;
      case "EVR085": return 2;
      case "EVR087": return 1;
      case "EVR103": return 0;
      case "EVR137": return 0;
      case "EVR121": return 3;
      case "EVR157": return 1;
      case "EVR173": case "EVR174": case "EVR175": return 0;
      case "EVR176": return 0;
      case "EVR177": return 0;
      case "EVR178": return 0;
      case "EVR179": return 0;
      case "EVR180": return 0;
      case "EVR181": return 0;
      case "EVR182": case "EVR183": case "EVR184": case "EVR185": case "EVR186": return 0;
      case "EVR187": return 0;
      case "EVR190": return 0;
      case "EVR195": return 3;
      default: return 0;
    }
  }

  function EVRAbilityType($cardID, $index=-1)
  {
    global $currentPlayer, $mainPlayer, $defPlayer;
    switch($cardID)
    {
      case "EVR053": return "AR";
      case "EVR070": return "A";
      case "EVR085": return "A";
      case "EVR087": return "A";
      case "EVR103": return "A";
      case "EVR137": return "I";
      case "EVR121": return "I";
      case "EVR157": return $currentPlayer == $mainPlayer ? "I" : "";
      case "EVR173": case "EVR174": case "EVR175": return "I";
      case "EVR176": return "AR";
      case "EVR177": return "I";
      case "EVR178": return "DR";
      case "EVR179": return "I";
      case "EVR180": return "I";
      case "EVR181": return "I";
      case "EVR182": return "I";
      case "EVR183": return "A";
      case "EVR184": case "EVR185": case "EVR186": return "I";
      case "EVR187": return "I";
      case "EVR190": return "I";
      case "EVR195": return "A";
      default: return "";
    }
  }

  function EVRHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "EVR003": return true;
      case "EVR004": return true;
      case "EVR005": case "EVR006": case "EVR007": return true;
      case "EVR014": case "EVR015": case "EVR016": return true;
      case "EVR022": return true;
      case "EVR030": case "EVR031": case "EVR032": return true;
      case "EVR039": return true;
      case "EVR041": case "EVR042": case "EVR043": return true;
      case "EVR044": case "EVR045": case "EVR046": return true;
      case "EVR047": case "EVR048": case "EVR049": return true;
      case "EVR055": return true;
      case "EVR056": return true;
      case "EVR057": case "EVR058": case "EVR059": return true;
      case "EVR066": case "EVR067": case "EVR068": return true;
      case "EVR082": case "EVR083": case "EVR084": return true;
      case "EVR089": return true;
      case "EVR091": case "EVR092": case "EVR093": return true;
      case "EVR100": case "EVR101": case "EVR102": return true;
      case "EVR106": return true;
      case "EVR107": case "EVR108": case "EVR109": return true;
      case "EVR150": case "EVR151": case "EVR152": return true;
      case "EVR158": return true;
      case "EVR160": return true;
      case "EVR164": case "EVR165": case "EVR166": return true;
      case "EVR167": case "EVR168": case "EVR169": return true;
      case "EVR170": case "EVR171": case "EVR172": return true;
      case "EVR176": case "EVR177": case "EVR178": case "EVR179": case "EVR180": return true;
      case "EVR181": return true;
      case "EVR188": case "EVR189": case "EVR190": case "EVR191": case "EVR192": case "EVR193": return true;
      default: return false;
    }
  }

  function EVRAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "EVR085": return true;
      case "EVR087": return true;
      case "EVR103": return true;
      case "EVR183": return true;
      case "EVR195": return true;
      default: return false;
    }
  }

  function EVREffectAttackModifier($cardID)
  {
    $params = explode(",", $cardID);
    $cardID = $params[0];
    if(count($params) > 1) $parameter = $params[1];
    switch($cardID)
    {
      case "EVR001": return 1;
      case "EVR004": return $parameter;
      case "EVR008": case "EVR009": case "EVR010": return 2;
      case "EVR014": case "EVR015": case "EVR016": return 5;
      case "EVR017": return 2;
      case "EVR021": return -4;
      case "EVR047-2": case "EVR048-2": case "EVR049-2": return 1;
      case "EVR057-1": case "EVR058-1": case "EVR059-1": return 1;
      case "EVR057-2": return 3;
      case "EVR058-2": return 2;
      case "EVR059-2": return 1;
      case "EVR060": return 3;
      case "EVR061": return 2;
      case "EVR062": return 1;
      case "EVR066": return 3;
      case "EVR067": return 2;
      case "EVR068": return 1;
      case "EVR072": return 2;
      case "EVR082": return 3;
      case "EVR083": return 2;
      case "EVR084": return 1;
      case "EVR087": return 1;
      case "EVR090": return 2;
      case "EVR091": return 3;
      case "EVR092": return 2;
      case "EVR093": return 1;
      case "EVR100": return 3;
      case "EVR101": return 2;
      case "EVR102": return 1;
      case "EVR143": return 2;
      case "EVR150": return 4;
      case "EVR151": return 3;
      case "EVR152": return 2;
      case "EVR160": return IsHeroAttackTarget() ? -1 : 0;
      case "EVR161-2": return 2;
      case "EVR170-2": return 3;
      case "EVR171-2": return 2;
      case "EVR172-2": return 1;
      default: return 0;
    }
  }

  function EVRCombatEffectActive($cardID, $attackID)
  {
    global $CS_AtksWWeapon, $mainPlayer;
    $params = explode(",", $cardID);
    $cardID = $params[0];
    switch($cardID)
    {
      case "EVR001": return ClassContains($attackID, "BRUTE", $mainPlayer);
      case "EVR004": return ClassContains($attackID, "BRUTE", $mainPlayer);
      case "EVR008": case "EVR009": case "EVR010": return true;
      case "EVR014": case "EVR015": case "EVR016": return CardType($attackID) == "AA" && ClassContains($attackID, "BRUTE", $mainPlayer);
      case "EVR017": return CardCost($attackID) >= 3;
      case "EVR019": return HasCrush($attackID);
      case "EVR021": return true;
      case "EVR044": case "EVR045": case "EVR046": return CardType($attackID) == "AA" && AttackValue($attackID) <= 2;
      case "EVR047-1": case "EVR048-1": case "EVR049-1": return true;
      case "EVR047-2": case "EVR048-2": case "EVR049-2": return true;
      case "EVR057-1": case "EVR058-1": case "EVR059-1":
        $subtype = CardSubType($attackID);
        if($subtype != "Sword" && $subtype != "Dagger") return false;
        return CardType($attackID) == "W" && GetClassState($mainPlayer, $CS_AtksWWeapon) == 0;
      case "EVR057-2": case "EVR058-2": case "EVR059-2":
        $subtype = CardSubType($attackID);
        if($subtype != "Sword" && $subtype != "Dagger") return false;
        return CardType($attackID) == "W" && GetClassState($mainPlayer, $CS_AtksWWeapon) == 1;
      case "EVR060": case "EVR061": case "EVR062": return CardType($attackID) == "W";
      case "EVR066": case "EVR067": case "EVR068": return CardType($attackID) == "W" && Is1H($attackID);
      case "EVR066-1": case "EVR067-1": case "EVR068-1": return CardType($attackID) == "W";
      case "EVR072": return true;
      case "EVR082": case "EVR083": case "EVR084": return CardType($attackID) == "AA" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer);
      case "EVR087": return CardSubType($attackID) == "Arrow";
      case "EVR090": return CardSubType($attackID) == "Arrow";
      case "EVR091": case "EVR092": case "EVR093": return CardSubType($attackID) == "Arrow";
      case "EVR091-1": case "EVR092-1": case "EVR093-1": return CardSubType($attackID) == "Arrow";
      case "EVR094": case "EVR095": case "EVR096": return CardType($attackID) == "AA";
      case "EVR100": case "EVR101": case "EVR102": return CardSubType($attackID) == "Arrow";
      case "EVR142": return ClassContains($attackID, "ILLUSIONIST", $mainPlayer);
      case "EVR143": return ClassContains($attackID, "ILLUSIONIST", $mainPlayer) && CardType($attackID) == "AA";
      case "EVR150": case "EVR151": case "EVR152": return CardType($attackID) == "AA";
      case "EVR160": return true;
      case "EVR161-1": case "EVR161-2": case "EVR161-3": return true;
      case "EVR164": case "EVR165": case "EVR166": return true;
      case "EVR170-1": case "EVR171-1": case "EVR172-1": return CardType($attackID) == "AA";
      case "EVR170-2": case "EVR171-2": case "EVR172-2": return CardType($attackID) == "AA";
      case "EVR186": return true;
      default: return false;
    }
  }

  function EVRPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer, $combatChain, $CS_PlayIndex, $combatChainState, $CCS_GoesWhereAfterLinkResolves, $CCS_NumBoosted;
    global $CS_HighestRoll, $CS_NumNonAttackCards, $CS_NumAttackCards, $CS_NumBoosted, $mainPlayer, $CCS_RequiredEquipmentBlock;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    $rv = "";
    switch($cardID)
    {
      case "EVR003":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVR004":
        $roll = GetDieRoll($currentPlayer);
        AddCurrentTurnEffect($cardID . "," . $roll, $currentPlayer);
        return "Gives your next Brute attack +" . $roll . ".";
      case "EVR005": case "EVR006": case "EVR007":
        $rv = "Intimidates";
        Intimidate();
        if($cardID == "EVR005") $targetHigh = 4;
        else if($cardID == "EVR006") $targetHigh = 5;
        else if($cardID == "EVR007") $targetHigh = 6;
        if(GetClassState($currentPlayer, $CS_HighestRoll) >= $targetHigh)
        {
          Intimidate();
          $rv .= " twice";
        }
        return $rv . ".";
      case "EVR008": case "EVR009": case "EVR010":
        Draw($currentPlayer);
        $card = DiscardRandom();
        $rv = "Discarded " . CardLink($card, $card);
        if(AttackValue($card) >= 6)
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv .= " and got +2 from discarding a card with 6 or more power";
        }
        return $rv;
      case "EVR011": case "EVR012": case "EVR013":
        Draw($currentPlayer);
        $card = DiscardRandom();
        $rv = "Discarded " . CardLink($card, $card);
        if(AttackValue($card) >= 6)
        {
          GiveAttackGoAgain();
          $rv .= " and got go again from discarding a card with 6 or more power";
        }
        return $rv;
      case "EVR014": case "EVR015": case "EVR016":
        if($cardID == "EVR014") $target = 4;
        else if($cardID == "EVR015") $target = 5;
        else $target = 6;
        $roll = GetDieRoll($currentPlayer);
        if($roll >= $target) {
          $rv = "Gives the next Brute attack action card +5";
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return $rv;
      case "EVR022":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKAURAMAXCOST," . ($resourcesPaid-3));
        AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        return "";
      case "EVR023":
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=E;subtype=Chest;hasNegCounters=true");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which chest pieces to remove a -1 defense counter");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "GETCARDINDEX", 1);
        AddDecisionQueue("REMOVENEGDEFCOUNTER", $currentPlayer, "-", 1);
        return "";
      case "EVR030": case "EVR031": case "EVR032":
        if($cardID == "EVR030") $amount = 3;
        else if($cardID == "EVR031") $amount = 2;
        else $amount = 1;
        PlayAura("WTR075", $currentPlayer, $amount);
        return "";
      case "EVR033": case "EVR034": case "EVR035":
        if ($target != "-") AddCurrentTurnEffect($cardID, $currentPlayer, $from, GetMZCard(($currentPlayer == 1 ? 2 : 1), $target));
        return "";
      case "EVR047": case "EVR048": case "EVR049":
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts);
        AddDecisionQueue("MODAL", $currentPlayer, "TWINTWISTERS");
        return "";
      case "EVR053":
        $rv = "You have no cards in your deck.";
        $deck = &GetDeck($currentPlayer);
        if(count($deck) > 0)
        {
          $card = array_shift($deck);
          $mod = "-";
          $cardType = CardType($card);
          if ($cardType == "AR" || $cardType == "I" || $cardType == "AA" || CanPlayAsInstant($card)) $mod = "TCC";
          BanishCardForPlayer($card, $currentPlayer, "DECK", $mod);
          $rv = CardLink($card, $card) . " was banished. It is playable on this combat chain.";
        }
        return $rv;
      case "EVR054":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDMZBUFF", $currentPlayer, "EVR054", 1);
        return "";
      case "EVR055":
        $numCopper = CountItem("CRU197", $currentPlayer);
        if($numCopper == 0) return "No copper.";
        if($numCopper > 6) $numCopper = 6;
        $buttons = "";
        for($i=0; $i<=$numCopper; ++$i)
        {
          if($buttons != "") $buttons .= ",";
          $buttons .= $i;
        }
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many Copper to destroy");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, $buttons);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "CRU197-", 1);
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "<-", 1);
        AddDecisionQueue("LASTRESULTPIECE", $currentPlayer, "1", 1);
        AddDecisionQueue("APPENDLASTRESULT", $currentPlayer, "-Buff_Weapon,Buff_Weapon,Go_Again,Go_Again,Another_Swing,Another_Swing", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose {0} modes");
        AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "BLOODONHERHANDS", 1);
        break;
      case "EVR056":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVR057": case "EVR058": case "EVR059":
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
        return "";
      case "EVR060": case "EVR061": case "EVR062":
        GiveAttackGoAgain();
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        return "";
      case "EVR066": case "EVR067": case "EVR068":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        return "";
      case "EVR070":
        $rv = "Micro-Processor is a partially manual card. Only choose each option once per turn.";
        if($from == "PLAY")
        {
          $items = &GetItems($currentPlayer);
          if($items[GetClassState($currentPlayer, $CS_PlayIndex)+3] == 2) { $rv = "Gained an action point from Micro-Processor."; GainActionPoints(1); }
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
          AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Opt,Draw_then_top_deck,Banish_top_deck");
          AddDecisionQueue("MODAL", $currentPlayer, "MICROPROCESSOR", 1);
        }
        return $rv;
      case "EVR073": case "EVR074": case "EVR075":
        if($combatChainState[$CCS_NumBoosted] && !IsAllyAttackTarget()) {
          $combatChainState[$CCS_RequiredEquipmentBlock] = 1;
        }
        return "";
      case "EVR079": case "EVR080": case "EVR081":
        $numBoosts = GetClassState($currentPlayer, $CS_NumBoosted);
        Opt($cardID, $numBoosts);
        return "Lets you opt " . $numBoosts . ".";
      case "EVR082": case "EVR083": case "EVR084":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVR085":
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "0");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
        AddDecisionQueue("MAYCHOOSEHAND", $otherPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $otherPlayer, "-", 1);
        AddDecisionQueue("DRAW", $otherPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "EVR195", 1);
        AddDecisionQueue("PUTPLAY", $currentPlayer, "0", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "1", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("DQVARPASSIFSET", $currentPlayer, "0");
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "";
      case "EVR087":
        if(ArsenalFull($currentPlayer)) return "Your arsenal is full, so you cannot put an arrow in your arsenal.";
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDARSENALFACEUP", $currentPlayer, "HAND", 1);
        AddDecisionQueue("DREADBORE", $currentPlayer, $cardID, 1);
        return "";
      case "EVR089":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON,Bow");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDMZUSES", $currentPlayer, 2, 1);
        return "";
      case "EVR090":
        AddCurrentTurnEffect($cardID, 1);
        AddCurrentTurnEffect($cardID, 2);
        return "";
      case "EVR091": case "EVR092": case "EVR093":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddCurrentTurnEffect($cardID . "-1", $otherPlayer);
        return "";
      case "EVR100": case "EVR101": case "EVR102":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        Opt($cardID, 1);
        return "";
      case "EVR103":
        PlayAura("ARC112", $currentPlayer, 2);
        return "";
      case "EVR106":
        if(GetClassState($currentPlayer, $CS_NumNonAttackCards) > 1 && GetClassState($currentPlayer, $CS_NumAttackCards) > 0)
        {
          PlayAura("ARC112", $currentPlayer, 4);
          $rv = "Creates 4 Runechants";
        }
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return $rv;
      case "EVR121":
        DealArcane(1, 1, "ABILITY", $cardID);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "KRAKENAETHERVEIN");
        return "";
      case "EVR123":
        DealArcane(4, 1, "PLAYCARD", $cardID, resolvedTarget: $target);
        if($currentPlayer != $mainPlayer)
        {
          AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "EVR123,");
          AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "<-");
        }
        return "";
      case "EVR124":
        for($i=0; $i<$resourcesPaid; ++$i)
        {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRAURAS:maxCost=0");
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
        }
        AddDecisionQueue("SCOUR", $currentPlayer, $resourcesPaid);
        return "";
      case "EVR125": case "EVR126": case "EVR127":
        $oppTurn = $currentPlayer != $mainPlayer;
        if($cardID == "EVR125") $damage = ($oppTurn ? 6 : 4);
        if($cardID == "EVR126") $damage = ($oppTurn ? 5 : 3);
        if($cardID == "EVR127") $damage = ($oppTurn ? 4 : 2);
        DealArcane($damage, 0, "PLAYCARD", $cardID, resolvedTarget: $target);
        return "";
      case "EVR128": case "EVR129": case "EVR130":
        if($cardID == "EVR128") $numReveal = 3;
        else if($cardID == "EVR129") $numReveal = 2;
        else $numReveal = 1;
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, $numReveal);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target hero");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Target_Opponent,Target_Yourself");
        AddDecisionQueue("PLAYERTARGETEDABILITY", $currentPlayer, "PRY", 1);
        return "";
      case "EVR134": case "EVR135": case "EVR136":
        DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
        return "";
      case "EVR137":
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an Illusionist Aura to destroy and replace");
        AddDecisionQueue("FINDINDICES", $currentPlayer, "AURACLASS,ILLUSIONIST");
        AddDecisionQueue("MULTIZONEFORMAT", $currentPlayer, "MYAURAS", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "CROWNOFREFLECTION", 1);
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
        return "";
      case "EVR138":
        FractalReplicationStats("Ability");
        return "";
      case "EVR150": case "EVR151": case "EVR152":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVR157":
        if($from == "PLAY")
        {
          $rv = "Gained +1.";
          ++$combatChain[5];
        }
        return $rv;
      case "EVR160":
        Draw(1);
        Draw(2);
        if($currentPlayer != $mainPlayer) AddCurrentTurnEffect($cardID, $otherPlayer); //If played as an instant, needs to apply to the current turn
        else AddNextTurnEffect($cardID, $otherPlayer);
        return "";
      case "EVR161": case "EVR162": case "EVR163":
        $rand = GetRandom(1, 3);
        $altCostPaid = DelimStringContains($additionalCosts, "ALTERNATIVECOST");
        if($altCostPaid || $rand == 1) { WriteLog(CardLink($cardID, $cardID) . " gained 'When this hits, gain 2 life.'"); AddCurrentTurnEffect("EVR161-1", $currentPlayer); }
        if($altCostPaid || $rand == 2) { WriteLog(CardLink($cardID, $cardID) . " gained +2 power."); AddCurrentTurnEffect("EVR161-2", $currentPlayer); }
        if($altCostPaid || $rand == 3) { WriteLog(CardLink($cardID, $cardID) . " gained go again."); AddCurrentTurnEffect("EVR161-3", $currentPlayer); }
        return ($resourcesPaid == 0 ? "Party time!" : "");
      case "EVR164": case "EVR165": case "EVR166":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVR167": case "EVR168": case "EVR169":
        if($cardID == "EVR167") $times = 4;
        else if($cardID == "EVR168") $times = 3;
        else if($cardID == "EVR169") $times = 2;
        AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
        AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "MYHAND-", 1);
        AddDecisionQueue("MZOP", $otherPlayer, "GETCARDID", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "1", 1);
        AddDecisionQueue("WRITELOG", $currentPlayer, "<1> was chosen", 1);
        for($i=0; $i<$times; ++$i)
        {
          AddDecisionQueue("PICKACARD", $currentPlayer, "-", 1);
        }
        return "";
      case "EVR170": case "EVR171": case "EVR172":
        $rv = "Makes your next attack action that hits destroy an item";
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        if($from == "ARS")
        {
          AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
          $rv .= " and gives your next attack action card +" . EffectAttackModifier($cardID . "-2") . ".";
        }
        else { $rv .= "."; }
        return $rv;
      case "EVR173": case "EVR174": case "EVR175":
        if($cardID == "EVR173") $opt = 3;
        else if($cardID == "EVR174") $opt = 2;
        else if($cardID == "EVR175") $opt = 1;
        Opt($cardID, $opt);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "EVENBIGGERTHANTHAT");
        return "";
      case "EVR176":
        if($from == "PLAY")
        {
          $deck = &GetDeck($currentPlayer);
          if(count($deck) == 0) return "Deck is empty.";
          $mod = "DECK";
          if(CardType($deck[0]) == "AA") $mod = "TT";
          BanishCardForPlayer($deck[0], $mainPlayer, "DECK", $mod);
          WriteLog(CardLink($deck[0], $deck[0]) . " was banished.");
          array_shift($deck);
        }
        return "";
      case "EVR177":
        if($from == "PLAY")
        {
          if (ShouldAutotargetOpponent($currentPlayer)) {
            $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
            PummelHit($otherPlayer);
            PummelHit($otherPlayer);
          }
          else
          {
            AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target hero");
            AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Target_Opponent,Target_Yourself");
            AddDecisionQueue("PLAYERTARGETEDABILITY", $currentPlayer, "AMULETOFECHOES", 1);
          }
        }
        return "";
      case "EVR178":
        if($from == "PLAY")
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "EVR178");
          AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDCARDTOCHAIN", $currentPlayer, "DECK", 1);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        }
        return "";
      case "EVR179":
        if($from == "PLAY")
        {
          AddCurrentTurnEffect($cardID, $currentPlayer, $from);
          $rv = "Reduces your next ability cost by 1.";
        }
        return $rv;
      case "EVR180":
        if($from == "PLAY")
        {
          AddCurrentTurnEffect($cardID, $currentPlayer, $from);
          $rv = "Prevents 1 damage.";
        }
        return $rv;
      case "EVR181":
        if($from == "PLAY"){
          $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
        }
        return "";
      case "EVR182":
        if($from == "PLAY")
        {
          Opt($cardID, 2);
          $rv = "Lets you opt 2.";
        }
        return $rv;
      case "EVR183":
        if($from == "PLAY"){
          GainHealth(2, $currentPlayer);
        }
        return "";
      case "EVR184":
        if($from == "PLAY"){
          LookAtHand($otherPlayer);
          $rv = "Reveals the opponent's hand.";
        }
        return $rv;
      case "EVR185":
        if($from == "PLAY"){
          $cards = "";
          $pitch = &GetPitch($currentPlayer);
          while(count($pitch) > 0)
          {
            if($cards != "") $cards .= ",";
            $cards .= array_shift($pitch);
            for($i=1; $i<PitchPieces(); ++$i) array_shift($pitch);
          }
          if($cards != "") AddDecisionQueue("CHOOSETOP", $currentPlayer, $cards);
          $rv = "Puts your cards in your pitch zone on top of your deck.";
        }
        return $rv;
      case "EVR186":
        if($from == "PLAY")
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Gives your attack action cards +1 defense this turn.";
        }
        return $rv;
      case "EVR187":
        if($from == "PLAY"){
          $numToDraw = 0;
          $card = "";
          while(($card = RemoveHand($currentPlayer, 0)) != "") { AddBottomDeck($card, $currentPlayer, "HAND"); ++$numToDraw; }
          while(($card = RemoveArsenal($currentPlayer, 0)) != "") { AddBottomDeck($card, $currentPlayer, "ARS"); ++$numToDraw; }
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
          for ($i = 0; $i < $numToDraw; $i++) {
            AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
          }
          WriteLog(CardLink("EVR187","EVR187") . " shuffled your hand and arsenal into your deck and drew " . $numToDraw . " cards.");
        }
        return "";
      case "EVR190":
        $rv = "Talisman of Featherfoot is a partially manual card. Activate the instant ability if you met the criteria.";
        if($from == "PLAY"){
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
          GiveAttackGoAgain();
          $rv = "Gives the current attack go again.";
        }
        return $rv;
      case "EVR195":
        if($from == "PLAY"){
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
          $rv = "Draws a card.";
          Draw($currentPlayer);
        }
        return $rv;
      default: return "";
    }
  }

  function EVRHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer, $CS_NumAuras, $chainLinks;
    switch($cardID)
    {
      case "EVR021":
        if(IsHeroAttackTarget())
        {
          AddNextTurnEffect($cardID, $defPlayer);
        }
        break;
      case "EVR038":
        if(ComboActive())
        {
          $deck = &GetDeck($mainPlayer);
          BanishCardForPlayer($deck[0], $mainPlayer, "DECK", "NT");
          WriteLog(CardLink($deck[0], $deck[0]) . " was banished and can be played until end of next turn");
          array_shift($deck);
        }
        break;
      case "EVR039":
        for($i=0; $i<SearchCount(SearchChainLinks(-1, 2, "AA")); ++$i) Draw($mainPlayer);
        break;
      case "EVR040":
        if(ComboActive())
        {
          $deck = &GetDeck($mainPlayer);
          for($i=0; $i<count($chainLinks); ++$i)
          {
            $attackID = $chainLinks[$i][0];
            if($chainLinks[$i][2] == "1" && ($attackID == "EVR041" || $attackID == "EVR042" || $attackID == "EVR043"))
            {
              $chainLinks[$i][2] = "0";
              array_push($deck, $attackID);
            }
          }
          AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-");
        }
        break;
      case "EVR044": case "EVR045": case "EVR046":
        AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
        break;
      case "EVR088":
        if(IsHeroAttackTarget() && CanRevealCards($mainPlayer))
        {
          $hand = &GetHand($defPlayer);
          $cards = "";
          $numDiscarded = 0;
          for($i=count($hand)-HandPieces(); $i>=0; $i-=HandPieces())
          {
            $id = $hand[$i];
            $cardType = CardType($id);
            if($cardType != "A" && $cardType != "AA")
            {
              AddGraveyard($id, $defPlayer, "HAND");
              unset($hand[$i]);
              ++$numDiscarded;
            }
            if($cards != "") $cards .= ",";
            $cards .= $id;
          }
          LoseHealth($numDiscarded, $defPlayer);
          RevealCards($cards, $defPlayer);//CanReveal checked earlier
          WriteLog("Battering Bolt discarded " . $numDiscarded . " and caused the defending player to lose that much health.");
          $hand = array_values($hand);
        }
        break;
      case "EVR094": case "EVR095": case "EVR096":
        if(IsHeroAttackTarget())
        {
          AddNextTurnEffect($cardID, $defPlayer);
        }
        break;
      case "EVR097": case "EVR098": case "EVR099":
        if(IsHeroAttackTarget())
        {
          AddNextTurnEffect($cardID, $defPlayer);
        }
        break;
      case "EVR104":
        if(IsHeroAttackTarget())
        {
          AddDecisionQueue("FINDINDICES", $defPlayer, "AURACLASS,");
          AddDecisionQueue("MULTIZONEFORMAT", $defPlayer, "THEIRAURAS", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
          AddDecisionQueue("MZDESTROY", $mainPlayer, "-", 1);
          AddDecisionQueue("PASSPARAMETER", $mainPlayer, "ARC112", 1);
          AddDecisionQueue("PUTPLAY", $mainPlayer, "-", 1);
        }
        break;
      case "EVR105":
        if(IsHeroAttackTarget() && GetClassState($mainPlayer, $CS_NumAuras) >= 3) AddCurrentTurnEffect("EVR105", $defPlayer);
        break;
      case "EVR110": case "EVR111": case "EVR112":
        AddDecisionQueue("FINDINDICES", $mainPlayer, "GYNAA");
        AddDecisionQueue("MAYCHOOSEDISCARD", $mainPlayer, "<-", 1);
        AddDecisionQueue("REMOVEDISCARD", $mainPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $mainPlayer, "-", 1);
        AddDecisionQueue("SETDQVAR", $mainPlayer, "0", 1);
        AddDecisionQueue("WRITELOG", $mainPlayer, "<0> was selected.", 1);
        break;
      case "EVR113": case "EVR114": case "EVR115":
        if(IsHeroAttackTarget() && GetClassState($mainPlayer, $CS_NumAuras) > 0) PummelHit();
        break;
      case "EVR138":
        FractalReplicationStats("Hit");
        break;
      case "EVR156":
        if(IsHeroAttackTarget())
        {
          AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
          AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
          AddDecisionQueue("HANDCARD", $defPlayer, "-", 1);
          AddDecisionQueue("REVEALCARDS", $defPlayer, "-", 1);
          AddDecisionQueue("BINGO", $mainPlayer, "-", 1);
        }
      default: break;
    }
  }

  function HeaveValue($cardID)
  {
    switch($cardID)
    {
      case "EVR021": return 3;
      case "EVR024": case "EVR025": case "EVR026": return 3;
      default: return 0;
    }
  }

  function HeaveIndices()
  {
    global $mainPlayer;
    if(ArsenalFull($mainPlayer)) return "";//Heave does nothing if arsenal is full
    $hand = &GetHand($mainPlayer);
    $heaveIndices = "";
    for($i=0; $i<count($hand); $i+=HandPieces())
    {
      if(HeaveValue($hand[$i]) > 0)
      {
        if($heaveIndices != "") $heaveIndices .= ",";
        $heaveIndices .= $i;
      }
    }
    return $heaveIndices;
  }

  function Heave()
  {
    global $mainPlayer;
    AddDecisionQueue("FINDINDICES", $mainPlayer, "HEAVE");
    AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "You may choose to heave a card or pass");
    AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1, 1);
    AddDecisionQueue("MULTIREMOVEHAND", $mainPlayer, "-", 1);
    AddDecisionQueue("HEAVE", $mainPlayer, "-", 1);
  }

  function BravoStarOfTheShowIndices()
  {
    global $mainPlayer;
    $earth = SearchHand($mainPlayer, "", "", -1, -1, "", "EARTH");
    $ice = SearchHand($mainPlayer, "", "", -1, -1, "", "ICE");
    $lightning = SearchHand($mainPlayer, "", "", -1, -1, "", "LIGHTNING");
    if($earth != "" && $ice != "" && $lightning != "")
    {
      $indices = CombineSearches($earth, $ice);
      $indices = CombineSearches($indices, $lightning);
      $count = SearchCount($indices);
      if($count > 3) $count = 3;
      return $count . "-" . SearchRemoveDuplicates($indices);
    }
    return "";
  }

  //Returns true if it should be destroyed
  function TalismanOfBalanceEndTurn()
  {
    global $mainPlayer, $defPlayer;
    if(ArsenalFull($mainPlayer)) return false;
    $mainArs = &GetArsenal($mainPlayer);
    $defArs = &GetArsenal($defPlayer);
    if(count($mainArs) < count($defArs))
    {
      $deck = &GetDeck($mainPlayer);
      $card = array_shift($deck);
      AddArsenal($card, $mainPlayer, "DECK", "DOWN");
      WriteLog("Talisman of Balance destroyed itself and put a card in your arsenal.");
      return true;
    }
    return false;
  }

  function LifeOfThePartyIndices()
  {
    global $currentPlayer;
    $auras = SearchMultizoneFormat(SearchItemsForCard("WTR162", $currentPlayer), "MYITEMS");
    $handCards = SearchMultizoneFormat(SearchHandForCard($currentPlayer, "WTR162"), "MYHAND");
    return CombineSearches($auras, $handCards);
  }

  function CoalescentMirageDestroyed()
  {
    global $mainPlayer;
    AddDecisionQueue("FINDINDICES", $mainPlayer, "COALESCENTMIRAGE");
    AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $mainPlayer, "-", 1);
    AddDecisionQueue("PLAYAURA", $mainPlayer, "<-", 1);
  }

  function MirragingMetamorphDestroyed()
  {
    global $mainPlayer;
    AddDecisionQueue("FINDINDICES", $mainPlayer, "AURACLASS,");
    AddDecisionQueue("MULTIZONEFORMAT", $mainPlayer, "MYAURAS", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
    AddDecisionQueue("MULTIZONETOKENCOPY", $mainPlayer, "-", 1);
  }

  function BloodOnHerHandsResolvePlay($userInput)
  {
    global $currentPlayer;
    for($i=0; $i<count($userInput); ++$i)
    {
      switch($userInput[$i])
      {
        case "Buff_Weapon":
          WriteLog("Blood on Her Hands gives a weapon +1 this turn.");
          AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a weapon to give +1", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDMZBUFF", $currentPlayer, "EVR055-1", 1);
          break;
        case "Go_Again":
          WriteLog("Blood on Her Hands gives a weapon go again this turn.");
          AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a weapon to give go again", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDMZBUFF", $currentPlayer, "EVR055-2", 1);
          break;
        case "Another_Swing":
          WriteLog("Blood on Her Hands gives a weapon a second attack this turn.");
          AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a weapon to give a second attack", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDMZUSES", $currentPlayer, "1", 1);
          break;
        default: break;
      }
    }
  }

  function FractalReplicationStats($stat)
  {
    global $chainLinks, $combatChain, $currentPlayer, $chainLinkSummary;

    $highestAttack = 0;
    $highestBlock = 0;
    $hasPhantasm = false;
    $hasGoAgain = false;
    $hasDominate = false;
    for($i=0; $i<count($chainLinks); ++$i)
    {
      for($j=0; $j<count($chainLinks[$i]); $j+=ChainLinksPieces())
      {
        $isIllusionist = ClassContains($chainLinks[$i][$j], "ILLUSIONIST", $currentPlayer) || ($j == 0 && DelimStringContains($chainLinkSummary[$i*ChainLinkSummaryPieces()+3], "ILLUSIONIST"));
        if($chainLinks[$i][$j+2] == "1" && $chainLinks[$i][$j] != "EVR138" && $isIllusionist && CardType($chainLinks[$i][$j]) == "AA")
        {
          if($stat == "Hit")
          {
            ProcessHitEffect($chainLinks[$i][$j]);
          }
          elseif ($stat == "Ability")
          {
            PlayAbility($chainLinks[$i][$j], "HAND", 0);
          }
          else
          {
            $attack = AttackValue($chainLinks[$i][$j]);
            if($attack > $highestAttack) $highestAttack = $attack;
            $block = BlockValue($chainLinks[$i][$j]);
            if($block > $highestBlock) $highestBlock = $block;
            if(!$hasPhantasm) $hasPhantasm = HasPhantasm($chainLinks[$i][$j]);
            if(!$hasGoAgain) $hasGoAgain = HasGoAgain($chainLinks[$i][$j]);
          }
        }
      }
    }
    for($i=0; $i<count($combatChain); $i+=CombatChainPieces())
    {
      if($combatChain[$i] != "EVR138" && ClassContains($combatChain[$i], "ILLUSIONIST", $currentPlayer) && CardType($combatChain[$i]) == "AA")
      {
        if($stat == "Hit")
        {
          ProcessHitEffect($combatChain[$i]);
        }
        elseif ($stat == "Ability")
        {
            PlayAbility($combatChain[$i], "HAND", 0);
        }
        else
        {
          $attack = AttackValue($combatChain[$i]);
          if($attack > $highestAttack) $highestAttack = $attack;
          $block = BlockValue($combatChain[$i]);
          if($block > $highestBlock) $highestBlock = $block;
          if(!$hasPhantasm) $hasPhantasm = HasPhantasm($combatChain[$i]);
          if(!$hasGoAgain) $hasGoAgain = HasGoAgain($combatChain[$i]);
        }
      }
    }
    switch($stat)
    {
      case "Attack": return $highestAttack;
      case "Block": return $highestBlock;
      case "Phantasm": return $hasPhantasm;
      case "GoAgain": return $hasGoAgain;
      default: return 0;
    }
  }

  function ShatterIndices($player, $pendingDamage)
  {
    $character = &GetPlayerCharacter($player);
    $indices = "";
    for($i=0; $i<count($character); $i+=CharacterPieces())
    {
      if($character[$i+6] == 1 && $character[$i+1] != 0 && CardType($character[$i]) == "E" && (BlockValue($character[$i]) - $character[$i+4]) < $pendingDamage)
      {
        if($indices != "") $indices .= ",";
        $indices .= $i;
      }
    }
    return $indices;
  }

  function KnickKnackIndices($player)
  {
    $deck = &GetDeck($player);
    $indices = "";
    for($i=0; $i<count($deck); $i+=DeckPieces())
    {
      if(CardSubType($deck[$i]) == "Item")
      {
        $name = CardName($deck[$i]);
        if(str_contains($name, "Potion") || str_contains($name, "Talisman") || str_contains($name, "Amulet"))
        {
          if($indices != "") $indices .= ",";
          $indices .= $i;
        }
      }
    }
    return $indices;
  }

  function CashOutIndices($player)
  {
    $equipIndices = SearchMultizoneFormat(GetEquipmentIndices($player), "MYCHAR");
    $weaponIndices = WeaponIndices($player, $player);
    $itemIndices = SearchMultizoneFormat(SearchItems($player, "A"), "MYITEMS");
    $rv = CombineSearches($equipIndices, $weaponIndices);
    return CombineSearches($rv, $itemIndices);
  }
?>
