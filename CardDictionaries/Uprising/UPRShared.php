<?php

  include 'UPRIllusionist.php';
  include 'UPRNinja.php';
  include 'UPRWizard.php';
  include 'UPRTalent.php';

  function UPRAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "UPR004": return 0;
      case "UPR044": case "UPR045": $cost = 3 - NumDraconicChainLinks(); return ($cost < 0 ? 0 : $cost);
      case "UPR047": return 0;
      default: return 0;
    }
  }

  function UPRAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "UPR004": return "I";
      case "UPR044": case "UPR045": return "I";
      case "UPR047": return "I";
      default: return "";
    }
  }

  function UPRHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "UPR005": return true;
      case "UPR008": return true;
      case "UPR009": return true;
      case "UPR011": return true;
      case "UPR018": case "UPR019": case "UPR020": return true;
      case "UPR033": case "UPR034": case "UPR035": return true;
      case "UPR036": case "UPR037": case "UPR038": return true;
      case "UPR048": return (NumPhoenixFlameChainLinks() >= 1 ? true : false);
      case "UPR057": case "UPR058": case "UPR059": return true;
      case "UPR088": return true;
      case "UPR095": return true;
      case "UPR097": return true;
      case "UPR101": return true;
      case "UPR147": case "UPR148": case "UPR149": return true;
      default: return false;
    }
  }

  function UPRAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {

      default: return false;
    }
  }

  function UPREffectAttackModifier($cardID)
  {
    switch($cardID)
    {
      case "UPR036": return 3;
      case "UPR037": return 2;
      case "UPR038": return 1;
      case "UPR047": return 1;
      case "UPR057": return 3;
      case "UPR058": return 2;
      case "UPR059": return 1;
      case "UPR088": return 1;
      default: return 0;
    }
  }

  function UPRCombatEffectActive($cardID, $attackID)
  {
    switch($cardID)
    {
      case "UPR036": case "UPR037": case "UPR038": return true;
      case "UPR047": return $attackID == "UPR101";
      case "UPR057": case "UPR058": case "UPR059": CardTalent($attackID) == "DRACONIC" || CardClass($attackID) == "NINJA";
      case "UPR088": return CardTalent($attackID) == "DRACONIC";
      default: return false;
    }
  }

  function UPRCardTalent($cardID)
  {
    $number = intval(substr($cardID, 3));
    if($number <= 0) return "??";
    else if($number >= 1 && $number <= 101) return "DRACONIC";//Is this right?
    else if($number >= 102 && $number <= 111) return "ELEMENTAL";//Is this right?
    else if($number == 112) return "LIGHTNING,ICE";
    else if($number == 113) return "LIGHTNING,EARTH";
    else if($number == 114) return "ICE,EARTH";
    else if($number >= 115 && $number <= 143) return "EARTH";
    else if($number >= 144 && $number <= 172) return "ICE";
    else if($number >= 173 && $number <= 201) return "LIGHTNING";
    else return "NONE";
  }




  function UPRCardType($cardID)
  {
    $class = CardClass($cardID);
    switch($class)
    {
      case "ILLUSIONIST": return UPRIllusionistCardType($cardID);
      case "NINJA": return UPRNinjaCardType($cardID);
      case "WIZARD": return UPRWizardCardType($cardID);
      case "NONE": return UPRTalentCardType($cardID);
      default: return "";
    }
  }

  function UPRCardSubType($cardID)
  {
    $class = CardClass($cardID);
    switch($class)
    {
      case "ILLUSIONIST": return UPRIllusionistCardSubtype($cardID);
      case "NINJA": return UPRNinjaCardSubtype($cardID);
      case "WIZARD": return UPRWizardCardSubtype($cardID);
      case "NONE": return UPRTalentCardSubtype($cardID);
      default: return "";
    }
  }

  //Minimum cost of the card
  function UPRCardCost($cardID)
  {
    $class = CardClass($cardID);
    switch($class)
    {
      case "ILLUSIONIST": return UPRIllusionistCardCost($cardID);
      case "NINJA": return UPRNinjaCardCost($cardID);
      case "WIZARD": return UPRWizardCardCost($cardID);
      case "NONE": return UPRTalentCardCost($cardID);
      default: return "";
    }
  }

  function UPRPitchValue($cardID)
  {
    $class = CardClass($cardID);
    switch($class)
    {
      case "ILLUSIONIST": return UPRIllusionistPitchValue($cardID);
      case "NINJA": return UPRNinjaPitchValue($cardID);
      case "WIZARD": return UPRWizardPitchValue($cardID);
      case "NONE": return UPRTalentPitchValue($cardID);
      default: return "";
    }
  }

  function UPRBlockValue($cardID)
  {
    $class = CardClass($cardID);
    switch($class)
    {
      case "ILLUSIONIST": return UPRIllusionistBlockValue($cardID);
      case "NINJA": return UPRNinjaBlockValue($cardID);
      case "WIZARD": return UPRWizardBlockValue($cardID);
      case "NONE": return UPRTalentBlockValue($cardID);
      default: return "";
    }
  }

  function UPRAttackValue($cardID)
  {
    $class = CardClass($cardID);
    switch($class)
    {
      case "ILLUSIONIST": return UPRIllusionistAttackValue($cardID);
      case "NINJA": return UPRNinjaAttackValue($cardID);
      case "WIZARD": return UPRWizardAttackValue($cardID);
      case "NONE": return UPRTalentAttackValue($cardID);
      default: return "";
    }
  }

  function UPRPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer;
    $rv = "";
    $class = CardClass($cardID);
    switch($class)
    {
      case "ILLUSIONIST": return UPRIllusionistPlayAbility($cardID, $from, $resourcesPaid);
      case "NINJA": return UPRNinjaPlayAbility($cardID, $from, $resourcesPaid);
      case "WIZARD": return UPRWizardPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "NONE": return UPRTalentPlayAbility($cardID, $from, $resourcesPaid);
      default: return "";
    }
  }

  function UPRHitEffect($cardID)
  {
    $class = CardClass($cardID);
    switch($class)
    {
      case "ILLUSIONIST": return UPRIllusionistHitEffect($cardID);
      case "NINJA": return UPRNinjaHitEffect($cardID);
      case "WIZARD": return UPRWizardHitEffect($cardID);
      case "NONE": return UPRTalentHitEffect($cardID);
      default: return "";
    }
  }

  function UPRFuseAbility($cardID, $player, $element)
  {
    switch($cardID)
    {
      case "UPR104":
        AddDecisionQueue("LESSTHANPASS", $player, 1);
        AddDecisionQueue("WRITELOG", $player, "got here", 1);
        break;
      default: break;
    }
  }

?>
