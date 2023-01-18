<?php

error_reporting(E_ALL);
include "Libraries/HTTPLibraries.php";

$response = new stdClass();

// Get the details
$gameName = $_POST["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_POST["playerID"];
$authKey = $_POST["authKey"];
$options = $_POST["options"];

$response->playerID = $playerID;
$response->authKey = $authKey;
$response->options = $options;

if ($playerID >= 3 || $playerID <= 0) {
  $response->error = 'Invalid player ID';
  echo json_encode($response);
  exit;
}

include "MenuFiles/ParseGamefile.php";
$userID = "";
$userID = ($playerID == 1) ? $p1id : $p2id;

$response->userID = $userID;

// receive an array of options
$respOpt = array();
foreach($options as $option) {
  $woo = new stdClass();
  $woo->setting = $option->setting;
  $woo->value = $option->value;
  array_push($respOpt, $woo);
  // ChangeSetting($playerID, $option->setting, $option->value, $userID);
}

$response->optionsResp = $respOpt;

echo json_encode($response);
exit;