<?php

//0 - Card ID
//1 - Status (2=ready, 1=unavailable, 0=destroyed)
//2 - Num counters
//3 - Num attack counters
//4 - Num defense counters
//5 - Num uses
//6 - On chain (1 = yes, 0 = no)
//7 - Flagged for destruction (1 = yes, 0 = no)
//8 - Frozen (1 = yes, 0 = no)
//9 - Is Active (2 = always active, 1 = yes, 0 = no)
//10 - Subcards , delimited
//11 - Unique ID
//12 - Face Up/Down
class Character
{
  // property declaration
  public $cardID = "";
  public $status = 2;
  public $numCounters = 0;
  public $numAttackCounters = 0;
  public $numDefenseCounters = 0;
  public $numUses = 0;
  public $onChain = 0;
  public $flaggedForDestruction = 0;
  public $frozen = 0;
  public $isActive = 2;
  public $subCards = "";
  public $uniqueID = 0;
  public $facing = "UP";


  private $player = null;
  private $arrIndex = -1;

  public function __construct($player, $index)
  {
    $this->player = $player;
    $this->arrIndex = $index;
    $array = &GetPlayerCharacter($player);

    $this->cardID = $array[$index];
    $this->status = $array[$index+1];
    $this->numCounters = $array[$index+2];
    $this->numAttackCounters = $array[$index+3];
    $this->numDefenseCounters = $array[$index+4];
    $this->numUses = $array[$index+5];
    $this->onChain = $array[$index+6];
    $this->flaggedForDestruction = $array[$index+7];
    $this->frozen = $array[$index+8];
    $this->isActive = $array[$index+9];
    $this->subCards = $array[$index+10];
    $this->uniqueID = $array[$index+11];
    $this->facing = $array[$index+12];
  }

  public function Finished()
  {
    $array = &GetPlayerCharacter($this->player);
    $array[$this->arrIndex] = $this->cardID;
    $array[$this->arrIndex+1] = $this->status;
    $array[$this->arrIndex+2] = $this->numCounters;
    $array[$this->arrIndex+3] = $this->numAttackCounters;
    $array[$this->arrIndex+4] = $this->numDefenseCounters;
    $array[$this->arrIndex+5] = $this->numUses;
    $array[$this->arrIndex+6] = $this->onChain;
    $array[$this->arrIndex+7] = $this->flaggedForDestruction;
    $array[$this->arrIndex+8] = $this->frozen;
    $array[$this->arrIndex+9] = $this->isActive;
    $array[$this->arrIndex+10] = $this->subCards;
    $array[$this->arrIndex+11] = $this->uniqueID;
    $array[$this->arrIndex+12] = $this->facing;
  }
}

function PutCharacterIntoPlayForPlayer($cardID, $player)
{
  $char = &GetPlayerCharacter($player);
  $index = count($char);
  array_push($char, $cardID);
  array_push($char, 2);
  array_push($char, CharacterCounters($cardID));
  array_push($char, 0);
  array_push($char, 0);
  array_push($char, 1);
  array_push($char, 0);
  array_push($char, 0);
  array_push($char, 0);
  array_push($char, CharacterDefaultActiveState($cardID));
  array_push($char, "-");
  array_push($char, GetUniqueId($cardID, $player));
  array_push($char, HasCloaked($cardID, $player));
  return $index;
}

function CharacterCounters ($cardID)
{
  switch($cardID) {
    case "DYN492a": return 8;
    default: return 0;
  }
}

//CR 2.1 6.4.10f If an effect states that a prevention effect can not prevent the damage of an event, the prevention effect still applies to the event but its prevention amount is not reduced
function CharacterTakeDamageAbility($player, $index, $damage, $preventable) {
  $char = &GetPlayerCharacter($player);
  $type = "-";
  $remove = false;
  if($damage > 0 && HasWard($char[$index], $player)) {
    if($preventable) $damage -= WardAmount($char[$index], $player);
    $remove = true;
  }
  switch($char[$index]) {
    default: break;
  }
  if($remove) DestroyCharacter($player, $index);
  if($damage <= 0) $damage = 0;
  return $damage;
}

function CharacterStartTurnAbility($index)
{
  global $mainPlayer;
  $otherPlayer = $mainPlayer == 1 ? 2 : 1;
  $char = new Character($mainPlayer, $index);
  $character = GetPlayerCharacter($mainPlayer);
  if($char->status == 0 && !CharacterTriggerInGraveyard($char->cardID)) return;
  if($char->status == 1 || $char->status == 3) return;
  $cardID = $char->cardID;
  if($index == 0) $cardID = ShiyanaCharacter($cardID);
  switch($cardID) {
    case "WTR150":
      if($char->numCounters < 3) ++$char->numCounters;
      $char->Finished();
      break;
    case "CRU097":
      AddLayer("TRIGGER", $mainPlayer, $char->cardID);
      break;
    case "MON187":
      if(GetHealth($mainPlayer) <= 13) {
        $char->status = 0;
        BanishCardForPlayer($char->cardID, $mainPlayer, "EQUIP", "NA");
        WriteLog(CardLink($char->cardID, $char->cardID) . " got banished for having 13 or less life");
        $char->Finished();
      }
      break;
    case "EVR017":
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "You may reveal an Earth, Ice, and Lightning card for Bravo, Star of the Show");
      AddDecisionQueue("FINDINDICES", $mainPlayer, "BRAVOSTARSHOW");
      AddDecisionQueue("MULTICHOOSEHAND", $mainPlayer, "<-", 1);
      AddDecisionQueue("BRAVOSTARSHOW", $mainPlayer, "-", 1);
      break;
    case "EVR019":
      if(CountAura("WTR075", $mainPlayer) >= 3) {
        WriteLog(CardLink($char->cardID, $char->cardID) . " gives Crush attacks Dominate this turn");
        AddCurrentTurnEffect("EVR019", $mainPlayer);
      }
      break;
    case "DYN117": case "DYN118": case "OUT011": case "EVO235":
      $discardIndex = SearchDiscardForCard($mainPlayer, $char->cardID);
      if(CountItem("EVR195", $mainPlayer) >= 2 && $discardIndex != "") {
        AddDecisionQueue("COUNTITEM", $mainPlayer, "EVR195");
        AddDecisionQueue("LESSTHANPASS", $mainPlayer, "2");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Do you want to pay 2 silver to equip " . CardLink($char->cardID, $char->cardID) . "?", 1);
        AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_pay_and_equip_" . CardLink($char->cardID, $char->cardID), 1);
        AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, "EVR195-2", 1);
        AddDecisionQueue("FINDANDDESTROYITEM", $mainPlayer, "<-", 1);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, "MYCHAR-" . $index, 1);
        AddDecisionQueue("MZUNDESTROY", $mainPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, "MYDISCARD-" . $discardIndex, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "DTD564":
      if($character[1] < 3) {
        AddCurrentTurnEffect("DTD564", $mainPlayer);
      }
      break;
    case "DTD133": case "DTD134":
      if($character[1] < 3) {
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card to banish for Vynnset");
        MZMoveCard($mainPlayer, "MYHAND", "MYBANISH,HAND,-");
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, "ARC112", 1);
        AddDecisionQueue("PUTPLAY", $mainPlayer, "-", 1);
      }
      break;
    case "ROGUE015":
      $hand = &GetHand($mainPlayer);
      array_unshift($hand, "DYN065");
      break;
    case "ROGUE017":
      $hand = &GetHand($mainPlayer);
      array_unshift($hand, "CRU181");
      Draw($mainPlayer);
      break;
    case "ROGUE018":
      AddCurrentTurnEffect("ROGUE018", $mainPlayer);
      break;
    case "ROGUE010":
      PlayAura("ARC112", $mainPlayer);
      PlayAura("ARC112", $mainPlayer);
      break;
    case "ROGUE021":
      $hand = &GetHand($mainPlayer);
      array_unshift($hand, "MON226");
      $resources = &GetResources($mainPlayer);
      $resources[0] += 2;
      break;
    case "ROGUE022":
      $defBanish = &GetBanish($otherPlayer);
      $health = &GetHealth($mainPlayer);
      $totalBD = 0;
      for($i = 0; $i < count($defBanish); $i += BanishPieces())
      {
        if(HasBloodDebt($defBanish[$i])) ++$totalBD;
      }
      $health += $totalBD;
      array_push($defBanish, "MON203");
      array_push($defBanish, "");
      array_push($defBanish, GetUniqueId());
      break;
    case "ROGUE024":
      AddCurrentTurnEffect("ROGUE024", $otherPlayer);
      break;
    case "ROGUE028":
      PlayAura("MON104", $mainPlayer);
      break;
    case "HVY047": case "HVY048":
      if(!SearchCurrentTurnEffects($cardID."-1", $mainPlayer) && $character[1] < 3) AddCurrentTurnEffect($cardID."-1", $mainPlayer);
      break;
    case "HVY254":
      AddCurrentTurnEffect("HVY254-1", $mainPlayer);
      AddCurrentTurnEffect("HVY254-2", $mainPlayer);
      break;
    case "MST005":
      $index = FindCharacterIndex($mainPlayer, $cardID);
      if($character[$index+12] == "DOWN" && GetHealth($mainPlayer) == 1) {
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Do you want to turn face-up " . CardLink($cardID, $cardID) . "?", 1);
        AddDecisionQueue("YESNO", $mainPlayer, "an_action", 1);
        AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, $index, 1);
        AddDecisionQueue("TURNCHARACTERFACEUP", $mainPlayer, "-", 1);
      }
      break;
    case "MST028":
      $index = FindCharacterIndex($mainPlayer, $cardID);
      if($character[$index+12] == "DOWN" && GetHealth($mainPlayer) == 1) {
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Do you want to turn face-up " . CardLink($cardID, $cardID) . "?", 1);
        AddDecisionQueue("YESNO", $mainPlayer, "an_action", 1);
        AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, $index, 1);
        AddDecisionQueue("TURNCHARACTERFACEUP", $mainPlayer, "-", 1);
      }
      break;
    case "MST049": 
      $index = FindCharacterIndex($mainPlayer, $cardID);
      if($character[$index+12] == "DOWN" && GetHealth($mainPlayer) == 1) {
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Do you want to turn face-up " . CardLink($cardID, $cardID) . "?", 1);
        AddDecisionQueue("YESNO", $mainPlayer, "an_action", 1);
        AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, $index, 1);
        AddDecisionQueue("TURNCHARACTERFACEUP", $mainPlayer, "-", 1);
      }
      break;
    case "MST067": case "MST069": case "MST070":
      $index = FindCharacterIndex($mainPlayer, $cardID);
      if($character[$index+12] == "UP") DestroyCharacter($mainPlayer, $index);
      break;
    case "MST068":
      $index = FindCharacterIndex($mainPlayer, $cardID);
      if($character[$index+12] == "DOWN" && GetHealth($mainPlayer) == 1) {
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Do you want to turn face-up " . CardLink($cardID, $cardID) . "?", 1);
        AddDecisionQueue("YESNO", $mainPlayer, "an_action", 1);
        AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, $index, 1);
        AddDecisionQueue("TURNCHARACTERFACEUP", $mainPlayer, "-", 1);
        AddDecisionQueue("DESTROYCHARACTER", $mainPlayer, "-", 1);
        MZMoveCard($mainPlayer, "MYDECK:isSameName=MST499", "MYHAND", may:true, isReveal:true, isSubsequent:true);
        AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-", 1);
      }
      break;
    default: break;
  }
}

function DefCharacterStartTurnAbilities()
{
  global $defPlayer, $mainPlayer;
  $character = &GetPlayerCharacter($defPlayer);
  for($i = 0; $i < count($character); $i += CharacterPieces()) {
    if($character[$i + 1] == 0 || $character[$i + 1] == 1) continue; //Do not process ability if it is destroyed
    $character[$i] = ShiyanaCharacter($character[$i]);
    switch($character[$i]) {
      case "EVR086":
        if (PlayerHasLessHealth($mainPlayer)) {
          AddDecisionQueue("CHARREADYORPASS", $defPlayer, $i);
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_draw_a_card_and_give_your_opponent_a_silver.", 1);
          AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
          AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
          AddDecisionQueue("PASSPARAMETER", $defPlayer, "EVR195", 1);
          AddDecisionQueue("PUTPLAY", $defPlayer, "0", 1);
        }
        break;
      case "DTD564":
        $character = GetPlayerCharacter($defPlayer);
        if($character[1] < 3) {
          AddCurrentTurnEffect("DTD564", $defPlayer);
        }
        break;
      case "HVY047": case "HVY048":
        $character = GetPlayerCharacter($defPlayer);
        if(!SearchCurrentTurnEffects($character[$i]."-1", $defPlayer) && $character[1] < 3) AddCurrentTurnEffect($character[$i]."-1", $defPlayer);
        break;
      case "ROGUE018":
        AddCurrentTurnEffect("ROGUE018", $mainPlayer);
        break;
      default:
        break;
    }
  }
}

function CharacterDestroyEffect($cardID, $player)
{
  switch($cardID) {
    case "ELE213":
      WriteLog(Cardlink($cardID, $cardID) . " destroys your arsenal");
      DestroyArsenal($player, effectController:$player);
      break;
    case "DYN214":
      AddLayer("TRIGGER", $player, "DYN214", "-", "-");
      break;
    case "DYN492b":
      $weaponIndex = FindCharacterIndex($player, "DYN492a");
      if(intval($weaponIndex) != -1) DestroyCharacter($player, $weaponIndex, true);
      break;
    case "EVO410b":
      # Add easter egg here when Teklovessen lore drops
      #WriteLog("Teklovessen lost his humanity for the greater good however as the machine shuts down he can no longer breathe.");
      include_once "./includes/dbh.inc.php";
      include_once "./includes/functions.inc.php";
      $conceded = true;
      if(!IsGameOver()) PlayerLoseHealth($player, GetHealth($player));
      break;
    case "MST027":
      SearchCurrentTurnEffects("MERIDIANWARD", $player, true);
      break;
    default:
      break;
  }
}

function CharacterBanishEffect($cardID, $player) {
  switch ($cardID) {
    case "DYN089":
      global $currentTurnEffects;
      $effectsCount = count($currentTurnEffects);
      $effectPieces = CurrentTurnPieces();
      for ($i = 0; $i < $effectsCount; $i += $effectPieces) {
        if ($currentTurnEffects[$i] == "DYN089-UNDER") {
          RemoveCurrentTurnEffect($i);
          break;
        }
      }
      break;
    default:
      break;
  }
}

function MainCharacterEndTurnAbilities()
{
  global $mainClassState, $CS_HitsWDawnblade, $CS_AtksWWeapon, $mainPlayer, $CS_NumNonAttackCards, $defPlayer;
  global $CS_NumAttackCards, $CS_ArcaneDamageDealt;
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  for($i = 0; $i < count($mainCharacter); $i += CharacterPieces()) {
    $characterID = ShiyanaCharacter($mainCharacter[$i]);
    switch($characterID) {
      case "WTR115":
        if(GetClassState($mainPlayer, $CS_HitsWDawnblade) == 0) $mainCharacter[$i+3] = 0;
        break;
      case "CRU077":
        if($mainCharacter[$i+1] == 1) break; //Do not process ability if it is disabled (e.g. Humble)
        KassaiEndTurnAbility();
        break;
      case "MON107":
        if($mainClassState[$CS_AtksWWeapon] >= 2 && $mainCharacter[$i+4] < 0) ++$mainCharacter[$i+4];
        break;
      case "ELE223":
        if(GetClassState($mainPlayer, $CS_NumNonAttackCards) == 0 || GetClassState($mainPlayer, $CS_NumAttackCards) == 0) $mainCharacter[$i + 3] = 0;
        break;
      case "ELE224":
        if(GetClassState($mainPlayer, $CS_ArcaneDamageDealt) < $mainCharacter[$i + 2]) DestroyCharacter($mainPlayer, $i);
        break;
      case "DTD222": case "DTD223": case "DTD224": case "DTD225":
        if($mainCharacter[$i+1] == 0) break; //Do not add negative counters if destroyed
        --$mainCharacter[$i+4];
        break;
      case "ROGUE018":
        PlayAura("ELE109", $mainPlayer);
        break;
      case "TER001":
        if($mainCharacter[$i+1] == 1) break; //Do not process ability if it is disabled (e.g. Humble)
        TerraEndTurnAbility($characterID, $mainPlayer);
        break;
      default: break;
    }
  }

  $defCharacter = &GetPlayerCharacter($defPlayer);
  for($i = 0; $i < count($defCharacter); $i += CharacterPieces()) {
    $characterID = ShiyanaCharacter($defCharacter[$i]);
    switch($characterID) {
      case "TER001":
        if($defCharacter[$i+1] == 1) break; //Do not process ability if it is disabled (e.g. Humble)
        TerraEndTurnAbility($characterID, $defPlayer);
        break;
      default: break;
    }
  }
}

function MainCharacterHitTrigger()
{
  global $CombatChain, $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  $attackID = $CombatChain->AttackCard()->ID();
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  for($i = 0; $i < count($mainCharacter); $i += CharacterPieces()) {
    if(TypeContains($mainCharacter[$i], "W", $mainPlayer) || $mainCharacter[$i + 1] != "2") continue;
    $characterID = ShiyanaCharacter($mainCharacter[$i], $mainPlayer);
    switch($characterID) {
      case "WTR076": case "WTR077":
        if(CardType($attackID) == "AA") {
          AddLayer("TRIGGER", $mainPlayer, $characterID, $attackID, "MAINCHARHITEFFECT");
          $mainCharacter[$i+1] = 1;
        }
        break;
      case "WTR079":
        if(CardType($attackID) == "AA" && HitsInRow() >= 2) {
          AddLayer("TRIGGER", $mainPlayer, $characterID, $attackID, "MAINCHARHITEFFECT");
          $mainCharacter[$i+1] = 1;
        }
        break;
      case "WTR113": case "WTR114":
        if($mainCharacter[$i+1] == 2 && TypeContains($attackID, "W", $mainPlayer) && $mainCharacter[$combatChainState[$CCS_WeaponIndex]+1] != 0) {
          $mainCharacter[$i+1] = 1;
          $mainCharacter[$combatChainState[$CCS_WeaponIndex]+1] = 2;
          ++$mainCharacter[$combatChainState[$CCS_WeaponIndex]+5];
        }
        break;
      case "WTR117":
        if(TypeContains($attackID, "W", $mainPlayer) && IsCharacterActive($mainPlayer, $i)) {
          AddLayer("TRIGGER", $mainPlayer, $characterID, $attackID, "MAINCHARHITEFFECT");
        }
        break;
      case "ARC152":
        if(CardType($attackID) == "AA" && IsCharacterActive($mainPlayer, $i)) {
          AddLayer("TRIGGER", $mainPlayer, $characterID, $attackID, "MAINCHARHITEFFECT");
        }
        break;
      case "CRU047":
        if(CardType($attackID) == "AA" && $mainCharacter[$i+5] == 1) {
          AddCurrentTurnEffectFromCombat("CRU047", $mainPlayer);
          $mainCharacter[$i+5] = 0;
        }
        break;
      case "CRU053":
        if(CardType($attackID) == "AA" && ClassContains($attackID, "NINJA", $mainPlayer) && IsCharacterActive($mainPlayer, $i)) {
          AddLayer("TRIGGER", $mainPlayer, $characterID, $attackID, "MAINCHARHITEFFECT");
        }
        break;
      case "ELE062": case "ELE063":
        if(IsHeroAttackTarget() && CardType($attackID) == "AA" && !SearchAuras("ELE109", $mainPlayer)) {
          AddLayer("TRIGGER", $mainPlayer, $characterID, $attackID, "MAINCHARHITEFFECT");
        }
        break;
      case "EVR037":
        if(CardType($attackID) == "AA" && IsCharacterActive($mainPlayer, $i)) {
          AddLayer("TRIGGER", $mainPlayer, $characterID, $attackID, "MAINCHARHITEFFECT");
        }
        break;
      case "HVY097":
        if(TypeContains($attackID, "W", $mainPlayer))
        {
          AddLayer("TRIGGER", $mainPlayer, $characterID, $attackID, "MAINCHARHITEFFECT");
        }
        break;
      case "ROGUE016":
        if(CardType($attackID) == "AA") {
          $deck = &GetDeck($mainPlayer);
          array_unshift($deck, "ARC069");
        }
        break;
      case "ROGUE024":
        if(IsHeroAttackTarget()) {
          $otherPlayer = ($mainPlayer == 1 ? 2 : 1);
          DamageTrigger($otherPlayer, 1, "ATTACKHIT");
        }
        break;
      case "ROGUE028":
        if(IsHeroAttackTarget()) {
          PlayAura("MON104", $mainPlayer);
          PlayAura("MON104", $mainPlayer);
        }
        break;
      default: break;
    }
  }
}

function MainCharacterAttackModifiers(&$attackModifiers, $index = -1, $onlyBuffs = false, $player=-1)
{
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  $modifier = 0;
  $mainCharacterEffects = &GetMainCharacterEffects($mainPlayer);
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  if($index == -1) $index = $combatChainState[$CCS_WeaponIndex];
  for($i = 0; $i < count($mainCharacterEffects); $i += CharacterEffectPieces()) {
    if($player != -1 && !SearchCurrentTurnEffects(substr($mainCharacterEffects[$i + 1], 0, 6), $player)) return false;
    if($mainCharacterEffects[$i] == $index) {
      switch($mainCharacterEffects[$i + 1]) {
        case "WTR119":
          $modifier += 2;
          array_push($attackModifiers, $mainCharacterEffects[$i + 1]);
          array_push($attackModifiers, 2);
          break;
        case "WTR122": case "WTR135": case "WTR136": case "WTR137":
        case "CRU079": case "CRU080":
        case "MON105": case "MON106": case "MON113": case "MON114": case "MON115":
        case "EVR055-1":
          $modifier += 1;
          array_push($attackModifiers, $mainCharacterEffects[$i + 1]);
          array_push($attackModifiers, 1);
          break;
        default:
          break;
      }
    }
  }
  if($onlyBuffs) return $modifier;
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  for($i = 0; $i < count($mainCharacter); $i += CharacterPieces()) {
    if(!IsCharacterAbilityActive($mainPlayer, $i)) continue;
    $characterID = ShiyanaCharacter($mainCharacter[$i]);
    switch($characterID) {
      case "MON029": case "MON030":
        if(HaveCharged($mainPlayer) && NumAttacksBlocking() > 0)
        {
          $modifier += 1;
          array_push($attackModifiers, $characterID);
          array_push($attackModifiers, 1);
        }
        break;
      default: break;
    }
  }
  return $modifier;
}

function MainCharacterHitEffects()
{
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  $modifier = 0;
  $mainCharacterEffects = &GetMainCharacterEffects($mainPlayer);
  for($i = 0; $i < count($mainCharacterEffects); $i += 2) {
    if($mainCharacterEffects[$i] == $combatChainState[$CCS_WeaponIndex]) {
      switch($mainCharacterEffects[$i + 1]) {
        case "WTR119":
          AddLayer("TRIGGER", $mainPlayer, $mainCharacterEffects[$i + 1]);
          break;
        default: break;
      }
    }
  }
  return $modifier;
}

function MainCharacterGrantsGoAgain()
{
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  if($combatChainState[$CCS_WeaponIndex] == -1) return false;
  $mainCharacterEffects = &GetMainCharacterEffects($mainPlayer);
  for($i = 0; $i < count($mainCharacterEffects); $i += 2) {
    if($mainCharacterEffects[$i] == $combatChainState[$CCS_WeaponIndex]) {
      switch($mainCharacterEffects[$i + 1]) {
        case "EVR055-2": return true;
        default: break;
      }
    }
  }
  return false;
}

function WeaponHasGoAgainLabel($index, $player)
{
  global $mainPlayer;
  $mainCharacterEffects = &GetMainCharacterEffects($mainPlayer);
  for($i = 0; $i < count($mainCharacterEffects); $i += 2) {
    if($mainCharacterEffects[$i] == $index) {
      if(!SearchCurrentTurnEffects(substr($mainCharacterEffects[$i + 1], 0, 6), $player)) return false;
      switch($mainCharacterEffects[$i + 1]) {
        case "EVR055-2": return true;
        default: break;
      }
    }
  }
  return false;
}

function CharacterCostModifier($cardID, $from, $cost)
{
  global $currentPlayer, $CS_NumSwordAttacks, $CS_NumCardsDrawn, $CS_NumSpectralShieldAttacks;
  $modifier = 0;
  $char = &GetPlayerCharacter($currentPlayer);
  for($i=0; $i<count($char); $i+=CharacterPieces()) {
    if($char[$i+1] >= 3 || $char[$i+1] == 0) continue;
    if(CardType($char[$i]) == "C") $thisChar = ShiyanaCharacter($char[$i]);
    else $thisChar = $char[$i];
    switch($thisChar) {
      case "CRU077": if(CardSubtype($cardID) == "Sword" && GetClassState($currentPlayer, $CS_NumSwordAttacks) == 1) --$modifier; break;
      case "TCC001": if(SubtypeContains($cardID, "Evo")) --$modifier; break;
      case "TCC408": if($cardID == "TCC002") --$modifier; break;
      case "EVO001": case "EVO002": if($from == "DECK" && SubtypeContains($cardID, "Item", $currentPlayer) && CardCost($cardID) < 2) ++$modifier; break;
      case "HVY090": case "HVY091": if(CardSubtype($cardID) == "Sword" && GetClassState($currentPlayer, $CS_NumCardsDrawn) >= 1) --$modifier; break;
      case "MST001": case "MST002": if($from == "THEIRBANISH" && (SearchCurrentTurnEffects("MST001", $currentPlayer) || SearchCurrentTurnEffects("MST002", $currentPlayer))) $modifier -= $cost; break;
      case "MST025": case "MST026": if(CardNameContains($cardID, "Spectral Shield", $currentPlayer) && GetClassState($currentPlayer, $CS_NumSpectralShieldAttacks) == 0) --$modifier; break;
      default: break;
    }
  }
  return CostCantBeModified($cardID) ? 0 : $modifier;
}

function EquipEquipment($player, $card, $slot="")
{
  if($slot == "") {
    if(SubtypeContains($card, "Head")) $slot = "Head";
    else if(SubtypeContains($card, "Chest")) $slot = "Chest";
    else if(SubtypeContains($card, "Arms")) $slot = "Arms";
    else if(SubtypeContains($card, "Legs")) $slot = "Legs";
  }
  $char = &GetPlayerCharacter($player);
  $uniqueID = GetUniqueId($card, $player);
  $replaced = 0;
  //Replace the first destroyed weapon; if none you can't re-equip
  for($i=CharacterPieces(); $i<count($char) && !$replaced; $i+=CharacterPieces())
  {
    if(SubtypeContains($char[$i], $slot, $player))
    {
      $char[$i] = $card;
      $char[$i+1] = 2;
      $char[$i+2] = 0;
      $char[$i+3] = 0;
      $char[$i+4] = 0;
      $char[$i+5] = 1;
      $char[$i+6] = 0;
      $char[$i+7] = 0;
      $char[$i+8] = 0;
      $char[$i+9] = CharacterDefaultActiveState($card);
      $char[$i+10] = "-";
      $char[$i+11] = $uniqueID;
      $char[$i+12] = HasCloaked($card, $player);
      $replaced = 1;
    }
  }
  if(!$replaced)
  {
    $insertIndex = count($char);
    array_splice($char, $insertIndex, 0, $card);
    array_splice($char, $insertIndex+1, 0, 2);
    array_splice($char, $insertIndex+2, 0, 0);
    array_splice($char, $insertIndex+3, 0, 0);
    array_splice($char, $insertIndex+4, 0, 0);
    array_splice($char, $insertIndex+5, 0, 1);
    array_splice($char, $insertIndex+6, 0, 0);
    array_splice($char, $insertIndex+7, 0, 0);
    array_splice($char, $insertIndex+8, 0, 0);
    array_splice($char, $insertIndex+9, 0, 2);
    array_splice($char, $insertIndex+10, 0, "-");
    array_splice($char, $insertIndex+11, 0, $uniqueID);
    array_splice($char, $insertIndex+12, 0, HasCloaked($card, $player));
  }
  if($card == "EVO013") AddCurrentTurnEffect("EVO013-" . $uniqueID . "," . $slot, $player);
}

function EquipWeapon($player, $card)
{
  $char = &GetPlayerCharacter($player);
  $lastWeapon = 0;
  $replaced = 0;
  $numHands = 0;
  //Replace the first destroyed weapon; if none you can't re-equip
  for($i=CharacterPieces(); $i<count($char) && !$replaced; $i+=CharacterPieces())
  {
    if(TypeContains($char[$i], "W", $player))
    {
      $lastWeapon = $i;
      if($char[$i+1] == 0)
      {
        $char[$i] = $card;
        $char[$i+1] = 2;
        $char[$i+2] = 0;
        $char[$i+3] = 0;
        $char[$i+4] = 0;
        $char[$i+5] = 1;
        $char[$i+6] = 0;
        $char[$i+7] = 0;
        $char[$i+8] = 0;
        $char[$i+9] = CharacterDefaultActiveState($card);
        $char[$i+10] = "-";
        $char[$i+11] = GetUniqueId($card, $player);
        $char[$i+12] = HasCloaked($card, $player);
        $replaced = 1;
      }
      else if(Is1H($char[$i])) ++$numHands;
      else $numHands += 2;
    }
  }
  if($numHands < 2 && !$replaced)
  {
    $insertIndex = $lastWeapon + CharacterPieces();
    array_splice($char, $insertIndex, 0, $card);
    array_splice($char, $insertIndex+1, 0, 2);
    array_splice($char, $insertIndex+2, 0, 0);
    array_splice($char, $insertIndex+3, 0, 0);
    array_splice($char, $insertIndex+4, 0, 0);
    array_splice($char, $insertIndex+5, 0, 1);
    array_splice($char, $insertIndex+6, 0, 0);
    array_splice($char, $insertIndex+7, 0, 0);
    array_splice($char, $insertIndex+8, 0, 0);
    array_splice($char, $insertIndex+9, 0, 2);
    array_splice($char, $insertIndex+10, 0, "-");
    array_splice($char, $insertIndex+11, 0, GetUniqueId($card, $player));
    array_splice($char, $insertIndex+12, 0, HasCloaked($card, $player));
  }
}

function ShiyanaCharacter($cardID, $player="")
{
  global $currentPlayer;
  if($player == "") $player = $currentPlayer; 
  if($cardID == "CRU097") {
    $otherPlayer = ($player == 1 ? 2 : 1);
    $otherCharacter = &GetPlayerCharacter($otherPlayer);
    if(SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $player)) $cardID = $otherCharacter[0];
  }
  return $cardID;
}

function EquipPayAdditionalCosts($cardIndex, $from)
{
  global $currentPlayer, $CS_AdditionalCosts, $currentTurnEffects;
  $character = &GetPlayerCharacter($currentPlayer);
  $cardID = $character[$cardIndex];
  $cardID = ShiyanaCharacter($cardID);
  switch($cardID) {
    case "WTR150": //Tunic energy counters
      $character[$cardIndex+2] -= 3;
      break;
    case "CRU177": //Talishar rust counters
      $character[$cardIndex+1] = 1;
      ++$character[$cardIndex+2];
      break;
    case "WTR037": case "WTR038":
    case "ARC003": case "ARC113": case "ARC114":
    case "CRU024": case "CRU101":
    case "MON029": case "MON030":
    case "ELE173":
    case "DYN001":
    case "OUT096":
    case "TCC050":
      break; //Unlimited uses
    case "ELE224": //Spellbound Creepers - Bind counters
      ++$character[$cardIndex + 2];//Add a counter
      --$character[$cardIndex + 5];
      if($character[$cardIndex + 5] == 0) $character[$cardIndex + 1] = 1;
      break;
    case "UPR151": //Ghostly Touch - Haunt counters
      $character[$cardIndex+2] -= 1;//Remove a counter
      --$character[$cardIndex+5];
      if($character[$cardIndex+5] == 0) $character[$cardIndex + 1] = 1;
      break;
    case "UPR166": //Alluvion Constellas - Energy counters
      $character[$cardIndex+2] -= 2;
      break;
    case "DYN088": //Hanabi Blaster - Steam counters, once per turn
      $character[$cardIndex+2] -= 2;
      $character[$cardIndex+1] = 1;
      break;
    case "DYN492a":
      --$character[$cardIndex+ 2];
      break;
    case "WTR005": case "WTR042": case "WTR080": case "WTR151": case "WTR152": case "WTR153": case "WTR154":
    case "ARC005": case "ARC042": case "ARC079": case "ARC116": case "ARC117": case "ARC151": case "ARC153": case "ARC154":
    case "CRU006": case "CRU025": case "CRU081": case "CRU102": case "CRU122": case "CRU141":
    case "MON061": case "MON090": case "MON108": case "MON188": case "MON230": case "MON238": case "MON239": case "MON240":
    case "ELE116": case "ELE145": case "ELE214": case "ELE225": case "ELE233": case "ELE234": case "ELE235": case "ELE236":
    case "EVR053": case "EVR103": case "EVR137":
    case "DVR004": case "DVR005":
    case "RVD004":
    case "UPR004": case "UPR047": case "UPR085": case "UPR125": case "UPR137": case "UPR159": case "UPR167":
    case "DYN046": case "DYN117": case "DYN118": case "DYN171": case "DYN235":
    case "OUT011": case "OUT049": case "OUT095": case "OUT098": case "OUT140": case "OUT141": case "OUT157": case "OUT158":
    case "OUT175": case "OUT176": case "OUT177": case "OUT178": case "OUT179": case "OUT180": case "OUT181": case "OUT182":
    case "TCC079": case "TCC082":
    case "EVO235": case "EVO247":
    case "TCC051": case "TCC052": case "TCC053": case "TCC054": case "TCC080":
    case "HVY009": case "HVY010": case "HVY098": case "HVY135": case "HVY155": case "HVY175": case "HVY195": case "HVY196": case "HVY197":
    case "AKO004":
    case "MST006": case "MST007": case "MST048": 
    case "AAZ004":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "DTD001": case "DTD002":
      BanishFromSoul($currentPlayer);
      --$character[$cardIndex+5];
      break;
    case "DTD075": case "DTD076": case "DTD077": case "DTD078":
      $char = new Character($currentPlayer, $cardIndex);
      $char->status = 0;
      BanishCardForPlayer($char->cardID, $currentPlayer, "EQUIP", "NA");
      $char->Finished();
      BanishFromSoul($currentPlayer);
      break;
    case "DTD106":
      $char = new Character($currentPlayer, $cardIndex);
      $char->status = 0;
      BanishCardForPlayer($char->cardID, $currentPlayer, "EQUIP", "NA");
      $char->Finished();
      break;
    case "DTD135":
      LoseHealth(1, $currentPlayer);
      --$character[$cardIndex+5];
      if($character[$cardIndex+5] == 0) $character[$cardIndex+1] = 1; //By default, if it's used, set it to used
      break;
    case "DTD136":
      BanishCardForPlayer("DTD136", $currentPlayer, "EQUIP", "NA");
      DestroyCharacter($currentPlayer, $cardIndex, true);
      break;
    case "EVO003":
      $character[$cardIndex+2] -= 1;
      break;
    case "EVO014": case "EVO015": case "EVO016": case "EVO017":
      $character[$cardIndex+2] = 0;
      break;
    case "EVO434": case "EVO435": case "EVO436": case "EVO437":
    case "EVO446": case "EVO447": case "EVO448": case "EVO449":
      --$character[$cardIndex+5];
      if($character[$cardIndex+5] == 0) $character[$cardIndex+1] = 1; //By default, if it's used, set it to used
      break;
    case "HVY055":
      $index = GetItemIndex("DYN243", $currentPlayer);
      if($index != -1) DestroyItemForPlayer($currentPlayer, $index);
      else {
        $charIndex = FindCharacterIndex($currentPlayer, "HVY051");
        if($charIndex != -1) DestroyCharacter($currentPlayer, $charIndex);
      }
      break;
    case "HVY099":
      DestroyCharacter($currentPlayer, $cardIndex, true);
      MZMoveCard($currentPlayer, "MYDISCARD:pitch=1", "MYBANISH,GY,-");
      MZMoveCard($currentPlayer, "MYDISCARD:pitch=2", "MYBANISH,GY,-");
      break;
    case "MST029": case "MST030": 
    case "MST067": case "MST069": case "MST070": 
      $character[$cardIndex + 12] = "UP";
      break;
    case "MST071": case "MST072": case "MST073": case "MST074":
      $character[$cardIndex + 12] = "UP";
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    case "MST232": 
      DestroyCharacter($currentPlayer, $cardIndex, true);
      break;  
    default:
      --$character[$cardIndex+5];
      if($character[$cardIndex+5] == 0) $character[$cardIndex+1] = 1; //By default, if it's used, set it to used
      break;
  }
}

function CharacterTriggerInGraveyard($cardID)
{
  switch($cardID) {
    case "DYN117": case "DYN118": return true;
    case "OUT011": return true;
    case "EVO235": return true;
    default: return false;
  }
}

function CharacterTakeDamageAbilities($player, $damage, $type, $preventable)
{
  global $CS_NumCharged, $CS_DamageDealt;
  $char = &GetPlayerCharacter($player);
  $otherPlayer = $player == 1 ? 2 : 1;
  for($i = count($char) - CharacterPieces(); $i >= 0; $i -= CharacterPieces())
  {
    if($char[$i+1] == 0) continue;
    switch($char[$i]) {
      case "DTD047":
        if($damage > 0 && $preventable && $char[$i+5] > 0 && GetClassState($player, $CS_NumCharged) > 0 && GetClassState($otherPlayer, $CS_DamageDealt) <= 0)
        {
          --$damage;
          --$char[$i+5];
          SearchCurrentTurnEffects("DTD047", $player, true);
        }
        break;
      case "DTD165": case "DTD166": case "DTD167": case "DTD168":
        if($char[$i+9] == 0) break;
        if($damage > 0) {
          if($preventable) $damage -= 2;
          BanishCardForPlayer($char[$i], $player, "PLAY");
          DestroyCharacter($player, $i, skipDestroy:true);
        }
        break;
      default:
        break;
    }
  }
  return $damage > 0 ? $damage : 0;
}

function CharacterDamageTakenAbilities($player, $damage)
{
  $char = &GetPlayerCharacter($player);
  for($i = count($char) - CharacterPieces(); $i >= 0; $i -= CharacterPieces())
  {
    if($char[$i + 1] != 2) continue;
    switch($char[$i]) {
      case "ROGUE015":
        $hand = &GetHand($player);
        for($j = 0; $j < $damage; ++$j)
        {
          $randomNimb = rand(1,3);
          if($randomNimb == 1) array_unshift($hand, "WTR218");
          else if($randomNimb == 2) array_unshift($hand, "WTR219");
          else array_unshift($hand, "WTR220");
        }
        break;
      case "ROGUE019":
        PlayAura("CRU075", $player, 4, false, true);
        break;
      default:
        break;
    }
  }
}

function CharacterAttackDestroyedAbilities($attackID)
{
  global $mainPlayer;
  $character = &GetPlayerCharacter($mainPlayer);
  for($i=0; $i<count($character); $i += CharacterPieces()) {
    if($character[$i+1] == 0) continue;
    switch($character[$i]) {
      case "MON089":
        if($character[$i+5] > 0 && CardType($attackID) == "AA" && ClassContains($attackID, "ILLUSIONIST", $mainPlayer)){
          AddDecisionQueue("ADDTRIGGER", $mainPlayer, $character[$i], $i);
          --$character[$i+5];
        }
        break;
      case "UPR152":
        $hand = &GetHand($mainPlayer);
        $resources = &GetResources($mainPlayer);
        if(Count($hand) > 0 || $resources[0] > 0)
        {
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_pay_3_to_gain_an_action_point", 0, 1);
          AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
          AddDecisionQueue("PASSPARAMETER", $mainPlayer, 3, 1);
          AddDecisionQueue("PAYRESOURCES", $mainPlayer, "<-", 1);
          AddDecisionQueue("GAINACTIONPOINTS", $mainPlayer, "1", 1);
          AddDecisionQueue("FINDINDICES", $mainPlayer, "EQUIPCARD,UPR152", 1);
          AddDecisionQueue("DESTROYCHARACTER", $mainPlayer, "-", 1);
        }
        break;
      default: break;
    }
  }
}

function CharacterPlayCardAbilities($cardID, $from) {
  global $currentPlayer, $CS_NumLess3PowAAPlayed, $CS_NumAttacks;
  $character = &GetPlayerCharacter($currentPlayer);
  for($i=0; $i<count($character); $i+=CharacterPieces()) {
    if($character[$i+1] != 2) continue;
    $characterID = ShiyanaCharacter($character[$i]);
    switch($characterID) {
      case "UPR158"://Tiger Stripe Shuko
        if(GetClassState($currentPlayer, $CS_NumLess3PowAAPlayed) == 2 && AttackValue($cardID) <= 2) {
          AddCurrentTurnEffect($characterID, $currentPlayer);
          $character[$i+1] = 1;
        }
        break;
      case "CRU046": case "ROGUE008":
        if(GetClassState($currentPlayer, $CS_NumAttacks) == 2) {
          AddCurrentTurnEffect($characterID, $currentPlayer);
          $character[$i+1] = 1;
        }
        break;
      case "ROGUE025":
        $resources = &GetResources($currentPlayer);
        ++$resources[0];
        break;
      case "TCC049"://Melody, Sing-Along
        if(SubtypeContains($cardID, "Song", $currentPlayer)) PutItemIntoPlayForPlayer("CRU197", $currentPlayer);
        break;
      default: break;
    }
  }
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $otherCharacter = &GetPlayerCharacter($otherPlayer);
  for($i=0; $i<count($otherCharacter); $i+=CharacterPieces()) {
    $characterID = $otherCharacter[$i];
    switch($characterID) {
      case "ROGUE026":
        if(CardType($cardID) != "W" && CardType($cardID) != "E") {
          $generatedAmount = CardCost($cardID);
          if($generatedAmount < 1) $generatedAmount = 1;
          for($j = 0; $j < $generatedAmount; ++$j)
          {
            PutItemIntoPlayForPlayer("DYN243", $currentPlayer, effectController:$currentPlayer);
          }
        }
        break;
      default: break;
    }
  }
}

function MainCharacterPlayCardAbilities($cardID, $from)
{
  global $currentPlayer, $mainPlayer, $CS_NumNonAttackCards, $CS_NumBoostPlayed;
  $character = &GetPlayerCharacter($currentPlayer);
  for($i = 0; $i < count($character); $i += CharacterPieces()) {
    if($character[$i + 1] != 2) continue;
    $characterID = ShiyanaCharacter($character[$i]);
    switch($characterID) {
      case "ARC075": case "ARC076": //Viserai
        if(!IsStaticType(CardType($cardID), $from, $cardID) && ClassContains($cardID, "RUNEBLADE", $currentPlayer)) {
          AddLayer("TRIGGER", $currentPlayer, $characterID, $cardID);
        }
        break;
      case "CRU161":
        if((ActionsThatDoArcaneDamage($cardID) || ActionsThatDoXArcaneDamage($cardID)) && SearchCharacterActive($currentPlayer, "CRU161", checkGem:true)) AddLayer("TRIGGER", $currentPlayer, "CRU161");
        break;
      case "ELE062": case "ELE063":
        if(CardType($cardID) == "A" && GetClassState($currentPlayer, $CS_NumNonAttackCards) == 2 && $from != "PLAY") {
          AddLayer("TRIGGER", $currentPlayer, $characterID);
        }
        break;
      case "EVR120": case "UPR102": case "UPR103": //Iyslander
        if($currentPlayer != $mainPlayer && TalentContains($cardID, "ICE", $currentPlayer) && !IsStaticType(CardType($cardID), $from, $cardID)) {
          AddLayer("TRIGGER", $currentPlayer, $characterID);
        }
        break;
      case "DYN088":
        $numBoostPlayed = 0;
        if(HasBoost($cardID, $currentPlayer)) {
          $numBoostPlayed = GetClassState($currentPlayer, $CS_NumBoostPlayed) + 1;
          SetClassState($currentPlayer, $CS_NumBoostPlayed, $numBoostPlayed);
        }
        if($numBoostPlayed == 3) {
          $index = FindCharacterIndex($currentPlayer, "DYN088");
          ++$character[$index + 2];
        }
        break;
      case "DYN113": case "DYN114":
        if(ContractType($cardID) != "") AddLayer("TRIGGER", $currentPlayer, $characterID);
        break;
      case "OUT003":
        if(HasStealth($cardID)) {
          GiveAttackGoAgain();
          $character[$i+1] = 1;
        }
        break;
      case "OUT091": case "OUT092": //Riptide
        if($from == "HAND" && GetResolvedAbilityName($cardID,"HAND") != "Ability") {
          AddLayer("TRIGGER", $currentPlayer, $characterID, $cardID);
        }
        break;
      case "DTD133": case "DTD134": //Vynnset
        if(CardType($cardID) == "A" && TalentContains($cardID, "SHADOW", $currentPlayer))
        {
          AddLayer("TRIGGER", $currentPlayer, $characterID, $cardID);
        }
        break;
      case "EVO001": case "EVO002":
        if($from == "DECK") {
          --$character[$i+5];
        }
        break;
      case "ROGUE017":
        if(CardType($cardID) == "AA") {
          $deck = &GetDeck($currentPlayer);
          array_unshift($deck, $cardID);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        }
        break;
      case "ROGUE003":
        if(CardType($cardID) == "AA") {
          $deck = &GetDeck($currentPlayer);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        }
        break;
      case "ROGUE019":
        if($cardID == "CRU066" || $cardID == "CRU067" || $cardID == "CRU068") {
          $choices = array("CRU057", "CRU058", "CRU059");
          $hand = &GetHand($currentPlayer);
          array_unshift($hand, $choices[rand(0, count($choices)-1)]);
        }
        else if($cardID == "CRU057" || $cardID == "CRU058" || $cardID == "CRU059") {
          $choices = array("CRU054", "CRU056");
          $hand = &GetHand($currentPlayer);
          array_unshift($hand, $choices[rand(0, count($choices)-1)]);
        }
        break;
      case "ROGUE031":
        global $actionPoints;
        if(CardTalent($cardID) == "LIGHTNING"){
          $actionPoints++;
        }
        break;
      default: break;
    }
  }
}

function CharacterDealDamageAbilities($player, $damage)
{
  $char = &GetPlayerCharacter($player);
  for ($i = count($char) - CharacterPieces(); $i >= 0; $i -= CharacterPieces())
  {
    if($char[$i + 1] != 2) continue;
    switch ($char[$i]) {
      case "ROGUE023":
        if($damage >= 4)
        {
          PlayAura("CRU031", $player, 1, false, true);
        }
        break;
      case "ROGUE029":
        for($j = count($char) - CharacterPieces(); $j >= 0; $j -= CharacterPieces())
        {
          if($char[$j] == "DYN068") $indexCounter = $j+3;
        }
        $char[$indexCounter] += 1;
        if($damage >= 4)
        {
          $char[$indexCounter] = $char[$indexCounter] * 2;
        }
        break;
      default:
        break;
    }
  }
}

function CharacterAttackAbilities($attackID)
{
  global $mainPlayer, $combatChainState, $CCS_LinkBaseAttack, $CS_PlayIndex;
  $char = &GetPlayerCharacter($mainPlayer);
  for($i=0; $i<count($char); $i+=CharacterPieces()) {
    if($char[$i+1] == 0) continue;//Don't do effect if destroyed
    switch($char[$i]) {
      case "TCC409":
        if($attackID == "TCC002") {
          AddCurrentTurnEffect($char[$i], $mainPlayer);
          WriteLog("Evo Scatter Shot gives +1");
        }
        break;
      case "TCC410":
        if($attackID == "TCC002") {
          GiveAttackGoAgain();
          WriteLog("Evo Rapid Fire gives Go Again");
        }
        break;
      case "MST130":
        if(HasWard($attackID, $mainPlayer) && SubtypeContains($attackID, "Aura", $mainPlayer)) {
          $combatChainState[$CCS_LinkBaseAttack] = WardAmount($attackID, $mainPlayer, GetClassState($mainPlayer, $CS_PlayIndex));
        }
        break;
      default: break;
    }
  }
}

function GetCharacterGemState($player, $cardID)
{
  $char = &GetPlayerCharacter($player);
  for($i=0; $i<count($char); $i+=CharacterPieces()) {
    if($char[$i] == $cardID) return $char[$i+9];
  }
  return 0;
}

function CharacterBoostAbilities($player) {
  $char = &GetPlayerCharacter($player);
  for($i=0; $i<count($char); $i+=CharacterPieces()) {
    if(intval($char[$i+1]) < 2) continue;
    switch($char[$i]) {
      case "EVO430":
        if($char[$i+9] == 1 && EvoHasUnderCard($player, $i)) {
          MZMoveCard($player, "MYBANISH:type=AA", "MYTOPDECK", may:false);
          MZMoveCard($player, "MYBANISH:type=AA", "MYTOPDECK", may:false);
          AddDecisionQueue("SHUFFLEDECK", $player, "-");
          CharacterChooseSubcard($player, $i, fromDQ:false);
          AddDecisionQueue("ADDDISCARD", $player, "-", 1);
        }
        break;
      case "EVO431":
        if($char[$i+9] == 1 && EvoHasUnderCard($player, $i)) {
          GainResources($player, 2);
          CharacterChooseSubcard($player, $i, fromDQ:false);
          AddDecisionQueue("ADDDISCARD", $player, "-", 1);
        }
        break;
      case "EVO432":
        if($char[$i+9] == 1 && EvoHasUnderCard($player, $i)) {
          AddCurrentTurnEffect($char[$i], $player);
          CharacterChooseSubcard($player, $i, fromDQ:false);
          AddDecisionQueue("ADDDISCARD", $player, "-", 1);
        }
        break;
      case "EVO433":
        if($char[$i+9] == 1 && EvoHasUnderCard($player, $i)) {
          PlayAura("WTR225", $player);
          CharacterChooseSubcard($player, $i, fromDQ:false);
          AddDecisionQueue("ADDDISCARD", $player, "-", 1);
        }
        break;
      default: break;
    }
  }
}