<?php
function MSTAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    "MST001", "MST002", "MST025", "MST026", "MST027", "MST046", "MST047", "MST048", "MST029", "MST030", "MST067",
    "MST071", "MST072", "MST073", "MST074", "MST133", "MST232", "MST238" => "I",
    "MST003", "MST159" => "AA",
    "MST004", "MST006", "MST007", "MST069", "MST070" => "AR",
    default => ""
  };
}

function MSTAbilityCost($cardID): int
{
  return match ($cardID) {
    "MST001", "MST004", "MST027", "MST026", "MST025", "MST048", "MST067", "MST047", "MST046", "MST238", "MST002" => 3,
    "MST159", "MST003" => 2,
    "MST006", "MST030", "MST029", "MST070", "MST069", "MST007" => 1,
    default => 0
  };
}

function MSTCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer, $CS_NumBluePlayed, $combatChainState, $CCS_LinkBaseAttack, $CS_Transcended, $CombatChain;
  $from = $CombatChain->AttackCard()->From();
  $idArr = explode(",", $cardID);
  $cardID = $idArr[0];
  return match ($cardID) {
    "MST000" => IsHeroAttackTarget(),
    "MST003", "MST092", "MST093", "MST193-BLUE" => ColorContains($attackID, 3, $mainPlayer),
    "MST054-1", "MST055-1", "MST056-1", "MST054-2", "MST055-2", "MST056-2", "MST053", "MST063", "MST064", "MST065",
    "MST159", "MST161", "MST185", "MST186", "MST187" => CardNameContains($attackID, "Crouching Tiger", $mainPlayer),
    "MST094" => $from != "PLAY" && ColorContains($attackID, 3, $mainPlayer) && (TypeContains($attackID, "AA", $mainPlayer) || TypeContains($attackID, "A", $mainPlayer)),
    "MST193-RED" => ColorContains($attackID, 1, $mainPlayer),
    "MST193-YELLOW" => ColorContains($attackID, 2, $mainPlayer),
    "MST212", "MST213", "MST214" => $combatChainState[$CCS_LinkBaseAttack] <= 1,
    "MST232" => CardSubType($attackID) == "Arrow",
    "MST011-1", "MST012-1", "MST013-1", "MST011-2", "MST012-2", "MST013-2", "MST014", "MST015", "MST016", "MST017-BUFF",
    "MST018-BUFF", "MST019-BUFF", "MST020", "MST021", "MST022", "MST023", "MST024", "MST051-BUFF", "MST069", "MST075",
    "MST077", "MST079-HITPREVENTION", "MST079-DEBUFF", "MST084", "MST085", "MST086", "MST095", "MST102", "MST105-BUFF",
    "MST105-HIT", "MST162-BUFF", "MST162-HIT", "MST190", "MST198", "MST233", "MST236-1", "MST236-2" => true,
    default => false,
  };
}

function MSTEffectAttackModifier($cardID): int
{
  global $mainPlayer;
  $idArr = explode(",", $cardID);
  $cardID = $idArr[0];
  return match ($cardID) {
    "MST095", "MST190" => -1,
    "MST000", "MST079-DEBUFF" => IsHeroAttackTarget() ? -1 : 0,
    "MST084", "MST085" => SearchPitchForColor($mainPlayer, 3),
    "MST011-2", "MST054-2", "MST105-BUFF" => 5,
    "MST012-2", "MST055-2", "MST232" => 4,
    "MST011-1", "MST013-2", "MST014", "MST020", "MST054-1", "MST056-2", "MST063", "MST075", "MST161", "MST162-BUFF",
    "MST193-BLUE" => 3,
    "MST012-1", "MST015", "MST021", "MST055-1", "MST064", "MST077", "MST093", "MST193-YELLOW", "MST198" => 2,
    "MST003", "MST016", "MST022", "MST023", "MST051-BUFF", "MST053", "MST056-1", "MST065", "MST013-1", "MST017-BUFF",
    "MST018-BUFF", "MST019-BUFF", "MST069", "MST102", "MST159", "MST185", "MST186", "MST187", "MST193-RED", "MST212",
    "MST213", "MST214", "MST233", "MST236-1" => 1,
    default => 0,
  };
}

function MSTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $CS_NumBluePlayed, $CS_Transcended, $mainPlayer, $CS_DamagePrevention, $CS_PlayIndex;
  global $combatChain, $defPlayer, $CombatChain, $chainLinks, $combatChainState, $CCS_LinkBaseAttack, $CS_NumAttacks;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $hand = &GetHand($currentPlayer);
  switch ($cardID) {
    case "MST000":
      if (GetClassState($otherPlayer, $CS_NumAttacks) <= 1) AddCurrentTurnEffect($cardID, $otherPlayer);
      return "";
    case "MST001":
    case "MST002":
      AddDecisionQueue("DECKCARDS", $otherPlayer, "0");
      AddDecisionQueue("SETDQVAR", $otherPlayer, "0");
      AddDecisionQueue("ALLCARDPITCHORPASS", $currentPlayer, "3", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to banish <0> with " . CardLink($cardID, $cardID)."?");
      AddDecisionQueue("YESNO", $currentPlayer, "whether to banish a card with " . CardLink($cardID, $cardID), 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("PARAMDELIMTOARRAY", $currentPlayer, "0", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $otherPlayer, "0", 1);
      AddDecisionQueue("MULTIBANISH", $otherPlayer, "DECK,-," . $cardID, 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{0}");
      AddDecisionQueue("NONECARDPITCHORPASS", $currentPlayer, "3");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, CardLink($cardID, $cardID)." shows the top of your deck is <0>");
      AddDecisionQueue("OK", $currentPlayer, "whether to banish a card with " . CardLink($cardID, $cardID), 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "-");
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "MST004":
      AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
      AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose a card to banish", 1);
      AddDecisionQueue("CHOOSEHAND", $otherPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
      AddDecisionQueue("BANISHCARD", $otherPlayer, "HAND,-", 1);
      return "";
    case "MST006":
      AddPlayerHand("MST023", $currentPlayer, $cardID); //Fang Strike
      return "";
    case "MST007":
      AddPlayerHand("MST024", $currentPlayer, $cardID); //Slither
      return "";
    case "MST008":
      AddPlayerHand("MST024", $currentPlayer, $cardID); //Slither
      $mod = "-";
      if (SearchCardList($additionalCosts, $currentPlayer, subtype: "Chi") != "") $mod = "TCCGorgonsGaze";
      $defendingCards = GetChainLinkCards($defPlayer);
      if (!empty($defendingCards)) {
        $defendingCards = explode(",", $defendingCards);
        foreach (array_reverse($defendingCards) as $card) {
          if (CardType($combatChain[$card]) === "AA") {
            BanishCardForPlayer($combatChain[$card], $defPlayer, "CC", $mod, $cardID);
            $index = GetCombatChainIndex($combatChain[$card], $defPlayer);
            $CombatChain->Remove($index);
          }
        }
      }
      foreach ($chainLinks as &$link) {
        for ($k = 0; $k < count($link); $k += ChainLinksPieces()) {
          if (CardType($link[$k]) == "AA" && $link[$k + 1] == $defPlayer) {
            BanishCardForPlayer($link[$k], $defPlayer, "CC", $mod, $cardID);
            $link[$k + 2] = 0;
          }
        }
      }
      return "";
    case "MST009":
      $deck = new Deck($defPlayer);
      if (IsHeroAttackTarget()) {
        LookAtHand($defPlayer);
        AddDecisionQueue("FINDINDICES", $otherPlayer, "HANDPITCH,3");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to add to the chain link", 1);
        AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
        AddDecisionQueue("ADDCARDTOCHAINASDEFENDINGCARD", $otherPlayer, "HAND", 1);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
      }
      return "";
    case "MST010":
      if ($additionalCosts != "-") {
        $modes = explode(",", $additionalCosts);
        for ($i = 0; $i < count($modes); ++$i) {
          switch ($modes[$i]) {
            case "Create_a_Fang_Strike_and_Slither":
              AddPlayerHand("MST023", $currentPlayer, $cardID); //Fang Strike
              AddPlayerHand("MST024", $currentPlayer, $cardID); //Slither
              break;
            case "Banish_up_to_2_cards_in_an_opposing_hero_graveyard":
              AddDecisionQueue("FINDINDICES", $otherPlayer, $cardID);
              AddDecisionQueue("MULTICHOOSETHEIRDISCARD", $currentPlayer, "<-", 1);
              AddDecisionQueue("MULTIREMOVEDISCARD", $otherPlayer, "-", 1);
              AddDecisionQueue("MULTIBANISH", $otherPlayer, "DISCARD,Source-" . $cardID . "," . $cardID, 1);
              AddDecisionQueue("UNDERCURRENTDESIRES", $currentPlayer, "-", 1);
              break;
            case "Transcend":
              Transcend($currentPlayer, "MST410", $from);
              break;
            default:
              break;
          }
        }
      }
      return "";
    case "MST011":
    case "MST012":
    case "MST013":
    case "MST054":
    case "MST055":
    case "MST056":
    case "MST034":
    case "MST035":
    case "MST036":
      if (GetClassState($currentPlayer, $CS_Transcended) <= 0) AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
      else AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
      return "";
    case "MST014":
    case "MST015":
    case "MST016":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if (SearchPitchForColor($currentPlayer, 3) > 0) AddPlayerHand("MST024", $currentPlayer, $cardID); //Slither
      return "";
    case "MST017":
    case "MST018":
    case "MST019":
      $amount = 4;
      if ($cardID == "MST018") $amount = 3;
      elseif ($cardID == "MST019") $amount = 2;
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Here's the card that on top of your deck.", 1);
      AddDecisionQueue("FINDINDICES", $otherPlayer, "DECKTOPXINDICES," . $amount);
      AddDecisionQueue("DECKCARDS", $currentPlayer, "<-", 1);
      AddDecisionQueue("LOOKTOPDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("CHOOSETHEIRDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDCARDTOCHAINASDEFENDINGCARD", $otherPlayer, "DECK", 1);
      AddDecisionQueue("ALLCARDPITCHORPASS", $currentPlayer, "3", 1);
      AddDecisionQueue("PUTCOMBATCHAINDEFENSE0", $otherPlayer, "-", 1);
      AddDecisionQueue("PUTINANYORDER", $currentPlayer, $amount - 1);
      AddCurrentTurnEffect($cardID . "-BUFF", $currentPlayer);
      return "";
    case "MST020":
    case "MST021":
    case "MST022":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if (SearchPitchForColor($currentPlayer, 3) > 0) AddPlayerHand("MST023", $currentPlayer, $cardID); //Fang Strike
      return "";
    case "MST023":
    case "MST024":
    case "MST027":
    case "MST069":
    case "MST084":
    case "MST085":
    case "MST086":
    case "MST092":
    case "MST093":
    case "MST094":
    case "MST159":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "MST025":
    case "MST026":
      PlayAura("MON104", $currentPlayer, 1, numAttackCounters: 1);
      return "";
    case "MST029":
      MZMoveCard($currentPlayer, "MYDISCARD:subtype=Aura", "MYBOTDECK");
      return "";
    case "MST030":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS:hasWard=true", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target aura");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZADDCOUNTERS", $currentPlayer, "1", 1);
      return "";
    case "MST032":
      if ($additionalCosts != "-") {
        $modes = explode(",", $additionalCosts);
        for ($i = 0; $i < count($modes); ++$i) {
          switch ($modes[$i]) {
            case "Create_2_Spectral_Shield":
              PlayAura("MON104", $currentPlayer, 2);
              break;
            case "Put_a_+1_counter_on_each_aura_with_ward_you_control":
              AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS:hasWard=true", 1);
              AddDecisionQueue("ADDALLATTACKCOUNTERS", $currentPlayer, "1", 1);
              break;
            case "Transcend":
              Transcend($currentPlayer, "MST432", $from);
              break;
            default:
              break;
          }
        }
      }
      return "";
    case "MST046":
    case "MST047":
      AddPlayerHand("DYN065", $currentPlayer, "NA");
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDECK:comboOnly=true", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZADDZONE", $currentPlayer, "MYBANISH,DECK,TT", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      return "";
    case "MST048":
      PlayAura("CRU075", $currentPlayer); //Zen Token
      return "";
    case "MST051":
      if (CanRevealCards($currentPlayer)) {
        AddDecisionQueue("FINDINDICES", $currentPlayer, "CROUCHINGTIGERHAND");
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which cards to reveal", 1);
        AddDecisionQueue("MAYMULTICHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REVEALHANDCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("TOOTHANDCLAW", $currentPlayer, "-", 1);
      }
      break;
    case "MST052":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if (SearchCardList($additionalCosts, $currentPlayer, subtype: "Chi") != "") AddPlayerHand("DYN065", $currentPlayer, "NA", 2);
      return "";
    case "MST053":
      if ($additionalCosts != "-") {
        $modes = explode(",", $additionalCosts);
        for ($i = 0; $i < count($modes); ++$i) {
          switch ($modes[$i]) {
            case "Create_2_Crouching_Tigers":
              AddPlayerHand("DYN065", $currentPlayer, "NA", 2);
              break;
            case "Crouching_Tigers_Get_+1_this_turn":
              AddCurrentTurnEffect($cardID, $currentPlayer);
              break;
            case "Transcend":
              Transcend($currentPlayer, "MST453", $from);
              break;
            default:
              break;
          }
        }
      }
      return "";
    case "MST057":
    case "MST058":
    case "MST059":
    case "MST060":
    case "MST061":
    case "MST062":
      if (SearchPitchForColor($currentPlayer, 3) > 0) AddPlayerHand("DYN065", $currentPlayer, $cardID);
      return "";
    case "MST063":
    case "MST064":
    case "MST065":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if (SearchPitchForColor($currentPlayer, 3) > 0) AddPlayerHand("DYN065", $currentPlayer, $cardID);
      return "";
    case "MST067":
      Draw($currentPlayer);
      return "";
    case "MST070":
      GiveAttackGoAgain();
      return "";
    case "MST071":
    case "MST072":
    case "MST073":
    case "MST074":
      IncrementClassState($currentPlayer, $CS_DamagePrevention);
      return "";
    case "MST075":
      if (IsHeroAttackTarget()) {
        $deck = new Deck($defPlayer);
        if ($deck->Reveal(1) && PitchValue($deck->Top()) == 3) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
      }
      return "";
    case "MST076":
      $chiArray = explode(",", SearchCardList($additionalCosts, $currentPlayer, subtype: "Chi"));
      $amountChiPitch = count($chiArray);
      if (SearchCardList($additionalCosts, $currentPlayer, subtype: "Chi") != "") {
        switch ($amountChiPitch) {
          case 1:
            $combatChainState[$CCS_LinkBaseAttack] = 10;
            break;
          case 2:
            $combatChainState[$CCS_LinkBaseAttack] = 15;
            break;
          case 3:
            $combatChainState[$CCS_LinkBaseAttack] = 20;
            break;
          default:
            break;
        }
      }
      return "";
    case "MST077":
      $modalities = "Draw_a_card,Buff_Power,Go_again";
      $numChoices = SearchPitchForColor($currentPlayer, 3);
      if ($numChoices >= 3) {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $modalities);
        AddDecisionQueue("MODAL", $currentPlayer, "LEVELSOFENLIGHTENMENT", 1);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      } elseif ($numChoices < 3 && $numChoices > 0) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose " . $numChoices . " modes");
        AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, $numChoices . "-" . $modalities . "-" . $numChoices);
        AddDecisionQueue("MODAL", $currentPlayer, "LEVELSOFENLIGHTENMENT", 1);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      }
      return "";
    case "MST078":
      if (SearchCardList($additionalCosts, $currentPlayer, subtype: "Chi") != "") Draw($currentPlayer);
      return "";
    case "MST079":
      AddCurrentTurnEffect($cardID . "-DEBUFF", $otherPlayer);
      if (SearchCardList($additionalCosts, $currentPlayer, subtype: "Chi") != "") AddCurrentTurnEffect($cardID . "-HITPREVENTION", $currentPlayer);
      return "";
    case "MST080":
      Draw($currentPlayer);
      Draw($currentPlayer);
      if (SearchCardList($additionalCosts, $currentPlayer, subtype: "Chi") != "") Draw($currentPlayer);
      return "";
    case "MST095":
      if ($CombatChain->HasCurrentLink() || HasAttackLayer()) {
        AddCurrentTurnEffect($cardID, $mainPlayer);
      }
      if (GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST495," . $from);
      return "";
    case "MST096":
      GainHealth(1, $currentPlayer);
      if (GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST496," . $from);
      return "";
    case "MST097":
      $params = explode("-", $target);
      $index = SearchdiscardForUniqueID($params[1], $otherPlayer);
      if ($index != -1) {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "THEIRDISCARD-" . $index, 1);
        AddDecisionQueue("MZADDZONE", $currentPlayer, "THEIRBANISH,GY,-," . $cardID, 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        if (GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST497," . $from);
      } else {
        WriteLog(CardLink($cardID, $cardID) . " layer fails as there are no remaining targets for the targeted effect and this card does not transcend.");
        return "FAILED";
      }
      return "";
    case "MST098":
      GiveAttackGoAgain();
      if (GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST498," . $from);
      return "";
    case "MST099":
      $params = explode("-", $target);
      $index = SearchdiscardForUniqueID($params[1], $currentPlayer);
      if ($index != -1) {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "MYDISCARD-" . $index, 1);
        AddDecisionQueue("MZADDZONE", $currentPlayer, "MYBOTDECK", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        if (GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST499," . $from);
      } else {
        WriteLog(CardLink($cardID, $cardID) . " layer fails as there are no remaining targets for the targeted effect and this card does not transcend.");
        return "FAILED";
      }
      return "";
    case "MST100":
      Draw($currentPlayer);
      if (count($hand) == 1) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Show the card drawn:");
        AddDecisionQueue("OK", $currentPlayer, "<-", 1);
      }
      MZMoveCard($currentPlayer, "MYHAND", "MYBOTDECK", silent: true, DQContext: "Choose a card to put on the bottom of your deck:");
      if (GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST500," . $from);
      return "";
    case "MST101":
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      if (GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST501," . $from);
      return "";
    case "MST102":
      if ($CombatChain->HasCurrentLink() || HasAttackLayer()) {
        AddCurrentTurnEffect($cardID, $mainPlayer);
      }
      if (GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST502," . $from);
      return "";
    case "MST105":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "JUSTANICK", 1);
      return "";
    case "MST132":
      if ($from != "PLAY") {
        $illusionistAuras = SearchAura($currentPlayer, class: "ILLUSIONIST");
        $arrayAuras = explode(",", $illusionistAuras);
        if (count($arrayAuras) <= 1) GainActionPoints(1, $currentPlayer);
      }
      return "";
    case "MST133":
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      $auras = GetAuras($currentPlayer);
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($from != "PLAY") {
        $count = CountAuraAtkCounters($currentPlayer) + 10; //+10 is an arbitrary number to keep the loop going until the player pass
        for ($i = 0; $i < $count; $i++) {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS:hasAttackCounters=true", 1);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura to remove a -1 attack counter or pass", 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZOP", $currentPlayer, "TRANSFERATKCOUNTER", 1);
        }
        AddCurrentTurnEffect($cardID, $currentPlayer, $from, $auras[count($auras) - AuraPieces() + 6]);
      }
      if ($abilityType != "I") return "";

      if (SearchCurrentTurnEffectsForUniqueID($auras[$index + 6] . "-PAID") != -1) {
        PlayAura("MON104", $currentPlayer);
        RemoveCurrentTurnEffect(SearchCurrentTurnEffectsForUniqueID($auras[$index + 6] . "-PAID"));
      } elseif (SearchCurrentTurnEffectsForPartielID("PAID")) //It needs to check if the auras was destroy, but it's already paid for
      {
        PlayAura("MON104", $currentPlayer);
        RemoveCurrentTurnEffect(SearchCurrentTurnEffectsForUniqueID($auras[$index + 6] . "-PAID"));
      } else {
        WriteLog("You do not have the counters to pay for " . CardLink($cardID, $cardID) . " ability.", highlight: true);
      }
      return "";
    case "MST134":
    case "MST135":
    case "MST136":
      $amount = 3;
      if ($cardID == "MST135") $amount = 2;
      else if ($cardID == "MST136") $amount = 1;
      $params = explode("-", $target);
      if(substr($params[0], 0, 5) != "THEIR") {
        $zone = "MYAURAS-";
        $player = $currentPlayer;
      }
      else {
        $zone = "THEIRAURAS-";
        $player = $otherPlayer;
      }
      $index = SearchAurasForUniqueID($params[1], $player);
      if ($index != -1) {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $zone . $index, 1);
        AddDecisionQueue("MZADDCOUNTERS", $currentPlayer, $amount, 1);
      }
      else {
        WriteLog(CardLink($cardID, $cardID) . " layer fails as there are no remaining targets for the targeted effect.");
        return "FAILED";
      }
      return "";
    case "MST146":
    case "MST147":
    case "MST148":
      if ($from != "PLAY") {
        $auras = &GetAuras($currentPlayer);
        $illusionistAuras = SearchAura($currentPlayer, class: "ILLUSIONIST");
        $arrayAuras = explode(",", $illusionistAuras);
        $amount = 3;
        if ($cardID == "MST147") $amount = 2;
        else if ($cardID == "MST148") $amount = 1;
        if (count($arrayAuras) <= 1) {
          $index = count($auras) - AuraPieces();
          $auras[$index + 3] += $amount;
        }
      }
      return "";
    case "MST149":
    case "MST150":
    case "MST151":
      if ($from != "PLAY") {
        $illusionistAuras = SearchAura($currentPlayer, class: "ILLUSIONIST");
        $arrayAuras = explode(",", $illusionistAuras);
        if (count($arrayAuras) <= 1) PlayAura("MON104", $currentPlayer);
      }
      return "";
    case "MST152":
    case "MST153":
    case "MST154":
      if (SearchAura($currentPlayer, class: "ILLUSIONIST") != "") $amount = 0;
      else if ($cardID == "MST152") $amount = 3;
      else if ($cardID == "MST153") $amount = 2;
      else if ($cardID == "MST154") $amount = 1;
      PlayAura("MON104", $currentPlayer, numAttackCounters: $amount);
      return "";
    case "MST161":
      if (ComboActive()) AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "MST162":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "MAUL", 1);
      return "";
    case "MST164":
    case "MST165":
    case "MST166":
      if (ComboActive()) {
        BanishCardForPlayer("DYN065", $currentPlayer, "-", "TT", $currentPlayer);
      }
      return "";
    case "MST176":
    case "MST177":
    case "MST178":
      if (ComboActive()) {
        BanishCardForPlayer("DYN065", $currentPlayer, "-", "TT", $currentPlayer);
        GiveAttackGoAgain();
      }
      return "";
    case "MST185":
    case "MST186":
    case "MST187":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "MST193":
      AddCurrentTurnEffect($cardID . "-RED", $currentPlayer);
      AddCurrentTurnEffect($cardID . "-YELLOW", $currentPlayer);
      AddCurrentTurnEffect($cardID . "-BLUE", $currentPlayer);
      break;
    case "MST197":
      if ($additionalCosts != "-") Draw($currentPlayer);
      break;
    case "MST198":
      if ($additionalCosts != "-") AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "MST199":
      if ($additionalCosts != "-") AddDecisionQueue("OP", $currentPlayer, "GIVEATTACKGOAGAIN", 1);
      break;
    case "MST200":
    case "MST201":
    case "MST202":
      if (IsHeroAttackTarget()) MZMoveCard($currentPlayer, "THEIRDISCARD", "THEIRBANISH", true, true, DQContext: "Choose a card to banish from their graveyard.");
      return "";
    case "MST212":
    case "MST213":
    case "MST232":
    case "MST214":
      AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      return "";
    case "MST225":
      PutItemIntoPlayForPlayer("DYN243", $currentPlayer, effectController: $currentPlayer);
      $numGold = CountItem("DYN243", $currentPlayer);
      if ($numGold >= 3 && !SearchCurrentTurnEffects("OUT183", $currentPlayer)) {
        PlayAura("HVY241", $currentPlayer, $numGold); //Might
        WriteLog(CardLink($cardID, $cardID) . " created a Gold token and " . $numGold . " Might tokens");
      } else WriteLog(CardLink($cardID, $cardID) . " created a Gold token");
      return "";
    case "MST226":
      for ($i = 0; $i < intval($additionalCosts); ++$i) {
        AddDecisionQueue("VISITTHEGOLDENANVIL", $currentPlayer, "-");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to equip");
        AddDecisionQueue("CHOOSECARD", $currentPlayer, "<-");
        AddDecisionQueue("APPENDLASTRESULT", $currentPlayer, "-INVENTORY");
        AddDecisionQueue("EQUIPCARD", $currentPlayer, "<-");
      }
      return "";
    case "MST227":
      $cardList = SearchItemsByName($currentPlayer, "Hyper Driver");
      $countHyperDriver = count(explode(",", $cardList));
      if ($resourcesPaid > $countHyperDriver) $resourcesPaid = $countHyperDriver;
      for ($i = 0; $i < $resourcesPaid; $i++) {
        if ($i == 0) {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYITEMS:isSameName=ARC036");
          AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        }
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose " . $resourcesPaid . " Hyper Driver to get " . $resourcesPaid . " steam counter", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "{0}", 1);
        AddDecisionQueue("SUPERCELL", $currentPlayer, $resourcesPaid, 1);
      }
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "EVO234", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, $resourcesPaid, 1);
      if ($resourcesPaid >= 3 && SearchBanishForCardName($currentPlayer, "DYN092") != -1) {
        MZMoveCard($currentPlayer, "MYBANISH:isSameName=DYN092", "MYTOPDECK", true, true, DQContext: "Choose a card to shuffle in your deck, or pass");
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      }
      return "";
    case "MST233":
      if (HasAimCounter()) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      return "";
    case "MST234":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if (count($hand) == 0) Draw($currentPlayer);
      return "";
    case "MST235":
      PutPermanentIntoPlay($currentPlayer, $cardID);
      return "";
    case "MST238":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=E;faceDown=true");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("ENIGMAMOON", $currentPlayer, "-", 1);
      return "";
    default:
      return "";
  }
}

function MSTHitEffect($cardID, $from): void
{
  global $mainPlayer, $defPlayer, $combatChainState, $CCS_DamageDealt;
  $deck = new Deck($defPlayer);
  $discard = new Discard($defPlayer);
  switch ($cardID) {
    case "MST003":
      if ($from != "OUT139" && $from != "OUT148" && $from != "OUT149" && $from != "OUT150") AddCurrentTurnEffect($cardID, $mainPlayer);
      else AddCurrentTurnEffectNextAttack($cardID, $mainPlayer);
      break;
    case "MST103":
      $count = count(GetDeck($defPlayer));
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND");
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card from your opponent hand", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZSETDQVAR", $mainPlayer, "0", 1);
      for ($i = 0; $i < 3; $i++) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND:isSameName={0}&THEIRDECK:isSameName={0}&THEIRDISCARD:isSameName={0}", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which cards you want your opponent to banish", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $mainPlayer, "-,Source-" . $cardID . "," . $cardID, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      AddDecisionQueue("FINDINDICES", $defPlayer, "DECKTOPXINDICES," . $count);
      AddDecisionQueue("DECKCARDS", $defPlayer, "<-", 1);
      AddDecisionQueue("LOOKTOPDECK", $defPlayer, "-", 1);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, CardLink($cardID, $cardID) . " shows the your opponents deck are", 1);
      AddDecisionQueue("MULTISHOWCARDSTHEIRDECK", $mainPlayer, "<-", 1);
      AddDecisionQueue("SHUFFLEDECK", $defPlayer, "-");
      break;
    case "MST104":
      if (IsHeroAttackTarget()) {
        LookAtHand($defPlayer);
        $pitchValue = PitchValue($deck->Top());
        $deck->BanishTop("Source-" . $cardID, banishedBy: $cardID);
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND:pitch=" . $pitchValue);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want your opponent to banish", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $mainPlayer, "HAND,Source-" . $cardID . "," . $cardID, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "MST106":
    case "MST107":
    case "MST108":
      if (IsHeroAttackTarget()) {
        $deck->BanishTop("Source-" . $cardID, banishedBy: $cardID);
      }
      break;
    case "MST109":
    case "MST110":
    case "MST111":
      if (IsHeroAttackTarget()) {
        $deck->BanishTop("Source-" . $cardID, banishedBy: $cardID);
        if ($discard->NumCards() > 0) MZMoveCard($mainPlayer, "THEIRDISCARD", "THEIRBANISH,GY,Source-" . $cardID . "," . $cardID, silent: true);
      }
      break;
    case "MST112":
    case "MST113":
    case "MST114":
      if (IsHeroAttackTarget() && NumAttackReactionsPlayed() > 1) {
        $deck->BanishTop("Source-" . $cardID, banishedBy: $cardID);
        $deck->BanishTop("Source-" . $cardID, banishedBy: $cardID);
      }
      break;
    case "MST115":
    case "MST116":
    case "MST117":
      if (IsHeroAttackTarget()) {
        $deck = new Deck($defPlayer);
        $deck->BanishTop("Source-" . $cardID, banishedBy: $cardID);
        if ($discard->NumCards() > 0) MZMoveCard($mainPlayer, "THEIRDISCARD", "THEIRBANISH,GY,Source-" . $cardID . "," . $cardID, silent: true);
      }
      break;
    case "MST118":
    case "MST119":
    case "MST120":
    case "MST121":
    case "MST122":
    case "MST123":
    case "MST124":
    case "MST125":
    case "MST126":
      if (IsHeroAttackTarget()) {
        $deck->BanishTop("Source-" . $cardID, banishedBy: $cardID);
      }
      break;
    case "MST173":
    case "MST174":
    case "MST175":
      BanishCardForPlayer("DYN065", $mainPlayer, "-", "TT", $cardID);
      break;
    case "MST191":
      $hand = GetHand($mainPlayer);
      if (count($hand) > 0) {
        AddDecisionQueue("FINDINDICES", $mainPlayer, "HAND");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card from your hand to discard.");
        AddDecisionQueue("CHOOSEHAND", $mainPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $mainPlayer, "-", 1);
        AddDecisionQueue("DISCARDCARD", $mainPlayer, "HAND-" . $mainPlayer, 1);
        AddDecisionQueue("FINDINDICES", $defPlayer, "HAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card from your hand to discard.", 1);
        AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $defPlayer, "-", 1);
        AddDecisionQueue("DISCARDCARD", $defPlayer, "HAND-" . $mainPlayer, 1);
      }
      break;
    case "MST192":
      LookAtHand($defPlayer);
      AddDecisionQueue("BLOCKLESS0HAND", $defPlayer, "THEIRHAND:maxDef=-1");
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want your opponent to discard", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZDISCARD", $mainPlayer, "HAND," . $mainPlayer, 1);
      AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
      break;
    case "MST194":
    case "MST195":
    case "MST196":
      AddCurrentTurnEffect($cardID, $defPlayer);
      AddNextTurnEffect($cardID, $defPlayer);
      break;
    case "MST206":
    case "MST207":
    case "MST208":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRCHAR:type=E;faceDown=true&THEIRARS:faceDown=true");
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZREVEAL", $mainPlayer, "-", 1);
      break;
    case "MST233":
      $trapsArr = explode(",", SearchDiscard($mainPlayer, subtype: "Trap"));
      if (count($trapsArr) >= 3) {
        AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish traps");
        AddDecisionQueue("NOPASS", $mainPlayer, "-");
        AddDecisionQueue("FINDINDICES", $mainPlayer, "MULTITRAPSBANISH", 1);
        AddDecisionQueue("PREPENDLASTRESULT", $mainPlayer, "3-", 1);
        AddDecisionQueue("APPENDLASTRESULT", $mainPlayer, "-3", 1);
        AddDecisionQueue("MULTICHOOSEDISCARD", $mainPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $mainPlayer, "MURKYWATER", 1);
      }
      break;
    default:
      break;
  }
}
