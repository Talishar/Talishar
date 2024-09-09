<?php


function PutItemIntoPlayForPlayer($item, $player, $steamCounterModifier = 0, $number = 1, $effectController = "", $isToken = false, $mainPhase = "True", $from = "-")
{
  global $turn, $EffectContext;
  $otherPlayer = ($player == 1 ? 2 : 1);
  if (!DelimStringContains(CardSubType($item), "Item") && $item != "DTD164") return;
  if (TypeContains($item, "T", $player)) $isToken = true;
  $numMinusTokens = 0;
  $numMinusTokens = CountCurrentTurnEffects("HVY209", $player) + CountCurrentTurnEffects("HVY209", $otherPlayer);
  if ($numMinusTokens > 0 && $isToken && (TypeContains($EffectContext, "AA", $player) || TypeContains($EffectContext, "A", $player))) $number -= $numMinusTokens;
  $items = &GetItems($player);
  $myHoldState = ItemDefaultHoldTriggerState($item);
  if ($myHoldState == 0 && HoldPrioritySetting($player) == 1) $myHoldState = 1;
  $theirHoldState = ItemDefaultHoldTriggerState($item);
  if ($theirHoldState == 0 && HoldPrioritySetting($otherPlayer) == 1) $theirHoldState = 1;
  for ($i = 0; $i < $number; ++$i) {
    $uniqueID = GetUniqueId($item, $player);
    $steamCounters = SteamCounterLogic($item, $player, $uniqueID) + $steamCounterModifier;
    $index = count($items);
    array_push($items, $item);
    array_push($items, $steamCounters);
    array_push($items, 2);
    array_push($items, ItemUses($item));
    array_push($items, $uniqueID);
    array_push($items, $myHoldState);
    array_push($items, $theirHoldState);
    array_push($items, 0);
    array_push($items, ItemModalities($item));
    array_push($items, $from);
    if (HasCrank($item, $player)) Crank($player, $index, $mainPhase);
  }
  if (($symbiosisIndex = FindCharacterIndex($player, "EVO003")) > 0 && ClassContains($item, "MECHANOLOGIST", $player)) {
    $char = &GetPlayerCharacter($player);
    if ($char[$symbiosisIndex + 2] < 6) ++$char[$symbiosisIndex + 2];
  }
  if ($item == "DYN243") {
    $char = &GetPlayerCharacter($player);
    $hero = ShiyanaCharacter($char[0], $player);
    if ($number > 0 && ($hero == "HVY047" || $hero == "HVY048") && SearchCurrentTurnEffects($hero . "-1", $player, true) && $effectController == $player) {
      $EffectContext = $hero;
      WriteLog("Player $player drew a card from Victor");
      Draw($player);
    }
  }
  if ($item == "EVO073") AddLayer("TRIGGER", $player, $item);
}

function ItemUses($cardID)
{
  switch ($cardID) {
    case "EVR070":
      return 3;
    default:
      return 1;
  }
}

function ItemModalities($cardID)
{
  switch ($cardID) {
    case 'EVR070':
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
    case "WTR162":
    case "WTR170":
    case "WTR171":
    case "WTR172":
    case "ELE143":
    case "ELE172":
    case "ELE201":
    case "EVR176":
    case "EVR177":
    case "EVR178":
    case "EVR179":
    case "EVR180":
    case "EVR181":
    case "EVR182":
    case "EVR183":
    case "EVR184":
    case "EVR185":
    case "EVR186":
    case "EVR187":
    case "OUT054":
    case "EVO081":
    case "EVO082":
    case "EVO083":
      DestroyItemForPlayer($currentPlayer, $index);
      break;
    case "ARC035":
      AddAdditionalCost($currentPlayer, $items[$index + 1]);
      DestroyItemForPlayer($currentPlayer, $index);
      break;
    case "ARC010":
    case "ARC018":
      if ($from == "PLAY" && $items[$index + 1] > 0 && count($combatChain) > 0) {
        $items[$index + 1] -= 1;
        $items[$index + 2] = 1;
      }
      break;
    case "CRU105":
      if ($from == "PLAY" && $items[$index + 1] > 0) {
        $items[$index + 1] -= 1;
        AddAdditionalCost($currentPlayer, "PAID");
      }
      break;
    case "EVO071":
    case "EVO072":
      if ($from == "PLAY") {
        $items[$index + 2] = 1;
      }
      break;
    case "EVO075":
    case "EVO076":
    case "EVO077":
      RemoveItem($currentPlayer, $index);
      if (substr($items[$index + 9], 0, 5) != "THEIR") {
        $deck = new Deck($currentPlayer);
      } else {
        $deck = new Deck($otherPlayer);
      }
      $deck->AddBottom($cardID, from: "PLAY");
      break;
    case "EVO087":
    case "EVO088":
    case "EVO089":
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      --$items[$index + 1];
      if ($items[$index + 1] <= 0) DestroyItemForPlayer($currentPlayer, $index);
      break;
    case "AIO026":
      if ($from == "PLAY") {
        $items[$index + 2] = 1;
      }
      break;
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
      case "EVR189":
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
    if (CardType($items[$index]) != "T" && GoesWhereAfterResolving($items[$index], "PLAY", $player) == "GY")
      AddGraveyard($items[$index], $player, "PLAY");
    IncrementClassState($player, $CS_NumItemsDestroyed);
  }
  $cardID = $items[$index];
  for ($i = $index + ItemPieces() - 1; $i >= $index; --$i) {
    if ($items[$i] == "DYN492c") {
      $indexWeapon = FindCharacterIndex($player, "DYN492a");
      DestroyCharacter($player, $indexWeapon);
      $indexEquipment = FindCharacterIndex($player, "DYN492b");
      DestroyCharacter($player, $indexEquipment, true);
      SearchCurrentTurnEffects("DYN089-UNDER", $player, true);
    }
    unset($items[$i]);
  }
  $items = array_values($items);
  return $cardID;
}

function StealItem($srcPlayer, $index, $destPlayer)
{
  $srcItems = &GetItems($srcPlayer);
  $destItems = &GetItems($destPlayer);
  for ($i = 0; $i < ItemPieces(); ++$i) {
    if ($srcItems[$i] == "DYN492c") {
      $indexEquipment = FindCharacterIndex($srcPlayer, "DYN492b");
      RemoveCharacter($srcPlayer, $indexEquipment);
      $indexWeapon = FindCharacterIndex($srcPlayer, "DYN492a");
      RemoveCharacter($srcPlayer, $indexWeapon);
      SearchCurrentTurnEffects("DYN089-UNDER", $srcPlayer, true);
    }
    array_push($destItems, $srcItems[$index + $i]);
    unset($srcItems[$index + $i]);
  }
  $srcItems = array_values($srcItems);
}

function GetItemGemState($player, $cardID)
{
  global $currentPlayer;
  $items = &GetItems($player);
  $offset = ($currentPlayer == $player ? 5 : 6);
  $state = 0;
  for ($i = 0; $i < count($items); $i += ItemPieces()) {
    if ($items[$i] == $cardID && $items[$i + $offset] > $state) $state = $items[$i + $offset];
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
      case "DYN094":
        if ($attackSubType == "Gun" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer)) {
          AddLayer("TRIGGER", $mainPlayer, $items[$i], $attackID, "ITEMHITEFFECT", $items[$i + 4]);
        }
        break;
      case "EVO074":
        if (IsHeroAttackTarget() && $attackType == "AA" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer)) {
          AddLayer("TRIGGER", $mainPlayer, $items[$i], $attackID, "ITEMHITEFFECT", $items[$i + 4]);
        }
        break;
      case "EVO084":
      case "EVO085":
      case "EVO086":
        if (IsHeroAttackTarget() && $attackType == "AA" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer)) {
          AddLayer("TRIGGER", $mainPlayer, $items[$i], $attackID, "ITEMHITEFFECT", $items[$i + 4]);
        }
        break;
      case "EVO098":
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
    case "EVO093":
    case "EVO094":
    case "EVO095":
      if ($preventable) $damage -= ItemDamagePeventionAmount($player, $index);
      DestroyItemForPlayer($player, $index);
      break;
    default:
      break;
  }
  return $damage;
}

function ItemTakeDamageAbilities($player, $damage, $source, $type, $preventable = true)
{
  if ($type != "") {
    $otherPlayer = ($player == 1 ? 2 : 1);
    $preventable = CanDamageBePrevented($otherPlayer, $damage, $type, $source);
  }
  $items = &GetItems($player);
  for ($i = count($items) - ItemPieces(); $i >= 0 && $damage > 0; $i -= ItemPieces()) {
    switch ($items[$i]) {
      case "CRU104":
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
    case "ARC007":
    case "ARC035":
    case "EVR069":
    case "EVR071":
      AddLayer("TRIGGER", $mainPlayer, $mainItems[$index], "-", "-", $mainItems[$index + 4]);
      break;
    case "EVO070":
    case "EVO071":
    case "EVO072":
    case "EVO078":
    case "EVO079":
    case "EVO080":
    case "EVO081":
    case "EVO082":
    case "EVO083":
    case "EVO084":
    case "EVO085":
    case "EVO086":
    case "EVO087":
    case "EVO088":
    case "EVO089":
    case "EVO090":
    case "EVO091":
    case "EVO092":
    case "EVO093":
    case "EVO094":
    case "EVO095":
    case "EVO096":
    case "EVO097":
    case "EVO098":
    case "AIO026":
      if ($mainItems[$index + 1] > 0) --$mainItems[$index + 1];
      else DestroyItemForPlayer($mainPlayer, $index);
      break;
    case "EVO074":
      if ($mainItems[$index + 1] > 0) --$mainItems[$index + 1];
      else {
        DestroyItemForPlayer($mainPlayer, $index);
        DealDamageAsync($mainPlayer, 1);
        WriteLog(CardLink("EVO074", "EVO074") . " deals 1 damage to Player " . $mainPlayer . ".");
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
    $remove = false;
    switch ($items[$i]) {
      case "EVR188":
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
  $otherPlayer = ($player == 1 ? 2 : 1);
  $items = &GetItems($otherPlayer);
  for ($i = count($items) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
    $remove = false;
    switch ($items[$i]) {
      case "EVR193":
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

function SteamCounterLogic($item, $playerID, $uniqueID)
{
  global $CS_NumBoosted;
  $counters = ETASteamCounters($item);
  switch ($item) {
    case "CRU104":
      $counters += GetClassState($playerID, $CS_NumBoosted);
      break;
    default:
      break;
  }
  if (ClassContains($item, "MECHANOLOGIST", $playerID) && CardCost($item) >= 0 && CardCost($item) <= 2) {
    $items = &GetItems($playerID);
    for ($i = count($items) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
      if ($items[$i] == "DYN093") {
        AddLayer("TRIGGER", $playerID, $items[$i], $uniqueID, "-", $items[$i + 4]);
      }
    }
  }
  return $counters;
}

function ItemDamagePeventionAmount($player, $index)
{
  $items = &GetItems($player);
  switch ($items[$index]) {
    case "EVO093":
    case "EVO094":
    case "EVO095":
      return $items[$index + 1];
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
      case "EVO078":
        $type = CardType($cardID);
        $typeEvo = "";
        if (SubtypeContains($cardID, "Evo", $defPlayer)) {
          $number = intval(substr($cardID, 3));
          $number = $number - 400;
          $typeEvo = CardType("EVO0" . $number);
        }
        $attackID = $CombatChain->AttackCard()->ID();
        if (($type == "A" || $type == "AA" || $typeEvo == "A") && CardType($attackID) == "AA" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer)) --$blockModifier;
        break;
      default:
        break;
    }
  }
  $items = &GetItems($defPlayer);
  for ($i = 0; $i < count($items); $i += ItemPieces()) {
    switch ($items[$i]) {
      case "EVO080":
        if (CardType($cardID) == "AA" && ClassContains($cardID, "MECHANOLOGIST", $defPlayer)) ++$blockModifier;
        break;
      default:
        break;
    }
  }
  return $blockModifier;
}

function ItemAttackModifiers(&$attackModifiers)
{
  global $mainPlayer, $CombatChain;
  $items = &GetItems($mainPlayer);
  $modifier = 0;
  for ($i = 0; $i < count($items); $i += ItemPieces()) {
    switch ($items[$i]) {
      case "EVO079":
        $attackID = $CombatChain->AttackCard()->ID();
        if (CardType($attackID) == "AA" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer)) {
          $modifier += 1;
          array_push($attackModifiers, "Item Ability");
          array_push($attackModifiers, 1);
        }
      default:
        break;
    }
  }
  return $modifier;
}

function ItemsAttackModifiers($cardID, $player, $from)
{
  $items = &GetItems($player);
  $attackModifier = 0;
  for ($i = 0; $i < count($items); $i += ItemPieces()) {
    switch ($items[$i]) {
      case "EVO079":
        if (CardType($cardID) == "AA" && ClassContains($cardID, "MECHANOLOGIST", $player) && $from == "CC") ++$attackModifier;
      default:
        break;
    }
  }
  return $attackModifier;
}

