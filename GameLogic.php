<?php

include "Search.php";
include "CardLogic.php";
include "AuraAbilities.php";
include "ItemAbilities.php";
include "AllyAbilities.php";
include "PermanentAbilities.php";
include "LandmarkAbilities.php";
include "WeaponLogic.php";
include "MZLogic.php";

function PlayAbility($cardID, $from, $resourcesPaid, $target="-", $additionalCosts="-")
{
  global $mainPlayer, $CS_NumBoosted, $combatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $currentPlayer, $defPlayer, $actionPoints;
  global $CS_AtksWWeapon, $CS_DamagePrevention, $CS_Num6PowDisc, $CCS_DamageDealt, $CCS_WeaponIndex, $CS_NextDamagePrevented, $CS_CharacterIndex, $CS_PlayIndex;
  global $CS_NumNonAttackCards, $CS_ArcaneDamageTaken, $CS_NextWizardNAAInstant, $CS_NumWizardNonAttack;
  global $CCS_BaseAttackDefenseMax, $CCS_NumChainLinks, $CCS_ResourceCostDefenseMin, $CCS_CardTypeDefenseRequirement;
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  if(($set == "ELE" || $set == "UPR") && $additionalCosts != "-" && HasFusion($cardID))
  {
    FuseAbility($cardID, $currentPlayer, $additionalCosts);
  }
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
  else if($set == "EVR")
  {
    return EVRPlayAbility($cardID, $from, $resourcesPaid);
  }
  else if($set == "UPR")
  {
    return UPRPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  }
  else if($set == "DVR")
  {
    return DVRPlayAbility($cardID, $from, $resourcesPaid);
  }
  else if($set == "RVD")
  {
    return RVDPlayAbility($cardID, $from, $resourcesPaid);
  }
  $rv = "";
  switch($cardID)
  {
    case "WTR054": case "WTR055": case "WTR056":
      if(CountPitch(GetPitch($currentPlayer), 3) >= 1) MyDrawCard();
      return CountPitch(GetPitch($currentPlayer), 3) . " cards in pitch.";
    case "WTR004":
      $roll = GetDieRoll($currentPlayer);
      $actionPoints += intval($roll/2);
      return "Scabskin Leathers rolled $roll and gained " . intval($roll/2) . " action points.";
    case "WTR005":
      $resources = &GetResources($currentPlayer);
      $roll = GetDieRoll($currentPlayer);
      $resources[0] += intval($roll/2);
      return "Barkbone Strapping rolled $roll and gained " . intval($roll/2) . " resources.";
    case "WTR006":
      DiscardRandom($currentPlayer, $cardID);
      Intimidate();
      return "Alpha Rampage intimidated.";
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
      return "Bloodrush Bellow discarded " . CardLink($discarded, $discarded) . ($drew == 1 ? ", " : ", and ") . "gave your Brute attacks this turn +2" . ($drew == 1 ? ", drew two cards, and gained Go Again." : ".");
    case "WTR008":
      $damaged = false;
      if(AttackValue($additionalCosts) >= 6) { $damaged = true; DamageTrigger($mainPlayer, 2, "DAMAGE", $cardID); }
      return "Reckless Swing discarded a random card from your hand" . ($damaged ? " and did 2 damage." : ".");
    case "WTR009":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "DECK");
      AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDMYHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("SANDSKETCH", $currentPlayer, "-");
      return "";
    case "WTR010":
      $roll = GetDieRoll($currentPlayer);
      IncrementClassState($currentPlayer, $CS_DamagePrevention, $roll);
      return "Bone Head Barrier prevents the next $roll damage that will be dealt to you this turn.";
    case "WTR011": case "WTR012": case "WTR013":
      $discarded = DiscardRandom($currentPlayer, $cardID);
      $drew = 0;
      if(AttackValue($discarded) >= 6) { $drew = 1; $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; }
      return "Breakneck Battery discarded a random card from your hand" . ($drew ? " and gained Go Again." : ".");
    case "WTR014": case "WTR015": case "WTR016":
      $discarded = DiscardRandom($currentPlayer, $cardID);
      $drew = 0;
      if(AttackValue($discarded) >= 6) { $drew = 1; MyDrawCard(); }
      return "Savage Feast discarded a random card from your hand" . ($drew ? " and drew a card." : ".");
    case "WTR017": case "WTR018": case "WTR019":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      Intimidate();
      return "Barraging Beatdown intimidates and gives the next Brute attack this turn +" . EffectAttackModifier($cardID) . ".";
    case "WTR020": case "WTR021": case "WTR022":
      DiscardRandom($currentPlayer, $cardID);
      return "";
    case "WTR023": case "WTR024": case "WTR025":
      Intimidate();
      return "Pack Hunt intimidated.";
    case "WTR026": case "WTR027": case "WTR028":
      Intimidate();
      return "Smash Instinct intimidated.";
    case "WTR029": case "WTR030": case "WTR031":
      DiscardRandom($currentPlayer, $cardID);
      return "";
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
      AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKCLASSAA,GUARDIAN");
      AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDMYHAND", $currentPlayer, "-", 1);
      return "Show Time! allows you to search for a Guardian attack card.";
    //Ninja
    case "WTR078":
      if(CountPitch(GetPitch($currentPlayer), 0, 0)) $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
      return "";
    case "WTR082":
      MyDrawCard();
      return "Ancestral Empowerment drew a card.";
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
      AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDMZBUFF", $mainPlayer, $cardID, 1);
      return "";
    case "WTR120":
      if(RepriseActive())
      {
        $options = GetChainLinkCards(($mainPlayer == 1 ? 2 : 1), "", "E,C");
        AddDecisionQueue("CHOOSECOMBATCHAIN", $mainPlayer, $options);
        AddDecisionQueue("REMOVECOMBATCHAIN", $mainPlayer, "-");
        AddDecisionQueue("ADDHAND", $defPlayer, "-");
      }
      return "";
    case "WTR121":
      if(RepriseActive())
      {
        $ARs = SearchDeck($currentPlayer, "AR");
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, $ARs);
        AddDecisionQueue("BANISH", $currentPlayer, "TCL");
      }
      return "";
    case "WTR123": case "WTR124": case "WTR125":
      if(CardType($combatChain[0]) != "W") return "Overpower did nothing, because this is not a weapon attack.";
      return "Overpower gave your weapon attack +" . AttackModifier($cardID) . ".";
    case "WTR126": case "WTR127": case "WTR128":
      $text = "Steelblade Shunt ";
      if(CardType($combatChain[0]) == "W")
      {
        DamageTrigger($mainPlayer, 1, "DAMAGE", $cardID);
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
      $resources = &GetResources($currentPlayer);
      $resources[0] += 1;
      return "Fyendal's Spring Tunic added 1 resource.";
    case "WTR151":
      $indices = GetMyHandIndices();
      if($indices == "") return "";
      AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, count(GetHand($currentPlayer)) . "-" . $indices);
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
      return "Enlightened Strike put a card from your hand to the bottom of your deck.";
    case "WTR160":
      MyDrawCard();
      MyDrawCard();
      $hand = GetHand($currentPlayer); //Get hand size after draw for correct health gain
      if($from == "ARS") GainHealth(count($hand), $currentPlayer);
      return "Tome of Fyendal drew two cards" . ($from == "ARS" ? " and gained " . count($hand) . " life" : "") . ".";
    case "WTR161":
      if(count(GetDeck($currentPlayer)) == 0) {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        AddCurrentTurnEffect($cardID, $currentPlayer);
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
          $resources = &GetResources($currentPlayer);
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $resources[0] += 2;
          $actionPoints += 2;
          $rv .= " and gained 2 action points, resources, and damage.";
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
    case "WTR173": GainHealth(3, $currentPlayer); return "Sigil of Solace gained 3 health.";
    case "WTR174": GainHealth(2, $currentPlayer); return "Sigil of Solace gained 2 health.";
    case "WTR175": GainHealth(1, $currentPlayer); return "Sigil of Solace gained 1 health.";
    case "WTR182": case "WTR183": case "WTR184":
      PlayMyAura("WTR225");
      return "Flight of the Feather Walkers created a Quicken token.";
    case "WTR185": case "WTR186": case "WTR187":
      $indices = SearchDiscardForCard($currentPlayer, "WTR218", "WTR219", "WTR220");
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
      $indices = SearchDiscardForCard($currentPlayer, "WTR221", "WTR222", "WTR223");
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
      if(GetClassState($currentPlayer, $CS_Num6PowDisc) > 0)
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
      if(GetClassState($currentPlayer, $CS_Num6PowDisc) > 0)
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
      if(CountPitch(GetPitch($currentPlayer), 3) >= 2) { AddCurrentTurnEffect($cardID, $currentPlayer); $rv = "Stamp Authority gives you +1 intellect until end of turn."; }
      return $rv;
    case "CRU041": case "CRU042": case "CRU043":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Blessing of Serenity will prevent some of the next combat damage you take this turn.";
    //Ninja
    case "CRU054":
      if(ComboActive())
      {
        $combatChainState[$CCS_ResourceCostDefenseMin] = $combatChainState[$CCS_NumChainLinks];
        $rv = "Find Center cannot be defended by cards with resource cost less than " . $combatChainState[$CCS_NumChainLinks] . ".";
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
      $character = &GetPlayerCharacter($currentPlayer);
      if($character[$combatChainState[$CCS_WeaponIndex]+1] != 0) { $character[$combatChainState[$CCS_WeaponIndex]+1] = 2; }
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
      AddCurrentTurnEffect($cardID . "-2", ($mainPlayer == 1 ? 2 : 1));
      return "Dauntless gives your next weapon attack  +" . EffectAttackModifier($cardID . "-1") . " and makes the next Defense Reaction cost +1 to play.";
    case "CRU088": case "CRU089": case "CRU090":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      if(RepriseActive()) AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
      return "Out for Blood gave your weapon attack +" . EffectAttackModifier($cardID . "-1") . RepriseActive() ? " and gives your next attack +1." : ".";
    case "CRU091": case "CRU092": case "CRU093":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      $atkWWpn = GetClassState($currentPlayer, $CS_AtksWWeapon) > 0;
      if($atkWWpn) AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
      return "Hit and Run gives your next weapon attack Go Again" . ($atkWWpn ? " and +" . EffectAttackModifier($cardID . "-2") : "") . ".";
    case "CRU094": case "CRU095": case "CRU096":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      $atkWWpn = GetClassState($currentPlayer, $CS_AtksWWeapon) > 0;
      if($atkWWpn) AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
      return "Push Forward gives your next weapon attack +" . EffectAttackModifier($cardID . "-1") . ($atkWWpn ? " and gives your next attack Dominate." : ".");
    //CRU Mechanologist
    case "CRU101":
      $character = &GetPlayerCharacter($currentPlayer);
      $character[GetClassState($currentPlayer, $CS_CharacterIndex) + 2] = ($character[GetClassState($currentPlayer, $CS_CharacterIndex) + 2] == 0 ? 1 : 0);
      return "";
    case "CRU102":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU103":
      return "Meganetic Shockwave is a manual card. The opponent must block with " . GetClassState($currentPlayer, $CS_NumBoosted) . " equipment if able.";
    case "CRU105":
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      if($index != -1)
      {
        $items = &GetItems($currentPlayer);
        $items[$index + 1] = ($items[$index + 1] == 0 ? 1 : 0);
        if($items[$index + 1] == 0)
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
          AddDecisionQueue("CHOOSECHARACTER", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDCHARACTEREFFECT", $currentPlayer, $cardID, 1);
          $items[$index + 2] = 1;
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
      Opt($cardID, GetClassState($currentPlayer, $CS_NumBoosted));
      AddDecisionQueue("DECKCARDS", $currentPlayer, "0");
      AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
      AddDecisionQueue("ALLCARDSUBTYPEORPASS", $currentPlayer, "Item", 1);
      AddDecisionQueue("ALLCARDMAXCOSTORPASS", $currentPlayer, $maxCost, 1);
      AddDecisionQueue("FINDINDICES", $currentPlayer, "TOPDECK", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
      return "Teklovossen's Workshop lets you opt and put an item from the top of your deck into play.";
    //CRU Merchant
    case "CRU118":
      if(PlayerHasLessHealth(1))
      {
        LoseHealth(1, 2);
        PutItemIntoPlayForPlayer("CRU197", 2);
        WriteLog("Player 2 lost a health and gained a copper from Kavdaen");
        if(PlayerHasLessHealth(1)) { GainHealth(1, 1); WriteLog("Player 1 gained a health from Kavdaen"); }
      }
      else if(PlayerHasLessHealth(2))
      {
        LoseHealth(1, 1);
        PutItemIntoPlayForPlayer("CRU197", 1);
        WriteLog("Player 1 lost a health and gained a copper from Kavdaen");
        if(PlayerHasLessHealth(2)) { GainHealth(1, 2); WriteLog("Player 2 gained a health from Kavdaen"); }
      }
      return "";
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
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_pay_1_to_allow_hit_effects_this_chain_link", 1, 1);
      AddDecisionQueue("FINDRESOURCECOST", $otherPlayer, $cardID, 1);
      AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
      AddDecisionQueue("TRIPWIRETRAP", $otherPlayer, "-", 1);
      return "";
    case "CRU127":
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_pay_1_to_avoid_taking_two_damage", 1, 1);
      AddDecisionQueue("FINDRESOURCECOST", $otherPlayer, $cardID, 1);
      AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
      AddDecisionQueue("PITFALLTRAP", $otherPlayer, "-", 1);
      return "";
    case "CRU128":
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_pay_1_to_avoid_your_attack_getting_-1", 1, 1);
      AddDecisionQueue("FINDRESOURCECOST", $otherPlayer, $cardID, 1);
      AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
      AddDecisionQueue("ROCKSLIDETRAP", $otherPlayer, "-", 1);
      return "";
    case "CRU135": case "CRU136": case "CRU137":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect($cardID . "-1", ($currentPlayer == 1 ? 2 : 1));
      return "Increase the tension gives the next arrow attack this turn +" . EffectAttackModifier($cardID) . " and prevents defense reactions on that chain link.";
    //CRU Runeblade
    case "CRU141":
      AddCurrentTurnEffect($cardID . "-AA", $currentPlayer);
      AddCurrentTurnEffect($cardID . "-NAA", $currentPlayer);
      return "Bloodsheath Skeleta reduces the cost of your next attack action card and non-attack action card this turn.";
    case "CRU142":
      if(GetClassState($currentPlayer, $CS_NumNonAttackCards) > 0) PlayAura("ARC112", $currentPlayer);
      AddDecisionQueue("CLASSSTATEGREATERORPASS", $defPlayer, $CS_ArcaneDamageTaken . "-1", 1);
      AddDecisionQueue("PLAYAURA", $currentPlayer, "ARC112", 1);
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
      DealArcane(2, 1, "PLAYCARD", $cardID);
      return "Aether Conduit deals 2 arcane damage.";
    case "CRU162":
      $rv = "Chain lightning let you play your next Wizard non-attack as an instant";
      SetClassState($currentPlayer, $CS_NextWizardNAAInstant,1);
      if(GetClassState($currentPlayer, $CS_NumWizardNonAttack) >= 2)
      {
        DealArcane(3, 1, "PLAYCARD", $cardID);//TODO: All opponents
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
      DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID);
      Opt($cardID, 1);
      return "";
    case "CRU171": case "CRU172": case "CRU173":
      DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID);
      AddArcaneBonus(1, $currentPlayer);
      return "";
    case "CRU174": case "CRU175": case "CRU176":
      DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID);
      return "";
    //CRU Generics
    case "CRU181":
      $count = SearchCount(CombineSearches(SearchDiscardForCard(1, "CRU181"), SearchDiscardForCard(2, "CRU181")));
      for($i=0; $i<$count; ++$i) { MyDrawCard(); }
      return "Gorganian Tome drew " . $count . " cards.";
    case "CRU182":
      AddCurrentTurnEffect("CRU182", ($currentPlayer == 1 ? 2 : 1));
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
  global $combatChain,$defArsenal, $mainClassState, $CS_HitsWDawnblade, $combatChainState, $CCS_WeaponIndex, $mainPlayer, $mainCharacter, $defCharacter, $mainArsenal, $CCS_ChainLinkHitEffectsPrevented, $CCS_NextBoostBuff, $CS_ArcaneDamageTaken, $CCS_HitsInRow;
  global $mainPlayer, $defPlayer;
  $attackID = $combatChain[0];
  if(CardType($cardID) == "AA" && SearchAuras("CRU028", 1) || SearchAuras("CRU028", 2)) return;
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
  else if($set == "EVR")
  {
    return EVRHitEffect($cardID);
  }
  else if($set == "UPR")
  {
    return UPRHitEffect($cardID);
  }
  else if($set == "DVR")
  {
    return DVRHitEffect($cardID);
  }
  else if($set == "RVD")
  {
    return RVDHitEffect($cardID);
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
      if(ComboActive()){
        AddDecisionQueue("ADDMAINHAND", $mainPlayer, "-"); //Only back to hand if combo is active
      }
      break;
    case "WTR110": case "WTR111": case "WTR112": if(ComboActive()) { WriteLog("Whelming Gustwave drew a card."); MainDrawCard(); } break;
    case "WTR115":
     if($mainClassState[$CS_HitsWDawnblade] == 1 && $CCS_WeaponIndex < count($combatChainState)) { ++$mainCharacter[$combatChainState[$CCS_WeaponIndex]+3]; }
     ++$mainClassState[$CS_HitsWDawnblade];
    break;
    case "WTR167": case "WTR168": case "WTR169": MainDrawCard(); break;
    case "WTR206": case "WTR207": case "WTR208": if(CardType($attackID) == "AA") PummelHit(); break;
    case "WTR209": case "WTR210": case "WTR211": if(CardType($attackID) == "AA") GiveAttackGoAgain(); break;
    case "CRU054": if(ComboActive()) { PlayAura("CRU075", $mainPlayer); } break;
    case "CRU060": case "CRU061": case "CRU062": if(ComboActive()) RushingRiverHitEffect(); break;
    case "CRU066": case "CRU067": case "CRU068": GiveAttackGoAgain(); break;
    case "CRU069": case "CRU070": case "CRU071": GiveAttackGoAgain(); break;
    case "CRU072": AddCurrentTurnEffectFromCombat($cardID, $mainPlayer); break;
    case "CRU074": if($combatChainState[$CCS_HitsInRow] >= 2) { MainDrawCard(); MainDrawCard(); } break;
    case "CRU106": case "CRU107": case "CRU108": AddCurrentTurnEffectFromCombat($cardID, $mainPlayer); break;
    case "CRU109": case "CRU110": case "CRU111": $combatChainState[$CCS_NextBoostBuff] += 3; break;
    case "CRU123": AddCurrentTurnEffect("CRU123-DMG", $defPlayer); AddNextTurnEffect("CRU123-DMG", $defPlayer); break;
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
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose any number of options");
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

function ProcessMissEffect($cardID)
{
  global $defPlayer;
  switch($cardID)
  {
    case "EVR002": PlayAura("WTR225", $defPlayer);
    default: break;
  }
}

function ChainLinkBeginResolutionEffects()
{
  global $combatChain, $mainPlayer, $defPlayer, $CCS_CombatDamageReplaced, $combatChainState, $CCS_WeaponIndex;
  if(CardType($combatChain[0]) == "W")
  {
    $mainCharacterEffects = &GetMainCharacterEffects($mainPlayer);
    $index = $combatChainState[$CCS_WeaponIndex];
    for($i=0; $i<count($mainCharacterEffects); $i+=CharacterEffectPieces())
    {
      WriteLog($mainCharacterEffects[$i]);
      if($mainCharacterEffects[$i] == $index)
      {
        switch($mainCharacterEffects[$i+1])
        {
          case "EVR054":
            $pendingDamage = CachedTotalAttack() - CachedTotalBlock();
            AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Currently $pendingDamage damage would be dealt. Do you want to destroy a defending equipment instead?");
            AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_destroy_a_blocking_equipment_instead_of_dealing_damage");
            AddDecisionQueue("NOPASS", $mainPlayer, "-");
            AddDecisionQueue("PASSPARAMETER", $mainPlayer, "1", 1);
            AddDecisionQueue("SETCOMBATCHAINSTATE", $mainPlayer, $CCS_CombatDamageReplaced, 1);
            AddDecisionQueue("FINDINDICES", $defPlayer, "SHATTER,$pendingDamage", 1);
            AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
            AddDecisionQueue("DESTROYCHARACTER", $defPlayer, "-", 1);
            break;
          default: break;
        }
      }
    }
  }
}

function CombatChainResolutionEffects()
{
  global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $combatChain, $mainPlayer;
  for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
  {
    $cardID = $combatChain[$i-1];
    switch($cardID)
    {
      case "CRU010": case "CRU011": case "CRU012":
          if($i == 1 && NumCardsBlocking() < 2) GiveAttackGoAgain(); break;
        case "MON248": case "MON249": case "MON250":
          if($i == 1 && SearchHighestAttackDefended() < AttackValue($cardID)) GiveAttackGoAgain(); break;
        case "MON293": case "MON294": case "MON295":
          $mainPitch = &GetPitch($mainPlayer);
          if($i == 1 && SearchPitchHighestAttack($mainPitch) > AttackValue($cardID)) GiveAttackGoAgain(); break;
        case "ELE216": case "ELE217": case "ELE218":
          if($i == 1 && HasIncreasedAttack()) GiveAttackGoAgain(); break;
        default: break;
      }
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
      AddDecisionQueue("FINDINDICES", $defPlayer, "EQUIP");
      AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-");
      AddDecisionQueue("ADDNEGDEFCOUNTER", $defPlayer, "-", 1);
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
  global $CS_NumNonAttackCards, $CS_NumPlayedFromBanish, $CCS_NumChainLinks, $CS_NumAuras, $CS_AtksWWeapon;
  if($repriseActive == -1) $repriseActive = RepriseActive();
  switch($cardID)
  {
    case "WTR003": return (GetClassState($mainPlayer, $CS_Num6PowDisc) > 0 ? 1 : 0);
    case "WTR040": return CountPitch($mainPitch, 3) >= 2 ? 2 : 0;
    case "WTR080": return 1;
    case "WTR081": return $resourcesPaid;
    case "WTR082": return 1;
    case "WTR083": return (ComboActive() ? 1 : 0);
    case "WTR084": return (ComboActive() ? 1 : 0);
    case "WTR086": case "WTR087": case "WTR088": return (ComboActive() ? $combatChainState[$CCS_NumHits] : 0);
    case "WTR089": case "WTR090": case "WTR091": return (ComboActive() ? 3 : 0);
    case "WTR095": case "WTR096": case "WTR097": return (ComboActive() ? 1 : 0);
    case "WTR104": case "WTR105": case "WTR106": return (ComboActive() ? 2 : 0);
    case "WTR110": case "WTR111": case "WTR112": return (ComboActive() ? 1 : 0);
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
    case "ARC077": return GetClassState($mainPlayer, $CS_NumNonAttackCards) > 0 ? 3 : 0;
    case "ARC188": case "ARC189": case "ARC190": return $combatChainState[$CCS_HitsInRow] > 0 ? 2 : 0;
    case "CRU016": case "CRU017": case "CRU018": return GetClassState($mainPlayer, $CS_Num6PowDisc) > 0 ? 1 : 0;
    case "CRU056": return ComboActive() ? 2 : 0;
    case "CRU057": case "CRU058": case "CRU059": return ComboActive() ? 1 : 0;
    case "CRU060": case "CRU061": case "CRU062": return ComboActive() ? 1 : 0;
    case "CRU063": case "CRU064": case "CRU065": return $combatChainState[$CCS_NumChainLinks] >= 3 ? 2 : 0;
    case "CRU073": return $combatChainState[$CCS_NumHits];
    case "CRU083": return 3;
    case "CRU112": case "CRU113": case "CRU114": return $combatChainState[$CCS_NumBoosted];
    case "CRU186": return 1;
    case "MON031": return GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 3 : 0;
    case "MON039": case "MON040": case "MON041": return GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 3 : 0;
    case "MON057": return GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 3 : 0;
    case "MON058": return GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 2 : 0;
    case "MON059": return GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 1 : 0;
    case "MON155": return GetClassState($mainPlayer, $CS_NumPlayedFromBanish) > 0 ? 2 : 0;
    case "MON171": case "MON172": case "MON173": return GetClassState($defPlayer, $CS_ArcaneDamageTaken) > 0 ? 2 : 0;
    case "MON254": case "MON255": case "MON256": return GetClassState($mainPlayer, $CS_CardsBanished) > 0 ? 2 : 0;
    case "MON284": case "MON285": case "MON286": return NumCardsBlocking() < 2 ? 1 : 0;
    case "MON287": case "MON288": case "MON289": return NumCardsBlocking();
    case "MON290": case "MON291": case "MON292": return count($mainAuras) >= 1 ? 1 : 0;
    case "ELE082": case "ELE083": case "ELE084": return GetClassState($defPlayer,  $CS_ArcaneDamageTaken) >= 1 ? 2 : 0;
    case "ELE134": case "ELE135": case "ELE136": return $from == "ARS" ? 1 : 0;
    case "ELE202": return CountPitch($mainPitch, 3) >= 1 ? 1 : 0;
    case "EVR038": return (ComboActive() ? 3 : 0);
    case "EVR040": return (ComboActive() ? 2 : 0);
    case "EVR041": case "EVR042": case "EVR043": return (ComboActive() ? CountCardOnChain("EVR041", "EVR042", "EVR043") : 0);
    case "EVR063": return 3;
    case "EVR064": return 2;
    case "EVR065": return 1;
    case "EVR105": return (GetClassState($mainPlayer, $CS_NumAuras) >= 2 ? 1 : 0);
    case "EVR116": case "EVR117": case "EVR118": return (GetClassState($mainPlayer, $CS_NumAuras) > 0 ? 3 : 0);
    case "DVR002": return GetClassState($mainPlayer, $CS_AtksWWeapon) >= 1 ? 1 : 0;
    case "RVD009": return IntimidateCount($mainPlayer) > 0 ? 2 : 0;
    case "UPR048": return (NumPhoenixFlameChainLinks() >= 2 ? 2 : 0);
    case "UPR098": return (RuptureActive() ? 3 : 0);
    case "UPR101": return (NumDraconicChainLinks() >= 2 ? 1 : 0);
    default: return 0;
  }
}

//Return 1 if the effect should be removed
function EffectHitEffect($cardID)
{
  global $combatChainState, $CCS_GoesWhereAfterLinkResolves, $defPlayer, $mainPlayer, $CCS_WeaponIndex;
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
    case "ELE037-2": DamageTrigger($defPlayer, 1, "ATTACKHIT"); break;
    case "ELE047": case "ELE048": case "ELE049": DamageTrigger($defPlayer, 1, "ATTACKHIT"); break;
    case "ELE066-HIT": if(HasIncreasedAttack()) MainDrawCard(); break;
    case "ELE092-BUFF": DamageTrigger($defPlayer, 3, "ATTACKHIT"); break;
    case "ELE151-HIT": case "ELE152-HIT": case "ELE153-HIT": PlayAura("ELE111", $defPlayer); break;
    case "ELE163":
      PlayAura("ELE111", $defPlayer);
    case "ELE164":
      PlayAura("ELE111", $defPlayer);
    case "ELE165":
      PlayAura("ELE111", $defPlayer);
      break;
    case "ELE173": DamageTrigger($defPlayer, 1, "ATTACKHIT"); return 1;
    case "ELE195": case "ELE196": case "ELE197": DamageTrigger($defPlayer, 1, "ATTACKHIT"); break;
    case "ELE198": case "ELE199": case "ELE200":
      if($cardID == "ELE198") $damage = 3;
      else if($cardID == "ELE199") $damage = 2;
      else $damage = 1;
      DamageTrigger($defPlayer, $damage, "ATTACKHIT");
      return 1;
    case "ELE205": PummelHit(); PummelHit(); break;
    case "ELE215": AddNextTurnEffect($cardID . "-1", $defPlayer); break;
    case "EVR047-1": case "EVR048-1": case "EVR049-1": $idArr = explode("-", $cardID); AddCurrentTurnEffectFromCombat($idArr[0] . "-2", $mainPlayer); break;
    case "EVR066-1": case "EVR067-1": case "EVR068-1": PutItemIntoPlayForPlayer("CRU197", $mainPlayer); return 1;
    case "EVR161-1": GainHealth(2, $mainPlayer); break;
    case "EVR164": PutItemIntoPlayForPlayer("CRU197", $mainPlayer, 0, 6); break;
    case "EVR165": PutItemIntoPlayForPlayer("CRU197", $mainPlayer, 0, 4); break;
    case "EVR166": PutItemIntoPlayForPlayer("CRU197", $mainPlayer, 0, 2); break;
    case "EVR170-1": case "EVR171-1": case "EVR172-1":
      AddDecisionQueue("FINDINDICES", $defPlayer, "ITEMSMAX,2");
      AddDecisionQueue("CHOOSETHEIRITEM", $mainPlayer, "<-", 1);
      AddDecisionQueue("DESTROYITEM", $defPlayer, "<-", 1);
      return 1;
    case "DVR008-1": $char = &GetPlayerCharacter($mainPlayer); ++$char[$combatChainState[$CCS_WeaponIndex]+3]; break;
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
  else if($set == "EVR")
  {
    return EVREffectAttackModifier($cardID);
  }
  else if($set == "DVR")
  {
    return DVREffectAttackModifier($cardID);
  }
  else if($set == "RVD")
  {
    return RVDEffectAttackModifier($cardID);
  }
  else if($set == "UPR")
  {
    return UPREffectAttackModifier($cardID);
  }
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
    case "ELE000-2": return 1;
    case "ELE143": return 1;
    default: return 0;
  }
}

function BlockModifier($cardID, $from, $resourcesPaid)
{
  global $defAuras, $defAuras, $defPlayer, $CS_CardsBanished, $mainPlayer, $CS_ArcaneDamageTaken, $combatChain;
  $blockModifier = 0;
  $cardType = CardType($cardID);
  $cardTalent = CardTalent($cardID);
  if($cardType == "AA") $blockModifier += CountCurrentTurnEffects("ARC160-1", $defPlayer);
  if($cardType == "AA") $blockModifier += CountCurrentTurnEffects("EVR186", $defPlayer);
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
    case "WTR051": case "WTR052": case "WTR053": $blockModifier += ($resourcesPaid >= 6 ? 3 : 0); break;
    case "ARC150": $blockModifier += (DefHasLessHealth() ? 1 : 0); break;
    case "CRU187": $blockModifier += ($from == "ARS" ? 2 : 0); break;
    case "MON075": case "MON076": case "MON077": return GetClassState($mainPlayer, $CS_CardsBanished) >= 3 ? 2 : 0;
    case "MON290": case "MON291": case "MON292": return count($defAuras) >= 1 ? 1 : 0;
    case "ELE227": case "ELE228": case "ELE229": return GetClassState($mainPlayer, $CS_ArcaneDamageTaken) > 0 ? 1 : 0;
    case "EVR050": case "EVR051": case "EVR052": return (CardCost($combatChain[0]) == 0 && CardType($combatChain[0]) == "AA" ? 2 : 0);
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
  global $CS_NumCharged, $currentPlayer, $combatChain, $CS_LayerTarget;
  switch($cardID)
  {
    case "ARC080": return (-1 * NumRunechants($currentPlayer));
    case "ARC082": return (-1 * NumRunechants($currentPlayer));
    case "ARC088": case "ARC089": case "ARC090": return (-1 * NumRunechants($currentPlayer));
    case "ARC094": case "ARC095": case "ARC096": return (-1 * NumRunechants($currentPlayer));
    case "ARC097": case "ARC098": case "ARC099": return (-1 * NumRunechants($currentPlayer));
    case "ARC100": case "ARC101": case "ARC102": return (-1 * NumRunechants($currentPlayer));
    case "MON032": return (-1 * (2 * GetClassState($currentPlayer, $CS_NumCharged)));
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

function RemoveCurrentEffect($player, $effectID)
{
  global $currentTurnEffects;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i>=0; $i-=CurrentTurnPieces())
  {
    if($currentTurnEffects[$i+1] == $player && $currentTurnEffects[$i] == $effectID)
    {
      $remove = 0;
      RemoveCurrentTurnEffect($i);
    }
  }
  $currentTurnEffects = array_values($currentTurnEffects);
}

function CurrentEffectChainClosedEffects()
{
  global $currentTurnEffects, $currentPlayer;
  $costModifier = 0;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i>=0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    switch($currentTurnEffects[$i])
    {
      case "CRU106": case "CRU107": case "CRU108": $remove = 1; break;
      default: break;
    }
    if($remove == 1) RemoveCurrentTurnEffect($i);
  }
  return $costModifier;
}

function CurrentEffectBaseAttackSet($cardID)
{
  global $currentPlayer, $currentTurnEffects;
  $currentModifier = -1;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i>=0; $i-=CurrentTurnPieces())
  {
    $mod = -1;
    if($currentTurnEffects[$i+1] == $currentPlayer && IsCombatEffectActive($currentTurnEffects[$i]))
    {
      $remove = 0;
      switch($currentTurnEffects[$i])
      {
        case "UPR155": $mod = 8; break;
        case "UPR156": $mod = 7; break;
        case "UPR157": $mod = 6; break;
        default: break;
      }
      if($mod > $currentModifier) $currentModifier = $mod;
    }
  }
  return $currentModifier;
}

function CurrentEffectCostModifiers($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer, $CS_PlayUniqueID;
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
        case "EVR161": $costModifier -= 999; $remove = 1; break;
        case "ARC060": case "ARC061": case "ARC062": if(CardType($cardID) == "AA" || GetAbilityType($cardID) == "AA") {$costModifier += 1; $remove = 1; } break;
        case "ELE035-1": $costModifier += 1; break;
        case "ELE038": case "ELE039": case "ELE040": $costModifier += 1; break;
        case "ELE144": $costModifier += 1; break;
        case "EVR179": if(IsStaticType(CardType($cardID), $from, $cardID)) $costModifier -= 1; $remove = 1; break;
        case "UPR000": if(DelimStringContains(CardTalent($cardID), "DRACONIC")) { $costModifier -= 1; --$currentTurnEffects[$i+3]; if($currentTurnEffects[$i+3] <= 0) $remove = 1; }
        case "UPR075": case "UPR076": case "UPR077": if(GetClassState($currentPlayer, $CS_PlayUniqueID) == $currentTurnEffects[$i+2]) { --$costModifier; $remove = 1; } break;
        case "UPR166": if(IsStaticType(CardType($cardID), $from, $cardID) && DelimStringContains(CardSubType($cardID), "Staff")) $costModifier -= 3; $remove = 1; break;
        default: break;
      }
      if($remove == 1) RemoveCurrentTurnEffect($i);
    }
  }
  return $costModifier;
}

function BanishCostModifier($from, $index)
{
  global $currentPlayer;
  if($from != "BANISH") return 0;
  $banish = GetBanish($currentPlayer);
  $mod = explode("-", $banish[$index+1]);
  switch($mod[0])
  {
    case "ARC119": return -1 * intval($mod[1]);
    default: return 0;
  }
}

function CurrentEffectDamagePrevention($player, $type, $damage)
{
  global $currentTurnEffects;
  $prevention = 0;
  for($i=count($currentTurnEffects)-CurrentTurnEffectPieces(); $i>=0 && $prevention < $damage; $i-=CurrentTurnEffectPieces())
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
        case "EVR033": $prevention += 6; $remove = 1; break;
        case "EVR034": $prevention += 5; $remove = 1; break;
        case "EVR035": $prevention += 4; $remove = 1; break;
        case "EVR180": $prevention += 1; $remove = 1; break;
        case "UPR183": $prevention += 1; $remove = 1; break;
        case "UPR221": $prevention += 4; $remove = 1; break;
        case "UPR222": $prevention += 3; $remove = 1; break;
        case "UPR223": $prevention += 2; $remove = 1; break;
        default: break;
      }
      if($remove == 1) RemoveCurrentTurnEffect($i);
    }
  }
  return $prevention;
}

function CurrentEffectAttackAbility()
{
  global $currentTurnEffects, $combatChain, $mainPlayer;
  global $CS_PlayIndex;
  if(count($combatChain) == 0) return;
  $attackID = $combatChain[0];
  $attackType = CardType($attackID);
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i>=0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    if($currentTurnEffects[$i+1] == $mainPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "EVR056":
          if($attackType == "W")
          {
            $character = &GetPlayerCharacter($mainPlayer);
            ++$character[GetClassState($mainPlayer, $CS_PlayIndex)+3];
          }
          break;
        case "MON183": case "MON184": case "MON185":
          if($currentTurnEffects[$i] == "MON183") $maxCost = 2;
          else if($currentTurnEffects[$i] == "MON184") $maxCost = 1;
          else $maxCost = 0;
          if($attackType == "AA" && CardCost($attackID) <= $maxCost)
          {
            WriteLog("Seeds of Agony dealt 1 damage.");
            DealArcane(1, 0, "PLAYCARD", $currentTurnEffects[$i], true);
            $remove = 1;
          }
          break;
        default: break;
      }
    }
    if($remove == 1) RemoveCurrentTurnEffect($i);
  }
  $currentTurnEffects = array_values($currentTurnEffects);//In case any were removed
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
      if($remove == 1) RemoveCurrentTurnEffect($i);
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
    if($remove == 1) RemoveCurrentTurnEffect($i);
  }
  return $hasGoAgain;
}

function CurrentEffectGrantsGoAgain()
{
  global $currentTurnEffects, $mainPlayer, $combatChain, $combatChainState, $CCS_AttackFused;
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i+1] == $mainPlayer && IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i))
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
        case "EVR017": return true;
        case "EVR161-3": return true;
        case "EVR044": case "EVR045": case "EVR046": return true;
        case "DVR008": return true;
        case "DVR019": return true;
        case "UPR081": case "UPR082": case "UPR083": return true;
        case "UPR094": return true;
        default: break;
      }
    }
  }
  return false;
}

function CurrentEffectPreventsGoAgain()
{
  global $currentTurnEffects, $mainPlayer;
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
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
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i+1] == $currentPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "CRU123": return $from == "ARS" && IsCombatEffectActive($currentTurnEffects[$i]);
        case "CRU135-1": case "CRU136-1": case "CRU137-1": return $from == "HAND" && IsCombatEffectActive($currentTurnEffects[$i]);
        case "EVR091-1": case "EVR092-1": case "EVR093-1": return $from == "ARS" && IsCombatEffectActive($currentTurnEffects[$i]);
        default: break;
      }
    }
  }
  return false;
}

function CurrentEffectPreventsDraw($player, $isMainPhase)
{
  global $currentTurnEffects;
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i+1] == $player)
    {
      switch($currentTurnEffects[$i])
      {
        case "WTR045": return $isMainPhase;
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
  for($i=count($currentTurnEffects)-CurrentTurnEffectPieces(); $i>=0; $i-=CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i+1] == $currentPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "WTR042": $intellectModifier += 1; break;
        case "ARC161": $intellectModifier += 1; break;
        case "CRU028": $intellectModifier += 1; break;
        case "MON000": $intellectModifier += 1; break;
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
      case "EVR056"://Oath of Steel does the same thing
        $char = &GetPlayerCharacter($currentTurnEffects[$i+1]);
        for($j=0; $j<count($char); $j+=CharacterPieces())
        {
          if(CardType($char[$j]) == "W") $char[$j+3] = 0;//Glisten clears out all +1 attack counters
        }
        $remove = 1;
        break;
      case "UPR200": case "UPR201": case "UPR202": Draw($currentTurnEffects[$i+1]); break;
      default: break;
    }
    if($remove == 1) RemoveCurrentTurnEffect($i);
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
  else if($set == "EVR")
  {
    return EVRCombatEffectActive($cardID, $attackID);
  }
  else if($set == "DVR")
  {
    return DVRCombatEffectActive($cardID, $attackID);
  }
  else if($set == "UPR")
  {
    return UPRCombatEffectActive($cardID, $attackID);
  }
  switch($cardID)
  {
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
    case "WTR038": case "WTR039": return true;
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
    case "ELE173": return true;
    case "ELE198": case "ELE199": case "ELE200": return true;
    case "EVR001": return true;
    case "EVR019": return true;
    case "EVR066-1": case "EVR067-1": case "EVR068-1": return true;
    case "EVR090": return true;
    case "EVR160": return true;
    case "EVR170-1": case "EVR171-1": case "EVR172-1": return true;
    case "EVR186": return true;
    case "DVR008-1": return true;
    case "UPR047": return true;
    case "UPR049": return true;
    default: return false;
  }
}

function BeginEndStepEffects()
{
  global $currentTurnEffects, $mainPlayer;
  EndTurnBloodDebt();//This has to be before resetting character, because of sleep dart effects
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnPieces())
  {
    if($currentTurnEffects[$i+1] == $mainPlayer)
    {
      switch($currentTurnEffects[$i])
      {
        case "ELE215-1": WriteLog("Seek and Destroy discarded your hand and arsenal."); DestroyArsenal($mainPlayer); DiscardHand($mainPlayer); break;
        case "EVR106": WriteLog("Revel in Runeblood destroyed your Runechants."); DestroyAllThisAura($mainPlayer, "ARC112"); break;
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
    case "EVR069":
      WriteLog("Dissolution Sphere lost a steam counter to remain in play.");
      --$mainItems[$index+1];
      if($mainItems[$index+1] < 0) DestroyMainItem($index);
      break;
    case "EVR071":
      WriteLog("Signal Jammer lost a steam counter to remain in play.");
      --$mainItems[$index+1];
      if($mainItems[$index+1] < 0) DestroyMainItem($index);
      break;
    default:
      break;
  }
}

function ItemEndTurnAbilities()
{
  global $mainPlayer;
  $items = &GetItems($mainPlayer);
  for($i=count($items)-ItemPieces(); $i>=0; $i-=ItemPieces())
  {
    $remove = false;
    switch($items[$i])
    {
      case "EVR188": $remove = TalismanOfBalanceEndTurn(); break;
      default: break;
    }
    if($remove)
    {
      DestroyItemForPlayer($mainPlayer, $i);
    }
  }
}

function ItemDamageTakenAbilities($player, $damage)
{
  $otherPlayer = ($player == 1 ? 2 : 1);
  $items = &GetItems($otherPlayer);
  for($i=count($items)-ItemPieces(); $i>=0; $i-=ItemPieces())
  {
    $remove = false;
    switch($items[$i])
    {
      case "EVR193": if($damage == 2) { WriteLog("Talisman of Warfare destroyed both arsenals."); DestroyArsenal(1); DestroyArsenal(2); $remove = true; } break;
      default: break;
    }
    if($remove)
    {
      DestroyItemForPlayer($otherPlayer, $i);
    }
  }
}

function CharacterStartTurnAbility($index)
{
  global $mainPlayer, $defPlayer;
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
    case "EVR017":
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "You may reveal an Earth, Ice, and Lightning card from your hand for Bravo, Star of the Show.");
      AddDecisionQueue("FINDINDICES", $mainPlayer, "BRAVOSTARSHOW");
      AddDecisionQueue("MULTICHOOSEHAND", $mainPlayer, "<-", 1);
      AddDecisionQueue("BRAVOSTARSHOW", $mainPlayer, "-", 1);
      break;
    case "EVR019":
      if(CountAura("WTR075", $mainPlayer) >= 3)
      {
        WriteLog("Valda gives your Crush attacks Dominate this turn.");
        AddCurrentTurnEffect("EVR019", $mainPlayer);
      }
      break;
    default: break;
  }
}

function DefCharacterStartTurnAbilities()
{
  global $defPlayer, $mainPlayer;
  $character = &GetPlayerCharacter($defPlayer);
  for($i=0; $i<count($character); $i+=CharacterPieces())
  {
    if($character[$i+1] == 0) continue;//Do not process ability if it is destroyed
    switch($character[$i])
    {
      case "EVR086":
        if(PlayerHasLessHealth($mainPlayer))
        {
          AddDecisionQueue("CHARREADYORPASS", $defPlayer, $i);
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_draw_a_card_and_give_your_opponent_a_silver", 1);
          AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
          AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
          AddDecisionQueue("PASSPARAMETER", $defPlayer, "EVR195", 1);
          AddDecisionQueue("PUTPLAY", $defPlayer, "0", 1);
        }
        break;
      default: break;
    }
  }
}

function PitchAbility($cardID)
{
  global $currentPlayer;

  $pitchValue = PitchValue($cardID);
  if($pitchValue == 1)
  {
    $talismanOfRecompenseIndex = GetItemIndex("EVR191", $currentPlayer);
    if($talismanOfRecompenseIndex > -1)
    {
      WriteLog("Talisman of Recompense gained 3 instead of 1 and destroyed itself.");
      DestroyItemForPlayer($currentPlayer, $talismanOfRecompenseIndex);
      GainResources($currentPlayer, 2);
    }
    if(SearchCharacterActive($currentPlayer, "UPR001") || SearchCharacterActive($currentPlayer, "UPR002"))
    {
      WriteLog("Dromai creates an Ash.");
      PutPermanentIntoPlay($currentPlayer, "UPR043");
    }
  }
  switch($cardID)
  {
    case "WTR000":
        if(IHaveLessHealth()) { if(GainHealth(1, $currentPlayer)) WriteLog("Heart of Fyendal gained 1 life."); }
      break;
    case "ARC000":
      Opt($cardID, 2);
      break;
    case "CRU000":
      PlayAura("ARC112", $currentPlayer);
      break;
    case "EVR000":
      WriteLog("Grandeur of Valahai created a Seismic Surge when it was pitched.");
      PlayAura("WTR075", $currentPlayer);
      break;
    case "UPR000":
      WriteLog("Blood of the Dracai makes each of your next 3 draconic cards this turn cost 1 less.");
      AddCurrentTurnEffect($cardID, $currentPlayer);
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
    if($remove == 1) RemoveCurrentTurnEffect($i);
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
    if($remove == 1) RemoveCurrentTurnEffect($i);
  }
}

function OnBlockEffects($index, $from)
{
  global $currentTurnEffects, $combatChain, $currentPlayer, $combatChainState, $CCS_WeaponIndex, $mainPlayer, $defPlayer, $CS_DamageTaken;
  $cardType = CardType($combatChain[$index]);
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
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
    if($remove == 1) RemoveCurrentTurnEffect($i);
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
  switch($combatChain[$index])
  {
    case "EVR018": WriteLog("Stalagmite created a Frostbite."); PlayAura("ELE111", $otherPlayer); break;
    case "RVD003": case "RVD015":
      $deck = &GetDeck($currentPlayer);
      $rv = "";
      if(count($deck) == 0) $rv .= "Your deck is empty. No card is revealed.";
      $wasRevealed = RevealCards($deck[0]);
      if($wasRevealed)
      {
        if(AttackValue($deck[0]) < 6)
        {
          WriteLog("The card was put on the bottom of your deck.");
          array_push($deck, array_shift($deck));
        }
      }
      break;
    case "UPR095":
      if(GetClassState($currentPlayer, $CS_DamageTaken) > 0)
      {
        $discard = &GetDiscard($currentPlayer);
        $found = -1;
        for($i=0; $i<count($discard) && $found == -1; $i+=DiscardPieces())
        {
          if($discard[$i] == "UPR101") $found = $i;
        }
        if($found == -1) WriteLog("No Phoenix Flames in discard.");
        else
        {
          RemoveGraveyard($currentPlayer, $found);
          AddPlayerHand("UPR101", $currentPlayer, "GY");
        }
      }
      break;
    case "UPR182":
      BottomDeckMultizoneDraw("MYHAND","MYARS");
      break;
    case "UPR194": case "UPR195": case "UPR196":
      if(PlayerHasLessHealth($currentPlayer))
      {
        GainHealth(1, $currentPlayer);
        WriteLog("Fyendal's Fighting Spirit gained 1 health.");
      }
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
    if($remove == 1) RemoveCurrentTurnEffect($i);
  }
  $currentTurnEffects = array_values($currentTurnEffects);
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

function Draw($player, $mainPhase=true)
{
  global $CS_EffectContext, $mainPlayer;
  $otherPlayer = ($player == 1 ? 2 : 1);
  if($mainPhase && $player != $mainPlayer)
  {
    $talismanOfTithes = SearchItemsForCard("EVR192", $otherPlayer);
    if($talismanOfTithes != "")
    {
      $indices = explode(",", $talismanOfTithes);
      DestroyItemForPlayer($otherPlayer, $indices[0]);
      WriteLog("Talisman of Tithes prevented a draw and was destroyed.");
      return "";
    }
  }
  $deck = &GetDeck($player);
  $hand = &GetHand($player);
  if(count($deck) == 0) return -1;
  if(CurrentEffectPreventsDraw($player, $mainPhase)) return -1;
  array_push($hand, array_shift($deck));
  WriteReplay($player, "Hide", "DECK", "HAND");
  if($mainPhase && SearchCharacterActive($otherPlayer, "EVR019")) PlayAura("WTR075", $otherPlayer);
  if(SearchCharacterActive($player, "EVR020"))
  {
    $context = GetClassState($player, $CS_EffectContext);
    if($context != "-")
    {
      $cardType = CardType($context);
      if($cardType == "A" || $cardType == "AA") PlayAura("WTR075", $player);
    }
  }
  return $hand[count($hand)-1];
}

function MyDrawCard()
{
  global $currentPlayer;
  Draw($currentPlayer);
}

function TheirDrawCard()
{
  global $currentPlayer;
  Draw(($currentPlayer == 1 ? 2 : 1));
}

function MainDrawCard()
{
  global $mainPlayer;
  Draw($mainPlayer);
}

function CombatChainCloseAbilities($player, $cardID, $chainLink)
{
  global $chainLinkSummary;
  switch($cardID)
  {
    case "UPR189": if($chainLinkSummary[$chainLink * ChainLinkSummaryPieces() + 1] <= 2) { Draw($player); WriteLog("That's All You Got? drew a card."); } break;
    default: break;
  }
}

function NumNonEquipmentDefended()
{
  global $combatChain,$defPlayer;
  $number = 0;
  for($i=0; $i<count($combatChain); $i+=CombatChainPieces())
  {
    $cardType = CardType($combatChain[$i]);
    if($combatChain[$i+1] == $defPlayer && $cardType != "E" && $cardType != "C") ++$number;
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
      case "ELE224": if(GetClassState($defPlayer, $CS_ArcaneDamageTaken) < $mainCharacter[$i+2]) { DestroyCharacter($mainPlayer, $i); $mainCharacter[$i+2] = 0; } break;
      default: break;
    }
  }
}

function MainCharacterHitAbilities()
{
  global $characterPieces, $combatChain, $combatChainState, $CCS_WeaponIndex, $CCS_HitsInRow, $mainPlayer;
  $attackID = $combatChain[0];
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  for($i=0; $i<count($mainCharacter); $i += CharacterPieces())
  {
    if(CardType($mainCharacter[$i]) == "W" || $mainCharacter[$i+1] != "2") continue;
    switch($mainCharacter[$i])
    {
      case "WTR076": case "WTR077": if(CardType($attackID) == "AA") { KatsuHit($i); $mainCharacter[$i+1] = 1; } break;
      case "WTR079": if(CardType($attackID) == "AA" && $combatChainState[$CCS_HitsInRow] >= 3) { MainDrawCard(); $mainCharacter[$i+1] = 1; } break;
      case "WTR113": case "WTR114": if($mainCharacter[$i+1] == 2 && CardType($attackID) == "W" && $mainCharacter[$combatChainState[$CCS_WeaponIndex]+1] != 0) { $mainCharacter[$i+1] = 1; $mainCharacter[$combatChainState[$CCS_WeaponIndex]+1] = 2; ++$mainCharacter[$combatChainState[$CCS_WeaponIndex]+5]; } break;
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
      case "CRU047": if(CardType($attackID) == "AA") { AddCurrentTurnEffectFromCombat("CRU047", $mainPlayer); $mainCharacter[$i+1] = 1; } break;
      case "CRU053":
        if(CardType($attackID) == "AA" && CardClass($attackID) == "NINJA")
        {
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_destroy_Breeze_Rider_Boots_to_give_your_Combo_attacks_Go_Again");
          AddDecisionQueue("NOPASS", $mainPlayer, "-");
          AddDecisionQueue("PASSPARAMETER", $mainPlayer, $i, 1);
          AddDecisionQueue("DESTROYCHARACTER", $mainPlayer, "-", 1);
          AddDecisionQueue("ADDCURRENTEFFECT", $mainPlayer, $mainCharacter[$i], 1);
        }
        break;
      case "EVR037":
        if(CardType($attackID) == "AA")
        {
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_destroy_Mask_of_the_Pouncing_Lynx_to_tutor_a_card");
          AddDecisionQueue("NOPASS", $mainPlayer, "-");
          AddDecisionQueue("PASSPARAMETER", $mainPlayer, $i, 1);
          AddDecisionQueue("DESTROYCHARACTER", $mainPlayer, "-", 1);
          AddDecisionQueue("FINDINDICES", $mainPlayer, "MASKPOUNCINGLYNX", 1);
          AddDecisionQueue("CHOOSEDECK", $mainPlayer, "<-", 1);
          AddDecisionQueue("MULTIBANISH", $mainPlayer, "DECK,TT", 1);
          AddDecisionQueue("SHUFFLEDECK", $mainPlayer, $mainCharacter[$i], 1);
        }
        break;
      default: break;
    }
  }
}

function MainCharacterAttackModifiers($index=-1, $onlyBuffs=false)
{
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer, $CS_NumAttacks;
  $modifier = 0;
  $mainCharacterEffects = &GetMainCharacterEffects($mainPlayer);
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  if($index == -1) $index = $combatChainState[$CCS_WeaponIndex];
  for($i=0; $i<count($mainCharacterEffects); $i+=CharacterEffectPieces())
  {
    if($mainCharacterEffects[$i] == $index)
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
        case "EVR055-1": $modifier += 1; break;
        default: break;
      }
    }
  }
  if($onlyBuffs) return $modifier;
  for($i=0; $i<count($mainCharacter); $i+=CharacterPieces())
  {
    if(!IsEquipUsable($mainPlayer, $i)) continue;
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
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  $modifier = 0;
  $mainCharacterEffects = &GetMainCharacterEffects($mainPlayer);
  for($i=0; $i<count($mainCharacterEffects); $i+=2)
  {
    if($mainCharacterEffects[$i] == $combatChainState[$CCS_WeaponIndex])
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

function MainCharacterGrantsGoAgain()
{
  global $combatChainState, $CCS_WeaponIndex, $combatChain, $mainPlayer;
  if($combatChainState[$CCS_WeaponIndex] == -1) return false;
  $modifier = 0;
  $mainCharacterEffects = &GetMainCharacterEffects($mainPlayer);
  for($i=0; $i<count($mainCharacterEffects); $i+=2)
  {
    if($mainCharacterEffects[$i] == $combatChainState[$CCS_WeaponIndex])
    {
      switch($mainCharacterEffects[$i+1])
      {
        case "EVR055-2": return true;
        default: break;
      }
    }
  }
  return false;
}

function CombatChainPlayAbility($cardID)
{
  global $combatChain;
  for($i=0; $i<count($combatChain); $i+=CombatChainPieces())
  {
    switch($combatChain[$i])
    {
      case "EVR122": if(CardClass($cardID) == "WIZARD") { $combatChain[$i+6] += 2; WriteLog("Sigil of Parapets got +2 block."); } break;
      default: break;
    }
  }
}

function PutItemIntoPlay($item, $steamCounterModifier = 0)
{
  global $currentPlayer;
  PutItemIntoPlayForPlayer($item, $currentPlayer, $steamCounterModifier);
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
    array_push($items, ItemUses($item));
  }
}

function ItemUses($cardID)
{
  switch($cardID)
  {
    case "EVR070": return 3;
    default: return 1;
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
  global $currentTurnEffects, $mainPlayer, $CCS_WeaponIndex, $characterPieces, $combatChain, $combatChainState;
  global $CS_NumAuras, $CCS_NumBoosted;
  if(SearchCurrentTurnEffectsForCycle("EVR097", "EVR098", "EVR099", $mainPlayer)) return false;
  $characterEffects = GetCharacterEffects($mainPlayer);
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i+1] == $mainPlayer && IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i) && DoesEffectGrantDominate($currentTurnEffects[$i])) return true;
  }
  for($i=0; $i<count($characterEffects); $i+=CharacterEffectPieces())
  {
    if($characterEffects[$i] == $combatChainState[$CCS_WeaponIndex])
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
    case "WTR095": case "WTR096": case "WTR097": return (ComboActive() ? true : false);
    case "WTR179": case "WTR180": case "WTR181": return true;
    case "ARC080": return true;
    case "MON004": return true;
    case "MON023": case "MON024": case "MON025": return true;
    case "MON246": return SearchDiscard($mainPlayer, "AA") == "";
    case "MON275": case "MON276": case "MON277": return true;
    case "ELE209": case "ELE210": case "ELE211": return HasIncreasedAttack();
    case "EVR027": case "EVR028": case "EVR029": return true;
    case "EVR038": return (ComboActive() ? true : false);
    case "EVR076": case "EVR077": case "EVR078": return $combatChainState[$CCS_NumBoosted] > 0;
    case "EVR110": case "EVR111": case "EVR112": return GetClassState($mainPlayer, $CS_NumAuras) > 0;
    default: break;
  }
  return false;
}

function EquipPayAdditionalCosts($cardIndex, $from)
{
  global $currentPlayer;
  $character = &GetPlayerCharacter($currentPlayer);
  $cardID = $character[$cardIndex];
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
      $character[$cardIndex+2] -= 3;
      break;
    case "WTR151": case "WTR152": case "WTR153": case "WTR154":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "ARC003":
      $character[$cardIndex+1] = 2;
      break;
    case "ARC005": case "ARC042": case "ARC079": case "ARC116": case "ARC117": case "ARC151": case "ARC153": case "ARC154":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "ARC113": case "ARC114":
      $character[$cardIndex+1] = 2;
      break;
    case "CRU006": case "CRU025": case "CRU081": case "CRU102": case "CRU122": case "CRU141":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "CRU024":
      break;//No limits on use, so override default
    case "CRU101":
      /*
      if($character[$cardIndex+2] == 0) $character[$cardIndex+1] = 2;
      else
      {
        --$character[$cardIndex+5];
        if($character[$cardIndex+5] == 0) $character[$cardIndex+1] = 1;//By default, if it's used, set it to used
      }
      */
      break;
    case "CRU177":
      $character[$cardIndex+1] = 1;
      ++$character[$cardIndex+2];
      break;
    case "MON061": case "MON090": case "MON108": case "MON188": case "MON230": case "MON238": case "MON239": case "MON240": case "DVR005":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "MON029": case "MON030":
      $character[$cardIndex+1] = 2;//It's not limited to once
      break;
    case "ELE116": case "ELE145": case "ELE214": case "ELE225": case "ELE233": case "ELE234": case "ELE235": case "ELE236":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "ELE173": break;//Unlimited uses, explicitly don't use default
    case "ELE224":
      ++$character[$cardIndex + 2];
      --$character[$cardIndex+5];
      if($character[$cardIndex+5] == 0) $character[$cardIndex+1] = 1;
      break;
    case "EVR053": case "EVR103": case "EVR137":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "DVR004": case "RVD004":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "UPR004": case "UPR047": case "UPR085": case "UPR159": case "UPR167":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "UPR151":
      $character[$cardIndex+2] -= 1;
      break;
    case "UPR166":
      $character[$cardIndex+2] -= 2;
      break;
    default:
      --$character[$cardIndex+5];
      if($character[$cardIndex+5] == 0) $character[$cardIndex+1] = 1;//By default, if it's used, set it to used
      break;
  }
}

function DecisionQueueStaticEffect($phase, $player, $parameter, $lastResult)
{
  global $currentPlayer, $combatChain, $defPlayer;
  global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $actionPoints, $CCS_ChainAttackBuff;
  global $defCharacter, $CS_NumCharged, $otherPlayer, $CCS_ChainLinkHitEffectsPrevented;
  global $CS_NumFusedEarth, $CS_NumFusedIce, $CS_NumFusedLightning, $CS_NextNAACardGoAgain, $CCS_AttackTarget;
  global $CS_LayerTarget, $dqVars, $mainPlayer, $lastPlayed, $CS_DamageTaken, $CS_EffectContext, $dqState, $CS_AbilityIndex, $CS_CharacterIndex;
  global $CS_AdditionalCosts;
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
      else $subparam = "";
      switch($parameter)
      {
        case "ARCANETARGET": $rv = GetArcaneTargetIndices($player, $subparam); break;
        case "WTR083": $rv = SearchDeckForCard($player, "WTR081"); if($rv != "") $rv = count(explode(",", $rv)) . "-" . $rv; break;
        case "WTR076-1": $rv = SearchHand($player, "", "", 0); break;
        case "WTR076-2": $rv = GetComboCards(); break;
        case "WTR081": $rv = LordOfWindIndices($player); if($rv != "") $rv = count(explode(",", $rv)) . "-" . $rv; break;
        case "ARC014": $rv = SearchHand($player, "", "Item", 2, -1, "MECHANOLOGIST"); break;
        case "ARC015": $rv = SearchHand($player, "", "Item", 1, -1, "MECHANOLOGIST"); break;
        case "ARC016": $rv = SearchHand($player, "", "Item", 0, -1, "MECHANOLOGIST"); break;
        case "ARC079": $rv = CombineSearches(SearchDiscard($player, "AA", "", -1, -1, "RUNEBLADE"), SearchDiscard($player, "A", "", -1, -1, "RUNEBLADE")); break;
        case "ARC121": $rv = SearchDeck($player, "", "", $lastResult, -1, "WIZARD"); break;
        case "ARC138": case "ARC139": case "ARC140": $rv = SearchHand($player, "A", "", $lastResult, -1, "WIZARD"); break;
        case "ARC185": case "ARC186": case "ARC187": $rv = SearchDeckForCard($player, "ARC212", "ARC213", "ARC214"); break;
        case "CRU026": $rv = SearchEquipNegCounter($defCharacter); break;
        case "CRU105": $rv = GetWeaponChoices("Pistol"); break;
        case "CRU143": $rv = SearchDiscard($player, "AA", "", -1, -1, "RUNEBLADE"); break;
        case "LAYER": $rv = SearchLayerDQ($subparam); break;
        case "DECK": $deck = GetDeck($player); $rv = GetIndices(count($deck)); break;
        case "TOPDECK": $deck = &GetDeck($player); if(count($deck) > 0) $rv = "0"; break;
        case "DECKTOPX": $rv = ""; $deck = &GetDeck($player); for($i=0; $i<$subparam; ++$i) if($i < count($deck)) { if($rv != "") $rv .= ","; $rv .= $i; } break;
        case "DECKCLASSAA": $rv = SearchDeck($player, "AA", "", -1, -1, $subparam); break;
        case "DECKCLASSNAA": $rv = SearchDeck($player, "A", "", -1, -1, $subparam); break;
        case "DECKSPEC": $rv = SearchDeck($player, "", "", -1, -1, "", "", false, false, -1, true); break;
        case "DECKCARD": $rv = SearchDeckForCard($player, $subparam); break;
        case "PERMSUBTYPE": $rv = SearchPermanents($player, "", $subparam); break;
        case "SEARCHMZ": $rv = SearchMZ($player, $subparam); break;
        case "MZSTARTTURN": $rv = MZStartTurnIndices(); break;
        case "HAND": $rv = GetIndices(count(GetHand($player))); break;
        case "HANDTALENT":  $rv = SearchHand($player, "", "", -1, -1, "", $subparam); break;
        case "HANDPITCH": $rv = SearchHand($player, "", "", -1, -1, "", "", false, false, $subparam); break;
        case "HANDACTION": $rv = CombineSearches(SearchHand($player, "A"), SearchHand($player, "AA")); break;
        case "HANDACTIONMAXCOST": $rv = CombineSearches(SearchHand($player, "A", "", $subparam), SearchHand($player, "AA", "", $subparam)); break;
        case "MULTIHAND": $hand = &GetHand($player); $rv = count($hand) . "-" . GetIndices(count($hand)); break;
        case "MULTIHANDAA": $search = SearchHand($player, "AA"); $rv = SearchCount($search) . "-" . $search; break;
        case "ARSENAL": $arsenal = &GetArsenal($player); $rv = GetIndices(count($arsenal), 0, ArsenalPieces()); break;
        case "ARSENALDOWN": $rv = GetArsenalFaceDownIndices($player); break;
        case "ITEMS": $rv = GetIndices(count(GetItems($player)), 0, ItemPieces()); break;
        case "ITEMSMAX": $rv = SearchItems($player, "", "", $subparam); break;
        case "DECKITEMMAXCOST": $rv = SearchDeck($player, "", "Item", $subparam); break;
        case "EQUIP": $rv = GetEquipmentIndices($player); break;
        case "EQUIP0": $rv = GetEquipmentIndices($player, 0); break;
        case "EQUIPCARD": $rv = FindCharacterIndex($player, $subparam); break;
        case "CCAA": $rv = SearchCombatChain("AA"); break;
        case "CCDEFLESSX": $rv = SearchCombatChain("", "", -1, -1, "", "", false, false, -1, false, -1, $subparam); break;
        case "HANDEARTH": $rv = SearchHand($player, "", "", -1, -1, "", "EARTH"); break;
        case "HANDICE": $rv = SearchHand($player, "", "", -1, -1, "", "ICE"); break;
        case "HANDLIGHTNING": $rv = SearchHand($player, "", "", -1, -1, "", "LIGHTNING"); break;
        case "HANDAAMAXCOST": $rv = SearchHand($player, "AA","", $subparam); break;
        case "MYHANDAA": $rv = SearchHand($player, "AA"); break;
        case "MYHANDARROW": $rv = SearchHand($player, "", "Arrow"); break;
        case "MYDECKARROW": $rv = SearchDeck($player, "", "Arrow"); break;
        case "MAINHAND": $rv = GetIndices(count(GetHand($mainPlayer))); break;
        case "FIRSTXDECK": $deck = &GetDeck($player); if($subparam > count($deck)) $subparam = count($deck); $rv = GetIndices($subparam); break;
        case "BANISHTYPE": $rv = SearchBanish($player, $subparam); break;
        case "GY": $discard = &GetDiscard($player); $rv = GetIndices(count($discard)); break;
        case "GYTYPE": $rv = SearchDiscard($player, $subparam); break;
        case "GYAA": $rv = SearchDiscard($player, "AA"); break;
        case "GYNAA": $rv = SearchDiscard($player, "A"); break;
        case "GYCLASSAA": $rv = SearchDiscard($player, "AA", "", -1, -1, $subparam); break;
        case "GYCLASSNAA": $rv = SearchDiscard($player, "A", "", -1, -1, $subparam); break;
        case "GYCARD": $rv = SearchDiscardForCard($player, $subparam); break;
        case "WEAPON": $rv = WeaponIndices($player, $player, $subparam); break;
        case "MON020": case "MON021": case "MON022": $rv = SearchDiscard($player, "", "", -1, -1, "", "", false, true); break;
        case "MON033-1": $rv = GetIndices(count(GetSoul($player)), 1); break;
        case "MON033-2": $rv = CombineSearches(SearchDeck($player, "A", "", $lastResult), SearchDeck($player, "AA", "", $lastResult)); break;
        case "MON125": $rv = SearchDeck($player, "", "", -1, -1, "", "", true); break;
        case "MON156": $rv = SearchHand($player, "", "", -1, -1, "", "", true); break;
        case "MON158": $rv = InvertExistenceIndices($player); break;
        case "MON159": case "MON160": case "MON161": $rv = SearchDiscard($player, "A", "", -1, -1, "", "", true); break;
        case "MON212": $rv = SearchBanish($player, "AA", "", $subparam); break;
        case "MON266-1": $rv = SearchHand($player, "AA", "", -1, -1, "", "", false, false, -1, false, 3); break;
        case "MON266-2": $rv = SearchDeckForCard($player, "MON296", "MON297", "MON298"); break;
        case "MON303": $rv =  SearchDiscard($player, "AA", "", 2); break;
        case "MON304": $rv = SearchDiscard($player, "AA", "", 1); break;
        case "MON305": $rv = SearchDiscard($player, "AA", "", 0); break;
        case "HANDIFZERO": if($lastResult == 0) { $hand = &GetHand($player); $rv = GetIndices(count($hand)); } break;
        case "ELE006": $count = CountAura("WTR075", $player); $rv = SearchDeck($player, "AA", "", $count, -1, "GUARDIAN"); break;
        case "ELE113": $rv = PulseOfCandleholdIndices($player); break;
        case "ELE116": $rv = PlumeOfEvergrowthIndices($player); break;
        case "ELE125": case "ELE126": case "ELE127": $rv = SummerwoodShelterIndices($player); break;
        case "ELE140": case "ELE141": case "ELE142": $rv = SowTomorrowIndices($player, $parameter); break;
        case "EVR178": $rv = SearchDeckForCard($player, "MON281", "MON282", "MON283"); break;
        case "HEAVE": $rv = HeaveIndices(); break;
        case "BRAVOSTARSHOW": $rv = BravoStarOfTheShowIndices(); break;
        case "AURACLASS": $rv = SearchAura($player, "", "", -1, -1, $subparam); break;
        case "AURAMAXCOST": $rv = SearchAura($player, "", "", $subparam); break;
        case "DECKAURAMAXCOST": $rv = SearchDeck($player, "", "Aura", $subparam); break;
        case "CROWNOFREFLECTION": $rv = SearchHand($player, "", "Aura", -1, -1, "ILLUSIONIST"); break;
        case "LIFEOFPARTY": $rv = LifeOfThePartyIndices(); break;
        case "COALESCENTMIRAGE": $rv = SearchHand($player, "", "Aura", -1, 0, "ILLUSIONIST"); break;
        case "MASKPOUNCINGLYNX": $rv = SearchDeck($player, "AA", "", -1, -1, "", "", false, false, -1, false, 2); break;
        case "SHATTER": $rv = ShatterIndices($player, $subparam); break;
        case "KNICKKNACK": $rv = KnickKnackIndices($player); break;
        case "CASHOUT": $rv = CashOutIndices($player); break;
        case "UPR200": $rv = CombineSearches(SearchDiscard($player, "AA", "", 2), SearchDiscard($player, "A", "", 2)); break;
        case "UPR201": $rv = CombineSearches(SearchDiscard($player, "AA", "", 1), SearchDiscard($player, "A", "", 1)); break;
        case "UPR202": $rv = CombineSearches(SearchDiscard($player, "AA", "", 0), SearchDiscard($player, "A", "", 0)); break;
        default: $rv = ""; break;
      }
      return ($rv == "" ? "PASS" : $rv);
    case "PUTPLAY":
      $subtype = CardSubType($lastResult);
      if($subtype == "Item")
      {
        PutItemIntoPlayForPlayer($lastResult, $player, ($parameter != "-" ? $parameter : 0));
      }
      else if(DelimStringContains($subtype, "Aura"))
      {
        PlayAura($lastResult, $player);
        PlayAbility($lastResult, "-", 0);
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
      $mzIndices = "";
      for($i=0; $i<count($cards); ++$i)
      {
        $index = BanishCardForPlayer($cards[$i], $player, $params[0], $params[1]);
        if($mzIndices != "") $mzIndices .= ",";
        $mzIndices .= "BANISH-" . $index;
      }
      $dqState[5] = $mzIndices;
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
      $discard = &GetDiscard($player);
      $cardID = $discard[$lastResult];
      unset($discard[$lastResult]);
      $discard = array_values($discard);
      return $cardID;
    case "REMOVEDISCARD":
      $discard = &GetDiscard($player);
      $cardID = $discard[$lastResult];
      unset($discard[$lastResult]);
      $discard = array_values($discard);
      return $cardID;
    case "REMOVEMYHAND":
      $hand = &GetHand($player);
      $cardID = $hand[$lastResult];
      unset($hand[$lastResult]);
      $hand = array_values($hand);
      return $cardID;
    case "HANDCARD":
      $hand = &GetHand($player);
      $cardID = $hand[$lastResult];
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
      $oPlayer = ($player == 1 ? 2 : 1);
      AddPlayerHand($lastResult, $oPlayer, "-");
      return $lastResult;
    case "ADDMAINHAND":
      AddPlayerHand($lastResult, $mainPlayer, "-");
      return $lastResult;
    case "ADDMYHAND":
      AddPlayerHand($lastResult, $currentPlayer, "-");
      return $lastResult;
    case "ADDHAND":
      AddPlayerHand($lastResult, $player, "-");
      return $lastResult;
    case "ADDMYPITCH":
      $pitch = &GetPitch($player);
      array_push($pitch, $lastResult);
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
    case "TURNARSENALFACEUP":
      $arsenal = &GetArsenal($player);
      $arsenal[$lastResult+1] = "UP";
      return $lastResult;
    case "REMOVEARSENAL":
      $index = $lastResult;
      $arsenal = &GetArsenal($player);
      $cardToReturn = $arsenal[$index];
      RemoveArsenalEffects($player, $cardToReturn);
      for($i=$index+ArsenalPieces()-1; $i>=$index; --$i)
      {
        unset($arsenal[$i]);
      }
      $arsenal = array_values($arsenal);
     return $cardToReturn;
     case "FULLARSENALTODECK":
      $arsenal = &GetArsenal($player);
      $deck = &GetDeck($player);
      $i=0;
      while(count($arsenal)>0)
      {
        if($i%4 == 0){
          array_push($deck, $arsenal[$i]);
          RemoveArsenalEffects($player, $arsenal[$i]);
        }
        unset($arsenal[$i]);
        ++$i;
      }
     return $lastResult;
    case "MULTIADDHAND":
      $cards = explode(",", $lastResult);
      $hand = &GetHand($player);
      $displayText = "";
      for($i=0; $i<count($cards); ++$i)
      {
        if($parameter == "1")
        {
          if($displayText != "") $displayText .= ", ";
          if($i != 0 && $i == count($cards) - 1) $displayText .= "and ";
          $displayText .= CardName($cards[$i]);
        }
        array_push($hand, $cards[$i]);
      }
      if($displayText != "")
      {
        $word = (count($hand) == 1 ? "was" : "were");
        WriteLog($displayText . " $word added to your hand.");
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
      DestroyCharacter($player, $lastResult);
      return $lastResult;
    case "DESTROYTHEIRCHARACTER":
      DestroyCharacter($player == 1 ? 2 : 1, $lastResult);
      return $lastResult;
    case "CHARFLAGDESTROY":
      $character = &GetPlayerCharacter($player);
      $character[$parameter + 7] = 1;
      return $lastResult;
    case "ADDCHARACTEREFFECT":
      $characterEffects = &GetCharacterEffects($player);
      array_push($characterEffects, $lastResult);
      array_push($characterEffects, $parameter);
      return $lastResult;
    case "ADDMZBUFF":
      $lrArr = explode("-", $lastResult);
      $characterEffects = &GetCharacterEffects($player);
      array_push($characterEffects, $lrArr[1]);
      array_push($characterEffects, $parameter);
      return $lastResult;
    case "ADDMZUSES":
      $lrArr = explode("-", $lastResult);
      switch($lrArr[0])
      {
        case "MYCHAR": case "THEIRCHAR": AddCharacterUses($player, $lrArr[1], $parameter); break;
        default: break;
      }
      return $lastResult;
    case "MZOP":
      switch($parameter)//Mode
      {
        case "FREEZE": MZFreeze($lastResult); break;
        default: break;
      }
      return $lastResult;
    case "PASSPARAMETER":
      return $parameter;
    case "DISCARDMYHAND":
      $hand = &GetHand($player);
      $cardID = $hand[$lastResult];
      unset($hand[$lastResult]);
      $hand = array_values($hand);
      AddGraveyard($cardID, $player, $hand);
      return $cardID;
    case "ADDDISCARD":
      AddGraveyard($lastResult, $player, $parameter);
      return $lastResult;
    case "ADDBOTTOMMYDECK":
      $deck = &GetDeck($player);
      array_push($deck, $lastResult);
      return $lastResult;
    case "ADDBOTDECK":
      $deck = &GetDeck($player);
      array_push($deck, $lastResult);
      return $lastResult;
    case "MULTIADDDECK":
      $deck = &GetDeck($player);
      $cards = explode(",", $lastResult);
      for($i=0; $i<count($cards); ++$i)
      {
        array_push($deck, $cards[$i]);
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
    case "PLAYAURA":
      PlayAura($parameter, $player);
      break;
    case "DESTROYAURA":
      DestroyAura($player, $lastResult);
      break;
    case "PARAMDELIMTOARRAY":
      return explode(",", $parameter);
    case "ADDSOUL":
      AddSoul($lastResult, $player, $parameter);
      return $lastResult;
    case "SHUFFLEDECK":
      $zone = &GetDeck($player);
      $destArr = [];
      while(count($zone) > 0)
      {
        $index = rand(0, count($zone)-1);
        array_push($destArr, $zone[$index]);
        unset($zone[$index]);
        $zone = array_values($zone);
      }
      $zone = $destArr;
      return $lastResult;
    case "GIVEATTACKGOAGAIN":
      $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
      return $lastResult;
    case "EXHAUSTCHARACTER":
      $character = &GetPlayerCharacter($player);
      $character[$parameter+1] = 1;
      return $parameter;
    case "REVEALMYCARD":
      if(SearchLandmarks("ELE000")) KorshemRevealAbility($player);
      $hand = GetHand($player);
      WriteLog(CardLink($hand[$lastResult], $hand[$lastResult]) . " was revealed.");
      return $lastResult;
    case "DECKCARDS":
      $indices = explode(",", $parameter);
      $deck = &GetDeck($player);
      $rv = "";
      for($i=0; $i<count($indices); ++$i)
      {
        if($rv != "") $rv .= ",";
        $rv .= $deck[$i];
      }
      return $rv == "" ? "PASS" : $rv;;
    case "REVEALCARD":
      if(SearchLandmarks("ELE000")) KorshemRevealAbility($player);
      WriteLog(CardLink($lastResult, $lastResult) . " was revealed.");
      return $lastResult;
    case "REVEALCARDS":
      if(SearchLandmarks("ELE000")) KorshemRevealAbility($player);
      $cards = (is_array($lastResult) ? $lastResult : explode(",", $lastResult));
      for($i=0; $i<count($cards); ++$i)
      {
        WriteLog(CardLink($cards[$i], $cards[$i]) . " was revealed.");
      }
      return $lastResult;
    case "REVEALHANDCARDS":
      if(SearchLandmarks("ELE000")) KorshemRevealAbility($player);
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
    case "WRITELOG":
      WriteLog(implode(" ", explode("_", $parameter)));
      return $lastResult;
    case "WRITECARDLOG":
      $message = implode(" ", explode("_", $parameter)) . CardLink($lastResult, $lastResult);
      WriteLog($message);
      return $lastResult;
    case "ADDNEGDEFCOUNTER":
      $character = &GetPlayerCharacter($player);
      $character[$lastResult+4] -= 1;
      return $lastResult;
    case "ADDCURRENTEFFECT":
      AddCurrentTurnEffect($parameter, $player);
      return "1";
    case "ADDLIMITEDCURRENTEFFECT":
      $params = explode(",", $parameter);
      AddCurrentTurnEffect($params[0], $player, $params[1], $lastResult);
      return "";
    case "OPTX":
      Opt("NA", $parameter);
      return $lastResult;
    case "SETCLASSSTATE":
      SetClassState($player, $parameter, $lastResult);
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
    case "EQUIPDEFENSE":
      $char = &GetPlayerCharacter($player);
      $defense = BlockValue($char[$lastResult]) + $char[$lastResult + 4];
      if($defense < 0) $defense = 0;
      return $defense;
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
        case "Draw_a_card": WriteLog("Englightened Strike drew a card."); return MyDrawCard();
        case "2_Attack": WriteLog("Englightened Strike got +2 attack."); AddCurrentTurnEffect("WTR159", $player); return 1;
        case "Go_again": WriteLog("Englightened Strike got Go Again."); $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; return 2;
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
      if(count(GetHand($player)) == 0) { WriteLog("No card for Sand Sketched Plan to discard."); return "1"; }
      $discarded = DiscardRandom($player, "WTR009");
      if(AttackValue($discarded) >= 6)
      {
        $ap = 1;
        $actionPoints += 2;
      }
      if($ap == 1) WriteLog("Sand Sketched Plan gained two action points.");
      return "1";
    case "REMEMBRANCE":
      $cards = "";
      $deck = &GetDeck($player);
      $discard = &GetDiscard($player);
      for($i=0; $i<count($lastResult); ++$i)
      {
        array_push($deck, $discard[$lastResult[$i]]);
        if($cards != "") $cards .= ", ";
        if($i == count($lastResult) - 1) $cards .= "and ";
        $cards .= CardLink($discard[$lastResult[$i]], $discard[$lastResult[$i]]);
        unset($discard[$lastResult[$i]]);
      }
      WriteLog("Remembrance shuffled back " . $cards . ".");
      $discard = array_values($discard);
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
      AddResourceCost($player, $number);
      return $number;
    case "REFRACTIONBOLTERS":
      if($lastResult == "YES")
      {
        $character = &GetPlayerCharacter($player);
        $character[$parameter+1] = 0;
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
          case "Quicken_token": PlayAura("WTR225", 1); PlayAura("WTR225", 2); break;
          case "Draw_card": MyDrawCard(); TheirDrawCard(); break;
          case "Gain_life": GainHealth(1, $player); GainHealth(1, ($player == 1 ? 2 : 1)); break;
          default: break;
        }
      }
      return $lastResult;
    case "CAPTAINSCALL":
      switch($lastResult)
      {
        case "2_Attack": WriteLog("Captain's Call gives +2 attack."); AddCurrentTurnEffect($parameter . "-1", $player); return 1;
        case "Go_again": WriteLog("Captain's Call gives Go Again."); AddCurrentTurnEffect($parameter . "-2", $player); return 2;
      }
      return $lastResult;
    case "IRONHIDE":
      if($lastResult == 1)
      {
        $character = &GetPlayerCharacter($player);
        $character[$parameter+1] = 0;
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
        if(CachedTotalAttack() >= 6) $character[$parameter+1] = 0;
      }
      return $lastResult;
    case "ARTOFWAR":
      ArtOfWarResolvePlay($lastResult);
      return $lastResult;
    case "BLOODONHERHANDS":
      BloodOnHerHandsResolvePlay($lastResult);
      return $lastResult;
    case "VESTOFTHEFIRSTFIST":
      if($lastResult == "YES")
      {
        $character = &GetPlayerCharacter($player);
        $character[$parameter+1] = 0;
        GainResources($player, 2);
        WriteLog("Vest of the First Fist was destroyed and gave 2 resources.");
      }
      return $lastResult;
    case "BOOST":
      DoBoost();
      return "1";
    case "VOFTHEVANGUARD":
      if($parameter == "1" && TalentContains($lastResult, "LIGHT"))
      {
        WriteLog("V of the Vanguard gives all attacks on this combat chain +1.");
        ++$combatChainState[$CCS_ChainAttackBuff];
      }
      $hand = &GetHand($player);
      if(count($hand) > 0)
      {
        PrependDecisionQueue("VOFTHEVANGUARD", $player, "1", 1);
        PrependDecisionQueue("CHARGE", $player, "-", 1);
      }
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
        DamageTrigger($player, 2, "DAMAGE");
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
      IncrementClassState($player, $CS_NumCharged);
      return $lastResult;
    case "CHOOSEHERO":
      return "THEIRCHAR-0";
    case "DEALDAMAGE":
      $target = $lastResult;
      $parameters = explode("-", $parameter);
      $damage = $parameters[0];
      $source = $parameters[1];
      $type = $parameters[2];
      $damage = DealDamageAsync($player, $damage, $type, $source);
      return $damage;
    case "DEALARCANE":
      $targetPlayer = ($player == 1 ? 2 : 1);
      $target = explode("-", $lastResult);
      $parameters = explode("-", $parameter);
      $damage = $parameters[0];
      $source = $parameters[1];
      $type = $parameters[2];
      if($target[0] == "THEIRALLY")
      {
        $allies = &GetAllies($targetPlayer);
        $allies[$target[1] + 2] -= $damage;
        if($allies[$target[1] + 2] <= 0) DestroyAlly($targetPlayer, $target[1]);
        return;
      }
      $target = $targetPlayer;
      if($type == "PLAYCARD")
      {
        $damage += ConsumeArcaneBonus($player);
      }
      $sourceType = CardType($source);
      if(SearchCurrentTurnEffects("ELE065", $player) && ($sourceType == "A" || $sourceType == "AA")) ++$damage;
      $arcaneBarrier = ArcaneBarrierChoices($target, $damage);
      //Create cancel point
      PrependDecisionQueue("TAKEARCANE", $target, $damage . "-" . $source, 1);
      PrependDecisionQueue("PAYRESOURCES", $target, "<-", 1);
      PrependDecisionQueue("ARCANECHOSEN", $target, "-", 1, 1);
      PrependDecisionQueue("CHOOSEARCANE", $target, $arcaneBarrier, 1, 1);
      PrependDecisionQueue("SETDQVAR", $target, "0", 1);
      PrependDecisionQueue("PASSPARAMETER", $target, $damage . "-" . $source, 1);
      return $parameter;
    case "ARCANECHOSEN":
      if($lastResult > 0)
      {
        if(SearchCharacterActive($player, "UPR166"))
        {
          $char = &GetPlayerCharacter($player);
          $index = FindCharacterIndex($player, "UPR166");
          if($char[$index+2] < 4) ++$char[$index+2];
        }
      }
      return $lastResult;
    case "TAKEARCANE":
      $parameters = explode("-", $parameter);
      $damage = $parameters[0];
      $source = $parameters[1];
      if(!CanDamageBePrevented($player, $damage, "ARCANE")) $lastResult = 0;
      $damage = DealDamageAsync($player, $damage - $lastResult, "ARCANE", $source);
      if($damage < 0) $damage = 0;
      WriteLog(CardLink($source, $source) . " dealt $damage arcane damage.");
      return $damage;
    case "PAYRESOURCES":
      $resources = &GetResources($player);
      if($lastResult < 0) $resources[0] += (-1 * $lastResult);
      else if($resources[0] > 0)
      {
        $res = $resources[0];
        $resources[0] -= $lastResult;
        $lastResult -= $res;
        if($resources[0] < 0) $resources[0] = 0;
      }
      if($lastResult > 0)
      {
        $hand = &GetHand($player);
        if(count($hand) == 0)
        {
          WriteLog("You have resources to pay for a declared effect, but have no cards to pitch. Reverting gamestate prior to that declaration.");
          RevertGamestate();
        }
        PrependDecisionQueue("PAYRESOURCES", $player, $parameter, 1);
        PrependDecisionQueue("SUBPITCHVALUE", $player, $lastResult, 1);
        PrependDecisionQueue("PITCHABILITY", $player, "-", 1);
        PrependDecisionQueue("ADDMYPITCH", $player, "-", 1);
        PrependDecisionQueue("REMOVEMYHAND", $player, "-", 1);
        PrependDecisionQueue("CHOOSEHANDCANCEL", $player, "<-", 1);
        PrependDecisionQueue("FINDINDICES", $player, "HAND", 1);
      }
      return $parameter;
    case "ADDCLASSSTATE":
      $parameters = explode("-", $parameter);
      IncrementClassState($player, $parameters[0], $parameters[1]);
      return 1;
    case "APPENDCLASSSTATE":
      $parameters = explode("-", $parameter);
      AppendClassState($player, $parameters[0], $parameters[1]);
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
        AppendClassState($player, $CS_AdditionalCosts, $elements);
        CurrentTurnFuseEffects($player, $element);
        AuraFuseEffects($player, $element);
      }
      return $lastResult;
    case "SUBPITCHVALUE":
      return $parameter - PitchValue($lastResult);
    case "BUFFARCANE":
      AddArcaneBonus($parameter, $player);
      return $parameter;
    case "SHIVER":
      $arsenal = &GetArsenal($player);
      switch($lastResult)
      {
        case "1_Attack":
          WriteLog("Shiver gives the arrow +1.");
          AddCurrentTurnEffect("ELE033-1", $player, "HAND", $arsenal[count($arsenal)-ArsenalPieces()+5]);
          return 1;
        case "Dominate":
          WriteLog("Shiver gives the arrow Dominate.");
          AddCurrentTurnEffect("ELE033-2", $player, "HAND", $arsenal[count($arsenal)-ArsenalPieces()+5]);
          return 1;
      }
      return $lastResult;
    case "VOLTAIRE":
      $arsenal = &GetArsenal($player);
      switch($lastResult)
      {
        case "1_Attack":
          WriteLog("Voltaire gives the arrow +1.");
          AddCurrentTurnEffect("ELE034-1", $player, "HAND", $arsenal[count($arsenal)-ArsenalPieces()+5]);
          return 1;
        case "Go_again":
          WriteLog("Voltaire gives the arrow Go Again.");
          AddCurrentTurnEffect("ELE034-2", $player, "HAND", $arsenal[count($arsenal)-ArsenalPieces()+5]);
          return 1;
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
      LoseHealth(1, $player);
      if(AttackValue($card) >= 6)
      {
        WriteLog("Beast Within banished " . CardLink($card, $card) . " and was added to hand.");
        BanishCardForPlayer($card, $player, "DECK", "-");
        $banish = &GetBanish($player);
        RemoveBanish($player, count($banish)-BanishPieces());
        AddPlayerHand($card, $player, "BANISH");
      }
      else
      {
        WriteLog("Beast Within banished " . CardLink($card, $card) . ".");
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
      AddResourceCost($player, $parameter);
      return $parameter;
    case "PROCESSATTACKTARGET":
      $combatChainState[$CCS_AttackTarget] = $lastResult;
      WriteLog(GetMZCardLink($defPlayer, $lastResult) . " was chosen as the attack target.");
      return 1;
    case "STARTTURNABILITIES":
      StartTurnAbilities();
      return 1;
    case "DRAWTOINTELLECT":
      $deck = &GetDeck($player);
      $hand = &GetHand($player);
      $char = &GetPlayerCharacter($player);
      for($i=0; $i<CharacterIntellect($char[0]); ++$i)
      {
        array_push($hand, array_shift($deck));
      }
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
      $roll = RollDie($player, true, $parameter=="1");
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
    case "MULTIZONEDESTROY":
      $params = explode("-", $lastResult);
      $source = $params[0];
      $index = $params[1];
      $otherP = ($player == 1 ? 2 : 1);
      switch($source)
      {
        case "MYAURAS": DestroyAura($player, $index); break;
        case "THEIRAURAS": DestroyAura($otherP, $index); break;
        case "MYHAND": DiscardIndex($player, $index); break;
        case "MYITEMS": DestroyItemForPlayer($player, $index); break;
        case "MYCHAR": DestroyCharacter($player, $index); break;
        default: break;
      }
      return $lastResult;
    case "MULTIZONEREMOVE":
      $params = explode("-", $lastResult);
      $source = $params[0];
      $index = $params[1];
      $otherP = ($player == 1 ? 2 : 1);
      switch($source)
      {
        case "MYARS":
          $arsenal = &GetArsenal($player);
          $card = $arsenal[$index];
          RemoveFromArsenal($player, $index);
          break;
        case "MYHAND":
          $hand = &GetHand($player);
          $card = $hand[$index];
          RemoveCard($player, $index);
          break;
        default: break;
      }
      return $card;
    case "MULTIZONETOKENCOPY":
      $params = explode("-", $lastResult);
      $source = $params[0];
      $index = $params[1];
      switch($source)
      {
        case "MYAURAS": TokenCopyAura($player, $index); break;
        default: break;
      }
      return $lastResult;
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
    case "APPENDLASTRESULT":
      return $lastResult . $parameter;
    case "LASTRESULTPIECE":
      $pieces = explode("-", $lastResult);
      return $pieces[$parameter];
    case "IMPLODELASTRESULT":
      return implode($parameter, $lastResult);
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
    case "FINALIZEDAMAGE":
      $params = explode(",", $parameter);
      return FinalizeDamage($player, $lastResult, $params[0], $params[1], $params[2]);
    case "ONARCANEDAMAGEPREVENTED":
      $damage = $parameter;
      if($lastResult != "PASS")
      {
        $damage -= ArcaneDamagePrevented($player, $lastResult);
        if($damage < 0) $damage = 0;
        PrependArcaneDamageReplacement($player, $damage);
      }
      return $damage;
    case "KORSHEM":
      switch($lastResult)
      {
        case "Gain_a_resource": GainResources($player, 1); return 1;
        case "Gain_a_life": GainHealth(1, $player); return 2;
        case "1_Attack": AddCurrentTurnEffect("ELE000-1", $player); return 3;
        case "1_Defense": AddCurrentTurnEffect("ELE000-2", $player); return 4;
      }
      return $lastResult;
    case "SETDQVAR":
      $dqVars[$parameter] = $lastResult;
      return $lastResult;
    case "INCDQVAR":
      $dqVars[$parameter] += $lastResult;
      return $lastResult;
    case "DIVIDE":
      return floor($lastResult / $parameter);
    case "DQVARPASSIFSET":
      if($dqVars[$parameter] == "1") return "PASS";
      return "PROCEED";
    case "LORDSUTCLIFFE":
      LordSutcliffeAfterDQ($player, $parameter);
      return $lastResult;
    case "APPROVEMANUALMODE":
      ApproveManualMode($player);
      return $lastResult;
    case "BINGO":
      if($lastResult == "") WriteLog("No card was revealed for Bingo.");
      $cardType = CardType($lastResult);
      if($cardType == "AA") { WriteLog("Bingo gained Go Again."); GiveAttackGoAgain(); }
      else if($cardType == "A") {WriteLog("Bingo drew a card."); Draw($player); }
      else WriteLog("Bingo... did not hit the mark.");
      return $lastResult;
    case "ADDCARDTOCHAIN":
      AddCombatChain($lastResult, $player, $parameter, 0);
      return $lastResult;
    case "EVENBIGGERTHANTHAT":
      $deck = &GetDeck($player);
      RevealCards($deck[0]);
      if(AttackValue($deck[0]) > GetClassState(($player == 1 ? 2 :1), $CS_DamageTaken))
      {
        WriteLog("Even Bigger Than That! drew a card and created a Quicken token.");
        Draw($player);
        PlayAura("WTR225", $player);
      }
      return $lastResult;
    case "KRAKENAETHERVEIN":
      if($lastResult > 0)
      {
        for($i=0; $i<$lastResult; ++$i) Draw($player);
      }
      return $lastResult;
    case "HEAVE":
      PrependDecisionQueue("PAYRESOURCES", $player, "<-");
      AddArsenal($lastResult, $player, "HAND", "UP");
      $heaveValue = HeaveValue($lastResult);
      for($i=0; $i<$heaveValue; ++$i)
      {
        PlayAura("WTR075", $player);
      }
      WriteLog("You must pay " . HeaveValue($lastResult) . " resources to heave this.");
      return HeaveValue($lastResult);
    case "POTIONOFLUCK":
      $arsenal = &GetArsenal($player);
      $hand = &GetHand($player);
      $deck = &GetDeck($player);
      $sizeToDraw = count($hand) + count($arsenal)/ArsenalPieces();
      $i = 0;
      while (count($hand)>0) {
        array_push($deck, $hand[$i]);
        unset($hand[$i]);
        ++$i;
      }
      for ($i=0; $i < $sizeToDraw; $i++) {
        PrependDecisionQueue("DRAW", $currentPlayer, "-", 1);
      }
      PrependDecisionQueue("FULLARSENALTODECK", $currentPlayer, "-", 1);
      PrependDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      WriteLog("Potion of Luck shuffled your hand and arsenal into your deck and drew " . $sizeToDraw . " cards.");
      return $lastResult;
    case "BRAVOSTARSHOW":
      $hand = &GetHand($player);
      $cards = "";
      $hasLightning = false; $hasIce = false; $hasEarth = false;
      for($i=0; $i<count($lastResult); ++$i)
      {
        if($cards != "") $cards .= ",";
        $card = $hand[$lastResult[$i]];
        if(TalentContains($card, "LIGHTNING")) $hasLightning = true;
        if(TalentContains($card, "ICE")) $hasIce = true;
        if(TalentContains($card, "EARTH")) $hasEarth = true;
        $cards .= $card;
      }
      RevealCards($cards);
      if($hasLightning && $hasIce && $hasEarth)
      {
        WriteLog("Bravo, Star of the Show gives the next attack with cost 3 or more +2, Dominate, and Go Again.");
        AddCurrentTurnEffect("EVR017", $player);
      }
      return $lastResult;
    case "SETDQCONTEXT":
      $dqState[4] = implode("_", explode(" ", $parameter));
      return $lastResult;
    case "AFTERDIEROLL":
      AfterDieRoll($player);
      return $lastResult;
    case "PICKACARD":
      $hand = &GetHand(($player == 1 ? 2 : 1));
      $rand = rand(0, count($hand)-1);
      RevealCards($hand[$rand]);
      if(CardName($hand[$dqVars[0]]) == CardName($hand[$rand])) { WriteLog("Bingo! Your opponent tossed you a silver."); PutItemIntoPlayForPlayer("EVR195", $player); }
      return $lastResult;
    case "TWINTWISTERS":
      switch($lastResult)
      {
        case "Hit_Effect": WriteLog("If Twin Twisters hits, the next attack gets +1 attack."); AddCurrentTurnEffect($parameter . "-1", $player); return 1;
        case "1_Attack": WriteLog("Twin Twisters gets +1 attack."); AddCurrentTurnEffect($parameter . "-2", $player); return 2;
      }
      return $lastResult;
    case "AETHERWILDFIRE":
      AddCurrentTurnEffect("EVR123," . $lastResult, $player);
      return $lastResult;
    case "CLEAREFFECTCONTEXT":
      SetClassState($currentPlayer, $CS_EffectContext, "-");
      return $lastResult;
    case "MICROPROCESSOR":
      $deck = &GetDeck($player);
      switch($lastResult)
      {
        case "Opt": Opt("EVR070", 1); break;
        case "Draw_then_top_deck": if(count($deck) > 0) { Draw($player); HandToTopDeck($player); } break;
        case "Banish_top_deck": if(count($deck) > 0) { $card = array_shift($deck); BanishCardForPlayer($card, $player, "DECK", "-"); } break;
        default: break;
      }
      return "";
    case "TALISMANOFCREMATION":
      $discard = &GetDiscard($player);
      $cardName = CardName($discard[$lastResult]);
      $count = 0;
      for($i=count($discard)-DiscardPieces(); $i>=0; $i-=DiscardPieces())
      {
        if(CardName($discard[$i]) == $cardName)
        {
          BanishCardForPlayer($discard[$i], $player, "GY");
          RemoveGraveyard($player, $i);
          ++$count;
        }
      }
      WriteLog("Talisman of Cremation banished " . $count . " cards named " . $cardName . ".");
      return "";
    case "SCOUR":
      WriteLog("Scour deals " . $parameter . " arcane damage.");
      DealArcane($parameter, 0, "PLAYCARD", "EVR124", true);
      return "";
    case "KNICKKNACK":
      for($i=0; $i<($dqVars[0]+1); ++$i)
      {
        PrependDecisionQueue("PUTPLAY", $player, "-", 1);
        PrependDecisionQueue("CHOOSEDECK", $player, "<-", 1);
        PrependDecisionQueue("FINDINDICES", $player, "KNICKKNACK");
      }
      return "";
    case "CASHOUTCONTINUE":
      PrependDecisionQueue("CASHOUTCONTINUE", $currentPlayer, "-", 1);
      PrependDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
      PrependDecisionQueue("PASSPARAMETER", $currentPlayer, "EVR195", 1);
      PrependDecisionQueue("MULTIZONEDESTROY", $currentPlayer, "-", 1);
      PrependDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      PrependDecisionQueue("FINDINDICES", $currentPlayer, "CASHOUT");
      return "";
    case "SETABILITYTYPE":
      $lastPlayed[2] = $lastResult;
      $index = GetAbilityIndex($parameter, GetClassState($player, $CS_CharacterIndex), $lastResult);
      SetClassState($player, $CS_AbilityIndex, $index);
      $names = explode(",", GetAbilityNames($parameter, GetClassState($player, $CS_CharacterIndex)));
      WriteLog(implode(" ", explode("_", $names[$index])) . " ability was chosen.");
      return $lastResult;
    case "ENCASEDAMAGE":
      $character = &GetPlayerCharacter($player);
      $character[8] = 1;
      for($i=CharacterPieces(); $i<count($character); $i+=CharacterPieces())
      {
        if(CardType($character[$i]) == "E") $character[$i+8] = 1;
      }
      return $lastResult;
    case "MZSTARTTURNABILITY":
      $params = explode("-", $lastResult);
      $zone = &GetMZZone($player, $params[0]);
      $cardID = $zone[$params[1]];
      MZStartTurnAbility($cardID);
      return "";
    case "MZREMOVE":
      $params = explode("-", $lastResult);
      $zone = &GetMZZone($player, $params[0]);
      $cardID = $zone[$params[1]];
      Remove($cardID);
      return "";

    case "TRANSFORM":
      return "ALLY-" . ResolveTransform($player, $lastResult, $parameter);
    case "TRANSFORMPERMANENT":
      return "PERMANENT-" . ResolveTransformPermanent($player, $lastResult, $parameter);
    case "MZGETUNIQUEID":
      $params = explode("-", $lastResult);
      $zone = &GetMZZone($player, $params[0]);
      switch($params[0])
      {
        case "ALLY": return $zone[$params[1] + 5];
        case "BANISH": return $zone[$params[1] + 2];
      }
      return "-1";
    case "SIFT":
      $numCards = SearchCount($lastResult);
      for($i=0; $i<$numCards; ++$i)
      {
        Draw($player);
      }
      return "1";
    case "CCFILTERTYPE":
      $arr = explode(",", $lastResult);
      $rv = [];
      for($i=0; $i<count($arr); ++$i)
      {
        if(CardType($combatChain[$arr[$i]]) != $parameter) array_push($rv, $arr[$i]);
      }
      $rv = implode(",", $rv);
      return ($rv == "" ? "PASS" : $rv);
    case "CCFILTERPLAYER":
      $arr = explode(",", $lastResult);
      $rv = [];
      for($i=0; $i<count($arr); ++$i)
      {
        if($combatChain[$arr[$i]+1] != $parameter) array_push($rv, $arr[$i]);
      }
      $rv = implode(",", $rv);
      return ($rv == "" ? "PASS" : $rv);
    case "ITEMGAINCONTROL":
      $otherPlayer = ($player == 1 ? 2 : 1);
      StealItem($otherPlayer, $lastResult, $player);
      return $lastResult;
    default:
      return "NOTSTATIC";
  }
}

?>
