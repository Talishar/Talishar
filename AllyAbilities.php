<?php

function PlayAlly($cardID, $player, $subCards = "-", $number = 1, $isToken = false, $firstTransform = true)
{
  global $EffectContext;
  $otherPlayer = ($player == 1 ? 2 : 1);
  if (TypeContains($cardID, "T", $player)) $isToken = true;
  $numMinusTokens = 0;
  $numMinusTokens = CountCurrentTurnEffects("HVY209", $player) + CountCurrentTurnEffects("HVY209", $otherPlayer);
  if ($numMinusTokens > 0 && $isToken && (TypeContains($EffectContext, "AA", $player) || TypeContains($EffectContext, "A", $player)) && $firstTransform) $number -= $numMinusTokens;
  $allies = &GetAllies($player);
  for ($i = 0; $i < $number; ++$i) {
    array_push($allies, $cardID);
    array_push($allies, 2);
    array_push($allies, AllyHealth($cardID));
    array_push($allies, 0); //Frozen
    array_push($allies, $subCards); //Subcards
    array_push($allies, GetUniqueId($cardID, $player)); //Unique ID
    array_push($allies, AllyEnduranceCounters($cardID)); //Endurance Counters
    array_push($allies, 0); //Life Counters
    array_push($allies, 1); //Ability/effect uses
    array_push($allies, 0); //Attack Counters
    array_push($allies, 0); //Damage dealt to the opponent
    if ($cardID == "UPR414") {
      WriteLog(CardLink($cardID, $cardID) . " lets you transform up to 1 ash into an Ashwing.");
      Transform($player, "Ash", "UPR042", true);
    }
  }
  return count($allies) - AllyPieces();
}

function DestroyAlly($player, $index, $skipDestroy = false, $fromCombat = false, $uniqueID = "")
{
  $allies = &GetAllies($player);
  if (!$skipDestroy) AllyDestroyedAbility($player, $index);
  if (IsSpecificAllyAttacking($player, $index) || (IsSpecificAllyAttackTarget($player, $index, $uniqueID) && !$fromCombat)) {
    CloseCombatChain();
  }
  $cardID = $allies[$index];
  AllyAddGraveyard($player, $cardID);
  AllyAddGraveyard($player, $allies[$index + 4]);
  for ($j = $index + AllyPieces() - 1; $j >= $index; --$j) unset($allies[$j]);
  $allies = array_values($allies);
  return $cardID;
}

function AllyAddGraveyard($player, $cardID)
{
  if (CardType($cardID) != "T") {
    if (SubtypeContains($cardID, "Ash", $player)) AddGraveyard($cardID, $player, "PLAY", $player);
    $set = substr($cardID, 0, 3);
    $number = intval(substr($cardID, 3, 3));
    $number -= 400;
    if ($number < 0) return;
    $id = $number;
    if ($number < 100) $id = "0" . $id;
    if ($number < 10) $id = "0" . $id;
    $id = $set . $id;
    if (!SubtypeContains($id, "Invocation", $player) && !SubtypeContains($id, "Figment", $player)) return;
    AddGraveyard($id, $player, "PLAY", $player);
  }
}

function AllyHealth($cardID)
{
  switch ($cardID) {
    case "MON219":
      return 6;
    case "MON220":
      return 6;
    case "UPR406":
      return 6;
    case "UPR407":
      return 5;
    case "UPR408":
      return 4;
    case "UPR409":
      return 3;
    case "UPR410":
      return 2;
    case "UPR411":
      return 2;
    case "UPR412":
      return 4;
    case "UPR413":
      return 7;
    case "UPR414":
      return 6;
    case "UPR415":
      return 4;
    case "UPR416":
      return 1;
    case "UPR417":
      return 3;
    case "DYN612":
      return 4;
    case "DTD193":
      return 6;
    case "DTD405":
      return 4;
    case "DTD406":
      return 4;
    case "DTD407":
      return 4;
    case "DTD408":
      return 4;
    case "DTD409":
      return 4;
    case "DTD410":
      return 4;
    case "DTD411":
      return 4;
    case "DTD412":
      return 4;
    case "HVY134":
      return 2;
    default:
      return 1;
  }
}

function AllyDestroyedAbility($player, $index)
{
  global $mainPlayer;
  $allies = &GetAllies($player);
  $cardID = $allies[$index];
  switch ($cardID) {
    case "UPR410":
      if ($player == $mainPlayer && $allies[$index + 8] > 0) {
        GainActionPoints(1, $player);
        --$allies[$index + 8];
      }
      break;
    case "UPR551":
      $charIndex = FindCharacterIndex($player, "UPR151");
      if ($charIndex > -1) DestroyCharacter($player, $charIndex);
      break;
    default:
      break;
  }
  if (HasWard($cardID, $player)) WardPoppedAbility($player, $cardID);
}

function AllyStartTurnAbilities($player)
{
  $allies = &GetAllies($player);
  for ($i = 0; $i < count($allies); $i += AllyPieces()) {
    switch ($allies[$i]) {
      case "UPR414":
        WriteLog(CardLink($allies[$i], $allies[$i]) . " lets you transform up to 1 ash into an Ashwing.");
        Transform($player, "Ash", "UPR042", true);
        break;
      default:
        break;
    }
  }
}

function AllyEnduranceCounters($cardID)
{
  switch ($cardID) {
    case "UPR417":
      return 1;
    default:
      return 0;
  }
}

function AllyDamagePrevention($player, $index, $damage, $type = "")
{
  $allies = &GetAllies($player);
  $cardID = $allies[$index];
  $preventedDamage = 0;
  $canBePrevented = CanDamageBePrevented($player, $damage, $type);
  switch ($cardID) {
    case "UPR417":
      if ($allies[$index + 6] > 0) {
        if ($damage > 0) --$allies[$index + 6];
        if ($canBePrevented) $preventedDamage += 3;
        if ($preventedDamage > 0 && SearchCurrentTurnEffects("OUT174", $player) != "") {
          $preventedDamage -= 1;
          SearchCurrentTurnEffects("OUT174", $player, remove:true);
        }
        $damage -= $preventedDamage;
        if ($damage < 0) $damage = 0;
      }
      return $damage;
    default:
      return $damage;
  }
}

//NOTE: This is for ally abilities that trigger when any ally attacks (for example miragai GRANTS an ability)
function AllyAttackAbilities($attackID)
{
  global $mainPlayer, $CS_NumDragonAttacks;
  $allies = &GetAllies($mainPlayer);
  for ($i = 0; $i < count($allies); $i += AllyPieces()) {
    switch ($allies[$i]) {
      case "UPR412":
        if ($allies[$i + 8] > 0 && DelimStringContains(CardSubType($attackID), "Dragon") && GetClassState($mainPlayer, $CS_NumDragonAttacks) <= 1) {
          AddCurrentTurnEffect("UPR412", $mainPlayer);
          --$allies[$i + 8];
        }
        break;
      default:
        break;
    }
  }
}

//NOTE: This is for the actual attack abilities that allies have
function SpecificAllyAttackAbilities($attackID)
{
  global $mainPlayer, $combatChainState, $CCS_WeaponIndex, $defPlayer;
  $allies = &GetAllies($mainPlayer);
  $i = $combatChainState[$CCS_WeaponIndex];
  switch ($allies[$i]) {
    case "UPR406":
    case "UPR407":
    case "UPR408":
      if (IsHeroAttackTarget()) {
        AddLayer("TRIGGER", $mainPlayer, $allies[$i], "-", "-", $allies[$i + 5]);
      }
      return "";
    case "UPR409":
      AddLayer("TRIGGER", $mainPlayer, $allies[$i], "-", "-", $allies[$i + 5]);
      return "";
    case "UPR410":
      if ($attackID == $allies[$i] && $allies[$i + 8] > 0) {
        GainActionPoints(1);
        --$allies[$i + 8];
        WriteLog("Gained 1 action point from " . CardLink($allies[$i], $allies[$i]));
      }
      break;
    case "DTD405":
      $soul = &GetSoul($mainPlayer);
      if (count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
      AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
      AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
      break;
    case "DTD406":
      $soul = &GetSoul($mainPlayer);
      if (count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRBANISH&MYBANISH", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $mainPlayer, "TURNBANISHFACEDOWN", 1);
      break;
    case "DTD407":
      $soul = &GetSoul($mainPlayer);
      if (count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
      AddDecisionQueue("PLAYAURA", $mainPlayer, "MON104-2", 1);
      break;
    case "DTD408":
      $soul = &GetSoul($mainPlayer);
      if (count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
      DealArcane(2, 2, "PLAYCARD", $allies[$i], false, $mainPlayer, isPassable: 1);
      break;
    case "DTD409":
      $soul = &GetSoul($mainPlayer);
      if (count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
      MZMoveCard($mainPlayer, "MYDISCARD:pitch=2", "MYTOPDECK", isSubsequent: true);
      break;
    case "DTD410":
      $soul = &GetSoul($mainPlayer);
      if (count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
      AddDecisionQueue("ADDCURRENTEFFECT", $mainPlayer, "DTD410", 1);
      break;
    case "DTD411":
      $soul = &GetSoul($mainPlayer);
      if (count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
      AddDecisionQueue("ADDCURRENTANDNEXTTURNEFFECT", $defPlayer, "DTD411", 1);
      break;
    case "DTD412":
      $soul = &GetSoul($mainPlayer);
      if (count($soul) == 0) break;
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
      AddDecisionQueue("NOPASS", $mainPlayer, "-");
      MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYALLY:subtype=Angel", 1);
      AddDecisionQueue("ADDALLATTACKCOUNTERS", $mainPlayer, "1", 1);
      break;
    default:
      break;
  }
}

function AllyDamageTakenAbilities($player, $i)
{
  $allies = &GetAllies($player);
  switch ($allies[$i]) {
    case "UPR413":
      $allies[$i + 2] -= 1;
      $allies[$i + 7] -= 1;
      PutPermanentIntoPlay($player, "UPR043");
      WriteLog(CardLink($allies[$i], $allies[$i]) . " got a -1 life counter and created an ash token");
      break;
    default:
      break;
  }
}

function AllyTakeDamageAbilities($player, $index, $damage, $preventable)
{
  $allies = &GetAllies($player);
  //CR 2.1 6.4.10f If an effect states that a prevention effect can not prevent the damage of an event, the prevention effect still applies to the event but its prevention amount is not reduced. Any additional modifications to the event by the prevention effect still occur.
  $remove = false;
  $preventedDamage = 0;
  if ($damage > 0 && HasWard($allies[$index], $player)) {
    if ($preventable) $preventedDamage += WardAmount($allies[$index], $player);
    $remove = true;
    WardPoppedAbility($player, $allies[$index]);
  }
  switch ($allies[$index]) {
    default:
      break;
  }
  if ($remove) DestroyAlly($player, $index, uniqueID: $allies[$index + 5]);
  if ($preventedDamage > 0 && SearchCurrentTurnEffects("OUT174", $player) != "") {
    $preventedDamage -= 1;
    SearchCurrentTurnEffects("OUT174", $player, remove:true);
  }
  $damage -= $preventedDamage;
  if ($damage <= 0) $damage = 0;
  return $damage;
}

function AllyBeginEndTurnEffects()
{
  global $mainPlayer, $defPlayer;
  //CR 2.0 4.4.3a Reset life for all allies
  $mainAllies = &GetAllies($mainPlayer);
  updateAllyHealth($mainAllies);

  $defAllies = &GetAllies($defPlayer);
  updateAllyHealth($defAllies);
}

function updateAllyHealth(&$allies)
{
  $pieces = AllyPieces();
  $count = count($allies);
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($allies[$i + 1] != 0) {
      $allies[$i + 1] = 2;
      $allies[$i + 2] = AllyHealth($allies[$i]) + $allies[$i + 7];
      $allies[$i + 8] = 1;
      $allies[$i + 10] = 0;
    }
  }
}

function AllyEndTurnAbilities()
{
  global $mainPlayer;
  $allies = &GetAllies($mainPlayer);
  for ($i = count($allies) - AllyPieces(); $i >= 0; $i -= AllyPieces()) {
    switch ($allies[$i]) {
      case "UPR551":
        DestroyAlly($mainPlayer, $i, true);
        break;
      default:
        break;
    }
  }
}
