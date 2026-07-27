<?php

class odds_on_favorite_blue extends Card {
  function __construct($controller) {
    $this->cardID = "odds_on_favorite_blue";
    $this->controller = $controller;
  }
  
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function OnAttackEffect($cardID, $i) {
		global $CombatChain;
    $Effect = new CurrentEffect($i);
    if (SubtypeContains($CombatChain->AttackCard()->ID(), "Sword")) {
      AddLayer("TRIGGER", $this->controller, $this->cardID);
      return true;
    }
    return false;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddOnWagerEffects();
    AddCurrentTurnEffect("$this->cardID-WAGER", $this->controller);
  }

	function WonWager($wonWager, $amount) {
		Await($wonWager, "MultiZoneIndices", "indices", search:"MYDECK", subsequent:0);
		Await($wonWager, "ChooseMultiZone", "MZIndex", context:"Choose a card to put on top");
		Await($wonWager, "MZRemove", "cardID");
		Await($wonWager, "ShuffleDeck");
		Await($wonWager, "AddTopDeck", from:"DECK", final:true);
	}

  function IsWagerEffect($index) {
    $Effect = new CurrentEffect($index);
    return str_contains($Effect->EffectID(), "WAGER");
  }
}

class prizeworn_pathfinders extends Card {
  function __construct($controller) {
    $this->cardID = "prizeworn_pathfinders";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function WinWagerTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $message = "if_you_want_to_repair_pathfinders";
    $context = "Choose if you want to pay a resource remove a counter from " . CardLink($this->cardID);
    Await($this->controller, "YesNo", message:$message, context:$context, subsequent:0);
    Await($this->controller, "PayResources", amount:1);
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    $Character = new PlayerCharacter($this->controller);
    $Equipment = $Character->FindCardID($this->cardID);
    if ($Equipment->NumDefenseCounters() < 0)
      $Equipment->AddDefCounters(1);
  }

  function SpecialName() {
    return "Prizeworn Pathfinders";
  }

  function SpecialBlock() {
    return 1;
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Legs";
  }

  function SpecialClass() {
    return "WARRIOR,GUARDIAN";
  }
}

class heads_up_red extends Card {
  function __construct($controller) {
    $this->cardID = "heads_up_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    return SubtypeContains($CombatChain->AttackCard()->ID(), "Sword");
  }

  function EffectPowerModifier($param, $attached = false) {
    return $param != "DOMINATE" ? 3 : 0;
  }

  function OnAttackEffect($cardID, $i) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $combatChainState, $CCS_WagersThisLink;
    if (intval($combatChainState[$CCS_WagersThisLink]) > 0)
      AddCurrentTurnEffect("$this->cardID-DOMINATE", $this->controller, from:"PLAY");
  }

  function DoesEffectGrantDominate($effectIndex) {
    $Effect = new CurrentEffect($effectIndex);
    $param = explode("-", $Effect->EffectID())[1] ?? "";
    return $param == "DOMINATE";
  }

  function SpecialName() {
    return "Heads Up";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialType() {
    return "A";
  }

  function SpecialClass() {
    return "WARRIOR";
  }

  function HasGoAgain($from) {
    return true;
  }
}

class visit_the_prize_room_blue extends Card {
  function __construct($controller) {
    $this->cardID = "visit_the_prize_room_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("vigor", $this->controller);
    PlayAura("courage", $this->controller);
    if (CountItemByName("Gold", $this->controller) > 0) {
      AddDecisionQueue("YESNO", $this->controller, "if_you_want_to_pay_a_" . CardLink("gold", "gold"), 1);
      AddDecisionQueue("NOPASS", $this->controller, "-", 1);
      QueueDestroyGold($this->controller);
      Await($this->controller, $this->cardID, final:true);
    }
    return "";
  }

  function SpecificLogic() {
    if (DelimStringContains(FindEmptyEquipmentSlots($this->controller), "Head")) {
      $inds = SearchInventoryForCard($this->controller, "prized_galea");
      if ($inds != "") {
        $ind = explode(",", $inds)[0];
        $Inventory = new Inventory($this->controller);
        EquipEquipment($this->controller, $Inventory->GetCard($ind));
        $Inventory->Remove($ind);
      }
    }
  }

  function SpecialName() {
    return "Visit the Prize Room";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialType() {
    return "A";
  }

  function HasGoAgain($from) {
    return true;
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialClass() {
    return "WARRIOR";
  }

  function SpecialBlock() {
    return 2;
  }
}