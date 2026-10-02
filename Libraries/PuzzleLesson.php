<?php

include_once __DIR__ . "/PuzzleGame.php";
include_once __DIR__ . "/../GeneratedCode/GeneratedCardDictionaries.php";

const PUZZLE_THEMES = [
  "ATTACK_REACTION" => "An attack reaction changes the math.",
  "NON_ATTACK_ACTION" => "A non-attack action sets up the kill.",
  "INSTANT" => "Something you can play at instant speed makes the difference.",
  "ARSENAL" => "Do not forget the card in your arsenal.",
  "BANISH" => "Something in a banished zone can still be played.",
  "GRAVEYARD" => "Something in your graveyard is still useful.",
  "DECK" => "The answer is in your deck.",
  "HERO" => "Your hero's ability is part of the line.",
  "EQUIPMENT" => "Use your equipment.",
  "WEAPON" => "Your weapon is part of the line.",
  "ABILITY" => "Something you control has an ability that matters.",
  "ATTACK" => "The right attack is not the obvious one.",
  "PITCH" => "What you pitch matters as much as what you play.",
  "TRIGGER_ORDER" => "The order your triggers resolve in matters.",
  "OPT" => "Look ahead: what you leave on top of your deck matters.",
  "CHOICES" => "The choices you make on your cards decide it.",
  "SEQUENCING" => "The bot played these same cards and fell short. The order matters.",
  "COUNT" => "Count every point of damage before you commit.",
  "DEFENSE_REACTION" => "A defense reaction at the right moment saves you.",
  "INSTANT_DEFENSE" => "An instant gets you through the turn.",
  "EQUIPMENT_BLOCK" => "Your equipment is part of the defense.",
  "SAVE_BLOCKS" => "Do not block everything early. Keep something for the attack that matters.",
  "BLOCK_CHOICE" => "Which card you block with matters."
];

// Most specific first: when several key cards point at different themes, the earlier one names the puzzle.
const PUZZLE_THEME_ORDER = ["ATTACK_REACTION", "NON_ATTACK_ACTION", "INSTANT", "ARSENAL", "BANISH", "GRAVEYARD", "DECK",
  "HERO", "EQUIPMENT", "WEAPON", "ABILITY", "ATTACK"];

const PUZZLE_ZONE_THEMES = ["MYARS" => "ARSENAL", "MYBANISH" => "BANISH", "THEIRBANISH" => "BANISH",
  "MYDISCARD" => "GRAVEYARD", "MYDECK" => "DECK", "THEIRARS" => "ARSENAL"];

const PUZZLE_TYPE_THEMES = ["AR" => "ATTACK_REACTION", "A" => "NON_ATTACK_ACTION", "I" => "INSTANT", "C" => "HERO",
  "E" => "EQUIPMENT", "W" => "WEAPON", "AA" => "ATTACK"];

function PuzzleCardToken($cardID)
{
  $name = GeneratedCardName($cardID);
  if ($name === "") return $cardID;
  return "{{" . $cardID . "|" . $name . "|" . intval(GeneratedPitchValue($cardID)) . "}}";
}

function PuzzleCardTokens($cardIDs)
{
  $tokens = array_map("PuzzleCardToken", array_values(array_unique($cardIDs)));
  if (count($tokens) <= 1) return $tokens[0] ?? "";
  return implode(", ", array_slice($tokens, 0, -1)) . " and " . end($tokens);
}

// $cards minus $others, as multisets, in $cards order.
function PuzzleCardsMinus($cards, $others)
{
  $remaining = array_count_values($others);
  $result = [];
  foreach ($cards as $cardID) {
    if (($remaining[$cardID] ?? 0) > 0) --$remaining[$cardID];
    else $result[] = $cardID;
  }
  return $result;
}

function PuzzleStepCards($steps, $kinds)
{
  $cards = [];
  foreach ($steps as $step) {
    if (in_array($step["kind"] ?? "", $kinds, true)) array_push($cards, ...($step["cards"] ?? []));
  }
  return $cards;
}

function PuzzleCardTheme($cardID, $from)
{
  if (isset(PUZZLE_ZONE_THEMES[$from])) return PUZZLE_ZONE_THEMES[$from];
  return PUZZLE_TYPE_THEMES[GeneratedCardType($cardID)] ?? "ABILITY";
}

function PuzzleFirstMove($steps, $defending)
{
  foreach ($steps as $step) {
    $kind = $step["kind"] ?? "";
    $card = PuzzleCardToken($step["cards"][0] ?? "");
    if ($kind === "BLOCK" && !empty($step["target"])) {
      return "Your first defensive move: block " . PuzzleCardToken($step["target"]) . " with $card.";
    }
    if ($kind === "BLOCK") return "Your first defensive move: block with $card.";
    if ($kind === "PLAY") {
      $from = ($step["from"] ?? "") === "MYARS" ? " from your arsenal" : "";
      return $defending ? "Your first defensive move: play $card$from." : "Start by playing $card$from.";
    }
    if ($kind === "ACTIVATE") return $defending ? "Your first defensive move: activate $card." : "Start by activating $card.";
  }
  return null;
}

// Theme, key cards, three hints of growing strength and the explanation shown once the puzzle is over.
// The key cards are what the real line used that the bot did not.
function PuzzleLesson($kind, $steps, $baseline, $proof)
{
  $steps = is_array($steps) ? $steps : [];
  if (count($steps) == 0) return null;
  $real = $baseline["real"] ?? null;
  $bot = $baseline["bot"] ?? null;
  $life = intval($proof["life"] ?? ($baseline["life"] ?? 0));
  return $kind == PUZZLE_KIND_SURVIVE
    ? PuzzleSurviveLesson($steps, $real, $bot, $life)
    : PuzzleLethalLesson($steps, $real, $bot, $life);
}

function PuzzleLethalLesson($steps, $real, $bot, $life)
{
  $played = $real["played"] ?? PuzzleStepCards($steps, ["PLAY", "ACTIVATE"]);
  $botPlayed = $bot["played"] ?? [];
  $keys = $bot === null
    ? array_values(array_filter($played, fn($cardID) => GeneratedCardType($cardID) !== "AA"))
    : PuzzleCardsMinus($played, $botPlayed);
  $from = [];
  foreach ($steps as $step) {
    if (($step["kind"] ?? "") === "PLAY" && isset($step["from"])) $from[$step["cards"][0] ?? ""] = $step["from"];
  }

  $theme = null;
  $themeCards = [];
  foreach ($keys as $cardID) {
    $cardTheme = PuzzleCardTheme($cardID, $from[$cardID] ?? "");
    $rank = array_search($cardTheme, PUZZLE_THEME_ORDER, true);
    if ($theme === null || $rank < array_search($theme, PUZZLE_THEME_ORDER, true)) $theme = $cardTheme;
  }
  foreach ($keys as $cardID) {
    if (PuzzleCardTheme($cardID, $from[$cardID] ?? "") === $theme) $themeCards[] = $cardID;
  }
  $keyHint = count($themeCards) == 1 ? "The key card is " . PuzzleCardToken($themeCards[0]) . "."
    : (count($themeCards) > 1 ? "The key cards are " . PuzzleCardTokens($themeCards) . "." : null);

  if ($theme === null) {
    $kinds = array_column($steps, "kind");
    $pitchedPlays = array_values(array_intersect($real["pitched"] ?? [], $botPlayed));
    $choice = array_values(array_filter($steps, fn($step) => ($step["kind"] ?? "") === "CHOOSE" && isset($step["text"])));
    if (in_array("ORDER", $kinds, true)) $theme = "TRIGGER_ORDER";
    else if (in_array("OPT", $kinds, true)) $theme = "OPT";
    else if (count($pitchedPlays) > 0) {
      $theme = "PITCH";
      $themeCards = [$pitchedPlays[0]];
      $keyHint = "Pitch " . PuzzleCardToken($pitchedPlays[0]) . " instead of playing it.";
    } else if (count($choice) > 0) $theme = "CHOICES";
    else $theme = $bot !== null && empty($bot["won"]) ? "SEQUENCING" : "COUNT";
    $keyHint ??= $theme === "SEQUENCING" ? "Think about which card has to go first." : null;
  }

  $hints = array_values(array_filter([PUZZLE_THEMES[$theme], $keyHint, PuzzleFirstMove($steps, false)]));
  $trick = PUZZLE_THEMES[$theme];
  if (count($themeCards) > 0) $trick .= " The key: " . PuzzleCardTokens($themeCards) . ".";
  if ($bot !== null && empty($bot["won"])) $trick .= " The bot only dealt " . intval($bot["damage"] ?? 0) . " of the $life you needed.";
  return ["theme" => $theme, "themeText" => PUZZLE_THEMES[$theme], "keyCards" => $themeCards, "hints" => $hints, "trick" => $trick];
}

function PuzzleSurviveLesson($steps, $real, $bot, $life)
{
  $defense = array_merge($real["blocked"] ?? PuzzleStepCards($steps, ["BLOCK"]), $real["played"] ?? PuzzleStepCards($steps, ["PLAY", "ACTIVATE"]));
  $botDefense = array_merge($bot["blocked"] ?? [], $bot["played"] ?? []);
  $keys = $bot === null ? [] : PuzzleCardsMinus($defense, $botDefense);
  $botDied = $bot !== null && empty($bot["won"]);
  $saved = $botDied ? PuzzleCardsMinus($bot["blocked"] ?? [], $real["blocked"] ?? []) : [];
  $blocks = array_values(array_filter($steps, fn($step) => ($step["kind"] ?? "") === "BLOCK" && !empty($step["target"])));
  $types = array_map(fn($cardID) => GeneratedCardType($cardID), $keys);

  $themeCards = $keys;
  $keyHint = null;
  if (in_array("DR", $types, true)) $theme = "DEFENSE_REACTION";
  else if (in_array("I", $types, true)) $theme = "INSTANT_DEFENSE";
  else if (in_array("E", $types, true)) $theme = "EQUIPMENT_BLOCK";
  else if ($botDied && (count($saved) > 0 || count($keys) == 0)) {
    // The bot used the same cards and still died, so it blocked the wrong attacks.
    $theme = "SAVE_BLOCKS";
    $themeCards = $saved;
    if (count($blocks) > 0) {
      $block = end($blocks);
      $themeCards = $block["cards"];
      $keyHint = "Save " . PuzzleCardTokens($block["cards"]) . " for " . PuzzleCardToken($block["target"]) . ".";
    } else if (count($saved) > 0) $keyHint = "Hold on to " . PuzzleCardTokens($saved) . " for later.";
  } else $theme = "BLOCK_CHOICE";
  if (in_array($theme, ["DEFENSE_REACTION", "INSTANT_DEFENSE", "EQUIPMENT_BLOCK"], true)) {
    $type = ["DEFENSE_REACTION" => "DR", "INSTANT_DEFENSE" => "I", "EQUIPMENT_BLOCK" => "E"][$theme];
    $themeCards = array_values(array_filter($keys, fn($cardID) => GeneratedCardType($cardID) === $type));
  }
  if ($keyHint === null && count($themeCards) > 0) {
    $keyHint = (count($themeCards) == 1 ? "The key card is " : "The key cards are ") . PuzzleCardTokens($themeCards) . ".";
  }
  $hints = array_values(array_filter([PUZZLE_THEMES[$theme], $keyHint, PuzzleFirstMove($steps, true)]));
  $trick = PUZZLE_THEMES[$theme];
  if (count($themeCards) > 0) $trick .= " The key: " . PuzzleCardTokens($themeCards) . ".";
  if ($bot !== null && empty($bot["won"])) $trick .= " Blocking greedily, the bot took " . intval($bot["damage"] ?? 0) . " damage at $life life and died.";
  return ["theme" => $theme, "themeText" => PUZZLE_THEMES[$theme], "keyCards" => $themeCards, "hints" => $hints, "trick" => $trick];
}
