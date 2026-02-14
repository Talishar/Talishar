<?php

include './APIKeys/APIKeys.php';
include './Libraries/HTTPLibraries.php';
include_once './Libraries/SHMOPLibraries.php';
include_once './HostFiles/Redirector.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit();
}

if ($_SERVER['SERVER_NAME'] !== 'localhost' && $_SERVER['SERVER_NAME'] !== '127.0.0.1') {
  http_response_code(403);
  exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$source = $data['source'];
$target = $data['target'];

if (!$source || !$target) {
  http_response_code(400);
  echo json_encode(array('error' => 'Missing source or target parameter'));
  exit();
}

// Validate that the bug report exists on the remote server
$test_url = "https://legacy.talishar.net/game/BugReports/{$source}/gamestate.txt";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $test_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_NOBODY, 1);
curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code != 200) {
  http_response_code(404);
  echo json_encode(array('error' => "Bug report {$source} does not exist"));
  exit();
}

SaveFile($source, $target, "gamestate.txt");
SaveFile($source, $target, "gamestateBackup.txt");
SaveFile($source, $target, "beginTurnGamestate.txt");
SaveFile($source, $target, "lastTurnGamestate.txt");

// Load undo gamestate backups
for ($i = 0; $i <= 4; $i++) {
  SaveFile($source, $target, "gamestateBackup_{$i}.txt");
}

// Clear the gamestate cache to force reload from file
// Read the new gamestate file and write it to cache
$newGamestate = file_get_contents("./Games/{$target}/gamestate.txt");
if ($newGamestate !== false) {
  WriteGamestateCache($target, $newGamestate);
}

// Update gamestate to trigger SSE refresh
GamestateUpdated($target);

echo json_encode(array('success' => true));

function SaveFile($source, $target, $file) {
  $source_path = "https://legacy.talishar.net/game/BugReports/{$source}/{$file}";
  $target_path = "./Games/{$target}/{$file}";
  $target_realpath = "./$target_path";
  $gs_path = "./Games/{$target}/gamestate.txt";

  $gs_file = fopen("{$gs_path}", 'r+');
  if (!$gs_file) {
    http_response_code(500);
    echo json_encode(array('error' => 'Failed to open target file on local'));
    exit();
  }

  //Try using cURL instead of ssh
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $source_path); // Set the URL
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return the response as a string
  $source_file_contents = curl_exec($ch);
  if (curl_errno($ch)) {
      echo 'cURL error: ' . curl_error($ch);
  }
  curl_close($ch);

  $gs_file_contents = stream_get_contents($gs_file);

  // For gamestate.txt, parse and properly merge the files
  if ($file === "gamestate.txt") {
    $source_lines = explode("\r\n", trim($source_file_contents));
    $target_lines = explode("\r\n", trim($gs_file_contents));
    
    // Extract auth keys from target (local game)
    $p1Key = isset($target_lines[58]) ? trim($target_lines[58]) : '';
    $p2Key = isset($target_lines[59]) ? trim($target_lines[59]) : '';
    
    // Use source file but replace the auth keys with local keys to preserve game identity
    if (isset($source_lines[58])) {
      $source_lines[58] = $p1Key;
    }
    if (isset($source_lines[59])) {
      $source_lines[59] = $p2Key;
    }
    
    $target_file_contents = implode("\r\n", $source_lines) . "\r\n";
  } else {
    // For non-gamestate files, use regex merge as before
    $source_file_contents = preg_replace("/[a-zA-Z0-9]{64}[\s\S]*$/", '', $source_file_contents);
    $target_file_contents = preg_replace("/^[\s\S]*?([a-zA-Z0-9]{64})/", $source_file_contents . '$1', $gs_file_contents, 1);
  }
  
  fclose($gs_file);
  file_put_contents("{$target_realpath}", $target_file_contents);
}

?>
