<?php

function DYNAbilityCost($cardID)
{
  switch ($cardID) {
    case "DYN001": return 3;
    case "DYN005": return 3;
    case "DYN025": return 3;
    //Warrior
    case "DYN067": return 1;
    case "DYN068": return 3;
    case "DYN069": case "DYN070": return 1;
    case "DYN115": case "DYN116": return 2;
    case "DYN117": return 0;
    case "DYN118": return 0;
    case "DYN151": return 1;
    case "DYN172": return 3;
    case "DYN192": return 2;
    case "DYN240": return 0;
    case "DYN241": return 0;
    case "DYN242": return 1;
    case "DYN243": return 2;
    case "DYN492a": return 0;
    default: return 0;
  }
}

function DYNAbilityType($cardID, $index = -1)
{
  switch ($cardID) {
    case "DYN001": return "A";
    case "DYN005": return "AA";
    //Warrior
    case "DYN067": return "AA";
    case "DYN068": return "AA";
    case "DYN069": case "DYN070": return "AA";
    case "DYN088": return "AA";
    case "DYN025": return "I";
    case "DYN115": case "DYN116": return "AA";
    case "DYN117": return "AR";
    case "DYN118": return "AR";
    case "DYN151": return "A";
    case "DYN171": return "I";
    case "DYN172": return "A";
    case "DYN192": return "A";
    case "DYN240": return "A";
    case "DYN241": return "A";
    case "DYN242": case "DYN243": return "A";
    case "DYN492a": return "AA";
    default: return "";
  }
}
// Natural go again or ability go again. Attacks that gain go again should be in CoreLogic (due to hypothermia)
function DYNHasGoAgain($cardID)
{
  switch ($cardID) {
    case "DYN009": return true;
    case "DYN028": return true;
    //Ninja
    case "DYN049": return true;
    case "DYN050": case "DYN051": case "DYN052": return true;
    case "DYN062": case "DYN063": case "DYN064": return true;
    case "DYN065": return true;
    //Warrior
    case "DYN076": case "DYN077": case "DYN078": return true;
    //Mechanologist
    case "DYN091": return true;
    case "DYN092": return true;
    //Assassin
    case "DYN115": case "DYN116": return true;
    //Ranger
    case "DYN155": return true;
    //Runeblade
    case "DYN188": case "DYN189": case "DYN190": return  true;
    //Illusionist
    case "DYN230": case "DYN231": case "DYN232": return  true;
    default: return false;
  }
}

function DYNAbilityHasGoAgain($cardID)
{
  switch ($cardID) {
    case "DYN151": return true;
    case "DYN192": return true;
    case "DYN240": return true;
    case "DYN243": return true;
  }
}

function DYNEffectAttackModifier($cardID)
{
  global $combatChainState, $CCS_LinkBaseAttack;
  $params = explode(",", $cardID);
  $cardID = $params[0];
  if (count($params) > 1) $parameter = $params[1];
  switch ($cardID) {
    case "DYN007": return 6;
    case "DYN028": return 1;
    case "DYN049": return 1;
    case "DYN053": return 3;
    case "DYN054": return 2;
    case "DYN055": return 1;
    case "DYN076": return NumEquipBlock() > 0 ? 3 : 0;
    case "DYN077": return NumEquipBlock() > 0 ? 2 : 0;
    case "DYN078": return NumEquipBlock() > 0 ? 1 : 0;
    case "DYN089-UNDER": return 1;
    case "DYN091": return 3;
    case "DYN155": return 3;
    default:
      return 0;
  }
}

function DYNCombatEffectActive($cardID, $attackID)
{
  global $combatChainState, $CCS_IsBoosted, $mainPlayer;
  $params = explode(",", $cardID);
  $cardID = $params[0];
  switch ($cardID) {
    case "DYN007": return true;
    case "DYN028": return ClassContains($attackID, "GUARDIAN", $mainPlayer);
    case "DYN049": return $attackID == "DYN065";
    case "DYN053": case "DYN054": case "DYN055": return $attackID == "DYN065";
    case "DYN076": case "DYN077": case "DYN078":
      $subtype = CardSubType($attackID);
      return $subtype == "Sword" || $subtype == "Dagger";
    case "DYN089-UNDER":
      $character = &GetPlayerCharacter($mainPlayer);
      $index = FindCharacterIndex($mainPlayer, "DYN492a"); // Todo: not great for future implementations. Need to check if it's still under
      return $character[$index + 2] >= 1;
    case "DYN091": return $combatChainState[$CCS_IsBoosted];
    case "DYN115": case "DYN116": return true;
    case "DYN155": return CardSubType($attackID) == "Arrow";
    default:
      return false;
  }
}


function DYNCardTalent($cardID)
{
  $number = intval(substr($cardID, 3));
  if ($number <= 0) return "";
  else if ($number >= 1 && $number <= 2) return "ROYAL,DRACONIC";
  else if ($number >= 3 && $number <= 4) return "DRACONIC";
  else if($number == 66) return "LIGHT";
  //   else if($number >= 125 && $number <= 150) return "";
  //   else if($number >= 406 && $number <= 417 ) return "";
  //   else if($number >= 439 && $number <= 441) return "";
  else return "NONE";
}

function DYNCardType($cardID)
{
  switch ($cardID) {
    case "DYN001": return "C";
    case "DYN002": return "A";
    case "DYN003": return "A";
    case "DYN004": return "A";
    //Brute
    case "DYN005": return "W";
    case "DYN007": return "AA";
    case "DYN008": return "AA";
    case "DYN009": return "A";
		case "DYN010": case "DYN011": case "DYN012": return "AA";
    case "DYN025": return "C";
    case "DYN026": return "E";
    //Guardian
    case "DYN028": return "A";
    case "DYN033": case "DYN034": case "DYN035": return "A";
    case "DYN039": case "DYN040": case "DYN041": return "A";
    //Ninja
    case "DYN045": return "E";
    case "DYN047": return "AA";
    case "DYN048": return "A";
    case "DYN049": return "A";
    case "DYN050": case "DYN051": case "DYN052": return "AA";
    case "DYN053": case "DYN054": case "DYN055": return "A";
    case "DYN056": case "DYN057": case "DYN058": return "AA";
    case "DYN062": case "DYN063": case "DYN064": return "A";
    case "DYN065": return "AA";
    //Warrior
    case "DYN066": return "A";
    case "DYN067": return "W";
    case "DYN068": return "W";
    case "DYN069": case "DYN070": return "W";
    case "DYN072": return "I";
    case "DYN076": case "DYN077": case "DYN078": return "A";
    case "DYN079": case "DYN080": case "DYN081": return "AR";
    //Mechanologist
    case "DYN088": return "W";
    case "DYN089": return "E";
    case "DYN090": return "AA";
    case "DYN091": return "A";
    case "DYN092": return "A";
    case "DYN093": return "A";
    case "DYN094": return "A";
    case "DYN098": return "A";
    case "DYN107": case "DYN108": case "DYN109": return "AA";
    case "DYN110": case "DYN111": case "DYN112": return "A";
    case "DYN492a": return "W";
    case "DYN492b": return "E";
    case "DYN492c": return "T";
    //Assassin
    case "DYN113": case "DYN114": return "C";
    case "DYN115": case "DYN116": return "W";
    case "DYN117": return "E";
    case "DYN118": return "E";
    case "DYN119": return "AA";
    case "DYN120": return "AA";
    case "DYN121": return "AA";
    case "DYN122": return "AA";
    case "DYN123": return "A";
    case "DYN124": case "DYN125": case "DYN126": return "AA";
    case "DYN127": case "DYN128": case "DYN129": return "AA";
    case "DYN130": case "DYN131": case "DYN132": return "AR";
    case "DYN133": case "DYN134": case "DYN135": return "AA";
    case "DYN136": case "DYN137": case "DYN138": return "AA";
    case "DYN139": case "DYN140": case "DYN141": return "AA";
    case "DYN142": case "DYN143": case "DYN144": return "AA";
    case "DYN145": case "DYN146": case "DYN147": return "AA";
    case "DYN148": case "DYN149": case "DYN150": return "AR";
    //Ranger
    case "DYN151": return "W";
    case "DYN152": return "E";
    case "DYN153": return "AA";
    case "DYN155": return "A";
    case "DYN162": case "DYN163": case "DYN164": return "AA";
    //Runeblade
    case "DYN171": return "E";
    case "DYN172": return "W";
    case "DYN173": return "AA";
    case "DYN174": return "A";
    case "DYN175": return "A";
		case "DYN179": case "DYN180": case "DYN181": return "A";
    case "DYN188": case "DYN189": case "DYN190": return "A";
    //Wizard
    case "DYN192": return "W";
		case "DYN195": return "A";
    case "DYN196": return "I";
    case "DYN197": case "DYN198": case "DYN199": return "A";
    case "DYN200": case "DYN201": case "DYN202": return "A";
    case "DYN206": case "DYN207": case "DYN208": return "A";
    //Illusionist
    case "DYN217": return "A";
    case "DYN218": return "A";
    case "DYN230": case "DYN231": case "DYN232": return "A";
    case "DYN234": return "E";
    case "DYN240": return "A";
    case "DYN241": return "A";
    case "DYN242": return "A";
    case "DYN243": return "T";
    case "DYN244": return "T";
    default: return "";
  }
}

function DYNCardSubtype($cardID)
{
  switch ($cardID) {
    case "DYN002": return "Ash";
    case "DYN003": return "Ash";
    case "DYN004": return "Ash";
    //Brute
    case "DYN005": return "Rock";
    case "DYN026": return "Off-Hand";
    //Guardian
    case "DYN033": case "DYN034": case "DYN035": return "Aura";
    //Ninja
    case "DYN045": return "Chest";
    case "DYN048": return "Aura";
    case "DYN053": case "DYN054": case "DYN055": return "Aura";
    //Warrior
    case "DYN066": return "Item";
    case "DYN067": return "Sword";
    case "DYN068": return "Axe";
    case "DYN069": case "DYN070": return "Dagger";
    case "DYN072": return "Aura";
    //Mechanologist
    case "DYN088": return "Gun";
    case "DYN089": return "Arms";
    case "DYN092": return "Construct";
    case "DYN093": return "Item";
    case "DYN094": return "Item";
    case "DYN098": return "Aura";
    case "DYN110": case "DYN111": case "DYN112": return "Item";
    case "DYN492b": return "Chest";
    case "DYN492c": return "Item";
    //Assassin
    case "DYN115": case "DYN116": return "Dagger";
    case "DYN117": return "Legs";
    case "DYN118": return "Head";
    //Ranger
    case "DYN151": return "Bow";
    case "DYN152": return "Arms";
    case "DYN153": return "Arrow";
    case "DYN162": case "DYN163": case "DYN164": return "Arrow";
    //Runeblade
    case "DYN171": return "Head";
    case "DYN172": return "Book";
    case "DYN175": return "Aura";
		case "DYN179": case "DYN180": case "DYN181": return "Aura";
    //Wizard
    case "DYN192": return "Staff";
    case "DYN200": case "DYN201": case "DYN202": return "Aura";
    //Illusionist
    case "DYN217": return "Aura";
    case "DYN218": return "Aura";
    //Generic
    case "DYN234": return "Head";
    case "DYN240": return "Item";
    case "DYN241": return "Item";
    case "DYN242": return "Item";
    case "DYN243": return "Item";
    case "DYN244": return "Aura";
    default:return "";
  }
}

function DYNCardCost($cardID)
{
  switch ($cardID) {
    //Brute
    case "DYN007": return 3;
    case "DYN008": return 2;
    case "DYN009": return 1;
		case "DYN010": case "DYN011": case "DYN012": return 3;
    case "DYN028": return 3;
    //Guardian
    case "DYN033": case "DYN034": case "DYN035": return 1;
    case "DYN039": case "DYN040": case "DYN041": return 2;
    //Ninja
    case "DYN050": case "DYN051": case "DYN052": return 1;
    case "DYN053": case "DYN054": case "DYN055": return 1;
    case "DYN072": return 1;
    case "DYN076": case "DYN077": case "DYN078": return 1;
    case "DYN079": case "DYN080": case "DYN081": return 1;
    //Mechanologist
    case "DYN090": return 1;
    case "DYN091": return 0;
    case "DYN092": return 4;
    case "DYN093": return 2;
    case "DYN098": return 1;
    case "DYN107": case "DYN108": case "DYN109": return 0;
    case "DYN110": case "DYN111": case "DYN112": return 1;
    case "DYN119": return 1;
    case "DYN121": return 0;
    case "DYN122": return 2;
    case "DYN123": return 0;
    case "DYN124": case "DYN125": case "DYN126": return 0;
    case "DYN127": case "DYN128": case "DYN129": return 1;
    case "DYN133": case "DYN134": case "DYN135": return 1;
    case "DYN139": case "DYN140": case "DYN141": return 1;
    case "DYN142": case "DYN143": case "DYN144": return 0;
    case "DYN145": case "DYN146": case "DYN147": return 0;
    case "DYN148": case "DYN149": case "DYN150": return 0;
    //Ranger
    case "DYN153": return 1;
    case "DYN155": return 1;
    case "DYN162": case "DYN163": case "DYN164": return 1;
    //Runeblade
    case "DYN173": return 3;
    case "DYN174": return 3;
    case "DYN175": return 3;
		case "DYN179": case "DYN180": case "DYN181": return 1;
    //Wizard
		case "DYN195": return 2;
    case "DYN196": return 3;
    case "DYN197": case "DYN198": case "DYN199": return 1;
    case "DYN200": case "DYN201": case "DYN202": return 1;
    //Illusionist
    case "DYN217": return 1;
    case "DYN218": return 1;
    case "DYN242": return 2;
    default: return 0;
  }
}

function DYNPitchValue($cardID)
{
  switch ($cardID) {
    case "DYN002": return 1;
    case "DYN003": return 1;
    case "DYN004": return 1;
    //Brute
    case "DYN005": return 0;
    case "DYN007": return 1;
    case "DYN008":  case "DYN010": return 1;
    case "DYN009":  case "DYN011": return 2;
    //Guardian
    case "DYN033": case "DYN039": return 1;
    case "DYN034": case "DYN040": return 2;
    //Ninja
    case "DYN047": return 1;
    case "DYN049": return 2;
    case "DYN050": case "DYN053": case "DYN056": case "DYN062": return 1;
    case "DYN051": case "DYN054": case "DYN057": case "DYN063": return 2;
    case "DYN065": return 0;
    //Warrior
    case "DYN067": return 0;
    case "DYN066": return 2;
    case "DYN069": case "DYN070": return 0;
    case "DYN072": return 1;
    case "DYN076": case "DYN079": return 1;
    case "DYN077": case "DYN080": return 2;
    case "DYN078": case "DYN081": return 3;
    //Mechanologist
    case "DYN089": return 0;
    case "DYN090": return 1;
    case "DYN091": return 1;
    case "DYN092": return 2;
    case "DYN093": return 1;
    case "DYN098": return 1;
    case "DYN107": case "DYN110": return 1;
    case "DYN108": case "DYN111": return 2;
    case "DYN492a": return 0;
    case "DYN492b": return 0;
    case "DYN492c": return 0;
    //Assassin
    case "DYN113": case "DYN114": return 0;
    case "DYN115": case "DYN116": return 0;
    case "DYN200": return 1;
    case "DYN201": return 2;
    case "DYN117": return 0;
    case "DYN118": return 0;
    case "DYN119": return 2;
    case "DYN120": return 1;
    case "DYN124": case "DYN127": case "DYN130": case "DYN133": case "DYN136": case "DYN139": case "DYN142": case "DYN145": case "DYN148": return 1;
    case "DYN125": case "DYN128": case "DYN131": case "DYN134": case "DYN137": case "DYN140": case "DYN143": case "DYN146": case "DYN149": return 2;
    //Ranger
    case "DYN151": return 0;
    case "DYN153": return 1;
    case "DYN155": return 2;
    case "DYN162": return 1;
    case "DYN163": return 2;
    //Runeblade
    case "DYN173": return 2;
    case "DYN174": return 1;
    case "DYN175": return 3;
    case "DYN179": case "DYN188": case "DYN230": return 1;
    case "DYN180": case "DYN189": case "DYN231": return 2;
    //Wizard
    case "DYN195": return 1;
    case "DYN197": case "DYN206": return 1;
    case "DYN198": case "DYN207": return 2;
    //Illusionist
    case "DYN218": return 1;
    case "DYN234": return 0;
    case "DYN240": return 1;
    case "DYN241": return 1;
    case "DYN242": return 1;
    case "DYN243": return 0;
    case "DYN244": return 0;
    default: return 3;
  }
}

function DYNBlockValue($cardID)
{
  switch ($cardID) {
    case "DYN001": return -1;
    case "DYN005": return -1;
    case "DYN025": return -1;
    case "DYN026": return 3;
    case "DYN045": return 1;
    //Ninja
    case "DYN050": case "DYN051": case "DYN052": return 2;
    case "DYN062": case "DYN063": case "DYN064": return 2;
    case "DYN065": return -1;
    //Warrior
    case "DYN066": return -1;
    case "DYN067": return -1;
    case "DYN068": return -1;
    case "DYN069": case "DYN070": return -1;
    case "DYN072": return -1;
    //Mechanologist
    case "DYN088": return -1;
    case "DYN089": return 1;
    case "DYN093": return -1;
    case "DYN094": return -1;
    case "DYN110": case "DYN111": case "DYN112": return -1;
    case "DYN492a": return -1;    case "DYN244": return "T";
    case "DYN492b": return 5;
    case "DYN492c": return -1;
    //Assassin
    case "DYN113": case "DYN114": return -1;
    case "DYN115": case "DYN116": return -1;
    case "DYN117": return 1;
    case "DYN118": return 1;
    case "DYN200": case "DYN201": case "DYN202": return 2;
    //Ranger
    case "DYN151": return -1;
    case "DYN152": return 1;
    //Runeblade
    case "DYN171": return 1;
    case "DYN172": return -1;
    case "DYN174": return 2;
    case "DYN175": return 3;
		case "DYN179": case "DYN180": case "DYN181": return 2;
    case "DYN188": case "DYN189": case "DYN190": return 2;
    //Wizard
    case "DYN192": return -1;
    case "DYN196": return -1;
    //Illusionist
    case "DYN217": return 2;
    case "DYN218": return 2;
    case "DYN230": case "DYN231": case "DYN232": return 2;
    //Generic
    case "DYN234": return -1;
    case "DYN240": return -1;
    case "DYN241": return -1;
    case "DYN242": case "DYN243": case "DYN244": return -1;
    default: return 3;
  }
}

function DYNAttackValue($cardID)
{
  switch ($cardID) {
    //Brute
    case "DYN005": case "DYN010": return 7;
    case "DYN007": case "DYN008": return 6;
    case "DYN011": return 6;
    case "DYN012": return 5;
    //Ninja
    case "DYN050": return 4;
    case "DYN051": case "DYN056": case "DYN068": return 3;
    case "DYN047": case "DYN052": case "DYN057": return 2;
    case "DYN058": return 1;
    //Warrior
    case "DYN067": return 3;
    case "DYN069": case "DYN070": return 1;
    case "DYN079": return 3 + (NumEquipBlock() > 0 ? 1 : 0);
    case "DYN080": return 2 + (NumEquipBlock() > 0 ? 1 : 0);
    case "DYN081": return 1 + (NumEquipBlock() > 0 ? 1 : 0);
    //Mechanologist
    case "DYN088": return 5;
    case "DYN090": return 4;
    case "DYN107": return 4;
    case "DYN108": return 3;
    case "DYN109": return 2;
    case "DYN115": case "DYN116": return 1;
    case "DYN120": return 4;
    case "DYN121": return 3;
    case "DYN122": return 4;
    //Assassin
    case "DYN127": case "DYN133": case "DYN139": return 5;
    case "DYN119": case "DYN124": case "DYN128": case "DYN134": case "DYN136": case "DYN140": case "DYN142": case "DYN145": return 4;
    case "DYN125": case "DYN129": case "DYN135": case "DYN137": case "DYN141": case "DYN143": case "DYN146": return 3;
    case "DYN126": case "DYN144": case "DYN147": case "DYN138": return 2;
    //Ranger
    case "DYN153": return 5;
    case "DYN162": return 5;
    case "DYN163": return 4;
    case "DYN164": return 3;
    //Runeblade
    case "DYN173": return 6;
    case "DYN492a": return 5;
    default: return 0;
  }
}

function DYNPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
  global $currentPlayer, $CS_PlayIndex, $CS_NumContractsCompleted, $combatChainState, $CCS_NumBoosted;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  switch ($cardID) {
    case "DYN001":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKCARD,ARC159");
      AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("ATTACKWITHIT", $currentPlayer, "-", 1);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      return "";
    case "DYN007":
      if (AttackValue($additionalCosts) >= 6) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      return "";
    case "DYN009":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN002":
      PutPermanentIntoPlay($currentPlayer, $cardID);
      return "";
    case "DYN003":
      PutPermanentIntoPlay($currentPlayer, $cardID);
      return "";
    case "DYN004":
      PutPermanentIntoPlay($currentPlayer, $cardID);
      return "";
    case "DYN025":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN028":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN039":
    case "DYN040":
    case "DYN041":
      if ($cardID == "DYN039") $maxDef = 3;
      else if ($cardID == "DYN040") $maxDef = 2;
      else $maxDef = 1;
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=E;subtype=Off-Hand;hasNegCounters=true;maxDef=" . $maxDef . ";class=GUARDIAN");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which Guardian Off-Hand to remove a -1 defense counter");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZGETCARDINDEX", $currentPlayer, "-", 1);
      AddDecisionQueue("REMOVENEGDEFCOUNTER", $currentPlayer, "-", 1);
      return "Remove a -1 counter from a Guardian Off-hand with " . $maxDef . " or less base defense.";
    case "DYN049":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddPlayerHand("DYN065", $currentPlayer, "-");
      return "Create a " . CardLink("DYN065","DYN065") . " in your hand and give them +1 this turn.";
    case "DYN062": case "DYN063": case "DYN064":
      if ($cardID == "DYN062") $amount = 3;
      else if ($cardID == "DYN063") $amount = 2;
      else $amount = 1;
      for ($i=0; $i < $amount; $i++) {
        BanishCardForPlayer("DYN065", $currentPlayer, "-", "TT", $currentPlayer);
      }
      return "Create " . $amount . " Crouching Tigers.";
    case "DYN072":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=W;subtype=Sword");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which Sword gain a +1 counter");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZGETCARDINDEX", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDEQUIPCOUNTER", $currentPlayer, "-", 1);
      return "Add a +1 counter from a sword you control.";
    case "DYN076": case "DYN077": case "DYN078":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Your next sword or dagger attack gains go again and piercing.";
    case "DYN090":
      if(IsHeroAttackTarget())
      {
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $combatChainState[$CCS_NumBoosted]);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
        AddDecisionQueue("PREPENDLASTRESULT", $otherPlayer, "{0}-", 1);
        AddDecisionQueue("APPENDLASTRESULT", $otherPlayer, "-{0}", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose {0} card(s)", 1);
        AddDecisionQueue("MULTICHOOSEHAND", $otherPlayer, "<-", 1);
        AddDecisionQueue("IMPLODELASTRESULT", $otherPlayer, ",", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card", 1);
        AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("HANDCARD", $otherPlayer, "-");
        AddDecisionQueue("BLOCKVALUE", $currentPlayer, "-");
        AddDecisionQueue("GREATERTHANPASS", $currentPlayer, $combatChainState[$CCS_NumBoosted], 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{0}", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
        AddDecisionQueue("ADDCARDTOCHAIN", $otherPlayer, "HAND", 1);
      }
      return "";
    case "DYN091":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "The next card you boost gets +3 attack and if you banish an item you play it.";
    case "DYN092":
      $hasHead = false; $hasChest = false; $hasArms = false; $hasLegs = false; $hasWeapon = false; $numHypers = 0;
      $char = &GetPlayerCharacter($currentPlayer);
      for($i=0; $i<count($char); $i+=CharacterPieces())
      {
        if($char[$i+1] == 0) continue;//If it's destroyed
        if(!ClassContains($char[$i], "MECHANOLOGIST", $currentPlayer)) continue;
        if(CardType($char[$i]) == "W") $hasWeapon = true;
        else {
          $subtype = CardSubType($char[$i]);
          switch($subtype)
          {
            case "Head": $hasHead = true; break;
            case "Chest": $hasChest = true; break;
            case "Arms": $hasArms = true; break;
            case "Legs": $hasLegs = true; break;
          }
        }
      }
      if(!$hasHead || !$hasChest || !$hasArms || !$hasLegs || !$hasWeapon) return "You do not meet the equipment requirement.";
      $numHypers = CountItem("ARC036", $currentPlayer);
      $numHypers += CountItem("DYN111", $currentPlayer);
      $numHypers += CountItem("DYN112", $currentPlayer);
      if($numHypers < 3) return "You do not meet the Hyper Driver requirement.";
      //Congrats, you have met the requirement to summon the mech! Let's remove the old stuff
      for($i=count($char)-1; $i>=CharacterPieces(); --$i) {
        if ($char[$i] == "DYN089") AddCurrentTurnEffect($char[$i] . "-UNDER", $currentPlayer);
        unset($char[$i]);
      }
      $char = array_values($char);
      $items = &GetItems($currentPlayer);
      for($i=count($items)-ItemPieces(); $i>=0; $i-=ItemPieces())
      {
        if($items[$i] == "ARC036" || $items[$i] == "DYN111" || $items[$i] == "DYN112") DestroyItemForPlayer($currentPlayer, $i);
      }
      //Now add the new stuff
      PutCharacterIntoPlayForPlayer("DYN492a", $currentPlayer);//Weapon
      PutCharacterIntoPlayForPlayer("DYN492b", $currentPlayer);//Armor
      PutItemIntoPlayForPlayer("DYN492c", $currentPlayer);//Item
      return "";
    case "DYN123":
      if (GetClassState($currentPlayer, $CS_NumContractsCompleted) > 0) {
        PutItemIntoPlayForPlayer("EVR195", $currentPlayer, 0, 4);
      }
      return "";
    case "DYN130":
    case "DYN131":
    case "DYN132":
      if ($cardID == "DYN130") $amount = 4;
      else if ($cardID == "DYN131") $amount = 3;
      else $amount = 2;
      $options = GetChainLinkCards(($currentPlayer == 1 ? 2 : 1), "", "C");
      if (!empty($options)) {
        AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, $options);
        AddDecisionQueue("COMBATCHAINDEBUFFDEFENSE", $currentPlayer, $amount, 1);
      }
      return "Reduce the defense of target defending card by " . $amount;
    case "DYN148": case "DYN149": case "DYN150":
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      AddDecisionQueue("DECKCARDS", $otherPlayer, "0", 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want sink <0> with Cut to the Chase", 1);
      AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_sink_the_opponent's_card", 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("FINDINDICES", $otherPlayer, "TOPDECK", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $otherPlayer, "<-", 1);
      AddDecisionQueue("ADDBOTTOMMYDECK", $otherPlayer, "-", 1);
      return "";
    case "DYN151":
      $deck = &GetDeck($currentPlayer);
      AddDecisionQueue("DECKCARDS", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      if(ArsenalFull($currentPlayer)) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Sandscour Greatbow shows you the top of your deck: <0>");
        AddDecisionQueue("OK", $currentPlayer, "whether to put an arrow in arsenal", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "NO");
        return "Your arsenal is full, so you cannot put an arrow in your arsenal.";
      }
      if (CardSubType($deck[0]) != "Arrow") {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Sandscour Greatbow shows you the top of your deck: <0>");
        AddDecisionQueue("OK", $currentPlayer, "whether to put an arrow in arsenal", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "NO");
      } else {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to put <0> in your arsenal", 1);
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_put_the_card_in_arsenal", 1);
      }
      AddDecisionQueue("SANDSCOURGREATBOW", $currentPlayer, "-");
      return "";
    case "DYN155":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives your next Arrow attack action card +" . EffectAttackModifier($cardID);
    case "DYN171":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return CardLink("ARC112", "ARC112") . "s you control have spellvoid 1 this turn.";
    case "DYN172":
      $pitchArr = explode(",", $additionalCosts);
      $attackActionPitched = 0;
      $naaPitched = 0;
      for ($i = 0; $i < count($pitchArr); ++$i) {
        if (CardType($pitchArr[$i]) == "A") $naaPitched = 1;
        if (CardType($pitchArr[$i]) == "AA") $attackActionPitched = 1;
      }
      $rv = "Draw a card";
      MyDrawCard();
      if ($naaPitched && $attackActionPitched) {
        PlayAura("ARC112", $currentPlayer);
        $rv .= " and creates a Runechant token";
      }
      return $rv . ".";
    case "DYN173":
      $pitchArr = explode(",", $additionalCosts);
      $attackActionPitched = 0;
      $naaPitched = 0;
      for ($i = 0; $i < count($pitchArr); ++$i) {
        if (CardType($pitchArr[$i]) == "A") $naaPitched = 1;
        if (CardType($pitchArr[$i]) == "AA") $attackActionPitched = 1;
      }
      if ($naaPitched && $attackActionPitched && IsHeroAttackTarget()) {
        AddCurrentTurnEffect($cardID, $currentPlayer, $from);
        return "On-damage the defending hero discard a card and you draw a card";
      }
      return "";
    case "DYN174":
      $pitchArr = explode(",", $additionalCosts);
      $attackActionPitched = 0;
      $naaPitched = 0;
      for ($i = 0; $i < count($pitchArr); ++$i) {
        if (CardType($pitchArr[$i]) == "A") $naaPitched = 1;
        if (CardType($pitchArr[$i]) == "AA") $attackActionPitched = 1;
      }
      $rv = "";
      if ($attackActionPitched) {
        // Opponent
        AddDecisionQueue("FINDINDICES", $otherPlayer, "SEARCHMZ,MYALLY");
        AddDecisionQueue("CHOOSEMULTIZONE", $otherPlayer, "<-");
        AddDecisionQueue("MZDESTROY", $otherPlayer, "-", 1);
        // Player
        AddDecisionQueue("FINDINDICES", $currentPlayer, "SEARCHMZ,MYALLY");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-");
        AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
        $rv .= "each hero chose and destroyed an ally they control.";
      }
      if ($naaPitched) {
        // Opponent
        AddDecisionQueue("FINDINDICES", $otherPlayer, "SEARCHMZ,MYAURAS");
        AddDecisionQueue("CHOOSEMULTIZONE", $otherPlayer, "<-");
        AddDecisionQueue("MZDESTROY", $otherPlayer, "-", 1);
        // Player
        AddDecisionQueue("FINDINDICES", $currentPlayer, "SEARCHMZ,MYAURAS");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-");
        AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
        $rv .= "each hero chose and destroyed an aura they control.";
      }
      return $rv;
    case "DYN175":
      $numRunechants = DestroyAllThisAura($currentPlayer, "ARC112");
      $auras = &GetAuras($currentPlayer);
      $index = count($auras) - AuraPieces();//Get index of last played aura i.e. this
      $auras[$index+2] = $numRunechants;
      return "";
    case "DYN188":
    case "DYN189":
    case "DYN190":
      if (CanRevealCards($currentPlayer)) {
        $deck = GetDeck($currentPlayer);
        if (count($deck) == 0) return "Your deck is empty. Nothing was revealed.";
        if (PitchValue($deck[0]) == PitchValue($cardID)) {
          PlayAura("ARC112", $currentPlayer, 1, true);
          return "Reveals " . CardLink($deck[0], $deck[0]) . " and creates a " . CardLink("ARC112", "ARC112");
        } else {
          return "Reveals " . CardLink($deck[0], $deck[0]);
        }
      }
      return "Reveal has been prevented.";
    case "DYN192":
      DealArcane(1, 1, "ABILITY", $cardID, resolvedTarget: $target);
      AddDecisionQueue("SURGENTAETHERTIDE", $currentPlayer, "-");
      return "";
    case "DYN195":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "DYN196":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN197": case "DYN198": case "DYN199":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "DYN206": case "DYN207": case "DYN208":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "DYN230": case "DYN231": case "DYN232":
      if (CanRevealCards($currentPlayer)) {
        $deck = GetDeck($currentPlayer);
        if (count($deck) == 0) return "Your deck is empty. Nothing was revealed.";
        if (PitchValue($deck[0]) == PitchValue($cardID)) {
          PlayAura("MON104", $currentPlayer, 1, true);
          return "Reveals " . CardLink($deck[0], $deck[0]) . " and creates a " . CardLink("MON104", "MON104");
        } else {
          return "Reveals " . CardLink($deck[0], $deck[0]);
        }
      }
      return "Reveal has been prevented.";
    case "DYN240":
      $rv = "";
      if ($from == "PLAY") {
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
        $rv = "Imperial Edict is a partially manual card. Name the card in chat and enforce play restriction.";
        if(IsRoyal($currentPlayer))
        {
          $rv .= " Imperial Edict revealed the opponent's hand.";
          $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
          AddDecisionQueue("REVEALHANDCARDS", $otherPlayer, "<-", 1);
        }
      }
      return $rv;
    case "DYN241":
      $rv = "";
      if ($from == "PLAY") {
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
        $item = (IsRoyal($currentPlayer) ? "DYN243": "CRU197");
        PutItemIntoPlayForPlayer($item, $currentPlayer);
        $rv = "Imperial Ledger shuffled itself and created a " . CardLink($item, $item) . ".";
        $deck = &GetDeck($currentPlayer);
        array_push($deck, "DYN241");
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
      }
      return $rv;
    case "DYN242":
      $rv = "";
      if ($from == "PLAY") {
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose any number of heroes");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Target_Opponent,Target_Both_Heroes,Target_Yourself,Target_No_Heroes");
        AddDecisionQueue("IMPERIALWARHORN", $currentPlayer, "<-", 1);
      }
      return $rv;
    case "DYN243":
      $rv = "";
      if ($from == "PLAY") {
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
        $rv = "Draws a card.";
        Draw($currentPlayer);
      }
      return $rv;
    default:
      return "";
  }
}

function DYNHitEffect($cardID)
{
  global $mainPlayer, $defPlayer, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $CCS_DamageDealt, $CCS_NumBoosted;
  global $chainLinks, $combatChain;
  switch ($cardID) {
    case "DYN047":
      if (ComboActive())
      {
        $numLinks = 0;
        for ($i = 0; $i < count($chainLinks); ++$i) {
          if ($chainLinks[$i][0] == "DYN065") ++$numLinks;
        }
        if (count($combatChain) > 0 && $combatChain[0] == "DYN065") ++$numLinks;
        for ($i=0; $i < $numLinks; $i++) {
          BanishCardForPlayer("DYN065", $mainPlayer, "-", "TT", $mainPlayer);
        }
      }
      break;
    case "DYN050": case "DYN051": case "DYN052":
      BanishCardForPlayer("DYN065", $mainPlayer, "-", "TT", $mainPlayer);
      break;
    case "DYN067":
      if (IsHeroAttackTarget() && !SearchAuras("MON104", $mainPlayer)) { //MON104 to be changed to Spellbane Aegis on release
        PlayAura("MON104", $mainPlayer); //MON104 to be changed to Spellbane Aegis on release
      }
      break;
    case "DYN107": case "DYN108": case "DYN109":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYHAND:subtype=Item;class=MECHANOLOGIST;maxCost=" . $combatChainState[$CCS_NumBoosted]);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an item to put into play");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("SETDQVAR", $mainPlayer, "0", 1);
      AddDecisionQueue("MZGETCARDID", $mainPlayer, "-", 1);
      AddDecisionQueue("PUTPLAY", $mainPlayer, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $mainPlayer, "{0}", 1);
      AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      break;
    case "DYN115":
      if (IsHeroAttackTarget()) {
        AddCurrentTurnEffectFromCombat("DYN115", $defPlayer);
      }
      break;
    case "DYN116":
      if (IsHeroAttackTarget()) {
        AddCurrentTurnEffectFromCombat("DYN115", $defPlayer);
      }
      break;
    case "DYN117":
      if (IsHeroAttackTarget()) {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        WriteLog(CardLink($cardID, $cardID) . " gives the current Assassin attack go again.");
      }
      break;
    case "DYN118":
      if (IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) WriteLog("The opponent is already... depleted.");
        $cardToBanish = array_shift($deck);
        BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
      }
      break;
    case "DYN119":
      if (IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) WriteLog("The opponent is already... depleted.");
        for ($i = 0; $i < $combatChainState[$CCS_DamageDealt]; ++$i) {
          if (count($deck) == 0) break;
          $cardToBanish = array_shift($deck);
          BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
        }
      }
      break;
    case "DYN121":
      if (IsHeroAttackTarget() && IsRoyal($defPlayer)) {
        PlayerLoseHealth($defPlayer, GetHealth($defPlayer));
      }
      break;
    case "DYN120":
      if (IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) WriteLog("The opponent is already... depleted.");
        $cardToBanish = array_shift($deck);
        BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
        AddDecisionQueue("FINDINDICES", $mainPlayer, "SEARCHMZ,THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to banish", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $mainPlayer, "ARS,-," . $mainPlayer, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "DYN122":
      if (IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) WriteLog("The opponent is already... depleted.");
        $cardToBanish = array_shift($deck);
        BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
        AddDecisionQueue("FINDINDICES", $mainPlayer, "SEARCHMZ,THEIRHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to banish", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $mainPlayer, "HAND,-," . $mainPlayer, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "DYN124": case "DYN125": case "DYN126":
    case "DYN127": case "DYN128": case "DYN129":
    case "DYN133": case "DYN134": case "DYN135":
    case "DYN136": case "DYN137": case "DYN138":
    case "DYN139": case "DYN140": case "DYN141":
    case "DYN142": case "DYN143": case "DYN144":
    case "DYN145": case "DYN146": case "DYN147":
      if (IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) WriteLog("The opponent is already... depleted.");
        $cardToBanish = array_shift($deck);
        BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
      }
      break;
    case "DYN153":
      AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
      break;
    case "DYN162": case "DYN163": case "DYN164":
      if (SearchCurrentTurnEffects($cardID . "-AIM", $mainPlayer, true) && IsHeroAttackTarget()) {
        AddDecisionQueue("FINDINDICES", $mainPlayer, "SEARCHMZ,THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to Destroy", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZDISCARD", $mainPlayer, "ARS,-," . $mainPlayer, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    default: break;
  }
}

function IsRoyal($player)
{
  $mainCharacter = &GetPlayerCharacter($player);

  if (SearchCharacterForCard($player, "DYN234")) return true;

  switch ($mainCharacter[0]) {
    case "DYN001": return true;
    default: break;
  }
  return false;
}

function HasSurge($cardID)
{
  switch ($cardID) {
		case "DYN195": return true;
    case "DYN197": case "DYN198": case "DYN199": return true;
    case "DYN206": case "DYN207": case "DYN208": return true;
    default: return false;
  }
}

function HasEphemeral($cardID)
{
  switch ($cardID) {
    case "DYN065": return true;
    default: return false;
  }
}

function ContractType($cardID)
{
  switch($cardID)
  {
    case "DYN119": return "YELLOWPITCH";
    case "DYN120": return "REDPITCH";
    case "DYN122": return "BLUEPITCH";
    case "DYN124": case "DYN125": case "DYN126": return "COST1ORLESS";
    case "DYN127": case "DYN128": case "DYN129": return "COST2ORMORE";
    case "DYN133": case "DYN134": case "DYN135": return "AA";
    case "DYN136": case "DYN137": case "DYN138": return "BLOCK2ORLESS";
    case "DYN139": case "DYN140": case "DYN141": return "REACTIONS";
    case "DYN142": case "DYN143": case "DYN144": return "GOAGAIN";
    case "DYN145": case "DYN146": case "DYN147": return "NAA";
    default: return "";
  }
}

function ContractCompleted($player, $cardID)
{
  global $CS_NumContractsCompleted;
  WriteLog("Player " . $player . " completed the contract for " . CardLink($cardID, $cardID) . ".");
  IncrementClassState($player, $CS_NumContractsCompleted);
  switch($cardID)
  {
    case "DYN119": case "DYN120": case "DYN122":
    case "DYN124": case "DYN125": case "DYN126":
    case "DYN127": case "DYN128": case "DYN129":
    case "DYN133": case "DYN134": case "DYN135":
    case "DYN136": case "DYN137": case "DYN137":
    case "DYN139": case "DYN140": case "DYN141":
    case "DYN142": case "DYN143": case "DYN144":
    case "DYN145": case "DYN146": case "DYN147":
      PutItemIntoPlayForPlayer("EVR195", $player);
      break;
    default: break;
  }
}

function CheckContracts($banishedBy, $cardBanished)
{
  global $combatChain, $chainLinks;
  //Current Chainlink
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    if ($combatChain[$i + 1] != $banishedBy) continue;
    $contractType = ContractType($combatChain[$i]);
    $contractCompleted = false;
    switch ($contractType) {
      case "REDPITCH":
        if (PitchValue($cardBanished) == 1) $contractCompleted = true;
        break;
      case "YELLOWPITCH":
        if (PitchValue($cardBanished) == 2) $contractCompleted = true;
        break;
      case "BLUEPITCH":
        if (PitchValue($cardBanished) == 3) $contractCompleted = true;
        break;
      case "COST1ORLESS":
        if (CardCost($cardBanished) <= 1) $contractCompleted = true;
        break;
      case "COST2ORMORE":
        if (CardCost($cardBanished) >= 2) $contractCompleted = true;
        break;
      case "AA":
        if (CardType($cardBanished) == "AA") $contractCompleted = true;
        break;
      case "GOAGAIN":
        if (HasGoAgain($cardBanished)) $contractCompleted = true;
        break;
      case "NAA":
        if (CardType($cardBanished) == "A") $contractCompleted = true;
        break;
      case "BLOCK2ORLESS":
        if (BlockValue($cardBanished) <= 2) $contractCompleted = true;
        break;
      case "REACTIONS":
        if (CardType($cardBanished) == "AR" || CardType($cardBanished) == "DR") $contractCompleted = true;
        break;
      default:
        break;
    }
    if ($contractCompleted) ContractCompleted($banishedBy, $combatChain[$i]);
  }
  //Chain Links
  for ($i = 0; $i < count($chainLinks); ++$i) {
    for ($j = 0; $j < count($chainLinks[$i]); $j += ChainLinksPieces()) {
      if ($chainLinks[$i][$j + 1] != $banishedBy) continue;
      if ($chainLinks[$i][$j + 2] == 0) continue; //Skip if the card isn't on the chain anymore
      $contractType = ContractType($chainLinks[$i][$j]);
      $contractCompleted = false;
      switch ($contractType) {
        case "REDPITCH":
          if (PitchValue($cardBanished) == 1) $contractCompleted = true;
          break;
        case "YELLOWPITCH":
          if (PitchValue($cardBanished) == 2) $contractCompleted = true;
          break;
        case "BLUEPITCH":
          if (PitchValue($cardBanished) == 3) $contractCompleted = true;
          break;
        case "COST1ORLESS":
          if (CardCost($cardBanished) <= 1) $contractCompleted = true;
          break;
        case "COST2ORMORE":
          if (CardCost($cardBanished) >= 2) $contractCompleted = true;
          break;
        case "AA":
          if (CardType($cardBanished) == "AA") $contractCompleted = true;
          break;
        case "GOAGAIN":
          if (HasGoAgain($cardBanished)) $contractCompleted = true;
          break;
        case "NAA":
          if (CardType($cardBanished) == "A") $contractCompleted = true;
          break;
        case "BLOCK2ORLESS":
          if (BlockValue($cardBanished) <= 2) $contractCompleted = true;
          break;
        case "REACTIONS":
          if (CardType($cardBanished) == "AR" || CardType($cardBanished) == "DR") $contractCompleted = true;
          break;
        default:
          break;
        }
      if ($contractCompleted) ContractCompleted($banishedBy, $chainLinks[$i][$j]);
      }
    }
}
