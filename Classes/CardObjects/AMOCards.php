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
}

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
}

class languid_pace_blue extends Card {
  function __construct($controller) {
    $this->cardID = "languid_pace_blue";
    $this->controller = $controller;
    $this->baseCard = new languid_pace($this->cardID, $this->controller);
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
}

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
    if (!TypeContains($cardID, "AA") || !ClassContains($cardID, "ASSASSIN", $this->controller)) return 0;
    $Layer = $Stack->TopLayer($cardID);
    $attackTarget = CleanTargetToObject($this->controller, $Layer->Target());
    if ($attackTarget == "") return 0;
    if (!TypeContains($attackTarget->CardID(), "C")) return 0;
    return IsInfected($defPlayer) ? -1 : 0;
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
}
