<?php

  include "Constants.php";
  include "CardDictionaries/ArcaneRising/ARCShared.php";
  include "CardDictionaries/ArcaneRising/ARCGeneric.php";
  include "CardDictionaries/ArcaneRising/ARCMechanologist.php";
  include "CardDictionaries/ArcaneRising/ARCRanger.php";
  include "CardDictionaries/ArcaneRising/ARCRuneblade.php";
  include "CardDictionaries/ArcaneRising/ARCWizard.php";
  include "CardDictionaries/CrucibleOfWar/CRUShared.php";
  include "CardDictionaries/Monarch/MONShared.php";
  include "CardDictionaries/Monarch/MONGeneric.php";
  include "CardDictionaries/Monarch/MONBrute.php";
  include "CardDictionaries/Monarch/MONIllusionist.php";
  include "CardDictionaries/Monarch/MONRuneblade.php";
  include "CardDictionaries/Monarch/MONWarrior.php";
  include "CardDictionaries/Monarch/MONTalent.php";
  include "CardDictionaries/TalesOfAria/ELEShared.php";
  include "CardDictionaries/TalesOfAria/ELEGuardian.php";
  include "CardDictionaries/TalesOfAria/ELERanger.php";
  include "CardDictionaries/TalesOfAria/ELERuneblade.php";
  include "CardDictionaries/TalesOfAria/ELETalent.php";
  include "CardDictionaries/Everfest/EVRShared.php";
  include "CardDictionaries/Uprising/UPRShared.php";
  include "CardDictionaries/card_names.php";
  include "CardDictionaries/ClassicBattles/DVRShared.php";
  include "CardDictionaries/ClassicBattles/RVDShared.php";

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
    else if($set == "ELE")
    {
      switch($class)
      {
        case "GUARDIAN": return ELEGuardianCardType($cardID);
        case "RANGER": return ELERangerCardType($cardID);
        case "RUNEBLADE": return ELERunebladeCardType($cardID);
        default: return ELETalentCardType($cardID);
      }
    }
    else if($set == "EVR")
    {
      return EVRCardType($cardID);
    }
    else if($set == "UPR")
    {
      return UPRCardType($cardID);
    }
    else if($set == "DVR")
    {
      return DVRCardType($cardID);
    }
    else if($set == "RVD")
    {
      return RVDCardType($cardID);
    }
    switch($cardID)
    {
      case "DUMMY": return "C";
      case "OVRPVE001": return "C";
      case "OVRPVE002": return "C";
      case "OVRPVE003": return "C";
      case "OVRPVE004": return "W";
      case "OVRPVE005": return "B";
      case "OVRPVE006": return "S";
      case "OVRPVE007": return "S";
      case "OVRPVE008": return "S";
      case "OVRPVE009": return "S";
      case "OVRPVE010": return "S";
      case "OVRPVE011": return "S";
      case "OVRPVE012": return "S";
      case "OVRPVE013": return "S";
      case "OVRPVE014": return "S";
      case "OVRPVE015": return "S";
      case "OVRPVE016": return "S";
      case "OVRPVE017": return "S";
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
      case "WTR162": return "A";
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
      //CRU Fable
      case "CRU000": return "R";
      //CRU Brute
      case "CRU002": return "C";
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
      //CRU Ninja
      case "CRU046": case "CRU047": return "C";
      case "CRU050": case "CRU051": case "CRU052": return "W";
      case "CRU053": return "E";
      case "CRU054": case "CRU055": case "CRU056": return "AA";
      case "CRU057": case "CRU058": case "CRU059": return "AA";
      case "CRU060": case "CRU061": case "CRU062": return "AA";
      case "CRU063": case "CRU064": case "CRU065": return "AA";
      case "CRU066": case "CRU067": case "CRU068": return "AA";
      case "CRU069": case "CRU070": case "CRU071": return "AA";
      case "CRU072": case "CRU073": case "CRU074": return "AA";
      case "CRU075": return "T";
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
      case "CRU097": return "C";
      //CRU MECH:
      case "CRU099": return "C";
      case "CRU101": return "W";
      case "CRU102": return "E";
      case "CRU103": return "AA";
      case "CRU104": case "CRU105": return "A";
      case "CRU106": case "CRU107": case "CRU108": return "AA";
      case "CRU109": case "CRU110": case "CRU111": return "AA";
      case "CRU112": case "CRU113": case "CRU114": return "AA";
      case "CRU115": case "CRU116": case "CRU117": return "A";
      //CRU Merchant
      case "CRU118": return "C";
      //CRU Ranger
      case "CRU121": return "W";
      case "CRU122": return "E";
      case "CRU123": return "AA";
      case "CRU124": return "A";
      case "CRU125": return "I";
      case "CRU126": case "CRU127": case "CRU128": return "DR";
      case "CRU129": case "CRU130": case "CRU131": return "AA";
      case "CRU132": case "CRU133": case "CRU134": return "AA";
      case "CRU135": case "CRU136": case "CRU137": return "A";
      //CRU Runeblade
      case "CRU140": return "W";
      case "CRU141": return "E";
      case "CRU142": return "AA";
      case "CRU143": return "A";
      case "CRU144": return "A";
      case "CRU145": case "CRU146": case "CRU147": return "A";
      case "CRU148": case "CRU149": case "CRU150": return "AA";
      case "CRU151": case "CRU152": case "CRU153": return "AA";
      case "CRU154": case "CRU155": case "CRU156": return "A";
      //CRU Wizard
      case "CRU160": return "W";
      case "CRU161": return "E";
      case "CRU162": case "CRU163": return "A";
      case "CRU164": return "I";
      case "CRU165": case "CRU166": case "CRU167": return "A";
      case "CRU168": case "CRU169": case "CRU170": return "A";
      case "CRU171": case "CRU172": case "CRU173": return "A";
      case "CRU174": case "CRU175": case "CRU176": return "A";
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
      case "CRU197": return "T";
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
    else if($set == "ELE")
    {
      switch($class)
      {
        case "GUARDIAN": return ELEGuardianCardSubType($cardID);
        case "RANGER": return ELERangerCardSubType($cardID);
        case "RUNEBLADE": return ELERunebladeCardSubType($cardID);
        default: return ELETalentCardSubType($cardID);
      }
    }
    else if($set == "EVR")
    {
      return EVRCardSubtype($cardID);
    }
    else if($set == "UPR")
    {
      return UPRCardSubtype($cardID);
    }
    else if($set == "DVR")
    {
      return DVRCardSubtype($cardID);
    }
    else if($set == "RVD")
    {
      return RVDCardSubtype($cardID);
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
      case "WTR162": return "Item";
      case "WTR170": case "WTR171": case "WTR172": return "Item";
      case "WTR225": return "Aura";
      case "CRU000": return "Gem";
      case "CRU004": case "CRU005": return "Claw";
      case "CRU006": return "Head";
      case "CRU024": return "Hammer";
      case "CRU025": return "Arms";
      case "CRU028": case "CRU029": case "CRU030": case "CRU031": return "Aura";
      case "CRU038": case "CRU039": case "CRU040": return "Aura";
      case "CRU050": return "Sword";
      case "CRU051": case "CRU052": return "Dagger";
      case "CRU053": return "Legs";
      case "CRU075": return "Aura";
      case "CRU079": case "CRU080": return "Sword";
      case "CRU081": return "Chest";
      case "CRU101": return "Gun";
      case "CRU102": return "Head";
      case "CRU104": case "CRU105": return "Item";
      case "CRU121": return "Bow";
      case "CRU122": return "Legs";
      case "CRU123": return "Arrow";
      case "CRU126": case "CRU127": case "CRU128": return "Trap";
      case "CRU129": case "CRU130": case "CRU131": return "Arrow";
      case "CRU132": case "CRU133": case "CRU134": return "Arrow";
      case "CRU140": return "Sword";
      case "CRU141": return "Chest";
      case "CRU144": return "Aura";
      case "CRU160": return "Staff";
      case "CRU161": return "Arms";
      case "CRU177": return "Sword";
      case "CRU179": return "Arms";
      case "CRU197": return "Item";
      default: return "";
    }
  }

  function CharacterHealth($cardID)
  {
    switch($cardID)
    {
      case "DUMMY": return 1000;
      case "WTR001": case "WTR038": case "WTR076": case "WTR113": return 40;
      case "ARC001": case "ARC038": case "ARC075": return 40;
      case "ARC113": return 30;
      case "ARC114": return 15;
      case "CRU002": return 19;
      case "CRU046": return 20;
      case "CRU047": return 17;
      case "CRU118": return 20;
      case "MON001": case "MON029": case "MON119": case "MON153": return 40;
      case "ELE001": case "ELE031": case "ELE062": return 40;
      case "EVR017": return 40;
      case "EVR019": return 21;
      case "EVR120": return 18;
      case "UPR001": return 40;
      case "UPR002": return 20;
      case "UPR044": return 40;
      case "UPR045": return 20;
      case "UPR102": return 36;
      case "UPR103": return 18;
      default: return 20;
    }
  }

  function CharacterIntellect($cardID)
  {
    switch($cardID)
    {
      case "CRU099": return 3;
      default: return 4;
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
        else if($number == 404) return "ILLUSIONIST";
        else if($number == 405) return "WARRIOR";
        else if($number == 406) return "BRUTE";
        else if($number == 407) return "RUNEBLADE";
        else return "GENERIC";
      case "ELE":
        if($number == 0) return "NONE";
        else if($number >= 1 && $number <= 30) return "GUARDIAN";
        else if($number >= 31 && $number <= 61) return "RANGER";
        else if($number >= 31 && $number <= 90) return "RUNEBLADE";
        else if($number >= 202 && $number <= 212) return "GUARDIAN";
        else if($number >= 213 && $number <= 221) return "RANGER";
        else if($number >= 222 && $number <= 232) return "RUNEBLADE";
        else if($number >= 233) return "GENERIC";
        else return "NONE";
      case "EVR":
        if($number == 0) return "GUARDIAN";
        else if($number >= 1 && $number <= 16) return "BRUTE";
        else if($number >= 17 && $number <= 36) return "GUARDIAN";
        else if($number >= 37 && $number <= 52) return "NINJA";
        else if($number >= 53 && $number <= 68) return "WARRIOR";
        else if($number >= 69 && $number <= 84) return "MECHANOLOGIST";
        else if($number >= 85 && $number <= 86) return "MERCHANT";
        else if($number >= 87 && $number <= 102) return "RANGER";
        else if($number >= 103 && $number <= 119) return "RUNEBLADE";
        else if($number >= 120 && $number <= 136) return "WIZARD";
        else if($number >= 137 && $number <= 153) return "ILLUSIONIST";
        else return "GENERIC";
      case "UPR":
        if($number == 0) return "NONE";
        else if($number >= 1 && $number <= 43) return "ILLUSIONIST";
        else if($number >= 44 && $number <= 83) return "NINJA";
        else if($number >= 84 && $number <= 101) return "NONE";
        else if($number >= 102 && $number <= 135) return "WIZARD";
        else if($number >= 136 && $number <= 150) return "NONE";
        else if($number >= 151 && $number <= 157) return "ILLUSIONIST";
        else if($number >= 158 && $number <= 164) return "NINJA";
        else if($number >= 165 && $number <= 181) return "WIZARD";
        else if($number >= 182 && $number <= 223) return "GENERIC";
        else if($number >= 406 && $number <= 417) return "ILLUSIONIST";
        else return "NONE";
      case "DVR":
        if($number >= 2) return "WARRIOR";
        else if($number = 5) return "WARRIOR";
        else if($number >= 7 && $number <= 12) return "WARRIOR";
        else if($number >= 15 && $number <= 18) return "WARRIOR";
        else if($number >= 20 && $number <= 21) return "WARRIOR";
        else return "GENERIC";
      case "RVD":
        if($number >= 3) return "BRUTE";
        else if($number >= 7 && $number <= 17) return "BRUTE";
        else if($number = 21) return "BRUTE";
        else if($number = 23) return "BRUTE";
        else if($number = 25) return "BRUTE";
        else return "GENERIC";
      default: return 0;
    }
  }

  function CardTalent($cardID)
  {
    $set = substr($cardID, 0, 3);
    if($set == "MON") return MONCardTalent($cardID);
    else if($set == "ELE") return ELECardTalent($cardID);
    else if($set == "UPR") return UPRCardTalent($cardID);
    return "NONE";
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
    else if($set == "ELE")
    {
      switch($class)
      {
        case "GUARDIAN": return ELEGuardianCardCost($cardID);
        case "RANGER": return ELERangerCardCost($cardID);
        case "RUNEBLADE": return ELERunebladeCardCost($cardID);
        default: return ELETalentCardCost($cardID);
      }
    }
    else if($set == "EVR")
    {
      return EVRCardCost($cardID);
    }
    else if($set == "UPR")
    {
      return UPRCardCost($cardID);
    }
    else if($set == "DVR")
    {
      return DVRCardCost($cardID);
    }
    else if($set == "RVD")
    {
      return RVDCardCost($cardID);
    }
    switch($cardID)
    {
      case "WTR000": return -1;
      case "WTR153": return 0;//TODO: Change ability costs to a different function
      case "WTR078": case "WTR115": return 1;//TODO: Change ability costs to a different function
      case "CRU177": return 2;//TODO: Change ability costs to a different function
      case "ARC159": return 2;
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
      case "WTR151": case "WTR152": case "WTR154": return -1;
      case "WTR159": return 0;
      case "WTR160": return 1;
      case "WTR161": return 3;
      case "WTR162": return 0;
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
      case "CRU001": return -1;
      //CRU Brute
      case "CRU004": case "CRU005": return 2;
      case "CRU006": return 0;
      case "CRU007": case "CRU008": case "CRU009": return 3;
      case "CRU010": case "CRU011": case "CRU012": return 2;
      case "CRU013": case "CRU014": case "CRU015": return 2;
      case "CRU016": case "CRU017": case "CRU018": return 3;
      case "CRU019": case "CRU020": case "CRU021": return 1;
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
      //CRU Ninja
      case "CRU050": case "CRU051": case "CRU052": return 1;
      case "CRU054": case "CRU055": case "CRU056": return 0;
      case "CRU057": case "CRU058": case "CRU059": return 0;
      case "CRU060": case "CRU061": case "CRU062": return 0;
      case "CRU063": case "CRU064": case "CRU065": return 2;
      case "CRU066": case "CRU067": case "CRU068": return 0;
      case "CRU069": case "CRU070": case "CRU071": return 1;
      case "CRU072": return 1;
      case "CRU073": return 0;
      case "CRU074": return 1;
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
      //CRU Mechanologist
      case "CRU103": return 2;
      case "CRU104": return 0;
      case "CRU105": return 2;
      case "CRU106": case "CRU107": case "CRU108": return 1;
      case "CRU109": case "CRU110": case "CRU111": return 2;
      case "CRU112": case "CRU113": case "CRU114": return 2;
      case "CRU115": case "CRU116": case "CRU117": return 0;
      //CRU Ranger
      case "CRU121": return 0;
      case "CRU122": return 2;
      case "CRU123": return 1;
      case "CRU124": return 0;
      case "CRU125": return 1;
      case "CRU126": case "CRU127": case "CRU128": return 0;
      case "CRU129": case "CRU130": case "CRU131": return 0;
      case "CRU132": case "CRU133": case "CRU134": return 1;
      case "CRU135": case "CRU136": case "CRU137": return 1;
      //CRU Runeblade
      case "CRU140": return 1;
      case "CRU141": return 0;
      case "CRU142": return 3;
      case "CRU143": return 2;
      case "CRU144": return 3;
      case "CRU145": case "CRU146": case "CRU147": return 0;
      case "CRU148": case "CRU149": case "CRU150": return 1;
      case "CRU151": case "CRU152": case "CRU153": return 1;
      case "CRU154": case "CRU155": case "CRU156": return 1;
      //CRU Wizard
      case "CRU160": case "CRU161": return 0;
      case "CRU162": return 1;
      case "CRU163": return 0;
      case "CRU164": return 1;
      case "CRU165": case "CRU166": case "CRU167": return 0;
      case "CRU168": case "CRU169": case "CRU170": return 1;
      case "CRU171": case "CRU172": case "CRU173": return 2;
      case "CRU174": case "CRU175": case "CRU176": return 1;
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
    global $currentPlayer;
    $set = CardSet($cardID);
    $class = CardClass($cardID);
    $subtype = CardSubtype($cardID);
    if($class == "ILLUSIONIST" && $subtype == "Aura")
    {
      if(SearchCharacterForCard($currentPlayer, "MON003")) return 0;
      if(SearchCharacterForCard($currentPlayer, "MON088")) return 3;
    }
    if(DelimStringContains($subtype, "Dragon"))
    {
      if(SearchCharacterActive($currentPlayer, "UPR003")) return 0;
    }
    if($set == "ARC")
    {
      return ARCAbilityCost($cardID);
    }
    else if($set == "CRU" && ($class == "MECHANOLOGIST" || $class == "WIZARD" || $cardID == "CRU197" || $class == "MERCHANT"))
    {
      return CRUAbilityCost($cardID);
    }
    else if($set == "MON")
    {
      return MONAbilityCost($cardID);
    }
    else if($set == "ELE")
    {
      return ELEAbilityCost($cardID);
    }
    else if($set == "EVR")
    {
      return EVRAbilityCost($cardID);
    }
    else if($set == "UPR")
    {
      return UPRAbilityCost($cardID);
    }
    else if($set == "DVR")
    {
      return DVRAbilityCost($cardID);
    }
    else if($set == "RVD")
    {
      return RVDAbilityCost($cardID);
    }
    return CardCost($cardID);
  }

  function ResourcesPaidBlockModifier($cardID, $amountPaid)
  {
    switch($cardID)
    {
      case "MON241": case "MON242": case "MON243": case "MON244": case "RVD005": case "RVD006": return ($amountPaid >= 1 ? 2 : 0);
      case "UPR203": case "UPR204": case "UPR205": return ($amountPaid >= 1 ? 2 : 0);
      default: return 0;
    }
  }

  function DynamicCost($cardID)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "WTR051": case "WTR052": case "WTR053": return "2,6";
      case "ARC009": return "0,2,4,6,8,10,12";
      case "MON231": return "0,2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36,38,40";
      case "EVR022": return "3,4,5,6,7,8,9,10,11,12";
      case "EVR124": return GetIndices(SearchCount(SearchAura(($currentPlayer == 1 ? 2 : 1), "", "", 0))+1);
      case "UPR109": return "0,2,4,6,8,10,12,14,16,18,20";
      default:
        return "";
    }
  }

  function BlockDynamicCost($cardID)
  {
    switch($cardID)
    {
      case "MON089": return "0,1";
      case "MON241": case "MON242": case "MON243": case "MON244": case "RVD005": case "RVD006": return "0,1";
      case "ELE203": return "0,1";
      case "UPR203": case "UPR204": case "UPR205": return "0,1";
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
    else if($set == "ELE")
    {
      switch($class)
      {
        case "GUARDIAN": return ELEGuardianPitchValue($cardID);
        case "RANGER": return ELERangerPitchValue($cardID);
        case "RUNEBLADE": return ELERunebladePitchValue($cardID);
        default: return ELETalentPitchValue($cardID);
      }
    }
    else if($set == "EVR")
    {
      return EVRPitchValue($cardID);
    }
    else if($set == "UPR")
    {
      return UPRPitchValue($cardID);
    }
    else if($set == "DVR")
    {
      return DVRPitchValue($cardID);
    }
    else if($set == "RVD")
    {
      return RVDPitchValue($cardID);
    }
    switch($cardID)
    {
       case "WTR000": return 3;
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
      case "WTR113": case "WTR114": case "WTR115": case "WTR116": case "WTR117": return 0;
      case "WTR118": return 3;
      case "WTR119": case "WTR120": return 1;
      case "WTR121": case "WTR122": return 2;
      case "WTR123": case "WTR126": case "WTR129": case "WTR132": case "WTR135": case "WTR138": case "WTR141": case "WTR144": case "WTR147": return 1;
      case "WTR124": case "WTR127": case "WTR130": case "WTR133": case "WTR136": case "WTR139": case "WTR142": case "WTR145": case "WTR148": return 2;
      case "WTR125": case "WTR128": case "WTR131": case "WTR134": case "WTR137": case "WTR140": case "WTR143": case "WTR146": case "WTR149": return 3;
      case "WTR150": case "WTR151": case "WTR152": case "WTR153": case "WTR154": case "WTR155": case "WTR156": case "WTR157": case "WTR158": return 0;
      case "WTR159": return 1;
      case "WTR160": return 2;
      case "WTR161": return 3;
      case "WTR162": return 3;
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
      case "CRU002": return 0;
      case "CRU004": case "CRU005": case "CRU006": return 0;
      case "CRU007": return 2;
      case "CRU008": return 1;
      case "CRU009": return 2;
      case "CRU010": case "CRU013": case "CRU016": case "CRU019": return 1;
      case "CRU011": case "CRU014": case "CRU017": case "CRU020": return 2;
      case "CRU012": case "CRU015": case "CRU018": case "CRU021": return 3;
      //CRU Ninja
      case "CRU046": case "CRU047": case "CRU050": case "CRU051": case "CRU052": case "CRU053": return 0;
      case "CRU054": return 3;
      case "CRU055": return 2;
      case "CRU056": return 1;
      case "CRU057": case "CRU060": case "CRU063": case "CRU066": case "CRU069": return 1;
      case "CRU058": case "CRU061": case "CRU064": case "CRU067": case "CRU070": return 2;
      case "CRU059": case "CRU062": case "CRU065": case "CRU068": case "CRU071": return 3;
      case "CRU072": case "CRU073": case "CRU074": return 2;
      //CRU Warrior
      case "CRU077": return 0;
      case "CRU079": case "CRU080": return 0;
      case "CRU081": return 0;
      case "CRU082": case "CRU083": return 2;
      case "CRU084": return 1;
      case "CRU085": case "CRU088": case "CRU091": case "CRU094": return 1;
      case "CRU086": case "CRU089": case "CRU092": case "CRU095": return 2;
      case "CRU087": case "CRU090": case "CRU093": case "CRU096": return 3;
      //CRU Shapeshifter
      case "CRU097": return 0;
      //CRU Mechanologist
      case "CRU099": case "CRU101": case "CRU102": return 0;
      case "CRU103": return 3;
      case "CRU104": return 2;
      case "CRU105": return 1;
      case "CRU106": case "CRU109": case "CRU112": case "CRU115": return 1;
      case "CRU107": case "CRU110": case "CRU113": case "CRU116": return 2;
      case "CRU108": case "CRU111": case "CRU114": case "CRU117": return 3;
      //CRU Merchant
      case "CRU118": return "0";
      //CRU Ranger
      case "CRU121": case "CRU122": return 0;
      case "CRU123": return 1;
      case "CRU124": case "CRU125": return 2;
      case "CRU126": case "CRU129": case "CRU132": case "CRU135": return 1;
      case "CRU127": case "CRU130": case "CRU133": case "CRU136": return 2;
      case "CRU126": case "CRU131": case "CRU134": case "CRU137": return 3;
      //CRU Runeblade
      case "CRU140": case "CRU141": return 0;
      case "CRU143": return 1;
      case "CRU144": return 2;
      case "CRU145": case "CRU148": case "CRU151": case "CRU154": return 1;
      case "CRU146": case "CRU149": case "CRU152": case "CRU155": return 2;
      case "CRU147": case "CRU150": case "CRU153": case "CRU156": return 3;
      //CRU Wizard
      case "CRU160": case "CRU161": return 0;
      case "CRU162": return 2;
      case "CRU163": case "CRU164": return 3;
      case "CRU165": case "CRU168": case "CRU171": case "CRU174": return 1;
      case "CRU166": case "CRU169": case "CRU172": case "CRU175": return 2;
      case "CRU167": case "CRU170": case "CRU173": case "CRU176": return 3;
      //CRU Generic
      case "CRU177": return 0;
      case "CRU179": return 0;
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
    else if($set == "ELE")
    {
      switch($class)
      {
        case "GUARDIAN": return ELEGuardianBlockValue($cardID);
        case "RANGER": return ELERangerBlockValue($cardID);
        case "RUNEBLADE": return ELERunebladeBlockValue($cardID);
        default: return ELETalentBlockValue($cardID);
      }
    }
    else if($set == "EVR")
    {
      return EVRBlockValue($cardID);
    }
    else if($set == "UPR")
    {
      return UPRBlockValue($cardID);
    }
    else if($set == "DVR")
    {
      return DVRBlockValue($cardID);
    }
    else if($set == "RVD")
    {
      return RVDBlockValue($cardID);
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
      case "WTR162": return 0;
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
      case "CRU002": return 0;
      //CRU Guardian
      case "CRU024": return 0;
      case "CRU025": return 2;
      case "CRU041": case "CRU042": case "CRU043": return 0;
      //CRU Brute
      case "CRU004": case "CRU005": case "CRU006": return 0;
      //CRU Ninja
      case "CRU046": case "CRU047": case "CRU050": case "CRU051": case "CRU052": return 0;
      case "CRU053": return 1;
      case "CRU072": case "CRU074": return 2;
      //CRU Warrior
      case "CRU077": return 0;
      case "CRU079": case "CRU080": return 0;
      case "CRU081": return 2;
      //CRU Shapeshifter
      case "CRU097": return 0;
      //CRU Mechanologist
      case "CRU099": case "CRU101": case "CRU102": return 0;
      case "CRU104": case "CRU105": return 0;
      //CRU Ranger
      case "CRU121": return 0;
      case "CRU122": return 2;
      case "CRU124": return 2;
      case "CRU125": return 0;
      case "CRU126": return 4;
      case "CRU127": return 3;
      case "CRU128": return 2;
      case "CRU135": case "CRU136": case "CRU137": return 2;
      //CRU Runeblade
      case "CRU000": return 0;
      case "CRU140": return 0;
      case "CRU141": return 2;
      case "CRU144": return 2;
      case "CRU145": case "CRU146": case "CRU147": return 2;
      case "CRU154": case "CRU155": case "CRU156": return 2;
      //CRU Wizard
      case "CRU160": case "CRU161": case "CRU164": return 0;
      case "CRU165": case "CRU166": case "CRU167": return 2;
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
    global $combatChainState, $CCS_NumBoosted, $mainPlayer;
    $set = CardSet($cardID);
    $class = CardClass($cardID);
    $subtype = CardSubtype($cardID);
    if($class == "ILLUSIONIST" && $subtype == "Aura")
    {
      if(SearchCharacterForCard($mainPlayer, "MON003")) return 1;
      if(SearchCharacterForCard($mainPlayer, "MON088")) return 4;
    }
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
    else if($set == "ELE")
    {
      switch($class)
      {
        case "GUARDIAN": return ELEGuardianAttackValue($cardID);
        case "RANGER": return ELERangerAttackValue($cardID);
        case "RUNEBLADE": return ELERunebladeAttackValue($cardID);
        default: return ELETalentAttackValue($cardID);
      }
    }
    else if($set == "EVR")
    {
      return EVRAttackValue($cardID);
    }
    else if($set == "UPR")
    {
      return UPRAttackValue($cardID);
    }
    else if($set == "DVR")
    {
      return DVRAttackValue($cardID);
    }
    else if($set == "RVD")
    {
      return RVDAttackValue($cardID);
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
      //CRU Ninja
      case "CRU050": return 1;
      case "CRU051": case "CRU052": return 2;
      case "CRU054": return 2;
      case "CRU055": return 1;
      case "CRU056": return 3;
      case "CRU057": case "CRU060": return 3;
      case "CRU058": case "CRU061": return 2;
      case "CRU059": case "CRU062": return 1;
      case "CRU063": case "CRU069": return 5;
      case "CRU064": case "CRU066": case "CRU070": return 4;
      case "CRU065": case "CRU067": case "CRU071": case "CRU072": return 3;
      case "CRU068": case "CRU073": case "CRU074": return 2;
      //CRU Guardian
       return 6;
      case "CRU027": return 10;
      case "CRU026": case "CRU035": return 8;
      case "CRU032": case "CRU036": return 7;
      case "CRU024": case "CRU033": case "CRU037": return 6;
      case "CRU034": return 5;
      //CRU Warrior
      case "CRU079": case "CRU080": return 2;
      //CRU Mech
      case "CRU101": return 1 + $combatChainState[$CCS_NumBoosted];
      case "CRU103": return 4;
      case "CRU112": return 5;
      case "CRU106": case "CRU109": case "CRU113": return 4;
      case "CRU107": case "CRU110": case "CRU114": return 3;
      case "CRU108": case "CRU111": return 2;
      //CRU Ranger
      case "CRU123": return 5;
      case "CRU132": return 5;
      case "CRU129": case "CRU133": return 4;
      case "CRU130": case "CRU134": return 3;
      case "CRU131": return 2;
      //CRU Runeblade
      case "CRU140": return 3;
      case "CRU142": case "CRU148": case "CRU151": return 4;
      case "CRU149": case "CRU152": return 3;
      case "CRU150": case "CRU153": return 2;
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
    global $myDeck;
    $set = CardSet($cardID);
    if($set == "ARC")
    {
      return ARCHasGoAgain($cardID);
    }
    else if($set == "CRU")
    {
      return CRUHasGoAgain($cardID);
    }
    else if($set == "MON")
    {
      return MONHasGoAgain($cardID);
    }
    else if($set == "ELE")
    {
      return ELEHasGoAgain($cardID);
    }
    else if($set == "EVR")
    {
      return EVRHasGoAgain($cardID);
    }
    else if($set == "UPR")
    {
      return UPRHasGoAgain($cardID);
    }
    else if($set == "DVR")
    {
      return DVRHasGoAgain($cardID);
    }
    else if($set == "RVD")
    {
      return RVDHasGoAgain($cardID);
    }
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
      case "WTR161": return count($myDeck) == 0;
      case "WTR218": case "WTR219": case "WTR220": return true;
      case "WTR223": case "WTR222": case "WTR221": return true;
      default: return false;
    }
  }

  function GetAbilityType($cardID, $index=-1)
  {
    global $currentPlayer;
    $set = CardSet($cardID);
    $class = CardClass($cardID);
    $subtype = CardSubtype($cardID);
    if($class == "ILLUSIONIST" && $subtype == "Aura")
    {
      if(SearchCharacterForCard($currentPlayer, "MON003")) return "AA";
      if(SearchCharacterForCard($currentPlayer, "MON088")) return "AA";
    }
    if(DelimStringContains($subtype, "Dragon"))
    {
      if(SearchCharacterActive($currentPlayer, "UPR003")) return "AA";
    }
    if($set == "ARC")
    {
      return ARCAbilityType($cardID, $index);
    }
    else if($set == "CRU" && ($class == "MECHANOLOGIST" || $class == "WIZARD" || $cardID == "CRU197" || $class == "MERCHANT"))
    {
      return CRUAbilityType($cardID, $index);
    }
    else if($set == "MON")
    {
      return MONAbilityType($cardID, $index);
    }
    else if($set == "ELE")
    {
      return ELEAbilityType($cardID, $index);
    }
    else if($set == "EVR")
    {
      return EVRAbilityType($cardID, $index);
    }
    else if($set == "UPR")
    {
      return UPRAbilityType($cardID, $index);
    }
    else if($set == "DVR")
    {
      return DVRAbilityType($cardID, $index);
    }
    else if($set == "RVD")
    {
      return RVDAbilityType($cardID, $index);
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
      case "WTR162": return "A";
      case "WTR170": return "I";
      case "WTR171": case "WTR172": return "A";
      case "CRU004": case "CRU005": return "AA";
      case "CRU006": return "A";
      case "CRU024": return "AA";
      case "CRU025": return "A";
      case "CRU050": case "CRU051": case "CRU052": return "AA";
      case "CRU079": case "CRU080": return "AA";
      case "CRU081": return "A";
      case "CRU121": return "A";
      case "CRU122": return "A";
      case "CRU140": return "AA";
      case "CRU141": return "I";
      case "CRU177": return "AA";
      default: return "";
    }
  }

  function GetAbilityTypes($cardID)
  {
    switch($cardID)
    {
      case "ARC003": return "A,AA";
      default: return "";
    }
  }

  function GetAbilityNames($cardID, $index=-1)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "ARC003":
        $character = &GetPlayerCharacter($currentPlayer);
        if($index == -1) return "";
        $rv = "Add_a_steam_counter";
        if($character[$index+2] > 0) $rv .= ",Attack";
        return $rv;
      default: return "";
    }
  }

  function GetAbilityIndex($cardID, $index, $abilityName)
  {
    $names = explode(",", GetAbilityNames($cardID, $index));
    for($i=0; $i<count($names); ++$i)
    {
      if($abilityName == $names[$i]) return $i;
    }
    return 0;
  }

  function GetResolvedAbilityType($cardID)
  {
    global $currentPlayer, $CS_AbilityIndex;
    $abilityIndex = GetClassState($currentPlayer, $CS_AbilityIndex);
    $abilityTypes = GetAbilityTypes($cardID);
    if($abilityTypes == "" || $abilityIndex == "-") return GetAbilityType($cardID);
    else
    {
      $abilityTypes = explode(",", $abilityTypes);
      return $abilityTypes[$abilityIndex];
    }
  }

  function IsPlayable($cardID, $phase, $from, $index=-1, &$restriction=null)
  {
    global $myHand, $currentPlayer, $myClassState, $CS_NumActionsPlayed, $combatChainState, $CCS_BaseAttackDefenseMax;
    global $CCS_ResourceCostDefenseMin, $CCS_CardTypeDefenseRequirement, $actionPoints, $myCharacter, $mainPlayer, $playerID;
    global $combatChain, $myAllies, $myArsenal;
    $restriction = "";
    $cardType = CardType($cardID);
    $subtype = CardSubType($cardID);
    if($phase == "P" && $from != "HAND") return false;
    if($phase == "B" && $from == "BANISH") return false;
    if($phase == "B" && $cardType == "E") { $restriction = ($myCharacter[$index+6] == 1 ? "On combat chain" : ""); return $myCharacter[$index+6] == 0; }
    if($from == "CHAR" && $phase != "B" && $myCharacter[$index+8] == "1") { $restriction = "Frozen"; return false; }
    if($from == "PLAY" && $subtype == "Ally" && $phase != "B" && $myAllies[$index+3] == "1") { $restriction = "Frozen"; return false; }
    if($from == "ARS" && $phase != "B" && $myArsenal[$index+4] == "1") { $restriction = "Frozen"; return false; }
    if(($phase == "B" || ($phase == "D" && $cardType == "DR")) && $from == "HAND")
    {
      if(IsDominateActive() && NumBlockedFromHand() >= 1) return false;
      if(CachedTotalAttack() <= 2 && SearchCharacterForCard($mainPlayer, "CRU047") && CardType($combatChain[0]) == "AA") return false;
    }
    if($phase == "B" && $from == "ARS" && !($cardType == "AA" && SearchCurrentTurnEffects("ARC160-2", $currentPlayer))) return false;
    if($phase == "B" || $phase == "D")
    {
      if($cardType == "AA")
      {
        $baseAttackMax = $combatChainState[$CCS_BaseAttackDefenseMax];
        if($baseAttackMax > -1 && AttackValue($cardID) > $baseAttackMax) return false;
      }
      $resourceMin = $combatChainState[$CCS_ResourceCostDefenseMin];
      if($resourceMin > -1 && CardCost($cardID) < $resourceMin) return false;
      if($combatChainState[$CCS_CardTypeDefenseRequirement] == "Attack_Action" && $cardType != "AA") return false;
      if($combatChainState[$CCS_CardTypeDefenseRequirement] == "Non-attack_Action" && $cardType != "A") return false;
    }
    if($from != "PLAY" && $phase == "B" && $cardType != "DR") return BlockValue($cardID) > 0;
    if($phase == "P" && IsPitchRestricted($cardID, $restriction, $from, $index)) return false;
    if($from != "PLAY" && $phase == "P" && PitchValue($cardID) > 0) return true;
    $isStaticType = IsStaticType($cardType, $from, $cardID);
    if($isStaticType) { $cardType = GetAbilityType($cardID, $index); }
    if($cardType == "") return false;
    if(RequiresDiscard($cardID) || $cardID == "WTR159")
    {
      if($from == "HAND" && count($myHand) < 2) return false;//TODO: Account for where it was from
      else if(count($myHand) < 1) return false;
    }
    if($phase != "B" && $phase != "P" && IsPlayRestricted($cardID, $restriction, $from, $index)) return false;
    if($phase == "M" && $subtype == "Arrow" && $from != "ARS") return false;
    if($phase == "D" && $subtype == "Trap" && $from != "ARS") return false;
    if(SearchCurrentTurnEffects("ARC044", $currentPlayer) && !$isStaticType && $from != "ARS") return false;
    if(SearchCurrentTurnEffects("ARC043", $currentPlayer) && ($cardType == "A" || $cardType == "AA") && $myClassState[$CS_NumActionsPlayed] >= 1) return false;
    if(($cardType == "I" || CanPlayAsInstant($cardID, $index, $from)) && CanPlayInstant($phase)) return true;
    if(($cardType == "A" || $cardType == "AA") && $actionPoints < 1) return false;
    switch($cardType)
    {
      case "A": return $phase == "M";
      case "AA": return $phase == "M";
      case "AR": return $phase == "A";
      case "DR": return $phase == "D" && IsDefenseReactionPlayable($cardID, $from);
      default: return false;
    }
  }

  function GoesWhereAfterResolving($cardID, $from = null, $player="")
  {
    global $currentPlayer, $CS_NumWizardNonAttack, $CS_NumBoosted, $mainPlayer, $combatChainState, $CCS_AttackPlayedFrom;
    $otherPlayer = $currentPlayer == 2 ? 1 : 2;
    if($player == "") $player = $currentPlayer;
    if($from == "COMBATCHAIN" && $player != $mainPlayer && CardType($cardID) != "DR") return "GY";//If it was blocking, don't put it where it would go if it was played
    switch($cardID)
    {
      case "WTR163": return "BANISH";
      case "CRU163": return GetClassState($currentPlayer, $CS_NumWizardNonAttack) >= 2 ? "HAND" : "GY";
      case "MON063": return "SOUL";
      case "MON064": return "SOUL";
      case "MON231": return "BANISH";
      case "ELE113": return "BANISH";
      case "ELE140": case "ELE141": case "ELE142": return "BANISH";
      case "MON087":
        $theirChar = GetPlayerCharacter($otherPlayer);
        if(TalentContains($theirChar[0], "SHADOW") && PlayerHasLessHealth($currentPlayer)) return "SOUL";
        else return "GY";
      case "MON192": if($from=="BANISH") return "HAND";
      case "EVR082": case "EVR083": case "EVR084": return (GetClassState($currentPlayer, $CS_NumBoosted) > 0 ? "BOTDECK" : "GY");
      case "EVR134": case "EVR135": case "EVR136": return ($currentPlayer != $mainPlayer ? "BOTDECK" : "GY");
      case "UPR160": return ($from == "CHAINCLOSING" || $combatChainState[$CCS_AttackPlayedFrom] == "BANISH" ? "GY" : "BANISH,TCC");
      default: return "GY";
    }
  }

  function CanPlayInstant($phase)
  {
    if($phase == "M") return true;
    //if($phase == "B") return true;
    if($phase == "A") return true;
    if($phase == "D") return true;
    if($phase == "INSTANT") return true;
    return false;
  }

  function IsPitchRestricted($cardID, &$restriction, $from="", $index=-1)
  {
    global $playerID;
    if(SearchCurrentTurnEffects("ELE035-3", $playerID) && CardCost($cardID) == 0) { $restriction = "ELE035"; return true; }
    return false;
  }

  function IsPlayRestricted($cardID, &$restriction, $from="", $index=-1)
  {
    global $playerID, $myClassState, $theirClassState, $CS_NumBoosted, $combatChain, $myCharacter, $myHand, $combatChainState, $currentPlayer, $mainPlayer, $CS_Num6PowBan, $myDiscard;
    global $CS_DamageTaken, $myArsenal, $myItems, $mySoul, $CS_NumFusedEarth, $CS_NumFusedIce, $CS_NumFusedLightning, $CS_NumNonAttackCards;
    global $CS_NumAttackCards, $CS_NumBloodDebtPlayed, $layers, $CS_HitsWithWeapon, $CS_AtksWWeapon, $CS_CardsEnteredGY, $turn, $CS_NumRedPlayed, $CS_NumPhantasmAADestroyed;
    if(SearchCurrentTurnEffects("CRU032", $playerID) && CardType($cardID) == "AA" && AttackValue($cardID) <= 3) {$restriction = "CRU032"; return true; }
    if(SearchCurrentTurnEffects("MON007", $playerID) && $from == "BANISH") {$restriction = "MON007"; return true; }
    if(SearchCurrentTurnEffects("ELE036", $playerID) && CardType($cardID) == "E")  {$restriction = "ELE036"; return true; }
    if(SearchCurrentTurnEffects("ELE035-3", $playerID) && CardCost($cardID) == 0 && !IsStaticType(CardType($cardID), $from, $cardID))  { $restriction = "ELE035"; return true; }
    if(CardType($cardID) == "A" && $from != "PLAY" && GetClassState($playerID, $CS_NumNonAttackCards) == 1 && (SearchItemsForCard("EVR071", 1) != "" || SearchItemsForCard("EVR071", 2) != "")) {$restriction = "EVR071"; return true; }
    if($turn[0] != "B" && $turn[0] != "P" && $playerID != $mainPlayer && SearchAlliesForCard($mainPlayer, "UPR415")) { $restriction = "UPR415"; return true; }
    switch($cardID)
    {
      case "ARC004": return $myClassState[$CS_NumBoosted] < 1;
      case "ARC005": return $myClassState[$CS_NumBoosted] < 1;
      case "ARC008": return $myClassState[$CS_NumBoosted] < 3;
      case "ARC010": return (count($combatChain) > 0 && $from == "PLAY" && $myItems[$index+1] > 0 && (CardSubtype($combatChain[0]) != "Pistol" || $myItems[$index+2] != 2));
      case "ARC018": return (count($combatChain) > 0 && $from == "PLAY" && $myItems[$index+1] > 0 && (CardType($combatChain[0]) != "AA" || $myItems[$index+2] != 2));
      case "ARC041": return !ArsenalHasFaceDownCard($currentPlayer);//Restricted if you don't have a face down card
      case "WTR209": case "WTR210": case "WTR211":
        if(count($combatChain) == 0) return true;
        $subtype = CardSubtype($combatChain[0]);
        if($subtype == "Sword" || $subtype == "Dagger" || (CardType($combatChain[0]) == "AA" && CardCost($combatChain[0]) <= 1)) return false;
        return true;
      case "WTR080":
        if(count($combatChain) == 0) return true;
        return !HasCombo($combatChain[0]);
      case "WTR082": return count($combatChain) == 0 || CardClass($combatChain[0]) != "NINJA" || CardType($combatChain[0]) != "AA";
      case "WTR116":
        return GetClassState($playerID, $CS_HitsWithWeapon) == 0;
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
      case "CRU125": return !HasTakenDamage($currentPlayer);
      case "CRU143": return SearchCount(SearchDiscard($currentPlayer, "AA", "", -1, -1, "RUNEBLADE")) == 0;
      case "CRU164": return count($layers) == 0;
      case "CRU186":
        if(count($combatChain) == 0) return true;
        $type = CardType($combatChain[0]);
        return $type != "AA";
      case "MON000": return $from == "PLAY" && SearchCount(SearchHand($currentPlayer, "", "", -1, -1, "", "", false, false, 2)) < 2;
      case "MON001": case "MON002": return count($mySoul) == 0;
      case "MON029": case "MON030": return count($mySoul) == 0;
      case "MON062": return count($mySoul) < 3;
      case "MON084": case "MON085": case "MON086": return count($combatChain) == 0;
      case "MON144": case "MON145": case "MON146": return GetClassState($currentPlayer, $CS_Num6PowBan) == 0;
      case "MON126": case "MON127": case "MON128":
      case "MON129": case "MON130": case "MON131":
      case "MON132": case "MON133": case "MON134":
      case "MON135": case "MON136": case "MON137":
      case "MON141": case "MON142": case "MON143":
      case "MON147": case "MON148": case "MON149":
      case "MON150": case "MON151": case "MON152": return count($myDiscard) < 3;
      case "MON189": return SearchCount(SearchBanish($currentPlayer, "", "", -1, -1, "", "", true)) < 6;
      case "MON190": return $myClassState[$CS_NumBloodDebtPlayed] < 6;
      case "MON198": $discard = GetDiscard($currentPlayer); return count($discard) < 6;
      case "MON230": return GetClassState($currentPlayer, $CS_NumAttackCards) == 0 || GetClassState($currentPlayer, $CS_NumNonAttackCards) == 0;
      case "MON238": return $myClassState[$CS_DamageTaken] == 0 && $theirClassState[$CS_DamageTaken] == 0;
      case "ELE118": return ArsenalEmpty($currentPlayer);
      case "ELE143": return $from == "PLAY" && GetClassState($currentPlayer, $CS_NumFusedEarth) == 0;
      case "ELE147": return count($combatChain) == 0;
      case "ELE172": return $from == "PLAY" && GetClassState($currentPlayer, $CS_NumFusedIce) == 0;
      case "ELE183": case "ELE184": case "ELE185": return count($combatChain) == 0 || CardType($combatChain[0]) != "AA" || CardCost($combatChain[0]) > 1;
      case "ELE201": return $from == "PLAY" && GetClassState($currentPlayer, $CS_NumFusedLightning) == 0;
      case "ELE224": return GetClassState($currentPlayer, $CS_NumAttackCards) == 0;
      case "ELE225": return count($combatChain) == 0 || CardType($combatChain[0]) != "AA" || GetClassState($currentPlayer, $CS_NumNonAttackCards) == 0;
      case "ELE233": return count($myHand) != 1;
      case "ELE234": return count($myHand) == 0;
      case "ELE236": return !HasTakenDamage($currentPlayer);
      case "EVR054": return count($combatChain) == 0 || CardType($combatChain[0]) != "W" || Is1H($combatChain[0]);
      case "EVR060": case "EVR061": case "EVR062": return count($combatChain) == 0 || CardType($combatChain[0]) != "W" || !Is1H($combatChain[0]);
      case "EVR063": case "EVR064": case "EVR065": return GetClassState($currentPlayer, $CS_AtksWWeapon) < 1;
      case "EVR173": case "EVR174": case "EVR175": return $theirClassState[$CS_DamageTaken] == 0;
      case "EVR176": $hand = &GetHand($currentPlayer); return $from == "PLAY" && count($hand) < 4;
      case "EVR178": $hand = &GetHand($currentPlayer); return ($from == "PLAY" && count($hand) > 0);
      case "EVR053": return !HelmOfSharpEyePlayable();
      case "EVR181": return $from == "PLAY" && (GetClassState(1, $CS_CardsEnteredGY) == 0 && GetClassState(2, $CS_CardsEnteredGY) == 0 || count($combatChain) == 0 || CardType($combatChain[0]) != "AA");
      case "DVR013": return (count($combatChain) == 0 || CardType($combatChain[0]) != "W" || CardSubType($combatChain[0]) != "Sword");
      case "DVR014": case "DVR023": return count($combatChain) == 0 || CardSubType($combatChain[0]) != "Sword";
      case "UPR085": return GetClassState($currentPlayer, $CS_NumRedPlayed) == 0;
      case "UPR089": $restriction = "UPR089"; return NumDraconicChainLinks() < 4;
      case "UPR153": return GetClassState($currentPlayer, $CS_NumPhantasmAADestroyed) < 1;
      case "UPR159": return count($combatChain) == 0 || AttackValue($combatChain[0]) > 2 || CardType($combatChain[0]) != "AA";
      case "UPR162": case "UPR163": case "UPR164": return count($combatChain) == 0 || CardType($combatChain[0]) != "AA" || CardCost($combatChain[0]) > 0;
      case "UPR165": return GetClassState($currentPlayer, $CS_NumNonAttackCards) == 0;
      case "UPR167": return $currentPlayer == $mainPlayer;
      default: return false;
    }
  }

  function IsDefenseReactionPlayable($cardID, $from)
  {
    global $combatChain, $mainPlayer;
    if($combatChain[0] == "ARC159" && CardType($cardID) == "DR") return false;
    if($combatChain[0] == "MON245") if(!ExudeConfidenceReactionsPlayable()) return false;
    if($from == "HAND" && CardSubType($combatChain[0]) == "Arrow" && SearchCharacterForCard($mainPlayer, "EVR087")) return false;
    if(CurrentEffectPreventsDefenseReaction($from)) return false;
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
    if($phase != "B" && $from == "EQUIP" || $from == "PLAY") $cardType = GetResolvedAbilityType($cardID);
    elseif($phase=="M" && $cardID == "MON192" && $from== "BANISH") $cardType = GetResolvedAbilityType($cardID);
    else $cardType = CardType($cardID);
    if($cardType == "I") return false;//Instants as yet never go on the combat chain
    if($phase == "B") return true;//Anything you play during these combat phases would go on the chain
    if(($phase == "A" || $phase == "D") && $cardType == "A") return false;//Non-attacks played as instants never go on combat chain
    if($cardType == "AR") return true;
    if($cardType == "DR") return true;
    if($phase == "M" && $cardType == "AA") return true;//If it's an attack action, it goes on the chain
    return false;
  }

  function IsStaticType($cardType, $from="", $cardID="")
  {
    if($cardType == "C" || $cardType == "E" || $cardType == "W") return true;
    if($from == "PLAY") return true;
    if($cardID != "" && $from == "BANISH" && AbilityPlayableFromBanish($cardID)) return true;
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
      case "CRU122": return true;
      case "MON060": return true;
      case "ELE144": return true;
      case "ELE204": return true;
      case "ELE213": return true;
      case "ELE224": return true;
      case "EVR037": return true;
      case "EVR086": return true;
      case "DVR003": case "DVR006": return true;
      case "RVD003": return true;
      case "UPR136": return true;
      case "UPR158": return true;
      case "UPR182": return true;
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
      case "WTR116": case "WTR117": return true;
      case "ARC004": return true;
      case "ARC078": return true;
      case "ARC150": return true;
      case "CRU053": return true;
      case "MON107": case "MON108": return true;
      case "MON122": return true;
      case "MON230": return true;
      case "EVR001": return true;
      case "EVR053": return true;
      case "DVR005": return true;
      default: return false;
    }
  }

  function HasTemper($cardID)
  {
    switch($cardID)
    {
      case "CRU025": return true;
      case "CRU081": return true;
      case "CRU141": return true;
      case "EVR018": return true;
      case "EVR020": return true;
      case "UPR084": return true;
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
      case "CRU104": return 0;
      case "CRU105": return 0;
      case "EVR069": return 1;
      case "EVR071": return 1;
      case "EVR072": return 3;
      default: return 0;
    }
  }

  function AbilityHasGoAgain($cardID)
  {
    global $currentPlayer;
    $set = CardSet($cardID);
    $class = CardClass($cardID);
    $subtype = CardSubtype($cardID);
    if($class == "ILLUSIONIST" && $subtype == "Aura")
    {
      if(SearchCharacterForCard($currentPlayer, "MON088")) return true;
    }
    if($set == "ARC")
    {
      return ARCAbilityHasGoAgain($cardID);
    }
    else if($set == "CRU")
    {
      return CRUAbilityHasGoAgain($cardID);
    }
    else if($set == "MON")
    {
      return MONAbilityHasGoAgain($cardID);
    }
    else if($set == "ELE")
    {
      return ELEAbilityHasGoAgain($cardID);
    }
    else if($set == "EVR")
    {
      return EVRAbilityHasGoAgain($cardID);
    }
    switch($cardID)
    {
      case "WTR038": case "WTR039": return true;
      case "WTR041": return true;
      case "WTR116": return true;
      case "WTR152": return true;
      case "WTR153": return true;
      case "WTR162": return GetDieRoll($currentPlayer) <= 4;
      case "WTR171": return true;
      case "RVD004": return true;
      case "DVR004": return true;
      default: return false;
    }
  }

  function DoesEffectGrantDominate($cardID)
  {
    global $combatChainState, $CCS_AttackFused;
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
      case "MON109": return true;
      case "MON129": case "MON130": case "MON131": return true;
      case "MON132": case "MON133": case "MON134": return true;
      case "MON195": case "MON196": case "MON197": return true;
      case "MON223": case "MON224": case "MON225": return true;
      case "MON278": case "MON279": case "MON280": return true;
      case "MON406": return true;
      case "ELE005": return true;
      case "ELE016": case "ELE017": case "ELE018": return true;
      case "ELE033-2": return true;
      case "ELE056": case "ELE057": case "ELE058": return true;
      case "ELE092-DOMATK": return true;
      case "ELE097": case "ELE098": case "ELE099": return true;
      case "ELE154": case "ELE155": case "ELE156": return $combatChainState[$CCS_AttackFused] == 1;
      case "ELE166": case "ELE167": case "ELE168": return true;
      case "ELE205": return true;
      case "EVR017": return true;
      case "EVR019": return true;
      case "UPR091": return true;
      default: return false;
    }
  }

  function CharacterNumUsesPerTurn($cardID)
  {
    switch($cardID)
    {
      case "ELE034": return 2;
      default: return 1;
    }
  }

  function ArsenalNumUsesPerTurn($cardID)
  {
    switch($cardID)
    {
      default: return 1;
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
      case "CRU054":
      case "CRU055":
      case "CRU056":
      case "CRU057": case "CRU058": case "CRU059":
      case "CRU060": case "CRU061": case "CRU062": return true;
      case "EVR038": case "EVR040":
      case "EVR041": case "EVR042": case "EVR043": return true;
    }
    return false;
  }

  function ComboActive($cardID = "")
  {
    global $combatChainState, $CCS_LastAttack, $combatChain;
    if($cardID == "" && count($combatChain) > 0) $cardID = $combatChain[0];
    if($cardID == "") return false;
    $LA = $combatChainState[$CCS_LastAttack];
    if($LA == "NA") return false;
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
      case "CRU054": return $LA == "CRU057" || $LA == "CRU058" || $LA == "CRU059";
      case "CRU055": return $LA == "CRU055" || $LA == "CRU060" || $LA == "CRU061" || $LA == "CRU062";
      case "CRU056": return $LA == "CRU057" || $LA == "CRU058" || $LA == "CRU059";
      case "CRU057": case "CRU058": case "CRU059": return $LA == "CRU066" || $LA == "CRU067" || $LA == "CRU068";
      case "CRU060": case "CRU061": case "CRU062": return $LA == "CRU069" || $LA == "CRU070" || $LA == "CRU071";
      case "EVR038": return $LA == "CRU060" || $LA == "CRU061" || $LA == "CRU062" || $LA == "CRU055";
      case "EVR040": return $LA == "EVR041" || $LA == "EVR042" || $LA == "EVR043";
      case "EVR041": case "EVR042": case "EVR043": return $LA == "EVR041" || $LA == "EVR042" || $LA == "EVR043";
    }
    return false;
  }

  function HasBloodDebt($cardID)
  {
    switch($cardID)
    {
      //Shadow Brute
      case "MON123"; return true;
      case "MON124"; return true;
      case "MON125"; return true;
      case "MON126": case "MON127": case "MON128"; return true;
      case "MON129": case "MON130": case "MON131"; return true;
      case "MON135": case "MON136": case "MON137"; return true;
      case "MON138": case "MON139": case "MON140"; return true;
      case "MON141": case "MON142": case "MON143"; return true;
      case "MON144": case "MON145": case "MON146"; return true;
      case "MON147": case "MON148": case "MON149"; return true;
      //Shadow Runeblade
      case "MON156": case "MON158":
      case "MON159": case "MON160": case "MON161":
      case "MON165": case "MON166": case "MON167":
      case "MON168": case "MON169": case "MON170":
      case "MON171": case "MON172": case "MON173":
      case "MON174": case "MON175": case "MON176":
      case "MON177": case "MON178": case "MON179":
      case "MON180": case "MON181": case "MON182":
      case "MON183": case "MON184": case "MON185": return true;
      //Shadow
      case "MON187": case "MON191": case "MON192": case "MON194":
      case "MON200": case "MON201": case "MON202":
      case "MON203": case "MON204": case "MON205":
      case "MON209": case "MON210": case "MON211": return true;
      default: return false;
    }
  }

  function PlayableFromBanish($cardID)
  {
    global $currentPlayer, $CS_NumNonAttackCards, $CS_Num6PowBan;
    switch($cardID)
    {
      //Shadow Brute
      case "MON123": return GetClassState($currentPlayer, $CS_Num6PowBan) > 0;
      //Shadow Runeblade
      case "MON156": case "MON158": return true;
      case "MON159": case "MON160": case "MON161": return GetClassState($currentPlayer, $CS_NumNonAttackCards) > 0;
      case "MON165": case "MON166": case "MON167": return true;
      case "MON168": case "MON169": case "MON170": return GetClassState($currentPlayer, $CS_NumNonAttackCards) > 0;
      case "MON171": case "MON172": case "MON173": return true;
      case "MON174": case "MON175": case "MON176": return true;
      case "MON177": case "MON178": case "MON179": return true;
      case "MON180": case "MON181": case "MON182": return true;
      case "MON183": case "MON184": case "MON185": return true;
      //Shadow
      case "MON190": return true;//Eclipse - Since play is restricted by num played, it's fine to not restrict this
      case "MON191": case "MON194": return true;
      case "MON200": case "MON201": case "MON202":
      case "MON203": case "MON204": case "MON205":
      case "MON209": case "MON210": case "MON211": return true;
    }
  }

  function AbilityPlayableFromBanish($cardID)
  {
    switch($cardID)
    {
      case "MON192": return true;
      default: return false;
    }
  }

  function RequiresDieRoll($cardID, $from)
  {
    global $currentPlayer, $turn;
    if($turn[0] == "B") return false;
    $type = CardType($cardID);
    if($type == "AA" && SearchCharacterActive($currentPlayer, "CRU002") && AttackValue($cardID) >= 6) return true;
    switch($cardID)
    {
      case "WTR004": case "WTR005": return true;
      case "WTR010": return true;
      case "WTR162": return $from == "PLAY";
      case "CRU009": return true;
      case "EVR004": return true;
      case "EVR014": case "EVR015": case "EVR016": return true;
    }
    return false;
  }

  function SpellVoidAmount($cardID)
  {
    switch($cardID)
    {
      case "ELE173": return 2;
      case "MON061": return 2;
      case "MON090": return 1;
      case "MON188": return 2;
      case "MON302": return 1;
      case "MON400": return 1;
      case "MON401": return 1;
      case "MON402": return 1;
    }
    return 0;
  }

  function IsSpecialization($cardID)
  {
    switch($cardID)
    {
      case "WTR006": case "WTR009": case "WTR043": case "WTR047": case "WTR081": case "WTR083": case "WTR119": case "WTR121": return true;
      case "ARC007": case "ARC009": case "ARC043": case "ARC046": case "ARC080": case "ARC083": case "ARC118": case "ARC121": return true;
      case "CRU000": case "CRU074": return true;
      case "MON005": case "MON007": case "MON035": case "MON036": case "MON189": case "MON190": case "MON198": case "MON199": return true;
      case "ELE004": case "ELE036": case "ELE066": return true;
      case "EVR003": case "EVR039": case "EVR055": case "EVR070": return true;
      case "DVR008": case "RVD008": return true;
      default: return false;
    }
  }

  function Is1H($cardID)
  {
    switch($cardID)
    {
      case "WTR078": return true;
      case "CRU004": case "CRU005": return true;
      case "CRU051": case "CRU052": return true;
      case "CRU079": case "CRU080": return true;
      case "MON105": case "MON106": return true;
      case "ELE003": case "ELE202": return true;
      default: return false;
    }
  }

  function AbilityPlayableFromCombatChain($cardID)
  {
    switch($cardID)
    {
      case "MON245": return true;
      case "MON281": case "MON282": case "MON283": return true;
      case "ELE195": case "ELE196": case "ELE197": return true;
      case "EVR157": return true;
      default: return false;
    }
  }

?>
