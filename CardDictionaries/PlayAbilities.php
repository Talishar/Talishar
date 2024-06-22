<?php

function AKOPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer;
  switch($cardID) {
    case "AKO004":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    default: return "";
  }
} 

function ASBPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $CS_NumCharged, $CombatChain;
  switch($cardID) {
    case "ASB025":
      if(GetClassState($currentPlayer, $CS_NumCharged) > 0) $CombatChain->Card(0)->ModifyPower(-2);
      return "";
    default: return "";
  }
} 

function AAZPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer;
  switch($cardID) {
    case "AAZ004": AddCurrentTurnEffect($cardID, $currentPlayer); return "";
    case "AAZ024":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $arsenal = &GetArsenal($currentPlayer);
      for($i=0; $i<count($arsenal); $i+=ArsenalPieces()) {
        if($arsenal[$i+1] == "DOWN") {
          AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_turn_your_arsenal_face_up");
          AddDecisionQueue("NOPASS", $currentPlayer, "-");
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, $i, 1);
          AddDecisionQueue("TURNARSENALFACEUP", $currentPlayer, $i, 1);
          AddDecisionQueue("ADDAIMCOUNTER", $currentPlayer, $i, 1);
        }
      }
      return "";
    default: return "";
  }
}

function MSTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $CS_NumBluePlayed, $CS_Transcended, $mainPlayer, $CS_DamagePrevention, $CS_PlayIndex;
  global $combatChain, $defPlayer, $CombatChain, $chainLinks, $combatChainState, $CCS_LinkBaseAttack, $CS_NumAttacks;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $hand = &GetHand($currentPlayer);
  switch($cardID) {
    case "MST000":
      if(GetClassState($otherPlayer, $CS_NumAttacks) <= 1)  AddCurrentTurnEffect($cardID, $otherPlayer);
      return "";
    case "MST001": case "MST002":
      AddDecisionQueue("DECKCARDS", $otherPlayer, "0");
      AddDecisionQueue("SETDQVAR", $otherPlayer, "0");
      AddDecisionQueue("ALLCARDPITCHORPASS", $currentPlayer, "3", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to banish <0> with Nuu?");
      AddDecisionQueue("YESNO", $currentPlayer, "whether to banish a card with Nuu", 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("PARAMDELIMTOARRAY", $currentPlayer, "0", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $otherPlayer, "0", 1);
      AddDecisionQueue("MULTIBANISH", $otherPlayer, "DECK,-,".$cardID, 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{0}");
      AddDecisionQueue("NONECARDPITCHORPASS", $currentPlayer, "3");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Nuu shows the top of your deck is <0>");
      AddDecisionQueue("OK", $currentPlayer, "whether to banish a card with Nuu", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "-");
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "MST004": 
      AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
      AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose a card to banish", 1);
      AddDecisionQueue("CHOOSEHAND", $otherPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
      AddDecisionQueue("BANISHCARD", $otherPlayer, "HAND,-,".$cardID, 1);  
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
      if(SearchCardList($additionalCosts, $currentPlayer, subtype:"Chi") != "") $mod = "TCCGorgonsGaze";
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
      if(IsHeroAttackTarget())
      {
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
      if($additionalCosts != "-"){
        $modes = explode(",", $additionalCosts);
        for($i=0; $i<count($modes); ++$i)
        {
          switch($modes[$i])
          {
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
            case "Transcend": Transcend($currentPlayer, "MST410", $from); break;
            default: break;
          }
        }
      }
      return "";
    case "MST011": case "MST012": case "MST013":
      if(GetClassState($currentPlayer, $CS_Transcended) <= 0) AddCurrentTurnEffect($cardID."-1", $currentPlayer);
      else AddCurrentTurnEffect($cardID."-2", $currentPlayer);
      return "";  
    case "MST014": case "MST015": case "MST016":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if(SearchPitchForColor($currentPlayer, 3) > 0) AddPlayerHand("MST024", $currentPlayer, $cardID); //Slither
      return "";
    case "MST017": case "MST018": case "MST019":
      $amount = 4;
      if($cardID == "MST018") $amount = 3;
      elseif ($cardID == "MST019") $amount = 2;
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Here's the card that on top of your deck.", 1);
      AddDecisionQueue("FINDINDICES", $otherPlayer, "DECKTOPXINDICES," . $amount);
      AddDecisionQueue("DECKCARDS", $currentPlayer, "<-", 1);
      AddDecisionQueue("LOOKTOPDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("CHOOSETHEIRDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDCARDTOCHAINASDEFENDINGCARD", $otherPlayer, "DECK", 1);
      AddDecisionQueue("ALLCARDPITCHORPASS", $currentPlayer, "3", 1);
      AddDecisionQueue("PUTCOMBATCHAINDEFENSE0", $otherPlayer, "-", 1);
      AddDecisionQueue("PUTINANYORDER", $currentPlayer, $amount-1);
      AddCurrentTurnEffect($cardID."-BUFF", $currentPlayer);
      return "";
    case "MST020": case "MST021": case "MST022":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if(SearchPitchForColor($currentPlayer, 3) > 0) AddPlayerHand("MST023", $currentPlayer, $cardID); //Fang Strike
      return "";  
    case "MST023": case "MST024":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";  
    case "MST025": case "MST026": 
      PlayAura("MON104", $currentPlayer, 1, numAttackCounters:1);
      return "";
    case "MST027":
      AddCurrentTurnEffect($cardID, $currentPlayer);
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
      if($additionalCosts != "-"){
        $modes = explode(",", $additionalCosts);
        for($i=0; $i<count($modes); ++$i)
        {
          switch($modes[$i])
          {
            case "Create_2_Spectral_Shield": PlayAura("MON104", $currentPlayer, 2); break;
            case "Put_a_+1_counter_on_each_aura_with_ward_you_control": 
              AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS:hasWard=true", 1);
              AddDecisionQueue("ADDALLATTACKCOUNTERS", $currentPlayer, "1", 1);
              break;
            case "Transcend": Transcend($currentPlayer, "MST432", $from); break;
            default: break;
          }
        }
      }
      return "";
    case "MST034": case "MST035": case "MST036":
      if(GetClassState($currentPlayer, $CS_Transcended) <= 0) AddCurrentTurnEffect($cardID."-1", $currentPlayer);
      else AddCurrentTurnEffect($cardID."-2", $currentPlayer);
      return "";
    case "MST046": case "MST047": 
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
      if(CanRevealCards($currentPlayer)) {
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
      if(SearchCardList($additionalCosts, $currentPlayer, subtype:"Chi") != "") AddPlayerHand("DYN065", $currentPlayer, "NA", 2);
      return "";
    case "MST053":
      if($additionalCosts != "-"){
        $modes = explode(",", $additionalCosts);
        for($i=0; $i<count($modes); ++$i)
        {
          switch($modes[$i])
          {
            case "Create_2_Crouching_Tigers": AddPlayerHand("DYN065", $currentPlayer, "NA", 2); break;
            case "Crouching_Tigers_Get_+1_this_turn": AddCurrentTurnEffect($cardID, $currentPlayer); break;
            case "Transcend": Transcend($currentPlayer, "MST453", $from); break;
            default: break;
          }
        }
      }
      return "";
    case "MST054": case "MST055": case "MST056":
      if(GetClassState($currentPlayer, $CS_Transcended) <= 0) AddCurrentTurnEffect($cardID."-1", $currentPlayer);
      else AddCurrentTurnEffect($cardID."-2", $currentPlayer);
      return "";
    case "MST057": case "MST058": case "MST059":
    case "MST060": case "MST061": case "MST062":
      if(SearchPitchForColor($currentPlayer, 3) > 0) AddPlayerHand("DYN065", $currentPlayer, $cardID);
      return "";
    case "MST063": case "MST064": case "MST065":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if(SearchPitchForColor($currentPlayer, 3) > 0) AddPlayerHand("DYN065", $currentPlayer, $cardID);
      return "";
    case "MST067":
      Draw($currentPlayer);
      return "";
    case "MST069":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "MST070":
      GiveAttackGoAgain();
      return "";
    case "MST071": case "MST072": case "MST073": case "MST074":
      IncrementClassState($currentPlayer, $CS_DamagePrevention);
      return "";
    case "MST075":
      if (IsHeroAttackTarget()) {
        $deck = new Deck($defPlayer);
        if($deck->Reveal(1) && PitchValue($deck->Top()) == 3) {
            AddCurrentTurnEffect($cardID, $currentPlayer);
        }
      }
      return "";
    case "MST076":
      $chiArray = explode(",", SearchCardList($additionalCosts, $currentPlayer, subtype:"Chi"));
      $amountChiPitch = count($chiArray);
      if(SearchCardList($additionalCosts, $currentPlayer, subtype:"Chi") != "") 
      {
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
      if($numChoices >= 3){
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $modalities);
        AddDecisionQueue("MODAL", $currentPlayer, "LEVELSOFENLIGHTENMENT", 1);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      }
      elseif($numChoices < 3 && $numChoices > 0) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose " . $numChoices . " modes");
        AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, $numChoices."-".$modalities."-".$numChoices);
        AddDecisionQueue("MODAL", $currentPlayer, "LEVELSOFENLIGHTENMENT", 1);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      }
      return "";
    case "MST078":
      if(SearchCardList($additionalCosts, $currentPlayer, subtype:"Chi") != "") Draw($currentPlayer); 
      return "";
    case "MST079": 
      AddCurrentTurnEffect($cardID."-DEBUFF", $otherPlayer);
      if(SearchCardList($additionalCosts, $currentPlayer, subtype:"Chi") != "") AddCurrentTurnEffect($cardID."-HITPREVENTION", $currentPlayer);
      return "";
    case "MST080":
      Draw($currentPlayer);
      Draw($currentPlayer);
      if(SearchCardList($additionalCosts, $currentPlayer, subtype:"Chi") != "") Draw($currentPlayer); 
      return "";
    case "MST084": case "MST085": case "MST086":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "MST092":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "MST093":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "MST094":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "MST095":
      if($CombatChain->HasCurrentLink() || HasAttackLayer()) {
        AddCurrentTurnEffect($cardID, $mainPlayer);
      }
      if(GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST495,".$from);
      return "";
    case "MST096":
      GainHealth(1, $currentPlayer);
      if(GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST496,".$from);
      return "";    
    case "MST097":
      $params = explode("-", $target);
      $index = SearchdiscardForUniqueID($params[1], $otherPlayer);
      if($index != -1) {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "THEIRDISCARD-".$index, 1);
        AddDecisionQueue("MZADDZONE", $currentPlayer, "THEIRBANISH,GY,-,".$cardID, 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        if(GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST497,".$from);  
      }
      else {
        WriteLog(CardLink($cardID, $cardID) . " layer fails as there are no remaining targets for the targeted effect and this card does not transcend.");
      }
      return "";
    case "MST098":
      GiveAttackGoAgain();
      if(GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST498,".$from);
      return "";
    case "MST099":
      $params = explode("-", $target);
      $index = SearchdiscardForUniqueID($params[1], $currentPlayer);
      if($index != -1) {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "MYDISCARD-".$index, 1);
        AddDecisionQueue("MZADDZONE", $currentPlayer, "MYBOTDECK", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        if(GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST499,".$from);
      }
      else {
        WriteLog(CardLink($cardID, $cardID) . " layer fails as there are no remaining targets for the targeted effect and this card does not transcend.");
      }
      return "";
    case "MST100":
      Draw($currentPlayer);
      if(count($hand) == 1) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Show the card drawn:");
        AddDecisionQueue("OK", $currentPlayer, "<-", 1);
      }
      MZMoveCard($currentPlayer, "MYHAND", "MYBOTDECK", silent:true, DQContext:"Choose a card to put on the bottom of your deck:");
      if(GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST500,".$from);
      return "";
    case "MST101":
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      if(GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST501,".$from);
      return "";
    case "MST102":
      if($CombatChain->HasCurrentLink() || HasAttackLayer()) {
        AddCurrentTurnEffect($cardID, $mainPlayer);
      }
      if(GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) AddDecisionQueue("TRANSCEND", $currentPlayer, "MST502,".$from);
      return "";
    case "MST105":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "JUSTANICK", 1);
      return "";
    case "MST132": 
      if($from != "PLAY")
      {
        $illusionistAuras = SearchAura($currentPlayer, class:"ILLUSIONIST");
        $arrayAuras = explode(",", $illusionistAuras);
        if(count($arrayAuras) <= 1) GainActionPoints(1, $currentPlayer);
      }
      return "";
    case "MST133":
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      $auras = GetAuras($currentPlayer);
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if($from != "PLAY"){
        $count = CountAuraAtkCounters($currentPlayer)+10; //+10 is an arbitrary number to keep the loop going until the player pass
        for($i=0; $i < $count; $i++) { 
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS:hasAttackCounters=true", 1);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura to remove a -1 attack counter or pass", 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZOP", $currentPlayer, "TRANSFERATKCOUNTER", 1);
        }
        AddCurrentTurnEffect($cardID, $currentPlayer, $from, $auras[count($auras)-AuraPieces()+6]);
      }
      if($abilityType != "I") return "";

      if(SearchCurrentTurnEffectsForUniqueID($auras[$index+6]."-PAID") != -1)
      {
        PlayAura("MON104", $currentPlayer);
        RemoveCurrentTurnEffect(SearchCurrentTurnEffectsForUniqueID($auras[$index+6]."-PAID"));
      }
      elseif(SearchCurrentTurnEffectsForPartielID("PAID")) //It needs to check if the auras was destroy, but it's already paid for
      {
        PlayAura("MON104", $currentPlayer);
        RemoveCurrentTurnEffect(SearchCurrentTurnEffectsForUniqueID($auras[$index+6]."-PAID"));
      }
      else
      {
        WriteLog("You do not have the counters to pay for ". CardLink($cardID, $cardID)." ability.", highlight:true);
      }
      return "";
    case "MST134": case "MST135": case "MST136":
      $amount = 3;
      if($cardID == "MST135") $amount = 2;
      else if ($cardID == "MST136") $amount = 1;
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $target, 1);
      AddDecisionQueue("MZADDCOUNTERS", $currentPlayer, $amount, 1);
      return "";
    case "MST146": case "MST147": case "MST148": 
      if($from != "PLAY") {
        $auras = &GetAuras($currentPlayer);
        $illusionistAuras = SearchAura($currentPlayer, class:"ILLUSIONIST");
        $arrayAuras = explode(",", $illusionistAuras);
        $amount = 3;
        if($cardID == "MST147") $amount = 2;
        else if ($cardID == "MST148") $amount = 1;
        if(count($arrayAuras) <= 1) {
          $index = count($auras)-AuraPieces();
          $auras[$index+3] += $amount;
        }
      }
      return "";
    case "MST149": case "MST150": case "MST151": 
      if($from != "PLAY") {
        $illusionistAuras = SearchAura($currentPlayer, class:"ILLUSIONIST");
        $arrayAuras = explode(",", $illusionistAuras);
        if(count($arrayAuras) <= 1) PlayAura("MON104", $currentPlayer);  
      }
      return "";
    case "MST152": case "MST153": case "MST154":  
      if(SearchAura($currentPlayer, class:"ILLUSIONIST") != "") $amount = 0;
      else if ($cardID == "MST152") $amount = 3;
      else if($cardID == "MST153") $amount = 2;
      else if ($cardID == "MST154") $amount = 1;  
      PlayAura("MON104", $currentPlayer, numAttackCounters:$amount);
      return "";
    case "MST159":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";  
    case "MST161":
      if(ComboActive()) AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";  
    case "MST162":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "MAUL", 1);
      return "";
    case "MST164": case "MST165": case "MST166":
      if(ComboActive()) {
        BanishCardForPlayer("DYN065", $currentPlayer, "-", "TT", $currentPlayer);
      }
      return "";
    case "MST176": case "MST177": case "MST178":
      if(ComboActive()) {
        BanishCardForPlayer("DYN065", $currentPlayer, "-", "TT", $currentPlayer);
        GiveAttackGoAgain();
      }
      return "";
    case "MST185": case "MST186": case "MST187":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "MST193":
      AddCurrentTurnEffect($cardID."-RED", $currentPlayer);
      AddCurrentTurnEffect($cardID."-YELLOW", $currentPlayer);
      AddCurrentTurnEffect($cardID."-BLUE", $currentPlayer);
      break;
    case "MST197":
      if($additionalCosts != "-") Draw($currentPlayer);
      break;
    case "MST198":
      if($additionalCosts != "-") AddCurrentTurnEffect($cardID, $currentPlayer);
      break;  
    case "MST199":
      if($additionalCosts != "-") AddDecisionQueue("OP", $currentPlayer, "GIVEATTACKGOAGAIN", 1);
      break;
    case "MST200": case "MST201": case "MST202":
      if(IsHeroAttackTarget()) MZMoveCard($currentPlayer, "THEIRDISCARD", "THEIRBANISH", true, true, DQContext:"Choose a card to banish from their graveyard.");
      return "";
    case "MST212": case "MST213": case "MST214":
      AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      return "";
    case "MST225":
      PutItemIntoPlayForPlayer("DYN243", $currentPlayer, effectController:$currentPlayer);
      $numGold = CountItem("DYN243", $currentPlayer);
      if($numGold >= 3 && !SearchCurrentTurnEffects("OUT183", $currentPlayer)) {
        PlayAura("HVY241", $currentPlayer, $numGold); //Might
        WriteLog(CardLink($cardID, $cardID) . " created a Gold token and " . $numGold . " Might tokens");
      }
      else WriteLog(CardLink($cardID, $cardID) . " created a Gold token");
      return "";
    case "MST226":
      for($i = 0; $i < intval($additionalCosts); ++$i) {
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
      if($resourcesPaid > $countHyperDriver) $resourcesPaid = $countHyperDriver;
      for($i=0; $i < $resourcesPaid; $i++) { 
        if($i==0){
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYITEMS:isSameName=ARC036");
          AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        }
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose " . $resourcesPaid . " Hyper Driver to get " . $resourcesPaid . " steam counter", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "{0}", 1);
        AddDecisionQueue("SUPERCELL", $currentPlayer, $resourcesPaid, 1);  
      }
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "EVO234", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, $resourcesPaid, 1);
      if($resourcesPaid >= 3 && SearchBanishForCardName($currentPlayer, "DYN092") != -1){
        MZMoveCard($currentPlayer, "MYBANISH:isSameName=DYN092", "MYTOPDECK", true, true, DQContext:"Choose a card to shuffle in your deck, or pass");
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      }
      return "";
    case "MST232":
      AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      return "";
    case "MST233":
      if(HasAimCounter()) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      return "";
    case "MST234":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if(count($hand) == 0) Draw($currentPlayer);
      return "";
    case "MST235":
      PutPermanentIntoPlay($currentPlayer, $cardID);
      return "";
    case "MST238":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=E;faceDown=true");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("ENIGMAMOON", $currentPlayer, "-", 1);
      return "";
    default: return "";
  }
}

  function HVYPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $currentPlayer, $chainLinks, $defPlayer, $CS_NumCardsDrawn, $CS_HighestRoll, $CombatChain, $CS_NumMightDestroyed, $CS_DieRoll;
    $otherPlayer = $currentPlayer == 1 ? 2 : 1;
    $rv = "";
    switch($cardID) {
      case "HVY007":
        Draw($currentPlayer);
        DiscardRandom();
        return "";
      case "HVY009":
        $roll = GetDieRoll($currentPlayer);
        AddCurrentTurnEffect($cardID . "-" . $roll, $currentPlayer);
        return "Rolled $roll and your intellect is " . $roll . " until the end of turn.";
      case "HVY010":
        Draw($currentPlayer);
        DiscardRandom($currentPlayer, $cardID);
        return "";
      case "HVY012":
        if (IsHeroAttackTarget()) {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRARS");
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZBANISH", $currentPlayer, "CC," . $cardID, 1);
          AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
          AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
        } else {
          WriteLog("<span style='color:red;'>No arsenal is banished because it does not attack a hero.</span>");
        }
        return "";
      case "HVY013":
        if (IsHeroAttackTarget()) Intimidate();
        return "";
      case "HVY014":
        $deck = new Deck($currentPlayer);
        if($deck->Reveal(6)) {
          $cards = explode(",", $deck->Top(amount:6));
          $numSixes = 0;
          for($i = 0; $i < count($cards); ++$i) {
            if(ModifiedAttackValue($cards[$i], $currentPlayer, "DECK") >= 6) ++$numSixes;
          }
          PlayAura("HVY241", $currentPlayer, $numSixes); //Might
          if(CountAura("HVY241", $currentPlayer) >= 6) PlayAura("HVY240", $currentPlayer); //Agility

          $zone = &GetDeck($currentPlayer);
          $topDeck = array_slice($zone, 0, 6);
          shuffle($topDeck);
          for($i = 0; $i < count($topDeck); ++$i) {
            $zone[$i] = $topDeck[$i];
          }
        }
        return "";
      case "HVY015":
        RollDie($currentPlayer);
        $roll = GetClassState($currentPlayer, $CS_DieRoll);
        GainActionPoints(intval($roll/2), $currentPlayer);
        if(GetClassState($currentPlayer, $CS_HighestRoll) == 6) Draw($currentPlayer);
        return "Rolled $roll and gained " . intval($roll/2) . " action points";
      case "HVY016":
        AddCurrentTurnEffect($cardID . "-" . $additionalCosts, $currentPlayer);
        return "";
      case "HVY023": case "HVY024": case "HVY025":
        if(SearchCurrentTurnEffects("BEATCHEST", $currentPlayer) && IsHeroAttackTarget()) Intimidate();
        return "";
      case "HVY026": case "HVY027": case "HVY028":
        if(SearchCurrentTurnEffects("BEATCHEST", $currentPlayer)) PlayAura("HVY240", $currentPlayer);//Agility
        return "";
      case "HVY035": case "HVY036": case "HVY037":
        if(SearchCurrentTurnEffects("BEATCHEST", $currentPlayer)) PlayAura("HVY241", $currentPlayer);//Might
        return "";
      case "HVY041": case "HVY042": case "HVY043":
        if($cardID == "HVY041") $amount = 3;
        else if($cardID == "HVY042") $amount = 2;
        else if($cardID == "HVY043") $amount = 1;
        if(SearchCurrentTurnEffects("BEATCHEST", $currentPlayer)) $amount += 2;
        AddCurrentTurnEffect($cardID . "," . $amount, $currentPlayer);
        return "";
      case "HVY044":
        PlayAura("HVY240", $currentPlayer);//Agility
        PlayAura("HVY241", $currentPlayer);//Might
        return "";
      case "HVY055":
        AddCurrentTurnEffect($cardID . "-PAID", $currentPlayer);
        return "";
      case "HVY057":
        if(IsHeroAttackTarget()) AskWager($cardID);
        return "";
      case "HVY058":
        if(GetClassState($currentPlayer, $CS_NumMightDestroyed) > 0 || SearchAurasForCard("HVY241", $currentPlayer)) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "HVY063":
        AddCurrentTurnEffect($cardID, $defPlayer);
        return "";
      case "HVY089":
        PlayAura("HVY241", $currentPlayer);//Might
        PlayAura("HVY242", $currentPlayer);//Vigor
        return "";
      case "HVY133":
        PlayAura("HVY240", $currentPlayer);//Agility
        PlayAura("HVY242", $currentPlayer);//Vigor
        return "";
      case "HVY090": case "HVY091":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "HVY098":
        if(IsHeroAttackTarget()) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          AddOnWagerEffects();
        }
        return "";
      case "HVY099":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "HVY101":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        return "";
      case "HVY102":
        if(CachedTotalAttack() > AttackValue($CombatChain->AttackCard()->ID())) {
          GiveAttackGoAgain();
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "HVY103":
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
        AddDecisionQueue("MODAL", $currentPlayer, "UPTHEANTE", 1);
        return "";
      case "HVY104":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddCurrentTurnEffect($cardID . "-BUFF", $currentPlayer);
        return "";
      case "HVY105":
        PlayAlly("HVY134", $currentPlayer, number:intval($additionalCosts));
        return "";
      case "HVY106": case "HVY107": case "HVY108":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        if (NumAttacksBlocking() > 0) {
          Draw($currentPlayer);
          $hand = &GetHand($currentPlayer);
          $arsenal = GetArsenal($currentPlayer);
          if(count($hand) + count($arsenal) == 1) {
            AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Here's the card that goes to the bottom of your deck.", 1);
            AddDecisionQueue("OK", $currentPlayer, "-");
          }
          if(count($hand) + count($arsenal) > 0) MZMoveCard($currentPlayer, "MYHAND&MYARS", "MYBOTDECK", silent:true);
        }
        return "";
      case "HVY109": case "HVY110": case "HVY111":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "HVY115": case "HVY116": case "HVY117":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        if(NumAttacksBlocking() > 0)  PlayAura("HVY240", $currentPlayer); //Agility
        return "";
      case "HVY118": case "HVY119": case "HVY120":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        if(NumAttacksBlocking() > 0)  PlayAura("HVY242", $currentPlayer); //Vigor
        return "";
      case "HVY121": case "HVY122": case "HVY123":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        Draw($currentPlayer);
        return "";
      case "HVY124": case "HVY125": case "HVY126":
        AddCurrentTurnEffect($cardID . "-BUFF", $currentPlayer);
        return "";
      case "HVY127": case "HVY128": case "HVY129":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "HVY130": case "HVY131": case "HVY132":
        AddCurrentTurnEffect($cardID . "-BUFF", $currentPlayer);
        return "";
      case "HVY135":
        PlayAura("HVY241", $currentPlayer); //Might
        return "";
      case "HVY136":
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a number");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20");
        AddDecisionQueue("WRITELOGLASTRESULT", $currentPlayer, "-", 1);
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "HVY136,");
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "<-");
        return "";
      case "HVY140":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "HVY143": case "HVY144": case "HVY145":
        if(GetResolvedAbilityType($cardID, "HAND") == "I") {
          PlayAura("HVY241", $currentPlayer); //Might
          CardDiscarded($currentPlayer, $cardID, source:$cardID);
        }
        return "";
      case "HVY149": case "HVY150": case "HVY151":
        if(IsHeroAttackTarget()) AskWager($cardID);
        return "";
      case "HVY152": case "HVY153": case "HVY154":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        PlayAura("HVY241", $currentPlayer); //Might
        return "";
      case "HVY155":
        PlayAura("HVY240", $currentPlayer); //Agility
        return "";
      case "HVY156":
        if(DoesAttackHaveGoAgain()) PlayAura("HVY240", $currentPlayer); //Agility
        return "";
      case "HVY160":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "HVY163": case "HVY164": case "HVY165":
        if(GetResolvedAbilityType($cardID, "HAND") == "I") {
          PlayAura("HVY240", $currentPlayer); //Agility
          CardDiscarded($currentPlayer, $cardID, source:$cardID);
        }
        return "";
      case "HVY169": case "HVY170": case "HVY171":
        if(IsHeroAttackTarget()) AskWager($cardID);
        return "";
      case "HVY172": case "HVY173": case "HVY174":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        PlayAura("HVY240", $currentPlayer); //Agility
        return "";
      case "HVY175":
        PlayAura("HVY242", $currentPlayer); //Vigor
        return "";
      case "HVY176":
        AddCurrentTurnEffect($cardID . "-BUFF", $currentPlayer);
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddCurrentTurnEffect($cardID, $otherPlayer);
        return "";
      case "HVY180":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "HVY186": case "HVY187": case "HVY188":
        if(GetResolvedAbilityType($cardID, "HAND") == "I") {
          PlayAura("HVY242", $currentPlayer); //Vigor
          CardDiscarded($currentPlayer, $cardID, source:$cardID);
        }
        return "";
      case "HVY189": case "HVY190": case "HVY191":
        if(IsHeroAttackTarget()) AskWager($cardID);
        return "";
      case "HVY192": case "HVY193": case "HVY194":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        PlayAura("HVY242", $currentPlayer); //Vigor
        return "";
      case "HVY195":
        Draw($currentPlayer);
        return "";
      case "HVY196":
        Draw($currentPlayer);
        return "";
      case "HVY197":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "HVY209":
        if(GetResolvedAbilityType($cardID, "HAND") == "I") {
          AddCurrentTurnEffect($cardID, $currentPlayer, $from);
        }
        return "";
      case "HVY210":
        MZMoveCard($currentPlayer, "MYARS", "MYBOTDECK", may:true, silent:true);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
        return "";
      case "HVY211":
        $buff = NumCardsBlocking();
        for($i=0; $i<count($chainLinks); ++$i) {
          for($j=0; $j<count($chainLinks[$i]); $j+=ChainLinksPieces()) {
            if($chainLinks[$i][$j+1] == $defPlayer) ++$buff;
          }
        }
        AddCurrentTurnEffect($cardID . "," . $buff, $currentPlayer);
        return "";
      case "HVY212":
        LookAtTopCard($currentPlayer, $cardID, showHand:true);
        if($from == "ARS") Draw($currentPlayer);
        return "";
      case "HVY213": case "HVY214": case "HVY215":
        if(IsHeroAttackTarget() && PlayerHasLessHealth($currentPlayer) && GetPlayerNumEquipment($currentPlayer) < GetPlayerNumEquipment($otherPlayer) && GetPlayerNumTokens($currentPlayer) < GetPlayerNumTokens($otherPlayer)) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "HVY216": case "HVY217": case "HVY218":
        if(IsHeroAttackTarget()) AskWager($cardID);
        return "";
      case "HVY225": case "HVY226": case "HVY227":
        if($from == "ARS") GiveAttackGoAgain();
        return "";
      case "HVY235": case "HVY236": case "HVY237":
        AddCurrentTurnEffect($cardID . "-BUFF", $currentPlayer);
        return "";
      case "HVY238":
        if(CountItem("DYN243", $currentPlayer, false) == 0){
          PutItemIntoPlayForPlayer("DYN243", $currentPlayer, effectController:$currentPlayer);
          WriteLog(CardLink($cardID, $cardID) . " created a Gold token");
        }
        return;
      case "HVY245":
        if($from == "GY") {
          $character = &GetPlayerCharacter($currentPlayer);
          EquipWeapon($currentPlayer,"HVY245");
          $index = FindCharacterIndex($currentPlayer, "HVY245");
          if ($character[$index + 3] == 0) {
            ++$character[$index + 3];
          } else {
            ++$character[$index + 15];
          }
        }
        return "";
      case "HVY246":
        if(IsHeroAttackTarget()) {
          $deck = new Deck($otherPlayer);
          if($deck->RemainingCards() > 0) {
            AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to put on top of their deck");
            AddDecisionQueue("CHOOSETOPOPPONENT", $currentPlayer, $deck->Top(true, 3));
            AddDecisionQueue("FINDINDICES", $otherPlayer, "TOPDECK", 1);
            AddDecisionQueue("MULTIREMOVEDECK", $otherPlayer, "<-", 1);
            AddDecisionQueue("MULTIBANISH", $otherPlayer, "DECK,". $cardID. "," . $currentPlayer);
          }
        }
        return "";
      case "HVY247":
        $deck = new Deck($currentPlayer);
        $banishMod = "-";
        if(HasCombo($deck->Top())) $banishMod = "TT";
        $deck->BanishTop($banishMod, $currentPlayer);
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "HVY251":
        $xVal = $resourcesPaid/2;
        MZMoveCard($currentPlayer, "MYDECK:maxCost=" . $xVal . ";subtype=Aura;class=RUNEBLADE", "MYAURAS", may:true);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        if($xVal >= 2) {
          global $CS_NextNAACardGoAgain;
          SetClassState($currentPlayer, $CS_NextNAACardGoAgain, 1);
        }
        return "";
      case "HVY252":
        DealArcane(1, 1, "PLAYCARD", $cardID);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "AERTHERARC");
        return "";
      case "HVY253":
        for($i = 1; $i < 3; $i += 1) {
          $arsenal = &GetArsenal($i);
          for($j = 0; $j < count($arsenal); $j += ArsenalPieces()) {
            AddDecisionQueue("FINDINDICES", $i, "ARSENAL");
            AddDecisionQueue("CHOOSEARSENAL", $i, "<-", 1);
            AddDecisionQueue("REMOVEARSENAL", $i, "-", 1);
            AddDecisionQueue("ADDBOTDECK", $i, "-", 1);
          }
          PlayAura("DYN244", $i);
        }
        return "";
      case "HVY250":
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Here's the card that on top of your deck.", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKTOPXINDICES," . ($resourcesPaid + 1));
        AddDecisionQueue("DECKCARDS", $currentPlayer, "<-", 1);
        AddDecisionQueue("LOOKTOPDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, CardLink($cardID, $cardID) . " shows the top cards of your deck are:", 1);
        AddDecisionQueue("MULTISHOWCARDSDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKTOPXINDICES," . ($resourcesPaid + 1));
        AddDecisionQueue("DECKCARDS", $currentPlayer, "<-", 1);
        AddDecisionQueue("TOPDECKCHOOSE", $currentPlayer, $resourcesPaid + 1 .",Trap" , 1);
        AddDecisionQueue("MULTICHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        Reload();
        return "";
      default: return "";
    }
  }

  function TCCPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $mainPlayer, $currentPlayer, $defPlayer;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID) {
      case "TCC035":
        AddCurrentTurnEffect($cardID, $defPlayer);
        return "";
      case "TCC050":
        $abilityType = GetResolvedAbilityType($cardID);
        $character = &GetPlayerCharacter($currentPlayer);
        $charIndex = FindCharacterIndex($mainPlayer, $cardID);
        if ($abilityType == "A") {
          AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose a token to create");
          AddDecisionQueue("MULTICHOOSETEXT", $otherPlayer, "1-Might (+1),Vigor (Resource),Quicken (Go Again)-1");
          AddDecisionQueue("SHOWMODES", $otherPlayer, $cardID, 1);
          AddDecisionQueue("MODAL", $otherPlayer, "JINGLEWOOD", 1);
          PutItemIntoPlayForPlayer("CRU197", $currentPlayer);
          --$character[$charIndex+5];
          }
        return "";
      case "TCC051":
        Draw(1);
        Draw(2);
        return "";
      case "TCC052":
        PlayAura("HVY242", 1);
        PlayAura("HVY242", 2);
        return "";
      case "TCC053":
        PlayAura("HVY241", 1);
        PlayAura("HVY241", 2);
        return "";
      case "TCC054":
        PlayAura("WTR225", 1);
        PlayAura("WTR225", 2);
        return "";
      case "TCC057":
        $numPitch = SearchCount(SearchPitch($currentPlayer)) + SearchCount(SearchPitch($otherPlayer));
        AddCurrentTurnEffect($cardID . "," . ($numPitch*2), $currentPlayer);
        return "";
      case "TCC058": case "TCC062": case "TCC075":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "TCC061":
        MZMoveCard($currentPlayer, "MYDISCARD:class=BARD;type=AA", "MYHAND", may:false, isSubsequent:false);
        return "";
      case "TCC064":
        PlayAura("WTR225", $otherPlayer);
        return "";
      case "TCC065":
        GainHealth(1, $otherPlayer);
        return "";
      case "TCC066": 
        PlayAura("HVY242", $otherPlayer);
        return "";
      case "TCC067":
        PlayAura("HVY241", $otherPlayer);
        return "";
      case "TCC068":
        Draw($otherPlayer);
        return "";
      case "TCC069":
        MZMoveCard($otherPlayer, "MYDISCARD:type=AA", "MYBOTDECK", may:true);
        return "";
      case "TCC079":
        Draw($currentPlayer);
        return "";
      case "TCC080":
        GainResources($currentPlayer, 1);
        return "";
      case "TCC082":
        BanishCardForPlayer("DYN065", $currentPlayer, "-", "TT", $currentPlayer);
        return "";
      case "TCC086": case "TCC094":
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        break;
      default: return "";
    }
  }

  function EVOPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $mainPlayer, $currentPlayer, $defPlayer, $layers, $combatChain, $CCS_RequiredNegCounterEquipmentBlock, $combatChainState;
    global $CS_NamesOfCardsPlayed, $CS_NumBoosted, $CS_PlayIndex, $CS_NumItemsDestroyed, $CS_DamagePrevention;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    $character = &GetPlayerCharacter($currentPlayer);
    switch($cardID) {
      case "EVO004": case "EVO005":
        PutItemIntoPlayForPlayer("EVO234", $currentPlayer, 2);
        --$character[5];
        return "";
      case "EVO007": case "EVO008":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        --$character[5];
        return "";
      case "EVO010":
        $conditionsMet = CheckIfSingularityConditionsAreMet($currentPlayer);
        if ($conditionsMet != "") return $conditionsMet;
        $char = &GetPlayerCharacter($currentPlayer);
        // We don't want function calls in every iteration check
        $charCount = count($char);
        $charPieces = CharacterPieces();
        AddSoul($char[0], $currentPlayer, "-");
        if (isSubcardEmpty($char, 0)) $char[10] = $char[0];
        else $char[10] = $char[10] . "," . $char[0];
        $char[0] = "EVO410";
        $char[1] = 2;
        $char[2] = 0;
        $char[3] = 0;
        $char[4] = 0;
        $char[5] = 999; // Remove the 'Once per Turn' limitation from Teklovossen
        $char[6] = 0;
        $char[7] = 0;
        $char[8] = 0;
        $char[9] = 2;
        $char[11] = GetUniqueId("EVO410", $currentPlayer);
        $mechropotentIndex = 0; // we pushed it, so should be the last element
        for ($i = $charCount - $charPieces; $i >= 0; $i -= $charPieces) {
          if($char[$i] != "EVO410") {
            EvoTransformAbility("EVO410", $char[$i], $currentPlayer);
            RemoveCharacterAndAddAsSubcardToCharacter($currentPlayer, $i, $mechropotentIndex);
          }
        }
        PutCharacterIntoPlayForPlayer("EVO410b", $currentPlayer);
        return "";
      case "EVO014":
        MZMoveCard($mainPlayer, "MYBANISH:class=MECHANOLOGIST;type=AA", "MYTOPDECK", isReveal:true);
        AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-", 1);
        return "";
      case "EVO015":
        AddDecisionQueue("GAINRESOURCES", $mainPlayer, "2");
        return "";
      case "EVO016":
        AddCurrentTurnEffectNextAttack($cardID, $mainPlayer);
        return "";
      case "EVO017":
        AddDecisionQueue("GAINACTIONPOINTS", $mainPlayer, "1");
        return "";
      case "EVO030": case "EVO031": case "EVO032": case "EVO033":
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "-");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "EVOBREAKER");
        return "Light up the gem when you want the conditional boost effect to trigger";
      case "EVO057":
        if(IsHeroAttackTarget() && EvoUpgradeAmount($mainPlayer) > 0) {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRITEMS:hasSteamCounter=true&THEIRCHAR:hasSteamCounter=true");
          AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "MAXCOUNT-" . EvoUpgradeAmount($mainPlayer) . ",MINCOUNT-" . 0 . ",", 1);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose up to " . EvoUpgradeAmount($currentPlayer) . " card" . (EvoUpgradeAmount($mainPlayer) > 1 ? "s" : "") . " to remove all steam counters from." , 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZREMOVECOUNTER", $currentPlayer, "<-");
        }
        return "";
      case "EVO058":
        if(IsHeroAttackTarget() && EvoUpgradeAmount($currentPlayer) > 0)
        {
          $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
          AddDecisionQueue("PASSPARAMETER", $otherPlayer, EvoUpgradeAmount($currentPlayer), 1);
          AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
          AddDecisionQueue("APPENDLASTRESULT", $otherPlayer, "-{0}", 1);
          AddDecisionQueue("PREPENDLASTRESULT", $otherPlayer, "{0}-", 1);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose " . EvoUpgradeAmount($currentPlayer) . " card(s)", 1);
          AddDecisionQueue("MULTICHOOSEHAND", $otherPlayer, "<-", 1);
          AddDecisionQueue("IMPLODELASTRESULT", $otherPlayer, ",", 1);
          AddDecisionQueue("SETDQVAR", $currentPlayer, "1");
          AddDecisionQueue("REVEALHANDCARDS", $otherPlayer, "<-", 1);
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{1}", 1);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card", 1);
          AddDecisionQueue("SPECIFICCARD", $otherPlayer, "PULSEWAVEPROTOCOLFILTER", 1);
          AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
          AddDecisionQueue("ADDCARDTOCHAINASDEFENDINGCARD", $otherPlayer, "HAND", 1);
        }
        return "";
      case "EVO059":
        $negCounterEquip = explode(",", SearchCharacter($otherPlayer, hasNegCounters:true));
        $numNegCounterEquip = count($negCounterEquip);
        if($numNegCounterEquip > EvoUpgradeAmount($currentPlayer)) $requiredEquip = EvoUpgradeAmount($currentPlayer);
        else $requiredEquip = $numNegCounterEquip;
        if($numNegCounterEquip > 0 && $requiredEquip > 0 && !IsAllyAttackTarget()) {
          $combatChainState[$CCS_RequiredNegCounterEquipmentBlock] = $requiredEquip;
          if($requiredEquip > 1) $rv = CardLink($cardID, $cardID) . " requires you to block with " . $requiredEquip . " equipments";
          else $rv = CardLink($cardID, $cardID) . " requires you to block with " . $requiredEquip . " equipment";
          WriteLog($rv);
        }
        return "";
      case "EVO070":
        if($from == "PLAY") DestroyTopCardTarget($currentPlayer);
        break;
      case "EVO071":
        if($from == "PLAY") {
          $deck = new Deck($currentPlayer);
          $deck->Reveal();
          $pitchValue = PitchValue($deck->Top());
          MZMoveCard($currentPlayer, ("MYBANISH:class=MECHANOLOGIST;subtype=Item;pitch=" . $pitchValue),"MYTOPDECK", may:true, isReveal:true);
        }
        break;
      case "EVO072":
        if($from == "PLAY") {
          MZMoveCard($currentPlayer, "MYHAND:class=MECHANOLOGIST;subtype=Item;maxCost=1", "", may:true);
          AddDecisionQueue("PUTPLAY", $currentPlayer, "0", 1);
        }
        break;
      case "EVO073":
        if($from == "PLAY") {
          $index = GetClassState($currentPlayer, $CS_PlayIndex);
          RemoveItem($currentPlayer, $index);
          $deck = new Deck($currentPlayer);
          $deck->AddBottom($cardID, from:"PLAY");
          AddDecisionQueue("FINDINDICES", $otherPlayer, "EQUIP");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target equipment it cannot defend this turn");
          AddDecisionQueue("CHOOSETHEIRCHARACTER", $currentPlayer, "<-", 1);
          AddDecisionQueue("EQUIPCANTDEFEND", $otherPlayer, "EVO073-B-", 1);
        }
        break;
      case "EVO075":
        if($from == "PLAY") GainResources($currentPlayer, 1);
        return "";
      case "EVO076":
        if($from == "PLAY") GainHealth(2, $currentPlayer);
        return "";
      case "EVO077":
        if($from == "PLAY")
        {
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card with Crank to get a steam counter", 1);
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYITEMS:hasCrank=true");
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZADDCOUNTER", $currentPlayer, "-", 1);
        }
        return "";
      case "EVO079":
        if($currentPlayer == $defPlayer) {
          for($j = CombatChainPieces(); $j < count($combatChain); $j += CombatChainPieces()) {
            if($combatChain[$j+1] != $currentPlayer) continue;
            if(CardType($combatChain[$j]) == "AA" && ClassContains($combatChain[$j], "MECHANOLOGIST", $currentPlayer)) CombatChainPowerModifier($j, 1);
          }
        }
        break;
      case "EVO081": case "EVO082": case "EVO083":
        if($from == "PLAY") {
          MZMoveCard($currentPlayer, "MYDISCARD:pitch=". PitchValue($cardID) .";type=AA;class=MECHANOLOGIST", "MYHAND", may:true, isReveal:true);
        }
        return "";
      case "EVO087": case "EVO088": case "EVO089":
        if($from == "PLAY") {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          IncrementClassState($currentPlayer, $CS_DamagePrevention, 1);
        }
        return "";
      case "EVO100":
        $items = SearchDiscard($currentPlayer, subtype: "Item");
        $itemsCount = count(explode(",", $items));
        if ($itemsCount < $resourcesPaid) {
          WriteLog("Player " . $currentPlayer . " would need to banish " . $resourcesPaid . " items from their graveyard but they only have " . $itemsCount . " items in their graveyard.");
          RevertGamestate();
        }
        AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, $resourcesPaid . "-" . $items . "-" . $resourcesPaid, 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "HYPERSCRAPPER");
        return "";
      case "EVO101":
        $numScrap = 0;
        $costAry = explode(",", $additionalCosts);
        for($i=0; $i<count($costAry); ++$i) if($costAry[$i] == "SCRAP") ++$numScrap;
        if($numScrap > 0) GainResources($currentPlayer, $numScrap * 2);
        return "";
      case "EVO102": case "EVO103": case "EVO104":
        if($additionalCosts == "SCRAP") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO105": case "EVO106": case "EVO107":
        if(GetClassState($currentPlayer, $CS_NumItemsDestroyed) > 0) AddCurrentTurnEffect($cardID, $defPlayer);
        return "";
      case "EVO108": case "EVO109": case "EVO110":
        if($additionalCosts == "SCRAP") PlayAura("WTR225", $currentPlayer);
        return "";
      case "EVO126": case "EVO127": case "EVO128":
        if($additionalCosts == "SCRAP") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO129": case "EVO130": case "EVO131":
        if($additionalCosts == "SCRAP") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO132": case "EVO133": case "EVO134":
        if($additionalCosts == "SCRAP") {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYITEMS:hasCrank=true");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card with Crank to get a steam counter", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZADDCOUNTER", $currentPlayer, "-", 1);
        }
        return "";
      case "EVO135": case "EVO136": case "EVO137":
        if($additionalCosts == "SCRAP") GainResources($currentPlayer, 1);
        return "";
      case "EVO140":
        for($i=0; $i<$resourcesPaid; $i+=2) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO141":
        if(GetClassState($mainPlayer, $CS_NumItemsDestroyed) > 0) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO143":
        if ($resourcesPaid == 0) return;
        AddDecisionQueue("MULTIZONEINDICES", $otherPlayer, "MYCHAR:type=E");
        AddDecisionQueue("PREPENDLASTRESULT", $otherPlayer, "MAXCOUNT-" . $resourcesPaid/3 . ",MINCOUNT-" . $resourcesPaid/3 . ",");
        AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose " . $resourcesPaid/3 . " equipment for the effect of " . CardLink("EVO143", "EVO143") . ".");
        AddDecisionQueue("CHOOSEMULTIZONE", $otherPlayer, "<-", 1);
        AddDecisionQueue("MZSWITCHPLAYER", $currentPlayer, "<-", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "MEGANETICLOCKWAVE");
        return "";
      case "EVO144":
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS:hasSteamCounter=true&THEIRCHAR:hasSteamCounter=true&MYITEMS:hasSteamCounter=true&MYCHAR:hasSteamCounter=true");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an equipment, item, or weapon. Remove all steam counters from it.");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZREMOVECOUNTER", $currentPlayer, "-", 1);
        AddDecisionQueue("SYSTEMFAILURE", $currentPlayer, "<-", 1);
        return "";
      case "EVO145":
        $indices = SearchMultizone($currentPlayer, "MYITEMS:class=MECHANOLOGIST;maxCost=1");
        $indices = str_replace("MYITEMS-", "", $indices);
        $num = SearchCount($indices);
        $num = $resourcesPaid < $num ? $resourcesPaid : $num;
        AddDecisionQueue("MULTICHOOSEITEMS", $currentPlayer, $num . "-" . $indices . "-" . $num);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "SYSTEMRESET");
        return "";
      case "EVO146":
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
        AddDecisionQueue("MODAL", $currentPlayer, "FABRICATE", 1);
        return "";
      case "EVO153": case "EVO154": case "EVO155":
        if(GetClassState($currentPlayer, $CS_NumBoosted) >= 2) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO156": case "EVO157": case "EVO158":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO222": case "EVO223": case "EVO224":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        MZMoveCard($currentPlayer, "MYBANISH:isSameName=ARC036", "", may:true);
        AddDecisionQueue("PUTPLAY", $currentPlayer, "0", 1);
        return "";
      case "EVO225": case "EVO226": case "EVO227":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO228": case "EVO229": case "EVO230":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a Hyper Driver to get a steam counter", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYITEMS:isSameName=ARC036");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZADDCOUNTER", $currentPlayer, "-", 1);
        return "";
      case "EVO235":
        $options = GetChainLinkCards(($currentPlayer == 1 ? 2 : 1), "AA");
        if($options != "") {
          AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, $options);
          AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $currentPlayer, -1, 1);
        }
        return "";
      case "EVO237":
        Draw($currentPlayer);
        $card = DiscardRandom();
        if(ModifiedAttackValue($card, $currentPlayer, "HAND", source:"EVO237") >= 6) {
          $items = SearchMultizone($currentPlayer, "THEIRITEMS&MYITEMS");
          if($items != "") {
            $items = explode(",", $items);
            $destroyedItem = $items[GetRandom(0, count($items) - 1)];
            $destroyedItemID = GetMZCard($currentPlayer, $destroyedItem);
            WriteLog(CardLink("EVO237", "EVO237") . " destroys " . CardLink($destroyedItemID, $destroyedItemID) . ".");
            MZDestroy($currentPlayer, $destroyedItem, $currentPlayer);
          }
        }
        return "";
      case "EVO238":
        PlayAura("WTR075", $currentPlayer, number:$resourcesPaid);
        return "";
      case "EVO239":
        $cardsPlayed = explode(",", GetClassState($currentPlayer, $CS_NamesOfCardsPlayed));
        for($i=0; $i<count($cardsPlayed); ++$i) {
          if(CardName($cardsPlayed[$i]) == "Wax On") {
            PlayAura("CRU075", $currentPlayer);
            break;
          }
        }
        return "";
      case "EVO240":
        if(ArsenalHasFaceDownCard($otherPlayer)) {
          SetArsenalFacing("UP", $otherPlayer);
          if (SearchArsenal($otherPlayer, type:"DR") != "") {
            DestroyArsenal($otherPlayer, effectController:$currentPlayer);
            AddCurrentTurnEffect($cardID, $currentPlayer);
          }
        }
        return "";
      case "EVO242":
        $xVal = $resourcesPaid/2;
        PlayAura("ARC112", $currentPlayer, $xVal);
        if($xVal >= 6) {
          DiscardRandom($otherPlayer, $cardID, $currentPlayer);
          DiscardRandom($otherPlayer, $cardID, $currentPlayer);
          DiscardRandom($otherPlayer, $cardID, $currentPlayer);
        }
        return "";
      case "EVO245":
        Draw($currentPlayer);
        if(IsRoyal($currentPlayer)) Draw($currentPlayer);
        PrependDecisionQueue("OP", $currentPlayer, "BANISHHAND", 1);
        if(SearchCount(SearchHand($currentPlayer, pitch:1)) >= 2) {
          PrependDecisionQueue("ELSE", $currentPlayer, "-");
          PitchCard($currentPlayer, "MYHAND:pitch=1");
          PitchCard($currentPlayer, "MYHAND:pitch=1");
          PrependDecisionQueue("NOPASS", $currentPlayer, "-");
          PrependDecisionQueue("YESNO", $currentPlayer, "if you want to pitch 2 red cards");
        }
        return "";
      case "EVO246":
        PutPermanentIntoPlay($currentPlayer, $cardID);
        return "";
      case "EVO247":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO248":
        MZChooseAndDestroy($currentPlayer, "THEIRALLY:subtype=Angel");
        return "";
      case "EVO410":
        if (IsHeroAttackTarget()) PummelHit();
        return "";
      case "EVO434":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO435":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO436":
        AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
        return "";
      case "EVO437":
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=W");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a weapon to attack an additional time");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "ADDITIONALUSE", 1);
        return "";
      case "EVO446":
        Draw($currentPlayer);
        MZMoveCard($currentPlayer, "MYHAND", "MYTOPDECK", silent:true);
        return "";
      case "EVO447":
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card with Crank to get a steam counter", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYITEMS:hasCrank=true");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZADDCOUNTER", $currentPlayer, "-", 1);
        return "";
      case "EVO448":
        MZMoveCard($currentPlayer, "MYHAND:subtype=Item;maxCost=1", "MYITEMS", may:true);
        return "";
      case "EVO449":
        PlayAura("WTR225", $currentPlayer);
        return "";
      default: return "";
    }
  }

  function PhantomTidemawDestroy($player = -1, $index = -1)
{
    global $mainPlayer;
    $auras = &GetAuras($player);
    if($player == -1) {
        $player = $mainPlayer;
    }

    if($index == -1) {
        for($i=0; $i < count($auras); $i++) { 
            if(isset($auras[$i*AuraPieces()]) && $auras[$i*AuraPieces()] == "EVO244") {
                ++$auras[$i*AuraPieces()+3];
            }
        }
    }
    else if($index > -1) {
        ++$auras[$index+3];
    }
}

