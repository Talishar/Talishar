<?php

  include "Constants.php";

  function CardType($cardID)
  {
    switch($cardID)
    {
      case "WTR002": case "WTR039": return "C";
      case "WTR003": case "WTR040": return "W";
      case "WTR155": case "WTR156": case "WTR157": case "WTR158": return "E";
      case "WTR206": case "WTR207": return "AR";
      case "WTR067":  case "WTR066":  case "WTR068":  case "WTR060":  case "WTR061":  case "WTR062":  case "WTR065": case "WTR190": case "WTR050": case "WTR059": return "AA";
      case "ARC159": case "CRU016": case "CRU018": case "WTR006": case "WTR023": case "WTR024": case "WTR025": case "WTR026": case "WTR027": case "WTR028": return "AA";
      case "WTR029": case "WTR030": case "WTR031": case "WTR161": return "AA";
      case "WTR051":  case "WTR215": case "WTR214": case "WTR213": case "ARC200": case "ARC201": case "ARC202": return "DR";
      case "WTR069":  case "WTR072": case "WTR054": case "WTR223": case "WTR017": case "WTR018": case "WTR019": case "WTR032": case "WTR037": return "A";
    }
  }

  function CardSubType($cardID)
  {
    switch($cardID)
    {
      case "WTR069":  case "WTR072": case "WTR054": return "Aura";
      default: return "";
    }
  }

  function CardClass($cardID)
  {
    $set = substr($cardID, 0, 3);
    $number = intval(substr($cardID, 3));
    switch($set)
    {
      case "WTR":
        if($number >= 1 && $number <= 37) return "BRUTE";
        else if($number >= 38 && $number <= 75) return "GUARDIAN";
        else if($number >= 76 && $number <= 112) return "NINJA";
        else if($number >= 113 && $number <= 149) return "WARRIOR";
        else return "GENERIC";
      case "ARC":
        if($number == 0) return "GENERIC";
        else if($number >= 1 && $number <= 37) return "MECHANOLOGIST";
        else if($number >= 38 && $number <= 74) return "RANGER";
        else if($number >= 75 && $number <= 112) return "RUNEBLADE";
        else if($number >= 113 && $number <= 149) return "WIZARD";
        else return "GENERIC";
      case "CRU":
        if($number == 0) return "GENERIC";
        else if($number >= 1 && $number <= 21) return "BRUTE";
        else if($number >= 22 && $number <= 44) return "GUARDIAN";
        else if($number >= 45 && $number <= 75) return "NINJA";
        else if($number >= 76 && $number <= 96) return "WARRIOR";
        else if($number == 97) return "SHAPESHIFTER";
        else if($number >= 98 && $number <= 117) return "MECHANOLOGIST";
        else if($number == 118) return "MERCHANT";
        else if($number >= 119 && $number <= 137) return "RANGER";
        else if($number >= 138 && $number <= 157) return "RUNEBLADE";
        else if($number >= 158 && $number <= 176) return "WIZARD";
        else return "GENERIC";
      default: return 0;
    }
  }

  //Minimum cost of the card
  function CardCost($cardID)
  {
    switch($cardID)
    {
      case "WTR003": case "WTR039": return 2;//TODO: Change ability costs to a different function
      case "WTR040": return 3;
      case "WTR035": case "WTR036": case "WTR037": case "WTR017": case "WTR018": case "WTR019": case "WTR215": return 0;
      case "ARC159": case "WTR206": case "WTR207": case "WTR051":  case "WTR069":  case "WTR072": case "WTR054": return 2;
      case "WTR029": case "WTR030": case "WTR031": case "WTR023": case "WTR024": case "WTR025": return 2;
      case "WTR060":  case "WTR061":  case "WTR062":  case "WTR065": case "WTR223": case "WTR190": case "WTR214": case "WTR213": return 3;
      case "WTR161": case "WTR026": case "WTR027": case "WTR028": case "WTR006": case "CRU017": case "CRU018": case "CRU019": return 3;
      case "WTR067":  case "WTR066":  case "WTR068":  case "WTR059": return 4;
      case "WTR032": case "WTR033": case "WTR034": return 1;
      case "WTR050": return 5;
    }
  }

  function DynamicCost($cardID)
  {
    switch($cardID)
    {
      case "WTR051": case "WTR052": case "WTR053": return "2,6";
      default:
        return "";
    }
  }

  function PitchValue($cardID)
  {
    switch($cardID)
    {
       case "WTR002": case "WTR003": case "WTR039": case "WTR040": case "WTR155": case "WTR156": case "WTR157": case "WTR158": return 0;
       case "ARC159": case "ARC200": case "WTR206": case "WTR066": case "WTR051": case "WTR069": case "WTR060": case "WTR215": case "WTR072": case "WTR054": return 1;
       case "WTR032": case "WTR026": case "WTR023": case "WTR006": case "WTR017": return 1;
       case "WTR030": case "WTR027": case "WTR024": case "WTR018": case "CRU017": case "WTR207": case "WTR067": case "WTR061": case "WTR213": return 2;
       default: return 3;
    }
  }

  function BlockValue($cardID)
  {
    switch($cardID)
    {
      case "WTR002": case "WTR003": case "WTR039": case "WTR040": return 0;
      case "WTR155": case "WTR156": case "WTR157": case "WTR158": return 1;
      case "WTR206": case "WTR207": case "WTR223": return 2;
      case "WTR215": case "ARC200": return 4;
      case "WTR214": return 5;
      case "WTR213": return 6;
      case "WTR051":  return 7;
      default: return 3;
    }
  }

  function AttackValue($cardID)
  {
    //global $
    switch($cardID)
    {
      case "WTR040": return 4;
      case "WTR206": return 4;
      case "WTR207": return 3;
      case "WTR067":  return 7;
      case "WTR066":  return 8;
      case "WTR068":  return 6;
      case "WTR060":  return 7;
      case "WTR061":  return 6;
      case "WTR062":  return 5;
      case "WTR065": return 5;
      case "WTR190": return 5;
      case "WTR050": return 7;
      case "WTR059": return 6;
      case "WTR069": return 3;
      case "WTR070": return 2;
      case "WTR071": return 1;
      case "ARC159": return 6;
      case "CRU017": return 6;
      case "CRU018": return 5;
      case "WTR006": return 9;
      case "WTR023": return 6;
      case "WTR024": return 5;
      case "WTR025": return 4;
      case "WTR026": return 7;
      case "WTR027": return 6;
      case "WTR028": return 5;
      case "WTR030": return 7;
      case "WTR031": return 6;
      case "WTR161": return 4;
      case "WTR003": return 4;
      default: return 0;
    }
  }

  function HasGoAgain($cardID)
  {
    global $myDeck;
    switch($cardID)
    {
      case "WTR039": case "WTR072": case "WTR054": case "WTR069":  return true;
      case "WTR223": case "WTR222": case "WTR221": return true;
      case "WTR017": case "WTR018": case "WTR019": return true;
      case "WTR032": case "WTR033": case "WTR034": return true;
      case "WTR035": case "WTR036": case "WTR037": return true;
      case "WTR161": return count($myDeck) == 0;
      default: return false;
    }
  }

  function GetAbilityType($cardID)
  {
    switch($cardID)
    {
      case "WTR003": return "AA";
      case "WTR039": return "A";
      case "WTR040": return "AA";
      default: return "";
    }
  }

  function IsPlayable($cardID, $phase, $from)
  {
    global $myHand;
    $cardType = CardType($cardID);
    if($phase == "B" && $cardType != "DR") return BlockValue($cardID);
    if($phase == "P" && PitchValue($cardID) > 0) return true;
    if(IsStaticType($cardType))
    {
      $cardType = GetAbilityType($cardID);
    }
    switch($cardType)
    {
      case "A": return $phase == "M";
      case "AA": return $phase == "M";
      case "AR": return $phase == "A";
      case "DR": return $phase == "D" && IsDefenseReactionPlayable($cardID);
      default: return false;
    }
    if(RequiresDiscard($cardID))
    {
      if($from == "HAND" && count($myHand) < 2) return false;//TODO: Account for where it was from
      else if(count($myHand) < 1) return false;
    }
  }

  function IsDefenseReactionPlayable($cardID)
  {
    global $combatChain;
    if($combatChain[0] == "ARC159") return false;
    return true;
  }

  function IsAction($cardID)
  {
    $cardType = CardType($cardID);
    if($cardType == "A" || $cardType == "AA") return true;
    $abilityType = GetAbilityType($cardID);
    if($abilityType == "A" || $abilityType == "AA") return true;
    return false;
  }

  function GoesOnCombatChain($phase, $cardID)
  {
    $cardType = CardType($cardID);
    if($cardType == "I") return false;//Instants as yet never go on the combat chain
    if($phase == "B" || $phase == "A" || $phase == "D") return true;//Anything you play during these combat phases would go on the chain
    if($phase == "M" && ($cardType == "AA" || GetAbilityType($cardID) == "AA")) return true;//If it's an attack action, it goes on the chain
    return false;
  }

  function IsStaticType($cardType)
  {
    if($cardType == "C" || $cardType == "E" || $cardType == "W") return true;
    return false;
  }

  function HasBladeBreak($cardID)
  {
    switch($cardID)
    {
      case "WTR155": case "WTR156": case "WTR157": case "WTR158": return true;
      default: return false;
    }
  }

  function CombatChainPieces()
  {
    return 4;
  }

  function RequiresDiscard($cardID)
  {
    switch($cardID)
    {
      case "WTR006": return true;
      default: return false;
    }
  }

?>

