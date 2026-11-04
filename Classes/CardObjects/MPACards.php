<?php

class mortimer_base extends BaseCard {
  function PlayAbility($from, $target) {
    $abilityType = GetResolvedAbilityType($this->cardID, $from, $this->controller);
    if ($abilityType == "I") {
      CureThem($this->controller);
      Await($this->controller, "PlayItem", cardID:"silver", final:true);
    }
    elseif ($abilityType == "AR") {
      if (str_contains($target, "COMBATCHAINLINK"))
        AddCurrentTurnEffect($this->cardID, $this->controller, "PLAY");
      elseif (str_contains($target, "ATTACKQUEUE"))
        WriteLog("Targeting attack queue not yet supported for $this->cardID", highlight:true);
    }
  }

  private
  function GetTargets() {
    $targets = [];
    $attacks = TargetAttack($this->controller);
    foreach($attacks as $attack) {
      $cardID = GetMZCard($this->controller, $attack);
      if (ClassContains($cardID, "ASSASSIN", $this->controller))
        $targets[] = $attack;
    }
    return $targets;
  }

  function GetAbilityNames($from, $index) {
    $names = ["-", "-"];
    //can it instant?
    $instantRestricted = InstantRestricted($this->cardID, $from, $index);
    if (!$instantRestricted) $names[0] = "Instant";
    $silverCount = CountItemByName("Silver", $this->controller);
    if ($silverCount > 1 && count($this->GetTargets()) > 0 && IsReactionPhase())
      $names[1] = "AttackReaction";
    if ($names[1] == "-") return $names[0];
    return implode(",", $names);
  }

  function IsPlayRestricted($index, $from) {
    $names = GetAbilityNames($this->cardID, $index, $from);
    if ($names == "-") return true;
    $CharacterCard = new CharacterCard($index, $this->controller);
    return $CharacterCard->Tapped();
  }

  function PayAdditionalCosts($from, $index) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->TapForCost();
    $abilityType = GetResolvedAbilityType($this->cardID, $from, $this->controller);
    if ($abilityType == "AR") {
      AddDecisionQueue("PASSPARAMETER", $this->controller, "silver-2", 1);
      AddDecisionQueue("FINDANDDESTROYITEM", $this->controller, "<-", 1);
      $targets = $this->GetTargets();
      Await($this->controller, "ChooseMultiZone", "index", indices:implode(",", $targets), subsequent:0);
      Await($this->controller, "SetLayerTarget", layerID:$this->cardID, final:true);
    }
  }
}

class dr_mortimer extends Card {
  function __construct($controller) {
    $this->cardID = "dr_mortimer";
    $this->controller = $controller;
    $this->baseCard = new mortimer_base($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $target);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return "I,AR";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-", $allNames = false) {
    return $this->baseCard->GetAbilityNames($from, $index);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted($index, $from);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts($from, $index);
  }

  function SpecialName() {
    return "Dr. Mortimer";
  }

  function DefaultActiveState() {
    return 1;
  }
}

class dr_mortimer_blight_of_the_pits extends Card {
  function __construct($controller) {
    $this->cardID = "dr_mortimer_blight_of_the_pits";
    $this->controller = $controller;
    $this->baseCard = new mortimer_base($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $target);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return "I,AR";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-", $allNames = false) {
    return $this->baseCard->GetAbilityNames($from, $index);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted($index, $from);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts($from, $index);
  }

  function DefaultActiveState() {
    return 1;
  }
}

class remember_the_mists_blue extends Card {
  function __construct($controller) {
    $this->cardID = "remember_the_mists_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return !str_contains($from, "HAND") && !str_contains($from, "ARS") ? 2 : 0;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $mainPlayer;
    MZMoveCard($mainPlayer, "THEIRHAND", "THEIRBANISH,HAND,NT,$cardID,$mainPlayer");
  }
}

class prey_on_insecurity_red extends Card {
  function __construct($controller) {
    $this->cardID = "prey_on_insecurity_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "COMBATCHAINATTACKS") {
      if (str_contains($target, "COMBATCHAINLINK"))
        AddCurrentTurnEffect($this->cardID, $this->controller, "PLAY");
      elseif (str_contains($target, "ATTACKQUEUE"))
        WriteLog("Targeting attack queue not yet supported for $this->cardID", highlight:true);
    }
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return ($from == "PLAY" || $from == "COMBATCHAINATTACKS") ? "AR": "AA";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }

  private
  function GetTargets($index) {
    $targets = [];
    $attacks = TargetAttack($this->controller);
    foreach($attacks as $attack) {
      $cardID = GetMZCard($this->controller, $attack);
      if (str_contains($attack, "PASTCHAINLINK")) {
        $linkNum = explode("-", $attack)[2] ?? -1;
        if ($linkNum == $index) continue; // it can't target itself
      }
      if (HasStealth($cardID))
        $targets[] = $attack;
    }
    return $targets;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $mainPlayer;
    if ($this->controller != $mainPlayer) return true;
    if ($from != "PLAY" && $from != "COMBATCHAINATTACKS") return false;
    if ($from == "PLAY") return true; // can't use it if its the active chain link
    $ChainLink = new ChainLink($index);
    $LinkCard = $ChainLink->AttackCard();
    if ($from == "COMBATCHAINATTACKS") {
      if (!IsReactionPhase()) return true;
      $hand = GetHand($this->controller);
      if (count($hand) == 0) return true;
      if (!$LinkCard->StillOnChain() || count($this->GetTargets($index)) == 0) return true;
    }
    return false;
  }

  function AbilityPlayableFromCombatChain($index = '-') {
    global $mainPlayer;
    return $this->controller == $mainPlayer;
  }

  function PayAdditionalCosts($from, $index = '-') {
    if ($from == "COMBATCHAINATTACKS") {
      $i = intdiv($index, ChainLinksPieces());
      $Link = new ChainLink($i);
      $LinkCard = $Link->AttackCard();
      $LinkCard->Destroy();

      $targets = $this->GetTargets($index);
      $hand = &GetHand($this->controller);
      if (count($hand) == 0) {
        WriteLog("You do not have a card to sink. Reverting gamestate.", highlight: true);
        RevertGamestate();
        return;
      }
      BottomDeck();
      Await($this->controller, "ChooseMultiZone", "index", indices:implode(",", $targets), subsequent:0);
      Await($this->controller, "SetLayerTarget", layerID:$this->cardID, final:true);
    }
  }
}

class mutually_assured_destruction_red extends Card {
  function __construct($controller) {
    $this->cardID = "mutually_assured_destruction_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ContractType($chosenName = '') {
    return "BANISHINFECTED";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller);
  }

  function AttackPlayCardAbility($cardID, $from) {
    global $currentPlayer, $mainPlayer, $combatChainState, $CCS_AttackReactionsPlayed, $CCS_DefenseReactionsPlayed;
    $piece = $currentPlayer == $mainPlayer ? $CCS_AttackReactionsPlayed : $CCS_DefenseReactionsPlayed;
    if ($combatChainState[$piece] == 1 && !IsActivated($cardID, $from) && (TypeContains($cardID, "DR") || TypeContains($cardID, "AR")))
      AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $mainPlayer, $defPlayer;
    $players = [$mainPlayer, $defPlayer];
    foreach ($players as $player) 
      PlayAura("bloodrot_pox", $player, effectController:$this->controller, effectSource:$this->cardID);
    foreach($players as $player) {
      $deck = new Deck($player);
      if($deck->Empty()) 
        WriteLog("The deck is already depleted.");
      else $deck->BanishTop(banishedBy:$this->cardID, banisher:$this->controller);
    }
  }
}

class seed extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $disease = match($this->cardID) {
      "seed_bloodrot_blue" => "bloodrot_pox",
      "seed_frailty_blue" => "frailty",
      "seed_inertia_blue" => "inertia",
      default => ""
    };
    $otherPlayer = OtherPlayer($this->controller);
    PlayAura($disease, $this->controller, effectController:$this->controller, effectSource:$this->cardID, effectAgent:$this->controller);
    PlayAura($disease, $otherPlayer, effectController:$this->controller, effectSource:$this->cardID, effectAgent:$this->controller);
    return "";
  }
}

class seed_bloodrot_blue extends Card {
  function __construct($controller) {
    $this->cardID = "seed_bloodrot_blue";
    $this->controller = $controller;
    $this->baseCard = new seed($this->cardID, $this->controller);
  }
}

class seed_frailty_blue extends Card {
  function __construct($controller) {
    $this->cardID = "seed_frailty_blue";
    $this->controller = $controller;
    $this->baseCard = new seed($this->cardID, $this->controller);
  }
}

class seed_inertia_blue extends Card {
  function __construct($controller) {
    $this->cardID = "seed_inertia_blue";
    $this->controller = $controller;
    $this->baseCard = new seed($this->cardID, $this->controller);
  }
}

class prolonged_illness_blue extends Card {
  function __construct($controller) {
    $this->cardID = "prolonged_illness_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function EffectDestroyedEffect($cardID, $controller, $effectIndex, $permIndex, $from) {
		global $CS_FrailtyDestroyed, $CS_InertiaDestroyed, $CS_BloodrotDestroyed, $CS_ExertionDestroyed, $mainPlayer, $defPlayer;
    $Effect = new CurrentEffect($effectIndex);
    $Disease = new AuraCard($permIndex, $controller);
    if (!SubtypeContains($Disease->CardID(), "Disease")) return;
    $diseaseName = NameOverride($Disease->CardID(), $controller);
		$bloodrot = $diseaseName == "Bloodrot Pox" && GetClassState($mainPlayer, $CS_BloodrotDestroyed) + GetClassState($defPlayer, $CS_BloodrotDestroyed) == 0;
		$frailty = $diseaseName == "Frailty" && GetClassState($mainPlayer, $CS_FrailtyDestroyed) + GetClassState($defPlayer, $CS_FrailtyDestroyed) == 0;
		$inertia = $diseaseName == "Inertia" && GetClassState($mainPlayer, $CS_InertiaDestroyed) + GetClassState($defPlayer, $CS_InertiaDestroyed) == 0;
		$exertion = $diseaseName == "Exertion" && GetClassState($mainPlayer, $CS_ExertionDestroyed) + GetClassState($defPlayer, $CS_ExertionDestroyed) == 0;
    if ($bloodrot || $frailty || $inertia || $exertion)
      AddLayer("TRIGGER", $this->controller, $this->cardID, $controller, $Disease->CardID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura($additionalCosts, $target, effectController:$this->controller, effectSource:$this->cardID);
    WriteLog(CardLink($additionalCosts) . " has been prolonged!");
  }
}

class outbreak_blue extends Card {
  function __construct($controller) {
    $this->cardID = "outbreak_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddEffectToAttack($this->controller, $this->cardID, $target, true);
    return "";
  }

  private
  function GetTargets() {
    $choices = [];
    $attacks = TargetAttack($this->controller);
    foreach($attacks as $attack) {
      $cardID = GetMZCard($this->controller, $attack);
      if (ClassContains($cardID, "ASSASSIN", $this->controller) && TypeContains($cardID, "AA"))
        $choices[] = $attack;
    }
    return $choices;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return count($this->GetTargets()) == 0;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $choices = implode(",", $this->GetTargets());
    SetTargetsChoices($this->controller, $this->cardID, $choices, "Target an Assassin attack action card");
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    return HeroHitTrigger($this->controller, $this->cardID, $check, true);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    global $defPlayer, $CombatChain, $Diseases;
    foreach($Diseases as $disease)
      PlayAura($disease, $defPlayer, effectSource:$CombatChain->AttackCard()->ID(), effectAgent:$this->controller);
  }
}

class phial_of_calamity extends Card {
  function __construct($controller) {
    $this->cardID = "phial_of_calamity";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		if (str_contains($target, "MYCHAR")) {
			$daggerUID = explode("-", $target)[1] ?? "-";
			AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID:$daggerUID);
		}
		else
			AddEffectToAttack($this->controller, $this->cardID, $target, true);
		return "";
  }

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function IsCombatEffectPersistent($mode) {
		return true;
	}

	function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
		return HeroHitTrigger($this->controller, $this->cardID, $check, true, $target);
	}

	function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
		global $Diseases, $defPlayer;
		$target = !is_numeric($target) ? $defPlayer : $target;
		$indices = [];
		foreach($Diseases as $disease)
			$indices[] = "CARDID-$disease";
		$indices = implode(",", $indices);
		$context = $target == $defPlayer ? "infect your opponent" : "infect yourself";
		Await($this->controller, "ChooseMultiZone", "cardID", indices:$indices, context:$context, subsequent:0);
		Await($target, "PlayAura", effectController:$this->controller, effectSource:$source, effectAgent:$this->controller, final:true);
	}

	function Targets() {
		global $CombatChain;
		$attackID = $CombatChain->AttackCard()->ID();
		$search = SearchMultizone($this->controller, DaggerSearch());
		return SubtypeContains($attackID, "Dagger") && TypeContains($attackID, "AA") ? $search . ",COMBATCHAINLINK-0" : $search;
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return SearchCount($this->Targets()) == 0;
	}

	function AbilityType($index = -1, $from = '-') {
		return "AR";
	}

	function AbilityCost() {
		return 1;
	}

	function PayAbilityAdditionalCosts($index, $from = '-', $zoneIndex = -1) {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->Destroy();
		SetTargetsChoices($this->controller, $this->cardID, $this->Targets(), "Target a Dagger");
	}

	function DiscardStartTurnTrigger($index) {
		SilverBuyback($this->controller, $index);
	}
}

class cadaver_handlers extends Card {
  function __construct($controller) {
    $this->cardID = "cadaver_handlers";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CombatChain, $defPlayer;
    if (!ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $this->controller))
      return;
    AddCurrentTurnEffect($this->cardID, $defPlayer);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectBlockModifier($index, $from, $effectInd) {
    return -1;
  }

  function AbilityType($index = -1, $from = '-') {
    return "AR";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $defPlayer, $CombatChain;
    // it can technically target previous chain links for no effect
    if (!IsInfected($defPlayer)) return true;
    if (!ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $this->controller)) return true;
    return false;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function DiscardStartTurnTrigger($index) {
		SilverBuyback($this->controller, $index);
	}
}

class demoralize_the_dracai_red extends Card {
  function __construct($controller) {
    $this->cardID = "demoralize_the_dracai_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CombatChainHitEffect($sourceID, $targetPlayer, $check) {
    global $defPlayer;
    $targetPlayer = $targetPlayer == "HERO" ? $defPlayer : $targetPlayer;
    if (is_numeric($targetPlayer)) {
      if (TypeContains($sourceID, "AA")) {
        if (!$check)
          AddLayer("TRIGGER", $this->controller, $this->cardID, $targetPlayer, "ONHITEFFECT");
        return true;
      }
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $deck = new Deck($target);
    $deck->BanishTop(banishedBy:$this->cardID, banisher:$this->controller);
  }

  function ContractType($chosenName = '') {
    return "REDPITCH";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller);
  }
}

class tailored_targeting_red extends Card {
  function __construct($controller) {
    $this->cardID = "tailored_targeting_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    AddCurrentTurnEffect($this->cardID, $this->controller); // holds the "when you hit this chain link" trigger
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function AddCardEffectHitTrigger($sourceID, $targetPlayer, $mode) {
    if ($targetPlayer == $this->controller) return false;
    return HeroHitTrigger($this->controller, $this->cardID, false, true, $targetPlayer);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    global $defPlayer, $CombatChain;
    $Deck = new Deck($defPlayer);
    $targetedName = GetAttackNamedCard();
    $banishedCard = $targetedName; // ensure you go through the process at least once
    while (!$Deck->Empty() && $banishedCard == $targetedName) {
      $cardID = $Deck->BanishTop(banishedBy:$this->cardID, banisher:$this->controller);
      $banishedCard = GamestateSanitize(NameOverride($cardID, $defPlayer));
    }
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    //name the card
    AddDecisionQueue("INPUTCARDNAME", $this->controller, "-");
    AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
    AddDecisionQueue("WRITELOG", $this->controller, "<b>📣{0}</b> is being hunted!", 1);
    //Adding the name to the card to track
    AddDecisionQueue("PREPENDLASTRESULT", $this->controller, "NAMEDCARD|", 1);
    AddDecisionQueue("ADDSTATICBUFF", $this->controller, 0, 1);
  }

  function ContractType($chosenName = '') {
    return "NAMEDCARD-$chosenName";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller);
  }
}

class flip_the_script_blue extends Card {
  function __construct($controller) {
    $this->cardID = "flip_the_script_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AttackLeavesTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CombatChain;
    if ($CombatChain->HasCurrentLink())
      AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }
}

class bribe_the_guards_blue extends Card {
  function __construct($controller) {
    $this->cardID = "bribe_the_guards_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $targetCard = MZIndexToObject($this->controller, $target);
    if ($targetCard != "") {
      $fromPlay = $targetCard->From() == "EQUIP" || $targetCard->From() == "PLAY";
      $uid = $fromPlay ? $targetCard->OriginUniqueID() : $targetCard->UniqueID();
      if ($from == "PLAY" || $from == "COMBATCHAINATTACKS")
        AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID:$uid);
      return "";
    }
  }

  function EffectBlockModifier($index, $from, $effectInd) {
    $uniqueID = IndexToBlockCardUID($index);
    $Effect = new CurrentEffect($effectInd);
    return $Effect->AppliestoUniqueID() == $uniqueID ? -1 : 0;
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function RemoveEffectFromCombatChain($effectIndex) {
    return true;
  }

  function IsPlayRestricted(&$restriction, $from="", $index=-1, $resolutionCheck=false) {
    global $mainPlayer, $CombatChain, $ChainLinks;
    if ($this->controller != $mainPlayer) return true;
    if ($from != "PLAY" && $from != "COMBATCHAINATTACKS") return false;
    if ($from == "PLAY" || $from == "COMBATCHAINATTACKS") {
      if ($this->GetTargets($from, $index) == "") return true;
      return CountItemByName("Silver", $this->controller) == 0;
    }
    return false;
  }

  function AbilityPlayableFromCombatChain($index="-") {
    global $mainPlayer;
    return $this->controller == $mainPlayer;
  }

  function AbilityType($index = -1, $from = '-') {
    return ($from == "PLAY" || $from == "COMBATCHAINATTACKS") ? "I": "AA";
  }

  private
  function GetTargets($from, $index) {
    global $CombatChain, $ChainLinks;
    $targets = [];
    if ($from == "CC" || $from == "PLAY") {
      for ($i = 1; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
        $targets[] = "COMBATCHAINLINK-" . $CombatChain->Card($i, true)->Index();
      }
    }
    elseif ($from == "COMBATCHAINATTACKS") {
      $Link = $ChainLinks->GetLink($index);
      for ($i = 1; $i < $Link->NumCards(); ++$i) {
        $targets[] = "PASTCHAINLINK-" . $Link->GetLinkCard($i, true)->Index() . "-$index";
      }
    }
    return implode(",", $targets);
  }

  function PayAdditionalCosts($from, $index = '-') {
    if ($from == "CC" || $from == "COMBATCHAINATTACKS") {
      Await($this->controller, "MultiZoneIndices", search:"MYITEMS:isSameName=silver", subsequent:0);
      Await($this->controller, "ChooseMultiZone", context:"Destroy a silver you control");
      Await($this->controller, "MZDestroy", final:true);
      $targets = $this->GetTargets($from, $index);
      SetTargetsChoices($this->controller, $this->cardID, $targets, "Give a defending card -1 block");
    }
  }
}

class continued_coercion_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "continued_coercion_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AttackLeavesTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CombatChain;
    if ($CombatChain->HasCurrentLink())
      AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }
}

class sick_intent_red extends Card {
  function __construct($controller) {
    $this->cardID = "sick_intent_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $Diseases, $defPlayer;
    $diseaseChoices = [];
    foreach ($Diseases as $disease)
      $diseaseChoices[] = "CARDID-$disease";
    $diseaseChoices = implode(",", $diseaseChoices);
    Await($this->controller, "ChooseMultiZone", "cardID", indices:$diseaseChoices, context:"Infect your opponent", subsequent:0);
    Await($defPlayer, "PlayAura", effectController:$this->controller, effectSource:$this->cardID, effectAgent:$this->controller, final:true);
  }

  function ContractType($chosenName = '') {
    return "INFECT";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller);
  }

  function SpecialName() {
    return "Sick Intent";
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialPower() {
    return 6;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class concoct_agony_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "concoct_agony_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return NumAttackReactionsPlayed() >= 3 ? 3 : 0;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (NumAttackReactionsPlayed() < 3) return false;
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    $Arsenal = new Arsenal($defPlayer);
    $Arsenal->BanishAll($this->controller);
  }

  function SpecialName() {
    return "Concoct Agony";
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialPower() {
    return 2;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }

  function HasStealth() {
    return true;
  }
}

class sickening_exhaustion_blue extends Card {
  function __construct($controller) {
    $this->cardID = "sickening_exhaustion_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $defPlayer;
    if (IsInfected($defPlayer)) {
      $numToBottom = count(GetArsenal($defPlayer));
      for ($i = 0; $i < $numToBottom; $i++) {
        BottomDeckMultizone($defPlayer, "MYARS", "-", true, "Choose a card from your arsenal to add on the bottom of your deck");
      }
    }
    return "";
  }

  function SpecialName() {
    return "Sickening Exhaustion";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AR";
  }
}

class sickening_rot_blue extends Card {
  function __construct($controller) {
    $this->cardID = "sickening_rot_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $defPlayer;
    if (IsInfected($defPlayer)) {
      $hand = &GetHand($defPlayer);
      $resources = &GetResources($defPlayer);
      if ((Count($hand) > 0 || $resources[0] > 0) && !IsPlayerAI($defPlayer)) {
        AddDecisionQueue("YESNO", $defPlayer, "if_you_want_to_pay_3_to_avoid_taking_2_damage", 0, 1);
        AddDecisionQueue("NOPASS", $defPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $defPlayer, "3", 1);
        AddDecisionQueue("PAYRESOURCES", $defPlayer, "3", 1);
        AddDecisionQueue("ELSE", $defPlayer, "-");
      }
      AddDecisionQueue("PASSPARAMETER", $defPlayer, "2-$this->cardID-DAMAGE-$this->controller", 1);
      AddDecisionQueue("DEALDAMAGE", $defPlayer, "MYCHAR-0", 1);
    }
    return "";
  }

  function SpecialName() {
    return "Sickening Rot";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AR";
  }
}

class sickening_weakness_blue extends Card {
  // If the defending hero is infected, defending cards get -1{d} this chain link. (A hero is infected if they control a disease.)
  function __construct($controller) {
    $this->cardID = "sickening_weakness_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $defPlayer;
    if (IsInfected($defPlayer)) {
      AddCurrentTurnEffect($this->cardID, $defPlayer);
    }
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectBlockModifier($index, $from, $effectInd) {
    return -1;
  }

  function SpecialName() {
    return "Sickening Weakness";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AR";
  }
}

class spike_slasher_red extends Card {
  function __construct($controller) {
    $this->cardID = "spike_slasher_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function RemoveEffectLinkResolves() {
    return true;
  }

  function CurrentEffectCostModifier($cardID, $from, &$remove, $index, $playIndex) {
    return TypeContains($cardID, "AR") && !IsActivated($cardID, $from) ? -1 : 0;
  }

  function SpecialName() {
    return "Spike Slasher";
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialPower() {
    return 3;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }

  function HasStealth() {
    return true;
  }
}

class painful_choice_red extends Card {
  function __construct($controller) {
    $this->cardID = "painful_choice_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    Await($this->controller, "MultiZoneIndices", search:"MYHAND", subsequent:0);
    $context = "Put a card from your hand on the bottom to give the opponent a " . CardLink($this->cardID);
    Await($this->controller, "ChooseMultiZone", context:$context, may:true);
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $defPlayer, $dqVars;
    $MZindex = $dqVars["MZIndex"] ?? "-";
    $index = explode("-", $MZindex)[1] ?? -1;
    if ($index != -1) {
      $cardID = RemoveHand($this->controller, $index);
      $Deck = new Deck($this->controller);
      $Deck->AddBottom($cardID, "HAND");

      PrependDecisionQueue("ADDCURRENTTURNEFFECT", $this->controller, $this->cardID, 1);
      PrependDecisionQueue("ELSE", $this->controller, "-");
      Await($defPlayer, "MZRemoveAndBanish", banisher:$this->controller, prepend:true);
      $context = "Choose a card to be banished (or pass and give the attack +4)";
      Await($defPlayer, "ChooseMultiZone", may:true, context:$context, prepend:true);
      Await($defPlayer, "MultiZoneIndices", search:"MYHAND", prepend:true, subsequent:0);
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 4;
  }

  function SpecialName() {
    return "Painful Choice";
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialPower() {
    return 3;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }

  function HasStealth() {
    return true;
  }
}

class patrons_lockbox extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ContractCompleteEffect($index) {
    $Item = new ItemCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, $Item->UniqueID());
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "A";
  }

  function SpecialSubType() {
    return "Item";
  }

  function HasGoAgain($from) {
    return true;
  }
}

class patrons_lockbox_valuables_blue extends Card {
  // Go again
  // When you complete a contract, destroy this. If you do, create 2 Silver tokens.
  function __construct($controller) {
    $this->cardID = "patrons_lockbox_valuables_blue";
    $this->controller = $controller;
    $this->baseCard = new patrons_lockbox($this->cardID, $this->controller);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Items = new Items($this->controller);
    $Item = $Items->FindCardUID($target);
    if ($Item->Index() != -1) {
      $Item->Destroy();
      PutItemIntoPlayForPlayer("silver", $this->controller, number:2, effectSource:$this->cardID);
    }
  }

  function SpecialName() {
    return "Patron's Lockbox: Valuables";
  }
}

class patrons_lockbox_carriers_blue extends Card {
  // Go again
  // When you complete a contract, destroy this. If you do, create 2 Silver tokens.
  function __construct($controller) {
    $this->cardID = "patrons_lockbox_carriers_blue";
    $this->controller = $controller;
    $this->baseCard = new patrons_lockbox($this->cardID, $this->controller);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Items = new Items($this->controller);
    $Item = $Items->FindCardUID($target);
    if ($Item->Index() != -1) {
      $Item->Destroy();
      DeathTouch($this->controller, $this->cardID);
    }
  }

  function SpecialName() {
    return "Patron's Lockbox: Carriers";
  }
}

class patrons_lockbox_serums_blue extends Card {
  // Go again
  // When you complete a contract, destroy this. If you do, create 2 Silver tokens.
  function __construct($controller) {
    $this->cardID = "patrons_lockbox_serums_blue";
    $this->controller = $controller;
    $this->baseCard = new patrons_lockbox($this->cardID, $this->controller);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Items = new Items($this->controller);
    $Item = $Items->FindCardUID($target);
    if ($Item->Index() != -1) {
      $Item->Destroy();
      AddCurrentTurnEffect($this->cardID, $this->controller, from:"PLAY");
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }

  function SpecialName() {
    return "Patron's Lockbox: Serums";
  }
}

class mask_of_one_million_eyes extends Card {
  // While this is in your graveyard, at the start of your turn, you may destroy 2 Silver you control. If you do, equip this.
  // Attack Reaction - Destroy this: Draw a card, then put a card from your hand on the bottom of your deck.
  function __construct($controller) {
    $this->cardID = "mask_of_one_million_eyes";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    DrawAndPutBack($this->controller, $this->cardID, bottom:true);
    return "";
  }

  function DiscardStartTurnTrigger($index) {
    SilverBuyback($this->controller, $index);
  }

  function AbilityType($index = -1, $from = '-') {
    return "AR";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function SpecialName() {
    return "Mask of One Million Eyes";
  }

  function SpecialBlock() {
    return 0;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Head";
  }
}

class break_down_the_bloodline extends BaseCard {
  // Contract - If you are Arakni, you are contracted to banish opponents' Assassin cards. Whenever you complete this contract, create a Silver token.
  // If you are Arakni, this gets "When this hits a hero, banish the top card of their deck."
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ContractType($chosenName = '') {
    $Hero = new CharacterCard(0, $this->controller);
    if (str_contains(NameOverride($Hero->CardID(), $this->controller), "Arakni"))
      return "ASSASSIN";
    return "";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller, effectSource:$this->cardID);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    $Hero = new CharacterCard(0, $this->controller);
    if (!str_contains(NameOverride($Hero->CardID(), $this->controller), "Arakni")) return false;
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    $deck = new Deck($defPlayer);
    if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); return; }
    $deck->BanishTop(banishedBy:$cardID, banisher:$this->controller);
  }

  function SpecialName() {
    return "Break Down the Bloodline";
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class break_down_the_bloodline_red extends Card {
  function __construct($controller) {
    $this->cardID = "break_down_the_bloodline_red";
    $this->controller = $controller;
    $this->baseCard = new break_down_the_bloodline($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 6;
  }
}

class break_down_the_bloodline_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "break_down_the_bloodline_yellow";
    $this->controller = $controller;
    $this->baseCard = new break_down_the_bloodline($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 5;
  }
}

class break_down_the_bloodline_blue extends Card {
  function __construct($controller) {
    $this->cardID = "break_down_the_bloodline_blue";
    $this->controller = $controller;
    $this->baseCard = new break_down_the_bloodline($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 4;
  }
}

class triple_blind extends BaseCard {
  // If you are Mortimer, this gets "When this hits a hero, they choose Frailty, Inertia, or Bloodrot Pox. Create that token under their control."
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    $Hero = new CharacterCard(0, $this->controller);
    if (!str_contains(NameOverride($Hero->CardID(), $this->controller), "Mortimer")) return false;
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $Diseases, $defPlayer;
		$target = !is_numeric($target) ? $defPlayer : $target;
		$indices = [];
		foreach($Diseases as $disease)
			$indices[] = "CARDID-$disease";
		$indices = implode(",", $indices);
		$context = "infect yourself";
		Await($defPlayer, "ChooseMultiZone", "cardID", indices:$indices, context:$context, subsequent:0);
		Await($defPlayer, "PlayAura", effectController:$this->controller, effectSource:$this->cardID, effectAgent:$this->controller, final:true);
  }

  function SpecialName() {
    return "Triple Blind";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class triple_blind_red extends Card {
  function __construct($controller) {
    $this->cardID = "triple_blind_red";
    $this->controller = $controller;
    $this->baseCard = new triple_blind($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 5;
  }
}

class triple_blind_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "triple_blind_yellow";
    $this->controller = $controller;
    $this->baseCard = new triple_blind($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 4;
  }
}

class triple_blind_blue extends Card {
  function __construct($controller) {
    $this->cardID = "triple_blind_blue";
    $this->controller = $controller;
    $this->baseCard = new triple_blind($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 3;
  }
}

class spawn_disease extends BaseCard {
  // When this hits an Assassin hero, create an Inertia token under their control.
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $defPlayer;
    $Hero = new CharacterCard(0, $defPlayer);
    if (!ClassContains($Hero->CardID(), "ASSASSIN", $defPlayer)) return false;
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    $disease = match($this->cardID) {
      "spawn_inertia_red" => "inertia",
      "spawn_frailty_red" => "frailty",
      "spawn_bloodrot_red" => "bloodrot_pox",
      default => "-"
    };
    if ($disease != "-")
      PlayAura($disease, $defPlayer, effectAgent:$this->controller);
  }

  function SpecialName() {
    return "Spawn Inertia";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class spawn_inertia_red extends Card {
  function __construct($controller) {
    $this->cardID = "spawn_inertia_red";
    $this->controller = $controller;
    $this->baseCard = new spawn_disease($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 5;
  }
}

class spawn_frailty_red extends Card {
  function __construct($controller) {
    $this->cardID = "spawn_frailty_red";
    $this->controller = $controller;
    $this->baseCard = new spawn_disease($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 5;
  }
}

class spawn_bloodrot_red extends Card {
  function __construct($controller) {
    $this->cardID = "spawn_bloodrot_red";
    $this->controller = $controller;
    $this->baseCard = new spawn_disease($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 5;
  }
}


class desperate_drive extends BaseCard {
  // Stealth
  // If you are infected, this gets go again. (A hero is infected if they control a disease.)
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function SpecialName() {
    return "Desperate Drive";
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }

  function HasStealth() {
    return true;
  }
}

class desperate_drive_red extends Card {
  function __construct($controller) {
    $this->cardID = "desperate_drive_red";
    $this->controller = $controller;
    $this->baseCard = new desperate_drive($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return IsInfected($this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 3;
  }
}

class desperate_drive_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "desperate_drive_yellow";
    $this->controller = $controller;
    $this->baseCard = new desperate_drive($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 2;
  }
}

class desperate_drive_blue extends Card {
  function __construct($controller) {
    $this->cardID = "desperate_drive_blue";
    $this->controller = $controller;
    $this->baseCard = new desperate_drive($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 1;
  }
}

class free_of_burdens extends BaseCard {
  // Once per Turn Instant - Destroy an aura token you control: This gets +1 power.
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "PLAY") {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsPlayRestricted(&$restriction, $from="", $index=-1, $resolutionCheck=false) {
    global $mainPlayer, $CombatChain, $ChainLinks;
    if ($this->controller != $mainPlayer) return true;
    if ($from != "PLAY" && $from != "COMBATCHAINATTACKS") return false;
    if (AuraCount($this->controller) == 0) return true;
    if ($from == "PLAY" && $CombatChain->AttackCard()->NumTimesUsed() >= 1) return true;
    if ($from == "COMBATCHAINATTACKS") {
      $Link = $ChainLinks->GetLink($index);
      return $Link->AttackCard()->NumTimesUsed() >= 1;
    }
    return false;
  }

  function AbilityPlayableFromCombatChain($index="-") {
    global $mainPlayer;
    return $this->controller == $mainPlayer;
  }

  function AbilityType($index = -1, $from = '-') {
    return ($from == "PLAY" || $from == "COMBATCHAINATTACKS") ? "I": "AA";
  }

  function PayAdditionalCosts($from, $index = '-') {
    global $CombatChain, $ChainLinks;
    if (is_numeric($index)) {
      if ($from == "CC") {
        $i = 0;
      }
      else {
        $i = intdiv($index, ChainLinksPieces());
      }
      if ($from == "CC" || $from == "COMBATCHAINATTACKS") {
        if ($from == "COMBATCHAINATTACKS") $ChainLinks->GetLink($i)->AttackCard()->AddUse(1);
        else $CombatChain->AttackCard()->AddUse(1);
        Await($this->controller, "MultiZoneIndices", search:"MYAURAS", subsequent:0);
        Await($this->controller, "ChooseMultiZone", context:"Destroy an aura you control");
        Await($this->controller, "MZDestroy", final:true);
      }
    }
  }

  function SpecialName() {
    return "Free of Burdens";
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "GENERIC";
  }

  function SpecialType() {
    return "AA";
  }
}

class free_of_burdens_red extends Card {
  function __construct($controller) {
    $this->cardID = "free_of_burdens_red";
    $this->controller = $controller;
    $this->baseCard = new free_of_burdens($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 6;
  }
}

class free_of_burdens_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "free_of_burdens_yellow";
    $this->controller = $controller;
    $this->baseCard = new free_of_burdens($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 5;
  }
}

class free_of_burdens_blue extends Card {
  function __construct($controller) {
    $this->cardID = "free_of_burdens_blue";
    $this->controller = $controller;
    $this->baseCard = new free_of_burdens($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 4;
  }
}

class pinpoint_elimination_blue extends Card {
  // Name a card, then the defending hero reveals the top card of their deck. If it's the named card, banish it and create a Silver token.
  function __construct($controller) {
    $this->cardID = "pinpoint_elimination_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddDecisionQueue("INPUTCARDNAME", $this->controller, "-");
    Await($this->controller, $this->cardID, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars, $defPlayer;
    $choice = $dqVars["LASTRESULT"] ?? "-";
    if ($choice != "-") {
      $choiceName = GamestateUnsanitize($choice);
      WriteLog("<b>📣$choiceName </b> is being hunted!");
      $deck = new Deck($defPlayer);
      if ($deck->Reveal()) {
        $topCard = $deck->Top();
        if (NameOverride($topCard, $defPlayer) == $choiceName) {
          $deck->BanishTop(banisher:$this->controller);
          PutItemIntoPlayForPlayer("silver", $this->controller, effectSource:$this->cardID);
        }
      }
    }
  }

  function SpecialName() {
    return "Pinpoint Elimination";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AR";
  }
}

class easy_prey extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $obj = MZIndexToObject($this->controller, $target);
    if (ClassContains($obj->ID(), "ASSASSIN", $this->controller))
      AddEffectToAttack($this->controller, $this->cardID, $target, true);
    return "";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $targets = GetTargetsAssassinAA($this->controller);
    SetTargetsChoices($this->controller, $this->cardID, $targets);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $targets = GetTargetsAssassinAA($this->controller);
    return $targets == "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    global $defPlayer;
    $val = match($this->cardID) {
      "easy_prey_red" => 3,
      "easy_prey_yellow" => 2,
      "easy_prey_blue" => 1,
    };
    return IsHeroAttackTarget() && IsInfected($defPlayer) ? $val : 0;
  }

  function SpecialName() {
    return "Easy Prey";
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AR";
  }
}

class easy_prey_red extends Card {
  // Target Assassin attack gets +1 power while it's attacking an infected hero. (A hero is infected if they control a disease.)
  function __construct($controller) {
    $this->cardID = "easy_prey_red";
    $this->controller = $controller;
    $this->baseCard = new easy_prey($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }
}

class easy_prey_yellow extends Card {
  // Target Assassin attack gets +1 power while it's attacking an infected hero. (A hero is infected if they control a disease.)
  function __construct($controller) {
    $this->cardID = "easy_prey_yellow";
    $this->controller = $controller;
    $this->baseCard = new easy_prey($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }
}

class easy_prey_blue extends Card {
  // Target Assassin attack gets +1 power while it's attacking an infected hero. (A hero is infected if they control a disease.)
  function __construct($controller) {
    $this->cardID = "easy_prey_blue";
    $this->controller = $controller;
    $this->baseCard = new easy_prey($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }
}

class expense_not_spared_yellow extends Card {
  // As an additional cost to play this, destroy 2 Silver you control.
  // Target Assassin attack action card gets +5 power.
  function __construct($controller) {
    $this->cardID = "expense_not_spared_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CombatChain;
    if (ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $this->controller))
      AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return 5;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CombatChain;
    $attackID = $CombatChain->AttackCard()->ID();
    if (!ClassContains($attackID, "ASSASSIN", $this->controller)) return true;
    if (!TypeContains($attackID, "AA")) return true;
    if (CountItemByName("silver", $this->controller) < 2) return true;
    return false;
  }

  function PayAdditionalCosts($from, $index = '-') {
    AddDecisionQueue("PASSPARAMETER", $this->controller, "silver-2", 1);
    AddDecisionQueue("FINDANDDESTROYITEM", $this->controller, "<-", 1);
  }

  function SpecialName() {
    return "Expense Not Spared";
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AR";
  }
}

class repeat_reconnaissance extends BaseCard {
  // Stealth
  // If you are Uzuri, this gets "When this is defended by 2 or more cards, you may put an attack action card with cost 2 from your graveyard on the bottom of your deck."
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AttackGetsBlockedEffect($start) {
    global $CombatChain;
    $numAlreadyBlocking = intdiv($start, CombatChainPieces()) - 1;
    $numBlocking = $CombatChain->NumCardsActiveLink() - 1;
    $Hero = new CharacterCard(0, $this->controller);
    if ($numBlocking >= 2 && $numAlreadyBlocking < 2 && str_contains(NameOverride($Hero->CardID(), $this->controller), "Uzuri"))
      AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $context = "Put a 2 cost attack on the bottom of your deck (or pass)";
    ChooseAndProcess($this->controller, "MYDISCARD:minCost=2;maxCost=2", $context, $this->cardID, true);
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["MZIndex"] ?? "-";
    $DisCard = MZIndexToObject($this->controller, $choice);
    if ($DisCard != "") {
      WriteLog(CardLink($DisCard->CardID()) . " was put on the bottom of the deck!");
      AddBottomDeck($DisCard->CardID(), $this->controller, "DISCARD");
      $DisCard->Remove();
    }
  }

  function SpecialName() {
    return "Repeat Reconnaissance";
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }

  function HasStealth() {
    return true;
  }
}

class repeat_reconnaissance_red extends Card {
  function __construct($controller) {
    $this->cardID = "repeat_reconnaissance_red";
    $this->controller = $controller;
    $this->baseCard = new repeat_reconnaissance($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 3;
  }
}

class repeat_reconnaissance_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "repeat_reconnaissance_yellow";
    $this->controller = $controller;
    $this->baseCard = new repeat_reconnaissance($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 2;
  }
}

class repeat_reconnaissance_blue extends Card {
  function __construct($controller) {
    $this->cardID = "repeat_reconnaissance_blue";
    $this->controller = $controller;
    $this->baseCard = new repeat_reconnaissance($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 1;
  }
}

class the_spiders_cut extends BaseCard {
  // As an additional cost to play this, destroy a Silver you control.
  // When this hits a hero, create 2 Silver tokens.
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    PutItemIntoPlayForPlayer("silver", $this->controller, number:2);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return CountItemByName("silver", $this->controller) < 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    AddDecisionQueue("PASSPARAMETER", $this->controller, "silver-1", 1);
    AddDecisionQueue("FINDANDDESTROYITEM", $this->controller, "<-", 1);
  }

  function SpecialName() {
    return "The Spider's Cut";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class the_spiders_cut_red extends Card {
  function __construct($controller) {
    $this->cardID = "the_spiders_cut_red";
    $this->controller = $controller;
    $this->baseCard = new the_spiders_cut($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 6;
  }
}

class the_spiders_cut_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "the_spiders_cut_yellow";
    $this->controller = $controller;
    $this->baseCard = new the_spiders_cut($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 5;
  }
}

class the_spiders_cut_blue extends Card {
  function __construct($controller) {
    $this->cardID = "the_spiders_cut_blue";
    $this->controller = $controller;
    $this->baseCard = new the_spiders_cut($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 4;
  }
}

class wristcuff_injectors extends Card {
  // Action - Destroy this: The next Assassin attack action card you play this turn gets "When this hits a hero, create a Frailty, Inertia, or Bloodrot Pox token under their control." Activate this only if you are Mortimer. Go again
  // Blade Break
  function __construct($controller) {
    $this->cardID = "wristcuff_injectors";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    $attackID = $CombatChain->AttackCard()->ID();
    return ClassContains($attackID, "ASSASSIN", $this->controller) && TypeContains($attackID, "AA");
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    return HeroHitTrigger($this->controller, $this->cardID, $check, true);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    global $Diseases, $defPlayer;
    $indices = [];
		foreach($Diseases as $disease)
			$indices[] = "CARDID-$disease";
		$indices = implode(",", $indices);
		$context = "infect your opponent";
		Await($this->controller, "ChooseMultiZone", "cardID", indices:$indices, context:$context, subsequent:0);
		Await($defPlayer, "PlayAura", effectController:$this->controller, effectSource:$source, effectAgent:$this->controller, final:true);
  }

  function AbilityHasGoAgain($from) {
    return true;
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $Hero = new CharacterCard(0, $this->controller);
    return !str_contains(NameOverride($Hero->CardID(), $this->controller), "Mortimer"); 
  }

  function SpecialName() {
    return "Wristcuff Injectors";
  }

  function SpecialBlock() {
    return 1;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Arms";
  }
}

class slice_the_sickly extends BaseCard {
  // Contract - You are contracted to banish infected opponents' cards. Whenever you complete this contract, create a Silver token. (A hero is infected if they control a disease.)
  // When this hits a hero, banish the top card of their deck.
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    $deck = new Deck($defPlayer);
    $deck->BanishTop(banisher:$this->controller);
  }

  function ContractType($chosenName = '') {
    return "BANISHINFECTED";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller);
  }

  function SpecialName() {
    return "Slice the Sickly";
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class slice_the_sickly_red extends Card {
  function __construct($controller) {
    $this->cardID = "slice_the_sickly_red";
    $this->controller = $controller;
    $this->baseCard = new slice_the_sickly($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 4;
  }
}

class slice_the_sickly_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "slice_the_sickly_yellow";
    $this->controller = $controller;
    $this->baseCard = new slice_the_sickly($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 3;
  }
}

class slice_the_sickly_blue extends Card {
  function __construct($controller) {
    $this->cardID = "slice_the_sickly_blue";
    $this->controller = $controller;
    $this->baseCard = new slice_the_sickly($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 2;
  }
}

class in_the_hot_zone extends BaseCard {
  // When this attacks, create a Bloodrot Pox token under your control.
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    PlayAura("bloodrot_pox", $this->controller);
  }

  function SpecialName() {
    return "In the Hot Zone";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class in_the_hot_zone_red extends Card {
  function __construct($controller) {
    $this->cardID = "in_the_hot_zone_red";
    $this->controller = $controller;
    $this->baseCard = new in_the_hot_zone($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 6;
  }
}

class in_the_hot_zone_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "in_the_hot_zone_yellow";
    $this->controller = $controller;
    $this->baseCard = new in_the_hot_zone($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 5;
  }
}

class in_the_hot_zone_blue extends Card {
  function __construct($controller) {
    $this->cardID = "in_the_hot_zone_blue";
    $this->controller = $controller;
    $this->baseCard = new in_the_hot_zone($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 4;
  }
}

class feverish_strike extends BaseCard {
  // If you are infected, this gets +1 power. (A hero is infected if they control a disease.)
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return IsInfected($this->controller) ? 1 : 0;
  }

  function SpecialName() {
    return "Feverish Strike";
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class feverish_strike_red extends Card {
  function __construct($controller) {
    $this->cardID = "feverish_strike_red";
    $this->controller = $controller;
    $this->baseCard = new feverish_strike($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 6;
  }
}

class feverish_strike_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "feverish_strike_yellow";
    $this->controller = $controller;
    $this->baseCard = new feverish_strike($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 5;
  }
}

class feverish_strike_blue extends Card {
  function __construct($controller) {
    $this->cardID = "feverish_strike_blue";
    $this->controller = $controller;
    $this->baseCard = new feverish_strike($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 4;
  }
}

class festering extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $obj = MZIndexToObject($this->controller, $target);
    if (ClassContains($obj->ID(), "ASSASSIN", $this->controller))
      AddEffectToAttack($this->controller, $this->cardID, $target, true);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
		return HeroHitTrigger($this->controller, $this->cardID, $check, true, $target);
	}

	function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
		global $defPlayer;
		$target = !is_numeric($target) ? $defPlayer : $target;
    $disease = match($this->cardID) {
      "festering_rot_yellow" => "bloodrot_pox",
      "festering_exhaustion_yellow" => "inertia",
      "festering_weakness_yellow" => "frailty",
    };
		PlayAura($disease, $target, effectSource:$source, effectController:$this->controller, effectAgent:$this->controller);
	}

  function PayAdditionalCosts($from, $index = '-') {
    $targets = GetTargetsAssassinAA($this->controller);
    SetTargetsChoices($this->controller, $this->cardID, $targets);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $targets = GetTargetsAssassinAA($this->controller);
    return $targets == "";
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AR";
  }
}

class festering_rot_yellow extends Card {
  // Target Assassin attack action card gets go again and "When this hits a hero, create a Bloodrot Pox token under their control."
  function __construct($controller) {
    $this->cardID = "festering_rot_yellow";
    $this->controller = $controller;
    $this->baseCard = new festering($this->cardID, $this->controller);
  }

  function SpecialName() {
    return "Festering Rot";
  }
}

class festering_exhaustion_yellow extends Card {
  // Target Assassin attack action card gets go again and "When this hits a hero, create a Bloodrot Pox token under their control."
  function __construct($controller) {
    $this->cardID = "festering_exhaustion_yellow";
    $this->controller = $controller;
    $this->baseCard = new festering($this->cardID, $this->controller);
  }

  function SpecialName() {
    return "Festering Exhaustion";
  }
}

class festering_weakness_yellow extends Card {
  // Target Assassin attack action card gets go again and "When this hits a hero, create a Bloodrot Pox token under their control."
  function __construct($controller) {
    $this->cardID = "festering_weakness_yellow";
    $this->controller = $controller;
    $this->baseCard = new festering($this->cardID, $this->controller);
  }

  function SpecialName() {
    return "Festering Weakness";
  }
}

class decapitate_the_disloyal extends BaseCard {
  // Contract - You are contracted to banish opponents' cards with no class or talent. Whenever you complete this contract, create a Silver token.
  // When this hits a hero, banish the top card of their deck.
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function SpecialName() {
    return "Decapitate the Disloyal";
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class decapitate_the_disloyal_red extends Card {
  function __construct($controller) {
    $this->cardID = "decapitate_the_disloyal_red";
    $this->controller = $controller;
    $this->baseCard = new decapitate_the_disloyal($this->cardID, $this->controller);
  }

  function ContractType($chosenName = '') {
    return "GENERIC";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller, effectSource:$this->cardID);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    $deck = new Deck($defPlayer);
    $deck->BanishTop(banisher:$this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 6;
  }
}

class decapitate_the_disloyal_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "decapitate_the_disloyal_yellow";
    $this->controller = $controller;
    $this->baseCard = new decapitate_the_disloyal($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 5;
  }
}

class decapitate_the_disloyal_blue extends Card {
  function __construct($controller) {
    $this->cardID = "decapitate_the_disloyal_blue";
    $this->controller = $controller;
    $this->baseCard = new decapitate_the_disloyal($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 4;
  }
}

class eviscerate_the_enervated extends BaseCard {
  // Contract - You are contracted to banish infected opponents' cards. Whenever you complete this contract, create a Silver token. (A hero is infected if they control a disease.)
  // When this hits a hero, banish the top card of their deck.
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ContractType($chosenName = '') {
    return "BANISHINFECTED";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    $Deck = new Deck($defPlayer);
    $Deck->BanishTop(banishedBy:$this->cardID, banisher:$this->controller);
  }

  function SpecialName() {
    return "Eviscerate the Enervated";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class eviscerate_the_enervated_red extends Card {
  function __construct($controller) {
    $this->cardID = "eviscerate_the_enervated_red";
    $this->controller = $controller;
    $this->baseCard = new eviscerate_the_enervated($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 5;
  }
}

class eviscerate_the_enervated_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "eviscerate_the_enervated_yellow";
    $this->controller = $controller;
    $this->baseCard = new eviscerate_the_enervated($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 4;
  }
}

class eviscerate_the_enervated_blue extends Card {
  function __construct($controller) {
    $this->cardID = "eviscerate_the_enervated_blue";
    $this->controller = $controller;
    $this->baseCard = new eviscerate_the_enervated($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 3;
  }
}

class act_on_intel extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $obj = MZIndexToObject($this->controller, $target);
    if (ClassContains($obj->ID(), "ASSASSIN", $this->controller))
      AddEffectToAttack($this->controller, $this->cardID, $target, true);
    return "";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $targets = GetTargetsAssassinAA($this->controller, contract:true);
    SetTargetsChoices($this->controller, $this->cardID, $targets);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $targets = GetTargetsAssassinAA($this->controller, contract:true);
    return $targets == "";
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    return HeroHitTrigger($this->controller, $this->cardID, $check, true);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AR";
  }
}

class act_on_intel_whereabouts_red extends Card {
  // Target Assassin attack action card with contract gets +1{p} and "When this hits a hero, they choose a card in their hand. Banish it."
  function __construct($controller) {
    $this->cardID = "act_on_intel_whereabouts_red";
    $this->controller = $controller;
    $this->baseCard = new act_on_intel($this->cardID, $this->controller);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    global $defPlayer;
    Await($defPlayer, "MZRemoveAndBanish", banisher:$this->controller, prepend:true);
    $context = "Choose a card to be banished";
    Await($defPlayer, "ChooseMultiZone", context:$context, prepend:true);
    Await($defPlayer, "MultiZoneIndices", search:"MYHAND", prepend:true, subsequent:0);
  }

  function SpecialName() {
    return "Act on Intel: Whereabouts";
  }

  function SpecialPitch() {
    return 1;
  }
}

class act_on_intel_plans_yellow extends Card {
  // Target Assassin attack action card with contract gets +1{p} and "When this hits a hero, they choose a card in their hand. Banish it."
  function __construct($controller) {
    $this->cardID = "act_on_intel_plans_yellow";
    $this->controller = $controller;
    $this->baseCard = new act_on_intel($this->cardID, $this->controller);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    global $defPlayer;
    Await($defPlayer, "MZRemoveAndBanish", banisher:$this->controller, prepend:true);
    $context = "Choose a card to be banished";
    Await($defPlayer, "ChooseMultiZone", context:$context, prepend:true);
    Await($defPlayer, "MultiZoneIndices", search:"MYARS", prepend:true, subsequent:0);
  }

  function SpecialName() {
    return "Act on Intel: Plans";
  }

  function SpecialPitch() {
    return 2;
  }
}

class act_on_intel_haunts_blue extends Card {
  // Target Assassin attack action card with contract gets +1{p} and "When this hits a hero, they choose a card in their hand. Banish it."
  function __construct($controller) {
    $this->cardID = "act_on_intel_haunts_blue";
    $this->controller = $controller;
    $this->baseCard = new act_on_intel($this->cardID, $this->controller);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    global $defPlayer;
    $deck = new Deck($defPlayer);
    $deck->BanishTop(banisher:$this->controller);
  }

  function SpecialName() {
    return "Act on Intel: Haunts";
  }

  function SpecialPitch() {
    return 3;
  }
}

class wound_poke extends BaseCard {
  // If you've hit with a dagger this combat chain, this gets "When this hits a hero, create a Frailty, Inertia, or Bloodrot Pox token under their control."
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (NumDaggerHits() == 0) return false;
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class wound_extraction_red extends Card {
  function __construct($controller) {
    $this->cardID = "wound_extraction_red";
    $this->controller = $controller;
    $this->baseCard = new wound_poke($this->cardID, $this->controller);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    DeathTouch($this->controller, $this->cardID);
  }

  function SpecialName() {
    return "Wound Extraction";
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 5;
  }
}

class wound_excavation_red extends Card {
  function __construct($controller) {
    $this->cardID = "wound_excavation_red";
    $this->controller = $controller;
    $this->baseCard = new wound_poke($this->cardID, $this->controller);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    LoseHealth(1, $defPlayer);
  }

  function SpecialName() {
    return "Wound Excavation";
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 5;
  }
}

class wound_excision_red extends Card {
  function __construct($controller) {
    $this->cardID = "wound_excision_red";
    $this->controller = $controller;
    $this->baseCard = new wound_poke($this->cardID, $this->controller);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    $Deck = new Deck($defPlayer);
    $Deck->BanishTop(banisher:$this->controller);
    $Deck->BanishTop(banisher:$this->controller);
  }

  function SpecialName() {
    return "Wound Excision";
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 5;
  }
}

class escaped_subject extends BaseCard {
  // If the defending hero is infected, this gets go again. (A hero is infected if they control a disease.)
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function DoesAttackHaveGoAgain() {
    global $defPlayer;
    return IsInfected($defPlayer);
  }

  function SpecialName() {
    return "Escaped Subject";
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class escaped_subject_red extends Card {
  function __construct($controller) {
    $this->cardID = "escaped_subject_red";
    $this->controller = $controller;
    $this->baseCard = new escaped_subject($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 4;
  }
}

class escaped_subject_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "escaped_subject_yellow";
    $this->controller = $controller;
    $this->baseCard = new escaped_subject($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 3;
  }
}

class escaped_subject_blue extends Card {
  function __construct($controller) {
    $this->cardID = "escaped_subject_blue";
    $this->controller = $controller;
    $this->baseCard = new escaped_subject($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 2;
  }
}

class viral_sampling extends BaseCard {
  // Stealth
  // Contract - You are contracted to hit an infected opposing hero. Whenever you complete this contract, create a Silver token. (A hero is infected if they control a disease.)

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ContractType($chosenName = '') {
    return "INFECTEDHIT";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller);
  }

  function SpecialName() {
    return "Viral Sampling";
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }

  function HasStealth() {
    return true;
  }
}

class viral_sampling_red extends Card {
  function __construct($controller) {
    $this->cardID = "viral_sampling_red";
    $this->controller = $controller;
    $this->baseCard = new viral_sampling($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 3;
  }
}

class viral_sampling_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "viral_sampling_yellow";
    $this->controller = $controller;
    $this->baseCard = new viral_sampling($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 2;
  }
}

class viral_sampling_blue extends Card {
  function __construct($controller) {
    $this->cardID = "viral_sampling_blue";
    $this->controller = $controller;
    $this->baseCard = new viral_sampling($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 1;
  }
}

class sneaking_disease extends BaseCard {
  // If you've played or activated an attack reaction this chain link, this gets "When this hits a hero, create a {} token under their control."
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (NumAttackReactionsPlayed() == 0) return false;
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $disease = match($this->cardID) {
      "sneaking_rot_red" => "bloodrot_pox",
      "sneaking_exhaustion_red" => "inertia",
      "sneaking_weakness_red" => "frailty",
    };
    PlayAura($disease, $otherPlayer, effectSource:$this->cardID, effectController:$this->controller);
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class sneaking_rot_red extends Card {
  function __construct($controller) {
    $this->cardID = "sneaking_rot_red";
    $this->controller = $controller;
    $this->baseCard = new sneaking_disease($this->cardID, $this->controller);
  }
  
  function SpecialName() {
    return "Sneaking Rot";
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 6;
  }
}

class sneaking_exhaustion_red extends Card {
  function __construct($controller) {
    $this->cardID = "sneaking_exhaustion_red";
    $this->controller = $controller;
    $this->baseCard = new sneaking_disease($this->cardID, $this->controller);
  }

  function SpecialName() {
    return "Sneaking Exhaustion";
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 6;
  }
}

class sneaking_weakness_red extends Card {
  function __construct($controller) {
    $this->cardID = "sneaking_weakness_red";
    $this->controller = $controller;
    $this->baseCard = new sneaking_disease($this->cardID, $this->controller);
  }

  function SpecialName() {
    return "Sneaking Weakness";
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 6;
  }
}

class scouting_cloak extends Card {
  // While this is in your graveyard, at the start of your turn, you may destroy 2 Silver you control. If you do, equip this.
  // Attack Reaction - Destroy this: Until end of turn, whenever you complete a contract, gain 1{h}.
  function __construct($controller) {
    $this->cardID = "scouting_cloak";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function DiscardStartTurnTrigger($index) {
    SilverBuyback($this->controller, $index);
  }

  function AbilityType($index = -1, $from = '-') {
    return "AR";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function EffectContractCompleteEffect($index) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    GainHealth(1, $this->controller);
  }

  function SpecialName() {
    return "Scouting Cloak";
  }

  function SpecialBlock() {
    return 0;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Chest";
  }
}

class plaguewardens_wraps extends Card {
  // While this is in your graveyard, at the start of your turn, you may destroy 2 Silver you control. If you do, equip this.
  // Attack Reaction - Destroy this: Target Assassin attack action card gets "When this hits a hero, give them a disease you control."
  function __construct($controller) {
    $this->cardID = "plaguewardens_wraps";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $obj = MZIndexToObject($this->controller, $target);
    if (ClassContains($obj->ID(), "ASSASSIN", $this->controller))
      AddEffectToAttack($this->controller, $this->cardID, $target, true);
    return "";
  }

  function DiscardStartTurnTrigger($index) {
    SilverBuyback($this->controller, $index);
  }

  function AbilityType($index = -1, $from = '-') {
    return "AR";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
    $targets = GetTargetsAssassinAA($this->controller);
    SetTargetsChoices($this->controller, $this->cardID, $targets);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $targets = GetTargetsAssassinAA($this->controller);
    return $targets == "";
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    return HeroHitTrigger($this->controller, $this->cardID, $check, true, $target);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    if ($target != $this->controller)
      GiveDisease($this->controller);
  }

  function SpecialName() {
    return "Plaguewarden's Wraps";
  }

  function SpecialBlock() {
    return 0;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Arms";
  }
}

class step_overs extends Card {
  // While this is in your graveyard, at the start of your turn, you may destroy 2 Silver you control. If you do, equip this.
  // Attack Reaction - Destroy this: Target Assassin attack action card gets go again. Activate this only if the defending hero has played a defense reaction this chain link.
  function __construct($controller) {
    $this->cardID = "step_overs";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $obj = MZIndexToObject($this->controller, $target);
    if (ClassContains($obj->ID(), "ASSASSIN", $this->controller))
      AddEffectToAttack($this->controller, $this->cardID, $target, true);
    return "";
  }

  function DiscardStartTurnTrigger($index) {
    SilverBuyback($this->controller, $index);
  }

  function AbilityType($index = -1, $from = '-') {
    return "AR";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
    $targets = GetTargetsAssassinAA($this->controller);
    SetTargetsChoices($this->controller, $this->cardID, $targets);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CCS_DefenseReactionsPlayed;
    return GetCombatChainState($CCS_DefenseReactionsPlayed) == 0;
  }

  function SpecialName() {
    return "Step Overs";
  }

  function SpecialBlock() {
    return 0;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Legs";
  }
}

class bounty_brim extends Card {
  // Action - Destroy this: Until end of turn, whenever you complete a contract, create a Silver token. Activate this only if you are Arakni. Go again
  // Blade Break (When the combat chain closes, if this defended, destroy it.)
  function __construct($controller) {
    $this->cardID = "bounty_brim";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function EffectContractCompleteEffect($index) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PutItemIntoPlayForPlayer("silver", $this->controller, effectSource:$this->cardID);
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $Hero = new CharacterCard(0, $this->controller);
    if (!str_contains(NameOverride($Hero->CardID(), $this->controller), "Arakni")) return true;
    return false;
  }

  function AbilityHasGoAgain($from) {
    return true;
  }

  function SpecialName() {
    return "Bounty Brim";
  }

  function SpecialBlock() {
    return 1;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Head";
  }
}

class stimtek_vest extends Card {
  // When you complete a contract, if you are Uzuri, you may destroy this. If you do, gain {r}{r}.
  // Blade Break (When the combat chain closes, if this defended, destroy it.)
  function __construct($controller) {
    $this->cardID = "stimtek_vest";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function DefaultActiveState() {
    return 1;
  }

  function ContractCompleteEffect($index) {
    $Hero = new CharacterCard(0, $this->controller);
    $CharacterCard = new CharacterCard($index, $this->controller);
    if (str_contains(NameOverride($Hero->CardID(), $this->controller), "Uzuri") && $CharacterCard->IsActive())
      AddLayer("TRIGGER", $this->controller, $this->cardID, $CharacterCard->UniqueID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Await($this->controller, "YesNo", message:"destroy_to_gain_resources", context:"Destroy this to gain 2 resources?", subsequent:0);
    Await($this->controller, $this->cardID, uid:$target, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $uid = $dqVars["uid"] ?? "-";
    $Character = new PlayerCharacter($this->controller);
    $Equip = $Character->FindCardUID($uid);
    if ($Equip->Index() != -1) {
      GainResources(2, $this->controller);
      $Equip->Destroy();
    }
  }

  function SpecialName() {
    return "Stimtek Vest";
  }

  function SpecialBlock() {
    return 1;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Chest";
  }
}

class murder_sprees extends Card {
  // Action - Destroy this: The next time you complete a contract this turn, gain an action point. Go again
  // Blade Break (When the combat chain closes, if this defended, destroy it.)
  function __construct($controller) {
    $this->cardID = "murder_sprees";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function EffectContractCompleteEffect($index) {
    $Effect = new CurrentEffect($index);
    $Effect->Remove();
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    GainActionPoints(1, $this->controller);
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function AbilityHasGoAgain($from) {
    return true;
  }

  function SpecialName() {
    return "Murder Sprees";
  }

  function SpecialBlock() {
    return 1;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Legs";
  }
}

class kill_confirmed_red extends Card {
  // When this attacks, name a card, then the defending hero reveals the top card of their deck. If it's the named card, banish it and create a Silver token.
  function __construct($controller) {
    $this->cardID = "kill_confirmed_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    AddDecisionQueue("INPUTCARDNAME", $this->controller, "-");
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $dqVars, $defPlayer;
    $choice = $dqVars["LASTRESULT"] ?? "-";
    if ($choice != "-") {
      $choiceName = GamestateUnsanitize($choice);
      WriteLog("<b>📣$choiceName </b> is being hunted!");
      $deck = new Deck($defPlayer);
      if ($deck->Reveal()) {
        $topCard = $deck->Top();
        if (NameOverride($topCard, $defPlayer) == $choiceName) {
          $deck->BanishTop(banisher:$this->controller);
          PutItemIntoPlayForPlayer("silver", $this->controller, effectSource:$this->cardID);
        }
      }
    }
  }

  function SpecialName() {
    return "Kill Confirmed";
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialPower() {
    return 6;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class lab_rat extends BaseCard {
  // If the defending hero is infected, this gets +1{p}. (A hero is infected if they control a disease.)
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    global $defPlayer;
    return IsInfected($defPlayer) ? 1 : 0;
  }

  function SpecialName() {
    return "Lab Rat";
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }
}

class lab_rat_red extends Card {
  function __construct($controller) {
    $this->cardID = "lab_rat_red";
    $this->controller = $controller;
    $this->baseCard = new lab_rat($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 6;
  }
}

class lab_rat_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "lab_rat_yellow";
    $this->controller = $controller;
    $this->baseCard = new lab_rat($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 5;
  }
}

class lab_rat_blue extends Card {
  function __construct($controller) {
    $this->cardID = "lab_rat_blue";
    $this->controller = $controller;
    $this->baseCard = new lab_rat($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 4;
  }
}

class viral_accelerant_blue extends Card {
  // For each disease token you control with a different name, create a token copy of it under the defending hero's control.
  function __construct($controller) {
    $this->cardID = "viral_accelerant_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $Auras = new Auras($this->controller);
    $foundNames = [];
    $otherPlayer = OtherPlayer($this->controller);
    for ($i = 0; $i < $Auras->NumAuras(); ++$i) {
      $AuraCard = $Auras->Card($i, true);
      $name = NameOverride($AuraCard->CardID(), $this->controller);
      if ($name != "" && !in_array($name, $foundNames)) {
        $foundNames[] = $name;
        PlayAura($AuraCard->CardID(), $otherPlayer, isToken:true, effectSource:$this->cardID, effectController:$this->controller);
      }
    }
    return "";
  }

  function SpecialName() {
    return "Viral Accelerant";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AR";
  }
}

class just_my_type_red extends Card {
  // When this defends, name a card, then the attacking hero reveals the top card of their deck. If it's the named card, banish it and create a Silver token.
  function __construct($controller) {
    $this->cardID = "just_my_type_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function OnDefenseReactionResolveEffects($from, $blockedFromHand) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddDecisionQueue("INPUTCARDNAME", $this->controller, "-");
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $dqVars, $mainPlayer;
    $choice = $dqVars["LASTRESULT"] ?? "-";
    if ($choice != "-") {
      $choiceName = GamestateUnsanitize($choice);
      WriteLog("<b>📣$choiceName </b> is being hunted!");
      $deck = new Deck($mainPlayer);
      if ($deck->Reveal()) {
        $topCard = $deck->Top();
        if (NameOverride($topCard, $mainPlayer) == $choiceName) {
          $deck->BanishTop(banisher:$this->controller);
          PutItemIntoPlayForPlayer("silver", $this->controller, effectSource:$this->cardID);
        }
      }
    }
  }

  function SpecialName() {
    return "Just My Type";
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "DR";
  }
}

class professional_rivalry_blue extends Card {
  // Stealth
  // Contract - You are contracted to hit an Assassin hero. Whenever you complete this contract, create a Silver token.
  function __construct($controller) {
    $this->cardID = "professional_rivalry_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ContractType($chosenName = '') {
    return "HITASSASSIN";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller, effectSource:$this->cardID);
  }

  function SpecialName() {
    return "Professional Rivalry";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialPower() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "AA";
  }

  function HasStealth() {
    return true;
  }
}

class puredew_panacea extends Card {
  // Action - Destroy this: Cure a disease you control. If you do, gain 1{h}. Go again
  // (Destroy the disease.)
  function __construct($controller) {
    $this->cardID = "puredew_panacea";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    CureThem($this->controller, "self");
    Await($this->controller, $this->cardID, final:true);
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function SpecificLogic() {
    GainHealth(1, $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function AbilityHasGoAgain($from) {
    return true;
  }

  function SpecialName() {
    return "Puredew Panacea";
  }

  function SpecialBlock() {
    return -2;
  }

  function SpecialClass() {
    return "GENERIC";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Off-Hand";
  }
}

class needlebane extends BaseCard {
  // When this defends a dagger, this gets +1{d}.
  // Blade Break (When the combat chain closes, if this defended, destroy it.)
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    global $CombatChain;
    $attackID = $CombatChain->AttackCard()->ID();
    $blockCard = $CombatChain->Card($i);
    if (SubtypeContains($attackID, "Dagger"))
      AddLayer("TRIGGER", $this->controller, $this->cardID, $blockCard->OriginUniqueID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID: $target);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectBlockModifier($index, $from, $effectInd) {
    $Effect = new CurrentEffect($effectInd);
    $blockCard = new ChainCard($index);
    return $Effect->AppliestoUniqueID() == $blockCard->OriginUniqueID() ? 1 : 0;
  }

  function SpecialBlock() {
    return 1;
  }

  function SpecialClass() {
    return "GENERIC";
  }

  function SpecialType() {
    return "E";
  }
}

class needlebane_cowl extends Card {
  function __construct($controller) {
    $this->cardID = "needlebane_cowl";
    $this->controller = $controller;
    $this->baseCard = new needlebane($this->cardID, $this->controller);
  }

  function SpecialName() {
    return "Needlebane Cowl";
  }

  function SpecialSubType() {
    return "Head";
  }
}

class needlebane_coat extends Card {
  function __construct($controller) {
    $this->cardID = "needlebane_coat";
    $this->controller = $controller;
    $this->baseCard = new needlebane($this->cardID, $this->controller);
  }

  function SpecialName() {
    return "Needlebane Coat";
  }

  function SpecialSubType() {
    return "Chest";
  }
}

class needlebane_gauntlets extends Card {
  function __construct($controller) {
    $this->cardID = "needlebane_gauntlets";
    $this->controller = $controller;
    $this->baseCard = new needlebane($this->cardID, $this->controller);
  }

  function SpecialName() {
    return "Needlebane Gauntlets";
  }

  function SpecialSubType() {
    return "Arms";
  }
}

class needlebane_steppers extends Card {
  function __construct($controller) {
    $this->cardID = "needlebane_steppers";
    $this->controller = $controller;
    $this->baseCard = new needlebane($this->cardID, $this->controller);
  }

  function SpecialName() {
    return "Needlebane Steppers";
  }

  function SpecialSubType() {
    return "Legs";
  }
}

class banger_blue extends Card {
  public $archetype;
  // If this is defended, it gets +6{p}.
  // Instant - Discard this: Create a Silver token.
  function __construct($controller) {
    $this->cardID = "banger_blue";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PutItemIntoPlayForPlayer("silver", $this->controller, effectSource:$this->cardID);
  }

  function CardCost($from = '-', $base=false) {
    if ($base) return 2;
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 0;
    return 2;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    global $CombatChain;
    return $CombatChain->NumCardsActiveLink() > 1 ? 6 : 0;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-", $allNames = false) {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    return $this->archetype->AddPrePitchDecisionQueue($from, $index);
  }

  function SpecialName() {
    return "Banger!";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "GENERIC";
  }

  function SpecialType() {
    return "AA";
  }
}

class self_determination_blue extends Card {
  public $archetype;
  // Instant - Discard this: Until end of turn, opponents can't create tokens under your control.
  function __construct($controller) {
    $this->cardID = "self_determination_blue";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CardCost($from = '-', $base=false) {
    if ($base) return 2;
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 0;
    return 2;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-", $allNames = false) {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    return $this->archetype->AddPrePitchDecisionQueue($from, $index);
  }

  function SpecialName() {
    return "Self-determination";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 4;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "GENERIC";
  }

  function SpecialType() {
    return "AA";
  }
}
