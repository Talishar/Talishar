<?php

  include 'UPRIllusionist.php';
  include 'UPRNinja.php';
  include 'UPRWizard.php';
  include 'UPRTalent.php';

  function UPRAbilityCost($cardID)
  {
    global $mainPlayer, $currentPlayer;
    switch($cardID)
    {
      case "UPR044": case "UPR045":
        if($mainPlayer == $currentPlayer) {
          $cost = 3 - NumDraconicChainLinks();
        } else {
          $cost = 3;
        }
        return $cost;
      case "UPR046": return 2;
      case "UPR084": return 1;
      case "UPR136": return 3;
      case "UPR165": return 2;
      case "UPR183": return 2;
      case "UPR551": return 3;
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
      case "UPR084": return "I";
      case "UPR085": return "I";
      case "UPR125": return "I";
      case "UPR136": return "A";
      case "UPR137": return "A";
      case "UPR151": return "A";
      case "UPR159": return "AR";
      case "UPR165": return "I";
      case "UPR166": return "I";
      case "UPR167": return "I";
      case "UPR183": return "I";
      case "UPR551": return "AA";
      default: return "";
    }
  }

  function UPRHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "UPR005": return true;
      case "UPR006": return true;
      case "UPR007": return true;
      case "UPR008": return true;
      case "UPR009": return true;
      case "UPR010": return true;
      case "UPR011": return true;
      case "UPR012": return true;
      case "UPR013": return true;
      case "UPR014": return true;
      case "UPR015": return true;
      case "UPR016": return true;
      case "UPR017": return true;
      case "UPR018": case "UPR019": case "UPR020": return true;
      case "UPR021": case "UPR022": case "UPR023": return true;
      case "UPR030": case "UPR031": case "UPR032": return true;
      case "UPR033": case "UPR034": case "UPR035": return true;
      case "UPR036": case "UPR037": case "UPR038": return true;
      case "UPR049": return true;
      case "UPR051": case "UPR052": case "UPR053": return true;
      case "UPR054": case "UPR055": case "UPR056": return true;
      case "UPR057": case "UPR058": case "UPR059": return true;
      case "UPR060": case "UPR061": case "UPR062": return true;
      case "UPR066": case "UPR067": case "UPR068": return true;
      case "UPR072": case "UPR073": case "UPR074": return true;
      case "UPR075": case "UPR076": case "UPR077": return true;
      case "UPR078": case "UPR079": case "UPR080": return true;
      case "UPR081": case "UPR082": case "UPR083": return true;
      case "UPR088": return true;
      case "UPR095": return true;
      case "UPR096": return true;
      case "UPR097": return true;
      case "UPR101": return true;
      case "UPR138": return true;
      case "UPR141": case "UPR142": case "UPR143": return true;
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
      case "UPR137": return true;
      case "UPR151": return true;
      default: return false;
    }
  }

  function UPREffectAttackModifier($cardID)
  {
    $params = explode("-", $cardID);
    $cardID = $params[0];
    if(count($params) > 1) $subparam = $params[1];
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
      case "UPR151": return $subparam;
      case "UPR158": return 1;
      case "UPR191": case "UPR192": case "UPR193": return 2;
      default: return 0;
    }
  }

  function UPRCombatEffectActive($cardID, $attackID)
  {
    global $mainPlayer;
    $params = explode("-", $cardID);
    $cardID = $params[0];
    switch($cardID)
    {
      case "UPR036": case "UPR037": case "UPR038": return true;
      case "UPR047": return $attackID == "UPR101";
      case "UPR049": return TalentContains($attackID, "DRACONIC", $mainPlayer) && AttackValue($attackID) < NumDraconicChainLinks();
      case "UPR054": case "UPR055": case "UPR056": return true;
      case "UPR057": case "UPR058": case "UPR059": return cardType($attackID) == "AA" && (TalentContains($attackID, "DRACONIC", $mainPlayer) || ClassContains($attackID, "NINJA", $mainPlayer));
      case "UPR060": case "UPR061": case "UPR062": return true;
      case "UPR081": case "UPR082": case "UPR083": return true;
      case "UPR088": return TalentContains($attackID, "DRACONIC", $mainPlayer);
      case "UPR091": return true;
      case "UPR094": return true;
      case "UPR151": return $attackID == "UPR551";
      case "UPR154": return ClassContains($attackID, "ILLUSIONIST", $mainPlayer);
      case "UPR155": case "UPR156": case "UPR157": return CardType($attackID) == "AA";
      case "UPR158": return true;
      case "UPR191": case "UPR192": case "UPR193": return true;
      case "UPR412": return true;
      default: return false;
    }
  }

  function UPRCardTalent($cardID)
  {
    $number = intval(substr($cardID, 3));
    if($number <= 0) return "DRACONIC";
    else if($number >= 1 && $number <= 101) return "DRACONIC";
    else if($number >= 102 && $number <= 124) return "ELEMENTAL";
    else if($number >= 125 && $number <= 150) return "ICE";
    else if($number >= 406 && $number <= 417 ) return "DRACONIC";
    else if($number >= 439 && $number <= 441) return "DRACONIC";
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

function UPRDealDamageEffect($cardID)
{
  $class = CardClass($cardID);
  switch ($class) {
    case "ILLUSIONIST":
      return UPRIllusionistDealDamageEffect($cardID);
    default:
      return UPRTalentHitEffect($cardID);
  }
}

  function QuellAmount($cardID)
  {
    switch($cardID)
    {
      case "UPR004": return 1;
      case "UPR047": return 1;
      case "UPR125": return 1;
      case "UPR184": return 1;
      case "UPR185": return 1;
      case "UPR186": return 1;
      default: return 0;
    }
  }

  function QuellChoices($player, $damage)
  {
    $character = &GetPlayerCharacter($player);
    $quellAmount = 0;
    for($i=0; $i<count($character); $i+=CharacterPieces())
    {
      if($character[$i+1] == "0" || $character[$i+9] == "0") continue;
      $quellAmount += QuellAmount($character[$i]);
    }
    if($quellAmount > $damage) $quellAmount = $damage;
    $rv = "0";
    for($i=1; $i<=$quellAmount; ++$i)
    {
      $rv .= "," . $i;
    }
    return $rv;
  }

  function QuellEndPhase($player)
  {
    global $CS_MaxQuellUsed;
    $maxUsed = GetClassState($player, $CS_MaxQuellUsed);
    for($i=0; $i<$maxUsed; ++$i)
    {
      AddDecisionQueue("FINDINDICES", $player, "QUELL");
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose " . ($maxUsed - $i) . " quell cards to destroy");
      AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MULTIZONEDESTROY", $player, "-", 1);
    }
  }

  function QuellIndices($player)
  {
    $character = &GetPlayerCharacter($player);
    $indices = "";
    for($i=0; $i<count($character); $i+=CharacterPieces())
    {
      if($character[$i+1] == "0") continue;
      if(QuellAmount($character[$i]) > 0)
      {
        if($indices != "") $indices .= ",";
        $indices .= SearchMultizoneFormat($i, "MYCHAR");
      }
    }
    return $indices;
  }

?>
