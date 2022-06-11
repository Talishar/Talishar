<?php

  include 'UPRIllusionist.php';
  include 'UPRNinja.php';
  include 'UPRWizard.php';
  include 'UPRTalent.php';

  function UPRAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "UPR044": case "UPR045": $cost = 3 - NumDraconicChainLinks(); return ($cost < 0 ? 0 : $cost);
      case "UPR046": return 2;
      case "UPR165": return 2;
      case "UPR183": return 2;
      default: return 0;
    }
  }

  function UPRAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "UPR004": return "I";
      case "UPR044": case "UPR045": return "I";
      case "UPR046": return "AA";
      case "UPR047": return "I";
      case "UPR085": return "I";
      case "UPR159": return "AR";
      case "UPR165": return "I";
      case "UPR167": return "I";
      case "UPR183": return "I";
      default: return "";
    }
  }

  function UPRHasGoAgain($cardID)
  {
    global $mainPlayer, $CS_NumRedPlayed;
    switch($cardID)
    {
      case "UPR005": return true;
      case "UPR006": return true;
      case "UPR007": return true;
      case "UPR008": return true;
      case "UPR009": return true;
      case "UPR010": return true;
      case "UPR011": return true;
      case "UPR013": return true;
      case "UPR015": return true;
      case "UPR016": return true;
      case "UPR017": return true;
      case "UPR018": case "UPR019": case "UPR020": return true;
      case "UPR021": case "UPR022": case "UPR023": return true;
      case "UPR030": case "UPR031": case "UPR032": return true;
      case "UPR033": case "UPR034": case "UPR035": return true;
      case "UPR036": case "UPR037": case "UPR038": return true;
      case "UPR046": return (NumDraconicChainLinks() >= 2 ? true : false);
      case "UPR048": return (NumPhoenixFlameChainLinks() >= 1 ? true : false);
      case "UPR049": return true;
      case "UPR051": case "UPR052": case "UPR053": return true;
      case "UPR054": case "UPR055": case "UPR056": return true;
      case "UPR057": case "UPR058": case "UPR059": return true;
      case "UPR060": case "UPR061": case "UPR062": return true;
      case "UPR063": case "UPR064": case "UPR065": return (NumDraconicChainLinks() >= 2 ? true : false);
      case "UPR066": case "UPR067": case "UPR068": return true;
      case "UPR069": case "UPR070": case "UPR071": return (NumDraconicChainLinks() >= 2 ? true : false);
      case "UPR072": case "UPR073": case "UPR074": return true;
      case "UPR075": case "UPR076": case "UPR077": return true;
      case "UPR078": case "UPR079": case "UPR080": return true;
      case "UPR081": case "UPR082": case "UPR083": return true;
      case "UPR088": return true;
      case "UPR092": return GetClassState($mainPlayer, $CS_NumRedPlayed) > 1;
      case "UPR095": return true;
      case "UPR096": return true;
      case "UPR097": return true;
      case "UPR101": return true;
      case "UPR147": case "UPR148": case "UPR149": return true;
      case "UPR155": case "UPR156": case "UPR157": return true;
      case "UPR160": return true;
      case "UPR197": case "UPR198": case "UPR199": return true;
      case "UPR200": case "UPR201": case "UPR202": return true;
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
      case "UPR049": return 1;
      case "UPR054": case "UPR055": case "UPR056": return 1;
      case "UPR057": return 3;
      case "UPR058": return 2;
      case "UPR059": return 1;
      case "UPR088": return 1;
      case "UPR091": return NumPhoenixFlameChainLinks()*2;
      case "UPR094": return 2;
      default: return 0;
    }
  }

  function UPRCombatEffectActive($cardID, $attackID)
  {
    switch($cardID)
    {
      case "UPR036": case "UPR037": case "UPR038": return true;
      case "UPR047": return $attackID == "UPR101";
      case "UPR049": return CardTalent($attackID) == "DRACONIC" && AttackValue($attackID) < NumDraconicChainLinks();
      case "UPR054": case "UPR055": case "UPR056": return true;
      case "UPR057": case "UPR058": case "UPR059": CardTalent($attackID) == "DRACONIC" || CardClass($attackID) == "NINJA";
      case "UPR081": case "UPR082": case "UPR083": return true;
      case "UPR088": return CardTalent($attackID) == "DRACONIC";
      case "UPR091": return true;
      case "UPR094": return true;
      case "UPR155": case "UPR156": case "UPR157": return CardType($attackID) == "AA";
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
      default: return UPRTalentCardType($cardID);
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
      default: return UPRTalentCardSubtype($cardID);
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
      default: return UPRTalentCardCost($cardID);
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
      default: return UPRTalentPitchValue($cardID);
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
      default: return UPRTalentBlockValue($cardID);
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
      default: return UPRTalentAttackValue($cardID);
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
      default: return UPRTalentPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
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
      default: return UPRTalentHitEffect($cardID);
    }
  }

  function UPRFuseAbility($cardID, $player, $element)
  {
    switch($cardID)
    {
      case "UPR104":
        AddDecisionQueue("LESSTHANPASS", $player, 1);
        break;
      default: break;
    }
  }

?>
