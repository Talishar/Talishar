<?php

function CardName($cardID)
{
    $cardID = BlindCard($cardID, true);
    if($cardID == NULL) return "";
    if($cardID == "POWERCOUNTER") return "+1 Power Counter";
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
      case "polly_cranka":
        return "Polly Cranka";
      case "DUMMY":
        return "Practice Dummy";
      case "DUMMYDISHONORED":
        return "Dishonored Hero";
      case "tusk": // AI custom weapon
        return "Tusk";
      case "wrenchtastic": // AI custom weapon
        return "Wrench-tastic!";
      case "consign_to_cosmos__shock_yellow":
        return "Consign To Cosmos // Shock";
      default:
        break;
    }
    // $setID = SetID($cardID);
    // $arr = str_split($setID, 3);
    // if(count($arr) < 2) return "";
    // $set = $arr[0];
    $set = CardSet($cardID);
    if($set != "DUM")
    {
      return GeneratedCardName($cardID);
    }
	}

//checks if meld cards share a name
function ShareName($name1, $name2) {
  if ($name1 == $name2) return true;
  if (str_contains($name1, " // ") && !str_contains($name2, " // ")) {
    foreach (explode(" // ", $name1) as $n1) {
      if ($n1 == $name2) {
        return true;
      }
    }
  }
  return false;
}