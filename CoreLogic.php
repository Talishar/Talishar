<?php

function EvaluateCombatChain(&$totalAttack, &$totalDefense)
{
  global $combatChain, $mainPlayer, $currentTurnEffects, $defCharacter;
  BuildMainPlayerGameState();
    for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
    {
      $from = $combatChain[$i+1];
      $resourcesPaid = $combatChain[$i+2];
      if($combatChain[$i] == $mainPlayer)
      {
        $totalAttack += AttackValue($combatChain[$i-1]) + AttackModifier($combatChain[$i-1], $combatChain[$i+1], $combatChain[$i+2], $combatChain[$i+3]) + $combatChain[$i + 4];
      }
      else
      {
        $defense = BlockValue($combatChain[$i-1]) + BlockModifier($combatChain[$i-1], $from, $resourcesPaid) + $combatChain[$i + 5];
        if(CardType($combatChain[$i-1]) == "E")
        {
          $index = FindDefCharacter($combatChain[$i-1]);
          $defense += $defCharacter[$index+4];
        }
        if($defense > 0) $totalDefense += $defense;
      }
    }

    //Now check current turn effects
    for($i=0; $i<count($currentTurnEffects); $i+=2)
    {
      if(IsCombatEffectActive($currentTurnEffects[$i]))
      {
        if($currentTurnEffects[$i+1] == $mainPlayer)
        {
          $totalAttack += EffectAttackModifier($currentTurnEffects[$i]);
        }
        else
        {
          $totalDefense += EffectBlockModifier($currentTurnEffects[$i], "", 0);
        }
      }
    }

    $totalAttack += MainCharacterAttackModifiers();
}

function DamagePlayer($damage, &$classState, &$health)
{
  global $CS_DamagePrevention;
  $damage = $damage > 0 ? $damage : 0;
  if($damage <= $classState[$CS_DamagePrevention])
  {
    $classState[$CS_DamagePrevention] -= $damage;
    $damage = 0;
  }
  else
  {
    $damage -= $classState[$CS_DamagePrevention];
    $classState[$CS_DamagePrevention] = 0;
  }
  PlayerLoseHealth($damage, $health);
  return $damage;
}

function PlayerLoseHealth($amount, &$health)
{
  global $mainPlayer;
  $health -= $amount;
  if($health <= 0)
  {
    PlayerWon($mainPlayer);
  }
}

function PlayerWon($playerID)
{
  global $winner;
  $winner = $playerID;
  WriteLog("Player " . $playerID . " wins!");
}

function UnsetChainLinkBanish()
{
  global $mainBanish;
  for($i=0; $i<count($mainBanish); $i+=BanishPieces())
  {
    if($mainBanish[$i+1] == "TCL") $mainBanish[$i+1] = "DECK";
  }
}

function UnsetCombatChainBanish()
{
  global $mainBanish;
  for($i=0; $i<count($mainBanish); $i+=BanishPieces())
  {
    if($mainBanish[$i+1] == "TCC") $mainBanish[$i+1] = "DECK";
  }
}

function UnsetMyCombatChainBanish()
{
  global $myBanish;
  for($i=0; $i<count($myBanish); $i+=BanishPieces())
  {
    if($myBanish[$i+1] == "TCC") $myBanish[$i+1] = "DECK";
  }
}

function UnsetTurnBanish()
{
  global $mainBanish;
  for($i=0; $i<count($mainBanish); $i+=BanishPieces())
  {
    if($mainBanish[$i+1] == "TT") $mainBanish[$i+1] = "DECK";
  }
  UnsetCombatChainBanish();
}

function GetChainLinkCards($playerID="", $cardType="")
{
  global $combatChain;
  $pieces = "";
  for($i=0; $i<count($combatChain); $i+=CombatChainPieces())
  {
    if(($playerID == "" || $combatChain[$i+1] == $playerID) && ($cardType == "" || CardType($combatChain[$i]) == $cardType))
    {
      if($pieces != "") $pieces .= ",";
      $pieces .= $i;
    }
  }
  return $pieces;
}

function GetMainPlayerComboCards()
{
  global $mainDeck;
  $combo = "";
  for($i=0; $i<count($mainDeck); ++$i)
  {
    if(HasCombo($mainDeck[$i]))
    {
      if($combo != "") $combo .= ",";
      $combo .= $i;
    }
  }
  return $combo;
}

function GetComboCards()
{
  global $myDeck, $mainDeck, $mainPlayerGamestateBuilt;
  $deck = $mainPlayerGamestateBuilt ? $mainDeck : $myDeck;
  $combo = "";
  for($i=0; $i<count($deck); ++$i)
  {
    if(HasCombo($deck[$i]))
    {
      if($combo != "") $combo .= ",";
      $combo .= $i;
    }
  }
  return $combo;
}

function GetWeaponChoices()
{
  global $myCharacter;
  $weapons = "";
  for($i=0; $i<count($myCharacter); $i+=CharacterPieces())
  {
    if(CardType($myCharacter[$i]) == "W")
    {
      if($weapons != "") $weapons .= ",";
      $weapons .= $i;
    }
  }
  return $weapons;
}

function GetTheirEquipmentChoices()
{
  global $theirCharacter;
  $equipment = "";
  for($i=0; $i<count($theirCharacter); $i+=CharacterPieces())
  {
    if(CardType($theirCharacter[$i]) == "E" && $theirCharacter[$i+1] != 0)
    {
      if($equipment != "") $equipment .= ",";
      $equipment .= $i;
    }
  }
  return $equipment;
}

function ApplyEffectToEachWeapon($effectID)
{
  global $myCharacter;
  for($i=0; $i<count($myCharacter); $i+=CharacterPieces())
  {
    if(CardType($myCharacter[$i]) == "W")
    {
      AddCharacterEffect($i, $effectID);
    }
  }
}

function AddCharacterEffect($index, $effectID)
{
  global $myCharacterEffects;
  array_push($myCharacterEffects, $index);
  array_push($myCharacterEffects, $effectID);
}

function FindMyCharacter($cardID)
{
  global $myCharacter;
  for($i=0; $i<count($myCharacter); $i+=CharacterPieces())
  {
    if($myCharacter[$i] == $cardID)
    {
      return $i;
    }
  }
  return -1;
}

function FindDefCharacter($cardID)
{
  global $defCharacter;
  for($i=0; $i<count($defCharacter); $i+=CharacterPieces())
  {
    if($defCharacter[$i] == $cardID)
    {
      return $i;
    }
  }
  return -1;
}

function CheckDestroyTemper()
{
  global $defCharacter;
  for($i = count($defCharacter)-CharacterPieces(); $i >= 0; $i -= CharacterPieces())
  {
    if(HasTemper($defCharacter[$i]) && ((BlockValue($defCharacter[$i]) + $defCharacter[$i + 4]) <= 0))
    {
      $defCharacter[$i+1] = 0;
    }
  }
}

function NumBlockedFromHand()
{
  global $combatChain, $defPlayer;
  $num = 0;
  for($i=0; $i<count($combatChain); $i += CombatChainPieces())
  {
    if($combatChain[$i+1] == $defPlayer)
    {
      $type = CardType($combatChain[$i]);
      if($type != "I" && $combatChain[$i+2] == "HAND") ++$num;
    }
  }
  return $num;
}

function NumCardsBlocking()
{
  global $combatChain, $defPlayer;
  $num = 0;
  for($i=0; $i<count($combatChain); $i += CombatChainPieces())
  {
    if($combatChain[$i+1] == $defPlayer)
    {
      $type = CardType($combatChain[$i]);
      if($type != "E" && $type != "I") ++$num;
    }
  }
  return $num;
}

function IHaveLessHealth()
{
  global $myHealth, $theirHealth;
  return $myHealth < $theirHealth;
}

function DefHasLessHealth()
{
  global $mainHealth, $defHealth;
  return $defHealth < $mainHealth;
}

function GetIndices($count)
{
  $indices = "";
  for($i=0; $i<$count; ++$i)
  {
    if($indices != "") $indices .= ",";
    $indices .= $i;
  }
  return $indices;
}

function GetMyHandIndices()
{
  global $myHand;
  return GetIndices(count($myHand));
}

function GetDefHandIndices()
{
  global $defHand;
  return GetIndices(count($defHand));
}

function PlayMyAura($cardID)
{
  global $myAuras;
  array_push($myAuras, $cardID);
  array_push($myAuras, 0);
}

function RollDie()
{
  return random_int(1, 6);
}

?>

