<?php

function CardName($cardID)
{
    if($cardID == NULL) return "";
    if($cardID == "ATKCOU") return "+1 Attack Counter";
    switch ($cardID) {
      case "valda_seismic_impact":
        return "Valda, Seismic Impact";
      case "silversheen_needle":
        return "Silversheen Needle";
      case "fabric_of_spring_yellow":
        return "Fabric of Spring";
      case "venomback_fabric_yellow":
        return "Venomback Fabric";
      case "taylor":
        return "Taylor";
      default:
        break;
    }
    $setID = SetID($cardID);
    $arr = str_split($setID, 3);
    if(count($arr) < 2) return "";
    $set = $arr[0];
    if($set != "ROG" && $set != "DUM")
    {
      return GeneratedCardName($cardID);
      // $number = intval(substr($setID, 3));
      // if($number < 400 || ($set != "MON" && $set != "DYN" && $set != "MST" && $set != "HNT" && $cardID != "teklovossen_the_mechropotent" && $cardID != "teklovossen_the_mechropotentb" && $cardID != "levia_redeemed")) return GeneratedCardName($cardID);
    }
    if ($set == "ROG") {
      return ROGUEName($cardID);
    }
    switch($cardID)
    {
      case "DUMMY": return "Practice Dummy";
      case "DUMMYDISHONORED": return "Dishonored Hero";
      default: return "";
    }
	}

//checks if meld cards share a name
function ShareName($name1, $name2) {
  if ($name1 == $name2) return true;
  foreach (explode(" // ", $name1) as $n1) {
    foreach (explode(" // ", $name2) as $n2) {
      if ($n1 == $n2) return true;
    }
  }
  return false;
}