<?php

  function WTRAbilityType($cardID, $index=-1)
  {
    switch ($cardID)
    {
      case "WTR003": return "AA";
      case "WTR004": return "A";
      case "WTR005": return "I";
      case "WTR038": case "WTR039": return "A";
      case "WTR040": return "AA";
      case "WTR041": case "WTR042": return "A";
      case "WTR078": return "AA";
      case "WTR080": return "AR";
      case "WTR115": return "AA";
      case "WTR116": return "A";
      case "WTR150": return "I";
      case "WTR151": return "I";
      case "WTR152": return "A";
      case "WTR154": return "AR";
      case "WTR153": return "A";
      case "WTR162": return "A";
      case "WTR170": return "I";
      case "WTR171": case "WTR172": return "A";
      default: return "";
    }
  }

  function WTRAbilityHasGoAgain($cardID)
  {
    global $currentPlayer;
    switch ($cardID)
    {
      case "WTR038": case "WTR039": return true;
      case "WTR041": return true;
      case "WTR116": return true;
      case "WTR152": return true;
      case "WTR153": return true;
      case "WTR162": return GetDieRoll($currentPlayer) <= 4;
      case "WTR171": return true;
      default: return false;
    }
  }

  function WTREffectAttackModifier($cardID)
  {
    switch ($cardID)
    {
      case "WTR007": return 2;
      case "WTR017": return NumNonEquipmentDefended() < 2 ? 4 : 0;
      case "WTR018": return NumNonEquipmentDefended() < 2 ? 3 : 0;
      case "WTR019": return NumNonEquipmentDefended() < 2 ? 2 : 0;
      case "WTR032": return 3;
      case "WTR033": return 2;
      case "WTR034": return 1;
      case "WTR035": return 5;
      case "WTR036": return 4;
      case "WTR037": return 3;
      case "WTR066": case "WTR067": case "WTR068": return -2;
      case "WTR069": return 3;
      case "WTR070": return 2;
      case "WTR071": return 1;
      case "WTR116": return 1;
      case "WTR129": return 3;
      case "WTR130": return 2;
      case "WTR131": return 1;
      case "WTR141": return 3;
      case "WTR142": return 2;
      case "WTR143": return 1;
      case "WTR144": return 3;
      case "WTR145": return 2;
      case "WTR146": return 1;
      case "WTR147": return 3;
      case "WTR148": return 2;
      case "WTR149": return 1;
      case "WTR153": return 2;
      case "WTR159": return 2;
      case "WTR161": return 4;
      case "WTR162": return 2;
      case "WTR171": return 2;
      case "WTR185": return 1;
      case "WTR200": case "WTR201": case "WTR202": return 1;
      case "WTR218": return 3;
      case "WTR219": return 2;
      case "WTR220": return 1;
      case "WTR221": return 6;
      case "WTR222": return 5;
      case "WTR223": return 4;
      default: return 0;
    }
  }

  function WTRCombatEffectActive($cardID, $attackID)
  {
    global $mainPlayer;
    switch ($cardID)
    {
      //Brute
      case "WTR007": return ClassContains($attackID, "BRUTE", $mainPlayer);
      case "WTR017": case "WTR018": case "WTR019": return ClassContains($attackID, "BRUTE", $mainPlayer);
      case "WTR032": case "WTR033": case "WTR034": return CardType($attackID) == "AA" && ClassContains($attackID, "BRUTE", $mainPlayer);
      case "WTR035": case "WTR036": case "WTR037": return ClassContains($attackID, "BRUTE", $mainPlayer);
      //Guardian
      case "WTR038": case "WTR039": return CardType($attackID) == "AA" && CardCost($attackID) >= 3;//TODO: Make last the whole turn
      case "WTR066": case "WTR067": case "WTR068": return true;
      case "WTR069": case "WTR070": case "WTR071": return CardType($attackID) == "AA" && ClassContains($attackID, "GUARDIAN", $mainPlayer);
      //Warrior
      case "WTR116": return CardType($attackID) == "W";
      case "WTR129": case "WTR130": case "WTR131": return CardType($attackID) == "W";
      case "WTR141": case "WTR142": case "WTR143": return CardType($attackID) == "W";
      case "WTR144": case "WTR145": case "WTR146": return CardType($attackID) == "W";
      case "WTR147": case "WTR148": case "WTR149": return CardType($attackID) == "W";
      //Generics
      case "WTR153": return CardType($attackID) == "AA" && CardCost($attackID) >= 2;
      case "WTR159": return true;
      case "WTR161": return true;
      case "WTR162": return true;
      case "WTR171": return true;
      case "WTR185": return true;
      case "WTR197": return true;
      case "WTR200": case "WTR201": case "WTR202": return true;
      case "WTR218": case "WTR219": case "WTR220": return CardType($attackID) == "AA" && CardCost($attackID) <= 1;
      case "WTR221": case "WTR222": case "WTR223": return CardType($attackID) == "AA" && CardCost($attackID) >= 2;
      default: return false;
    }
  }

  function WTRCardType($cardID)
  {
    switch ($cardID)
    {
      case "WTR000": return "R";
      case "WTR113": case "WTR114": case "ARC002": return "C";
      case "WTR115": case "CRU177": return "W";
      case "WTR155": case "WTR156": case "WTR157": case "WTR158": case "WTR117": return "E";
      //Brute
      case "WTR001": case "WTR002": return "C";
      case "WTR003": return "W";
      case "WTR004": case "WTR005": return "E";
      case "WTR006": return "AA";
      case "WTR007": return "A";
      case "WTR008": return "DR";
      case "WTR009": return "A";
      case "WTR010": return "I";
      case "WTR011": case "WTR012": case "WTR013": return "AA";
      case "WTR014": case "WTR015": case "WTR016": return "AA";
      case "WTR017": case "WTR018": case "WTR019": return "A";
      case "WTR020": case "WTR021": case "WTR022": return "AA";
      case "WTR023": case "WTR024": case "WTR025": return "AA";
      case "WTR026": case "WTR027": case "WTR028": return "AA";
      case "WTR029": case "WTR030": case "WTR031": return "AA";
      case "WTR032": case "WTR033": case "WTR034": return "A";
      case "WTR035": case "WTR036": case "WTR037": return "A";
      //Guardian
      case "WTR038": case "WTR039": return "C";
      case "WTR040": return "W";
      case "WTR041": case "WTR042": return "E";
      case "WTR043": case "WTR044": case "WTR045": return "AA";
      case "WTR046": case "WTR047": return "A";
      case "WTR048": case "WTR049": case "WTR050": return "AA";
      case "WTR051": case "WTR052": case "WTR053": return "DR";
      case "WTR054": case "WTR055": case "WTR056": return "A";
      case "WTR057": case "WTR058": case "WTR059": return "AA";
      case "WTR060": case "WTR061": case "WTR062": return "AA";
      case "WTR063": case "WTR064": case "WTR065": return "AA";
      case "WTR066": case "WTR067": case "WTR068": return "AA";
      case "WTR069": case "WTR070": case "WTR071": return "A";
      case "WTR072": case "WTR073": case "WTR074": return "A";
      case "WTR075": return "T";
      //Ninja
      case "WTR076": case "WTR077": return "C";
      case "WTR078": return "W";
      case "WTR079": case "WTR080": return "E";
      case "WTR082": return "AR";
      case "WTR081": case "WTR083": case "WTR084": case "WTR085": return "AA";
      case "WTR086": case "WTR087": case "WTR088": return "AA";
      case "WTR089": case "WTR090": case "WTR091": return "AA";
      case "WTR092": case "WTR093": case "WTR094": return "DR";
      case "WTR095": case "WTR096": case "WTR097": return "AA";
      case "WTR098": case "WTR099": case "WTR100": return "AA";
      case "WTR101": case "WTR102": case "WTR103": return "AA";
      case "WTR104": case "WTR105": case "WTR106": return "AA";
      case "WTR107": case "WTR108": case "WTR109": return "AA";
      case "WTR110": case "WTR111": case "WTR112": return "AA";
      case "WTR116": return "E";
      case "WTR118": return "AR";
      case "WTR119": return "A";
      case "WTR120": case "WTR121": return "AR";
      case "WTR122": return "A";
      case "WTR123": case "WTR124": case "WTR125": return "AR";
      case "WTR126": case "WTR127": case "WTR128": return "DR";
      case "WTR129": case "WTR130": case "WTR131": return "A";
      case "WTR132": case "WTR133": case "WTR134": return "AR";
      case "WTR135": case "WTR136": case "WTR137": return "AR";
      case "WTR138": case "WTR139": case "WTR140": return "AR";
      case "WTR141": case "WTR142": case "WTR143": return "A";
      case "WTR144": case "WTR145": case "WTR146": return "A";
      case "WTR147": case "WTR148": case "WTR149": return "A";
      case "WTR150": case "WTR151": case "WTR152": case "WTR153": case "WTR154": return "E";
      case "WTR159": return "AA";
      case "WTR160": return "A";
      case "WTR161": return "AA";
      case "WTR162": return "A";
      case "WTR163": return "I";
      case "WTR164": case "WTR165": case "WTR166": return "AA";
      case "WTR167": case "WTR168": case "WTR169": return "AA";
      case "WTR170": case "WTR171": case "WTR172": return "A";
      case "WTR173": case "WTR174": case "WTR175": return "I";
      case "WTR176": case "WTR177": case "WTR178": return "AA";
      case "WTR179": case "WTR180": case "WTR181": return "AA";
      case "WTR182": case "WTR183": case "WTR184": return "AA";
      case "WTR185": case "WTR186": case "WTR187": return "AA";
      case "WTR188": case "WTR189": case "WTR190": return "AA";
      case "WTR191": case "WTR192": case "WTR193": return "AA";
      case "WTR194": case "WTR195": case "WTR196": return "AA";
      case "WTR197": case "WTR198": case "WTR199": return "AA";
      case "WTR200": case "WTR201": case "WTR202": return "AA";
      case "WTR203": case "WTR204": case "WTR205": return "AA";
      case "WTR206": case "WTR207": case "WTR208": return "AR";
      case "WTR209": case "WTR210": case "WTR211": return "AR";
      case "WTR212": case "WTR213": case "WTR214": return "DR";
      case "WTR215": case "WTR216": case "WTR217": return "DR";
      case "WTR218": case "WTR219": case "WTR220": return "A";
      case "WTR221": case "WTR222": case "WTR223": return "A";
      case "WTR225": return "T";
      default: return "";
    }
  }

  function WTRCardSubtype($cardID)
  {
    switch ($cardID)
    {
      case "WTR003": return "Club";
      case "WTR004": return "Legs";
      case "WTR005": return "Chest";
      case "WTR040": return "Hammer";
      case "WTR041": return "Chest";
      case "WTR042": return "Head";
      case "WTR046": case "WTR047":
      case "WTR054": case "WTR055": case "WTR056":
      case "WTR069":  case "WTR070": case "WTR071":
      case "WTR072": case "WTR073": case "WTR074": case "WTR075": return "Aura";
      case "WTR078": return "Dagger";
      case "WTR079": return "Head";
      case "WTR080": return "Arms";
      case "WTR115": return "Sword";
      case "WTR116": return "Arms";
      case "WTR117": return "Legs";
      case "WTR150": return "Chest";
      case "WTR151": return "Head";
      case "WTR152": return "Chest";
      case "WTR153": return "Arms";
      case "WTR154": return "Legs";
      case "WTR155": return "Head";
      case "WTR156": return "Chest";
      case "WTR157": return "Arms";
      case "WTR158": return "Legs";
      case "WTR162": return "Item";
      case "WTR170": case "WTR171": case "WTR172": return "Item";
      case "WTR225": return "Aura";
      default: return "";
    }
  }

  function WTRCardCost($cardID)
  {
    switch ($cardID)
    {
      case "WTR000": return -1;
      //Brute
      case "WTR003": return 2;
      case "WTR004": case "WTR005": return 0;
      case "WTR006": return 3;
      case "WTR007": return 1;
      case "WTR008": case "WTR009": return 0;
      case "WTR010": return 1;
      case "WTR011": case "WTR012": case "WTR013": return 2;
      case "WTR014": case "WTR015": case "WTR016": return 1;
      case "WTR017": case "WTR018": case "WTR019": return 0;
      case "WTR020": case "WTR021": case "WTR022": return 1;
      case "WTR023": case "WTR024": case "WTR025": return 2;
      case "WTR026": case "WTR027": case "WTR028": return 3;
      case "WTR029": case "WTR030": case "WTR031": return 2;
      case "WTR032": case "WTR033": case "WTR034": return 1;
      case "WTR035": case "WTR036": case "WTR037": return 0;
      //Guardian
      case "WTR038": case "WTR039": return 2;
      case "WTR040": return 3;
      case "WTR041": case "WTR042": return 1;
      case "WTR043": return 7;
      case "WTR044": return 5;
      case "WTR045": return 6;
      case "WTR046": return 2;
      case "WTR047": return 3;
      case "WTR048": case "WTR049": case "WTR050": return 5;
      case "WTR051": case "WTR052": case "WTR053": return 2;
      case "WTR054": case "WTR055": case "WTR056": return 2;
      case "WTR057": case "WTR058": case "WTR059": return 4;
      case "WTR060": case "WTR061": case "WTR062": return 3;
      case "WTR063": case "WTR064": case "WTR065": return 3;
      case "WTR066": case "WTR067": case "WTR068": return 4;
      case "WTR069": case "WTR070": case "WTR071": return 2;
      case "WTR072": case "WTR073": case "WTR074": return 2;
      case "WTR075": return 0;
      //Ninja
      case "WTR078": return 1;
      case "WTR081": case "WTR082": return 0;
      case "WTR083": case "WTR084": case "WTR085": return 1;
      case "WTR086": case "WTR087": case "WTR088": return 0;
      case "WTR089": case "WTR090": case "WTR091": return 1;
      case "WTR092": case "WTR093": case "WTR094": return 0;
      case "WTR095": case "WTR096": case "WTR097": return 2;
      case "WTR098": case "WTR099": case "WTR100": return 0;
      case "WTR101": case "WTR102": case "WTR103": return 1;
      case "WTR104": case "WTR105": case "WTR106": return 0;
      case "WTR107": case "WTR108": case "WTR109": return 2;
      case "WTR110": case "WTR111": case "WTR112": return 0;
      //Warrior
      case "WTR115": case "WTR116": return 1;
      case "WTR118": return 0;
      case "WTR119": return 1;
      case "WTR120": return 2;
      case "WTR121": return 1;
      case "WTR122": return 0;
      case "WTR123": case "WTR124": case "WTR125": return 3;
      case "WTR126": case "WTR127": case "WTR128": return 1;
      case "WTR129": case "WTR130": case "WTR131": return 1;
      case "WTR132": case "WTR133": case "WTR134": return 0;
      case "WTR135": case "WTR136": case "WTR137": return 2;
      case "WTR138": case "WTR139": case "WTR140": return 1;
      case "WTR141": case "WTR142": case "WTR143": return 0;
      case "WTR144": case "WTR145": case "WTR146": return 2;
      case "WTR147": case "WTR148": case "WTR149": return 1;
      //Generics
      case "WTR151": case "WTR152": case "WTR153": case "WTR154": return 0;
      case "WTR159": return 0;
      case "WTR160": return 1;
      case "WTR161": return 3;
      case "WTR162": return 0;
      case "WTR164": case "WTR165": case "WTR166": return 2;
      case "WTR167": case "WTR168": case "WTR169": return 0;
      case "WTR163": case "WTR170": case "WTR171": case "WTR172": return 0;
      case "WTR173": case "WTR174": case "WTR175": return 0;
      case "WTR176": case "WTR177": case "WTR178": return 3;
      case "WTR179": case "WTR180": case "WTR181": return 2;
      case "WTR182": case "WTR183": case "WTR184": return 1;
      case "WTR185": case "WTR186": case "WTR187": return 1;
      case "WTR188": case "WTR189": case "WTR190": return 3;
      case "WTR191": case "WTR192": case "WTR193": return 0;
      case "WTR194": case "WTR195": case "WTR196": return 0;
      case "WTR197": case "WTR198": case "WTR199": return 2;
      case "WTR200": case "WTR201": case "WTR202": return 3;
      case "WTR203": case "WTR204": case "WTR205": return 0;
      case "WTR206": case "WTR207": case "WTR208": return 2;
      case "WTR209": case "WTR210": case "WTR211": return 1;
      case "WTR212": case "WTR213": case "WTR214": return 3;
      case "WTR215": case "WTR216": case "WTR217": return 0;
      case "WTR218": case "WTR219": case "WTR220": return 0;
      case "WTR221": case "WTR222": case "WTR223": return 3;
      default: return 0;
    }
  }

  function WTRPitchValue($cardID)
  {
    switch ($cardID)
    {
      case "WTR000": return 3;
      //Brute
      case "WTR001": case "WTR002": case "WTR003": case "WTR004": case "WTR005": return 0;
      case "WTR006": return 1;
      case "WTR007": return 2;
      case "WTR008": case "WTR009": return 3;
      case "WTR010": return 2;
      case "WTR011": case "WTR014": case "WTR017": case "WTR020": case "WTR023": case "WTR026": case "WTR029": case "WTR032": case "WTR035": return 1;
      case "WTR012": case "WTR015": case "WTR018": case "WTR021": case "WTR024": case "WTR027": case "WTR030": case "WTR033": case "WTR036": return 2;
      case "WTR013": case "WTR016": case "WTR019": case "WTR022": case "WTR025": case "WTR028": case "WTR031": case "WTR034": case "WTR037": return 3;
      //Guardian
      case "WTR038": case "WTR039": case "WTR040": return 0;
      case "WTR041": case "WTR042": return 0;
      case "WTR043": case "WTR044": return 1;
      case "WTR045": return 3;
      case "WTR046": return 2;
      case "WTR047": return 3;
      case "WTR048": case "WTR051": case "WTR054": case "WTR057": case "WTR060": case "WTR063": case "WTR066": case "WTR069": case "WTR072": return 1;
      case "WTR049": case "WTR052": case "WTR055": case "WTR058": case "WTR061": case "WTR064": case "WTR067": case "WTR070": case "WTR073": return 2;
      case "WTR050": case "WTR053": case "WTR056": case "WTR059": case "WTR062": case "WTR065": case "WTR068": case "WTR071": case "WTR074": return 3;
      case "WTR075": return 0;
      //Ninja
      case "WTR076": case "WTR077": case "WTR078": case "WTR079": case "WTR080": return 0;
      case "WTR081": return 3;
      case "WTR082": return 1;
      case "WTR083": case "WTR084": return 2;
      case "WTR085": return 1;
      case "WTR086": case "WTR089": case "WTR092": case "WTR095": case "WTR098": case "WTR101": case "WTR104": case "WTR107": case "WTR110": return 1;
      case "WTR087": case "WTR090": case "WTR093": case "WTR096": case "WTR099": case "WTR102": case "WTR105": case "WTR108": case "WTR111": return 2;
      case "WTR088": case "WTR091": case "WTR094": case "WTR097": case "WTR100": case "WTR103": case "WTR106": case "WTR109": case "WTR112": return 3;
      //Warrior
      case "WTR113": case "WTR114": case "WTR115": case "WTR116": case "WTR117": return 0;
      case "WTR118": return 3;
      case "WTR119": case "WTR120": return 1;
      case "WTR121": case "WTR122": return 2;
      case "WTR123": case "WTR126": case "WTR129": case "WTR132": case "WTR135": case "WTR138": case "WTR141": case "WTR144": case "WTR147": return 1;
      case "WTR124": case "WTR127": case "WTR130": case "WTR133": case "WTR136": case "WTR139": case "WTR142": case "WTR145": case "WTR148": return 2;
      case "WTR125": case "WTR128": case "WTR131": case "WTR134": case "WTR137": case "WTR140": case "WTR143": case "WTR146": case "WTR149": return 3;
      case "WTR150": case "WTR151": case "WTR152": case "WTR153": case "WTR154": case "WTR155": case "WTR156": case "WTR157": case "WTR158": return 0;
      //Generics
      case "WTR159": return 1;
      case "WTR160": return 2;
      case "WTR161": return 3;
      case "WTR162": return 3;
      case "WTR163": return 2;
      case "WTR164": return 1;
      case "WTR165": return 2;
      case "WTR167": return 1;
      case "WTR168": return 2;
      case "WTR169": return 3;
      case "WTR170": case "WTR171": case "WTR172": return 3;
      case "WTR173": case "WTR176": case "WTR179": case "WTR182": case "WTR185": case "WTR188": case "WTR191": case "WTR194": case "WTR197": return 1;
      case "WTR200": case "WTR203": case "WTR206": case "WTR209": case "WTR212": case "WTR215": case "WTR218": case "WTR221": return 1;
      case "WTR174": case "WTR177": case "WTR180": case "WTR183": case "WTR186": case "WTR189": case "WTR192": case "WTR195": case "WTR198": return 2;
      case "WTR201": case "WTR204": case "WTR207": case "WTR210": case "WTR213": case "WTR216": case "WTR219": case "WTR222": return 2;
      case "WTR175": case "WTR178": case "WTR181": case "WTR184": case "WTR187": case "WTR190": case "WTR193": case "WTR196": case "WTR199": return 3;
      case "WTR202": case "WTR205": case "WTR208": case "WTR211": case "WTR214": case "WTR217": case "WTR220": case "WTR223": return 3;
      default: return 3;
    }
  }

  function WTRBlockValue($cardID)
  {
    switch ($cardID)
    {
      case "WTR000": return 0;
      //Brute
      case "WTR001": case "WTR002": case "WTR003": return 0;
      case "WTR004": return 2;
      case "WTR005": return 1;
      case "WTR008": return 4;
      case "WTR010": return 0;
      //Guardian
      case "WTR038": case "WTR039": case "WTR040": return 0;
      case "WTR041": return 2;
      case "WTR042": return 1;
      case "WTR051": return 7;
      case "WTR052": return 6;
      case "WTR053": return 5;
      case "WTR075": return 0;
      //Ninja
      case "WTR076": case "WTR077": case "WTR078": return 0;
      case "WTR079": return 2;
      case "WTR080": return 1;
      case "WTR092": return 4;
      case "WTR093": return 3;
      case "WTR094": return 2;
      case "WTR098": case "WTR099": case "WTR100": case "WTR101": case "WTR102": case "WTR103": return 2;
      case "WTR107": case "WTR108": case "WTR109": return 2;
      case "WTR150": return 1;
      case "WTR155": case "WTR156": case "WTR157": case "WTR158": return 1;
      //Warrior
      case "WTR113": case "WTR114": case "WTR115": return 0;
      case "WTR116": return 2;
      case "WTR117": return 1;
      case "WTR126": return 6;
      case "WTR127": return 5;
      case "WTR128": return 4;
      //Generics
      case "WTR151": case "WTR152": case "WTR153": case "WTR154": return 0;
      case "WTR160": return 2;
      case "WTR162": return 0;
      case "WTR163": return 0;
      case "WTR164": case "WTR165": case "WTR166": return 2;
      case "WTR167": case "WTR168": case "WTR169": return 2;
      case "WTR170": case "WTR171": case "WTR172": return 0;
      case "WTR173": case "WTR174": case "WTR175": return 0;
      case "WTR176": case "WTR177": case "WTR178": return 2;
      case "WTR179": case "WTR180": case "WTR181": return 2;
      case "WTR182": case "WTR183": case "WTR184": return 2;
      case "WTR185": case "WTR186": case "WTR187": return 2;
      case "WTR191": case "WTR192": case "WTR193": return 2;
      case "WTR194": case "WTR195": case "WTR196": return 2;
      case "WTR197": case "WTR198": case "WTR199": return 2;
      case "WTR200": case "WTR201": case "WTR202": return 2;
      case "WTR206": case "WTR207": case "WTR208": return 2;
      case "WTR209": case "WTR210": case "WTR211": return 2;
      case "WTR212": return 7;
      case "WTR213": return 6;
      case "WTR214": return 5;
      case "WTR215": return 4;
      case "WTR216": return 3;
      case "WTR217": return 2;
      case "WTR218": case "WTR219": case "WTR220": return 2;
      case "WTR221": case "WTR222": case "WTR223": return 2;
      default: return 3;
    }
  }

  function WTRAttackValue($cardID)
  {
    switch ($cardID)
    {
      //Brute
      case "WTR003": return 4;
      case "WTR006": return 9;
      case "WTR029": return 8;
      case "WTR020": case "WTR026": case "WTR030": return 7;
      case "WTR011": case "WTR014": case "WTR021": case "WTR023": case "WTR027": case "WTR031": return 6;
      case "WTR012": case "WTR015": case "WTR022": case "WTR024": case "WTR028": return 5;
      case "WTR013": case "WTR016": case "WTR025": return 4;
      //Guardian
      case "WTR040": return 4;
      case "WTR043": return 11;
      case "WTR044": case "WTR048": return 9;
      case "WTR045": case "WTR049": case "WTR057": case "WTR066": return 8;
      case "WTR050": case "WTR058": case "WTR060": case "WTR063": case "WTR067": return 7;
      case "WTR059": case "WTR061": case "WTR064": case "WTR068": return 6;
      case "WTR062": case "WTR065": return 5;
      //Ninja
      case "WTR078": case "WTR100": case "WTR106": case "WTR112": return 1;
      case "WTR081": case "WTR088": case "WTR091": case "WTR099": case "WTR103": case "WTR105": case "WTR111": return 2;
      case "WTR087": case "WTR090": case "WTR097": case "WTR098": case "WTR102": case "WTR104": case "WTR109": case "WTR110": return 3;
      case "WTR083": case "WTR084": case "WTR086": case "WTR089": case "WTR096": case "WTR101": case "WTR108": return 4;
      case "WTR085": case "WTR095": case "WTR107": return 5;
      //Warrior
      case "WTR115": return 3;
      //Generic
      case "WTR159": return 5;
      case "WTR161": return 4;
      case "WTR164": return 6;
      case "WTR165": return 5;
      case "WTR166": return 4;
      case "WTR167": return 4;
      case "WTR168": return 3;
      case "WTR169": return 2;
      case "WTR176": return 7;
      case "WTR177": return 6;
      case "WTR178": return 5;
      case "WTR179": return 6;
      case "WTR180": return 5;
      case "WTR181": return 4;
      case "WTR182": return 5;
      case "WTR183": return 4;
      case "WTR184": return 3;
      case "WTR185": return 4;
      case "WTR186": return 3;
      case "WTR187": return 2;
      case "WTR188": return 7;
      case "WTR189": return 6;
      case "WTR190": return 5;
      case "WTR191": return 4;
      case "WTR192": return 3;
      case "WTR193": return 2;
      case "WTR194": return 3;
      case "WTR195": return 2;
      case "WTR196": return 1;
      case "WTR197": return 6;
      case "WTR198": return 5;
      case "WTR199": return 4;
      case "WTR200": return 7;
      case "WTR201": return 6;
      case "WTR202": return 5;
      case "WTR203": return 4;
      case "WTR204": return 3;
      case "WTR205": return 2;
      default: return 0;
    }
  }

  function WTRHasGoAgain($cardID)
  {
    global $myDeck;
    switch ($cardID)
    {
      //Brute
      case "WTR017": case "WTR018": case "WTR019": return true;
      case "WTR032": case "WTR033": case "WTR034": return true;
      case "WTR035": case "WTR036": case "WTR037": return true;
      //Guardian
      case "WTR046": return true;
      case "WTR054": case "WTR055": case "WTR056": return true;
      case "WTR069": case "WTR070": case "WTR071": return true;
      case "WTR072": case "WTR073": case "WTR074": return true;
      //Ninja
      case "WTR083": case "WTR084":
      case "WTR095": case "WTR096": case "WTR097": return ComboActive($cardID);
      case "WTR098": case "WTR099": case "WTR100": return true;
      case "WTR101": case "WTR102": case "WTR103": return true;
      case "WTR104": case "WTR105": case "WTR106": return ComboActive($cardID);
      case "WTR107": case "WTR108": case "WTR109": return true;
      case "WTR110": case "WTR111": case "WTR112": return ComboActive($cardID);
      //Warrior
      case "WTR119": case "WTR122": return true;
      case "WTR129": case "WTR130": case "WTR131": return true;
      case "WTR141": case "WTR142": case "WTR143": return true;
      case "WTR144": case "WTR145": case "WTR146": return true;
      case "WTR147": case "WTR148": case "WTR149": return true;
      //Generics
      case "WTR161": return count($myDeck) == 0;
      case "WTR218": case "WTR219": case "WTR220": return true;
      case "WTR223": case "WTR222": case "WTR221": return true;
      default: return false;
    }
  }

  function WTRPlayAbility($cardID, $from, $resourcesPaid, $additionalCosts)
  {
    global $mainPlayer, $combatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $currentPlayer, $defPlayer, $actionPoints;
    global $CS_DamagePrevention;
    switch ($cardID)
    {
      case "WTR054": case "WTR055": case "WTR056":
        if(CountPitch(GetPitch($currentPlayer), 3) >= 1) MyDrawCard();
        return CountPitch(GetPitch($currentPlayer), 3) . " cards in pitch.";
      case "WTR004":
        $roll = GetDieRoll($currentPlayer);
        $actionPoints += intval($roll/2);
        return "Rolled $roll and gains " . intval($roll/2) . " action points.";
      case "WTR005":
        $resources = &GetResources($currentPlayer);
        $roll = GetDieRoll($currentPlayer);
        $resources[0] += intval($roll/2);
        return "Rolled $roll and gains " . intval($roll/2) . " resources.";
      case "WTR006":
        Intimidate();
        return "Intimidates.";
      case "WTR007":
        $rv = "";
        $drew = 0;
        if(AttackValue($additionalCosts) >= 6)
        {
          $drew = 1;
          MyDrawCard();
          MyDrawCard();
          AddCurrentTurnEffect($cardID, $currentPlayer);
          if(!CurrentEffectPreventsGoAgain()) ++$actionPoints;//TODO: This is not strictly accurate, but good enough for now
          $rv .= "Gives your Brute attacks +2 this turn, draws 2 cards and gains go again.";
        }
        return $rv;
      case "WTR008":
        $damaged = false;
        if(AttackValue($additionalCosts) >= 6) { $damaged = true; DamageTrigger($mainPlayer, 2, "DAMAGE", $cardID); }
        return "Discarded a random card from your hand" . ($damaged ? " and does 2 damage." : ".");
      case "WTR009":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECK");
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("SANDSKETCH", $currentPlayer, "-");
        return "";
      case "WTR010":
        $roll = GetDieRoll($currentPlayer);
        IncrementClassState($currentPlayer, $CS_DamagePrevention, $roll);
        return "Prevents the next $roll damage that will be dealt to you this turn.";
      case "WTR011": case "WTR012": case "WTR013":
        $rv = "";
        if(AttackValue($additionalCosts) >= 6)
        {
          $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
          $rv .= "Discarded a 6 power card and gains go again.";
        }
        return $rv;
      case "WTR014": case "WTR015": case "WTR016":
        if(AttackValue($additionalCosts) >= 6)
        {
          MyDrawCard();
        }
        return "Discarded a random card from your hand.";
      case "WTR017": case "WTR018": case "WTR019":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        Intimidate();
        return "Intimidates and gives the next Brute attack this turn +" . EffectAttackModifier($cardID) . ".";
      case "WTR023": case "WTR024": case "WTR025":
        Intimidate();
        return "Intimidates.";
      case "WTR026": case "WTR027": case "WTR028":
        Intimidate();
        return "Intimidates.";
      case "WTR032": case "WTR033": case "WTR034":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        Intimidate();
        return "Intimidates.";
      case "WTR035": case "WTR036": case "WTR037":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Discarded a random card from your hand and gives the next Brute attack this turn +" . EffectAttackModifier($cardID) . ".";
      //Guardian
      case "WTR038": case "WTR039":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives your action cards with cost 3 or greater Dominate.";
      case "WTR041":
        PlayMyAura("WTR075");
        return "Creates a Seismic Surge token.";
      case "WTR042":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives you +1 Intellect until end of turn.";
      case "WTR047":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKCLASSAA,GUARDIAN");
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        return "Lets you to search for a Guardian attack card.";
      //Ninja
      case "WTR078":
        if(CountPitch(GetPitch($currentPlayer), 0, 0)) $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        return "";
      case "WTR082":
        MyDrawCard();
        return "Draws a card.";
      case "WTR092": case "WTR093": case "WTR094":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Gives the next blocking Combo card +2 this turn.";
      //Warrior
      case "WTR116":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Gives your next weapon attack +1 this turn.";
      case "WTR118":
        $s1 = "";
        $s2 = "";
        if(CardType($combatChain[0]) == "W")
        {
          $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
          $s1 = " gives your weapon attack go again";
        }
        if(RepriseActive())
        {
          MyDrawCard();
          $s2 = " draws a card";
        }
        return "Glint the Quicksilver" . $s1 . ($s1 != "" && $s2 != "" ? " and" : "") . $s2 . ".";
      case "WTR119": case "WTR122":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDMZBUFF", $mainPlayer, $cardID, 1);
        return "";
      case "WTR120":
        if(RepriseActive())
        {
          $options = GetChainLinkCards(($mainPlayer == 1 ? 2 : 1), "", "E,C");
          AddDecisionQueue("MAYCHOOSECOMBATCHAIN", $mainPlayer, $options);
          AddDecisionQueue("REMOVECOMBATCHAIN", $mainPlayer, "-", 1);
          AddDecisionQueue("ADDHAND", $defPlayer, "-", 1);
        }
        return "";
      case "WTR121":
        if(RepriseActive())
        {
          $ARs = SearchDeck($currentPlayer, "AR");
          AddDecisionQueue("CHOOSEDECK", $currentPlayer, $ARs);
          AddDecisionQueue("BANISH", $currentPlayer, "TCL");
          AddDecisionQueue("SHOWBANISHEDCARD", $currentPlayer, "-", 1);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        }
        return "";
      case "WTR123": case "WTR124": case "WTR125":
        if(CardType($combatChain[0]) != "W") return "Does nothing, because this is not a weapon attack.";
        return "Gives your weapon attack +" . AttackModifier($cardID) . ".";
      case "WTR126": case "WTR127": case "WTR128":
        $rv = "";
        if(CardType($combatChain[0]) == "W")
        {
          DamageTrigger($mainPlayer, 1, "DAMAGE", $cardID);
          $rv .= "DID";
        } else { $rv .= "Did NOT"; }
        $rv .= " deal 1 damage to the attacking hero.";
        return $rv;
      case "WTR129": case "WTR130": case "WTR131":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives your next weapon attack +" . EffectAttackModifier($cardID) . " and if it hits, it gains go again";
      case "WTR132": case "WTR133": case "WTR134":
        if(CardType($combatChain[0]) != "W") return "Does nothing, because this is not a weapon attack.";
        return "Gives your weapon attack +" . AttackModifier($cardID) . ".";
      case "WTR135": case "WTR136": case "WTR137":
        $log = "Gives your weapon attack +" . AttackModifier($cardID);
        if(RepriseActive()) { ApplyEffectToEachWeapon($cardID); $log .= " and gives weapons you control +1 for the rest of the turn"; }
        return $log . ".";
      case "WTR138": case "WTR139": case "WTR140":
        if(RepriseActive())
        {
          MyDrawCard();
          AddDecisionQueue("HANDTOPBOTTOM", $mainPlayer, "");
        }
        return "Gives your weapon attack +" . AttackModifier($cardID) . ".";
      case "WTR141": case "WTR142": case "WTR143":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives your next weapon attack +" . EffectAttackModifier($cardID) . ".";
      case "WTR144": case "WTR145": case "WTR146":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives your next weapon attack +" . EffectAttackModifier($cardID) . " and go again.";
      case "WTR147": case "WTR148": case "WTR149":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives your next weapon attack +" . EffectAttackModifier($cardID) . " and a hit effect.";
      case "WTR150":
        $resources = &GetResources($currentPlayer);
        $resources[0] += 1;
        return "Gain 1 resource.";
      case "WTR151":
        $indices = GetMyHandIndices();
        if($indices == "") return "";
        AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, count(GetHand($currentPlayer)) . "-" . $indices);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("HELMHOPEMERCHANT", $currentPlayer, "-", 1);
        return "";
      case "WTR152":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Reduces the resource cost of your next attack action card by 2.";
      case "WTR154":
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        return "Gives your current attack go again.";
      case "WTR159":
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Draw_a_card,2_Attack,Go_again");
        AddDecisionQueue("ESTRIKE", $currentPlayer, "-", 1);
        return "Puts a card from your hand to the bottom of your deck.";
      case "WTR160":
        MyDrawCard();
        MyDrawCard();
        $hand = GetHand($currentPlayer); //Get hand size after draw for correct health gain
        if($from == "ARS") GainHealth(count($hand), $currentPlayer);
        return "Draws 2 cards" . ($from == "ARS" ? " and gained " . count($hand) . " health" : "") . ".";
      case "WTR161":
        if(count(GetDeck($currentPlayer)) == 0) {
          $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Gains go again and +4.";
        }
        return $rv;
      case "WTR162":
        if($from == "PLAY")
        {
          $roll = GetDieRoll($currentPlayer);
          $rv = "Crazy Brew rolled " . $roll;
          if($roll <= 2)
          {
            LoseHealth(2, $currentPlayer);
            $rv .= " and lost you 2 health.";
          }
          else if($roll <= 4)
          {
            GainHealth(2, $currentPlayer);
            $rv .= " and gained you 2 health.";
          }
          else if($roll <= 6)
          {
            $resources = &GetResources($currentPlayer);
            AddCurrentTurnEffect($cardID, $currentPlayer);
            $resources[0] += 2;
            $actionPoints += 2;
            $rv .= " and gained 2 action points, resources, and power.";
          }
        }
        return $rv;
      case "WTR163":
        $actions = SearchDiscard($currentPlayer, "A");
        $attackActions = SearchDiscard($currentPlayer, "AA");
        if($actions == "") $actions = $attackActions;
        else if($attackActions != "") $actions = $actions . "," . $attackActions;
        if($actions == "") return "";
        AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "3-" . $actions);
        AddDecisionQueue("REMEMBRANCE", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        return "";
      case "WTR170":
        if($from == "PLAY")
        {
          $resources = &GetResources($currentPlayer);
          $resources[0] += 2;
        }
        return "";
      case "WTR171":
        if($from == "PLAY")
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "WTR172":
        if($from == "PLAY")
        {
          $actionPoints += 2;
        }
        return "";
      case "WTR173": GainHealth(3, $currentPlayer); return "Sigil of Solace gained 3 health.";
      case "WTR174": GainHealth(2, $currentPlayer); return "Sigil of Solace gained 2 health.";
      case "WTR175": GainHealth(1, $currentPlayer); return "Sigil of Solace gained 1 health.";
      case "WTR182": case "WTR183": case "WTR184":
        PlayMyAura("WTR225");
        return "Creates a Quicken token.";
      case "WTR191": case "WTR192": case "WTR193":
        if(IHaveLessHealth()) { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $rv = "Gains go again."; }
        return $rv;
      case "WTR194": case "WTR195": case "WTR196":
        BottomDeckDraw();
        if($from == "ARS") { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $rv = "Gains go again."; }
        return $rv;
      case "WTR200": case "WTR201": case "WTR202":
        if(IHaveLessHealth()) { AddCurrentTurnEffect($cardID, $mainPlayer); $rv = "Gains +1."; }
        return $rv;
      case "WTR215": case "WTR216": case "WTR217":
        BottomDeckDraw();
        return "";
      case "WTR218": case "WTR219": case "WTR220":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives the next attack action card with cost 1 or less this turn +" . EffectAttackModifier($cardID) . ".";
      case "WTR221": case "WTR222": case "WTR223"://Sloggism
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives the next attack action card with cost greater than 2 this turn +" . EffectAttackModifier($cardID) . ".";
      case "WTR153":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        return "Gives your next attack action card with cost 2 or greater +" . EffectAttackModifier($cardID) . ".";
      default: return "";
    }
  }

  function WTRHitEffect($cardID)
  {
    global $mainClassState, $CS_HitsWDawnblade, $combatChainState, $CCS_WeaponIndex, $mainCharacter;
    global $mainPlayer, $defPlayer, $CCS_DamageDealt, $combatChain;
    $attackID = $combatChain[0];
    switch ($cardID)
    {
      case "WTR083":
        if(ComboActive())
        {
          AddDecisionQueue("FINDINDICES", $mainPlayer, "WTR083");
          AddDecisionQueue("MULTICHOOSEDECK", $mainPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEDECK", $mainPlayer, "-", 1);
          AddDecisionQueue("MULTIADDHAND", $mainPlayer, "-", 1);
          AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-", 1);
        }
        break;
      case "WTR084":
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, $cardID);
        if(ComboActive()){
          AddDecisionQueue("ADDMAINHAND", $mainPlayer, "-"); //Only back to hand if combo is active
        }
        break;
      case "WTR085":
        if(IsHeroAttackTarget() && ComboActive()){
          LoseHealth($combatChainState[$CCS_DamageDealt], $defPlayer);
        }
        break;
      case "WTR110": case "WTR111": case "WTR112": if(ComboActive()) { WriteLog("Whelming Gustwave drew a card."); MainDrawCard(); } break;
      case "WTR115":
       if($mainClassState[$CS_HitsWDawnblade] == 1 && $CCS_WeaponIndex < count($combatChainState)) { ++$mainCharacter[$combatChainState[$CCS_WeaponIndex]+3]; }
       ++$mainClassState[$CS_HitsWDawnblade];
      break;
      case "WTR167": case "WTR168": case "WTR169": MainDrawCard(); break;
      case "WTR206": case "WTR207": case "WTR208": if(IsHeroAttackTarget() && CardType($attackID) == "AA") PummelHit(); break;
      case "WTR209": case "WTR210": case "WTR211": if(CardType($attackID) == "AA") GiveAttackGoAgain(); break;
      default: break;
    }
  }

  function BlessingOfDeliveranceDestroy($amount)
  {
    global $mainPlayer;
    if(!CanRevealCards($mainPlayer)) return "Blessing of Deliverance cannot reveal cards.";
    $deck = GetDeck($mainPlayer);
    $lifegain = 0;
    $cards = "";
    for($i=0; $i<$amount; ++$i)
    {
      if(count($deck) > $i)
      {
        $cards .= $deck[$i] . ($i < 2 ? "," : "");
        if(CardCost($deck[$i]) >= 3) ++$lifegain;
      }
    }
    RevealCards($cards, $mainPlayer);//CanReveal called
    GainHealth($lifegain, $mainPlayer);
    return "Blessing of Deliverance gained " . $lifegain . " life.";
  }

  function EmergingPowerDestroy($cardID)
  {
    global $mainPlayer;
    $log = "Emerging Power gives the next Guardian attack this turn +" . EffectAttackModifier($cardID) . ".";
    AddCurrentTurnEffect($cardID, $mainPlayer);
    return $log;
  }

  function KatsuHit($index)
  {
    global $mainPlayer;
    AddDecisionQueue("YESNO", $mainPlayer, "to_use_Katsu's_ability");
    AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
    AddDecisionQueue("FINDINDICES", $mainPlayer, "WTR076-1", 1);
    AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
    AddDecisionQueue("DISCARDMYHAND", $mainPlayer, "-", 1);
    AddDecisionQueue("FINDINDICES", $mainPlayer, "WTR076-2", 1);
    AddDecisionQueue("CHOOSEDECK", $mainPlayer, "<-", 1);
    AddDecisionQueue("BANISH", $mainPlayer, "TT", 1);
    AddDecisionQueue("SHOWBANISHEDCARD", $mainPlayer, "-", 1);
    AddDecisionQueue("EXHAUSTCHARACTER", $mainPlayer, $index, 1);
    AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-", 1);
  }

  function LordOfWindIndices($player)
  {
    $array = [];
    $indices = SearchDiscardForCard($player, "WTR107", "WTR108", "WTR109");
    if($indices != "") array_push($array, $indices);
    $indices = SearchDiscardForCard($player, "WTR110", "WTR111", "WTR112");
    if($indices != "") array_push($array, $indices);
    $indices = SearchDiscardForCard($player, "WTR083");
    if($indices != "") array_push($array, $indices);
    return implode(",", $array);
  }

  function NaturesPathPilgrimageHit()
  {
    global $mainPlayer;
    $deck = &GetDeck($mainPlayer);
    if(!ArsenalFull($mainPlayer) && count($deck) > 0)
    {
      $type = CardType($deck[0]);
      if(RevealCards($deck[0], $mainPlayer) && ($type == "A" || $type == "AA"))
      {
        AddArsenal($deck[0], $mainPlayer, "DECK", "DOWN");
        array_shift($deck);
      }
    }
  }
?>
