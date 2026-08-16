<?php

include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../APIKeys/APIKeys.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../Libraries/HTTPLibraries.php";
include_once "../includes/MetafyHelper.php";

SetHeaders();

if (!IsUserLoggedIn()) {
  echo json_encode(new stdClass());
  exit;
}

$response = new stdClass();

$response->userName = LoggedInUserName();

// Get Metafy info from database
$conn = GetDBConnection(DBL_METAFY_API);
$sql = "SELECT metafyAccessToken, metafyCommunities FROM users WHERE usersUid=?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
  $userName = LoggedInUserName();
  mysqli_stmt_bind_param($stmt, 's', $userName);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  mysqli_stmt_close($stmt);
  
  $response->isMetafyLinked = !empty($row['metafyAccessToken']);
  $response->metafyInfo = MetafyLink();
  $response->metafyCommunities = isset($row['metafyCommunities']) ? json_decode($row['metafyCommunities'], true) : [];
} else {
  $response->isMetafyLinked = false;
  $response->metafyInfo = MetafyLink();
  $response->metafyCommunities = [];
}

mysqli_close($conn);

echo json_encode($response);
exit;

// MetafyLink() lives in includes/MetafyHelper.php.

