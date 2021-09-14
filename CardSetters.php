<?php

function BanishCardForPlayer($cardID, $player, $from, $modifier)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myBanish, $theirBanish, $mainBanish, $defBanish;
  global $myClassState, $theirClassState, $mainClassState, $defClassState;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) BanishCard($mainBanish, $mainClassState, $cardID, $modifier);
    else BanishCard($defBanish, $defClassState, $cardID, $modifier);
  }
  else
  {
    if($player == $currentPlayer) BanishCard($myBanish, $myClassState, $cardID, $modifier);
    else BanishCard($theirBanish, $theirClassState, $cardID, $modifier);
  }
}

function BanishCard(&$banish, &$classState, $cardID, $modifier)
{
  global $CS_CardsBanished, $actionPoints;
  if($modifier == "BOOST" && ($cardID == "ARC176" || $cardID == "ARC177" || $cardID == "ARC178")) {
      WriteLog("Back Alley Breakline was banished from your deck face up by an action card. Gained 1 action point.");
      ++$actionPoints;
    }
  array_push($banish, $cardID);
  array_push($banish, $modifier);
  ++$classState[$CS_CardsBanished];
}

function AddBottomMainDeck($cardID, $from)
{
  global $mainDeck;
  array_push($mainDeck, $cardID);
}

function AddBottomMyDeck($cardID, $from)
{
  global $myDeck;
  array_push($myDeck, $cardID);
}

function AddBottomDeck($cardID, $player, $from)
{
  $deck = &GetDeck($player);
  array_push($deck, $cardID);
}

function RemoveTopMyDeck()
{
  global $myDeck;
  if(count($myDeck) == 0) return "";
  return array_shift($myDeck);
}

function AddMainHand($cardID, $from)
{
  global $mainHand;
  array_push($mainHand, $cardID);
}

function AddPlayerHand($cardID, $player, $from)
{
  $hand = &GetHand($player);
  array_push($hand, $cardID);
}

function RemoveHand($cardID, $player)
{
  $hand = &GetHand($player);
  for($i=count($hand)-HandPieces(); $i>=0; $i-=HandPieces())
  {
    if($hand[$i] == $cardID)
    {
      for($j = $i+HandPieces()-1; $j >= $i; --$j)
      {
        unset($hand[$j]);
      }
      $hand = array_values($hand);
      break;
    }
  }
}

function AddArsenal($cardID, $player, $from, $facing)
{
  $arsenal = &GetArsenal($player);
  array_push($arsenal, $cardID);
  array_push($arsenal, $facing);
  $otherPlayer = $player == 1 ? 2 : 1;
  if($facing == "UP")
  {
    if($from == "DECK" && ($cardID == "ARC176" || $cardID == "ARC177" || $cardID == "ARC178")) {
      WriteLog("Back Alley Breakline was put into your arsenal from your deck face up. Gained 1 action point.");
      ++$actionPoints;
    }
    switch($cardID)
    {
      case "ARC057": case "ARC058": case "ARC059": AddCurrentTurnEffect($cardID, $player); break;
      case "ARC063": case "ARC064": case "ARC065": Opt($cardID, 1); break;
      case "CRU123": AddCurrentTurnEffect($cardID, $otherPlayer); break;
      default: break;
    }
  }
}

function AddMyArsenal($cardID, $from, $facing)
{
  global $currentPlayer;
  AddArsenal($cardID, $currentPlayer, $from, $facing);
}

function SetArsenalFacing($facing, $player)
{
  $arsenal = &GetArsenal($player);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    if(facing == "UP" && $arsenal[$i+1] == "DOWN") { $arsenal[$i+1] = "UP"; return $arsenal[$i]; }
  }
}

//Deprecated -- do not use
function GetMyArsenalFacing()
{
  global $myClassState, $CS_ArsenalFacing;
  WriteLog("Deprecated function GetArsenalFacing called. Please submit report.");
  return $myClassState[$CS_ArsenalFacing];
}

function SetCCAttackModifier($index, $amount)
{
  global $combatChain;
  $combatChain[$index + 5] += $amount;
}

function AddSoul($cardID, $player, $from)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mySoul, $theirSoul, $mainSoul, $defSoul;
  global $CS_NumAddedToSoul;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) AddSpecificSoul($cardID, $mainSoul, $from);
    else AddSpecificSoul($cardID, $defSoul, $from);
  }
  else
  {
    if($player == $currentPlayer) AddSpecificSoul($cardID, $mySoul, $from);
    else AddSpecificSoul($cardID, $theirSoul, $from);
  }
  IncrementClassState($player, $CS_NumAddedToSoul);
}

function AddSpecificSoul($cardID, &$soul, $from)
{
  array_push($soul, $cardID);
}

function BanishFromSoul($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mySoul, $theirSoul, $mainSoul, $defSoul;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) BanishFromSpecificSoul($mainSoul, $player);
    else BanishFromSpecificSoul($defSoul, $player);
  }
  else
  {
    if($player == $currentPlayer) BanishFromSpecificSoul($mySoul, $player);
    else BanishFromSpecificSoul($theirSoul, $player);
  }
}

function BanishFromSpecificSoul(&$soul, $player)
{
  $cardID = array_shift($soul);
  BanishCardForPlayer($cardID, $player, "SOUL", "SOUL");
}

function AddArcaneBonus($bonus, $player)
{
  global $CS_NextArcaneBonus;
  $newBonus = GetClassState($player, $CS_NextArcaneBonus) + $bonus;
  SetClassState($player, $CS_NextArcaneBonus, $newBonus);
}

function ConsumeArcaneBonus($player)
{
  global $CS_NextArcaneBonus;
  $bonus = GetClassState($player, $CS_NextArcaneBonus);
  SetClassState($player, $CS_NextArcaneBonus, 0);
  return $bonus;
}

function ConsumeDamagePrevention($player)
{
  global $CS_NextDamagePrevented;
  $prevention = GetClassState($player, $CS_NextDamagePrevented);
  SetClassState($player,$CS_NextDamagePrevented, 0);
  return $prevention;
}

function AddThisCardPitch($player, $cardID)
{
  global $CS_PitchedForThisCard;
  $pitch = GetClassState($player, $CS_PitchedForThisCard);
  if($pitch == "-") SetClassState($player, $CS_PitchedForThisCard, $cardID);
  else SetClassState($player, $CS_PitchedForThisCard, $pitch . "-" . $cardID);
}

function ResetThisCardPitch($player)
{
  global $CS_PitchedForThisCard;
  SetClassState($player, $CS_PitchedForThisCard, "-");
}

function IncrementClassState($player, $piece, $amount=1)
{
  SetClassState($player, $piece, (GetClassState($player, $piece) + $amount));
}

function SetClassState($player, $piece, $value)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myClassState, $theirClassState, $mainClassState, $defClassState;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) $mainClassState[$piece] = $value;
    else $defClassState[$piece] = $value;
  }
  else
  {
    if($player == $currentPlayer) $myClassState[$piece] = $value;
    else $theirClassState[$piece] = $value;
  }
}

function AddCharacterEffect($player, $index, $effect)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myCharacterEffects, $theirCharacterEffects, $mainCharacterEffects, $defCharacterEffects;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) { array_push($mainCharacterEffects, $index); array_push($mainCharacterEffects, $effect); }
    else { array_push($defCharacterEffects, $index); array_push($defCharacterEffects, $effect); }
  }
  else
  {
    if($player == $currentPlayer) { array_push($myCharacterEffects, $index); array_push($myCharacterEffects, $effect); }
    else { array_push($theirCharacterEffects, $index); array_push($theirCharacterEffects, $effect); }
  }
}

function AddGraveyard($cardID, $player, $from)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myDiscard, $theirDiscard, $mainDiscard, $defDiscard;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) AddSpecificGraveyard($cardID, $mainDiscard, $from);
    else AddSpecificGraveyard($cardID, $defDiscard, $from);
  }
  else
  {
    if($player == $currentPlayer) AddSpecificGraveyard($cardID, $myDiscard, $from);
    else AddSpecificGraveyard($cardID, $theirDiscard, $from);
  }
}

function SearchCharacterAddUses($player, $uses, $type="", $subtype="")
{
  $character = &GetPlayerCharacter($player);
  for($i=0; $i<count($character); $i+=CharacterPieces())
  {
    if($character[$i+1] != 0 && ($type == "" || CardType($character[$i]) == $type) && ($subtype == "" || $subtype == CardSubtype($character[$i])))
    {
      $character[$i+1] = 2;
      $character[$i+5] += $uses;
    }
  }
}

function SearchCharacterAddEffect($player, $effect, $type="", $subtype="")
{
  $character = &GetPlayerCharacter($player);
  for($i=0; $i<count($character); $i+=CharacterPieces())
  {
    if($character[$i+1] != 0 && ($type == "" || CardType($character[$i]) == $type) && ($subtype == "" || $subtype == CardSubtype($character[$i])))
    {
      AddCharacterEffect($player, $i, $effect);
    }
  }
}

function RemoveCharacterEffects($player, $index, $effect)
{
  $effects = &GetCharacterEffects($player);
  for($i=count($effects) - CharacterEffectPieces(); $i>=0; $i-=CharacterEffectPieces())
  {
    if($effects[$i] == $index && $effects[$i+1] == $effect)
    {
      unset($effects[$i+1]);
      unset($effects[$i]);
    }
  }
  $effects = array_values($effects);
  return false;
}

function AddSpecificGraveyard($cardID, &$graveyard, $from)
{
  array_push($graveyard, $cardID);
}

?>

