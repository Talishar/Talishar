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
    if ($DecayingAlly->Index() != -1) {
      $cardID = $DecayingAlly->CardID();
      $DecayingAlly->AddLifeCounters(-1);
      WriteLog(CardLink($cardID) . " decays!");
    }
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
}

class vox_necropolis extends Card {
  function __construct($controller) {
    $this->cardID = "vox_necropolis";
    $this->controller = $controller;
  }

  function PermanentAllyPlayAbility($allyIndex, $charIndex, $from) {
    $AllyCard = new AllyCard($allyIndex, $this->controller);
    if ($from != "GY" && $from != "BANISH" && $from != "THEIRBANISH") return;
    if (!SubtypeContains($AllyCard->CardID(), "Zombie")) return;
    AddDecisionQueue("GETATTACKQUEUETARGET", $this->controller, $AllyCard->CardID() . ",PLAY,1");
    Await($this->controller, "AQTargeting", "target", lastResultName:"target");
    Await($this->controller, "AddTrigger", uniqueID:$AllyCard->UniqueID(), cardID:"vox_necropolis", final:true);
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
    BanishFromHand($otherPlayer);
  }
}

class restless_quartermaster_red extends Card {
  function __construct($controller) {
    $this->cardID = "restless_quartermaster_red";
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
    BanishFromArsenal($otherPlayer, $this->cardID);
  }
}

class restless_cleric_red extends Card {
  function __construct($controller) {
    $this->cardID = "restless_cleric_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (GetResolvedAbilityType($this->cardID, $from, $this->controller) == "A" && $from == "PLAY")
      GainHealth(1, $this->controller);
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $from == "PLAY" ? "A" : "";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = '-', $allNames = false) {
    if (SearchLayersForPhase("RESOLUTIONSTEP") != -1) return "-";
    return "Gain_Life";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    if ($from != "PLAY") return false;
    $AllyCard = new AllyCard($index, $this->controller);
    return $AllyCard->Tapped();
  }

  function PayAdditionalCosts($from, $index = '-') {
    if ($from == "PLAY") {
      $AllyCard = new AllyCard($index, $this->controller);
      $AllyCard->TapForCost();
    }
  }

  function AbilityHasGoAgain($from) {
    return GetResolvedAbilityType($this->cardID, $from, $this->controller) == "A";
  }

  function GoesOnCombatChain($phase, $from) {
    return GetResolvedAbilityType($this->cardID, $from) == "AA";
  }

  function HasGoAgain($from) {
    return false;
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
}

class viserai_base extends BaseCard {

  function ProcessTrigger() {
    global $CS_NumRunechantsCreated, $CS_OriginalHero;
    $Deck = new Deck($this->controller);
    if (!$Deck->Empty()) {
      $Deck->BanishTop();
    }
    if (GetClassState($this->controller, $CS_NumRunechantsCreated) >= 3) {
      WriteLog("Viserai has usurped the Shadow Throne!");
      SetClassState($this->controller, $CS_OriginalHero, $this->cardID);
      $Hero = new CharacterCard(0, $this->controller);
      $Hero->Become("viserai_usurper");
    }
  }
}

class viserai_the_forsaken extends Card {
  function __construct($controller) {
    $this->cardID = "viserai_the_forsaken";
    $this->controller = $controller;
    $this->baseCard = new viserai_base($this->cardID, $this->controller);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class viserai_between_worlds extends Card {
  function __construct($controller) {
    $this->cardID = "viserai_between_worlds";
    $this->controller = $controller;
    $this->baseCard = new viserai_base($this->cardID, $this->controller);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class viserai_usurper extends Card {
  function __construct($controller) {
    $this->cardID = "viserai_usurper";
    $this->controller = $controller;
  }

  private
  function EndPhaseAbility() {
    global $CS_IARGatesMadeorUsed;
    if (GetClassState($this->controller, $CS_IARGatesMadeorUsed) >= 1) {
      $message = "if_you_want_to_forsake_your_throne";
      $context = "Choose if you want to forsake your throne";
      Await($this->controller, "YesNo", message: $message, context: $context, subsequent:0);
      Await($this->controller, $this->cardID, final:true);
    }
  }

  function DefenderPermanentEndPhaseAbility($index) {
    $this->EndPhaseAbility();
  }

  function PermanentEndPhaseAbility($index) {
    $this->EndPhaseAbility();
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function SpecificLogic() {
    global $CS_OriginalHero;
    $Hero = new CharacterCard(0, $this->controller);
    $Hero->Become(GetClassState($this->controller, $CS_OriginalHero));
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

class runic_reaving_yellow extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runic_reaving_yellow";
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
    return 2;
  }

  function SpecialPower() {
    return 3;
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "RUNEBLADE";
  }
}

class runic_reaving_blue extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runic_reaving_blue";
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
    return 3;
  }

  function SpecialPower() {
    return 2;
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
    global $mainPlayer;
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $mainPlayer);
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
    global $mainPlayer;
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $mainPlayer);
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
    global $mainPlayer;
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $mainPlayer);
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
  function __construct($controller) {
    $this->cardID = "gate_to_iarathael";
    $this->controller = $controller;
  }

  private
  function TargetSearch($remove=false) {
    $search = "MYBANISH:bloodDebtOnly=true;type=A&MYBANISH:bloodDebtOnly=true;type=AA";
    if (SearchCurrentTurnEffects("planar_chaos_red", $this->controller, $remove)) {
      $search .= "&THEIRBANISH:bloodDebtOnly=true;type=A&THEIRBANISH:bloodDebtOnly=true;type=AA";
    }
    return $search;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $uid = explode("-", $target)[1] ?? "-";
    $zone = explode("-", $target)[0];
    if ($zone == "THEIRBANISH")
      AddCurrentTurnEffect("$this->cardID-CHAOS", $this->controller, uniqueID:$uid);
    else
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
    SetTargets($this->controller, $this->cardID, $this->TargetSearch(true));
    $AuraCard = new AuraCard($index, $this->controller);
    $AuraCard->Destroy();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $targets = SearchMultizone($this->controller, $this->TargetSearch());
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

  function SpecialName() {
    return "Figment of Hope";
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialBlock() {
    return -2;
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

  function AbilityCost() {
    return 2;
  }

  function AbilityType($index = -1, $from = '-') {
    return "AA";
  }

  function AwakenAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    GainHealth(1, $this->controller);
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

  function SpecialName() {
    return "Suraya, Archangel of Endless Hope";
  }

  function SpecialHealth() {
    return 4;
  }

  function WardAmount($index) {
    return 4;
  }

  function HasWard() {
    return true;
  }
}

class soul_of_existence_purple extends Card {
  function __construct($controller) {
    $this->cardID = "soul_of_existence_purple";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PitchAbility($from) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    LoseHealth(1, $this->controller);
  }
}

class blood_harvest extends Card {
  function __construct($controller) {
    $this->cardID = "blood_harvest";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return "I,AA";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-", $allNames = false) {
    return GetEasyAbilityNames($this->cardID, $index, $from);
  }

  function GoesOnCombatChain($phase, $from) {
    global $layers;
    return ($phase == "B" && count($layers) == 0) || GetResolvedAbilityType($this->cardID, $from) == "AA";
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return ($from == "HAND");
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 0;
    return 3;
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    global $CS_NumActionsPlayed;
    $names = GetAbilityNames($this->cardID, $index, $from);
    $names = str_replace("-,", "", $names);
    if (SearchCurrentTurnEffects("red_in_the_ledger_red", $this->controller) && GetClassState($this->controller, $CS_NumActionsPlayed) >= 1) {
      AddDecisionQueue("SETABILITYTYPEABILITY", $this->controller, $this->cardID);
    } elseif ($names != "" && $from == "HAND") {
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose to play the ability or attack");
      AddDecisionQueue("BUTTONINPUT", $this->controller, $names);
      AddDecisionQueue("SETABILITYTYPE", $this->controller, $this->cardID);
    } else {
      AddDecisionQueue("SETABILITYTYPEATTACK", $this->controller, $this->cardID);
    }
    AddDecisionQueue("NOTEQUALPASS", $this->controller, "Ability");
    AddDecisionQueue("PASSPARAMETER", $this->controller, $this->cardID, 1);
    AddDecisionQueue("BANISHCARD", $this->controller, "HAND", 1);
    AddDecisionQueue("CONVERTLAYERTOABILITY", $this->controller, $this->cardID, 1);
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    GainResources($this->controller, 3);
  }
}

class sinspeaker_gloomblade_red extends Card {
  function __construct($controller) {
    $this->cardID = "sinspeaker_gloomblade_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "BANISH") {
      AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    }
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    MaySearchDeck($this->controller, "subtype=Aura;nameIncludes=Runechant", "MYAURAS", context:"Search your deck for a runechant to play");
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return true;
  }

  function PayAdditionalCosts($from, $index = '-') {
    Usurp($this->cardID, $this->controller, $from);
  }
}

class demonbound_gloomblade extends BaseCard {
  function PlayAbility($additionalCosts) {
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function EffectPowerModifier() {
    return 2;
  }

  function CombatEffectActive() {
    return true;
  }

  function PayAdditionalCosts($from) {
    Usurp($this->cardID, $this->controller, $from);
  }
}

class demonbound_gloomblade_red extends Card {
  function __construct($controller) {
    $this->cardID = "demonbound_gloomblade_red";
    $this->controller = $controller;
    $this->baseCard = new demonbound_gloomblade($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($additionalCosts);
    return "";
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts($from);
  }
}

class demonbound_gloomblade_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "demonbound_gloomblade_yellow";
    $this->controller = $controller;
    $this->baseCard = new demonbound_gloomblade($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($additionalCosts);
    return "";
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts($from);
  }
}

class demonbound_gloomblade_blue extends Card {
  function __construct($controller) {
    $this->cardID = "demonbound_gloomblade_blue";
    $this->controller = $controller;
    $this->baseCard = new demonbound_gloomblade($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($additionalCosts);
    return "";
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts($from);
  }
}

class corrupt_and_conquer_red extends Card {
  function __construct($controller) {
    $this->cardID = "corrupt_and_conquer_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "BANISH")
      AddCurrentTurnEffect($this->cardID, $this->controller); // makes dreacts unplayable
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    $Arsenal = new Arsenal($defPlayer);
    $Arsenal->BanishAll($this->controller);
  }
}

class open_the_gate_to_iarathael_red extends Card {
  function __construct($controller) {
    $this->cardID = "open_the_gate_to_iarathael_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return AnyHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    PlayAura("gate_to_iarathael", $this->controller);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("gate_to_iarathael", $this->controller);
  }

  function GetBanishedEffect($from, $banisher, $banishedBy) {
    if ($from == "HAND" || $from == "DECK")
      AddLayer("TRIGGER", $this->controller, $this->cardID); //hit effect to consolidate the trigger
  }
}

class shadowrealm_harrower_blue extends Card {
  function __construct($controller) {
    $this->cardID = "shadowrealm_harrower_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "BANISH")
      AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    return HeroHitTrigger($this->controller, $this->cardID, $check, true);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    global $combatChainState, $CCS_DamageDealt;
    GainHealth(GetCombatChainState($CCS_DamageDealt), $this->controller);
  }

}

class shadowrealm_harvester_red extends Card {
  function __construct($controller) {
    $this->cardID = "shadowrealm_harvester_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "BANISH")
      AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function DoesEffectGrantOverpower() {
    return true;
  }
}

class shadowrealm_reaper_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "shadowrealm_reaper_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "BANISH")
      AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }
}

class unbound_by_shadow extends BaseCard {
  function PlayAbility($from) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, $from, "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target) {
    if ($target == "BANISH")
      PlayAura("gate_to_iarathael", $this->controller);
  }
}

class unbound_by_shadow_red extends Card {
  function __construct($controller) {
    $this->cardID = "unbound_by_shadow_red";
    $this->controller = $controller;
    $this->baseCard = new unbound_by_shadow($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from);
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger($target);
  }
}

class pull_from_beyond extends BaseCard {
  function PlayAbility() {
    Opt($this->cardID, 2);
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic($color) {
    $deck = new Deck($this->controller);
    $banishedCard = $deck->BanishTop();
    if (ColorContains($banishedCard, $color, $this->controller))
      PlayAura("gate_to_iarathael", $this->controller);
  }
}

class pull_from_beyond_red extends Card {
  function __construct($controller) {
    $this->cardID = "pull_from_beyond_red";
    $this->controller = $controller;
    $this->baseCard = new pull_from_beyond($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic(1);
  }
}

class pull_from_beyond_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "pull_from_beyond_yellow";
    $this->controller = $controller;
    $this->baseCard = new pull_from_beyond($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic(2);
  }
}

class pull_from_beyond_blue extends Card {
  function __construct($controller) {
    $this->cardID = "pull_from_beyond_blue";
    $this->controller = $controller;
    $this->baseCard = new pull_from_beyond($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic(3);
  }
}

class blasmophet_the_insatiable_hunger extends Card {
  function __construct($controller) {
    $this->cardID = "blasmophet_the_insatiable_hunger";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  private
  function EndPhase($index) {
    // I'm assuming right now that there's no reason to resolve this after blood debt
    $Hand = new Hand($this->controller);
    if ($Hand->NumCards() != 0) {
      Await($this->controller, "MultiZoneIndices", "indices", search:"MYHAND", subsequent:0);
      Await($this->controller, "ChooseMultiZone", "MZIndex", context:"Banish a card from your hand (or pass)", may:true, subsequent:0);
      Await($this->controller, "MZBanish");
      Await($this->controller, "MZRemove", final:true);
    }
    Await($this->controller, $this->cardID, index:$index, subsequent:0, final:true);
  }

  function PermanentEndPhaseAbility($index) {
    $this->EndPhase($index);
  }

  function DefenderPermanentEndPhaseAbility($index) {
    $this->EndPhase($index);
  }

  function SpecificLogic() {
    global $dqVars, $CS_NumBloodDebtBanished;
    $index = $dqVars["index"];
    $AllyCard = new AllyCard($index, $this->controller);
    if (GetClassState($this->controller, $CS_NumBloodDebtBanished) == 0) {
      WriteLog(CardLink($this->cardID) . " is starving and has left to find food elsewhere");
      $AllyCard->Destroy();
    }
  }

  function StartTurnAbility($index) { // give the once per turn ability to play from banish
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function OppStartTurnAbility($index) { // give the once per turn ability to play from banish
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function EntersArenaAbility($index=-1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function IsUnique() {
    return true;
  }
}

class circlet_of_eternal_end extends Card {
  function __construct($controller) {
    $this->cardID = "circlet_of_eternal_end";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Await($this->controller, "MultiZoneIndices", "indices", search:"THEIRBANISH", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "choice", context:"Turn a card in the attacker's banish face down", may:true);
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $dqVars, $mainPlayer;
    $choice = $dqVars["choice"];
    $ind = explode("-", $choice)[1] ?? -1;
    if ($ind != -1) {
      $BanishCard = new BanishCard($mainPlayer, $ind);
      $BanishCard->SetModifier("DOWN");
    }
  }
}

class beckoning_hunger extends BaseCard {
  function PlayAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger() {
    $Deck = new Deck($this->controller);
    $Deck->BanishTop();
  }

  function AddOnHitTrigger($check) {
    return AnyHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect() {
    PlayAlly("blasmophet_the_insatiable_hunger", $this->controller);
  }
}

class beckoning_hunger_red extends Card {
  function __construct($controller) {
    $this->cardID = "beckoning_hunger_red";
    $this->controller = $controller;
    $this->baseCard = new beckoning_hunger($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class beckoning_hunger_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "beckoning_hunger_yellow";
    $this->controller = $controller;
    $this->baseCard = new beckoning_hunger($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class beckoning_hunger_blue extends Card {
  function __construct($controller) {
    $this->cardID = "beckoning_hunger_blue";
    $this->controller = $controller;
    $this->baseCard = new beckoning_hunger($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class seven_sin_nebula extends Card {
  function __construct($controller) {
    $this->cardID = "seven_sin_nebula";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "AA";
  }

  function AbilityCost() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $Weapon = new CharacterCard($index, $this->controller);
    $Weapon->TapForCost();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    PlayAura("runechant", $this->controller);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CS_NumPlayedFromBanish;
    if (GetClassState($this->controller, $CS_NumPlayedFromBanish) == 0) return true;
    $Weapon = new CharacterCard($index, $this->controller);
    if ($Weapon->Tapped()) return true;
    return false;
  }
}

class become_the_shadow_lord_blue extends Card {
  function __construct($controller) {
    $this->cardID = "become_the_shadow_lord_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $context = "Banish a card from your hand";
    $hand = new Hand($this->controller);
    if ($hand->NumCards() > 0) {
      Await($this->controller, "MultiZoneIndices", "indices", search:"MYHAND", subsequent:0);
      Await($this->controller, "CHOOSEMULTIZONE", "choice", context:$context);
      Await($this->controller, $this->cardID, final:true);
    }
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["choice"];
    $ind = explode("-", $choice)[1] ?? -1;
    if ($ind != -1) {
      $Hand = new Hand($this->controller);
      $cardID = $Hand->Remove($ind);
      WriteLog(CardLink($cardID) . " was sacrificed to " . CardLink($this->cardID) . "!");
      BanishCardForPlayer($cardID, $this->controller, "HAND");
      if (ClassContains($cardID, "RUNEBLADE", $this->controller))
        PlayAura("runechant", $this->controller, effectSource:$this->cardID);
      if (TalentContains($cardID, "SHADOW", $this->controller))
        PlayAura("gate_to_iarathael", $this->controller, effectSource:$this->cardID);
    }
  }
}

class bridge_of_damnation_blue extends Card {
  function __construct($controller) {
    $this->cardID = "bridge_of_damnation_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  private
  function Maintenence($index) {
    Await($this->controller, "MultiZoneIndices", "indices", search:"MYBANISH:subtype=Zombie", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "choice", may:1, context:"Move a zombie from banish to graveyard (or pass)");
    Await($this->controller, $this->cardID);
    AddDecisionQueue("ELSE", $this->controller, "-");
    Await($this->controller, $this->cardID, destroyIndex:$index);
    Await($this->controller, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $index = $dqVars["destroyIndex"] ?? -1;
    $choice = $dqVars["choice"] ?? "";
    if ($index == -1) {
      $banishInd = explode("-", $choice)[1] ?? "";
      if ($banishInd != "") {
        $BanishCard = new BanishCard($this->controller, $banishInd);
        AddGraveyard($BanishCard->ID(), $this->controller, "BANISH");
        $BanishCard->Remove();
      }
    }
    else {
      $AuraCard = new AuraCard($index, $this->controller);
      $AuraCard->Destroy();
    }
  }

  function StartTurnAbility($index) {
    $this->Maintenence($index);
    return false;
  }

  function OppStartTurnAbility($index) {
    $this->Maintenence($index);
    return false;
  }
}

class hex_gauntlet extends Card {
  function __construct($controller) {
    $this->cardID = "hex_gauntlet";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Await($this->controller, "MultiZoneIndices", "indices", search:"MYBANISH:bloodDebtOnly=1", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "choice", context:"Turn a card with blood debt facedown");
    Await($this->controller, $this->cardID, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["choice"] ?? "";
    $ind = explode("-", $choice)[1] ?? -1;
    if ($ind != -1) {
      $BanishCard = new BanishCard($this->controller, $ind);
      $BanishCard->SetModifier("DOWN");
    }
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function PayAdditionalCosts($from, $index = '-') {
    BanishCardForPlayer($this->cardID, $this->controller, "EQUIP");
  }
}

class appalling_bearers extends Card {
  function __construct($controller) {
    $this->cardID = "appalling_bearers";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount = false) {
    return FloatingPrevention($index, $damage, $amount, $remove, $preventable);
  }

  function CurrentTurnEffectUses() {
    return 2;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  private
  function GetInds() {
    $Hand = new Hand($this->controller);
    $inds = [];
    for($i = 0; $i < $Hand->NumCards(); ++$i) {
      if (SubtypeContains($Hand->Card($i, true), "Zombie"))
        $inds[] = $i;
    }
    return $inds;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return count($this->GetInds()) == 0;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $inds = $this->GetInds();
    if (count($inds) == 0) {
      WriteLog("No Zombie to discard, reverting gamestate", highlight:true);
      RevertGamestate();
      return;
    }
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
    AddDecisionQueue("PASSPARAMETER", $this->controller, implode(",", $inds));
    AddDecisionQueue("CHOOSEHAND", $this->controller, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $this->controller, "-", 1);
    AddDecisionQueue("DISCARDCARD", $this->controller, "HAND-" . $this->controller, 1);
  }
}

class grasp_of_the_darknight extends Card {
  function __construct($controller) {
    $this->cardID = "grasp_of_the_darknight";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Opt($this->cardID, 1);
    Await($this->controller, "PlayAura", cardID:"runechant");
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityCost() {
    return 1;
  }

  function AbilityHasGoAgain($from) {
    return true;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }
}

class grille_of_repentance extends Card {
  function __construct($controller) {
    $this->cardID = "grille_of_repentance";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Await($this->controller, "MultiZoneIndices", "indices", search:"MYBANISH:bloodDebtOnly=1", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "choice", contenxt:"Turn a card with blood debt in your banish face down");
    Await($this->controller, $this->cardID, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["choice"];
    $ind = explode("-", $choice)[1] ?? -1;
    if ($ind != -1) {
      $BanishCard = new BanishCard($this->controller, $ind);
      $BanishCard->SetModifier("DOWN");
    }
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }
}

class robe_of_repentance extends Card {
  function __construct($controller) {
    $this->cardID = "robe_of_repentance";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Await($this->controller, "MultiZoneIndices", "indices", search:"MYBANISH:bloodDebtOnly=1", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "choice", contenxt:"Turn a card with blood debt in your banish face down");
    Await($this->controller, $this->cardID, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["choice"];
    $ind = explode("-", $choice)[1] ?? -1;
    if ($ind != -1) {
      $BanishCard = new BanishCard($this->controller, $ind);
      $BanishCard->SetModifier("DOWN");
    }
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }
}

class path_of_repentance extends Card {
  function __construct($controller) {
    $this->cardID = "path_of_repentance";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Await($this->controller, "MultiZoneIndices", "indices", search:"MYBANISH:bloodDebtOnly=1", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "choice", contenxt:"Turn a card with blood debt in your banish face down");
    Await($this->controller, $this->cardID, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["choice"];
    $ind = explode("-", $choice)[1] ?? -1;
    if ($ind != -1) {
      $BanishCard = new BanishCard($this->controller, $ind);
      $BanishCard->SetModifier("DOWN");
    }
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }
}

class herald_of_hope extends BaseCard {
  function AddOnHitTrigger($check) {
    return AnyHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect() {
    global $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    if (DoesAttackHaveGoAgain()) GiveAttackGoAgain();
    SetCombatChainState($CCS_GoesWhereAfterLinkResolves, "-"); 
    AddSoul($this->cardID, $this->controller, "CC");
    GainHealth(1, $this->controller);
  }
}

class herald_of_hope_red extends Card {
  function __construct($controller) {
    $this->cardID = "herald_of_hope_red";
    $this->controller = $controller;
    $this->baseCard = new herald_of_hope($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
 
  function SpecialName() {
    return "Herald of Hope";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialPower() {
    return 6;
  }

  function SpecialClass() {
    return "ILLUSIONIST";
  }

  function SpecialTalent() {
    return "LIGHT";
  }

  function HasPhantasm() {
    return true;
  }
}

class herald_of_hope_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "herald_of_hope_yellow";
    $this->controller = $controller;
    $this->baseCard = new herald_of_hope($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
 
  function SpecialName() {
    return "Herald of Hope";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 5;
  }

  function SpecialClass() {
    return "ILLUSIONIST";
  }

  function SpecialTalent() {
    return "LIGHT";
  }

  function HasPhantasm() {
    return true;
  }
}

class herald_of_hope_blue extends Card {
  function __construct($controller) {
    $this->cardID = "herald_of_hope_blue";
    $this->controller = $controller;
    $this->baseCard = new herald_of_hope($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
 
  function SpecialName() {
    return "Herald of Hope";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 4;
  }

  function SpecialClass() {
    return "ILLUSIONIST";
  }

  function SpecialTalent() {
    return "LIGHT";
  }

  function HasPhantasm() {
    return true;
  }
}

class shadowrealm_strength extends BaseCard {
  function PlayAbility() {
    Await($this->controller, "MultiZoneIndices", "indices", search:"MYBANISH", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "choice", may:true, context:"Move a card in your banish to your graveyard");
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["choice"] ?? "";
    $ind = explode("-", $choice)[1] ?? -1;
    if ($ind != -1) {
      $BanishCard = new BanishCard($this->controller, $ind);
      AddGraveyard($BanishCard->ID(), $this->controller, "MYBANISH");
      if (SubtypeContains($BanishCard->ID(), "Zombie"))
        AddCurrentTurnEffect($this->cardID, $this->controller);
      $BanishCard->Remove();
    }
  }
}

class shadowrealm_strength_red extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "shadowrealm_strength_red";
    $this->controller = $controller;
    $this->baseCard = new shadowrealm_strength($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic();
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class shadowrealm_strength_blue extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "shadowrealm_strength_blue";
    $this->controller = $controller;
    $this->baseCard = new shadowrealm_strength($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic();
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function SpecialName() {
    return "Shadowrealm Strength";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialType() {
    return "A";
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function SpecialClass() {
    return "NECROMANCER";
  }

  function HasGoAgain($from) {
    return true;
  }
}

class otherworldly_sins extends BaseCard {
  function PlayAbility() {
    PlayAura("runechant", $this->controller);
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CombatEffectActive() {
    global $CombatChain;
    $attackCard = $CombatChain->AttackCard()->ID();
    return TalentContains($attackCard, "SHADOW", $this->controller) || ClassContains($attackCard, "RUNEBLADE", $this->controller);
  }
}

class otherworldly_sins_red extends Card {
  function __construct($controller) {
    $this->cardID = "otherworldly_sins_red";
    $this->controller = $controller;
    $this->baseCard = new otherworldly_sins($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }
}

class otherworldly_sins_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "otherworldly_sins_yellow";
    $this->controller = $controller;
    $this->baseCard = new otherworldly_sins($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }
}

class otherworldly_sins_blue extends Card {
  function __construct($controller) {
    $this->cardID = "otherworldly_sins_blue";
    $this->controller = $controller;
    $this->baseCard = new otherworldly_sins($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }
}

class usurp_the_shadow_throne_blue extends Card {
  function __construct($controller) {
    $this->cardID = "usurp_the_shadow_throne_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    WriteLog("Kneel, Reaper. The time has come, and I am unbound.", highlight:true, highlightColor:"purple");
    return "";
  }

  function SelfCostModifier($from) {
    global $CS_UsurpedThisTurn;
    return GetClassState($this->controller, $CS_UsurpedThisTurn) > 0 ? -6 : 0;
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    global $CS_UsurpedThisTurn;
    return GetClassState($this->controller, $CS_UsurpedThisTurn) > 0;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $Banish = new Banish($otherPlayer);
    $num = 0;
    for ($i = 0; $i < $Banish->NumCards(); ++$i) {
      $BanishCard = $Banish->Card($i, true);
      if (!isFaceDownMod($BanishCard->Modifier())) {
        $BanishCard->Modify("DOWN");
        ++$num;
      }
    }
    GainHealth($num, $this->controller);
    LoseHealth($num, $otherPlayer);
  }
}

class battle_clearing_bellow_blue extends Card {
  function __construct($controller) {
    $this->cardID = "battle_clearing_bellow_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function AssignEffectToCard($cardID, $effectIndex, $from) {
    global $Stack;
    $Effect = new CurrentEffect($effectIndex);
    $TopLayer = $Stack->TopLayer($cardID);
    if (TypeContains($TopLayer->ID(), "AA") && (LayerStepBasePower() >= 6))
      $Effect->ApplyToUniqueID("ATTACK");
    elseif (TypeContains($TopLayer->ID(), "AA")) // make sure it does not apply to an attack it "missed" but still let it apply in the future
      $Effect->ApplyToUniqueID("MISSED");
  }

  function IsLayerContinuousBuff() {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return $attached ? 6 : 0;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    if ($CombatChain->HasCurrentLink())
      return LinkBasePower(true) >= 6;
    return false;
  }
}

class bone_barrier_blue extends Card {
  function __construct($controller) {
    $this->cardID = "bone_barrier_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function OnDefenseReactionResolveEffects($from, $blockedFromHand) {
    global $CombatChain;
    $ChainCard = $CombatChain->Card($CombatChain->NumCardsActiveLink() -1, true);
    AddLayer("TRIGGER", $this->controller, $this->cardID, uniqueID: $ChainCard->UniqueID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Await($this->controller, "MultiZoneIndices", search:"MYHAND:subtype=Ally&MYALLY", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "choice", may:true, context:"Destroy an Ally or discard an Ally to gain 2 block");
    Await($this->controller, $this->cardID, uniqueID: $uniqueID, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["choice"] ?? "-";
    $zone = explode("-", $choice)[0];
    $index = explode("-", $choice)[1] ?? "";
    $uniqueID = $dqVars["uniqueID"];
    if ($index != "") {
      switch ($zone) {
        case "MYHAND":
          DiscardCard($this->controller, $index, "", $this->controller);
          break;
        case "MYALLY":
          $AllyCard = new AllyCard($index, $this->controller);
          $AllyCard->Destroy();
          break;
        default:
          break;
      }
    }
    AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID:$uniqueID);
  }

  function EffectBlockModifier($index, $from, $effectInd) {
    $Effect = new CurrentEffect($effectInd);
    $ChainCard = new ChainCard($index);
    return $Effect->AppliestoUniqueID() == $ChainCard->UniqueID() ? 2 : 0;
  }
}

class consuming_strength_yellow extends Card {
  public $archetype;

  function __construct($controller) {
    $this->cardID = "consuming_strength_yellow";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 1;
    return 2;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-", $allNames = false) {
    $names = explode(",", $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount));
    if (count($names) > 1 && !ControlsBlasmo($this->controller)) return $names[0];
    return implode(",", $names);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    return $this->archetype->AddPrePitchDecisionQueue($from, $index, dest:"BANISH");
  }
}

class consuming_lash_yellow extends Card {
  public $archetype;

  function __construct($controller) {
    $this->cardID = "consuming_lash_yellow";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 1;
    return 2;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-", $allNames = false) {
    $names = explode(",", $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount));
    if (count($names) > 1 && !ControlsBlasmo($this->controller)) return $names[0];
    return implode(",", $names);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    return $this->archetype->AddPrePitchDecisionQueue($from, $index, dest:"BANISH");
  }
}

class harbinger_of_destruction_red extends Card {
  function __construct($controller) {
    $this->cardID = "harbinger_of_destruction_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $hand = new Hand($this->controller);
    $count = $from == "HAND" ? 2 : 1;
    return $hand->NumCards() < $count;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $hand = new Hand($this->controller);
    if ($hand->NumCards() == 0) {
      WriteLog("No card in hand to banish, reverting gamestate", highlight:true);
      RevertGamestate();
      return;
    }
    Await($this->controller, "MultiZoneIndices", search:"MYHAND", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "choice", context:"Banish a card from your hand");
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["choice"];
    $index = explode("-", $choice)[1] ?? -1;
    if ($index != -1) {
      $hand = new Hand($this->controller);
      $cardID = $hand->Card($index);
      BanishCardForPlayer($cardID, $this->controller, "HAND");
      if (TalentContains($cardID, "SHADOW", $this->controller))
        AddCurrentTurnEffect($this->cardID, $this->controller);
      $hand->Remove($index);
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    return AnyHitTrigger($this->controller, $this->cardID, $check, true);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    PlayAura("gate_to_iarathael", $this->controller, 2);
  }
}

class tribute_to_greater_power_red extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "tribute_to_greater_power_red";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function DoesEffectGrantOverpower() {
    return true;
  }

  function CardCost($from = '-') {
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
    return $this->archetype->AddPrePitchDecisionQueue($from, $index, dest:"BANISH");
  }
}

class embrace_sin extends BaseCard {
  function PlayAbility() {
    AddCurrentTurnEffectNextAttack("$this->cardID-BUFF", $this->controller);
    AddCurrentTurnEffect("$this->cardID-SIN", $this->controller);
  }

  function CombatEffectActive($parameter) {
    return $parameter == "BUFF";
  }

  function PlayCardEffectAbility($cardID, $index, &$remove, $from) {
    $Effect = new CurrentEffect($index);
    if (IsRunechant($cardID) && str_contains($Effect->EffectID(), "SIN") && $from == "BANISH")
      $remove = true;
  }
}

class embrace_sin_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "embrace_sin_yellow";
    $this->controller = $controller;
    $this->baseCard = new embrace_sin($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive($parameter);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function PlayCardEffectAbility($cardID, $from, &$remove, $index = -1) {
    $this->baseCard->PlayCardEffectAbility($cardID, $index, $remove, $from);
  }
}

class vexing_gloomblade extends BaseCard {
  function PlayAbility($additionalCosts) {
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function EffectPowerModifier() {
    return 2;
  }

  function CombatEffectActive() {
    return true;
  }

  function PayAdditionalCosts($from) {
    Usurp($this->cardID, $this->controller, $from);
  }

  function AddOnHitTrigger($check) {
    global $CombatChain;
    if (IsHeroAttackTarget()) {
      if (!$check) {
        $uid = $CombatChain->AttackCard()->UniqueID();
        SetArcaneTarget($this->controller, $this->cardID, "any");
        Await($this->controller, "AddTrigger", lastResultName:"target", cardID:$this->cardID, uniqueID:$uid, additional: "ONHITEFFECT", final:true);
      }
      return true;
    }
    return false;
  }

  function HitEffect($target) {
    DealArcane(2, resolvedTarget:$target);
  }
}

class vexing_gloomblade_red extends Card {
  function __construct($controller) {
    $this->cardID = "vexing_gloomblade_red";
    $this->controller = $controller;
    $this->baseCard = new vexing_gloomblade($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($additionalCosts);
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function PayAdditionalCosts($from, $index = '-') {
    return $this->baseCard->PayAdditionalCosts($from);
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return true;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect($target);
  }
}

class vexing_gloomblade_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "vexing_gloomblade_yellow";
    $this->controller = $controller;
    $this->baseCard = new vexing_gloomblade($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($additionalCosts);
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function PayAdditionalCosts($from, $index = '-') {
    return $this->baseCard->PayAdditionalCosts($from);
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return true;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect($target);
  }
}

class vexing_gloomblade_blue extends Card {
  function __construct($controller) {
    $this->cardID = "vexing_gloomblade_blue";
    $this->controller = $controller;
    $this->baseCard = new vexing_gloomblade($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($additionalCosts);
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function PayAdditionalCosts($from, $index = '-') {
    return $this->baseCard->PayAdditionalCosts($from);
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return true;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect($target);
  }
}

class bloodsong_gloomblade extends BaseCard {
  function PlayAbility($additionalCosts) {
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function EffectPowerModifier() {
    return 2;
  }

  function CombatEffectActive() {
    return true;
  }

  function PayAdditionalCosts($from) {
    Usurp($this->cardID, $this->controller, $from);
  }

  function AddOnHitTrigger($check) {
    global $CombatChain;
    if (IsHeroAttackTarget()) {
      if (!$check) {
        $uid = $CombatChain->AttackCard()->UniqueID();
        SetTargets($this->controller, $this->cardID, "THEIRAURAS", playCard:false);
        Await($this->controller, "AddTrigger", lastResultName:"target", cardID:$this->cardID, uniqueID:$uid, additional: "ONHITEFFECT", final:true);
      }
      return true;
    }
    return false;
  }

  function HitEffect($target) {
    global $CombatChain;
    $AuraCard = CleanTargetToObject($this->controller, $target);
    $AuraCard->Banish("-", $CombatChain->AttackCard()->ID(), $this->controller);
  }
}

class bloodsong_gloomblade_red extends Card {
  function __construct($controller) {
    $this->cardID = "bloodsong_gloomblade_red";
    $this->controller = $controller;
    $this->baseCard = new bloodsong_gloomblade($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($additionalCosts);
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function PayAdditionalCosts($from, $index = '-') {
    return $this->baseCard->PayAdditionalCosts($from);
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return true;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect($target);
  }
}

class battle_prep extends BaseCard {
  function PlayAbility($from) {
    Opt($this->cardID, 2);
    if ($from == "ARS")
      AddCurrentTurnEffect($this->cardID, $this->controller);
  }
}

class battle_prep_red extends Card {
  function __construct($controller) {
    $this->cardID = "battle_prep_red";
    $this->controller = $controller;
    $this->baseCard = new battle_prep($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }
}

class battle_prep_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "battle_prep_yellow";
    $this->controller = $controller;
    $this->baseCard = new battle_prep($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }
}

class battle_prep_blue extends Card {
  function __construct($controller) {
    $this->cardID = "battle_prep_blue";
    $this->controller = $controller;
    $this->baseCard = new battle_prep($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }
}

class crushing_headache_red extends Card {
  function __construct($controller) {
    $this->cardID = "crushing_headache_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddCrushEffectTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "CRUSHEFFECT");
  }

  function ProcessCrushEffect() {
    global $defPlayer;
    if (CanRevealCards($defPlayer)) {
      RevealHand($defPlayer);
      $Arsenal = new Arsenal($defPlayer);
      for ($i = 0; $i < $Arsenal->NumCards(); ++$i) {
        $Card = $Arsenal->Card($i, true);
        RevealCards($Card->CardID());
        if (TypeContains($Card->CardID(), "A"))
          $Card->Destroy($this->controller);
      }
      $Hand = new Hand($defPlayer);
      for ($i = $Hand->NumCards() - 1; $i >= 0; --$i) {
        if (TypeContains($Hand->Card($i, true), "A"))
          DiscardCard($defPlayer, $i);
      }
    }
  }

  function HasCrush() {
    return true;
  }
}

class ice_aged_oak_blue extends Card {
  function __construct($controller) {
    $this->cardID = "ice_aged_oak_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    foreach (explode(",", $additionalCosts) as $pitchedCard) {
      if (TalentContains($pitchedCard, "ICE", $this->controller)) {
        AddCurrentTurnEffect($this->cardID, $this->controller);
        break;
      }
    }
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function DoesEffectGrantDominate($effectIndex) {
    return true;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    return HeroHitTrigger($this->controller, $this->cardID, $check, true);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    PlayAura("embodiment_of_earth", $this->controller);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    global $defPlayer;
    $zones = ListExposedEquipSlots($defPlayer);
    if ($zones != "") {
      foreach (explode(",", $zones) as $zone)
        EquipEquipment($defPlayer, "frostbite", $zone, "-", effectAgent:$this->controller);
    }
  }

  function CardCaresAboutPitch() {
    return true;
  }

  function HasDominate() {
    return false;
  }
}

class ancient_earth_oak_red extends Card {
  function __construct($controller) {
    $this->cardID = "ancient_earth_oak_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    foreach (explode(",", $additionalCosts) as $pitchedCard) {
      if (TalentContains($pitchedCard, "EARTH", $this->controller)) {
        AddCurrentTurnEffect($this->cardID, $this->controller);
        break;
      }
    }
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    return HeroHitTrigger($this->controller, $this->cardID, $check, true);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    PlayAura("frostbite", $defPlayer, effectAgent:$this->controller);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    global $CombatChain, $CCS_GoesWhereAfterLinkResolves, $defPlayer, $mainPlayer;
    SetCombatChainState($CCS_GoesWhereAfterLinkResolves, "-");
    $origCardID = $CombatChain->AttackCard()->OriginalID();
    $destPlayer = str_contains($CombatChain->AttackCard()->From(), "THEIR") ? $defPlayer : $mainPlayer;
    WriteLog(CardLink($origCardID) . " was put on the bottom of player $destPlayer's deck");
    AddBottomDeck($origCardID, $destPlayer, "CC");
  }

  function CardCaresAboutPitch() {
    return true;
  }
}

class head_banging_chorus_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "head_banging_chorus_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function StartTurnAbility($index) {
    RemoveSuspense($this->controller, "MYAURAS-$index", mainPhase: false);
  }

  function HasSuspense() {
    return true;
  }

  function PermanentHitEffect($index, $damageSource, $targetPlayer, $flicked, $check) {
    global $CS_GuardianAACThisTurn, $CS_ReveredAACThisTurn, $CombatChain;
    if (!IsHeroAttackTarget()) return;
    $attackCard = $CombatChain->AttackCard()->ID();
    if (ClassContains($attackCard, "GUARDIAN", $this->controller)) {
      if (GetClassState($this->controller, $CS_GuardianAACThisTurn) == 1) {
        if (!$check)
          AddLayer("TRIGGER", $this->controller, $this->cardID, $index, "ONHITEFFECT");
        return true;
      }
    }
    elseif (TalentContains($attackCard, "REVERED", $this->controller)) {
      if (GetClassState($this->controller, $CS_ReveredAACThisTurn) == 1) {
        if (!$check)
          AddLayer("TRIGGER", $this->controller, $this->cardID, $index, "ONHITEFFECT");
        return true;
      }
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $CombatChain;
    $hand = new Hand($this->controller);
    if ($hand->NumCards() == 0) {
      Draw($this->controller, effectSource:$CombatChain->AttackCard()->ID());
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "DESTROY") {
      DestroyAuraUniqueID($this->controller, $target);
    }
  }
}

class apex_buster_yellow extends Card {
  public $archetype;
  function __construct($controller) {
    $this->cardID = "apex_buster_yellow";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Target = CleanTargetToObject($this->controller, $target);
    if ($Target != "")
      $Target->Destroy();
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 2;
    return 3;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  private
  function GetTargets() {
    global $CombatChain, $ChainLinks, $mainPlayer;
    $targets = [];
    if ($this->controller != $mainPlayer) return $targets;
    if ($CombatChain->HasCurrentLink()) {
      if (LinkBasePower() >= 6) {
        for ($i = 1; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
          $ChainCard = $CombatChain->Card($i, true);
          $targets[] = "COMBATCHAINLINK-" . $ChainCard->Index();
        }
      }
    }
    $numLinks = $ChainLinks->NumLinks();
    for ($i = 0; $i < $numLinks; ++$i) {
      $Link = $ChainLinks->GetLink($i);
      if (PowerValue($Link->AttackCard()->ID(), $mainPlayer, "CC", $i, true, true) >= 6) {
        for ($j = 0; $j < $Link->NumCards(); ++$j) {
          $ChainCard = $Link->GetLinkCard($j, true);
          $targets[] = "PASTCHAINLINK-" . $ChainCard->Index() . "-$i";
        }
      }
    }
    return $targets;
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-", $allNames = false) {
    $names = explode(",", $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount));
    if (count($names) > 1 && count($this->GetTargets()) == 0) return "-,$names[1]";
    return implode(",", $names);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    global $CS_NumActionsPlayed;
    $names = GetAbilityNames($this->cardID, $index, $from);
    $names = str_replace("-,", "", $names);
    if (SearchCurrentTurnEffects("red_in_the_ledger_red", $this->controller) && GetClassState($this->controller, $CS_NumActionsPlayed) >= 1) {
      AddDecisionQueue("SETABILITYTYPEABILITY", $this->controller, $this->cardID);
    } elseif ($names != "" && $from == "HAND") {
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose to play the ability or attack");
      AddDecisionQueue("BUTTONINPUT", $this->controller, $names);
      AddDecisionQueue("SETABILITYTYPE", $this->controller, $this->cardID);
    } else {
      AddDecisionQueue("SETABILITYTYPEATTACK", $this->controller, $this->cardID);
    }
    AddDecisionQueue("NOTEQUALPASS", $this->controller, "Ability");
    AddDecisionQueue("PASSPARAMETER", $this->controller, $this->cardID, 1);
    AddDecisionQueue("DISCARDCARD", $this->controller, "HAND-$this->cardID", 1);
    // targetting a blocking card
    AddDecisionQueue("PASSPARAMETER", $this->controller, implode(",", $this->GetTargets()), 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a defending card to destroy", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "-", 1);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);

    AddDecisionQueue("CONVERTLAYERTOABILITY", $this->controller, $this->cardID, 1);
  }
}

class danse_macabre extends Card {
  function __construct($controller) {
    $this->cardID = "danse_macabre";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PermanentAllyPlayAbility($allyIndex, $charIndex, $from) {
    $CharacterCard = new CharacterCard($charIndex, $this->controller);
    if (!$CharacterCard->IsActive() || $CharacterCard->Tapped()) return;
    $AllyCard = new AllyCard($allyIndex, $this->controller);
    Await($this->controller, "AddTrigger", uniqueID:$AllyCard->UniqueID(), cardID:$this->cardID, subsequent:0, final:true);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "DESTROY") {
      $Allies = new Allies($this->controller);
      $Ally = $Allies->FindCardUID($uniqueID);
      $Ally->Destroy();
    }
    else {
      $Character = new PlayerCharacter($this->controller);
      $CharacterCard = $Character->FindCardID($this->cardID);
      if ($CharacterCard->Index() != -1) {
        $message = "if_you_want_to_accelerate_your_zombie";
        $context = "Choose if you want to pay 2 and tap " . CardLink($this->cardID) . " to accelerate your zombie";
        Await($this->controller, "YesNo", message:$message, context:$context, subsequent:0);
        Await($this->controller, "PayResources", amount:2);
        Await($this->controller, $this->cardID, uniqueID:$uniqueID, final:true);
      }
    }
  }

  function SpecificLogic() {
    global $dqVars;
    $uid = $dqVars["uniqueID"];
    $Character = new PlayerCharacter($this->controller);
    $CharacterCard = $Character->FindCardID($this->cardID);
    $CharacterCard->Tap();
    AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID:$uid);
    AddCurrentTurnEffect("$this->cardID-GOAGAIN", $this->controller, uniqueID:$uid);
  }

  function CurrentEffectBeginEndPhaseAbility($i) {
    $Effect = new CurrentEffect($i);
    if (str_contains($Effect->EffectID(), "GOAGAIN")) return;
    AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts: "DESTROY", uniqueID:$Effect->AppliestoUniqueID());
  }
  

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $parameter == "GOAGAIN";
  }

  function CurrentEffectGrantsGoAgain($param) {
    return $param == "GOAGAIN";
  }

  function DefaultActiveState() {
    return 1;
  }
}

class hellbound_assault extends BaseCard {
  function AddOnHitTrigger($check) {
    return AnyHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect() {
    global $CombatChain;
    BanishCardForPlayer($this->cardID, $this->controller, "CC");
    $CombatChain->AttackCard()->Remove();
  }
}

class hellbound_assault_red extends Card {
  function __construct($controller) {
    $this->cardID = "hellbound_assault_red";
    $this->controller = $controller;
    $this->baseCard = new hellbound_assault($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class hellbound_assault_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "hellbound_assault_yellow";
    $this->controller = $controller;
    $this->baseCard = new hellbound_assault($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class hellbound_assault_blue extends Card {
  function __construct($controller) {
    $this->cardID = "hellbound_assault_blue";
    $this->controller = $controller;
    $this->baseCard = new hellbound_assault($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class ingest_the_unknown_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "ingest_the_unknown_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $Deck = new Deck($this->controller);
    $cardID = $Deck->BanishTop();
    if ($cardID != "") {
      $power = PowerValue($cardID, $this->controller, "BANISH", base:true);
      AddCurrentTurnEffect("$this->cardID-$power", $this->controller);
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    if (!is_numeric($param)) return 0;
    return intval($param);
  }
}

class forsaken_strike_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "forsaken_strike_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($additionalCosts == "GATE")
      AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    PlayAura("gate_to_iarathael", $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $zombiePlayInds = MultiZoneIndices($this->controller, "MYALLY:subtype=Zombie");
    $zombieHandInds = MultiZoneIndices($this->controller, "MYHAND:subtype=Zombie");
    $iters = min(SearchCount($zombiePlayInds), 3) + min(SearchCount($zombieHandInds), 3);
    for ($i = 0; $i < $iters; ++$i) {
      $sub = $i != 0;
      $remaining = $iters - $i;
      Await($this->controller, $this->cardID, "indices", mode:"indices", subsequent:$sub);
      Await($this->controller, "ChooseMultiZone", may:true, context:"Destroy or Discard up to $remaining more Zombies (or pass)");
      Await($this->controller, $this->cardID, "numModes", mode:"destroy");
    }

    Await($this->controller, $this->cardID, mode:"selection", subsequent:false, final:true);
  }

  function SpecificLogic() {
    global $dqVars, $CS_AdditionalCosts;
    $numSac = GetClassState($this->controller, $CS_AdditionalCosts);
    $numDestroyed = explode("-", $numSac)[0];
    if (!is_numeric($numDestroyed)) $numDestroyed = 0;
    $numDiscarded = explode("-", $numSac)[1] ?? 0;
    if (!is_numeric($numDiscarded)) $numDiscarded = 0;
    $mode = $dqVars["mode"] ?? "-";
    switch($mode) {
      case "indices":
        $search = [];
        if ($numDestroyed < 3)
          $search[] = "MYALLY:subtype=Zombie";
        if ($numDiscarded < 3)
          $search[] = "MYHAND:subtype=Zombie";
        $search = implode("&", $search);
        return MultiZoneIndices($this->controller, $search);
      case "destroy":
        $choice = $dqVars["MZIndex"] ?? "";
        $zone = explode("-", $choice)[0];
        $ind = explode("-", $choice)[1] ?? -1;
        if ($ind != -1) {
          switch ($zone) {
            case "MYALLY":
              $AllyCard = new AllyCard($ind, $this->controller);
              $AllyCard->Destroy();
              ++$numDestroyed;
              break;
            case "MYHAND":
              DiscardCard($this->controller, $ind);
              ++$numDiscarded;
              break;
            default:
              break;
          }
        }
        SetClassState($this->controller, $CS_AdditionalCosts, "$numDestroyed-$numDiscarded");
        return "$numDestroyed-$numDiscarded";
      case "selection":
        $modalities = "Create_a_gate,Buff_Power,Go_again";
        $numModes = $numDestroyed + $numDiscarded;
        if ($numModes >= 3) {
          AddDecisionQueue("PASSPARAMETER", $this->controller, $modalities);
          AddDecisionQueue("MODAL", $this->controller, $this->cardID, 1);
          AddDecisionQueue("SHOWMODES", $this->controller, $this->cardID, 1);
        } elseif ($numModes < 3 && $numModes > 0) {
          AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose $numModes modes");
          AddDecisionQueue("MULTICHOOSETEXT", $this->controller, "$numModes-$modalities-$numModes");
          AddDecisionQueue("MODAL", $this->controller, $this->cardID, 1);
          AddDecisionQueue("SHOWMODES", $this->controller, $this->cardID, 1);
        }
        return "";
      default:
        return "";
    }
  }

  function ModalAbility($lastResult, $index) {
    global $CS_AdditionalCosts;
    if(!is_array($lastResult)) $lastResult = explode(",", $lastResult);
    $countLastResult = count($lastResult);
    for($i = 0; $i < $countLastResult; ++$i) {
      switch($lastResult[$i]) {
        case "Create_a_gate": {
          SetClassState($this->controller, $CS_AdditionalCosts, "GATE");
          break;
        }
        case "Buff_Power": {
          AddCurrentTurnEffect("$this->cardID-BUFF", $this->controller);
          break;
        }
        case "Go_again": {
          AddCurrentTurnEffect("$this->cardID-GOAGAIN", $this->controller);
          break;
        }
      }
    }
    return $lastResult;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return $param == "BUFF" ? 2 : 0;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return $param == "GOAGAIN";
  }

  function SpecialName() {
    return "Forsaken Strike";
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialClass() {
    return "NECROMANCER";
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function SpecialPower() {
    return 3;
  }
}

class restless_corporal_red extends Card {
  function __construct($controller) {
    $this->cardID = "restless_corporal_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (GetResolvedAbilityType($this->cardID, $from, $this->controller) == "A" && $from == "PLAY")
      MZMoveCard($this->controller, "MYBANISH", "MYDISCARD", DQContext:"Move a card from banish to graveyard");
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $from == "PLAY" ? "A" : "";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = '-', $allNames = false) {
    if (SearchLayersForPhase("RESOLUTIONSTEP") != -1) return "-";
    return "Recycle_Banished_Card";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    if ($from != "PLAY") return false;
    $AllyCard = new AllyCard($index, $this->controller);
    return $AllyCard->Tapped();
  }

  function PayAdditionalCosts($from, $index = '-') {
    if ($from == "PLAY") {
      $AllyCard = new AllyCard($index, $this->controller);
      $AllyCard->TapForCost();
    }
  }

  function AbilityHasGoAgain($from) {
    return GetResolvedAbilityType($this->cardID, $from, $this->controller) == "A";
  }

  function GoesOnCombatChain($phase, $from) {
    return GetResolvedAbilityType($this->cardID, $from) == "AA";
  }

  function HasGoAgain($from) {
    return false;
  }

  function SpecialName() {
    return "Restless Corporal";
  }

  function SpecialHealth() {
    return 3;
  }

  function SpecialBlock() {
    return -2;
  }

  function SpecialPower() {
    return 3;
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

  function SpecialSubType() {
    return "Zombie,Ally";
  }

  function HasDecay() {
    return true;
  }
}

class restless_outlaw_red extends Card {
  function __construct($controller) {
    $this->cardID = "restless_outlaw_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddGraveyardEffect($from, $effectController, $cardController) {
    if ($from == "PLAY") 
      AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    BanishCardForPlayer("corrupted_corpse", $this->controller, "-", created:true);
  }

  function SpecialName() {
    return "Restless Outlaw";
  }

  function SpecialHealth() {
    return 3;
  }

  function SpecialBlock() {
    return -2;
  }

  function SpecialPower() {
    return 3;
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

  function SpecialSubType() {
    return "Zombie,Ally";
  }

  function HasDecay() {
    return true;
  }
}

class cullingsong_gloomblade_red extends Card {
  function __construct($controller) {
    $this->cardID = "cullingsong_gloomblade_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function PayAdditionalCosts($from, $index = '-') {
    Usurp($this->cardID, $this->controller, $from);
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return true;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    BanishFromHand($defPlayer);
  }

  function SpecialName() {
    return "Cullingsong Gloomblade";
  }

  function SpecialPower() {
    return 2;
  }

  function SpecialClass() {
    return "RUNEBLADE";
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function HasBloodDebt() {
    return true;
  }
}

class sonata_dystopia_blue extends Card {
  function __construct($controller) {
    $this->cardID = "sonata_dystopia_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect("$this->cardID-$resourcesPaid", $this->controller);
    return "";
  }

  function DoesEffectGrantOverpower() {
    return true;
  }

  function CurrentEffectCostModifier($cardID, $from, &$remove, $index, $playIndex) {
    if (TypeContains($cardID, "AA") && !IsActivated($cardID, $from)) {
      $Effect = new CurrentEffect($index);
      $amount = explode("-", $Effect->EffectID())[1] ?? 0;
      return -$amount;
    }
    return 0;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    return TypeContains($CombatChain->AttackCard()->ID(), "AA");
  }

  function EffectPowerModifier($param, $attached = false) {
    return intval($param);
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    // need to pass parameter instead of cardID to make sure the number of runechants follows through
    if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $parameter, "EFFECTHITEFFECT");
    return true;
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    global $CombatChain;
    $amount = intval($mode);
    if ($amount > 0) PlayAura("runechant", $this->controller, $amount, effectSource:$CombatChain->AttackCard()->ID());
  }

  function DynamicCost() {
    $end = CountAura("runechant", $this->controller);
    return implode(",", range(0, $end));
  }

  function PayAdditionalCosts($from, $index = '-') {
    global $CS_DynCostResolved;
    $numRunechants = GetClassState($this->controller, $CS_DynCostResolved);
    for ($i = 0; $i < $numRunechants; ++$i) {
      Await($this->controller, "RunechantIndices", "indices", subsequent:$i > 0);
      Await($this->controller, "ChooseMultiZone", context:"Destroy a runechant");
      Await($this->controller, "MZDestroy", final: $i == $numRunechants-1);
    }
  }

  function SpecialName() {
    return "Sonata Dystopia";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialClass() {
    return "RUNEBLADE";
  }

  function SpecialType() {
    return "A";
  }

  function HasGoAgain($from) {
    return true;
  }
}

class runic_disposition_red extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runic_disposition_red";
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
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 0;
    return 2;
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
    return "Runic Disposition";
  }

  function SpecialPower() {
    return 6;
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "RUNEBLADE";
  }
}

class runic_disposition_yellow extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runic_disposition_yellow";
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
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 0;
    return 2;
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
    return "Runic Disposition";
  }

  function SpecialPower() {
    return 5;
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "RUNEBLADE";
  }
}

class runic_disposition_blue extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runic_disposition_blue";
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
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 0;
    return 2;
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
    return "Runic Disposition";
  }

  function SpecialPower() {
    return 4;
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "RUNEBLADE";
  }
}

class countdown_to_extinction extends BaseCard {
  function PlayAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger() {
    PlayAura("gate_to_iarathael", $this->controller);
  }

  function AddOnHitTrigger($check) {
    return AnyHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect() {
    MaySearchDeck($this->controller, "isSameName=darkest_hour_red", "MYBANISH", 0, "-", "Banish a Darkest Hour (or pass)");
  }
}

class countdown_to_extinction_red extends Card {
  function __construct($controller) {
    $this->cardID = "countdown_to_extinction_red";
    $this->controller = $controller;
    $this->baseCard = new countdown_to_extinction($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }

  function SpecialName() {
    return "Countdown to Extinction";
  }

  function SpecialCost() {
    return 3;
  }

  function SpecialPower() {
    return 6;
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function HasBloodDebt() {
    return true;
  }
}

class countdown_to_extinction_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "countdown_to_extinction_yellow";
    $this->controller = $controller;
    $this->baseCard = new countdown_to_extinction($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }

  function SpecialName() {
    return "Countdown to Extinction";
  }

  function SpecialCost() {
    return 3;
  }

  function SpecialPower() {
    return 5;
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function HasBloodDebt() {
    return true;
  }
}

class countdown_to_extinction_blue extends Card {
  function __construct($controller) {
    $this->cardID = "countdown_to_extinction_blue";
    $this->controller = $controller;
    $this->baseCard = new countdown_to_extinction($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }

  function SpecialName() {
    return "Countdown to Extinction";
  }

  function SpecialCost() {
    return 3;
  }

  function SpecialPower() {
    return 4;
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function HasBloodDebt() {
    return true;
  }
}

class darkest_hour extends BaseCard {
  function PlayAbility() {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CombatEffectActive() {
    global $CombatChain;
    return TalentContains($CombatChain->AttackCard()->ID(), "SHADOW", $this->controller);
  }

  function AddPrePitchDecisionQueue() {
    HandToTopDeck($this->controller);
    AddDecisionQueue("ADDCURRENTTURNEFFECT", $this->controller, "$this->cardID-PAID", 1);
  }

  function CurrentTurnEffectPaid(&$remove, $index) {
    $Effect = new CurrentEffect($index);
    if ($Effect->EffectID() == "$this->cardID-PAID") {
      $remove = true;
      return true;
    }
    return false;
  }
}

class darkest_hour_red extends Card {
  function __construct($controller) {
    $this->cardID = "darkest_hour_red";
    $this->controller = $controller;
    $this->baseCard = new darkest_hour($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return 4;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = '-') {
    $this->baseCard->AddPrePitchDecisionQueue();
  }

  function CurrentTurnEffectPaid($cardID, $from, &$remove, $index) {
    return $this->baseCard->CurrentTurnEffectPaid($remove, $index);
  }

  function SpecialName() {
    return "Darkest Hour";
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialType() {
    return "A";
  }

  function HasGoAgain($from) {
    return true;
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function HasBloodDebt() {
    return true;
  }
}

class darkest_hour_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "darkest_hour_yellow";
    $this->controller = $controller;
    $this->baseCard = new darkest_hour($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = '-') {
    $this->baseCard->AddPrePitchDecisionQueue();
  }

  function CurrentTurnEffectPaid($cardID, $from, &$remove, $index) {
    return $this->baseCard->CurrentTurnEffectPaid($remove, $index);
  }

  function SpecialName() {
    return "Darkest Hour";
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialType() {
    return "A";
  }

  function HasGoAgain($from) {
    return true;
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function HasBloodDebt() {
    return true;
  }
}

class darkest_hour_blue extends Card {
  function __construct($controller) {
    $this->cardID = "darkest_hour_blue";
    $this->controller = $controller;
    $this->baseCard = new darkest_hour($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = '-') {
    $this->baseCard->AddPrePitchDecisionQueue();
  }

  function CurrentTurnEffectPaid($cardID, $from, &$remove, $index) {
    return $this->baseCard->CurrentTurnEffectPaid($remove, $index);
  }

  function SpecialName() {
    return "Darkest Hour";
  }

  function SpecialCost() {
    return 2;
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

  function SpecialTalent() {
    return "SHADOW";
  }

  function HasBloodDebt() {
    return true;
  }
}

class planar_chaos_red extends Card {
  function __construct($controller) {
    $this->cardID = "planar_chaos_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("gate_to_iarathael", $this->controller);
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function SpecialName() {
    return "Planar Chaos";
  }

  function SpecialType() {
    return "A";
  }

  function HasGoAgain($from) {
    return true;
  }

  function SpecialTalent() {
    return "SHADOW";
  }
}

class plundersong_gloomblade_red extends Card {
  function __construct($controller) {
    $this->cardID = "plundersong_gloomblade_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($additionalCosts == "USURPED")
      AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function PayAdditionalCosts($from, $index = '-') {
    Usurp($this->cardID, $this->controller, $from);
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return true;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    BanishFromArsenal($otherPlayer, $this->cardID);
  }

  function SpecialName() {
    return "Plundersong Gloomblade";
  }

  function SpecialPower() {
    return 2;
  }

  function SpecialClass() {
    return "RUNEBLADE";
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function HasBloodDebt() {
    return true;
  }
}

class dimenxxional_ferryman_blue extends Card {
  function __construct($controller) {
    $this->cardID = "dimenxxional_ferryman_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $search = "MYBANISH:type=A;bloodDebtOnly=true&MYBANISH:type=AA;bloodDebtOnly=true";
    $choices = MultiZoneIndices($this->controller, $search);
    if (SearchCount($choices) > 0) {
      Await($this->controller, "ChooseMultiZone", indices:$choices, context: "Put a blood debt action on the bottom of your deck", subsequent:0);
      Await($this->controller, $this->cardID, final:true);
    }
    else
      Await($this->controller, $this->cardID, subsequent:0, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["MZIndex"] ?? "-";
    $BanishCard = MZIndexToObject($this->controller, $choice);
    if ($BanishCard != "") {
      $cards = "$this->cardID," . $BanishCard->CardID();
      $BanishCard->Remove();
      AddDecisionQueue("CHOOSEBOTTOM", $this->controller, $cards);
    }
    else
      AddBottomDeck($this->cardID, $this->controller, "LAYERS");
  }

  function GoesWhereAfterResolving($from, $playedFrom, $stillOnCombatChain, $additionalCosts) {
    return "-";
  }

  function SpecialName() {
    return "Dimenxxional Ferryman";
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

  function SpecialTalent() {
    return "SHADOW";
  }
}

class echoing_trap_blue extends Card {
  function __construct($controller) {
    $this->cardID = "echoing_trap_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    global $CS_NamesOfCardsPlayed, $mainPlayer, $CombatChain;
    if(GetClassState($mainPlayer, $CS_NamesOfCardsPlayed) == "-") return;
    $cardsPlayed = explode(",", GetClassState($mainPlayer, $CS_NamesOfCardsPlayed) ?? "");
    $echoCount = 0;
    $attackingCard = $CombatChain->AttackCard()->ID();
    foreach ($cardsPlayed as $card) {
      if (CardNameContains($attackingCard, CardName($card), $mainPlayer))
        ++$echoCount;
    }
    if ($echoCount >= 2)
      AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $mainPlayer;
    PummelHit($mainPlayer);
  }

  function SpecialName() {
    return "Echoing Trap";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialCost() {
    return -1;
  }

  function SpecialType() {
    return "B";
  }

  function SpecialSubType() {
    return "Trap";
  }

  function SpecialClass() {
    return "RANGER";
  }

  function HasAmbush() {
    return true;
  }
}

class stoke_vengeance_red extends Card {
  function __construct($controller) {
    $this->cardID = "stoke_vengeance_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function HasCombo() {
    return true;
  }

  function ComboActive($lastAttackName) {
    if ($lastAttackName == "Edge of Autumn") return true;
  }

  function DoesAttackHaveGoAgain() {
    global $CombatChain;
    $attackID = $CombatChain->AttackCard()->ID();
    return ComboActive($attackID);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $CombatChain;
    $attackID = $CombatChain->AttackCard()->ID();
    if (ComboActive($attackID))
      return AnyHitTrigger($this->controller, $this->cardID, $check);
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
  }

  function RemoveEffectFromCombatChain($effectIndex) {
    return true;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function SpecialName() {
    return "Stoke Vengeance";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialPower() {
    return 4;
  }

  function SpecialClass() {
    return "NINJA";
  }
}

class chains_of_consecration_yellow extends Card {
  private $search;
  function __construct($controller) {
    $this->cardID = "chains_of_consecration_yellow";
    $this->controller = $controller;
    $this->search = "THEIRALLY&MYALLY";
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $targetPlayer = str_contains($target, "THEIR") ? $this->controller : $otherPlayer;
    $uid = explode("-", $target)[1] ?? "-";
    if ($uid != "-")
      AddCurrentTurnEffect($this->cardID, $targetPlayer, uniqueID:$uid);
    return "";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return MultiZoneIndices($this->controller, $this->search) == "";
  }

  function PayAdditionalCosts($from, $index = '-') {
    SetTargets($this->controller, $this->cardID, $this->search);
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount = false) {
    global $CS_ResolvingLayerUniqueID, $CombatChain;
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $Effect = new CurrentEffect($index);
    if (!$preventable) return 0;
    if ($amount && $CombatChain->HasCurrentLink() && $source == $CombatChain->AttackCard()->ID() && $type == "COMBAT") { //this block is mostly for displaying prevention
      if ($CombatChain->AttackCard()->UniqueID() == $Effect->AppliestoUniqueID() || $CombatChain->AttackCard()->OriginUniqueID() == $Effect->AppliestoUniqueID())
        return $damage;
    }
    if (GetClassState(1, $CS_ResolvingLayerUniqueID) == $Effect->AppliestoUniqueID()) {
      if (!$amount) {
        $TheirAllies = new Allies($otherPlayer);
        $AllyCard = $TheirAllies->FindCardUID($Effect->AppliestoUniqueID());
        // potential issue here if this destroys an ally before the damage step
        if (TalentContains($AllyCard->CardID(), "SHADOW", $otherPlayer)) {
          WriteLog(CardLink($AllyCard->CardID()) . " was consecrated!");
          $AllyCard->Destroy(toBanished:true, skipClose:true, mod:"DOWN");
        }
      }
      return $damage;
    }
    return 0;
  }

  function SpecialName() {
    return "Chains of Consecration";
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialTalent() {
    return "LIGHT";
  }

  function SpecialType() {
    return "I";
  }

  function SpecialBlock() {
    return -2;
  }
}

class deadly_spinneret_red extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "deadly_spinneret_red";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $numZones = 2 - NumOccupiedHands($this->controller);
    EquipWeapon($this->controller, "graphene_chelicera", $this->cardID, num:$numZones);
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

  function SpecialName() {
    return "Deadly Spinneret";
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

  function HasStealth() {
    return true;
  }
}

class sigil_of_the_muse_red extends Card {
  function __construct($controller) {
    $this->cardID = "sigil_of_the_muse_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function BeginningActionPhaseAbility($index) {
    $AuraCard = new AuraCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "DESTROY", $AuraCard->UniqueID());
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID="-"): void {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CS_ArcaneTargetsSelected;
    if ($additionalCosts == "DESTROY") {
      $Auras = new Auras($this->controller);
      $AuraCard = $Auras->FindCardUID($uniqueID);
      if ($AuraCard != "") $AuraCard->Destroy();
    }
    else {
      PlayAura("ponder", $this->controller);
    }
  }

  function SpecialName() {
    return "Sigil of the Muse";
  }

  function SpecialType() {
    return "A";
  }

  function SpecialSubType() {
    return "Aura";
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "WIZARD";
  }
}

class rush_of_knowledge_blue extends Card {
  function __construct($controller) {
    $this->cardID = "rush_of_knowledge_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $Auras = new Auras($this->controller);
    $Ponder = $Auras->FindCardID("ponder");
    if ($Ponder->Index() != -1) {
      $choice = "MYAURAS-" . $Ponder->Index();
      $context = "Destroy a ponder to draw a card and gain an action point (or pass)";
      Await($this->controller, "ChooseMultiZone", indices:$choice, may:true, context:$context, subsequent:0);
      Await($this->controller, $this->cardID, final:true);
    }
  }

  function SpecificLogic() {
    global $dqVars;
    $Ponder = MZIndexToObject($this->controller, $dqVars["MZIndex"]);
    if ($Ponder != "") {
      $Ponder->Destroy();
      GainActionPoints(1, $this->controller);
      Draw($this->controller); 
    }
  }

  function SpecialName() {
    return "Rush of Knowledge";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 4;
  }

  function SpecialClass() {
    return "ILLUSIONIST,WIZARD";
  }

  function SpecialBlock() {
    return 2;
  }

  function HasPhantasm() {
    return true;
  }
}

class astral_ambience_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "astral_ambience_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
      if ($from == "PLAY")
        AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function AbilityPlayableFromCombatChain($index = '-') {
    global $mainPlayer;
    return $this->controller == $mainPlayer;
  }

  function AbilityType($index = -1, $from = '-') {
		return ($from == "PLAY" || $from == "COMBATCHAINATTACKS") ? "I": "AA";
	}

  function PayAdditionalCosts($from, $index = '-') {
    $inds = GetUntapped($this->controller, "MYAURAS", "isSameName=spectral_shield");
    if($inds != "") {
      AddDecisionQueue("PASSPARAMETER", $this->controller, $inds);
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("MZTAP", $this->controller, "<-", 1);
    }
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    if ($from == "PLAY" || $from == "COMBATCHAINATTACKS") {
      $inds = GetUntapped($this->controller, "MYAURAS", "isSameName=spectral_shield");
      return $inds == "";
    }
    return false;
  }

  function HasFragment() {
    return true;
  }

  function FragmentTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("spectral_shield", $this->controller);
  }

  function SpecialName() {
    return "Astral Ambience";
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialPower() {
    return 6;
  }

  function SpecialClass() {
    return "ILLUSIONIST";
  }
}

class ominous_toll extends BaseCard {
  function PlayAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger() {
    Await($this->controller, "MultiZoneIndices", search:"MYHAND:subtype=Zombie", subsequent:0);
    Await($this->controller, "ChooseMultiZone", context:"Discard a zombie to make a gate (or pass)", may:true);
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["MZIndex"] ?? "-";
    $index = explode("-", $choice)[1] ?? -1;
    if ($index != "-") {
      DiscardCard($this->controller, $index);
      PlayAura("gate_to_iarathael", $this->controller);
    }
  }
}

class ominous_toll_red extends Card {
  function __construct($controller) {
    $this->cardID = "ominous_toll_red";
    $this->controller = $controller;
    $this->baseCard = new ominous_toll($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }
  
  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic();
  }

  function SpecialName() {
    return "Ominous Toll";
  }

  function SpecialPower() {
    return 3;
  }

  function SpecialBlock() {
    return 2;
  }

  function HasGoAgain($from) {
    return true;
  }

  function SpecialClass() {
    return "NECROMANCER";
  }

  function SpecialTalent() {
    return "SHADOW";
  }
}

class ominous_toll_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "ominous_toll_yellow";
    $this->controller = $controller;
    $this->baseCard = new ominous_toll($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }
  
  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic();
  }

  function SpecialName() {
    return "Ominous Toll";
  }

  function SpecialPower() {
    return 2;
  }

  function SpecialBlock() {
    return 2;
  }

  function HasGoAgain($from) {
    return true;
  }

  function SpecialClass() {
    return "NECROMANCER";
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function SpecialPitch() {
    return 2;
  }
}

class ominous_toll_blue extends Card {
  function __construct($controller) {
    $this->cardID = "ominous_toll_blue";
    $this->controller = $controller;
    $this->baseCard = new ominous_toll($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }
  
  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic();
  }

  function SpecialName() {
    return "Ominous Toll";
  }

  function SpecialPower() {
    return 1;
  }

  function SpecialBlock() {
    return 2;
  }

  function HasGoAgain($from) {
    return true;
  }

  function SpecialClass() {
    return "NECROMANCER";
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function SpecialPitch() {
    return 3;
  }
}

class embrace_ursur extends BaseCard {
  function PlayAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger() {
    Await($this->controller, "MultiZoneIndices", search:"MYHAND", subsequent:0);
    Await($this->controller, "ChooseMultiZone", context:"Banish a card (or pass)", may:true);
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["MZIndex"] ?? "-";
    $index = explode("-", $choice)[1] ?? -1;
    if ($index != "-") {
      $Hand = new Hand($this->controller);
      $cardID = $Hand->Remove($index);
      BanishCardForPlayer($cardID, $this->controller, "HAND");
      if (ClassContains($cardID, "RUNEBLADE", $this->controller))
        PlayAura("runechant", $this->controller);
      if (TalentContains($cardID, "SHADOW", $this->controller))
        AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }
}

class embrace_ursur_red extends Card {
  function __construct($controller) {
    $this->cardID = "embrace_ursur_red";
    $this->controller = $controller;
    $this->baseCard = new embrace_ursur($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialName() {
    return "Embrace Ursur";
  }

  function SpecialPower() {
    return 3;
  }

  function SpecialClass() {
    return "RUNEBLADE";
  }

  function SpecialTalent() {
    return "SHADOW";
  }
}

class embrace_ursur_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "embrace_ursur_yellow";
    $this->controller = $controller;
    $this->baseCard = new embrace_ursur($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialName() {
    return "Embrace Ursur";
  }

  function SpecialPower() {
    return 2;
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialClass() {
    return "RUNEBLADE";
  }

  function SpecialTalent() {
    return "SHADOW";
  }
}

class embrace_ursur_blue extends Card {
  function __construct($controller) {
    $this->cardID = "embrace_ursur_blue";
    $this->controller = $controller;
    $this->baseCard = new embrace_ursur($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialName() {
    return "Embrace Ursur";
  }

  function SpecialPower() {
    return 1;
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialClass() {
    return "RUNEBLADE";
  }

  function SpecialTalent() {
    return "SHADOW";
  }
}

class consuming_appetite_yellow extends Card {
  public $archetype;
  function __construct($controller) {
    $this->cardID = "consuming_appetite_yellow";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    return CardNameContains($CombatChain->AttackCard()->ID(), "Blasmophet, the Insatiable Hunger");
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 1;
    return 3;
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
    return $this->archetype->AddPrePitchDecisionQueue($from, $index, dest:"BANISH");
  }

  function SpecialName() {
    return "Consuming Appetite";
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 6;
  }

  function SpecialClass() {
    return "BRUTE";
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function HasBloodDebt() {
    return true;
  }
}

class reach_of_the_abyss extends Card {
  function __construct($controller) {
    $this->cardID = "reach_of_the_abyss";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CombatChainCloseAbility($chainLink) {
    global $ChainLinks;
    for ($i = 0; $i < $ChainLinks->NumLinks(); ++$i) {
      $Link = $ChainLinks->GetLink($i);
      for ($j = 1; $j < $Link->NumCards(); ++$j) {
        $LinkCard = $Link->GetLinkCard($j, true);
        $cardID = $LinkCard->ID();
        BanishCardForPlayer($cardID, $this->controller, "CC");
        $LinkCard->Remove();
      }
    }
  }

  // function SpecialName() {
  //   return "Reach of the Abyss";
  // }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Arms";
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialTalent() {
    return "SHADOW";
  }
}