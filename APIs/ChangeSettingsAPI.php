<?php

//NOT TESTED -- DO NOT USE

include_once '../Libraries/HTTPLibraries.php';
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../includes/dbh.inc.php";
include_once "../includes/functions.inc.php";
include_once "../Libraries/PlayerSettings.php";

SetHeaders();

$_POST = json_decode(file_get_contents('php://input'), true);
$submissionString = $_POST["submission"];
$submission = json_decode($submissionString);
$playerID = $submission->playerID;
$userID = IsUserLoggedIn() ? LoggedInUser() : "";

$response = new stdClass();

for($i=0; $i<count($submission->settings); ++$i)
{
  ChangeSetting($playerID, $submission->settings[$i]->setting, $submission->settings[$i]->value, $userID);
}
$response->message = "Settings changed successfully.";

echo (json_encode($response));

exit;
