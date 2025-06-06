<?php

function PlayAlly($cardID, $player, $subCards = "-", $number = 1, $isToken = false, $firstTransform = true, $tapped = 0, $from = "-")
{
  global $EffectContext;
  $otherPlayer = $player == 1 ? 2 : 1;
  if (TypeContains($cardID, "T", $player)) $isToken = true;
  $numMinusTokens = 0;
  $numMinusTokens = CountCurrentTurnEffects("ripple_away_blue", $player) + CountCurrentTurnEffects("ripple_away_blue", $otherPlayer);
  if (TypeContains($EffectContext, "C", $player) && (SearchAurasForCard("preach_modesty_red", 1) != "" || SearchAurasForCard("preach_modesty_red", 2) != "")) {
    WriteLog("🙇 " . CardLink("preach_modesty_red", "preach_modesty_red") . " prevents the creation of " . CardLink($cardID, $cardID));
    return;
  }
  if ($numMinusTokens > 0 && $isToken && (TypeContains($EffectContext, "AA", $player) || TypeContains($EffectContext, "A", $player)) && $firstTransform) $number -= $numMinusTokens;
  $allies = &GetAllies($player);
  for ($i = 0; $i < $number; ++$i) {
    $index = count($allies);
    array_push($allies, $cardID);
    array_push($allies, 2);
    array_push($allies, AllyHealth($cardID));
    array_push($allies, 0); //Frozen
    array_push($allies, $subCards); //Subcards
    array_push($allies, GetUniqueId($cardID, $player)); //Unique ID
    array_push($allies, AllyEnduranceCounters($cardID)); //Endurance Counters
    array_push($allies, 0); //Life Counters
    array_push($allies, 1); //Ability/effect uses
    array_push($allies, 0); //Power Counters
    array_push($allies, 0); //Damage dealt to the opponent
    array_push($allies, $tapped); //tapped
    array_push($allies, AllySteamCounters($cardID)); //steam counters
    array_push($allies, $from); //where it's played from
    array_push($allies, 0); //Modifier - e.g "Temporary" for cards that get stolen for a turn.
    if ($cardID == "ouvia") {
      WriteLog(CardLink($cardID, $cardID) . " lets you transform up to 1 ash into an Ashwing.");
      Transform($player, "Ash", "aether_ashwing", true);
    }
    if (HasCrank($cardID, $player)) Crank($player, $index, zone:"MYALLY");
  }
  return count($allies) - AllyPieces();
}

function CheckAllyDeath($player)
{
  $allies = &GetAllies($player);
  for ($i = count($allies) - AllyPieces(); $i >= 0; $i -= AllyPieces()) {
    if ($allies[$i + 2] <= 0) DestroyAlly($player, $i, false, true, $allies[$i + 5]);
  }
}

function DestroyAlly($player, $index, $skipDestroy = false, $fromCombat = false, $uniqueID = "", $toBanished = false)
{
  $allies = &GetAllies($player);
  if (!$skipDestroy) AllyDestroyedAbility($player, $index);
  $inDamageStep = SearchLayersForPhase("FINALIZECHAINLINK") != -1;
  if (IsSpecificAllyAttacking($player, $index) || (IsSpecificAllyAttackTarget($player, $index, $uniqueID) && !$fromCombat && !$inDamageStep)) {
    CloseCombatChain();
  }
  $cardID = $allies[$index];
  AllyAddGraveyard($player, $cardID, toBanished:$toBanished);
  AllyAddGraveyard($player, $allies[$index + 4], toBanished:$toBanished);
  for ($j = $index + AllyPieces() - 1; $j >= $index; --$j) unset($allies[$j]);
  $allies = array_values($allies);
  return $cardID;
}

function IsTokenAlly($cardID)
{
  return match($cardID) {
    "aether_ashwing" => true,
    "blasmophet_the_soul_harvester" => true,
    "nasreth_the_soul_harrower" => true,
    "ursur_the_soul_reaper" => true,
    "cintari_sellsword" => true,
    "-" => true,
    default => false
  };
}

function AllyAddGraveyard($player, $cardID, $toBanished=false)
{
  if (CardType($cardID) != "T") {
    if (SubtypeContains($cardID, "Ash", $player)) AddGraveyard($cardID, $player, "PLAY", $player);
    $id = match($cardID) {
      "suraya_archangel_of_erudition" => "figment_of_erudition_yellow",
      "themis_archangel_of_judgment" => "figment_of_judgment_yellow",
      "aegis_archangel_of_protection" => "figment_of_protection_yellow",
      "sekem_archangel_of_ravages" => "figment_of_ravages_yellow",
      "avalon_archangel_of_rebirth" => "figment_of_rebirth_yellow",
      "metis_archangel_of_tenacity" => "figment_of_tenacity_yellow",
      "victoria_archangel_of_triumph" => "figment_of_triumph_yellow",
      "bellona_archangel_of_war" => "figment_of_war_yellow",
      "dracona_optimai" => "invoke_dracona_optimai_red",
      "tomeltai" => "invoke_tomeltai_red",
      "dominia" => "invoke_dominia_red",
      "azvolai" => "invoke_azvolai_red",
      "cromai" => "invoke_cromai_red",
      "kyloria" => "invoke_kyloria_red",
      "miragai" => "invoke_miragai_red",
      "nekria" => "invoke_nekria_red",
      "ouvia" => "invoke_ouvia_red",
      "themai" => "invoke_themai_red",
      "vynserakai" => "invoke_vynserakai_red",
      "yendurai" => "invoke_yendurai_red",
      "suraya_archangel_of_knowledge" => "invoke_suraya_yellow",
      "polly_cranka_ally" => "polly_cranka",
      "sticky_fingers_ally" => "sticky_fingers",
      default => $cardID
    };
    if (IsTokenAlly($id)) return;
    if (!$toBanished) AddGraveyard($id, $player, "PLAY", $player);
    else BanishCardForPlayer($id, $player, "PLAY");
  }
}

function AllyHealth($cardID)
{
  return match($cardID) {
    "polly_cranka_ally" => 1,
    "sticky_fingers_ally" => 2,
    default => GeneratedCharacterHealth($cardID)
  };
}

function AllyDestroyedAbility($player, $index)
{
  global $mainPlayer;
  $allies = &GetAllies($player);
  $cardID = $allies[$index];
  switch ($cardID) {
    case "cromai":
      if ($player == $mainPlayer && $allies[$index + 8] > 0) {
        GainActionPoints(1, $player);
        --$allies[$index + 8];
      }
      break;
    case "UPR551":
      $charIndex = FindCharacterIndex($player, "ghostly_touch");
      if ($charIndex > -1) DestroyCharacter($player, $charIndex);
      break;
    case "oysten_heart_of_gold_yellow":
      PutItemIntoPlayForPlayer("gold", $player, isToken:true, from:$cardID);
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
      case "ouvia":
        WriteLog(CardLink($allies[$i], $allies[$i]) . " lets you transform up to 1 ash into an ".CardLink("aether_ashwing", "aether_ashwing").".");
        Transform($player, "Ash", "aether_ashwing", true);
        break;
      default:
        break;
    }
  }
}

function AllyEnduranceCounters($cardID)
{
  switch ($cardID) {
    case "yendurai":
      return 1;
    default:
      return 0;
  }
}

function AllySteamCounters($cardID)
{
  switch ($cardID) {
    case "polly_cranka_ally":
      return 1;
    default:
      return 0;
  }
}

function AllyDamagePrevention($player, $index, $damage, $type = "")
{
  global $currentTurnEffects;
  $allies = &GetAllies($player);
  $cardID = $allies[$index];
  $preventedDamage = 0;
  $canBePrevented = CanDamageBePrevented($player, $damage, $type);
  //checking for effects that prevent damage on allies
  for ($i = count($currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0; $i -= CurrentTurnEffectPieces()) {
    if ($preventedDamage < $damage && $currentTurnEffects[$i + 1] == $player) {
      switch ($currentTurnEffects[$i]) {
        case "sawbones_dock_hand_yellow":
          if(ClassContains($cardID, "PIRATE", $player)) {
            $preventedDamage += 1;
            RemoveCurrentTurnEffect($i);
          }
          break;
        default:
          break;
      }
    }
  }
  $damage -= $preventedDamage;
  //checking for allies that can prevent damage
  switch ($cardID) {
    case "yendurai":
      if ($allies[$index + 6] > 0) {
        if ($damage > 0) --$allies[$index + 6];
        if ($canBePrevented) $preventedDamage += 3;
        $damage -= $preventedDamage;
        if ($damage < 0) $damage = 0;
      }
      break;
    default:
      break;
  }
  if ($preventedDamage > 0 && SearchCurrentTurnEffects("vambrace_of_determination", $player) != "") {
    $preventedDamage -= 1;
    SearchCurrentTurnEffects("vambrace_of_determination", $player, remove:true);
  }
  return $damage;
}

//NOTE: This is for ally abilities that trigger when any ally attacks (for example miragai GRANTS an ability)
function AllyAttackAbilities($attackID)
{
  global $mainPlayer, $CS_NumDragonAttacks;
  $allies = &GetAllies($mainPlayer);
  for ($i = 0; $i < count($allies); $i += AllyPieces()) {
    switch ($allies[$i]) {
      case "miragai":
        if ($allies[$i + 8] > 0 && DelimStringContains(CardSubType($attackID), "Dragon") && GetClassState($mainPlayer, $CS_NumDragonAttacks) <= 1) {
          AddCurrentTurnEffect("miragai", $mainPlayer);
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
  if (isset($allies[$i])) {
    switch ($allies[$i]) {
      case "dracona_optimai":
      case "tomeltai":
      case "dominia":
        if (IsHeroAttackTarget()) {
          AddLayer("TRIGGER", $mainPlayer, $allies[$i], "-", "-", $allies[$i + 5]);
        }
        return "";
      case "azvolai":
        AddLayer("TRIGGER", $mainPlayer, $allies[$i], "-", "-", $allies[$i + 5]);
        return "";
      case "cromai":
        if ($attackID == $allies[$i] && $allies[$i + 8] > 0) {
          GainActionPoints(1);
          --$allies[$i + 8];
          WriteLog("Gained 1 action point from " . CardLink($allies[$i], $allies[$i]));
        }
        break;
      case "suraya_archangel_of_erudition":
        $soul = &GetSoul($mainPlayer);
        if (count($soul) == 0) break;
        AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
        AddDecisionQueue("NOPASS", $mainPlayer, "-");
        MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
        AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
        AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
        break;
      case "themis_archangel_of_judgment":
        $soul = &GetSoul($mainPlayer);
        if (count($soul) == 0) break;
        AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
        AddDecisionQueue("NOPASS", $mainPlayer, "-");
        MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRBANISH&MYBANISH", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $mainPlayer, "TURNBANISHFACEDOWN", 1);
        break;
      case "aegis_archangel_of_protection":
        $soul = &GetSoul($mainPlayer);
        if (count($soul) == 0) break;
        AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
        AddDecisionQueue("NOPASS", $mainPlayer, "-");
        MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
        AddDecisionQueue("PLAYAURA", $mainPlayer, "spectral_shield-2", 1);
        break;
      case "sekem_archangel_of_ravages":
        $soul = &GetSoul($mainPlayer);
        if (count($soul) == 0) break;
        AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
        AddDecisionQueue("NOPASS", $mainPlayer, "-");
        MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
        DealArcane(2, 2, "PLAYCARD", $allies[$i], false, $mainPlayer, isPassable: 1);
        break;
      case "avalon_archangel_of_rebirth":
        $soul = &GetSoul($mainPlayer);
        if (count($soul) == 0) break;
        AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
        AddDecisionQueue("NOPASS", $mainPlayer, "-");
        MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
        MZMoveCard($mainPlayer, "MYDISCARD:pitch=2", "MYTOPDECK", isSubsequent: true);
        break;
      case "metis_archangel_of_tenacity":
        $soul = &GetSoul($mainPlayer);
        if (count($soul) == 0) break;
        AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
        AddDecisionQueue("NOPASS", $mainPlayer, "-");
        MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $mainPlayer, "metis_archangel_of_tenacity", 1);
        break;
      case "victoria_archangel_of_triumph":
        $soul = &GetSoul($mainPlayer);
        if (count($soul) == 0) break;
        AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
        AddDecisionQueue("NOPASS", $mainPlayer, "-");
        MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
        AddDecisionQueue("ADDCURRENTANDNEXTTURNEFFECT", $defPlayer, "victoria_archangel_of_triumph", 1);
        break;
      case "bellona_archangel_of_war":
        $soul = &GetSoul($mainPlayer);
        if (count($soul) == 0) break;
        AddDecisionQueue("YESNO", $mainPlayer, "if you want to banish a card from soul");
        AddDecisionQueue("NOPASS", $mainPlayer, "-");
        MZMoveCard($mainPlayer, "MYSOUL", "MYBANISH,SOUL,-", isSubsequent: true);
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYALLY:subtype=Angel", 1);
        AddDecisionQueue("ADDALLPOWERCOUNTERS", $mainPlayer, "1", 1);
        break;
      default:
        break;
    }
  }
}

function AllyDamageTakenAbilities($player, $i)
{
  $allies = &GetAllies($player);
  switch ($allies[$i]) {
    case "nekria":
      $allies[$i + 2] -= 1;
      $allies[$i + 7] -= 1;
      PutPermanentIntoPlay($player, "ash");
      WriteLog(CardLink($allies[$i], $allies[$i]) . " got a -1 life counter and created an ".CardLink("ash", "ash")." token");
      break;
    default:
      break;
  }
  $otherPlayer = $player == 1 ? 2 : 1;
  $otherAuras = &GetAuras($otherPlayer);
  for ($i = count($otherAuras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    $remove = 0;
    switch ($otherAuras[$i]) {
      // case "bloodspill_invocation_red": //need to check if damage was dealt by an AA card?
      // case "bloodspill_invocation_yellow":
      // case "bloodspill_invocation_blue":
      case "arcane_cussing_red":
      case "arcane_cussing_yellow":
      case "arcane_cussing_blue":
        $remove = 1;
        break;
      default:
        break;
    }
    if ($remove) DestroyAura($otherPlayer, $i);
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
  if ($preventedDamage > 0 && SearchCurrentTurnEffects("vambrace_of_determination", $player) != "") {
    $preventedDamage -= 1;
    SearchCurrentTurnEffects("vambrace_of_determination", $player, remove:true);
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
  UpdateAllyHealth($mainAllies);

  $defAllies = &GetAllies($defPlayer);
  UpdateAllyHealth($defAllies);
}

function UpdateAllyHealth(&$allies)
{
  $pieces = AllyPieces();
  $count = count($allies);
  for ($i = 0; $i < $count; $i += $pieces) {
    if(isset($allies[$i + 1]) && $allies[$i + 1] != 0) {
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
    //untap
    Tap("MYALLY-$i", $mainPlayer, 0, endStepUntap:true);
    switch ($allies[$i]) {
      case "UPR551":
        DestroyAlly($mainPlayer, $i, true);
        break;
      default:
        break;
    }
  }
}

function AllyPayAdditionalCosts($cardIndex, $from)
{
  global $currentPlayer;
  $ally = &GetAllies($currentPlayer);
  $cardID = $ally[$cardIndex];
  switch ($cardID) {
    case "chum_friendly_first_mate_yellow":
    case "riggermortis_yellow":
    case "swabbie_yellow":
    case "limpit_hop_a_long_yellow":
    case "sawbones_dock_hand_yellow":
    case "chowder_hearty_cook_yellow":
    case "wailer_humperdinck_yellow":
    case "barnacle_yellow":
    case "kelpie_tangled_mess_yellow":
    case "scooba_salty_sea_dog_yellow":
    case "shelly_hardened_traveler_yellow":
    case "moray_le_fay_yellow":
    case "sticky_fingers_ally":
    case "oysten_heart_of_gold_yellow":
    case "anka_drag_under_yellow":
      Tap("MYALLY-$cardIndex", $currentPlayer);
      $ally[$cardIndex + 1] = 2;//Not once per turn effects
      break;
    case "polly_cranka_ally":
      Tap("MYALLY-$cardIndex", $currentPlayer);
      DestroyAlly($currentPlayer, $cardIndex, skipDestroy:true, toBanished:true);
      break;
    case "cutty_shark_quick_clip_yellow":
      if(GetResolvedAbilityType($cardID, $from, $currentPlayer) == "AA") {
        Tap("MYALLY-$cardIndex", $currentPlayer);
        if($ally[$cardIndex + 8] > 0) $ally[$cardIndex + 1] = 2;//Not once per turn effects
      }
      else {
        $ally[$cardIndex + 8] = 0;//Once per turn ability used
      }
      break; 
    default: break;
  }
}

function GetPerchedAllies($player)
{
  $perchedAllies = [];
  $char = GetPlayerCharacter($player);
  for ($i = 0; $i < count($char); $i += CharacterPieces()) {
    if (HasPerched($char[$i]) && $char[$i + 1] != 0) array_push($perchedAllies, $i);
  }
  return implode(",", $perchedAllies);
}

function StealAlly($srcPlayer, $index, $destPlayer, $from, $mod=0)
{
  $srcAlly = &GetAllies($srcPlayer);
  $destAlly = &GetAllies($destPlayer);
  for ($i = 0; $i < AllyPieces(); ++$i) {
    if($i == 14 && $mod != 0) {
      $srcAlly[$index + $i] = $mod; //14 - Modifier or e.g "Temporary" for cards that get stolen for a turn.
      $srcAlly[$index + 11] = 0; //Untap
    }
    if($i == 13) //13 - Where it's played from ... Important for where it'll go when destroyed for example.
    {
      if (strpos($srcAlly[$index + $i], 'MY') === 0) {
          $srcAlly[$index + $i] = 'THEIR' . substr($srcAlly[$index + $i], 2);
      } elseif (strpos($srcAlly[$index + $i], 'THEIR') === 0) {
          $srcAlly[$index + $i] = 'MY' . substr($srcAlly[$index + $i], 5);
      } else {
          $srcAlly[$index + $i] = 'THEIR' . $srcAlly[$index + $i];
      }
    }
    array_push($destAlly, $srcAlly[$index + $i]);
    unset($srcAlly[$index + $i]);
  }
  array_pop($srcAlly);
  $srcAlly = array_values($srcAlly);
}