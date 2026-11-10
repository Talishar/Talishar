<?php

class Auras extends ZoneObjects {

  // Properties
  private $boundIndex = null;

  // Constructor
  function __construct($player) {
    $this->cards = &GetAuras($player);
    $this->player = $player;
    $this->pieces = AuraPieces();
    $this->uidOffset = 6;
  }

  // a null object AuraCard (index -1) has all the methods, but they do nothing
  protected function NewCard($index) {
    return new AuraCard($index, $this->player);
  }

  // Methods
  function SearchAurasForModality($mode, $cardID="-") {
    $count = count($this->cards);
    if ($count == 0) return "";
    $auraPieces = AuraPieces();
    for ($i = 0; $i < $count; $i += $auraPieces) {
      if ($cardID != "-" && $this->cards[$i] != $cardID) continue;
      if (DelimStringContains($this->cards[$i + 10], $mode)) return new AuraCard($i, $this->player);
    }
    return "";
  }

  function NumAuras() {
    return $this->NumCards();
  }

  function BuildBoundIndex() {
    $this->boundIndex = [];
    $count = count($this->cards);
    $auraPieces = AuraPieces();
    for ($i = 0; $i < $count; $i += $auraPieces) {
      if (isset($this->cards[$i+14])) $this->boundIndex[$this->cards[$i+14]][] = $i;
    }
  }

  function FindBoundAuras($uid, $zone="MYALLY") {
    $ret = [];
    $count = count($this->cards);
    if ($count == 0) return [];
    if ($this->boundIndex !== null) {
      foreach ($this->boundIndex["$zone-$uid"] ?? [] as $i) $ret[] = new AuraCard($i, $this->player);
      return $ret;
    }
    $auraPieces = AuraPieces();
    for ($i = 0; $i < $count; $i += $auraPieces) {
      if (!isset($this->cards[$i + 14])) continue;
      if ($this->cards[$i+14] == "$zone-$uid") $ret[] = new AuraCard($i, $this->player);
    }
    return $ret;
  }
}

class AuraCard {
  // Properties
  private $pieces = [];
  private $index;
	private $controller;

  // Constructor
  function __construct($index, $player) {
    if ($index != -1) $this->pieces = &GetAuras($player);
    else $this->pieces = [];
    $this->index = $index;
		$this->controller = $player;
  }

  function Index() {
    return $this->index;
  }

  function Player() {
    return $this->controller;
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

  function SetUsed($status) { //alias for SetStatus
    $this->SetStatus($status);
  }

  function NumCounters() {
    return $this->pieces[$this->index+2] ?? 0;
  }

	function AddCounters($n=1) {
    if ($this->index == -1) return;
		if (isset($this->pieces[$this->index+2])) $this->pieces[$this->index+2] += $n;
		return $this->NumCounters();
	}

  function NumPowerCounters() {
    return $this->pieces[$this->index+3] ?? 0;
  }

  function AddPowerCounters($n=1) {
		if (isset($this->pieces[$this->index+3])) $this->pieces[$this->index+3] += $n;
		return $this->NumPowerCounters();
	}

  function IsToken() {
    return $this->pieces[$this->index+4] ?? 0;
  }

  function NumAbilityUses() {
    return $this->pieces[$this->index+5] ?? 0;
  }

  function AddUses($n=1) {
    if (isset($this->pieces[$this->index+5]))
      $this->pieces[$this->index+5] += $n;
  }

  function AddUse($n=1) { // alias for AddUses
    $this->AddUses($n);
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

	function Remove($skipTrigger = false, $skipClose = false, $mainPhase = true, $destinationUID = "-") { //don't call this for removing auras in the equipment
		if ($this->index != -1)
      return RemoveAura($this->controller, $this->index, $this->UniqueID(), "AURAS", $skipTrigger, $skipClose, $mainPhase, $destinationUID);
	}

	function Destroy($skipTrigger = false, $skipClose = false, $mainPhase = true, $destroyedBy = -1) { //don't call this for removing auras in the equipment
    if ($this->index != -1)
      return DestroyAura($this->controller, $this->index, $this->UniqueID(), "AURAS", $skipTrigger, $skipClose, $mainPhase, $destroyedBy);
	}

  function Banish($mod="-", $banishedBy="", $banisher="-") {
    if ($this->index != -1) {
      $isToken = $this->IsToken();
      $cardID = $this->CardID();
      $this->Remove();
      if ($isToken) return -1;
      else return BanishCardForPlayer($cardID, $this->controller, "PLAY", $mod, $banishedBy, $banisher);
    }
    return -1;
  }

  function Bounce() {
    $cardID = $this->CardID();
    $targetedPlayer = $this->Player();
    $notTargetedPlayer = OtherPlayer($targetedPlayer);
    $cardOwner = substr($this->From(), 0, 5) == "THEIR" ? $notTargetedPlayer : $targetedPlayer;
    if (!$this->IsToken() && $this->Index() != -1)
      AddPlayerHand($cardID, $cardOwner, "-");
    return $this->Remove();
  }

  function GetModalities() {
    return $this->pieces[$this->index+10] ?? "-";
  }

  function AddModality($mode) {
    if (isset($this->pieces[$this->index+10])) {
      if ($this->pieces[$this->index+10] == "-")
        $this->pieces[$this->index+10] = $mode;
      else $this->pieces[$this->index+10] .= ",$mode";
    }
  }

  function RemoveAllCounters() {
    if (isset($this->pieces[$this->index + 2])) $this->pieces[$this->index + 2] = 0;
    if (isset($this->pieces[$this->index + 3])) $this->pieces[$this->index + 3] = 0;
  }

  function IsFrozen() {
    return $this->pieces[$this->index+11] ?? 0;
  }

  function FreezeState($state=0) {
    if (isset($this->pieces[$this->index + 11]))
      $this->pieces[$this->index + 11] = $state;
  }

  function IsTapped() {
    return $this->pieces[$this->index+12] ?? 0;
  }

  function Tapped() {
    //alias
    return $this->IsTapped();
  }

  function Tap($tapState=1, $endStepUntap=false) {
    if ($this->index != -1)
      Tap("MYAURAS-" . $this->index, $this->controller, $tapState, $endStepUntap);
  }

  function HoloCounters() {
    return $this->pieces[$this->index + 13] ?? 0;
  }

  function AddHoloCounter($n=1) {
    if ($this->index != -1)
      $this->pieces[$this->index + 13] += $n;
  }

  function Bind($val) {
    if ($this->index != -1 && isset($this->pieces[$this->index + 14]))
      $this->pieces[$this->index + 14] = $val;
  }

  function BoundTo() {
    return $this->pieces[$this->index + 14] ?? "-";
  }
}