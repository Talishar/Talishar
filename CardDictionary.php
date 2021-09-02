<?php

  include "Constants.php";
  include "CardDictionaries/ArcaneRising/ARCShared.php";
  include "CardDictionaries/ArcaneRising/ARCGeneric.php";
  include "CardDictionaries/ArcaneRising/ARCMechanologist.php";
  include "CardDictionaries/ArcaneRising/ARCRanger.php";
  include "CardDictionaries/ArcaneRising/ARCRuneblade.php";
  include "CardDictionaries/ArcaneRising/ARCWizard.php";
  include "CardDictionaries/Monarch/MONShared.php";
  include "CardDictionaries/Monarch/MONGeneric.php";
  include "CardDictionaries/Monarch/MONBrute.php";
  include "CardDictionaries/Monarch/MONIllusionist.php";
  include "CardDictionaries/Monarch/MONRuneblade.php";
  include "CardDictionaries/Monarch/MONWarrior.php";
  include "CardDictionaries/Monarch/MONTalent.php";

  function CardType($cardID)
  {
    $set = CardSet($cardID);
    $class = CardClass($cardID);
    if($set == "ARC")
    {
      switch($class)
      {
        case "MECHANOLOGIST": return ARCMechanologistCardType($cardID);
        case "RANGER": return ARCRangerCardType($cardID);
        case "RUNEBLADE": return ARCRunebladeCardType($cardID);
        case "WIZARD": return ARCWizardCardType($cardID);
        case "GENERIC": return ARCGenericCardType($cardID);
        default: return "";
      }
    }
    else if($set == "MON")
    {
      switch($class)
      {
        case "BRUTE": return MONBruteCardType($cardID);
        case "ILLUSIONIST": return MONIllusionistCardType($cardID);
        case "RUNEBLADE": return MONRunebladeCardType($cardID);
        case "WARRIOR": return MONWarriorCardType($cardID);
        case "GENERIC": return MONGenericCardType($cardID);
        case "NONE": return MONTalentCardType($cardID);
        default: return "";
      }
    }
    switch($cardID)
    {
      case "WTR000": return "R";
      case "WTR113": case "WTR114": case "ARC002": return "C";
      case "WTR115": case "CRU177": return "W";
      case "WTR155": case "WTR156": case "WTR157": case "WTR158": case "WTR117": return "E";
      case "ARC159": case "CRU016": case "CRU017": case "CRU018": return "AA";
      case "ARC200": case "ARC201": case "ARC202": return "DR";
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
      case "WTR163": return "I";
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
      case "ARC203": return "A";
      case "ARC150": return "E";
      //CRU Brute
      case "CRU004": case "CRU005": return "W";
      case "CRU006": return "E";
      case "CRU007": case "CRU008": return "AA";
      case "CRU009": return "A";
      case "CRU010": case "CRU011": case "CRU012": return "AA";
      case "CRU013": case "CRU014": case "CRU015": return "AA";
      case "CRU016": case "CRU017": case "CRU018": return "AA";
      case "CRU019": case "CRU020": case "CRU021": return "AA";
      //CRU Guardian
      case "CRU024": return "W";
      case "CRU025": return "E";
      case "CRU026": case "CRU027": return "AA";
      case "CRU028": case "CRU029": case "CRU030": case "CRU031": return "A";
      case "CRU032": case "CRU033": case "CRU034": return "AA";
      case "CRU035": case "CRU036": case "CRU037": return "AA";
      case "CRU038": case "CRU039": case "CRU040": return "A";
      case "CRU041": case "CRU042": case "CRU043": return "I";
      //CRU Warrior
      case "CRU077": return "C";
      case "CRU079": case "CRU080": return "W";
      case "CRU081": return "E";
      case "CRU082": case "CRU083": return "AR";
      case "CRU084": return "A";
      case "CRU085": case "CRU086": case "CRU087": return "A";
      case "CRU088": case "CRU089": case "CRU090": return "AR";
      case "CRU091": case "CRU092": case "CRU093": return "A";
      case "CRU094": case "CRU095": case "CRU096": return "A";
      //CRU MECH:
      case "CRU103": case "CRU106": return "AA";
      //CRU Generics
      case "CRU179": return "E";
      case "CRU180": return "AA";
      case "CRU181": return "A";
      case "CRU182": return "I";
      case "CRU183": case "CRU184": case "CRU185": return "AA";
      case "CRU186": return "AR";
      case "CRU187": return "DR";
      case "CRU188": return "A";
      case "CRU189": case "CRU190": case "CRU191": return "I";
      case "CRU192": case "CRU193": case "CRU194": return "AA";
      default: return "";
    }
  }

  function CardSubType($cardID)
  {
    $set = CardSet($cardID);
    $class = CardClass($cardID);
    if($set == "ARC")
    {
      switch($class)
      {
        case "MECHANOLOGIST": return ARCMechanologistCardSubType($cardID);
        case "RANGER": return ARCRangerCardSubType($cardID);
        case "RUNEBLADE": return ARCRunebladeCardSubType($cardID);
        case "WIZARD": return ARCWizardCardSubType($cardID);
        case "GENERIC": return ARCGenericCardSubType($cardID);
      }
    }
    else if($set == "MON")
    {
      switch($class)
      {
        case "BRUTE": return MONBruteCardSubType($cardID);
        case "ILLUSIONIST": return MONIllusionistCardSubType($cardID);
        case "RUNEBLADE": return MONRunebladeCardSubType($cardID);
        case "WARRIOR": return MONWarriorCardSubType($cardID);
        case "GENERIC": return MONGenericCardSubType($cardID);
        case "NONE": return MONTalentCardSubType($cardID);
        default: return "";
      }
    }
    switch($cardID)
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
      case "WTR170": case "WTR171": case "WTR172": return "Item";
      case "WTR225": return "Aura";
      case "CRU004": case "CRU005": return "Claw";
      case "CRU006": return "Head";
      case "CRU024": return "Hammer";
      case "CRU025": return "Arms";
      case "CRU028": case "CRU029": case "CRU030": case "CRU031": return "Aura";
      case "CRU038": case "CRU039": case "CRU040": return "Aura";
      case "CRU079": case "CRU080": return "Sword";
      case "CRU081": return "Chest";
      case "CRU179": return "Arms";
      default: return "";
    }
  }

  function CharacterHealth($cardID)
  {
    switch($cardID)
    {
      case "WTR001": case "WTR038": case "WTR076": case "WTR113": return 40;
      case "ARC001": case "ARC038": case "ARC075": case "ARC113": return 40;
      case "MON001": case "MON029": case "MON119": case "MON153": return 40;
      default: return 20;
    }
  }

  function CardSet($cardID)
  {
    return substr($cardID, 0, 3);
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
      case "MON":
        if($number == 0) return "NONE";
        else if($number >= 1 && $number <= 28) return "ILLUSIONIST";//Light
        else if($number >= 29 && $number <= 59) return "WARRIOR";//Light
        else if($number >= 60 && $number <= 87) return "NONE";//Light
        else if($number >= 88 && $number <= 104) return "ILLUSIONIST";
        else if($number >= 105 && $number <= 118) return "WARRIOR";
        else if($number >= 119 && $number <= 152) return "BRUTE";//Shadow
        else if($number >= 153 && $number <= 186) return "RUNEBLADE";//Shadow
        else if($number >= 187 && $number <= 220) return "NONE";//Shadow
        else if($number >= 221 && $number <= 228) return "BRUTE";
        else if($number >= 229 && $number <= 237) return "RUNEBLADE";
        else return "GENERIC";
      default: return 0;
    }
  }

  //Minimum cost of the card
  function CardCost($cardID)
  {
    $set = CardSet($cardID);
    $class = CardClass($cardID);
    if($set == "ARC")
    {
      switch($class)
      {
        case "MECHANOLOGIST": return ARCMechanologistCardCost($cardID);
        case "RANGER": return ARCRangerCardCost($cardID);
        case "RUNEBLADE": return ARCRunebladeCardCost($cardID);
        case "WIZARD": return ARCWizardCardCost($cardID);
        case "GENERIC": return ARCGenericCardCost($cardID);
      }
    }
    else if($set == "MON")
    {
      switch($class)
      {
        case "BRUTE": return MONBruteCardCost($cardID);
        case "ILLUSIONIST": return MONIllusionistCardCost($cardID);
        case "RUNEBLADE": return MONRunebladeCardCost($cardID);
        case "WARRIOR": return MONWarriorCardCost($cardID);
        case "GENERIC": return MONGenericCardCost($cardID);
        case "NONE": return MONTalentCardCost($cardID);
        default: return "";
      }
    }
    switch($cardID)
    {
      case "WTR000": return -1;
      case "WTR153": return 0;//TODO: Change ability costs to a different function
      case "WTR078": case "WTR115": return 1;//TODO: Change ability costs to a different function
      case "CRU177": return 2;//TODO: Change ability costs to a different function
      case "ARC159": return 2;
      case "ARC203": case "ARC204": case "ARC205": case "CRU106": return 1;
      case "CRU103": return 2;
      //Now do in order
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
      case "WTR116": return 1;
      case "WTR118": return 0;
      case "WTR119": return 1;
      case "WTR120": return 2;
      case "WTR121": return 1;
      case "WTR122": return 1;
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
      case "WTR151": case "WTR152": case "WTR154": return -1;
      case "WTR159": return 0;
      case "WTR160": return 1;
      case "WTR161": return 3;
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
      //CRU Guardian
      case "CRU024": return 4;
      case "CRU025": return 3;
      case "CRU026": return 4;
      case "CRU027": return 7;
      case "CRU028": return 3;
      case "CRU029": case "CRU030": case "CRU031": return 9;
      case "CRU032": case "CRU033": case "CRU034": return 3;
      case "CRU035": case "CRU036": case "CRU037": return 4;
      case "CRU038": case "CRU039": case "CRU040": return 2;
      case "CRU041": case "CRU042": case "CRU043": return 0;
      //CRU Brute
      case "CRU004": case "CRU005": return 2;
      case "CRU006": return 0;
      case "CRU007": case "CRU008": case "CRU009": return 3;
      case "CRU010": case "CRU011": case "CRU012": return 2;
      case "CRU013": case "CRU014": case "CRU015": return 2;
      case "CRU016": case "CRU017": case "CRU018": return 3;
      case "CRU019": case "CRU020": case "CRU021": return 1;
      //CRU Warrior
      case "CRU079": case "CRU080": return 1;
      case "CRU081": return 0;
      case "CRU082": return 0;
      case "CRU083": return 2;
      case "CRU084": return 1;
      case "CRU085": case "CRU086": case "CRU087": return 1;
      case "CRU088": case "CRU089": case "CRU090": return 1;
      case "CRU091": case "CRU092": case "CRU093": return 0;
      case "CRU094": case "CRU095": case "CRU096": return 1;
      //CRU Generic
      case "CRU180": case "CRU181": case "CRU182": return 0;
      case "CRU183": case "CRU184": case "CRU185": return 0;
      case "CRU186": case "CRU187": return 0;
      case "CRU188": return 4;
      case "CRU189": case "CRU190": case "CRU191": return 0;
      case "CRU192": case "CRU193": case "CRU194": return 2;
      default: return 0;
    }
  }

  function AbilityCost($cardID)
  {
    $set = CardSet($cardID);
    $class = CardClass($cardID);
    if($set == "ARC")
    {
      return ARCAbilityCost($cardID);
    }
    else if($set == "MON")
    {
      return MONAbilityCost($cardID);
    }
    return CardCost($cardID);
  }

  function ResourcesPaidBlockModifier($cardID, $amountPaid)
  {
    switch($cardID)
    {
      case "MON241": case "MON242": case "MON243": case "MON244": return ($amountPaid >= 1 ? 2 : 0);
      default: return 0;
    }
  }

  function DynamicCost($cardID)
  {
    switch($cardID)
    {
      case "WTR051": case "WTR052": case "WTR053": return "2,6";
      case "ARC009": return "0,2,4,6,8,10,12";
      default:
        return "";
    }
  }

  function BlockDynamicCost($cardID)
  {
    switch($cardID)
    {
      case "MON241": case "MON242": case "MON243": case "MON244": return "0,1";
      default:
        return "";
    }
  }

  function PitchValue($cardID)
  {
    $set = CardSet($cardID);
    $class = CardClass($cardID);
    if($set == "ARC")
    {
      switch($class)
      {
        case "MECHANOLOGIST": return ARCMechanologistPitchValue($cardID);
        case "RANGER": return ARCRangerPitchValue($cardID);
        case "RUNEBLADE": return ARCRunebladePitchValue($cardID);
        case "WIZARD": return ARCWizardPitchValue($cardID);
        case "GENERIC": return ARCGenericPitchValue($cardID);
      }
    }
    else if($set == "MON")
    {
      switch($class)
      {
        case "BRUTE": return MONBrutePitchValue($cardID);
        case "ILLUSIONIST": return MONIllusionistPitchValue($cardID);
        case "RUNEBLADE": return MONRunebladePitchValue($cardID);
        case "WARRIOR": return MONWarriorPitchValue($cardID);
        case "GENERIC": return MONGenericPitchValue($cardID);
        case "NONE": return MONTalentPitchValue($cardID);
        default: return "";
      }
    }
    switch($cardID)
    {
       case "WTR000": return 3;
       case "WTR002": case "WTR003": case "WTR038": case "WTR039": case "WTR040": case "WTR155": case "WTR156": case "WTR157": case "WTR158": return 0;
       case "ARC002": case "CRU177": case "WTR153": return 0;
       case "WTR113": case "WTR114": case "WTR115": case "WTR117": return 0;
       case "ARC159": case "ARC200": case "WTR206": case "WTR066": case "WTR051": case "WTR069": case "WTR060": case "WTR215": case "WTR072": case "WTR054": return 1;
       case "WTR030": case "WTR027": case "WTR024": case "WTR018": case "CRU017": case "WTR207": case "WTR067": case "WTR061": case "WTR213": return 2;
       case "ARC203": case "CRU106": return 1;
      //Now do in order
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
      case "WTR081": return 3;
      case "WTR082": return 1;
      case "WTR083": case "WTR084": return 2;
      case "WTR085": return 1;
      case "WTR086": case "WTR089": case "WTR092": case "WTR095": case "WTR098": case "WTR101": case "WTR104": case "WTR107": case "WTR110": return 1;
      case "WTR087": case "WTR090": case "WTR093": case "WTR096": case "WTR099": case "WTR102": case "WTR105": case "WTR108": case "WTR111": return 2;
      case "WTR088": case "WTR091": case "WTR094": case "WTR097": case "WTR100": case "WTR103": case "WTR106": case "WTR109": case "WTR112": return 3;
      case "WTR116": return 0;
      case "WTR118": return 3;
      case "WTR119": case "WTR120": return 1;
      case "WTR121": case "WTR122": return 2;
      case "WTR123": case "WTR126": case "WTR129": case "WTR132": case "WTR135": case "WTR138": case "WTR141": case "WTR144": case "WTR147": return 1;
      case "WTR124": case "WTR127": case "WTR130": case "WTR133": case "WTR136": case "WTR139": case "WTR142": case "WTR145": case "WTR148": return 2;
      case "WTR125": case "WTR128": case "WTR131": case "WTR134": case "WTR137": case "WTR140": case "WTR143": case "WTR146": case "WTR149": return 3;
      case "WTR150": return 0;
      case "WTR159": return 1;
      case "WTR160": return 2;
      case "WTR161": return 3;
      case "WTR163": return 2;
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
      //CRU Guardian
      case "CRU024": case "CRU025": return 0;
      case "CRU026": return 1;
      case "CRU027": return 2;
      case "CRU028": return 3;
      case "CRU029": case "CRU032": case "CRU035": case "CRU038": case "CRU041": return 1;
      case "CRU030": case "CRU033": case "CRU036": case "CRU039": case "CRU042": return 2;
      case "CRU031": case "CRU034": case "CRU037": case "CRU040": case "CRU043": return 3;
      //CRU Brute
      case "CRU004": case "CRU005": case "CRU006": return 0;
      case "CRU007": return 2;
      case "CRU008": return 1;
      case "CRU009": return 2;
      case "CRU010": case "CRU013": case "CRU016": case "CRU019": return 1;
      case "CRU011": case "CRU014": case "CRU017": case "CRU020": return 2;
      case "CRU012": case "CRU015": case "CRU018": case "CRU021": return 3;
      //CRU Warrior
      case "CRU079": case "CRU080": return 0;
      case "CRU081": return 0;
      case "CRU082": case "CRU083": return 2;
      case "CRU085": case "CRU088": case "CRU091": case "CRU094": return 1;
      case "CRU086": case "CRU089": case "CRU092": case "CRU095": return 2;
      case "CRU087": case "CRU090": case "CRU093": case "CRU096": return 3;
      //CRU Generic
      case "CRU180": return 1;
      case "CRU181": return 0;
      case "CRU182": return 3;
      case "CRU183": case "CRU189": case "CRU192": return 1;
      case "CRU184": case "CRU190": case "CRU193": return 2;
      case "CRU185": case "CRU191": case "CRU194": return 3;
      case "CRU186": return 3;
      case "CRU187": case "CRU188": return 2;
      default: return 3;
    }
  }

  function BlockValue($cardID)
  {
    $set = CardSet($cardID);
    $class = CardClass($cardID);
    if($set == "ARC")
    {
      switch($class)
      {
        case "MECHANOLOGIST": return ARCMechanologistBlockValue($cardID);
        case "RANGER": return ARCRangerBlockValue($cardID);
        case "RUNEBLADE": return ARCRunebladeBlockValue($cardID);
        case "WIZARD": return ARCWizardBlockValue($cardID);
        case "GENERIC": return ARCGenericBlockValue($cardID);
      }
    }
    else if($set == "MON")
    {
      switch($class)
      {
        case "BRUTE": return MONBruteBlockValue($cardID);
        case "ILLUSIONIST": return MONIllusionistBlockValue($cardID);
        case "RUNEBLADE": return MONRunebladeBlockValue($cardID);
        case "WARRIOR": return MONWarriorBlockValue($cardID);
        case "GENERIC": return MONGenericBlockValue($cardID);
        case "NONE": return MONTalentBlockValue($cardID);
        default: return "";
      }
    }
    switch($cardID)
    {
      case "WTR000": return 0; 
      case "WTR038": case "WTR039": case "WTR040": return 0;
      case "CRU177": case "WTR153": return 0;
      //Brute
      case "WTR001": case "WTR002": case "WTR003": return 0;
      case "WTR004": return 2;
      case "WTR005": return 1;
      case "WTR008": return 4;
      case "WTR010": return 0;
      //Guardian
      case "WTR041": return 2;
      case "WTR042": return 1;
      case "WTR051": return 7;
      case "WTR052": return 6;
      case "WTR053": return 5;
      case "WTR075": return 0;
      //Ninja
      case "WTR079": return 2;
      case "WTR080": return 1;
      case "WTR092": return 4;
      case "WTR093": return 3;
      case "WTR094": return 2;
      case "WTR098": case "WTR099": case "WTR100": case "WTR101": case "WTR102": case "WTR103": return 2;
      case "WTR107": case "WTR108": case "WTR109": return 2;
      case "WTR150": return 1;
      case "WTR155": case "WTR156": case "WTR157": case "WTR158": return 1;
      case "ARC200": return 4;
      case "WTR113": case "WTR114": case "WTR115": return 0;
      case "WTR116": return 2;
      case "WTR117": return 1;
      case "WTR126": return 6;
      case "WTR127": return 5;
      case "WTR128": return 4;
      //Generics
      case "WTR151": case "WTR152": case "WTR154": return 0;
      case "WTR160": return 2;
      case "WTR163": return 0;
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
      case "ARC150": return 1;
      //CRU Guardian
      case "CRU024": return 0;
      case "CRU025": return 2;
      case "CRU041": case "CRU042": case "CRU043": return 0;
      //CRU Brute
      case "CRU004": case "CRU005": case "CRU006": return 0;
      //CRU Warrior
      case "CRU079": case "CRU080": return 0;
      case "CRU081": return 2;
      //CRU Generics
      case "CRU179": return 0;
      case "CRU180": return 2;
      case "CRU181": case "CRU182": return 0;
      case "CRU183": case "CRU184": case "CRU185": return 2;
      case "CRU186": case "CRU187": case "CRU188": return 2;
      case "CRU189": case "CRU190": case "CRU191": return 0;
      default: return 3;
    }
  }

  function AttackValue($cardID)
  {
    $set = CardSet($cardID);
    $class = CardClass($cardID);
    if($set == "ARC")
    {
      switch($class)
      {
        case "MECHANOLOGIST": return ARCMechanologistAttackValue($cardID);
        case "RANGER": return ARCRangerAttackValue($cardID);
        case "RUNEBLADE": return ARCRunebladeAttackValue($cardID);
        case "WIZARD": return ARCWizardAttackValue($cardID);
        case "GENERIC": return ARCGenericAttackValue($cardID);
      }
    }
    else if($set == "MON")
    {
      switch($class)
      {
        case "BRUTE": return MONBruteAttackValue($cardID);
        case "ILLUSIONIST": return MONIllusionistAttackValue($cardID);
        case "RUNEBLADE": return MONRunebladeAttackValue($cardID);
        case "WARRIOR": return MONWarriorAttackValue($cardID);
        case "GENERIC": return MONGenericAttackValue($cardID);
        case "NONE": return MONTalentAttackValue($cardID);
        default: return "";
      }
    }
    switch($cardID)
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
      case "WTR115": return 3;
      //Generic
      case "WTR159": return 5;
      case "WTR161": return 4;
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
      case "ARC159": return 6;
      //CRU Brute
      case "CRU004": case "CRU005": return 3;
      case "CRU007": case "CRU008": return 6;
      case "CRU010": case "CRU016": return 7;
      case "CRU011": case "CRU013": case "CRU017": return 6;
      case "CRU012": case "CRU014": case "CRU018": return 5;
      case "CRU015": case "CRU019": return 4;
      case "CRU020": return 3;
      case "CRU021": return 2;
      //CRU Guardian
       return 6;
      case "CRU027": return 10;
      case "CRU026": case "CRU035": return 8;
      case "CRU032": case "CRU036": return 7;
      case "CRU024": case "CRU033": case "CRU037": return 6;
      case "CRU034": return 5;
      //CRU Warrior
      case "CRU079": case "CRU080": return 2;
      case "CRU103": return 4;
      case "CRU106": return 4;
      //CRU Generic
      case "CRU177": return 4;
      case "CRU180": return 4;
      case "CRU183": return 3;
      case "CRU184": return 2;
      case "CRU185": return 1;
      case "CRU192": return 6;
      case "CRU193": return 5;
      case "CRU194": return 4;
      default: return 0;
    }
  }

  function HasGoAgain($cardID)
  {
    $set = CardSet($cardID);
    if($set == "ARC")
    {
      return ARCHasGoAgain($cardID);
    }
    else if($set == "MON")
    {
      return MONHasGoAgain($cardID);
    }
    global $myDeck;
    switch($cardID)
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
      case "WTR119": case "WTR122": return true;
      case "WTR129": case "WTR130": case "WTR131": return true;
      case "WTR141": case "WTR142": case "WTR143": return true;
      case "WTR144": case "WTR145": case "WTR146": return true;
      case "WTR147": case "WTR148": case "WTR149": return true;
      case "WTR218": case "WTR219": case "WTR220": return true;
      case "WTR223": case "WTR222": case "WTR221": return true;
      case "ARC203": case "ARC204": case "ARC205": return true;
      case "WTR161": return count($myDeck) == 0;
      case "CRU084": return true;
      case "CRU085": case "CRU086": case "CRU087": return true;
      case "CRU091": case "CRU092": case "CRU093": return true;
      case "CRU094": case "CRU095": case "CRU096": return true;
      //CRU Brute
      case "CRU009": return true;
      case "CRU019": case "CRU020": case "CRU021": return true;
      //CRU Generic
      case "CRU181": case "CRU188": return true;
      default: return false;
    }
  }

  function GetAbilityType($cardID, $index=-1)
  {
    $set = CardSet($cardID);
    if($set == "ARC")
    {
      return ARCAbilityType($cardID, $index);
    }
    else if($set == "MON")
    {
      return MONAbilityType($cardID, $index);
    }
    switch($cardID)
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
      case "WTR170": return "I";
      case "WTR171": case "WTR172": return "A";
      case "CRU004": case "CRU005": return "AA";
      case "CRU006": return "A";
      case "CRU024": return "AA";
      case "CRU025": return "A";
      case "CRU079": case "CRU080": return "AA";
      case "CRU081": return "A";
      case "CRU177": return "AA";
      default: return "";
    }
  }

  function IsPlayable($cardID, $phase, $from, $index=-1)
  {
    global $myHand, $currentPlayer, $myClassState, $CS_NumActionsPlayed;
    $cardType = CardType($cardID);
    $subtype = CardSubType($cardID);
    if($phase == "B" && $cardType == "E") return true;
    $isStaticType = IsStaticType($cardType, $from);
    if($isStaticType)
    {
      $cardType = GetAbilityType($cardID, $index);
    }
    if(RequiresDiscard($cardID) || $cardID == "WTR159")
    {
      if($from == "HAND" && count($myHand) < 2) return false;//TODO: Account for where it was from
      else if(count($myHand) < 1) return false;
    }
    if($phase != "B" && $phase != "P" && IsPlayRestricted($cardID, $from, $index)) return false;
    if(($cardType == "I" || CanPlayAsInstant($cardID)) && CanPlayInstant($phase)) return true;
    if(($phase == "B" || $phase == "D") && $from == "HAND" && IsDominateActive() && NumBlockedFromHand() >= 1) return false;
    if($phase == "B" && $from == "ARS" && !($cardType == "AA" && SearchCurrentTurnEffects("ARC160-2", $currentPlayer))) return false;
    if($phase == "B" && $cardType != "DR") return BlockValue($cardID);
    if($phase == "P" && PitchValue($cardID) > 0) return true;
    if($phase == "M" && $subtype == "Arrow" && $from != "ARS") return false;
    if(SearchCurrentTurnEffects("ARC044", $currentPlayer) && !$isStaticType && $from != "ARS") return false;
    if(SearchCurrentTurnEffects("ARC043", $currentPlayer) && ($cardType == "A" || $cardType == "AA") && $myClassState[$CS_NumActionsPlayed] >= 1) return false;
    switch($cardType)
    {
      case "A": return $phase == "M";
      case "AA": return $phase == "M";
      case "AR": return $phase == "A";
      case "DR": return $phase == "D" && IsDefenseReactionPlayable($cardID);
      default: return false;
    }
  }

  function CanPlayInstant($phase)
  {
    if($phase == "M") return true;
    //if($phase == "B") return true;
    if($phase == "A") return true;
    if($phase == "D") return true;
    return false;
  }

  function IsPlayRestricted($cardID, $from="", $index=-1)
  {
    global $myClassState, $theirClassState, $CS_NumBoosted, $combatChain, $myCharacter, $myHand, $combatChainState, $CCS_HitsWithWeapon, $currentPlayer;
    global $CS_DamageTaken, $myArsenal, $myItems, $mySoul;
    if(SearchCurrentTurnEffects("CRU032", $currentPlayer) && CardType($cardID) == "AA" && AttackValue($cardID) <= 3) return true;
    switch($cardID)
    {
      case "ARC005": return $myClassState[$CS_NumBoosted] < 1;
      case "ARC008": return $myClassState[$CS_NumBoosted] < 3;
      case "ARC010": return (count($combatChain) > 0 && $from == "PLAY" && $myItems[$index+1] > 0 && (CardSubtype($combatChain[0]) != "Pistol" || $myItems[$index+2] != 2));
      case "ARC018": return (count($combatChain) > 0 && $from == "PLAY" && $myItems[$index+1] > 0 && (CardType($combatChain[0]) != "AA" || $myItems[$index+2] != 2));
      case "ARC041": return $myArsenal == "" || GetMyArsenalFacing() == "UP";
      case "WTR209": case "WTR210": case "WTR211":
        if(count($combatChain) == 0) return true;
        $subtype = CardSubtype($combatChain[0]);
        if($subtype == "Sword" || $subtype == "Dagger" || (CardType($combatChain[0]) == "AA" && CardCost($combatChain[0]) <= 1)) return false;
        return true;
      case "WTR080":
        if(count($combatChain) == 0) return true;
        return !HasCombo($combatChain[0]);
      case "WTR116":
        return $combatChainState[$CCS_HitsWithWeapon] == 0;
      case "WTR120": case "WTR121":
      case "WTR123": case "WTR124": case "WTR125":
      case "WTR135": case "WTR136": case "WTR137":
        if(count($combatChain) == 0) return true;
        $type = CardType($combatChain[0]);
        return $type != "W";
      case "WTR138": case "WTR139": case "WTR140":
        if(count($combatChain) == 0) return true;
        $type = CardType($combatChain[0]);
        return $type != "W";
      case "WTR132": case "WTR133": case "WTR134":
        if(count($combatChain) == 0) return true;
        if(!RepriseActive()) return false;
        $type = CardType($combatChain[0]);
        return $type != "W";
      case "WTR150":
        $index = FindMyCharacter($cardID);
        return $myCharacter[$index+2] < 3;
      case "WTR154":
        if(count($combatChain) == 0) return true;
        if(CardType($combatChain[0]) != "AA") return true;
        if(CardCost($combatChain[0]) > 1) return true;
        return false;
      case "WTR206": case "WTR207": case "WTR208":
        if(count($combatChain) == 0) return true;
        $subtype = CardSubtype($combatChain[0]);
        if($subtype == "Club" || $subtype == "Hammer" || (CardType($combatChain[0]) == "AA" && CardCost($combatChain[0]) >= 2)) return false;
        return true;
      case "CRU082": case "CRU083":
        if(count($combatChain) == 0) return true;
        $type = CardType($combatChain[0]);
        return $type != "W";
      case "CRU186":
        if(count($combatChain) == 0) return true;
        $type = CardType($combatChain[0]);
        return $type != "AA";
      case "MON029": case "MON030": return count($mySoul) == 0;
      case "MON238": return $myClassState[$CS_DamageTaken] == 0 && $theirClassState[$CS_DamageTaken] == 0;
      default: return false;
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

  function GoesOnCombatChain($phase, $cardID, $from)
  {
    if($phase != "B" && $from == "EQUIP" || $from == "PLAY") $cardType = GetAbilityType($cardID);
    else $cardType = CardType($cardID);
    if($cardType == "I") return false;//Instants as yet never go on the combat chain
    if($phase == "B" || $phase == "A" || $phase == "D") return true;//Anything you play during these combat phases would go on the chain
    if($phase == "M" && $cardType == "AA") return true;//If it's an attack action, it goes on the chain
    return false;
  }

  function IsStaticType($cardType, $from="")
  {
    if($cardType == "C" || $cardType == "E" || $cardType == "W") return true;
    if($from == "PLAY") return true;
    return false;
  }

  function HasBladeBreak($cardID)
  {
    switch($cardID)
    {
      case "WTR079": return true;
      case "WTR150": return true;
      case "WTR155": case "WTR156": case "WTR157": case "WTR158": return true;
      case "ARC041": return true;
      default: return false;
    }
  }

  function HasBattleworn($cardID)
  {
    switch($cardID)
    {
      case "WTR004": case "WTR005": return true;
      case "WTR041": case "WTR042": return true;
      case "WTR080": return true;
      case "WTR116": return true;
      case "ARC004": return true;
      case "ARC150": return true;
      default: return false;
    }
  }

  function HasTemper($cardID)
  {
    switch($cardID)
    {
      case "CRU025": return true;
      case "CRU081": return true;
      default: return false;
    }
  }

  function RequiresDiscard($cardID)
  {
    switch($cardID)
    {
      case "WTR006": case "WTR007": case "WTR008":
      case "WTR011": case "WTR012": case "WTR013":
      case "WTR014": case "WTR015": case "WTR016":
      case "WTR020": case "WTR021": case "WTR022":
      case "WTR029": case "WTR030": case "WTR031":
      case "WTR035": case "WTR036": case "WTR037":
      case "CRU010": case "CRU011": case "CRU012":
      case "CRU019": case "CRU020": case "CRU021": return true;
      default: return false;
    }
  }

  function ETASteamCounters($cardID)
  {
    switch($cardID)
    {
      case "ARC017": return 1;
      case "ARC007": case "ARC019": return 2;
      case "ARC036": return 3;
      case "ARC035": return 4;
      case "ARC037": return 5;
      case "CRU104": return 0;//TODO
    }
  }

  function AbilityHasGoAgain($cardID)
  {
    $set = CardSet($cardID);
    $class = CardClass($cardID);
    if($set == "ARC")
    {
      return ARCAbilityHasGoAgain($cardID);
    }
    else if($set == "MON")
    {
      return MONAbilityHasGoAgain($cardID);
    }
    switch($cardID)
    {
      case "WTR038": case "WTR039": return true;
      case "WTR041": return true;
      case "WTR116": return true;
      case "WTR152": return true;
      case "WTR153": return true;
      case "WTR171": return true;
      case "CRU006": return true;
      case "CRU025": return true;
      case "CRU081": return true;
      default: return false;
    }
  }

  function DoesEffectGrantDominate($cardID)
  {
    switch($cardID)
    {
      case "WTR038": case "WTR039": return true;
      case "WTR197": return true;
      case "ARC011": case "ARC012": case "ARC013": return true;
      case "ARC019": return true;
      case "ARC038": case "ARC039": return true;
      case "CRU013": case "CRU014": case "CRU015": return true;
      case "CRU038": case "CRU039": case "CRU040": return true;
      case "CRU094-2": case "CRU095-2": case "CRU096-2": return true;
      case "CRU106": case "CRU107": case "CRU108": return true;
      case "MON278": case "MON279": case "MON280": return true;
      default: return false;
    }
  }

  function HasReprise($cardID)
  {
    switch($cardID)
    {
      case "WTR118": case "WTR120": case "WTR121":
      case "WTR123": case "WTR124": case "WTR125":
      case "WTR132": case "WTR133": case "WTR134":
      case "WTR135": case "WTR136": case "WTR137":
      case "WTR138": case "WTR139": case "WTR140":
      case "CRU083":
      case "CRU088": case "CRU089": case "CRU090":
        return true;
      default:
        return false;
    }
  }

  //Is it active AS OF THIS MOMENT?
  function RepriseActive()
  {
    return NumBlockedFromHand() > 0;
  }

  function HasCombo($cardID)
  {
    switch($cardID)
    {
      case "WTR081":
      case "WTR083":
      case "WTR084":
      case "WTR085":
      case "WTR086": case "WTR087": case "WTR088":
      case "WTR089": case "WTR090": case "WTR091":
      case "WTR095": case "WTR096": case "WTR097":
      case "WTR104": case "WTR105": case "WTR106":
      case "WTR110": case "WTR111": case "WTR112": return true;
    }
    return false;
  }

  function ComboActive($cardID = "")
  {
    global $combatChainState, $CCS_LastAttack, $combatChain;
    if($cardID == "") $cardID = $combatChain[0];
    $LA = $combatChainState[$CCS_LastAttack];
    if($LA == "NA") return;
    switch($cardID)
    {
      case "WTR081": return $LA == "WTR083";
      case "WTR083": return $LA == "WTR110" || $LA == "WTR111" || $LA == "WTR112";
      case "WTR084": return $LA == "WTR104" || $LA == "WTR105" || $LA == "WTR106";
      case "WTR085": return $LA == "WTR095" || $LA == "WTR096" || $LA == "WTR097";
      case "WTR086": case "WTR087": case "WTR088": return $LA == "WTR095" || $LA == "WTR096" || $LA == "WTR097";
      case "WTR089": case "WTR090": case "WTR091": return $LA == "WTR104" || $LA == "WTR105" || $LA == "WTR106";
      case "WTR095": case "WTR096": case "WTR097": return $LA == "WTR098" || $LA == "WTR099" || $LA == "WTR100";
      case "WTR104": case "WTR105": case "WTR106": return $LA == "WTR101" || $LA == "WTR102" || $LA == "WTR103";
      case "WTR110": case "WTR111": case "WTR112": return $LA == "WTR107" || $LA == "WTR108" || $LA == "WTR109";
    }
    return false;
  }

?>

