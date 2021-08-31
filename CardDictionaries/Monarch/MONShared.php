<?php

  function MONAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "MON029": case "MON030": case "MON031": return 0;
      case "MON238": case "MON239": return 0;
      case "MON240": return 3;
      default: return 0;
    }
  }

  function MONAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "MON029": case "MON030": return "AR";
      case "MON031": return "AA";
      case "MON238": return "I";
      case "MON239": case "MON240": return "A";
      default: return "";
    }
  }

  function MONHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "MON034": return true;
      case "MON260": case "MON261": case "MON262": return true;
      case "MON266": case "MON267": case "MON268": return true;
      case "MON269": case "MON270": case "MON271": return true;
      case "MON296": case "MON297": case "MON298": return true;
      case "MON299": case "MON300": case "MON301": return true;
      case "MON302": return true;
      default: return false;
    }
  }

  function MONAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "MON239": return true;
      default: return false;
    }
  }

  function MONEffectAttackModifier($cardID)
  {
    switch($cardID)
    {
      case "MON034": return 1;
      case "MON239": return 1;
      case "MON260-1": case "MON261-1": case "MON262-1": return 2;
      case "MON263": case "MON264": case "MON265": return 3;
      case "MON269": case "MON270": case "MON271": return 1;
      case "MON296": return 3;
      case "MON297": return 2;
      case "MON298": return 1;
      case "MON299": return 3;
      case "MON300": return 2;
      case "MON301": return 1;
      default: return 0;
    }
  }

  function MONCombatEffectActive($cardID, $attackID)
  {
    switch($cardID)
    {
      case "MON034": return CardType($attackID) == "W";
      case "MON239": return CardType($attackID) == "AA" && AttackValue($attackID) <= 3;
      case "MON260-1": case "MON260-2": return CardType($attackID) == "AA" && CardCost($attackID) <= 2;
      case "MON261-1": case "MON261-2": return CardType($attackID) == "AA" && CardCost($attackID) <= 1;
      case "MON262-1": case "MON262-2": return CardType($attackID) == "AA" && CardCost($attackID) <= 0;
      case "MON263": case "MON264": case "MON265": return true;
      case "MON269": case "MON270": case "MON271": return CardType($attackID) == "W";
      case "MON278": case "MON279": case "MON280": return true;
      case "MON296": case "MON297": case "MON298": return CardType($attackID) == "AA" && AttackValue($attackID) <= 3;
      case "MON299": case "MON300": case "MON301": return CardType($attackID) == "AA";
      default: return false;
    }
  }

  function CardTalent($cardID)
  {
    $number = intval(substr($cardID, 3));
    if($number <= 87) return "LIGHT";
    else if($number >= 119 && $number <= 220) return "SHADOW";
    else return "NONE";
  }

?>

