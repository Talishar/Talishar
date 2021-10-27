<?php

  include "CardSetters.php";
  include "CardGetters.php";

function EvaluateCombatChain(&$totalAttack, &$totalDefense)
{
  global $combatChain, $mainPlayer, $currentTurnEffects, $defCharacter, $playerID, $combatChainState, $CCS_ChainAttackBuff;
    UpdateGameState($playerID);
    BuildMainPlayerGameState();
    $attackType = CardType($combatChain[0]);
    $CanGainAttack = !SearchCurrentTurnEffects("CRU035", $mainPlayer) || $attackType != "AA";
    $SnagActive = SearchCurrentTurnEffects("CRU182", $mainPlayer) && $attackType == "AA";
    for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
    {
      $from = $combatChain[$i+1];
      $resourcesPaid = $combatChain[$i+2];

      if($combatChain[$i] == $mainPlayer)
      {
        $attack = AttackValue($combatChain[$i-1]);
        if($CanGainAttack || $i == 1 || $attack < 0) $totalAttack += $attack;
        $attack = AttackModifier($combatChain[$i-1], $combatChain[$i+1], $combatChain[$i+2], $combatChain[$i+3]) + $combatChain[$i + 4];
        if(($CanGainAttack && !$SnagActive) || $attack < 0) $totalAttack += $attack;
      }
      else
      {
        $defense = BlockValue($combatChain[$i-1]) + BlockModifier($combatChain[$i-1], $from, $resourcesPaid) + $combatChain[$i + 5];
        if(CardType($combatChain[$i-1]) == "E")
        {
          $index = FindDefCharacter($combatChain[$i-1]);
          $defense += $defCharacter[$index+4] + DefCharacterBlockModifier($index);
        }
        if($defense > 0) $totalDefense += $defense;
      }
    }

    //Now check current turn effects
    for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnPieces())
    {
      if(IsCombatEffectActive($currentTurnEffects[$i]))
      {
        if($currentTurnEffects[$i+1] == $mainPlayer)
        {
          $attack = EffectAttackModifier($currentTurnEffects[$i]);
          if($CanGainAttack || $attack < 0) $totalAttack += $attack;
        }
        else
        {
          $totalDefense += EffectBlockModifier($currentTurnEffects[$i], "", 0);
        }
      }
    }

    $attack = MainCharacterAttackModifiers();//TODO: If there are both negatives and positives here, this might mess up?...
    if($CanGainAttack || $attack < 0) $totalAttack += $attack;
    $attack = AuraAttackModifiers(0);//TODO: If there are both negatives and positives here, this might mess up?...
    if($CanGainAttack || $attack < 0) $totalAttack += $attack;
    $attack = $combatChainState[$CCS_ChainAttackBuff];
    if($CanGainAttack || $attack < 0) $totalAttack += $attack;
}

function HasIncreasedAttack()
{
  global $combatChain;
  if(count($combatChain) > 0)
  {
    $attack = 0;
    $defense = 0;
    EvaluateCombatChain($attack, $defense);
    if($attack > AttackValue($combatChain[0])) return true;
  }
  return false;
}

function DamageOtherPlayer($amount, $type="DAMAGE")
{
  global $otherPlayer;
  return DealDamage($otherPlayer, $amount, $type);
}

function DealDamage($player, $damage, $type, $source="NA")
{
  $classState = &GetPlayerClassState($player);
  $health = &GetHealth($player);
  $auras = &GetAuras($player);
  $items = &GetItems($player);
  return DamagePlayer($player, $damage, $classState, $health, $auras, $items, $type, $source);
}

function DamagePlayer($playerID, $damage, &$classState, &$health, &$Auras, &$Items, $type="DAMAGE", $source="NA")
{
  global $CS_DamagePrevention, $CS_DamageTaken, $CS_ArcaneDamageTaken, $combatChainState, $CCS_AttackTotalDamage, $defPlayer, $combatChain;
  global $CCS_AttackFused;
  if($type == "COMBAT" || $type == "ATTACKHIT") $source = $combatChain[0];
  $otherPlayer = $playerID == 1 ? 2 : 1;
  $damage = $damage > 0 ? $damage : 0;
  $damageThreatened = $damage;
  if(ConsumeDamagePrevention($playerID)) return 0;//If damage can be prevented outright, don't use up your limited damage prevention
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
  $damage -= CurrentEffectDamagePrevention($playerID, $type, $damage);
  for($i=count($Items) - ItemPieces(); $i >= 0 && $damage > 0; $i -= ItemPieces())
  {
    if($Items[$i] == "CRU104")
    {
      if($damage > $Items[$i+1]) { $damage -= $Items[$i+1]; $Items[$i+1] = 0; }
      else { $Items[$i+1] -= $damage; $damage = 0; }
      if($Items[$i+1] <= 0) DestroyItem($Items, $i);
    }
  }
  $damage = $damage > 0 ? $damage : 0;
  $damage = AuraTakeDamageAbilities($playerID, $damage);
  if($damage > 0 && ($type == "COMBAT" || $type == "ATTACKHIT" || ($source != "NA" && CardType($source) == "AA")))
  {
    $damage += CurrentEffectDamageModifiers($source);
    $otherCharacter = &GetPlayerCharacter($otherPlayer);
    if($otherCharacter[0] == "ELE062" || $otherCharacter[0] == "ELE063") PlayAura("ELE109", $otherPlayer);
    if(($source == "ELE067" || $source == "ELE068" || $source == "ELE069") && $combatChainState[$CCS_AttackFused])
    { AddCurrentTurnEffect($source, $otherPlayer); }
    AuraDamageTakenAbilities($Auras, $damage);
  }
  if($damage > 0)
  {
    if(SearchAuras("MON013", $otherPlayer)) { LoseHealth(1, $playerID); WriteLog("Lost 1 health from Ode to Wrath."); }
    $classState[$CS_DamageTaken] += $damage;
    if($playerID == $defPlayer && $type == "COMBAT" || $type == "ATTACKHIT") $combatChainState[$CCS_AttackTotalDamage] += $damage;
    if($source == "MON229") AddNextTurnEffect("MON229", $playerID);
    if($type == "ARCANE") $classState[$CS_ArcaneDamageTaken] += $damage;
    CurrentEffectDamageEffects($playerID);
  }
  if($damage > 0 && ($type == "COMBAT" || $type == "ATTACKHIT") && SearchCurrentTurnEffects("ELE037-2", $otherPlayer))
  { for($i=0; $i<$damage; ++$i) PlayAura("ELE111", $playerID); }
  PlayerLoseHealth($damage, $health);
  LogDamageStats($playerID, $damageThreatened, $damage);
  return $damage;
}

function CurrentEffectDamageModifiers($source)
{
  global $currentTurnEffects;
  $modifier = 0;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i >= 0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    switch($currentTurnEffects[$i])
    {
      case "ELE186": case "ELE187": case "ELE188": if(TalentContains($source, "LIGHTNING") || TalentContains($source, "ELEMENTAL")) ++$modifier; break;
      default: break;
    }
    if($remove == 1)
    {
      unset($currentTurnEffects[$i+1]);
      unset($currentTurnEffects[$i]);
    }
  }
  return $modifier;
}

function CurrentEffectDamageEffects($player)
{
  global $currentTurnEffects;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i >= 0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    switch($currentTurnEffects[$i])
    {
      case "ELE064": BlossomingSpellbladeDamageEffect($player); break;
      default: break;
    }
    if($remove == 1)
    {
      unset($currentTurnEffects[$i+1]);
      unset($currentTurnEffects[$i]);
    }
  }
}

function AttackDamageAbilities()
{
  global $combatChain, $defPlayer, $combatChainState, $CCS_AttackTotalDamage;
  $attackID = $combatChain[0];
  switch($attackID)
  {
    case "ELE036":
      if($combatChainState[$CCS_AttackTotalDamage] >= NumEquipment($defPlayer))
      { AddCurrentTurnEffect("ELE036", $defPlayer); AddNextTurnEffect("ELE036", $defPlayer); }
      break;
    default: break;
  }
}

function LoseHealth($amount, $player)
{
  $health = &GetHealth($player);
  PlayerLoseHealth($amount, $health);
}

function GainHealth($amount, $player)
{
  $otherPlayer = ($player == 1 ? 2 : 1);
  $health = &GetHealth($player);
  $otherHealth = &GetHealth($otherPlayer);
  if(SearchCurrentTurnEffects("MON229", $player)) { WriteLog("Dread Scythe prevented you from gaining health."); return; }
  if(SearchCharacterForCard($player, "CRU140") || SearchCharacterForCard($otherPlayer, "CRU140"))
  {
    if($health > $otherHealth) return;
  }
  PlayerGainHealth($amount, $health);
}

function PlayerLoseHealth($amount, &$health)
{
  global $mainPlayer;
  $amount = AuraLoseHealthAbilities($mainPlayer, $amount);
  $health -= $amount;
  if($health <= 0)
  {
    PlayerWon($mainPlayer);
  }
}

function PlayerGainHealth($amount, &$health)
{
  $health += $amount;
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
  global $mainBanish, $defBanish;
  for($i=0; $i<count($mainBanish); $i+=BanishPieces())
  {
    if($mainBanish[$i+1] == "TT") $mainBanish[$i+1] = "DECK";
    if($mainBanish[$i+1] == "INST") $mainBanish[$i+1] = "DECK";
  }
  for($i=0; $i<count($defBanish); $i+=BanishPieces())
  {
    if($defBanish[$i+1] == "TT") $defBanish[$i+1] = "DECK";
    if($defBanish[$i+1] == "INST") $defBanish[$i+1] = "DECK";
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
  global $mainPlayer;
  $deck = &GetDeck($mainPlayer);
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

function GetWeaponChoices($subtype="")
{
  global $myCharacter;
  $weapons = "";
  for($i=0; $i<count($myCharacter); $i+=CharacterPieces())
  {
    if(CardType($myCharacter[$i]) == "W" && ($subtype == "" || $subtype == CardSubtype($myCharacter[$i])))
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
  global $myCharacter, $currentPlayer;
  for($i=0; $i<count($myCharacter); $i+=CharacterPieces())
  {
    if(CardType($myCharacter[$i]) == "W")
    {
      AddCharacterEffect($currentPlayer, $i, $effectID);
    }
  }
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

function DestroyItem(&$Items, $index)
{
  unset($Items[$index]);
  unset($Items[$index+1]);
  unset($Items[$index+2]);
  $Items = array_values($Items);
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

function NumAttacksBlocking()
{
  global $combatChain, $defPlayer;
  $num = 0;
  for($i=0; $i<count($combatChain); $i += CombatChainPieces())
  {
    if($combatChain[$i+1] == $defPlayer)
    {
      if(CardType($combatChain[$i]) == "AA") ++$num;
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

function PlayerHasLessHealth($playerID)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mainHealth, $defHealth, $myHealth, $theirHealth;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainHealth < $defHealth;
    else return $defHealth < $mainHealth;
  }
  else
  {
    if($player == $currentPlayer) return $myHealth < $theirHealth;
    else return $theirHealth < $myHealth;
  }
}

function GetIndices($count, $add=0, $pieces=1)
{
  $indices = "";
  for($i=0; $i<$count; $i+=$pieces)
  {
    if($indices != "") $indices .= ",";
    $indices .= ($i + $add);
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

function PlayAura($cardID, $player, $number=1)
{
  $auras = &GetAuras($player);
  if($cardID == "ARC112" && SearchCurrentTurnEffects("ARC081", $player)) ++$number;
  for($i=0; $i<$number; ++$i)
  {
    array_push($auras, $cardID);
    array_push($auras, 2);
  }
}

function PlayMyAura($cardID)
{
  global $myAuras;
  array_push($myAuras, $cardID);
  array_push($myAuras, 2);
}

function PlayTheirAura($cardID)
{
  global $theirAuras;
  array_push($theirAuras, $cardID);
  array_push($theirAuras, 2);
}

function RollDie()
{
  return random_int(1, 6);
}

function CanPlayAsInstant($cardID, $index=-1)
{
  global $currentPlayer, $CS_NextWizardNAAInstant, $CS_NextNAAInstant, $CS_CharacterIndex, $CS_ArcaneDamageTaken;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  $cardType = CardType($cardID);
  if(GetClassState($currentPlayer, $CS_NextWizardNAAInstant))
  {
    if(CardClass($cardID) == "WIZARD" && $cardType == "A") return true;
  }
  if(GetClassState($currentPlayer, $CS_NextNAAInstant))
  {
    if($cardType == "A") return true;
  }
  if($cardType == "C" || $cardType == "E" || $cardType == "W")
  {
    if($index == -1) $index = GetClassState($currentPlayer, $CS_CharacterIndex);
    if(SearchCharacterEffects($currentPlayer, $index, "INSTANT")) return true;
  }
  if($cardID == "ELE106" || $cardID == "ELE107" || $cardID == "ELE108") { return PlayerHasFused($currentPlayer); }
  if($cardID == "CRU143") { return GetClassState($otherPlayer, $CS_ArcaneDamageTaken) > 0; }
  return false;
}

function TalentContains($cardID, $talent)
{
  $cardTalent = CardTalent($cardID);
  $talents = explode(" ", $cardTalent);
  for($i=0; $i<count($talents); ++$i)
  {
    if($talents[$i] == $talent) return true;
  }
  return false;
}

function RevealCards($cards)
{
  $cardArray = explode(",", $cards);
  $string = "";
  for($i=0; $i<count($cardArray); ++$i)
  {
    if($string != "") $string .= ", ";
    $string .= CardLink($cardArray[$i], $cardArray[$i]);
  }
  $string .= (count($cardArray) == 1 ? " is" : " are");
  $string .= " revealed.";
  WriteLog($string);
}

function AuraDestroyed($player, $cardID)
{
  if(CardType($cardID) == "T") return;//Don't need to add to anywhere if it's a token
  $goesWhere = GoesWhereAfterResolving($cardID);
  if(SearchAuras("MON012", $player))
  {
    $goesWhere = "SOUL";
    DealArcane(1, 0, "STATIC", "MON012", false, $player);
  }
  switch($goesWhere)
  {
    case "GY": AddGraveyard($cardID, $player, "PLAY"); break;
    case "SOUL": AddSoul($cardID, $player, "PLAY"); break;
    case "BANISH": BanishCardForPlayer($cardID, $player, "PLAY", "NA"); break;
    default: break;
  }
}

function DoesAttackHaveGoAgain()
{
  global $combatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $mainPlayer;
  if(count($combatChain) == 0) return false;//No combat chain, so no
  $attackType = CardType($combatChain[0]);
  if(CurrentEffectPreventsGoAgain()) return false;
  if(HasGoAgain($combatChain[0]) || $combatChainState[$CCS_CurrentAttackGainedGoAgain] == 1 || CurrentEffectGrantsGoAgain()) return true;
  if(CardClass($combatChain[0]) == "ILLUSIONIST")
  {
    if(SearchCharacterForCard($mainPlayer, "MON003") && SearchPitchForColor($mainPlayer, 2) > 0) return true;
    if($attackType == "AA" && SearchAuras("MON013", $mainPlayer)) return true;
  }
  return false;
}

function IsEquipUsable($player, $index)
{
  $character = &GetPlayerCharacter($player);
  if($index >= count($character) || $index < 0) return false;
  return $character[$index + 1] == 2;
}

function DestroyCurrentWeapon()
{
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  $index = $combatChainState[$CCS_WeaponIndex];
  DestroyCharacter($mainPlayer, $index);
}

function AttackDestroyed($attackID)
{
  global $mainPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
  if(SearchAuras("MON012", $mainPlayer))
  {
    $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL";
    DealArcane(1, 0, "STATIC", "MON012", false, $mainPlayer);
  }
}

function CloseCombatChain()
{
  global $turn, $currentPlayer, $mainPlayer;
  AddDecisionQueue("FINALIZECHAINLINK", $mainPlayer, "true");
  $turn[0] = "M";
  $currentPlayer = $mainPlayer;
}

function DestroyCharacter($player, $index)
{
  $char = &GetPlayerCharacter($player);
  $char[$index+1] = 0;
}

?>

