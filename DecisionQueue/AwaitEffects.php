<?php

function Await2($player, $function, $acceptNames=[], $returnNames=["LASTRESULT"], $lastResultName="LASTRESULT", $subsequent=1, ...$args) {
  AddDecisionQueue("SETDQVAR", $player, $lastResultName, $subsequent);
  foreach ($args as $key => $value) {
    AddDecisionQueue("PASSPARAMETER", $player, $value, $subsequent);
    AddDecisionQueue("SETDQVAR", $player, $key, $subsequent);
  }
  if (count($acceptNames) > 0) AddDecisionQueue("PASSPARAMETER", $player, implode("|", $acceptNames), $subsequent);
  AddDecisionQueue("AWAIT", $player, $function, $subsequent);
  if ($lastResultName == "FINAL") AddDecisionQueue("CLEARDQVARs", $player, "-");
  else AddDecisionQueue("SETDQVAR", $player, implode("|", $returnNames), $subsequent);
}

// $maxNumber, $minNumber, $indices = await $controller.sonata_arcanix_red($cardIDs, mode:"choose_cards");
// $indices = await $controller.MultiChooseDeck($maxNumber, $minNumber, $indices);

// Await($controller, "sonata_arcanix_red", ["cardID"], ["maxNumber","minNumber","indices"], mode: "choose_cards");
// Await($controller, "MultiChooseDeck", ["maxNumber", "minNumber", "indices"], ["indices"]);
function MultiChooseDeckAwait2($player, $lastResult) {
  global $dqVars;
  $paramNames = explode("|", $lastResult);
  $maxNumber = $dqVars[$paramNames[0]] ?? 1;
  $minNumber = $dqVars[$paramNames[1]] ?? 0;
  $indices = $dqVars[$paramNames[2]] ?? "";
  $subsequent = $paramNames[3] ? ($dqVars[$paramNames[3]] ?? 1) : 1;
  $param = "$maxNumber-$indices-$minNumber";
  PrependDecisionQueue("MULTICHOOSEDECK", $player, $param, $subsequent);
}

function Await($player, $function,  $returnName="LASTRESULT", $lastResultName="LASTRESULT", $subsequent=1, ...$args) {
  AddDecisionQueue("SETDQVAR", $player, $lastResultName, $subsequent);
  foreach ($args as $key => $value) {
    AddDecisionQueue("PASSPARAMETER", $player, $value, $subsequent);
    AddDecisionQueue("SETDQVAR", $player, $key, $subsequent);
  }
  AddDecisionQueue("AWAIT", $player, $function, $subsequent);
  if ($lastResultName == "FINAL") AddDecisionQueue("CLEARDQVARs", $player, "-");
  else AddDecisionQueue("SETDQVAR", $player, $returnName, $subsequent);
}

// $this->cardID is calling a specific function that is hard-coded to set the correct dqVars
// Await($this->controller, $this->cardID, mode:"choose_cards");
// Await($this->controller, "MultiChooseDeck", "indices");
function MultiChooseDeckAwait($player) {
  global $dqVars;
  $notSubsequent = $dqVars["notSubsequent"] ?? false;
  $maxNumber = $dqVars["maxNumber"] ?? 1;
  $minNumber = $dqVars["minNumber"] ?? 0;
  $indices = $dqVars["indices"] ?? "";
  $param = "$maxNumber-$indices-$minNumber";
  PrependDecisionQueue("MULTICHOOSEDECK", $player, $param, !$notSubsequent);
}

function AwaitLogic($player, $function) {
  global $dqVars;
  // WriteLog("Await executing $function");
  // foreach ($dqVars as $key => $value) WriteLog("$key => $value"); //uncommment to view await processing
  // does this function already exist?
  $functionAwait = $function . "Await";
  if (function_exists($functionAwait)) return $functionAwait($player);
  // is this a card specific function?
	$card = GetClass($function, $player);
  if ($card != "-") return $card->SpecificLogic();
  WriteLog("$function does not appear to exist as an Await function yet");
  return "PASS";
}

function SetLastResultAwait($player, $key) {
  global $dqVars;
  return $dqVars[$key];
}

function DeckTopCardsAwait($player) {
  global $dqVars;
  $deck = new Deck($player);
  return $deck->Top(amount: $dqVars["number"] ?? 1);
}

function RevealCardsAwait($player) {
  global $dqVars;
  $revealed = RevealCards($dqVars["cardIDs"], $player);
  return $revealed ? "REVEALED" : "PASS";
}

function MultiRemoveDeckAwait($player) {
  global $dqVars;
  $deck = new Deck($player);
  return $deck->Remove($dqVars["indices"]);
}

function MultiAddHandAwait($player) {
  global $dqVars;
  $cards = explode(",", $dqVars["cardIDs"]);
  $hand = &GetHand($player);
  $log = "";
  for ($i = 0; $i < count($cards); ++$i) {
    if ($parameter == "1") {
      if ($log != "") $log .= ", ";
      if ($i != 0 && $i == count($cards) - 1) $log .= "and ";
      $log .= CardLink($cards[$i], $cards[$i]);
    }
    array_push($hand, $cards[$i]);
  }
  if ($log != "") WriteLog("$log added to hand");
  return $dqVars["cardIDs"];
}

function ShuffleDeckAwait($player) {
  global $dqVars;
  $parameter = $dqVars["parameter"] ?? "-";
  $deck = new Deck($player);
  $deck->Shuffle($parameter);
}

function PlayAuraAwait($player) {
  global $dqVars;
  $cardID = $dqVars["cardID"];
  $number = $dqVars["number"] ?? 1;
  $isToken = $dqVars["isToken"] ?? false;
  $rogueHeronSpecial = $dqVars["rogueHeronSpecial"] ?? false;
  $numPowerCounters = $dqVars["numPowerCounters"] ?? 0;
  $from = $dqVars["from"] ?? "-";
  $additionalCosts = $dqVars["additionalCosts"] ?? "-";
  $effectController = $dqVars["effectController"] ?? "-";
  $effectSource = $dqVars["effectSource"] ?? "-";
  PlayAura($cardID, $player, $number, $isToken, $rogueHeronSpecial, $numPowerCounters, $from, $additionalCosts, $effectController, $effectSource);
}