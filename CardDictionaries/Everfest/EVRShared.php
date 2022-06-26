<?php

  function EVRAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "EVR053": return 1;
      case "EVR070": return 0;
      case "EVR085": return 2;
      case "EVR087": return 1;
      case "EVR103": return 0;
      case "EVR137": return 0;
      case "EVR121": return 3;
      case "EVR157": return 1;
      case "EVR173": case "EVR174": case "EVR175": return 0;
      case "EVR176": return 0;
      case "EVR177": return 0;
      case "EVR178": return 0;
      case "EVR179": return 0;
      case "EVR180": return 0;
      case "EVR181": return 0;
      case "EVR182": case "EVR183": case "EVR184": case "EVR185": case "EVR186": return 0;
      case "EVR187": return 0;
      case "EVR190": return 0;
      case "EVR195": return 3;
      default: return 0;
    }
  }

  function EVRAbilityType($cardID, $index=-1)
  {
    global $currentPlayer, $mainPlayer, $defPlayer;
    switch($cardID)
    {
      case "EVR053": return "AR";
      case "EVR070": return "A";
      case "EVR085": return "A";
      case "EVR087": return "A";
      case "EVR103": return "A";
      case "EVR137": return "I";
      case "EVR121": return "I";
      case "EVR157": return $currentPlayer == $mainPlayer ? "I" : "";
      case "EVR173": case "EVR174": case "EVR175": return "I";
      case "EVR176": return "AR";
      case "EVR177": return "I";
      case "EVR178": return "DR";
      case "EVR179": return "I";
      case "EVR180": return "I";
      case "EVR181": return "I";
      case "EVR182": return "I";
      case "EVR183": return "A";
      case "EVR184": case "EVR185": case "EVR186": return "I";
      case "EVR187": return "I";
      case "EVR190": return "I";
      case "EVR195": return "A";
      default: return "";
    }
  }

  function EVRHasGoAgain($cardID)
  {
    global $mainPlayer, $CS_NumAuras;
    switch($cardID)
    {
      case "EVR003": return true;
      case "EVR004": return true;
      case "EVR005": case "EVR006": case "EVR007": return true;
      case "EVR014": case "EVR015": case "EVR016": return true;
      case "EVR022": return true;
      case "EVR030": case "EVR031": case "EVR032": return true;
      case "EVR039": return true;
      case "EVR041": case "EVR042": case "EVR043": return true;
      case "EVR044": case "EVR045": case "EVR046": return true;
      case "EVR047": case "EVR048": case "EVR049": return true;
      case "EVR055": return true;
      case "EVR056": return true;
      case "EVR057": case "EVR058": case "EVR059": return true;
      case "EVR066": case "EVR067": case "EVR068": return true;
      case "EVR082": case "EVR083": case "EVR084": return true;
      case "EVR089": return true;
      case "EVR091": case "EVR092": case "EVR093": return true;
      case "EVR100": case "EVR101": case "EVR102": return true;
      case "EVR105": return GetClassState($mainPlayer, $CS_NumAuras) > 0;
      case "EVR106": return true;
      case "EVR107": case "EVR108": case "EVR109": return true;
      case "EVR138": return FractalReplicationStats("Attack");
      case "EVR150": case "EVR151": case "EVR152": return true;
      case "EVR158": return true;
      case "EVR160": return true;
      case "EVR164": case "EVR165": case "EVR166": return true;
      case "EVR167": case "EVR168": case "EVR169": return true;
      case "EVR170": case "EVR171": case "EVR172": return true;
      case "EVR176": case "EVR177": case "EVR178": case "EVR179": case "EVR180": return true;
      case "EVR181": return true;
      case "EVR188": case "EVR189": case "EVR190": case "EVR191": case "EVR192": case "EVR193": return true;
      default: return false;
    }
  }

  function EVRAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "EVR085": return true;
      case "EVR087": return true;
      case "EVR103": return true;
      case "EVR183": return true;
      case "EVR195": return true;
      default: return false;
    }
  }

  function EVREffectAttackModifier($cardID)
  {
    global $combatChainState, $CCS_LinkBaseAttack;
    $params = explode(",", $cardID);
    $cardID = $params[0];
    if(count($params) > 1) $parameter = $params[1];
    switch($cardID)
    {
      case "EVR001": return 1;
      case "EVR004": return $parameter;
      case "EVR008": case "EVR009": case "EVR010": return 2;
      case "EVR014": case "EVR015": case "EVR016": return 5;
      case "EVR017": return 2;
      case "EVR021": return -4;
      case "EVR047-2": case "EVR048-2": case "EVR049-2": return 1;
      case "EVR057-1": case "EVR058-1": case "EVR059-1": return 1;
      case "EVR057-2": return 3;
      case "EVR058-2": return 2;
      case "EVR059-2": return 1;
      case "EVR060": return 3;
      case "EVR061": return 2;
      case "EVR062": return 1;
      case "EVR066": return 3;
      case "EVR067": return 2;
      case "EVR068": return 1;
      case "EVR072": return 2;
      case "EVR082": return 3;
      case "EVR083": return 2;
      case "EVR084": return 1;
      case "EVR087": return 1;
      case "EVR090": return 2;
      case "EVR091": return 3;
      case "EVR092": return 2;
      case "EVR093": return 1;
      case "EVR094": case "EVR095": case "EVR096": return floor($combatChainState[$CCS_LinkBaseAttack]/2) * -1;
      case "EVR100": return 3;
      case "EVR101": return 2;
      case "EVR102": return 1;
      case "EVR143": return 2;
      case "EVR150": return 4;
      case "EVR151": return 3;
      case "EVR152": return 2;
      case "EVR160": return -1;
      case "EVR161-2": return 2;
      case "EVR170-2": return 3;
      case "EVR171-2": return 2;
      case "EVR172-2": return 1;
      default: return 0;
    }
  }

  function EVRCombatEffectActive($cardID, $attackID)
  {
    global $combatChain, $CS_AtksWWeapon, $mainPlayer;
    $params = explode(",", $cardID);
    $cardID = $params[0];
    if(count($params) > 1) $parameter = $params[1];
    switch($cardID)
    {
      case "EVR001": return CardClass($attackID) == "BRUTE";
      case "EVR004": return CardClass($attackID) == "BRUTE";
      case "EVR008": case "EVR009": case "EVR010": return true;
      case "EVR014": case "EVR015": case "EVR016": return CardType($attackID) == "AA" && CardClass($attackID) == "BRUTE";
      case "EVR017": return CardCost($attackID) >= 3;
      case "EVR019": return HasCrush($attackID);
      case "EVR021": return true;
      case "EVR044": case "EVR045": case "EVR046": return CardType($attackID) == "AA" && AttackValue($attackID) <= 2;
      case "EVR047-1": case "EVR048-1": case "EVR049-1": return true;
      case "EVR047-2": case "EVR048-2": case "EVR049-2": return true;
      case "EVR057-1": case "EVR058-1": case "EVR059-1":
        $subtype = CardSubType($attackID);
        if($subtype != "Sword" && $subtype != "Dagger") return false;
        return CardType($attackID) == "W" && GetClassState($mainPlayer, $CS_AtksWWeapon) == 0;
      case "EVR057-2": case "EVR058-2": case "EVR059-2":
        $subtype = CardSubType($attackID);
        if($subtype != "Sword" && $subtype != "Dagger") return false;
        return CardType($attackID) == "W" && GetClassState($mainPlayer, $CS_AtksWWeapon) == 1;
      case "EVR060": case "EVR061": case "EVR062": return CardType($attackID) == "W";
      case "EVR066": case "EVR067": case "EVR068": return CardType($attackID) == "W" && Is1H($attackID);
      case "EVR066-1": case "EVR067-1": case "EVR068-1": return CardType($attackID) == "W";
      case "EVR072": return true;
      case "EVR082": case "EVR083": case "EVR084": return CardType($attackID) == "AA" && CardClass($attackID) == "MECHANOLOGIST";
      case "EVR087": return CardSubType($attackID) == "Arrow";
      case "EVR090": return CardSubType($attackID) == "Arrow";
      case "EVR091": case "EVR092": case "EVR093": return CardSubType($attackID) == "Arrow";
      case "EVR091-1": case "EVR092-1": case "EVR093-1": return CardSubType($attackID) == "Arrow";
      case "EVR094": case "EVR095": case "EVR096": return CardType($attackID) == "AA";
      case "EVR100": case "EVR101": case "EVR102": return CardSubType($attackID) == "Arrow";
      case "EVR142": return CardClass($attackID) == "ILLUSIONIST";
      case "EVR143": return CardClass($attackID) == "ILLUSIONIST" && CardType($attackID) == "AA";
      case "EVR150": case "EVR151": case "EVR152": return CardType($attackID) == "AA";
      case "EVR160": return true;
      case "EVR161-1": case "EVR161-2": case "EVR161-3": return true;
      case "EVR164": case "EVR165": case "EVR166": return true;
      case "EVR170-1": case "EVR171-1": case "EVR172-1": return CardType($attackID) == "AA";
      case "EVR170-2": case "EVR171-2": case "EVR172-2": return CardType($attackID) == "AA";
      case "EVR186": return true;
      default: return false;
    }
  }

  function EVRCardType($cardID)
  {
    switch($cardID)
    {
      case "EVR000": return "R";
      case "EVR001": return "E";
      case "EVR002": return "AA";
      case "EVR003": return "A";
      case "EVR004": return "A";
      case "EVR005": case "EVR006": case "EVR007": return "A";
      case "EVR008": case "EVR009": case "EVR010": return "AA";
      case "EVR011": case "EVR012": case "EVR013": return "AA";
      case "EVR014": case "EVR015": case "EVR016": return "A";
      case "EVR017": return "C";
      case "EVR018": return "E";
      case "EVR019": return "C";
      case "EVR020": return "E";
      case "EVR021": return "AA";
      case "EVR022": return "A";
      case "EVR023": return "A";
      case "EVR024": case "EVR025": case "EVR026": return "AA";
      case "EVR027": case "EVR028": case "EVR029": return "AA";
      case "EVR030": case "EVR031": case "EVR032": return "A";
      case "EVR033": case "EVR034": case "EVR035": return "I";
      case "EVR036": return "T";
      case "EVR037": return "E";
      case "EVR038": return "AA";
      case "EVR039": return "AA";
      case "EVR040": return "AA";
      case "EVR041": case "EVR042": case "EVR043": return "AA";
      case "EVR044": case "EVR045": case "EVR046": return "AA";
      case "EVR047": case "EVR048": case "EVR049": return "AA";
      case "EVR050": case "EVR051": case "EVR052": return "DR";
      case "EVR053": return "E";
      case "EVR054": return "AR";
      case "EVR055": return "A";
      case "EVR056": return "A";
      case "EVR057": case "EVR058": case "EVR059": return "A";
      case "EVR060": case "EVR061": case "EVR062": return "AR";
      case "EVR063": case "EVR064": case "EVR065": return "AR";
      case "EVR066": case "EVR067": case "EVR068": return "A";
      case "EVR069": return "A";
      case "EVR070": return "A";
      case "EVR071": return "A";
      case "EVR072": return "A";
      case "EVR073": case "EVR074": case "EVR075": return "AA";
      case "EVR076": case "EVR077": case "EVR078": return "AA";
      case "EVR079": case "EVR080": case "EVR081": return "AA";
      case "EVR082": case "EVR083": case "EVR084": return "A";
      case "EVR085": return "C";
      case "EVR086": return "E";
      case "EVR087": return "W";
      case "EVR088": return "AA";
      case "EVR089": return "A";
      case "EVR090": return "I";
      case "EVR091": case "EVR092": case "EVR093": return "A";
      case "EVR094": case "EVR095": case "EVR096": return "AA";
      case "EVR097": case "EVR098": case "EVR099": return "AA";
      case "EVR100": case "EVR101": case "EVR102": return "A";
      case "EVR103": return "E";
      case "EVR104": return "AA";
      case "EVR105": return "AA";
      case "EVR106": return "A";
      case "EVR107": case "EVR108": case "EVR109": return "A";
      case "EVR110": case "EVR111": case "EVR112": return "AA";
      case "EVR113": case "EVR114": case "EVR115": return "AA";
      case "EVR116": case "EVR117": case "EVR118": return "AA";
      case "EVR119": return "T";
      case "EVR120": return "C";
      case "EVR121": return "W";
      case "EVR122": return "DR";
      case "EVR123": return "A";
      case "EVR124": return "A";
      case "EVR125": case "EVR126": case "EVR127": return "A";
      case "EVR128": case "EVR129": case "EVR130": return "A";
      case "EVR131": case "EVR132": case "EVR133": return "A";
      case "EVR134": case "EVR135": case "EVR136": return "A";
      case "EVR137": return "E";
      case "EVR138": return "AA";
      case "EVR139": return "AA";
      case "EVR140": return "A";
      case "EVR141": case "EVR142": case "EVR143": return "A";
      case "EVR144": case "EVR145": case "EVR146": return "AA";
      case "EVR147": case "EVR148": case "EVR149": return "AA";
      case "EVR150": case "EVR151": case "EVR152": return "A";
      case "EVR153": return "T";
      case "EVR155": return "E";
      case "EVR156": case "EVR157": return "AA";
      case "EVR158": case "EVR159": case "EVR160": return "A";
      case "EVR161": case "EVR162": case "EVR163": return "AA";
      case "EVR164": case "EVR165": case "EVR166": return "A";
      case "EVR167": case "EVR168": case "EVR169": return "A";
      case "EVR170": case "EVR171": case "EVR172": return "A";
      case "EVR173": case "EVR174": case "EVR175": return "I";
      case "EVR176": return "A";
      case "EVR177": return "A";
      case "EVR178": return "A";
      case "EVR179": return "A";
      case "EVR180": return "A";
      case "EVR181": return "A";
      case "EVR182": return "A";
      case "EVR183": case "EVR184": case "EVR185": return "A";
      case "EVR186": return "A";
      case "EVR187": return "A";
      case "EVR188": return "A";
      case "EVR189": return "A";
      case "EVR190": return "A";
      case "EVR191": return "A";
      case "EVR192": return "A";
      case "EVR193": return "A";
      case "EVR195": return "T";
      default: return "";
    }
  }

  function EVRCardSubtype($cardID)
  {
    switch($cardID)
    {
      case "EVR000": return "Gem";
      case "EVR001": return "Arms";
      case "EVR018": return "Off-Hand";
      case "EVR020": return "Chest";
      case "EVR023": return "Aura";
      case "EVR037": return "Head";
      case "EVR053": return "Head";
      case "EVR069": case "EVR070": case "EVR071": case "EVR072": return "Item";
      case "EVR086": return "Arms";
      case "EVR087": return "Bow";
      case "EVR088": return "Arrow";
      case "EVR094": case "EVR095": case "EVR096": return "Arrow";
      case "EVR097": case "EVR098": case "EVR099": return "Arrow";
      case "EVR103": return "Arms";
      case "EVR107": case "EVR108": case "EVR109": return "Aura";
      case "EVR121": return "Staff";
      case "EVR131": case "EVR132": case "EVR133": return "Aura";
      case "EVR137": return "Head";
      case "EVR140": return "Aura";
      case "EVR141": case "EVR142": case "EVR143": return "Aura";
      case "EVR155": return "Off-Hand";
      case "EVR176": case "EVR177": case "EVR178": case "EVR179": case "EVR180": case "EVR181": case "EVR182": case "EVR183":
      case "EVR184": case "EVR185": case "EVR186": case "EVR187": case "EVR188": case "EVR189": case "EVR190": case "EVR191":
      case "EVR192": case "EVR193": case "EVR195": return "Item";
      default: return "";
    }
  }

  function EVRCardCost($cardID)
  {
    switch($cardID)
    {
      case "EVR000": return -1;
      case "EVR001": return 0;
      case "EVR002": return 2;
      case "EVR003": return 0;
      case "EVR004": return 1;
      case "EVR005": case "EVR006": case "EVR007": return 0;
      case "EVR008": case "EVR009": case "EVR010": return 2;
      case "EVR011": case "EVR012": case "EVR013": return 2;
      case "EVR014": case "EVR015": case "EVR016": return 0;
      case "EVR017": return 0;
      case "EVR019": return 0;
      case "EVR020": return 0;
      case "EVR021": return 10;
      case "EVR022": return 3;
      case "EVR023": return 3;
      case "EVR024": case "EVR025": case "EVR026": return 6;
      case "EVR027": case "EVR028": case "EVR029": return 7;
      case "EVR030": case "EVR031": case "EVR032": return 2;
      case "EVR033": case "EVR034": case "EVR035": return 3;
      case "EVR037": return 0;
      case "EVR038": case "EVR039": case "EVR040": return 0;
      case "EVR041": case "EVR042": case "EVR043": return 0;
      case "EVR044": case "EVR045": case "EVR046": return 0;
      case "EVR047": case "EVR048": case "EVR049": return 1;
      case "EVR050": case "EVR051": case "EVR052": return 0;
      case "EVR053": return 0;
      case "EVR054": return 0;
      case "EVR055": return 0;
      case "EVR056": return 0;
      case "EVR057": case "EVR058": case "EVR059": return 0;
      case "EVR060": case "EVR061": case "EVR062": return 1;
      case "EVR063": case "EVR064": case "EVR065": return 0;
      case "EVR066": case "EVR067": case "EVR068": return 0;
      case "EVR069": return 2;
      case "EVR070": return 2;
      case "EVR071": return 0;
      case "EVR072": return 2;
      case "EVR073": case "EVR074": case "EVR075": return 0;
      case "EVR076": case "EVR077": case "EVR078": return 2;
      case "EVR079": case "EVR080": case "EVR081": return 2;
      case "EVR082": case "EVR083": case "EVR084": return 0;
      case "EVR088": return 2;
      case "EVR089": return 0;
      case "EVR090": return 0;
      case "EVR091": case "EVR092": case "EVR093": return 0;
      case "EVR094": case "EVR095": case "EVR096": return 1;
      case "EVR097": case "EVR098": case "EVR099": return 1;
      case "EVR100": case "EVR101": case "EVR102": return 0;
      case "EVR103": return 0;
      case "EVR104": return 3;
      case "EVR105": return 0;
      case "EVR106": return 0;
      case "EVR107": case "EVR108": case "EVR109": return 1;
      case "EVR110": case "EVR111": case "EVR112": return 2;
      case "EVR113": case "EVR114": case "EVR115": return 2;
      case "EVR116": case "EVR117": case "EVR118": return 2;
      case "EVR120": return 0;
      case "EVR121": return 0;
      case "EVR122": return 1;
      case "EVR123": return 2;
      case "EVR124": return 0;
      case "EVR125": case "EVR126": case "EVR127": return 2;
      case "EVR128": case "EVR129": case "EVR130": return 0;
      case "EVR131": case "EVR132": case "EVR133": return 2;
      case "EVR134": case "EVR135": case "EVR136": return 3;
      case "EVR137": return 0;
      case "EVR138": return 0;
      case "EVR139": return 1;
      case "EVR140": return 0;
      case "EVR141": case "EVR142": case "EVR143": return 0;
      case "EVR144": case "EVR145": case "EVR146": return 2;
      case "EVR147": case "EVR148": case "EVR149": return 3;
      case "EVR150": case "EVR151": case "EVR152": return 1;
      case "EVR155": return 0;
      case "EVR156": return 1;
      case "EVR157": return 2;
      case "EVR158": return 0;
      case "EVR159": return 3;
      case "EVR160": return 1;
      case "EVR161": case "EVR162": case "EVR163": return 2;
      case "EVR164": case "EVR165": case "EVR166": return 0;
      case "EVR167": case "EVR168": case "EVR169": return 0;
      case "EVR170": case "EVR171": case "EVR172": return 0;
      case "EVR173": case "EVR174": case "EVR175": return 0;
      case "EVR176": case "EVR177": case "EVR178": return 0;
      case "EVR179": case "EVR180": case "EVR181": return 0;
      case "EVR182": case "EVR183": case "EVR184": case "EVR185": case "EVR186": return 0;
      case "EVR187": case "EVR188": case "EVR189": case "EVR190": case "EVR191": return 0;
      case "EVR192": case "EVR193": case "EVR195": return 0;
      default: return 0;
    }
  }

  function EVRPitchValue($cardID)
  {
    switch($cardID)
    {
      case "EVR000": return 3;
      case "EVR001": return 0;
      case "EVR002": return 1;
      case "EVR003": return 3;
      case "EVR004": return 1;
      case "EVR005": return 1;
      case "EVR006": return 2;
      case "EVR007": return 3;
      case "EVR008": case "EVR011": case "EVR014": return 1;
      case "EVR009": case "EVR012": case "EVR015": return 2;
      case "EVR010": case "EVR013": case "EVR016": return 3;
      case "EVR017": return 0;
      case "EVR018": return 0;
      case "EVR019": return 0;
      case "EVR020": return 0;
      case "EVR021": return 1;
      case "EVR022": return 3;
      case "EVR023": return 3;
      case "EVR024": case "EVR027": case "EVR030": case "EVR033": return 1;
      case "EVR025": case "EVR028": case "EVR031": case "EVR034": return 2;
      case "EVR026": case "EVR029": case "EVR032": case "EVR035": return 3;
      case "EVR037": return 0;
      case "EVR038": return 2;
      case "EVR039": return 2;
      case "EVR040": return 3;
      case "EVR041": case "EVR044": case "EVR047": case "EVR050": return 1;
      case "EVR042": case "EVR045": case "EVR048": case "EVR051": return 2;
      case "EVR043": case "EVR046": case "EVR049": case "EVR052": return 3;
      case "EVR053": return 0;
      case "EVR054": return 2;
      case "EVR055": return 2;
      case "EVR056": return 1;
      case "EVR057": case "EVR060": case "EVR063": case "EVR066": return 1;
      case "EVR058": case "EVR061": case "EVR064": case "EVR067": return 2;
      case "EVR059": case "EVR062": case "EVR065": case "EVR068": return 3;
      case "EVR069": return 2;
      case "EVR070": case "EVR071": case "EVR072": return 3;
      case "EVR073": case "EVR076": case "EVR079": case "EVR082": return 1;
      case "EVR074": case "EVR077": case "EVR080": case "EVR083": return 2;
      case "EVR075": case "EVR078": case "EVR081": case "EVR084": return 3;
      case "EVR085": return 0;
      case "EVR086": return 0;
      case "EVR087": return 0;
      case "EVR088": return 1;
      case "EVR089": return 3;
      case "EVR090": return 2;
      case "EVR091": case "EVR094": case "EVR097": case "EVR100": return 1;
      case "EVR092": case "EVR095": case "EVR098": case "EVR101": return 2;
      case "EVR093": case "EVR096": case "EVR099": case "EVR102": return 3;
      case "EVR103": return 0;
      case "EVR104": return 1;
      case "EVR105": return 1;
      case "EVR106": return 1;
      case "EVR107": case "EVR110": case "EVR113": case "EVR116": return 1;
      case "EVR108": case "EVR111": case "EVR114": case "EVR117": return 2;
      case "EVR109": case "EVR112": case "EVR115": case "EVR118": return 3;
      case "EVR120": return 0;
      case "EVR121": return 0;
      case "EVR122": return 3;
      case "EVR123": return 1;
      case "EVR124": return 3;
      case "EVR125": case "EVR128": case "EVR131": case "EVR134": return 1;
      case "EVR126": case "EVR129": case "EVR132": case "EVR135": return 2;
      case "EVR127": case "EVR130": case "EVR133": case "EVR136": return 3;
      case "EVR137": return 0;
      case "EVR138": return 1;
      case "EVR139": return 1;
      case "EVR140": return 3;
      case "EVR141": case "EVR142": case "EVR143": return 3;
      case "EVR144": case "EVR147": case "EVR150": return 1;
      case "EVR145": case "EVR148": case "EVR151": return 2;
      case "EVR146": case "EVR149": case "EVR152": return 3;
      case "EVR155": return 0;
      case "EVR156": case "EVR157": return 1;
      case "EVR158": return 3;
      case "EVR159": return 1;
      case "EVR160": return 3;
      case "EVR161": case "EVR164": case "EVR167": case "EVR170": return 1;
      case "EVR162": case "EVR165": case "EVR168": case "EVR171": return 2;
      case "EVR163": case "EVR166": case "EVR169": case "EVR172": return 3;
      case "EVR173": return 1;
      case "EVR174": return 2;
      case "EVR175": return 3;
      case "EVR176": return 2;
      case "EVR177": case "EVR178": return 3;
      case "EVR179": return 2;
      case "EVR180": case "EVR181": return 3;
      case "EVR182": case "EVR183": case "EVR184": case "EVR185": case "EVR186": return 3;
      case "EVR187": return 3;
      case "EVR188": case "EVR189": return 3;
      case "EVR190": case "EVR191": return 2;
      case "EVR192": return 3;
      case "EVR193": return 2;
      case "EVR195": return 0;
      default: return 3;
    }
  }

  function EVRBlockValue($cardID)
  {
    switch($cardID)
    {
      case "EVR000": return -1;
      case "EVR001": return 1;
      case "EVR008": case "EVR009": case "EVR010": return -1;
      case "EVR011": case "EVR012": case "EVR013": return -1;
      case "EVR017": return 0;
      case "EVR018": return 2;
      case "EVR019": return 0;
      case "EVR020": return 2;
      case "EVR033": case "EVR034": case "EVR035": return -1;
      case "EVR037": return 2;
      case "EVR041": case "EVR042": case "EVR043": return 2;
      case "EVR044": case "EVR045": case "EVR046": return 2;
      case "EVR047": case "EVR048": case "EVR049": return 2;
      case "EVR050": return 3;
      case "EVR051": return 2;
      case "EVR052": return 1;
      case "EVR053": return 1;
      case "EVR069": case "EVR070": case "EVR071": case "EVR072": return -1;
      case "EVR085": return -1;
      case "EVR086": return 2;
      case "EVR087": return -1;
      case "EVR090": return -1;
      case "EVR091": case "EVR092": case "EVR093": return 2;
      case "EVR100": case "EVR101": case "EVR102": return 2;
      case "EVR103": return 0;
      case "EVR106": return 2;
      case "EVR107": case "EVR108": case "EVR109": return 2;
      case "EVR120": return 0;
      case "EVR121": return 0;
      case "EVR122": return 2;
      case "EVR131": case "EVR132": case "EVR133": return 2;
      case "EVR137": return 0;
      case "EVR138": return FractalReplicationStats("Block");
      case "EVR140": return 2;
      case "EVR141": case "EVR142": case "EVR143": return 2;
      case "EVR150": case "EVR151": case "EVR152": return 2;
      case "EVR155": return -1;
      case "EVR156": case "EVR157": return 3;
      case "EVR158": return 2;
      case "EVR159": return 2;
      case "EVR161": case "EVR162": case "EVR163": return 2;
      case "EVR164": case "EVR165": case "EVR166": return 2;
      case "EVR167": case "EVR168": case "EVR169": return 2;
      case "EVR170": case "EVR171": case "EVR172": return 2;
      case "EVR173": case "EVR174": case "EVR175": return -1;
      case "EVR176": case "EVR177": case "EVR178": case "EVR179": case "EVR180": case "EVR181": return -1;
      case "EVR182": case "EVR183": case "EVR184": case "EVR185": case "EVR186": case "EVR187": return -1;
      case "EVR188": case "EVR189": case "EVR190": case "EVR191": case "EVR192": case "EVR193": return -1;
      case "EVR195": return -1;
      default: return 3;
    }
  }

  function EVRAttackValue($cardID)
  {
    switch($cardID)
    {
      case "EVR002": return 8;
      case "EVR008": return 6;
      case "EVR009": return 5;
      case "EVR010": return 4;
      case "EVR011": return 6;
      case "EVR012": return 5;
      case "EVR013": return 4;
      case "EVR021": return 14;
      case "EVR024": case "EVR027": return 10;
      case "EVR025": case "EVR028": return 9;
      case "EVR026": case "EVR029": return 8;
      case "EVR038": return 2;
      case "EVR039": return 2;
      case "EVR040": return 2;
      case "EVR041": case "EVR044": case "EVR047": return 3;
      case "EVR042": case "EVR045": case "EVR048": return 2;
      case "EVR043": case "EVR046": case "EVR049": return 1;
      case "EVR073": return 3;
      case "EVR074": return 2;
      case "EVR075": return 1;
      case "EVR076": return 6;
      case "EVR077": return 5;
      case "EVR078": return 4;
      case "EVR079": return 5;
      case "EVR080": return 4;
      case "EVR081": return 3;
      case "EVR088": return 6;
      case "EVR094": case "EVR097": return 5;
      case "EVR095": case "EVR098": return 4;
      case "EVR096": case "EVR099": return 3;
      case "EVR104": return 7;
      case "EVR105": return 3;
      case "EVR110": return 5;
      case "EVR111": return 4;
      case "EVR112": return 3;
      case "EVR113": case "EVR116": return 4;
      case "EVR114": case "EVR117": return 3;
      case "EVR115": case "EVR118": return 2;
      case "EVR138": return FractalReplicationStats("Attack");
      case "EVR139": return 7;
      case "EVR147": return 8;
      case "EVR144": case "EVR148": return 7;
      case "EVR145": case "EVR149": return 6;
      case "EVR146": return 5;
      case "EVR156": return 5;
      case "EVR157": return 3;
      case "EVR161": return 4;
      case "EVR162": return 3;
      case "EVR163": return 2;
      case "EVR173": case "EVR174": case "EVR175": return 0;
      default: return 0;
    }
  }

  function EVRPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $combatChain, $CS_PlayIndex, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    global $CS_HighestRoll, $CS_NumNonAttackCards, $CS_NumAttackCards, $CS_NumBoosted, $mainPlayer;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "EVR003":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Ready to Roll lets you roll an extra die this turn.";
      case "EVR004":
        $roll = GetDieRoll($currentPlayer);
        AddCurrentTurnEffect($cardID . "," . $roll, $currentPlayer);
        return "Rolling Thunder gives your next Brute attack +" . $roll . ".";
      case "EVR005": case "EVR006": case "EVR007":
        $rv = "High Roller Intimidated";
        Intimidate();
        if($cardID == "EVR005") $targetHigh = 4;
        else if($cardID == "EVR006") $targetHigh = 5;
        else if($cardID == "EVR007") $targetHigh = 6;
        if(GetClassState($currentPlayer, $CS_HighestRoll) >= $targetHigh)
        {
          Intimidate();
          $rv .= " twice";
        }
        return $rv . ".";
      case "EVR008": case "EVR009": case "EVR010":
        MyDrawCard();
        $card = DiscardRandom();
        $rv = "Bare Fangs discarded " . CardLink($card, $card);
        if(AttackValue($card) >= 6)
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv .= " and got +2 from discarding a card with 6 or more power";
        }
        $rv .= ".";
        return $rv;
      case "EVR011": case "EVR012": case "EVR013":
        MyDrawCard();
        $card = DiscardRandom();
        $rv = "Wild Ride discarded " . CardLink($card, $card);
        if(AttackValue($card) >= 6)
        {
          GiveAttackGoAgain();
          $rv .= " and got Go Again from discarding a card with 6 or more power";
        }
        $rv .= ".";
        return $rv;
      case "EVR014": case "EVR015": case "EVR016":
        $rv = "Bad Beats - Did nothing.";
        if($cardID == "EVR014") $target = 4;
        else if($cardID == "EVR015") $target = 5;
        else $target = 6;
        $roll = GetDieRoll($currentPlayer);
        if($roll >= $target)
        {
          $rv = "Bad Beats gives the next Brute attack action card +5.";
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return $rv;
      case "EVR022":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKAURAMAXCOST," . ($resourcesPaid-3));
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
        return "Imposing Visage let you tutor an aura.";
      case "EVR030": case "EVR031": case "EVR032":
        if($cardID == "EVR030") $amount = 3;
        else if($cardID == "EVR031") $amount = 2;
        else $amount = 1;
        PlayAura("WTR075", $currentPlayer, $amount);
        return "Seismic Stir created " . $amount . " Seismic Surge tokens.";
      case "EVR033": case "EVR034": case "EVR035":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Steadfast prevents damage this turn.";
      case "EVR047": case "EVR048": case "EVR049":
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Hit_Effect,1_Attack");
        AddDecisionQueue("TWINTWISTERS", $currentPlayer, $cardID);
        return "";
      case "EVR053":
        $deck = &GetDeck($currentPlayer);
        $card = array_shift($deck);
        BanishCardForPlayer($card, $currentPlayer, "DECK", "TCC");
        return "Helm of the Sharp Eye banished a card. It is playable to this combat chain.";
      case "EVR054":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDMZBUFF", $currentPlayer, "EVR054", 1);
        return "";
      case "EVR055":
        $numCopper = CountItem("CRU197", $currentPlayer);
        if($numCopper == 0) return "No copper.";
        if($numCopper > 6) $numCopper = 6;
        $buttons = "";
        for($i=0; $i<=$numCopper; ++$i)
        {
          if($buttons != "") $buttons .= ",";
          $buttons .= $i;
        }
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many Copper to destroy");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, $buttons);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "CRU197-", 1);
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "<-", 1);
        AddDecisionQueue("LASTRESULTPIECE", $currentPlayer, "1", 1);
        AddDecisionQueue("APPENDLASTRESULT", $currentPlayer, "-Buff_Weapon,Buff_Weapon,Go_Again,Go_Again,Another_Swing,Another_Swing", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose {0} modes");
        AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "<-", 1);
        AddDecisionQueue("BLOODONHERHANDS", $currentPlayer, "-", 1);
        return "";
      case "EVR056":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Oath of Steel gives your weapon +1 each time you attack this turn, but loses all counters at end of turn.";
      case "EVR057": case "EVR058": case "EVR059":
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
        return "";
      case "EVR060": case "EVR061": case "EVR062":
        GiveAttackGoAgain();
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        return "Blade Runner gives Go Again and buffs your next 1H weapon attack.";
      case "EVR066": case "EVR067": case "EVR068":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        return "";
      case "EVR070":
        $rv = "Micro-Processor is a partially manual card. Only choose each option once per turn.";
        if($from == "PLAY")
        {
          $rv = "";
          $items = &GetItems($currentPlayer);
          if($items[GetClassState($currentPlayer, $CS_PlayIndex)+3] == 2) { $rv = "Gained an action point from Micro-Processor."; GainActionPoints(1); }
          AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Opt,Draw_then_top_deck,Banish_top_deck");
          AddDecisionQueue("MICROPROCESSOR", $currentPlayer, "-", 1);
        }
        return $rv;
      case "EVR073": case "EVR074": case "EVR075":
        return "T-Bone is a partially manual card. If you have a boosted card on the combat chain, the opponent must block with an equipment if possible.";
      case "EVR079": case "EVR080": case "EVR081":
        $numBoosts = GetClassState($currentPlayer, $CS_NumBoosted);
        Opt($cardID, $numBoosts);
        return "Zoom In let you opt " . $numBoosts . ".";
      case "EVR082": case "EVR083": case "EVR084":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVR085":
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "0");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
        AddDecisionQueue("MAYCHOOSEHAND", $otherPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $otherPlayer, "-", 1);
        AddDecisionQueue("DRAW", $otherPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "EVR195", 1);
        AddDecisionQueue("PUTPLAY", $currentPlayer, "0", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "1", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("DQVARPASSIFSET", $currentPlayer, "0");
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "Genis Wotchuneed let the opponent choose if they want to sink a card for a silver.";
      case "EVR087":
        if(ArsenalFull($currentPlayer)) return "Your arsenal is full, so you cannot put an arrow in your arsenal.";
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDARSENALFACEUP", $currentPlayer, "HAND", 1);
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVR089":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON,Bow");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDMZUSES", $currentPlayer, 2, 1);
        return "Tri-shot gives your bow 2 additional uses.";
      case "EVR090":
        AddCurrentTurnEffect($cardID, 1);
        AddCurrentTurnEffect($cardID, 2);
        return "Rain Razors gives Arrow attacks +2 this turn.";
      case "EVR091": case "EVR092": case "EVR093":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddCurrentTurnEffect($cardID . "-1", $otherPlayer);
        return "Release the Tension buffs your next arrow and prevents Defense reactions on the chain link.";
      case "EVR100": case "EVR101": case "EVR102":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        Opt($cardID, 1);
        return "";
      case "EVR103":
        PlayAura("ARC112", $currentPlayer, 2);
        return "Vexing Quillhand created two Runechant tokens.";
      case "EVR106":
        $rv = "";
        if(GetClassState($currentPlayer, $CS_NumNonAttackCards) > 1 && GetClassState($currentPlayer, $CS_NumAttackCards) > 0)
        {
          PlayAura("ARC112", $currentPlayer, 4);
          $rv = "Revel in Runeblood created 4 Runechants.";
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return $rv;
      case "EVR121":
        DealArcane(1, 1, "ABILITY", $cardID);
        AddDecisionQueue("KRAKENAETHERVEIN", $currentPlayer, "-");
        return "";
      case "EVR123":
        DealArcane(4, 1, "PLAYCARD", $cardID);
        if($currentPlayer != $mainPlayer)
        {
          AddDecisionQueue("AETHERWILDFIRE", $currentPlayer, "-");
        }
        return "";
      case "EVR124":
        for($i=0; $i<$resourcesPaid; ++$i)
        {
          AddDecisionQueue("FINDINDICES", $otherPlayer, "AURAMAXCOST,0");
          AddDecisionQueue("CHOOSETHEIRAURA", $currentPlayer, "<-", 1);
          AddDecisionQueue("DESTROYAURA", $otherPlayer, "-", 1);
        }
        AddDecisionQueue("SCOUR", $currentPlayer, $resourcesPaid);
        return "";
      case "EVR125": case "EVR126": case "EVR127":
        $oppTurn = $currentPlayer != $mainPlayer;
        if($cardID == "EVR125") $damage = ($oppTurn ? 6 : 4);
        if($cardID == "EVR126") $damage = ($oppTurn ? 5 : 3);
        if($cardID == "EVR127") $damage = ($oppTurn ? 4 : 2);
        DealArcane($damage, 1, "PLAYCARD", $cardID);
        return "";
      case "EVR128": case "EVR129": case "EVR130":
        if($cardID == "EVR128") $numReveal = 3;
        else if($cardID == "EVR129") $numReveal = 2;
        else $numReveal = 1;
        AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
        if($currentPlayer == $mainPlayer)
        {
          AddDecisionQueue("PREPENDLASTRESULT", $otherPlayer, $numReveal . "-", 1);
          AddDecisionQueue("MULTICHOOSEHAND", $otherPlayer, "<-", 1);
          AddDecisionQueue("IMPLODELASTRESULT", $otherPlayer, ",", 1);
        }
        AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $otherPlayer, "-", 1);
        AddDecisionQueue("DRAW", $otherPlayer, "-", 1);
        return "Pry removes a card. Make sure you choose the right number of options.";
      case "EVR134": case "EVR135": case "EVR136":
        DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID);
        return "";
      case "EVR137":
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You may choose an Illusionist Aura to destroy and replace.");
        AddDecisionQueue("FINDINDICES", $currentPlayer, "AURACLASS,ILLUSIONIST");
        AddDecisionQueue("MULTIZONEFORMAT", $currentPlayer, "MYAURAS", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIZONEDESTROY", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "CROWNOFREFLECTION", 1);
        AddDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
        return "Crown of Reflection let you destroy an aura and play a new one.";
      case "EVR138":
        FractalReplicationStats("Ability");
        return "Fractal Replication will copy effects of other Illusionist cards on the combat chain. Note that according to Everfest release notes, cards that are no longer on the chain (for example, went to Soul) are not counted.";
      case "EVR150": case "EVR151": case "EVR152":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Veiled Intentions buffed your next attack action card and makes it draw if it is destroyed.";
      case "EVR157":
        $rv = "";
        if($from == "PLAY")
        {
          $rv = "Firebreathing gained +1.";
          ++$combatChain[5];
        }
        return $rv;
      case "EVR160":
        Draw(1);
        Draw(2);
        if($currentPlayer != $mainPlayer)
        {
          AddCurrentTurnEffect($cardID, $otherPlayer);//If played as an instant, needs to apply to the current turn
        }
        else
        {
          AddNextTurnEffect($cardID, $otherPlayer);
        }
        return "This Round's on Me drew a card for each player and gave attacks targeting you -1.";
      case "EVR161": case "EVR162": case "EVR163":
        $rand = rand(1, 3);
        if($resourcesPaid == 0 || $rand == 1) { WriteLog("Gain +2 life on hit."); AddCurrentTurnEffect("EVR161-1", $currentPlayer); }
        if($resourcesPaid == 0 || $rand == 2) { WriteLog("Gained +2 attack."); AddCurrentTurnEffect("EVR161-2", $currentPlayer); }
        if($resourcesPaid == 0 || $rand == 3) { WriteLog("Gained Go Again."); AddCurrentTurnEffect("EVR161-3", $currentPlayer); }
        return ($resourcesPaid == 0 ? "Party time!" : "");
      case "EVR164": case "EVR165": case "EVR166":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVR167": case "EVR168": case "EVR169":
        if($cardID == "EVR167") $times = 4;
        else if($cardID == "EVR168") $times = 3;
        else if($cardID == "EVR169") $times = 2;
        AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
        AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        for($i=0; $i<$times; ++$i)
        {
          AddDecisionQueue("PICKACARD", $currentPlayer, "-", 1);
        }
        return "";
      case "EVR170": case "EVR171": case "EVR172":
        $rv = "Smashing Good Time makes your next attack action that hits destroy an item";
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        if($from == "ARS")
        {
          AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
          $rv .= " and gives your next attack action card +" . EffectAttackModifier($cardID . "-2") . ".";
        }
        else { $rv .= "."; }
        return $rv;
      case "EVR173": case "EVR174": case "EVR175":
        if($cardID == "EVR173") $opt = 3;
        else if($cardID == "EVR174") $opt = 2;
        else if($cardID == "EVR175") $opt = 1;
        Opt($cardID, $opt);
        AddDecisionQueue("EVENBIGGERTHANTHAT", $currentPlayer, "-");
        return "";
      case "EVR176":
        $rv = "";
        if($from == "PLAY")
        {
          $deck = &GetDeck($currentPlayer);
          if(count($deck) == 0) return "Deck is empty.";
          $mod = "DECK";
          if(CardType($deck[0]) == "AA") $mod = "TT";
          BanishCardForPlayer($deck[0], $mainPlayer, "DECK", $mod);
          array_shift($deck);
        }
        return "";
      case "EVR177":
        $rv = "";
        if($from == "PLAY")
        {
          $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
          PummelHit($otherPlayer);
          PummelHit($otherPlayer);
        }
        else
        {
          $rv = "Amulet of Echoes is a partially manually card. Only activate the ability when the target player has played two or more cards with the same name this turn.";
        }
        return "";
      case "EVR178":
        if($from == "PLAY")
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "EVR178");
          AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDCARDTOCHAIN", $currentPlayer, "DECK", 1);
        }
        return "";
      case "EVR179":
        $rv = "Amulet of Ignition is a partially manual card. Only use the abiliy when you have not played anything.";
        if($from == "PLAY")
        {
          AddCurrentTurnEffect($cardID, $currentPlayer, $from);
          $rv = "Amulet of Intervention reduces your next ability cost by 1.";
        }
        return $rv;
      case "EVR180":
        $rv = "Amulet of Intervention is a partially manual card. Only use the abiliy when you are the target of lethal damage.";
        if($from == "PLAY")
        {
          AddCurrentTurnEffect($cardID, $currentPlayer, $from);
          $rv = "Amulet of Intervention prevents 1 damage.";
        }
        return $rv;
      case "EVR181":
        if($from == "PLAY"){
          $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
        }
        return "";
      case "EVR182":
        $rv = "";
        if($from == "PLAY")
        {
          Opt($cardID, 2);
          $rv = "Clarity Potion let you opt 2.";
        }
        return $rv;
      case "EVR183":
        if($from == "PLAY"){
          GainHealth(2, $currentPlayer);
        }
        return "Healing Potion gained 2 health.";
      case "EVR184":
        $rv = "";
        if($from == "PLAY"){
          LookAtHand($otherPlayer);
          $rv = "Potion of Seeing revealed the opponent's hand.";
        }
        return $rv;
      case "EVR185":
        $rv = "";
        if($from == "PLAY"){
          $cards = "";
          $pitch = &GetPitch($currentPlayer);
          while(count($pitch) > 0)
          {
            if($cards != "") $cards .= ",";
            $cards .= array_shift($pitch);
            for($i=1; $i<PitchPieces(); ++$i) array_shift($pitch);
          }
          if($cards != "") AddDecisionQueue("CHOOSETOP", $currentPlayer, $cards);
          $rv = "Potion of Deja Vu put your pitch cards on top of your deck.";
        }
        return $rv;
      case "EVR186":
        $rv = "";
        if($from == "PLAY")
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Potion of Ironhide gives your attack action cards +1 Block this turn.";
        }
        return $rv;
      case "EVR187":
        if($from == "PLAY"){
          AddDecisionQueue("POTIONOFLUCK", $currentPlayer, "-", 1);
        }
        return "";
      case "EVR190":
        $rv = "Talisman of Featherfoot is a partially manual card. Activate the instant ability if you met the criteria.";
        if($from == "PLAY"){
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
          GiveAttackGoAgain();
          $rv = "Talisman of Featherfoot gave the current attack Go Again.";
        }
        return $rv;
      case "EVR195":
        $rv = "";
        if($from == "PLAY"){
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
          $rv = "Silver drew a card.";
          Draw($currentPlayer);
        }
        return $rv;
      default: return "";
    }
  }

  function EVRHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer, $CS_NumAuras, $chainLinks;
    switch($cardID)
    {
      case "EVR021":
        AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "EVR038":
        if(ComboActive())
        {
          $deck = &GetDeck($mainPlayer);
          BanishCardForPlayer($deck[0], $mainPlayer, "DECK", "NT");
          array_shift($deck);
        }
        break;
      case "EVR039":
        for($i=0; $i<SearchCount(SearchChainLinks(-1, 2, "AA")); ++$i) Draw($mainPlayer);
        break;
      case "EVR040":
        if(ComboActive())
        {
          $deck = &GetDeck($mainPlayer);
          for($i=0; $i<count($chainLinks); ++$i)
          {
            $attackID = $chainLinks[$i][0];
            if($chainLinks[$i][2] == "1" && ($attackID == "EVR041" || $attackID == "EVR042" || $attackID == "EVR043"))
            {
              $chainLinks[$i][2] = "0";
              array_push($deck, $attackID);
            }
          }
          AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-");
        }
        break;
      case "EVR044": case "EVR045": case "EVR046":
        AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
        break;
      case "EVR088":
        $hand = &GetHand($defPlayer);
        $cards = "";
        $numDiscarded = 0;
        for($i=count($hand)-HandPieces(); $i>=0; $i-=HandPieces())
        {
          $id = $hand[$i];
          $cardType = CardType($id);
          if($cardType != "A" && $cardType != "AA")
          {
            AddGraveyard($id, $defPlayer, "HAND");
            unset($hand[$i]);
            ++$numDiscarded;
          }
          if($cards != "") $cards .= ",";
          $cards .= $id;
        }
        LoseHealth($numDiscarded, $defPlayer);
        RevealCards($cards);
        WriteLog("Battering Bolt discarded " . $numDiscarded . " and caused the defending player to lose that much health.");
        $hand = array_values($hand);
        break;
      case "EVR094": case "EVR095": case "EVR096":
        AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "EVR097": case "EVR098": case "EVR099":
        AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "EVR104":
        AddDecisionQueue("FINDINDICES", $defPlayer, "AURACLASS,");
        AddDecisionQueue("MULTIZONEFORMAT", $defPlayer, "THEIRAURAS", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MULTIZONEDESTROY", $mainPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, "ARC112", 1);
        AddDecisionQueue("PUTPLAY", $mainPlayer, "-", 1);
        break;
      case "EVR105":
        if(GetClassState($mainPlayer, $CS_NumAuras) >= 3) AddCurrentTurnEffect("EVR105", $defPlayer);
        break;
      case "EVR110": case "EVR111": case "EVR112":
        AddDecisionQueue("FINDINDICES", $mainPlayer, "GYNAA");
        AddDecisionQueue("MAYCHOOSEDISCARD", $mainPlayer, "<-", 1);
        AddDecisionQueue("REMOVEDISCARD", $mainPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $mainPlayer, "-", 1);
        break;
      case "EVR113": case "EVR114": case "EVR115":
        if(GetClassState($mainPlayer, $CS_NumAuras) > 0) PummelHit();
        break;
      case "EVR138":
        FractalReplicationStats("Hit");
        break;
      case "EVR156":
        AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
        AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
        AddDecisionQueue("HANDCARD", $defPlayer, "-", 1);
        AddDecisionQueue("REVEALCARD", $defPlayer, "-", 1);
        AddDecisionQueue("BINGO", $mainPlayer, "-", 1);
        break;
      default: break;
    }
  }

  function HeaveValue($cardID)
  {
    switch($cardID)
    {
      case "EVR021": return 3;
      case "EVR024": case "EVR025": case "EVR026": return 3;
      default: return 0;
    }
  }

  function HeaveIndices()
  {
    global $mainPlayer;
    if(ArsenalFull($mainPlayer)) return "";//Heave does nothing if arsenal is full
    $hand = &GetHand($mainPlayer);
    $heaveIndices = "";
    for($i=0; $i<count($hand); $i+=HandPieces())
    {
      if(HeaveValue($hand[$i]) > 0)
      {
        if($heaveIndices != "") $heaveIndices .= ",";
        $heaveIndices .= $i;
      }
    }
    return $heaveIndices;
  }

  function Heave()
  {
    global $mainPlayer;
    AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "You may choose to Heave a card or pass.");
    AddDecisionQueue("FINDINDICES", $mainPlayer, "HEAVE");
    AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1, 1);
    AddDecisionQueue("MULTIREMOVEHAND", $mainPlayer, "-", 1);
    AddDecisionQueue("HEAVE", $mainPlayer, "-", 1);
  }

  function HelmOfSharpEyePlayable()
  {
    global $currentPlayer, $combatChainState, $CCS_CachedTotalAttack, $combatChain;
    if(count($combatChain) > 0 && CardType($combatChain[0]) == "W" && $combatChainState[$CCS_CachedTotalAttack] > (AttackValue($combatChain[0]) * 2)) return true;
    $character = &GetPlayerCharacter($currentPlayer);
    for($i=0; $i<count($character); $i+=CharacterPieces())
    {
      if(cardType($character[$i]) != "W") continue;
      $baseAttack = AttackValue($character[$i]);
      $buffedAttack = $baseAttack + $character[$i+3] + MainCharacterAttackModifiers($i, true) + AttackModifier($character[$i]);
      if($buffedAttack > $baseAttack*2) return true;
    }
    return false;
  }

  function BravoStarOfTheShowIndices()
  {
    global $mainPlayer;
    $earth = SearchHand($mainPlayer, "", "", -1, -1, "", "EARTH");
    $ice = SearchHand($mainPlayer, "", "", -1, -1, "", "ICE");
    $lightning = SearchHand($mainPlayer, "", "", -1, -1, "", "LIGHTNING");
    if($earth != "" && $ice != "" && $lightning != "")
    {
      $indices = CombineSearches($earth, $ice);
      $indices = CombineSearches($indices, $lightning);
      $count = SearchCount($indices);
      if($count > 3) $count = 3;
      return $count . "-" . SearchRemoveDuplicates($indices);
    }
    return "";
  }

  //Returns true if it should be destroyed
  function TalismanOfBalanceEndTurn()
  {
    global $mainPlayer, $defPlayer;
    if(ArsenalFull($mainPlayer)) return false;
    $mainArs = &GetArsenal($mainPlayer);
    $defArs = &GetArsenal($defPlayer);
    if(count($mainArs) < count($defArs))
    {
      $deck = &GetDeck($mainPlayer);
      $card = array_shift($deck);
      AddArsenal($card, $mainPlayer, "DECK", "DOWN");
      WriteLog("Talisman of Balance destroyed itself and put a card in your arsenal.");
      return true;
    }
    return false;
  }

  function LifeOfThePartyIndices()
  {
    global $currentPlayer;
    $auras = SearchMultizoneFormat(SearchItemsForCard("WTR162", $currentPlayer), "MYITEMS");
    $handCards = SearchMultizoneFormat(SearchHandForCard($currentPlayer, "WTR162"), "MYHAND");
    return CombineSearches($auras, $handCards);
  }

  function CoalescentMirageDestroyed()
  {
    global $mainPlayer;
    AddDecisionQueue("FINDINDICES", $mainPlayer, "COALESCENTMIRAGE");
    AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $mainPlayer, "-", 1);
    AddDecisionQueue("PLAYAURA", $mainPlayer, "<-", 1);
  }

  function MirragingMetamorphDestroyed()
  {
    global $mainPlayer;
    AddDecisionQueue("FINDINDICES", $mainPlayer, "AURACLASS,");
    AddDecisionQueue("MULTIZONEFORMAT", $mainPlayer, "MYAURAS", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
    AddDecisionQueue("MULTIZONETOKENCOPY", $mainPlayer, "-", 1);
  }

  function BloodOnHerHandsResolvePlay($userInput)
  {
    global $currentPlayer;
    for($i=0; $i<count($userInput); ++$i)
    {
      switch($userInput[$i])
      {
        case "Buff_Weapon":
          WriteLog("Blood on Her Hands gives a weapon +1 this turn.");
          AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a weapon to give +1", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDMZBUFF", $currentPlayer, "EVR055-1", 1);
          break;
        case "Go_Again":
          WriteLog("Blood on Her Hands gives a weapon Go Again this turn.");
          AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a weapon to give Go Again", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDMZBUFF", $currentPlayer, "EVR055-2", 1);
          break;
        case "Another_Swing":
          WriteLog("Blood on Her Hands gives a weapon a second attack this turn.");
          AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a weapon to give a second attack", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDMZUSES", $currentPlayer, "1", 1);
          break;
        default: break;
      }
    }
  }

  function FractalReplicationStats($stat)
  {
    global $chainLinks, $combatChain;
    $highestAttack = 0;
    $highestBlock = 0;
    $hasPhantasm = false;
    $hasGoAgain = false;
    for($i=0; $i<count($chainLinks); ++$i)
    {
      for($j=0; $j<count($chainLinks[$i]); $j+=ChainLinksPieces())
      {
        if($chainLinks[$i][$j+2] == "1" && $chainLinks[$i][$j] != "EVR138" && CardClass($chainLinks[$i][$j]) == "ILLUSIONIST" && CardType($chainLinks[$i][$j]) == "AA")
        {
          if($stat == "Hit")
          {
            ProcessHitEffect($chainLinks[$i][$j]);
          }
          elseif ($stat == "Ability")
          {
            PlayAbility($chainLinks[$i][$j], "HAND", 0);
          }
          else
          {
            $attack = AttackValue($chainLinks[$i][$j]);
            if($attack > $highestAttack) $highestAttack = $attack;
            $block = BlockValue($chainLinks[$i][$j]);
            if($block > $highestBlock) $highestBlock = $block;
            if(!$hasPhantasm) $hasPhantasm = HasPhantasm($chainLinks[$i][$j]);
            if(!$hasGoAgain) $hasGoAgain = HasGoAgain($chainLinks[$i][$j]);
          }
        }
      }
    }
    for($i=0; $i<count($combatChain); $i+=CombatChainPieces())
    {
      if($combatChain[$i] != "EVR138" && CardClass($combatChain[$i]) == "ILLUSIONIST" && CardType($combatChain[$i]) == "AA")
      {
        if($stat == "Hit")
        {
          ProcessHitEffect($combatChain[$i]);
        }
        elseif ($stat == "Ability")
        {
            PlayAbility($combatChain[$i], "HAND", 0);
        }
        else
        {
          $attack = AttackValue($combatChain[$i]);
          if($attack > $highestAttack) $highestAttack = $attack;
          $block = BlockValue($combatChain[$i]);
          if($block > $highestBlock) $highestBlock = $block;
          if(!$hasPhantasm) $hasPhantasm = HasPhantasm($combatChain[$i]);
          if(!$hasGoAgain) $hasGoAgain = HasGoAgain($combatChain[$i]);
        }
      }
    }
    switch($stat)
    {
      case "Attack": return $highestAttack;
      case "Block": return $highestBlock;
      case "Phantasm": return $hasPhantasm;
      case "GoAgain": return $hasGoAgain;
      default: return 0;
    }
  }

  function TalismanOfCremationBanishPlay()
  {
    global $currentPlayer;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You may choose a card to banish with Talisman of Cremation.");
    AddDecisionQueue("FINDINDICES", $otherPlayer, "GY");
    AddDecisionQueue("MAYCHOOSETHEIRDISCARD", $currentPlayer, "<-", 1);
    AddDecisionQueue("TALISMANOFCREMATION", $otherPlayer, "-", 1);
  }

  function ShatterIndices($player, $pendingDamage)
  {
    $character = &GetPlayerCharacter($player);
    $indices = "";
    for($i=0; $i<count($character); $i+=CharacterPieces())
    {
      if($character[$i+6] == 1 && $character[$i+1] != 0 && CardType($character[$i]) == "E" && (BlockValue($character[$i]) - $character[$i+4]) < $pendingDamage)
      {
        if($indices != "") $indices .= ",";
        $indices .= $i;
      }
    }
    return $indices;
  }

  function KnickKnackIndices($player)
  {
    $deck = &GetDeck($player);
    $indices = "";
    for($i=0; $i<count($deck); $i+=DeckPieces())
    {
      if(CardSubType($deck[$i]) == "Item")
      {
        $name = CardName($deck[$i]);
        if(str_contains($name, "Potion") || str_contains($name, "Talisman") || str_contains($name, "Amulet"))
        {
          if($indices != "") $indices .= ",";
          $indices .= $i;
        }
      }
    }
    return $indices;
  }

  function CashOutIndices($player)
  {
    $equipIndices = SearchMultizoneFormat(GetEquipmentIndices($player), "MYCHAR");
    $weaponIndices = WeaponIndices($player, $player);
    $itemIndices = SearchMultizoneFormat(SearchItems($player, "A"), "MYITEMS");
    $rv = CombineSearches($equipIndices, $weaponIndices);
    return CombineSearches($rv, $itemIndices);
  }
?>
