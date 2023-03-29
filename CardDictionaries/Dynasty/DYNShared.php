<?php

function DYNAbilityCost($cardID)
{
  switch($cardID) {
    case "DYN001": return 3;
    case "DYN005": return 3;
    case "DYN025": return 3;
    case "DYN046": return 0;
    case "DYN067": return 1;
    case "DYN068": return 3;
    case "DYN069": case "DYN070": return 1;
    case "DYN115": case "DYN116": return 2;
    case "DYN117": return 0;
    case "DYN118": return 0;
    case "DYN151": return 1;
    case "DYN172": return 3;
    case "DYN192": return 2;
    case "DYN193": return 3;
    case "DYN235": return 1;
    case "DYN240": return 0;
    case "DYN241": return 0;
    case "DYN242": return 1;
    case "DYN243": return 2;
    case "DYN492a": return 0;
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

function DYNHasGoAgain($cardID)
{
  switch($cardID) {
    case "DYN009":
    case "DYN022": case "DYN023": case "DYN024":
    case "DYN028":
    case "DYN049":
    case "DYN050": case "DYN051": case "DYN052":
    case "DYN062": case "DYN063": case "DYN064":
    case "DYN065":
    case "DYN071":
    case "DYN076": case "DYN077": case "DYN078":
		case "DYN082": case "DYN083": case "DYN084":
		case "DYN085": case "DYN086": case "DYN087":
    case "DYN091":
    case "DYN092":
    case "DYN115": case "DYN116":
    case "DYN155":
		case "DYN168": case "DYN169": case "DYN170":
		case "DYN185": case "DYN186": case "DYN187":
    case "DYN188": case "DYN189": case "DYN190":
    case "DYN209": case "DYN210": case "DYN211":
    case "DYN212":
    case "DYN230": case "DYN231": case "DYN232": return true;
    default: return false;
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
    case "DYN076": return NumEquipBlock() > 0 ? 3 : 0;
    case "DYN077": return NumEquipBlock() > 0 ? 2 : 0;
    case "DYN078": return NumEquipBlock() > 0 ? 1 : 0;
    case "DYN082": return 6;
    case "DYN083": return 5;
    case "DYN084": return 4;
    case "DYN085": return NumEquipBlock() > 0 ? 3 : 0;
    case "DYN086": return NumEquipBlock() > 0 ? 2 : 0;
    case "DYN087": return NumEquipBlock() > 0 ? 1 : 0;
    case "DYN089-UNDER": return 1;
    case "DYN091-1": return 3;
    case "DYN155": return 3;
    case "DYN156": case "DYN157": case "DYN158": return NumEquipBlock() > 0 ? 1 : 0;
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
    case "DYN013": case "DYN014": case "DYN015": return AttackValue($attackID) >= 6;
    case "DYN019": case "DYN020": case "DYN021": return true;
    case "DYN022": case "DYN023": case "DYN024": return ClassContains($attackID, "BRUTE", $mainPlayer);
    case "DYN028": return ClassContains($attackID, "GUARDIAN", $mainPlayer);
    case "DYN046": return $attackID == "DYN065";
    case "DYN049": return $attackID == "DYN065";
    case "DYN053": case "DYN054": case "DYN055": return $attackID == "DYN065";
    case "DYN068": return true;
    case "DYN071": return CardSubType($attackID) == "Axe";
    case "DYN073": case "DYN074": case "DYN075": return CardType($attackID) == "W";
    case "DYN076": case "DYN077": case "DYN078":
      $subtype = CardSubType($attackID);
      return $subtype == "Sword" || $subtype == "Dagger";
    case "DYN082": case "DYN083": case "DYN084": return CardSubType($attackID) == "Axe";
		case "DYN085": case "DYN086": case "DYN087": return (CardSubType($attackID) == "Sword" || CardSubType($attackID) == "Dagger");
    case "DYN089-UNDER":
      $character = &GetPlayerCharacter($mainPlayer);
      $index = FindCharacterIndex($mainPlayer, "DYN492a");
      return $attackID == "DYN492a" && $character[$index + 2] >= 1;
    case "DYN091-1": return $combatChainState[$CCS_IsBoosted];
    case "DYN154": return true;
    case "DYN155": return CardSubType($attackID) == "Arrow";
    case "DYN156": case "DYN157": case "DYN158": return true;
		case "DYN165": case "DYN166": case "DYN167": return true;
		case "DYN168": case "DYN169": case "DYN170": return CardSubType($attackID) == "Arrow";
    case "DYN176": case "DYN177": case "DYN178": return true;
		case "DYN185-BUFF": case "DYN186-BUFF": case "DYN187-BUFF": return ClassContains($attackID, "RUNEBLADE", $mainPlayer);
		case "DYN185-HIT": case "DYN186-HIT": case "DYN187-HIT": return true;
    default:
      return false;
  }
}

function DYNCardTalent($cardID)
{
  $number = intval(substr($cardID, 3));
  if($number <= 0) return "";
  else if($number >= 1 && $number <= 2) return "ROYAL,DRACONIC";
  else if($number >= 3 && $number <= 4) return "DRACONIC";
  else if($number == 66 || $number == 212 || $number == 612) return "LIGHT";
  else return "NONE";
}

function DYNPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
  global $currentPlayer, $CS_PlayIndex, $CS_NumContractsCompleted, $combatChainState, $CCS_NumBoosted, $CCS_CurrentAttackGainedGoAgain, $combatChain;
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
      if (AttackValue($additionalCosts) >= 6) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $rv .= "Discarded a 6 power card and gains +" . EffectAttackModifier($cardID) . " power.";
      }
      return $rv;
    case "DYN009":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN002":
      PutPermanentIntoPlay($currentPlayer, $cardID);
      return "";
    case "DYN003":
      PutPermanentIntoPlay($currentPlayer, $cardID);
      return "";
    case "DYN004":
      PutPermanentIntoPlay($currentPlayer, $cardID);
      return "";
    case "DYN016": case "DYN017": case "DYN018":
      if (AttackValue($additionalCosts) >= 6) {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        $rv .= "Discarded a 6 power card and gains go again.";
      }
      return $rv;
    case "DYN019": case "DYN020": case "DYN021":
      if(AttackValue($additionalCosts) >= 6) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $rv .= "Discarded a 6 power card and gains +" . EffectAttackModifier($cardID);
      }
      return $rv;
    case "DYN022": case "DYN023": case "DYN024":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $rv .= "Your next Brute attack this turn gains +" . EffectAttackModifier($cardID);
      return $rv;
    case "DYN028":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN030": case "DYN031": case "DYN032":
      if(!IsAllyAttacking()){
        $index = SearchCombatChainLink($currentPlayer, subtype:"Off-Hand", class:"GUARDIAN");
        if ($index != ""){
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
          AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Discard a card or take 1 damage");
          AddDecisionQueue("MAYCHOOSEHAND", $otherPlayer, "<-", 1);
          AddDecisionQueue("REMOVEMYHAND", $otherPlayer, "-", 1);
          AddDecisionQueue("DISCARDCARD", $otherPlayer, "HAND", 1);
          AddDecisionQueue("ELSE", $otherPlayer, "-");
          AddDecisionQueue("TAKEDAMAGE", $otherPlayer, 1, 1);
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
      AddDecisionQueue("REMOVENEGDEFCOUNTER", $currentPlayer, "-", 1);
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
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddPlayerHand("DYN065", $currentPlayer, "-");
      return "";
    case "DYN062": case "DYN063": case "DYN064":
      if($cardID == "DYN062") $amount = 3;
      else if($cardID == "DYN063") $amount = 2;
      else $amount = 1;
      for($i=0; $i < $amount; $i++) {
        BanishCardForPlayer("DYN065", $currentPlayer, "-", "TT", $currentPlayer);
      }
      return "";
    case "DYN068":
      if(IsWeaponGreaterThanTwiceBasePower()) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return CardLink($cardID, $cardID) . " gains overpower";
      }
      return "";
    case "DYN071":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN072":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=W;subtype=Sword");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a Sword to gain a +1 counter");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $currentPlayer, "GETCARDINDEX", 1);
      AddDecisionQueue("ADDEQUIPCOUNTER", $currentPlayer, "-", 1);
      return "";
    case "DYN076": case "DYN077": case "DYN078":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
		case "DYN082": case "DYN083": case "DYN084":
      if ($cardID == "DYN082") $amount = 3;
      else if ($cardID == "DYN083") $amount = 2;
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
        AddDecisionQueue("APPENDLASTRESULT", $otherPlayer, "-{0}");
        AddDecisionQueue("PREPENDLASTRESULT", $otherPlayer, "{0}-");
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
        AddDecisionQueue("ADDCARDTOCHAIN", $otherPlayer, "HAND", 1);
      }
      return "";
    case "DYN091":
      AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
      AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
      return "";
    case "DYN092":
      $hasHead = false; $hasChest = false; $hasArms = false; $hasLegs = false; $hasWeapon = false; $numHypers = 0;
      $char = &GetPlayerCharacter($currentPlayer);
      for($i=0; $i<count($char); $i+=CharacterPieces())
      {
        if($char[$i+1] == 0) continue;
        if(!ClassContains($char[$i], "MECHANOLOGIST", $currentPlayer)) continue;
        if(CardType($char[$i]) == "W") $hasWeapon = true;
        else {
          $subtype = CardSubType($char[$i]);
          switch($subtype)
          {
            case "Head": $hasHead = true; break;
            case "Chest": $hasChest = true; break;
            case "Arms": $hasArms = true; break;
            case "Legs": $hasLegs = true; break;
          }
        }
      }
      if(!$hasHead || !$hasChest || !$hasArms || !$hasLegs || !$hasWeapon) return "You do not meet the equipment requirement.";
      $numHypers = CountItem("ARC036", $currentPlayer);
      $numHypers += CountItem("DYN111", $currentPlayer);
      $numHypers += CountItem("DYN112", $currentPlayer);
      if($numHypers < 3) return "You do not meet the Hyper Driver requirement.";
      //Congrats, you have met the requirement to summon the mech! Let's remove the old stuff
      $mechMaterial = "";
      for($i=count($char)-1; $i>=CharacterPieces(); --$i) {
        if ($char[$i] == "DYN089") AddCurrentTurnEffect($char[$i] . "-UNDER", $currentPlayer);
        if($mechMaterial != "") $mechMaterial .= ",";
        $mechMaterial .= $char[$i];
        unset($char[$i]);
      }
      $char = array_values($char);
      $items = &GetItems($currentPlayer);
      $hyperToDestroy = 3;
      for($i=count($items)-ItemPieces(); $i>=0 && $hyperToDestroy>0; $i-=ItemPieces())
      {
        if($mechMaterial != "") $mechMaterial .= ",";
        $mechMaterial .= $items[$i];
        if($items[$i] == "ARC036" || $items[$i] == "DYN111" || $items[$i] == "DYN112") DestroyItemForPlayer($currentPlayer, $i);
        $hyperToDestroy--;
      }
      //Now add the new stuff
      PutCharacterIntoPlayForPlayer("DYN492a", $currentPlayer);//Weapon
      PutCharacterIntoPlayForPlayer("DYN492b", $currentPlayer);//Armor
      PutItemIntoPlayForPlayer("DYN492c", $currentPlayer);//Item
      return "";
    case "DYN095": case "DYN096": case "DYN097":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN123":
      if (GetClassState($currentPlayer, $CS_NumContractsCompleted) > 0) {
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
      AddDecisionQueue("WRITELOG", $currentPlayer, "Left the top card", 1);
      return "";
    case "DYN151":
      $deck = &GetDeck($currentPlayer);
      AddDecisionQueue("DECKCARDS", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      if(ArsenalFull($currentPlayer)) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Sandscour Greatbow shows you the top of your deck: <0>");
        AddDecisionQueue("OK", $currentPlayer, "whether to put an arrow in arsenal", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "NO");
        return "Your arsenal is full, so you cannot put an arrow in your arsenal.";
      }
      if(CardSubType($deck[0]) != "Arrow") {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Sandscour Greatbow shows you the top of your deck: <0>");
        AddDecisionQueue("OK", $currentPlayer, "whether to put an arrow in arsenal", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "NO");
      } else {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to put <0> in your arsenal", 1);
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_put_the_card_in_arsenal", 1);
      }
      AddDecisionQueue("SANDSCOURGREATBOW", $currentPlayer, "-");
      return "";
    case "DYN155":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN156": case "DYN157": case "DYN158":
      if(HasAimCounter()) AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN165": case "DYN166": case "DYN167":
      if(HasAimCounter()) AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN168": case "DYN169": case "DYN170":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "ARSENALUP");
      AddDecisionQueue("CHOOSEARSENAL", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDAIMCOUNTER", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDARSENALUNIQUEIDCURRENTEFFECT", $currentPlayer, $cardID . "," . "HAND", 1);
      return "";
    case "DYN171":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN172":
      $pitchArr = explode(",", $additionalCosts);
      $attackActionPitched = 0;
      $naaPitched = 0;
      for($i = 0; $i < count($pitchArr); ++$i) {
        if(CardType($pitchArr[$i]) == "A") $naaPitched = 1;
        if(CardType($pitchArr[$i]) == "AA") $attackActionPitched = 1;
      }
      Draw($currentPlayer);
      if($naaPitched && $attackActionPitched) {
        PlayAura("ARC112", $currentPlayer);
        $rv .= "Created a Runechant token";
      }
      return $rv;
    case "DYN173":
      $pitchArr = explode(",", $additionalCosts);
      $attackActionPitched = 0;
      $naaPitched = 0;
      for ($i = 0; $i < count($pitchArr); ++$i) {
        if (CardType($pitchArr[$i]) == "A") $naaPitched = 1;
        if (CardType($pitchArr[$i]) == "AA") $attackActionPitched = 1;
      }
      if($naaPitched && $attackActionPitched && IsHeroAttackTarget()) {
        AddCurrentTurnEffect($cardID, $currentPlayer, $from);
        return "The next time " . CardLink($cardID, $cardID) . "deals damage they discard a card and you draw a card";
      }
      return "";
    case "DYN174":
      $pitchArr = explode(",", $additionalCosts);
      $attackPitched = false;
      $nonAttackPitched = false;
      for($i = 0; $i < count($pitchArr); ++$i) {
        if(CardType($pitchArr[$i]) == "A") $nonAttackPitched = true;
        if(CardType($pitchArr[$i]) == "AA") $attackPitched = true;
      }
      $rv = "";
      if($attackPitched) {
        AddDecisionQueue("MULTIZONEINDICES", $otherPlayer, "MYALLY");
        AddDecisionQueue("CHOOSEMULTIZONE", $otherPlayer, "<-");
        AddDecisionQueue("MZDESTROY", $otherPlayer, "-", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYALLY");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-");
        AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
        $rv .= "Each hero chooses and destroys an ally they control";
      }
      if($nonAttackPitched) {
        AddDecisionQueue("MULTIZONEINDICES", $otherPlayer, "MYAURAS");
        AddDecisionQueue("CHOOSEMULTIZONE", $otherPlayer, "<-");
        AddDecisionQueue("MZDESTROY", $otherPlayer, "-", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-");
        AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
        if ($rv != "") $rv .= " and an aura they control";
        else $rv = "Each hero chooses and destroys an aura they control";
      }
      return $rv;
    case "DYN175":
      $numRunechants = DestroyAllThisAura($currentPlayer, "ARC112");
      $auras = &GetAuras($currentPlayer);
      $index = count($auras) - AuraPieces();
      $auras[$index+2] = $numRunechants;
      return "";
    case "DYN176": case "DYN177": case "DYN178":
      $pitchArr = explode(",", $additionalCosts);
      $attackActionPitched = 0;
      $naaPitched = 0;
      $rv = "";
      for ($i = 0; $i < count($pitchArr); ++$i) {
        if (CardType($pitchArr[$i]) == "A") $naaPitched = 1;
        if (CardType($pitchArr[$i]) == "AA") $attackActionPitched = 1;
      }
      if ($attackActionPitched) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $rv .= "Gain +2 power";
      }
      if ($naaPitched) {
        PlayAura("ARC112", $currentPlayer, 2, true);
        if ($rv != "") $rv .= " and ";
        $rv .= "creates 2 Runechant.";
      }
      return $rv;
		case "DYN182": case "DYN183": case "DYN184":
      $pitchArr = explode(",", $additionalCosts);
      $naaPitched = 0;
      for ($i = 0; $i < count($pitchArr); ++$i) {
        if (CardType($pitchArr[$i]) == "A") $naaPitched = 1;
      }
      if ($naaPitched) {
        DealArcane(1, 2, "PLAYCARD", $cardID);
      }
      return "";
		case "DYN185": case "DYN186": case "DYN187":
      if($cardID == "DYN185") $amount = 3;
      else if($cardID == "DYN186") $amount = 2;
      else $amount = 1;
      $pitchArr = explode(",", $additionalCosts);
      $attackActionPitched = 0;
      $rv = "The next Runeblade attack action card you play creates Runechants on-hit";
      for($i = 0; $i < count($pitchArr); ++$i) {
        if(CardType($pitchArr[$i]) == "AA") $attackActionPitched = 1;
      }
      AddCurrentTurnEffect($cardID . "-HIT", $currentPlayer);
      if($attackActionPitched) {
        AddCurrentTurnEffect($cardID . "-BUFF", $currentPlayer);
        $rv .= " and gains +1 power";
      }
      return $rv;
    case "DYN188": case "DYN189": case "DYN190":
      $deck = new Deck($currentPlayer);
      if($deck->Reveal(1)) {
        if(PitchValue($deck->Top()) == PitchValue($cardID)) {
          PlayAura("ARC112", $currentPlayer, 1, true);
        }
      }
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
  	case "DYN194":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "DYN195":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "DYN196":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN197": case "DYN198": case "DYN199":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "DYN203": case "DYN204": case "DYN205":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "DYN206": case "DYN207": case "DYN208":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "DYN209": case "DYN210": case "DYN211":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN212":
      Transform($currentPlayer, "MON104", "DYN612");
      return "";
    case "DYN215":
      return CardLink($cardID, $cardID) . " is a partially manual card. Name the card in chat and enforce play restriction";
    case "DYN221": case "DYN222": case "DYN223":
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      $auras = &GetAuras($currentPlayer);
      $uniqueID = $auras[count($auras) - AuraPieces() + 6];
      if($cardID == "DYN221") $maxCost = 3;
      else if($cardID == "DYN222") $maxCost = 2;
      else $maxCost = 1;
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRAURAS:maxCost=" . $maxCost);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZBANISH", $currentPlayer, "AURAS,DYN221-" . $uniqueID . "," . $currentPlayer, 1);
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
      if($deck->Reveal(1) && PitchValue($deck->Top()) == PitchValue($cardID)) PlayAura("MON104", $currentPlayer, 1, true);
      return "";
    case "DYN235":
      BottomDeck($currentPlayer, false, shouldDraw:true);
      return "";
    case "DYN240":
      $rv = "";
      if($from == "PLAY") {
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
        $rv = CardLink($cardID, $cardID) . " is a partially manual card. Name the card in chat and enforce play restriction.";
        if(IsRoyal($currentPlayer))
        {
          $rv .= CardLink($cardID, $cardID) . " revealed the opponent's hand.";
          $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
          AddDecisionQueue("REVEALHANDCARDS", $otherPlayer, "-", 1);
        }
      }
      return $rv;
    case "DYN241":
      $rv = "";
      if($from == "PLAY") {
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
        $item = (IsRoyal($currentPlayer) ? "DYN243": "CRU197");
        PutItemIntoPlayForPlayer($item, $currentPlayer);
        $rv = CardLink($cardID, $cardID) . " shuffled itself and created a " . CardLink($item, $item) . ".";
        $deck = &GetDeck($currentPlayer);
        array_push($deck, "DYN241");
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
      }
      return $rv;
    case "DYN242":
      if($from == "PLAY") {
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose any number of heroes");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Target_Opponent,Target_Both_Heroes,Target_Yourself,Target_No_Heroes");
        AddDecisionQueue("PLAYERTARGETEDABILITY", $currentPlayer, "IMPERIALWARHORN", 1);
      }
      return "";
    case "DYN243":
      $rv = "";
      if ($from == "PLAY") {
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
        $rv = "Draws a card";
        Draw($currentPlayer);
      }
      return $rv;
    case "DYN612":
      $mySoul = &GetSoul($currentPlayer);
      if (count($mySoul) > 0){
        AddDecisionQueue("FINDINDICES", $currentPlayer, "SOUL");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to banish");
        AddDecisionQueue("MAYCHOOSEMYSOUL", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVESOUL", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "SOUL,-", 1);
        AddDecisionQueue("THREATENARCANE", $currentPlayer, $cardID, 1);
      }
      return "";
    default:
      return "";
  }
}

function DYNHitEffect($cardID)
{
  global $mainPlayer, $defPlayer, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $CCS_DamageDealt, $CCS_NumBoosted;
  global $chainLinks, $combatChain;
  switch($cardID) {
    case "DYN047":
      if(ComboActive())
      {
        $numLinks = 0;
        for($i = 0; $i < count($chainLinks); ++$i) {
          if($chainLinks[$i][0] == "DYN065") ++$numLinks;
        }
        if(count($combatChain) > 0 && $combatChain[0] == "DYN065") ++$numLinks;
        for($i=0; $i < $numLinks; $i++) {
          BanishCardForPlayer("DYN065", $mainPlayer, "-", "TT", $mainPlayer);
        }
      }
      break;
    case "DYN050": case "DYN051": case "DYN052":
      BanishCardForPlayer("DYN065", $mainPlayer, "-", "TT", $mainPlayer);
      break;
    case "DYN067":
      if(IsHeroAttackTarget() && !SearchAuras("DYN246", $mainPlayer)) {
        PlayAura("DYN246", $mainPlayer);
      }
      break;
    case "DYN107": case "DYN108": case "DYN109":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYHAND:subtype=Item;class=MECHANOLOGIST;maxCost=" . $combatChainState[$CCS_NumBoosted]);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an item to put into play");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("SETDQVAR", $mainPlayer, "0", 1);
      AddDecisionQueue("MZOP", $mainPlayer, "GETCARDID", 1);
      AddDecisionQueue("PUTPLAY", $mainPlayer, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $mainPlayer, "{0}", 1);
      AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      break;
    case "DYN115": case "DYN116":
      if(IsHeroAttackTarget()) {
        AddCurrentTurnEffect($cardID, $defPlayer);
      }
      break;
    case "DYN117":
      if(IsHeroAttackTarget()) {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        WriteLog(CardLink($cardID, $cardID) . " gives the current Assassin attack go again.");
      }
      break;
    case "DYN118":
      if(IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) WriteLog("The opponent deck is already... depleted.");
        $cardToBanish = array_shift($deck);
        BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
        WriteLog(CardLink($cardToBanish, $cardToBanish) . " was banished.");
      }
      break;
    case "DYN119":
      if(IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if(count($deck) == 0) { WriteLog("The opponent deck is already... depleted."); break; }
        $cardsName = "";
        for($i = 0; $i < $combatChainState[$CCS_DamageDealt]; ++$i) {
          if (count($deck) == 0) break;
          $cardToBanish = array_shift($deck);
          BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
          if ($cardsName != "") $cardsName .= ", ";
          $cardsName .= CardLink($cardToBanish, $cardToBanish);
        }
        if($cardsName != "") WriteLog(CardLink($cardID, $cardID) . " Banished the following cards: " . $cardsName);
      }
      break;
    case "DYN121":
      if(IsHeroAttackTarget() && IsRoyal($defPlayer)) {
        PlayerLoseHealth($defPlayer, GetHealth($defPlayer));
      }
      break;
    case "DYN120":
      if(IsHeroAttackTarget()) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to banish", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $mainPlayer, "ARS,-," . $mainPlayer, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) { WriteLog("The opponent deck is already... depleted."); break; }
        $cardToBanish = array_shift($deck);
        BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
        WriteLog(CardLink($cardToBanish, $cardToBanish) . " was banished");
      }
      break;
    case "DYN122":
      if(IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if(count($deck) == 0) WriteLog("The opponent deck is already... depleted.");
        else {
          $cardToBanish = array_shift($deck);
          BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
          WriteLog(CardLink($cardToBanish, $cardToBanish) . " was banished.");
        }
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to banish", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $mainPlayer, "HAND,-," . $mainPlayer, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "DYN124": case "DYN125": case "DYN126":
    case "DYN127": case "DYN128": case "DYN129":
    case "DYN133": case "DYN134": case "DYN135":
    case "DYN136": case "DYN137": case "DYN138":
    case "DYN139": case "DYN140": case "DYN141":
    case "DYN142": case "DYN143": case "DYN144":
    case "DYN145": case "DYN146": case "DYN147":
      if(IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) { WriteLog("The opponent deck is already... depleted."); break; }
        $cardToBanish = array_shift($deck);
        BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
        WriteLog(CardLink($cardToBanish, $cardToBanish) . " was banished.");
      }
      break;
    case "DYN153":
      AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
      break;
    case "DYN154":
      if(HasAimCounter() && IsHeroAttackTarget()) {
        AddNextTurnEffect($cardID, $defPlayer);
      }
      break;
    case "DYN156": case "DYN157": case "DYN158":
      if(IsHeroAttackTarget()){
        AddDecisionQueue("FINDINDICES", $defPlayer, "EQUIP");
        AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
        AddDecisionQueue("ADDNEGDEFCOUNTER", $defPlayer, "-", 1);
      }
      break;
    case "DYN162": case "DYN163": case "DYN164":
      if(HasAimCounter() && IsHeroAttackTarget()) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to destroy", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZDISCARD", $mainPlayer, "ARS,-," . $mainPlayer, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    default: break;
  }
}

function IsRoyal($player)
{
  $mainCharacter = &GetPlayerCharacter($player);

  if(SearchCharacterForCard($player, "DYN234")) return true;

  switch ($mainCharacter[0]) {
    case "DYN001": return true;
    default: break;
  }
  return false;
}

function HasSurge($cardID)
{
  switch($cardID) {
		case "DYN194": return true;
		case "DYN195": return true;
    case "DYN197": case "DYN198": case "DYN199": return true;
    case "DYN203": case "DYN204": case "DYN205": return true;
    case "DYN206": case "DYN207": case "DYN208": return true;
    default: return false;
  }
}

function HasEphemeral($cardID)
{
  switch($cardID) {
    case "DYN065": return true;
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
    default: return "";
  }
}

function ContractCompleted($player, $cardID)
{
  global $CS_NumContractsCompleted;
  WriteLog("Player " . $player . " completed the contract for " . CardLink($cardID, $cardID) . ".");
  IncrementClassState($player, $CS_NumContractsCompleted);
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
      PutItemIntoPlayForPlayer("EVR195", $player);
      break;
    default: break;
  }
}

function CheckContracts($banishedBy, $cardBanished)
{
  global $combatChain, $chainLinks;
  for($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    if($combatChain[$i + 1] != $banishedBy) continue;
    $contractType = ContractType($combatChain[$i]);
    if(CheckContract($contractType, $cardBanished)) ContractCompleted($banishedBy, $combatChain[$i]);
  }
  for($i = 0; $i < count($chainLinks); ++$i) {
    for($j = 0; $j < count($chainLinks[$i]); $j += ChainLinksPieces()) {
      if($chainLinks[$i][$j + 1] != $banishedBy) continue;
      if($chainLinks[$i][$j + 2] == 0) continue;
      $contractType = ContractType($chainLinks[$i][$j]);
      if(CheckContract($contractType, $cardBanished)) ContractCompleted($banishedBy, $chainLinks[$i][$j]);
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
    case "REDPITCH": if(PitchValue($cardBanished) == 1) return true;
    case "YELLOWPITCH": if(PitchValue($cardBanished) == 2) return true;
    case "BLUEPITCH": if(PitchValue($cardBanished) == 3) return true;
    case "COST1ORLESS": if(CardCost($cardBanished) <= 1) return true;
    case "COST2ORMORE": if(CardCost($cardBanished) >= 2) return true;
    case "AA": if(CardType($cardBanished) == "AA") return true;
    case "GOAGAIN": if(HasGoAgain($cardBanished)) return true;
    case "NAA": if(CardType($cardBanished) == "A") return true;
    case "BLOCK2ORLESS": if(BlockValue($cardBanished) <= 2 && BlockValue($cardBanished) >= 0) return true;
    case "REACTIONS": if(CardType($cardBanished) == "AR" || CardType($cardBanished) == "DR") return true;
    default: return false;
    }
}
