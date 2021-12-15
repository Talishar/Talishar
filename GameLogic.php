<?php

include "Search.php";
include "CardLogic.php";
include "AuraAbilities.php";
include "AllyAbilities.php";
include "WeaponLogic.php";

function PlayAbility($cardID, $from, $resourcesPaid, $target="-")
{
  global $myPitch, $myHand, $myCharacter, $myDeck, $mainPlayer, $myHealth, $otherPlayer, $myClassState, $CS_NumBoosted, $combatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $theirHealth, $currentPlayer, $defPlayer, $theirHand, $actionPoints;
  global $myClassState, $theirClassState, $CS_AtksWWeapon, $CS_DamagePrevention, $CS_Num6PowDisc, $CCS_DamageDealt, $myResources, $CCS_WeaponIndex, $CS_NextDamagePrevented, $CS_CharacterIndex, $CS_PlayIndex, $myItems;
  global $actionPoints, $CS_NumNonAttackCards, $CS_ArcaneDamageTaken, $CS_NextWizardNAAInstant, $CS_NumWizardNonAttack;
  global $CCS_BaseAttackDefenseMax, $CCS_NumChainLinks, $CCS_ResourceCostDefenseMin, $CCS_CardTypeDefenseRequirement;
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
      case "NONE": return MONTalentPlayAbility($cardID, $from, $resourcesPaid, $target);
      default: return "";
    }
  }
  else if($set == "ELE")
  {
    switch($class)
    {
      case "GUARDIAN": return ELEGuardianPlayAbility($cardID, $from, $resourcesPaid);
      case "RANGER": return ELERangerPlayAbility($cardID, $from, $resourcesPaid);
      case "RUNEBLADE": return ELERunebladePlayAbility($cardID, $from, $resourcesPaid);
      default: return ELETalentPlayAbility($cardID, $from, $resourcesPaid);
    }
  }
  $rv = "";
  switch($cardID)
  {
    case "WTR054": case "WTR055": case "WTR056":
      if(CountPitch($myPitch, 3) >= 1) MyDrawCard();
      return CountPitch($myPitch, 3) . " cards in pitch.";
    case "WTR004":
      $roll = GetDieRoll($currentPlayer);
      $actionPoints += intval($roll/2);
      return "Scabskin Leathers rolled $roll and gained " . intval($roll/2) . " action points.";
    case "WTR005":
      $roll = GetDieRoll($currentPlayer);
      $myResources[0] += intval($roll/2);
      return "Barkbone Strapping rolled $roll and gained " . intval($roll/2) . " resources.";
    case "WTR006":
      DiscardRandom($currentPlayer, $cardID);
      Intimidate();
      return "Alpha Rampage discarded a random card from your hand and intimidated.";
    case "WTR007":
      $discarded = DiscardRandom($currentPlayer, $cardID);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $drew = 0;
      if(AttackValue($discarded) >= 6)
      {
        $drew = 1;
        MyDrawCard();
        MyDrawCard();
        if(!CurrentEffectPreventsGoAgain()) ++$actionPoints;//TODO: This is not strictly accurate, but good enough for now
      }
      return "Bloodrush Bellow discarded " . $discarded . ($drew == 1 ? ", " : ", and ") . "gave your Brute attacks this turn +2" . ($drew == 1 ? ", drew two cards, and gained Go Again." : ".");
    case "WTR008":
      $discarded = DiscardRandom($currentPlayer, $cardID);
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
      $roll = GetDieRoll($currentPlayer);
      $myClassState[$CS_DamagePrevention] += $roll;
      return "Bone Head Barrier prevents the next $roll damage that will be dealt to you this turn.";
    case "WTR011": case "WTR012": case "WTR013":
      $discarded = DiscardRandom($currentPlayer, $cardID);
      if(AttackValue($discarded) >= 6) { $drew = 1; $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; }
      return "Savage Feast discarded a random card from your hand" . ($drew ? " and gained Go Again." : ".");
    case "WTR014": case "WTR015": case "WTR016":
      $discarded = DiscardRandom($currentPlayer, $cardID);
      if(AttackValue($discarded) >= 6) { $drew = 1; MyDrawCard(); }
      return "Savage Feast discarded a random card from your hand" . ($drew ? " and drew a card." : ".");
    case "WTR017": case "WTR018": case "WTR019":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      Intimidate();
      return "Barraging Beatdown intimidates and gives the next Brute attack this turn +" . EffectAttackModifier($cardID) . ".";
    case "WTR020": case "WTR021": case "WTR022":
      DiscardRandom($currentPlayer, $cardID);
      return "Savage Swing discarded a random card from your hand.";
    case "WTR023": case "WTR024": case "WTR025":
      Intimidate();
      return "Pack Hunt intimidated.";
    case "WTR026": case "WTR027": case "WTR028":
      Intimidate();
      return "Smash Instinct intimidated.";
    case "WTR029": case "WTR030": case "WTR031":
      DiscardRandom($currentPlayer, $cardID);
      return "Wrecker Romp discarded a random card from your hand.";
    case "WTR032": case "WTR033": case "WTR034":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      Intimidate();
      return "Awakening Bellow intimidated.";
    case "WTR035": case "WTR036": case "WTR037":
      DiscardRandom($currentPlayer, $cardID);
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
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Flic Flak gives the next Combo card you block with this turn +2.";
    //Warrior
    case "WTR116":
      AddCurrentTurnEffect($cardID, $currentPlayer);
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
      AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-", 1);
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
        $rv = "Last Ditch Effort gains Go Again and +4.";
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
          $rv .= " and lost you 2 health.";
        }
        else if($roll <= 4)
        {
          GainHealth(2, $currentPlayer);
          $rv .= " and gained you 2 health.";
        }
        else if($roll <= 6)
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $myResources[0] += 2;
          $actionPoints += 2;
          $rv .= " and gained 2 action points, resources, and damage.";
        }
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
      }
      return $rv;
    case "WTR163":
      $actions = SearchMyDiscard("A");
      $attackActions = SearchMyDiscard("AA");
      if($actions == "") $actions = $attackActions;
      else if($attackActions != "") $actions = $actions . "," . $attackActions;
      if($actions == "") return "";
      AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "3-" . $actions);
      AddDecisionQueue("REMEMBRANCE", $currentPlayer, "-", 1);
      return "";
    case "WTR170":
      if($from == "PLAY")
      {
        $myResources[0] += 2;
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
      }
      return "";
    case "WTR171":
      if($from == "PLAY")
      {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
      }
      return "";
    case "WTR172":
      if($from == "PLAY")
      {
        $actionPoints += 2;
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
      }
      return "";
    case "WTR173": GainHealth(3, $currentPlayer); return "Sigil of Solace gained 3 health.";
    case "WTR174": GainHealth(2, $currentPlayer); return "Sigil of Solace gained 2 health.";
    case "WTR175": GainHealth(1, $currentPlayer); return "Sigil of Solace gained 1 health.";
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
      if(IHaveLessHealth()) { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $rv = "Scar for a Scar gained Go Again."; }
      return $rv;
    case "WTR194": case "WTR195": case "WTR196":
      BottomDeckDraw();
      if($from == "ARS") { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $rv = "Scour the Battlescape gained Go Again."; }
      return $rv;
    case "WTR197": case "WTR198": case "WTR199":
      $indices = SearchMyDiscardForCard("WTR221", "WTR222", "WTR223");
      if($indices == "") { return "No Sloggism to banish."; }
      AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, $indices);
      AddDecisionQueue("REMOVEMYDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("BANISH", $currentPlayer, "DISCARD", 1);
      AddDecisionQueue("SLOGGISM", $currentPlayer, "-", 1);
    case "WTR200": case "WTR201": case "WTR202":
      if(IHaveLessHealth()) { AddCurrentTurnEffect($cardID, $mainPlayer); $rv = "Wounded Bull gains +1."; }
      return $rv;
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
      $discarded = DiscardRandom($currentPlayer, $cardID);
      return "Skullhorn discarded " . $discarded . ".";
    case "CRU008":
      if(GetClassState($currentPlayer, $CS_Num6PowDisc) > 0)
      {
        Intimidate();
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      return "";
    case "CRU009":
      $roll = GetDieRoll($currentPlayer);
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      for($i=1; $i<$roll; $i+=2)//half rounded down
      {
        AddDecisionQueue("FINDINDICES", $otherPlayer, "ITEMS", 1);
        AddDecisionQueue("CHOOSETHEIRITEM", $currentPlayer, "<-", 1);
        AddDecisionQueue("DESTROYITEM", $otherPlayer, "<-", 1);
      }
      return "Argh... Smash! rolled " . $roll . ".";
    case "CRU010": case "CRU011": case "CRU012":
      $discarded = DiscardRandom($currentPlayer, $cardID);
      return "Barraging Bighorn discarded " . $discarded . ".";
    case "CRU013": case "CRU014": case "CRU015":
      if($myClassState[$CS_Num6PowDisc] > 0)
      {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $rv = "Predatory Assault gained Dominate.";
      }
      return $rv;
    case "CRU019": case "CRU020": case "CRU021":
      $discarded = DiscardRandom($currentPlayer, $cardID);
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
    //Ninja
    case "CRU054":
      if(ComboActive())
      {
        $combatChainState[$CCS_ResourceCostDefenseMin] = $combatChainState[$CCS_NumChainLinks];
        $rv = "Crane Dance cannot be defended by cards with resource cost less than " . $combatChainState[$CCS_NumChainLinks] . ".";
      }
      return $rv;
    case "CRU055":
      if(ComboActive())
      {
        FloodOfForcePlayEffect();
        $rv = "Flood of Force reveals your deck and puts it in your hand if it has combo.";
      }
      return $rv;
    case "CRU056":
      if(ComboActive())
      {
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Attack_Action,Non-attack_Action");
        AddDecisionQueue("SETCOMBATCHAINSTATE", $currentPlayer, $CCS_CardTypeDefenseRequirement, 1);
      }
      return $rv;
    case "CRU057": case "CRU058": case "CRU059":
      if(ComboActive())
      {
        $combatChainState[$CCS_BaseAttackDefenseMax] = $combatChainState[$CCS_NumChainLinks];
        $rv = "Crane Dance cannot be defended by attacks with greater than " . $combatChainState[$CCS_NumChainLinks] . " base attack.";
      }
      return $rv;
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
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect($cardID . "-2", $currentPlayer);//Hit effect
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
      if(ArsenalFull($currentPlayer)) return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
      AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
      AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDARSENALFACEUP", $currentPlayer, "HAND", 1);
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
    //CRU Runeblade
    case "CRU141":
      AddCurrentTurnEffect($cardID . "-AA", $currentPlayer);
      AddCurrentTurnEffect($cardID . "-NAA", $currentPlayer);
      return "Bloodsheath Skeleta reduces the cost of your next attack action card and non-attack action card this turn.";
    case "CRU142":
      if(GetClassState($currentPlayer, $CS_NumNonAttackCards) > 0) PlayAura("ARC112", $currentPlayer);
      if(GetClassState($otherPlayer, $CS_ArcaneDamageTaken) > 0) PlayAura("ARC112", $currentPlayer);
      return "";
    case "CRU143":
      AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
      AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("BANISH", $currentPlayer, "TT", 1);
      return "Rattle Bones banishes a Runeblade attack action card, which can be played this turn.";
    case "CRU144":
      PlayAura("ARC112", $currentPlayer);
      PlayAura("ARC112", $currentPlayer);
      PlayAura("ARC112", $currentPlayer);
      PlayAura("ARC112", $currentPlayer);
      return "Runeblood Barrier created 4 Runechant tokens.";
    case "CRU145": case "CRU146": case "CRU147":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Mauvrion Skies gives your next Runeblade attack action Go Again and creates Runechants if it hits.";
    case "CRU154": case "CRU155": case "CRU156":
      if($cardID == "CRU154") $count = 3;
      else if($cardID == "CRU155") $count = 2;
      else $count = 1;
      $deck = &GetDeck($currentPlayer);
      $cards = "";
      for($i=0; $i<$count; ++$i)
      {
        if(count($deck) > 0)
        {
          if($cards != "") $cards .= ",";
          $card = array_shift($deck);
          $cards .= $card;
          if(CardClass($card) == "RUNEBLADE" && CardType($card) == "AA") PlayAura("ARC112", $currentPlayer);
        }
      }
      RevealCards($cards);
      AddDecisionQueue("CHOOSETOP", $currentPlayer, $cards);
      return "";
    //CRU Wizard
    case "CRU160":
      DealArcane(2);
      return "Aether Conduit deals 2 arcane damage.";
    case "CRU162":
      $rv = "Chain lightning let you play your next Wizard non-attack as an instant";
      SetClassState($currentPlayer, $CS_NextWizardNAAInstant,1);
      if(GetClassState($currentPlayer, $CS_NumWizardNonAttack) >= 2)
      {
        DealArcane(3);//TODO: All opponents
        $rv .= " and deal 3 arcane damage to each opposing hero";
      }
      return $rv . ".";
    case "CRU163":
      Opt($cardID, 2);
      return "";
    case "CRU164":
      NegateLayer($target);
      return "Aetherize negated an instant.";
    case "CRU165": case "CRU166": case "CRU167":
      if($cardID == "CRU165") $optAmt = 3;
      else if($cardID == "CRU166") $optAmt = 2;
      else $optAmt = 1;
      AddArcaneBonus(1, $currentPlayer);
      Opt($cardID, $optAmt);
      return "";
    case "CRU168": case "CRU169": case "CRU170":
      DealArcane(ArcaneDamage($cardID));
      Opt($cardID, 1);
      return "";
    case "CRU171": case "CRU172": case "CRU173":
      DealArcane(ArcaneDamage($cardID));
      AddArcaneBonus(1, $currentPlayer);
      return "";
    case "CRU174": case "CRU175": case "CRU176":
      DealArcane(ArcaneDamage($cardID));
      return "";
    //CRU Generics
    case "CRU181":
      $count = SearchCount(CombineSearches(SearchMyDiscardForCard("CRU181"), SearchTheirDiscardForCard("CRU181")));
      for($i=0; $i<$count; ++$i) { MyDrawCard(); }
      return "Gorganian Tome drew " . $count . " cards.";
    case "CRU182":
      AddCurrentTurnEffect("CRU182", $otherPlayer);
      return "Snag made attack actions unable to gain attack.";
    case "CRU183": case "CRU184": case "CRU185":
      if($from == "ARS") { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $rv = "Promise of Plenty gained Go Again."; }
      return $rv;
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
    case "CRU197":
      if($from == "PLAY")
      {
        MyDrawCard();
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
      }
      return "";
    default:
      break;
  }
}

function ProcessHitEffect($cardID)
{
  WriteLog("Processing hit effect for " . CardLink($cardID, $cardID));
  global $combatChain,$defArsenal, $mainClassState, $CS_HitsWDawnblade, $combatChainState, $CCS_WeaponIndex, $mainPlayer, $mainCharacter, $defCharacter, $mainArsenal, $CCS_ChainLinkHitEffectsPrevented, $CCS_NextBoostBuff, $CS_ArcaneDamageTaken, $defPlayer, $CCS_HitsInRow;
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
  else if($set == "ELE")
  {
    switch($class)
    {
      case "GUARDIAN": return ELEGuardianHitEffect($cardID);
      case "RANGER": return ELERangerHitEffect($cardID);
      case "RUNEBLADE": return ELERunebladeHitEffect($cardID);
      default: return ELETalentHitEffect($cardID);
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
    case "WTR206": case "WTR207": case "WTR208": if(CardType($attackID) == "AA") PummelHit(); break;
    case "WTR209": case "WTR210": case "WTR211": if(CardType($attackID) == "AA") GiveAttackGoAgain(); break;
    case "CRU054": PlayAura("CRU075", $mainPlayer); break;
    case "CRU060": case "CRU061": case "CRU062": if(ComboActive()) RushingRiverHitEffect(); break;
    case "CRU066": case "CRU067": case "CRU068": GiveAttackGoAgain(); break;
    case "CRU069": case "CRU070": case "CRU071": GiveAttackGoAgain(); break;
    case "CRU072": AddCurrentTurnEffectFromCombat($cardID, $mainPlayer); break;
    case "CRU074": if($combatChainState[$CCS_HitsInRow] >= 1) { MainDrawCard(); MainDrawCard(); } break;
    case "CRU106": case "CRU107": case "CRU108": AddCurrentTurnEffectFromCombat($cardID, $mainPlayer); break;
    case "CRU109": case "CRU110": case "CRU111": $combatChainState[$CCS_NextBoostBuff] += 3; break;
    case "CRU123": AddNextTurnEffect("CRU123-DMG", $defPlayer); break;
    case "CRU129": case "CRU130": case "CRU131":
      if(!ArsenalEmpty($mainPlayer)) return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
      AddDecisionQueue("FINDINDICES", $mainPlayer, "MAINHAND");
      AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $mainPlayer, "-", 1);
      AddDecisionQueue("ADDARSENALFACEDOWN", $mainPlayer, "HAND", 1);
      break;
    case "CRU132": case "CRU133": case "CRU134": $defCharacter[1] = 3; break;
    case "CRU142": PlayAura("ARC112", $mainPlayer); break;
    case "CRU148": case "CRU149": case "CRU150": if(GetClassState($defPlayer, $CS_ArcaneDamageTaken)) { PummelHit(); } break;
    case "CRU151": case "CRU152": case "CRU153":
      PlayAura("ARC112", $mainPlayer);
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
    case "ELE216": case "ELE217": case "ELE218": if(HasIncreasedAttack()) $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; break;
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
    case "WTR050": case "WTR049": case "WTR048": ArsenalToBottomDeck($defPlayer); break;
    case "CRU026":
      AddDecisionQueue("FINDINDICES", $mainPlayer, "CRU026");
      AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
      AddDecisionQueue("DESTROYTHEIRCHARACTER", $mainPlayer, "-", 1);
      break;
    case "CRU027":
      AddDecisionQueue("FINDINDICES", $defPlayer, "DECKTOPX,5");
      AddDecisionQueue("COUNTPARAM", $defPlayer, "<-", 1);
      AddDecisionQueue("MULTICHOOSETHEIRDECK", $mainPlayer, "<-", 1, 1);
      AddDecisionQueue("VALIDATEALLSAMENAME", $defPlayer, "DECK", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $defPlayer, "-", 1);
      AddDecisionQueue("MULTIBANISH", $defPlayer, "DECK,-", 1);
      break;
    case "CRU032": case "CRU033": case "CRU034": AddNextTurnEffect("CRU032", $defPlayer); break;
    case "CRU035": case "CRU036": case "CRU037": AddNextTurnEffect("CRU035", $defPlayer); break;
    default: return;
  }
}

//NOTE: This happens at combat resolution, so can't use the my/their directly
function AttackModifier($cardID, $from="", $resourcesPaid=0, $repriseActive=-1)
{
  global $mainPlayer, $mainPitch, $mainClassState, $CS_Num6PowDisc, $combatChain, $combatChainState, $mainCharacter, $mainAuras, $CCS_NumHits, $CS_CardsBanished, $CCS_HitsInRow, $CS_NumCharged, $CCS_NumBoosted, $defPlayer, $CS_ArcaneDamageTaken;
  global $CS_NumNonAttackCards, $CS_NumPlayedFromBanish, $CCS_NumChainLinks;
  if($repriseActive == -1) $repriseActive = RepriseActive();
  switch($cardID)
  {
    case "WTR003": return $mainClassState[$CS_Num6PowDisc];
    case "WTR040": return CountPitch($mainPitch, 3) >= 2 ? 2 : 0;
    case "WTR080": return 1;
    case "WTR081": return $resourcesPaid;
    case "WTR082": return 1;
    case "WTR083": if(ComboActive()) return 1;
    case "WTR084": if(ComboActive()) return 1;
    case "WTR086": case "WTR087": case "WTR088": if(ComboActive()) return $combatChainState[$CCS_NumHits];
    case "WTR089": case "WTR090": case "WTR091": if(ComboActive()) return 3;
    case "WTR095": case "WTR096": case "WTR097": if(ComboActive()) return 1;
    case "WTR104": case "WTR105": case "WTR106": if(ComboActive()) return 2;
    case "WTR110": case "WTR111": case "WTR112": if(ComboActive()) return 1;
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
    case "ARC077": return $mainClassState[$CS_NumNonAttackCards] > 0 ? 3 : 0;
    case "ARC188": case "ARC189": case "ARC190": return $combatChainState[$CCS_HitsInRow] > 0 ? 2 : 0;
    case "CRU016": case "CRU017": case "CRU018": return $mainClassState[$CS_Num6PowDisc] > 0 ? 1 : 0;
    case "CRU056": return ComboActive() ? 2 : 0;
    case "CRU057": case "CRU058": case "CRU059": return ComboActive() ? 1 : 0;
    case "CRU060": case "CRU061": case "CRU062": return ComboActive() ? 1 : 0;
    case "CRU063": case "CRU064": case "CRU065": return $combatChainState[$CCS_NumChainLinks] >= 3 ? 2 : 0;
    case "CRU073": return $combatChainState[$CCS_NumHits];
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
    case "MON155": return $mainClassState[$CS_NumPlayedFromBanish] > 0 ? 2 : 0;
    case "MON171": case "MON172": case "MON173": return GetClassState($defPlayer, $CS_ArcaneDamageTaken) > 0 ? 2 : 0;
    case "MON254": case "MON255": case "MON256": return $mainClassState[$CS_CardsBanished] > 0 ? 2 : 0;
    case "MON284": case "MON285": case "MON286": return NumCardsBlocking() < 2 ? 1 : 0;
    case "MON287": case "MON288": case "MON289": return NumCardsBlocking();
    case "MON290": case "MON291": case "MON292": return count($mainAuras) >= 1 ? 1 : 0;
    case "ELE082": case "ELE083": case "ELE084": return GetClassState($defPlayer,  $CS_ArcaneDamageTaken) >= 1 ? 2 : 0;
    case "ELE134": case "ELE135": case "ELE136": return $from == "ARS" ? 1 : 0;
    case "ELE202": return CountPitch($mainPitch, 3) >= 1 ? 1 : 0;
    default: return 0;
  }
}

//Return 1 if the effect should be removed
function EffectHitEffect($cardID)
{
  global $combatChainState, $CCS_GoesWhereAfterLinkResolves, $defPlayer, $mainPlayer;
  switch($cardID)
  {
    case "WTR129": case "WTR130": case "WTR131": GiveAttackGoAgain(); break;
    case "WTR147": case "WTR148": case "WTR149": NaturesPathPilgrimageHit(); break;
    case "ARC170-1": case "ARC171-1": case "ARC172-1": MainDrawCard(); return 1;
    case "CRU124": PummelHit(); break;
    case "CRU145": PlayAura("ARC112", $mainPlayer);
    case "CRU146": PlayAura("ARC112", $mainPlayer);
    case "CRU147": PlayAura("ARC112", $mainPlayer); break;
    case "CRU084-2": PutItemIntoPlayForPlayer("CRU197", $mainPlayer, 0, 2); break;
    case "MON034": LuminaAscensionHit(); break;
    case "MON081": case "MON082": case "MON083": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL"; break;
    case "MON110": case "MON111": case "MON112": DuskPathPilgrimageHit(); break;
    case "MON193": ShadowPuppetryHitEffect(); break;
    case "MON218": if(count(GetSoul($defPlayer)) > 0) { BanishFromSoul($defPlayer); LoseHealth(1, $defPlayer); } break;
    case "MON299": case "MON300": case "MON301": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK"; break;
    case "ELE003": PlayAura("ELE111", $defPlayer); break;
    case "ELE005": RandomHandBottomDeck($defPlayer); RandomHandBottomDeck($defPlayer); break;
    case "ELE019": case "ELE020": case "ELE021": ArsenalToBottomDeck($defPlayer); break;
    case "ELE022": case "ELE023": case "ELE024": PlayAura("ELE111", $defPlayer); break;
    case "ELE035-2": AddCurrentTurnEffect("ELE035-3", $defPlayer); AddNextTurnEffect("ELE035-3", $defPlayer); break;
    case "ELE037-2": DealDamage($defPlayer, 1, "ATTACKHIT"); break;
    case "ELE044": case "ELE045": case "ELE046": PlayAura("ELE111", $defPlayer); break;
    case "ELE047": case "ELE048": case "ELE049": DealDamage($defPlayer, 1, "ATTACKHIT"); break;
    case "ELE050": case "ELE051": case "ELE052": PayOrDiscard($defPlayer, 1); break;
    case "ELE066-HIT": if(HasIncreasedAttack()) MainDrawCard(); break;
    case "ELE092-BUFF": DealDamage($defPlayer, 3, "ATTACKHIT"); break;
    case "ELE151-HIT": case "ELE152-HIT": case "ELE153-HIT": PlayAura("ELE111", $defPlayer); break;
    case "ELE163":
      PlayAura("ELE111", $defPlayer);
    case "ELE164":
      PlayAura("ELE111", $defPlayer);
    case "ELE165":
      PlayAura("ELE111", $defPlayer);
      break;
    case "ELE173": DealDamage($defPlayer, 1, "ATTACKHIT"); break;
    case "ELE195": case "ELE196": case "ELE197": DealDamage($defPlayer, 1, "ATTACKHIT"); break;
    case "ELE198": case "ELE199": case "ELE200":
      if($cardID == "ELE198") $damage = 3;
      else if($cardID == "ELE199") $damage = 2;
      else $damage = 1;
      DealDamage($defPlayer, $damage, "ATTACKHIT");
      break;
    case "ELE205": PummelHit(); PummelHit(); break;
    case "ELE215": AddNextTurnEffect($cardID, $defPlayer); break;
    default: break;
  }
  return 0;
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
  else if($set == "ELE")
  {
    return ELEEffectAttackModifier($cardID);
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

function EffectBlockModifier($cardID)
{
  switch($cardID)
  {
    case "ELE143": return 1;
    default: return 0;
  }
}

function BlockModifier($cardID, $from, $resourcesPaid)
{
  global $defAuras, $defAuras, $defPlayer, $CS_CardsBanished, $mainPlayer, $CS_ArcaneDamageTaken;
  $blockModifier = 0;
  $cardType = CardType($cardID);
  $cardTalent = CardTalent($cardID);
  if(SearchCurrentTurnEffects("ARC160-1", $defPlayer) && $cardType == "AA") $blockModifier += 1;
  if(SearchCurrentTurnEffects("ELE114", $defPlayer) && ($cardType == "AA" || $cardType == "A") && ($cardTalent == "ICE" || $cardTalent == "EARTH" || $cardTalent == "ELEMENTAL")) $blockModifier += 1;
  for($i=0; $i<count($defAuras); $i+=AuraPieces())
  {
    if($defAuras[$i] == "WTR072" && CardCost($cardID) >= 3) $blockModifier += 4;
    if($defAuras[$i] == "WTR073" && CardCost($cardID) >= 3) $blockModifier += 3;
    if($defAuras[$i] == "WTR074" && CardCost($cardID) >= 3) $blockModifier += 2;
    if($defAuras[$i] == "WTR046" && $cardType == "E") $blockModifier += 1;
    if($defAuras[$i] == "ELE109" && $cardType == "A") $blockModifier += 1;
  }
  switch($cardID)
  {
    case "WTR212": case "WTR213": case "WTR214": $blockModifier += $from == "ARS" ? 1 : 0; break;
    case "WTR051": case "WTR052": case "WTR053": $blockModifier += ($resourcesPaid == 6 ? 3 : 0); break;
    case "ARC150": $blockModifier += (DefHasLessHealth() ? 1 : 0); break;
    case "CRU187": $blockModifier += ($from == "ARS" ? 2 : 0); break;
    case "MON075": case "MON076": case "MON077": return GetClassState($mainPlayer, $CS_CardsBanished) >= 3 ? 2 : 0;
    case "MON290": case "MON291": case "MON292": return count($defAuras) >= 1 ? 1 : 0;
    case "ELE227": case "ELE228": case "ELE229": return GetClassState($mainPlayer, $CS_ArcaneDamageTaken) > 0 ? 1 : 0;
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
    case "ELE125": return 4;
    case "ELE126": return 3;
    case "ELE127": return 2;
    default: return 0;
  }
}

function SelfCostModifier($cardID)
{
  global $myClassState, $CS_NumCharged, $currentPlayer, $combatChain, $CS_LayerTarget;
  switch($cardID)
  {
    case "ARC080": return (-1 * NumRunechants($currentPlayer));
    case "ARC082": return (-1 * NumRunechants($currentPlayer));
    case "ARC088": case "ARC089": case "ARC090": return (-1 * NumRunechants($currentPlayer));
    case "ARC094": case "ARC095": case "ARC096": return (-1 * NumRunechants($currentPlayer));
    case "ARC097": case "ARC098": case "ARC099": return (-1 * NumRunechants($currentPlayer));
    case "ARC100": case "ARC101": case "ARC102": return (-1 * NumRunechants($currentPlayer));
    case "MON032": return (-1 * (2 * $myClassState[$CS_NumCharged]));
    case "MON084": case "MON085": case "MON086": return TalentContains($combatChain[GetClassState($currentPlayer, $CS_LayerTarget)], "SHADOW") ? -1 : 0;
    default: return 0;
  }
}

function CharacterCostModifier($cardID, $from)
{
  global $currentPlayer, $CS_NumSwordAttacks;
  $modifier = 0;
  if(CardSubtype($cardID) == "Sword" && GetClassState($currentPlayer, $CS_NumSwordAttacks) == 1 && SearchCharacterActive($currentPlayer, "CRU077"))
  {
    --$modifier;
  }
  return $modifier;
}

function CurrentEffectCostModifiers($cardID)
{
  global $currentTurnEffects, $currentPlayer;
  $costModifier = 0;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i>=0; $i-=CurrentTurnPieces())
  {
    if($currentTurnEffects[$i+1] == $currentPlayer)
    {
      $remove = 0;
      switch($currentTurnEffects[$i])
      {
        case "WTR060": case "WTR061": case "WTR062": if(IsAction($cardID)) { $costModifier += 1; $remove = 1; } break;
        case "WTR075": if(CardClass($cardID) == "GUARDIAN" && CardType($cardID) == "AA") { $costModifier -= 1; $remove = 1; } break;
        case "WTR152": if(CardType($cardID) == "AA") {$costModifier -= 2; $remove = 1; } break;
        case "CRU081": if(CardType($cardID) == "W" && CardSubType($cardID) == "Sword") { $costModifier -= 1; } break;
        case "CRU085-2": case "CRU086-2": case "CRU087-2": if(CardType($cardID) == "DR") {$costModifier += 1; $remove = 1; } break;
        case "CRU141-AA": if(CardType($cardID) == "AA") { $costModifier -= CountAura("ARC112", $currentPlayer); $remove = 1; } break;
        case "CRU141-NAA": if(CardType($cardID) == "A") { $costModifier -= CountAura("ARC112", $currentPlayer); $remove = 1; } break;
        case "CRU188": $costModifier -= 999; $remove = 1; break;
        case "MON257": $costModifier -= 999; $remove = 1; break;
        case "MON199": $costModifier -= 999; $remove = 1; break;
        case "ARC185": $costModifier -= 999; $remove = 1; break;
        case "ARC060": case "ARC061": case "ARC062": if(CardType($cardID) == "AA" || GetAbilityType($cardID) == "AA") {$costModifier += 1; $remove = 1; } break;
        case "ELE035-1": $costModifier += 1; break;
        case "ELE038": case "ELE039": case "ELE040": $costModifier += 1; break;
        case "ELE144": $costModifier += 1; break;
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
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i>=0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
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
        for($j = $i+CurrentTurnPieces()-1; $j >= $i; --$j) unset($currentTurnEffects[$j]);
      }
    }
  }
  $currentTurnEffects = array_values($currentTurnEffects);//In case any were removed
  return false;
}

function CurrentEffectGrantsNonAttackActionGoAgain($action)
{
  global $currentTurnEffects, $currentPlayer;
  $hasGoAgain = false;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i>=0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    if($currentTurnEffects[$i+1] == $currentPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "MON153": case "MON154":
          if(CardClass($action) == "RUNEBLADE" || CardTalent($action) == "SHADOW") { $hasGoAgain = true; $remove = 1;} break;
        case "ELE177": if(CardCost($action) >= 0) { $hasGoAgain = true; $remove = 1; } break;
        case "ELE178": if(CardCost($action) >= 1) { $hasGoAgain = true; $remove = 1; } break;
        case "ELE179": if(CardCost($action) >= 2) { $hasGoAgain = true; $remove = 1; } break;
        case "ELE201": $hasGoAgain = true; $remove = 1; break;
        default: break;
      }
    }
    if($remove == 1)
    {
      unset($currentTurnEffects[$i+1]);
      unset($currentTurnEffects[$i]);
    }
  }
  return $hasGoAgain;
}

function CurrentEffectGrantsGoAgain()
{
  global $currentTurnEffects, $mainPlayer, $combatChain, $combatChainState, $CCS_AttackFused;
  for($i=0; $i<count($currentTurnEffects); $i+=2)
  {
    if($currentTurnEffects[$i+1] == $mainPlayer && IsCombatEffectActive($currentTurnEffects[$i]))
    {
      switch($currentTurnEffects[$i])
      {
        case "WTR144": case "WTR145": case "WTR146": return true;
        case "ARC047": return true;
        case "ARC160-3": return true;
        case "CRU053": return true;
        case "CRU055": return true;
        case "CRU084": return true;
        case "CRU091-1": case "CRU092-1": case "CRU093-1": return true;
        case "CRU094-2": case "CRU095-2": case "CRU096-2": return true;
        case "CRU122": return true;
        case "CRU145": case "CRU146": case "CRU147": return true;
        case "MON153": case "MON154": return true;
        case "MON165": case "MON166": case "MON167": return true;
        case "MON193": return true;
        case "MON247": return true;
        case "MON260-2": case "MON261-2": case "MON262-2": return true;
        case "ELE031-1": return true;
        case "ELE034-2": return true;
        case "ELE091-GA": return true;
        case "ELE177": case "ELE178": case "ELE179": return true;
        case "ELE180": case "ELE181": case "ELE182": return $combatChainState[$CCS_AttackFused] == 1;
        case "ELE201": return true;
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
        case "ELE147": return true;
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

function CurrentEffectEndTurnAbilities()
{
  global $currentTurnEffects;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i>=0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    switch($currentTurnEffects[$i])
    {
      case "MON069": case "MON070": case "MON071":
        $char = &GetPlayerCharacter($currentTurnEffects[$i+1]);
        for($j=0; $j<count($char); $j+=CharacterPieces())
        {
          if(CardType($char[$j]) == "W") $char[$j+3] = 0;//Glisten clears out all +1 attack counters
        }
        $remove = 1;
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

function IsCombatEffectActive($cardID)
{
  global $combatChain, $mainPlayer;
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
  else if($set == "ELE")
  {
    return ELECombatEffectActive($cardID, $attackID);
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
    case "WTR162": return true;
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
    case "ARC170-1": case "ARC171-1": case "ARC172-1": return true;
    case "CRU025": return true;
    case "CRU053": return true;
    case "CRU084-2": return true;
    case "CRU122": return true;
    case "CRU124": return true;
    case "MON034": return true;
    case "MON087": return true;
    case "MON108": return true;
    case "MON109": return true;
    case "MON218": return true;
    case "MON239": return true;
    case "ELE044": case "ELE045": case "ELE046": return true;
    case "ELE047": case "ELE048": case "ELE049": return true;
    case "ELE050": case "ELE051": case "ELE052": return true;
    case "ELE059": case "ELE060": case "ELE061": return true;
    case "ELE066-HIT": return true;
    case "ELE067": case "ELE068": case "ELE069": return true;
    case "ELE091-BUFF": case "ELE091-GA": return true;
    case "ELE092-DOM": case "ELE092-BUFF": return true;
    case "ELE143": return true;
    case "ELE151-HIT": case "ELE152-HIT": case "ELE153-HIT": return true;
    default: return false;
  }
}


function BeginEndStepEffects()
{
  global $currentTurnEffects, $mainPlayer;
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnPieces())
  {
    if($currentTurnEffects[$i+1] == $mainPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "ELE215": WriteLog("Seek and Destroy discarded your hand and arsenal."); DestroyArsenal($mainPlayer); DiscardHand($mainPlayer); break;
        default: break;
      }
    }
  }
}

//NOTE: This happens at start of turn, so must use main player game state
function ItemStartTurnAbility($index)
{
  global $mainPlayer;
  $mainItems = &GetItems($mainPlayer);
  $mainResources = &GetResources($mainPlayer);
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
  global $mainPlayer;
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  if($mainCharacter[$index+1] == 0) return;//Do not process ability if it is destroyed
  switch($mainCharacter[$index])
  {
    case "WTR150":  if($mainCharacter[$index+2] < 3) ++$mainCharacter[$index+2]; break;
    case "MON187":
      if(GetHealth($mainPlayer) <= 13)
      {
          $mainCharacter[$index+1] = 0;
          BanishCardForPlayer($mainCharacter[$index], $mainPlayer, "EQUIP", "NA");
          WriteLog("Carrion Husk got banished for having 13 or less health.");
      } break;
    default: break;
  }
}

function PitchAbility($cardID)
{
  global $myHealth, $currentPlayer;
  switch($cardID)
  {
    case "WTR000":
        if(IHaveLessHealth()) ++$myHealth;
      break;
    case "ARC000":
        Opt($cardID, 2);
      break;
    case "CRU000":
      PlayAura("ARC112", $currentPlayer);
      break;
    default:
      break;
  }
}

function RemoveEffectsOnChainClose()
{
  global $currentTurnEffects;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i >= 0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    switch($currentTurnEffects[$i])
    {
      case "ELE067": case "ELE068": case "ELE069": $remove = 1; break;
      case "ELE186": case "ELE187": case "ELE188": $remove = 1; break;
      default: break;
    }
    if($remove == 1)
    {
      unset($currentTurnEffects[$i+1]);
      unset($currentTurnEffects[$i]);
    }
  }
}

function OnAttackEffects($attack)
{
  global $currentTurnEffects, $mainPlayer, $defPlayer;
  $attackType = CardType($attack);
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i >= 0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    if($currentTurnEffects[$i+1] == $mainPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "ELE085": case "ELE086": case "ELE087": if($attackType == "AA") { DealArcane(1, 0, "PLAYCARD", $attack, true); $remove = 1; } break;
        case "ELE092-DOM":
          AddDecisionQueue("BUTTONINPUT", $defPlayer, "0,2", 0, 1);
          AddDecisionQueue("PAYRESOURCES", $defPlayer, "<-", 1);
          AddDecisionQueue("GREATERTHANPASS", $defPlayer, "0", 1);
          AddDecisionQueue("ADDCURRENTEFFECT", $mainPlayer, "ELE092-DOMATK", 1);
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
}

function OnBlockEffects($index, $from)
{
  global $currentTurnEffects, $combatChain, $currentPlayer, $combatChainState, $CCS_WeaponIndex, $otherPlayer, $mainPlayer, $defPlayer;
  $cardType = CardType($combatChain[$index]);
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i >= 0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    if($currentTurnEffects[$i+1] == $currentPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "WTR092": case "WTR093": case "WTR094": if(HasCombo($combatChain[$index])) { $combatChain[$index+6] += 2; } $remove = 1; break;
        case "ELE004": PlayAura("ELE111", $currentPlayer); break;
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
        $first = true;
        for($i=0; $i<$index; $i+=CombatChainPieces())
        {
          if($combatChain[$i+1] == $currentPlayer && CardType($combatChain[$i]) == "AA") $first = false;
        }
        if($first)
        {
          AddCharacterEffect($otherPlayer, $combatChainState[$CCS_WeaponIndex], "CRU079");
          WriteLog("Cintari Saber got +1 for the rest of the turn.");
        }
      }
      break;
    case "CRU051": case "CRU052":
        $totalAttack = 0; $totalBlock = 0;
        EvaluateCombatChain($totalAttack, $totalDefense);
        if(BlockValue($combatChain[$index]) > $totalAttack) DestroyCurrentWeapon();
      break;
    default: break;
  }
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  for($i=0; $i<count($mainCharacter); $i+=CharacterPieces())
  {
    if($mainCharacter[$i+1] != 2) continue;
    switch($mainCharacter[$i])
    {
      case "ELE174":
        if($from == "HAND")
        {
          $talent = CardTalent($combatChain[0]);
          if($talent == "LIGHTNING" || $talent == "ELEMENTAL")
          {
            AddDecisionQueue("YESNO", $mainPlayer, "destroy_mark_of_lightning_to_have_the_attack_deal_1_damage");
            AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
            AddDecisionQueue("PASSPARAMETER", $mainPlayer, $i, 1);
            AddDecisionQueue("DESTROYCHARACTER", $mainPlayer, "-", 1);
            AddDecisionQueue("DEALDAMAGE", $defPlayer, 1 . "-" . $combatChain[0] . "-" . "COMBAT", 1);
          }
        }
      default: break;
    }
  }
  ProcessPhantasmOnBlock($index);
}

function ActivateAbilityEffects()
{
  global $currentPlayer, $currentTurnEffects;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i >= 0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    if($currentTurnEffects[$i+1] == $currentPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "ELE004-HIT": WriteLog("Endless winter gives a frostbite token."); PlayAura("ELE111", $currentPlayer); break;
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
}

function DestroyMainItem($index)
{
  global $mainItems;
  unset($mainItems[$index+2]);
  unset($mainItems[$index+1]);
  unset($mainItems[$index]);
  $mainItems = array_values($mainItems);
}

function DestroyMyItem($index)
{
  global $myItems;
  unset($myItems[$index+2]);
  unset($myItems[$index+1]);
  unset($myItems[$index]);
  $myItems = array_values($myItems);
}

function DestroyItemForPlayer($player, $index)
{
  $items = &GetItems($player);
  for($i=$index+ItemPieces()-1; $i>=$index; --$i)
  {
    unset($items[$i]);
  }
  $items = array_values($items);
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

function Draw($player)
{
  global $myHand, $myDeck;
  $deck = &GetDeck($player);
  $hand = &GetHand($player);
  if(count($deck) == 0) return -1;
  if(CurrentEffectPreventsDraw($player)) return -1;
  array_push($hand, array_shift($deck));
  return $hand[count($hand)-1];
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

function CharacterDestroyEffect($cardID, $player)
{
  switch($cardID)
  {
    case "ELE213": DestroyArsenal($player); break;
    default: break;
  }
}

function MainCharacterEndTurnAbilities()
{
  global $mainCharacter, $characterPieces, $mainClassState, $CS_HitsWDawnblade, $CS_AtksWWeapon, $mainPlayer, $defPlayer, $CS_NumNonAttackCards;
  global $CS_NumAttackCards, $CS_ArcaneDamageTaken;
  for($i=0; $i<count($mainCharacter); $i += CharacterPieces())
  {
    switch($mainCharacter[$i])
    {
      case "WTR115": if($mainClassState[$CS_HitsWDawnblade] == 0) $mainCharacter[$i+3] = 0; break;
      case "CRU077": KassaiEndTurnAbility(); break;
      case "ELE223":
        if(GetClassState($mainPlayer, $CS_NumNonAttackCards) == 0 || GetClassState($mainPlayer, $CS_NumAttackCards) == 0) $mainCharacter[$i+3] = 0; break;
      case "MON107": if($mainClassState[$CS_AtksWWeapon] >= 2 && $mainCharacter[$i+4] < 0) ++$mainCharacter[$i+4]; break;
      case "ELE224": if(GetClassState($defPlayer, $CS_ArcaneDamageTaken) < $mainCharacter[$i+2]) $mainCharacter[$i+1] = 0; $mainCharacter[$i+2] = 0; break;
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
      case "WTR076": case "WTR077": if(CardType($attackID) == "AA") { KatsuHit($i); $mainCharacter[$i+1] = 1; } break;
      case "WTR079": if(CardType($attackID) == "AA" && $combatChainState[$CCS_HitsInRow] == 3) { MainDrawCard(); $mainCharacter[$i+1] = 1; } break;
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
      case "CRU053":
        if(HasCombo($attackID))
        {
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_destroy_Breeze_Rider_Boots_to_give_your_Combo_attacks_Go_Again");
          AddDecisionQueue("NOPASS", $mainPlayer, "-");
          AddDecisionQueue("PASSPARAMETER", $mainPlayer, $i, 1);
          AddDecisionQueue("DESTROYCHARACTER", $mainPlayer, "-", 1);
          AddDecisionQueue("ADDCURRENTEFFECT", $mainPlayer, $mainCharacter[$i], 1);
        }
        break;
      default: break;
    }
  }
}

function MainCharacterAttackModifiers()
{
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer, $CS_NumAttacks;
  $modifier = 0;
  $mainCharacterEffects = &GetMainCharacterEffects($mainPlayer);
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  for($i=0; $i<count($mainCharacterEffects); $i+=CharacterEffectPieces())
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
      case "CRU046": if(GetClassState($mainPlayer, $CS_NumAttacks) == 2) $modifier += 1; break;
      default: break;
    }
  }
  return $modifier;
}

function DefCharacterBlockModifier($index)
{
  global $defPlayer;
  $characterEffects = &GetCharacterEffects($defPlayer);
  $modifier = 0;
  for($i=0; $i<count($characterEffects); $i+=CharacterEffectPieces())
  {
    if($characterEffects[$i] == $index)
    {
      switch($characterEffects[$i+1])
      {
        case "ELE203": $modifier += 1; break;
        default: break;
      }
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


function PutItemIntoPlayForPlayer($item, $player, $steamCounterModifier = 0, $number=1)
{
  if(CardSubType($item) != "Item") return;
  $items = &GetItems($player);
  for($i=0; $i<$number; ++$i)
  {
    array_push($items, $item);//Card ID
    array_push($items, ETASteamCounters($item) + SteamCounterLogic($item, $player) + $steamCounterModifier);//Counters
    array_push($items, 2);//Status
  }
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
    case "ARC080": return true;
    case "MON004": return true;
    case "MON023": case "MON024": case "MON025": return true;
    case "MON246": return SearchMyDiscard("AA") == "";
    case "MON275": case "MON276": case "MON277": return true;
    case "ELE209": case "ELE210": case "ELE211": return HasIncreasedAttack();
    default: break;
  }
  return false;
}

function EquipPayAdditionalCosts($cardIndex, $from)
{
  global $myCharacter, $currentPlayer;
  $cardID = $myCharacter[$cardIndex];
  switch($cardID)
  {
    case "WTR005":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "WTR042":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "WTR080":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "WTR150":
      $myCharacter[$cardIndex+2] -= 3;
      break;
    case "WTR151": case "WTR152": case "WTR153": case "WTR154":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "ARC003":
      $myCharacter[$cardIndex+1] = 2;
      break;
    case "ARC005": case "ARC042": case "ARC079": case "ARC116": case "ARC117": case "ARC151": case "ARC153": case "ARC154":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "ARC113": case "ARC114":
      $myCharacter[$cardIndex+1] = 2;
      break;
    case "CRU006": case "CRU025": case "CRU081": case "CRU102": case "CRU122": case "CRU141":
      DestroyCharacter($currentPlayer, $cardIndex);
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
    case "MON061": case "MON090": case "MON108": case "MON188": case "MON230": case "MON238": case "MON239": case "MON240":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "MON029": case "MON030":
      $myCharacter[$cardIndex+1] = 2;//It's not limited to once
      break;
    case "ELE116": case "ELE145": case "ELE214": case "ELE225": case "ELE233": case "ELE234": case "ELE235": case "ELE236":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "ELE224":
      ++$myCharacter[$cardIndex + 2];
      --$myCharacter[$cardIndex+5];
      if($myCharacter[$cardIndex+5] == 0) $myCharacter[$cardIndex+1] = 1;
      break;
    default:
      --$myCharacter[$cardIndex+5];
      if($myCharacter[$cardIndex+5] == 0) $myCharacter[$cardIndex+1] = 1;//By default, if it's used, set it to used
      break;
  }
}

function DecisionQueueStaticEffect($phase, $player, $parameter, $lastResult)
{
  global $currentPlayer, $myCharacter, $myHand, $myDeck, $myDiscard, $myBanish, $mySoul, $mainHand, $combatChain, $myCharacterEffects, $myPitch;
  global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $actionPoints, $myResources, $myHealth, $theirHealth, $myArsenal, $CCS_ChainAttackBuff;
  global $defCharacter, $myClassState, $CS_NumCharged, $theirCharacter, $theirHand, $otherPlayer, $CCS_ChainLinkHitEffectsPrevented;
  global $CS_NumFusedEarth, $CS_NumFusedIce, $CS_NumFusedLightning, $CCS_AttackFused, $CS_NextNAACardGoAgain, $CCS_AttackTarget;
  global $CS_LayerTarget;
  $rv = "";
  switch($phase)
  {
    case "FINALIZECHAINLINK":
      $param = $parameter != "-" ? true : false;
      FinalizeChainLink($param);
      return "1";
    case "FINDRESOURCECOST":
      switch($parameter)
      {
        case "CRU126": case "CRU127": case "CRU128": return ($lastResult == "YES" ? 1 : 0);
        case "ELE148": case "ELE149": case "ELE150": return ($lastResult == "YES" ? 2 : 0);
        default: return ($lastResult == "YES" ? $parameter : 0);
      }
      return 0;
    case "FINDINDICES":
      UpdateGameState($currentPlayer);
      BuildMainPlayerGamestate();
      $parameters = explode(",", $parameter);
      $parameter = $parameters[0];
      if(count($parameters) > 1) $subparam = $parameters[1];
      switch($parameter)
      {
        case "WTR083": $rv = SearchMainDeckForCard("WTR081"); $rv = count(explode(",", $rv)) . "-" . $rv; break;
        case "WTR076-1": $rv = SearchMainHand("", "", 0); break;
        case "WTR076-2": $rv = GetComboCards(); break;
        case "WTR081": $rv = LordOfWindIndices(); $rv = count(explode(",", $rv)) . "-" . $rv; break;
        case "ARC014": $rv = SearchMyHand("", "Item", 2, -1, "MECHANOLOGIST"); break;
        case "ARC015": $rv = SearchMyHand("", "Item", 1, -1, "MECHANOLOGIST"); break;
        case "ARC016": $rv = SearchMyHand("", "Item", 0, -1, "MECHANOLOGIST"); break;
        case "ARC079": $rv = CombineSearches(SearchDiscard($player, "AA", "", -1, -1, "RUNEBLADE"), SearchDiscard($player, "A", "", -1, -1, "RUNEBLADE")); break;
        case "ARC121": $rv = SearchDeck($player, "", "", $lastResult, -1, "WIZARD"); break;
        case "ARC138": case "ARC139": case "ARC140": $rv = SearchHand($player, "A", "", $lastResult, -1, "WIZARD"); break;
        case "ARC185": case "ARC186": case "ARC187": $rv = SearchMainDeckForCard("ARC212", "ARC213", "ARC214"); break;
        case "CRU026": $rv = SearchEquipNegCounter($defCharacter); break;
        case "CRU105": $rv = GetWeaponChoices("Pistol"); break;
        case "CRU143": $rv = SearchDiscard($player, "AA", "", -1, -1, "RUNEBLADE"); break;
        case "LAYER": $rv = SearchLayerDQ($subparam); break;
        case "TOPDECK": $deck = &GetDeck($player); if(count($deck) > 0) $rv = "0"; break;
        case "DECKTOPX": $rv = ""; $deck = &GetDeck($player); for($i=0; $i<$subparam; ++$i) if($i < count($deck)) { if($rv != "") $rv .= ","; $rv .= $i; } break;
        case "DECKCLASSAA": $rv = SearchDeck($player, "AA", "", -1, -1, $subparam); break;
        case "DECKCLASSNAA": $rv = SearchDeck($player, "A", "", -1, -1, $subparam); break;
        case "HAND": $hand = &GetHand($player); $rv = GetIndices(count($hand)); break;
        case "HANDTALENT":  $rv = SearchHand($player, "", "", -1, -1, "", $subparam); break;
        case "HANDACTION": $rv = CombineSearches(SearchHand($player, "A"), SearchHand($player, "AA")); break;
        case "MULTIHAND": $hand = &GetHand($player); $rv = count($hand) . "-" . GetIndices(count($hand)); break;
        case "MULTIHANDAA": $search = SearchHand($player, "AA"); $rv = SearchCount($search) . "-" . $search; break;
        case "ARSENAL": $arsenal = &GetArsenal($player); $rv = GetIndices(count($arsenal), 0, 2); break;
        case "ARSENALDOWN": $rv = GetArsenalFaceDownIndices($player); break;
        case "MYHAND": $rv = GetIndices(count($myHand)); break;
        case "ITEMS": $rv = GetIndices(count(GetItems($player))); break;
        case "EQUIP": $rv = GetEquipmentIndices($player); break;
        case "EQUIP0": $rv = GetEquipmentIndices($player, 0); break;
        case "CCAA": $rv = SearchCombatChain("AA"); break;
        case "HANDEARTH": $rv = SearchHand($player, "", "", -1, -1, "", "EARTH"); break;
        case "HANDICE": $rv = SearchHand($player, "", "", -1, -1, "", "ICE"); break;
        case "HANDLIGHTNING": $rv = SearchHand($player, "", "", -1, -1, "", "LIGHTNING"); break;
        case "MYHANDAA": $rv = SearchMyHand("AA"); break;
        case "MYHANDARROW": $rv = SearchMyHand("", "Arrow"); break;
        case "MYDECKARROW": $rv = SearchMyDeck("", "Arrow"); break;
        case "MAINHAND": $rv = GetIndices(count($mainHand)); break;
        case "MAINDISCARDNAA": $rv = SearchMainDiscard("A"); break;
        case "FIRSTXDECK": $deck = &GetDeck($player); if($subparam > count($deck)) $subparam = count($deck); $rv = GetIndices($subparam); break;
        case "BANISHTYPE": $rv = SearchBanish($player, $subparam); break;
        case "GY": $discard = &GetDiscard($player); $rv = GetIndices(count($discard)); break;
        case "GYTYPE": $rv = SearchDiscard($player, $subparam); break;
        case "GYAA": $rv = SearchDiscard($player, "AA"); break;
        case "GYNAA": $rv = SearchDiscard($player, "A"); break;
        case "GYCLASSAA": $rv = SearchDiscard($player, "AA", "", -1, -1, $subparam); break;
        case "GYCLASSNAA": $rv = SearchDiscard($player, "A", "", -1, -1, $subparam); break;
        case "WEAPON": $rv = WeaponIndices($player, $player); break;
        case "MON020": case "MON021": case "MON022": $rv = SearchDiscard($player, "", "", -1, -1, "", "", false, true); break;
        case "MON033-1": $rv = GetIndices(count($mySoul), 1); break;
        case "MON033-2": $rv = CombineSearches(SearchMyDeck("A", "", $lastResult), SearchMyDeck("AA", "", $lastResult)); break;
        case "MON125": $rv = SearchDeck($player, "", "", -1, -1, "", "", true); break;
        case "MON156": $rv = SearchHand($player, "", "", -1, -1, "", "", true); break;
        case "MON158": $rv = InvertExistenceIndices($player); break;
        case "MON159": case "MON160": case "MON161": $rv = SearchDiscard($player, "A", "", -1, -1, "", "", true); break;
        case "MON212": $rv = SearchBanish($player, "AA", "", $subparam); break;
        case "MON266-1": $rv = SearchMyHand("AA", "", -1, -1, 3); break;
        case "MON266-2": $rv = SearchMyDeckForCard("MON296", "MON297", "MON298"); break;
        case "MON303": $rv = SearchMyDiscard($type="AA", $subtype="", $maxCost=2); break;
        case "MON304": $rv = SearchMyDiscard($type="AA", $subtype="", $maxCost=1); break;
        case "MON305": $rv = SearchMyDiscard($type="AA", $subtype="", $maxCost=0); break;
        case "HANDIFZERO": if($lastResult == 0) { $hand = &GetHand($player); $rv = GetIndices(count($hand)); } break;
        case "ELE006": $count = CountAura("WTR075", $player); $rv = SearchDeck($player, "AA", "", $count, -1, "GUARDIAN"); break;
        case "ELE113": $rv = PulseOfCandleholdIndices($player); break;
        case "ELE116": $rv = PlumeOfEvergrowthIndices($player); break;
        case "ELE125": case "ELE126": case "ELE127": $rv = SummerwoodShelterIndices($player); break;
        case "ELE140": case "ELE141": case "ELE142": $rv = SowTomorrowIndices($player, $parameter); break;
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
      return Draw($player);
    case "BANISH":
      BanishCardForPlayer($lastResult, $player, "-", $parameter);
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
    case "REMOVEDISCARD":
      $discard = &GetDiscard($player);
      $cardID = $discard[$lastResult];
      unset($discard[$lastResult]);
      $discard = array_values($discard);
      return $cardID;
    case "REMOVEMYHAND":
      $cardID = $myHand[$lastResult];
      unset($myHand[$lastResult]);
      $myHand = array_values($myHand);
      return $cardID;
    case "MULTIREMOVEDISCARD":
      $discard = &GetDiscard($player);
      $cards = "";
      if(!is_array($lastResult)) $lastResult = explode(",", $lastResult);
      for($i=0; $i<count($lastResult); ++$i)
      {
        if($cards != "") $cards .= ",";
        $cards .= $discard[$lastResult[$i]];
        unset($discard[$lastResult[$i]]);
      }
      $discard = array_values($discard);
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
    case "ADDHAND":
      $hand = &GetHand($player);
      array_push($hand, $lastResult);
      return $lastResult;
    case "ADDMYPITCH":
      array_push($myPitch, $lastResult);
      return $lastResult;
    case "PITCHABILITY":
      PitchAbility($lastResult);
      return $lastResult;
    case "ADDARSENALFACEUP":
      AddArsenal($lastResult, $player, $parameter, "UP");
      return $lastResult;
    case "ADDARSENALFACEDOWN":
      AddArsenal($lastResult, $player, $parameter, "DOWN");
      return $lastResult;
    case "REMOVEARSENAL":
      $index = $lastResult;
      $arsenal = &GetArsenal($player);
      $cardToReturn = $arsenal[$index];
      for($i=$index+ArsenalPieces()-1; $i>=$index; --$i)
      {
        unset($arsenal[$i]);
      }
      $arsenal = array_values($arsenal);
     return $cardToReturn;
    case "MULTIADDHAND":
      $cards = explode(",", $lastResult);
      $hand = &GetHand($player);
      for($i=0; $i<count($cards); ++$i)
      {
        array_push($hand, $cards[$i]);
      }
      return $lastResult;
    case "MULTIREMOVEHAND":
      $cards = "";
      $hand = &GetHand($player);
      if(!is_array($lastResult)) $lastResult = explode(",", $lastResult);
      for($i=0; $i<count($lastResult); ++$i)
      {
        if($cards != "") $cards .= ",";
        $cards .= $hand[$lastResult[$i]];
        unset($hand[$lastResult[$i]]);
      }
      $hand = array_values($hand);
      return $cards;
    case "DESTROYCHARACTER":
      $character = &GetPlayerCharacter($player);
      $character[$lastResult+1] = 0;
      DestroyCharacter($player, $lastResult);
      return $lastResult;
    case "DESTROYTHEIRCHARACTER":
      $theirCharacter[$lastResult+1] = 0;
      DestroyCharacter($player == 1 ? 2 : 1, $lastResult);
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
    case "ADDDISCARD":
      AddGraveyard($lastResult, $player, $parameter);
      return $lastResult;
    case "ADDBOTTOMMYDECK":
      array_push($myDeck, $lastResult);
      return $lastResult;
    case "ADDBOTDECK":
      $deck = &GetDeck($player);
      array_push($deck, $lastResult);
      return $lastResult;
    case "MULTIADDDECK":
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        array_push($myDeck, $cards[$i]);
      }
      return $lastResult;
    case "MULTIADDTOPDECK":
      $deck = &GetDeck($player);
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        array_unshift($deck, $cards[$i]);
      }
      return $lastResult;
    case "MULTIREMOVEDECK":
      if(!is_array($lastResult)) $lastResult = ($lastResult == "" ? [] : explode(",", $lastResult));
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
      WriteLog(CardLink($myHand[$lastResult], $myHand[$lastResult]) . " was revealed.");
      return $lastResult;
    case "DECKCARDS":
      $indices = explode(",", $parameter);
      $deck = &GetDeck($player);
      for($i=0; $i<count($indices); ++$i)
      {
        if($rv != "") $rv .= ",";
        $rv .= $deck[$i];
      }
      return $rv;
    case "REVEALCARD":
      WriteLog(CardLink($lastResult, $lastResult) . " was revealed.");
      return $lastResult;
    case "REVEALCARDS":
      $cards = (is_array($lastResult) ? $lastResult : explode(",", $lastResult));
      for($i=0; $i<count($cards); ++$i)
      {
        WriteLog(CardLink($cards[$i], $cards[$i]) . " was revealed.");
      }
      return $lastResult;
    case "REVEALHANDCARDS":
      $indices = (is_array($lastResult) ? $lastResult : explode(",", $lastResult));
      $hand = &GetHand($player);
      $cards = "";
      for($i=0; $i<count($indices); ++$i)
      {
        if($cards != "") $cards .= ",";
        $cards .= $hand[$indices[$i]];
        WriteLog(CardLink($hand[$indices[$i]], $hand[$indices[$i]]) . " was revealed.");
      }
      return $cards;
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
    case "GAINACTIONPOINTS":
      $actionPoints += $parameter;
      return $lastResult;
    case "NOPASS":
      if($lastResult == "NO") return "PASS";
      return 1;
    case "NULLPASS":
      if($lastResult == "") return "PASS";
      return $lastResult;
    case "LESSTHANPASS":
      if($lastResult < $parameter) return "PASS";
      return $lastResult;
    case "GREATERTHANPASS":
      if($lastResult > $parameter) return "PASS";
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
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        if(CardTalent($cards[$i]) != $parameter) return "PASS";
      }
      return $lastResult;
    case "ALLCARDSCOMBOORPASS":
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        if(!HasCombo($cards[$i])) return "PASS";
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
    case "CLASSSTATEGREATERORPASS":
      $parameters = explode("-", $parameter);
      $state = $parameters[0];
      $threshold = $parameters[1];
      if(GetClassState($player, $state) < $threshold) return "PASS";
      return 1;
    case "CHARREADYORPASS":
      $char = &GetPlayerCharacter($player);
      if($char[$parameter+1] != 2) return "PASS";
      return 1;
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
      $discarded = DiscardRandom($player, $cardID);
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
          case "Gain_life": GainHealth(1, $player); GainHealth(1, ($player == 1 ? 2 : 1)); break;
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
    case "RAMPARTOFTHERAMSHEAD":
      if($lastResult == 1)
      {
        AddCharacterEffect($player, $parameter, "ELE203");
      }
      return $lastResult;
    case "PHANTASMALFOOTSTEPS":
      if($lastResult == 1)
      {
        $character = &GetPlayerCharacter($player);
        $character[$parameter+4] = 1;
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
      $hand = &GetHand($player);
      if(count($hand) > 1) PrependDecisionQueue("VOFTHEVANGUARD", $player, "1", 1);
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
    case "SONATAARCANIX":
      $cards = explode(",", $lastResult);
      $numAA = 0; $numNAA = 0;
      $AAIndices = "";
      for($i=0; $i<count($cards); ++$i)
      {
        $cardType = CardType($cards[$i]);
        if($cardType == "A") ++$numNAA;
        else if($cardType == "AA")
        {
          ++$numAA;
          if($AAIndices != "") $AAIndices .= ",";
          $AAIndices .= $i;
        }
      }
      $numMatch = ($numAA > $numNAA ? $numNAA : $numAA);
      if($numMatch == 0) return "PASS";
      return $numMatch . "-" . $AAIndices;
    case "SONATAARCANIXSTEP2":
      $numArcane = count(explode(",", $lastResult));
      DealArcane($numArcane, 0, "PLAYCARD", "MON231", true);
      WriteLog("Sonata Arcanix deals " . $numArcane . " arcane damage.");
      return 1;
    case "SOULREAPING":
      $cards = explode(",", $lastResult);
      if(count($cards) > 0) AddCurrentTurnEffect("MON199", $player);
      $numBD = 0;
      for($i=0; $i<count($cards); ++$i) if(HasBloodDebt($cards[$i])) { ++$numBD; }
      GainResources($player, $numBD);
      return 1;
    case "CHARGE":
      DQCharge();
      return "1";
    case "FINISHCHARGE":
      ++$myClassState[$CS_NumCharged];
      return $lastResult;
    case "CHOOSEHERO":
      return $player == 1 ? 2 : 1;
    case "DEALDAMAGE":
      $target = $lastResult;
      $parameters = explode("-", $parameter);
      $damage = $parameters[0];
      $source = $parameters[1];
      $type = $parameters[2];
      $damage = DealDamage($player, $damage, $type, $source);
      return $damage;
    case "DEALARCANE":
      $target = $lastResult;
      $parameters = explode("-", $parameter);
      $damage = $parameters[0];
      $source = $parameters[1];
      $sourceType = CardType($source);
      if(SearchCurrentTurnEffects("ELE065", $player) && ($sourceType == "A" || $sourceType == "AA")) ++$damage;
      $arcaneBarrier = ArcaneBarrierChoices($target, $damage);
      //Create cancel point
      PrependDecisionQueue("TAKEARCANE", $target, $damage . "-" . $source, 1);
      PrependDecisionQueue("PAYRESOURCES", $target, "<-", 1);
      PrependDecisionQueue("CHOOSEARCANE", $target, $arcaneBarrier, 1, 1);
      return $parameter;
    case "TAKEARCANE":
      $parameters = explode("-", $parameter);
      $damage = $parameters[0];
      $source = $parameters[1];
      $damage = DealDamage($player, $damage - $lastResult, "ARCANE", $source);
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
    case "ADDCLASSSTATE":
      $parameters = explode("-", $parameter);
      IncrementClassState($player, $parameters[0], $parameters[1]);
      return 1;
    case "AFTERFUSE":
      $params = explode("-", $parameter);
      $card = $params[0];
      $elements = $params[1];
      $elementArray = explode(",", $elements);
      for($i=0; $i<count($elementArray); ++$i)
      {
        $element = $elementArray[$i];
        switch($element)
        {
          case "EARTH":  IncrementClassState($player, $CS_NumFusedEarth); break;
          case "ICE": IncrementClassState($player, $CS_NumFusedIce); break;
          case "LIGHTNING": IncrementClassState($player, $CS_NumFusedLightning); break;
          default: break;
        }
      }
      FuseAbility($card, $player, $elements);
      if(CardType($card) == "AA") $combatChainState[$CCS_AttackFused] = 1;
      return $lastResult;
    case "SUBPITCHVALUE":
      return $parameter - PitchValue($lastResult);
    case "BUFFARCANE":
      AddArcaneBonus($parameter, $player);
      return $parameter;
    case "SHIVER":
      switch($lastResult)
      {
        case "1_Attack": AddCurrentTurnEffect("ELE033-1", $player); return 1;
        case "Dominate": AddCurrentTurnEffect("ELE033-2", $player); return 1;
      }
      return $lastResult;
    case "VOLTAIRE":
      switch($lastResult)
      {
        case "1_Attack": AddCurrentTurnEffect("ELE034-1", $player); return 1;
        case "Go_again": AddCurrentTurnEffect("ELE034-2", $player); return 1;
      }
      return $lastResult;
    case "AWAKENINGTOKENS":
        $num = GetHealth($player == 1 ? 2 : 1) - GetHealth($player);
        for($i=0; $i<$num; ++$i)
        {
          PlayAura("WTR075", $player);
        }
      return 1;
    case "DIMENXXIONALGATEWAY":
        $class = CardClass($lastResult);
        $talent = CardTalent($lastResult);
        if($class == "RUNEBLADE") DealArcane(1, 0, "PLAYCARD", "MON161", true);//TODO: Not totally correct
        if($talent == "SHADOW")
        {
          PrependDecisionQueue("MULTIBANISH", $player, "DECK,-", 1);
          PrependDecisionQueue("MULTIREMOVEDECK", $player, "<-", 1);
          PrependDecisionQueue("FINDINDICES", $player, "TOPDECK", 1);
          PrependDecisionQueue("NOPASS", $player, "-", 1);
          PrependDecisionQueue("YESNO", $player, "if_you_want_to_banish_the_card", 1);
        }
      return $lastResult;
    case "INVERTEXISTENCE":
      $cards = explode(",", $lastResult);
      $numAA = 0;
      $numNAA = 0;
      for($i=0; $i<count($cards); ++$i)
      {
        $type = CardType($cards[$i]);
        if($type == "AA") ++$numAA;
        else if($type == "A") ++$numNAA;
      }
      if($numAA == 1 && $numNAA == 1) DealArcane(2, 0, "PLAYCARD", "MON158", true, $player);
      return $lastResult;
    case "ROUSETHEANCIENTS":
      $cards = (is_array($lastResult) ? $lastResult : explode(",", $lastResult));
      $totalAV = 0;
      for($i=0; $i<count($cards); ++$i)
      {
        $totalAV += AttackValue($cards[$i]);
      }
      if($totalAV >= 13)
      {
        AddCurrentTurnEffect("MON247", $player);
        WriteLog("Rouse the Ancients got +7 and Go Again.");
      }
      return $lastResult;
    case "BEASTWITHIN":
      $deck = &GetDeck($player);
      if(count($deck) == 0) { LoseHealth(9999, $player); WriteLog("Your deck has no cards, so Beast Within continues damaging you until you die."); return 1; }
      $card = array_shift($deck);
      if(AttackValue($card) >= 6)
      {
        AddPlayerHand($card, $player, "DECK");
      }
      else
      {
        BanishCardForPlayer($card, $player, "DECK", "-");
        PrependDecisionQueue("BEASTWITHIN", $player, "-");
      }
      return 1;
    case "CROWNOFDICHOTOMY":
      $lastType = CardType($lastResult);
      $indicesParam = ($lastType == "A" ? "GYCLASSAA,RUNEBLADE" : "GYCLASSNAA,RUNEBLADE");
      PrependDecisionQueue("REVEALCARDS", $player, "-", 1);
      PrependDecisionQueue("DECKCARDS", $player, "0", 1);
      PrependDecisionQueue("MULTIADDTOPDECK", $player, "-", 1);
      PrependDecisionQueue("MULTIREMOVEDISCARD", $player, "-", 1);
      PrependDecisionQueue("CHOOSEDISCARD", $player, "<-", 1);
      PrependDecisionQueue("FINDINDICES", $player, $indicesParam);
      return 1;
    case "BECOMETHEARKNIGHT":
      $lastType = CardType($lastResult);
      $indicesParam = ($lastType == "A" ? "DECKCLASSAA,RUNEBLADE" : "DECKCLASSNAA,RUNEBLADE");
      PrependDecisionQueue("MULTIADDHAND", $player, "-", 1);
      PrependDecisionQueue("REVEALCARDS", $player, "-", 1);
      PrependDecisionQueue("CHOOSEDECK", $player, "<-", 1);
      PrependDecisionQueue("FINDINDICES", $player, $indicesParam);
      return 1;
    case "GENESIS":
      if(CardTalent($lastResult) == "LIGHT") Draw($player);
      if(CardClass($lastResult) == "ILLUSIONIST") PlayAura("MON104", $player);
      return 1;
    case "PREPITCHGIVEGOAGAIN":
      if($parameter == "A") SetClassState($player, $CS_NextNAACardGoAgain, 1);
      else if($parameter == "AA") GiveAttackGoAgain();
      return 1;
    case "ADDMYRESOURCES":
      $myResources[1] += $parameter;
      return $parameter;
    case "PROCESSATTACKTARGET":
      $combatChainState[$CCS_AttackTarget] = $lastResult;
      return 1;
    case "STARTTURNABILITIES":
      StartTurnAbilities();
      return 1;
    case "REMOVELAST":
      if($lastResult == "") return $parameter;
      $cards = explode(",", $parameter);
      for($i=0; $i<count($cards); ++$i)
      {
        if($cards[$i] == $lastResult)
        {
          unset($cards[$i]);
          $cards = array_values($cards);
          break;
        }
      }
      return implode(",", $cards);
    case "ROLLDIE":
      $roll = RollDie($player, true);
      return $roll;
    case "SETCOMBATCHAINSTATE":
      $combatChainState[$parameter] = $lastResult;
      return $lastResult;
    case "BANISHADDMODIFIER":
      $banish = &GetBanish($player);
      $banish[$lastResult + 1] = $parameter;
      return $lastResult;
    case "SETLAYERTARGET":
      SetClassState($player, $CS_LayerTarget, $lastResult);
      return $lastResult;
    case "MULTIZONEFORMAT":
      return SearchMultizoneFormat($lastResult, $parameter);
    case "COUNTITEM":
      return CountItem($parameter, $player);
    case "FINDANDDESTROYITEM":
      $params = explode("-", $parameter);
      $cardID = $params[0];
      $number = $params[1];
      for($i=0; $i<$number; ++$i)
      {
        $index = GetItemIndex($cardID, $player);
        if($index != -1) DestroyItemForPlayer($player, $index);
      }
      return $lastResult;
    case "DESTROYITEM":
      DestroyItemForPlayer($player, $parameter);
      return $lastResult;
    case "COUNTPARAM":
      $array = explode(",", $parameter);
      return count($array) . "-" . $parameter;
    case "VALIDATEALLSAMENAME":
      if($parameter == "DECK") { $zone = &GetDeck($player); }
      $indices = explode(",", $lastResult);
      if(count($lastResult) == 0) return "PASS";
      $name = CardName($zone[$lastResult[0]]);
      for($i=1; $i<count($lastResult); ++$i)
      {
        if(CardName($zone[$lastResult[$i]]) != $name)
        {
          WriteLog("You selected cards that do not have the same name. Reverting gamestate prior to that effect.");
          RevertGamestate();
          return "PASS";
        }
      }
      return $lastResult;
    case "PREPENDLASTRESULT":
      return $parameter . $lastResult;
    case "VALIDATECOUNT":
      if(count($lastResult) != $parameter)
      {
        WriteLog("The count from the last step is incorrect. Reverting gamestate prior to that effect.");
        RevertGamestate();
        return "PASS";
      }
      return $lastResult;
    case "SOULHARVEST":
      $numBD = 0;
      $discard = GetDiscard($player);
      for($i=0; $i<count($lastResult); ++$i)
      {
        if(HasBloodDebt($discard[$lastResult[$i]])) ++$numBD;
      }
      if($numBD > 0) AddCurrentTurnEffect("MON198," . $numBD, $player);
      return $lastResult;
    case "ADDATTACKCOUNTERS":
      $lastResults = explode("-", $lastResult);
      $zone = $lastResults[0];
      $zoneDS = &GetMZZone($player, $zone);
      $index = $lastResults[1];
      if($zone == "MYCHAR" || $zone == "THEIRCHAR") $zoneDS[$index+3] += $parameter;
      else if($zone == "MYAURAS" || $zone == "THEIRAURAS") $zoneDS[$index+3] += $parameter;
      return $lastResult;
    default:
      return "NOTSTATIC";
  }
}

?>

