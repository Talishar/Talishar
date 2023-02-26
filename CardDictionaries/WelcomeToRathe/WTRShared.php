<?php

function WTRAbilityCost($cardID)
{
  switch($cardID)
  {
    case "WTR003": return 2;
    case "WTR038": case "WTR039": return 2;
    case "WTR040": return 3;
    case "WTR041": return 1;
    case "WTR042": return 1;
    case "WTR078": return 1;
    case "WTR115": return 1;
    case "WTR116": return 1;
    default: return 0;
  }
}

  function WTRAbilityType($cardID, $index=-1)
  {
    switch ($cardID)
    {
      case "WTR003": return "AA";
      case "WTR004": return "A";
      case "WTR005": return "I";
      case "WTR038": case "WTR039": return "A";
      case "WTR040": return "AA";
      case "WTR041": case "WTR042": return "A";
      case "WTR078": return "AA";
      case "WTR080": return "AR";
      case "WTR115": return "AA";
      case "WTR116": return "A";
      case "WTR150": return "I";
      case "WTR151": return "I";
      case "WTR152": return "A";
      case "WTR154": return "AR";
      case "WTR153": return "A";
      case "WTR162": return "A";
      case "WTR170": return "I";
      case "WTR171": case "WTR172": return "A";
      default: return "";
    }
  }

  function WTRAbilityHasGoAgain($cardID)
  {
    switch ($cardID)
    {
      case "WTR038": case "WTR039": return true;
      case "WTR041": return true;
      case "WTR116": return true;
      case "WTR152": return true;
      case "WTR153": return true;
      case "WTR171": return true;
      default: return false;
    }
  }

  function WTREffectAttackModifier($cardID)
  {
    $idArr = explode("-", $cardID);
    $cardID = $idArr[0];
    switch ($cardID)
    {
      case "WTR007": return 2;
      case "WTR017": return NumNonEquipmentDefended() < 2 ? 4 : 0;
      case "WTR018": return NumNonEquipmentDefended() < 2 ? 3 : 0;
      case "WTR019": return NumNonEquipmentDefended() < 2 ? 2 : 0;
      case "WTR032": return 3;
      case "WTR033": return 2;
      case "WTR034": return 1;
      case "WTR035": return 5;
      case "WTR036": return 4;
      case "WTR037": return 3;
      case "WTR066": case "WTR067": case "WTR068": return -2;
      case "WTR069": return 3;
      case "WTR070": return 2;
      case "WTR071": return 1;
      case "WTR081": return (count($idArr) > 1 ? $idArr[1] : 0);
      case "WTR116": return 1;
      case "WTR129": return 3;
      case "WTR130": return 2;
      case "WTR131": return 1;
      case "WTR141": return 3;
      case "WTR142": return 2;
      case "WTR143": return 1;
      case "WTR144": return 3;
      case "WTR145": return 2;
      case "WTR146": return 1;
      case "WTR147": return 3;
      case "WTR148": return 2;
      case "WTR149": return 1;
      case "WTR153": return 2;
      case "WTR159": return 2;
      case "WTR161": return 4;
      case "WTR162": return 2;
      case "WTR171": return 2;
      case "WTR185": return 1;
      case "WTR200": case "WTR201": case "WTR202": return 1;
      case "WTR218": return 3;
      case "WTR219": return 2;
      case "WTR220": return 1;
      case "WTR221": return 6;
      case "WTR222": return 5;
      case "WTR223": return 4;
      default: return 0;
    }
  }

  function WTRCombatEffectActive($cardID, $attackID)
  {
    global $mainPlayer;
    $idArr = explode("-", $cardID);
    $cardID = $idArr[0];
    switch ($cardID)
    {
      //Brute
      case "WTR007": return ClassContains($attackID, "BRUTE", $mainPlayer);
      case "WTR017": case "WTR018": case "WTR019": return ClassContains($attackID, "BRUTE", $mainPlayer);
      case "WTR032": case "WTR033": case "WTR034": return CardType($attackID) == "AA" && ClassContains($attackID, "BRUTE", $mainPlayer);
      case "WTR035": case "WTR036": case "WTR037": return ClassContains($attackID, "BRUTE", $mainPlayer);
      //Guardian
      case "WTR038": case "WTR039": return CardType($attackID) == "AA" && CardCost($attackID) >= 3;
      case "WTR066": case "WTR067": case "WTR068": return true;
      case "WTR069": case "WTR070": case "WTR071": return CardType($attackID) == "AA" && ClassContains($attackID, "GUARDIAN", $mainPlayer);
      //Ninja
      case "WTR081": return true;
      //Warrior
      case "WTR116": return CardType($attackID) == "W";
      case "WTR129": case "WTR130": case "WTR131": return CardType($attackID) == "W";
      case "WTR141": case "WTR142": case "WTR143": return CardType($attackID) == "W";
      case "WTR144": case "WTR145": case "WTR146": return CardType($attackID) == "W";
      case "WTR147": case "WTR148": case "WTR149": return CardType($attackID) == "W";
      //Generics
      case "WTR153": return CardType($attackID) == "AA" && CardCost($attackID) >= 2;
      case "WTR159": return true;
      case "WTR161": return true;
      case "WTR162": return true;
      case "WTR171": return true;
      case "WTR185": return true;
      case "WTR197": return true;
      case "WTR200": case "WTR201": case "WTR202": return true;
      case "WTR218": case "WTR219": case "WTR220": return CardType($attackID) == "AA" && CardCost($attackID) <= 1;
      case "WTR221": case "WTR222": case "WTR223": return CardType($attackID) == "AA" && CardCost($attackID) >= 2;
      default: return false;
    }
  }

  function WTRHasGoAgain($cardID)
  {
    switch ($cardID)
    {
      //Brute
      case "WTR017": case "WTR018": case "WTR019": return true;
      case "WTR032": case "WTR033": case "WTR034": return true;
      case "WTR035": case "WTR036": case "WTR037": return true;
      //Guardian
      case "WTR046": return true;
      case "WTR054": case "WTR055": case "WTR056": return true;
      case "WTR069": case "WTR070": case "WTR071": return true;
      case "WTR072": case "WTR073": case "WTR074": return true;
      //Ninja
      case "WTR098": case "WTR099": case "WTR100": return true;
      case "WTR101": case "WTR102": case "WTR103": return true;
      case "WTR107": case "WTR108": case "WTR109": return true;
      //Warrior
      case "WTR119": case "WTR122": return true;
      case "WTR129": case "WTR130": case "WTR131": return true;
      case "WTR141": case "WTR142": case "WTR143": return true;
      case "WTR144": case "WTR145": case "WTR146": return true;
      case "WTR147": case "WTR148": case "WTR149": return true;
      //Generics
      case "WTR218": case "WTR219": case "WTR220": return true;
      case "WTR223": case "WTR222": case "WTR221": return true;
      default: return false;
    }
  }

  function WTRPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $mainPlayer, $combatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $currentPlayer, $defPlayer, $actionPoints;
    global $CS_DamagePrevention;
    $rv = "";
    switch ($cardID)
    {
      case "WTR054": case "WTR055": case "WTR056":
        if(CountPitch(GetPitch($currentPlayer), 3) >= 1) MyDrawCard();
        return CountPitch(GetPitch($currentPlayer), 3) . " cards in pitch.";
      case "WTR004":
        $roll = GetDieRoll($currentPlayer);
        $actionPoints += intval($roll/2);
        return "Rolled $roll and gains " . intval($roll/2) . " action points.";
      case "WTR005":
        $resources = &GetResources($currentPlayer);
        $roll = GetDieRoll($currentPlayer);
        $resources[0] += intval($roll/2);
        return "Rolled $roll and gains " . intval($roll/2) . " resources.";
      case "WTR006":
        Intimidate();
        return "Intimidates.";
      case "WTR007":
          $rv .= "Gives your Brute attacks +2 this turn";
        if(AttackValue($additionalCosts) >= 6)
        {
          MyDrawCard();
          MyDrawCard();
          if(!CurrentEffectPreventsGoAgain()) ++$actionPoints;
          $rv .= ", draws 2 cards and gains go again";
        }
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return $rv . ".";
      case "WTR008":
        $damaged = false;
        if(IsAllyAttacking())
        {
          return "<span style='color:red;'>No damage is dealt because there is no attacking hero when allies attack.</span>";
        }
        else if(AttackValue($additionalCosts) >= 6) { $damaged = true; DamageTrigger($mainPlayer, 2, "DAMAGE", $cardID); }
        return "Discarded a random card from your hand" . ($damaged ? " and does 2 damage." : ".");
      case "WTR009":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECK");
        AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("SANDSKETCH", $currentPlayer, "-");
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        return "";
      case "WTR010":
        $roll = GetDieRoll($currentPlayer);
        IncrementClassState($currentPlayer, $CS_DamagePrevention, $roll);
        return "Prevents the next $roll damage that will be dealt to you this turn.";
      case "WTR011": case "WTR012": case "WTR013":
        if(AttackValue($additionalCosts) >= 6)
        {
          $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
          $rv .= "Discarded a 6 power card and gains go again.";
        }
        return $rv;
      case "WTR014": case "WTR015": case "WTR016":
        if(AttackValue($additionalCosts) >= 6)
        {
          MyDrawCard();
        }
        return "Discarded a random card from your hand.";
      case "WTR017": case "WTR018": case "WTR019":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        Intimidate();
        return "Intimidates and gives the next Brute attack this turn +" . EffectAttackModifier($cardID) . ".";
      case "WTR023": case "WTR024": case "WTR025":
        Intimidate();
        return "Intimidates.";
      case "WTR026": case "WTR027": case "WTR028":
        Intimidate();
        return "Intimidates.";
      case "WTR032": case "WTR033": case "WTR034":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        Intimidate();
        return "Intimidates.";
      case "WTR035": case "WTR036": case "WTR037":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Discarded a random card from your hand and gives the next Brute attack this turn +" . EffectAttackModifier($cardID) . ".";
      //Guardian
      case "WTR038": case "WTR039":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives your action cards with cost 3 or greater Dominate.";
      case "WTR041":
        PlayMyAura("WTR075");
        return "Creates a Seismic Surge token.";
      case "WTR042":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives you +1 Intellect until end of turn.";
      case "WTR047":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKCLASSAA,GUARDIAN");
        AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        return "Lets you to search for a Guardian attack card.";
      //Ninja
      case "WTR078":
        if(CountPitch(GetPitch($currentPlayer), 0, 0)) $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        return "";
      case "WTR082":
        if(CardName($combatChain[0]) == "Bonds of Ancestry") WriteLog("Your ancestors reward you for your loyalty.");
        MyDrawCard();
        return "";
      case "WTR092": case "WTR093": case "WTR094":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Gives the next blocking Combo card +2 this turn.";
      //Warrior
      case "WTR116":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Gives your next weapon attack +1 this turn.";
      case "WTR118":
        $s1 = "";
        $s2 = "";
        if(CardType($combatChain[0]) == "W")
        {
          $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
          $s1 = "Gives your weapon attack go again";
        }
        if(RepriseActive())
        {
          MyDrawCard();
          $s2 = " draws a card";
        }
        return $s1 . ($s1 != "" && $s2 != "" ? " and" : "") . $s2 . ".";
      case "WTR119": case "WTR122":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose_target_weapon");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDMZBUFF", $currentPlayer, $cardID, 1);
        return "";
      case "WTR120":
        if(RepriseActive())
        {
          $options = GetChainLinkCards(($mainPlayer == 1 ? 2 : 1), "", "E,C");
          AddDecisionQueue("MAYCHOOSECOMBATCHAIN", $mainPlayer, $options);
          AddDecisionQueue("REMOVECOMBATCHAIN", $mainPlayer, "-", 1);
          AddDecisionQueue("ADDHAND", $defPlayer, "-", 1);
        }
        return "";
      case "WTR121":
        if(RepriseActive() && SearchDeck($currentPlayer, "AR") != "")
        {
          $ARs = SearchDeck($currentPlayer, "AR");
          AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, $ARs);
          AddDecisionQueue("BANISH", $currentPlayer, "TCL", 1);
          AddDecisionQueue("SHOWBANISHEDCARD", $currentPlayer, "-", 1);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        }
        return "";
      case "WTR123": case "WTR124": case "WTR125":
        if(CardType($combatChain[0]) != "W") return "Does nothing, because this is not a weapon attack.";
        return "Gives your weapon attack +" . AttackModifier($cardID) . ".";
      case "WTR126": case "WTR127": case "WTR128":
        if(CardType($combatChain[0]) == "W" || isAuraWeapon($combatChain[0], $mainPlayer, $combatChain[2]))
        {
          DamageTrigger($mainPlayer, 1, "DAMAGE", $cardID);
          $rv .= "DID";
        } else { $rv .= "Did NOT"; }
        $rv .= " deal 1 damage to the attacking hero.";
        return $rv;
      case "WTR129": case "WTR130": case "WTR131":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives your next weapon attack +" . EffectAttackModifier($cardID) . " and if it hits, it gains go again";
      case "WTR132": case "WTR133": case "WTR134":
        if(CardType($combatChain[0]) != "W") return "Does nothing, because this is not a weapon attack.";
        return "Gives your weapon attack +" . AttackModifier($cardID) . ".";
      case "WTR135": case "WTR136": case "WTR137":
        $log = "Gives your weapon attack +" . AttackModifier($cardID);
        if(RepriseActive()) { ApplyEffectToEachWeapon($cardID); $log .= " and gives weapons you control +1 for the rest of the turn"; }
        return $log . ".";
      case "WTR138": case "WTR139": case "WTR140":
        if(RepriseActive())
        {
          MyDrawCard();
          $hand = &GetHand($mainPlayer);
          if (count($hand) > 0) AddDecisionQueue("HANDTOPBOTTOM", $mainPlayer, "");
        }
        return "Gives your weapon attack +" . AttackModifier($cardID) . ".";
      case "WTR141": case "WTR142": case "WTR143":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives your next weapon attack +" . EffectAttackModifier($cardID) . ".";
      case "WTR144": case "WTR145": case "WTR146":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives your next weapon attack +" . EffectAttackModifier($cardID) . " and go again.";
      case "WTR147": case "WTR148": case "WTR149":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives your next weapon attack +" . EffectAttackModifier($cardID) . " and a hit effect.";
      case "WTR150":
        $resources = &GetResources($currentPlayer);
        $resources[0] += 1;
        return "Gain 1 resource.";
      case "WTR151":
        $indices = GetMyHandIndices();
        if($indices == "") return "";
        AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, count(GetHand($currentPlayer)) . "-" . $indices);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("HOPEMERCHANTHOOD", $currentPlayer, "-", 1);
        return "";
      case "WTR152":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Reduces the resource cost of your next attack action card by 2.";
      case "WTR154":
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        return "Gives your current attack go again.";
      case "WTR159":
        PrependDecisionQueue("ESTRIKE", $currentPlayer, "-", 1);
        PrependDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
        return "";
      case "WTR160":
        MyDrawCard();
        MyDrawCard();
        $hand = GetHand($currentPlayer); //Get hand size after draw for correct health gain
        if($from == "ARS") GainHealth(count($hand), $currentPlayer);
        return "";
      case "WTR161":
        if(count(GetDeck($currentPlayer)) == 0) {
          $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Gains go again and +4.";
        }
        return $rv;
      case "WTR162":
        if($from == "PLAY")
        {
          $roll = GetDieRoll($currentPlayer);
          $rv = "Crazy Brew rolled " . $roll;
          if($roll <= 2)
          {
            LoseHealth(2, $currentPlayer);
            $actionPoints += 1;
            $rv .= " and lost you 2 health.";
          }
          else if($roll <= 4)
          {
            GainHealth(2, $currentPlayer);
            $actionPoints += 1;
          }
          else if($roll <= 6)
          {
            $resources = &GetResources($currentPlayer);
            AddCurrentTurnEffect($cardID, $currentPlayer);
            $resources[0] += 2;
            $actionPoints += 2;
            $rv .= " and gained 2 action points, resources, and power.";
          }
        }
        return $rv;
      case "WTR163":
        $actions = SearchDiscard($currentPlayer, "A");
        $attackActions = SearchDiscard($currentPlayer, "AA");
        if($actions == "") $actions = $attackActions;
        else if($attackActions != "") $actions = $actions . "," . $attackActions;
        if($actions == "") return "";
        AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "3-" . $actions);
        AddDecisionQueue("REMEMBRANCE", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        return "";
      case "WTR170":
        if($from == "PLAY")
        {
          $resources = &GetResources($currentPlayer);
          $resources[0] += 2;
        }
        return "";
      case "WTR171":
        if($from == "PLAY")
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "WTR172":
        if($from == "PLAY")
        {
          $actionPoints += 2;
        }
        return "";
      case "WTR173": GainHealth(3, $currentPlayer); return "";
      case "WTR174": GainHealth(2, $currentPlayer); return "";
      case "WTR175": GainHealth(1, $currentPlayer); return "";
      case "WTR182": case "WTR183": case "WTR184":
        PlayMyAura("WTR225");
        return "Creates a Quicken token.";
      case "WTR191": case "WTR192": case "WTR193":
        if(IHaveLessHealth()) { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $rv = "Gains go again."; }
        return $rv;
      case "WTR194": case "WTR195": case "WTR196":
        MayBottomDeckDraw();
        if($from == "ARS") { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $rv = "Gains go again."; }
        return $rv;
      case "WTR200": case "WTR201": case "WTR202":
        if(IHaveLessHealth()) { AddCurrentTurnEffect($cardID, $mainPlayer); $rv = "Gains +1."; }
        return $rv;
      case "WTR215": case "WTR216": case "WTR217":
        MayBottomDeckDraw();
        return "";
      case "WTR218": case "WTR219": case "WTR220":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives the next attack action card with cost 1 or less this turn +" . EffectAttackModifier($cardID) . ".";
      case "WTR221": case "WTR222": case "WTR223"://Sloggism
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives the next attack action card with cost greater than 2 this turn +" . EffectAttackModifier($cardID) . ".";
      case "WTR153":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives your next attack action card with cost 2 or greater +" . EffectAttackModifier($cardID) . ".";
      default: return "";
    }
  }

  function WTRHitEffect($cardID)
  {
    global $CS_HitsWDawnblade, $combatChainState, $CCS_WeaponIndex;
    global $mainPlayer, $defPlayer, $CCS_DamageDealt, $combatChain;
    $attackID = $combatChain[0];
    switch ($cardID)
    {
      case "WTR083":
        if(ComboActive())
        {
          AddDecisionQueue("FINDINDICES", $mainPlayer, "WTR083");
          AddDecisionQueue("MULTICHOOSEDECK", $mainPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEDECK", $mainPlayer, "-", 1);
          AddDecisionQueue("MULTIADDHAND", $mainPlayer, "-", 1);
          AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-");
        }
        break;
      case "WTR084":
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, $cardID);
        if(ComboActive()){
          AddDecisionQueue("ADDMAINHAND", $mainPlayer, "-"); //Only back to hand if combo is active
        }
        break;
      case "WTR085":
        if(IsHeroAttackTarget() && ComboActive()){
          LoseHealth($combatChainState[$CCS_DamageDealt], $defPlayer);
        }
        break;
      case "WTR110": case "WTR111": case "WTR112": if(ComboActive()) { WriteLog(CardLink($cardID,$cardID) . " draw a card."); MainDrawCard(); } break;
      case "WTR115":
        if(GetClassState($mainPlayer, $CS_HitsWDawnblade) == 1 && $CCS_WeaponIndex < count($combatChainState)) {
          $mainCharacter = &GetPlayerCharacter($mainPlayer);
          $index = FindCharacterIndex($mainPlayer, $cardID);
          ++$mainCharacter[$index+3];
        }
        IncrementClassState($mainPlayer, $CS_HitsWDawnblade, 1);
      break;
      case "WTR167": case "WTR168": case "WTR169": MainDrawCard(); break;
      case "WTR206": case "WTR207": case "WTR208": if(IsHeroAttackTarget() && CardType($attackID) == "AA") PummelHit(); break;
      case "WTR209": case "WTR210": case "WTR211": if(CardType($attackID) == "AA") GiveAttackGoAgain(); break;
      default: break;
    }
  }

  function BlessingOfDeliveranceDestroy($amount)
  {
    global $mainPlayer;
    if(!CanRevealCards($mainPlayer)) return "Blessing of Deliverance cannot reveal cards.";
    $deck = GetDeck($mainPlayer);
    $lifegain = 0;
    $cards = "";
    for($i=0; $i<$amount; ++$i)
    {
      if(count($deck) > $i)
      {
        $cards .= $deck[$i] . ($i < 2 ? "," : "");
        if(CardCost($deck[$i]) >= 3) ++$lifegain;
      }
    }
    RevealCards($cards, $mainPlayer);//CanReveal called
    GainHealth($lifegain, $mainPlayer);
    return "";
  }

  function KatsuHit()
  {
    global $mainPlayer;
    $hand = &GetHand($mainPlayer);
    //If hand is empty skip the popup
    if(count($hand) > 0)
    {
      AddDecisionQueue("YESNO", $mainPlayer, "to_use_Katsu's_ability");
      AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
      AddDecisionQueue("FINDINDICES", $mainPlayer, "WTR076-1", 1);
      AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
      AddDecisionQueue("DISCARDMYHAND", $mainPlayer, "-", 1);
      AddDecisionQueue("FINDINDICES", $mainPlayer, "WTR076-2", 1);
      AddDecisionQueue("MAYCHOOSEDECK", $mainPlayer, "<-", 1);
      AddDecisionQueue("BANISH", $mainPlayer, "TT", 1);
      AddDecisionQueue("SHOWBANISHEDCARD", $mainPlayer, "-", 1);
      AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-", 1);
    }
  }

  function LordOfWindIndices($player)
  {
    $array = [];
    $indices = SearchDiscardForCard($player, "WTR107", "WTR108", "WTR109");
    if($indices != "") array_push($array, $indices);
    $indices = SearchDiscardForCard($player, "WTR110", "WTR111", "WTR112");
    if($indices != "") array_push($array, $indices);
    $indices = SearchDiscardForCard($player, "WTR083");
    if($indices != "") array_push($array, $indices);
    return implode(",", $array);
  }

  function NaturesPathPilgrimageHit()
  {
    global $mainPlayer;
    $deck = &GetDeck($mainPlayer);
    if(!ArsenalFull($mainPlayer) && count($deck) > 0)
    {
      $type = CardType($deck[0]);
      if(RevealCards($deck[0], $mainPlayer) && ($type == "A" || $type == "AA"))
      {
        AddArsenal($deck[0], $mainPlayer, "DECK", "DOWN");
        array_shift($deck);
      }
    }
  }
?>
