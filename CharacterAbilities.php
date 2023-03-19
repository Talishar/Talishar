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

function CharacterDamageTakenAbilities($player, $damage)
{
  $char = &GetPlayerCharacter($player);
  $otherPlayer = $player == 1 ? 1 : 2;
  for ($i = count($char) - CharacterPieces(); $i >= 0; $i -= CharacterPieces())
  {
    switch ($char[$i]) {
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

function CharacterDealDamageAbilities($player, $damage)
{
  $char = &GetPlayerCharacter($player);
  $otherPlayer = $player == 1 ? 1 : 2;
  for ($i = count($char) - CharacterPieces(); $i >= 0; $i -= CharacterPieces())
  {
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
      default:
        break;
    }
  }
}

?>
