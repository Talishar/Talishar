<?php

function CardName($cardID)
{
    $arr = str_split($cardID, 3);
    if(count($arr) < 2) return "";
    $set = $arr[0];
    if($set != "ROG" && $set != "DUM")
    {
      $number = intval(substr($cardID, 3));
      if($number < 400) return GeneratedCardName($cardID);
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
      case "UPR406": return "Dracona Optimai";
      case "UPR407": return "Tomeltai";
      case "UPR408": return "Dominia";
      case "UPR409": return "Azvolai";
      case "UPR410": return "Cromai";
      case "UPR411": return "Kyloria";
      case "UPR412": return "Miragai";
      case "UPR413": return "Nekria";
      case "UPR414": return "Ouvia";
      case "UPR415": return "Themai";
      case "UPR416": return "Vynserakai";
      case "UPR417": return "Yendurai";
      case "UPR551": return "Ghostly Touch";
      case "DYN492": return "Nitro Mechanoid";
		  case "DYN492a": return "Nitro Mechanoid";
		  case "DYN492b": return "Nitro Mechanoid";
		  case "DYN492c": return "Nitro Mechanoid";
      case "DYN612": return "Suraya, Archangel of Knowledge";
      case "DUMMY": return "Practice Dummy";
      default: return "";
    }
    return "";
	}
