<?php

include './APIKeys/APIKeys.php';

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

$ssh_conn = ssh2_connect($prodServerIP, 22);
if (!ssh2_auth_password($ssh_conn, $prodServerUsername, $prodServerPassword)) {
  http_response_code(500);
  echo json_encode(array('error' => 'Failed to authenticate with remote server'));
  exit();
}

$sftp = ssh2_sftp($ssh_conn);
$source_path = "/opt/lampp/htdocs/game/BugReports/{$source}-0/gamestate.txt";
$target_path = "./Games/{$target}/gamestate.txt";

$source_realpath = ssh2_sftp_realpath($sftp, $source_path);
$target_realpath = "./$target_path";

if (!$source_realpath || !$target_realpath) {
  http_response_code(500);
  echo json_encode(array('error' => 'Failed to get file paths on remote server'));
  exit();
}

$source_file = fopen("ssh2.sftp://{$sftp}{$source_realpath}", 'r');
if (!$source_file) {
  http_response_code(500);
  echo json_encode(array('error' => 'Failed to open source file on remote server'));
  exit();
}

$target_file = fopen("{$target_realpath}", 'r+');
if (!$target_file) {
  http_response_code(500);
  echo json_encode(array('error' => 'Failed to open target file on local'));
  fclose($source_file);
  exit();
}

$source_file_contents = stream_get_contents($source_file);
$target_file_contents = stream_get_contents($target_file);

$source_file_contents = preg_replace("/[a-zA-Z0-9]{64}[\s\S]*$/", '', $source_file_contents);

$target_file_contents = preg_replace("/^[\s\S]*?([a-zA-Z0-9]{64})/", $source_file_contents . '$1', $target_file_contents, 1);
fclose($target_file);

file_put_contents("{$target_realpath}", $target_file_contents);
fclose($source_file);


echo json_encode(array('success' => true));
