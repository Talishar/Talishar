<?php

  function CRUAbilityCost($cardID)
  {
    global $CS_PlayIndex, $currentPlayer;

    switch($cardID)
    {
      case "CRU004": case "CRU005": return 2;
      case "CRU024": return 4;
      case "CRU025": return 3;
      case "CRU049": return 1;
      case "CRU050": case "CRU051": case "CRU052": return 1;
      case "CRU079": case "CRU080": return 1;
      case "CRU101": return (GetResolvedAbilityType($cardID) == "A" ? 2 : 0);
      case "CRU105": $items = &GetItems($currentPlayer); return ($items[GetClassState($currentPlayer, $CS_PlayIndex) + 1] > 0 ? 0 : 1);
      case "CRU118": return 3;
      case "CRU122": return 2;
      case "CRU140": return 1;
      case "CRU160": return 2;
      case "CRU177": return 2;
      case "CRU197": return 4;
      default: return 0;
    }
  }

  function CRUAbilityType($cardID, $index=-1)
  {
    global $myCharacter, $myClassState, $CS_PlayIndex;
    switch($cardID)
    {
      case "CRU004": case "CRU005": return "AA";
      case "CRU006": return "A";
      case "CRU024": return "AA";
      case "CRU025": return "A";
      case "CRU049": return "AA";
      case "CRU050": case "CRU051": case "CRU052": return "AA";
      case "CRU079": case "CRU080": return "AA";
      case "CRU081": return "A";
      case "CRU101": return "A";
      case "CRU102": return "A";
      case "CRU105": return "A";
      case "CRU118": return "A";
      case "CRU121": return "A";
      case "CRU122": return "A";
      case "CRU140": return "AA";
      case "CRU141": return "I";
      case "CRU160": return "A";
      case "CRU177": return "AA";
      case "CRU197": return "A";
      default: return "";
    }
  }

  function CRUAbilityHasGoAgain($cardID)
  {
    global $myCharacter, $myClassState, $CS_PlayIndex;
    switch($cardID)
    {
      case "CRU006": return true;
      case "CRU025": return true;
      case "CRU081": return true;
      case "CRU101": return GetResolvedAbilityType($cardID) == "A";
      case "CRU102": return true;
      case "CRU105": return true;
      case "CRU118": return true;
      case "CRU121": case "CRU122": return true;
      case "CRU197": return true;
      default: return false;
    }
  }

  function CRUEffectAttackModifier($cardID)
  {
    switch($cardID)
    {
      case "CRU008": return 2;
      case "CRU025": return 2;
      case "CRU029": return 10;
      case "CRU030": return 9;
      case "CRU031": return 8;
      case "CRU038": return 3;
      case "CRU039": return 2;
      case "CRU040": return 1;
      case "CRU046": return 1;
      case "CRU047": return 1;
      case "CRU055": return 3;
      case "CRU072": return 1;
      case "CRU084": return 2;
      case "CRU085-1": return 3;
      case "CRU086-1": return 2;
      case "CRU087-1": return 1;
      case "CRU088-1": return 3;
      case "CRU089-1": return 2;
      case "CRU090-1": return 1;
      case "CRU088-2": return 1;
      case "CRU089-2": return 1;
      case "CRU090-2": return 1;
      case "CRU091-2": return 3;
      case "CRU092-2": return 2;
      case "CRU093-2": return 1;
      case "CRU094-1": return 3;
      case "CRU095-1": return 2;
      case "CRU096-1": return 1;
      case "CRU105": return 1;
      case "CRU109": case "CRU110": case "CRU111": return 3;
      case "CRU135": return 3;
      case "CRU136": return 2;
      case "CRU137": return 1;
      default: return 0;
    }
  }

  function CRUCombatEffectActive($cardID, $attackID)
  {
    global $combatChain, $combatChainState, $mainPlayer, $CCS_IsBoosted, $CS_ArsenalFacing;
    switch($cardID)
    {
      //Brute
      case "CRU008": return true;
      case "CRU013": case "CRU014": case "CRU015": return true;
      //Guardian
      case "CRU025": return HasCrush($attackID);
      case "CRU029": case "CRU030": case "CRU031": return CardType($attackID) == "AA" && ClassContains($attackID, "GUARDIAN", $mainPlayer);
      case "CRU038": case "CRU039": case "CRU040": return CardType($attackID) == "AA" && ClassContains($attackID, "GUARDIAN", $mainPlayer);
      //Ninja
      case "CRU046": return true;
      case "CRU047": return true;
      case "CRU053": return HasCombo($combatChain[0]);
      case "CRU055": return true;
      case "CRU072": return true;
      //Warrior
      case "CRU084": return CardType($attackID) == "W";
      case "CRU084-2": return CardType($attackID) == "W";
      case "CRU085-1": case "CRU086-1": case "CRU087-1": return CardType($attackID) == "W";
      case "CRU088-1": case "CRU089-1": case "CRU090-1": return CardType($attackID) == "W";
      case "CRU088-2": case "CRU089-2": case "CRU090-2": return true;
      case "CRU091-1": case "CRU092-1": case "CRU093-1": return CardType($attackID) == "W";
      case "CRU091-2": case "CRU092-2": case "CRU093-2": return true;
      case "CRU094-1": case "CRU095-1": case "CRU096-1": return CardType($attackID) == "W";
      case "CRU094-2": case "CRU095-2": case "CRU096-2": return true;
      //Mechnologist
      case "CRU105": return CardType($attackID) == "W" && CardSubtype($attackID) == "Pistol" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer);
      case "CRU106": case "CRU107": case "CRU108": return $combatChainState[$CCS_IsBoosted] == "1";
      case "CRU109": case "CRU110": case "CRU111": return $combatChainState[$CCS_IsBoosted] == "1";
      //Ranger
      case "CRU122": return $combatChain[2] == "ARS" && GetClassState($mainPlayer, $CS_ArsenalFacing) == "UP" && CardSubtype($attackID) == "Arrow"; //The card being played from ARS and being an Arrow implies that the card is UP.
      case "CRU123": return $attackID == "CRU123";
      case "CRU124": return CardSubtype($combatChain[0]) == "Arrow";
      case "CRU125": return true;
      case "CRU135": case "CRU136": case "CRU137": return CardSubtype($attackID) == "Arrow";
      case "CRU135-1": case "CRU136-1": case "CRU137-1": return CardSubtype($attackID) == "Arrow";
      //Runeblade
      case "CRU145": case "CRU146": case "CRU147": return CardType($attackID) == "AA" && ClassContains($attackID, "RUNEBLADE", $mainPlayer);
      default: return false;
    }
  }

  function CRUHasGoAgain($cardID)
  {
    switch($cardID)
    {
      //CRU Ninja
      case "CRU084": return true;
      case "CRU085": case "CRU086": case "CRU087": return true;
      case "CRU091": case "CRU092": case "CRU093": return true;
      case "CRU094": case "CRU095": case "CRU096": return true;
      //CRU Brute
      case "CRU009": return true;
      case "CRU019": case "CRU020": case "CRU021": return true;
      //CRU Ninja
      case "CRU050": case "CRU051": case "CRU052": return true;
      case "CRU072": case "CRU074": return true;
      //CRU Ranger
      case "CRU124": case "CRU135": case "CRU136": case "CRU137": return true;
      //CRU Runeblade
      case "CRU143": return true;
      case "CRU145": case "CRU146": case "CRU147": return true;
      case "CRU154": case "CRU155": case "CRU156": return true;
      //CRU Generic
      case "CRU181": case "CRU188": return true;
      default: return false;
    }
  }

function CRUPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
  global $mainPlayer, $CS_NumBoosted, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $currentPlayer, $defPlayer;
  global $CS_AtksWWeapon, $CS_Num6PowDisc, $CCS_WeaponIndex, $CS_NextDamagePrevented, $CS_PlayIndex, $CS_NextWizardNAAInstant, $CS_NumWizardNonAttack;
  global $CCS_BaseAttackDefenseMax, $CCS_ResourceCostDefenseMin, $CCS_CardTypeDefenseRequirement, $CCS_RequiredEquipmentBlock, $CCS_NumBoosted;
  $rv = "";
  switch ($cardID) {
      //CRU Brute
    case "CRU004": case "CRU005":
      if (GetClassState($currentPlayer, $CS_Num6PowDisc) > 0) {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        $rv = "Gains go again.";
      }
      return $rv;
    case "CRU006":
      MyDrawCard();
      $discarded = DiscardRandom($currentPlayer, $cardID);
      return "Discarded " . CardLink($discarded, $discarded);
    case "CRU008":
      if (GetClassState($currentPlayer, $CS_Num6PowDisc) > 0) {
        Intimidate();
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      return "";
    case "CRU009":
      $roll = GetDieRoll($currentPlayer);
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      for ($i = 1; $i < $roll; $i += 2) //half rounded down
      {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRITEMS", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      }
      return "Argh... Smash! rolled " . $roll . ".";
    case "CRU013": case "CRU014": case "CRU015":
      if (GetClassState($currentPlayer, $CS_Num6PowDisc) > 0) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $rv = "Gains Dominate.";
      }
      return $rv;
      //CRU Guardian
    case "CRU025":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives your Crush attacks +2 this turn.";
    case "CRU028":
      if (CountPitch(GetPitch($currentPlayer), 3) >= 2) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $rv = "Stamp Authority gives you +1 intellect until end of turn.";
      }
      return $rv;
    case "CRU041": case "CRU042": case "CRU043":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Prevents some of the next damage you take this turn.";
      //Ninja
    case "CRU049":
      if (CountPitch(GetPitch($currentPlayer), 0, 0)) $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
      return "";
    case "CRU054":
      if (ComboActive()) {
        $numLinks = NumChainLinks();
        $combatChainState[$CCS_ResourceCostDefenseMin] = $numLinks;
        $rv = "Cannot be defended by cards with cost less than " . $numLinks . ".";
      }
      return $rv;
    case "CRU055":
      if (ComboActive()) {
        FloodOfForcePlayEffect();
        $rv = "Reveals the top card of your deck and puts it in your hand if it has combo.";
      }
      return $rv;
    case "CRU056":
      if (ComboActive()) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Attack_Action,Non-attack_Action");
        AddDecisionQueue("SETCOMBATCHAINSTATE", $currentPlayer, $CCS_CardTypeDefenseRequirement, 1);
      }
      return $rv;
    case "CRU057": case "CRU058": case "CRU059":
      if (ComboActive()) {
        $numLinks = NumChainLinks();
        $combatChainState[$CCS_BaseAttackDefenseMax] = $numLinks;
        $rv = "Cannot be defended by attacks with greater than " . $numLinks . " base attack.";
      }
      return $rv;
      //CRU Warrior
    case "CRU081":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Reduces the cost of your weapon attacks by 1 this turn.";
    case "CRU082":
      $character = &GetPlayerCharacter($currentPlayer);
      ++$character[$combatChainState[$CCS_WeaponIndex] + 5];
      if ($character[$combatChainState[$CCS_WeaponIndex] + 1] == 1) {
        $character[$combatChainState[$CCS_WeaponIndex] + 1] = 2;
      }
      return "Allows you to attack with target sword an additional time.";
    case "CRU083":
      if (RepriseActive()) {
        AddDecisionQueue("DECKCARDS", $currentPlayer, "0");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("ALLCARDTYPEORPASS", $currentPlayer, "AR", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to banish <0> with Unified Decree?");
        AddDecisionQueue("YESNO", $currentPlayer, "whether to banish a card the card", 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("PARAMDELIMTOARRAY", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,TCC", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("WRITELOG", $currentPlayer, "<0> was banished.", 1);
      }
      return "Gives your weapon attack +" . AttackModifier($cardID) . " and looks for an attack reaction.";
    case "CRU084":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect($cardID . "-2", $currentPlayer); //Hit effect
      return "Gives your next weapon attack +2 and go again.";
    case "CRU085": case "CRU086": case "CRU087":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      AddCurrentTurnEffect($cardID . "-2", ($mainPlayer == 1 ? 2 : 1));
      return "Gives your next weapon attack  +" . EffectAttackModifier($cardID . "-1") . " and makes the next Defense Reaction cost +1 to play.";
    case "CRU088": case "CRU089": case "CRU090":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      if (RepriseActive()) AddCurrentTurnEffectFromCombat($cardID . "-2", $mainPlayer);
      return "Gives your weapon attack +" . EffectAttackModifier($cardID . "-1") . RepriseActive() ? " and gives your next attack +1." : ".";
    case "CRU091": case "CRU092": case "CRU093":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      $atkWWpn = GetClassState($currentPlayer, $CS_AtksWWeapon) > 0;
      if ($atkWWpn) AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
      return "Gives your next weapon attack go again" . ($atkWWpn ? " and +" . EffectAttackModifier($cardID . "-2") : "") . ".";
    case "CRU094": case "CRU095": case "CRU096":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      $atkWWpn = GetClassState($currentPlayer, $CS_AtksWWeapon) > 0;
      if ($atkWWpn) AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
      return "Gives your next weapon attack +" . EffectAttackModifier($cardID . "-1") . ($atkWWpn ? " and gives your next attack Dominate." : ".");
      //CRU Mechanologist
    case "CRU101":
      $abilityType = GetResolvedAbilityType($cardID);
      if($abilityType == "A")
      {
        $character = &GetPlayerCharacter($currentPlayer);
        $index = GetClassState($currentPlayer, $CS_PlayIndex);
        $character[$index + 2] = 1;
      }
      return "";
    case "CRU102":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU103":
      if ($combatChainState[$CCS_NumBoosted] && !IsAllyAttackTarget()) {
        $combatChainState[$CCS_RequiredEquipmentBlock] = $combatChainState[$CCS_NumBoosted];
        $rv .= "Requires you to block with " . $combatChainState[$CCS_NumBoosted] . " equipment if able.";
      }
      return $rv;
    case "CRU105":
      if($from == "PLAY") {
        $items = &GetItems($currentPlayer);
        $index = GetClassState($currentPlayer, $CS_PlayIndex);
        if(ClassContains($items[$index], "MECHANOLOGIST", $currentPlayer) && $items[$index + 2] == 2 && $additionalCosts == "PAID") {
          $items[$index + 2] = 1;
          AddCurrentTurnEffect($cardID, $currentPlayer); //Show an effect for better visualization.
          $rv = "Gives pistol +1";
        } else {
          $items[$index + 1] = 1;
          $rv = "Gains a steam counter";
        }
      }
      return $rv;
    case "CRU115": case "CRU116": case "CRU117":
      if ($cardID == "CRU115") $maxCost = 2;
      else if ($cardID == "CRU116") $maxCost = 1;
      else if ($cardID == "CRU117") $maxCost = 0;
      Opt($cardID, GetClassState($currentPlayer, $CS_NumBoosted));
      AddDecisionQueue("DECKCARDS", $currentPlayer, "0");
      AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
      AddDecisionQueue("ALLCARDSUBTYPEORPASS", $currentPlayer, "Item", 1);
      AddDecisionQueue("ALLCARDMAXCOSTORPASS", $currentPlayer, $maxCost, 1);
      AddDecisionQueue("FINDINDICES", $currentPlayer, "TOPDECK", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
      return "Lets you opt and put an item from the top of your deck into play.";
      //CRU Merchant
    case "CRU118":
      if (PlayerHasLessHealth(1)) {
        LoseHealth(1, 2);
        PutItemIntoPlayForPlayer("CRU197", 2);
        WriteLog("Player 2 lost a health and gained a copper from Kavdaen");
        if (PlayerHasLessHealth(1)) {
          GainHealth(1, 1);
        }
      } else if (PlayerHasLessHealth(2)) {
        LoseHealth(1, 1);
        PutItemIntoPlayForPlayer("CRU197", 1);
        WriteLog("Player 1 lost a health and gained a copper from Kavdaen");
        if (PlayerHasLessHealth(2)) {
          GainHealth(1, 2);
        }
      }
      return "";
      //CRU Ranger
    case "CRU121":
      if (ArsenalFull($currentPlayer)) return "Your arsenal is full, so you cannot put an arrow in your arsenal.";
      AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
      AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDARSENALFACEUP", $currentPlayer, "HAND", 1);
      return "";
    case "CRU122":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives face up arrow attacks go again this turn.";
    case "CRU124":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      Reload();
      return "Makes arrow attacks discard on hero hit, and allows you to Reload.";
    case "CRU125":
      SetClassState($currentPlayer, $CS_NextDamagePrevented, 1);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Prevents the next damage you would take.";
    case "CRU126":
      if (!IsAllyAttacking()) {
        TrapTriggered($cardID);
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_pay_1_to_allow_hit_effects_this_chain_link", 1, 1);
        AddDecisionQueue("NOPASS", $otherPlayer, $cardID, 1);
        AddDecisionQueue("PAYRESOURCES", $otherPlayer, "1", 1);
        AddDecisionQueue("ELSE", $otherPlayer, "-");
        AddDecisionQueue("TRIPWIRETRAP", $otherPlayer, "-", 1);
      }
      return "";
    case "CRU127":
      if(!IsAllyAttacking()) {
        TrapTriggered($cardID);
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_pay_1_to_avoid_taking_2_damage", 1, 1);
        AddDecisionQueue("NOPASS", $otherPlayer, $cardID, 1);
        AddDecisionQueue("PAYRESOURCES", $otherPlayer, "1", 1);
        AddDecisionQueue("ELSE", $otherPlayer, "-");
        AddDecisionQueue("TAKEDAMAGE", $otherPlayer, 2, 1);
      }
      return "";
    case "CRU128":
      if (!IsAllyAttacking()) {
        TrapTriggered($cardID);
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_pay_1_to_avoid_your_attack_getting_-2", 1, 1);
        AddDecisionQueue("NOPASS", $otherPlayer, $cardID, 1);
        AddDecisionQueue("PAYRESOURCES", $otherPlayer, "1", 1);
        AddDecisionQueue("ELSE", $otherPlayer, "-");
        AddDecisionQueue("ATTACKMODIFIER", $otherPlayer, "-2", 1);
      }
      return "";
    case "CRU135": case "CRU136": case "CRU137":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect($cardID . "-1", ($currentPlayer == 1 ? 2 : 1));
      return "Gives the next arrow attack this turn +" . EffectAttackModifier($cardID) . " and prevents defense reactions on that chain link.";
      //CRU Runeblade
    case "CRU141":
      AddCurrentTurnEffect($cardID . "-AA", $currentPlayer);
      AddCurrentTurnEffect($cardID . "-NAA", $currentPlayer);
      return "Reduces the cost of your next attack action card and non-attack action card this turn.";
    case "CRU142":
      //When you attack with Dread Triptych, if you've played a 'non-attack' action card this turn, create a Runechant token.
      //When you attack with Dread Triptych, if you've dealt arcane damage this turn, create a Runechant token.
      AddLayer("TRIGGER", $currentPlayer, $cardID);
      return "";
    case "CRU143":
      AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
      AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIBANISH", $currentPlayer, "GY,TT", 1);
      return "Banishes a Runeblade attack action card, which can be played this turn.";
    case "CRU144":
      PlayAura("ARC112", $currentPlayer, 4);
      return "Creates 4 Runechant.";
    case "CRU145": case "CRU146": case "CRU147":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives your next Runeblade attack action go again and creates Runechants if it hits.";
    case "CRU154": case "CRU155": case "CRU156":
      if (!CanRevealCards($currentPlayer)) return "Cannot reveal cards.";
      if ($cardID == "CRU154") $count = 3;
      else if ($cardID == "CRU155") $count = 2;
      else $count = 1;
      $deck = &GetDeck($currentPlayer);
      $cards = "";
      for ($i = 0; $i < $count; ++$i) {
        if (count($deck) > 0) {
          if ($cards != "") $cards .= ",";
          $card = array_shift($deck);
          $cards .= $card;
          if (ClassContains($card, "RUNEBLADE", $currentPlayer) && CardType($card) == "AA") PlayAura("ARC112", $currentPlayer);
        }
      }
      RevealCards($cards);
      AddDecisionQueue("CHOOSETOP", $currentPlayer, $cards);
      return "";
      //CRU Wizard
    case "CRU160":
      DealArcane(2, 0, "ABILITY", $cardID);
        return "";
    case "CRU162":
      $rv = "Lets you play your next Wizard non-attack as an instant";
      SetClassState($currentPlayer, $CS_NextWizardNAAInstant, 1);
      if (GetClassState($currentPlayer, $CS_NumWizardNonAttack) >= 2) {
        DealArcane(3, 1, "PLAYCARD", $cardID, resolvedTarget: $target);
      }
      return $rv . ".";
    case "CRU163":
      Opt($cardID, 2);
      return "";
    case "CRU164":
      NegateLayer($target);
      return "Negates an instant.";
    case "CRU165": case "CRU166": case "CRU167":
      if ($cardID == "CRU165") $optAmt = 3;
      else if ($cardID == "CRU166") $optAmt = 2;
      else $optAmt = 1;
      AddCurrentTurnEffect($cardID, $currentPlayer);
      Opt($cardID, $optAmt);
      return "";
    case "CRU168": case "CRU169": case "CRU170":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      Opt($cardID, 1);
      return "";
    case "CRU171": case "CRU172": case "CRU173":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU174": case "CRU175": case "CRU176":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD",$cardID, resolvedTarget: $target);
      return "";
      //CRU Generics
    case "CRU181":
      $count = SearchCount(CombineSearches(SearchDiscardForCard(1, "CRU181"), SearchDiscardForCard(2, "CRU181")));
      for ($i = 0; $i < $count; ++$i) {
        MyDrawCard();
      }
      if ($count <= 1) return "Draws " . $count . " card.";
      else return "Draws " . $count . " cards.";
    case "CRU182":
      AddCurrentTurnEffect("CRU182", ($currentPlayer == 1 ? 2 : 1));
      return "Makes attack actions unable to gain power.";
    case "CRU183": case "CRU184": case "CRU185":
      if ($from == "ARS") {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        $rv = "Gains go again.";
      }
      return $rv;
    case "CRU188":
      MyDrawCard();
      MyDrawCard();
      return "Draws 2 cards.";
    case "CRU189": case "CRU190": case "CRU191":
      $options = GetChainLinkCards($defPlayer, "AA");
      if ($options == "") return "No defending attack action cards.";
      AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, $options);
      AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $currentPlayer, PlayBlockModifier($cardID), 1);
      return "";
    case "CRU197":
      if ($from == "PLAY") {
        MyDrawCard();
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
      }
      return "";
    default:
      return "";
  }
}

function CRUHitEffect($cardID)
{
  global $mainPlayer, $defPlayer, $combatChainState, $CS_ArcaneDamageTaken;
  switch($cardID) {
    case "CRU054":
      if(ComboActive()) {
        PlayAura("CRU075", $mainPlayer);
      }
      break;
    case "CRU060": case "CRU061": case "CRU062":
      if(ComboActive()) RushingRiverHitEffect();
      break;
    case "CRU066": case "CRU067": case "CRU068":
      GiveAttackGoAgain();
      break;
    case "CRU069": case "CRU070": case "CRU071":
      GiveAttackGoAgain();
      break;
    case "CRU072":
      AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
      break;
    case "CRU074":
      if (HitsInRow() > 0) {
        Draw($mainPlayer);
        Draw($mainPlayer);
      }
      break;
    case "CRU106": case "CRU107": case "CRU108":
      AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
      break;
    case "CRU109": case "CRU110": case "CRU111":
      AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
      break;
    case "CRU123":
      if (IsHeroAttackTarget()) {
        AddCurrentTurnEffect("CRU123-DMG", $defPlayer);
        AddNextTurnEffect("CRU123-DMG", $defPlayer);
      }
      break;
    case "CRU129": case "CRU130": case "CRU131":
      if(!ArsenalEmpty($mainPlayer)) return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
      AddDecisionQueue("FINDINDICES", $mainPlayer, "MAINHAND");
      AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $mainPlayer, "-", 1);
      AddDecisionQueue("ADDARSENALFACEDOWN", $mainPlayer, "HAND", 1);
      break;
    case "CRU132": case "CRU133": case "CRU134":
      if(IsHeroAttackTarget()) {
        $char = &GetPlayerCharacter($defPlayer);
        $char[1] = 3;
      }
      break;
    case "CRU142":
      PlayAura("ARC112", $mainPlayer);
      break;
    case "CRU148": case "CRU149": case "CRU150":
      if(IsHeroAttackTarget() && GetClassState($defPlayer, $CS_ArcaneDamageTaken)) {
        PummelHit();
      }
      break;
    case "CRU151": case "CRU152": case "CRU153":
      PlayAura("ARC112", $mainPlayer);
      break;
    case "CRU180":
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose any number of options");
      AddDecisionQueue("MAYMULTICHOOSETEXT", $mainPlayer, "3-Quicken_token,Draw_card,Gain_life");
      AddDecisionQueue("COAXCOMMOTION", $mainPlayer, "-", 1);
      break;
    case "CRU183": case "CRU184": case "CRU185":
      TopDeckToArsenal($defPlayer);
      TopDeckToArsenal($mainPlayer);
      break;
    default:
      break;
  }
}

function RushingRiverHitEffect()
{
  global $combatChainState, $mainPlayer;
  $num = NumAttacksHit()+1;
  for($i = 0; $i < $num; ++$i) {
    Draw($mainPlayer);
    AddDecisionQueue("FINDINDICES", $mainPlayer, "HAND");
    AddDecisionQueue("CHOOSEHAND", $mainPlayer, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $mainPlayer, "-", 1);
    AddDecisionQueue("MULTIADDTOPDECK", $mainPlayer, "-", 1);
  }
}

function FloodOfForcePlayEffect()
{
  global $mainPlayer;
  AddDecisionQueue("DECKCARDS", $mainPlayer, "0");
  AddDecisionQueue("REVEALCARDS", $mainPlayer, "-", 1);
  AddDecisionQueue("ALLCARDSCOMBOORPASS", $mainPlayer, "-", 1);
  AddDecisionQueue("FINDINDICES", $mainPlayer, "TOPDECK", 1);
  AddDecisionQueue("MULTIREMOVEDECK", $mainPlayer, "<-", 1);
  AddDecisionQueue("MULTIADDHAND", $mainPlayer, "-", 1);
  AddDecisionQueue("ADDCURRENTEFFECT", $mainPlayer, "CRU055", 1);
}

function KayoStaticAbility()
{
  global $combatChainState, $CCS_LinkBaseAttack, $mainPlayer;
  $roll = GetDieRoll($mainPlayer);
  if($roll >= 5)
  {
    if(CanGainAttack()) $combatChainState[$CCS_LinkBaseAttack] *= 2;
  }
  else $combatChainState[$CCS_LinkBaseAttack] = floor($combatChainState[$CCS_LinkBaseAttack] / 2);
}

function KassaiEndTurnAbility()
{
  global $mainPlayer, $CS_AtksWWeapon, $CS_HitsWithWeapon;
  if(GetClassState($mainPlayer, $CS_AtksWWeapon) >= 2) {
    for($i = 0; $i < GetClassState($mainPlayer, $CS_HitsWithWeapon); ++$i) {
      PutItemIntoPlayForPlayer("CRU197", $mainPlayer);
    }
  }
}
