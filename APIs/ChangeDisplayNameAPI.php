<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

include_once "../AccountFiles/AccountSessionAPI.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once '../Libraries/FriendLibraries.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if (!IsUserLoggedIn()) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

header('Content-Type: application/json');

function DisplayNameError($message, $code = 400, $extra = [])
{
  http_response_code($code);
  echo json_encode(array_merge(["status" => "error", "message" => $message], $extra));
  exit;
}

if (!IsUserLoggedIn()) {
  DisplayNameError("Please log in to change your display name.", 401);
}
if (intval($_SESSION["isBanned"] ?? 0) == 1) {
  DisplayNameError("Your account is not eligible to change its display name.", 403);
}

if (!IsLoggedInUserPatron()) {
  DisplayNameError("Changing your display name is a supporter perk. Support Talishar to unlock it!", 403);
}

$userId = intval(LoggedInUser());
$userUid = LoggedInUserName();

$_POST = json_decode(file_get_contents('php://input'), true) ?? [];
$newName = trim(TryPOST("displayName", ""));

$isClearing = ($newName === "");

if (!$isClearing) {
  if (invalidUid($newName)) {
    DisplayNameError("Display names may only contain letters and numbers.");
  }
  if (strlen($newName) < 3 || strlen($newName) > 20) {
    DisplayNameError("Display names must be between 3 and 20 characters.");
  }
}

$conn = GetDBConnection(DBL_CHANGE_DISPLAY_NAME);
if (!$conn) {
  DisplayNameError("Service temporarily unavailable. Please try again later.", 503);
}

$sql = "SELECT displayName, lastNameChange FROM users WHERE usersId = ?";
$stmt = mysqli_stmt_init($conn);
$currentDisplayName = null;
$lastNameChange = null;
if (mysqli_stmt_prepare($stmt, $sql)) {
  mysqli_stmt_bind_param($stmt, "i", $userId);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $currentDisplayName, $lastNameChange);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);
}

$cooldownSeconds = 7 * 86400;
$nextChangeAllowed = null;
if ($lastNameChange !== null) {
  $nextChangeTime = strtotime($lastNameChange) + $cooldownSeconds;
  if ($nextChangeTime > time()) {
    $nextChangeAllowed = date("c", $nextChangeTime);
    DisplayNameError("You can only change your display name once every 7 days.", 429, ["nextChangeAllowed" => $nextChangeAllowed]);
  }
}

$currentShownName = ($currentDisplayName !== null && $currentDisplayName !== "") ? $currentDisplayName : $userUid;
$newShownName = $isClearing ? $userUid : $newName;
if ($newShownName === $currentShownName) {
  DisplayNameError("That is already your display name.");
}

if (!$isClearing && strcasecmp($newName, $userUid) != 0) {
  // Banned names stay reserved even after the original account is deleted
  if (IsBannedPlayer($newName)) {
    DisplayNameError("That name is already taken.");
  }
  $sql = "SELECT usersId FROM users WHERE (LOWER(usersUid) = LOWER(?) OR LOWER(displayName) = LOWER(?)) AND usersId <> ? LIMIT 1";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "ssi", $newName, $newName, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $taken = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    if ($taken) {
      DisplayNameError("That name is already taken.");
    }
  }
}

$dbNewName = $isClearing ? null : $newName;

mysqli_begin_transaction($conn);
try {
  $sql = "UPDATE users SET displayName = ?, lastNameChange = NOW() WHERE usersId = ?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) throw new Exception("prepare failed");
  mysqli_stmt_bind_param($stmt, "si", $dbNewName, $userId);
  if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    throw new Exception("taken");
  }
  mysqli_stmt_close($stmt);

  $sql = "INSERT INTO name_history (usersId, oldName, newName) VALUES (?, ?, ?)";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) throw new Exception("prepare failed");
  mysqli_stmt_bind_param($stmt, "iss", $userId, $currentShownName, $newShownName);
  if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    throw new Exception("history failed");
  }
  mysqli_stmt_close($stmt);

  mysqli_commit($conn);
} catch (Exception $e) {
  mysqli_rollback($conn);
  if ($e->getMessage() === "taken") {
    DisplayNameError("That name is already taken.");
  }
  DisplayNameError("Failed to update display name. Please try again later.", 500);
}

$_SESSION["displayName"] = $isClearing ? "" : $newName;

echo json_encode([
  "status" => "success",
  "displayName" => $newShownName,
  "nextChangeAllowed" => date("c", time() + $cooldownSeconds)
]);
