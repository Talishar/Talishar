<?php

  function ELEAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "ELE000": return 0;
      case "ELE001": case "ELE002": return 3;
      case "ELE003": return 3;
      case "ELE031": case "ELE032": return 0;
      case "ELE033": case "ELE034": return 1;
      case "ELE115": return 1;
      case "ELE116": return 3;
      case "ELE144": return 1;
      case "ELE145": return 0;
      case "ELE173": return 2;
      case "ELE195": case "ELE196": case "ELE197": return 2;
      case "ELE202": return 3;
      case "ELE222": case "ELE223": case "ELE224": case "ELE225": return 1;
      default: return 0;
    }
  }

  function ELEAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "ELE000": return "I";
      case "ELE001": case "ELE002": return "DR";
      case "ELE003": return "AA";
      case "ELE031": case "ELE032": return "A";
      case "ELE033": case "ELE034": return "I";
      case "ELE115": return "I";
      case "ELE116": return "I";
      case "ELE143": return "I";
      case "ELE144": return "A";
      case "ELE145": return "A";
      case "ELE172": return "I";
      case "ELE173": return "I";
      case "ELE195": case "ELE196": case "ELE197": return "I";
      case "ELE201": return "I";
      case "ELE202": return "AA";
      case "ELE214": return "I";
      case "ELE222": case "ELE223": return "AA";
      case "ELE224": return "I";
      case "ELE225": return "AR";
      case "ELE233": case "ELE236": return "I";
      case "ELE234": case "ELE235": return "A";
      default: return "";
    }
  }

  function ELEHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "ELE000": return true;
      case "ELE025": case "ELE026": case "ELE027": return true;
      case "ELE028": case "ELE029": case "ELE030": return true;
      case "ELE037": return true;
      case "ELE065": case "ELE066": return true;
      case "ELE085": case "ELE086": case "ELE087": return true;
      case "ELE091": case "ELE092": return true;
      case "ELE103": case "ELE104": case "ELE105": return true;
      case "ELE113": return true;
      case "ELE117": return true;
      case "ELE118": return true;
      case "ELE122": case "ELE123": case "ELE124": return true;
      case "ELE143": return true;
      case "ELE146": return true;
      case "ELE151": case "ELE152": case "ELE153": return true;
      case "ELE154": case "ELE155": case "ELE156": return true;
      case "ELE163": case "ELE164": case "ELE165": return true;
      case "ELE166": case "ELE167": case "ELE168": return true;
      case "ELE172": return true;
      case "ELE175": return true;
      case "ELE137": case "ELE138": case "ELE139": return true;
      case "ELE140": case "ELE141": case "ELE142": return true;
      case "ELE151": case "ELE152": case "ELE153": return true;
      case "ELE166": case "ELE167": case "ELE168": return true;
      case "ELE169": case "ELE170": case "ELE171": return true;
      case "ELE177": case "ELE178": case "ELE179": return true;
      case "ELE180": case "ELE181": case "ELE182": return true;
      case "ELE186": case "ELE187": case "ELE188": return true;
      case "ELE198": case "ELE199": case "ELE200": return true;
      case "ELE201": return true;
      case "ELE205": return true;
      case "ELE206": case "ELE207": case "ELE208": return true;
      case "ELE215": return true;
      case "ELE219": case "ELE220": case "ELE221": return true;
      case "ELE226": return true;
      default: return false;
    }
  }

  function ELEAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "ELE031": case "ELE032": return true;
      case "ELE144": return true;
      case "ELE145": return true;
      case "ELE234": case "ELE235": return true;
      default: return false;
    }
  }

  function ELEEffectAttackModifier($cardID)
  {
    global $combatChainState, $CCS_AttackFused;
    switch($cardID)
    {
      case "ELE000-1": return 1;
      case "ELE005": return 2;
      case "ELE013": case "ELE014": case "ELE015": return -2;
      case "ELE025": case "ELE028": return 3;
      case "ELE026": case "ELE029": return 2;
      case "ELE027": case "ELE030": return 1;
      case "ELE033-1": return 1;
      case "ELE034-1": return 1;
      case "ELE035-2": return 1;
      case "ELE037-1": return 3;
      case "ELE066": return 1;
      case "ELE067": case "ELE068": case "ELE069": return 1;
      case "ELE082": case "ELE083": case "ELE084": return 2;
      case "ELE085-FUSE": return 3;
      case "ELE086-FUSE": return 2;
      case "ELE087-FUSE": return 1;
      case "ELE091-BUFF": return 3;
      case "ELE103": return 4;
      case "ELE104": return 3;
      case "ELE105": return 2;
      case "ELE112": return 4;
      case "ELE122": return 3 + ($combatChainState[$CCS_AttackFused] ? 1 : 0);
      case "ELE123": return 2 + ($combatChainState[$CCS_AttackFused] ? 1 : 0);
      case "ELE124": return 1 + ($combatChainState[$CCS_AttackFused] ? 1 : 0);
      case "ELE137": return 5;
      case "ELE138": return 4;
      case "ELE139": return 3;
      case "ELE143": return 1;
      case "ELE151": return 3;
      case "ELE152": return 2;
      case "ELE153": return 1;
      case "ELE154": return 3;
      case "ELE155": return 2;
      case "ELE156": return 1;
      case "ELE180": return 3;
      case "ELE181": return 2;
      case "ELE182": return 1;
      case "ELE205": return 1;
      case "ELE206": return 5;
      case "ELE207": return 4;
      case "ELE208": return 3;
      case "ELE215": return 3;
      case "ELE219": return 4;
      case "ELE220": return 3;
      case "ELE221": return 2;
      case "ELE235": return 1;
      default: return 0;
    }
  }

  function ELECombatEffectActive($cardID, $attackID)
  {
    global $combatChainState, $CCS_AttackFused;
    switch($cardID)
    {
      case "ELE000-1": case "ELE000-2": return CardType($attackID) == "AA";
      case "ELE003": return true;
      case "ELE004": return true;
      case "ELE005": return true;
      case "ELE013": case "ELE014": case "ELE015": return true;
      case "ELE016": case "ELE017": case "ELE018": return true;
      case "ELE019": case "ELE020": case "ELE021": return true;
      case "ELE022": case "ELE023": case "ELE024": return true;
      case "ELE025": case "ELE026": case "ELE027": return CardType($attackID) == "AA";
      case "ELE028": case "ELE029": case "ELE030": return CardType($attackID) == "AA";
      case "ELE031-1": return true;
      case "ELE033-1": case "ELE033-2": return CardSubtype($attackID) == "Arrow";//TODO+1 not strictly accurate because of multiple arsenal slots
      case "ELE034-1": case "ELE034-2": return CardSubtype($attackID) == "Arrow";//TODO+1 not strictly accurate because of multiple arsenal slots
      case "ELE035-2": return true;
      case "ELE037-1": case "ELE037-2": return CardSubtype($attackID) == "Arrow";
      case "ELE044": case "ELE045": case "ELE046": return true;
      case "ELE047": case "ELE048": case "ELE049": return true;
      case "ELE050": case "ELE051": case "ELE052": return true;
      case "ELE056": case "ELE057": case "ELE058": return true;
      case "ELE059": case "ELE060": case "ELE061": return true;
      case "ELE066": return true;
      case "ELE066-HIT": return true;
      case "ELE067": case "ELE068": case "ELE069": return true;
      case "ELE082": case "ELE083": case "ELE084": return true;
      case "ELE085-FUSE": case "ELE086-FUSE": case "ELE087-FUSE": return CardType($attackID) == "AA";
      case "ELE091-BUFF": case "ELE091-GA": return CardType($attackID) == "AA";
      case "ELE092-DOM": case "ELE092-DOMATK": case "ELE092-BUFF": return true;
      case "ELE097": case "ELE098": case "ELE099": return true;
      case "ELE103": case "ELE104": case "ELE105": return $combatChainState[$CCS_AttackFused] == 1;
      case "ELE112": $talent = CardTalent($attackID); return $talent == "ICE" || $talent == "LIGHTNING" || $talent == "ELEMENTAL";
      case "ELE122": case "ELE123": case "ELE124":
        $talent = CardTalent($attackID); return ($talent == "EARTH" || $talent == "ELEMENTAL") && CardType($attackID) == "AA";
      case "ELE137": case "ELE138": case "ELE139": return CardType($attackID) == "AA";
      case "ELE143": return CardType($attackID) == "AA";
      case "ELE147": return true;
      case "ELE151": case "ELE152": case "ELE153": return true;
      case "ELE151-HIT": case "ELE152-HIT": case "ELE153-HIT": return true;
      case "ELE154": case "ELE155": case "ELE156":
        $talent = CardTalent($attackID);
        return CardType($attackID) == "AA" && ($talent == "ICE" || $talent == "ELEMENTAL");
      case "ELE163": case "ELE164": case "ELE165": $talent = CardTalent($attackID); return $talent == "ICE" || $talent == "ELEMENTAL";
      case "ELE166": case "ELE167": case "ELE168": return true;
      case "ELE173": return CardType($attackID) == "AA";
      case "ELE177": return CardCost($attackID) >= 0;
      case "ELE178": return CardCost($attackID) >= 1;
      case "ELE179": return CardCost($attackID) >= 2;
      case "ELE180": case "ELE181": case "ELE182": $talent = CardTalent($attackID); return $talent == "LIGHTNING" || $talent == "ELEMENTAL";
      case "ELE195": case "ELE196": case "ELE197": return true;
      case "ELE198": case "ELE199": case "ELE200": return CardType($attackID) == "AA";
      case "ELE201": return CardType($attackID) == "AA";
      case "ELE205": return CardClass($attackID) == "GUARDIAN";
      case "ELE206": case "ELE207": case "ELE208": return CardClass($attackID) == "GUARDIAN" && CardType($attackID) == "AA";
      case "ELE215": return CardSubtype($attackID) == "Arrow";
      case "ELE219": case "ELE220": case "ELE221": return CardSubtype($attackID) == "Arrow";
      case "ELE235": return CardType($attackID) == "AA";
      default: return false;
    }
  }

  function ELECardTalent($cardID)
  {
    $number = intval(substr($cardID, 3));
    if($number <= 0) return "??";
    else if($number >= 1 && $number <= 111) return "ELEMENTAL";//Is this right?
    else if($number == 112) return "LIGHTNING,ICE";
    else if($number == 113) return "LIGHTNING,EARTH";
    else if($number == 114) return "ICE,EARTH";
    else if($number >= 115 && $number <= 143) return "EARTH";
    else if($number >= 144 && $number <= 172) return "ICE";
    else if($number >= 173 && $number <= 201) return "LIGHTNING";
    else return "NONE";
  }

  function PlayerHasFused($player)
  {
    global $CS_NumFusedEarth, $CS_NumFusedIce, $CS_NumFusedLightning;
    return GetClassState($player, $CS_NumFusedEarth) > 0 || GetClassState($player, $CS_NumFusedIce) > 0 || GetClassState($player, $CS_NumFusedLightning) > 0;
  }

?>

