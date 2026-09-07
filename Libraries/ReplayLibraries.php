<?php

const REPLAY_FORMAT_VERSION = 2;
const REPLAY_FORMAT_FILENAME = "replayFormat.json";

function ReplayStateFilename(string $directory, int $pointer): string
{
  return rtrim($directory, "/\\") . "/replayState_$pointer.txt.gz";
}

function ReplayCommandCount(string $commandFilename): int
{
  if (!file_exists($commandFilename)) return 0;
  $commands = file($commandFilename);
  return is_array($commands) ? count($commands) : 0;
}

function SaveReplayStateSnapshot(string $gameDirectory, ?string $gamestate = null): ?int
{
  $gameDirectory = rtrim($gameDirectory, "/\\") . "/";
  $pointer = ReplayCommandCount($gameDirectory . "commandfile.txt");
  if ($pointer < 1) return null;

  if ($gamestate === null) {
    $gamestate = $GLOBALS["lastWrittenGamestate"] ?? null;
  }
  if (!is_string($gamestate)) {
    $gamestate = @file_get_contents($gameDirectory . "gamestate.txt");
  }
  if (!is_string($gamestate)) return null;

  $compressed = gzencode($gamestate, 6);
  if ($compressed === false) return null;

  return file_put_contents(
    ReplayStateFilename($gameDirectory, $pointer),
    $compressed,
    LOCK_EX
  ) === false ? null : $pointer;
}

function ReplayStatePointers(string $directory): array
{
  $pointers = [];
  foreach (glob(rtrim($directory, "/\\") . "/replayState_*.txt.gz") ?: [] as $filename) {
    if (preg_match('/replayState_(\d+)\.txt\.gz$/', basename($filename), $matches)) {
      $pointers[] = (int)$matches[1];
    }
  }
  sort($pointers, SORT_NUMERIC);
  return array_values(array_unique($pointers));
}

function WriteReplayFormat(string $gameDirectory, string $replayDirectory): bool
{
  $gameDirectory = rtrim($gameDirectory, "/\\") . "/";
  $replayDirectory = rtrim($replayDirectory, "/\\") . "/";
  $pointers = ReplayStatePointers($gameDirectory);
  if (count($pointers) === 0) return false;

  $states = [];
  foreach ($pointers as $pointer) {
    $source = ReplayStateFilename($gameDirectory, $pointer);
    $destination = ReplayStateFilename($replayDirectory, $pointer);
    $compressed = @file_get_contents($source);
    $gamestate = is_string($compressed) ? @gzdecode($compressed) : false;
    if (!is_string($gamestate) || @copy($source, $destination) === false) return false;
    $states[(string)$pointer] = hash("sha256", $gamestate);
  }

  $turnStates = [];
  foreach (glob($gameDirectory . "turn_*-*_Gamestate.txt") ?: [] as $source) {
    $basename = basename($source);
    if (!preg_match('/^turn_[12]-\d+_Gamestate\.txt$/', $basename)) continue;
    $gamestate = @file_get_contents($source);
    if (!is_string($gamestate) || @copy($source, $replayDirectory . $basename) === false) return false;
    $turnStates[$basename] = hash("sha256", $gamestate);
  }

  $commandFile = @file_get_contents($replayDirectory . "commandfile.txt");
  $initialState = @file_get_contents($replayDirectory . "origGamestate.txt");
  if (!is_string($commandFile) || !is_string($initialState)) return false;

  $format = [
    "version" => REPLAY_FORMAT_VERSION,
    "storage" => "state-snapshots",
    "steps" => $pointers,
    "stateHashes" => $states,
    "turnStateHashes" => $turnStates,
    "commandHash" => hash("sha256", $commandFile),
    "initialStateHash" => hash("sha256", $initialState)
  ];

  return file_put_contents(
    $replayDirectory . REPLAY_FORMAT_FILENAME,
    json_encode($format, JSON_UNESCAPED_SLASHES),
    LOCK_EX
  ) !== false;
}

function ReadReplayFormat(string $directory): ?array
{
  $filename = rtrim($directory, "/\\") . "/" . REPLAY_FORMAT_FILENAME;
  if (!file_exists($filename)) return null;
  $format = json_decode((string)file_get_contents($filename), true);
  if (!is_array($format) || ($format["version"] ?? null) !== REPLAY_FORMAT_VERSION) return null;
  if (($format["storage"] ?? "") !== "state-snapshots" || !is_array($format["steps"] ?? null)) return null;
  return $format;
}

function ValidateReplayStateFiles(string $directory): bool
{
  $format = ReadReplayFormat($directory);
  if ($format === null || count($format["steps"]) === 0) return false;

  $directory = rtrim($directory, "/\\") . "/";
  $commandFile = @file_get_contents($directory . "commandfile.txt");
  $initialState = @file_get_contents($directory . "origGamestate.txt");
  if (
    !is_string($commandFile) ||
    !is_string($initialState) ||
    !is_string($format["commandHash"] ?? null) ||
    !is_string($format["initialStateHash"] ?? null) ||
    !hash_equals($format["commandHash"], hash("sha256", $commandFile)) ||
    !hash_equals($format["initialStateHash"], hash("sha256", $initialState))
  ) return false;

  foreach ($format["steps"] as $rawPointer) {
    if (!is_int($rawPointer) && !ctype_digit((string)$rawPointer)) return false;
    $pointer = (int)$rawPointer;
    $compressed = @file_get_contents(ReplayStateFilename($directory, $pointer));
    $gamestate = is_string($compressed) ? @gzdecode($compressed) : false;
    $expectedHash = $format["stateHashes"][(string)$pointer] ?? null;
    if (!is_string($gamestate) || !is_string($expectedHash) || !hash_equals($expectedHash, hash("sha256", $gamestate))) {
      return false;
    }
  }
  if (!is_array($format["turnStateHashes"] ?? null)) return false;
  foreach ($format["turnStateHashes"] as $basename => $expectedHash) {
    if (
      !is_string($basename) ||
      !preg_match('/^turn_[12]-\d+_Gamestate\.txt$/', $basename) ||
      !is_string($expectedHash)
    ) return false;
    $gamestate = @file_get_contents($directory . $basename);
    if (!is_string($gamestate) || !hash_equals($expectedHash, hash("sha256", $gamestate))) return false;
  }
  return true;
}

function CopyReplayStateFiles(string $sourceDirectory, string $destinationDirectory): bool
{
  if (!ValidateReplayStateFiles($sourceDirectory)) return false;
  $format = ReadReplayFormat($sourceDirectory);
  if ($format === null) return false;

  $destinationDirectory = rtrim($destinationDirectory, "/\\") . "/";
  foreach ($format["steps"] as $rawPointer) {
    $pointer = (int)$rawPointer;
    if (!@copy(
      ReplayStateFilename($sourceDirectory, $pointer),
      ReplayStateFilename($destinationDirectory, $pointer)
    )) return false;
  }
  foreach ($format["turnStateHashes"] as $basename => $_expectedHash) {
    if (!@copy(
      rtrim($sourceDirectory, "/\\") . "/" . $basename,
      $destinationDirectory . $basename
    )) return false;
  }
  return @copy(
    rtrim($sourceDirectory, "/\\") . "/" . REPLAY_FORMAT_FILENAME,
    $destinationDirectory . REPLAY_FORMAT_FILENAME
  );
}

function NextReplayStatePointer(string $directory, int $currentPointer): ?int
{
  $format = ReadReplayFormat($directory);
  if ($format === null) return null;
  foreach ($format["steps"] as $rawPointer) {
    $pointer = (int)$rawPointer;
    if ($pointer > $currentPointer) return $pointer;
  }
  return null;
}

function ReadReplayStateSnapshot(string $directory, int $pointer): ?string
{
  $format = ReadReplayFormat($directory);
  if ($format === null) return null;
  $expectedHash = $format["stateHashes"][(string)$pointer] ?? null;
  if (!is_string($expectedHash)) return null;

  $compressed = @file_get_contents(ReplayStateFilename($directory, $pointer));
  $gamestate = is_string($compressed) ? @gzdecode($compressed) : false;
  if (!is_string($gamestate) || !hash_equals($expectedHash, hash("sha256", $gamestate))) return null;
  return $gamestate;
}

function ReadReplayTurnSnapshot(string $directory, int $player, int $turn): ?string
{
  if (!in_array($player, [1, 2], true) || $turn < 0) return null;
  $format = ReadReplayFormat($directory);
  if ($format === null) return null;
  $basename = "turn_$player-$turn" . "_Gamestate.txt";
  $expectedHash = $format["turnStateHashes"][$basename] ?? null;
  if (!is_string($expectedHash)) return null;
  $gamestate = @file_get_contents(rtrim($directory, "/\\") . "/" . $basename);
  if (!is_string($gamestate) || !hash_equals($expectedHash, hash("sha256", $gamestate))) return null;
  return $gamestate;
}

function ReadReplayInitialStateSnapshot(string $directory): ?string
{
  $format = ReadReplayFormat($directory);
  $expectedHash = $format["initialStateHash"] ?? null;
  if (!is_string($expectedHash)) return null;
  $gamestate = @file_get_contents(rtrim($directory, "/\\") . "/replayStartGamestate.txt");
  if (!is_string($gamestate) || !hash_equals($expectedHash, hash("sha256", $gamestate))) return null;
  return $gamestate;
}

function IsReplayControlMode($mode): bool
{
  return in_array(intval($mode), [99, 10018, 10023], true);
}

function NextReplayCommand(array $commands, int $currentPointer): array
{
  $pointer = $currentPointer + 1;
  $params = explode(" ", $commands[$pointer] ?? "");
  while (($params[1] ?? "") === "StartTurn" && $pointer < count($commands) - 1) {
    ++$pointer;
    $params = explode(" ", $commands[$pointer] ?? "");
  }

  return [$pointer, $params];
}

function ReplayUndoHasRecordedResponse(array $commands, int $pointer): bool
{
  $responseModes = [100016, 100017, 100018, 100019, 100022];
  $commandsCount = count($commands);

  for ($i = $pointer + 1; $i < $commandsCount; ++$i) {
    $params = explode(" ", $commands[$i]);
    $mode = $params[1] ?? "";
    if ($mode === "StartTurn") return false;
    $numericMode = intval($mode);
    if (in_array($numericMode, $responseModes, true)) return true;
    if ($numericMode === 10000 || $numericMode === 10003) return false;
  }

  return false;
}

function ShouldProcessReplayUndo(
  bool $isReplay,
  bool $isReplayAdvance,
  bool $hasRecordedResponse = false
): bool
{
  return !$isReplay || ($isReplayAdvance && !$hasRecordedResponse);
}
