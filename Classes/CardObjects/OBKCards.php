<?php

class vyle_base extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    foreach ([$this->controller, $otherPlayer] as $player) {
      if (!ArsenalFull($player)) {
        $deck = new Deck($player);
        if (!$deck->Empty()) {
          $topCard = $deck->Top(true);
          AddArsenal($topCard, $player, "DECK", "UP");
        }
      }
    }
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return 2;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->TapForCost();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $mainPlayer;
    $CharacterCard = new CharacterCard($index, $this->controller);
    return $CharacterCard->Tapped() || $mainPlayer != $this->controller;
  }

  function PermanentPowerModifier(&$powerModifiers) {
    global $CombatChain, $defPlayer;
    $hasFaceUpCard = false;
    $Arsenal = new Arsenal($defPlayer);
    for ($i = 0; $i < $Arsenal->NumCards(); ++$i) {
      $ArsenalCard = $Arsenal->Card($i, true);
      if ($ArsenalCard->Facing() == "UP")
        $hasFaceUpCard = true;
    }
    if (SubTypeContains($CombatChain->AttackCard()->ID(), "Arrow") && $hasFaceUpCard) {
      $powerModifiers[] = $this->cardID;
      $powerModifiers[] = 1;
      return 1;
    }
    return 0;
  }
}

class vyle extends Card {
  function __construct($controller) {
    $this->cardID = "vyle";
    $this->controller = $controller;
    $this->baseCard = new vyle_base($this->cardID, $this->controller);
  }
}

class vyle_catalyst_of_collapse extends Card {
  function __construct($controller) {
    $this->cardID = "vyle_catalyst_of_collapse";
    $this->controller = $controller;
    $this->baseCard = new vyle_base($this->cardID, $this->controller);
  }
}

class mortimer_living_virus extends Card {
  function __construct($controller) {
    $this->cardID = "mortimer_living_virus";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Await($this->controller, "MultiZoneIndices", search:"MYAURAS:subtype=Disease", subsequent:0);
    Await($this->controller, "ChooseMultiZone", context:"Spread one of your diseases");
    Await($this->controller, $this->cardID, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $choice = MZIndexToObject($this->controller, $dqVars["MZIndex"] ?? "-");
    if ($choice != "") {
      $disease = $choice->CardID();
      PlayAura($disease, $otherPlayer, effectAgent:$this->controller, effectController:$this->controller);
    }
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->TapForCost();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $mainPlayer;
    $CharacterCard = new CharacterCard($index, $this->controller);
    return $CharacterCard->Tapped() || $mainPlayer != $this->controller;
  }
}

class min_front extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function BeginEndTurnAbilities($index) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Hero = new CharacterCard(0, $this->controller);
    $Hero->Become($this->Backside());
  }

  function Backside() {
    return match($this->cardID) {
      "min_half_genius" => "min_half_mad",
      "min_genius" => "min_mad"
    };
  }
}

class min_half_genius extends Card {
  function __construct($controller) {
    $this->cardID = "min_half_genius";
    $this->controller = $controller;
    $this->baseCard = new min_front($this->cardID, $this->controller);
  }
}

class min_genius extends Card {
  function __construct($controller) {
    $this->cardID = "min_genius";
    $this->controller = $controller;
    $this->baseCard = new min_front($this->cardID, $this->controller);
  }
}

class min_back extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function BeginEndTurnAbilities($index) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Hero = new CharacterCard(0, $this->controller);
    $Hero->Become($this->Frontside());
  }

  function Frontside() {
    return match($this->cardID) {
      "min_half_mad" => "min_half_genius",
      "min_mad" => "min_genius"
    };
  }
}

class min_half_mad extends Card {
  function __construct($controller) {
    $this->cardID = "min_half_mad";
    $this->controller = $controller;
    $this->baseCard = new min_back($this->cardID, $this->controller);
  }
}

class min_mad extends Card {
  function __construct($controller) {
    $this->cardID = "min_mad";
    $this->controller = $controller;
    $this->baseCard = new min_back($this->cardID, $this->controller);
  }
}

class huxley_base extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PermanentBoostAbility($index, $banishInd, $owner) {
    if ($owner != $this->controller) return;
    $boostedCard = new BanishCard($this->controller, $banishInd);
    $heroCard = new CharacterCard($index, $this->controller);
    if ($heroCard->Status() >= 3) return;
    $boostedCardID = $boostedCard->CardID();
    if (!$heroCard->Tapped() && HasStealth($boostedCardID) && (TypeContains($boostedCardID, "A") || TypeContains($boostedCardID, "AA")))
      AddLayer("TRIGGER", $this->controller, $this->cardID, $boostedCard->UniqueID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Hero = new CharacterCard(0, $this->controller);
    if ($Hero->Tapped()) return;
    $Banish = new Banish($this->controller);
    $BanishCard = $Banish->FindCardUID($target);
    $context = "Would you like to tap your hero to be able to play " . CardLink($BanishCard->CardID()) . " at instant speed?";
    Await($this->controller, "YesNo", context:$context, subsequent:0);
    Await($this->controller, $this->cardID, uniqueID:$target, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $target = $dqVars["uniqueID"] ?? "-";
    if ($target != "-") {
      AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID:$target);
      $Hero = new CharacterCard(0, $this->controller);
      $Hero->Tap();
    }
  }
}

class huxley extends Card {
  function __construct($controller) {
    $this->cardID = "huxley";
    $this->controller = $controller;
    $this->baseCard = new huxley_base($this->cardID, $this->controller);
  }
}

class huxley_racketeer extends Card {
  function __construct($controller) {
    $this->cardID = "huxley_racketeer";
    $this->controller = $controller;
    $this->baseCard = new huxley_base($this->cardID, $this->controller);
  }
}

class a_01_first_cogwarden extends Card {
  function __construct($controller) {
    $this->cardID = "a_01_first_cogwarden";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    switch ($target) {
      case "Load_Boosted_Arrow":
        $boostedArrows = [];
        $Banish = new Banish($this->controller);
        for ($i = 0; $i < $Banish->NumCards(); ++$i) {
          $BanishCard = $Banish->Card($i, true);
          if ($BanishCard->Modifier() == "BOOST" && SubtypeContains($BanishCard->CardID(), "Arrow"))
            $boostedArrows[] = "MYBANISH-" . $BanishCard->Index();
        }
        $choices = implode(",", $boostedArrows);
        Await($this->controller, "ChooseMultiZone", indices:$choices, context:"Load a boosted arrow", subsequent:0);
        Await($this->controller, $this->cardID, mode:"load", final:true);
        break;
      case "Give_Go_Again":
        AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
        break;
      default:
        break;
    }
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain, $combatChainState, $CCS_NumPowerCounters;
    return SubtypeContains($CombatChain->AttackCard()->ID(), "Arrow") && $combatChainState[$CCS_NumPowerCounters] > 0;
  }

  function AbilityCost() {
    return 1;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    return $CharacterCard->Tapped();
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->TapForCost();
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an ability for " . CardLink($this->cardID));
    AddDecisionQueue("BUTTONINPUT", $this->controller, "Load_Boosted_Arrow,Give_Go_Again");
    AddDecisionQueue("SHOWMODES", $this->controller, $this->cardID, 1);
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $Stack, $dqVars;
    $mode = $dqVars["mode"] ?? "-";
    if ($mode == "load") {
      $choice = $dqVars["MZIndex"] ?? "-";
      $banishCard = MZIndexToObject($this->controller, $choice);
      if ($banishCard != "") {
        AddArsenal($banishCard->CardID(), $this->controller, "BANISH", "UP", powerCounters:2);
        $banishCard->Remove();
      }
    }
    else {
      $Layer = $Stack->TopLayer($this->cardID);
      $Layer->AddTarget($dqVars["LASTRESULT"]);
    }
  }

  function DefaultActiveState() {
    return 1;
  }
}

class azalea_assassin_base extends baseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $Deck = new Deck($this->controller);
    if (ArsenalFull($this->controller)) {
      WriteLog("🏹 Your arsenal is full, you cannot load");
      return "🏹 Your arsenal is full, you cannot load";
    }
    $topCard = $Deck->Top(true);
    $uniqueID = AddArsenal($topCard, $this->controller, "DECK", "UP");
    if (HasStealth($topCard))
      AddCurrentTurnEffect("$this->cardID-STEALTH", $this->controller, uniqueID:$uniqueID);
    if (SubtypeContains($topCard, "Arrow"))
      AddCurrentTurnEffect("$this->cardID-ARROW", $this->controller, uniqueID:$uniqueID);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return $param == "STEALTH";
  }

  function EffectPowerModifier($param, $attached = false) {
    return $param == "STEALTH" ? 1 : 0;
  }

  function DoesEffectGrantVeil($effectIndex) {
    $effect = new CurrentEffect($effectIndex);
    return str_contains($effect->EffectID(), "ARROW");
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->TapForCost();
    if(ArsenalEmpty($this->controller)) {
      WriteLog("There's no card in your arsenal! Reverting Gamestate!", highlight:true);
      RevertGamestate();
      return;
    }
    AddDecisionQueue("FINDINDICES", $this->controller, "ARSENAL");
    AddDecisionQueue("CHOOSEARSENAL", $this->controller, "<-", 1);
    AddDecisionQueue("REMOVEARSENAL", $this->controller, "-", 1);
    AddDecisionQueue("ADDBOTDECK", $this->controller, "-", 1);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $CharacterCard  = new CharacterCard($index, $this->controller);
    return $CharacterCard->Tapped() || ArsenalEmpty($this->controller);
  }

  function DefaultActiveState() {
    return 1;
  }
}

class azalea_lone_vigilante extends Card {
  function __construct($controller) {
    $this->cardID = "azalea_lone_vigilante";
    $this->controller = $controller;
    $this->baseCard = new azalea_assassin_base($this->cardID, $this->controller);
  }
}

class azalea_ace_of_spades extends Card {
  function __construct($controller) {
    $this->cardID = "azalea_ace_of_spades";
    $this->controller = $controller;
    $this->baseCard = new azalea_assassin_base($this->cardID, $this->controller);
  }
}

class paupers_knife extends Card {
  function __construct($controller) {
    $this->cardID = "paupers_knife";
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

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->TapForCost();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    return $CharacterCard->Tapped();
  }
}

class cogwerx_dueling_pistol extends Card {
  function __construct($controller) {
    $this->cardID = "cogwerx_dueling_pistol";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CS_PlayIndex;
    $abilityType = GetResolvedAbilityType($this->cardID);
    if($abilityType == "A")
    {
      $index = GetClassState($this->controller, $CS_PlayIndex);
      $character = new CharacterCard($index, $this->controller);
      $character->SetCounters(1);
    }
    return "";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->TapForCost();
    $abilityType = GetResolvedAbilityType($this->cardID);
    if ($abilityType == "AA")
      $CharacterCard->AddCounters(-1);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    return $CharacterCard->Tapped() || GetAbilityNames($this->cardID, $index, $from) == "-";;
  }

  function HasGoAgain($from) {
    return false;
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = '-', $allNames = false) {
    if ($allNames) return "Add_a_steam_counter,Attack";
    if (!is_numeric($index) || $index == -1) return "";
    $rv = SearchLayersForPhase("RESOLUTIONSTEP") == -1 ? "Add_a_steam_counter" : "-";
    $CharacterCard = new CharacterCard($index, $this->controller);
    if ($CharacterCard->NumCounters() > 0 && !SearchCurrentTurnEffects("kabuto_of_imperial_authority", $this->controller)) $rv .= ",Attack";
    return $rv;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return "A,AA";
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityCost() {
    $abilityType = GetResolvedAbilityType($this->cardID);
    return ($abilityType == "A" ? 1 : 0);
  }

  function AbilityHasGoAgain($from) {
    $abilityType = GetResolvedAbilityType($this->cardID);
    return $abilityType == "A";
  }
}

class one_click_pinpointer extends Card {
  function __construct($controller) {
    $this->cardID = "one_click_pinpointer";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $Arsenal = new Arsenal($this->controller);
    for ($i = $Arsenal->NumCards() - 1; $i >= 0; --$i) {
      $ArsenalCard = $Arsenal->Card($i, true);
      AddBottomDeck($ArsenalCard->CardID(), $this->controller, "ARS");
      $ArsenalCard->Remove();
    }
    LoadArrow($this->controller);
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityHasGoAgain($from) {
    return true;
  }

  function AbilityCost() {
    $Character = new PlayerCharacter($this->controller);
    for ($i = 0; $i < $Character->NumCards(); ++$i) {
      $CharacterCard = $Character->Card($i, true);
      if (SubtypeContains($CharacterCard->CardID(), "Bow") && $CharacterCard->Tapped())
        return 1;
    }
    return 0;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->TapForCost();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    return $CharacterCard->Tapped();
  }
}

class extinction_level_event_red extends Card {
  function __construct($controller) {
    $this->cardID = "extinction_level_event_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function EntersArenaAbility($index = -1) {
    $AuraCard = new AuraCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, $AuraCard->UniqueID(), "ENTERS");
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $AllDiseases;
    switch($additionalCosts) {
      case "ENTERS":
        $Auras = new Auras($this->controller);
        $AuraCard = $Auras->FindCardUID($target);
        if ($AuraCard->Index() != -1) {
          $choices = [];
          foreach ($AllDiseases as $disease) {
            $choices[] = "CARDID-$disease";
          }
          $choices = implode(",", $choices);
          Await($this->controller, "ChooseMultiZone", indices:$choices, context:"Choose a disease to propagate", subsequent:0);
          Await($this->controller, $this->cardID, uniqueID:$target);
        }
        break;
      case "EXTINCT":
        $count = 0;
        foreach ([1, 2] as $player) {
          $Auras = new Auras($player);
          for ($i = 0; $i < $Auras->NumAuras(); ++$i) {
            $AuraCard = $Auras->Card($i, true);
            if (SubtypeContains($AuraCard->CardID(), "Disease"))
              ++$count;
          }
        }
        if ($count >= 33) {
          WriteLog("And all was still", highlight:true);
          $otherPlayer = $this->controller == 1 ? 2 : 1;
          LoseHealth(GetHealth($otherPlayer), $otherPlayer);
        }
        else {
          WriteLog(33 - $count . " diseases left until extinction");
        }
        break;
      default:
        break;
    }
  }

  function SpecificLogic() {
    global $dqVars;
    $uniqueID = $dqVars["uniqueID"] ?? "-";
    $choice = explode("-", $dqVars["MZIndex"] ?? "-")[1];
    $Auras = new Auras($this->controller);
    $AuraCard = $Auras->FindCardUID($uniqueID);
    if ($AuraCard->Index() != -1 && $choice != "") {
      WriteLog(CardLink($choice) . " will spread!", highlight:true, highlightColor:"g");
      $AuraCard->AddModality(GamestateSanitize(CardName($choice)));
    }
  }

  function StartTurnAbility($index) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "EXTINCT");
  }
}

class bootlegger_bust_up extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function SelfBoostEffect($boostedCardID, $owner) {
    if (!ClassContains($boostedCardID, "MECHANOLOGIST", $owner))
      AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    return $CombatChain->AttackCard()->ID() == $this->cardID;
  }
}

class bootlegger_bust_up_red extends Card {
  function __construct($controller) {
    $this->cardID = "bootlegger_bust_up_red";
    $this->controller = $controller;
    $this->baseCard = new bootlegger_bust_up($this->cardID, $this->controller);
  }
}

class bootlegger_bust_up_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "bootlegger_bust_up_yellow";
    $this->controller = $controller;
    $this->baseCard = new bootlegger_bust_up($this->cardID, $this->controller);
  }
}

class bootlegger_bust_up_blue extends Card {
  function __construct($controller) {
    $this->cardID = "bootlegger_bust_up_blue";
    $this->controller = $controller;
    $this->baseCard = new bootlegger_bust_up($this->cardID, $this->controller);
  }
}

class exertion extends Card {
  function __construct($controller) {
    $this->cardID = "exertion";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    if ($from == "HAND" && !IsActivated($cardID, $from))
      AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "EXERT");
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    switch($additionalCosts) {
      case "EXERT":
        LoseHealth(1, $this->controller);
        break;
      case "DESTROY":
        DestroyAuraUniqueID($this->controller, $uniqueID, mainPhase:false);
        break;
      default:
        break;
    }
  }

  function BeginEndTurnAbilities($index) {
    $AuraCard = new AuraCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "DESTROY", uniqueID:$AuraCard->UniqueID());
  }
}

class exertion_bloom_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "exertion_bloom_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    foreach ([$this->controller, $otherPlayer] as $player) {
      PlayAura("exertion", $player, effectController:$this->controller, effectSource:$this->cardID, effectAgent:$this->controller);
    }
    return "";
  }

  function SelfCostModifier($from) {
    global $mainPlayer;
    return $this->controller == $mainPlayer ? -3 : 0;
  }
}

class curative_ceremony_red extends Card {
  function __construct($controller) {
    $this->cardID = "curative_ceremony_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $modes = explode(",", $additionalCosts);
    foreach ($modes as $mode) {
      $suffix = match($mode) {
        "Buff_Power" => "BUFF", "Go_again" => "GOAGAIN", "Veil" => "VEIL",
        default => "-"
      };
      if ($suffix != "-")
        AddCurrentTurnEffect("$this->cardID-$suffix", $this->controller);
    }
    return "";
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

  function DoesEffectGrantVeil($effectIndex) {
    $Effect = new CurrentEffect($effectIndex);
    return $Effect->EffectID() == "$this->cardID-VEIL";
  }

  function PayAdditionalCosts($from, $index = '-') {
    global $CS_AdditionalCosts;
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    Await($this->controller, "SetClassState", piece:$CS_AdditionalCosts, value:0);
    foreach ([$this->controller, $otherPlayer] as $player) {
      Await($player, "MultiZoneIndices", search:"MYAURAS:subtype=Disease", subsequent:0);
      Await($player, "ChooseMultiZone", context:"Offer a disease to the " . CardLink($this->cardID));
      Await($player, "MZDestroy");
      Await($this->controller, "IncrementClassState", piece:$CS_AdditionalCosts);
    }
    Await($this->controller, $this->cardID, subsequent:0, final:true);
  }

  function SpecificLogic() {
    global $CS_AdditionalCosts;
    $numModes = GetClassState($this->controller, $CS_AdditionalCosts);
    $modalities = "Buff_Power,Go_again,Veil";
    if ($numModes > 0) {
      Await($this->controller, "SetClassState", lastResultName:"value", piece:$CS_AdditionalCosts, prepend:"true");
      PrependDecisionQueue("SHOWMODES", $this->controller, $this->cardID, 1);
      PrependDecisionQueue("MULTICHOOSETEXT", $this->controller, "$numModes-$modalities-$numModes");
      PrependDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a mode for " . CardLink($this->cardID));
    }
  }
}

class incubator_implanter_red extends Card {
  function __construct($controller) {
    $this->cardID = "incubator_implanter_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $defPlayer;
    if (IsHeroAttackTarget()) {
      $defAuras = new Auras($defPlayer);
      if ($defAuras->FindCardID("bloodrot_pox")->Index() != -1)
        AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    }
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $parameter != "BLEED";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    return HeroHitTrigger($this->controller, $this->cardID, $check, true);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
    global $defPlayer;
    WriteLog("🩸 Player $defPlayer has begun bleeding out! 🩸");
    AddCurrentTurnEffect("$this->cardID-BLEED", $defPlayer);
  }

  function CurrentEffectEndTurnAbilities($i, &$remove) {
    global $mainPlayer;
    $Effect = new CurrentEffect($i);
    if ($Effect->EffectID() == "$this->cardID-BLEED") {
      if ($Effect->PlayerID() == $mainPlayer)
        PlayAura("bloodrot_pox", $this->controller);
      AddNextTurnEffect("$this->cardID-BLEED", $this->controller);
    }
  }

  function SpecialName() {
    return "Incubator Implanter";
  }

  function SpecialClass() {
    return "RANGER";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialSubType() {
    return "Arrow";
  }

  function SpecialPower() {
    return 3;
  }
}

class max_tension_shot_red extends Card {
  function __construct($controller) {
    $this->cardID = "max_tension_shot_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $Deck = new Deck($this->controller);
    if ($Deck->Reveal()) {
      $topCard = $Deck->Top();
      if (ColorContains($topCard, 1, $this->controller))
        AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }
}

class back_alley extends BaseCard {
  private
  function Disease() {
    return match($this->cardID) {
      "back_alley_bloodbank_red" => "bloodrot_pox",
      "back_alley_bilebank_yellow" => "exertion",
      "back_alley_plasmabank_blue" => "inertia",
      default => ""
    };
  }

  function BackAlleyTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    switch($this->cardID) {
      case "back_alley_breakline_red": case "back_alley_breakline_yellow": case "back_alley_breakline_blue":
        WriteLog("Player $this->controller gained 1 action point from " . CardLink($this->cardID).".");
        GainActionPoints(1, $this->controller);
        break;
      case "back_alley_bloodbank_red":
      case "back_alley_bilebank_yellow":
      case "back_alley_plasmabank_blue":
        $indices = "THEIRCHAR-0";
        if (!ShouldAutotargetOpponent($this->controller))
          $indices .= ",MYCHAR-0";
        $context = "Give a player " . CardLink($this->Disease());
        Await($this->controller, "ChooseMultiZone", indices:$indices, context:$context, subsequent:0);
        Await($this->controller, $this->cardID, final:true);
        break;
      default:
        break;
    }
  }

  function SpecificLogic() {
    global $dqVars;
    $targetHero = $dqVars["MZIndex"] ?? "THEIRCHAR-0";
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $targetPlayer = $targetHero == "THEIRCHAR-0" ? $otherPlayer : $this->controller;
    PlayAura($this->Disease(), $targetPlayer, effectController:$this->controller, effectSource:$this->cardID, effectAgent:$this->controller);
  }
}

class back_alley_breakline_red extends Card {
  function __construct($controller) {
    $this->cardID = "back_alley_breakline_red";
    $this->controller = $controller;
    $this->baseCard = new back_alley($this->cardID, $this->controller);
  }
}

class back_alley_breakline_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "back_alley_breakline_yellow";
    $this->controller = $controller;
    $this->baseCard = new back_alley($this->cardID, $this->controller);
  }
}

class back_alley_breakline_blue extends Card {
  function __construct($controller) {
    $this->cardID = "back_alley_breakline_blue";
    $this->controller = $controller;
    $this->baseCard = new back_alley($this->cardID, $this->controller);
  }
}

class back_alley_bloodbank_red extends Card {
  function __construct($controller) {
    $this->cardID = "back_alley_bloodbank_red";
    $this->controller = $controller;
    $this->baseCard = new back_alley($this->cardID, $this->controller);
  }
}

class back_alley_bilebank_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "back_alley_bilebank_yellow";
    $this->controller = $controller;
    $this->baseCard = new back_alley($this->cardID, $this->controller);
  }
}

class back_alley_plasmabank_blue extends Card {
  function __construct($controller) {
    $this->cardID = "back_alley_plasmabank_blue";
    $this->controller = $controller;
    $this->baseCard = new back_alley($this->cardID, $this->controller);
  }
}

class courier extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  private
  function Disease() {
    return match($this->cardID) {
      "bloodrot_courier_red" => "bloodrot_pox",
      "exertion_courier_yellow" => "exertion",
      "inertia_courier_blue" => "inertia",
      default => ""
    };
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $mainPlayer, $defPlayer;
    foreach ([$mainPlayer, $defPlayer] as $player)
      PlayAura($this->Disease(), $player, effectController:$this->controller, effectSource:$this->cardID, effectAgent:$this->controller);
  }
}

class bloodrot_courier_red extends Card {
  function __construct($controller) {
    $this->cardID = "bloodrot_courier_red";
    $this->controller = $controller;
    $this->baseCard = new courier($this->cardID, $this->controller);
  }
}

class exertion_courier_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "exertion_courier_yellow";
    $this->controller = $controller;
    $this->baseCard = new courier($this->cardID, $this->controller);
  }

  function SpecialCost() {
    return 3;
  }

  function SpecialPower() {
    return 4;
  }
}

class inertia_courier_blue extends Card {
  function __construct($controller) {
    $this->cardID = "inertia_courier_blue";
    $this->controller = $controller;
    $this->baseCard = new courier($this->cardID, $this->controller);
  }

  function SpecialCost() {
    return 4;
  }

  function SpecialPower() {
    return 4;
  }
}

class incite extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $foundColor = false;
    foreach ([$this->controller, $otherPlayer] as $player) {
      $Deck = new Deck($player);
      if (!ArsenalFull($player) && !$Deck->Empty()) {
        $Top = $Deck->Top(true);
        AddArsenal($Top, $player, "DECK", "UP");
        if (ColorContains($Top, PitchValue($this->cardID), $player))
          $foundColor = true;
      }
    }
    if ($foundColor)
      AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class incite_aggression_red extends Card {
  function __construct($controller) {
    $this->cardID = "incite_aggression_red";
    $this->controller = $controller;
    $this->baseCard = new incite($this->cardID, $this->controller);
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }
}

class incite_resolve_blue extends Card {
  function __construct($controller) {
    $this->cardID = "incite_resolve_blue";
    $this->controller = $controller;
    $this->baseCard = new incite($this->cardID, $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }
}

class patient_zero extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    GiveDisease($this->controller);
  }
}

class patient_zero_red extends Card {
  function __construct($controller) {
    $this->cardID = "patient_zero_red";
    $this->controller = $controller;
    $this->baseCard = new patient_zero($this->cardID, $this->controller);
  }
}

class patient_zero_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "patient_zero_yellow";
    $this->controller = $controller;
    $this->baseCard = new patient_zero($this->cardID, $this->controller);
  }
}

class patient_zero_blue extends Card {
  function __construct($controller) {
    $this->cardID = "patient_zero_blue";
    $this->controller = $controller;
    $this->baseCard = new patient_zero($this->cardID, $this->controller);
  }
}

class swap_spit extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function OnDefenseReactionResolveEffects($from, $blockedFromHand) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $myDiseases = SearchMultizone($this->controller, "MYAURAS:subtype=Disease");
    $theirDiseases = SearchMultizone($this->controller, "THEIRAURAS:subtype=Disease");
    $chosenMyDisease = "-";
    $chosenTheirDisease = "-";
    // I need to do something silly like this to make sure you don't swap the same disease twice
    if (SearchCount($myDiseases) > 0) { 
      $myDiseases = explode(",", $myDiseases);
      $chosenMyDisease = $myDiseases[GetRandom(0, count($myDiseases) - 1)];
      $chosenMyDisease = CleanTarget($this->controller, $chosenMyDisease);
    }
    if (SearchCount($theirDiseases) > 0) {
      $theirDiseases = explode(",", $theirDiseases);
      $chosenTheirDisease = $theirDiseases[GetRandom(0, count($theirDiseases) - 1)];
      $chosenTheirDisease = CleanTarget($this->controller, $chosenTheirDisease);
    }
    foreach ([$chosenMyDisease, $chosenTheirDisease] as $chosenDisease) {
      $obj = CleanTargetToObject($this->controller, $chosenDisease);
      if ($obj != "") {
        $srcPlayer = str_contains($chosenDisease, "MY") ? $this->controller : $otherPlayer;
        $destPlayer = OtherPlayer($srcPlayer);
        WriteLog("Player $srcPlayer gave their " . CardLink($obj->CardID()) . " to Player $destPlayer!");
        StealAura($srcPlayer, $obj->Index(), $destPlayer, "-", realSteal:false);
      }
    }
  }
}

class swap_spit_red extends Card {
  function __construct($controller) {
    $this->cardID = "swap_spit_red";
    $this->controller = $controller;
    $this->baseCard = new swap_spit($this->cardID, $this->controller);
  }
}

class swap_spit_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "swap_spit_yellow";
    $this->controller = $controller;
    $this->baseCard = new swap_spit($this->cardID, $this->controller);
  }
}

class swap_spit_blue extends Card {
  function __construct($controller) {
    $this->cardID = "swap_spit_blue";
    $this->controller = $controller;
    $this->baseCard = new swap_spit($this->cardID, $this->controller);
  }
}

class cure_all_elixir extends Card {
  function __construct($controller) {
    $this->cardID = "cure_all_elixir";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    Await($this->controller, "MultiZoneIndices", search:"MYAURAS:substype=Disease", subsequent:0);
    Await($this->controller, "ChooseMultiZone", context:"Cure a disease?", may:true);
    Await($this->controller, "MZDestroy");
    Await($this->controller, "GainHealth", final:true);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }
}

class raging_streets_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "raging_streets_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $choice = RandomNonEquipOnLink();
    if ($choice != -1) {
      CombatChainPowerModifier($choice, 3, $this->cardID);
      CombatChainDefenseModifier($choice, 3, $this->cardID);
    }
    return "";
  }
}

class quiet_streets_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "quiet_streets_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $choice = RandomNonEquipOnLink();
    if ($choice != -1) {
      CombatChainPowerModifier($choice, -3, $this->cardID);
      CombatChainDefenseModifier($choice, -3, $this->cardID);
    }
    return "";
  }
}

class revere_the_copper_idol_blue extends Card {
  function __construct($controller) {
    $this->cardID = "revere_the_copper_idol_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $inds = [];
    $foundCards = [];
    $Banish = new Banish($this->controller);
    for ($i = 0; $i < $Banish->NumCards(); ++$i) {
      $BanishCard = $Banish->Card($i, true);
      if (in_array($BanishCard->ID(), $foundCards)) continue;
      if (!isFaceDownMod($BanishCard->Modifier()) && IsAction($BanishCard->ID()) && ClassContains($BanishCard->ID(), "MECHANOLOGIST", $this->controller)) {
        $inds[] = $BanishCard->Index();
        $foundCards[] = $BanishCard->ID();
      }
    }
    $maxChoosable = (SearchCurrentTurnEffects("amnesia_red", $this->controller)) ? 0 : 3;
    $inds = implode(",", $inds);
    AddDecisionQueue("PASSPARAMETER", $this->controller, $inds);
    AddDecisionQueue("PREPENDLASTRESULT", $this->controller, "$maxChoosable-", 1);
    AddDecisionQueue("MULTICHOOSEBANISH", $this->controller, "<-", 1);
    Await($this->controller, $this->cardID, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    $lastResult = $dqVars["LASTRESULT"] ?? "";
    if ($lastResult != "") {
      $lastResult = explode(",", $lastResult);
      $cards = "";
    $deck = new Deck($this->controller);
    $discard = new Banish($this->controller);
    sort($lastResult);
    for ($i = count($lastResult) - 1; $i >= 0; --$i) {
      $cardID = $discard->Remove($lastResult[$i]);
      $deck->AddBottom($cardID, "BANISH");
      if ($cards != "")
      $cards .= ", ";
      if ($i == 0)
      $cards .= "and ";
      $cards .= CardLink($cardID, $cardID);
    }
    WriteLog(CardLink($this->cardID) . " shuffled into your deck " . $cards);
      $deck->Shuffle("-");
    }
  }

  function OnBoostedEffect($owner, $cardID) {
    $Banish = new Banish($this->controller);
    $BanishedCard = $Banish->Card($Banish->NumCards() - 1, true);
    if ($BanishedCard->ID() == $this->cardID)
      AddLayer("TRIGGER", $this->controller, $this->cardID, uniqueID:$BanishedCard->UniqueID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Banish = new Banish($this->controller);
    $BanishCard = $Banish->FindCardUID($uniqueID);
    $Deck = new Deck($this->controller);
    if ($BanishCard->Index() != -1) {
      $Deck->AddBottom($this->cardID);
      $BanishCard->Remove();
    }
    $Deck->Shuffle("-");
  }
}

class mortimers_lab extends Card {
  function __construct($controller) {
    $this->cardID = "mortimers_lab";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PermanentHitEffect($index, $damageSource, $targetPlayer, $flicked, $check) {
    global $CS_HitCounter, $mainPlayer;
    // This technically counts ally hits, won't matter for limited (probably)
    if (IsHeroAttackTarget() && GetClassState($mainPlayer, $CS_HitCounter) == 1)
      AddLayer("TRIGGER", $mainPlayer, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $defPlayer, $mainPlayer;
    $deck = new Deck($defPlayer);
    if ($deck->Reveal()) {
      $top = $deck->Top();
      if (ColorContains($top, 1, $defPlayer))
        $disease = "bloodrot_pox";
      elseif (ColorContains($top, 2, $defPlayer))
        $disease = "exertion";
      elseif (ColorContains($top, 3, $defPlayer))
        $disease = "inertia";
      else
        $disease = "-";
      if ($disease != "-")
        PlayAura($disease, $defPlayer, effectAgent:$mainPlayer, effectSource:$this->cardID);
    }
  }
}

class vengeance_never_dies_red extends Card {
  // When this hits a hero, create an Autumn's Curse token under their control.
  function __construct($controller) {
    $this->cardID = "vengeance_never_dies_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    PlayAura("autumns_curse", $defPlayer, effectController:$this->controller, effectSource:$this->cardID);
  }

  function SpecialName() {
    return "Vengeance Never Dies";
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
    return "NINJA";
  }

  function SpecialType() {
    return "AA";
  }
}

class autumns_curse extends Card {
  // Whenever an attack with Vengeance in its name hits you, lose 1{h}.
  function __construct($controller) {
    $this->cardID = "autumns_curse";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PermanentGetHitEffect($index, $damageSource, $targetPlayer, $flicked, $check) {
    global $mainPlayer;
    if (str_contains(NameOverride($damageSource, $mainPlayer), "Vengeance")) {
      if (!$check)
        AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    LoseHealth(1, $defPlayer);
  }

  function SpecialName() {
    return "Autumn's Curse";
  }

  function SpecialBlock() {
    return -2;
  }

  function SpecialClass() {
    return "NINJA";
  }

  function SpecialType() {
    return "T";
  }

  function SpecialSubType() {
    return "Aura";
  }
}

class kickback extends BaseCard {
  // Boost
  // Stealth
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function SpecialName() {
    return "Kickback";
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN,MECHANOLOGIST";
  }

  function SpecialType() {
    return "AA";
  }

  function HasStealth() {
    return true;
  }

  function HasBoost() {
    return true;
  }
}

class kickback_red extends Card {
  function __construct($controller) {
    $this->cardID = "kickback_red";
    $this->controller = $controller;
    $this->baseCard = new kickback($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 3;
  }
}

class kickback_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "kickback_yellow";
    $this->controller = $controller;
    $this->baseCard = new kickback($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 2;
  }
}

class kickback_blue extends Card {
  function __construct($controller) {
    $this->cardID = "kickback_blue";
    $this->controller = $controller;
    $this->baseCard = new kickback($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 1;
  }
}

class whisper_shot extends BaseCard {
  // Stealth
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function SpecialName() {
    return "Whisper Shot";
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "ASSASSIN,RANGER";
  }

  function SpecialType() {
    return "AA";
  }

  function SpecialSubType() {
    return "Arrow";
  }

  function HasStealth() {
    return true;
  }
}

class whisper_shot_red extends Card {
  function __construct($controller) {
    $this->cardID = "whisper_shot_red";
    $this->controller = $controller;
    $this->baseCard = new whisper_shot($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 4;
  }
}

class whisper_shot_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "whisper_shot_yellow";
    $this->controller = $controller;
    $this->baseCard = new whisper_shot($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 3;
  }
}

class whisper_shot_blue extends Card {
  function __construct($controller) {
    $this->cardID = "whisper_shot_blue";
    $this->controller = $controller;
    $this->baseCard = new whisper_shot($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 2;
  }
}

class rocket_powered_arrow extends BaseCard {
  // Boost
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function SpecialName() {
    return "Rocket-Powered Arrow";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "MECHANOLOGIST,RANGER";
  }

  function SpecialType() {
    return "AA";
  }

  function SpecialSubType() {
    return "Arrow";
  }

  function HasBoost() {
    return true;
  }
}

class rocket_powered_arrow_red extends Card {
  function __construct($controller) {
    $this->cardID = "rocket_powered_arrow_red";
    $this->controller = $controller;
    $this->baseCard = new rocket_powered_arrow($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 5;
  }
}

class rocket_powered_arrow_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "rocket_powered_arrow_yellow";
    $this->controller = $controller;
    $this->baseCard = new rocket_powered_arrow($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 4;
  }
}

class rocket_powered_arrow_blue extends Card {
  function __construct($controller) {
    $this->cardID = "rocket_powered_arrow_blue";
    $this->controller = $controller;
    $this->baseCard = new rocket_powered_arrow($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 3;
  }
}

class virulent_cascade extends BaseCard {
  // Stealth
  // When this attacks, choose Exertion, Inertia, or Bloodrot Pox at random and create that token under your control.
  // If you and the defending hero are infected, this gets +1{p} and go again.
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    global $OBKDiseases;
    $ind = GetRandom(0,3);
    WriteLog(CardLink($this->cardID) . " creates a $OBKDiseases[$ind]!");
    PlayAura($OBKDiseases[$ind], $this->controller, effectController:$this->controller, effectSource:$this->controller);
    return "";
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return IsInfected($this->controller) && IsInfected(OtherPlayer($this->controller)) ? 1 : 0;
  }

  function DoesAttackHaveGoAgain() {
    return IsInfected($this->controller) && IsInfected(OtherPlayer($this->controller));
  }

  function SpecialName() {
    return "Virulent Cascade";
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

  function SpecialTalent() {
    return "CHAOS";
  }

  function SpecialType() {
    return "AA";
  }

  function HasStealth() {
    return true;
  }
}

class virulent_cascade_red extends Card {
  function __construct($controller) {
    $this->cardID = "virulent_cascade_red";
    $this->controller = $controller;
    $this->baseCard = new virulent_cascade($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 3;
  }
}

class virulent_cascade_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "virulent_cascade_yellow";
    $this->controller = $controller;
    $this->baseCard = new virulent_cascade($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 2;
  }
}

class virulent_cascade_blue extends Card {
  function __construct($controller) {
    $this->cardID = "virulent_cascade_blue";
    $this->controller = $controller;
    $this->baseCard = new virulent_cascade($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 1;
  }
}

class change_of_mind_blue extends Card {
  // Min Specialization (You may only have this in your deck if your hero is Min.)
  // Flip your hero. If you flip into your △ face, gain {r}{r}. If you flip into your ▽ face, your next attack this turn gets +2{p}.
  function __construct($controller) {
    $this->cardID = "change_of_mind_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $Hero = new CharacterCard(0, $this->controller);
    $HeroObj = GetClass($Hero->CardID(), $this->controller);
    if ($HeroObj != "-") {
      if ($HeroObj->Backside() != "-") {
        WriteLog("I'm not mad, an I?");
        $Hero->Become($HeroObj->Backside());
        AddCurrentTurnEffect($this->cardID, $this->controller);
      }
      elseif ($HeroObj->Frontside() != $Hero->CardID()) {
        WriteLog("I'm a genius!");
        $Hero->Become($HeroObj->Frontside());
        GainResources(2, $this->controller);
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

  function SpecialName() {
    return "Change of Mind";
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
    return "MECHANOLOGIST";
  }

  function SpecialTalent() {
    return "CHAOS";
  }

  function SpecialType() {
    return "I";
  }
}

class eidetic_endpoint extends BaseCard {
  // When this is put face-up into your arsenal, turn all cards in all arsenals face-up.
  // Go again
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddArsenalEffect($index) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    foreach ([$this->controller, OtherPlayer($this->controller)] as $player) {
      $ars = new Arsenal($player);
      for ($i = 0; $i < $ars->NumCards(); ++$i) {
        $arsCard = $ars->Card($i, true);
        $arsCard->Flip();
      }
    }
  }

  function SpecialName() {
    return "Eidetic Endpoint";
  }

  function SpecialCost() {
    return 1;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "RANGER";
  }

  function SpecialTalent() {
    return "CHAOS";
  }

  function SpecialType() {
    return "AA";
  }

  function SpecialSubType() {
    return "Arrow";
  }

  function HasGoAgain($from) {
    return true;
  }
}

class eidetic_endpoint_red extends Card {
  function __construct($controller) {
    $this->cardID = "eidetic_endpoint_red";
    $this->controller = $controller;
    $this->baseCard = new eidetic_endpoint($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 1;
  }

  function SpecialPower() {
    return 4;
  }
}

class eidetic_endpoint_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "eidetic_endpoint_yellow";
    $this->controller = $controller;
    $this->baseCard = new eidetic_endpoint($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialPower() {
    return 3;
  }
}

class eidetic_endpoint_blue extends Card {
  function __construct($controller) {
    $this->cardID = "eidetic_endpoint_blue";
    $this->controller = $controller;
    $this->baseCard = new eidetic_endpoint($this->cardID, $this->controller);
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialPower() {
    return 2;
  }
}

class cogwerx_gyrostriders extends Card {
  // This can't be untapped during your end phase.
  // Instant - {t}: {u} your hero.
  // Instant - Destroy this: The next attack action card you play from arsenal this turn gets go again. Activate this only if you haven't activated this card this turn.
  function __construct($controller) {
    $this->cardID = "cogwerx_gyrostriders";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  // function SpecialName() {
  //   return "Cogwerx Gyrostriders";
  // }

  function SpecialBlock() {
    return 0;
  }

  function SpecialClass() {
    return "MECHANOLOGIST,RANGER";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Legs";
  }
}

class codex_of_exertion_yellow extends Card {
  // Each hero puts a card from their arsenal into their hand. Each hero that does, draws a card.
  // Create a Ponder token under your control and an Exertion token under each opponent's control.
  // Go again
  function __construct($controller) {
    $this->cardID = "codex_of_exertion_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $search = "MYARS";
    $context = "Pick up a card from your arsenal";
    $otherPlayer = OtherPlayer($this->controller);
    foreach ([$this->controller, $otherPlayer] as $player) {
      Await($player, "MultiZoneIndices", search:$search, subsequent:0);
      Await($player, "ChooseMultiZone", context:$context);
      Await($player, $this->cardID, final:true);
    }
    Await($this->controller, "PlayAura", cardID:"ponder", effectSource:$this->cardID, subsequent:0);
    Await($otherPlayer, "PlayAura", cardID:"exertion", effectController:$this->controller, effectSource:$this->cardID, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["MZIndex"] ?? "-";
    $index = explode("-", $choice)[1] ?? -1;
    if ($index != -1) {
      $cardID = RemoveArsenal($this->controller, $index);
      AddPlayerHand($cardID, $this->controller, "ARS");
      Draw($this->controller);
    }
  }

  function SpecialName() {
    return "Codex of Exertion";
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
    return "ASSASSIN,RANGER";
  }

  function SpecialType() {
    return "A";
  }

  function HasGoAgain($from) {
    return true;
  }
}

