<?php

include "../AccountFiles/AccountSessionAPI.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../Libraries/PlayerSettings.php";
include_once "../Libraries/HTTPLibraries.php";
include_once "../Libraries/CosmeticsLibrary.php";
include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "../Assets/MetafyDictionary.php";

session_start();

SetHeaders();

$userName = IsUserLoggedIn() ? LoggedInUserName() : null;
$response = GetUserCosmeticsEntitlements($userName);

session_write_close();
echo json_encode($response);
