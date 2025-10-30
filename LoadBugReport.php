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

SaveFile($source, $target, "gamestate.txt");
SaveFile($source, $target, "gamestateBackup.txt");
SaveFile($source, $target, "beginTurnGamestate.txt");
SaveFile($source, $target, "lastTurnGamestate.txt");

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
  $source_path = "https://legacy.talishar.net/game/BugReports/{$source}-0/{$file}";
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

  $source_file_contents = preg_replace("/[a-zA-Z0-9]{64}[\s\S]*$/", '', $source_file_contents);

  $target_file_contents = preg_replace("/^[\s\S]*?([a-zA-Z0-9]{64})/", $source_file_contents . '$1', $gs_file_contents, 1);
  fclose($gs_file);

  file_put_contents("{$target_realpath}", $target_file_contents);
}

?>
