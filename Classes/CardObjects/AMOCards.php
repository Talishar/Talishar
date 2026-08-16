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

  function SpecialName() {
    return "Humour Plunge";
  }

  function SpecialType() {
    return "W";
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialPower() {
    return 1;
  }

  function HasPiercing() {
    return true;
  }

  function SpecialSubType() {
    return "Dagger";
  }

  function SpecialBlock() {
    return -2;
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
    global $mainPlayer;
    PlayAura("bloodrot_pox", $mainPlayer);
    PlayAura("frailty", $mainPlayer);
    PlayAura("inertia", $mainPlayer);
  }

  function SpecialName() {
    return "Viral Diffusion";
  }

  function SpecialClass() {
    return "ASSASSIN";
  }

  function SpecialCost() {
    return 3;
  }

  function SpecialType() {
    return "DR";
  }

  function SpecialSubType() {
    return "Trap";
  }

  function SpecialBlock() {
    return 4;
  }
}