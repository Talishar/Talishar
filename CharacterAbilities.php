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


?>
