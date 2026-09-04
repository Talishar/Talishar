<?php

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
