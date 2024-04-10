<?php

function CardName($cardID)
{
    if($cardID == NULL) return "";
    $arr = str_split($cardID, 3);
    if(count($arr) < 2) return "";
    $set = $arr[0];
    if($set != "ROG" && $set != "DUM")
    {
      $number = intval(substr($cardID, 3));
      if($number < 400 || ($set != "MON" && $set != "DYN" && $cardID != "EVO410" && $cardID != "EVO410b" && $cardID != "DTD564")) return GeneratedCardName($cardID);
    }
    if ($set == "ROG") {
      return ROGUEName($cardID);
    }
    switch($cardID)
    {
		  case "MON400": return "Spell Fray Cloak";
		  case "MON401": return "Spell Fray Gloves";
		  case "MON402": return "Spell Fray Leggings";
		  case "MON404": return "The Librarian";
		  case "MON405": return "Minerva Themis";
		  case "MON406": return "Lady Barthimont";
		  case "MON407": return "Lord Sutcliffe";
      case "DYN492": return "Nitro Mechanoid";
		  case "DYN492a": return "Nitro Mechanoid";
		  case "DYN492b": return "Nitro Mechanoid";
		  case "DYN492c": return "Nitro Mechanoid";
      case "DYN612": return "Suraya, Archangel of Knowledge";
      case "DUMMY": return "Practice Dummy";
      case "DUMMYDISHONORED": return "Dishonored Hero";
      case "EVO410": return "Teklovossen, the Mechropotent";
      case "EVO410b": return "Teklovossen, the Mechropotent";
      case "DTD564": return "Blasmophet, Levia Consumed";
      case "MST410": case "MST432": case "MST453":
      case "MST496": case "MST497": case "MST498": case "MST499":
      case "MST500": case "MST501": case "MST502":
        return "Inner Chi";
      default: return "";
    }
	}
