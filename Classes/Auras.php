<?php

class Auras {

  // Properties
  private $auras = [];
  private $player = 0;

  // Constructor
  function __construct($player) {
    $this->auras = &GetAuras($player);
    $this->player = $player;
  }

  // Methods
  function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * AuraPieces();
    return new AuraCard($index, $this->player);
  }

  function FindCardUID($uid) {
    if (count($this->auras) == 0) return "";
    for ($i = 0; $i < count($this->auras); $i += AuraPieces()) {
      if ($this->auras[$i + 6] == $uid) return new AuraCard($i, $this->player);
    }
    return "";
  } 

  function NumAuras() {
    return intdiv(count($this->auras), AuraPieces());
  }
}

class AuraCard {
  // Properties
  private $pieces = [];
  private $index;
	private $controller;

  // Constructor
  function __construct($index, $player) {
    $this->pieces = &GetAuras($player);
    $this->index = $index;
		$this->controller = $player;
  }

  function Index() {
    return $this->index;
  }

  function CardID() {
    return $this->pieces[$this->index] ?? "-";
  }

  function Status() {
    return $this->pieces[$this->index+1] ?? 0;
  }

  function SetStatus($status) {
    if (isset($this->pieces[$this->index+1])) $this->pieces[$this->index+1] = $status;
  }

  function NumCounters() {
    return $this->pieces[$this->index+2] ?? 0;
  }

	function AddCounters($n=1) {
		if (isset($this->pieces[$this->index+2])) $this->pieces[$this->index+2] += $n;
		return $this->NumCounters();
	}

  function NumPowerCounters() {
    return $this->pieces[$this->index+3] ?? 0;
  }

  function IsToken() {
    return $this->pieces[$this->index+4] ?? 0;
  }

  function NumAbilityUses() {
    return $this->pieces[$this->index+5] ?? 0;
  }


  function UniqueID() {
    return $this->pieces[$this->index+6] ?? "-";
  }

  function MyGemStatus() { //(2 = always hold, 1 = hold, 0 = don't hold)
    return $this->pieces[$this->index+7] ?? 0;
  }

	function TheirGemStatus() {
    return $this->pieces[$this->index+8] ?? 0;
  }

  function ToggleGem($player=0) {
    $offset = ($player == $this->controller || $player == 0) ? 7 : 8;
    if (isset($this->pieces[$this->index+$offset])) {
      $state = $this->pieces[$this->index+$offset]  == "1" ? "0" : "1";
      $this->pieces[$this->index+$offset] = $state;
    }
  }

  function From() {
    return $this->pieces[$this->index+9] ?? "-";
  }

	function Remove($skipTrigger = false, $skipClose = false, $mainPhase = true) { //don't call this for removing auras in the equipment
		if (!$skipTrigger) AuraLeavesPlay($this->controller, $this->index, $this->UniqueID(), "AURAS", $mainPhase);
		$cardID = $this->CardID();
		$ClassState = new ClassState($this->controller);
		if (HasSuspense($cardID)) $ClassState->SetSuspensePoppedThisTurn($ClassState->SuspensePoppedThisTurn() + 1);
		if (!AfterDamage() && !$skipClose) {
			if (IsSpecificAuraAttacking($this->controller, $this->index)) {
				CloseCombatChain();
			}
		}
		for ($i = $this->index + AuraPieces() - 1; $i >= $this->index; --$i) {
			unset($this->pieces[$i]);
		}
		$this->pieces = array_values($this->pieces);
		return $cardID;
	}

	function Destroy($skipTrigger = false, $skipClose = false, $mainPhase = true) { //don't call this for removing auras in the equipment
		global $CombatChainState, $CombatChain, $mainPlayer;
		$location = "AURAS";
		AuraDestroyAbility($this->controller, $this->index, $this->IsToken(), $location);
		$from = $this->From();
		$isToken = $this->IsToken();
		$cardID = $this->Remove($skipTrigger, $skipClose, $mainPhase);
		AuraDestroyed($this->controller, $cardID, $isToken, $from);
		// Refreshes the aura index with the Unique ID in case of aura destruction
		if ($CombatChain->HasCurrentLink() && DelimStringContains(CardSubtype($CombatChain->AttackCard()->ID()), "Aura") && $this->controller == $mainPlayer) {
			$Auras = new Auras($this->controller);
			$updatedIndex = $Auras->FindCardUID($CombatChain->AttackCard()->OriginUniqueID());
			$CombatChainState->SetWeaponIndex($updatedIndex);
		}
		if ($cardID == "passing_mirage_blue") ReEvalCombatChain(); //check if phantasm should trigger
		return $cardID;
	}
}