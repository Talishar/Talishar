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
    AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
    AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose a card to banish", 1);
    AddDecisionQueue("CHOOSEHAND", $otherPlayer, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
    AddDecisionQueue("BANISHCARD", $otherPlayer, "HAND,-", 1);
  }

  function HasDecay() {
    return true;
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
    GainHealth($combatChainState[$CCS_DamageDealt], $this->controller);
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
      Await($this->controller, $this->cardID, index:$index, subsequent:0, final:true);
    }
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

  function EntersArenaAbility() {
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