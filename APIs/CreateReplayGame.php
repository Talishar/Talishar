<?php

session_start();

ob_start();
include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/PlayerSettings.php";
include_once '../Assets/patreon-php-master/src/PatreonDictionary.php';
ob_end_clean();

// $userId = "";
// if (isset($_SESSION["userid"])) $userId = $_SESSION["userid"];
// if ($userId == "") {
//   echo ("You must be logged in to use this feature.");
//   exit;
// }
$response = new stdClass();

$_POST = json_decode(file_get_contents('php://input'), true);

$replayNumber = $_POST["replayNumber"] ?? null;

// Validation: replayNumber must be provided
if ($replayNumber === null || $replayNumber === '') {
  $response->error = "Missing required parameter: replayNumber\n\nPlease provide a valid replay game number.";
  $response->replayNumber = $replayNumber;
  http_response_code(400);
  echo json_encode($response);
  exit;
}

// Validation: replayNumber must be numeric
if (!is_numeric($replayNumber)) {
  $response->error = "Invalid replayNumber format. Expected a numeric value, received: " . htmlspecialchars($replayNumber);
  http_response_code(400);
  echo json_encode($response);
  exit;
}

$replayPath = "../Replays/$replayNumber/";

// Validation: Replay directory exists
if (!file_exists($replayPath)) {
  $replayPath_display = htmlspecialchars($replayPath);
  $availableReplays = array_filter(scandir("../Replays/") ?? [], function($item) {
    return $item !== '.' && $item !== '..' && is_dir("../Replays/$item");
  });
  
  $availableList = !empty($availableReplays) ? "\n\nAvailable replays: " . implode(", ", array_values($availableReplays)) : "\n\nNo replay directories found yet.";
  
  $response->error = "Replay directory not found at: $replayPath_display" . $availableList;
  $response->debug = array(
    "requestedReplayNumber" => $replayNumber,
    "availableReplays" => array_values($availableReplays),
    "replaysDirectory" => realpath("../Replays/") ?: "../Replays/"
  );
  http_response_code(404);
  echo json_encode($response);
  exit;
}

// Validation: Required replay files exist
$requiredFiles = [
  'origGamestate.txt' => 'Original game state file',
  'commandfile.txt' => 'Replay command file'
];

$missingFiles = [];
foreach ($requiredFiles as $file => $description) {
  if (!file_exists($replayPath . $file)) {
    $missingFiles[$file] = $description;
  }
}

if (!empty($missingFiles)) {
  $missingFilesList = "\n\nMissing files:\n";
  foreach ($missingFiles as $file => $description) {
    $missingFilesList .= "  • $file ($description)\n";
  }
  
  $filesInDir = array_values(array_filter(scandir($replayPath) ?? [], function($item) {
    return $item !== '.' && $item !== '..';
  }));
  $filesList = !empty($filesInDir) ? "\n\nFiles found in directory:\n  " . implode(", ", $filesInDir) : "\n\nDirectory is empty!";
  
  $response->error = "Replay directory exists but missing required files." . $missingFilesList . $filesList;
  $response->missingFiles = $missingFiles;
  $response->debug = array(
    "replayPath" => realpath($replayPath) ?: $replayPath,
    "filesInDirectory" => $filesInDir
  );
  http_response_code(400);
  echo json_encode($response);
  exit;
}

// if (!file_exists("./Replays/" . $userId . "/" . $replayNumber . "/")) {
//   echo ("That replay file does not exist.");
//   exit;
// }


$gameName = GetGameCounter("../");

if (!file_exists("../Games/$gameName") && !mkdir("../Games/$gameName", 0700, true)) {
  $response->error = "Failed to create game directory at: ../Games/$gameName";
  $response->debug = array(
    "gameID" => $gameName,
    "attemptedPath" => realpath("../Games/") . "/$gameName",
    "parentDirExists" => file_exists("../Games/")
  );
  http_response_code(500);
  echo json_encode($response);
  exit;
}

$startingHealth = TryGet("startingHealth", "");

$p1Data = [1];
$p2Data = [2];

$gameStatus = 5; //Game started

$firstPlayerChooser = "";
$firstPlayer = 1;

// Extract original auth keys from the replay gamestate file
// Auth keys are stored in the gamestate file - p1Key and p2Key come after chainLinkSummary
// Simpler approach: parse the file and look for the auth key pattern (SHA256 hashes are 64 hex chars)
$origGamestateContent = file_get_contents($replayPath . "origGamestate.txt");
$gamestateLine = explode("\r\n", $origGamestateContent);

// SHA256 hashes are 64 character hex strings - look for them in the file
$p1Key = "";
$p2Key = "";

// Search from near the end since auth keys come late in the file
for ($i = count($gamestateLine) - 1; $i >= 0; $i--) {
  $line = trim($gamestateLine[$i]);
  // Check if this line is a valid SHA256 hash (64 hex characters)
  if (strlen($line) === 64 && ctype_xdigit($line)) {
    if ($p2Key === "") {
      $p2Key = $line;
    } elseif ($p1Key === "") {
      $p1Key = $line;
      break; // Found both keys
    }
  }
}

// Fallback to generating new keys if extraction fails
if (empty($p1Key) || empty($p2Key)) {
  error_log("Replay: Auth key extraction failed, generating new keys");
  $p1Key = hash("sha256", rand() . rand());
  $p2Key = hash("sha256", rand() . rand() . rand());
}

$p1uid = "-";
$p2uid = "-";
$p1id = "-";
$p2id = "-";
$hostIP = $_SERVER['REMOTE_ADDR'];
$p1StartingHealth = $startingHealth;

$filename = "../Games/$gameName/GameFile.txt";
$gameFileHandler = fopen($filename, "w");
include "../MenuFiles/WriteGamefile.php";
WriteGameFile();

$filename = "../Games/$gameName/gamelog.txt";
$handler = fopen($filename, "w");
fclose($handler);


$currentTime = round(microtime(true) * 1000);
$isReplay = "1";
$visibility = "1";
WriteCache($gameName, 1 . "!" . $currentTime . "!" . $currentTime . "!0!-1!" . $currentTime . "!!!" . $visibility . "!" . $isReplay . "!0!0!0!" . $gameStatus . "!0!0"); //Initialize SHMOP cache for this game

// Copy replay files with error handling
$origGamestateSource = $replayPath . "origGamestate.txt";
$origGamestateDest = "../Games/$gameName/gamestate.txt";
$commandFileSource = $replayPath . "commandfile.txt";
$commandFileDest = "../Games/$gameName/replayCommands.txt";

$copyErrors = [];

if (!@copy($origGamestateSource, $origGamestateDest)) {
  $copyErrors[] = "Failed to copy original gamestate from $origGamestateSource to $origGamestateDest";
}

if (!@copy($commandFileSource, $commandFileDest)) {
  $copyErrors[] = "Failed to copy command file from $commandFileSource to $commandFileDest";
}

if (!empty($copyErrors)) {
  $response->error = "Failed to copy replay files to game directory.";
  $response->copyErrors = $copyErrors;
  $response->debug = array(
    "gameID" => $gameName,
    "sourcePath" => realpath($replayPath) ?: $replayPath,
    "destPath" => realpath("../Games/$gameName") ?: "../Games/$gameName",
    "sourceFilesExist" => array(
      "origGamestate.txt" => file_exists($origGamestateSource),
      "commandfile.txt" => file_exists($commandFileSource)
    )
  );
  http_response_code(500);
  echo json_encode($response);
  exit;
}

//Write initial gamestate to memory
$gamestate = file_get_contents("../Games/" . $gameName . "/gamestate.txt");
if ($gamestate === false) {
  $response->error = "Failed to read gamestate file after copying.";
  $response->debug = array(
    "gameID" => $gameName,
    "gamestateFilePath" => realpath("../Games/$gameName/gamestate.txt") ?: "../Games/$gameName/gamestate.txt"
  );
  http_response_code(500);
  echo json_encode($response);
  exit;
}

WriteGamestateCache($gameName, $gamestate);

$response->playerID = 1;
$response->gameName = $gameName;
$response->authKey = $p1Key;
$response->message = "Replay game created successfully!";
$response->success = true;

echo (json_encode($response));

// // header("Location: NextTurn4.php?gameName=$gameName&playerID=1&authKey=$p1Key");
// header("Location: http://127.0.0.1/:5173/game/play?gameName=$gameName&playerID=1&authKey=$p1Key");