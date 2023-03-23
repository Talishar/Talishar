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
  array_push($char, 2);
  return $index;
}

function CharacterCounters ($cardID)
{
  switch($cardID) {
    case "DYN492a": return 8;
    default: return 0;
  }
}

function CharacterTakeDamageAbility($player, $index, $damage, $preventable)
{
  $char = &GetPlayerCharacter($player);
  $otherPlayer = $player == 1 ? 1 : 2;
  //CR 2.1 6.4.10f If an effect states that a prevention effect can not prevent the damage of an event, the prevention effect still applies to the event but its prevention amount is not reduced. Any additional modifications to the event by the prevention effect still occur.
  $type = "-";//Add this if it ever matters
  $preventable = CanDamageBePrevented($otherPlayer, $damage, $type);
  switch ($char[$index]) {
    case "DYN213":
      if ($damage > 0) {
        if ($preventable) $damage -= 1;
        $remove = 1;
      }
      break;
    case "DYN214":
      if ($damage > 0) {
        if ($preventable) $damage -= 1;
        $remove = 1;
      }
      break;
    default:
      break;
  }
  if ($remove == 1) {
    if (HasWard($char[$index]) && (SearchCharacterActive($player, "DYN213") || $char[$index] == "DYN213") && CardType($char[$index]) != "T") {
      $kimonoIndex = FindCharacterIndex($player, "DYN213");
      $char[$kimonoIndex + 1] = 1;
      GainResources($player, 1);
    }
    DestroyCharacter($player, $index);
  }
  if ($damage <= 0) $damage = 0;
  return $damage;
}

function CharacterStartTurnAbility($index)
{
  global $mainPlayer;
  $char = new Character($mainPlayer, $index);
  if($char->status == 0 && !CharacterTriggerInGraveyard($char->cardID)) return;
  if($char->status == 1) return;
  switch($char->cardID) {
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
        WriteLog(CardLink($char->cardID, $char->cardID) . " got banished for having 13 or less health");
        $char->Finished();
      }
      break;
    case "EVR017":
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "You may reveal an Earth, Ice, and Lightning card for Bravo, Star of the Show.");
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
    case "DYN117": case "DYN118": case "OUT011":
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
        AddDecisionQueue("MULTIZONEREMOVE", $mainPlayer, "-", 1);
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
    if($character[$i + 1] == 0) continue; //Do not process ability if it is destroyed
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
      default:
        break;
    }
  }
}

function CharacterDestroyEffect($cardID, $player)
{
  switch($cardID) {
    case "ELE213":
      DestroyArsenal($player);
      break;
    case "DYN214":
      AddLayer("TRIGGER", $player, "DYN214", "-", "-");
      break;
    case "DYN492b":
      $weaponIndex = FindCharacterIndex($player, "DYN492a");
      if(intval($weaponIndex) != -1) DestroyCharacter($player, $weaponIndex);
      break;
    default:
      break;
  }
}

function MainCharacterEndTurnAbilities()
{
  global $mainClassState, $CS_HitsWDawnblade, $CS_AtksWWeapon, $mainPlayer, $defPlayer, $CS_NumNonAttackCards;
  global $CS_NumAttackCards, $defCharacter, $CS_ArcaneDamageDealt;

  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  for($i = 0; $i < count($mainCharacter); $i += CharacterPieces()) {
    $characterID = $mainCharacter[$i];
    if($i == 0 && $mainCharacter[$i] == "CRU097")
    {
      $otherCharacter = &GetPlayerCharacter($defPlayer);
      if(SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $mainPlayer)) {
        $characterID = $otherCharacter[0];
      }
    }
    switch($characterID) {
      case "WTR115":
        if(GetClassState($mainPlayer, $CS_HitsWDawnblade) == 0) $mainCharacter[$i + 3] = 0;
        break;
      case "CRU077":
        KassaiEndTurnAbility();
        break;
      case "MON107":
        if($mainClassState[$CS_AtksWWeapon] >= 2 && $mainCharacter[$i + 4] < 0) ++$mainCharacter[$i + 4];
        break;
      case "ELE223":
        if(GetClassState($mainPlayer, $CS_NumNonAttackCards) == 0 || GetClassState($mainPlayer, $CS_NumAttackCards) == 0) $mainCharacter[$i + 3] = 0;
        break;
      case "ELE224":
        if(GetClassState($mainPlayer, $CS_ArcaneDamageDealt) < $mainCharacter[$i + 2]) {
          DestroyCharacter($mainPlayer, $i);
          $mainCharacter[$i + 2] = 0;
        }
        break;
      default: break;
    }
  }
}

function MainCharacterHitAbilities()
{
  global $combatChain, $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  $attackID = $combatChain[0];
  $mainCharacter = &GetPlayerCharacter($mainPlayer);

  for($i = 0; $i < count($mainCharacter); $i += CharacterPieces()) {
    if(CardType($mainCharacter[$i]) == "W" || $mainCharacter[$i + 1] != "2") continue;
    $characterID = $mainCharacter[$i];
    if($i == 0 && $mainCharacter[0] == "CRU097") {
      $otherPlayer = ($mainPlayer == 1 ? 2 : 1);
      $otherCharacter = &GetPlayerCharacter($otherPlayer);
      if(SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $mainPlayer)) {
        $characterID = $otherCharacter[0];
      }
    }
    switch($characterID) {
      case "WTR076": case "WTR077":
        if(CardType($attackID) == "AA") {
          AddLayer("TRIGGER", $mainPlayer, $characterID);
          $mainCharacter[$i + 1] = 1;
        }
        break;
      case "WTR079":
        if(CardType($attackID) == "AA" && HitsInRow() >= 2) {
          AddLayer("TRIGGER", $mainPlayer, $characterID);
          $mainCharacter[$i + 1] = 1;
        }
        break;
      case "WTR113": case "WTR114":
        if($mainCharacter[$i + 1] == 2 && CardType($attackID) == "W" && $mainCharacter[$combatChainState[$CCS_WeaponIndex] + 1] != 0) {
          $mainCharacter[$i + 1] = 1;
          $mainCharacter[$combatChainState[$CCS_WeaponIndex] + 1] = 2;
          ++$mainCharacter[$combatChainState[$CCS_WeaponIndex] + 5];
        }
        break;
      case "WTR117":
        if(CardType($attackID) == "W" && IsCharacterActive($mainPlayer, $i)) {
          AddLayer("TRIGGER", $mainPlayer, $characterID);
        }
        break;
      case "ARC152":
        if(CardType($attackID) == "AA" && IsCharacterActive($mainPlayer, $i)) {
          AddLayer("TRIGGER", $mainPlayer, $characterID);
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
          AddLayer("TRIGGER", $mainPlayer, $characterID);
        }
        break;
      case "ELE062": case "ELE063":
        if(IsHeroAttackTarget() && CardType($attackID) == "AA" && !SearchAuras("ELE109", $mainPlayer)) {
          PlayAura("ELE109", $mainPlayer);
        }
        break;
      case "EVR037":
        if(CardType($attackID) == "AA" && IsCharacterActive($mainPlayer, $i)) {
          AddLayer("TRIGGER", $mainPlayer, $characterID);
        }
        break;
      default:
        break;
    }
  }
}

function MainCharacterAttackModifiers($index = -1, $onlyBuffs = false)
{
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer, $CS_NumAttacks;
  $modifier = 0;
  $mainCharacterEffects = &GetMainCharacterEffects($mainPlayer);
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  if($index == -1) $index = $combatChainState[$CCS_WeaponIndex];
  for($i = 0; $i < count($mainCharacterEffects); $i += CharacterEffectPieces()) {
    if($mainCharacterEffects[$i] == $index) {
      switch($mainCharacterEffects[$i + 1]) {
        case "WTR119": $modifier += 2; break;
        case "WTR122": $modifier += 1; break;
        case "WTR135": case "WTR136": case "WTR137": $modifier += 1; break;
        case "CRU079": case "CRU080": $modifier += 1; break;
        case "MON105": case "MON106": $modifier += 1; break;
        case "MON113": case "MON114": case "MON115": $modifier += 1; break;
        case "EVR055-1": $modifier += 1; break;
        default:
          break;
      }
    }
  }
  if($onlyBuffs) return $modifier;

  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  for($i = 0; $i < count($mainCharacter); $i += CharacterPieces()) {
    if(!IsEquipUsable($mainPlayer, $i)) continue;
    $characterID = $mainCharacter[$i];
    if($i == 0 && $mainCharacter[0] == "CRU097")
    {
      $otherPlayer = ($mainPlayer == 1 ? 2 : 1);
      $otherCharacter = &GetPlayerCharacter($otherPlayer);
      if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $mainPlayer)) {
        $characterID = $otherCharacter[0];
      }
    }
    switch($characterID) {
      case "MON029": case "MON030":
        if (HaveCharged($mainPlayer) && NumAttacksBlocking() > 0) $modifier += 1;
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
          MainDrawCard();
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

?>
