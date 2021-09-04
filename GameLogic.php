<?php

include "CardLogic.php";
include "Search.php";

function PlayAbility($cardID, $from, $resourcesPaid)
{
  global $myPitch, $myHand, $myCharacter, $myDeck, $mainPlayer, $myHealth, $otherPlayer, $myClassState, $CS_NumBoosted, $combatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $theirHealth, $currentPlayer, $defPlayer, $theirHand, $actionPoints;
  global $myClassState, $theirClassState, $CS_AtksWWeapon, $CS_DamagePrevention, $CS_Num6PowDisc, $CCS_DamageDealt, $playerID, $myResources, $CCS_WeaponIndex, $CS_NextDamagePrevented, $CS_CharacterIndex, $CS_PlayIndex, $myItems;
  global $actionPoints;
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  if($set == "ARC")
  {
    switch($class)
    {
      case "MECHANOLOGIST": return ARCMechanologistPlayAbility($cardID, $from, $resourcesPaid);
      case "RANGER": return ARCRangerPlayAbility($cardID, $from, $resourcesPaid);
      case "RUNEBLADE": return ARCRunebladePlayAbility($cardID, $from, $resourcesPaid);
      case "WIZARD": return ARCWizardPlayAbility($cardID, $from, $resourcesPaid);
      case "GENERIC": return ARCGenericPlayAbility($cardID, $from, $resourcesPaid);
    }
  }
  else if($set == "MON")
  {
    switch($class)
    {
      case "BRUTE": return MONBrutePlayAbility($cardID, $from, $resourcesPaid);
      case "ILLUSIONIST": return MONIllusionistPlayAbility($cardID, $from, $resourcesPaid);
      case "RUNEBLADE": return MONRunebladePlayAbility($cardID, $from, $resourcesPaid);
      case "WARRIOR": return MONWarriorPlayAbility($cardID, $from, $resourcesPaid);
      case "GENERIC": return MONGenericPlayAbility($cardID, $from, $resourcesPaid);
      case "NONE": return MONTalentPlayAbility($cardID, $from, $resourcesPaid);
      default: return "";
    }
  }
  switch($cardID)
  {
    case "WTR054": case "WTR055": case "WTR056":
      if(CountPitch($myPitch, 3) >= 1) MyDrawCard();
      return CountPitch($myPitch, 3) . " cards in pitch.";
    case "WTR004":
      $roll = RollDie();
      $actionPoints += intval($roll/2);
      return "Scabskin Leathers rolled $roll and gained " . intval($roll/2) . " action points.";
    case "WTR005":
      $roll = RollDie();
      $myResources[0] += intval($roll/2);
      return "Barkbone Strapping rolled $roll and gained " . intval($roll/2) . " resources.";
    case "WTR006":
      DiscardRandom();
      Intimidate();
      return "Alpha Rampage discarded a random card from your hand and intimidated.";
    case "WTR007":
      $discarded = DiscardRandom();
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if(AttackValue($discarded) >= 6)
      {
        $drew = 1;
        MyDrawCard();
        MyDrawCard();
        if(!CurrentEffectPreventsGoAgain()) ++$actionPoints;//TODO: This is not strictly accurate, but good enough for now
      }
      return "Bloodrush Bellow discarded " . $discarded . ($drew == 1 ? ", " : ", and ") . "gave your Brute attacks this turn +2" . ($drew == 1 ? ", drew two cards, and gained Go Again." : ".");
    case "WTR008":
      $discarded = DiscardRandom();
      if(AttackValue($discarded) >= 6) { $damaged = DamageOtherPlayer(2) > 0; }
      return "Reckless Swing discarded a random card from your hand" . ($damaged ? " and did 2 damage." : ".");
    case "WTR009":
      $indices = GetIndices(count($myDeck));
      if($indices != "")
      {
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, $indices);
        AddDecisionQueue("ADDMYHAND", $currentPlayer, "-", 1);
      }
      AddDecisionQueue("SANDSKETCH", $currentPlayer, "-");
    case "WTR010":
      $roll = RollDie();
      $myClassState[$CS_DamagePrevention] += $roll;
      return "Bone Head Barrier prevents the next $roll damage that will be dealt to you this turn.";
    case "WTR011": case "WTR012": case "WTR013":
      $discarded = DiscardRandom();
      if(AttackValue($discarded) >= 6) { $drew = 1; $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; }
      return "Savage Feast discarded a random card from your hand" . ($drew ? " and gained Go Again." : ".");
    case "WTR014": case "WTR015": case "WTR016":
      $discarded = DiscardRandom();
      if(AttackValue($discarded) >= 6) { $drew = 1; MyDrawCard(); }
      return "Savage Feast discarded a random card from your hand" . ($drew ? " and drew a card." : ".");
    case "WTR017": case "WTR018": case "WTR019":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      Intimidate();
      return "Barraging Beatdown intimidates and gives the next Brute attack this turn +" . EffectAttackModifier($cardID) . ".";
    case "WTR020": case "WTR021": case "WTR022":
      DiscardRandom();
      return "Savage Swing discarded a random card from your hand.";
    case "WTR023": case "WTR024": case "WTR025":
      Intimidate();
      return "Pack Hunt intimidated.";
    case "WTR026": case "WTR027": case "WTR028":
      Intimidate();
      return "Smash Instinct intimidated.";
    case "WTR029": case "WTR030": case "WTR031":
      DiscardRandom();
      return "Wrecker Romp discarded a random card from your hand.";
    case "WTR032": case "WTR033": case "WTR034":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      Intimidate();
      return "Awakening Bellow intimidated.";
    case "WTR035": case "WTR036": case "WTR037":
      DiscardRandom();
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Primeval Bellow discarded a random card from your hand and gives the next Brute attack this turn +" . EffectAttackModifier($cardID) . ".";
    //Guardian
    case "WTR038": case "WTR039":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Bravo gives your action cards with cost 3 or greater Dominate.";
    case "WTR041":
      PlayMyAura("WTR075");
      return "Tectonic Plating created a Seismic Surge token.";
    case "WTR042":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Helm of Isen's Peak gives you +1 Intellect until end of turn.";
    case "WTR047":
        $AAs = SearchMyDeck("AA","",-1,-1,"GUARDIAN");
        if($AAs == "") return "No attack actions to find.";
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, $AAs);
        AddDecisionQueue("ADDMYHAND", $currentPlayer, "-", 1);
      return "Show Time! searched for a Guardian attack action card.";
    //Ninja
    case "WTR078":
      if(CountPitch($myPitch, 0, 0)) $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
      return "";
    case "WTR082":
      MyDrawCard();
      return "Ancentral Empowerment drew a card.";
    case "WTR085":
     $damage = DamageOtherPlayer($combatChainState[$CCS_DamageDealt]);
     return "Pounding Gail dealt an extra " . $damage . " damage.";
    case "WTR092": case "WTR093": case "WTR094":
      AddCurrentTurnEffect($cardID, $playerID);
      return "Flic Flak gives the next Combo card you block with this turn +2.";
    //Warrior
    case "WTR116":
      AddCurrentTurnEffect($cardID, $playerID);
      return "Braveforge Bracers gives your next weapon attack this turn +1.";
    case "WTR118":
      $s1 = "";
      $s2 = "";
      if(CardType($combatChain[0]) == "W")
      {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        $s1 = " gave your weapon attack Go Again";
      }
      if(RepriseActive())
      {
        MyDrawCard();
        $s2 = " drew a card";
      }
      return "Glint the Quicksilver" . $s1 . ($s1 != "" && $s2 != "" ? " and" : "") . $s2 . ".";
    case "WTR119": case "WTR122":
      $weapons = GetWeaponChoices();
      AddDecisionQueue("CHOOSECHARACTER", $mainPlayer, $weapons);
      AddDecisionQueue("ADDCHARACTEREFFECT", $mainPlayer, $cardID);
      return "";
    case "WTR120":
      if(RepriseActive())
      {
        $options = GetChainLinkCards($otherPlayer);
        AddDecisionQueue("CHOOSECOMBATCHAIN", $mainPlayer, $options);
        AddDecisionQueue("REMOVECOMBATCHAIN", $mainPlayer, "-");
        AddDecisionQueue("ADDTHEIRHAND", $mainPlayer, "-");
      }
      return "";
    case "WTR121":
      if(RepriseActive())
      {
        $ARs = SearchMyDeck("AR");
        AddDecisionQueue("CHOOSEDECK", $mainPlayer, $ARs);
        AddDecisionQueue("BANISH", $mainPlayer, "TCL");
      }
      return "";
    case "WTR123": case "WTR124": case "WTR125":
      if(CardType($combatChain[0]) != "W") return "Overpower did nothing, because this is not a weapon attack.";
      return "Overpower gave your weapon attack +" . AttackModifier($cardID) . ".";
    case "WTR126": case "WTR127": case "WTR128":
      $text = "Steelblade Shunt ";
      if(CardType($combatChain[0]) == "W")
      {
        DamageOtherPlayer(1);
        $text .= "DID";
      } else { $text .= "did NOT"; }
      $text .= " deal 1 damage to the attacking hero.";
      return $text;
    case "WTR129": case "WTR130": case "WTR131":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Warrior's Valor gives your next weapon attack +" . EffectAttackModifier($cardID) . " and if it hits, it gains Go Again";
    case "WTR132": case "WTR133": case "WTR134":
      if(CardType($combatChain[0]) != "W") return "Ironsong Response did nothing, because this is not a weapon attack.";
      return "Ironsong Response gave your weapon attack +" . AttackModifier($cardID) . ".";
    case "WTR135": case "WTR136": case "WTR137":
      $log = "Biting blade gave your weapon attack +" . AttackModifier($cardID);
      if(RepriseActive()) { ApplyEffectToEachWeapon($cardID); $log .= " and gives weapons you control +1 for the rest of the turn"; }
      return $log . ".";
    case "WTR138": case "WTR139": case "WTR140":
      if(RepriseActive())
      {
        MyDrawCard();
        AddDecisionQueue("HANDTOPBOTTOM", $mainPlayer, "");
      }
      return "Stroke of Foresight gave your weapon attack +" . AttackModifier($cardID) . ".";
    case "WTR141": case "WTR142": case "WTR143":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Sharpen Steel gave your next weapon attack +" . EffectAttackModifier($cardID) . ".";
    case "WTR144": case "WTR145": case "WTR146":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Driving Blade gives your next weapon attack +" . EffectAttackModifier($cardID) . " and Go Again.";
    case "WTR147": case "WTR148": case "WTR149":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Nature's Path Pilgrimage gives your next weapon attack +" . EffectAttackModifier($cardID) . " and a hit effect.";
    case "WTR150":
      $myResources[0] += 1;
      return "Fyendal's Spring Tunic added 1 resource.";
    case "WTR151":
      $indices = GetMyHandIndices();
      if($indices == "") return "";
      AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, count($myHand) . "-" . $indices);
      AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIADDDECK", $currentPlayer, "-", 1);
      AddDecisionQueue("SHUFFLEMYDECK", $mainPlayer, "-", 1);
      AddDecisionQueue("HELMHOPEMERCHANT", $currentPlayer, "-", 1);
      return "";
    case "WTR152":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Heartened Cross Strap reduces the resource cost of your next attack action card by 2.";
    case "WTR154":
      $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
      return "Snapdragon Scalers gave your current attack Go Again.";
    case "WTR159":
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Draw_a_card,2_Attack,Go_again");
      AddDecisionQueue("ESTRIKE", $currentPlayer, "-", 1);
      return "";
    case "WTR160":
      MyDrawCard();
      MyDrawCard();
      if($from == "ARS") $myHealth += count($myHand);
      return "Tome of Fyendal drew two cards" . ($from == "ARS" ? " and gained " . count($myHand) . " life" : "") . ".";
    case "WTR161":
      if(count($myDeck) == 0) {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        AddCurrentTurnEffect($cardID, $mainPlayer);
        $return = "Last Ditch Effort gains Go Again and +4.";
      }
      return $return;
    case "WTR163":
      $actions = SearchMyDiscard("A");
      $attackActions = SearchMyDiscard("AA");
      if($actions == "") $actions = $attackActions;
      else if($attackActions != "") $actions = $actions . "," . $attackActions;
      if($actions == "") return "";
      AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "3-" . $actions);
      AddDecisionQueue("REMEMBRANCE", $currentPlayer, "-", 1);
      return "";
    case "WTR173": $myHealth += 3; return "Sigil of Solace gained 3 health.";
    case "WTR174": $myHealth += 2; return "Sigil of Solace gained 2 health.";
    case "WTR175": $myHealth += 1; return "Sigil of Solace gained 1 health.";
    case "WTR182": case "WTR183": case "WTR184":
      PlayMyAura("WTR225");
      return "Flight of the Feather Walkers created a Quicken token.";
    case "WTR185": case "WTR186": case "WTR187":
      $indices = SearchMyDiscardForCard("WTR218", "WTR219", "WTR220");
      if($indices == "") { return "No Nimblism to banish."; }
      AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, $indices);
      AddDecisionQueue("REMOVEMYDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("BANISH", $currentPlayer, "DISCARD", 1);
      AddDecisionQueue("NIMBLESTRIKE", $currentPlayer, "-", 1);
      return "";
    case "WTR191": case "WTR192": case "WTR193":
      if(IHaveLessHealth()) { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $ret = "Scar for a Scar gained Go Again."; }
      return $ret;
    case "WTR194": case "WTR195": case "WTR196":
      BottomDeckDraw();
      if($from == "ARS") { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $ret = "Scour the Battlescape gained Go Again."; }
      return $ret;
    case "WTR197": case "WTR198": case "WTR199":
      $indices = SearchMyDiscardForCard("WTR221", "WTR222", "WTR223");
      if($indices == "") { return "No Sloggism to banish."; }
      AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, $indices);
      AddDecisionQueue("REMOVEMYDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("BANISH", $currentPlayer, "DISCARD", 1);
      AddDecisionQueue("SLOGGISM", $currentPlayer, "-", 1);
    case "WTR200": case "WTR201": case "WTR202":
      if(IHaveLessHealth()) { AddCurrentTurnEffect($cardID, $mainPlayer); $ret = "Wounded Bull gains +1."; }
      return $ret;
    case "WTR215": case "WTR216": case "WTR217":
      BottomDeckDraw();
      return "";
    case "WTR218": case "WTR219": case "WTR220":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Nimblism gives the next attack action card with cost 1 or less this turn +" . EffectAttackModifier($cardID) . ".";
    case "WTR221": case "WTR222": case "WTR223"://Sloggism
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Sloggism gives the next attack action card with cost greater than 2 this turn +" . EffectAttackModifier($cardID) . ".";
    case "WTR153":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Goliath Gauntlet gives your next attack action card with cost 2 or greater +" . EffectAttackModifier($cardID) . ".";
    //CRU Brute
    case "CRU004": case "CRU005":
      if($myClassState[$CS_Num6PowDisc] > 0)
      {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        $rv = "Mandible Claw gained Go Again.";
      }
      return $rv;
    case "CRU006":
      MyDrawCard();
      $discarded = DiscardRandom();
      return "Skullhorn discarded " . $discarded . ".";
    case "CRU010": case "CRU011": case "CRU012":
      $discarded = DiscardRandom();
      return "Barraging Bighorn discarded " . $discarded . ".";
    case "CRU013": case "CRU014": case "CRU015":
      if($myClassState[$CS_Num6PowDisc] > 0)
      {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $rv = "Predatory Assault gained Dominate.";
      }
      return $rv;
    case "CRU019": case "CRU020": case "CRU021":
      $discarded = DiscardRandom();
      return "Swing Fist, Think Later discarded " . $discarded . ".";
    //CRU Guardian
    case "CRU025":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Crater Fist gives your Crush attacks +2 this turn.";
    case "CRU028":
      if(CountPitch($myPitch, 3) >= 2) { AddCurrentTurnEffect($cardID, $currentPlayer); $rv = "Stamp Authority gives you +1 intellect until end of turn."; }
      return $rv;
    case "CRU041": case "CRU042": case "CRU043":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Blessing of Serenity will prevent some of the next combat damage you take this turn.";
    //CRU Warrior
    case "CRU081":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Courage of Bladehold reduces the cost of your weapon attacks by 1 this turn.";
    case "CRU082":
      if($myCharacter[$combatChainState[$CCS_WeaponIndex]+1] != 0) { $myCharacter[$combatChainState[$CCS_WeaponIndex]+1] = 2; }
      return "Twinning Blade allows you to attack with target sword an additional time.";
    case "CRU083":
      if(RepriseActive()) UnifiedDecreePlayEffect();
      return "Unified Decree gave your weapon attack +" . AttackModifier($cardID) . " and looks for an attack reaction.";
    case "CRU084":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Spoils of War gives your next weapon attack +2 and go again.";
    case "CRU085": case "CRU086": case "CRU087":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      AddCurrentTurnEffect($cardID . "-2", $otherPlayer);
      return "Dauntless gives your next weapon attack  +" . EffectAttackModifier($cardID . "-1") . " and makes the next Defense Reaction cost +1 to play.";
    case "CRU088": case "CRU089": case "CRU090":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
      return "Out for Blood gave your weapon attack +" . EffectAttackModifier($cardID . "-1") . RepriseActive() ? " and gives your next attack +1." : ".";
    case "CRU091": case "CRU092": case "CRU093":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      $atkWWpn = $myClassState[$CS_AtksWWeapon] > 0;
      if($atkWWpn) AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
      return "Hit and Run gives your next weapon attack Go Again" . ($atkWWpn ? " and +" . EffectAttackModifier($cardID . "-2") : "") . ".";
    case "CRU094": case "CRU095": case "CRU096":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      $atkWWpn = $myClassState[$CS_AtksWWeapon] > 0;
      if($atkWWpn) AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
      return "Push Forward gives your next weapon attack +" . EffectAttackModifier($cardID . "-1") . ($atkWWpn ? " and gives your next attack Dominate." : ".");
    //CRU Mechanologist
    case "CRU101":
      $myCharacter[$myClassState[$CS_CharacterIndex] + 2] = ($myCharacter[$myClassState[$CS_CharacterIndex] + 2] == 0 ? 1 : 0);
      return "";
    case "CRU102":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU105":
      $index = $myClassState[$CS_PlayIndex];
      if($index != -1)
      {
        $myItems[$index + 1] = ($myItems[$index + 1] == 0 ? 1 : 0);
        if($myItems[$index + 1] == 0)
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
          AddDecisionQueue("CHOOSECHARACTER", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDCHARACTEREFFECT", $currentPlayer, $cardID, 1);
          $myItems[$index + 2] = 1;
          $rv = "Plasma Purifier gave target pistol +1.";
        }
        else
        {
          $rv = "Plasma Purifier gained a steam counter.";
        }
      }
      return $rv;
    case "CRU115": case "CRU116": case "CRU117":
      if($cardID == "CRU115") $maxCost = 2;
      else if($cardID == "CRU116") $maxCost = 1;
      else if($cardID == "CRU117") $maxCost = 0;
      Opt($cardID, $myClassState[$CS_NumBoosted]);
      AddDecisionQueue("DECKCARDS", $currentPlayer, "0");
      AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
      AddDecisionQueue("ALLCARDSUBTYPEORPASS", $currentPlayer, "Item", 1);
      AddDecisionQueue("ALLCARDMAXCOSTORPASS", $currentPlayer, $maxCost, 1);
      AddDecisionQueue("FINDINDICES", $currentPlayer, "TOPDECK", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
      return "Teklovossen's Workshop lets you opt and put an item from the top of your deck into play.";
    //CRU Ranger
    case "CRU121":
      if($myArsenal != "") return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
      AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
      AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDMYARSENALFACEUP", $currentPlayer, "HAND", 1);
      return "";
    case "CRU122":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Perch Grapplers gives face up arrow attacks Go Again this turn.";
    case "CRU124":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      Reload();
      return "Poison the Tips makes arrow attacks discard on hit, and allows you to Reload.";
    case "CRU125":
      SetClassState($currentPlayer, $CS_NextDamagePrevented, 1);
      return "Feign Death prevents the next damage you would take.";
    case "CRU126":
      AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_pay_1_to_allow_hit_effects_this_chain_link", 1, 1);
      AddDecisionQueue("FINDRESOURCECOST", $otherPlayer, $cardID, 1);
      AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
      AddDecisionQueue("TRIPWIRETRAP", $otherPlayer, "-", 1);
      return "";
    case "CRU127":
      AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_pay_1_to_allow_hit_effects_this_chain_link", 1, 1);
      AddDecisionQueue("FINDRESOURCECOST", $otherPlayer, $cardID, 1);
      AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
      AddDecisionQueue("PITFALLTRAP", $otherPlayer, "-", 1);
      return "";
    case "CRU128":
      AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_pay_1_to_allow_hit_effects_this_chain_link", 1, 1);
      AddDecisionQueue("FINDRESOURCECOST", $otherPlayer, $cardID, 1);
      AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
      AddDecisionQueue("ROCKSLIDETRAP", $otherPlayer, "-", 1);
      return "";
    case "CRU135": case "CRU136": case "CRU137":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect($cardID . "-1", $otherPlayer);
      return "Increase the tension gives the next arrow attack this turn +" . EffectAttackModifier($cardID) . " and prevents defense reactions on that chain link.";
    //CRU Generics
    case "CRU181":
      $count = SearchCount(CombineSearches(SearchMyDiscardForCard("CRU181"), SearchTheirDiscardForCard("CRU181")));
      for($i=0; $i<$count; ++$i) { MyDrawCard(); }
      return "Gorganian Tome drew " . $count . " cards.";
    case "CRU182":
      AddCurrentTurnEffect("CRU182", $otherPlayer);
      return "Snag made attack actions unable to gain attack.";
    case "CRU183": case "CRU184": case "CRU185":
      if($from == "ARS") { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $ret = "Promise of Plenty gained Go Again."; }
      return $ret;
    case "CRU188":
      MyDrawCard();
      MyDrawCard();
      return "Cash In drew two cards.";
    case "CRU189": case "CRU190": case "CRU191":
       $options = GetChainLinkCards($defPlayer, "AA");
       if($options == "") return "No defending attack action cards.";
       AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, $options);
       AddDecisionQueue("COMBATCHAINBUFFDEFENSE", $currentPlayer, PlayBlockModifier($cardID), 1);
       return "";
    default:
      break;
  }
}

function ProcessHitEffect($cardID)
{
  WriteLog("Processing hit effect for " . $cardID);
  global $combatChain,$defArsenal, $mainClassState, $CS_HitsWDawnblade, $combatChainState, $CCS_WeaponIndex, $mainPlayer, $mainCharacter, $defCharacter, $mainArsenal, $CCS_ChainLinkHitEffectsPrevented, $CCS_NextBoostBuff;
  global $defPlayer;
  $attackID = $combatChain[0];
  if(SearchMainAuras("CRU028") && CardType($cardID) == "AA") return;
  if($combatChainState[$CCS_ChainLinkHitEffectsPrevented]) return;
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  if($set == "ARC")
  {
    switch($class)
    {
      case "MECHANOLOGIST": ARCMechanologistHitEffect($cardID); return;
      case "RANGER": ARCRangerHitEffect($cardID); return;
      case "RUNEBLADE": ARCRunebladeHitEffect($cardID); return;
      case "WIZARD": ARCWizardHitEffect($cardID); return;
      case "GENERIC": ARCGenericHitEffect($cardID); return;
    }
  }
  else if($set == "MON")
  {
    switch($class)
    {
      case "BRUTE": return MONBruteHitEffect($cardID);
      case "ILLUSIONIST": return MONIllusionistHitEffect($cardID);
      case "RUNEBLADE": return MONRunebladeHitEffect($cardID);
      case "WARRIOR": return MONWarriorHitEffect($cardID);
      case "GENERIC": return MONGenericHitEffect($cardID);
      case "NONE": return MONTalentHitEffect($cardID);
      default: return "";
    }
  }
  switch($cardID)
  {
    case "WTR083":
      if(ComboActive())
      {
        AddDecisionQueue("FINDINDICES", $mainPlayer, "WTR083");
        AddDecisionQueue("MULTICHOOSEDECK", $mainPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $mainPlayer, "-", 1);
        AddDecisionQueue("MULTIADDHAND", $mainPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-", 1);
      }
      break;
    case "WTR084":
      AddDecisionQueue("PASSPARAMETER", $mainPlayer, $cardID);
      AddDecisionQueue("ADDMAINHAND", $mainPlayer, "-");
      break;
    case "WTR110": case "WTR111": case "WTR112": if(ComboActive()) MainDrawCard(); break;
    case "WTR115":
     if($mainClassState[$CS_HitsWDawnblade] == 1 && $CCS_WeaponIndex < count($combatChainState)) { ++$mainCharacter[$combatChainState[$CCS_WeaponIndex]+3]; }
     ++$mainClassState[$CS_HitsWDawnblade];
    break;
    case "WTR167": case "WTR168": case "WTR169": MainDrawCard(); break;
    case "CRU106": case "CRU107": case "CRU108": AddCurrentTurnEffectFromCombat($cardID, $mainPlayer); break;
    case "CRU132": case "CRU133": case "CRU134": $defCharacter[1] = 3; break;
    case "WTR206": case "WTR207": case "WTR208": if(CardType($attackID) == "AA") PummelHit(); break;
    case "WTR209": case "WTR210": case "WTR211": if(CardType($attackID) == "AA") GiveAttackGoAgain(); break;
    case "CRU109": case "CRU110": case "CRU111": $combatChainState[$CCS_NextBoostBuff] += 3; break;
    case "CRU123": AddNextTurnEffect("CRU123-DMG", $defPlayer); break;
    case "CRU129": case "CRU130": case "CRU131":
      if($mainArsenal != "") return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
      AddDecisionQueue("FINDINDICES", $mainPlayer, "MAINHAND");
      AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $mainPlayer, "-", 1);
      AddDecisionQueue("ADDMYARSENALFACEDOWN", $mainPlayer, "HAND", 1);
      break;
    case "CRU180":
      AddDecisionQueue("MULTICHOOSETEXT", $mainPlayer, "3-Quicken_token,Draw_card,Gain_life");
      AddDecisionQueue("COAXCOMMOTION", $mainPlayer, "-", 1);
      break;
    case "CRU183": case "CRU184": case "CRU185":
      DefenderTopDeckToArsenal();
      MainTopDeckToArsenal();
      break;
    default: break;
  }
}

function CombatChainResolutionEffects($cardID, $player)
{
  global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $mainPlayer, $mainPitch;
  switch($cardID)
  {
    case "CRU010": case "CRU011": case "CRU012":
      if($player == $mainPlayer && NumCardsBlocking() < 2) $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; break;
    case "MON248": case "MON249": case "MON250":
      if(SearchHighestAttackDefended() < AttackValue($cardID)) $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; break;
    case "MON293": case "MON294": case "MON295":
      if(SearchPitchHighestAttack($mainPitch) > AttackValue($cardID)) $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; break;
    default: break;
  }
}

function HasCrush($cardID)
{
  global $combatChain, $mainPlayer, $defPlayer, $defCharacter;
  $attackID = $combatChain[0];
  switch($cardID)
  {
    case "WTR043": case "WTR044": case "WTR045":
    case "WTR057": case "WTR058": case "WTR059":
    case "WTR060": case "WTR061": case "WTR062":
    case "WTR063": case "WTR064": case "WTR065":
    case "WTR066": case "WTR067": case "WTR068":
    case "WTR050": case "WTR049": case "WTR048":
    case "CRU026": case "CRU027":
    case "CRU032": case "CRU033": case "CRU034":
    case "CRU035": case "CRU036": case "CRU037": return true;
    default: return false;
  }
}

function ProcessCrushEffect($cardID)
{
  global $combatChain, $mainPlayer, $defPlayer, $defCharacter;
  $attackID = $combatChain[0];
  switch($cardID)
  {
    case "WTR043": DefDiscardRandom(); DefDiscardRandom(); break;
    case "WTR044": AddNextTurnEffect($cardID, $defPlayer); break;
    case "WTR045": AddNextTurnEffect($cardID, $defPlayer); break;
    case "WTR057": case "WTR058": case "WTR059":
      $equipment = GetTheirEquipmentChoices();
      if($equipment == "") break;
      AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, $equipment);
      AddDecisionQueue("ADDTHEIRNEGDEFCOUNTER", $mainPlayer, "-", 1);
      break;
    case "WTR060": case "WTR061": case "WTR062": AddNextTurnEffect($cardID, $defPlayer); break;
    case "WTR063": case "WTR064": case "WTR065": $defCharacter[1] = 3; break;
    case "WTR066": case "WTR067": case "WTR068": AddNextTurnEffect($cardID, $defPlayer); break;
    case "WTR050": case "WTR049": case "WTR048": DefenderArsenalToBottomOfDeck(); break;
    case "CRU026":
      AddDecisionQueue("FINDINDICES", $mainPlayer, "CRU026");
      AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
      AddDecisionQueue("DESTROYTHEIRCHARACTER", $mainPlayer, "-", 1);
      break;
    case "CRU027": break;
    case "CRU032": case "CRU033": case "CRU034": AddNextTurnEffect("CRU032", $defPlayer); break;
    case "CRU035": case "CRU036": case "CRU037": AddNextTurnEffect("CRU035", $defPlayer); break;
    default: return;
  }
}

//NOTE: This happens at combat resolution, so can't use the my/their directly
function AttackModifier($cardID, $from="", $resourcesPaid=0, $repriseActive=-1)
{
  global $mainPlayer, $mainPitch, $mainClassState, $CS_Num6PowDisc, $combatChain, $combatChainState, $mainCharacter, $mainAuras, $CCS_WeaponIndex, $CCS_NumHits, $CS_CardsBanished, $CCS_HitsInRow, $CS_NumCharged, $CCS_NumBoosted;
  if($repriseActive == -1) $repriseActive = RepriseActive();
  switch($cardID)
  {
    case "WTR003": return $mainClassState[$CS_Num6PowDisc];
    case "WTR040": return CountPitch($mainPitch, 3) >= 2 ? 2 : 0;
    case "WTR081": return $resourcesPaid;
    case "WTR082": return 1;
    case "WTR083": if(ComboActive()) return 1;
    case "WTR084": if(ComboActive()) return 1;
    case "WTR086": case "WTR087": case "WTR088": if(ComboActive()) return $combatChainState[$CCS_NumHits];
    case "WTR089": case "WTR090": case "WTR091": if(ComboActive()) return 3;
    case "WTR095": case "WTR096": case "WTR097": if(ComboActive()) return 1;
    case "WTR104": case "WTR105": case "WTR106": if(ComboActive()) return 2;
    case "WTR110": case "WTR111": case "WTR112": if(ComboActive()) return 1;
    case "WTR115": return $mainCharacter[$combatChainState[$CCS_WeaponIndex]+3];
    case "WTR120": return 3;
    case "WTR121": return 1;
    case "WTR123": return $repriseActive ? 6 : 4;
    case "WTR124": return $repriseActive ? 5 : 3;
    case "WTR125": return $repriseActive ? 4 : 2;
    case "WTR132": return CardType($combatChain[0]) == "W" && $repriseActive ? 3 : 0;
    case "WTR133": return CardType($combatChain[0]) == "W" && $repriseActive ? 2 : 0;
    case "WTR134": return CardType($combatChain[0]) == "W" && $repriseActive ? 1 : 0;
    case "WTR135": return 3;
    case "WTR136": return 2;
    case "WTR137": return 1;
    case "WTR138": return 3;
    case "WTR139": return 2;
    case "WTR140": return 1;
    case "WTR176": case "WTR177": case "WTR178": return NumCardsBlocking() < 2 ? 1 : 0;
    case "WTR206": return 4;
    case "WTR207": return 3;
    case "WTR208": return 2;
    case "WTR209": return 3;
    case "WTR210": return 2;
    case "WTR211": return 1;
    case "ARC188": case "ARC189": case "ARC190": return $combatChainState[$CCS_HitsInRow] > 0 ? 2 : 0;
    case "CRU016": case "CRU017": case "CRU018": return $mainClassState[$CS_Num6PowDisc] > 0 ? 1 : 0;
    case "CRU083": return 3;
    case "CRU112": case "CRU113": case "CRU114": return $combatChainState[$CCS_NumBoosted];
    case "CRU186": return 1;
    case "MON031": return $mainClassState[$CS_NumCharged] > 0 ? 3 : 0;
    case "MON039": return $mainClassState[$CS_NumCharged] > 0 ? 3 : 0;
    case "MON040": return $mainClassState[$CS_NumCharged] > 0 ? 3 : 0;
    case "MON041": return $mainClassState[$CS_NumCharged] > 0 ? 3 : 0;
    case "MON057": return $mainClassState[$CS_NumCharged] > 0 ? 3 : 0;
    case "MON058": return $mainClassState[$CS_NumCharged] > 0 ? 2 : 0;
    case "MON059": return $mainClassState[$CS_NumCharged] > 0 ? 1 : 0;
    case "MON254": case "MON255": case "MON256": return $mainClassState[$CS_CardsBanished] > 0 ? 2 : 0;
    case "MON284": case "MON285": case "MON286": return NumCardsBlocking() < 2 ? 1 : 0;
    case "MON287": case "MON288": case "MON289": return NumCardsBlocking();
    case "MON290": case "MON291": case "MON292": return count($mainAuras) >= 1 ? 1 : 0;
    default: return 0;
  }
}

function EffectHitEffect($cardID)
{
  global $combatChainState, $CCS_GoesWhereAfterLinkResolves;
  switch($cardID)
  {
    case "WTR129": case "WTR130": case "WTR131": GiveAttackGoAgain(); break;
    case "WTR147": case "WTR148": case "WTR149": NaturesPathPilgrimageHit(); break;
    case "ARC170-1": case "ARC171-1": case "ARC172-1": MainDrawCard(); break;
    case "CRU124": PummelHit(); break;
    case "MON034": LuminaAscensionHit(); break;
    case "MON081": case "MON082": case "MON083": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL"; break;
    case "MON110": case "MON111": case "MON112": DuskPathPilgrimageHit(); break;
    case "MON299": case "MON300": case "MON301": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK"; break;
    default: break;
  }
}

function EffectAttackModifier($cardID)
{
  $set = CardSet($cardID);
  if($set == "ARC")
  {
    return ARCEffectAttackModifier($cardID);
  }
  else if($set == "CRU")
  {
    return CRUEffectAttackModifier($cardID);
  }
  else if($set == "MON")
  {
    return MONEffectAttackModifier($cardID);
  }
  switch($cardID)
  {
    case "AAPLUS1": return 1;
    case "AAPLUS2": return 2;
    case "AAPLUS3": return 3;
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

function EffectBlockModifier($cardID)
{
  switch($cardID)
  {
    default: return 0;
  }
}

function BlockModifier($cardID, $from, $resourcesPaid)
{
  global $myAuras, $defAuras, $defPlayer, $CS_CardsBanished, $mainPlayer;
  $blockModifier = 0;
  $cardType = CardType($cardID);
  if(SearchCurrentTurnEffects("ARC160-1", $defPlayer) && $cardType == "AA") $blockModifier += 1;
  for($i=0; $i<count($myAuras); $i+=2)
  {
    if($myAuras[$i] == "WTR072" && CardCost($cardID) >= 3) $blockModifier += 4;
    if($myAuras[$i] == "WTR073" && CardCost($cardID) >= 3) $blockModifier += 3;
    if($myAuras[$i] == "WTR074" && CardCost($cardID) >= 3) $blockModifier += 2;
    if($myAuras[$i] == "WTR046" && $cardType == "E") $blockModifier += 1;
  }
  switch($cardID)
  {
    case "WTR212": case "WTR213": case "WTR214": $blockModifier += $from == "ARS" ? 1 : 0; break;
    case "WTR051": case "WTR052": case "WTR053": $blockModifier += ($resourcesPaid == 6 ? 3 : 0); break;
    case "ARC150": $blockModifier += (DefHasLessHealth() ? 1 : 0); break;
    case "CRU187": $blockModifier += ($from == "ARS" ? 2 : 0); break;
    case "MON075": case "MON076": case "MON077": return GetClassState($mainPlayer, $CS_CardsBanished) >= 3 ? 2 : 0;
    case "MON290": case "MON291": case "MON292": return count($defAuras) >= 1 ? 1 : 0;
    default: break;
  }
  return $blockModifier;
}

function PlayBlockModifier($cardID)
{
  switch($cardID)
  {
    case "CRU189": return 4;
    case "CRU190": return 3;
    case "CRU191": return 2;
    default: return 0;
  }
}

function SelfCostModifier($cardID)
{
  global $myClassState, $CS_NumCharged;
  switch($cardID)
  {
    case "MON032":
      $modifier = -1 * (2 * $myClassState[$CS_NumCharged]);
      return $modifier;
    default: return 0;
  }
}

function CurrentEffectCostModifiers($cardID)
{
  global $currentTurnEffects, $currentPlayer;
  $costModifier = 0;
  for($i=count($currentTurnEffects)-2; $i>=0; $i-=2)
  {
    if($currentTurnEffects[$i+1] == $currentPlayer)
    {
      $remove = 0;
      switch($currentTurnEffects[$i])
      {
        case "WTR060": case "WTR061": case "WTR062": if(IsAction($cardID)) { $costModifier += 1; $remove = 1; } break;
        case "WTR075": if(CardClass($cardID) == "GUARDIAN" && CardType($cardID) == "AA") { $costModifier -= 1; $remove = 1; } break;
        case "CRU081": if(CardType($cardID) == "W" && CardSubType($cardID) == "Sword") { $costModifier -= 1; } break;
        case "CRU085-2": case "CRU086-2": case "CRU087-2": if(CardType($cardID) == "DR") {$costModifier += 1; $remove = 1; } break;
        case "WTR152": if(CardType($cardID) == "AA") {$costModifier -= 2; $remove = 1; } break;
        case "MON257": $costModifier -= 999; $remove = 1; break;
        case "ARC185": $costModifier -= 999; $remove = 1; break;
        case "ARC060": case "ARC061": case "ARC062": if(CardType($cardID) == "AA" || GetAbilityType($cardID) == "AA") {$costModifier += 1; $remove = 1; } break;
        default: break;
      }
      if($remove == 1)
      {
        unset($currentTurnEffects[$i+1]);
        unset($currentTurnEffects[$i]);
      }
    }
  }
  return $costModifier;
}

function CurrentEffectDamagePrevention($player, $type, $damage)
{
  global $currentTurnEffects;
  $prevention = 0;
  for($i=count($currentTurnEffects)-2; $i>=0 && $prevention < $damage; $i-=2)
  {
    if($currentTurnEffects[$i+1] == $player)
    {
      $effects = explode("-", $currentTurnEffects[$i]);
      $remove = 0;
      switch($effects[0])
      {
        case "PREVENT": $prevention += $effects[1]; $remove = 1; break;
        case "CRU041": if($type == "COMBAT") { $prevention += 3; $remove = 1; } break;
        case "CRU042": if($type == "COMBAT") { $prevention += 2; $remove = 1; } break;
        case "CRU043": if($type == "COMBAT") { $prevention += 1; $remove = 1; } break;
        default: break;
      }
      if($remove == 1)
      {
        unset($currentTurnEffects[$i+1]);
        unset($currentTurnEffects[$i]);
      }
    }
  }
  return $prevention;
}

function CurrentEffectPlayAbility($cardID)
{
  global $currentTurnEffects, $currentPlayer, $actionPoints;
  for($i=0; $i<count($currentTurnEffects); $i+=2)
  {
    if($currentTurnEffects[$i+1] == $currentPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "ARC209":
          $cardType = CardType($cardID); if(($cardType == "A" || $cardType == "AA") && CardCost($cardID) >= 0) { ++$actionPoints; $remove = 1; }
          break;
        case "ARC210":
          $cardType = CardType($cardID); if(($cardType == "A" || $cardType == "AA") && CardCost($cardID) >= 1) { ++$actionPoints; $remove = 1; }
          break;
        case "ARC211":
          $cardType = CardType($cardID); if(($cardType == "A" || $cardType == "AA") && CardCost($cardID) >= 2) { ++$actionPoints; $remove = 1; }
          break;
        default: break;
      }
      if($remove == 1)
      {
        unset($currentTurnEffects[$i+1]);
        unset($currentTurnEffects[$i]);
      }
    }
  }
  return false;
}

function CurrentEffectGrantsGoAgain()
{
  global $currentTurnEffects, $mainPlayer, $combatChain;
  for($i=0; $i<count($currentTurnEffects); $i+=2)
  {
    if($currentTurnEffects[$i+1] == $mainPlayer && IsCombatEffectActive($currentTurnEffects[$i]))
    {
      switch($currentTurnEffects[$i])
      {
        case "WTR144": case "WTR145": case "WTR146": return true;
        case "ARC047": return true;
        case "ARC160-3": return true;
        case "CRU084": return true;
        case "CRU091-1": case "CRU092-1": case "CRU093-1": return true;
        case "CRU094-2": case "CRU095-2": case "CRU096-2": return true;
        case "CRU122": return true;
        case "MON260-2": case "MON261-2": case "MON262-2": return true;
        default: break;
      }
    }
  }
  return false;
}

function CurrentEffectPreventsGoAgain()
{
  global $currentTurnEffects, $mainPlayer;
  for($i=0; $i<count($currentTurnEffects); $i+=2)
  {
    if($currentTurnEffects[$i+1] == $mainPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "WTR044": return true;
        default: break;
      }
    }
  }
  return false;
}

function CurrentEffectPreventsDefenseReaction($from)
{
  global $currentTurnEffects, $currentPlayer;
  for($i=0; $i<count($currentTurnEffects); $i+=2)
  {
    if($currentTurnEffects[$i+1] == $currentPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "CRU123": return $from == "ARS" && IsCombatEffectActive($currentTurnEffects[$i]);
        case "CRU135-1": case "CRU136-1": case "CRU137-1": return $from == "HAND" && IsCombatEffectActive($currentTurnEffects[$i]);
        default: break;
      }
    }
  }
  return false;
}

function CurrentEffectPreventsDraw($player)
{
  global $currentTurnEffects;
  for($i=0; $i<count($currentTurnEffects); $i+=2)
  {
    if($currentTurnEffects[$i+1] == $player)
    {
      switch($currentTurnEffects[$i])
      {
        case "WTR045": return true;
        default: break;
      }
    }
  }
  return false;
}

function CurrentEffectIntellectModifier()
{
  global $currentTurnEffects, $currentPlayer;
  $intellectModifier = 0;
  for($i=count($currentTurnEffects)-2; $i>=0; $i-=2)
  {
    if($currentTurnEffects[$i+1] == $currentPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "WTR042": $intellectModifier += 1; break;
        case "ARC161": $intellectModifier += 1; break;
        case "CRU028": $intellectModifier += 1; break;
        case "MON246": $intellectModifier += 1; break;
        default: break;
      }
    }
  }
  return $intellectModifier;
}

function IsCombatEffectActive($cardID)
{
  global $combatChain, $CS_ArsenalFacing, $mainPlayer;
  if(count($combatChain) == 0) return;
  $attackID = $combatChain[0];
  $set = CardSet($cardID);
  if($set == "ARC")
  {
    return ARCCombatEffectActive($cardID, $attackID);
  }
  else if($set == "CRU")
  {
    return CRUCombatEffectActive($cardID, $attackID);
  }
  else if($set == "MON")
  {
    return MONCombatEffectActive($cardID, $attackID);
  }
  switch($cardID)
  {
    case "AAPLUS1": case "AAPLUS2": case "AAPLUS3": return CardType($attackID) == "AA";
    case "WTR007": return CardClass($attackID) == "BRUTE";
    case "WTR017": case "WTR018": case "WTR019": return CardClass($attackID) == "BRUTE";
    case "WTR032": case "WTR033": case "WTR034": return CardType($attackID) == "AA" && CardClass($attackID) == "BRUTE";
    case "WTR035": case "WTR036": case "WTR037": return CardClass($attackID) == "BRUTE";
    //Guardian
    case "WTR038": case "WTR039": return CardType($attackID) == "AA" && CardCost($attackID) >= 3;//TODO: Make last the whole turn
    case "WTR066": case "WTR067": case "WTR068": return true;
    case "WTR069": case "WTR070": case "WTR071": return CardType($attackID) == "AA" && CardClass($attackID) == "GUARDIAN";
    case "WTR116": return CardType($attackID) == "W";
    case "WTR129": case "WTR130": case "WTR131": return CardType($attackID) == "W";
    case "WTR141": case "WTR142": case "WTR143": return CardType($attackID) == "W";
    case "WTR144": case "WTR145": case "WTR146": return CardType($attackID) == "W";
    case "WTR147": case "WTR148": case "WTR149": return CardType($attackID) == "W";
    case "WTR153": return CardType($attackID) == "AA" && CardCost($attackID) >= 2;
    case "WTR159": return true;
    case "WTR161": return true;
    case "WTR171": return true;
    case "WTR185": return true;
    case "WTR197": return true;
    case "WTR200": case "WTR201": case "WTR202": return true;
    case "WTR218": case "WTR219": case "WTR220": return CardType($attackID) == "AA" && CardCost($attackID) <= 1;
    case "WTR221": case "WTR222": case "WTR223": return CardType($attackID) == "AA" && CardCost($attackID) >= 2;
    default: return 0;
  }
}

function IsCombatEffectPersistent($cardID)
{
  switch($cardID)
  {
    case "WTR007": return true;
    case "ARC047": return true;
    case "ARC160-1": return true;
    case "CRU025": return true;
    case "CRU122": return true;
    case "CRU124": return true;
    case "MON034": return true;
    case "MON108": return true;
    case "MON109": return true;
    case "MON239": return true;
    default: return false;
  }
}

//NOTE: This happens at start of turn, so can't use the my/their directly
function AuraDestroyAbility($cardID)
{
  global $mainPlayer;
  switch($cardID)
  {
    case "WTR047": MainDrawCard(); return "Show Time! drew a card.";
    case "WTR054": return BlessingOfDeliveranceDestroy(3);
    case "WTR055": return BlessingOfDeliveranceDestroy(2);
    case "WTR056": return BlessingOfDeliveranceDestroy(1);
    case "WTR069": case "WTR070": case "WTR071": return EmergingPowerDestroy($cardID);
    case "WTR075": AddCurrentTurnEffect($cardID, $mainPlayer); return "Seismic Surge reduces the cost of the next Guardian attack action card you play this turn by 1.";
    case "CRU029": case "CRU030": case "CRU031": AddCurrentTurnEffect($cardID, $mainPlayer); return "Towering Titan gives your next Guardian Attack Action +" . EffectAttackModifier($cardID) . ".";
    case "CRU038": case "CRU039": case "CRU040": AddCurrentTurnEffect($cardID, $mainPlayer); return "Emerging Dominance gives your next Guardian Attack Action +" . EffectAttackModifier($cardID) . " and dominate.";
    default: return "";
  }
}

function AuraStartTurnAbilities()
{
  global $mainAuras;
  for($i=count($mainAuras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $dest = AuraDestroyAbility($mainAuras[$i]);
    WriteLog($dest);
    if($dest != "")
    {
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($mainAuras[$j]);
      }
      $mainAuras = array_values($mainAuras);
    }
  }
}

function AuraEndTurnAbilities()
{
  global $mainAuras, $mainClassState, $CS_NumNonAttackCards;
  for($i=count($mainAuras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    switch($mainAuras[$i])
    {
      case "ARC167": case "ARC168": case "ARC169": if($mainClassState[$CS_NumNonAttackCards] == 0) { $remove = 1; } break;
      default: break;
    }
    if($remove == 1)
    {
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($mainAuras[$j]);
      }
      $mainAuras = array_values($mainAuras);
    }
  }
}

function AuraTakeDamageAbilities(&$Auras, $damage)
{
  for($i=count($Auras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    if($damage <= 0) { $damage = 0; break; }
    switch($Auras[$i])
    {
      case "ARC167": $damage -= 4; $remove = 1; break;
      case "ARC168": $damage -= 3; $remove = 1; break;
      case "ARC169": $damage -= 2; $remove = 1; break;
      default: break;
    }
    if($remove == 1)
    {
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($Auras[$j]);
      }
      $Auras = array_values($Auras);
    }
  }
  return $damage;
}

function AuraAttackAbilities($attackID)
{
  global $myAuras, $combatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain;
  $attackType = CardType($attackID);
  for($i=count($myAuras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $remove = 0;
    switch($myAuras[$i])
    {
      case "WTR225": if($attackType == "AA" || $attackType == "W") 
        { WriteLog("Quicken grants Go Again."); $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $remove = 1; } break;
      default: break;
    }
    if($remove == 1)
    {
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($myAuras[$j]);
      }
      $myAuras = array_values($myAuras);
    }
  }
}

//NOTE: This happens at start of turn, so must use main player game state
function ItemStartTurnAbility($index)
{
  global $mainItems, $mainResources;
  switch($mainItems[$index])
  {
    case "ARC007":
      WriteLog("Teklo Core produced 2 resources.");
      --$mainItems[$index+1];
      $mainResources[0] += 2;
      if($mainItems[$index+1] <= 0) DestroyMainItem($index);
      break;
    case "ARC035":
      WriteLog("Dissipation Shield lost a steam counter to remain in play.");
      --$mainItems[$index+1];
      if($mainItems[$index+1] <= 0) DestroyMainItem($index);
      break;
    default:
      break;
  }
}

function CharacterStartTurnAbility($index)
{
  global $mainCharacter;
  switch($mainCharacter[$index])
  {
    case "WTR150":  if($mainCharacter[$index+2] < 3) ++$mainCharacter[$index+2]; break;
    default: break;
  }
}

function ItemActionAbility($index)
{
  global $playerID, $myItems, $myResources, $actionPoints;
  $cardID = $myItems[$index];
  switch($cardID)
  {
    case "WTR170":
      $myResources[0] += 2;
      DestroyMyItem($index);
      break;
    case "WTR171":
      AddCurrentTurnEffect($cardID, $playerID);
      DestroyMyItem($index);
      break;
    case "WTR172":
      $actionPoints += 2;
      DestroyMyItem($index);
      break;
    default:
      break;
  }
}

function PitchAbility($cardID)
{
  global $myHealth;
  switch($cardID)
  {
    case "WTR000":
        if(IHaveLessHealth()) ++$myHealth;
      break;
    case "ARC000":
        Opt($cardID, 2);
      break;
    default:
      break;
  }
}

function OnBlockEffects($index)
{
  global $currentTurnEffects, $combatChain, $currentPlayer, $combatChainState, $CCS_WeaponIndex, $otherPlayer;
  $cardType = CardType($combatChain[$index]);
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i >= 0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    if($currentTurnEffects[$i+1] == $currentPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "WTR092": case "WTR093": case "WTR094": if(HasCombo($index)) { $combatChain[$index+6] += 2; $remove = 1; } break;
        default: break;
      }
    }
    else if($currentTurnEffects[$i+1] == $otherPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "MON113": case "MON114": case "MON115":
          if($cardType == "AA" && IsCombatEffectActive($currentTurnEffects[$i]))
          {
            AddCharacterEffect($otherPlayer, $combatChainState[$CCS_WeaponIndex], $currentTurnEffects[$i]);
            WriteLog("The current weapon got +1 for the rest of the turn.");
          }
          break;
        default: break;
      }
    }
    if($remove == 1)
    {
      unset($currentTurnEffects[$i+1]);
      unset($currentTurnEffects[$i]);
    }
  }
  $currentTurnEffects = array_values($currentTurnEffects);
  switch($combatChain[0])
  {
    case "CRU079": case "CRU080":
      if($cardType == "AA")
      {
        AddCharacterEffect($otherPlayer, $combatChainState[$CCS_WeaponIndex], "CRU079");
        WriteLog("Cintari Saber got +1 for the rest of the turn.");
      }
      break;
    default: break;
  }
}

function DestroyMainItem($index)
{
  global $mainItems;
  unset($mainItems[$index]);
  unset($mainItems[$index+1]);
  unset($mainItems[$index+2]);
  $mainItems = array_values($mainItems);
}

function DestroyMyItem($index)
{
  global $myItems;
  unset($myItems[$index]);
  unset($myItems[$index+1]);
  unset($myItems[$index+2]);
  $myItems = array_values($myItems);
}

function CountPitch(&$pitch, $min=0, $max=9999)
{
  $pitchCount = 0;
  for($i=0; $i<count($pitch); ++$i)
  {
    $cost = CardCost($pitch[$i]);
    if($cost >= $min && $cost <= $max) ++$pitchCount;
  }
  return $pitchCount;
}

function MyDrawCard()
{
  global $myHand, $myDeck, $playerID;
  if(count($myDeck) == 0) return -1;
  if(CurrentEffectPreventsDraw($playerID)) return -1;
  array_push($myHand, array_shift($myDeck));
  return $myHand[count($myHand)-1];
}

function TheirDrawCard()
{
  global $theirHand, $theirDeck, $otherPlayer;
  if(count($theirDeck) == 0) return -1;
  if(CurrentEffectPreventsDraw($otherPlayer)) return -1;
  array_push($theirHand, array_shift($theirDeck));
  return $theirHand[count($theirHand)-1];
}

function MainDrawCard()
{
  global $mainHand, $mainDeck, $mainPlayer;
  if(count($mainDeck) == 0) return -1;
  if(CurrentEffectPreventsDraw($mainPlayer)) return -1;
  array_push($mainHand, array_shift($mainDeck));
  return $mainHand[count($mainHand)-1];
}

function NumNonEquipmentDefended()
{
  global $combatChain,$defPlayer;
  $number = 0;
  for($i=0; $i<count($combatChain); $i+=CombatChainPieces())
  {
    if($combatChain[$i+1] == $defPlayer && CardType($combatChain[$i]) != "E") ++$number;
  }
  return $number;
}

function MainCharacterEndTurnAbilities()
{
  global $mainCharacter, $characterPieces, $mainClassState, $CS_HitsWDawnblade, $CS_AtksWWeapon;
  for($i=0; $i<count($mainCharacter); $i += CharacterPieces())
  {
    switch($mainCharacter[$i])
    {
      case "WTR115": if($mainClassState[$CS_HitsWDawnblade] == 0) $mainCharacter[$i+3] = 0; break;
      case "MON107": if($mainClassState[$CS_AtksWWeapon] >= 2 && $mainCharacter[$i+4] < 0) ++$mainCharacter[$i+4]; break;
      default: break;
    }
  }
}

function MainCharacterHitAbilities()
{
  global $mainCharacter, $characterPieces, $combatChain, $combatChainState, $CCS_WeaponIndex, $CCS_HitsInRow, $mainPlayer;
  $attackID = $combatChain[0];
  for($i=0; $i<count($mainCharacter); $i += CharacterPieces())
  {
    if(CardType($mainCharacter[$i]) == "W" || $mainCharacter[$i+1] != "2") continue;
    switch($mainCharacter[$i])
    {
      case "WTR076": case "WTR077": KatsuHit($i); break;
      case "WTR079": if($combatChainState[$CCS_HitsInRow] == 3) { MainDrawCard(); $mainCharacter[$i+1] = 1; } break;
      case "WTR113": case "WTR114": if(CardType($attackID) == "W" && $mainCharacter[$combatChainState[$CCS_WeaponIndex]+1] != 0) { $mainCharacter[$i+1] = 1; $mainCharacter[$combatChainState[$CCS_WeaponIndex]+1] = 2; } break;
      case "WTR117":
        if(CardType($attackID) == "W")
        {
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_destroy_Refraction_Bolters_to_give_your_attack_Go_Again");
          AddDecisionQueue("REFRACTIONBOLTERS", $mainPlayer, $i, 1);
        }
        break;
      case "ARC152":
        if(CardType($attackID) == "AA")
        {
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_destroy_Vest_of_the_First_Fist_to_gain_2_resources");
          AddDecisionQueue("VESTOFTHEFIRSTFIST", $mainPlayer, $i, 1);
        }
        break;
      default: break;
    }
  }
}

function MainCharacterAttackModifiers()
{
  global $mainCharacterEffects, $mainCharacter, $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  $modifier = 0;
  for($i=0; $i<count($mainCharacterEffects); $i+=2)
  {
    if($mainCharacterEffects[$i] == $combatChainState[$CCS_WeaponIndex])
    {
      switch($mainCharacterEffects[$i+1])
      {
        case "WTR119": $modifier += 2; break;
        case "WTR122": $modifier += 1; break;
        case "WTR135": case "WTR136": case "WTR137": $modifier += 1; break;
        case "CRU079": $modifier += 1; break;
        case "CRU105": $modifier += 1; break;
        case "MON105": case "MON106": $modifier += 1; break;
        case "MON113": case "MON114": case "MON115": $modifier += 1; break;
        default: break;
      }
    }
  }
  for($i=0; $i<count($mainCharacter); $i+=CharacterPieces())
  {
    switch($mainCharacter[$i])
    {
      case "MON029": case "MON030": if(HaveCharged($mainPlayer) && NumAttacksBlocking() > 0) { $modifier +=1; } break;
      default: break;
    }
  }
  return $modifier;
}

function MainCharacterHitEffects()
{
  global $mainCharacterEffects, $mainCharacter, $combatChainState, $CCS_WeaponIndex, $characterPieces;
  $modifier = 0;
  for($i=0; $i<count($mainCharacterEffects); $i+=2)
  {
    if($mainCharacterEffects[$i] == $CCS_WeaponIndex * $characterPieces)
    {
      switch($mainCharacterEffects[$i+1])
      {
        case "WTR119": MainDrawCard(); break;
        default: break;
      }
    }
  }
  return $modifier;
}

function PutItemIntoPlay($item, $steamCounterModifier = 0)
{
  global $myItems, $currentPlayer;
  if(CardSubType($item) != "Item") return;
  array_push($myItems, $item);//Card ID
  array_push($myItems, ETASteamCounters($item) + SteamCounterLogic($item, $currentPlayer) + $steamCounterModifier);//Counters
  array_push($myItems, 2);//Status
}


function PutItemIntoPlayForPlayer($item, $player, $steamCounterModifier = 0)
{
  if(CardSubType($item) != "Item") return;
  $items = &GetItems($player);
  array_push($items, $item);//Card ID
  array_push($items, ETASteamCounters($item) + SteamCounterLogic($item, $player) + $steamCounterModifier);//Counters
  array_push($items, 2);//Status
}

function SteamCounterLogic($item, $playerID)
{
  global $CS_NumBoosted;
  switch($item)
  {
    case "CRU104": return GetClassState($playerID, $CS_NumBoosted);
    default: return 0;
  }
}

function IsDominateActive()
{
  global $currentTurnEffects, $playerID, $mainPlayer, $myCharacterEffects, $theirCharacterEffects, $CCS_WeaponIndex, $characterPieces, $combatChain;
  $characterEffects = $playerID == $mainPlayer ? $myCharacterEffects : $theirCharacterEffects;
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    if(IsCombatEffectActive($currentTurnEffects[$i]) && DoesEffectGrantDominate($currentTurnEffects[$i])) return true;
  }
  for($i=0; $i<count($characterEffects); $i+=2)
  {
    if($characterEffects[$i] == $CCS_WeaponIndex * $characterPieces)
    {
      switch($characterEffects[$i+1])
      {
        case "WTR122": return true;
        default: break;
      }
    }
  }
  switch($combatChain[0])
  {
    case "WTR095": case "WTR096": case "WTR097": if(ComboActive()) return true;
    case "WTR179": case "WTR180": case "WTR181": return true;
    case "MON246": return SearchMyDiscard("AA") == "";
    case "MON275": case "MON276": case "MON277": return true;
    default: break;
  }
  return false;
}

function EquipPayAdditionalCosts($cardIndex, $from)
{
  global $myCharacter;
  $cardID = $myCharacter[$cardIndex];
  switch($cardID)
  {
    case "WTR005":
      $myCharacter[$cardIndex+1] = 0;
      break;
    case "WTR042":
      $myCharacter[$cardIndex+1] = 0;
      break;
    case "WTR080":
      $myCharacter[$cardIndex+1] = 0;
      break;
    case "WTR150":
      $myCharacter[$cardIndex+2] -= 3;
      break;
    case "WTR151": case "WTR152": case "WTR153": case "WTR154":
      $myCharacter[$cardIndex+1] = 0;
      break;
    case "ARC003":
      $myCharacter[$cardIndex+1] = 2;
      break;
    case "ARC005": case "ARC042": case "ARC116": case "ARC117": case "ARC151": case "ARC153": case "ARC154":
      $myCharacter[$cardIndex+1] = 0;
      break;
    case "ARC113": case "ARC114":
      $myCharacter[$cardIndex+1] = 2;
      break;
    case "CRU006": case "CRU025": case "CRU102": case "CRU122":
      $myCharacter[$cardIndex+1] = 0;
      break;
    case "CRU101":
      if($myCharacter[$cardIndex+2] == 0) $myCharacter[$cardIndex+1] = 2;
      else
      {
        --$myCharacter[$cardIndex+5];
        if($myCharacter[$cardIndex+5] == 0) $myCharacter[$cardIndex+1] = 1;//By default, if it's used, set it to used
      }
      break;
    case "CRU177":
      $myCharacter[$cardIndex+1] = 1;
      ++$myCharacter[$cardIndex+2];
      break;
    case "MON061": case "MON108": case "MON238": case "MON239": case "MON240":
      $myCharacter[$cardIndex+1] = 0;
      break;
    case "MON029": case "MON030":
      $myCharacter[$cardIndex+1] = 2;//It's not limited to once
      break;
    default:
      --$myCharacter[$cardIndex+5];
      if($myCharacter[$cardIndex+5] == 0) $myCharacter[$cardIndex+1] = 1;//By default, if it's used, set it to used
      break;
  }
}

function DecisionQueueStaticEffect($phase, $player, $parameter, $lastResult)
{
  global $playerID, $myCharacter, $myHand, $myDeck, $myDiscard, $myBanish, $mySoul, $mainHand, $combatChain, $myCharacterEffects, $myPitch;
  global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $actionPoints, $myResources, $myHealth, $theirHealth, $myArsenal, $CCS_ChainAttackBuff;
  global $defCharacter, $myClassState, $CS_NumCharged, $theirCharacter, $theirHand, $otherPlayer, $CCS_ChainLinkHitEffectsPrevented;
  switch($phase)
  {
    case "FINALIZECHAINLINK":
      FinalizeChainLink();
      return "1";
    case "FINDRESOURCECOST":
      switch($parameter)
      {
        case "CRU126": case "CRU127": case "CRU128": return ($lastResult == "YES" ? 1 : 0);
      }
      return 0;
    case "FINDINDICES":
      $rv = "";
      UpdateGameState($playerID);
      BuildMainPlayerGamestate();
      switch($parameter)
      {
        case "WTR083": $rv = SearchMainDeckForCard("WTR081"); $rv = count(explode(",", $rv)) . "-" . $rv; break;
        case "WTR076-1": $rv = SearchMainHand("", "", 0); break;
        case "WTR076-2": $rv = GetComboCards(); break;
        case "WTR081": $rv = LordOfWindIndices(); $rv = count(explode(",", $rv)) . "-" . $rv; break;
        case "ARC014": $rv = SearchMyHand("", "Item", 2, -1, "MECHANOLOGIST"); break;
        case "ARC015": $rv = SearchMyHand("", "Item", 1, -1, "MECHANOLOGIST"); break;
        case "ARC016": $rv = SearchMyHand("", "Item", 0, -1, "MECHANOLOGIST"); break;
        case "ARC121": $rv = SearchDeck($player, "", "", $lastResult, -1, "WIZARD"); break;
        case "ARC138": case "ARC139": case "ARC140": $rv = SearchHand($player, "A", "", $lastResult, -1, "WIZARD"); break;
        case "ARC185": case "ARC186": case "ARC187": $rv = SearchMainDeckForCard("ARC212", "ARC213", "ARC214"); break;
        case "CRU026": $rv = SearchEquipNegCounter($defCharacter); break;
        case "CRU105": $rv = GetWeaponChoices("Pistol"); break;
        case "TOPDECK": $deck = &GetDeck($player); if(count($deck) > 0) $rv = "0"; break;
        case "MYHAND": $rv = GetIndices(count($myHand)); break;
        case "MYHANDAA": $rv = SearchMyHand("AA"); break;
        case "MYHANDARROW": $rv = SearchMyHand("", "Arrow"); break;
        case "MYDECKARROW": $rv = SearchMyDeck("", "Arrow"); break;
        case "MAINHAND": $rv = GetIndices(count($mainHand)); break;
        case "MAINDISCARDNAA": $rv = SearchMainDiscard("A"); break;
        case "MON033-1": $rv = GetIndices(count($mySoul), 1); break;
        case "MON033-2": $rv = CombineSearches(SearchMyDeck("A", "", $lastResult), SearchMyDeck("AA", "", $lastResult)); break;
        case "MON266-1": $rv = SearchMyHand("AA", "", -1, -1, 3); break;
        case "MON266-2": $rv = SearchMyDeckForCard("MON296", "MON297", "MON298"); break;
        case "MON303": $rv = SearchMyDiscard($type="AA", $subtype="", $maxCost=2); break;
        case "MON304": $rv = SearchMyDiscard($type="AA", $subtype="", $maxCost=1); break;
        case "MON305": $rv = SearchMyDiscard($type="AA", $subtype="", $maxCost=0); break;
        default: $rv = ""; break;
      }
      return ($rv == "" ? "-1" : $rv);
    case "PUTPLAY":
      if(CardSubtype($lastResult) == "Item")
      {
        PutItemIntoPlayForPlayer($lastResult, $player, ($parameter != "-" ? $parameter : 0));
      }
      return $lastResult;
    case "DRAW":
      return MyDrawCard();
    case "BANISH":
      BanishCard($myBanish, $myClassState, $lastResult, $parameter);
      return $lastResult;
    case "MULTIBANISH":
      $cards = explode(",", $lastResult);
      $params = explode(",", $parameter);
      for($i=0; $i<count($cards); ++$i)
      {
        BanishCardForPlayer($cards[$i], $player, $params[0], $params[1]);
      }
      return $lastResult;
    case "REMOVECOMBATCHAIN":
      $cardID = $combatChain[$lastResult];
      for($i=CombatChainPieces() - 1; $i >= 0; --$i)
      {
        unset($combatChain[$lastResult+$i]);
      }
      $combatChain = array_values($combatChain);
      return $cardID;
    case "COMBATCHAINBUFFDEFENSE":
      $combatChain[$lastResult+6] += $parameter;
      return $lastResult;
    case "REMOVEMYDISCARD":
      $cardID = $myDiscard[$lastResult];
      unset($myDiscard[$lastResult]);
      $myDiscard = array_values($myDiscard);
      return $cardID;
    case "REMOVEMYHAND":
      $cardID = $myHand[$lastResult];
      unset($myHand[$lastResult]);
      $myHand = array_values($myHand);
      return $cardID;
    case "MULTIREMOVEDISCARD":
      $cards = "";
      for($i=0; $i<count($lastResult); ++$i)
      {
        if($cards != "") $cards .= ",";
        $cards .= $myDiscard[$lastResult[$i]];
        unset($myDiscard[$lastResult[$i]]);
      }
      $myDiscard = array_values($myDiscard);
      return $cards;
    case "MULTIREMOVEMYSOUL":
      for($i=0; $i<$lastResult; ++$i) BanishFromSoul($player);
      return $lastResult;
    case "ADDTHEIRHAND":
      array_push($theirHand, $lastResult);
      return $lastResult;
    case "ADDMAINHAND":
      array_push($mainHand, $lastResult);
      return $lastResult;
    case "ADDMYHAND":
      array_push($myHand, $lastResult);
      return $lastResult;
    case "ADDMYPITCH":
      array_push($myPitch, $lastResult);
      return $lastResult;
    case "PITCHABILITY":
      PitchAbility($lastResult);
      return $lastResult;
    case "ADDMYARSENAL":
      AddMyArsenal($lastResult, $parameter, "DOWN");
      return $lastResult;
    case "ADDMYARSENALFACEUP":
      AddMyArsenal($lastResult, $parameter, "UP");
      return $lastResult;
    case "ADDMYARSENALFACEDOWN":
      AddMyArsenal($lastResult, $parameter, "DOWN");
      return $lastResult;
    case "MULTIADDHAND":
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        array_push($myHand, $cards[$i]);
      }
      return $lastResult;
    case "MULTIREMOVEHAND":
      $cards = "";
      for($i=0; $i<count($lastResult); ++$i)
      {
        if($cards != "") $cards .= ",";
        $cards .= $myHand[$lastResult[$i]];
        unset($myHand[$lastResult[$i]]);
      }
      $myHand = array_values($myHand);
      return $cards;
    case "DESTROYTHEIRCHARACTER":
      $theirCharacter[$lastResult+1] = 0;
      return $lastResult;
    case "ADDCHARACTEREFFECT":
      array_push($myCharacterEffects, $lastResult);
      array_push($myCharacterEffects, $parameter);
      return $lastResult;
    case "PASSPARAMETER":
      return $parameter;
    case "DISCARDMYHAND":
      $cardID = $myHand[$lastResult];
      unset($myHand[$lastResult]);
      $myHand = array_values($myHand);
      return $cardID;
    case "ADDBOTTOMMYDECK":
      array_push($myDeck, $lastResult);
      return $lastResult;
    case "MULTIADDDECK":
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        array_push($myDeck, $cards[$i]);
      }
      return $lastResult;
    case "MULTIADDTOPDECK":
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        array_unshift($myDeck, $cards[$i]);
      }
      return $lastResult;
    case "MULTIREMOVEDECK":
      $cards = "";
      $deck = &GetDeck($player);
      for($i=0; $i<count($lastResult); ++$i)
      {
        if($cards != "") $cards .= ",";
        $cards .= $deck[$lastResult[$i]];
        unset($deck[$lastResult[$i]]);
      }
      $deck = array_values($deck);
      return $cards;
    case "PARAMDELIMTOARRAY":
      return explode(",", $parameter);
    case "ADDSOUL":
      AddSoul($lastResult, $player, $parameter);
      return $lastResult;
    case "SHUFFLEDECK":
      //TODO: Implement
      return $lastResult;
    case "GIVEATTACKGOAGAIN":
      $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
      return $lastResult;
    case "EXHAUSTCHARACTER":
      $myCharacter[$parameter+1] = 1;
      return $parameter;
    case "REVEALMYCARD":
      WriteLog($myHand[$lastResult] . " was revealed.");
      return $lastResult;
    case "DECKCARDS":
      $indices = explode(",", $parameter);
      $deck = &GetDeck($player);
      for($i=0; $i<count($indices); ++$i)
      {
        if($rv != "") $return .= ",";
        $rv .= $deck[$i];
      }
      return $rv;
    case "REVEALCARD":
      WriteLog($lastResult . " was revealed.");
      return $lastResult;
    case "REVEALCARDS":
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        WriteLog($cards[$i] . " was revealed.");
      }
      return $lastResult;
    case "WRITECARDLOG":
      $message = implode(" ", explode("_", $parameter)) . $lastResult;
      WriteLog($message);
      return $lastResult;
    case "ADDTHEIRNEGDEFCOUNTER":
      $theirCharacter[$lastResult+4] -= 1;
      return "Added a negative defense counter.";
    case "ADDCURRENTEFFECT":
      AddCurrentTurnEffect($parameter, $player);
      return "1";
    case "OPTX":
      Opt("NA", $parameter);
      return $lastResult;
    case "NOPASS":
      if($lastResult == "NO") return "PASS";
      return 1;
    case "LESSTHANPASS":
      if($lastResult < $parameter) return "PASS";
      return $lastResult;
    case "ALLCARDTYPEORPASS":
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        if(CardType($cards[$i]) != $parameter) return "PASS";
      }
      return $lastResult;
    case "ALLCARDSUBTYPEORPASS":
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        if(CardSubtype($cards[$i]) != $parameter) return "PASS";
      }
      return $lastResult;
    case "ALLCARDTALENTORPASS":
WriteLog($lastResult . " " . CardTalent($lastResult) . " " . $parameter);
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        if(CardTalent($cards[$i]) != $parameter) return "PASS";
      }
      return $lastResult;
    case "ALLCARDMAXCOSTORPASS":
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        if(CardCost($cards[$i]) > $parameter) return "PASS";
      }
      return $lastResult;
    case "ALLCARDCLASSORPASS":
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        if(CardClass($cards[$i]) != $parameter) return "PASS";
      }
      return $lastResult;
    case "ESTRIKE":
      switch($lastResult)
      {
        case "Draw_a_card": return MyDrawCard();
        case "2_Attack": AddCurrentTurnEffect("WTR159", $player); return 1;
        case "Go_again": $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; return 2;
      }
      return $lastResult;
    case "NIMBLESTRIKE":
      AddCurrentTurnEffect("WTR185", $player);
      $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
      return "1";
    case "SLOGGISM":
      AddCurrentTurnEffect("WTR197", $player);
      return "1";
    case "SANDSKETCH":
      if(count($myHand) == 0) { WriteLog("No card for Sand Sketched Plan to discard."); return "1"; }
      $discarded = DiscardRandom();
      if(AttackValue($discarded) >= 6)
      {
        $ap = 1;
        $actionPoints += 2;
      }
      WriteLog("Sand Sketched Plan discarded " . $discarded . ($ap == 1 ? " and gained two action points." : "."));
      return "1";
    case "REMEMBRANCE":
      $cards = "";
      for($i=0; $i<count($lastResult); ++$i)
      {
        array_push($myDeck, $myDiscard[$lastResult[$i]]);
        if($cards != "") $cards .= ", ";
        if($i == count($lastResult) - 1) $cards .= "and ";
        $cards .= $myDiscard[$lastResult[$i]];
        unset($myDiscard[$lastResult[$i]]);
      }
      WriteLog("Remembrance shuffled back " . $cards . ".");
      $myDiscard = array_values($myDiscard);
      return "1";
    case "HELMHOPEMERCHANT":
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        MyDrawCard();
      }
      WriteLog("Helm of the Hope Merchant shuffled and drew " . count($cards) . " cards.");
      return "1";
    case "LORDOFWIND":
      $number = count(explode(",", $lastResult));
      $myResources[1] += $number;
      return $number;
    case "REFRACTIONBOLTERS":
      if($lastResult == "YES")
      {
        $myCharacter[$parameter+1] = 0;
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        WriteLog("Refraction Bolters was destroyed and gave the current attack Go Again.");
      }
      return $lastResult;
    case "TOMEOFAETHERWIND":
      for($i=0; $i<count($lastResult); ++$i)
      {
        switch($lastResult[$i])
        {
          case "Buff_Arcane": AddArcaneBonus(1, $player); break;
          case "Draw_card": MyDrawCard(); break;
          default: break;
        }
      }
      return $lastResult;
    case "COAXCOMMOTION":
      for($i=0; $i<count($lastResult); ++$i)
      {
        switch($lastResult[$i])
        {
          case "Quicken_token": PlayMyAura("WTR225"); PlayTheirAura("WTR225"); break;
          case "Draw_card": MyDrawCard(); TheirDrawCard(); break;
          case "Gain_life": PlayerGainHealth(1, $myHealth); PlayerGainHealth(1, $theirHealth); break;
          default: break;
        }
      }
      return $lastResult;
    case "CAPTAINSCALL":
      switch($lastResult)
      {
        case "2_Attack": AddCurrentTurnEffect($parameter . "-1", $player); return 1;
        case "Go_again": AddCurrentTurnEffect($parameter . "-2", $player); return 2;
      }
      return $lastResult;
    case "IRONHIDE":
      if($lastResult == 1)
      {
        $myCharacter[$parameter+1] = 0;
      }
      return $lastResult;
    case "ARTOFWAR":
      ArtOfWarResolvePlay($lastResult);
      return $lastResult;
    case "VESTOFTHEFIRSTFIST":
      if($lastResult == "YES")
      {
        $myCharacter[$parameter+1] = 0;
        $myResources[0] += 2;
        WriteLog("Vest of the First Fist was destroyed and gave 2 resources.");
      }
      return $lastResult;
    case "BOOST":
      DoBoost();
      return "1";
    case "VOFTHEVANGUARD":
      if($parameter == "1" && CardTalent($lastResult) == "LIGHT") ++$combatChainState[$CCS_ChainAttackBuff];
      PrependDecisionQueue("VOFTHEVANGUARD", $player, "1", 1);
      PrependDecisionQueue("CHARGE", $player, "-", 1);
      return "1";
    case "BEACONOFVICTORY":
      $combatChain[5] += $lastResult;
      return $lastResult;
    case "TRIPWIRETRAP":
      if($lastResult == 0)
      {
        WriteLog("Hit effects are prevented by Tripwire Trap this chain link.");
        $combatChainState[$CCS_ChainLinkHitEffectsPrevented] = 1;
      }
      return 1;
    case "PITFALLTRAP":
      if($lastResult == 0)
      {
        WriteLog("Pitfall Trap did two damage to the attacking hero.");
        DealDamage($player, 2, "DAMAGE");
      }
      return 1;
    case "ROCKSLIDETRAP":
      if($lastResult == 0)
      {
        WriteLog("Pitfall Trap gave the current attack -2.");
        $combatChain[5] -= 2;
      }
      return 1;
    case "CHARGE":
      DQCharge();
      return "1";
    case "FINISHCHARGE":
      ++$myClassState[$CS_NumCharged];
      return $lastResult;
    case "CHOOSEHERO":
      return $otherPlayer;
    case "DEALARCANE":
      $target = $lastResult;
      $damage = $parameter;
      $arcaneBarrier = ArcaneBarrierChoices($target, $parameter);
      //Create cancel point
      PrependDecisionQueue("TAKEARCANE", $target, $damage, 1);
      PrependDecisionQueue("PAYRESOURCES", $target, "<-", 1);
      PrependDecisionQueue("CHOOSEARCANE", $target, $arcaneBarrier, 1, 1);
      return $parameter;
    case "TAKEARCANE":
      $damage = DealDamage($player, $parameter - $lastResult, "ARCANE");
      if($damage == 0) $damage = -1;
      return $damage;
    case "PAYRESOURCES":
      if($lastResult < 0) $myResources[0] += (-1 * $lastResult);
      else if($myResources[0] > 0)
      {
        $resources = $myResources[0];
        $myResources[0] -= $lastResult;
        $lastResult -= $resources;
        if($myResources[0] < 0) $myResources[0] = 0;
      }
      if($lastResult > 0)
      {
        PrependDecisionQueue("PAYRESOURCES", $player, $parameter, 1);
        PrependDecisionQueue("SUBPITCHVALUE", $player, $lastResult, 1);
        PrependDecisionQueue("PITCHABILITY", $player, "-", 1);
        PrependDecisionQueue("ADDMYPITCH", $player, "-", 1);
        PrependDecisionQueue("REMOVEMYHAND", $player, "-", 1);
        PrependDecisionQueue("CHOOSEHANDCANCEL", $player, "<-", 1);
        PrependDecisionQueue("FINDINDICES", $player, "MYHAND", 1);
      }
      return $parameter;
    case "SUBPITCHVALUE":
      return $parameter - PitchValue($lastResult);
    case "BUFFARCANE":
      AddArcaneBonus($parameter, $player);
      return $parameter;
    default:
      return "NOTSTATIC";
  }
}

?>

