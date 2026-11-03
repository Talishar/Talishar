<?php

class humour_plunge extends Card {
  function __construct($controller) {
    $this->cardID = "humour_plunge";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "AA";
  }

  function AbilityCost() {
    return 2;
  }

  function HasGoAgain($from) {
    return true;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->TapForCost();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    return $CharacterCard->Tapped();
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    global $defPlayer;
    return IsHeroAttackTarget() && IsInfected($defPlayer) ? 1 : 0;
  }
}

class viral_diffusion_red extends Card {
  function __construct($controller) {
    $this->cardID = "viral_diffusion_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function OnDefenseReactionResolveEffects($from, $blockedFromHand) {
    global $combatChain;
    $index = count($combatChain) - CombatChainPieces();
    AddLayer("TRIGGER", $this->controller, $this->cardID, target:$index);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $mainPlayer, $Diseases;
    foreach($Diseases as $disease)
			PlayAura($disease, $mainPlayer, effectSource:$this->cardID, effectAgent:$this->controller);
  }
}

class pick_your_poison_yellow extends Card {
  // Each opposing hero chooses Frailty, Inertia, or Bloodrot Pox. Create the 2 tokens they didn't choose under their control.
  // Go again
  function __construct($controller) {
    $this->cardID = "pick_your_poison_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $Diseases;
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $diseaseChoices = [];
    foreach ($Diseases as $disease)
      $diseaseChoices[] = "CARDID-$disease";
    $diseaseChoices = implode(",", $diseaseChoices);
    Await($otherPlayer, "ChooseMultiZone", indices:$diseaseChoices, context:"Choose a disease to NOT get", subsequent:0);
    Await($this->controller, $this->cardID, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars, $Diseases;
    $choice = $dqVars["MZIndex"] ?? "-";
    $chosenDisease = explode("-", $choice)[1] ?? "-";
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    if ($chosenDisease != "-") {
      foreach($Diseases as $disease) {
        if ($disease != $chosenDisease)
          PlayAura($disease, $otherPlayer, effectController:$this->controller, effectSource:$this->cardID, effectAgent:$this->controller);
      }
    }
  }

  function SpecialName() {
    return "Pick Your Poison";
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialType() {
    return "A";
  }

  function HasGoAgain($from) {
    return true;
  }
}

class second_salvos extends Card {
  // While this is in your graveyard, at the start of your turn, you may destroy 2 Silver you control. If you do, equip this.
  // Instant - Destroy this: untap your hero.
  // Blade Break (When the combat chain closes, if this defended, destroy it.)
  function __construct($controller) {
    $this->cardID = "second_salvos";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $Hero = new CharacterCard(0, $this->controller);
    $Hero->Tap(0);
    return "";
  }

  function DiscardStartTurnTrigger($index) {
		SilverBuyback($this->controller, $index);
	}

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $boots = new CharacterCard($index, $this->controller);
    $boots->Destroy();
  }

  function DefaultActiveState() {
    return 1;
  }

  function SpecialName() {
    return "Second Salvos";
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

class wound_extraction extends BaseCard {
  // If you've hit with a dagger this combat chain, this gets "When this hits a hero, create a Frailty, Inertia, or Bloodrot Pox token under their control."
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (NumDaggerHits() == 0) return false;
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $Diseases, $defPlayer;
		$target = !is_numeric($target) ? $defPlayer : $target;
		$indices = [];
		foreach($Diseases as $disease)
			$indices[] = "CARDID-$disease";
		$indices = implode(",", $indices);
		$context = $target == $defPlayer ? "infect your opponent" : "infect yourself";
		Await($this->controller, "ChooseMultiZone", "cardID", indices:$indices, context:$context, subsequent:0);
		Await($target, "PlayAura", effectController:$this->controller, effectSource:$this->cardID, effectAgent:$this->controller, final:true);
  }

  function SpecialName() {
    return "Wound Extraction";
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
    $this->baseCard = new wound_extraction($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 5;
  }
}

// class wound_extraction_yellow extends Card {
//   function __construct($controller) {
//     $this->cardID = "wound_extraction_yellow";
//     $this->controller = $controller;
//     $this->baseCard = new wound_extraction($this->cardID, $this->controller);
//   }

//   function SpecialPitch() {
//     return 2;
//   }

//   function SpecialPower() {
//     return 4;
//   }
// }

// class wound_extraction_blue extends Card {
//   function __construct($controller) {
//     $this->cardID = "wound_extraction_blue";
//     $this->controller = $controller;
//     $this->baseCard = new wound_extraction($this->cardID, $this->controller);
//   }

//   function SpecialPitch() {
//     return 3;
//   }

//   function SpecialPower() {
//     return 3;
//   }
// }

class languid_pace extends BaseCard {
  // When this attacks or defends, create an Inertia token under each hero's control.
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
    return "";
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    foreach ([$this->controller, $otherPlayer] as $player)
      PlayAura("inertia", $player, effectSource:$this->cardID, effectAgent:$this->controller, effectController:$this->controller);
  }

  function SpecialName() {
    return "Languid Pace";
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

// class languid_pace_red extends Card {
//   function __construct($controller) {
//     $this->cardID = "languid_pace_red";
//     $this->controller = $controller;
//     $this->baseCard = new languid_pace($this->cardID, $this->controller);
//   }

//   function SpecialPitch() {
//     return 1;
//   }

//   function SpecialPower() {
//     return 5;
//   }
// }

// class languid_pace_yellow extends Card {
//   function __construct($controller) {
//     $this->cardID = "languid_pace_yellow";
//     $this->controller = $controller;
//     $this->baseCard = new languid_pace($this->cardID, $this->controller);
//   }

//   function SpecialPitch() {
//     return 2;
//   }

//   function SpecialPower() {
//     return 4;
//   }
// }

class languid_pace_blue extends Card {
  function __construct($controller) {
    $this->cardID = "languid_pace_blue";
    $this->controller = $controller;
    $this->baseCard = new languid_pace($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 3;
  }
}

class surgical_gloves extends Card {
  // Instant - Cure a disease an opponent controls: Target attack they control gets -1 power. Destroy this at the beginning of the end phase.
  // Arcane Barrier 1 (If you would be dealt arcane damage, you may pay 1 resource to prevent 1 of it.)
  function __construct($controller) {
    $this->cardID = "surgical_gloves";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CombatChain, $mainPlayer;
    if (str_contains($target, "LAYER")) {
      $index = explode("-", $target)[1];
      if ($index != -1) {
        $Layer = new Layer($index);
        AddCurrentTurnEffect($this->cardID, $mainPlayer, uniqueID:$Layer->UniqueID());
      }
    }
    elseif (str_contains($target, "COMBATCHAINLINK")) {
      AddCurrentTurnEffect($this->cardID, $mainPlayer, uniqueID:$CombatChain->AttackCard()->UniqueID());
    }
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return -1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    CureThem($this->controller, final:true);
    $attacks = TargetAttack($this->controller);
    SetTargetsChoices($this->controller, $this->cardID, implode(",", $attacks), "Target an attack to get -1 power");
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->FlagDestroy();
    $CharacterCard->AddUse(); // unlimited uses
    $CharacterCard->SetUsed(2);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    return !IsInfected($otherPlayer);
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function SpecialName() {
    return "Surgical Gloves";
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

  function ArcaneBarrier($index) {
    return 1;
  }
}

class sneaking_rot extends BaseCard {
  // If you've played or activated an attack reaction this chain link, this gets "When this hits a hero, create a Bloodrot Pox token under their control."
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (NumAttackReactionsPlayed() == 0) return false;
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    PlayAura("bloodrot_pox", $otherPlayer, effectSource:$this->cardID, effectController:$this->controller);
  }

  function SpecialName() {
    return "Sneaking Rot";
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
    $this->baseCard = new sneaking_rot($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 6;
  }
}

// class sneaking_rot_yellow extends Card {
//   function __construct($controller) {
//     $this->cardID = "sneaking_rot_yellow";
//     $this->controller = $controller;
//     $this->baseCard = new sneaking_rot($this->cardID, $this->controller);
//   }

//   function SpecialPitch() {
//     return 2;
//   }

//   function SpecialPower() {
//     return 5;
//   }
// }

// class sneaking_rot_blue extends Card {
//   function __construct($controller) {
//     $this->cardID = "sneaking_rot_blue";
//     $this->controller = $controller;
//     $this->baseCard = new sneaking_rot($this->cardID, $this->controller);
//   }

//   function SpecialPitch() {
//     return 3;
//   }

//   function SpecialPower() {
//     return 4;
//   }
// }

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

// class escaped_subject_yellow extends Card {
//   function __construct($controller) {
//     $this->cardID = "escaped_subject_yellow";
//     $this->controller = $controller;
//     $this->baseCard = new escaped_subject($this->cardID, $this->controller);
//   }

//   function SpecialPitch() {
//     return 2;
//   }

//   function SpecialPower() {
//     return 3;
//   }
// }

// class escaped_subject_blue extends Card {
//   function __construct($controller) {
//     $this->cardID = "escaped_subject_blue";
//     $this->controller = $controller;
//     $this->baseCard = new escaped_subject($this->cardID, $this->controller);
//   }

//   function SpecialPitch() {
//     return 3;
//   }

//   function SpecialPower() {
//     return 2;
//   }
// }

class isolation_vestment extends Card {
  // Action — Destroy this: Assassin attack action cards that target an infected hero cost you {r} less to play this turn. Go again (A hero is infected if they control a disease.)
  // Blade Break (When the combat chain closes, if this defended, destroy it.)
  function __construct($controller) {
    $this->cardID = "isolation_vestment";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityHasGoAgain($from) {
    return true;
  }

  function CurrentEffectCostModifier($cardID, $from, &$remove, $index, $playIndex) {
    global $defPlayer, $Stack;
    $Layer = $Stack->TopLayer($cardID);
    $attackTarget = CleanTargetToObject($this->controller, $Layer->Target());
    if ($attackTarget == "") return 0;
    if (!TypeContains($attackTarget->CardID(), "C")) return 0;
    if (TypeContains($cardID, "AA") && ClassContains($cardID, "ASSASSIN", $this->controller)) {
      return IsInfected($defPlayer) ? -1 : 0;
    }
    return 0;
  }

  function SpecialName() {
    return "Isolation Vestment";
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


class plague_mask extends Card {
  // Instant — Destroy this: Disease tokens can't be created under your control this turn.
  // Blade Break (When the combat chain closes, if this defended, destroy it.)
  function __construct($controller) {
    $this->cardID = "plague_mask";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function DefaultActiveState() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function SpecialName() {
    return "Plague Mask";
  }

  function SpecialBlock() {
    return 2;
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

// class eviscerate_the_enervated_yellow extends Card {
//   function __construct($controller) {
//     $this->cardID = "eviscerate_the_enervated_yellow";
//     $this->controller = $controller;
//     $this->baseCard = new eviscerate_the_enervated($this->cardID, $this->controller);
//   }

//   function SpecialPitch() {
//     return 2;
//   }

//   function SpecialPower() {
//     return 4;
//   }
// }

// class eviscerate_the_enervated_blue extends Card {
//   function __construct($controller) {
//     $this->cardID = "eviscerate_the_enervated_blue";
//     $this->controller = $controller;
//     $this->baseCard = new eviscerate_the_enervated($this->cardID, $this->controller);
//   }

//   function SpecialPitch() {
//     return 3;
//   }

//   function SpecialPower() {
//     return 3;
//   }
// }

class act_on_intel_whereabouts_red extends Card {
  // Target Assassin attack action card with contract gets +1{p} and "When this hits a hero, they choose a card in their hand. Banish it."
  function __construct($controller) {
    $this->cardID = "act_on_intel_whereabouts_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CombatChain;
    if (ContractType($CombatChain->AttackCard()->ID()) == "") return;
    AddEffectToCurrentAttack($this->cardID);
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CombatChain;
    return ContractType($CombatChain->AttackCard()->ID()) == "";
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    return HeroHitTrigger($this->controller, $this->cardID, $check, true);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    global $defPlayer;
    Await($defPlayer, "MZRemoveAndBanish", banisher:$this->controller, prepend:true);
    $context = "Choose a card to be banished (or pass and give the attack +4)";
    Await($defPlayer, "ChooseMultiZone", context:$context, prepend:true);
    Await($defPlayer, "MultiZoneIndices", search:"MYHAND", prepend:true, subsequent:0);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function SpecialName() {
    return "Act on Intel: Whereabouts";
  }

  function SpecialPitch() {
    return 1;
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

// class viral_sampling_red extends Card {
//   function __construct($controller) {
//     $this->cardID = "viral_sampling_red";
//     $this->controller = $controller;
//     $this->baseCard = new viral_sampling($this->cardID, $this->controller);
//   }

//   function SpecialPitch() {
//     return 1;
//   }

//   function SpecialPower() {
//     return 3;
//   }
// }

// class viral_sampling_yellow extends Card {
//   function __construct($controller) {
//     $this->cardID = "viral_sampling_yellow";
//     $this->controller = $controller;
//     $this->baseCard = new viral_sampling($this->cardID, $this->controller);
//   }

//   function SpecialPitch() {
//     return 2;
//   }

//   function SpecialPower() {
//     return 2;
//   }
// }

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
