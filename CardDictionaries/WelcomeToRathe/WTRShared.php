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

  function WTRAbilityType($cardID, $index=-1, $from="")
  {
    switch($cardID)
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
      case "WTR170": 
        if($from == "PLAY") return "I";
        else return "A";
      case "WTR171": case "WTR172": return "A";
      default: return "";
    }
  }

  function WTRAbilityHasGoAgain($cardID)
  {
    switch($cardID)
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
    switch($cardID)
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
      case "WTR185": case "WTR186": case "WTR187": return 1;
      case "WTR200": case "WTR201": case "WTR202": return 1;
      case "WTR206": return 4;
      case "WTR207": return 3;
      case "WTR208": return 2;
      case "WTR209": return 3;
      case "WTR210": return 2;
      case "WTR211": return 1;
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
    global $mainPlayer, $CS_LastDynCost;
    $idArr = explode("-", $cardID);
    $cardID = $idArr[0];
    switch($cardID)
    {
      case "WTR007": return ClassContains($attackID, "BRUTE", $mainPlayer);
      case "WTR017": case "WTR018": case "WTR019": return ClassContains($attackID, "BRUTE", $mainPlayer);
      case "WTR032": case "WTR033": case "WTR034": return CardType($attackID) == "AA" && ClassContains($attackID, "BRUTE", $mainPlayer);
      case "WTR035": case "WTR036": case "WTR037": return ClassContains($attackID, "BRUTE", $mainPlayer);
      case "WTR038": case "WTR039": return CardType($attackID) == "AA" && CardCost($attackID) >= 3;
      case "WTR066": case "WTR067": case "WTR068": return true;
      case "WTR069": case "WTR070": case "WTR071": return CardType($attackID) == "AA" && ClassContains($attackID, "GUARDIAN", $mainPlayer);
      case "WTR081": return true;
      case "WTR116": return TypeContains($attackID, "W", $mainPlayer);
      case "WTR129": case "WTR130": case "WTR131": return TypeContains($attackID, "W", $mainPlayer);
      case "WTR141": case "WTR142": case "WTR143": return TypeContains($attackID, "W", $mainPlayer);
      case "WTR144": case "WTR145": case "WTR146": return TypeContains($attackID, "W", $mainPlayer);
      case "WTR147": case "WTR148": case "WTR149": return TypeContains($attackID, "W", $mainPlayer);
      case "WTR153": return CardType($attackID) == "AA" && (CardCost($attackID) >= 2 || GetClassState($mainPlayer, $CS_LastDynCost) >= 2);
      case "WTR154": return true;
      case "WTR159": return true;
      case "WTR161": return true;
      case "WTR162": return true;
      case "WTR171": return true;
      case "WTR185": case "WTR186": case "WTR187": return true;
      case "WTR197": case "WTR198": case "WTR199": return true;
      case "WTR200": case "WTR201": case "WTR202": return true;
      case "WTR206": case "WTR207": case "WTR208": return true;
      case "WTR209": case "WTR210": case "WTR211": return true;
      case "WTR218": case "WTR219": case "WTR220": return CardType($attackID) == "AA" && CardCost($attackID) <= 1;
      case "WTR221": case "WTR222": case "WTR223": return CardType($attackID) == "AA" && CardCost($attackID) >= 2;
      default: return false;
    }
  }

  function WTRPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $mainPlayer, $currentPlayer, $defPlayer, $CS_DamagePrevention;
    $rv = "";
    switch($cardID) {
      case "WTR054": case "WTR055": case "WTR056": if(SearchCount(SearchPitch($currentPlayer, minCost:3)) > 0) Draw($currentPlayer); return "";
      case "WTR004":
        $roll = GetDieRoll($currentPlayer);
        GainActionPoints(intval($roll/2), $currentPlayer);
        return "Rolled $roll and gained " . intval($roll/2) . " action points";
      case "WTR005":
        $roll = GetDieRoll($currentPlayer);
        GainResources($currentPlayer, intval($roll/2));
        return "Rolled $roll and gained " . intval($roll/2) . " resources";
      case "WTR006":
        Intimidate();
        return "";
      case "WTR007":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        if(ModifiedAttackValue($additionalCosts, $currentPlayer, "HAND", source:$cardID) >= 6) {
          AddCurrentTurnEffect($cardID."-GOAGAIN", $currentPlayer);
          Draw($currentPlayer);
          Draw($currentPlayer);
          ResolveGoAgain($cardID, $currentPlayer, $from);
          $rv = "Draws 2 cards and gains go again";
        }
        return $rv;
      case "WTR008":
        if(IsAllyAttacking()) {
          return "<span style='color:red;'>No damage is dealt because there is no attacking hero when allies attack.</span>";
        }
        else if(ModifiedAttackValue($additionalCosts, $currentPlayer, "HAND", source:$cardID) >= 6) {
          WriteLog(Cardlink($cardID, $cardID) . " deals 2 damage"); DamageTrigger($mainPlayer, 2, "DAMAGE", $cardID);
        }
        return "";
      case "WTR009":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECK");
        AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "SANDSKETCH");
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        return "";
      case "WTR010":
        $roll = GetDieRoll($currentPlayer);
        IncrementClassState($currentPlayer, $CS_DamagePrevention, $roll);
        return "Prevents the next $roll damage that will be dealt to you this turn";
      case "WTR011": case "WTR012": case "WTR013":
        if(ModifiedAttackValue($additionalCosts, $currentPlayer, "HAND", source:$cardID) >= 6) {
          GiveAttackGoAgain();
          $rv = "Discarded a 6 power card and gains go again.";
        }
        return $rv;
      case "WTR014": case "WTR015": case "WTR016":
        if(ModifiedAttackValue($additionalCosts, $currentPlayer, "HAND", source:$cardID) >= 6) Draw($currentPlayer);
        return "";
      case "WTR017": case "WTR018": case "WTR019":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        Intimidate();
        return "";
      case "WTR023": case "WTR024": case "WTR025":
        Intimidate();
        return "";
      case "WTR026": case "WTR027": case "WTR028":
        Intimidate();
        return "";
      case "WTR032": case "WTR033": case "WTR034":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        Intimidate();
        return "";
      case "WTR035": case "WTR036": case "WTR037":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "";
      //Guardian
      case "WTR038": case "WTR039":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "";
      case "WTR041":
        PlayAura("WTR075", $mainPlayer);
        return "";
      case "WTR042":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "";
      case "WTR047":
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDECK:type=AA;class=GUARDIAN");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        return "";
      //Ninja
      case "WTR082":
        Draw($currentPlayer);
        return "";
      case "WTR092": case "WTR093": case "WTR094":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      //Warrior
      case "WTR116":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "WTR118":
        GiveAttackGoAgain();
        if(RepriseActive()) Draw($currentPlayer);
        return "";
      case "WTR119": case "WTR122":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose_target_weapon");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDMZBUFF", $currentPlayer, $cardID, 1);
        return "";
      case "WTR120":
        $options = GetChainLinkCards($defPlayer, "", "E,C", exclCardSubTypes:"Evo");
        if(RepriseActive() && $options != "") {
          AddDecisionQueue("MAYCHOOSECOMBATCHAIN", $mainPlayer, $options);
          AddDecisionQueue("ADDHANDOWNER", $defPlayer, "-", 1);
          AddDecisionQueue("REMOVECOMBATCHAIN", $mainPlayer, "-", 1);
        }
        return "";
      case "WTR121":
        if(RepriseActive() && SearchDeck($currentPlayer, "AR") != "") {
          $ARs = SearchDeck($currentPlayer, "AR");
          AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, $ARs);
          AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,TCL", 1);
          AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
          AddDecisionQueue("WRITELOG", $currentPlayer, "<0> was banished.", 1);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        }
        return "";
      case "WTR126": case "WTR127": case "WTR128":
        if(IsWeaponAttack()) {
          DamageTrigger($mainPlayer, 1, "DAMAGE", $cardID);
          $rv = "Did 1 damage to the attacking hero";
        }
        return $rv;
      case "WTR129": case "WTR130": case "WTR131":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "";
      case "WTR135": case "WTR136": case "WTR137":
        if(RepriseActive()) { ApplyEffectToEachWeapon($cardID); $rv = "Gives weapons you control +1 for the rest of the turn"; }
        return $rv;
      case "WTR138": case "WTR139": case "WTR140":
        if(RepriseActive()) {
          Draw($currentPlayer);
          $hand = &GetHand($mainPlayer);
          if(count($hand) > 0) AddDecisionQueue("HANDTOPBOTTOM", $mainPlayer, "");
        }
        return "";
      case "WTR141": case "WTR142": case "WTR143":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "";
      case "WTR144": case "WTR145": case "WTR146":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "";
      case "WTR147": case "WTR148": case "WTR149":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "";
      case "WTR150":
        GainResources($currentPlayer, 1);
        return "";
      case "WTR151":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MULTIHAND");
        AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "HOPEMERCHANTHOOD", 1);
        return "";
      case "WTR152":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "";
      case "WTR153":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "";
      case "WTR154":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "";
      case "WTR159":
        PrependDecisionQueue("MODAL", $currentPlayer, "ESTRIKE", 1);
        PrependDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
        return "";
      case "WTR160":
        Draw($currentPlayer);
        Draw($currentPlayer);
        if($from == "ARS") { $hand = &GetHand($currentPlayer); GainHealth(count($hand), $currentPlayer); }
        return "";
      case "WTR161":
        if(count(GetDeck($currentPlayer)) == 0) {
          GiveAttackGoAgain();
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Gains go again and +4";
        }
        return $rv;
      case "WTR162":
        if($from == "PLAY") {
          $roll = GetDieRoll($currentPlayer);
          $rv = "Crazy Brew rolled " . $roll;
          if($roll <= 2) {
            LoseHealth(2, $currentPlayer);
            GainActionPoints(1, $currentPlayer);
            $rv .= " and lost you 2 life.";
          }
          else if($roll <= 4) {
            GainHealth(2, $currentPlayer);
            GainActionPoints(1, $currentPlayer);
          } else {
            $resources = &GetResources($currentPlayer);
            AddCurrentTurnEffect($cardID, $currentPlayer);
            GainResources($currentPlayer, 2);
            GainActionPoints(2, $currentPlayer);
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
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "REMEMBRANCE", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        return "";
      case "WTR170":
        if($from == "PLAY") GainResources($currentPlayer, 2);
        return "";
      case "WTR171":
        if($from == "PLAY") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "WTR172":
        if($from == "PLAY") GainActionPoints(2, $currentPlayer);
        return "";
      case "WTR173": GainHealth(3, $currentPlayer); return "";
      case "WTR174": GainHealth(2, $currentPlayer); return "";
      case "WTR175": GainHealth(1, $currentPlayer); return "";
      case "WTR182": case "WTR183": case "WTR184":
        PlayAura("WTR225", $currentPlayer);
        return "";
      case "WTR191": case "WTR192": case "WTR193":
        if(PlayerHasLessHealth($mainPlayer)) { GiveAttackGoAgain(); $rv = "Gains go again"; }
        return $rv;
      case "WTR194": case "WTR195": case "WTR196":
        BottomDeck($currentPlayer, true, shouldDraw:true);
        if($from == "ARS") { GiveAttackGoAgain(); $rv = "Gains go again"; }
        return $rv;
      case "WTR200": case "WTR201": case "WTR202":
        if(PlayerHasLessHealth($mainPlayer)) { AddCurrentTurnEffect($cardID, $mainPlayer); $rv = "Gains +1 attack"; }
        return $rv;
      case "WTR206": case "WTR207": case "WTR208":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "WTR209": case "WTR210": case "WTR211":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "WTR215": case "WTR216": case "WTR217":
        BottomDeck($currentPlayer, true, shouldDraw:true);
        return "";
      case "WTR218": case "WTR219": case "WTR220":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "";
      case "WTR221": case "WTR222": case "WTR223":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "";
      default: return "";
    }
  }

  function WTRHitEffect($cardID)
  {
    global $CS_HitsWDawnblade, $combatChainState, $CCS_WeaponIndex;
    global $mainPlayer, $defPlayer, $CCS_DamageDealt;
    switch($cardID)
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
        if(ComboActive()) {
          AddDecisionQueue("ADDHAND", $mainPlayer, "-");
        }
        break;
      case "WTR085":
        if(IsHeroAttackTarget() && ComboActive()) {
          LoseHealth($combatChainState[$CCS_DamageDealt], $defPlayer);
        }
        break;
      case "WTR110": case "WTR111": case "WTR112": if(ComboActive()) { Draw($mainPlayer); } break;
      case "WTR115":
        if(GetClassState($mainPlayer, $CS_HitsWDawnblade) == 1) {
          $mainCharacter = &GetPlayerCharacter($mainPlayer);
          $index = FindCharacterIndex($mainPlayer, $cardID);
          ++$mainCharacter[$index+3];
        }
        IncrementClassState($mainPlayer, $CS_HitsWDawnblade, 1);
      break;
      case "WTR167": case "WTR168": case "WTR169": Draw($mainPlayer); break;
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
    RevealCards($cards, $mainPlayer);
    GainHealth($lifegain, $mainPlayer);
    return "";
  }

  function KatsuHit($context="")
  {
    global $mainPlayer;
    $hand = &GetHand($mainPlayer);
    $char = &GetPlayerCharacter($mainPlayer);
    if($context == "") $context = "to_use_Katsu's_ability";
    if(count($hand) > 0)
    {
      AddDecisionQueue("YESNO", $mainPlayer, $context);
      AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYHAND:maxCost=0;minCost=0", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZDISCARD", $mainPlayer, "HAND,".$mainPlayer, 1);
      AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYDECK:comboOnly=true", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZADDZONE", $mainPlayer, "MYBANISH,DECK,TT", 1);
      AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-", 1);
      if($context == "to_use_Katsu's_ability")AddDecisionQueue("LOGPLAYCARDSTATS", $mainPlayer, $char[0].",HAND,KATSUDISCARD", 1);
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
    $deck = new Deck($mainPlayer);
    if(!ArsenalFull($mainPlayer) && !$deck->Empty()) {
      $type = CardType($deck->Top());
      if($deck->Reveal() && ($type == "A" || $type == "AA")) {
        AddArsenal($deck->Top(remove:true), $mainPlayer, "DECK", "DOWN");
      }
    }
  }


  function HasCrush($cardID)
  {
    switch($cardID) {
      case "WTR043": case "WTR044": case "WTR045": case "WTR057": case "WTR058": case "WTR059":
      case "WTR060": case "WTR061": case "WTR062": case "WTR063": case "WTR064": case "WTR065":
      case "WTR066": case "WTR067": case "WTR068": case "WTR050": case "WTR049": case "WTR048":
      case "CRU026": case "CRU027": case "CRU032": case "CRU033": case "CRU034": case "CRU035":
      case "CRU036": case "CRU037": case "TCC039": case "TCC044":
      case "DTD203":
        return true;
      default:
        return false;
    }
  }

  function Mangle()
  {
    global $mainPlayer;
    AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRCHAR:type=E;hasNegCounters=true");
    AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
    AddDecisionQueue("MZDESTROY", $mainPlayer, "-", 1);
  }

  function ProcessCrushEffect($cardID)
  {
    global $mainPlayer, $defPlayer, $defCharacter, $CombatChain, $combatChainState, $CCS_DamageDealt, $layers;
    if(!IsHeroAttackTarget()) return;
    if(CardType($CombatChain->AttackCard()->ID()) == "AA" && SearchCurrentTurnEffects("OUT108", $mainPlayer, count($layers) <= LayerPieces())) return true;
    switch($cardID) {
      case "WTR043":
        DiscardRandom($defPlayer, $cardID, $mainPlayer);
        DiscardRandom($defPlayer, $cardID, $mainPlayer);
        break;
      case "WTR044":
        AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "WTR045":
        AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "WTR048": case "WTR049": case "WTR050":
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to put on the bottom of the deck", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZADDZONE", $mainPlayer, "THEIRBOTDECK", 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
        break;
      case "WTR057": case "WTR058": case "WTR059":
        AddDecisionQueue("FINDINDICES", $defPlayer, "EQUIP");
        AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
        AddDecisionQueue("MODDEFCOUNTER", $defPlayer, "-1", 1);
        break;
      case "WTR060": case "WTR061": case "WTR062":
        AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "WTR063": case "WTR064": case "WTR065":
        if(IsHeroAttackTarget()) {
          $char = &GetPlayerCharacter($defPlayer);
          $char[1] = 3;
          AddNextTurnEffect($cardID, $defPlayer);
        }
        break;
      case "WTR066": case "WTR067": case "WTR068":
        AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "CRU026":
        Mangle();
        break;
      case "CRU027":
        AddDecisionQueue("FINDINDICES", $defPlayer, "DECKTOPXINDICES,5");
        AddDecisionQueue("SETDQVAR", $mainPlayer, "0");
        AddDecisionQueue("COUNTPARAM", $defPlayer, "<-", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card(s) to banish", 1);
        AddDecisionQueue("MULTICHOOSETHEIRDECK", $mainPlayer, "<-", 1, 1);
        AddDecisionQueue("VALIDATEALLSAMENAME", $defPlayer, "DECK", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $defPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $defPlayer, "DECK,-", 1);
        AddDecisionQueue("SPECIFICCARD", $mainPlayer, "RIGHTEOUSCLEANSING", 1);
        break;
      case "CRU032": case "CRU033": case "CRU034":
        AddNextTurnEffect("CRU032", $defPlayer);
        break;
      case "CRU035": case "CRU036": case "CRU037":
        AddNextTurnEffect("CRU035", $defPlayer);
        break;
      case "DTD203":
        $damageDone = $combatChainState[$CCS_DamageDealt];
        AddNextTurnEffect("DTD203," . $damageDone, $defPlayer);
        break;
      case "TCC039": case "TCC044":
        MZMoveCard($defPlayer, "MYHAND", "MYTOPDECK", silent:true);
        break;
      default: return;
    }
  }
