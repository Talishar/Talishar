<?php

include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../APIKeys/APIKeys.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../Libraries/HTTPLibraries.php";

SetHeaders();

if (!IsUserLoggedIn()) {
  echo (json_encode(new stdClass()));
  exit;
}

$response = new stdClass();

$response->userName = LoggedInUserName();

// Get Metafy info from database
$conn = GetDBConnection();
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

function MetafyLink()
{
  global $metafyClientID;
  $client_id = $metafyClientID;
  
  // Always use production Metafy OAuth endpoint
  $oauth_host = 'https://auth.metafy.gg';
  
  // Determine redirect URI based on environment
  $is_local = $_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === 'localhost:8000' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
  $redirect_uri = $is_local ? 'http://localhost:5173/user/profile/linkmetafy' : 'https://talishar.net/user/profile/linkmetafy';
  
  $response_type = 'code';
  $scope = 'read:communities read:user';
  
  $href = $oauth_host . '/oauth/authorize?' .
    'response_type=' . urlencode($response_type) .
    '&client_id=' . urlencode($client_id) .
    '&redirect_uri=' . urlencode($redirect_uri) .
    '&scope=' . urlencode($scope);
  
  return $href;
}

?>
