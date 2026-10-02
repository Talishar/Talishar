<?php

// Building and starting puzzle games. Needs the engine loaded (Libraries/PuzzleEngine.php).
include_once __DIR__ . "/PuzzleGame.php";
include_once __DIR__ . "/PuzzleVerify.php";
include_once __DIR__ . "/PuzzleScript.php";
include_once __DIR__ . "/PuzzleAnalysis.php";
include_once __DIR__ . "/FormatCodes.php";

// Everything a puzzle game needs besides its gamestate. A daily puzzle freezes this when it is scheduled.
function BuildPuzzleSetup($conn, $candidateID, $mode)
{
  $verified = VerifyPuzzleCandidate($conn, $candidateID);
  $row = LoadPuzzleCandidate($conn, $candidateID);
  $content = $row ? @gzuncompress($row["gamestate"]) : false;
  if ($content === false) return null;
  $kind = intval($row["kind"]);
  if ($kind == PUZZLE_KIND_SURVIVE) $mode = "survive";
  else if (!isset(PUZZLE_MODES[$mode]) || $mode === "survive") $mode = "lethal";
  $player = intval($row["player"]);
  $proof = $verified["proof"] ?? CurrentPuzzleProof($row["proof"]);
  $baseline = $verified["baseline"] ?? CurrentPuzzleBaseline($row["baseline"]);
  $proven = ($proof["status"] ?? "") === "proven";
  $healths = explode(" ", trim(explode("\r\n", $content)[0]));
  $realLife = intval($healths[($kind == PUZZLE_KIND_SURVIVE ? $player : 3 - $player) - 1] ?? 0);
  $steps = $proven ? json_decode($row["solution"] ?? "", true) : null;
  $lesson = $proven ? PuzzleLesson($kind, $steps, $baseline, $proof) : null;
  $analysis = AnalyzePuzzlePosition($content, $player, json_decode($row["meta"] ?? "", true), $proof, $baseline, $kind, $steps);

  $bars = null;
  if ($mode === "damage") {
    [$line] = PuzzleCandidateLines($row);
    if ($line !== null) $bars = PuzzleDamageBars($content, $player, $line, $row["format"], $proof ?? []);
    if ($lesson !== null) {
      $lesson["trick"] = $lesson["themeText"];
      if (count($lesson["keyCards"]) > 0) $lesson["trick"] .= " The key: " . PuzzleCardTokens($lesson["keyCards"]) . ".";
    }
  }
  return [
    "candidateId" => intval($candidateID),
    "mode" => $mode,
    "player" => $player,
    "life" => $mode === "damage" ? PUZZLE_DAMAGE_LIFE : ($proven ? intval($proof["life"]) : $realLife),
    "realLife" => $realLife,
    "format" => $row["format"],
    "hero" => $row["hero"],
    "heroName" => GeneratedCardName($row["hero"]),
    "opponentHero" => $row["opponent_hero"],
    "opponentHeroName" => GeneratedCardName($row["opponent_hero"]),
    "proven" => $proven,
    "difficulty" => $analysis["difficulty"],
    "score" => $analysis["score"],
    "theme" => $lesson["theme"] ?? null,
    "themeText" => $lesson["themeText"] ?? null,
    "keyCards" => $lesson["keyCards"] ?? [],
    "hints" => $lesson["hints"] ?? [],
    "trick" => $lesson["trick"] ?? "",
    "solution" => is_array($steps) ? $steps : [],
    "bars" => $bars
  ];
}

// Runs the scripted attacker of a survive puzzle until the solver has to answer.
function PuzzleKickoff($gameName, $player)
{
  $saved = [];
  foreach (["gameName", "filepath", "filename", "lastWrittenGamestate"] as $name) $saved[$name] = $GLOBALS[$name] ?? null;
  $GLOBALS["gameName"] = $gameName;
  $GLOBALS["filepath"] = "./Games/$gameName/";
  $GLOBALS["filename"] = "./Games/$gameName/gamestate.txt";
  $GLOBALS["lastWrittenGamestate"] = (string)@file_get_contents("./Games/$gameName/gamestate.txt");
  ob_start();
  try {
    PuzzleStep($player);
    FlushLogBuffer();
  } finally {
    ob_end_clean();
    foreach ($saved as $name => $value) $GLOBALS[$name] = $value;
  }
}

// $daily: ["date", "number", "userId", "practice"] for a daily puzzle game.
function CreatePuzzleGameFromSetup($setup, $row, $useruid, $userId, $daily = null, $hintsUsed = 0)
{
  global $gameFileHandler, $p1Data, $p2Data, $gameStatus, $format, $visibility, $firstPlayerChooser, $firstPlayer, $p1Key, $p2Key;
  global $p1uid, $p2uid, $p1id, $p2id, $gameDescription, $hostIP, $p1IsPatron, $p2IsPatron, $p1DeckLink, $p2DeckLink;
  global $p1IsChallengeActive, $p2IsChallengeActive, $joinerIP, $p1deckbuilderID, $p2deckbuilderID;
  global $p1Matchups, $p2Matchups, $p1StartingHealth, $p1ContentCreatorID, $p2ContentCreatorID;
  global $p1SideboardSubmitted, $p2SideboardSubmitted, $p1StartingEquipment, $p2StartingEquipment, $p1IsAI, $p2IsAI, $gameGUID;
  global $p1MetafyTiers, $p2MetafyTiers, $p1MetafyCommunities, $p2MetafyCommunities, $p1DisplayName, $p2DisplayName;
  global $p1EquipmentSubmitted, $p2EquipmentSubmitted;

  $content = @gzuncompress($row["gamestate"]);
  if ($content === false) return ["error" => "Puzzle candidate not found"];
  $gameName = GetGameCounter("./");
  $directory = "./Games/$gameName/";
  if (file_exists($directory) || !mkdir($directory, 0700, true)) return ["error" => "Game file could not be created."];

  $player = intval($setup["player"]);
  $mode = $setup["mode"];
  $p1Key = bin2hex(random_bytes(32));
  $p2Key = bin2hex(random_bytes(32));
  $gamestate = PreparePuzzleGamestate($content, $player, intval($setup["life"]), $p1Key, $p2Key, $mode);
  $lines = explode("\r\n", $gamestate);
  $p1Hero = explode(" ", trim($lines[3]))[0];
  $p2Hero = explode(" ", trim($lines[21]))[0];

  $p1Data = [1];
  $p2Data = [2];
  $gameStatus = 5; //MGS_GameStarted
  $format = FormatName(intval($setup["format"]));
  $visibility = "private";
  $firstPlayerChooser = "";
  $firstPlayer = trim($lines[39]);
  $p1uid = $player == 1 ? $useruid : "Puzzle Bot";
  $p2uid = $player == 2 ? $useruid : "Puzzle Bot";
  $p1id = $player == 1 && $userId > 0 ? $userId : "-";
  $p2id = $player == 2 && $userId > 0 ? $userId : "-";
  $gameDescription = $daily !== null ? "Daily puzzle #" . intval($daily["number"]) : "Puzzle #" . intval($setup["candidateId"]);
  $hostIP = GetClientIP();
  $p1IsPatron = "";
  $p2IsPatron = "";
  $p1DeckLink = "";
  $p2DeckLink = "";
  $p1IsChallengeActive = "0";
  $p2IsChallengeActive = "0";
  $joinerIP = "";
  $p1Matchups = [];
  $p2Matchups = [];
  $p1deckbuilderID = "";
  $p2deckbuilderID = "";
  $p1StartingHealth = "";
  $p1ContentCreatorID = "";
  $p2ContentCreatorID = "";
  $p1SideboardSubmitted = "1";
  $p2SideboardSubmitted = "1";
  $p1StartingEquipment = [];
  $p2StartingEquipment = [];
  $p1IsAI = $player == 1 ? "0" : "1";
  $p2IsAI = $player == 2 ? "0" : "1";
  $gameGUID = GenerateGameGUID();
  $p1DisplayName = $p1uid;
  $p2DisplayName = $p2uid;
  $p1EquipmentSubmitted = "1";
  $p2EquipmentSubmitted = "1";

  $gameFileHandler = @fopen($directory . "GameFile.txt", "w");
  if ($gameFileHandler === false) return ["error" => "Game file could not be initialized."];
  include_once __DIR__ . "/../MenuFiles/WriteGamefile.php";
  WriteGameFile();

  $info = [
    "candidateId" => intval($setup["candidateId"]),
    "mode" => $mode,
    "player" => $player,
    "life" => intval($setup["life"]),
    "hints" => $setup["hints"] ?? [],
    "trick" => $setup["trick"] ?? "",
    "bars" => $setup["bars"] ?? null,
    "hintsUsed" => min(count($setup["hints"] ?? []), intval($hintsUsed)),
    "tries" => 1
  ];
  if ($daily !== null) $info["daily"] = $daily;
  file_put_contents($directory . "gamestate.txt", $gamestate);
  file_put_contents($directory . "beginTurnGamestate.txt", $gamestate);
  file_put_contents($directory . PUZZLE_START_FILE, $gamestate);
  file_put_contents($directory . "gamelog.txt", PuzzleIntroLog($info));
  file_put_contents($directory . PUZZLE_MARKER_FILE, (string)intval($setup["candidateId"]));
  WritePuzzleInfo($gameName, $info);
  if ($mode === "survive") {
    [, $script] = PuzzleCandidateLines($row);
    if ($script === null) return ["error" => "This survive puzzle has no recorded attack."];
    WritePuzzleScript($gameName, $script["player"], $script["line"]);
  }

  $currentTime = round(microtime(true) * 1000);
  WriteCache($gameName, "1!$currentTime!$currentTime!-1!-1!$currentTime!" . GeneratedSetID($p1Hero) . "!" . GeneratedSetID($p2Hero)
    . "!0!0!0!0!" . FormatCode($format) . "!$gameStatus!0!0");
  WriteGamestateCache($gameName, $gamestate);
  if ($mode === "survive") PuzzleKickoff($gameName, $player);
  GamestateUpdated($gameName);

  return [
    "gameName" => $gameName,
    "playerID" => $player,
    "authKey" => $player == 1 ? $p1Key : $p2Key
  ];
}
