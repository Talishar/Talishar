<?php

function BanishCardForPlayer($cardID, $player, $from, $modifier="-")
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myBanish, $theirBanish, $mainBanish, $defBanish;
  global $myClassState, $theirClassState, $mainClassState, $defClassState;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) BanishCard($mainBanish, $mainClassState, $cardID, $modifier, $player, $from);
    else BanishCard($defBanish, $defClassState, $cardID, $modifier, $player, $from);
  }
  else
  {
    if($player == $myStateBuiltFor) BanishCard($myBanish, $myClassState, $cardID, $modifier, $player, $from);
    else BanishCard($theirBanish, $theirClassState, $cardID, $modifier, $player, $from);
  }
}

function BanishCard(&$banish, &$classState, $cardID, $modifier, $player="", $from="")
{
  global $CS_CardsBanished, $actionPoints, $CS_Num6PowBan, $currentPlayer;
  if($player == "") $player = $currentPlayer;
  WriteReplay($player, $cardID, $from, "BANISH");
  if(($modifier == "BOOST" || $from == "DECK") && ($cardID == "ARC176" || $cardID == "ARC177" || $cardID == "ARC178")) {
      WriteLog("Back Alley Breakline was banished from your deck face up by an action card. Gained 1 action point.");
      ++$actionPoints;
  }
  //Do effects that change where it goes, or banish it if not
  if($from == "DECK" && SearchCharacterActive($player, "CRU099") && CardCost($cardID) <= 2)
  {
    PutItemIntoPlay($cardID);
  }
  else
  {
    array_push($banish, $cardID);
    array_push($banish, $modifier);
  }
  ++$classState[$CS_CardsBanished];
  if(AttackValue($cardID) >= 6)
  {
    ++$classState[$CS_Num6PowBan];
    $index = FindCharacterIndex($player, "MON122");
    if($index >= 0 && IsEquipUsable($player, $index))
    {
       AddDecisionQueue("CHARREADYORPASS", $player, $index);
       AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Hooves_of_the_Shadowbeast_to_gain_an_action_point", 1);
       AddDecisionQueue("NOPASS", $player, "-", 1);
       AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
       AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);//Operates off last result
       AddDecisionQueue("GAINACTIONPOINTS", $player, 1, 1);
       return "Hooves of the Shadowbeast were destroyed to gain an action point.";
    }
  }
}

function AddBottomMainDeck($cardID, $from)
{
  global $mainPlayer;
  AddBottomDeck($cardID, $mainPlayer, $from);
}

function AddBottomMyDeck($cardID, $from)
{
  global $currentPlayer;
  AddBottomDeck($cardID, $currentPlayer, $from);
}

function AddBottomDeck($cardID, $player, $from)
{
  $deck = &GetDeck($player);
  WriteReplay($player, $cardID, $from, "BOTTOM");
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
  global $mainPlayer;
  AddPlayerHand($cardID, $mainPlayer, $from);
}

function AddPlayerHand($cardID, $player, $from)
{
  $hand = &GetHand($player);
  WriteReplay($player, $cardID, $from, "HAND");
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

function GainResources($player, $amount)
{
  $resources = &GetResources($player);
  $resources[0] += $amount;
}

function AddResourceCost($player, $amount)
{
  $resources = &GetResources($player);
  $resources[1] += $amount;
}

function AddArsenal($cardID, $player, $from, $facing)
{
  $arsenal = &GetArsenal($player);
  WriteReplay($player, $cardID, $from, "ARSENAL");
  array_push($arsenal, $cardID);
  array_push($arsenal, $facing);
  array_push($arsenal, ArsenalNumUsesPerTurn($cardID));//Num uses
  array_push($arsenal, "0");//Counters
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

function ArsenalEndTurn($player)
{
  $arsenal = &GetArsenal($player);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    $arsenal[$i+2] = ArsenalNumUsesPerTurn($arsenal[$i]);
  }
}

function SetArsenalFacing($facing, $player)
{
  $arsenal = &GetArsenal($player);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    if($facing == "UP" && $arsenal[$i+1] == "DOWN") { $arsenal[$i+1] = "UP"; return $arsenal[$i]; }
  }
}

function RemoveArsenal($player, $index)
{
  $arsenal = &GetArsenal($player);
  for($i=$index+ArsenalPieces()-1; $i >= $index; --$i)
  {
    unset($arsenal[$i]);
  }
  $arsenal = array_values($arsenal);
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
  WriteReplay($player, $cardID, $from, "SOUL");
  global $CS_NumAddedToSoul;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) AddSpecificSoul($cardID, $mainSoul, $from);
    else AddSpecificSoul($cardID, $defSoul, $from);
  }
  else
  {
    if($player == $myStateBuiltFor) AddSpecificSoul($cardID, $mySoul, $from);
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
  global $myStateBuiltFor;

  WriteReplay($player, $cardID, $from, "BANISH");
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) BanishFromSpecificSoul($mainSoul, $player);
    else BanishFromSpecificSoul($defSoul, $player);
  }
  else
  {
    if($player == $myStateBuiltFor) BanishFromSpecificSoul($mySoul, $player);
    else BanishFromSpecificSoul($theirSoul, $player);
  }
}

function BanishFromSpecificSoul(&$soul, $player)
{
  if(count($soul) == 0) return;
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
  WriteReplay($player, $cardID, "HAND", "PITCH");
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
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) $mainClassState[$piece] = $value;
    else $defClassState[$piece] = $value;
  }
  else
  {
    if($player == $myStateBuiltFor) $myClassState[$piece] = $value;
    else $theirClassState[$piece] = $value;
  }
}

function AddCharacterEffect($player, $index, $effect)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myCharacterEffects, $theirCharacterEffects, $mainCharacterEffects, $defCharacterEffects;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) { array_push($mainCharacterEffects, $index); array_push($mainCharacterEffects, $effect); }
    else { array_push($defCharacterEffects, $index); array_push($defCharacterEffects, $effect); }
  }
  else
  {
    if($player == $myStateBuiltFor) { array_push($myCharacterEffects, $index); array_push($myCharacterEffects, $effect); }
    else { array_push($theirCharacterEffects, $index); array_push($theirCharacterEffects, $effect); }
  }
}

function AddGraveyard($cardID, $player, $from)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myDiscard, $theirDiscard, $mainDiscard, $defDiscard;
  global $myStateBuiltFor;
  WriteReplay($player, $cardID, $from, "GRAVEYARD");
  if($cardID == "MON124") { BanishCardForPlayer($cardID, $player, $from, "NA"); return; }
  else if($cardID == "CRU007" && $from != "CC")
  {
    AddDecisionQueue("BEASTWITHIN", $player, "-");
  }
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) AddSpecificGraveyard($cardID, $mainDiscard, $from);
    else AddSpecificGraveyard($cardID, $defDiscard, $from);
  }
  else
  {
    if($player == $myStateBuiltFor) AddSpecificGraveyard($cardID, $myDiscard, $from);
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

function NegateLayer($index)
{
  global $layers;
  for($i=$index+LayerPieces(); $i >= $index; --$i)
  {
    unset($layers[$i]);
  }
  $layers = array_values($layers);
}

?>
