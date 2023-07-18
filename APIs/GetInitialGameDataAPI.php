<?php

ob_start();
include "../HostFiles/Redirector.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../CardDictionary.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";
include "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/PlayerSettings.php";
ob_end_clean();

SetHeaders();


$_POST = json_decode(file_get_contents('php://input'), true);
$gameName = TryPOST("gameName", 0);

$response = new stdClass();
session_write_close();

if (!file_exists("../Games/" . $gameName . "/GameFile.txt")) {
  echo (json_encode(new stdClass()));
  exit;
}

ob_start();
include "./APIParseGamefile.php";
ob_end_clean();

$response->p1Name = $p1uid;
$response->p2Name = $p2uid;
$contributors = array("sugitime", "OotTheMonk", "Launch", "LaustinSpayce", "Star_Seraph", "Tower", "Etasus", "scary987", "Celenar");
$response->p1IsPatron = $p1IsPatron == "" ? false : true;
$response->p1IsContributor = in_array($response->p1Name, $contributors);
$response->p2IsPatron = $p2IsPatron == "" ? false : true;
$response->p2IsContributor = in_array($response->p2Name, $contributors);
$response->roguelikeGameID = $roguelikeGameID;


echo json_encode($response);

exit;
