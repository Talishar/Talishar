<?php


function PutItemIntoPlayForPlayer($cardID, $player, $steamCounterModifier = 0, $number = 1, $effectController = "", $isToken = false, $mainPhase = "True", $from = "-")
{
  global $EffectContext, $CS_NumGoldCreated;
  $otherPlayer = $player == 1 ? 2 : 1;
  if ($effectController == "") $effectController = $player;
  if (!DelimStringContains(CardSubType($cardID), "Item") && $cardID != "levia_redeemed") return;
  if (TypeContains($EffectContext, "C", $player) && (SearchAurasForCard("preach_modesty_red", 1) != "" || SearchAurasForCard("preach_modesty_red", 2) != "")) {
    WriteLog(CardLink("preach_modesty_red", "preach_modesty_red") . " prevents the creation of " . CardLink($cardID, $cardID));
    return;
  }
  if (TypeContains($cardID, "T", $player)) $isToken = true;
  $numMinusTokens = 0;
  $numMinusTokens = CountCurrentTurnEffects("ripple_away_blue", $player) + CountCurrentTurnEffects("ripple_away_blue", $otherPlayer);
  if ($numMinusTokens > 0 && $isToken && (TypeContains($EffectContext, "AA", $player) || TypeContains($EffectContext, "A", $player))) $number -= $numMinusTokens;
  if ($number <= 0) return; // there's no event in this case
  $items = &GetItems($player);
  $myHoldState = ItemDefaultHoldTriggerState($cardID, $player);
  if ($myHoldState == 0 && HoldPrioritySetting($player) == 1) $myHoldState = 1;
  $theirHoldState = ItemDefaultHoldTriggerState($cardID, $otherPlayer);
  if ($theirHoldState == 0 && HoldPrioritySetting($otherPlayer) == 1) $theirHoldState = 1;
  for ($i = 0; $i < $number; ++$i) {
    $uniqueID = GetUniqueId($cardID, $player);
    $steamCounters = SteamCounterLogic($cardID, $player, $uniqueID) + $steamCounterModifier;
    $index = count($items);
    array_push($items, $cardID);
    array_push($items, $steamCounters);
    array_push($items, 2);
    array_push($items, ItemUses($cardID));
    array_push($items, $uniqueID);
    array_push($items, $myHoldState);
    array_push($items, $theirHoldState);
    array_push($items, 0);
    array_push($items, ItemModalities($cardID));
    array_push($items, $from);
    array_push($items, 0); //enters untapped
    if (HasCrank($cardID, $player)) Crank($player, $index, $mainPhase);
  }

  $char = &GetPlayerCharacter($player);
  $hero = ShiyanaCharacter($char[0], $player);
  if (($symbiosisIndex = FindCharacterIndex($player, "symbiosis_shot")) > 0 && ClassContains($cardID, "MECHANOLOGIST", $player)) {
    if ($char[$symbiosisIndex + 2] < 6) ++$char[$symbiosisIndex + 2];
  }
  if ($cardID == "gold") {
    IncrementClassState($player, $CS_NumGoldCreated, $number);
    UndestroyHook($player);
    if ($number > 0 && ($hero == "victor_goldmane_high_and_mighty" || $hero == "victor_goldmane") && SearchCurrentTurnEffects($hero . "-1", $player, true) && $effectController == $player) {
      $EffectContext = $hero;
      WriteLog("Player $player drew a card from Victor");
      Draw($player);
    }
  }
  if ($cardID == "goldkiss_rum" && $hero == "scurv_stowaway" && IsCharacterActive($player, 0)) {
    AddLayer("TRIGGER", $player, $hero);
  }
  //enters the arena triggers
  switch ($cardID) {
    case "stasis_cell_blue":
    case "null_time_zone_blue":
      AddLayer("TRIGGER", $player, $cardID);
      break;
    default:
      break;
  }
  PlayAbility($cardID, $from, 0);
}

function ItemUses($cardID)
{
  switch ($cardID) {
    case "micro_processor_blue":
      return 3;
    default:
      return 1;
  }
}

function ItemModalities($cardID)
{
  switch ($cardID) {
    case 'micro_processor_blue':
      return "Opt,Draw_then_top_deck,Banish_top_deck";
    default:
      return "-";
  }
}

function PayItemAbilityAdditionalCosts($cardID, $from)
{
  global $currentPlayer, $CS_PlayIndex, $combatChain;
  $index = GetClassState($currentPlayer, $CS_PlayIndex);
  $items = &GetItems($currentPlayer);
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  switch ($cardID) {
    case "crazy_brew_blue":
    case "energy_potion_blue":
    case "potion_of_strength_blue":
    case "timesnap_potion_blue":
    case "amulet_of_earth_blue":
    case "amulet_of_ice_blue":
    case "amulet_of_lightning_blue":
    case "amulet_of_assertiveness_yellow":
    case "amulet_of_echoes_blue":
    case "amulet_of_havencall_blue":
    case "amulet_of_ignition_yellow":
    case "amulet_of_intervention_blue":
    case "amulet_of_oblation_blue":
    case "clarity_potion_blue":
    case "healing_potion_blue":
    case "potion_of_seeing_blue":
    case "potion_of_deja_vu_blue":
    case "potion_of_ironhide_blue":
    case "potion_of_luck_blue":
    case "silverwind_shuriken_blue":
    case "backup_protocol_red_red":
    case "backup_protocol_yel_yellow":
    case "backup_protocol_blu_blue":
    case "imperial_seal_of_command_red":
    case "gold":
    case "diamond_amulet_blue":
    case "amethyst_amulet_blue":
    case "onyx_amulet_blue":
    case "opal_amulet_blue":
    case "pearl_amulet_blue":
    case "platinum_amulet_blue":
    case "pounamu_amulet_blue":
    case "ruby_amulet_blue":
    case "sapphire_amulet_blue":
      DestroyItemForPlayer($currentPlayer, $index);
      break;
    case "dissipation_shield_yellow":
      AddAdditionalCost($currentPlayer, $items[$index + 1]);
      DestroyItemForPlayer($currentPlayer, $index);
      break;
    case "induction_chamber_red":
    case "cognition_nodes_blue":
      if ($from == "PLAY" && $items[$index + 1] > 0 && count($combatChain) > 0) {
        $items[$index + 1] -= 1;
        $items[$index + 2] = 1;
      }
      break;
    case "plasma_purifier_red":
      if ($from == "PLAY" && $items[$index + 1] > 0 && $items[$index + 2] == 2) {
        $items[$index + 1] -= 1;
        AddAdditionalCost($currentPlayer, "PAID");
      }
      break;
    case "prismatic_lens_yellow":
    case "quantum_processor_yellow":
      if ($from == "PLAY") {
        $items[$index + 2] = 1;
      }
      break;
    case "fuel_injector_blue":
    case "medkit_blue":
    case "steam_canister_blue":
      if (substr($items[$index + 9], 0, 5) != "THEIR") {
        $deck = new Deck($currentPlayer);
      } else {
        $deck = new Deck($otherPlayer);
      }
      RemoveItem($currentPlayer, $index);
      $deck->AddBottom($cardID, from: "PLAY");
      break;
    case "stasis_cell_blue":
      RemoveItem($currentPlayer, $index);
      $deck = new Deck($currentPlayer);
      $deck->AddBottom($cardID, from: "PLAY");
      break;
    case "dissolving_shield_red":
    case "dissolving_shield_yellow":
    case "dissolving_shield_blue":
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      --$items[$index + 1];
      if ($items[$index + 1] <= 0) DestroyItemForPlayer($currentPlayer, $index);
      break;
    case "cerebellum_processor_blue":
      if ($from == "PLAY") {
        $items[$index + 2] = 1;
      }
      break;
    case "goldkiss_rum":
      Tap("MYCHAR-0", $currentPlayer);
      DestroyItemForPlayer($currentPlayer, $index);
    default:
      break;
  }
}

function ItemPlayAbilities($cardID, $from)
{
  global $currentPlayer;
  $items = &GetItems($currentPlayer);
  for ($i = count($items) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
    $remove = false;
    switch ($items[$i]) {
      case "talisman_of_cremation_blue":
        if ($from == "BANISH") {
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card name to banish with Talisman of Cremation");
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRDISCARD");
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZOP", $currentPlayer, "GETCARDID", 1);
          AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "THEIRDISCARD:isSameName=", 1);
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZBANISH", $currentPlayer, "GY,-," . $currentPlayer, 1);
          AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
          $remove = true;
        }
        break;
      default:
        break;
    }
    if ($remove) DestroyItemForPlayer($currentPlayer, $i);
  }
}

function RemoveItem($player, $index)
{
  return DestroyItemForPlayer($player, $index, true);
}

function DestroyItemForPlayer($player, $index, $skipDestroy = false)
{
  global $CS_NumItemsDestroyed;
  $items = &GetItems($player);
  if (!$skipDestroy) {
    if (str_contains($items[$index + 9], "THEIR")) $destPlayer = $player == 1 ? 2 : 1;
    else $destPlayer = $player;
    if (CardType($items[$index]) != "T" && GoesWhereAfterResolving($items[$index], "PLAY", $player) == "GY")
      AddGraveyard($items[$index], $destPlayer, "PLAY");
    IncrementClassState($player, $CS_NumItemsDestroyed);
  }
  $cardID = $items[$index];
  for ($i = $index + ItemPieces() - 1; $i >= $index; --$i) {
    if ($items[$i] == "nitro_mechanoidc") {
      $indexWeapon = FindCharacterIndex($player, "nitro_mechanoida");
      DestroyCharacter($player, $indexWeapon);
      $indexEquipment = FindCharacterIndex($player, "nitro_mechanoidb");
      DestroyCharacter($player, $indexEquipment, true);
      SearchCurrentTurnEffects("galvanic_bender-UNDER", $player, true);
    }
    unset($items[$i]);
  }
  $items = array_values($items);
  if ($cardID == "stasis_cell_blue") AddLayer("TRIGGER", $player, $cardID);
  return $cardID;
}

function StealItem($srcPlayer, $index, $destPlayer, $from, $mod=0)
{
  global $CS_NumGoldCreated;
  $srcItems = &GetItems($srcPlayer);
  if ($srcItems[$index] == "gold") {
    UndestroyHook($destPlayer);
    IncrementClassState($destPlayer, $CS_NumGoldCreated);
  }
  $destItems = &GetItems($destPlayer);
  for ($i = 0; $i < ItemPieces(); ++$i) {
    if ($srcItems[$i] == "nitro_mechanoidc") {
      $indexEquipment = FindCharacterIndex($srcPlayer, "nitro_mechanoidb");
      RemoveCharacter($srcPlayer, $indexEquipment);
      $indexWeapon = FindCharacterIndex($srcPlayer, "nitro_mechanoida");
      RemoveCharacter($srcPlayer, $indexWeapon);
      SearchCurrentTurnEffects("galvanic_bender-UNDER", $srcPlayer, true);
    }
    if($i == 8 && $mod != 0) $srcItems[$index + $i] = $mod; //8 - Modalities or e.g "Temporary" for cards that get stolen for a turn.
    if($i == 9) //9 - Where it's played from ... Important for where it'll go when destroyed for example.
    {
      if (strpos($srcItems[$index + $i], 'MY') === 0) {
          $srcItems[$index + $i] = 'THEIR' . substr($srcItems[$index + $i], 2);
      } elseif (strpos($srcItems[$index + $i], 'THEIR') === 0) {
          $srcItems[$index + $i] = 'MY' . substr($srcItems[$index + $i], 5);
      } else {
          $srcItems[$index + $i] = 'THEIR' . $srcItems[$index + $i];
      }
    }
    array_push($destItems, $srcItems[$index + $i]);
    unset($srcItems[$index + $i]);
  }
  $srcItems = array_values($srcItems);
}

function GetItemGemState($player, $cardID, $index=-1)
{
  global $currentPlayer;
  $items = &GetItems($player);
  if($index == -1) {
    $offset = $currentPlayer == $player ? 5 : 6;
    $state = 0;
    for ($i = 0; $i < count($items); $i += ItemPieces()) {
      if ($items[$i] == $cardID && $items[$i + $offset] > $state) $state = $items[$i + $offset];
    }
  }
  else {
    return $items[$index + 5];
  }
  return $state;
}

function ItemHitTrigger($attackID)
{
  global $mainPlayer, $defPlayer;
  $attackType = CardType($attackID);
  $attackSubType = CardSubType($attackID);
  $items = &GetItems($mainPlayer);
  for ($i = count($items) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
    switch ($items[$i]) {
      case "powder_keg_blue":
        if ($attackSubType == "Gun" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer)) {
          AddLayer("TRIGGER", $mainPlayer, $items[$i], $attackID, "ITEMHITEFFECT", $items[$i + 4]);
        }
        break;
      case "tick_tock_clock_red":
        if (IsHeroAttackTarget() && $attackType == "AA" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer)) {
          AddLayer("TRIGGER", $mainPlayer, $items[$i], $attackID, "ITEMHITEFFECT", $items[$i + 4]);
        }
        break;
      case "boom_grenade_red":
      case "boom_grenade_yellow":
      case "boom_grenade_blue":
        if (IsHeroAttackTarget() && $attackType == "AA" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer)) {
          AddLayer("TRIGGER", $mainPlayer, $items[$i], $attackID, "ITEMHITEFFECT", $items[$i + 4]);
        }
        break;
      case "autosave_script_blue":
        if ($attackType == "AA" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer)) {
          AddLayer("TRIGGER", $mainPlayer, $items[$i], $attackID, "ITEMHITEFFECT", $items[$i + 4]);
        }
        break;
      default:
        break;
    }
  }
}

function ChosenItemTakeDamageAbilities($player, $index, $damage, $preventable)
{
  $items = &GetItems($player);
  switch ($items[$index]) {
    case "mini_forcefield_red":
    case "mini_forcefield_yellow":
    case "mini_forcefield_blue":
      if ($preventable) $damage -= ItemDamagePeventionAmount($player, $index);
      DestroyItemForPlayer($player, $index);
      break;
    case "dissolution_sphere_yellow":
      if ($preventable) $damage -= ItemDamagePeventionAmount($player, $index, $damage);
      break;
    default:
      break;
  }
  return $damage;
}

function ItemTakeDamageAbilities($player, $damage, $source, $type, $preventable = true)
{
  if ($type != "") {
    $otherPlayer = $player == 1 ? 2 : 1;
    $preventable = CanDamageBePrevented($otherPlayer, $damage, $type, $source);
  }
  $items = &GetItems($player);
  for ($i = count($items) - ItemPieces(); $i >= 0 && $damage > 0; $i -= ItemPieces()) {
    switch ($items[$i]) {
      case "absorption_dome_yellow":
        if ($damage > $items[$i + 1]) {
          if ($preventable) $damage -= $items[$i + 1];
          $items[$i + 1] = 0;
        } else {
          $items[$i + 1] -= $damage;
          if ($preventable) $damage = 0;
        }
        if ($items[$i + 1] <= 0) DestroyItemForPlayer($player, $i);
        break;
      default:
        break;
    }
  }
  return $damage;
}

function ItemStartTurnAbility($index)
{
  global $mainPlayer;
  $mainItems = &GetItems($mainPlayer);
  switch ($mainItems[$index]) {
    case "teklo_core_blue":
    case "dissipation_shield_yellow":
    case "dissolution_sphere_yellow":
    case "signal_jammer_blue":
      AddLayer("TRIGGER", $mainPlayer, $mainItems[$index], "-", "-", $mainItems[$index + 4]);
      break;
    case "null_time_zone_blue":
      if ($mainItems[$index + 1] > 0) {
        AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_remove_a_Steam_Counter_and_keep_" . CardLink($mainItems[$index], $mainItems[$index]) . "_(chosen_name:_" . $mainItems[$index+8] . ")", 1);
        AddDecisionQueue("REMOVECOUNTERITEMORDESTROYUID", $mainPlayer, $mainItems[$index+4], 1);
      } else {
        WriteLog(CardLink($mainItems[$index], $mainItems[$index]) . " was destroyed");
        DestroyItemForPlayer($mainPlayer, $index);
      }
      break;
    case "grinding_gears_blue":
    case "prismatic_lens_yellow":
    case "quantum_processor_yellow":
    case "polarity_reversal_script_red":
    case "penetration_script_yellow":
    case "security_script_blue":
    case "backup_protocol_red_red":
    case "backup_protocol_yel_yellow":
    case "backup_protocol_blu_blue":
    case "boom_grenade_red":
    case "boom_grenade_yellow":
    case "boom_grenade_blue":
    case "dissolving_shield_red":
    case "dissolving_shield_yellow":
    case "dissolving_shield_blue":
    case "hadron_collider_red":
    case "hadron_collider_yellow":
    case "hadron_collider_blue":
    case "mini_forcefield_red":
    case "mini_forcefield_yellow":
    case "mini_forcefield_blue":
    case "overload_script_red":
    case "mhz_script_yellow":
    case "autosave_script_blue":
    case "cerebellum_processor_blue":
    case "clamp_press_blue":
    case "golden_cog":
    case "copper_cog_blue":
      if ($mainItems[$index + 1] > 0 && GetItemGemState($mainPlayer, $mainItems[$index], $index)) --$mainItems[$index + 1];
      else if($mainItems[$index + 1] > 0) {
        AddDecisionQueue("YESNO", $mainPlayer, "if you want to remove a Steam Counter and keep " . CardLink($mainItems[$index], $mainItems[$index]) . " and keep it in play?");
        AddDecisionQueue("REMOVECOUNTERITEMORDESTROYUID", $mainPlayer, $mainItems[$index + 4]);
      }
      else DestroyItemForPlayer($mainPlayer, $index);
      break;
    case "tick_tock_clock_red":
      if ($mainItems[$index + 1] > 0) --$mainItems[$index + 1];
      else {
        DestroyItemForPlayer($mainPlayer, $index);
        DealDamageAsync($mainPlayer, 1);
        WriteLog(CardLink("tick_tock_clock_red", "tick_tock_clock_red") . " deals 1 damage to Player " . $mainPlayer . ".");
      }
      break;
    default:
      break;
  }
}

function ItemEndTurnAbilities()
{
  global $mainPlayer;
  $items = &GetItems($mainPlayer);
  for ($i = count($items) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
    //untap
    Tap("MYITEMS-$i", $mainPlayer, 0);
    $remove = false;
    switch ($items[$i]) {
      case "talisman_of_balance_blue":
        $remove = TalismanOfBalanceEndTurn();
        break;
      default:
        break;
    }
    if (ItemModalities($items[$i]) != "-") $items[$i + 8] = ItemModalities($items[$i]);
    if ($remove) DestroyItemForPlayer($mainPlayer, $i);
  }
}

function ItemDamageTakenAbilities($player, $damage)
{
  $otherPlayer = $player == 1 ? 2 : 1;
  $items = &GetItems($otherPlayer);
  for ($i = count($items) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
    $remove = false;
    switch ($items[$i]) {
      case "talisman_of_warfare_yellow":
        if (IsHeroAttackTarget() && $damage == 2) {
          WriteLog("Talisman of Warfare destroyed both player's arsenal");
          DestroyArsenal($player, effectController: $otherPlayer);
          DestroyArsenal($otherPlayer, effectController: $otherPlayer);
          $remove = true;
        }
        break;
      default:
        break;
    }
    if ($remove) DestroyItemForPlayer($otherPlayer, $i);
  }
}

function SteamCounterLogic($cardID, $playerID, $uniqueID)
{
  global $CS_NumBoosted;
  $counters = ETASteamCounters($cardID);
  switch ($cardID) {
    case "absorption_dome_yellow":
      $counters += GetClassState($playerID, $CS_NumBoosted);
      break;
    default:
      break;
  }
  if (ClassContains($cardID, "MECHANOLOGIST", $playerID) && CardCost($cardID) >= 0 && CardCost($cardID) <= 2) {
    $items = &GetItems($playerID);
    for ($i = count($items) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
      if ($items[$i] == "plasma_mainline_red") {
        AddLayer("TRIGGER", $playerID, $items[$i], $uniqueID, "-", $items[$i + 4]);
      }
    }
  }
  if(SearchCurrentTurnEffects("master_cog_yellow-".$cardID, $playerID, true)) $counters += 1;
  return $counters;
}

function ItemDamagePeventionAmount($player, $index, $damage=0, $preventable=true)
{
  $items = &GetItems($player);
  switch ($items[$index]) {
    case "mini_forcefield_red":
    case "mini_forcefield_yellow":
    case "mini_forcefield_blue":
      return $items[$index + 1];
    case "dissolution_sphere_yellow":
      if ($damage == 1 && $preventable) return 1;
      else return 0;
    default:
      return 0;
  }
}

function ItemBlockModifier($cardID)
{
  global $mainPlayer, $defPlayer, $CombatChain;
  $items = &GetItems($mainPlayer);
  $blockModifier = 0;
  for ($i = 0; $i < count($items); $i += ItemPieces()) {
    switch ($items[$i]) {
      case "polarity_reversal_script_red":
        $type = CardType($cardID);
        $typeEvo = "";
        if (substr($cardID, -5) == "equip") {
          $typeEvo = CardType(substr($cardID,0, strlen($cardID) - 6));
        }
        $attackID = $CombatChain->AttackCard()->ID();
        if ((DelimStringContains($type, "A") || $type == "AA" || $typeEvo == "A") && CardType($attackID) == "AA" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer)) --$blockModifier;
        break;
      default:
        break;
    }
  }
  $items = &GetItems($defPlayer);
  for ($i = 0; $i < count($items); $i += ItemPieces()) {
    switch ($items[$i]) {
      case "security_script_blue":
        if (CardType($cardID) == "AA" && ClassContains($cardID, "MECHANOLOGIST", $defPlayer)) ++$blockModifier;
        break;
      default:
        break;
    }
  }
  return $blockModifier;
}

function ItemPowerModifiers(&$powerModifiers)
{
  global $mainPlayer, $CombatChain;
  $items = &GetItems($mainPlayer);
  $modifier = 0;
  for ($i = 0; $i < count($items); $i += ItemPieces()) {
    switch ($items[$i]) {
      case "penetration_script_yellow":
        $attackID = $CombatChain->AttackCard()->ID();
        if (CardType($attackID) == "AA" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer)) {
          $modifier += 1;
          array_push($powerModifiers, $items[$i]);
          array_push($powerModifiers, 1);
        }
        break;
      case "clamp_press_blue":
        $attackID = $CombatChain->AttackCard()->ID();
        if (SubtypeContains($attackID, "Wrench")) {
          $modifier += 2;
          array_push($powerModifiers, $items[$i]);
          array_push($powerModifiers, 2);
        }
        break;
      default:
        break;
    }
  }
  return $modifier;
}

function ItemsPowerModifiers($cardID, $player, $from)
{
  $items = &GetItems($player);
  $powerModifier = 0;
  for ($i = 0; $i < count($items); $i += ItemPieces()) {
    switch ($items[$i]) {
      case "penetration_script_yellow":
        if (CardType($cardID) == "AA" && ClassContains($cardID, "MECHANOLOGIST", $player) && $from == "CC") ++$powerModifier;
      case "clamp_press_blue":
        if (SubtypeContains($cardID, "Wrench")) $powerModifier += 2;
      default:
        break;
    }
  }
  return $powerModifier;
}

//checks if a cardname is blocked by null time zone
function FindNullTime($cardName) 
{
  global $mainPlayer, $defPlayer;
  
  $foundNullTime = SearchItemForModalities($cardName, $mainPlayer, "null_time_zone_blue") != -1;
  $foundNullTime = $foundNullTime || SearchItemForModalities($cardName, $defPlayer, "null_time_zone_blue") != -1;
  return $foundNullTime;
}