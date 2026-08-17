<?php

function PayGoldInstead($player, $cardID) {
  if (CountItemByName("Gold", $player) > 0) {
    $goldChoices = array_values(array_filter(explode(",", GetGoldIndices($player))));
    $hand = &GetHand($player);
    $resources = &GetResources($player);
    $mustPayWithGold = count($hand) == 0 && ($resources[0] ?? 0) < 2;

    if ($mustPayWithGold && GoldChoicesAreEquivalent($player, $goldChoices)) {
      AddDecisionQueue("PASSPARAMETER", $player, $goldChoices[0]);
    }
    else {
      $phase = $mustPayWithGold ? "CHOOSEGOLDTOPAY" : "PAYGOLDORPITCH";
      $canUseResources = ($resources[0] ?? 0) >= 2;
      $context = $mustPayWithGold
        ? "Choose a Gold to destroy"
        : ($canUseResources ? "Choose a Gold to destroy or use floating resources" : "Choose a Gold to destroy or a card to pitch");
      $choices = $mustPayWithGold ? implode(",", $goldChoices) : GetGoldOrPitchIndices($player, $cardID);
      AddDecisionQueue("SETDQCONTEXT", $player, $context, 1);
      AddDecisionQueue($phase, $player, $choices, 1);
    }
    QueueGoldOrPitchChoiceResult($player, $cardID);
  }
}

function GoldChoicesAreEquivalent($player, $goldChoices) {
  if (count($goldChoices) < 2) return true;
  $firstGold = GetMZCard($player, $goldChoices[0]);
  $choiceCount = count($goldChoices);
  for ($i = 1; $i < $choiceCount; ++$i) {
    if (GetMZCard($player, $goldChoices[$i]) != $firstGold) return false;
  }
  return true;
}

function QueueGoldOrPitchChoiceResult($player, $cardID) {
  AddDecisionQueue("SETDQVAR", $player, "goldOrPitchChoice");
  AddDecisionQueue("PASSPARAMETER", $player, $cardID);
  AddDecisionQueue("SETDQVAR", $player, "goldOrPitchCard");
}

function GetGoldOrPitchIndices($player, $cardID = "-") {
  $choices = array_filter(explode(",", GetGoldIndices($player)));
  $hand = &GetHand($player);
  $resources = &GetResources($player);
  if (($resources[0] ?? 0) >= 2) return implode(",", array_unique($choices));
  $handPieces = HandPieces();
  $handCount = count($hand);
  for ($i = 0; $i < $handCount; $i += $handPieces) {
    $restriction = "";
    if (PitchValue($hand[$i]) > 0 && IsPlayable($hand[$i], "P", "HAND", $i, $restriction, $player, $cardID)) {
      $choices[] = "MYHAND-$i";
    }
  }
  return implode(",", array_unique($choices));
}

function ResolveGoldOrPitch($player, $cardID, $choice) {
  if ($choice == "PASS") return $choice;

  $validChoices = array_flip(explode(",", GetGoldOrPitchIndices($player, $cardID)));
  if (!isset($validChoices[$choice])) return "PASS";

  [$zone, $index] = array_pad(explode("-", $choice, 2), 2, -1);
  if ($zone == "MYHAND") {
    $hand = &GetHand($player);
    $index = intval($index);
    if (!isset($hand[$index]) || PitchValue($hand[$index]) < 1) return "PASS";

    $pitchedCard = $hand[$index];
    array_splice($hand, $index, HandPieces());
    $resources = &GetResources($player);
    $resources[0] += PitchValue($pitchedCard);
    $pitch = &GetPitch($player);
    $pitch[] = $pitchedCard;
    $pitch[] = GetUniqueId($pitchedCard, $player);
    WriteLog("Player " . $player . " pitched " . CardLink($pitchedCard, $pitchedCard));
    if (CardCaresAboutPitch($cardID)) AddAdditionalCost($player, $pitchedCard);
    PitchAbility($pitchedCard);
  }
  else {
    MZDestroy($player, $choice);
    AddCurrentTurnEffect("$cardID-PAID", $player);
  }
  return $choice;
}

function ResolvePendingGoldOrPitch($player, $cardID, $resolve = true) {
  global $dqVars;
  if (($dqVars["goldOrPitchCard"] ?? "") != $cardID) return;
  $choice = $dqVars["goldOrPitchChoice"] ?? "PASS";
  unset($dqVars["goldOrPitchCard"], $dqVars["goldOrPitchChoice"]);
  if ($resolve) ResolveGoldOrPitch($player, $cardID, $choice);
}

function TargetSwordAttack($player) {
  $attacks = TargetAttack($player);
  $choices = [];
  foreach($attacks as $attack) {
    $cardID = GetMZCard($player, $attack);
    if (SubtypeContains($cardID, "Sword", $player))
      $choices[] = $attack;
  }
  return implode(",", $choices);
}

function DrawAndPutBack($player, $cardID) {
  Draw($player, effectSource:$cardID);
  $hand = GetHand($player);
  Await($player, "MultiZoneIndices", "indices", search:"MYHAND", subsequent:0);
  Await($player, "ChooseMultiZone", "MZIndex", context:"Put a card from hand back on top");
  Await($player, "MZRemove", "cardID");
  Await($player, "AddTopDeck", final:true);
  if (count($hand) == 1) { //handle case where the game automates putting a card back
    AddDecisionQueue("DECKCARDS", $player, "0", 1);
    AddDecisionQueue("SETDQVAR", $player, "1", 1);
    AddDecisionQueue("SETDQCONTEXT", $player, "You drew <1> and placed it back on top", 1);
    AddDecisionQueue("OK", $player, "-", 1);
    AddDecisionQueue("SETDQCONTEXT", $player, "-");
  }
}

function AddBlockingFromHand($player, $handInd) {
  $hand = &GetHand($player);
  $cardID = $hand[$handInd];
  $dominateRestricted = IsDominateActive() && NumDefendedFromHand() >= 1;
  $overpowerRestricted = IsOverpowerActive() && NumActionsBlocking() && (TypeContains($cardID, "A") || TypeContains($cardID, "AA")) >= 1;
  if (!$dominateRestricted && !$overpowerRestricted) {
    AddCombatChain($cardID, $player, "HAND", 0, -1);
    OnBlockResolveEffects($cardID);
    array_splice($hand, $handInd, 1);
  }
  else WriteLog(CardLink($cardID, $cardID) . " could not be added as a blocking card");
}
