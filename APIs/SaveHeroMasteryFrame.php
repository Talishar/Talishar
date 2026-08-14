<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../includes/functions.inc.php";
include_once "../includes/dbh.inc.php";
include_once "../includes/DBLogConstants.php";
include_once "../Libraries/HeroMastery.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if (!IsUserLoggedIn() && isset($_COOKIE["rememberMeToken"])) loginFromCookie();

header('Content-Type: application/json');

function MasteryFrameError($message, $code = 400)
{
  http_response_code($code);
  echo json_encode(["success" => false, "message" => $message]);
  exit;
}

if (!IsUserLoggedIn()) {
  MasteryFrameError("You must be logged in to choose a mastery frame.", 401);
}

$userId = intval(LoggedInUser());
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$heroId = trim(strval($body["heroId"] ?? ""));
$rawLevel = $body["level"] ?? null;

if ($heroId === "" || strlen($heroId) > 32) {
  MasteryFrameError("A hero is required.");
}

// A null level is the default: keep following the newest frame earned.
$displayLevel = null;
if ($rawLevel !== null) {
  if (!is_numeric($rawLevel) || intval($rawLevel) != $rawLevel) {
    MasteryFrameError("Invalid mastery frame.");
  }
  $displayLevel = intval($rawLevel);
  if ($displayLevel < 0 || $displayLevel > count(HeroMasteryMilestones())) {
    MasteryFrameError("Invalid mastery frame.");
  }
}

$conn = GetDBConnection(DBL_SAVE_HERO_MASTERY_FRAME);
if ($conn === false) {
  MasteryFrameError("Hero Mastery is temporarily unavailable.", 503);
}

$read = $conn->prepare("SELECT qualifyingGames FROM hero_mastery WHERE userId = ? AND heroId = ?");
$read->bind_param("is", $userId, $heroId);
$read->execute();
$row = $read->get_result()->fetch_assoc();
$read->close();

if (!$row) {
  mysqli_close($conn);
  MasteryFrameError("You have not played any qualifying games with that hero yet.", 404);
}

$earnedLevel = HeroMasteryLevel(intval($row["qualifyingGames"]));
if ($displayLevel !== null && $displayLevel > $earnedLevel) {
  mysqli_close($conn);
  MasteryFrameError("You have not unlocked that mastery frame.", 403);
}

try {
  $update = $conn->prepare("UPDATE hero_mastery SET displayLevel = ? WHERE userId = ? AND heroId = ?");
  $update->bind_param("iis", $displayLevel, $userId, $heroId);
  $update->execute();
  $update->close();
} catch (Throwable $e) {
  error_log("Hero Mastery frame save failed for user " . $userId . ": " . $e->getMessage());
  mysqli_close($conn);
  MasteryFrameError("Failed to save your mastery frame. Please try again.", 500);
}

mysqli_close($conn);
session_write_close();

echo json_encode([
  "success" => true,
  "message" => "Mastery frame saved.",
  "heroId" => $heroId,
  "displayLevel" => $displayLevel,
  "frameLevel" => HeroMasteryFrameLevel($earnedLevel, $displayLevel),
]);
