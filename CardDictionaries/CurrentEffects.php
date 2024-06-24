<?php
function AAZEffectAttackModifier($cardID) 
{
  switch($cardID) {
    case "AAZ024": return 3;
    default: return 0;
  }
}

function AAZCombatEffectActive($cardID, $attackID)
{
  switch ($cardID) {
    case "AAZ024": return CardSubType($attackID) == "Arrow";
    case "AAZ004": return CardSubType($attackID) == "Arrow" && HasAimCounter();
    default: return false;
  }
}


function MSTEffectAttackModifier($cardID) {
  global $mainPlayer, $CS_Transcended, $combatChain;
  $idArr = explode(",", $cardID);
  $cardID = $idArr[0];
  switch($cardID) {
    case "MST000": return IsHeroAttackTarget() ? -1 : 0;
    case "MST003": return 1;
    case "MST011-1": return 3;
    case "MST012-1": return 2;
    case "MST013-1": return 1;
    case "MST011-2": return 5;
    case "MST012-2": return 4;
    case "MST013-2": return 3;
    case "MST014": return 3;
    case "MST015": return 2;
    case "MST016": return 1;
    case "MST017-BUFF": case "MST018-BUFF": case "MST019-BUFF": return 1;
    case "MST020": return 3;
    case "MST021": return 2;
    case "MST022": return 1;
    case "MST023": return 1;
    case "MST051-BUFF": return 1;
    case "MST053": return 1;
    case "MST054-1": return 3;
    case "MST055-1": return 2;
    case "MST056-1": return 1;
    case "MST054-2": return 5;
    case "MST055-2": return 4;
    case "MST056-2": return 3;
    case "MST063": return 3;
    case "MST064": return 2;
    case "MST065": return 1;
    case "MST069": return 1;
    case "MST075": return 3;
    case "MST077": return 2;
    case "MST079-DEBUFF": return IsHeroAttackTarget() ? -1 : 0;
    case "MST084": return SearchPitchForColor($mainPlayer, 3);
    case "MST085": return SearchPitchForColor($mainPlayer, 3);
    case "MST093": return 2;
    case "MST095": return -1;
    case "MST102": return 1;
    case "MST105-BUFF": return 5;
    case "MST159": return 1;
    case "MST161": return 3;
    case "MST162-BUFF": return 3;
    case "MST185": case "MST186": case "MST187": return 1;
    case "MST190": return -1;
    case "MST193-RED": return 1;
    case "MST193-YELLOW": return 2;
    case "MST193-BLUE": return 3;
    case "MST198": return 2;
    case "MST212": case "MST213": case "MST214": return 1;
    case "MST232": return 4;
    case "MST233": return 1;
    case "MST236-1": return 1;
    default: return 0;
  }
}

function MSTCombatEffectActive($cardID, $attackID)
{
  global $mainPlayer, $CS_NumBluePlayed, $combatChainState, $CCS_LinkBaseAttack, $CS_Transcended, $CombatChain;
  $from = $CombatChain->AttackCard()->From();
  $idArr = explode(",", $cardID);
  $cardID = $idArr[0];
  switch($cardID) {
    case "MST000":return IsHeroAttackTarget();
    case "MST003": return ColorContains($attackID, 3, $mainPlayer);
    case "MST011-1": case "MST012-1": case "MST013-1": 
    case "MST011-2": case "MST012-2": case "MST013-2": 
      return true;
    case "MST014": case "MST015": case "MST016": return true;
    case "MST017-BUFF": case "MST018-BUFF": case "MST019-BUFF": return true;
    case "MST020": case "MST021": case "MST022": return true;
    case "MST023": case "MST024": return true;
    case "MST051-BUFF": return true;
    case "MST054-1": case "MST055-1": case "MST056-1": 
    case "MST054-2": case "MST055-2": case "MST056-2":
    case "MST053": 
    case "MST063": case "MST064": case "MST065":
      return CardNameContains($attackID, "Crouching Tiger", $mainPlayer);
    case "MST069": return true;
    case "MST075": return true;
    case "MST077": return true;
    case "MST079-DEBUFF": return true;
    case "MST079-HITPREVENTION": return true;
    case "MST084": case "MST085": case "MST086": return true;
    case "MST092": return ColorContains($attackID, 3, $mainPlayer);
    case "MST093": return ColorContains($attackID, 3, $mainPlayer);
    case "MST094": return $from != "PLAY" && ColorContains($attackID, 3, $mainPlayer) && (TypeContains($attackID, "AA", $mainPlayer) || TypeContains($attackID, "A", $mainPlayer));
    case "MST095": return true;
    case "MST102": return true;
    case "MST105-BUFF": return true;
    case "MST105-HIT": return true;
    case "MST159": case "MST161": return CardNameContains($attackID, "Crouching Tiger", $mainPlayer);
    case "MST162-BUFF": return true;
    case "MST162-HIT": return true;
    case "MST185": case "MST186": case "MST187": return CardNameContains($attackID, "Crouching Tiger", $mainPlayer);
    case "MST190": return true;
    case "MST193-RED": return ColorContains($attackID, 1, $mainPlayer);
    case "MST193-YELLOW": return ColorContains($attackID, 2, $mainPlayer);
    case "MST193-BLUE": return ColorContains($attackID, 3, $mainPlayer);
    case "MST198": return true;
    case "MST212": case "MST213": case "MST214": return $combatChainState[$CCS_LinkBaseAttack] <= 1;
    case "MST232": return CardSubType($attackID) == "Arrow";
    case "MST233": return true;
    case "MST236-1": case "MST236-2": return true;
    default: return false;
  }
}

  function TCCEffectAttackModifier($cardID)
  {
    $idArr = explode(",", $cardID);
    $cardID = $idArr[0];
    switch($cardID) {
      case "TCC037": return 6;
      case "TCC038": return 5;
      case "TCC042": return 5;
      case "TCC043": return 4;
      case "TCC057": return $idArr[1];
      case "TCC083": return 1;
      case "TCC086": case "TCC094": return 1;
      case "TCC105": return 1;
      case "TCC409": return 1;
      default: return 0;
    }
  }

  function TCCCombatEffectActive($cardID, $attackID)
  {
    global $mainPlayer;
    $idArr = explode(",", $cardID);
    $cardID = $idArr[0];
    switch($cardID) {
      case "TCC035": case "HVY063": return true;
      case "TCC037": case "TCC038": case "TCC042": case "TCC043": return ClassContains($attackID, "GUARDIAN", $mainPlayer) && CardType($attackID) == "AA";
      case "TCC057": return true;
      case "TCC083": return true;
      case "TCC086": case "TCC094": return CardNameContains($attackID, "Crouching Tiger", $mainPlayer);
      case "TCC105": return true;
      case "TCC409": return true;
      default: return false;
    }
  }

  function EVOEffectAttackModifier($cardID)
  {
    $idArr = explode(",", $cardID);
    $cardID = $idArr[0];
    switch($cardID) {
      case "EVO016": return 1;
      case "EVO052": return 1;
      case "EVO090": case "EVO091": case "EVO092": return $idArr[1];
      case "EVO126": case "EVO127": case "EVO128": return 1;
      case "EVO140": return 3;
      case "EVO141": return 2;
      case "EVO153": case "EVO154": case "EVO155": return 2;
      case "EVO156": return 4;
      case "EVO157": return 3;
      case "EVO158": return 2;
      case "EVO192": case "EVO193": case "EVO194":
      case "EVO195": case "EVO196": case "EVO197": return 1;
      case "EVO222": case "EVO225": case "EVO228": return 4;
      case "EVO223": case "EVO226": case "EVO229": return 3;
      case "EVO224": case "EVO227": case "EVO230": return 2;
      case "EVO240": return 1;
      case "EVO432": return 2;
      case "EVO436": return 1;
      default: return 0;
    }
  }

  function EVOCombatEffectActive($cardID, $attackID)
  {
    global $mainPlayer, $combatChainState, $CCS_IsBoosted, $CS_NumItemsDestroyed;
    $idArr = explode(",", $cardID);
    $cardID = $idArr[0];
    switch($cardID) {
      case "EVO016": return ClassContains($attackID, "MECHANOLOGIST", $mainPlayer);
      case "EVO052": return true;
      case "EVO090": case "EVO091": case "EVO092": return true;
      case "EVO102": case "EVO103": case "EVO104": return true;
      case "EVO105": case "EVO106": case "EVO107": return true;
      case "EVO126": case "EVO127": case "EVO128": return true;
      case "EVO140": return true;
      case "EVO141": return true;
      case "EVO146": return true;
      case "EVO153": case "EVO154": case "EVO155": return true;
      case "EVO156": case "EVO157": case "EVO158": return ClassContains($attackID, "MECHANOLOGIST", $mainPlayer);
      case "EVO192": case "EVO193": case "EVO194":
      case "EVO195": case "EVO196": case "EVO197": return true;
      case "EVO222": case "EVO223": case "EVO224":
      case "EVO225": case "EVO226": case "EVO227":
      case "EVO228": case "EVO229": case "EVO230": return $combatChainState[$CCS_IsBoosted];
      case "EVO240": return TypeContains($attackID, "W", $mainPlayer);
      case "EVO432": return true;
      case "EVO434": return TypeContains($attackID, "W", $mainPlayer);
      case "EVO436": return TypeContains($attackID, "W", $mainPlayer);
      default: return false;
    }
  }

  function HVYEffectAttackModifier($cardID)
  {
    $idArr = explode(",", $cardID);
    $cardID = $idArr[0];
    switch($cardID) {
      case "HVY017": case "HVY018": case "HVY019": return 2;
      case "HVY041": case "HVY042": case "HVY043": return $idArr[1];
      case "HVY045": case "HVY046": return 1;
      case "HVY053": return $idArr[1] == "ACTIVE" ? -1 : 0;
      case "HVY058": return 1;
      case "HVY059": return 3;
      case "HVY083-BUFF": return 5;
      case "HVY084-BUFF": return 4;
      case "HVY085-BUFF": return 3;
      case "HVY086-BUFF": return 5;
      case "HVY087-BUFF": return 4;
      case "HVY088-BUFF": return 3;
      case "HVY101": return 2;
      case "HVY104-BUFF": return 3;
      case "HVY106": return 3;
      case "HVY107": return 2;
      case "HVY108": return 1;
      case "HVY109": return 5;
      case "HVY110": return 4;
      case "HVY111": return 3;
      case "HVY115": return 3;
      case "HVY116": return 2;
      case "HVY117": return 1;
      case "HVY118": return 3;
      case "HVY119": return 2;
      case "HVY120": return 1;
      case "HVY121": return 3;
      case "HVY122": return 2;
      case "HVY123": return 1;
      case "HVY124-BUFF": return 3;
      case "HVY125-BUFF": return 2;
      case "HVY126-BUFF": return 1;
      case "HVY127": return 3;
      case "HVY128": return 2;
      case "HVY129": return 1;
      case "HVY130-BUFF": return 3;
      case "HVY131-BUFF": return 2;
      case "HVY132-BUFF": return 1;
      case "HVY152": return 3;
      case "HVY153": return 2;
      case "HVY154": return 1;
      case "HVY172": return 3;
      case "HVY173": return 2;
      case "HVY174": return 1;
      case "HVY176-BUFF": return 3;
      case "HVY192": return 3;
      case "HVY193": return 2;
      case "HVY194": return 1;
      case "HVY210": return 2;
      case "HVY211": return $idArr[1];
      case "HVY213": case "HVY214": case "HVY215": return 3;
      case "HVY235-BUFF": return 3;
      case "HVY236-BUFF": return 2;
      case "HVY237-BUFF": return 1;
      case "HVY241": return 1;
      case "HVY247": return 1;
      default: return 0;
    }
  }

  function HVYCombatEffectActive($cardID, $attackID)
  {
    global $mainPlayer, $combatChainState, $CombatChain;
    $idArr = explode(",", $cardID);
    $cardID = $idArr[0];
    switch($cardID) {
      case "HVY041": case "HVY042": case "HVY043": return ClassContains($CombatChain->AttackCard()->ID(), "BRUTE", $mainPlayer);
      case "HVY045": case "HVY046": return true;
      case "HVY058": return true;
      case "HVY052": return true;
      case "HVY053": return true;
      case "HVY055-PAID": return true;
      case "HVY059": return true;
      case "HVY083": case "HVY084": case "HVY085": return true;
      case "HVY083-BUFF": case "HVY084-BUFF": case "HVY085-BUFF": return ClassContains($CombatChain->AttackCard()->ID(), "GUARDIAN", $mainPlayer);
      case "HVY086": case "HVY087": case "HVY088": return true;
      case "HVY086-BUFF": case "HVY087-BUFF": case "HVY088-BUFF": return ClassContains($CombatChain->AttackCard()->ID(), "GUARDIAN", $mainPlayer);
      case "HVY090": case "HVY091": return TypeContains($attackID, "W", $mainPlayer) && !IsAllyAttackTarget();
      case "HVY099": return true;
      case "HVY101": return TypeContains($attackID, "W", $mainPlayer);
      case "HVY104": case "HVY104-BUFF": return ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer);
      case "HVY106": case "HVY107": case "HVY108":
      case "HVY109": case "HVY110": case "HVY111":
      case "HVY115": case "HVY116": case "HVY117":
      case "HVY118": case "HVY119": case "HVY120":
          return true;
      case "HVY121": case "HVY122": case "HVY123": return ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer);
      case "HVY124-BUFF": case "HVY125-BUFF": case "HVY126-BUFF": return ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer);
      case "HVY127": case "HVY128": case "HVY129": return ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer);
      case "HVY130-BUFF": case "HVY131-BUFF": case "HVY132-BUFF": return ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer);
      case "HVY136": return true;
      case "HVY149": case "HVY150": case "HVY151": return true;
      case "HVY152": case "HVY153": case "HVY154": return ClassContains($CombatChain->AttackCard()->ID(), "BRUTE", $mainPlayer) || ClassContains($CombatChain->AttackCard()->ID(), "GUARDIAN", $mainPlayer);
      case "HVY169": case "HVY170": case "HVY171": return true;
      case "HVY172": case "HVY173": case "HVY174": return ClassContains($CombatChain->AttackCard()->ID(), "BRUTE", $mainPlayer) || ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer);
      case "HVY176-BUFF": return CachedWagerActive();
      case "HVY189": case "HVY190": case "HVY191": return true;
      case "HVY192": case "HVY193": case "HVY194": return ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer) || ClassContains($CombatChain->AttackCard()->ID(), "GUARDIAN", $mainPlayer);
      case "HVY202": case "HVY203": case "HVY204": case "HVY205": case "HVY206": return true;
      case "HVY210": return true;
      case "HVY211": return true;
      case "HVY213": case "HVY214": case "HVY215": return true;
      case "HVY216": case "HVY217": case "HVY218": return true;
      case "HVY235-BUFF": case "HVY236-BUFF": case "HVY237-BUFF": return true;
      case "HVY240": return true;
      case "HVY241": return true;
      case "HVY247": return HasCombo($attackID);
      case "HVY254-1": return str_contains(NameOverride($CombatChain->AttackCard()->ID(), $mainPlayer), "Herald");
      case "HVY254-2": return DelimStringContains(CardSubType($CombatChain->AttackCard()->ID()), "Angel");
      case "HVY246": return ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $mainPlayer);
      default: return false;
    }
  }
?>
