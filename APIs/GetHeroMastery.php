<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../includes/functions.inc.php";
include_once "../includes/dbh.inc.php";
include_once "../Libraries/HeroMastery.php";
include_once "../CardDictionary.php";
include_once "../Libraries/LegalHeroesHelper.php";
SetHeaders();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

if (!IsUserLoggedIn() && isset($_COOKIE["rememberMeToken"])) loginFromCookie();
if (!IsUserLoggedIn()) {
  http_response_code(401);
  echo json_encode(["error" => "Hero Mastery requires a Talishar account."]);
  exit;
}

$userId = intval(LoggedInUser());
$response = [
  "milestones" => HeroMasteryMilestones(),
  "heroes" => [],
  "heroGroups" => GetMasteryHeroGroups(),
];
$conn = GetDBConnection();
if ($conn === false) {
  http_response_code(503);
  echo json_encode(["error" => "Hero Mastery is temporarily unavailable."]);
  exit;
}

$stmt = $conn->prepare("SELECT heroId, qualifyingGames FROM hero_mastery WHERE userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $progress = HeroMasteryProgress(intval($row["qualifyingGames"]));
  $progress["heroId"] = $row["heroId"];
  $response["heroes"][] = $progress;
}
$stmt->close();

// In a live game, expose only the two cosmetic levels to its participants.
// This lets both lobby/versus portraits render the earned frame without
// exposing account statistics or accepting hero/account IDs from the client.
$gameName = trim((string)($_GET["gameName"] ?? ""));
if ($gameName !== "" && ctype_digit($gameName)) {
  $gameFile = "../Games/" . $gameName . "/GameFile.txt";
  if (is_file($gameFile)) {
    $lines = preg_split('/\r\n|\r|\n/', (string)file_get_contents($gameFile));
    $p1GameUser = intval($lines[11] ?? 0);
    $p2GameUser = intval($lines[12] ?? 0);
    if ($userId === $p1GameUser || $userId === $p2GameUser) {
      $response["gamePlayers"] = [];
      foreach ([1 => $p1GameUser, 2 => $p2GameUser] as $slot => $gameUserId) {
        $deckFile = "../Games/" . $gameName . "/p" . $slot . "Deck.txt";
        $firstLine = is_file($deckFile) ? strtok((string)file_get_contents($deckFile), "\r\n") : "";
        $heroId = explode(" ", trim((string)$firstLine))[0] ?? "";
        $level = 0;
        if ($gameUserId > 0 && $heroId !== "") {
          $gameProgress = $conn->prepare("SELECT qualifyingGames FROM hero_mastery WHERE userId = ? AND heroId = ? LIMIT 1");
          $gameProgress->bind_param("is", $gameUserId, $heroId);
          $gameProgress->execute();
          $gameRow = $gameProgress->get_result()->fetch_assoc();
          $level = HeroMasteryLevel(intval($gameRow["qualifyingGames"] ?? 0));
          $gameProgress->close();
        }
        $response["gamePlayers"][(string)$slot] = ["heroId" => $heroId, "level" => $level];
      }
    }
  }
}

$gameKey = trim((string)($_GET["gameKey"] ?? ""));
if ($gameKey !== "") {
  $award = $conn->prepare("SELECT heroId, gamesBefore, gamesAfter FROM hero_mastery_awards WHERE gameKey = ? AND userId = ? LIMIT 1");
  $award->bind_param("si", $gameKey, $userId);
  $award->execute();
  $row = $award->get_result()->fetch_assoc();
  if ($row) {
    $before = HeroMasteryProgress(intval($row["gamesBefore"]));
    $after = HeroMasteryProgress(intval($row["gamesAfter"]));
    $response["gameAward"] = [
      "heroId" => $row["heroId"], "gamesBefore" => intval($row["gamesBefore"]),
      "gamesAfter" => intval($row["gamesAfter"]), "levelBefore" => $before["level"],
      "levelAfter" => $after["level"], "unlocked" => $after["level"] > $before["level"],
      "nextThreshold" => $after["nextThreshold"], "gamesToNext" => $after["gamesToNext"],
    ];
  } else {
    $response["gameAward"] = null;
  }
  $award->close();
}

mysqli_close($conn);
header('Content-Type: application/json');
echo json_encode($response);
