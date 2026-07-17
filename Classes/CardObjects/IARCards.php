<?php
include_once  __DIR__ . "/HVYCards.php";
include_once  __DIR__ . "/SUPCards.php";

class DECAY extends card {
  function __construct($controller) {
    $this->cardID = "DECAY";
    $this->controller = $controller;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Allies = new Allies($this->controller);
    $DecayingAlly = $Allies->FindCardUID($target);
    $DecayingAlly->AddLifeCounters(-1);
    WriteLog(CardLink($DecayingAlly->CardID()) . " decays!");
  }
}

class malice_base extends BaseCard {
  private $targetSearch;
  function __construct($cardID, $controller="-") {
    $this->cardID = $cardID;
    $this->controller = $controller;
    $this->targetSearch = "MYDISCARD:subtype=Zombie";
  }
  function PlayAbility($target) {
    $uid = explode("-", $target)[1] ?? "-";
    $Discard = new Discard($this->controller);
    $targetCard = $Discard->FindCardUID($uid);
    AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID:$targetCard->UniqueID());
  }

  function IsPlayRestricted($index) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    if ($CharacterCard->Tapped()) return true;
    $search = SearchMultizone($this->controller, $this->targetSearch);
    if ($search == "") return true;
  }

  function PayAdditionalCosts($index) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Tap();
    $CharacterCard->SetUsed(2);
    $CharacterCard->AddUse(1);
    SetTargets($this->controller, $this->cardID, $this->targetSearch);
  }

  function ProcessTrigger($target) {
    $Discard = new Discard($this->controller);
    $TargetCard = $Discard->FindCardUID($target);
    if ($TargetCard->Index() != -1) {
      BanishCardForPlayer($TargetCard->CardID(), $this->controller, "DISCARD", "DOWN");
      $TargetCard->Remove();
    }
    BanishCardForPlayer("corrupted_corpse", $this->controller, "-", created:true);
  }
}

class malice extends Card {
  private $targetSearch;
  function __construct($controller) {
    $this->cardID = "malice";
    $this->controller = $controller;
    $this->baseCard = new malice_base($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($target);
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityCost() {
    return 1;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted($index);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts($index);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($target);
  }

  function AbilityHasGoAgain($from) {
    return true;
  }

  function SpecialType() {
    return "C";
  }

  function SpecialName() {
    return "Malice";
  }

  function SpecialClass() {
    return "NECROMANCER";
  }

  function SpecialTalent() {
    return "SHADOW";
  }
}

class malice_domina_of_the_dead extends Card {
  private $targetSearch;
  function __construct($controller) {
    $this->cardID = "malice_domina_of_the_dead";
    $this->controller = $controller;
    $this->baseCard = new malice_base($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($target);
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityCost() {
    return 1;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted($index);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts($index);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($target);
  }

  function AbilityHasGoAgain($from) {
    return true;
  }

  function SpecialType() {
    return "C";
  }

  function SpecialName() {
    return "Malice";
  }

  function SpecialClass() {
    return "NECROMANCER";
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function SpecialHealth() {
    return 40;
  }
}

class vox_necropolis extends Card {
  function __construct($controller) {
    $this->cardID = "vox_necropolis";
    $this->controller = $controller;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Allies = new Allies($this->controller);
    $AllyCard = $Allies->FindCardUID($uniqueID);
    if ($AllyCard->Index() != -1) {
      $index = $AllyCard->Index();
      $parameter = "PLAY|0|$index|$uniqueID|MYALLY";
			AddAttackQueue($AllyCard->CardID(), $this->controller, $target, $parameter, $uniqueID);
    }
  }

  function SpecialClass() {
    return "NECROMANCER";
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function SpecialType() {
    return "W";
  }

  function SpecialName() {
    return "Vox Necropolis";
  }
}

class restless_magister_red extends Card {
  function __construct($controller) {
    $this->cardID = "restless_magister_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
    AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose a card to banish", 1);
    AddDecisionQueue("CHOOSEHAND", $otherPlayer, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
    AddDecisionQueue("BANISHCARD", $otherPlayer, "HAND,-", 1);
  }

  function HasDecay() {
    return true;
  }

  function SpecialSubType() {
    return "Zombie,Ally";
  }

  function SpecialPower() {
    return 3;
  }

  function SpecialHealth() {
    return 3;
  }

  function SpecialType() {
    return "A";
  }

  function SpecialName() {
    return "Restless Magister";
  }

  function HasBloodDebt() {
    return true;
  }

  function SpecialClass() {
    return "NECROMANCER";
  }

  function SpecialTalent() {
    return "SHADOW";
  }
}

class corrupted_corpse extends Card {
  function __construct($controller) {
    $this->cardID = "corrupted_corpse";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "PLAY")
      AddCurrentTurnEffect($this->cardID, $this->controller, $from="PLAY");
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true; // doing it this way so it interacts correctly with hypothermia
  }

  function HasIncarnate() {
    return true;
  }

  function HasBloodDebt() {
    return true;
  }

  function SpecialName() {
    return "Corrupted Corpse";
  }

  function SpecialPitch() {
    return -1;
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialHealth() {
    return 3;
  }

  function SpecialPower() {
    return 3;
  }

  function SpecialSubType() {
    return "Zombie,Ally";
  }

  function SpecialType() {
    return "A";
  }

  function SpecialClass() {
    return "NECROMANCER";
  }

  function SpecialTalent() {
    return "SHADOW";
  }
}

class runic_reaving_red extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runic_reaving_red";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("runechant", $this->controller, effectSource:$this->cardID);
  }

  function CardCost($from = '-') {
    return 0;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = '-', $allNames = false) {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount, allNames:$allNames);
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

  function PayAdditionalCosts($from, $index = '-') {
    Usurp($this->cardID, $this->controller, $from);
  }

  function SpecialName() {
    return "Runic Reaving";
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 4;
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "RUNEBLADE";
  }
}

class runechant_of {
  public $cardID;
  public $controller;

  function __construct($cardID, $controller) {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }

  function DestroyEffect() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "DESTROYED");
  }

  function BeginningActionPhaseAbility($index) {
    $AuraCard = new AuraCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "DESTROY", $AuraCard->UniqueID());
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    $AuraCard = new AuraCard($i, $this->controller);
    if (!IsActivated($cardID, $from) && TypeContains($cardID, "AA", from:$from))
      AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "DESTROY", $AuraCard->UniqueID());
  }

  function UsurpedEffect() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "USURPED");
  }

  function ProcessTrigger($uniqueID, $additionalCosts) {
    switch ($additionalCosts) {
      case "DESTROY":
        $Auras = new Auras($this->controller);
        $AuraCard = $Auras->FindCardUID($uniqueID);
        $AuraCard->Destroy();
        break;
      case "DESTROYED":
        PlayAura("runechant", $this->controller);
        break;
      default:
        break;
    }
  }

  function IsRunechant() {
    return true;
  }
}

class runechant_of_greed_yellow extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runechant_of_greed_yellow";
    $this->controller = $controller;
    $this->archetype = new runechant_of($this->cardID, $this->controller);
  }

  function DestroyEffect() {
    $this->archetype->DestroyEffect();
  }

  function BeginningActionPhaseAbility($index) {
    $this->archetype->BeginningActionPhaseAbility($index);
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    $this->archetype->PermanentPlayAbility($cardID, $from, $i);
  }

  function UsurpedEffect() {
    $this->archetype->UsurpedEffect();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "USURPED")
      Draw($this->controller);
    else
      $this->archetype->ProcessTrigger($uniqueID, $additionalCosts);
  }

  function IsRunechant() {
    return $this->archetype->IsRunechant();
  }
}

class baalghor_omen_of_the_end extends Card {
  function __construct($controller) {
    $this->cardID = "baalghor_omen_of_the_end";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PermanentPitchCardAbility($pitchIndex) {
    $PitchCard = new PitchCard($pitchIndex, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, $PitchCard->UniqueID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Pitch = new PitchZone($this->controller);
    $PitchCard = $Pitch->FindCardUID($target);
    BanishCardForPlayer($PitchCard->CardID(), $this->controller, "PITCH");
    $PitchCard->Remove();
  }

  function PermanentPowerModifier(&$powerModifiers) {
    global $CombatChain;
    if ($CombatChain->AttackCard()->From() == "BANISH") {
      $powerModifiers[] = $this->cardID;
      $powerModifiers[] = 3;
      return 3;
    }
    return 0;
  }
}

class runechant_of_envy_yellow extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runechant_of_envy_yellow";
    $this->controller = $controller;
    $this->archetype = new runechant_of($this->cardID, $this->controller);
  }

  function DestroyEffect() {
    $this->archetype->DestroyEffect();
  }

  function BeginningActionPhaseAbility($index) {
    $this->archetype->BeginningActionPhaseAbility($index);
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    $this->archetype->PermanentPlayAbility($cardID, $from, $i);
  }

  function UsurpedEffect() {
    $this->archetype->UsurpedEffect();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "USURPED")
      GainHealth(1, $this->controller);
    else
      $this->archetype->ProcessTrigger($uniqueID, $additionalCosts);
  }

  function IsRunechant() {
    return $this->archetype->IsRunechant();
  }
}

class runechant_of_gluttony_yellow extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runechant_of_gluttony_yellow";
    $this->controller = $controller;
    $this->archetype = new runechant_of($this->cardID, $this->controller);
  }

  function DestroyEffect() {
    $this->archetype->DestroyEffect();
  }

  function BeginningActionPhaseAbility($index) {
    $this->archetype->BeginningActionPhaseAbility($index);
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    $this->archetype->PermanentPlayAbility($cardID, $from, $i);
  }

  function UsurpedEffect() {
    $this->archetype->UsurpedEffect();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "USURPED")
      GainResources($this->controller, 1);
    else
      $this->archetype->ProcessTrigger($uniqueID, $additionalCosts);
  }

  function IsRunechant() {
    return $this->archetype->IsRunechant();
  }
}

class runechant_of_lust_yellow extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runechant_of_lust_yellow";
    $this->controller = $controller;
    $this->archetype = new runechant_of($this->cardID, $this->controller);
  }

  function DestroyEffect() {
    $this->archetype->DestroyEffect();
  }

  function BeginningActionPhaseAbility($index) {
    $this->archetype->BeginningActionPhaseAbility($index);
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    $this->archetype->PermanentPlayAbility($cardID, $from, $i);
  }

  function UsurpedEffect() {
    $this->archetype->UsurpedEffect();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "USURPED")
      PlayAura("runechant", $this->controller);
    else
      $this->archetype->ProcessTrigger($uniqueID, $additionalCosts);
  }

  function IsRunechant() {
    return $this->archetype->IsRunechant();
  }
}

class runechant_of_pride_yellow extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runechant_of_pride_yellow";
    $this->controller = $controller;
    $this->archetype = new runechant_of($this->cardID, $this->controller);
  }

  function DestroyEffect() {
    $this->archetype->DestroyEffect();
  }

  function BeginningActionPhaseAbility($index) {
    $this->archetype->BeginningActionPhaseAbility($index);
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    $this->archetype->PermanentPlayAbility($cardID, $from, $i);
  }

  function UsurpedEffect() {
    $this->archetype->UsurpedEffect();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $this->controller);
    else
      $this->archetype->ProcessTrigger($uniqueID, $additionalCosts);
  }

  function IsRunechant() {
    return $this->archetype->IsRunechant();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }
}

class runechant_of_sloth_yellow extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runechant_of_sloth_yellow";
    $this->controller = $controller;
    $this->archetype = new runechant_of($this->cardID, $this->controller);
  }

  function DestroyEffect() {
    $this->archetype->DestroyEffect();
  }

  function BeginningActionPhaseAbility($index) {
    $this->archetype->BeginningActionPhaseAbility($index);
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    $this->archetype->PermanentPlayAbility($cardID, $from, $i);
  }

  function UsurpedEffect() {
    $this->archetype->UsurpedEffect();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $this->controller);
    else
      $this->archetype->ProcessTrigger($uniqueID, $additionalCosts);
  }

  function IsRunechant() {
    return $this->archetype->IsRunechant();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }
}

class runechant_of_wrath_yellow extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runechant_of_wrath_yellow";
    $this->controller = $controller;
    $this->archetype = new runechant_of($this->cardID, $this->controller);
  }

  function DestroyEffect() {
    $this->archetype->DestroyEffect();
  }

  function BeginningActionPhaseAbility($index) {
    $this->archetype->BeginningActionPhaseAbility($index);
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    $this->archetype->PermanentPlayAbility($cardID, $from, $i);
  }

  function UsurpedEffect() {
    $this->archetype->UsurpedEffect();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $this->controller);
    else
      $this->archetype->ProcessTrigger($uniqueID, $additionalCosts);
  }

  function IsRunechant() {
    return $this->archetype->IsRunechant();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function DoesEffectGrantOverpower() {
    return true;
  }
}

class gate_to_iarathael extends Card {
  private $targetSearch;
  function __construct($controller) {
    $this->cardID = "gate_to_iarathael";
    $this->controller = $controller;
    $this->targetSearch = "MYBANISH:bloodDebtOnly=true;type=A&MYBANISH:bloodDebtOnly=true;type=AA";
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $uid = explode("-", $target)[1] ?? "-";
    AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID:$uid);
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    SetTargets($this->controller, $this->cardID, $this->targetSearch);
    $AuraCard = new AuraCard($index, $this->controller);
    $AuraCard->Destroy();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $targets = SearchMultizone($this->controller, $this->targetSearch);
    return $targets == "";
  }
}

class figment_of_hope_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "figment_of_hope_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function Backside() {
    return "suraya_archangel_of_endless_hope";
  }

  function SpecialSubType() {
    return "Figment";
  }

  function SpecialCost() {
    return 4;
  }

  function SpecialType() {
    return "I";
  }
}

class suraya_archangel_of_endless_hope extends Card {
  function __construct($controller) {
    $this->cardID = "suraya_archangel_of_endless_hope";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function Frontside() {
    return "figment_of_hope_yellow";
  }

  function SpecialType() {
    return "-"; //this seems to be required for backside allies?
  }

  function SpecialSubType() {
    return "Angel,Ally"; // may be required even after fabcube update
  }

  function SpecialPower() {
    return 4;
  }

  function AbilityCost() {
    return 2;
  }

  function AbilityType($index = -1, $from = '-') {
    return "AA";
  }
}