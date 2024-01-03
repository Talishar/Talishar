<?php

function PlayAlly($cardID, $player, $subCards = "-")
{
  $allies = &GetAllies($player);
  array_push($allies, $cardID);
  array_push($allies, 2);
  array_push($allies, AllyHealth($cardID));
  array_push($allies, 0); //Frozen
  array_push($allies, $subCards); //Subcards
  array_push($allies, GetUniqueId()); //Unique ID
  array_push($allies, AllyEnduranceCounters($cardID)); //Endurance Counters
  array_push($allies, 0); //Life Counters
  array_push($allies, 1); //Ability/effect uses
  array_push($allies, 0); //Attack Counters
  if($cardID == "UPR414") {
    WriteLog(CardLink($cardID, $cardID) . " lets you transform up to 1 ash into an Ashwing.");
    Transform($player, "Ash", "UPR042", true);
  }
  return count($allies) - AllyPieces();
}

function DestroyAlly($player, $index, $skipDestroy = false, $fromCombat = false)
{
  global $mainPlayer;
  $allies = &GetAllies($player);
  if(!$skipDestroy) AllyDestroyedAbility($player, $index);
  if(IsSpecificAllyAttacking($player, $index) || (IsSpecificAllyAttackTarget($player, $index) && !$fromCombat)) {
    CloseCombatChain();
  }
  $cardID = $allies[$index];
  AllyAddGraveyard($player, $cardID);
  AllyAddGraveyard($player, $allies[$index+4]);
  for($j = $index + AllyPieces() - 1; $j >= $index; --$j) unset($allies[$j]);
  $allies = array_values($allies);
  return $cardID;
}

function AllyAddGraveyard($player, $cardID)
{
  if(CardType($cardID) != "T") {
    $set = substr($cardID, 0, 3);
    $number = intval(substr($cardID, 3, 3));
    $number -= 400;
    if($number < 0) return;
    $id = $number;
    if($number < 100) $id = "0" . $id;
    if($number < 10) $id = "0" . $id;
    $id = $set . $id;
    if(!SubtypeContains($id, "Invocation", $player) && !SubtypeContains($id, "Figment", $player) && !SubtypeContains($id, "Ash", $player)) return;
    AddGraveyard($id, $player, "PLAY");
  }
}

function AllyHealth($cardID)
{
  switch($cardID) {
    case "MON219": return 6;
    case "MON220": return 6;
    case "UPR406": return 6;
    case "UPR407": return 5;
    case "UPR408": return 4;
    case "UPR409": return 3;
    case "UPR410": return 2;
    case "UPR411": return 2;
    case "UPR412": return 4;
    case "UPR413": return 7;
    case "UPR414": return 6;
    case "UPR415": return 4;
    case "UPR416": return 1;
    case "UPR417": return 3;
    case "DYN612": return 4;
    case "DTD193": return 6;
    case "DTD405": return 4;
    case "DTD406": return 4;
    case "DTD407": return 4;
    case "DTD408": return 4;
    case "DTD409": return 4;
    case "DTD410": return 4;
    case "DTD411": return 4;
    case "DTD412": return 4;
    case "HVY134": return 2;
    default: return 1;
  }
}

function AllyDestroyedAbility($player, $index)
{
  global $mainPlayer;
  $allies = &GetAllies($player);
  $cardID = $allies[$index];
  switch($cardID) {
    case "UPR410":
      if($player == $mainPlayer && $allies[$index+8] > 0) {
        GainActionPoints(1, $player);
        --$allies[$index+8];
      }
      break;
    case "UPR551":
      $charIndex = FindCharacterIndex($player, "UPR151");
      if($charIndex > -1) DestroyCharacter($player, $charIndex);
      break;
    default: break;
  }
  if (HasWard($cardID, $player)) WardPoppedAbility($player, $cardID);
}

function AllyStartTurnAbilities($player)
{
  $allies = &GetAllies($player);
  for($i = 0; $i < count($allies); $i += AllyPieces()) {
    switch($allies[$i]) {
      case "UPR414":
        WriteLog(CardLink($allies[$i], $allies[$i]) . " lets you transform up to 1 ash into an Ashwing.");
        Transform($player, "Ash", "UPR042", true);
        break;
      default: break;
    }
  }
}

function AllyEnduranceCounters($cardID)
{
  switch($cardID) {
    case "UPR417": return 1;
    default: return 0;
  }
}

function AllyDamagePrevention($player, $index, $damage)
{
  $allies = &GetAllies($player);
  $cardID = $allies[$index];
  $canBePrevented = CanDamageBePrevented($player, $damage, "");
  switch($cardID) {
    case "UPR417":
      if($allies[$index + 6] > 0) {
        if($damage > 0) --$allies[$index + 6];
        if($canBePrevented) $damage -= 3;
        if($damage < 0) $damage = 0;
      }
      return $damage;
    default: return $damage;
  }
}

//NOTE: This is for ally abilities that trigger when any ally attacks (for example miragai GRANTS an ability)
function AllyAttackAbilities($attackID)
{
  global $mainPlayer, $CS_NumDragonAttacks;
  $allies = &GetAllies($mainPlayer);
  for($i = 0; $i < count($allies); $i += AllyPieces()) {
    switch($allies[$i]) {
      case "UPR412":
        if($allies[$i + 8] > 0 && DelimStringContains(CardSubType($attackID), "Dragon") && GetClassState($mainPlayer, $CS_NumDragonAttacks) <= 1) {
          AddCurrentTurnEffect("UPR412", $mainPlayer);
          --$allies[$i + 8];
        }
        break;
      default: break;
    }
  }
}

//NOTE: This is for the actual attack abilities that allies have
function SpecificAllyAttackAbilities($attackID)
{
  global $mainPlayer, $combatChainState, $CCS_WeaponIndex, $defPlayer;
  $allies = &GetAllies($mainPlayer);
  $i = $combatChainState[$CCS_WeaponIndex];
  switch($allies[$i]) {
    case "UPR406":
      if(IsHeroAttackTarget()) {
        $deck = new Deck($mainPlayer);
        if($deck->Reveal(3)) {
          $cards = explode(",", $deck->Top(amount:3));
          $numRed = 0;
          for($j = 0; $j < count($cards); ++$j) if(PitchValue($cards[$j]) == 1) ++$numRed;
          if($numRed > 0) DealArcane($numRed * 2, 2, "ABILITY", $allies[$i], false, $mainPlayer);
        }
      }
      return "";
    case "UPR407":
      if(IsHeroAttackTarget()) {
        $deck = new Deck($mainPlayer);
        if($deck->Reveal(2)) {
          $cards = explode(",", $deck->Top(amount:2));
          $numRed = 0;
          for($j = 0; $j < count($cards); ++$j) if(PitchValue($cards[$j]) == 1) ++$numRed;
          if($numRed > 0) {
            $otherPlayer = ($mainPlayer == 1 ? 2 : 1);
            AddDecisionQueue("FINDINDICES", $otherPlayer, "EQUIP");
            AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
            AddDecisionQueue("MODDEFCOUNTER", $otherPlayer, (-1 * $numRed), 1);
            AddDecisionQueue("DESTROYEQUIPDEF0", $mainPlayer, "-", 1);
          }
        }
      }
      return "";
    case "UPR408":
      if(IsHeroAttackTarget()) {
        $deck = new Deck($mainPlayer);
        if($deck->Reveal(1)) {
          if(PitchValue($deck->Top()) == 1) {
            $otherPlayer = ($mainPlayer == 1 ? 2 : 1);
            AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
            AddDecisionQueue("CHOOSETHEIRHAND", $mainPlayer, "<-", 1);
            AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
            AddDecisionQueue("MULTIBANISH", $otherPlayer, "HAND,-", 1);
          }
        }
      }
      return "";
    case "UPR409":
      DealArcane(1, 2, "PLAYCARD", $allies[$i], false, $mainPlayer, true, true);
      DealArcane(1, 2, "PLAYCARD", $allies[$i], false, $mainPlayer, true, false);
      return "";
    case "UPR410":
      if($attackID == $allies[$i] && $allies[$i + 8] > 0) {
        GainActionPoints(1);
        --$allies[$i + 8];
        WriteLog("Gained 1 action point from " . CardLink($allies[$i], $allies[$i]));
      }
      break;
    case "DTD405":
      $soul = &GetSoul($mainPlayer);
      if(count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent:true);
      AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
      AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
      break;
    case "DTD406":
      $soul = &GetSoul($mainPlayer);
      if(count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent:true);
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRBANISH", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $mainPlayer, "TURNBANISHFACEDOWN", 1);
      break;
    case "DTD407":
      $soul = &GetSoul($mainPlayer);
      if(count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent:true);
      AddDecisionQueue("PLAYAURA", $mainPlayer, "MON104", 1);
      AddDecisionQueue("PLAYAURA", $mainPlayer, "MON104", 1);
      break;
    case "DTD408":
      $soul = &GetSoul($mainPlayer);
      if(count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent:true);
      DealArcane(2, 2, "PLAYCARD", $allies[$i], false, $mainPlayer, isPassable:1);
      break;
    case "DTD409":
      $soul = &GetSoul($mainPlayer);
      if(count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent:true);
      MZMoveCard($mainPlayer, "MYDISCARD:pitch=2", "MYTOPDECK", isSubsequent:true);
      break;
    case "DTD410":
      $soul = &GetSoul($mainPlayer);
      if(count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent:true);
      AddDecisionQueue("ADDCURRENTEFFECT", $mainPlayer, "DTD410", 1);
      break;
    case "DTD411":
      $soul = &GetSoul($mainPlayer);
      if(count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent:true);
      AddDecisionQueue("ADDCURRENTANDNEXTTURNEFFECT", $defPlayer, "DTD411", 1);
      break;
    case "DTD412":
      $soul = &GetSoul($mainPlayer);
      if(count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent:true);
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYALLY:subtype=Angel", 1);
      AddDecisionQueue("ADDALLATTACKCOUNTERS", $mainPlayer, "1", 1);
      break;
    default: break;
  }
}

function AllyDamageTakenAbilities($player, $i)
{
  $allies = &GetAllies($player);
  switch($allies[$i]) {
    case "UPR413":
      $allies[$i+2] -= 1;
      $allies[$i+7] -= 1;
      PutPermanentIntoPlay($player, "UPR043");
      WriteLog(CardLink($allies[$i], $allies[$i]) . " got a -1 health counter and created an ash token");
      break;
    default: break;
  }
}

function AllyTakeDamageAbilities($player, $index, $damage, $preventable)
{
  $allies = &GetAllies($player);
  //CR 2.1 6.4.10f If an effect states that a prevention effect can not prevent the damage of an event, the prevention effect still applies to the event but its prevention amount is not reduced. Any additional modifications to the event by the prevention effect still occur.
  $remove = false;
  if($damage > 0 && HasWard($allies[$index], $player)) {
    if($preventable) $damage -= WardAmount($allies[$index], $player);
    $remove = true;
    WardPoppedAbility($player, $allies[$index]);
  }
  switch($allies[$index]) {
    default: break;
  }
  if($remove) DestroyAlly($player, $index);
  if($damage <= 0) $damage = 0;
  return $damage;
}

function AllyBeginEndTurnEffects()
{
  global $mainPlayer, $defPlayer;
  //CR 2.0 4.4.3a Reset health for all allies
  $mainAllies = &GetAllies($mainPlayer);
  for($i = 0; $i < count($mainAllies); $i += AllyPieces()) {
    if($mainAllies[$i+1] != 0) {
      $mainAllies[$i+1] = 2;
      $mainAllies[$i+2] = AllyHealth($mainAllies[$i]) + $mainAllies[$i+7];
      $mainAllies[$i+8] = 1;
    }
  }
  $defAllies = &GetAllies($defPlayer);
  for($i = 0; $i < count($defAllies); $i += AllyPieces()) {
    if($defAllies[$i+1] != 0) {
      $defAllies[$i+1] = 2;
      $defAllies[$i+2] = AllyHealth($defAllies[$i]) + $defAllies[$i + 7];
      $defAllies[$i+8] = 1;
    }
  }
}

function AllyEndTurnAbilities()
{
  global $mainPlayer;
  $allies = &GetAllies($mainPlayer);
  for($i = count($allies) - AllyPieces(); $i >= 0; $i -= AllyPieces()) {
    switch($allies[$i]) {
      case "UPR551":
        DestroyAlly($mainPlayer, $i, true);
        break;
      default: break;
    }
  }
}
