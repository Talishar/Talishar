<?php

  function CRUAbilityCost($cardID) {
    global $CS_PlayIndex, $currentPlayer;
    switch($cardID) {
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

  function CRUAbilityType($cardID, $index=-1) {
    switch($cardID) {
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

  function CRUAbilityHasGoAgain($cardID) {
    switch($cardID) {
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

  function CRUEffectAttackModifier($cardID) {
    switch($cardID) {
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
      case "CRU186": return 1;
      default: return 0;
    }
  }

  function CRUCombatEffectActive($cardID, $attackID) {
    global $CombatChain, $combatChainState, $mainPlayer, $CCS_IsBoosted, $CS_ArsenalFacing;
    switch($cardID) {
      case "CRU008": return true;
      case "CRU013": case "CRU014": case "CRU015": return true;
      case "CRU025": return HasCrush($attackID);
      case "CRU029": case "CRU030": case "CRU031": return CardType($attackID) == "AA" && ClassContains($attackID, "GUARDIAN", $mainPlayer);
      case "CRU038": case "CRU039": case "CRU040": return CardType($attackID) == "AA" && ClassContains($attackID, "GUARDIAN", $mainPlayer);
      case "CRU046": return true;
      case "CRU047": return true;
      case "CRU053": return HasCombo($attackID);
      case "CRU055": return true;
      case "CRU072": return true;
      case "CRU084": return CardType($attackID) == "W";
      case "CRU084-2": return CardType($attackID) == "W";
      case "CRU085-1": case "CRU086-1": case "CRU087-1": return CardType($attackID) == "W";
      case "CRU088-1": case "CRU089-1": case "CRU090-1": return CardType($attackID) == "W";
      case "CRU088-2": case "CRU089-2": case "CRU090-2": return true;
      case "CRU091-1": case "CRU092-1": case "CRU093-1": return CardType($attackID) == "W";
      case "CRU091-2": case "CRU092-2": case "CRU093-2": return true;
      case "CRU094-1": case "CRU095-1": case "CRU096-1": return CardType($attackID) == "W";
      case "CRU094-2": case "CRU095-2": case "CRU096-2": return true;
      case "CRU105": return CardType($attackID) == "W" && CardSubtype($attackID) == "Pistol" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer);
      case "CRU106": case "CRU107": case "CRU108": return $combatChainState[$CCS_IsBoosted] == "1";
      case "CRU109": case "CRU110": case "CRU111": return $combatChainState[$CCS_IsBoosted] == "1";
      case "CRU122": return $CombatChain->AttackCard()->From() == "ARS" && GetClassState($mainPlayer, $CS_ArsenalFacing) == "UP" && CardSubtype($attackID) == "Arrow"; //The card being played from ARS and being an Arrow implies that the card is UP.
      case "CRU123": return $attackID == "CRU123";
      case "CRU124": return CardSubtype($attackID) == "Arrow";
      case "CRU125": return true;
      case "CRU135": case "CRU136": case "CRU137": return CardSubtype($attackID) == "Arrow";
      case "CRU135-1": case "CRU136-1": case "CRU137-1": return CardSubtype($attackID) == "Arrow";
      case "CRU145": case "CRU146": case "CRU147": return CardType($attackID) == "AA" && ClassContains($attackID, "RUNEBLADE", $mainPlayer);
      case "CRU186": return true;
      default: return false;
    }
  }

function CRUPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts) {
  global $mainPlayer, $CS_NumBoosted, $combatChainState, $currentPlayer, $defPlayer;
  global $CS_AtksWWeapon, $CS_Num6PowDisc, $CCS_WeaponIndex, $CS_NextDamagePrevented, $CS_PlayIndex, $CS_NextWizardNAAInstant, $CS_NumWizardNonAttack;
  global $CCS_BaseAttackDefenseMax, $CCS_ResourceCostDefenseMin, $CCS_CardTypeDefenseRequirement, $CCS_RequiredEquipmentBlock, $CCS_NumBoosted;
  $rv = "";
  switch($cardID) {
    case "CRU004": case "CRU005":
      if(GetClassState($currentPlayer, $CS_Num6PowDisc) > 0) {
        GiveAttackGoAgain();
        $rv = "Gains go again";
      }
      return $rv;
    case "CRU006":
      Draw($currentPlayer);
      $discarded = DiscardRandom($currentPlayer, $cardID);
      return "";
    case "CRU008":
      if(GetClassState($currentPlayer, $CS_Num6PowDisc) > 0) {
        Intimidate();
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      return "";
    case "CRU009":
      $roll = GetDieRoll($currentPlayer);
      for($i = 1; $i < $roll; $i += 2) {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRITEMS", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      }
      return "Argh... Smash! rolled " . $roll;
    case "CRU013": case "CRU014": case "CRU015":
      if(GetClassState($currentPlayer, $CS_Num6PowDisc) > 0) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $rv = "Gains Dominate.";
      }
      return $rv;
    case "CRU025":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU028":
      if(SearchCount(SearchPitch($mainPlayer, minCost:3)) >= 2) AddCurrentTurnEffect($cardID, $currentPlayer);
      return $rv;
    case "CRU041": case "CRU042": case "CRU043":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU049":
      if(SearchCount(SearchPitch($currentPlayer, minCost:0, maxCost:0)) > 0) GiveAttackGoAgain();
      return "";
    case "CRU054":
      if(ComboActive()) {
        $numLinks = NumChainLinks();
        $combatChainState[$CCS_ResourceCostDefenseMin] = $numLinks;
        $rv = "Cannot be defended by cards with cost less than " . $numLinks;
      }
      return $rv;
    case "CRU055":
      if(ComboActive()) {
        AddDecisionQueue("DECKCARDS", $mainPlayer, "0");
        AddDecisionQueue("REVEALCARDS", $mainPlayer, "-", 1);
        AddDecisionQueue("ALLCARDSCOMBOORPASS", $mainPlayer, "-", 1);
        AddDecisionQueue("FINDINDICES", $mainPlayer, "TOPDECK", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $mainPlayer, "<-", 1);
        AddDecisionQueue("MULTIADDHAND", $mainPlayer, "-", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $mainPlayer, "CRU055", 1);
        $rv = "Reveals the top card of your deck and puts it in your hand if it has combo";
      }
      return $rv;
    case "CRU056":
      if(ComboActive()) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Attack_Action,Non-attack_Action");
        AddDecisionQueue("SETCOMBATCHAINSTATE", $currentPlayer, $CCS_CardTypeDefenseRequirement, 1);
      }
      return "";
    case "CRU057": case "CRU058": case "CRU059":
      if(ComboActive()) {
        $numLinks = NumChainLinks();
        $combatChainState[$CCS_BaseAttackDefenseMax] = $numLinks;
        $rv = "Cannot be defended by attacks with greater than " . $numLinks . " base attack";
      }
      return $rv;
    case "CRU081":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "";
    case "CRU082":
      $character = &GetPlayerCharacter($currentPlayer);
      ++$character[$combatChainState[$CCS_WeaponIndex] + 5];
      if($character[$combatChainState[$CCS_WeaponIndex] + 1] == 1) $character[$combatChainState[$CCS_WeaponIndex] + 1] = 2;
      return "";
    case "CRU083":
      if(RepriseActive()) {
        AddDecisionQueue("DECKCARDS", $currentPlayer, "0");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("ALLCARDTYPEORPASS", $currentPlayer, "AR", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to banish <0> with Unified Decree?");
        AddDecisionQueue("YESNO", $currentPlayer, "whether to banish the card", 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("PARAMDELIMTOARRAY", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,TCC", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("WRITELOG", $currentPlayer, "<0> was banished", 1);
      }
      return "";
    case "CRU084":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
      return "";
    case "CRU085": case "CRU086": case "CRU087":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      AddCurrentTurnEffect($cardID . "-2", ($mainPlayer == 1 ? 2 : 1));
      return "";
    case "CRU088": case "CRU089": case "CRU090":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      if(RepriseActive()) AddCurrentTurnEffectFromCombat($cardID . "-2", $mainPlayer);
      return "";
    case "CRU091": case "CRU092": case "CRU093":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      if(GetClassState($currentPlayer, $CS_AtksWWeapon) > 0)
      {
        AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
        $rv = "Gives your next weapon +" . EffectAttackModifier($cardID . "-2") . " because you've attacked with a weapon";
      }
      return $rv;
    case "CRU094": case "CRU095": case "CRU096":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      if(GetClassState($currentPlayer, $CS_AtksWWeapon) > 0) {
        AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
        $rv = "Gives your attack dominate because you've attacked with a weapon";
      }
      return $rv;
    case "CRU101":
      $abilityType = GetResolvedAbilityType($cardID);
      if($abilityType == "A") {
        $character = &GetPlayerCharacter($currentPlayer);
        $index = GetClassState($currentPlayer, $CS_PlayIndex);
        $character[$index + 2] = 1;
      }
      return "";
    case "CRU102":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU103":
      if($combatChainState[$CCS_NumBoosted] && !IsAllyAttackTarget()) {
        if ($combatChainState[$CCS_NumBoosted] > 1 && IsOverpowerActive()) $combatChainState[$CCS_RequiredEquipmentBlock] = 1;
        else $combatChainState[$CCS_RequiredEquipmentBlock] = $combatChainState[$CCS_NumBoosted];
        $rv .= "Requires you to block with " . $combatChainState[$CCS_NumBoosted] . " equipment if able";
      }
      return $rv;
    case "CRU105":
      if($from == "PLAY") {
        $items = &GetItems($currentPlayer);
        $index = GetClassState($currentPlayer, $CS_PlayIndex);
        if(ClassContains($items[$index], "MECHANOLOGIST", $currentPlayer) && $items[$index + 2] == 2 && $additionalCosts == "PAID") {
          $items[$index + 2] = 1;
          AddCurrentTurnEffect($cardID, $currentPlayer);
        } else {
          $items[$index + 1] = 1;
        }
      }
      return "";
    case "CRU115": case "CRU116": case "CRU117":
      if($cardID == "CRU115") $maxCost = 2;
      else if($cardID == "CRU116") $maxCost = 1;
      else if($cardID == "CRU117") $maxCost = 0;
      Opt($cardID, GetClassState($currentPlayer, $CS_NumBoosted));
      AddDecisionQueue("DECKCARDS", $currentPlayer, "0");
      AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
      AddDecisionQueue("ALLCARDSUBTYPEORPASS", $currentPlayer, "Item", 1);
      AddDecisionQueue("ALLCARDMAXCOSTORPASS", $currentPlayer, $maxCost, 1);
      AddDecisionQueue("FINDINDICES", $currentPlayer, "TOPDECK", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
      return "";
    case "CRU118":
      if(PlayerHasLessHealth(1)) {
        LoseHealth(1, 2);
        PutItemIntoPlayForPlayer("CRU197", 2);
        WriteLog("Player 2 lost a health and gained a copper from Kavdaen");
        if(PlayerHasLessHealth(1)) {
          GainHealth(1, 1);
        }
      } else if(PlayerHasLessHealth(2)) {
        LoseHealth(1, 1);
        PutItemIntoPlayForPlayer("CRU197", 1);
        WriteLog("Player 1 lost a health and gained a copper from Kavdaen");
        if(PlayerHasLessHealth(2)) {
          GainHealth(1, 2);
        }
      }
      return "";
    case "CRU121":
      if(!ArsenalEmpty($currentPlayer)) return "Your arsenal is not empty so you cannot load an arrow";
      LoadArrow($currentPlayer);
      return "";
    case "CRU122":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU124":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      Reload();
      return "";
    case "CRU125":
      SetClassState($currentPlayer, $CS_NextDamagePrevented, 1);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU126":
      if(!IsAllyAttacking()) {
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
      if(!IsAllyAttacking()) {
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
      return "";
    case "CRU141":
      AddCurrentTurnEffect($cardID . "-AA", $currentPlayer);
      AddCurrentTurnEffect($cardID . "-NAA", $currentPlayer);
      return "";
    case "CRU142":
      AddLayer("TRIGGER", $currentPlayer, $cardID);
      return "";
    case "CRU143":
      AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
      AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIBANISH", $currentPlayer, "GY,TT", 1);
      return "";
    case "CRU144":
      PlayAura("ARC112", $currentPlayer, 4);
      return "";
    case "CRU145": case "CRU146": case "CRU147":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU154": case "CRU155": case "CRU156":
      if($cardID == "CRU154") $count = 3;
      else if($cardID == "CRU155") $count = 2;
      else $count = 1;
      $deck = new Deck($currentPlayer);
      $numRunechants = 0;
      if($deck->Reveal($count)) {
        $cards = explode(",", $deck->Top(remove:true, amount:$count));
        for($i=0; $i<count($cards); ++$i) if(ClassContains($cards[$i], "RUNEBLADE", $currentPlayer) && CardType($cards[$i]) == "AA") ++$numRunechants;
        if($numRunechants > 0) PlayAura("ARC112", $currentPlayer, number:$numRunechants);
        AddDecisionQueue("CHOOSETOP", $currentPlayer, implode(",", $cards));
      }
      return "";
    case "CRU160":
      DealArcane(2, 0, "ABILITY", $cardID);
      return "";
    case "CRU162":
      SetClassState($currentPlayer, $CS_NextWizardNAAInstant, 1);
      if(GetClassState($currentPlayer, $CS_NumWizardNonAttack) >= 2) {
        DealArcane(3, 1, "PLAYCARD", $cardID, resolvedTarget: $target);
      }
      return "";
    case "CRU163":
      PlayerOpt($currentPlayer, 2);
      return "";
    case "CRU164":
      NegateLayer($target);
      return "";
    case "CRU165": case "CRU166": case "CRU167":
      if($cardID == "CRU165") $optAmount = 3;
      else if($cardID == "CRU166") $optAmount = 2;
      else $optAmount = 1;
      AddCurrentTurnEffect($cardID, $currentPlayer);
      PlayerOpt($currentPlayer, $optAmount);
      return "";
    case "CRU168": case "CRU169": case "CRU170":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      PlayerOpt($currentPlayer, 1);
      return "";
    case "CRU171": case "CRU172": case "CRU173":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU174": case "CRU175": case "CRU176":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD",$cardID, resolvedTarget: $target);
      return "";
    case "CRU181":
      $count = SearchCount(CombineSearches(SearchDiscardForCard(1, "CRU181"), SearchDiscardForCard(2, "CRU181")));
      for($i = 0; $i < $count; ++$i) Draw($currentPlayer);
      return "Drew " . $count . " card" . ($count > 1 ? "s" : "");
    case "CRU182":
      AddCurrentTurnEffect("CRU182", ($currentPlayer == 1 ? 2 : 1));
      return "";
    case "CRU183": case "CRU184": case "CRU185":
      if($from == "ARS") {
        GiveAttackGoAgain();
        $rv = "Gains go again";
      }
      return $rv;
    case "CRU186":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU188":
      Draw($currentPlayer);
      Draw($currentPlayer);
      return "";
    case "CRU189": case "CRU190": case "CRU191":
      $options = GetChainLinkCards($defPlayer, "AA");
      if($options == "") return "No defending attack action cards";
      AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, $options);
      AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $currentPlayer, PlayBlockModifier($cardID), 1);
      return "";
    case "CRU197":
      if($from == "PLAY") {
        Draw($currentPlayer);
        DestroyItemForPlayer($currentPlayer, GetClassState($currentPlayer, $CS_PlayIndex));
      }
      return "";
    default: return "";
  }
}

function CRUHitEffect($cardID)
{
  global $mainPlayer, $defPlayer, $combatChainState, $CS_ArcaneDamageTaken;
  switch($cardID) {
    case "CRU054": if(ComboActive()) PlayAura("CRU075", $mainPlayer); break;
    case "CRU060": case "CRU061": case "CRU062":
      if(ComboActive()) {
        $num = NumAttacksHit()+1;
        for($i = 0; $i < $num; ++$i) {
          Draw($mainPlayer);
          AddDecisionQueue("FINDINDICES", $mainPlayer, "HAND");
          AddDecisionQueue("CHOOSEHAND", $mainPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $mainPlayer, "-", 1);
          AddDecisionQueue("MULTIADDTOPDECK", $mainPlayer, "-", 1);
        }
      }
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
      if(HitsInRow() > 0) {
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
      if(IsHeroAttackTarget()) {
        AddCurrentTurnEffect("CRU123-DMG", $defPlayer);
        AddNextTurnEffect("CRU123-DMG", $defPlayer);
      }
      break;
    case "CRU129": case "CRU130": case "CRU131":
      if(!ArsenalEmpty($mainPlayer)) return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal";
      MZMoveCard($mainPlayer, "MYHAND", "MYARSENAL,HAND,DOWN", may:true);
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
      if(IsHeroAttackTarget() && GetClassState($defPlayer, $CS_ArcaneDamageTaken)) PummelHit();
      break;
    case "CRU151": case "CRU152": case "CRU153":
      PlayAura("ARC112", $mainPlayer);
      break;
    case "CRU180":
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose any number of options");
      AddDecisionQueue("MAYMULTICHOOSETEXT", $mainPlayer, "3-Quicken_token,Draw_card,Gain_life");
      AddDecisionQueue("MODAL", $mainPlayer, "COAXCOMMOTION", 1);
      AddDecisionQueue("SHOWMODES", $mainPlayer, $cardID, 1);
      break;
    case "CRU183": case "CRU184": case "CRU185":
      TopDeckToArsenal($defPlayer);
      TopDeckToArsenal($mainPlayer);
      break;
    default: break;
  }
}

function KayoStaticAbility()
{
  global $combatChainState, $CCS_LinkBaseAttack, $mainPlayer;
  $roll = GetDieRoll($mainPlayer);
  if($roll >= 5 && CanGainAttack()) $combatChainState[$CCS_LinkBaseAttack] *= 2;
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
