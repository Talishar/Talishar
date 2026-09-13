<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../includes/functions.inc.php";
include_once "../includes/dbh.inc.php";
include_once "../Libraries/HeroMastery.php";
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
$scope = trim((string)($_GET["scope"] ?? "account"));
$includeAccount = !in_array($scope, ["game", "award"], true);
$response = [
  "milestones" => HeroMasteryMilestones(),
  "heroes" => [],
  "heroGroups" => [
    "classicConstructed" => [],
    "silverAge" => [],
    "livingLegend" => [],
  ],
];

if ($includeAccount) {
  include_once "../CardDictionary.php";
  include_once "../Libraries/LegalHeroesHelper.php";
  $response["heroGroups"] = GetMasteryHeroGroups();
}

// In a live game, expose only the two cosmetic levels to its participants.
// This lets both lobby/versus portraits render the earned frame without
// exposing account statistics or accepting hero/account IDs from the client.
$gamePlayers = null;
$gameName = trim((string)($_GET["gameName"] ?? ""));
if ($gameName !== "" && ctype_digit($gameName)) {
  $gameFile = "../Games/" . $gameName . "/GameFile.txt";
  if (is_file($gameFile)) {
    $lines = preg_split('/\r\n|\r|\n/', (string)file_get_contents($gameFile));
    $p1GameUser = intval($lines[11] ?? 0);
    $p2GameUser = intval($lines[12] ?? 0);
    if ($userId === $p1GameUser || $userId === $p2GameUser) {
      include_once "../CardDictionary.php";
      $response["gamePlayers"] = [];
      $gamePlayers = [];
      foreach ([1 => $p1GameUser, 2 => $p2GameUser] as $slot => $gameUserId) {
        $deckFile = "../Games/" . $gameName . "/p" . $slot . "Deck.txt";
        $firstLine = is_file($deckFile) ? strtok((string)file_get_contents($deckFile), "\r\n") : "";
        // Deck files name the hero by card ID (ira_crimson_haze) while
        // hero_mastery keys by set ID (CRU046), so the lookup has to convert.
        // SetID returns "" for the AI and unrevealed heroes, which simply
        // matches no row and leaves the frame unornamented.
        $deckHero = explode(" ", trim((string)$firstLine))[0] ?? "";
        $heroId = $deckHero !== "" ? SetID($deckHero) : "";
        $gamePlayers[$slot] = ["userId" => $gameUserId, "heroId" => $heroId];
        $response["gamePlayers"][(string)$slot] = ["heroId" => $heroId, "level" => 0];
      }
    }
  }
}

$gameKey = trim((string)($_GET["gameKey"] ?? ""));
$needsDatabase = $includeAccount || $gamePlayers !== null || $gameKey !== "";
$conn = $needsDatabase ? GetDBConnection() : null;
if ($needsDatabase && $conn === false) {
  http_response_code(503);
  echo json_encode(["error" => "Hero Mastery is temporarily unavailable."]);
  exit;
}

if ($includeAccount) {
  $stmt = $conn->prepare("SELECT heroId, qualifyingGames, displayLevel FROM hero_mastery WHERE userId = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $progress = HeroMasteryProgress(intval($row["qualifyingGames"]), $row["displayLevel"]);
    $progress["heroId"] = $row["heroId"];
    $response["heroes"][] = $progress;
  }
  $stmt->close();
}

if ($gamePlayers !== null) {
  $gameProgress = $conn->prepare(
    "SELECT userId, heroId, qualifyingGames, displayLevel
     FROM hero_mastery
     WHERE (userId = ? AND heroId = ?) OR (userId = ? AND heroId = ?)"
  );
  $gameProgress->bind_param(
    "isis",
    $gamePlayers[1]["userId"],
    $gamePlayers[1]["heroId"],
    $gamePlayers[2]["userId"],
    $gamePlayers[2]["heroId"]
  );
  $gameProgress->execute();
  $gameResult = $gameProgress->get_result();
  while ($gameRow = $gameResult->fetch_assoc()) {
    foreach ($gamePlayers as $slot => $gamePlayer) {
      if (
        intval($gameRow["userId"]) === $gamePlayer["userId"] &&
        $gameRow["heroId"] === $gamePlayer["heroId"]
      ) {
        // This level is cosmetic only - it drives the lobby and versus frames -
        // so it is the frame the player chose, not necessarily their highest.
        $response["gamePlayers"][(string)$slot]["level"] = HeroMasteryFrameLevel(
          HeroMasteryLevel(intval($gameRow["qualifyingGames"])),
          $gameRow["displayLevel"]
        );
      }
    }
  }
  $gameProgress->close();
}

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

if ($conn instanceof mysqli) mysqli_close($conn);
header('Content-Type: application/json');
echo json_encode($response);
